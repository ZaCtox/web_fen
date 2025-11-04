{{-- Módulos de Postgrado FEN Fusionado --}}
@section('title', 'Módulos')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-[#005187] dark:text-[#84b6f4]">
            Módulos por Programa
        </h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Módulos', 'url' => '#']
    ]" />

    <div class="py-6 max-w-7xl mx-auto px-4">
        {{-- Botones superiores --}}
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
            {{-- Lado izquierdo: Botón Ver Programas --}}
            <a href="{{ route('magisters.index') }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 text-sm font-medium hci-button-ripple hci-glow">
                <img src="{{ asset('icons/searchw.svg') }}" alt="" class="w-5 h-5">
                <span>Ver Programas</span>
            </a>

            {{-- Lado derecho: Filtro de Año de Ingreso --}}
            <div class="w-full sm:w-auto">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                    <form method="GET" action="{{ route('courses.index') }}" class="flex items-center gap-3">
                        <label for="anio_ingreso" class="block text-sm font-semibold text-[#005187] dark:text-[#84b6f4] whitespace-nowrap">
                            Año de Ingreso:
                        </label>
                        <select name="anio_ingreso" 
                                id="anio_ingreso"
                                onchange="this.form.submit()"
                                class="w-full sm:w-64 rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-700 text-[#005187] dark:text-[#84b6f4] px-4 py-2.5 focus:ring-2 focus:ring-[#4d82bc] focus:border-[#4d82bc] font-medium hci-input-focus">
                            @foreach($aniosIngreso as $anio)
                                <option value="{{ $anio }}" {{ $anioIngresoSeleccionado == $anio ? 'selected' : '' }}>
                                    {{ $anio }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
        
        {{-- Indicador de filtro activo --}}
        @if($anioIngresoSeleccionado != $aniosIngreso->first())
            <div class="mb-6 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                    ⚠️ Mostrando módulos del año de ingreso <strong>{{ $anioIngresoSeleccionado }}</strong> (Periodo Pasado)
                </p>
            </div>
        @endif

        <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg shadow p-6 border border-[#c4dafa]">
            @php
                $romanos = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI'];
            @endphp

            @forelse ($magisters as $magister)
                <div class="mb-6 border border-[#c4dafa] rounded-lg shadow-sm bg-[#fcffff] dark:bg-gray-800">
                    {{-- Header clickable con affordance --}}
                    <div
                        class="flex flex-col sm:flex-row sm:justify-between sm:items-center cursor-pointer magister-header 
                                                    bg-[#c4dafa]/30 hover:bg-[#84b6f4]/30 px-4 py-3 rounded-t-lg transition gap-3">
                        <h3 class="text-base sm:text-lg font-semibold text-[#005187] dark:text-[#84b6f4]">
                            Magíster en {{ $magister->nombre }}
                        </h3>
                        <div class="flex items-center justify-between sm:justify-end gap-3">
                            {{-- Botón añadir módulo --}}
                            @if(tieneRol('director_administrativo'))
                            <a href="{{ route('courses.create', ['magister_id' => $magister->id]) }}"
                                class="inline-flex items-center gap-2 bg-[#4d82bc] hover:bg-[#005187] text-white px-3 py-2 rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 text-xs sm:text-sm font-medium"
                                title="Agregar módulo a este programa">
                                <img src="{{ asset('icons/agregar.svg') }}" alt="" class="w-4 h-4">
                                <span class="hidden sm:inline">Agregar Módulo</span>
                                <span class="sm:hidden">Agregar Módulo</span>
                            </a>
                            @endif
                            
                            {{-- Flecha de despliegue --}}
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

                                        <div class="overflow-x-auto">
                                        <table class="w-full table-auto text-sm rounded overflow-hidden shadow-sm">
                                            <thead class="bg-[#c4dafa]/40 dark:bg-gray-700 text-[#005187] dark:text-white">
                                                <tr>
                                                    <th class="px-4 py-2 text-left">Módulo</th>
                                                    <th class="px-4 py-2 text-center w-20">SCT</th>
                                                    <th class="px-4 py-2 text-center w-32">Requisitos</th>
                                                    <th class="px-4 py-2 text-right w-32">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($cursos as $course)
                                                    <tr class="border-b border-gray-200 dark:border-gray-600 
                                                                                   hover:bg-[#e3f2fd] dark:hover:bg-gray-700 
                                                                                   hover:border-l-4 hover:border-l-[#4d82bc]
                                                                                   hover:-translate-y-0.5 hover:shadow-md
                                                                                   transition-all duration-200 group">
                                                        <td class="px-4 py-2 text-[#005187] dark:text-gray-100 group-hover:text-[#4d82bc] dark:group-hover:text-[#84b6f4] transition-colors duration-200 font-medium">
                                                            {{ $course->nombre }}
                                                        </td>
                                                        <td class="px-4 py-2 text-center">
                                                            @if($course->sct)
                                                                <span class="inline-flex items-center justify-center w-8 h-8 text-[#005187] text-sm font-bold rounded-full">
                                                                    {{ $course->sct }}
                                                                </span>
                                                            @else
                                                                <span class="text-gray-400 dark:text-gray-500 text-sm">-</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-4 py-2 text-center">
                                                            @if($course->requisitos)
                                                                @php
                                                                    $requisitosArray = explode(',', $course->requisitos);
                                                                @endphp
                                                                <div class="flex flex-wrap gap-1 justify-center">
                                                                    @foreach($requisitosArray as $req)
                                                                        @if($req == 'ingreso')
                                                                            <span class="inline-flex items-center px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 text-xs font-medium rounded-full">
                                                                                🎓 Ingreso
                                                                            </span>
                                                                        @else
                                                                            @php
                                                                                $cursoReq = $magisters->flatMap->courses->firstWhere('id', $req);
                                                                            @endphp
                                                                            @if($cursoReq)
                                                                                <span class="inline-flex items-center px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 text-xs font-medium rounded-full" title="{{ $cursoReq->nombre }}">
                                                                                    {{ Str::limit($cursoReq->nombre, 20) }}
                                                                                </span>
                                                                            @else
                                                                                <span class="inline-flex items-center px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-full">
                                                                                    ID: {{ $req }}
                                                                                </span>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <span class="text-gray-400 dark:text-gray-500 text-xs">-</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-3 py-2 text-right">
                                                            <div class="flex justify-end items-center gap-2">
                                                                @if(tieneRol('director_administrativo'))
                                                                {{-- Botón Editar --}}
                                                                <x-action-button 
                                                                    variant="edit" 
                                                                    type="link" 
                                                                    :href="route('courses.edit', $course)" 
                                                                    icon="editw.svg"
                                                                    tooltip="Editar módulo" />

                                                                {{-- Botón Eliminar --}}
                                                                <x-action-button 
                                                                    variant="delete" 
                                                                    :formAction="route('courses.destroy', $course)" 
                                                                    formMethod="DELETE" 
                                                                    class="form-eliminar"
                                                                    tooltip="Eliminar módulo" />
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="px-4 py-8">
                                                            <x-empty-state type="no-data" icon="📚" title="No hay módulos registrados"
                                                                message="Crea tu primer módulo para comenzar a gestionar el contenido académico."
                                                                actionText="Crear Módulo" actionUrl="{{ route('courses.create') }}"
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
                                Este magíster aún no tiene módulos registrados.
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





