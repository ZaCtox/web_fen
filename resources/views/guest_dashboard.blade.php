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
            <x-filtros-calendario />
            {{-- Leyenda por Magíster --}}
            <x-leyenda-magister />


            <div id="calendar" data-url="{{ route('guest.events.index') }}"></div>
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
    @include('components.footer')

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

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
        @vite('resources/js/calendar-public.js')
    @endpush

</x-app-layout>