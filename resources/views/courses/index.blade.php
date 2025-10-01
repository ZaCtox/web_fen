<<<<<<< Updated upstream
{{-- Inicio de Cursos.blade.php --}}
@section('title', 'Cursos')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Programas</h2>
=======
<<<<<<< Updated upstream
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Programas de Magíster</h2>
=======
{{-- Cursos de Postgrado FEN Fusionado --}}
@section('title', 'Cursos')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-[#005187] dark:text-[#84b6f4]">
            Cursos por Programa
        </h2>
>>>>>>> Stashed changes
>>>>>>> Stashed changes
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4">
        {{-- Botón global --}}
        <div class="flex justify-start mb-3">
            <a href="{{ route('magisters.index') }}"
<<<<<<< Updated upstream
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Ver Detalles de Programas
=======
<<<<<<< Updated upstream
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                🔍 Ver Magísteres
=======
                class="inline-flex items-center bg-[#4d82bc] hover:bg-[#005187] text-white px-4 py-2 rounded-lg shadow transition">
                Detalles de Programas
>>>>>>> Stashed changes
>>>>>>> Stashed changes
            </a>
        </div>
        <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg shadow p-6 border border-[#c4dafa]">

<<<<<<< Updated upstream
        @foreach ($magisters as $magister)
            <div x-data="{ open: false }"
                 class="mb-6 border border-gray-300 dark:border-gray-600 rounded p-4">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center items-center text-center gap-2 mb-2 w-full">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white sm:text-left w-full sm:w-auto">
                        Magíster en {{ $magister->nombre }}
                    </h3>

                    <div class="flex flex-col sm:flex-row gap-2 sm:items-center sm:justify-end w-full sm:w-auto justify-center">
                        <a href="{{ route('courses.create', ['magister_id' => $magister->id]) }}"
                           class="text-sm bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">
                            + Añadir Curso
                        </a>

                        @if($magister->courses->count() > 0)
                            <button @click="open = !open"
                                    class="text-sm bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-white px-3 py-1 rounded hover:bg-gray-400 transition-colors duration-200">
                                <template x-if="!open"><span>📂 Ver Cursos</span></template>
                                <template x-if="open"><span>🔽 Ocultar Cursos</span></template>
                            </button>
                        @endif
=======
            @php
                $romanos = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI'];
            @endphp

            @forelse ($magisters as $magister)
                <div class="mb-6 border border-[#c4dafa] rounded-lg shadow-sm bg-[#fcffff] dark:bg-gray-800">

                    {{-- Header clickable con affordance --}}
                    <div class="flex justify-between items-center cursor-pointer magister-header 
                                            bg-[#c4dafa]/30 hover:bg-[#84b6f4]/30 px-4 py-3 rounded-t-lg transition">
                        <h3 class="text-lg font-semibold text-[#005187] dark:text-[#84b6f4]">
                            Magíster en {{ $magister->nombre }}
                        </h3>
                        <div class="flex items-center gap-3">
                            {{-- Botón añadir curso --}}
                            <a href="{{ route('courses.create', ['magister_id' => $magister->id]) }}"
                                class="inline-flex items-center bg-[#4d82bc] hover:bg-[#005187] text-white px-3 py-2 rounded-lg shadow transition transform hover:scale-105">
                                <img src="{{ asset('icons/agregar.svg') }}" alt="nueva" class="w-3 h-3">
                            </a>
                            <span class="text-sm text-[#4d82bc] flex items-center">
                                <svg class="ml-2 w-5 h-5 transition-transform" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </div>
>>>>>>> Stashed changes
                    </div>

<<<<<<< Updated upstream
                @if($magister->courses->count() > 0)
                    @php
                        $cursosAgrupados = $magister->courses->groupBy([
                            fn($curso) => $curso->period->anio ?? 'Sin año',
                            fn($curso) => $curso->period->numero ?? 'Sin trimestre',
                        ]);
                        $romanos = [1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI'];
                    @endphp

                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="mt-3 space-y-4">
                        @foreach ($cursosAgrupados as $anio => $porTrimestre)
                            <div class="border rounded p-3 bg-gray-50 dark:bg-gray-800">
                                <h4 class="font-bold text-gray-800 dark:text-gray-200 mb-2">📘 Año {{ $anio }}</h4>

                                @foreach ($porTrimestre as $trimestre => $cursos)
                                    <div class="ml-4 mb-2">
                                        <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            Trimestre {{ $romanos[$trimestre] ?? $trimestre }}
                                        </h5>

                                        <table class="w-full table-auto text-sm mt-1">
                                            <thead class="bg-gray-100 dark:bg-gray-700">
                                                <tr>
                                                    <th class="px-4 py-2 text-left">Cursos</th>
                                                    <th class="px-4 py-2 text-right w-32">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($cursos as $course)
                                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                                        <td class="px-4 py-2">
                                                            {{ $course->nombre }}
                                                        </td>
                                                        <td class="px-4 py-2 text-right space-x-2">
                                                            <a href="{{ route('courses.edit', $course) }}"
                                                               class="text-blue-600 hover:underline">✏️</a>

                                                            {{-- Usar SweetAlert confirm desde alerts.js --}}
                                                            <form action="{{ route('courses.destroy', $course) }}"
                                                                  method="POST"
                                                                  class="inline form-eliminar"
                                                                  data-confirm="¿Eliminar curso '{{ $course->nombre }}'?">
                                                                @csrf @method('DELETE')
                                                                <button type="submit"
                                                                        class="text-red-600 hover:underline">🗑️</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                        Este magíster aún no tiene cursos registrados.
                    </p>

                    {{-- Si quieres permitir eliminar el magíster sin cursos desde aquí --}}
                    <form action="{{ route('magisters.destroy', $magister) }}"
                          method="POST"
                          class="mt-4 form-eliminar"
                          data-confirm="¿Eliminar el magíster '{{ $magister->nombre }}'? Esta acción no se puede deshacer.">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded">
                            🗑️ Eliminar Magíster
                        </button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
<<<<<<< Updated upstream
</x-app-layout>
=======
=======
                    {{-- Contenido oculto inicialmente --}}
                    <div class="magister-content hidden p-4 space-y-4">
                        @php
                            $agrupados = $magister->courses->groupBy([
                                fn($curso) => $curso->period->anio ?? 'Sin año',
                                fn($curso) => $curso->period->numero ?? 'Sin trimestre',
                            ]);
                        @endphp

                        @forelse ($agrupados as $anio => $porTrimestre)
                            <div class="border border-[#c4dafa] rounded bg-[#c4dafa]/10 p-3">
                                <h4 class="font-bold text-[#005187] dark:text-[#84b6f4] mb-2">Año {{ $anio }}</h4>

                                @foreach ($porTrimestre as $trimestre => $cursos)
                                    <div class="ml-4 mb-3">
                                        <h5 class="text-sm font-semibold text-[#4d82bc] dark:text-[#84b6f4] mb-1">
                                            Trimestre {{ $romanos[$trimestre] ?? $trimestre }}
                                        </h5>

                                        <table class="w-full table-auto text-sm rounded overflow-hidden shadow-sm">
                                            <thead class="bg-[#c4dafa]/40 dark:bg-gray-700 text-[#005187] dark:text-white">
                                                <tr>
                                                    <th class="px-4 py-2 text-left">Curso</th>
                                                    <th class="px-4 py-2 text-right w-32">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($cursos as $course)
                                                    <tr
                                                        class="border-b border-gray-200 dark:border-gray-600 hover:bg-[#84b6f4]/10 transition">
                                                        <td class="px-4 py-2 text-[#005187] dark:text-gray-100">
                                                            {{ $course->nombre }}
                                                        </td>
                                                        <td class="px-3 py-2 text-right">
                                                            <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-2">
                                                                {{-- Botón editar --}}
                                                                <a href="{{ route('courses.edit', $course) }}"
                                                                    class="inline-flex items-center justify-center px-1 py-1 hover:bg-[#84b6f4]/30 rounded-lg text-xs font-medium transition w-full sm:w-auto">
                                                                    <img src="{{ asset('icons/edit.svg') }}" alt="Editar"
                                                                        class="w-5 h-5">
                                                                </a>

                                                                {{-- Botón eliminar con SweetAlert --}}
                                                                <form action="{{ route('courses.destroy', $course) }}" method="POST"
                                                                    class="form-eliminar inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="inline-flex items-center justify-center px-1 py-1 hover:bg-[#84b6f4]/30 rounded-lg text-xs font-medium transition w-full sm:w-auto">
                                                                        <img src="{{ asset('icons/trash.svg') }}" alt="Borrar"
                                                                            class="w-4 h-4">
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                            </div>
                        @empty
                            <p class="text-sm text-[#4d82bc] dark:text-gray-400 mt-2">
                                Este magíster aún no tiene cursos registrados.
                            </p>
                        @endforelse
                    </div>
                </div>
            @empty
                <p class="text-center text-[#4d82bc] dark:text-gray-400">
                    No hay programas de magíster registrados.
                </p>
            @endforelse
        </div>
    </div>

    {{-- Script para abrir/cerrar con rotación del ícono --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".magister-header").forEach(function (header) {
                header.addEventListener("click", function () {
                    const content = header.nextElementSibling;
                    const icon = header.querySelector("svg");

                    content.classList.toggle("hidden");
                    icon.classList.toggle("rotate-180");
                });
            });
        });
    </script>
>>>>>>> Stashed changes
</x-app-layout>
>>>>>>> Stashed changes
