{{-- Inicio de Incidencias.blade.php --}}
@section('title', 'Incidencias')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            Bitácora de Incidencias
        </h2>
    </x-slot>

    <div class="p-5 max-w-7xl mx-auto">
        <a href="{{ route('incidencias.create') }}"
            class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-2 py-2 rounded shadow">
            ➕ Nueva Incidencia
        </a>
        <a href="{{ route('incidencias.estadisticas') }}"
            class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white px-2 py-2 rounded shadow">
            📋 Ver Estadísticas
        </a>
    </div>

    <div class="p-6 max-w-7xl mx-auto" x-data="{
        estado: '{{ request('estado') }}',
        sala: '{{ request('room_id') }}',
        anio: '{{ request('anio') }}',
        trimestre: '{{ request('trimestre') }}',
        historico: {{ request()->filled('historico') ? 'true' : 'false' }},
        periodos: @js($periodos),
        get periodosFiltrados() {
            if (!this.anio) return this.periodos;
            return this.periodos.filter(p => p.anio == this.anio);
        },
        actualizarURL() {
            const params = new URLSearchParams(window.location.search);
            this.estado ? params.set('estado', this.estado) : params.delete('estado');
            this.sala ? params.set('room_id', this.sala) : params.delete('room_id');
            this.anio ? params.set('anio', this.anio) : params.delete('anio');
            this.trimestre ? params.set('trimestre', this.trimestre) : params.delete('trimestre');
            this.historico ? params.set('historico', '1') : params.delete('historico');
            window.location.search = params.toString();
        }
    }">

        {{-- Filtros --}}
        <div class="mb-4 grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label class="text-sm text-gray-700 dark:text-gray-300">Estado:</label>
                <select x-model="estado" @change="actualizarURL"
                    class="w-full rounded dark:bg-gray-800 dark:text-white">
                    <option value="">Todos</option>
                    <option value="pendiente">Pendientes</option>
                    <option value="en_revision">Revisión</option>
                    <option value="resuelta">Resueltas</option>
                    <option value="no_resuelta">No resueltas</option>
                </select>
            </div>

            <div>
                <label class="text-sm text-gray-700 dark:text-gray-300">Sala:</label>
                <select x-model="sala" @change="actualizarURL" class="w-full rounded dark:bg-gray-800 dark:text-white">
                    <option value="">Todas</option>
                    @foreach ($salas as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm text-gray-700 dark:text-gray-300">Año:</label>
                <select x-model="anio" @change="actualizarURL" class="w-full rounded dark:bg-gray-800 dark:text-white">
                    <option value="">Todos</option>
                    @foreach ($anios as $a)
                        <option value="{{ $a }}">{{ $a }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm text-gray-700 dark:text-gray-300">Trimestre:</label>
                <select x-model="trimestre" @change="actualizarURL"
                    class="w-full rounded dark:bg-gray-800 dark:text-white">
                    <option value="">Todos</option>
                    <template x-for="p in periodosFiltrados" :key="p.id">
                        <option :value="p.numero" x-text="'Trimestre ' + p.numero" :selected="trimestre == p.numero">
                        </option>
                    </template>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" x-model="historico" @change="actualizarURL" id="historico"
                    class="rounded border-gray-300 dark:bg-gray-800">
                <label for="historico" class="text-sm text-gray-700 dark:text-gray-300">Ver registros históricos</label>
            </div>
        </div>

        {{-- Botones --}}
        <div class="mb-4 flex flex-wrap justify-between items-center gap-2">
            <div>
                <button @click="
                    estado = '';
                    sala = '';
                    anio = '';
                    trimestre = '';
                    historico = false;
                    actualizarURL();
                " class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded text-sm">
                    🧹 Limpiar filtros
                </button>
            </div>

            <form action="{{ route('incidencias.exportar.pdf') }}" method="GET" class="flex gap-2 flex-wrap">
                <input type="hidden" name="estado" :value="estado">
                <input type="hidden" name="room_id" :value="sala">
                <input type="hidden" name="anio" :value="anio">
                <input type="hidden" name="trimestre" :value="trimestre">
                <input type="hidden" name="historico" x-bind:value="historico ? 1 : ''">

                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                    📄 Exportar PDF
                </button>
            </form>
        </div>

        <template x-if="historico">
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 px-4 py-3 rounded mb-4 text-sm">
                Mostrando solo incidencias fuera de los períodos académicos actuales.
            </div>
        </template>

        {{-- Tabla --}}
        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded">
            <table x-data="{ historico: {{ request()->filled('historico') ? 'true' : 'false' }} }"
                class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
                <thead class="bg-gray-100 dark:bg-gray-700 text-align:center">
                    <tr>
                        <th class="px-4 py-2 text-center">ID</th>
                        <th class="px-4 py-2 text-center">Título</th>
                        <th class="px-4 py-2 text-center">Sala</th>
                        <th class="px-4 py-2 text-center">Estado</th>
                        <th class="px-4 py-2 text-center">Fecha</th>
                        <th class="px-4 py-2 text-center">Ticket Jira</th>
                        <th class="px-4 py-2 text-center">Resuelta el</th>
                        <th class="px-4 py-2 text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($incidencias as $incidencia)
                        <tr class="border-t border-gray-200 dark:border-gray-600">
                            <td class="px-4 py-2">{{ $incidencia->id }}</td>
                            <td class="px-4 py-2">{{ $incidencia->titulo }}</td>
                            <td class="px-4 py-2">{{ $incidencia->room->name ?? 'Sin sala' }}</td>
                            @php
                                $estadoIconos = [
                                    'pendiente' => '🕒',
                                    'en_revision' => '🔍',
                                    'resuelta' => '✅',
                                    'no_resuelta' => '❌',
                                ];
                            @endphp
                            <td class="px-4 py-2 text-center">
                                {{ $estadoIconos[$incidencia->estado] ?? 'ℹ️' }}
                            </td>
                            <td class="px-4 py-2">{{ $incidencia->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2">{{ $incidencia->nro_ticket ?? '—' }}</td>
                            <td class="px-4 py-2">
                                {{ $incidencia->resuelta_en ? $incidencia->resuelta_en->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="px-4 py-2 space-x-2">
                                <a href="{{ route('incidencias.show', $incidencia) }}"
                                    class="bg-blue-100 hover:bg-blue-200 text-white px-1 py-1 rounded">🔍</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                No se encontraron incidencias con los filtros aplicados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $incidencias->links() }}
        </div>
    </div>
</x-app-layout>