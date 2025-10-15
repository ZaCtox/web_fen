{{-- Estadísticas de Incidencias.blade.php --}}
@section('title', 'Estadísticas')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#005187] dark:text-[#c4dafa] leading-tight">
            Estadísticas de Incidencias
        </h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Incidencias', 'url' => route('incidencias.index')],
        ['label' => 'Estadísticas', 'url' => '#']
    ]" />

    <div class="py-6 space-y-8">

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            {{-- Botón Volver --}}
            <div class="mb-6">
                <a href="{{ route('incidencias.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg shadow-md transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 text-sm font-medium"
                   aria-label="Volver a incidencias">
                    <img src="{{ asset('icons/back.svg') }}" alt="" class="w-5 h-5">
                </a>
            </div>

            {{-- Filtros --}}
            <form method="GET" id="form-filtros" x-data="{
            estado: '{{ request('estado') }}',
            sala: '{{ request('room_id') }}',
            programa: '{{ request('magister_id') }}',
            anioIngreso: '{{ $anioIngresoSeleccionado }}',
            anio: '{{ request('anio') }}',
            trimestre: '{{ request('trimestre') }}',
            historico: {{ request()->filled('historico') ? 'true' : 'false' }},
            periodos: @js($periodos),
            get periodosFiltrados() {
                if (!this.anio) return this.periodos;
                return this.periodos.filter(p => {
                    const year = new Date(p.fecha_inicio).getFullYear();
                    return year == this.anio;
                });
            },
            actualizarURL() {
                // Si cambió el año de ingreso, limpiar filtros de año y trimestre
                if (this.anioIngreso !== '{{ $anioIngresoSeleccionado }}') {
                    this.anio = '';
                    this.trimestre = '';
                }
                
                // Si se activa histórico, limpiar trimestre
                if (this.historico) {
                    this.trimestre = '';
                }
                
                // Actualizar opciones del select de año
                this.toggleAnioOptions();
                
                const params = new URLSearchParams(window.location.search);
                this.anioIngreso ? params.set('anio_ingreso', this.anioIngreso) : params.delete('anio_ingreso');
                this.estado ? params.set('estado', this.estado) : params.delete('estado');
                this.sala ? params.set('room_id', this.sala) : params.delete('room_id');
                this.programa ? params.set('magister_id', this.programa) : params.delete('magister_id');
                this.anio ? params.set('anio', this.anio) : params.delete('anio');
                this.trimestre ? params.set('trimestre', this.trimestre) : params.delete('trimestre');
                this.historico ? params.set('historico', '1') : params.delete('historico');
                window.location.search = params.toString();
            },
            
            toggleAnioOptions() {
                const anioSelect = document.getElementById('anio-select');
                const trimestreDiv = document.getElementById('trimestre-div');
                
                if (!anioSelect) return;
                
                const aniosNormales = anioSelect.querySelectorAll('.anio-normal');
                const aniosHistoricos = anioSelect.querySelectorAll('.anio-historico');
                
                if (this.historico) {
                    // Mostrar años históricos, ocultar normales y trimestre
                    aniosNormales.forEach(option => option.style.display = 'none');
                    aniosHistoricos.forEach(option => option.style.display = 'block');
                    if (trimestreDiv) trimestreDiv.style.display = 'none';
                } else {
                    // Mostrar años normales, ocultar históricos y mostrar trimestre
                    aniosNormales.forEach(option => option.style.display = 'block');
                    aniosHistoricos.forEach(option => option.style.display = 'none');
                    if (trimestreDiv) trimestreDiv.style.display = 'block';
                }
            }
        }" x-init="toggleAnioOptions()">
                {{-- Filtro de programa y año de ingreso --}}
                <div class="mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg border border-blue-200 dark:border-blue-800 shadow-md p-4">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center gap-4">
                        {{-- Programa --}}
                        <div>
                            <label for="programa" class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4] mb-2">Programa:</label>
                            <select x-model="programa" @change="actualizarURL"
                                class="w-full sm:w-80 rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-700 text-[#005187] dark:text-[#84b6f4] px-4 py-2.5 focus:ring-[#4d82bc] focus:border-[#4d82bc] transition font-medium text-base">
                                <option value="">Todos</option>
                                @foreach ($magisters as $m)
                                    <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Año de Ingreso --}}
                        <div>
                            <label for="anio_ingreso" class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4] mb-2">Año de Ingreso:</label>
                            <select x-model="anioIngreso" 
                                    @change="actualizarURL()"
                                    id="anio_ingreso"
                                    class="w-full sm:w-64 rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-700 text-[#005187] dark:text-[#84b6f4] px-4 py-2.5 focus:ring-[#4d82bc] focus:border-[#4d82bc] font-medium">
                                @foreach($aniosIngreso as $anio)
                                    <option value="{{ $anio }}">
                                        {{ $anio }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Filtros adicionales --}}
                <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                    <div class="flex flex-wrap items-end gap-4">
                        {{-- Estado --}}
                        <div>
                            <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Estado
                            </label>
                            <select x-model="estado" 
                                    @change="actualizarURL()"
                                    id="estado"
                                    class="px-4 py-2.5 pr-10 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition text-sm font-medium min-w-[140px]"
                                    aria-label="Filtrar por estado">
                                <option value="">Todos</option>
                                <option value="pendiente">Pendientes</option>
                                <option value="en_revision">En Revisión</option>
                                <option value="resuelta">Resueltas</option>
                                <option value="no_resuelta">No Resueltas</option>
                            </select>
                        </div>

                        {{-- Sala --}}
                        <div>
                            <label for="sala" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Sala
                            </label>
                            <select x-model="sala" 
                                    @change="actualizarURL()"
                                    id="sala"
                                    class="px-4 py-2.5 pr-10 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition text-sm font-medium min-w-[140px]"
                                    aria-label="Filtrar por sala">
                                <option value="">Todas</option>
                                @foreach ($salas as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Año Académico --}}
                        <div>
                            <label for="anio-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Año Académico
                            </label>
                            <select x-model="anio" 
                                    @change="actualizarURL()" 
                                    id="anio-select"
                                    class="px-4 py-2.5 pr-10 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition text-sm font-medium min-w-[140px]"
                                    aria-label="Filtrar por año académico">
                                <option value="">Todos</option>
                                @foreach ($anios as $a)
                                    <option value="{{ $a }}" class="anio-normal">{{ $a }}</option>
                                @endforeach
                                @foreach ($aniosHistoricos as $a)
                                    <option value="{{ $a }}" class="anio-historico" style="display: none;">{{ $a }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Trimestre --}}
                        <div id="trimestre-div">
                            <label for="trimestre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Trimestre
                            </label>
                            <select x-model="trimestre" 
                                    @change="actualizarURL()"
                                    id="trimestre"
                                    class="px-4 py-2.5 pr-10 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition text-sm font-medium min-w-[140px]"
                                    aria-label="Filtrar por trimestre">
                                <option value="">Todos</option>
                                <template x-for="p in periodosFiltrados" :key="p.id">
                                    <option :value="p.numero" x-text="'Trimestre ' + p.numero" :selected="trimestre == p.numero">
                                    </option>
                                </template>
                            </select>
                        </div>

                        {{-- Histórico --}}
                        <div class="flex items-end">
                            <label class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <input type="checkbox" 
                                       x-model="historico" 
                                       @change="actualizarURL()" 
                                       id="historico"
                                       class="rounded border-gray-300 dark:border-gray-600 text-[#4d82bc] focus:ring-[#4d82bc]">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Histórico</span>
                            </label>
                        </div>
                        
                        {{-- Limpiar --}}
                        <button type="button" 
                                @click="estado = ''; sala = ''; programa = ''; anio = ''; trimestre = ''; historico = false; anioIngreso = '{{ $aniosIngreso->first() }}'; actualizarURL();"
                                class="p-3 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 hover:scale-105 hci-button-ripple hci-glow"
                                title="Limpiar filtros"
                                aria-label="Limpiar filtros">
                            <img src="{{ asset('icons/filterw.svg') }}" alt="" class="w-5 h-5">
                        </button>
                    </div>
                </div>
            </form>

            {{-- Indicador de año de ingreso --}}
            @if($anioIngresoSeleccionado != $aniosIngreso->first())
                <div class="mb-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                    <p class="text-sm text-yellow-800 dark:text-yellow-200">
                        ⚠️ Mostrando estadísticas de un Año de Ingreso Anterior
                    </p>
                </div>
            @endif

            {{-- Mensaje histórico --}}
            @if(request('historico'))
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 px-4 py-3 rounded mb-4 text-sm">
                    Mostrando solo incidencias fuera de los períodos académicos actuales (modo histórico).
                </div>
            @endif

            {{-- KPIs --}}
            @php
                $totalInc = ($porEstado->sum()) ?? 0;
                $pendientes = $porEstado->get('Pendiente', $porEstado->get('pendiente', 0)) ?? 0;
                $resueltas = $porEstado->get('Resuelta', $porEstado->get('resuelta', 0)) ?? 0;
                $pctResueltas = $totalInc > 0 ? round(($resueltas / $totalInc) * 100) : 0;
                
                // Nuevas métricas de tiempo de respuesta
                $incidenciasResueltas = $incidenciasFiltradas->where('estado', 'resuelta')->whereNotNull('resuelta_en');
                $tiempoPromedioResolucion = $incidenciasResueltas->avg(function($inc) {
                    return $inc->created_at->diffInHours($inc->resuelta_en);
                });
                $tiempoPromedioResolucion = $tiempoPromedioResolucion ? round($tiempoPromedioResolucion, 1) : 0;
                
                // Tiempo promedio por estado
                $tiempoPorEstado = [];
                foreach (['pendiente', 'en_revision', 'resuelta'] as $estado) {
                    $incidenciasEstado = $incidenciasFiltradas->where('estado', $estado);
                    if ($estado === 'resuelta') {
                        $incidenciasEstado = $incidenciasEstado->whereNotNull('resuelta_en');
                    }
                    $tiempoPromedio = $incidenciasEstado->avg(function($inc) {
                        if ($inc->estado === 'resuelta' && $inc->resuelta_en) {
                            return $inc->created_at->diffInHours($inc->resuelta_en);
                        }
                        return $inc->created_at->diffInHours(now());
                    });
                    $tiempoPorEstado[$estado] = $tiempoPromedio ? round($tiempoPromedio, 1) : 0;
                }
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                {{-- Total --}}
                <a href="{{ route('incidencias.index') }}" 
                   class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:scale-105 transition-all duration-200 cursor-pointer"
                   title="Ver todas las incidencias">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total</p>
                            <p class="text-3xl font-bold text-[#005187] dark:text-[#84b6f4]">{{ $totalInc }}</p>
                        </div>
                        <div class="w-14 h-14 bg-[#4d82bc]/10 rounded-full flex items-center justify-center">
                            <span class="text-3xl">📊</span>
                        </div>
                    </div>
                </a>

                {{-- Pendientes --}}
                <a href="{{ route('incidencias.index', ['estado' => 'pendiente']) }}" 
                   class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:scale-105 transition-all duration-200 cursor-pointer"
                   title="Ver incidencias pendientes">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Pendientes</p>
                            <p class="text-3xl font-bold text-orange-600">{{ $pendientes }}</p>
                        </div>
                        <div class="w-14 h-14 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center">
                            <span class="text-3xl">⏳</span>
                        </div>
                    </div>
                </a>

                {{-- % Resueltas --}}
                <a href="{{ route('incidencias.index', ['estado' => 'resuelta']) }}" 
                   class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:scale-105 transition-all duration-200 cursor-pointer"
                   title="Ver incidencias resueltas">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">% Resueltas</p>
                            <p class="text-3xl font-bold text-green-600">{{ $pctResueltas }}%</p>
                        </div>
                        <div class="w-14 h-14 bg-green-100 dark:bg-green-900/20 rounded-full flex items-center justify-center">
                            <span class="text-3xl">✅</span>
                        </div>
                    </div>
                </a>

                {{-- Tiempo Promedio de Resolución --}}
                <a href="{{ route('incidencias.index', ['estado' => 'resuelta']) }}" 
                   class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:scale-105 transition-all duration-200 cursor-pointer"
                   title="Ver incidencias resueltas">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Tiempo Promedio</p>
                            <p class="text-3xl font-bold text-purple-600">{{ $tiempoPromedioResolucion }}<span class="text-lg">h</span></p>
                        </div>
                        <div class="w-14 h-14 bg-purple-100 dark:bg-purple-900/20 rounded-full flex items-center justify-center">
                            <span class="text-3xl">⏱️</span>
                        </div>
                    </div>
                </a>

                {{-- Tiempo en Pendiente --}}
                <a href="{{ route('incidencias.index', ['estado' => 'pendiente']) }}" 
                   class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:scale-105 transition-all duration-200 cursor-pointer"
                   title="Ver incidencias pendientes">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Tiempo Pendiente</p>
                            <p class="text-3xl font-bold text-orange-500">{{ $tiempoPorEstado['pendiente'] }}<span class="text-lg">h</span></p>
                        </div>
                        <div class="w-14 h-14 bg-orange-100 dark:bg-orange-900/20 rounded-full flex items-center justify-center">
                            <span class="text-3xl">⏸️</span>
                        </div>
                    </div>
                </a>

                {{-- Tiempo en Revisión --}}
                <a href="{{ route('incidencias.index', ['estado' => 'en_revision']) }}" 
                   class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:scale-105 transition-all duration-200 cursor-pointer"
                   title="Ver incidencias en revisión">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Tiempo Revisión</p>
                            <p class="text-3xl font-bold text-cyan-600">{{ $tiempoPorEstado['en_revision'] }}<span class="text-lg">h</span></p>
                        </div>
                        <div class="w-14 h-14 bg-cyan-100 dark:bg-cyan-900/20 rounded-full flex items-center justify-center">
                            <span class="text-3xl">🔍</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- Gráficos --}}
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Por sala --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Incidencias por Sala</h3>
                        <button id="dlSala" 
                                class="inline-flex items-center justify-center w-12 px-4 py-2.5 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg text-xs font-medium transition focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-1"
                                title="Descargar gráfico">
                            <img src="{{ asset('icons/download.svg') }}" class="w-6 h-6" alt="Descargar">
                        </button>
                    </div>
                    <canvas id="chartSala" height="220" style="max-height:350px;"></canvas>
                </div>

                {{-- Por estado --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Estado de Incidencias</h3>
                        <button id="dlEstado" 
                                class="inline-flex items-center justify-center w-12 px-4 py-2.5 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg text-xs font-medium transition focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-1"
                                title="Descargar gráfico">
                            <img src="{{ asset('icons/download.svg') }}" class="w-6 h-6" alt="Descargar">
                        </button>
                    </div>
                    <canvas id="chartEstado" height="220" style="max-height:350px;"></canvas>
                </div>
            </div>

            {{-- Por trimestre --}}
            @if(!request('historico'))
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Incidencias por Trimestre y Año</h3>
                        <button id="dlTrimestre" 
                                class="inline-flex items-center justify-center w-12 px-4 py-2.5 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg text-xs font-medium transition focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-1"
                                title="Descargar gráfico">
                            <img src="{{ asset('icons/download.svg') }}" class="w-6 h-6" alt="Descargar">
                        </button>
                    </div>
                    <canvas id="chartTrimestre" height="140" style="max-height:300px;"></canvas>
                </div>
            @endif

            {{-- Tiempos de Respuesta --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Tiempos de Respuesta por Estado</h3>
                    <button id="dlTiempos" 
                            class="inline-flex items-center justify-center w-12 px-4 py-2.5 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg text-xs font-medium transition focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-1"
                            title="Descargar gráfico">
                        <img src="{{ asset('icons/download.svg') }}" class="w-6 h-6" alt="Descargar">
                    </button>
                </div>
                <canvas id="chartTiempos" height="140" style="max-height:300px;"></canvas>
            </div>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const porSalaLabels = @json($porSala->keys());
            const porSalaValues = @json($porSala->values());
            const porEstadoLabels = @json($porEstado->keys());
            const porEstadoValues = @json($porEstado->values());
            @if(!request('historico'))
                const porTrimLabels = @json($porTrimestre->keys());
                const porTrimValues = @json($porTrimestre->values());
            @endif

            const isDark = () => document.documentElement.classList.contains('dark');
            const axisColor = () => isDark() ? '#d1d5db' : '#374151';
            const gridColor = () => isDark() ? 'rgba(229,231,235,0.1)' : 'rgba(209,213,219,0.4)';

            const charts = {};

            // Sala
            charts.sala = new Chart(document.getElementById('chartSala'), {
                type: 'bar',
                data: {
                    labels: porSalaLabels,
                    datasets: [{ label: 'Incidencias', data: porSalaValues, backgroundColor: 'rgba(59,130,246,0.7)', borderColor: 'rgba(59,130,246,1)', borderWidth: 1, maxBarThickness: 46 }]
                },
                options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ display:false }, tooltip:{ callbacks:{ label: ctx => ` ${ctx.parsed.y} incidencia(s)` } } }, scales:{ x:{ ticks:{ color:axisColor() }, grid:{ color:gridColor() } }, y:{ beginAtZero:true, ticks:{ color:axisColor() }, grid:{ color:gridColor() } } } }
            });

            // Estado
            const estadoColors = ['#fbbf24', '#10b981', '#ef4444', '#60a5fa', '#a78bfa'].slice(0, porEstadoLabels.length);
            charts.estado = new Chart(document.getElementById('chartEstado'), {
                type: 'doughnut',
                data: { labels: porEstadoLabels.map(label => label.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase())), datasets:[{ data: porEstadoValues, backgroundColor: estadoColors }] },
                options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ display:true, position:'bottom', labels:{ color:axisColor(), usePointStyle:true, pointStyle:'circle', generateLabels: chart => chart.data.labels.map((label,i)=>({ text:label, fillStyle:estadoColors[i], strokeStyle:estadoColors[i], index:i })) } }, tooltip:{ callbacks:{ label: ctx => { const total = porEstadoValues.reduce((a,b)=>a+b,0)||1; const value = ctx.parsed; const pct = Math.round((value/total)*100); return ` ${ctx.label}: ${value} (${pct}%)`; } } } }, cutout:'60%' } 
            });

            @if(!request('historico'))
                charts.trim = new Chart(document.getElementById('chartTrimestre'), {
                    type:'line',
                    data:{ labels:porTrimLabels, datasets:[{ label:'Incidencias', data:porTrimValues, fill:false, borderColor:'rgba(37,99,235,1)', backgroundColor:'rgba(37,99,235,0.5)', tension:0.3, pointRadius:4, pointHoverRadius:6 }] },
                    options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{ labels:{ color:axisColor() } } }, scales:{ x:{ ticks:{ color:axisColor() }, grid:{ color:gridColor() } }, y:{ beginAtZero:true, ticks:{ color:axisColor() }, grid:{ color:gridColor() } } } }
                });
            @endif

            // Gráfico de Tiempos de Respuesta
            const tiemposLabels = ['Pendiente', 'En Revisión', 'Resuelta'];
            const tiemposValues = [{{ $tiempoPorEstado['pendiente'] }}, {{ $tiempoPorEstado['en_revision'] }}, {{ $tiempoPromedioResolucion }}];
            const tiemposColors = ['#f59e0b', '#06b6d4', '#8b5cf6'];
            
            charts.tiempos = new Chart(document.getElementById('chartTiempos'), {
                type: 'bar',
                data: {
                    labels: tiemposLabels,
                    datasets: [{
                        label: 'Tiempo (horas)',
                        data: tiemposValues,
                        backgroundColor: tiemposColors.map(color => color + '80'),
                        borderColor: tiemposColors,
                        borderWidth: 2,
                        maxBarThickness: 60
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => ` ${ctx.parsed.y} horas`
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: axisColor() },
                            grid: { color: gridColor() }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: { color: axisColor() },
                            grid: { color: gridColor() }
                        }
                    }
                }
            });

            const observer = new MutationObserver(() => {
                Object.values(charts).forEach(ch => { if (!ch?.options) return; if (ch.options.scales){ if(ch.options.scales.x){ ch.options.scales.x.ticks.color=axisColor(); ch.options.scales.x.grid.color=gridColor(); } if(ch.options.scales.y){ ch.options.scales.y.ticks.color=axisColor(); ch.options.scales.y.grid.color=gridColor(); } } if(ch.options.plugins?.legend?.labels){ ch.options.plugins.legend.labels.color=axisColor(); } ch.update('none'); });
            });
            observer.observe(document.documentElement,{ attributes:true, attributeFilter:['class'] });

            function wireDownload(btnId, chart) {
                document.getElementById(btnId)?.addEventListener('click', () => {
                    const a=document.createElement('a'); a.href=chart.toBase64Image(); a.download=`${btnId}.png`; a.click();
                });
            }
            wireDownload('dlSala', charts.sala);
            wireDownload('dlEstado', charts.estado);
            @if(!request('historico')) wireDownload('dlTrimestre', charts.trim); @endif
            wireDownload('dlTiempos', charts.tiempos);
        });
    </script>
</x-app-layout>



