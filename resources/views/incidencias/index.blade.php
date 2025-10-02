{{-- Inicio de Incidencias.blade.php --}}
@section('title', 'Incidencias')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
            Bitácora de Incidencias
        </h2>
    </x-slot>

    {{-- Botones superiores --}}
    <div class="p-5 max-w-7xl mx-auto flex gap-3">
        <a href="{{ route('incidencias.create') }}"
            class="inline-flex items-center bg-[#4d82bc] hover:bg-[#005187] text-white px-4 py-2 rounded-lg shadow transition transform hover:scale-105">
            <img src="{{ asset('icons/agregar.svg') }}" alt="nueva" class="w-5 h-5">
        </a>
        <a href="{{ route('incidencias.estadisticas') }}"
            class="inline-flex items-center bg-[#84b6f4] hover:bg-[#4d82bc] text-[#005187] px-4 py-2 rounded-lg shadow transition transform hover:scale-105">
            <img src="{{ asset('icons/estadistica.svg') }}" alt="Estadísticas" class="w-6 h-6">
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

        {{-- 🔍 Filtros --}}
        <div class="mb-4 grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label class="text-sm font-semibold text-[#005187]">Estado:</label>
                <select x-model="estado" @change="actualizarURL"
                    class="w-full rounded-lg border border-[#84b6f4] bg-[#fcffff] text-[#005187] px-2 py-2 focus:ring-[#4d82bc] focus:border-[#4d82bc]">
                    <option value="">Todos</option>
                    <option value="pendiente">Pendientes</option>
                    <option value="en_revision">Revisión</option>
                    <option value="resuelta">Resueltas</option>
                    <option value="no_resuelta">No resueltas</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-semibold text-[#005187]">Sala:</label>
                <select x-model="sala" @change="actualizarURL"
                    class="w-full rounded-lg border border-[#84b6f4] bg-[#fcffff] text-[#005187] px-2 py-2 focus:ring-[#4d82bc] focus:border-[#4d82bc]">
                    <option value="">Todas</option>
                    @foreach ($salas as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-semibold text-[#005187]">Año:</label>
                <select x-model="anio" @change="actualizarURL"
                    class="w-full rounded-lg border border-[#84b6f4] bg-[#fcffff] text-[#005187] px-2 py-2 focus:ring-[#4d82bc] focus:border-[#4d82bc]">
                    <option value="">Todos</option>
                    @foreach ($anios as $a)
                        <option value="{{ $a }}">{{ $a }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-semibold text-[#005187]">Trimestre:</label>
                <select x-model="trimestre" @change="actualizarURL"
                    class="w-full rounded-lg border border-[#84b6f4] bg-[#fcffff] text-[#005187] px-2 py-2 focus:ring-[#4d82bc] focus:border-[#4d82bc]">
                    <option value="">Todos</option>
                    <template x-for="p in periodosFiltrados" :key="p.id">
                        <option :value="p.numero" x-text="'Trimestre ' + p.numero" :selected="trimestre == p.numero">
                        </option>
                    </template>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" x-model="historico" @change="actualizarURL" id="historico"
                    class="rounded border-[#84b6f4] text-[#4d82bc] focus:ring-[#4d82bc]">
                <label for="historico" class="text-sm font-semibold text-[#005187]">Registros Históricos</label>
            </div>
        </div>

        {{-- 🎛 Botones de acción --}}
        <div class="mb-4 flex flex-wrap justify-between items-center gap-2">
            <button @click="
                estado = '';
                sala = '';
                anio = '';
                trimestre = '';
                historico = false;
                actualizarURL();
            "
                class="bg-[#c4dafa] hover:bg-[#84b6f4] text-[#005187] px-4 py-2 rounded-lg shadow text-sm transition transform hover:scale-105">
                <img src="{{ asset('icons/filtro.svg') }}" alt="Filtro" class="w-5 h-5">
            </button>

            <form action="{{ route('incidencias.exportar.pdf') }}" method="GET" class="flex gap-2 flex-wrap">
                <input type="hidden" name="estado" :value="estado">
                <input type="hidden" name="room_id" :value="sala">
                <input type="hidden" name="anio" :value="anio">
                <input type="hidden" name="trimestre" :value="trimestre">
                <input type="hidden" name="historico" x-bind:value="historico ? 1 : ''">

                <button type="submit"
                    class="bg-[#005187] hover:bg-[#4d82bc] text-white px-4 py-2 rounded-lg shadow text-sm transition transform hover:scale-105 flex items-center gap-2">
                    <img src="{{ asset('icons/download.svg') }}" alt="download" class="w-5 h-5">
                </button>
            </form>
        </div>

        <template x-if="historico">
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 px-4 py-3 rounded mb-4 text-sm">
                Mostrando solo incidencias fuera de los períodos académicos actuales.
            </div>
        </template>

        {{-- 📋 Tabla --}}
        <div class="overflow-x-auto bg-[#fcffff] dark:bg-[#1e293b] shadow rounded-lg">
            <table class="min-w-full text-sm text-left text-[#005187] dark:text-[#c4dafa]">
                <thead class="bg-[#c4dafa] text-[#005187]">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Título</th>
                        <th class="px-4 py-2">Sala</th>
                        <th class="px-4 py-2">Estado</th>
                        <th class="px-4 py-2">Fecha</th>
                        <th class="px-4 py-2">Ticket Jira</th>
                        <th class="px-4 py-2">Resuelta el</th>
                        <th class="px-4 py-2">Ver</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($incidencias as $incidencia)
                        <tr class="border-t border-gray-200 dark:border-gray-600">
                            <td class="px-4 py-2">{{ $incidencia->id }}</td>
                            <td class="px-4 py-2">{{ $incidencia->titulo }}</td>
                            <td class="px-4 py-2">{{ $incidencia->room->name ?? 'Sin sala' }}</td>
                            <td class="px-4 py-2">
                                @if ($incidencia->estado === 'resuelta')
                                    <img src="{{ asset('icons/check.svg') }}" alt="Resuelta" class="w-6 h-6 inline">
                                @elseif ($incidencia->estado === 'pendiente')
                                    <img src="{{ asset('icons/clock.svg') }}" alt="Pendiente" class="w-5 h-5 inline">
                                @elseif ($incidencia->estado === 'en_revision')
                                    <img src="{{ asset('icons/revision.svg') }}" alt="Revision" class="w-5 h-5 inline">
                                @elseif ($incidencia->estado === 'no_resuelta')
                                    <img src="{{ asset('icons/no_resuelta.svg') }}" alt="No Resuelta" class="w-6 h-6 inline">
                                @else
                                    ℹ️
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $incidencia->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2">{{ $incidencia->nro_ticket ?? '—' }}</td>
                            <td class="px-4 py-2">
                                {{ $incidencia->resuelta_en ? $incidencia->resuelta_en->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="px-4 py-2 space-x-2">
                                <a href="{{ route('incidencias.show', $incidencia) }}"
                                    class="bg-blue-100 hover:bg-blue-200 text-white px-1 py-1 rounded">
                                    <img src="{{ asset('icons/ver.svg') }}" alt="Ver" class="w-5 h-5 inline"></a>
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