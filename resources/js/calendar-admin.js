document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const magisterFilter = document.getElementById('magister-filter');
    const roomFilter = document.getElementById('room-filter');
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const storeUrl = document.querySelector('meta[name="store-url"]').getAttribute('content');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek'
        },
        locale: 'es',
        firstDay: 1,
        slotMinTime: "08:30:00",
        slotMaxTime: "21:00:00",
        expandRows: true,
        editable: true,
        selectable: true,
        select: onSelect,
        eventClick: onEventClick,
        events: {
            url: calendarEl.dataset.url,
            extraParams: () => ({
                magister_id: magisterFilter.value || '',
                room_id: roomFilter.value || ''
            }),
            failure: (err) => console.error('Error cargando eventos:', err)
        },
        eventDidMount: setTooltip,
        datesSet: (info) => {
            const start = new Date(info.start); // Lunes (por defecto en calendar)
            const diaSemana = start.getDay(); // 0=Domingo, 6=Sábado

            // Sábado = día 6 → desplazamiento desde start
            const offset = (6 - diaSemana + 7) % 7;
            const sabado = new Date(start);
            sabado.setDate(start.getDate() + offset);

            actualizarTextoTrimestre(sabado);
        }
    });

    calendar.render();

    // 🎯 Botones de navegación por trimestres
    document.getElementById('btnAnterior').addEventListener('click', () => irATrimestre('anterior'));
    document.getElementById('btnSiguiente').addEventListener('click', () => irATrimestre('siguiente'));

    async function irATrimestre(direccion) {
        const fechaActual = calendar.getDate().toISOString().split('T')[0];

        try {
            const res = await fetch(`/api/trimestre-${direccion}?fecha=${fechaActual}`);
            const data = await res.json();

            if (data.fecha_inicio) {
                calendar.gotoDate(data.fecha_inicio);
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Trimestre no encontrado',
                    text: data.error || 'No hay trimestre en esa dirección.'
                });
            }
        } catch (error) {
            console.error("Error consultando trimestre:", error);
        }
    }

    document.getElementById('event-form').addEventListener('submit', saveEvent);
    document.getElementById('delete-btn').addEventListener('click', deleteEvent);

    [magisterFilter, roomFilter].forEach(select =>
        select.addEventListener('change', () => {
            calendar.refetchEvents();
        })
    );

    async function actualizarTextoTrimestre(fecha) {
        const fechaISO = fecha.toISOString().split('T')[0];

        try {
            const res = await fetch(`/api/periodo-por-fecha?fecha=${fechaISO}`);
            const data = await res.json();

            const romanos = { 1: 'I', 2: 'II', 3: 'III', 4: 'IV', 5: 'V', 6: 'VI' };

            const texto = data.periodo
                ? `Trimestre ${romanos[data.periodo.numero] || data.periodo.numero} del año ${data.periodo.anio}`
                : 'Fuera de períodos académicos';

            document.getElementById('current-period-text').textContent = texto;
        } catch (err) {
            console.error('Error cargando período:', err);
            document.getElementById('current-period-text').textContent = 'Error';
        }
    }


    function onSelect(info) {
        resetForm();
        document.getElementById('modal-header').textContent = 'Crear Evento';
        document.getElementById('start_time').value = info.startStr;
        document.getElementById('end_time').value = info.endStr;
        document.getElementById('modal').classList.remove('hidden');
    }

    function onEventClick(info) {
        document.getElementById('modal-title').textContent = info.event.title;
        document.getElementById('modal-description').textContent = info.event.extendedProps.description || '';
        document.getElementById('modal-magister-view').textContent = info.event.extendedProps.magister?.name || 'No especificado';
        document.getElementById('modal-modality').textContent = info.event.extendedProps.modality || 'No especificada';
        document.getElementById('modal-start').textContent = info.event.start.toLocaleString();
        document.getElementById('modal-end').textContent = info.event.end.toLocaleString();
        document.getElementById('modal-room').textContent = info.event.extendedProps.room?.name || 'Sin sala';

        const deleteBtn = document.getElementById('delete-btn');
        const editBtn = document.getElementById('edit-btn');

        if (info.event.extendedProps.type === 'manual') {
            deleteBtn.classList.remove('hidden');
            deleteBtn.setAttribute('data-id', info.event.id.replace('event-', ''));
            editBtn.classList.remove('hidden');
            editBtn.onclick = () => openEditModal(info.event);
        } else {
            deleteBtn.classList.add('hidden');
            editBtn.classList.add('hidden');
        }

        document.getElementById('eventModal').classList.remove('hidden');
    }

    function openEditModal(event) {
        resetForm();

        const formatForInput = (date) => {
            const local = new Date(date);
            const offset = local.getTimezoneOffset() * 60000;
            return new Date(local - offset).toISOString().slice(0, 16);
        };

        document.getElementById('event_id').value = event.id.replace('event-', '');
        document.getElementById('modal-title-input').value = event.title;
        document.getElementById('modal-description-input').value = event.extendedProps.description || '';
        document.getElementById('magister_id').value = event.extendedProps.magister?.id ?? '';
        document.getElementById('room_id').value = event.extendedProps.room?.id || '';
        document.getElementById('start_time').value = formatForInput(event.start);
        document.getElementById('end_time').value = formatForInput(event.end);
        document.getElementById('modal-header').textContent = 'Editar Evento';

        document.getElementById('eventModal').classList.add('hidden');
        document.getElementById('modal').classList.remove('hidden');
    }

    function saveEvent(e) {
        e.preventDefault();

        const eventId = document.getElementById('event_id').value;
        const data = {
            title: document.getElementById('modal-title-input').value,
            description: document.getElementById('modal-description-input').value,
            magister_id: document.getElementById('magister_id').value || null,
            start_time: document.getElementById('start_time').value,
            end_time: document.getElementById('end_time').value,
            room_id: document.getElementById('room_id').value || null,
            type: 'manual'
        };

        const url = eventId ? `/events/${eventId}` : storeUrl;
        const method = eventId ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify(data)
        }).then(res => {
            if (res.ok) {
                calendar.removeAllEvents();
                calendar.refetchEvents();
                window.closeModal();

                Swal.fire({
                    icon: 'success',
                    title: 'Evento guardado',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al guardar el evento',
                    text: 'Revisa los datos ingresados'
                });
            }
        });
    }

    function deleteEvent() {
        const id = this.getAttribute('data-id');
        if (!confirm("¿Estás seguro de eliminar este evento?")) return;

        fetch(`/events/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf }
        }).then(res => {
            if (res.ok) {
                calendar.refetchEvents();
                window.closeModal();
                Swal.fire({
                    icon: 'success',
                    title: 'Evento Eliminado',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al eliminar el evento',
                    text: 'Intenta nuevamente'
                });
            }
        });
    }

    function setTooltip(info) {
        const magister = info.event.extendedProps.magister?.name || 'Sin magíster';
        const sala = info.event.extendedProps.room?.name || 'Sin sala';
        const start = info.event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        const end = info.event.end.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        const tooltip = `${info.event.title}\n🏛️ ${magister}\n🏫 ${sala}\n🕒 ${start} - ${end}`;
        info.el.setAttribute('title', tooltip.trim());
    }

    window.closeModal = function () {
        document.getElementById('modal').classList.add('hidden');
        document.getElementById('eventModal').classList.add('hidden');
        resetForm();
    };

    function resetForm() {
        document.getElementById('event_id').value = '';
        document.getElementById('event-form').reset();
        document.getElementById('start_time').value = '';
        document.getElementById('end_time').value = '';
    }
});
