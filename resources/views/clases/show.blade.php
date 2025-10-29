<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#c4dafa]">Detalle de Clase</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Clases', 'url' => route('clases.index')],
        ['label' => 'Detalle de Clase', 'url' => '#']
    ]" />

    {{-- 🎯 Sticky Header con Botones de Acción (solo modo director administrativo) --}}
    @unless(!empty($public) && $public === true)
        <div class="sticky top-0 z-10 bg-white dark:bg-gray-900 py-4 mb-6 border-b border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="max-w-5xl mx-auto px-6">
                <div class="flex items-center justify-between gap-3">
                    {{-- Botón Volver --}}
                    <a href="{{ route('clases.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 text-sm font-medium"
                        aria-label="Volver a la lista de clases">
                        <img src="{{ asset('icons/back.svg') }}" alt="" class="w-5 h-5">
                    </a>

                    {{-- Acciones: Editar + Eliminar --}}
                    @if(false)
                    <div class="flex gap-2">
                        {{-- Editar --}}
                        <a href="{{ route('clases.edit', $clase) }}"
                            class="p-2 bg-[#84b6f4] hover:bg-[#4d82bc] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2"
                            aria-label="Editar clase">
                            <img src="{{ asset('icons/editw.svg') }}" alt="" class="w-5 h-5">
                        </a>

                        {{-- Eliminar --}}
                        <form action="{{ route('clases.destroy', $clase) }}" method="POST" class="inline-flex form-eliminar">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="p-2 bg-[#e57373] hover:bg-[#d32f2f] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2"
                                aria-label="Eliminar clase">
                                <img src="{{ asset('icons/trashw.svg') }}" alt="" class="w-5 h-5">
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    @endunless

    <div class="max-w-5xl mx-auto p-6">
        <div class="rounded-xl shadow-lg p-8 border-l-4 transition-all duration-200 hover:shadow-xl
                    bg-white dark:bg-gray-800"
            style="border-left-color: {{ $clase->course->magister->color ?? '#4d82bc' }}">

            {{-- Header con icono decorativo --}}
            <div class="flex items-start gap-4 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex-shrink-0 w-16 h-16 rounded-full flex items-center justify-center text-3xl"
                     style="background-color: {{ $clase->course->magister->color ?? '#4d82bc' }}22;">
                    📚
                </div>
                <div class="flex-1">
                    <h3 class="text-3xl font-bold text-[#005187] dark:text-[#c4dafa] mb-2">
                {{ $clase->course->nombre }}
            </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $clase->course->magister?->nombre ?? 'Sin Programa Asignado' }}
                    </p>
                </div>
            </div>

            {{-- Datos de la clase en grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                {{-- Columna izquierda --}}
                <div class="space-y-4">
                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-2xl" role="img" aria-label="Calendario">📅</span>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Sesiones</p>
                            <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $clase->sesiones->count() }} {{ $clase->sesiones->count() === 1 ? 'sesión' : 'sesiones' }}</p>
                        </div>
                    </div>

                    @php
                        $dias = $clase->sesiones->pluck('dia')->unique();
                    @endphp
                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-2xl" role="img" aria-label="Días">📆</span>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Días de clase</p>
                            <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $dias->join(', ') ?: '—' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-2xl" role="img" aria-label="Edificio">🏫</span>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Sala Principal</p>
                            <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $clase->room?->name ?? 'No asignada' }}</p>
                        </div>
                    </div>

                    @php
                        $modalidades = $clase->sesiones->pluck('modalidad')->unique();
                    @endphp
                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-2xl" role="img" aria-label="Monitor">💻</span>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Modalidades</p>
                            <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $modalidades->map(fn($m) => ucfirst($m))->join(', ') ?: '—' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Columna derecha --}}
                <div class="space-y-4">
                    @if($clase->sesiones->count() > 0)
                        @php
                            $primeraSesion = $clase->sesiones->first();
                            $ultimaSesion = $clase->sesiones->last();
                        @endphp
                        <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                            <span class="text-2xl" role="img" aria-label="Inicio">🗓️</span>
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Período</p>
                                <p class="text-base font-semibold text-gray-900 dark:text-white">
                                    {{ $primeraSesion->fecha->format('d/m/Y') }} - {{ $ultimaSesion->fecha->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-2xl" role="img" aria-label="Año">📊</span>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Año / Trimestre</p>
                            <p class="text-base font-semibold text-gray-900 dark:text-white">
                                {{ $clase->period?->anio ?? '—' }} / Trimestre {{ $clase->period?->numero ?? '—' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-2xl" role="img" aria-label="Encargado">👨‍🏫</span>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Encargado</p>
                            <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $clase->encargado ?? '—' }}</p>
                        </div>
                    </div>

                @if($clase->url_zoom)
                    <div class="flex items-start gap-3 p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
                        <span class="text-2xl" role="img" aria-label="Zoom">📹</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wide">Enlace Zoom</p>
                        <a href="{{ $clase->url_zoom }}" target="_blank"
                               class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] hover:text-[#005187] dark:hover:text-[#c4dafa] underline break-all transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 rounded"
                               title="Abrir enlace de Zoom">
                            {{ $clase->url_zoom }}
                        </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- ⏰ Horarios y 🎥 Grabaciones lado a lado (modo público) --}}
            @if(!empty($public) && $public === true && $clase->sesiones->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                    {{-- Horarios de Coffee y Lunch (izquierda) --}}
                    <div class="p-4 rounded-lg bg-gradient-to-r from-orange-50 to-amber-50 dark:from-orange-900/20 dark:to-amber-900/20 border-2 border-orange-200 dark:border-orange-800">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="text-3xl" role="img" aria-label="Horarios">⏰</span>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white">Horarios de Sesiones</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Coffee & Lunch Breaks</p>
                            </div>
                        </div>
                        <div class="space-y-3 max-h-60 overflow-y-auto">
                            @foreach($clase->sesiones as $sesion)
                                <div class="p-3 bg-white dark:bg-gray-800 rounded-lg border border-orange-200 dark:border-orange-700">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                                            <span class="text-lg">📅</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ \Carbon\Carbon::parse($sesion->fecha)->locale('es')->isoFormat('dddd, D [de] MMMM') }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="ml-13 space-y-2 text-sm">
                                        {{-- Mostrar breaks simples si existen --}}
                                        @if($sesion->coffee_break_inicio || $sesion->lunch_break_inicio)
                                            {{-- Horario principal de clase --}}
                                            <div class="flex items-center gap-2">
                                                <span class="text-blue-600 dark:text-blue-400">📚</span>
                                                <span class="text-gray-700 dark:text-gray-300 font-medium">
                                                    <strong>{{ substr($sesion->hora_inicio, 0, 5) }} - {{ substr($sesion->hora_fin, 0, 5) }}</strong> Clase
                                                </span>
                                            </div>
                                            
                                            {{-- Coffee Break --}}
                                            @if($sesion->coffee_break_inicio && $sesion->coffee_break_fin)
                                                <div class="flex items-center gap-2">
                                                    <span class="text-amber-600 dark:text-amber-400">☕</span>
                                                    <span class="text-gray-600 dark:text-gray-400 italic">
                                                        {{ substr($sesion->coffee_break_inicio, 0, 5) }} - {{ substr($sesion->coffee_break_fin, 0, 5) }} Coffee Break
                                                    </span>
                                                </div>
                                            @endif
                                            
                                            {{-- Lunch Break --}}
                                            @if($sesion->lunch_break_inicio && $sesion->lunch_break_fin)
                                                <div class="flex items-center gap-2">
                                                    <span class="text-orange-600 dark:text-orange-400">🍽️</span>
                                                    <span class="text-gray-600 dark:text-gray-400 italic">
                                                        {{ substr($sesion->lunch_break_inicio, 0, 5) }} - {{ substr($sesion->lunch_break_fin, 0, 5) }} Lunch Break
                                                    </span>
                                                </div>
                                            @endif
                                        {{-- Horario tradicional sin breaks --}}
                                        @elseif($sesion->hora_inicio && $sesion->hora_fin)
                                            <div class="flex items-center gap-2">
                                                <span class="text-blue-600 dark:text-blue-400">📚</span>
                                                <span class="text-gray-700 dark:text-gray-300 font-medium">
                                                    <strong>{{ substr($sesion->hora_inicio, 0, 5) }} - {{ substr($sesion->hora_fin, 0, 5) }}</strong>
                                                    @if($sesion->modalidad)
                                                        <span class="ml-2 px-2 py-0.5 text-xs rounded bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                                            {{ ucfirst($sesion->modalidad) }}
                                                        </span>
                                                    @endif
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    {{-- Grabaciones (derecha) --}}
                    <div class="p-4 rounded-lg bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 border-2 border-red-200 dark:border-red-800">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="text-3xl" role="img" aria-label="Grabaciones">🎥</span>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white">Grabaciones Disponibles</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $clase->sesiones->count() }} {{ $clase->sesiones->count() === 1 ? 'sesión' : 'sesiones' }} grabada(s)</p>
                            </div>
                        </div>
                        <div class="space-y-2 max-h-60 overflow-y-auto">
                            @foreach($clase->sesiones as $sesion)
                                <a href="{{ $sesion->url_grabacion }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg border border-red-200 dark:border-red-700 hover:shadow-md hover:border-red-400 dark:hover:border-red-500 transition-all duration-200 group">
                                    <div class="flex items-center gap-3 flex-1">
                                        <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">
                                                Sesión del {{ $sesion->fecha->format('d/m/Y') }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($sesion->fecha)->locale('es')->isoFormat('dddd') }} • Click para ver en YouTube
                                            </p>
                                        </div>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- 📅 Sesiones de la Clase --}}
            @unless(!empty($public) && $public === true)
                <div class="mt-8 pt-8 border-t-2 border-gray-200 dark:border-gray-700" x-data="{ 
                    showModal: false, 
                    modalMode: 'add',
                    editingSesion: null
                }">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-2xl font-bold text-[#005187] dark:text-[#c4dafa] flex items-center gap-3">
                            <span class="text-3xl" role="img" aria-label="Calendario">📅</span>
                            Sesiones de la Clase
                        </h4>
                        <button @click="showModal = true; modalMode = 'add'; editingSesion = null"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-[#3ba55d] hover:bg-[#2d864a] text-white font-medium rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#3ba55d] focus:ring-offset-2 text-sm hci-button-ripple hci-glow"
                                title="Agregar nueva sesión">
                            <img src="{{ asset('icons/agregar.svg') }}" alt="" class="w-5 h-5">
                            <span>Nueva Sesión</span>
                        </button>
                    </div>

                    {{-- Lista de sesiones --}}
                    @if($clase->sesiones->count() > 0)
                        <div class="space-y-3">
                            @foreach($clase->sesiones as $sesion)
                                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center gap-4">
                                        <div class="flex-shrink-0 w-16 h-16 rounded-lg flex flex-col items-center justify-center text-xs font-bold
                                                    {{ $sesion->es_hoy ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300' }}">
                                            <span class="text-2xl">{{ $sesion->fecha->format('d') }}</span>
                                            <span class="uppercase">{{ \Carbon\Carbon::parse($sesion->fecha)->locale('es')->isoFormat('MMM') }}</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900 dark:text-white">
                                                {{ \Carbon\Carbon::parse($sesion->fecha)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                                            </p>
                                            
                                            {{-- Mostrar bloques horarios si existen --}}
                                            @if($sesion->tiene_bloques)
                                                <div class="mt-2 space-y-1 text-sm">
                                                    @foreach($sesion->bloques_horarios as $bloque)
                                                        <div class="flex items-center gap-2">
                                                            @if(($bloque['tipo'] ?? '') === 'clase')
                                                                <span class="text-blue-600 dark:text-blue-400">📚</span>
                                                                <span class="text-gray-700 dark:text-gray-300">
                                                                    <strong>{{ $bloque['inicio'] ?? '' }} - {{ $bloque['fin'] ?? '' }}</strong>
                                                                    @if(!empty($bloque['nombre']))
                                                                        <span class="ml-1">{{ $bloque['nombre'] }}</span>
                                                                    @endif
                                                                </span>
                                                            @elseif(($bloque['tipo'] ?? '') === 'coffee_break')
                                                                <span class="text-amber-600 dark:text-amber-400">☕</span>
                                                                <span class="text-gray-600 dark:text-gray-400 italic">
                                                                    {{ $bloque['inicio'] ?? '' }} - {{ $bloque['fin'] ?? '' }} Coffee Break
                                                                </span>
                                                            @elseif(($bloque['tipo'] ?? '') === 'lunch_break')
                                                                <span class="text-orange-600 dark:text-orange-400">🍽️</span>
                                                                <span class="text-gray-600 dark:text-gray-400 italic">
                                                                    {{ $bloque['inicio'] ?? '' }} - {{ $bloque['fin'] ?? '' }} Lunch Break
                                                                </span>
                                                            @else
                                                                <span class="text-gray-500">⏰</span>
                                                                <span class="text-gray-600 dark:text-gray-400">
                                                                    {{ $bloque['inicio'] ?? '' }} - {{ $bloque['fin'] ?? '' }}
                                                                    @if(!empty($bloque['nombre']))
                                                                        <span class="ml-1">{{ $bloque['nombre'] }}</span>
                                                                    @endif
                                                                </span>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            {{-- Mostrar breaks simples si existen --}}
                                            @elseif($sesion->coffee_break_inicio || $sesion->lunch_break_inicio)
                                                <div class="mt-2 space-y-2 text-sm">
                                                    {{-- Horario principal de clase --}}
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-blue-600 dark:text-blue-400">📚</span>
                                                        <span class="text-gray-700 dark:text-gray-300 font-medium">
                                                            <strong>{{ substr($sesion->hora_inicio, 0, 5) }} - {{ substr($sesion->hora_fin, 0, 5) }}</strong> Clase
                                                        </span>
                                                    </div>
                                                    
                                                    {{-- Coffee Break --}}
                                                    @if($sesion->coffee_break_inicio && $sesion->coffee_break_fin)
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-amber-600 dark:text-amber-400">☕</span>
                                                            <span class="text-gray-600 dark:text-gray-400 italic">
                                                                {{ substr($sesion->coffee_break_inicio, 0, 5) }} - {{ substr($sesion->coffee_break_fin, 0, 5) }} Coffee Break
                                                            </span>
                                                        </div>
                                                    @endif
                                                    
                                                    {{-- Lunch Break --}}
                                                    @if($sesion->lunch_break_inicio && $sesion->lunch_break_fin)
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-orange-600 dark:text-orange-400">🍽️</span>
                                                            <span class="text-gray-600 dark:text-gray-400 italic">
                                                                {{ substr($sesion->lunch_break_inicio, 0, 5) }} - {{ substr($sesion->lunch_break_fin, 0, 5) }} Lunch Break
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @elseif($sesion->hora_inicio && $sesion->hora_fin)
                                                {{-- Modo tradicional sin bloques --}}
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                    <span class="font-medium">⏰ {{ substr($sesion->hora_inicio, 0, 5) }} - {{ substr($sesion->hora_fin, 0, 5) }}</span>
                                                    @if($sesion->modalidad)
                                                        <span class="ml-2 px-2 py-0.5 text-xs rounded bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                                            {{ ucfirst($sesion->modalidad) }}
                                                        </span>
                                                    @endif
                                                </p>
                                            @endif

                                            @if($sesion->observaciones)
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $sesion->observaciones }}</p>
                                            @endif
                                            <div class="mt-2">
                                                {!! $sesion->estado_badge !!}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- Botones debajo de la información --}}
                                    <div class="flex items-center justify-between gap-2 mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                                        {{-- Botón de grabación a la izquierda --}}
                                        @if($sesion->tiene_grabacion)
                                            <a href="{{ $sesion->url_grabacion }}" target="_blank"
                                               class="inline-flex items-center gap-2 px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2"
                                               title="Ver grabación en YouTube">
                                                <img src="{{ asset('icons/play.svg') }}" alt="Ver" class="w-4 h-4">
                                                <span class="hidden sm:inline">Ver Grabación</span>
                                            </a>
                                        @elseif($sesion->es_pasada)
                                            <button @click="showModal = true; modalMode = 'grabacion'; editingSesion = {{ $sesion->id }}"
                                                    class="inline-flex items-center gap-2 px-3 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white text-sm font-medium rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2"
                                                    title="Agregar grabación">
                                                <img src="{{ asset('icons/agregar.svg') }}" alt="Subir" class="w-4 h-4">
                                                <span class="hidden sm:inline">Agregar Grabación</span>
                                            </button>
                                        @else
                                            <div></div>
                                        @endif
                                        
                                        {{-- Botones de acción a la derecha --}}
                                        @if(false)
                                            <div class="flex gap-2">
                                                <button @click="showModal = true; modalMode = 'edit'; editingSesion = {{ $sesion->id }}"
                                                        class="p-2 bg-[#84b6f4] hover:bg-[#4d82bc] text-white rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2"
                                                        title="Editar sesión">
                                                    <img src="{{ asset('icons/editw.svg') }}" alt="Editar" class="w-5 h-5">
                                                </button>
                                                <form action="{{ route('sesiones.destroy', $sesion) }}" method="POST" class="inline-flex form-eliminar"
                                                      data-confirm="¿Estás seguro de que quieres eliminar esta sesión?">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="p-2 bg-[#e57373] hover:bg-[#d32f2f] text-white rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2"
                                                            title="Eliminar sesión">
                                                        <img src="{{ asset('icons/trashw.svg') }}" alt="Eliminar" class="w-5 h-5">
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50 dark:bg-gray-700/50 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600">
                            <span class="text-6xl" role="img" aria-label="Sin sesiones">📭</span>
                            <p class="mt-4 text-gray-600 dark:text-gray-400 font-medium">No hay sesiones registradas</p>
                            <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Agrega sesiones usando el botón "Nueva Sesión"</p>
                        </div>
                @endif

                    {{-- Modal para agregar/editar sesión --}}
                    @include('clases.partials.sesion-modal')
                </div>
            @endunless

            {{-- 🟢 Modo público: Botón Volver al Calendario --}}
            @if(!empty($public) && $public === true)
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex justify-start">
                    <a href="{{ url('/Calendario-Academico') }}"
                        class="inline-flex items-center gap-2 px-5 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white font-medium rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2"
                        title="Volver al calendario">
                        <img src="{{ asset('icons/back.svg') }}" alt="Volver" class="w-5 h-5">
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>



