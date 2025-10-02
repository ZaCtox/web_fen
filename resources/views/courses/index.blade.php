{{-- Cursos de Postgrado FEN Fusionado --}}
@section('title', 'Cursos')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-[#005187] dark:text-[#84b6f4]">
            Cursos por Programa
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4">
        {{-- Botón global --}}
        <div class="flex justify-start mb-3">
            <a href="{{ route('magisters.index') }}"
                class="inline-flex items-center bg-[#4d82bc] hover:bg-[#005187] text-white px-4 py-2 rounded-lg shadow transition">
                Detalles de Programas
            </a>
        </div>

        <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg shadow p-6 border border-[#c4dafa]">
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
                    </div>

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
                                                    <th class="px-4 py-2 text-left"></th>
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
</x-app-layout>