<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Salas Registradas</h2>
    </x-slot>

    <div class="p-6">
        <a href="{{ route('rooms.create') }}"
            class="mb-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            + Nueva Sala
        </a>
        <form method="GET" class="mb-4 flex flex-col md:flex-row md:items-center gap-4">
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-300">Ubicación:</label>
                <input type="text" name="ubicacion" value="{{ $ubicacion }}"
                    class="w-full md:w-48 px-3 py-1 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-800 dark:text-white"
                    placeholder="Ej: Edificio Norte">
            </div>
            <div>
                <label class="block text-sm text-gray-700 dark:text-gray-300">Capacidad mínima:</label>
                <input type="number" name="capacidad" value="{{ $capacidad }}"
                    class="w-full md:w-32 px-3 py-1 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-800 dark:text-white"
                    placeholder="Ej: 30">
            </div>
            <div class="self-end md:self-auto">
                <x-button-fen>🔍 Filtrar</x-button-fen>
                <a href="{{ route('rooms.index') }}"
                    class="ml-2 text-sm text-gray-600 dark:text-gray-300 hover:underline">
                    Limpiar
                </a>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full table-auto text-sm text-gray-700 dark:text-gray-200">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">Nombre</th>
                        <th class="px-4 py-2 text-left">Ubicación</th>
                        <th class="px-4 py-2 text-left">Capacidad</th>
                        <th class="px-4 py-2 text-left">Usos Académicos</th>
                        <th class="px-4 py-2 text-right w-40">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rooms as $room)
                        <tr class="border-b border-gray-200 dark:border-gray-600">
                            <td class="px-4 py-2">{{ $room->name }}</td>
                            <td class="px-4 py-2">{{ $room->location }}</td>
                            <td class="px-4 py-2">{{ $room->capacity }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ route('rooms.show', $room) }}"
                                    class="inline-flex items-center text-indigo-600 hover:underline text-sm">
                                    Ver asignaciones
                                </a>
                            </td>
                            <td class="px-4 py-2">
                                <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-2">
                                    <a href="{{ route('rooms.edit', $room) }}"
                                        class="inline-flex items-center justify-center px-3 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded text-xs w-full sm:w-auto">
                                        ✏️ Editar
                                    </a>
                                    <form action="{{ route('rooms.destroy', $room) }}" method="POST"
                                        class="inline w-full sm:w-auto">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('¿Eliminar sala?')"
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
        <div class="mt-6">
            {{ $rooms->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>