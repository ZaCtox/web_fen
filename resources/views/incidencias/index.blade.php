{{-- Inicio de Incidencias.blade.php --}}
@section('title', 'Incidencias')
<x-app-layout>
    <x-slot name="header">
<<<<<<< Updated upstream
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
=======
<<<<<<< Updated upstream
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
=======
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
>>>>>>> Stashed changes
>>>>>>> Stashed changes
            Bitácora de Incidencias
        </h2>
    </x-slot>

<<<<<<< Updated upstream
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
=======
<<<<<<< Updated upstream
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
                <a href="{{ route('incidencias.exportar.pdf', request()->query()) }}"
                   class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm">
                    Exportar PDF
                </a>
            </div>

            {{-- Filtros --}}
            <form method="GET" class="grid grid-cols-1 sm:grid-cols-6 gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar..."
                       class="rounded border-gray-300 shadow-sm">

                <select name="estado" class="rounded border-gray-300 shadow-sm">
                    <option value="">-- Estado --</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="resuelta" {{ request('estado') == 'resuelta' ? 'selected' : '' }}>Resuelta</option>
=======
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
>>>>>>> Stashed changes
                </select>

<<<<<<< Updated upstream
                <select name="room_id" class="rounded border-gray-300 shadow-sm">
                    <option value="">-- Sala --</option>
                    @foreach($salas as $sala)
                        <option value="{{ $sala->id }}" {{ request('room_id') == $sala->id ? 'selected' : '' }}>
                            {{ $sala->name }}
=======
            <div>
                <label class="text-sm font-semibold text-[#005187]">Trimestre:</label>
                <select x-model="trimestre" @change="actualizarURL"
                    class="w-full rounded-lg border border-[#84b6f4] bg-[#fcffff] text-[#005187] px-2 py-2 focus:ring-[#4d82bc] focus:border-[#4d82bc]">
                    <option value="">Todos</option>
                    <template x-for="p in periodosFiltrados" :key="p.id">
                        <option :value="p.numero" x-text="'Trimestre ' + p.numero" :selected="trimestre == p.numero">
>>>>>>> Stashed changes
                        </option>
>>>>>>> Stashed changes
                    @endforeach
                </select>
            </div>

<<<<<<< Updated upstream
            <div>
                <label class="text-sm text-gray-700 dark:text-gray-300">Año:</label>
                <select x-model="anio" @change="actualizarURL" class="w-full rounded dark:bg-gray-800 dark:text-white">
                    <option value="">Todos</option>
                    @foreach ($anios as $a)
                        <option value="{{ $a }}">{{ $a }}</option>
=======
<<<<<<< Updated upstream
                <select name="period_id" class="rounded border-gray-300 shadow-sm">
                    <option value="">-- Periodo --</option>
                    @foreach($periodos as $p)
                        <option value="{{ $p->id }}" {{ request('period_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nombre_completo }}
                        </option>
>>>>>>> Stashed changes
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

<<<<<<< Updated upstream
            <div class="flex items-center gap-2">
                <input type="checkbox" x-model="historico" @change="actualizarURL" id="historico"
                    class="rounded border-gray-300 dark:bg-gray-800">
                <label for="historico" class="text-sm text-gray-700 dark:text-gray-300">Ver registros históricos</label>
=======
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Filtrar
=======
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
>>>>>>> Stashed changes
                </button>
            </form>

<<<<<<< Updated upstream
            {{-- Tabla --}}
            <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
=======
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
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded">
                                    @if ($incidencia->estado === 'resuelta')
                                        <img src="{{ asset('icons/check.svg') }}" alt="Resuelta" class="w-6 h-6  inline">
                                    @elseif ($incidencia->estado === 'pendiente')
                                        <img src="{{ asset('icons/clock.svg') }}" alt="Pendiente" class="w-5 h-5  inline">
                                    @elseif ($incidencia->estado === 'en_revision')
                                        <img src="{{ asset('icons/revision.svg') }}" alt="Revision" class="w-5 h-5  inline">
                                    @elseif ($incidencia->estado === 'no_resuelta')
                                        <img src="{{ asset('icons/no_resuelta.svg') }}" alt="No Resuelta"
                                            class="w-6 h-6  inline">
                                    @else
                                        ℹ️
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 py-2">{{ $incidencia->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2">{{ $incidencia->nro_ticket ?? '—' }}</td>
                            <td class="px-4 py-2">
                                {{ $incidencia->resuelta_en ? $incidencia->resuelta_en->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="px-4 py-2">
                                <a href="{{ route('incidencias.show', $incidencia) }}"
                                    class="inline-block  hover:bg-[#4d82bc] text-white px-2 py-1 rounded text-sm shadow">
                                    <img src="{{ asset('icons/revision.svg') }}" alt="Revision" class="w-5 h-5  inline">
                                </a>
                            </td>
                        </tr>
                    @empty
>>>>>>> Stashed changes
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
                                        $periodo = $periodos->first(fn($p) =>
                                            $incidencia->created_at >= $p->fecha_inicio &&
                                            $incidencia->created_at <= $p->fecha_fin
                                        );
                                    @endphp
                                    {{ $periodo ? $periodo->nombre_completo : 'Fuera de rango' }}
                                </td>
                                <td class="px-4 py-2">
                                    @if($incidencia->imagen)
                                        <img src="{{ $incidencia->imagen }}" alt="Incidencia" class="w-24 h-auto rounded">
                                    @else
                                        <span class="text-sm text-gray-400 italic">Sin imagen</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 space-y-1">
                                    @if($incidencia->estado === 'pendiente')
                                        <form action="{{ route('incidencias.update', $incidencia) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button
                                                class="bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700 text-sm w-full">
                                                Marcar como resuelta
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('incidencias.show', $incidencia) }}"
                                           class="block bg-indigo-500 text-white px-2 py-1 rounded hover:bg-indigo-600 text-sm text-center">
                                            Ver
                                        </a>
                                    @endif
                                    <form action="{{ route('incidencias.destroy', $incidencia) }}" method="POST"
                                          onsubmit="return confirm('¿Estás seguro de eliminar esta incidencia?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm w-full">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Paginación --}}
                <div class="mt-4 flex justify-center">
                    {{ $incidencias->links() }}
                </div>
>>>>>>> Stashed changes
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