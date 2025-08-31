<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Cursos por Programas</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4">
        <div class="bg-white dark:bg-gray-800 rounded shadow p-6">

            @php
                $romanos = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI'];
            @endphp

            @forelse ($magisters as $magister)
                <div class="mb-6 border border-gray-300 dark:border-gray-600 rounded p-4">

                    {{-- Header clickable --}}
                    <div class="flex justify-between items-center cursor-pointer magister-header">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            🎓 Magíster en {{ $magister->nombre }}
                        </h3>
                        <span class="text-sm text-gray-600 dark:text-gray-400">📂 Ver Cursos</span>
                    </div>

                    {{-- Contenido oculto inicialmente --}}
                    <div class="magister-content hidden mt-3 space-y-4">
                        @php
                            $agrupados = $magister->courses->groupBy([
                                fn($curso) => $curso->period->anio ?? 'Sin año',
                                fn($curso) => $curso->period->numero ?? 'Sin trimestre',
                            ]);
                        @endphp

                        @forelse ($agrupados as $anio => $porTrimestre)
                            <div class="border rounded p-3 bg-gray-50 dark:bg-gray-700">
                                <h4 class="font-bold text-gray-800 dark:text-gray-200 mb-2">📘 Año {{ $anio }}</h4>

                                @foreach ($porTrimestre as $trimestre => $cursos)
                                    <div class="ml-4 mb-2">
                                        <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            Trimestre {{ $romanos[$trimestre] ?? $trimestre }}
                                        </h5>

                                        <ul class="list-disc ml-6 text-sm text-gray-700 dark:text-gray-200">
                                            @foreach ($cursos as $curso)
                                                <li>{{ $curso->nombre }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                Este magíster aún no tiene cursos registrados.
                            </p>
                        @endforelse
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 dark:text-gray-400">No hay programas de magíster registrados.</p>
            @endforelse
        </div>
    </div>

    {{-- Script para abrir/cerrar --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".magister-header").forEach(function (header) {
                header.addEventListener("click", function () {
                    const content = header.nextElementSibling;
                    content.classList.toggle("hidden");
                });
            });
        });
    </script>

    @include('components.footer')
</x-app-layout>