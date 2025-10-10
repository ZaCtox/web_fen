{{-- Cursos de Postgrado FEN Fusionado --}}
@section('title', 'Cursos')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-[#005187] dark:text-[#84b6f4]">
            Cursos por Programa
        </h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Cursos', 'url' => '#']
    ]" />

    <div class="py-6 max-w-7xl mx-auto px-4">
        {{-- Filtros y Botones --}}
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                {{-- Selector de ciclo --}}
                <div class="flex-1">
                    <form method="GET" action="{{ route('courses.index') }}" class="flex flex-col sm:flex-row gap-3 items-start sm:items-end">
                        <div class="w-full sm:w-auto">
                            <label for="cohorte" class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4] mb-2">
                                📅 Cohorte:
                            </label>
                            <select name="cohorte" 
                                    id="cohorte"
                                    onchange="this.form.submit()"
                                    class="w-full sm:w-64 rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-700 text-[#005187] dark:text-[#84b6f4] px-4 py-2.5 focus:ring-[#4d82bc] focus:border-[#4d82bc] font-medium">
                                @foreach($cohortes as $cohorte)
                                    <option value="{{ $cohorte }}" {{ $cohorteSeleccionada == $cohorte ? 'selected' : '' }}>
                                        {{ $cohorte }} {{ $cohorte == $cohortes->first() ? '(Actual)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>

                {{-- Botones de Acción --}}
                <div class="flex gap-3">
                    <a href="{{ route('mallas-curriculares.index') }}"
                        class="inline-flex items-center gap-2 bg-[#84b6f4] hover:bg-[#4d82bc] text-white px-4 py-2 rounded-lg shadow transition-all duration-200">
                        <img src="{{ asset('icons/searchw.svg') }}" alt="Mallas" class="w-5 h-5">
                        <span>Mallas Curriculares</span>
                    </a>
                    
                    <a href="{{ route('magisters.index') }}"
                        class="inline-flex items-center gap-2 bg-[#4d82bc] hover:bg-[#005187] text-white px-4 py-2 rounded-lg shadow transition-all duration-200">
                        <img src="{{ asset('icons/searchw.svg') }}" alt="Programas" class="w-5 h-5">
                        <span>Detalles de Programas</span>
                    </a>
                </div>
            </div>
            
            {{-- Indicador de filtro activo --}}
            @if($cohorteSeleccionada != $cohortes->first())
                <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                    <p class="text-sm text-yellow-800 dark:text-yellow-200">
                        ⚠️ Mostrando cursos de la ciclo <strong>{{ $cohorteSeleccionada }}</strong> (Periodo Pasado)
                    </p>
                </div>
            @endif
        </div>

        <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg shadow p-6 border border-[#c4dafa]">
            @php
                $romanos = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI'];
            @endphp

            @forelse ($magisters as $magister)
                <div class="mb-6 border border-[#c4dafa] rounded-lg shadow-sm bg-[#fcffff] dark:bg-gray-800">
                    {{-- Header clickable con affordance --}}
                    <div
                        class="flex justify-between items-center cursor-pointer magister-header 
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
                                                    <th class="px-4 py-2 text-left">Curso</th>
                                                    <th class="px-4 py-2 text-left">Malla</th>
                                                    <th class="px-4 py-2 text-right w-32">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($cursos as $course)
                                                    <tr class="border-b border-gray-200 dark:border-gray-600 
                                                                                   hover:bg-[#e3f2fd] dark:hover:bg-gray-700 
                                                                                   hover:border-l-4 hover:border-l-[#4d82bc]
                                                                                   hover:-translate-y-0.5 hover:shadow-md
                                                                                   transition-all duration-200 group cursor-pointer">
                                                        <td class="px-4 py-2 text-[#005187] dark:text-gray-100 group-hover:text-[#4d82bc] dark:group-hover:text-[#84b6f4] transition-colors duration-200 font-medium">
                                                            {{ $course->nombre }}
                                                        </td>
                                                        <td class="px-4 py-2">
                                                            @if($course->mallaCurricular)
                                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300"
                                                                      title="{{ $course->mallaCurricular->descripcion }}">
                                                                    {{ $course->mallaCurricular->codigo }}
                                                                </span>
                                                            @else
                                                                <span class="text-xs text-gray-400 dark:text-gray-500">Sin malla</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-3 py-2 text-right">
                                                            <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-2">
                                                                {{-- Botón Editar --}}
                                                                <x-action-button 
                                                                    variant="edit" 
                                                                    type="link" 
                                                                    :href="route('courses.edit', $course)" 
                                                                    icon="editw.svg"
                                                                    tooltip="Editar curso" />

                                                                {{-- Botón Eliminar --}}
                                                                <x-action-button 
                                                                    variant="delete" 
                                                                    :formAction="route('courses.destroy', $course)" 
                                                                    formMethod="DELETE" 
                                                                    class="form-eliminar"
                                                                    tooltip="Eliminar curso" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="px-4 py-8">
                                                            <x-empty-state type="no-data" icon="📚" title="No hay cursos registrados"
                                                                message="Crea tu primer curso para comenzar a gestionar el contenido académico."
                                                                actionText="Crear Curso" actionUrl="{{ route('courses.create') }}"
                                                                actionIcon="➕" />
                                                        </td>
                                                    </tr>
                                                @endforelse
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





