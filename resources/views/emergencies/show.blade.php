{{-- Vista de detalle de Emergencia --}}
@section('title', 'Detalle de Emergencia')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Detalle de Emergencia</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Emergencias', 'url' => route('emergencies.index')],
        ['label' => 'Detalle', 'url' => '#']
    ]" />

    <div class="py-6 max-w-7xl mx-auto px-4">
        {{-- Sticky Header con Botones de Acción --}}
        <div class="sticky top-0 z-10 bg-white dark:bg-gray-900 py-4 mb-6 -mx-4 px-4 border-b border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                {{-- Botón Volver --}}
                <a href="{{ route('emergencies.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 text-sm font-medium"
                    aria-label="Volver a emergencias">
                    <img src="{{ asset('icons/back.svg') }}" alt="" class="w-5 h-5">
                </a>

                {{-- Botones de acción --}}
                <div class="flex flex-wrap gap-2">
                    {{-- Editar --}}
                    <a href="{{ route('emergencies.edit', $emergency) }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 text-sm font-medium"
                        title="Editar emergencia"
                        aria-label="Editar emergencia">
                        <img src="{{ asset('icons/editw.svg') }}" alt="" class="w-5 h-5 flex-shrink-0">
                    </a>

                    {{-- Activar/Desactivar (solo si no está expirada) --}}
                    @php
                        $isExpired = $emergency->expires_at && $emergency->expires_at->isPast();
                    @endphp
                    @if(!$isExpired)
                        <form method="POST" action="{{ route('emergencies.toggleActive', $emergency) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="inline-flex items-center justify-center px-4 py-2 {{ $emergency->active ? 'bg-[#ffa726] hover:bg-[#ff9800] focus:ring-orange-400' : 'bg-green-600 hover:bg-green-700 focus:ring-green-500' }} text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 text-sm font-medium"
                                    title="{{ $emergency->active ? 'Desactivar emergencia' : 'Activar emergencia' }}"
                                    aria-label="{{ $emergency->active ? 'Desactivar emergencia' : 'Activar emergencia' }}">
                                <img src="{{ asset($emergency->active ? 'icons/desactivarw.svg' : 'icons/activarw.svg') }}" alt="" class="w-5 h-5 flex-shrink-0">
                            </button>
                        </form>
                    @endif

                    {{-- Eliminar --}}
                    <form method="POST" action="{{ route('emergencies.destroy', $emergency) }}"
                          class="form-eliminar inline"
                          data-confirm="¿Estás seguro de que quieres eliminar esta emergencia? Esta acción no se puede deshacer.">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center justify-center px-4 py-2 bg-[#e57373] hover:bg-[#d32f2f] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 text-sm font-medium"
                                title="Eliminar emergencia"
                                aria-label="Eliminar emergencia">
                            <img src="{{ asset('icons/trashw.svg') }}" alt="" class="w-5 h-5 flex-shrink-0">
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Header Principal --}}
        <div class="bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20 rounded-2xl p-8 mb-6 border border-red-200 dark:border-red-800 shadow-lg">
            <div class="flex flex-col items-center text-center">
                {{-- Icono de Emergencia --}}
                <div class="w-20 h-20 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mb-4 shadow-lg">
                    <span class="text-5xl">🚨</span>
                </div>

                {{-- Título --}}
                <h1 class="text-3xl font-bold text-[#005187] dark:text-[#84b6f4] mb-4">
                    {{ $emergency->title }}
                </h1>

                {{-- Badges --}}
                <div class="flex flex-wrap gap-2 justify-center">
                    {{-- Estado --}}
                    @if($emergency->active && !$isExpired)
                        <span class="inline-flex items-center gap-1 px-4 py-2 rounded-full text-sm font-semibold bg-red-500 text-white shadow-md">
                            🚨 Emergencia Activa
                        </span>
                    @elseif($isExpired)
                        <span class="inline-flex items-center gap-1 px-4 py-2 rounded-full text-sm font-semibold bg-gray-500 text-white shadow-md">
                            ⏰ Expirada
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-4 py-2 rounded-full text-sm font-semibold bg-gray-400 text-white shadow-md">
                            ⏸️ Inactiva
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Mensaje Principal --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="flex items-start gap-3 mb-4">
                <svg class="w-6 h-6 text-[#4d82bc] flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-[#005187] dark:text-[#84b6f4] mb-3">Mensaje de Emergencia</h2>
                    <div class="prose dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-relaxed">{{ $emergency->message }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Metadata en 2 Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            {{-- Card 1: Información General --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-[#005187] dark:text-[#84b6f4] mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Información General
                </h3>
                <div class="space-y-4">
                    {{-- Creada por --}}
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-[#4d82bc] flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Creada por</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $emergency->creator->name ?? 'Sistema' }}</p>
                        </div>
                    </div>

                    {{-- Estado --}}
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-[#4d82bc] flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Estado Actual</p>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                @if($emergency->active && !$isExpired)
                                    <span class="text-red-600">🚨 Activa</span>
                                @elseif($isExpired)
                                    <span class="text-gray-600">⏰ Expirada</span>
                                @else
                                    <span class="text-gray-500">⏸️ Inactiva</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: Fechas --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-[#005187] dark:text-[#84b6f4] mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                    Fechas
                </h3>
                <div class="space-y-4">
                    {{-- Creada --}}
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-[#4d82bc] flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Fecha de Creación</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $emergency->created_at->format('d/m/Y H:i') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $emergency->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    {{-- Expira --}}
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-[#4d82bc] flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Fecha de Expiración</p>
                            @if($emergency->expires_at)
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $emergency->expires_at->format('d/m/Y H:i') }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    @if($isExpired)
                                        Expiró {{ $emergency->expires_at->diffForHumans() }}
                                    @else
                                        Expira {{ $emergency->expires_at->diffForHumans() }}
                                    @endif
                                </p>
                            @else
                                <p class="font-semibold text-gray-900 dark:text-white flex items-center gap-1">
                                    <span>∞</span>
                                    <span>Permanente</span>
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Actualizada --}}
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-[#4d82bc] flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Última Actualización</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $emergency->updated_at->format('d/m/Y H:i') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $emergency->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

