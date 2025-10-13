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
                        <span class="text-2xl" role="img" aria-label="Profesor">👨‍🏫</span>
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

                    {{-- 🎥 Grabaciones disponibles (modo público) --}}
                    @if(!empty($public) && $public === true && $clase->sesiones->count() > 0)
                        <div class="col-span-2">
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
                </div>
            </div>

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
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-center gap-4 flex-1">
                                        <div class="flex-shrink-0 w-16 h-16 rounded-lg flex flex-col items-center justify-center text-xs font-bold
                                                    {{ $sesion->es_hoy ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300' }}">
                                            <span class="text-2xl">{{ $sesion->fecha->format('d') }}</span>
                                            <span class="uppercase">{{ \Carbon\Carbon::parse($sesion->fecha)->locale('es')->isoFormat('MMM') }}</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900 dark:text-white">
                                                {{ \Carbon\Carbon::parse($sesion->fecha)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                                            </p>
                                            @if($sesion->observaciones)
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $sesion->observaciones }}</p>
                                            @endif
                                            <div class="mt-2">
                                                {!! $sesion->estado_badge !!}
                                            </div>
                                        </div>
                                        @if($sesion->tiene_grabacion)
                                            <a href="{{ $sesion->url_grabacion }}" target="_blank"
                                               class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2"
                                               title="Ver grabación en YouTube">
                                                <img src="{{ asset('icons/play.svg') }}" alt="Ver" class="w-5 h-5">
                                                Ver Grabación
                                            </a>
                                        @elseif($sesion->es_pasada)
                                            <button @click="showModal = true; modalMode = 'grabacion'; editingSesion = {{ $sesion->id }}"
                                                    class="inline-flex items-center gap-2 px-4 py-2 bg-[#84b6f4] hover:bg-[#4d82bc] text-white font-medium rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#84b6f4] focus:ring-offset-2"
                                                    title="Agregar grabación">
                                                <img src="{{ asset('icons/agregar.svg') }}" alt="Subir" class="w-5 h-5">
                                                Agregar Grabación
                                            </button>
                                        @endif
                                    </div>
                                    <div class="flex gap-2 ml-4">
                                        <button @click="showModal = true; modalMode = 'edit'; editingSesion = {{ $sesion->id }}"
                                                class="p-2 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2"
                                                title="Editar sesión">
                                            <img src="{{ asset('icons/editw.svg') }}" alt="Editar" class="w-5 h-5">
                                        </button>
                                        <form action="{{ route('sesiones.destroy', $sesion) }}" method="POST" class="inline-flex form-eliminar"
                                              data-confirm="¿Estás seguro de que quieres eliminar esta sesión?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="p-2 bg-[#e57373] hover:bg-[#f28b82] text-white rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2"
                                                    title="Eliminar sesión">
                                                <img src="{{ asset('icons/trashw.svg') }}" alt="Eliminar" class="w-5 h-5">
                                            </button>
                                        </form>
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

            {{-- 🔒 Botonera interna --}}
            @unless(!empty($public) && $public === true)
                <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">

                    {{-- Volver (a la izquierda) --}}
                    <a href="{{ route('clases.index') }}"
                        class="inline-flex items-center px-5 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white font-medium rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2"
                        title="Volver a la lista de clases">
                        <img src="{{ asset('icons/back.svg') }}" alt="Volver" class="w-5 h-5">
                    </a>

                    {{-- Editar + Eliminar (a la derecha) --}}
                    <div class="flex gap-3">
                        {{-- Editar --}}
                        <a href="{{ route('clases.edit', $clase) }}"
                            class="inline-flex items-center px-3 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white font-medium rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2"
                            title="Editar clase">
                            <img src="{{ asset('icons/editw.svg') }}" alt="Editar" class="w-6 h-6">
                        </a>

                        {{-- Eliminar --}}
                        <form action="{{ route('clases.destroy', $clase) }}" method="POST" class="inline-flex form-eliminar">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center justify-center px-3 py-1 bg-[#e57373] hover:bg-[#f28b82] text-white rounded-lg text-xs font-medium transition w-full sm:w-auto focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2"
                                title="Eliminar clase">
                                <img src="{{ asset('icons/trashw.svg') }}" alt="Eliminar" class="w-5 h-5">
                            </button>
                        </form>
                    </div>
                </div>
            @else
                {{-- 🟢 Modo público --}}
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex justify-start">
                    <a href="{{ url('/Calendario-Academico') }}"
                        class="inline-flex items-center px-5 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white font-medium rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2"
                        title="Volver al calendario">
                        <img src="{{ asset('icons/back.svg') }}" alt="Volver" class="w-5 h-5">
                    </a>
                </div>
            @endunless
        </div>
    </div>
</x-app-layout>



