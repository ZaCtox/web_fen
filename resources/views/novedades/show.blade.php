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

    <div class="p-6 max-w-7xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            
            {{-- Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg p-6 mb-6">
                <div class="flex items-start">
                    <span class="text-5xl mr-4">{{ $novedad->icono ?? '📰' }}</span>
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold mb-2">{{ $novedad->titulo }}</h1>
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/20">
                                {{ ucfirst($novedad->tipo_novedad) }}
                            </span>
                            @if($novedad->es_urgente)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-500">
                                    🔴 URGENTE
                                </span>
                            @endif
                            @if($novedad->visible_publico)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-500">
                                    🌐 Público
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-500">
                                    🔒 Privado
                                </span>
                            @endif
                        </div>
                    </div>
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

            {{-- Información adicional --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                {{-- Estado --}}
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Estado</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Visibilidad:</span>
                            <span class="font-medium">{{ $novedad->visible_publico ? 'Público' : 'Privado' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Tipo:</span>
                            <span class="font-medium">{{ ucfirst($novedad->tipo_novedad) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Urgente:</span>
                            <span class="font-medium">{{ $novedad->es_urgente ? 'Sí' : 'No' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Fechas --}}
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Fechas</h4>
                    <div class="space-y-2 text-sm">
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">Creada:</span>
                            <span class="font-medium ml-2">{{ $novedad->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">Actualizada:</span>
                            <span class="font-medium ml-2">{{ $novedad->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($novedad->fecha_expiracion && $novedad->fecha_expiracion instanceof \Carbon\Carbon)
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Expira:</span>
                                <span class="font-medium ml-2">{{ $novedad->fecha_expiracion->format('d/m/Y H:i') }}</span>
                            </div>
                        @else
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Expiración:</span>
                                <span class="font-medium ml-2">Sin expiración</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Autor --}}
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Autor</h4>
                    <div class="space-y-2 text-sm">
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">Creado por:</span>
                            <span class="font-medium ml-2">{{ $novedad->user->name ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">ID:</span>
                            <span class="font-medium ml-2">{{ $novedad->id }}</span>
                        </div>
                        @if($novedad->magister)
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Programa:</span>
                                <span class="font-medium ml-2">{{ $novedad->magister->nombre }}</span>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- Acciones (Ley de Fitts: Botones grandes y accesibles) --}}
            <div class="hci-form-actions">
                <div class="flex justify-between items-center">
                    {{-- Botón Volver --}}
                    <a href="{{ route('novedades.index') }}"
                        class="hci-button hci-lift hci-focus-ring inline-flex items-center gap-2 bg-[#4d82bc] hover:bg-[#005187] text-white font-medium px-6 py-3 rounded-lg shadow transition-all duration-200"
                        title="Volver a novedades">
                        <img src="{{ asset('icons/back.svg') }}" alt="Volver" class="w-5 h-5">
                    </a>

                    {{-- Botones de acción --}}
                    <div class="flex space-x-2">
                        {{-- Editar --}}
                        <a href="{{ route('novedades.edit', $novedad) }}"
                            class="inline-flex items-center justify-center w-12 px-4 py-2.5 bg-[#84b6f4] hover:bg-[#4d82bc] text-white rounded-lg text-xs font-medium transition focus:outline-none focus:ring-2 focus:ring-[#84b6f4] focus:ring-offset-1"
                            title="Editar novedad">
                            <img src="{{ asset('icons/editw.svg') }}" alt="Editar" class="w-6 h-6">
                        </a>
                        
                        {{-- Duplicar --}}
                        <form method="POST" action="{{ route('novedades.duplicate', $novedad) }}" class="inline">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center justify-center w-12 px-4 py-2.5 bg-[#ffa726] hover:bg-[#ff9800] text-white rounded-lg text-xs font-medium transition focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-1"
                                    title="Duplicar novedad">
                                <img src="{{ asset('icons/duplicate.svg') }}" alt="Duplicar" class="w-6 h-6">
                            </button>
                        </form>
                        
                        {{-- Eliminar --}}
                        <form method="POST" action="{{ route('novedades.destroy', $novedad) }}" 
                              class="form-eliminar inline"
                              data-confirm="¿Estás seguro de que quieres eliminar esta novedad? Esta acción no se puede deshacer.">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center justify-center w-12 px-4 py-2.5 bg-[#e57373] hover:bg-[#f28b82] text-white rounded-lg text-xs font-medium transition focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-1"
                                    title="Eliminar novedad">
                                <img src="{{ asset('icons/trashw.svg') }}" alt="Eliminar" class="w-6 h-6">
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>