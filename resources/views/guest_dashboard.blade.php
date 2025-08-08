<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Bienvenido a Postrago FEN</h2>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 space-y-10">
        {{-- Calendario --}}
        <div class="bg-white dark:bg-gray-800 rounded shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Calendario Académico</h3>

            {{-- 🔍 Filtros --}}
            <div class="flex flex-wrap items-center gap-4 mb-6">
                <div>
                    <label for="magister-filter" class="block text-sm font-medium text-gray-800 dark:text-white">
                        Filtrar por Magíster:
                    </label>
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
                    <label for="room-filter" class="block text-sm font-medium text-gray-800 dark:text-white">
                        Filtrar por Sala:
                    </label>
                    <select id="room-filter" class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
                        <option value="">Todas</option>
                        @foreach(\App\Models\Room::all() as $room)
                            <option value="{{ $room->name }}">{{ $room->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

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

            <div id="calendar"></div>
        </div>

        {{-- Salas --}}
        <div x-data="{ open: false, search: '' }" class="bg-white dark:bg-gray-800 rounded shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Salas Registradas</h3>
                <button @click="open = !open" class="text-sm text-blue-600 dark:text-blue-300 hover:underline">
                    <span x-show="open">🔽 Ocultar</span>
                    <span x-show="!open">▶️ Mostrar</span>
                </button>
            </div>

            <div x-show="open" x-transition>
                <input type="text" x-model="search" placeholder="Buscar sala por nombre..."
                    class="w-full mb-4 px-3 py-2 border rounded dark:bg-gray-700 dark:text-white">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2">Nombre</th>
                                <th class="px-4 py-2">Ubicación</th>
                                <th class="px-4 py-2">Capacidad</th>
                                <th class="px-4 py-2">Descripción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rooms as $room)
                                <tr class="border-b border-gray-200 dark:border-gray-600"
                                    x-show="search === '' || '{{ strtolower($room->name) }}'.includes(search.toLowerCase())">
                                    <td class="px-4 py-2">{{ $room->name }}</td>
                                    <td class="px-4 py-2">{{ $room->location }}</td>
                                    <td class="px-4 py-2">{{ $room->capacity }}</td>
                                    <td class="px-4 py-2">{{ $room->description }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Cursos por Magíster --}}
        <div x-data="{ open: false, search: '' }" class="bg-white dark:bg-gray-800 rounded shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Cursos por Programa de Magíster</h3>
                <button @click="open = !open" class="text-sm text-blue-600 dark:text-blue-300 hover:underline">
                    <span x-show="open">🔽 Ocultar</span>
                    <span x-show="!open">▶️ Mostrar</span>
                </button>
            </div>

            <div x-show="open" x-transition>
                <input type="text" x-model="search" placeholder="Buscar programa por nombre..."
                    class="w-full mb-6 px-3 py-2 border rounded dark:bg-gray-700 dark:text-white">

                @php $romanos = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI']; @endphp

                @foreach ($magisters as $magister)
                    <div class="mb-6"
                        x-show="search === '' || '{{ strtolower($magister->nombre) }}'.includes(search.toLowerCase())">
                        <h4 class="text-base font-bold text-blue-700 dark:text-blue-300">{{ $magister->nombre }}</h4>

                        @php
                            $agrupados = $magister->courses->groupBy([
                                fn($curso) => $curso->period->anio ?? 'Sin año',
                                fn($curso) => $curso->period->numero ?? 'Sin trimestre',
                            ]);
                        @endphp

                        @foreach ($agrupados as $anio => $porTrimestre)
                            <div class="ml-4 mt-2">
                                <h5 class="text-sm font-semibold text-gray-800 dark:text-gray-300">📘 Año {{ $anio }}</h5>

                                @foreach ($porTrimestre as $trimestre => $cursos)
                                    <div class="ml-4 mt-1">
                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                            Trimestre {{ $romanos[$trimestre] ?? $trimestre }}
                                        </p>
                                        <ul class="list-disc ml-5 text-sm text-gray-700 dark:text-gray-200">
                                            @foreach ($cursos as $curso)
                                                <li>{{ $curso->nombre }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Día de viernes (6) y sábado (7) más anchos */
            .fc .fc-timegrid-col {
                flex-grow: 1;
                flex-basis: 0;
            }

            .fc .fc-timegrid-col:nth-child(7),
            .fc .fc-timegrid-col:nth-child(8) {
                flex-grow: 2.5;
            }

            .fc .fc-timegrid-slots {
                min-width: 100%;
            }
        </style>
    @endpush

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
                    events: {
                        url: '{{ route("guest.events.index") }}',
                        extraParams: function () {
                            return {
                                magister: magisterFilter.value,
                                room: roomFilter.value,
                            }
                        }
                    },
                    eventDidMount: info => {
                        const magister = info.event.extendedProps.magister || 'Sin magíster';
                        const sala = info.event.extendedProps.room?.name || 'Sin sala';
                        const start = info.event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                        const end = info.event.end.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                        const tooltip = `
                                ${info.event.title}
                                🏛️ ${magister}
                                🏫 ${sala}
                                🕒 ${start} - ${end}
                            `;
                        info.el.setAttribute('title', tooltip.trim());
                    }
                });

                calendar.render();

                [magisterFilter, roomFilter].forEach(select =>
                    select.addEventListener('change', () => calendar.refetchEvents())
                );
            });
        </script>
    @endsection
</x-app-layout>