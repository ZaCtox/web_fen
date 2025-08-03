<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Bitácora de Incidencias
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Botones superiores --}}
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                <a href="{{ route('incidencias.estadisticas') }}"
                    class="bg-indigo-500 text-white font-semibold py-2 px-4 rounded hover:bg-indigo-600 text-center w-full sm:w-auto">
                    Ver Estadísticas
                </a>
                <a href="{{ route('incidencias.create') }}"
                    class="bg-blue-500 text-white font-semibold py-2 px-4 rounded hover:bg-blue-600 text-center w-full sm:w-auto">
                    Nueva Incidencia
                </a>
                <button type="button" data-modal-toggle="pdfModal"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm">
                    Exportar PDF
                </button>
            </div>

            {{-- Filtros --}}
            <form method="GET" id="filtros-form" class="grid grid-cols-1 sm:grid-cols-6 gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar..."
                    class="rounded border-gray-300 shadow-sm">

                <select name="estado" class="rounded border-gray-300 shadow-sm">
                    <option value="">-- Estado --</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="resuelta" {{ request('estado') == 'resuelta' ? 'selected' : '' }}>Resuelta</option>
                </select>

                <select name="room_id" class="rounded border-gray-300 shadow-sm">
                    <option value="">-- Sala --</option>
                    @foreach($salas as $sala)
                        <option value="{{ $sala->id }}" {{ request('room_id') == $sala->id ? 'selected' : '' }}>
                            {{ $sala->name }}
                        </option>
                    @endforeach
                </select>

                <select name="period_id" id="period_id" class="rounded border-gray-300 shadow-sm">
                    <option value="">-- Periodo --</option>
                    @foreach($periodos as $p)
                        <option value="{{ $p->id }}" {{ request('period_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nombre_completo }}
                        </option>
                    @endforeach
                </select>

                <select name="anio" class="rounded border-gray-300 shadow-sm">
                    <option value="">-- Año --</option>
                    @foreach($anios as $anio)
                        <option value="{{ $anio }}" {{ request('anio') == $anio ? 'selected' : '' }}>
                            {{ $anio }}
                        </option>
                    @endforeach
                </select>

                <label class="flex items-center gap-1">
                    <input type="checkbox" name="historico" value="1" {{ request('historico') ? 'checked' : '' }}>
                    <span class="text-sm text-gray-700 dark:text-gray-200">Ver datos históricos</span>
                </label>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Filtrar
                </button>
            </form>

            {{-- Mensaje de datos históricos --}}
            @if(request('historico'))
                <div class="text-sm text-yellow-700 dark:text-yellow-300 font-medium">
                    Mostrando únicamente incidencias fuera de los períodos académicos actuales.
                </div>
            @endif

            {{-- Tabla --}}
            <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded">
                <table class="min-w-[900px] w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left">Título</th>
                            <th class="px-4 py-2 text-left">Sala</th>
                            <th class="px-4 py-2 text-left">Registrado por</th>
                            <th class="px-4 py-2 text-left">Estado</th>
                            <th class="px-4 py-2 text-left">Fecha</th>
                            <th class="px-4 py-2 text-left">Periodo</th>
                            <th class="px-4 py-2 text-left">Imagen</th>
                            <th class="px-4 py-2 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach($incidencias as $incidencia)
                            <tr>
                                <td class="px-4 py-2">{{ $incidencia->titulo }}</td>
                                <td class="px-4 py-2">{{ $incidencia->room->name ?? 'Sin sala' }}</td>
                                <td class="px-4 py-2">{{ $incidencia->user->name ?? 'N/D' }}</td>
                                <td class="px-4 py-2">
                                    @if($incidencia->estado === 'resuelta')
                                        <span class="text-green-600 dark:text-green-400 font-semibold">Resuelta</span>
                                    @else
                                        <span class="text-yellow-600 dark:text-yellow-400 font-semibold">Pendiente</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">{{ $incidencia->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-2">
                                    @php
                                        $periodo = $periodos->first(
                                            fn($p) =>
                                            $incidencia->created_at >= $p->fecha_inicio &&
                                            $incidencia->created_at <= $p->fecha_fin
                                        );
                                    @endphp
                                    @if ($periodo)
                                        <span class="text-sm">{{ $periodo->nombre_completo }}</span>
                                    @else
                                        <span class="text-xs italic text-red-500">📦 Histórico (fuera de períodos)</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    @if($incidencia->imagen)
                                        <img src="{{ $incidencia->imagen }}" alt="Incidencia" class="w-24 h-auto rounded">
                                    @else
                                        <span class="text-sm text-gray-400 italic">Sin imagen</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex flex-col gap-1">
                                        <a href="{{ route('incidencias.show', $incidencia) }}"
                                            class="bg-indigo-500 text-white px-2 py-1 rounded hover:bg-indigo-600 text-xs text-center w-full">
                                            👁️ Ver
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Paginación --}}
                <div class="mt-4 flex justify-center">
                    {{ $incidencias->links() }}
                </div>
            </div>
        </div>
        <!-- Modal Exportar PDF -->
        <div id="pdfModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-lg">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Exportar PDF de Incidencias</h2>

                <form method="GET" action="{{ route('incidencias.exportar.pdf') }}">
                    <div class="grid grid-cols-1 gap-4">

                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-200">Año</label>
                            <select name="anio" class="w-full rounded border-gray-300">
                                <option value="">Todos</option>
                                @foreach($anios as $anio)
                                    <option value="{{ $anio }}" {{ request('anio') == $anio ? 'selected' : '' }}>{{ $anio }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-200">Periodo</label>
                            <select name="period_id" class="w-full rounded border-gray-300">
                                <option value="">Todos</option>
                                @foreach($periodos as $periodo)
                                    <option value="{{ $periodo->id }}" {{ request('period_id') == $periodo->id ? 'selected' : '' }}>
                                        {{ $periodo->nombre_completo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-200">Sala</label>
                            <select name="room_id" class="w-full rounded border-gray-300">
                                <option value="">Todas</option>
                                @foreach($salas as $sala)
                                    <option value="{{ $sala->id }}" {{ request('room_id') == $sala->id ? 'selected' : '' }}>
                                        {{ $sala->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-700 dark:text-gray-200">Estado</label>
                            <select name="estado" class="w-full rounded border-gray-300">
                                <option value="">Todos</option>
                                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>
                                    Pendiente</option>
                                <option value="resuelta" {{ request('estado') == 'resuelta' ? 'selected' : '' }}>Resuelta
                                </option>
                            </select>
                        </div>

                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="historico" value="1" {{ request('historico') ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700 dark:text-gray-200">Incluir datos históricos</span>
                        </label>

                    </div>

                    <div class="flex justify-end mt-6 gap-2">
                        <button type="button" onclick="document.getElementById('pdfModal').classList.add('hidden')"
                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancelar</button>

                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Descargar
                            PDF</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- 1. Auto-submit al marcar "histórico" en el formulario principal -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const historicoCheckbox = document.querySelector('form#filtros-form input[name="historico"]');
            const filtrosForm = document.querySelector('form#filtros-form');

            if (historicoCheckbox && filtrosForm) {
                historicoCheckbox.addEventListener('change', function () {
                    filtrosForm.submit();
                });
            }
        });
    </script>

    <!-- 2. Desactiva select de periodo si "histórico" está marcado en index -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const historicoCheckbox = document.querySelector('form#filtros-form input[name="historico"]');
            const periodoSelect = document.getElementById('period_id');

            function togglePeriodo(disable) {
                if (!periodoSelect) return;
                if (disable) {
                    periodoSelect.setAttribute('disabled', 'disabled');
                    periodoSelect.classList.add('bg-gray-100', 'cursor-not-allowed');
                } else {
                    periodoSelect.removeAttribute('disabled');
                    periodoSelect.classList.remove('bg-gray-100', 'cursor-not-allowed');
                }
            }

            if (historicoCheckbox && periodoSelect) {
                togglePeriodo(historicoCheckbox.checked);
                historicoCheckbox.addEventListener('change', function () {
                    togglePeriodo(this.checked);
                });
            }
        });
    </script>

    <!-- 3. Abrir modal PDF y rellenar filtros desde URL -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnExportar = document.querySelector('[data-modal-toggle="pdfModal"]');

            btnExportar?.addEventListener('click', () => {
                const urlParams = new URLSearchParams(window.location.search);
                const modal = document.getElementById('pdfModal');

                modal.querySelectorAll('select, input[type="checkbox"]').forEach(el => {
                    const name = el.name;
                    if (!name) return;

                    if (el.type === 'checkbox') {
                        el.checked = urlParams.get(name) === '1';
                    } else {
                        const value = urlParams.get(name);
                        if (value) el.value = value;
                    }

                    // Desactivar periodo si histórico está marcado
                    if (name === 'historico') {
                        const periodoSelect = modal.querySelector('select[name="period_id"]');
                        if (periodoSelect) {
                            if (el.checked) {
                                periodoSelect.setAttribute('disabled', 'disabled');
                                periodoSelect.classList.add('bg-gray-100', 'cursor-not-allowed');
                            } else {
                                periodoSelect.removeAttribute('disabled');
                                periodoSelect.classList.remove('bg-gray-100', 'cursor-not-allowed');
                            }
                        }
                    }
                });

                modal.classList.remove('hidden');
            });
        });
    </script>

    <!-- 4. Botón cerrar modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('#pdfModal button[type="button"]').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.getElementById('pdfModal').classList.add('hidden');
                });
            });
        });
    </script>
</x-app-layout>