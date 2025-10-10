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
        ['label' => 'Cursos', 'url' => route('courses.index')],
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

        {{-- Header: acciones + búsqueda --}}
        <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <div class="flex gap-3">
                <a href="{{ route('courses.index') }}"
                    class="hci-button hci-lift hci-focus-ring inline-flex items-center bg-[#4d82bc] hover:bg-[#005187] text-white px-4 py-2 rounded-lg shadow transition-all duration-200"
                    title="Volver a cursos">
                    <img src="{{ asset('icons/back.svg') }}" alt="Volver" class="w-5 h-5">
                </a>

                <a href="{{ route('magisters.create') }}"
                    class="hci-button hci-lift hci-focus-ring inline-flex items-center bg-[#4d82bc] hover:bg-[#005187] text-white px-4 py-2 rounded-lg shadow transition-all duration-200"
                    title="Agregar nuevo programa">
                    <img src="{{ asset('icons/agregar.svg') }}" alt="Agregar" class="w-5 h-5">
                </a>
            </div>

            <form method="GET" class="w-full sm:w-auto">
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
                        ? 'Este programa tiene cursos asociados. ¿Deseas eliminar también esos cursos?'
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
                                    <p><strong class="text-[#005187] dark:text-[#84b6f4]">Encargado:</strong>
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
                                <p><strong class="text-[#005187] dark:text-[#84b6f4]">Cursos asociados:</strong>
                                    {{ $count }}</p>
                            </div>
                        </div>

                        {{-- Acciones --}}
                        <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-2">
                            <div class="flex gap-3">
                                {{-- Botón Editar --}}
                                <a href="{{ route('magisters.edit', $magister) }}"
                                   class="inline-flex items-center justify-center w-12 px-4 py-2.5 bg-[#84b6f4] hover:bg-[#84b6f4]/80 text-white rounded-lg text-xs font-medium transition focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-1"
                                   title="Editar programa">
                                    <img src="{{ asset('icons/editw.svg') }}" alt="Editar" class="w-6 h-6">
                                </a>

                                {{-- Botón Eliminar --}}
                                <form action="{{ route('magisters.destroy', $magister) }}" method="POST" class="inline form-eliminar" data-confirm="{{ $msg }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center justify-center w-12 px-4 py-2.5 bg-[#e57373] hover:bg-[#f28b82] text-white rounded-lg text-xs font-medium transition focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-1"
                                            title="Eliminar programa">
                                        <img src="{{ asset('icons/trashw.svg') }}" alt="Eliminar" class="w-6 h-6">
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <x-empty-state
                    type="no-data"
                    icon="🎓"
                    title="No hay programas de magíster registrados"
                    message="Crea tu primer programa de magíster para organizar los cursos y actividades académicas."
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


