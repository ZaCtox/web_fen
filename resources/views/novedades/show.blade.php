@section('title', 'Detalle de Novedad')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">📰 {{ $novedad->titulo }}</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Gestión de Novedades', 'url' => route('novedades.index')],
        ['label' => 'Detalle de Novedad', 'url' => '#']
    ]" />

    <div class="p-6 max-w-5xl mx-auto">
        {{-- Botones de acción superiores (sticky) --}}
        <div class="sticky top-0 z-10 bg-white dark:bg-gray-900 py-4 mb-6 -mx-6 px-6 border-b border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                {{-- Botón Volver --}}
                <a href="{{ route('novedades.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 text-sm font-medium"
                    aria-label="Volver a novedades">
                    <img src="{{ asset('icons/back.svg') }}" alt="" class="w-5 h-5">
                </a>

                {{-- Botones de acción --}}
                <div class="flex flex-wrap gap-2">
                    {{-- Ver en Público (solo si es visible públicamente) --}}
                    @if($novedad->visible_publico)
                        <a href="{{ url('Novedades-FEN/' . $novedad->id) }}"
                           target="_blank"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 text-sm font-medium"
                            aria-label="Ver en sitio público">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"/>
                                <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"/>
                            </svg>
                        </a>
                    @endif

                    {{-- Editar --}}
                    <a href="{{ route('novedades.edit', $novedad) }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 text-sm font-medium"
                        title="Editar novedad"
                        aria-label="Editar novedad">
                        <img src="{{ asset('icons/editw.svg') }}" alt="" class="w-5 h-5 flex-shrink-0">
                    </a>
                    
                    {{-- Duplicar --}}
                    <form method="POST" action="{{ route('novedades.duplicate', $novedad) }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center justify-center px-4 py-2 bg-[#ffa726] hover:bg-[#ff9800] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-2 text-sm font-medium"
                                title="Duplicar novedad"
                                aria-label="Duplicar novedad">
                            <img src="{{ asset('icons/duplicate.svg') }}" alt="" class="w-5 h-5 flex-shrink-0">
                        </button>
                    </form>
                    
                    {{-- Eliminar --}}
                    <form method="POST" action="{{ route('novedades.destroy', $novedad) }}" 
                          class="form-eliminar inline"
                          data-confirm="¿Estás seguro de que quieres eliminar esta novedad? Esta acción no se puede deshacer.">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center justify-center px-4 py-2 bg-[#e57373] hover:bg-[#d32f2f] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 text-sm font-medium"
                                title="Eliminar novedad"
                                aria-label="Eliminar novedad">
                            <img src="{{ asset('icons/trashw.svg') }}" alt="" class="w-5 h-5 flex-shrink-0">
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            
            {{-- Header limpio con icono centrado --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-[#4d82bc]/20 to-[#84b6f4]/10 border-4 border-[#84b6f4] mb-4 shadow-lg">
                    <span class="text-6xl">{{ $novedad->icono ?? '📰' }}</span>
                </div>
                <h1 class="text-3xl font-bold text-[#005187] dark:text-[#84b6f4] mb-4">{{ $novedad->titulo }}</h1>
                
                {{-- Badges --}}
                <div class="flex flex-wrap gap-2 justify-center">
                    {{-- Tipo --}}
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-[#4d82bc] text-white shadow-sm">
                        {{ ucfirst($novedad->tipo_novedad) }}
                    </span>
                    
                    {{-- Urgente --}}
                    @if($novedad->es_urgente)
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-red-500 text-white shadow-sm">
                            🔴 URGENTE
                        </span>
                    @endif
                    
                    {{-- Visibilidad --}}
                    @if($novedad->visible_publico)
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-green-500 text-white shadow-sm">
                            🌐 Público
                        </span>
                    @else
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-gray-500 text-white shadow-sm">
                            🔒 Privado
                        </span>
                    @endif

                    {{-- Estado de expiración --}}
                    @if($novedad->fecha_expiracion && $novedad->fecha_expiracion instanceof \Carbon\Carbon && $novedad->fecha_expiracion->isPast())
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-yellow-500 text-white shadow-sm">
                            ⏱️ Expirada
                        </span>
                    @endif
                </div>
            </div>

            {{-- Contenido --}}
            <div class="mb-6">
                <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">Contenido</h3>
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap text-base leading-relaxed">{{ $novedad->contenido }}</p>
                </div>
            </div>

            {{-- Imagen si existe --}}
            @if($novedad->imagen)
                <div class="mb-6">
                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">Imagen</h3>
                    <div class="flex justify-center">
                        <img src="{{ $novedad->imagen }}" 
                             alt="{{ $novedad->titulo }}" 
                             class="max-w-full h-auto rounded-lg shadow-lg border border-gray-300">
                    </div>
                </div>
            @endif

            {{-- Metadata con iconos --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                
                {{-- Información General --}}
                <div class="bg-gradient-to-br from-[#4d82bc]/5 to-[#84b6f4]/5 dark:from-[#4d82bc]/10 dark:to-[#84b6f4]/10 rounded-lg p-5 border border-[#84b6f4]/30">
                    <h4 class="font-bold text-[#005187] dark:text-[#84b6f4] mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        Información General
                    </h4>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                </svg>
                                Tipo:
                            </span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ ucfirst($novedad->tipo_novedad) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                </svg>
                                Visibilidad:
                            </span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $novedad->visible_publico ? '🌐 Público' : '🔒 Privado' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Urgente:
                            </span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $novedad->es_urgente ? '🔴 Sí' : 'No' }}</span>
                        </div>
                        @if($novedad->magister)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                    </svg>
                                    Programa:
                                </span>
                                <span class="font-semibold text-gray-900 dark:text-white text-right">{{ Str::limit($novedad->magister->nombre, 20) }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Fechas --}}
                <div class="bg-gradient-to-br from-purple-50 to-blue-50 dark:from-purple-900/10 dark:to-blue-900/10 rounded-lg p-5 border border-purple-200/30 dark:border-purple-700/30">
                    <h4 class="font-bold text-[#005187] dark:text-[#84b6f4] mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        Fechas
                    </h4>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                Creada:
                            </span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $novedad->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                </svg>
                                Actualizada:
                            </span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $novedad->updated_at->diffForHumans() }}</span>
                        </div>
                        @if($novedad->fecha_expiracion && $novedad->fecha_expiracion instanceof \Carbon\Carbon)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    Expira:
                                </span>
                                <span class="font-semibold {{ $novedad->fecha_expiracion->isPast() ? 'text-yellow-600 dark:text-yellow-400' : 'text-gray-900 dark:text-white' }}">
                                    {{ $novedad->fecha_expiracion->format('d/m/Y H:i') }}
                                </span>
                            </div>
                        @else
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Expiración:
                                </span>
                                <span class="font-semibold text-green-600 dark:text-green-400">♾️ Permanente</span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                Autor:
                            </span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $novedad->user->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>


