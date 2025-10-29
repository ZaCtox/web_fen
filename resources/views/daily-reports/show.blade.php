@section('title', 'Ver Reporte Diario')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
            Ver Reporte Diario
        </h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Reportes Diarios', 'url' => route('daily-reports.index')],
        ['label' => 'Ver Reporte', 'url' => '#']
    ]" />

    <div class="p-6 max-w-5xl mx-auto">
        {{-- Botones de acción superiores (sticky) --}}
        <div class="sticky top-0 z-10 bg-white dark:bg-gray-900 py-4 mb-6 -mx-6 px-6 border-b border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                {{-- Botón Volver --}}
                <a href="{{ route('daily-reports.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 text-sm font-medium"
                    aria-label="Volver a reportes diarios">
                    <img src="{{ asset('icons/back.svg') }}" alt="" class="w-5 h-5">
                </a>

                {{-- Botones de acción --}}
                <div class="flex flex-wrap gap-2">                    
                    @if(false)
                    {{-- Editar --}}
                    <a href="{{ route('daily-reports.edit', $dailyReport) }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 text-sm font-medium"
                        title="Editar reporte"
                        aria-label="Editar reporte">
                        <img src="{{ asset('icons/editw.svg') }}" alt="" class="w-5 h-5 flex-shrink-0">
                    </a>
                    @endif
                    
                    @if($dailyReport->tiene_pdf)
                    <a href="{{ route('daily-reports.download', $dailyReport) }}"
                       class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 text-sm font-medium"
                       title="Descargar PDF"
                       aria-label="Descargar PDF">
                        <img src="{{ asset('icons/download.svg') }}" alt="" class="w-5 h-5 flex-shrink-0">
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            {{-- Header limpio con icono centrado --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-[#4d82bc]/20 to-[#84b6f4]/10 border-4 border-[#84b6f4] mb-4 shadow-lg">
                    <svg class="w-12 h-12 text-[#4d82bc]" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-[#005187] dark:text-[#84b6f4] mb-4">{{ $dailyReport->title }}</h1>
                
                {{-- Badges --}}
                <div class="flex flex-wrap gap-2 justify-center">
                    {{-- Fecha --}}
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-[#4d82bc] text-white shadow-sm">
                        📅 {{ $dailyReport->fecha_formateada }}
                    </span>
                    
                    {{-- Entradas --}}
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-blue-500 text-white shadow-sm">
                        📝 {{ $dailyReport->numero_entradas }} {{ $dailyReport->numero_entradas === 1 ? 'entrada' : 'entradas' }}
                    </span>
                    
                    {{-- Creado por --}}
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-green-500 text-white shadow-sm">
                        👤 {{ $dailyReport->user->name }}
                    </span>
                </div>
            </div>

            {{-- Resumen General --}}
            @if($dailyReport->summary)
            <div class="mb-6">
                <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">Resumen General</h3>
                <div class="bg-gradient-to-br from-[#4d82bc]/5 to-[#84b6f4]/5 dark:from-[#4d82bc]/10 dark:to-[#84b6f4]/10 rounded-lg p-6 border border-[#84b6f4]/30">
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap text-base leading-relaxed">{{ $dailyReport->summary }}</p>
                </div>
            </div>
            @endif

            {{-- Entradas del Reporte --}}
            <div class="mb-6">
                <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">Observaciones del Día</h3>
                
                <div class="space-y-4">
                    @foreach($dailyReport->entries as $index => $entry)
                    <div class="bg-gradient-to-br from-[#4d82bc]/5 to-[#84b6f4]/5 dark:from-[#4d82bc]/10 dark:to-[#84b6f4]/10 rounded-lg p-6 border border-[#84b6f4]/30 hover:shadow-lg transition-all duration-200">
                        <div class="flex items-start gap-4 mb-4">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-[#4d82bc] text-white font-bold text-lg shadow-md">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <h5 class="font-bold text-[#005187] dark:text-[#84b6f4] text-lg mb-2">
                                    {{ $entry->location_type }}
                                    @if($entry->room)
                                        - {{ $entry->room->name }}
                                    @elseif($entry->location_detail)
                                        - {{ $entry->location_detail }}
                                    @endif
                                </h5>
                                
                                {{-- Información adicional de la bitácora --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                                    @if($entry->hora)
                                    <div class="bg-white dark:bg-gray-700 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                                        <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Horario</div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $entry->hora }}</div>
                                    </div>
                                    @endif
                                    
                                    @if($entry->escala)
                                    <div class="bg-white dark:bg-gray-700 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                                        <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Escala</div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold text-white" style="background-color: {{ $entry->color_escala }};">
                                                {{ $entry->escala }}
                                            </div>
                                            @if($entry->escala <= 2)
                                                <img src="{{ asset('icons/normal.svg') }}" class="w-5 h-5" alt="Normal">
                                            @elseif($entry->escala <= 4)
                                                <img src="{{ asset('icons/leve.svg') }}" class="w-5 h-5" alt="Leve">
                                            @elseif($entry->escala <= 6)
                                                <img src="{{ asset('icons/moderado.svg') }}" class="w-5 h-5" alt="Moderado">
                                            @elseif($entry->escala <= 8)
                                                <img src="{{ asset('icons/fuerte.svg') }}" class="w-5 h-5" alt="Fuerte">
                                            @else
                                                <img src="{{ asset('icons/critico.svg') }}" class="w-5 h-5" alt="Crítico">
                                            @endif
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $entry->nivel_severidad }}</span>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($entry->programa)
                                    <div class="bg-white dark:bg-gray-700 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                                        <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Programa</div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $entry->programa }}</div>
                                    </div>
                                    @endif
                                    
                                    @if($entry->area)
                                    <div class="bg-white dark:bg-gray-700 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                                        <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Área</div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $entry->area }}</div>
                                    </div>
                                    @endif
                                </div>
                                
                                <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4">{{ $entry->observation }}</p>
                                
                                @if($entry->tarea)
                                <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 border border-yellow-200 dark:border-yellow-800">
                                    <div class="text-sm font-semibold text-yellow-800 dark:text-yellow-200 mb-2">Tarea:</div>
                                    <p class="text-sm text-yellow-700 dark:text-yellow-300">{{ $entry->tarea }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        @if($entry->photo_url)
                        <div class="mt-4 pl-14">
                            <img src="{{ $entry->photo_url }}" 
                                 alt="Evidencia fotográfica" 
                                 class="max-w-full h-auto rounded-lg shadow-md cursor-pointer hover:scale-105 transition-transform duration-200"
                                 onclick="window.open('{{ $entry->photo_url }}', '_blank')">
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Metadata --}}
            <div class="mt-6 p-4 bg-[#fcffff] dark:bg-gray-800 rounded-lg border border-[#84b6f4]/20">
                <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
                    <strong>Generado el {{ now()->format('d/m/Y H:i') }}</strong> | Sistema de Gestión FEN
                </p>
            </div>
        </div>
    </div>
</x-app-layout>

