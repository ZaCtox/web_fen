{{-- Inicio.blade.php --}}
@section('title', 'Inicio')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-[#005187] dark:text-[#84b6f4]">
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Bienvenida --}}
            <div class="bg-gradient-to-r from-[#005187] to-[#4d82bc] dark:from-gray-800 dark:to-gray-700 shadow-lg rounded-lg p-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-3xl font-bold mb-2">¡Bienvenido/a, {{ Auth::user()->name }}!</h3>
                        <p class="text-lg opacity-90">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 backdrop-blur-sm">
                                {{ ucfirst(str_replace('_', ' ', Auth::user()->rol)) }}
                            </span>
                        </p>
                        <p class="mt-3 text-sm opacity-75">
                            {{ now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                        </p>
                    </div>
                    <div class="hidden md:block text-6xl">
                        👋
                    </div>
                </div>
            </div>

            {{-- Sección Novedades --}}
            @if($novedades && $novedades->count() > 0)
            <div class="bg-white dark:bg-gray-800 shadow-lg sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-[#005187] dark:text-[#84b6f4] mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                    </svg>
                    Novedades del Sistema
                </h3>
                
                <div class="space-y-3">
                    @foreach($novedades as $novedad)
                        <x-novedad-card :novedad="$novedad" />
                    @endforeach
                </div>
                
                <div class="mt-4 text-center">
                    <a href="{{ route('public.novedades') }}" 
                       class="text-sm text-[#4d82bc] hover:text-[#005187] dark:text-[#84b6f4] dark:hover:text-[#c4dafa] underline hover:no-underline transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 rounded px-2 py-1"
                       title="Ver todas las novedades del sistema">
                        Ver todas las novedades
                    </a>
                </div>
            </div>
            @endif

            {{-- Estadísticas Principales --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Incidencias Pendientes --}}
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 hci-card-hover border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Incidencias Pendientes</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['incidencias_pendientes'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">de {{ $stats['incidencias_totales'] }} totales</p>
                        </div>
                        <div class="text-4xl" role="img" aria-label="Advertencia">⚠️</div>
                    </div>
                    @can('viewAny', App\Models\Incident::class)
                    <a href="{{ route('incidencias.index') }}" 
                       class="mt-4 text-sm text-[#4d82bc] hover:text-[#005187] font-medium inline-flex items-center transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 rounded"
                       title="Ver todas las incidencias">
                        Ver todas
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    @endcan
                </div>

                {{-- Emergencias Activas --}}
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 hci-card-hover border-l-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Emergencias Activas</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['emergencias_activas'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">de {{ $stats['emergencias_totales'] }} totales</p>
                        </div>
                        <div class="text-4xl" role="img" aria-label="Emergencia">🚨</div>
                    </div>
                    @can('viewAny', App\Models\Emergency::class)
                    <a href="{{ route('emergencies.index') }}" 
                       class="mt-4 text-sm text-[#4d82bc] hover:text-[#005187] font-medium inline-flex items-center transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 rounded"
                       title="Ver todas las emergencias">
                        Ver todas
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    @endcan
                </div>

                {{-- Salas Totales --}}
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 hci-card-hover border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Salas Disponibles</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['salas_totales'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">espacios registrados</p>
                        </div>
                        <div class="text-4xl" role="img" aria-label="Edificio">🏫</div>
                    </div>
                    <a href="{{ route('rooms.index') }}" 
                       class="mt-4 text-sm text-[#4d82bc] hover:text-[#005187] font-medium inline-flex items-center transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 rounded"
                       title="Ver todas las salas">
                        Ver todas
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                {{-- Usuarios Totales --}}
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 hci-card-hover border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Usuarios Activos</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['usuarios_totales'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">en el sistema</p>
                        </div>
                        <div class="text-4xl" role="img" aria-label="Usuarios">👥</div>
                    </div>
                    @can('viewAny', App\Models\User::class)
                    <a href="{{ route('usuarios.index') }}" 
                       class="mt-4 text-sm text-[#4d82bc] hover:text-[#005187] font-medium inline-flex items-center transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 rounded"
                       title="Ver todos los usuarios">
                        Ver todos
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    @endcan
                </div>
            </div>

            {{-- Próximas Clases (solo para docentes) --}}
            @if($proximasClases && $proximasClases->isNotEmpty())
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-[#4d82bc]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Mis Próximas Clases
                </h3>
                <div class="space-y-3">
                    @foreach($proximasClases as $clase)
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-[#4d82bc] dark:hover:border-[#84b6f4] transition-all duration-200">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $clase->course->name ?? 'Módulo' }}</h4>
                            <div class="flex items-center mt-2 space-x-4 text-sm text-gray-600 dark:text-gray-400">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $clase->fecha->format('d/m/Y') }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $clase->hora_inicio }} - {{ $clase->hora_fin }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    {{ $clase->room->name ?? 'Sin asignar' }}
                                            </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Actividad Reciente --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Últimas Incidencias --}}
                @can('viewAny', App\Models\Incident::class)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center justify-between">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-[#4d82bc]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            Últimas Incidencias
                        </span>
                        <a href="{{ route('incidencias.index') }}" 
                           class="text-sm text-[#4d82bc] hover:text-[#005187] font-medium focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 rounded px-2 py-1"
                           title="Ver todas las incidencias">Ver todas</a>
                    </h3>
                    @if($actividadReciente['incidencias']->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 text-center py-8">No hay incidencias recientes</p>
                    @else
                        <div class="space-y-3">
                            @foreach($actividadReciente['incidencias'] as $incidencia)
                            <div class="flex items-start p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-[#4d82bc] dark:hover:border-[#84b6f4] transition-all duration-200">
                                <div class="flex-shrink-0 mr-3">
                                    @if($incidencia->estado === 'pendiente')
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-100 dark:bg-yellow-900/30">
                                            <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/30">
                                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $incidencia->titulo }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $incidencia->user->name ?? 'Usuario' }} • {{ $incidencia->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                @endcan

                {{-- Reportes Diarios Recientes --}}
                @can('viewAny', App\Models\DailyReport::class)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center justify-between">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-[#4d82bc]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Reportes Recientes
                        </span>
                        <a href="{{ route('daily-reports.index') }}" 
                           class="text-sm text-[#4d82bc] hover:text-[#005187] font-medium focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 rounded px-2 py-1"
                           title="Ver todos los reportes diarios">Ver todos</a>
                    </h3>
                    @if($actividadReciente['reportes']->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 text-center py-8">No hay reportes recientes</p>
                    @else
                        <div class="space-y-3">
                            @foreach($actividadReciente['reportes'] as $reporte)
                            <div class="flex items-start p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-[#4d82bc] dark:hover:border-[#84b6f4] transition-all duration-200">
                                <div class="flex-shrink-0 mr-3">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30">
                                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $reporte->title }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $reporte->user->name ?? 'Usuario' }} • {{ $reporte->report_date->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                            @endforeach
                    </div>
                @endif
                </div>
                @endcan
            </div>

        </div>
    </div>
</x-app-layout>
