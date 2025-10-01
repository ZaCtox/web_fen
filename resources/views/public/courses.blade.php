{{-- Cursos de Postgrado FEN --}}
@section('title', 'Cursos')
<x-app-layout>
    <x-slot name="header">
<<<<<<< Updated upstream
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Cursos por Programas</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4">
        <div class="bg-white dark:bg-gray-800 rounded shadow p-6">
=======
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
            Cursos por Programa
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4">
        <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg shadow p-6 border border-[#c4dafa]">
>>>>>>> Stashed changes

            @php
                $romanos = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI'];
            @endphp

            @forelse ($magisters as $magister)
<<<<<<< Updated upstream
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
=======
                <div class="mb-6 border border-[#c4dafa] rounded-lg shadow-sm bg-[#fcffff] dark:bg-gray-800">

                    {{-- Header clickable con affordance --}}
                    <div class="flex justify-between items-center cursor-pointer magister-header 
                                    bg-[#c4dafa]/30 hover:bg-[#84b6f4]/30 px-4 py-2 rounded-t-lg transition">
                        <h3 class="text-lg font-semibold text-[#005187] dark:text-[#84b6f4]">
                            🎓 Magíster en {{ $magister->nombre }}
                        </h3>
                        <span class="text-sm text-[#4d82bc] flex items-center">
                            📂 Ver Cursos
                            <svg class="ml-2 w-4 h-4 transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </div>

                    {{-- Contenido oculto inicialmente --}}
                    <div class="magister-content hidden p-4 space-y-4">
>>>>>>> Stashed changes
                        @php
                            $agrupados = $magister->courses->groupBy([
                                fn($curso) => $curso->period->anio ?? 'Sin año',
                                fn($curso) => $curso->period->numero ?? 'Sin trimestre',
                            ]);
                        @endphp

                        @forelse ($agrupados as $anio => $porTrimestre)
<<<<<<< Updated upstream
                            <div class="border rounded p-3 bg-gray-50 dark:bg-gray-700">
                                <h4 class="font-bold text-gray-800 dark:text-gray-200 mb-2">📘 Año {{ $anio }}</h4>

                                @foreach ($porTrimestre as $trimestre => $cursos)
                                    <div class="ml-4 mb-2">
                                        <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            Trimestre {{ $romanos[$trimestre] ?? $trimestre }}
                                        </h5>

                                        <ul class="list-disc ml-6 text-sm text-gray-700 dark:text-gray-200">
=======
                            <div class="border border-[#c4dafa] rounded bg-[#c4dafa]/20 p-3">
                                <h4 class="font-bold text-[#005187] dark:text-[#84b6f4] mb-2">📘 Año {{ $anio }}</h4>

                                @foreach ($porTrimestre as $trimestre => $cursos)
                                    <div class="ml-4 mb-2">
                                        <h5 class="text-sm font-semibold text-[#4d82bc] dark:text-[#84b6f4]">
                                            Trimestre {{ $romanos[$trimestre] ?? $trimestre }}
                                        </h5>

                                        <ul class="list-disc ml-6 text-sm text-[#005187] dark:text-[#fcffff]">
>>>>>>> Stashed changes
                                            @foreach ($cursos as $curso)
                                                <li>{{ $curso->nombre }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        @empty
<<<<<<< Updated upstream
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
=======
                            <p class="text-sm text-[#4d82bc] dark:text-gray-400 mt-2">
>>>>>>> Stashed changes
                                Este magíster aún no tiene cursos registrados.
                            </p>
                        @endforelse
                    </div>
                </div>
            @empty
<<<<<<< Updated upstream
                <p class="text-center text-gray-500 dark:text-gray-400">No hay programas de magíster registrados.</p>
=======
                <p class="text-center text-[#4d82bc] dark:text-gray-400">
                    No hay programas de magíster registrados.
                </p>
>>>>>>> Stashed changes
            @endforelse
        </div>
    </div>

<<<<<<< Updated upstream
    {{-- Script para abrir/cerrar --}}
=======
    {{-- Script para abrir/cerrar con rotación del ícono --}}
>>>>>>> Stashed changes
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".magister-header").forEach(function (header) {
                header.addEventListener("click", function () {
                    const content = header.nextElementSibling;
<<<<<<< Updated upstream
                    content.classList.toggle("hidden");
=======
                    const icon = header.querySelector("svg");

                    content.classList.toggle("hidden");
                    icon.classList.toggle("rotate-180");
>>>>>>> Stashed changes
                });
            });
        });
    </script>

    @include('components.footer')
</x-app-layout>