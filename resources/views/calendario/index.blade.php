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
                editable: true,
                selectable: true,

                select: function (info) {
                    // Abrir modal crear
                    document.getElementById('modal').classList.remove('hidden');
                    document.getElementById('start_time').value = info.startStr;
                    document.getElementById('end_time').value = info.endStr;
                },

                eventClick: function (info) {
                    // Abrir modal ver
                    document.getElementById('modal-title').textContent = info.event.title;
                    document.getElementById('modal-description').textContent = info.event.extendedProps.description || '';
                    document.getElementById('modal-start').textContent = info.event.start.toLocaleString();
                    document.getElementById('modal-end').textContent = info.event.end.toLocaleString();
                    document.getElementById('modal-room').textContent = info.event.extendedProps.room?.name || 'Sin sala';

                    const deleteBtn = document.getElementById('delete-btn');
                    if (info.event.extendedProps.type === 'manual') {
                        deleteBtn.classList.remove('hidden');
                        deleteBtn.setAttribute('data-id', info.event.id);
                    } else {
                        deleteBtn.classList.add('hidden');
                    }

                    document.getElementById('eventModal').classList.remove('hidden');
                },

                events: {
                    url: '{{ route("events.index") }}', // Solo para usuarios autenticados
                    extraParams: function () {
                        return {
                            magister: magisterFilter.value,
                            room: roomFilter.value,
                        }
                    }
                },

                eventDidMount: function (info) {
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

            // 🟢 Enviar nuevo evento
            document.getElementById('event-form').addEventListener('submit', function (e) {
                e.preventDefault();

                const data = {
                    title: document.getElementById('modal-title-input').value,
                    description: document.getElementById('modal-description-input').value,
                    start_time: document.getElementById('start_time').value,
                    end_time: document.getElementById('end_time').value,
                    room_id: document.getElementById('room_id').value || null,
                };

                fetch("{{ route('events.store') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                }).then(response => {
                    if (response.ok) {
                        calendar.refetchEvents();
                        closeModal();
                    } else {
                        alert("Error al crear el evento.");
                    }
                });
            });

            // 🗑️ Eliminar evento manual
            document.getElementById('delete-btn').addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                if (!confirm("¿Estás seguro de eliminar este evento?")) return;

                fetch(`/events/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(response => {
                    if (response.ok) {
                        calendar.refetchEvents();
                        closeModal();
                    } else {
                        alert("Error al eliminar el evento.");
                    }
                });
            });

            // ❌ Cancelar creación
            document.getElementById('cancel').addEventListener('click', () => {
                closeModal();
            });

            // ✅ Reset & cerrar modales
            window.closeModal = function () {
                document.getElementById('modal').classList.add('hidden');
                document.getElementById('eventModal').classList.add('hidden');

                document.getElementById('event-form').reset();
                document.getElementById('start_time').value = '';
                document.getElementById('end_time').value = '';
                document.getElementById('delete-btn').removeAttribute('data-id');
            };
        });
    </script>
@endsection


</x-app-layout>