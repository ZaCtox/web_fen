<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Salas Registradas</h2>
    </x-slot>

    <div class="p-6">
        <a href="{{ route('rooms.create') }}"
            class="mb-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            + Nueva Sala
        </a>

        <table class="w-full table-auto text-sm text-gray-700 dark:text-gray-200">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left">Nombre</th>
                    <th class="px-4 py-2 text-left">Ubicación</th>
                    <th class="px-4 py-2 text-left">Capacidad</th>
                    <th class="px-4 py-2 text-left">Usos Académicos</th>
                    <th class="px-4 py-2 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rooms as $room)
                    <tr class="border-b border-gray-200 dark:border-gray-600">
                        <td class="px-4 py-2">{{ $room->name }}</td>
                        <td class="px-4 py-2">{{ $room->location }}</td>
                        <td class="px-4 py-2">{{ $room->capacity }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('rooms.show', $room) }}" class="text-indigo-600 hover:underline">Ver
                                asignaciones</a>

                        </td>
                        <td class="px-4 py-2 space-x-2">
                            <a href="{{ route('rooms.edit', $room) }}" class="text-blue-600 hover:underline">Editar</a>
                            <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline"
                                    onclick="return confirm('¿Eliminar sala?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>