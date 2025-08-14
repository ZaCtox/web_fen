<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Períodos Académicos</h2>
    </x-slot>

    <div class="p-6">
        <a href="{{ route('periods.create') }}"
            class="mb-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            + Nuevo período
        </a>
        <div x-data="{ confirmar: false }" class="mt-2">
            <button @click="confirmar = true"
                class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded text-sm">
                🔄 Actualizar fechas al próximo año
            </button>

            <template x-if="confirmar">
                <div
                    class="mt-3 bg-yellow-100 border border-yellow-400 p-4 rounded text-sm text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100">
                    <p class="mb-2">
                        Esta acción actualizará <strong>todas las fechas de inicio y término</strong> de los períodos
                        actuales,
                        sumando 1 año completo. El número de trimestre y año académico (ej: Año 1, Año 2) no se
                        modifica.
                    </p>
                    <form method="POST" action="{{ route('periods.actualizarProximoAnio') }}" class="flex gap-2 mt-2">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            ✅ Confirmar actualización
                        </button>
                        <button type="button" @click="confirmar = false"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded text-gray-800 dark:bg-gray-700 dark:text-white">
                            ❌ Cancelar
                        </button>
                    </form>
                </div>
            </template>
        </div>
        @php
            $romanos = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI'];
            $agrupados = $periods
                ->sortBy([['anio', 'asc'], ['numero', 'asc']])
                ->groupBy('anio');
        @endphp

        @foreach ($agrupados as $anio => $porTrimestre)
            <div class="mt-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">🗓️ Año {{ $anio }}</h3>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto text-sm text-gray-700 dark:text-gray-200">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left">Trimestre</th>
                                <th class="px-4 py-2 text-left">Inicio</th>
                                <th class="px-4 py-2 text-left">Término</th>
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