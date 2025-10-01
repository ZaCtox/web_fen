{{-- Estadísticas de Incidencias.blade.php --}}
@section('title', 'Estadísticas')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Estadísticas de Incidencias
        </h2>
    </x-slot>

    <div class="py-6 space-y-8">
<<<<<<< Updated upstream

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 mb-6">
            <a href="{{ route('incidencias.index') }}"
                class="inline-flex items-center gap-2 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white hover:bg-gray-200 dark:hover:bg-gray-600 px-4 py-2 rounded shadow text-sm font-medium transition-all duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
                Volver a Incidencias
            </a>
        </div>

=======
<<<<<<< Updated upstream
>>>>>>> Stashed changes
        {{-- Filtros --}}
        <form method="GET" id="form-filtros" class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-5 gap-4 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                {{-- Año --}}
                <div>
                    <label for="anio" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Año</label>
<<<<<<< Updated upstream
                    <select name="anio" id="anio"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm">
=======
                    <input type="number" name="anio" id="anio" value="{{ request('anio') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
=======

        {{-- Volver --}}
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 mb-6">
            <a href="{{ route('incidencias.index') }}"
                class="inline-flex items-center gap-2 bg-[#4d82bc] hover:bg-[#005187] text-white font-medium px-4 py-2 rounded-lg shadow transition-all duration-200">
                <img src="{{ asset('icons/back.svg') }}" alt="Volver" class="w-5 h-5">
            </a>
        </div>

        {{-- Filtros mejorados --}}
        <form method="GET" id="form-filtros" class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div
                class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">

                {{-- Estado --}}
                <div>
                    <label class="text-sm font-semibold text-[#005187]">Estado:</label>
                    <select name="estado"
                        class="w-full rounded-lg border border-[#84b6f4] bg-[#fcffff] text-[#005187] px-2 py-2 focus:ring-[#4d82bc] focus:border-[#4d82bc]">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendientes
                        </option>
                        <option value="en_revision" {{ request('estado') == 'en_revision' ? 'selected' : '' }}>Revisión
                        </option>
                        <option value="resuelta" {{ request('estado') == 'resuelta' ? 'selected' : '' }}>Resueltas
                        </option>
                        <option value="no_resuelta" {{ request('estado') == 'no_resuelta' ? 'selected' : '' }}>No
                            resueltas</option>
                    </select>
                </div>

                {{-- Sala --}}
                <div>
                    <label class="text-sm font-semibold text-[#005187]">Sala:</label>
                    <select name="room_id"
                        class="w-full rounded-lg border border-[#84b6f4] bg-[#fcffff] text-[#005187] px-2 py-2 focus:ring-[#4d82bc] focus:border-[#4d82bc]">
                        <option value="">Todas</option>
                        @foreach ($salas as $s)
                            <option value="{{ $s->id }}" {{ request('room_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}
                            </option>
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
>>>>>>> Stashed changes
                </div>

                <div>
<<<<<<< Updated upstream
                    <label for="semestre" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Semestre</label>
                    <select name="semestre" id="semestre"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
=======
                    <label class="text-sm font-semibold text-[#005187]">Trimestre:</label>
                    <select name="trimestre"
                        class="w-full rounded-lg border border-[#84b6f4] bg-[#fcffff] text-[#005187] px-2 py-2 focus:ring-[#4d82bc] focus:border-[#4d82bc]">
>>>>>>> Stashed changes
>>>>>>> Stashed changes
                        <option value="">Todos</option>
                        @foreach($anios as $anio)
                            <option value="{{ $anio }}" {{ request('anio') == $anio ? 'selected' : '' }}>
                                {{ $anio }}
                            </option>
                        @endforeach
                    </select>
                </div>

<<<<<<< Updated upstream
                {{-- Trimestre --}}
                <div>
                    <label for="trimestre"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-200">Trimestre</label>
                    <select name="trimestre" id="trimestre"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm">
                        <option value="">Todos</option>
                        <option value="1" {{ request('trimestre') == '1' ? 'selected' : '' }}>1</option>
                        <option value="2" {{ request('trimestre') == '2' ? 'selected' : '' }}>2</option>
                        <option value="3" {{ request('trimestre') == '3' ? 'selected' : '' }}>3</option>
                    </select>
                </div>

                {{-- Sala --}}
=======
<<<<<<< Updated upstream
>>>>>>> Stashed changes
                <div>
                    <label for="room_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Sala</label>
                    <select name="room_id" id="room_id"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm">
                        <option value="">Todas</option>
                        @foreach($salas as $sala)
                            <option value="{{ $sala->id }}" {{ request('room_id') == $sala->id ? 'selected' : '' }}>
                                {{ $sala->name }} ({{ $sala->location }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Histórico --}}
                <div class="flex items-center gap-2 mt-7">
                    <input type="checkbox" name="historico" id="historico" value="1" {{ request('historico') ? 'checked' : '' }}>
                    <label for="historico" class="text-sm text-gray-700 dark:text-gray-200 cursor-pointer">Ver datos
                        históricos</label>
                </div>

                {{-- Botones --}}
                <div class="sm:col-span-1 flex items-end gap-2">
                    <button type="submit"
                        class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                        Aplicar Filtros
                    </button>
                    <a href="{{ route('incidencias.estadisticas') }}"
<<<<<<< Updated upstream
                        class="w-full text-center bg-gray-500 text-white font-bold py-2 px-4 rounded hover:bg-gray-600">
                        Limpiar
=======
                        class="bg-gray-500 text-white font-bold py-2 px-4 rounded hover:bg-gray-600 w-full text-center">
                        Limpiar Filtros
=======
                {{-- Histórico --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="historico" id="historico"
                        class="rounded border-[#84b6f4] text-[#4d82bc] focus:ring-[#4d82bc]" {{ request('historico') ? 'checked' : '' }}>
                    <label for="historico" class="text-sm font-semibold text-[#005187]">Registros Históricos</label>
                </div>

                {{-- Botones --}}
                <div class="sm:col-span-5 flex gap-2 mt-2">
                    <button type="submit"
                        class="flex-w bg-[#4d82bc] hover:bg-[#005187] text-white font-bold py-2 px-4 rounded-lg shadow transition-all duration-200 justify-center">
                        Aplicar
                    </button>
                    <a href="{{ route('incidencias.estadisticas') }}"
                        class="flex justify-center items-center bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow transition-all duration-200">
                        <img src="{{ asset('icons/filtro.svg') }}" class="w-6 h-6" alt="Filtro">
>>>>>>> Stashed changes
>>>>>>> Stashed changes
                    </a>
                </div>

            </div>
        </form>

<<<<<<< Updated upstream
        {{-- Mensaje si es histórico --}}
        @if(request('historico'))
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="rounded border border-yellow-300 bg-yellow-50 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200 px-4 py-2 text-sm">
                    Mostrando solo incidencias fuera de los períodos definidos (modo histórico).
                </div>
            </div>
        @endif

        {{-- KPIs rápidos --}}
        @php
            $totalInc = ($porEstado->sum()) ?? 0;
            $pendientes = $porEstado->get('Pendiente', $porEstado->get('pendiente', 0)) ?? 0;
            $resueltas = $porEstado->get('Resuelta', $porEstado->get('resuelta', 0)) ?? 0;
            $pctResueltas = $totalInc > 0 ? round(($resueltas / $totalInc) * 100) : 0;
        @endphp
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total incidencias</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalInc }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pendientes</p>
                    <p class="text-2xl font-bold text-amber-600">{{ $pendientes }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">% Resueltas</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ $pctResueltas }}%</p>
                </div>
            </div>
        </div>

=======
<<<<<<< Updated upstream
>>>>>>> Stashed changes
        {{-- Gráficos --}}
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Por sala --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Incidencias por Sala</h3>
                        <button id="dlSala"
                            class="text-sm px-2 py-1 rounded border hover:bg-gray-50 dark:hover:bg-gray-700">
                            ⬇️ Descargar imagen
                        </button>
                    </div>
                    <div class="relative">
                        <canvas id="chartSala" height="220" aria-label="Gráfico de barras de incidencias por sala"
                            role="img"></canvas>
                    </div>
                </div>

                {{-- Por estado --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Estado de Incidencias</h3>
                        <button id="dlEstado"
                            class="text-sm px-2 py-1 rounded border hover:bg-gray-50 dark:hover:bg-gray-700">
                            ⬇️ Descargar imagen
                        </button>
                    </div>
                    <div class="relative">
                        <canvas id="chartEstado" height="220" aria-label="Gráfico de estados de incidencias"
                            role="img"></canvas>
                    </div>
                </div>
            </div>

<<<<<<< Updated upstream
            {{-- Por trimestre (si no es histórico) --}}
=======
            {{-- Gráfico por estado --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-1 text-gray-800 dark:text-gray-100">Estado de Incidencias</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Distribución de incidencias según su estado actual: pendientes o resueltas.</p>
                <canvas id="chartEstado" height="100"></canvas>
            </div>

            {{-- Gráfico por semestre y año --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-1 text-gray-800 dark:text-gray-100">Incidencias por Semestre y Año</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Evolución de incidencias a lo largo de los semestres, útil para identificar patrones o aumentos por ciclo académico.</p>
                <canvas id="chartSemestre" height="100"></canvas>
            </div>
=======

        {{-- Mensaje histórico --}}
        @if(request('historico'))
            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="rounded border border-yellow-300 bg-yellow-50 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200 px-4 py-2 text-sm">
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
        @endphp
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                {{-- Total --}}
                <div
                    class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 flex items-center gap-4 hover:scale-105 transition-all duration-200 cursor-pointer">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                        <img src="{{ asset('icons/estadistica.svg') }}" class="w-6 h-6" alt="Total">
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total incidencias</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalInc }}</p>
                    </div>
                </div>

                {{-- Pendientes --}}
                <div
                    class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 flex items-center gap-4 hover:scale-105 transition-all duration-200 cursor-pointer">
                    <div class="p-3 bg-amber-100 dark:bg-amber-900/30 rounded-full">
                        <img src="{{ asset('icons/clock.svg') }}" class="w-6 h-6" alt="Pendientes">
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pendientes</p>
                        <p class="text-2xl font-bold text-amber-600">{{ $pendientes }}</p>
                    </div>
                </div>

                {{-- % Resueltas --}}
                <div
                    class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 flex items-center gap-4 hover:scale-105 transition-all duration-200 cursor-pointer">
                    <div class="p-3 bg-emerald-100 dark:bg-emerald-900/30 rounded-full">
                        <img src="{{ asset('icons/check.svg') }}" class="w-6 h-6" alt="Resueltas">
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">% Resueltas</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ $pctResueltas }}%</p>
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
                            class="inline-flex items-center gap-1 text-sm px-2 py-1 rounded border border-gray-300 text-gray-600 dark:text-gray-200 bg-[#4d82bc] hover:bg-[#005187] transition-all duration-150">
                            <img src="{{ asset('icons/download.svg') }}" class="w-6 h-6" alt="Descargar">
                        </button>
                    </div>
                    <div class="relative">
                        <canvas id="chartSala" height="220" style="max-height:350px;"
                            aria-label="Gráfico de barras de incidencias por sala" role="img"></canvas>
                    </div>
                </div>

                {{-- Por estado --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Estado de Incidencias</h3>
                        <button id="dlEstado" class="text-sm px-2 py-1 rounded border bg-[#4d82bc] hover:bg-[#005187]">
                            <img src="{{ asset('icons/download.svg') }}" class="w-6 h-6" alt="Descargar">
                        </button>
                    </div>
                    <div class="relative">
                        <canvas id="chartEstado" height="220" style="max-height:350px;"
                            aria-label="Gráfico de estados de incidencias" role="img"></canvas>
                    </div>
                </div>
            </div>

            {{-- Por trimestre --}}
>>>>>>> Stashed changes
            @if(!request('historico'))
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Incidencias por Trimestre y Año
                        </h3>
<<<<<<< Updated upstream
                        <button id="dlTrimestre"
                            class="text-sm px-2 py-1 rounded border hover:bg-gray-50 dark:hover:bg-gray-700">
                            ⬇️ Descargar imagen
                        </button>
                    </div>
                    <div class="relative">
                        <canvas id="chartTrimestre" height="140" aria-label="Gráfico de líneas por trimestre y año"
                            role="img"></canvas>
                    </div>
                </div>
            @endif
=======
                        <button id="dlTrimestre" class="text-sm px-2 py-1 rounded border bg-[#4d82bc] hover:bg-[#005187]">
                            <img src="{{ asset('icons/download.svg') }}" class="w-6 h-6" alt="Descargar">
                        </button>
                    </div>
                    <div class="relative">
                        <canvas id="chartTrimestre" height="140" style="max-height:300px;"
                            aria-label="Gráfico de líneas por trimestre y año" role="img"></canvas>
                    </div>
                </div>
            @endif
>>>>>>> Stashed changes
>>>>>>> Stashed changes
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const historicoCheckbox = document.getElementById('historico');
            const trimestreSelect = document.getElementById('trimestre');
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

<<<<<<< Updated upstream
            // Datos desde servidor
            const porSalaLabels = @json($porSala->keys());
            const porSalaValues = @json($porSala->values());
            const porEstadoLabels = @json($porEstado->keys());
            const porEstadoValues = @json($porEstado->values());
            @if(!request('historico'))
                const porTrimLabels = @json($porTrimestre->keys());
                const porTrimValues = @json($porTrimestre->values());
            @endif
=======
<<<<<<< Updated upstream
        new Chart(document.getElementById('chartEstado'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($porEstado->keys()) !!},
                datasets: [{
                    data: {!! json_encode($porEstado->values()) !!},
                    backgroundColor: ['#fbbf24', '#10b981']
                }]
            }
        });
>>>>>>> Stashed changes

    // Helpers de color por tema
    const isDark = () => document.documentElement.classList.contains('dark');
            const axisColor = () => isDark() ? '#d1d5db' : '#374151';
            const gridColor = () => isDark() ? 'rgba(229,231,235,0.1)' : 'rgba(209,213,219,0.4)';

            const charts = {};

            // Sala
            const ctxSala = document.getElementById('chartSala');
            charts.sala = new Chart(ctxSala, {
                type: 'bar',
                data: {
                    labels: porSalaLabels,
                    datasets: [{
                        label: 'Incidencias',
                        data: porSalaValues,
                        backgroundColor: 'rgba(59,130,246,0.7)',
                        borderColor: 'rgba(59,130,246,1)',
                        borderWidth: 1,
                        maxBarThickness: 46
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ` ${ctx.parsed.y} incidencia(s)`
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

            // Estado
            const ctxEstado = document.getElementById('chartEstado');
            const estadoColors = ['#fbbf24', '#10b981', '#ef4444', '#60a5fa', '#a78bfa'];
            charts.estado = new Chart(ctxEstado, {
                type: 'doughnut',
                data: {
                    labels: porEstadoLabels,
                    datasets: [{
                        data: porEstadoValues,
                        backgroundColor: estadoColors.slice(0, porEstadoLabels.length)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: axisColor() }
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => {
                                    const total = porEstadoValues.reduce((a, b) => a + b, 0) || 1;
                                    const value = ctx.parsed;
                                    const pct = Math.round((value / total) * 100);
                                    return ` ${ctx.label}: ${value} (${pct}%)`;
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });

            // Trimestre (solo si no es histórico)
            @if(!request('historico'))
                const ctxTrim = document.getElementById('chartTrimestre');
                charts.trim = new Chart(ctxTrim, {
                    type: 'line',
                    data: {
                        labels: porTrimLabels,
                        datasets: [{
                            label: 'Incidencias',
                            data: porTrimValues,
                            fill: false,
                            borderColor: 'rgba(37,99,235,1)',
                            backgroundColor: 'rgba(37,99,235,0.5)',
                            tension: 0.3,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                labels: { color: axisColor() }
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
            @endif

    // Tema oscuro: actualizar colores
    const observer = new MutationObserver(() => {
                Object.values(charts).forEach(ch => {
                    if (!ch?.options) return;
                    if (ch.options.scales) {
                        if (ch.options.scales.x) {
                            ch.options.scales.x.ticks.color = axisColor();
                            ch.options.scales.x.grid.color = gridColor();
                        }
                        if (ch.options.scales.y) {
                            ch.options.scales.y.ticks.color = axisColor();
                            ch.options.scales.y.grid.color = gridColor();
                        }
                    }
                    if (ch.options.plugins?.legend?.labels) {
                        ch.options.plugins.legend.labels.color = axisColor();
                    }
                    ch.update('none');
                });
            });
            observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

            // Descargar como imagen
            function wireDownload(btnId, chart) {
                const btn = document.getElementById(btnId);
                btn?.addEventListener('click', () => {
                    const url = chart.toBase64Image();
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `${btnId}.png`;
                    a.click();
                });
            }
<<<<<<< Updated upstream

            wireDownload('dlSala', charts.sala);
            wireDownload('dlEstado', charts.estado);
            @if(!request('historico'))
                if (charts.trim) {
                    wireDownload('dlTrimestre', charts.trim);
                }
            @endif
});
=======
=======
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
                    datasets: [{
                        label: 'Incidencias',
                        data: porSalaValues,
                        backgroundColor: 'rgba(59,130,246,0.7)',
                        borderColor: 'rgba(59,130,246,1)',
                        borderWidth: 1,
                        maxBarThickness: 46
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.y} incidencia(s)` } }
                    },
                    scales: {
                        x: { ticks: { color: axisColor() }, grid: { color: gridColor() } },
                        y: { beginAtZero: true, ticks: { color: axisColor() }, grid: { color: gridColor() } }
                    }
                }
            });

            // Estado// Estado
            const estadoColors = ['#fbbf24', '#10b981', '#ef4444', '#60a5fa', '#a78bfa'].slice(0, porEstadoLabels.length);
            charts.estado = new Chart(document.getElementById('chartEstado'), {
                type: 'doughnut',
                data: {
                    labels: porEstadoLabels.map(label =>
                        label.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase())
                    ),
                    datasets: [{
                        data: porEstadoValues,
                        backgroundColor: estadoColors
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                color: axisColor(),
                                usePointStyle: true,
                                pointStyle: 'circle',
                                generateLabels: (chart) => {
                                    return chart.data.labels.map((label, i) => ({
                                        text: label,
                                        fillStyle: estadoColors[i],
                                        strokeStyle: estadoColors[i],
                                        index: i
                                    }));
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => {
                                    const total = porEstadoValues.reduce((a, b) => a + b, 0) || 1;
                                    const value = ctx.parsed;
                                    const pct = Math.round((value / total) * 100);
                                    return ` ${ctx.label}: ${value} (${pct}%)`;
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });

            @if(!request('historico'))
                charts.trim = new Chart(document.getElementById('chartTrimestre'), {
                    type: 'line',
                    data: {
                        labels: porTrimLabels,
                        datasets: [{
                            label: 'Incidencias',
                            data: porTrimValues,
                            fill: false,
                            borderColor: 'rgba(37,99,235,1)',
                            backgroundColor: 'rgba(37,99,235,0.5)',
                            tension: 0.3,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { labels: { color: axisColor() } } },
                        scales: {
                            x: { ticks: { color: axisColor() }, grid: { color: gridColor() } },
                            y: { beginAtZero: true, ticks: { color: axisColor() }, grid: { color: gridColor() } }
                        }
                    }
                });
            @endif

            // Tema oscuro: actualizar colores
            const observer = new MutationObserver(() => {
                Object.values(charts).forEach(ch => {
                    if (!ch?.options) return;
                    if (ch.options.scales) {
                        if (ch.options.scales.x) { ch.options.scales.x.ticks.color = axisColor(); ch.options.scales.x.grid.color = gridColor(); }
                        if (ch.options.scales.y) { ch.options.scales.y.ticks.color = axisColor(); ch.options.scales.y.grid.color = gridColor(); }
                    }
                    if (ch.options.plugins?.legend?.labels) { ch.options.plugins.legend.labels.color = axisColor(); }
                    ch.update('none');
                });
            });
            observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

            // Descargar
            function wireDownload(btnId, chart) {
                document.getElementById(btnId)?.addEventListener('click', () => {
                    const a = document.createElement('a');
                    a.href = chart.toBase64Image();
                    a.download = `${btnId}.png`;
                    a.click();
                });
            }
            wireDownload('dlSala', charts.sala);
            wireDownload('dlEstado', charts.estado);
            @if(!request('historico'))
                if (charts.trim) wireDownload('dlTrimestre', charts.trim);
            @endif
>>>>>>> Stashed changes
        });
>>>>>>> Stashed changes
    </script>
</x-app-layout>