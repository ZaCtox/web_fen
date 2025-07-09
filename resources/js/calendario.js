document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const magisterFilter = document.getElementById('magister-filter');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: 'es',
        firstDay: 5,
        selectable: true,
        editable: true,
        slotMinTime: "08:00:00",
        slotMaxTime: "19:00:00",
        events: fetchFilteredEvents,

        select: info => openCreateModal(info),
        eventClick: info => openViewModal(info.event),
        eventDrop: info => updateEvent(info.event),
        eventResize: info => updateEvent(info.event),
        eventDidMount: info => {
            if (info.event.extendedProps.room) {
                const roomName = info.event.extendedProps.room.name;
                info.el.querySelector('.fc-event-title').innerHTML +=
                    `<br><small class="text-sm text-gray-400">${roomName}</small>`;
            }
        }
    });

    calendar.render();

    magisterFilter.addEventListener('change', () => {
        calendar.refetchEvents();
    });

    function fetchFilteredEvents(fetchInfo, successCallback, failureCallback) {
        fetch('/events')
            .then(res => res.json())
            .then(events => {
                const selectedMagister = magisterFilter.value;
                if (!selectedMagister) return successCallback(events);
                const filtered = events.filter(e => e.title && e.title.includes(selectedMagister));
                successCallback(filtered);
            })
            .catch(failureCallback);
    }

    function openCreateModal(info) {
        // Aquí mostrar modal personalizado con lógica de asignatura y magíster si lo deseas.
        alert("Aquí iría el modal de creación con validaciones.");
    }

    function openViewModal(event) {
        const modal = document.getElementById('eventModal');
        document.getElementById('modal-title').innerText = event.title;
        document.getElementById('modal-description').innerText = event.extendedProps.description ?? '';
        document.getElementById('modal-start').innerText = event.start.toLocaleString();
        document.getElementById('modal-end').innerText = event.end.toLocaleString();
        document.getElementById('modal-room').innerText = event.extendedProps.room?.name ?? 'No asignada';
        document.getElementById('delete-btn').onclick = () => deleteEvent(event);
        modal.classList.remove('hidden');
    }

    function updateEvent(event) {
        fetch(`/events/${event.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                title: event.title,
                start_time: event.start.toISOString(),
                end_time: event.end.toISOString(),
                room_id: event.extendedProps.room_id ?? null
            })
        });
    }

    function deleteEvent(event) {
        fetch(`/events/${event.id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(() => {
            calendar.refetchEvents();
            closeModal();
        });
    }

    window.closeModal = function () {
        document.getElementById('eventModal').classList.add('hidden');
    }
});
