<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Períodos Académicos</h2>
    </x-slot>

    <div class="p-6">
        <a href="{{ route('periods.create') }}"
            class="mb-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            + Nuevo período
        </a>

        @php
            $romanos = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI'];
            $agrupados = $periods
                ->sortBy([['anio', 'asc'], ['numero', 'asc']])
                ->groupBy('anio');
        @endphp

        @foreach ($agrupados as $anio => $porTrimestre)
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">🗓️ Año {{ $anio }}</h3>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto text-sm text-gray-700 dark:text-gray-200">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left">Trimestre</th>
                                <th class="px-4 py-2 text-left">Inicio</th>
                                <th class="px-4 py-2 text-left">Término</th>
                                <th class="px-4 py-2 text-left">Estado</th>
                                <th class="px-4 py-2 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($porTrimestre->sortBy('numero') as $period)
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <td class="px-4 py-2">Trimestre {{ $romanos[$period->numero] ?? $period->numero }}</td>
                                    <td class="px-4 py-2">{{ $period->fecha_inicio->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2">{{ $period->fecha_fin->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2">
                                        <span
                                            class="px-2 py-1 text-xs rounded {{ $period->activo ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                            {{ $period->activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="flex flex-col sm:flex-row sm:justify-start gap-2">
                                            <a href="{{ route('periods.edit', $period) }}"
                                                class="inline-flex items-center justify-center px-3 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded text-xs w-full sm:w-auto">
                                                ✏️ Editar
                                            </a>
                                            <form action="{{ route('periods.destroy', $period) }}" method="POST"
                                                class="inline w-full sm:w-auto">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('¿Eliminar período?')"
                                                    class="inline-flex items-center justify-center px-3 py-1 bg-red-100 text-red-700 hover:bg-red-200 rounded text-xs w-full sm:w-auto">
                                                    🗑️ Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>