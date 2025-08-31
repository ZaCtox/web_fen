<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Información de salas</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4">
        <div x-data="{ search: '' }" class="bg-white dark:bg-gray-800 rounded shadow p-6">
            <input type="text" x-model="search" placeholder="Buscar sala por nombre..."
                class="w-full mb-4 px-3 py-2 border rounded dark:bg-gray-700 dark:text-white">

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-center">Nombre</th>
                            <th class="px-4 py-2 text-center">Ubicación</th>
                            <th class="px-4 py-2 text-center">Capacidad</th>
                            <th class="px-4 py-2 text-center">Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rooms as $room)
                            <tr class="border-b border-gray-200 dark:border-gray-600"
                                x-show="search === '' || '{{ strtolower($room->name) }}'.includes(search.toLowerCase())">
                                <td class="px-4 py-2 text-center">{{ $room->name }}</td>
                                <td class="px-4 py-2 text-center">{{ $room->location }}</td>
                                <td class="px-4 py-2 text-center">{{ $room->capacity }}</td>
                                <td class="px-4 py-2 text-center">{{ $room->description }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('components.footer')
</x-app-layout>
