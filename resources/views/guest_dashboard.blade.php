<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Bienvenido a Web_FEN</h2>
            <a href="{{ route('login') }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                🔐 Iniciar Sesión
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 space-y-10">
        {{-- Calendario --}}
        <div class="bg-white dark:bg-gray-800 rounded shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Calendario Académico</h3>
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
        <div class="bg-white dark:bg-gray-800 rounded shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Salas Registradas</h3>
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
                            <tr class="border-b border-gray-200 dark:border-gray-600">
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

        {{-- Cursos por Magíster --}}
        <div class="bg-white dark:bg-gray-800 rounded shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Cursos por Programa de Magíster</h3>

            @php $romanos = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI']; @endphp

            @foreach ($magisters as $magister)
                <div class="mb-6">
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

    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const calendarEl = document.getElementById('calendar');

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'timeGridWeek',
                    locale: 'es',
                    firstDay: 1,
                    slotMinTime: "08:30:00",
                    slotMaxTime: "21:00:00",
                    editable: false,
                    selectable: false,
                    events: '/events', // Usa tu ruta API ya disponible
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
            });
        </script>
    @endsection
</x-app-layout>