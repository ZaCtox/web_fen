<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">periods Académicos</h2>
    </x-slot>

    <div class="p-6">
        <a href="{{ route('periods.create') }}"
            class="mb-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            + Nuevo period
        </a>

        <table class="w-full table-auto text-sm text-gray-700 dark:text-gray-200">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left">period</th>
                    <th class="px-4 py-2 text-left">Inicio</th>
                    <th class="px-4 py-2 text-left">Término</th>
                    <th class="px-4 py-2 text-left">Estado</th>
                    <th class="px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($periods as $period)
                    <tr class="border-b border-gray-200 dark:border-gray-600">
                        <td class="px-4 py-2">{{ $period->nombre_completo }}</td>
                        <td class="px-4 py-2">{{ $period->fecha_inicio->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">{{ $period->fecha_fin->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 text-xs rounded {{ $period->activo ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                {{ $period->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-4 py-2 space-x-2">
                            <a href="{{ route('periods.edit', $period) }}" class="text-blue-600 hover:underline">Editar</a>
                            <form action="{{ route('periods.destroy', $period) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline"
                                    onclick="return confirm('¿Eliminar period?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>