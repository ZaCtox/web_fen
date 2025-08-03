<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Calendario Académico</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-4">
            {{-- Filtros --}}
            <div class="flex flex-wrap items-center gap-4">
                <div>
                    <label for="magister-filter" class="block text-sm font-medium text-gray-800 dark:text-white">Filtrar
                        por Magíster:</label>
                    <select id="magister-filter" class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
                        <option value="">Todos</option>
                        <option value="Economía">Economía</option>
                        <option value="Gestión de Sistemas de Salud">Gestión de Sistemas de Salud</option>
                        <option value="Gestión y Políticas Públicas">Gestión y Políticas Públicas</option>
                        <option value="Dirección y Planificación Tributaria">Dirección y Planificación Tributaria
                        </option>
                    </select>
                </div>
                <div>
                    <label for="room-filter" class="block text-sm font-medium text-gray-800 dark:text-white">Filtrar por
                        Sala:</label>
                    <select id="room-filter" class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
                        <option value="">Todas</option>
                        @foreach(\App\Models\Room::all() as $room)
                            <option value="{{ $room->name }}">{{ $room->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Calendario --}}
            {{-- Leyenda por Magíster --}}
            <div class="flex flex-wrap gap-4 mb-4">
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 inline-block rounded-full bg-blue-500"></span>
                    <span class="text-sm text-gray-800 dark:text-gray-200">Economía</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 inline-block rounded-full bg-red-500"></span>
                    <span class="text-sm text-gray-800 dark:text-gray-200">Dirección y Planificación Tributaria</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 inline-block rounded-full bg-green-500"></span>
                    <span class="text-sm text-gray-800 dark:text-gray-200">Gestión de Sistemas de Salud</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 inline-block rounded-full bg-orange-500"></span>
                    <span class="text-sm text-gray-800 dark:text-gray-200">Gestión y Políticas Públicas</span>
                </div>
            </div>
            <div id="calendar" class="mt-6"></div>
        </div>
    </div>

    {{-- Modales --}}
    @include('calendario.modal-crear')
    @include('calendario.modal-ver')


    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', async function () {
                const calendarEl = document.getElementById('calendar');
                const magisterFilter = document.getElementById('magister-filter');
                const roomFilter = document.getElementById('room-filter');

                const res = await fetch('/magisteres_completo.json');
                const json = await res.json();
                const detalles = json.detalles;

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'timeGridWeek',
                    locale: 'es',
                    firstDay: 1,
                    selectable: true,
                    editable: true,
                    slotMinTime: "08:30:00",
                    slotMaxTime: "21:00:00",
                    events: fetchFilteredEvents,

                    select: openCreateModal,
                    eventClick: info => openViewModal(info.event),
                    eventDrop: info => updateEvent(info.event),
                    eventResize: info => updateEvent(info.event),

                    eventDidMount: info => {
                        const magister = info.event.extendedProps.magister || 'Sin magíster';
                        const sala = info.event.extendedProps.room?.name || 'Sin sala';
                        const start = info.event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                        const end = info.event.end.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                        const tooltip = `${info.event.title}\n🏛️ ${magister}\n🏫 ${sala}\n🕒 ${start} - ${end}`;
                        info.el.setAttribute('title', tooltip);
                    }
                });

                calendar.render();

                magisterFilter.addEventListener('change', () => calendar.refetchEvents());
                roomFilter.addEventListener('change', () => calendar.refetchEvents());

                function fetchFilteredEvents(fetchInfo, successCallback, failureCallback) {
                    fetch('/events')
                        .then(res => res.json())
                        .then(events => {
                            const selectedMagister = magisterFilter.value;
                            const selectedRoom = roomFilter.value;

                            const filtered = events.filter(e => {
                                if (e.editable === true) return true;
                                if (!selectedMagister) return true;
                                if (e.magister === null) return true;
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
                    document.getElementById('modal-title').innerText = event.title;
                    document.getElementById('modal-description').innerText = event.extendedProps.description ?? '';
                    document.getElementById('modal-start').innerText = event.start.toLocaleString();
                    document.getElementById('modal-end').innerText = event.end.toLocaleString();
                    document.getElementById('modal-room').innerText = event.extendedProps.room?.name ?? 'No asignada';
                    document.getElementById('delete-btn').onclick = () => deleteEvent(event);
                    document.getElementById('eventModal').classList.remove('hidden');
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