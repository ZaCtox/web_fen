{{-- Detalle de Novedad Pública FEN --}}
@section('title', $novedad->titulo)
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-[#005187] dark:text-[#4d82bc]">
                    {{ $novedad->titulo }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Publicado {{ $novedad->created_at->diffForHumans() }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto px-6">
        {{-- Botón Volver --}}
        <div class="mb-4">
            <a href="{{ route('public.novedades') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#4d82bc] hover:bg-[#005187] text-[#005187] font-semibold rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 transform hover:scale-105"
               title="Volver a novedades">
                <img src="{{ asset('icons/back.svg') }}" alt="Volver" class="w-5 h-5">
            </a>
        </div>

        {{-- Novedad Principal --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-600">
            {{-- Header con icono y tipo --}}
            <div class="p-6 text-center" style="background-color: {{ $novedad->color ?? '#4d82bc' }}22;">
                <span class="text-6xl">{{ $novedad->icono ?? '📰' }}</span>
                <div class="mt-4">
                    <span class="inline-block px-4 py-2 text-sm font-semibold rounded-full" 
                          style="background-color: {{ $novedad->color ?? '#4d82bc' }}; color: white;">
                        {{ ucfirst($novedad->tipo_novedad) }}
                    </span>
                    @if($novedad->es_urgente)
                        <span class="inline-block px-3 py-2 text-sm font-semibold rounded-full bg-red-500 text-white ml-2">
                            🔴 Urgente
                        </span>
                    @endif
                </div>
            </div>

            {{-- Contenido --}}
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4">
                    {{ $novedad->titulo }}
                </h1>
                
                <div class="prose prose-gray dark:prose-invert max-w-none">
                    {!! nl2br(e($novedad->contenido)) !!}
                </div>

                {{-- Información adicional --}}
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-600">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        @if($novedad->magister)
                        <div class="flex items-center">
                            <span class="mr-2 text-lg" role="img" aria-label="Programa">🎓</span>
                            <div>
                                <span class="font-medium text-gray-700 dark:text-gray-300">Programa:</span>
                                <span class="ml-2 text-gray-600 dark:text-gray-400">{{ $novedad->magister->nombre }}</span>
                            </div>
                        </div>
                        @endif
                        
                        @if($novedad->fecha_expiracion && $novedad->fecha_expiracion instanceof \Carbon\Carbon)
                        <div class="flex items-center">
                            <span class="mr-2 text-lg" role="img" aria-label="Fecha de expiración">📅</span>
                            <div>
                                <span class="font-medium text-gray-700 dark:text-gray-300">Válido hasta:</span>
                                <span class="ml-2 text-gray-600 dark:text-gray-400">{{ $novedad->fecha_expiracion->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                        @endif
                        
                        <div class="flex items-center">
                            <span class="mr-2 text-lg" role="img" aria-label="Fecha de publicación">📝</span>
                            <div>
                                <span class="font-medium text-gray-700 dark:text-gray-300">Publicado:</span>
                                <span class="ml-2 text-gray-600 dark:text-gray-400">{{ $novedad->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                        
                        @if($novedad->updated_at != $novedad->created_at)
                        <div class="flex items-center">
                            <span class="mr-2 text-lg" role="img" aria-label="Última actualización">✏️</span>
                            <div>
                                <span class="font-medium text-gray-700 dark:text-gray-300">Actualizado:</span>
                                <span class="ml-2 text-gray-600 dark:text-gray-400">{{ $novedad->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Novedades Relacionadas --}}
        @if($relacionadas->count() > 0)
        <div class="mt-8">
            <h3 class="text-xl font-bold text-[#005187] dark:text-[#4d82bc] mb-4">
                📰 Novedades Relacionadas
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($relacionadas as $relacionada)
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden hover:scale-105 transform transition-all duration-300 border border-gray-200 dark:border-gray-600 flex flex-col">
                    <div class="p-4 text-center" style="background-color: {{ $relacionada->color ?? '#4d82bc' }}22;">
                        <span class="text-3xl">{{ $relacionada->icono ?? '📰' }}</span>
                        <div class="mt-2">
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full" 
                                  style="background-color: {{ $relacionada->color ?? '#4d82bc' }}; color: white;">
                                {{ ucfirst($relacionada->tipo_novedad) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-4 flex-1 flex flex-col">
                        <h4 class="font-bold text-gray-800 dark:text-gray-100 mb-2 text-sm line-clamp-2">
                            {{ $relacionada->titulo }}
                        </h4>
                        <p class="text-xs text-gray-600 dark:text-gray-300 mb-3 line-clamp-2 flex-1">
                            {{ Str::limit($relacionada->contenido, 80) }}
                        </p>
                        
                        <div class="mt-auto">
                            <a href="{{ route('public.novedades.show', $relacionada) }}" 
                               class="block w-full text-center px-3 py-2.5 bg-[#4d82bc] hover:bg-[#005187] text-white font-semibold rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 transform hover:scale-105 text-sm"
                               title="Ver novedad: {{ $relacionada->titulo }}">
                                Ver →
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Footer --}}
    <footer>
        @include('components.footer')
    </footer>
</x-app-layout>



