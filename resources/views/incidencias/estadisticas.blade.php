<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Estadísticas de Incidencias
        </h2>
    </x-slot>

    <div class="py-6 space-y-8">
        {{-- Filtros --}}
        <form method="GET" id="form-filtros" class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                {{-- Año --}}
                <div>
                    <label for="anio" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Año</label>
                    <select name="anio" id="anio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Todos</option>
                        @foreach($anios as $anio)
                            <option value="{{ $anio }}" {{ request('anio') == $anio ? 'selected' : '' }}>
                                {{ $anio }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Trimestre --}}
                <div>
                    <label for="trimestre" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Trimestre</label>
                    <select name="trimestre" id="trimestre" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Todos</option>
                        <option value="1" {{ request('trimestre') == '1' ? 'selected' : '' }}>1</option>
                        <option value="2" {{ request('trimestre') == '2' ? 'selected' : '' }}>2</option>
                        <option value="3" {{ request('trimestre') == '3' ? 'selected' : '' }}>3</option>
                    </select>
                </div>

                {{-- Sala --}}
                <div>
                    <label for="room_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Sala</label>
                    <select name="room_id" id="room_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Todas</option>
                        @foreach($salas as $sala)
                            <option value="{{ $sala->id }}" {{ request('room_id') == $sala->id ? 'selected' : '' }}>
                                {{ $sala->name }} ({{ $sala->location }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Checkbox histórico --}}
                <div class="flex items-center gap-1 mt-6">
                    <input type="checkbox" name="historico" id="historico" value="1" {{ request('historico') ? 'checked' : '' }}>
                    <span class="text-sm text-gray-700 dark:text-gray-200">Ver datos históricos</span>
                </div>

                {{-- Botones --}}
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

        {{-- Mensaje si es histórico --}}
        @if(request('historico'))
            <div class="text-yellow-600 dark:text-yellow-300 text-sm font-medium px-6">
                Mostrando solo incidencias fuera de los períodos definidos (modo histórico).
            </div>
        @endif

        {{-- Gráficos --}}
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Gráfico por sala --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-1 text-gray-800 dark:text-gray-100">Incidencias por Sala</h3>
                    <canvas id="chartSala" height="200"></canvas>
                </div>

                {{-- Gráfico por estado --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-1 text-gray-800 dark:text-gray-100">Estado de Incidencias</h3>
                    <canvas id="chartEstado" height="200"></canvas>
                </div>
            </div>

            {{-- Gráfico por trimestre y año (solo si no es histórico) --}}
            @if(!request('historico'))
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-1 text-gray-800 dark:text-gray-100">Incidencias por Trimestre y Año</h3>
                    <canvas id="chartTrimestre" height="120"></canvas>
                </div>
            @endif
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
                if (disabled) {
                    trimestreSelect.setAttribute('disabled', 'disabled');
                    trimestreSelect.classList.add('bg-gray-100', 'cursor-not-allowed');
                } else {
                    trimestreSelect.removeAttribute('disabled');
                    trimestreSelect.classList.remove('bg-gray-100', 'cursor-not-allowed');
                }
            }

            // Al cargar la página
            toggleTrimestre(historicoCheckbox.checked);

            // Al cambiar el checkbox
            historicoCheckbox?.addEventListener('change', function () {
                toggleTrimestre(this.checked);
                form?.submit();
            });
        });

        // Gráfico por sala
        new Chart(document.getElementById('chartSala'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($porSala->keys()) !!},
                datasets: [{
                    label: 'Incidencias',
                    data: {!! json_encode($porSala->values()) !!},
                    backgroundColor: 'rgba(59,130,246,0.7)',
                    borderColor: 'rgba(59,130,246,1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });

        // Gráfico por estado
        new Chart(document.getElementById('chartEstado'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($porEstado->keys()) !!},
                datasets: [{
                    data: {!! json_encode($porEstado->values()) !!},
                    backgroundColor: ['#fbbf24', '#10b981']
                }]
            },
            options: { responsive: true }
        });
    </script>

    {{-- Gráfico de trimestre (solo si no es histórico) --}}
    @if(!request('historico'))
    <script>
        new Chart(document.getElementById('chartTrimestre'), {
            type: 'line',
            data: {
                labels: {!! json_encode($porTrimestre->keys()) !!},
                datasets: [{
                    label: 'Incidencias',
                    data: {!! json_encode($porTrimestre->values()) !!},
                    fill: false,
                    borderColor: 'rgba(37,99,235,1)',
                    backgroundColor: 'rgba(37,99,235,0.5)',
                    tension: 0.3,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
    @endif
</x-app-layout>
