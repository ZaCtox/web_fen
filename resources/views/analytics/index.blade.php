{{-- Dashboard de Estadísticas Generales --}}
@section('title', 'Estadísticas Generales')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#005187] dark:text-[#c4dafa] leading-tight">
            📊 Estadísticas Generales del Sistema
        </h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Estadísticas Generales', 'url' => '#']
    ]" />

    <div class="py-6 space-y-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Filtros Dinámicos --}}
            <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6" x-data="{
                mes: '{{ $mes }}',
                anio: '{{ $anio }}',
                meses: {
                    '1': 'Enero',
                    '2': 'Febrero',
                    '3': 'Marzo',
                    '4': 'Abril',
                    '5': 'Mayo',
                    '6': 'Junio',
                    '7': 'Julio',
                    '8': 'Agosto',
                    '9': 'Septiembre',
                    '10': 'Octubre',
                    '11': 'Noviembre',
                    '12': 'Diciembre'
                },
                actualizarURL() {
                    const params = new URLSearchParams(window.location.search);
                    params.set('mes', this.mes);
                    params.set('anio', this.anio);
                    window.location.search = params.toString();
                },
                limpiarFiltros() {
                    window.location.href = window.location.pathname;
                }
            }">
                <div class="flex flex-wrap gap-6 items-end">
                    <div>
                        <label for="mes" class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            📅 Mes
                        </label>
                        <select x-model="mes" 
                                @change="actualizarURL()"
                                id="mes" 
                                class="px-6 py-3.5 text-base font-medium rounded-lg border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-[#4d82bc] transition-all hover:border-[#4d82bc] cursor-pointer min-w-[180px]">
                            <template x-for="(nombre, valor) in meses" :key="valor">
                                <option :value="valor" x-text="nombre" :selected="mes == valor"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label for="anio" class="block text-base font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            📆 Año
                        </label>
                        <select x-model="anio" 
                                @change="actualizarURL()"
                                id="anio"
                                class="px-6 py-3.5 text-base font-medium rounded-lg border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-[#4d82bc] transition-all hover:border-[#4d82bc] cursor-pointer min-w-[150px]">
                            @foreach(range(now()->year - 3, now()->year + 1) as $y)
                                <option value="{{ $y }}" :selected="anio == '{{ $y }}'">{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Botón Limpiar Filtros --}}
                    <div>
                        <button type="button" 
                                @click="limpiarFiltros()"
                                class="p-3.5 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 hover:scale-105"
                                title="Limpiar filtros"
                                aria-label="Limpiar filtros">
                            <img src="{{ asset('icons/filterw.svg') }}" alt="Limpiar filtros" class="w-6 h-6">
                        </button>
                    </div>
                </div>
            </div>

            {{-- Métricas Principales (KPIs) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                {{-- 1. Tiempo Promedio de Respuesta --}}
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                ⏱️ Tiempo Promedio de Respuesta
                            </p>
                            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-2">
                                {{ $tiempoPromedioIncidencias }}<span class="text-lg">h</span>
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                A incidencias (resueltas)
                            </p>
                        </div>
                        <div class="text-4xl">⚡</div>
                    </div>
                </div>

                {{-- 2. Porcentaje de Utilización del Calendario --}}
                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-800/30 rounded-lg shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                📅 Utilización del Calendario
                            </p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">
                                {{ $porcentajeUtilizacionCalendario }}<span class="text-lg">%</span>
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $diasConClases }} de {{ $totalDiasHabiles }} días hábiles
                            </p>
                        </div>
                        <div class="text-4xl">📊</div>
                    </div>
                </div>

                {{-- 3. Accesos Mensuales --}}
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-800/30 rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                👥 Accesos a la Plataforma
                            </p>
                            <p class="text-3xl font-bold text-purple-600 dark:text-purple-400 mt-2">
                                {{ number_format($accesosMensuales) }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ number_format($sesionesUnicasMensuales) }} sesiones únicas
                            </p>
                        </div>
                        <div class="text-4xl">🌐</div>
                    </div>
                </div>
            </div>

            {{-- Accesos al Calendario (Público vs Autenticado) --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                    📅 Accesos al Calendario Académico
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Calendario Público</p>
                        <p class="text-lg text-gray-500 dark:text-gray-400 mt-1">(Visitantes sin login)</p>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-2">
                            {{ number_format($accesosCalendarioPublico) }}
                        </p>
                    </div>
                    <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Calendario Autenticado</p>
                        <p class="text-lg text-gray-500 dark:text-gray-400 mt-1">(Usuarios con sesión)</p>
                        <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mt-2">
                            {{ number_format($accesosCalendarioAutenticado) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Accesos por Tipo de Página --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                    📊 Top Páginas Más Visitadas
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Página
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Visitas
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    %
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @php
                                $totalVisitas = $accesosPorTipo->sum('total');
                            @endphp
                            @foreach($paginasMasVisitadas as $pagina)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ str_replace('_', ' ', ucfirst($pagina->page_type)) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-400">
                                        {{ number_format($pagina->total) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-400">
                                        {{ $totalVisitas > 0 ? round(($pagina->total / $totalVisitas) * 100, 1) : 0 }}%
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Usuarios Registrados vs Anónimos --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                    👤 Tipo de Usuarios
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Usuarios Registrados</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-2">
                            {{ number_format($accesosRegistrados) }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ $accesosMensuales > 0 ? round(($accesosRegistrados / $accesosMensuales) * 100, 1) : 0 }}% del total
                        </p>
                    </div>
                    <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Visitantes Anónimos</p>
                        <p class="text-2xl font-bold text-orange-600 dark:text-orange-400 mt-2">
                            {{ number_format($accesosAnonimos) }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ $accesosMensuales > 0 ? round(($accesosAnonimos / $accesosMensuales) * 100, 1) : 0 }}% del total
                        </p>
                    </div>
                </div>
            </div>

            {{-- Gráfico de Accesos Mensuales del Año --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                    📈 Accesos Mensuales {{ $anio }}
                </h3>
                <canvas id="chartAccesosMensuales" height="80"></canvas>
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Gráfico de accesos mensuales
            const ctxMensuales = document.getElementById('chartAccesosMensuales').getContext('2d');
            new Chart(ctxMensuales, {
                type: 'bar',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    datasets: [{
                        label: 'Accesos',
                        data: @json(array_values($mesesCompletos->toArray())),
                        backgroundColor: 'rgba(77, 130, 188, 0.6)',
                        borderColor: 'rgba(77, 130, 188, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        </script>
    @endpush

</x-app-layout>

