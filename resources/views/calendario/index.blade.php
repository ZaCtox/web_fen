<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            Calendario Académico
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-4">
                <div class="flex justify-between items-center mb-4">
                    <label for="magister-filter" class="text-sm font-medium text-gray-800 dark:text-white">Filtrar por
                        Magíster:</label>
                    <select id="magister-filter" class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
                        <option value="">Todos</option>
                        <option value="Economía">Economía</option>
                        <option value="Gestión de Sistemas de Salud">Gestión de Sistemas de Salud</option>
                        <option value="Gestión y Políticas Públicas">Gestión y Políticas Públicas</option>
                        <option value="Dirección y Planificación Tributaria">Dirección y Planificación Tributaria
                        </option>
                    </select>
                </div>
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    {{-- Modal de Detalles --}}
    <div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow w-full max-w-md">
            <h2 class="text-xl font-bold mb-2" id="modal-title">Título del evento</h2>
            <p class="mb-2 text-sm text-gray-700 dark:text-gray-300" id="modal-description">Descripción</p>
            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><strong>Inicio:</strong> <span
                    id="modal-start"></span></p>
            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><strong>Fin:</strong> <span id="modal-end"></span>
            </p>
            <p class="mb-4 text-sm text-gray-500 dark:text-gray-400"><strong>Sala:</strong> <span
                    id="modal-room"></span></p>
            <div class="flex justify-end gap-2">
                <button id="delete-btn"
                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">Eliminar</button>
                <button onclick="closeModal()"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded">Cerrar</button>
            </div>
        </div>
    </div>
    {{-- Modal de Creación --}}
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white dark:bg-gray-900 p-6 rounded shadow-lg w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Crear Evento</h3>
            <form id="event-form">
                @csrf
                <input type="hidden" id="start_time">
                <input type="hidden" id="end_time">

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título</label>
                    <input type="text" id="modal-title-input" required
                        class="w-full mt-1 px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción
                        (opcional)</label>
                    <textarea id="modal-description-input" rows="2"
                        class="w-full mt-1 px-3 py-2 rounded border dark:bg-gray-700 dark:text-white"></textarea>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Magíster</label>
                    <select id="modal-magister" required
                        class="w-full mt-1 px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
                        <option value="">Seleccione Magíster</option>
                        <option value="Economía">Economía</option>
                        <option value="Gestión de Sistemas de Salud">Gestión de Sistemas de Salud</option>
                        <option value="Gestión y Políticas Públicas">Gestión y Políticas Públicas</option>
                        <option value="Dirección y Planificación Tributaria">Dirección y Planificación Tributaria
                        </option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sala (opcional)</label>
                    <select id="room_id" class="w-full px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
                        <option value="">-- Sin sala --</option>
                        @foreach(\App\Models\Room::all() as $room)
                            <option value="{{ $room->id }}">{{ $room->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" id="cancel"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancelar</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Guardar</button>
                </div>
            </form>
        </div>
    </div>




    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', async function () {
                const calendarEl = document.getElementById('calendar');
                const magisterFilter = document.getElementById('magister-filter');
                const magSelect = document.getElementById('modal-magister');
                const subjectInput = document.getElementById('modal-subject');
                const yearInput = document.getElementById('modal-year');
                const trimestreInput = document.getElementById('modal-trimestre');

                let magisterData = {};
                let detalles = {};

                // Cargar JSON
                const res = await fetch('/magisteres_completo.json');
                const json = await res.json();
                magisterData = json.por_magister;
                detalles = json.detalles;

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'timeGridWeek',
                    locale: 'es',
                    firstDay: 5,
                    selectable: true,
                    editable: true,
                    slotMinTime: "08:30:00",
                    slotMaxTime: "21:00:00",
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

                magisterFilter.addEventListener('change', () => calendar.refetchEvents());

                function fetchFilteredEvents(fetchInfo, successCallback, failureCallback) {
                    fetch('/events')
                        .then(res => res.json())
                        .then(events => {
                            const selectedMagister = magisterFilter.value;
                            if (!selectedMagister) return successCallback(events);
                            const filtered = events.filter(e => {
                                if (e.editable === true) return true;
                                if (!selectedMagister) return true;
                                if (e.description && e.description.includes(selectedMagister)) return true;
                                if (detalles[e.title] && detalles[e.title].magister === selectedMagister) return true;
                                return false;
                            });
                            successCallback(filtered);
                        })
                        .catch(failureCallback);
                }

                function openCreateModal(info) {
                    document.getElementById('start_time').value = info.startStr;
                    document.getElementById('end_time').value = info.endStr;
                    document.getElementById('modal').classList.remove('hidden');
                }

                document.getElementById('cancel').addEventListener('click', () => {
                    document.getElementById('modal').classList.add('hidden');
                });

                document.getElementById('event-form').addEventListener('submit', function (e) {
                    e.preventDefault();

                    fetch('/events', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            title: document.getElementById('modal-title-input').value,
                            description: document.getElementById('modal-description-input').value +
                                ' - Magíster: ' + document.getElementById('modal-magister').value,
                            start_time: document.getElementById('start_time').value,
                            end_time: document.getElementById('end_time').value,
                            room_id: document.getElementById('room_id').value,
                            type: 'clase'
                        })
                    }).then(() => {
                        document.getElementById('modal').classList.add('hidden');
                        document.getElementById('event-form').reset();
                        calendar.refetchEvents();
                    });
                });
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
        </script>

    @endsection
</x-app-layout>