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

        {{-- Volver --}}
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 mb-6">
            <a href="{{ route('incidencias.index') }}"
               class="hci-button hci-lift hci-focus-ring inline-flex items-center gap-2 bg-[#4d82bc] hover:bg-[#005187] text-white font-medium px-4 py-2 rounded-lg shadow transition-all duration-200 min-h-[48px]">
                <img src="{{ asset('icons/back.svg') }}" alt="Volver" class="w-4 h-4">
            </a>
        </div>

        {{-- Filtros --}}
        <form method="GET" id="form-filtros" class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">

                {{-- Estado --}}
                <div>
                    <label class="text-sm font-semibold text-[#005187]">Estado:</label>
                    <select name="estado"
                        class="w-full rounded-lg border border-[#84b6f4] bg-[#fcffff] text-[#005187] px-2 py-2 focus:ring-[#4d82bc] focus:border-[#4d82bc]">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendientes</option>
                        <option value="en_revision" {{ request('estado') == 'en_revision' ? 'selected' : '' }}>Revisión</option>
                        <option value="resuelta" {{ request('estado') == 'resuelta' ? 'selected' : '' }}>Resueltas</option>
                        <option value="no_resuelta" {{ request('estado') == 'no_resuelta' ? 'selected' : '' }}>No resueltas</option>
                    </select>
                </div>

                {{-- Sala --}}
                <div>
                    <label class="text-sm font-semibold text-[#005187]">Sala:</label>
                    <select name="room_id"
                        class="w-full rounded-lg border border-[#84b6f4] bg-[#fcffff] text-[#005187] px-2 py-2 focus:ring-[#4d82bc] focus:border-[#4d82bc]">
                        <option value="">Todas</option>
                        @foreach ($salas as $s)
                            <option value="{{ $s->id }}" {{ request('room_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Año --}}
                <div>
                    <label class="text-sm font-semibold text-[#005187]">Año:</label>
                    <select name="anio"
                        class="w-full rounded-lg border border-[#84b6f4] bg-[#fcffff] text-[#005187] px-2 py-2 focus:ring-[#4d82bc] focus:border-[#4d82bc]">
                        <option value="">Todos</option>
                        @foreach ($anios as $a)
                            <option value="{{ $a }}" {{ request('anio') == $a ? 'selected' : '' }}>{{ $a }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Trimestre --}}
                <div>
                    <label class="text-sm font-semibold text-[#005187]">Trimestre:</label>
                    <select name="trimestre"
                        class="w-full rounded-lg border border-[#84b6f4] bg-[#fcffff] text-[#005187] px-2 py-2 focus:ring-[#4d82bc] focus:border-[#4d82bc]">
                        <option value="">Todos</option>
                        <option value="1" {{ request('trimestre') == '1' ? 'selected' : '' }}>1</option>
                        <option value="2" {{ request('trimestre') == '2' ? 'selected' : '' }}>2</option>
                        <option value="3" {{ request('trimestre') == '3' ? 'selected' : '' }}>3</option>
                    </select>
                </div>

                {{-- Histórico --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="historico" id="historico"
                           class="rounded border-[#84b6f4] text-[#4d82bc] focus:ring-[#4d82bc]" {{ request('historico') ? 'checked' : '' }}>
                    <label for="historico" class="text-sm font-semibold text-[#005187]">Registros Históricos</label>
                </div>

                {{-- Botones --}}
                <div class="sm:col-span-5 flex gap-2 mt-2">
                    <button type="submit"
                            class="hci-button hci-lift hci-focus-ring flex-w bg-[#4d82bc] hover:bg-[#005187] text-white font-bold py-2 px-4 rounded-lg shadow transition-all duration-200 justify-center min-h-[48px]">
                        Aplicar
                    </button>
                    <a href="{{ route('incidencias.estadisticas') }}"
                       class="hci-button hci-lift hci-focus-ring flex justify-center items-center bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow transition-all duration-200 min-h-[48px]">
                        <img src="{{ asset('icons/filtro.svg') }}" class="w-4 h-4" alt="Filtro">
                    </a>
                </div>

            </div>
        </form>

        {{-- Mensaje histórico --}}
        @if(request('historico'))
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div class="rounded border border-yellow-300 bg-yellow-50 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200 px-4 py-2 text-sm">
                    Mostrando solo incidencias fuera de los períodos definidos (modo histórico).
                </div>
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
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                {{-- Total --}}
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 flex items-center gap-4 hover:scale-105 transition-all duration-200 cursor-pointer">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                        <img src="{{ asset('icons/estadistica.svg') }}" class="w-6 h-6" alt="Total">
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total incidencias</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalInc }}</p>
                    </div>
                </div>

                {{-- Pendientes --}}
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 flex items-center gap-4 hover:scale-105 transition-all duration-200 cursor-pointer">
                    <div class="p-3 bg-amber-100 dark:bg-amber-900/30 rounded-full">
                        <img src="{{ asset('icons/clock.svg') }}" class="w-6 h-6" alt="Pendientes">
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pendientes</p>
                        <p class="text-2xl font-bold text-amber-600">{{ $pendientes }}</p>
                    </div>
                </div>

                {{-- % Resueltas --}}
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 flex items-center gap-4 hover:scale-105 transition-all duration-200 cursor-pointer">
                    <div class="p-3 bg-emerald-100 dark:bg-emerald-900/30 rounded-full">
                        <img src="{{ asset('icons/check.svg') }}" class="w-6 h-6" alt="Resueltas">
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">% Resueltas</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ $pctResueltas }}%</p>
                    </div>
                </div>

                {{-- Tiempo Promedio de Resolución --}}
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 flex items-center gap-4 hover:scale-105 transition-all duration-200 cursor-pointer">
                    <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-full">
                        <img src="{{ asset('icons/clock.svg') }}" class="w-6 h-6" alt="Tiempo">
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tiempo Promedio</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $tiempoPromedioResolucion }}h</p>
                    </div>
                </div>

                {{-- Tiempo en Pendiente --}}
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 flex items-center gap-4 hover:scale-105 transition-all duration-200 cursor-pointer">
                    <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-full">
                        <img src="{{ asset('icons/pause.svg') }}" class="w-6 h-6" alt="Pendiente">
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tiempo Pendiente</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $tiempoPorEstado['pendiente'] }}h</p>
                    </div>
                </div>

                {{-- Tiempo en Revisión --}}
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 flex items-center gap-4 hover:scale-105 transition-all duration-200 cursor-pointer">
                    <div class="p-3 bg-cyan-100 dark:bg-cyan-900/30 rounded-full">
                        <img src="{{ asset('icons/revision.svg') }}" class="w-6 h-6" alt="Revisión">
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tiempo Revisión</p>
                        <p class="text-2xl font-bold text-cyan-600">{{ $tiempoPorEstado['en_revision'] }}h</p>
                    </div>
                </div>
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
                                class="inline-flex items-center justify-center w-10 px-3 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg text-xs font-medium transition"
                                title="Descargar gráfico">
                            <img src="{{ asset('icons/download.svg') }}" class="w-4 h-4" alt="Descargar">
                        </button>
                    </div>
                    <canvas id="chartSala" height="220" style="max-height:350px;"></canvas>
                </div>

                {{-- Por estado --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Estado de Incidencias</h3>
                        <button id="dlEstado" 
                                class="inline-flex items-center justify-center w-10 px-3 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg text-xs font-medium transition"
                                title="Descargar gráfico">
                            <img src="{{ asset('icons/download.svg') }}" class="w-4 h-4" alt="Descargar">
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
                                class="inline-flex items-center justify-center w-10 px-3 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg text-xs font-medium transition"
                                title="Descargar gráfico">
                            <img src="{{ asset('icons/download.svg') }}" class="w-4 h-4" alt="Descargar">
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
                            class="inline-flex items-center justify-center w-10 px-3 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg text-xs font-medium transition"
                            title="Descargar gráfico">
                        <img src="{{ asset('icons/download.svg') }}" class="w-4 h-4" alt="Descargar">
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
            const historicoCheckbox = document.getElementById('historico');
            const trimestreSelect = document.getElementsByName('trimestre')[0];
            const form = document.getElementById('form-filtros');

            function toggleTrimestre(disabled) {
                if (!trimestreSelect) return;
                if (disabled) {
                    trimestreSelect.setAttribute('disabled', 'disabled');
                    trimestreSelect.classList.add('bg-gray-100', 'cursor-not-allowed', 'dark:bg-gray-700/50');
                } else {
                    trimestreSelect.removeAttribute('disabled');
                    trimestreSelect.classList.remove('bg-gray-100', 'cursor-not-allowed', 'dark:bg-gray-700/50');
                }
            }
            toggleTrimestre(!!historicoCheckbox?.checked);
            historicoCheckbox?.addEventListener('change', function () {
                toggleTrimestre(this.checked);
                form?.submit();
            });

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
