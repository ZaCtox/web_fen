<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">🏫 Salas Registradas</h2>
    </x-slot>

    <div class="p-6" x-data="{
        search: '',
        salas: @js($rooms->items()),
        get filtradas() {
            return this.salas.filter(s =>
                s.name.toLowerCase().includes(this.search.toLowerCase()) ||
                s.location.toLowerCase().includes(this.search.toLowerCase())
            );
        }
    }">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
            <a href="{{ route('rooms.create') }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                ➕ Nueva Sala
            </a>

            {{-- Filtro de búsqueda en tiempo real --}}
            <div class="w-full px-10 sm:w-1/2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar por nombre o ubicación:</label>
                <input type="text" x-model="search"
                    placeholder="Ej: Edificio Norte o Sala 101"
                    class="w-full px-3 py-2 border rounded dark:bg-gray-800 dark:text-white dark:border-gray-600"
                />
            </div>
        </div>

        {{-- Tabla de resultados --}}
        <template x-if="filtradas.length > 0">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto text-sm text-gray-700 dark:text-gray-200">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Nombre</th>
                            <th class="px-4 py-2 text-left">Ubicación</th>
                            <th class="px-4 py-2 text-left">Detalles</th>
                            <th class="px-4 py-2 text-left">Clases Asignadas</th>
                            <th class="px-4 py-2 text-right w-40">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="room in filtradas" :key="room.id">
                            <tr class="border-b border-gray-200 dark:border-gray-600">
                                <td class="px-4 py-2" x-text="room.name"></td>
                                <td class="px-4 py-2" x-text="room.location"></td>
                                <td class="px-4 py-2">
                                    <a :href="`/rooms/${room.id}#detalles`"
                                        class="inline-flex items-center text-sm text-green-600 hover:underline">
                                        ⚙️ Ver Detalles
                                    </a>
                                </td>
                                <td class="px-4 py-2">
                                    <a :href="`/rooms/${room.id}#clases`"
                                        class="inline-flex items-center text-indigo-600 hover:underline text-sm">
                                        📚 Ver Clases
                                    </a>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-2">
                                        <a :href="`/rooms/${room.id}/edit`"
                                            class="inline-flex items-center justify-center px-3 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded text-xs w-full sm:w-auto">
                                            ✏️ Editar
                                        </a>
                                        <form :action="`/rooms/${room.id}`" method="POST"
                                            @submit.prevent="if(confirm('¿Eliminar sala?')) $el.submit()">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center px-3 py-1 bg-red-100 text-red-700 hover:bg-red-200 rounded text-xs w-full sm:w-auto">
                                                🗑️ Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </template>

        {{-- Sin resultados --}}
        <template x-if="filtradas.length === 0">
            <p class="mt-6 text-center text-gray-600 dark:text-gray-400">😕 No se encontraron salas que coincidan con la búsqueda.</p>
        </template>
    </div>
</x-app-layout>
