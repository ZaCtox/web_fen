{{-- Novedades Públicas FEN --}}
@section('title', 'Novedades y Actividades')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#4d82bc]">
            📰 Novedades y Actividades
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-6">
        {{-- Filtros --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('public.novedades') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Buscador --}}
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        🔍 Buscar
                    </label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Buscar por título o contenido..."
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent dark:bg-gray-700 dark:text-white">
                </div>

                {{-- Tipo --}}
                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        📋 Tipo
                    </label>
                    <select id="tipo" 
                            name="tipo" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <option value="">Todos los tipos</option>
                        @foreach($tipos as $key => $label)
                            <option value="{{ $key }}" {{ request('tipo') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Programa --}}
                <div>
                    <label for="magister_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        🎓 Programa
                    </label>
                    <select id="magister_id" 
                            name="magister_id" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <option value="">Todos los programas</option>
                        @foreach($magisters as $magister)
                            <option value="{{ $magister->id }}" {{ request('magister_id') == $magister->id ? 'selected' : '' }}>
                                {{ $magister->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Botones --}}
                <div class="md:col-span-3 flex gap-2">
                    <button type="submit" 
                            class="px-6 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white font-semibold rounded-lg transition-colors duration-300">
                        🔍 Buscar
                    </button>
                    <a href="{{ route('public.novedades') }}" 
                       class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors duration-300">
                        🗑️ Limpiar
                    </a>
                </div>
            </form>
        </div>

        {{-- Resultados --}}
        @if($novedades->count() > 0)
            {{-- Información de resultados --}}
            <div class="mb-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Mostrando {{ $novedades->count() }} de {{ $novedades->total() }} novedades
                    @if(request()->hasAny(['search', 'tipo', 'magister_id']))
                        para los filtros seleccionados
                    @endif
                </p>
            </div>

            {{-- Grid de Novedades --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($novedades as $novedad)
                <div class="bg-white dark:bg-gray-700 rounded-xl shadow-lg overflow-hidden hover:scale-105 transform transition-all duration-300 border border-gray-200 dark:border-gray-600 flex flex-col">
                    {{-- Header con icono y tipo --}}
                    <div class="p-4 text-center" style="background-color: {{ $novedad->color ?? '#4d82bc' }}22;">
                        <span class="text-4xl">{{ $novedad->icono ?? '📰' }}</span>
                        <div class="mt-2">
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full" 
                                  style="background-color: {{ $novedad->color ?? '#4d82bc' }}; color: white;">
                                {{ ucfirst($novedad->tipo_novedad) }}
                            </span>
                            @if($novedad->es_urgente)
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-red-500 text-white ml-1">
                                    🔴 Urgente
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Contenido --}}
                    <div class="p-4 flex-1 flex flex-col">
                        <h4 class="font-bold text-gray-800 dark:text-gray-100 mb-2 text-base line-clamp-2">
                            {{ $novedad->titulo }}
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3 line-clamp-3 flex-1">
                            {{ Str::limit($novedad->contenido, 120) }}
                        </p>

                        {{-- Información adicional --}}
                        <div class="space-y-2 text-xs text-gray-500 dark:text-gray-400 mb-4">
                            @if($novedad->magister)
                            <div class="flex items-center">
                                <span class="mr-1">🎓</span>
                                <span>{{ Str::limit($novedad->magister->nombre, 25) }}</span>
                            </div>
                            @endif
                            @if($novedad->fecha_expiracion && $novedad->fecha_expiracion instanceof \Carbon\Carbon)
                            <div class="flex items-center">
                                <span class="mr-1">📅</span>
                                <span>Hasta: {{ $novedad->fecha_expiracion->format('d/m/Y') }}</span>
                            </div>
                            @endif
                            <div class="flex items-center text-gray-400">
                                <span class="mr-1">🕐</span>
                                <span>{{ $novedad->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        {{-- Botón Ver Más - Siempre abajo --}}
                        <div class="mt-auto">
                            <a href="{{ route('public.novedades.show', $novedad) }}" 
                               class="block w-full text-center px-4 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white font-semibold rounded-lg transition-colors duration-300">
                                Ver Más →
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Paginación --}}
            @if($novedades->hasPages())
            <div class="mt-8">
                {{ $novedades->links() }}
            </div>
            @endif

        @else
            {{-- Sin resultados --}}
            <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <span class="text-6xl">🔍</span>
                <h3 class="text-xl text-[#005187] dark:text-[#4d82bc] font-semibold mt-4">
                    @if(request()->hasAny(['search', 'tipo', 'magister_id']))
                        No hay archivos que coincidan con tu búsqueda
                    @else
                        No hay novedades disponibles
                    @endif
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">
                    @if(request()->hasAny(['search', 'tipo', 'magister_id']))
                        Intenta ajustar los filtros o buscar con otros términos
                    @else
                        Las novedades se publicarán próximamente
                    @endif
                </p>
                @if(request()->hasAny(['search', 'tipo', 'magister_id']))
                <a href="{{ route('public.novedades') }}" 
                   class="inline-block mt-4 px-6 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white font-semibold rounded-lg transition-colors duration-300">
                    Ver Todas las Novedades
                </a>
                @endif
            </div>
        @endif
    </div>

    {{-- Footer --}}
    <footer>
        @include('components.footer')
    </footer>
</x-app-layout>
