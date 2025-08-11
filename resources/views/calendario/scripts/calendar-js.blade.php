<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const magisterFilter = document.getElementById('magister-filter');
        const roomFilter = document.getElementById('room-filter');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek' },
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
                url: '{{ route("events.index") }}',
                extraParams: () => ({
                    magister: magisterFilter.value || '',
                    room_id: roomFilter.value || ''
                }),
                failure: (err) => console.error('Error cargando eventos:', err)
            },
            eventDidMount: setTooltip
        });

        calendar.render();

        // Filtros dinámicos
        [magisterFilter, roomFilter].forEach(select =>
            select.addEventListener('change', () => calendar.refetchEvents())
        );

        // Guardar (crear o editar)
        document.getElementById('event-form').addEventListener('submit', saveEvent);

        // Eliminar
        document.getElementById('delete-btn').addEventListener('click', deleteEvent);

        // Cancelar modal
        document.getElementById('cancel').addEventListener('click', () => window.closeModal());

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
            document.getElementById('modal-magister-view').textContent = info.event.extendedProps.magister || 'No especificado';
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
            document.getElementById('event_id').value = event.id.replace('event-', '');
            document.getElementById('modal-title-input').value = event.title;
            document.getElementById('modal-description-input').value = event.extendedProps.description || '';
            document.getElementById('modal-magister').value = event.extendedProps.magister || '';
            document.getElementById('room_id').value = event.extendedProps.room?.id || '';
            document.getElementById('start_time').value = event.start.toISOString();
            document.getElementById('end_time').value = event.end.toISOString();
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
                magister: document.getElementById('modal-magister').value,
                start_time: document.getElementById('start_time').value,
                end_time: document.getElementById('end_time').value,
                room_id: document.getElementById('room_id').value || null,
                type: 'manual'
            };

            const url = eventId ? `/events/${eventId}` : "{{ route('events.store') }}";
            const method = eventId ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            }).then(res => {
                if (res.ok) {
                    calendar.refetchEvents();
                    window.closeModal();
                } else {
                    alert("Error al guardar el evento.");
                }
            });
        }

        function deleteEvent() {
            const id = this.getAttribute('data-id');
            if (!confirm("¿Estás seguro de eliminar este evento?")) return;

            fetch(`/events/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(res => {
                if (res.ok) {
                    calendar.refetchEvents();
                    window.closeModal();
                } else {
                    alert("Error al eliminar evento.");
                }
            });
        }

        function setTooltip(info) {
            const magister = info.event.extendedProps.magister || 'Sin magíster';
            const sala = info.event.extendedProps.room?.name || 'Sin sala';
            const start = info.event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const end = info.event.end.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const tooltip = `${info.event.title}\n🏛️ ${magister}\n🏫 ${sala}\n🕒 ${start} - ${end}`;
            info.el.setAttribute('title', tooltip.trim());
        }

        // 🔹 Ahora es global
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
</script>