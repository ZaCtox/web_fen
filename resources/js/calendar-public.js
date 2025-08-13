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
            })
        },
        eventDidMount: info => {
            const magister = info.event.extendedProps.magister || 'Sin magíster';
            const sala = info.event.extendedProps.room?.name || 'Sin sala';
            const start = info.event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const end = info.event.end.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            const tooltip = `${info.event.title}\n🏛️ ${magister}\n🏫 ${sala}\n🕒 ${start} - ${end}`;
            info.el.setAttribute('title', tooltip.trim());
        }
    });

    calendar.render();

    [magisterFilter, roomFilter].forEach(select =>
        select.addEventListener('change', () => calendar.refetchEvents())
    );
});
