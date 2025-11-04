{{-- Inicio de Programas.blade.php --}}
@section('title', 'Programas')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-[#005187] dark:text-[#84b6f4]">
            Listado de Programas
        </h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Módulos', 'url' => route('courses.index')],
        ['label' => 'Programas', 'url' => '#']
    ]" />

    {{-- Metas para toasts --}}
    @if(session('success'))
        <meta name="session-success" content="{{ session('success') }}">
    @endif

    @if(session('error'))
        <meta name="session-error" content="{{ session('error') }}">
    @endif

    <div class="py-6 max-w-5xl mx-auto px-4 space-y-6" x-data="{ 
        q: '{{ request('q', '') }}',
        get hayResultados() {
            if (this.q.trim() === '') return true;
            const programas = document.querySelectorAll('[data-magister-nombre]');
            return Array.from(programas).some(p => 
                p.dataset.magisterNombre.toLowerCase().includes(this.q.toLowerCase())
            );
        }
    }">

        {{-- Filtro de año de ingreso --}}
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg border border-blue-200 dark:border-blue-800 shadow-md p-4">
            <form method="GET" action="{{ route('magisters.index') }}" class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <div>
                    <label for="anio_ingreso" class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4] mb-2">Año de Ingreso:</label>
                    <select name="anio_ingreso" 
                            id="anio_ingreso"
                            onchange="this.form.submit()"
                            class="w-full sm:w-64 rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-700 text-[#005187] dark:text-[#84b6f4] px-4 py-2.5 focus:ring-[#4d82bc] focus:border-[#4d82bc] font-medium">
                        @foreach($aniosIngreso as $anio)
                            <option value="{{ $anio }}" {{ $anioIngresoSeleccionado == $anio ? 'selected' : '' }}>
                                {{ $anio }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif
            </form>
        </div>

        {{-- Indicador de año de ingreso --}}
        @if($anioIngresoSeleccionado != $aniosIngreso->first())
            <div class="mb-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                    ⚠️ Mostrando programas de un Año de Ingreso Anterior
                </p>
            </div>
        @endif

        {{-- Header: acciones + búsqueda --}}
        <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <div class="flex gap-3">
                {{-- Botón Volver (Izquierda) --}}
                <a href="{{ route('courses.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 text-sm font-medium hci-button-ripple hci-glow">
                    <img src="{{ asset('icons/back.svg') }}" alt="" class="w-5 h-5">
                </a>

                {{-- Botón Agregar (Al lado del Volver) --}}
                @if(false)
                <a href="{{ route('magisters.create') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 text-sm font-medium hci-button-ripple hci-glow">
                    <img src="{{ asset('icons/agregar.svg') }}" alt="" class="w-5 h-5">
                    <span>Agregar Programa</span>
                </a>
                @endif
            </div>

            <form method="GET" class="w-full sm:w-auto">
                @if(request('anio_ingreso'))
                    <input type="hidden" name="anio_ingreso" value="{{ request('anio_ingreso') }}">
                @endif
                <input name="q" x-model="q" placeholder="Buscar por nombre…"
                    class="w-full sm:w-64 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
            </form>
        </div>

        {{-- Empty state para búsqueda sin resultados --}}
        <template x-if="!hayResultados && q.trim() !== ''">
            <div>
                <x-empty-state
                    type="no-results"
                    icon="🔍"
                    title="No se encontraron programas"
                    message="Intenta con otros términos de búsqueda o verifica la ortografía."
                    secondaryActionText="Limpiar Búsqueda"
                    secondaryActionUrl="{{ route('magisters.index') }}"
                    secondaryActionIcon="🔄"
                />
            </div>
        </template>

        {{-- Listado --}}
        <div class="space-y-4" x-show="hayResultados">
            @forelse ($magisters as $magister)
                @php
                    $count = $magister->courses_count ?? 0;
                    $hasCourses = $count > 0;
                    $msg = $hasCourses
                        ? 'Este programa tiene módulos asociados. ¿Deseas eliminar también esos módulos?'
                        : '¿Eliminar este programa?';
                @endphp

                <div class="p-5 rounded-xl shadow-md bg-white dark:bg-gray-900 border-l-4 transition hover:shadow-lg"
                    style="border-left-color: {{ $magister->color ?? '#999' }};"
                    data-magister-nombre="{{ Str::lower($magister->nombre) }}"
                    x-show="'{{ Str::lower($magister->nombre) }}'.includes(q.toLowerCase())" x-cloak>

                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">

                        {{-- Info principal --}}
                        <div class="space-y-2">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                                <span class="text-[#005187] dark:text-[#84b6f4]">Magíster:</span> {{ $magister->nombre }}
                            </h3>
                            <div class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                                @if ($magister->encargado)
                                    <p><strong class="text-[#005187] dark:text-[#84b6f4]">Directo de programa:</strong>
                                        {{ $magister->encargado }}</p>
                                @endif
                                @if ($magister->asistente)
                                    <p><strong class="text-[#005187] dark:text-[#84b6f4]">Asistente:</strong>
                                        {{ $magister->asistente }}</p>
                                @endif
                                @if ($magister->telefono)
                                    <p><strong class="text-[#005187] dark:text-[#84b6f4]">Teléfono:</strong>
                                        {{ $magister->telefono }}</p>
                                @endif
                                @if ($magister->anexo)
                                    <p><strong class="text-[#005187] dark:text-[#84b6f4]">Anexo:</strong>
                                        {{ $magister->anexo }}</p>
                                @endif
                                @if ($magister->correo)
                                    <p><strong class="text-[#005187] dark:text-[#84b6f4]">Correo:</strong>
                                        {{ $magister->correo }}</p>
                                @endif
                                <p><strong class="text-[#005187] dark:text-[#84b6f4]">Módulos asociados:</strong>
                                    {{ $count }}</p>
                                <p><strong class="text-[#005187] dark:text-[#84b6f4]">Total SCT:</strong>
                                    @if($magister->courses_sum_sct)
                                            {{ $magister->courses_sum_sct }}
                                        
                                        <span class="text-sm text-gray-500 ml-1">créditos</span>
                                    @else
                                        <span class="text-gray-400 text-sm">Sin créditos definidos</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- Acciones --}}
                        <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-2">
                            @if(tieneRol('director_administrativo'))
                            @if(tieneRol('director_administrativo'))
                            <div class="flex gap-3">
                                {{-- Botón Editar --}}
                                <a href="{{ route('magisters.edit', $magister) }}"
                                   class="inline-flex items-center justify-center w-12 px-4 py-2.5 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg text-xs font-medium transition focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-1"
                                   title="Editar programa">
                                    <img src="{{ asset('icons/editw.svg') }}" alt="Editar" class="w-6 h-6">
                                </a>

                                {{-- Botón Eliminar --}}
                                <form action="{{ route('magisters.destroy', $magister) }}" method="POST" class="inline form-eliminar" data-confirm="{{ $msg }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center justify-center w-12 px-4 py-2.5 bg-[#e57373] hover:bg-[#d32f2f] text-white rounded-lg text-xs font-medium transition focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-1"
                                            title="Eliminar programa">
                                        <img src="{{ asset('icons/trashw.svg') }}" alt="Eliminar" class="w-6 h-6">
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <x-empty-state
                    type="no-data"
                    icon="🎓"
                    title="No hay programas de magíster registrados"
                    message="Crea tu primer programa de magíster para organizar los módulos y actividades académicas."
                    actionText="Crear Programa"
                    actionUrl="{{ route('magisters.create') }}"
                    actionIcon="➕"
                />
            @endforelse
        </div>

        {{-- Paginación --}}
        @if(method_exists($magisters, 'links'))
            <div class="pt-4">{{ $magisters->links() }}</div>
        @endif
    </div>
</x-app-layout>
