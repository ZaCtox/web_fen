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
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Día</p>
                            <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $clase->dia ?? '—' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-2xl" role="img" aria-label="Reloj">🕐</span>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Horario</p>
                            <p class="text-base font-semibold text-gray-900 dark:text-white">
                                {{ $clase->hora_inicio ? \Carbon\Carbon::parse($clase->hora_inicio)->format('H:i') : '--:--' }}
                                - 
                                {{ $clase->hora_fin ? \Carbon\Carbon::parse($clase->hora_fin)->format('H:i') : '--:--' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-2xl" role="img" aria-label="Edificio">🏫</span>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Sala</p>
                            <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $clase->room?->name ?? 'No asignada' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-2xl" role="img" aria-label="Monitor">💻</span>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Modalidad</p>
                            <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $clase->modality ? ucfirst($clase->modality) : '—' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Columna derecha --}}
                <div class="space-y-4">
                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50">
                        <span class="text-2xl" role="img" aria-label="Tipo">📝</span>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Tipo</p>
                            <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $clase->tipo ? ucfirst($clase->tipo) : '—' }}</p>
                        </div>
                    </div>

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
                        <span class="text-2xl" role="img" aria-label="Persona">👤</span>
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
