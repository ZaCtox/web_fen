<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Estadísticas de Incidencias
        </h2>
    </x-slot>

    <div class="py-6 space-y-8">
        <form method="GET" class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div>
                    <label for="anio" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Año</label>
                    <input type="number" name="anio" id="anio" value="{{ request('anio') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                </div>

                <div>
                    <label for="semestre"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-200">Semestre</label>
                    <select name="semestre" id="semestre"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        <option value="">Todos</option>
                        <option value="1" {{ request('semestre') == '1' ? 'selected' : '' }}>1</option>
                        <option value="2" {{ request('semestre') == '2' ? 'selected' : '' }}>2</option>
                    </select>
                </div>

                <div>
                    <label for="sala" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Sala</label>
                    <input type="text" name="sala" id="sala" value="{{ request('sala') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 w-full">
                        Aplicar Filtros
                    </button>

                    <a href="{{ route('incidencias.estadisticas') }}"
                        class="bg-gray-500 text-white font-bold py-2 px-4 rounded hover:bg-gray-600 w-full text-center">
                        Limpiar Filtros
                    </a>
                </div>
            </div>
        </form>

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Incidencias por Sala</h3>
                <canvas id="chartSala" height="100"></canvas>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Estado de Incidencias</h3>
                <canvas id="chartEstado" height="100"></canvas>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Incidencias por Semestre y Año
                </h3>
                <canvas id="chartSemestre" height="100"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('chartSala'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($porSala->keys()) !!},
                datasets: [{
                    label: 'Incidencias por sala',
                    data: {!! json_encode($porSala->values()) !!},
                    backgroundColor: 'rgba(59,130,246,0.7)',
                    borderColor: 'rgba(59,130,246,1)',
                    borderWidth: 1
                }]
            }
        });

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

        new Chart(document.getElementById('chartSemestre'), {
            type: 'line',
            data: {
                labels: {!! json_encode($porSemestre->keys()) !!},
                datasets: [{
                    label: 'Incidencias por semestre/año',
                    data: {!! json_encode($porSemestre->values()) !!},
                    fill: false,
                    borderColor: 'rgba(37,99,235,1)',
                    backgroundColor: 'rgba(37,99,235,0.5)',
                    tension: 0.3
                }]
            }
        });
    </script>
</x-app-layout>