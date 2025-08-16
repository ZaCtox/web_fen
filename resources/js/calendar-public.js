document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const magisterFilter = document.getElementById('magister-filter');
    const roomFilter = document.getElementById('room-filter');

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
        events: {
            url: calendarEl.dataset.url,
            extraParams: () => ({
                magister_id: magisterFilter.value || '',
                room_id: roomFilter.value || ''
            }),
            failure: (err) => console.error('Error cargando eventos:', err)
        },
        eventDidMount: info => {
            const magister = info.event.extendedProps.magister || 'Sin magíster';
            const sala = info.event.extendedProps.room?.name || 'Sin sala';
            const start = info.event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const end = info.event.end.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            const tooltip = `${info.event.title}\n🏛️ ${magister}\n🏫 ${sala}\n🕒 ${start} - ${end}`;
            info.el.setAttribute('title', tooltip.trim());
        },
        datesSet: (info) => {
            // Tomar el sábado como referencia central
            const fechaReferencia = new Date(info.end.getTime());
            fechaReferencia.setDate(fechaReferencia.getDate() - 1); // Sábado

            actualizarTextoTrimestre(fechaReferencia);
        }
    });

    calendar.render();

    // 🎯 Botones de navegación entre trimestres
    document.getElementById('btnAnterior')?.addEventListener('click', () => irATrimestre('anterior'));
    document.getElementById('btnSiguiente')?.addEventListener('click', () => irATrimestre('siguiente'));

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

    // 🎯 Filtros dinámicos
    [magisterFilter, roomFilter].forEach(select =>
        select?.addEventListener('change', () => calendar.refetchEvents())
    );

    // 🎯 Mostrar el trimestre actual
    async function actualizarTextoTrimestre(fecha) {
        const fechaISO = fecha.toISOString().split('T')[0];

        try {
            const res = await fetch(`/api/periodo-por-fecha?fecha=${fechaISO}`);
            const data = await res.json();

            const texto = data.periodo
                ? `Trimestre ${data.periodo.numero} del año ${data.periodo.anio}`
                : 'Fuera de períodos académicos';

            document.getElementById('current-period-text').textContent = texto;
        } catch (err) {
            console.error('Error cargando período:', err);
            document.getElementById('current-period-text').textContent = 'Error';
        }
    }
});
