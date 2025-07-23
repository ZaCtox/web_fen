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
                </select>

                <select name="room_id" class="rounded border-gray-300 shadow-sm">
                    <option value="">-- Sala --</option>
                    @foreach($salas as $sala)
                        <option value="{{ $sala->id }}" {{ request('room_id') == $sala->id ? 'selected' : '' }}>
                            {{ $sala->name }}
                        </option>
                    @endforeach
                </select>

                <select name="period_id" class="rounded border-gray-300 shadow-sm">
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

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Filtrar
                </button>
            </form>

            {{-- Tabla --}}
            <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
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
            </div>
        </div>
    </div>
</x-app-layout>
