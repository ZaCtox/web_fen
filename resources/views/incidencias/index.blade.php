<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Bitácora de Incidencias
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                <a href="{{ route('incidencias.estadisticas') }}"
                    class="bg-indigo-500 text-white font-semibold py-2 px-4 rounded hover:bg-indigo-600 text-center w-full sm:w-auto mb-2 sm:mb-0">
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
            <form method="GET" class="grid grid-cols-1 sm:grid-cols-6 gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar..."
                    class="rounded border-gray-300 shadow-sm">

                <select name="estado" class="rounded border-gray-300 shadow-sm">
                    <option value="">-- Estado --</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="resuelta" {{ request('estado') == 'resuelta' ? 'selected' : '' }}>Resuelta</option>
                </select>

                <input type="text" name="sala" value="{{ request('sala') }}" placeholder="Sala"
                    class="rounded border-gray-300 shadow-sm">

                <select name="semestre" class="rounded border-gray-300 shadow-sm">
                    <option value="">-- Semestre --</option>
                    <option value="1" {{ request('semestre') == '1' ? 'selected' : '' }}>1</option>
                    <option value="2" {{ request('semestre') == '2' ? 'selected' : '' }}>2</option>
                </select>

                <input type="number" name="anio" value="{{ request('anio') }}" placeholder="Año"
                    class="rounded border-gray-300 shadow-sm">

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Filtrar
                </button>
            </form>

            <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left">Título</th>
                            <th class="px-4 py-2 text-left">Sala</th>
                            <th class="px-4 py-2 text-left">Registrado por</th>
                            <th class="px-4 py-2 text-left">Estado</th>
                            <th class="px-4 py-2 text-left">Fecha</th>
                            <th class="px-4 py-2 text-left">Imagen</th>
                            <th class="px-4 py-2 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach($incidencias as $incidencia)
                            <tr>
                                <td class="px-4 py-2">{{ $incidencia->titulo }}</td>
                                <td class="px-4 py-2">{{ $incidencia->sala }}</td>
                                <td class="px-4 py-2">{{ $incidencia->user->name ?? 'N/D' }}</td>
                                <td class="px-4 py-2">{{ ucfirst($incidencia->estado) }}</td>
                                <td class="px-4 py-2">{{ $incidencia->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-2">
                                    @if($incidencia->imagen)
                                        <img src="{{ asset('storage/incidencias/' . $incidencia->imagen) }}" alt="Incidencia"
                                            class="w-24 h-auto rounded">
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
                                                class="bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700 text-sm">
                                                Marcar como resuelta
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-green-600 font-semibold text-sm">Resuelta</span>
                                        <a href="{{ route('incidencias.show', $incidencia) }}"
                                            class="block bg-indigo-500 text-white px-2 py-1 rounded hover:bg-indigo-600 text-sm mt-1 text-center">
                                            Ver
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4 flex justify-center">
                    {{ $incidencias->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>