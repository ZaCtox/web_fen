{{-- Inicio de Salas.blade.php --}}
@section('title', 'Salas')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
            Salas Registradas
        </h2>
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

        {{-- Header acciones --}}
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
            <a href="{{ route('rooms.create') }}"
                class="inline-block bg-[#005187] hover:bg-[#4d82bc] text-white font-medium px-4 py-2 rounded-lg shadow transition duration-200">
                <img src="{{ asset('icons/agregar.svg') }}" alt="nueva" class="w-5 h-5">
            </a>

            {{-- Filtro de búsqueda en tiempo real --}}
            <div class="w-full sm:w-1/2">
                <label class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4] mb-1">
                    Buscar por nombre o ubicación:
                </label>
                <input type="text" x-model="search" placeholder="Ej: Edificio Norte o Sala 101" class="w-full px-3 py-2 border border-[#c4dafa] rounded-lg shadow-sm focus:ring-2 focus:ring-[#84b6f4] focus:outline-none
                              dark:bg-gray-800 dark:text-white dark:border-gray-600" />
            </div>
        </div>

        {{-- Tabla de resultados --}}
        <template x-if="filtradas.length > 0">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto text-sm text-[#005187] dark:text-[#fcffff]">
                    <thead class="bg-[#c4dafa]/50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Nombre</th>
                            <th class="px-4 py-2 text-left">Ubicación</th>
                            <th class="px-4 py-2 text-left">Ficha Técnica</th>
                            <th class="px-4 py-2 text-left">Clases Asignadas</th>
                            <th class="px-4 py-2 text-right w-40">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="room in filtradas" :key="room.id">
                            <tr
                                class="border-b border-[#c4dafa]/60 dark:border-gray-600 hover:bg-[#c4dafa]/20 transition">
                                <td class="px-4 py-2 font-medium" x-text="room.name"></td>
                                <td class="px-4 py-2" x-text="room.location"></td>
                                <td class="px-4 py-2">
                                    <a :href="`/rooms/${room.id}#ficha`"
                                        class="inline-flex items-center text-sm  hover:bg-[#84b6f4]/30  font-medium rounded-lg px-2 py-1 transition">
                                        <span>Ver</span>
                                        <img src="{{ asset('icons/ficha.svg') }}" alt="Ficha" class=" ml-1 w-5 h-5">
                                    </a>
                                </td>
                                <td class="px-4 py-2">
                                    <a :href="`/rooms/${room.id}#clases`"
                                        class="inline-flex items-center text-sm hover:bg-[#4d82bc]/30 font-medium rounded-lg px-2 py-1 transition">
                                        <span>Ver</span>
                                        <img src="{{ asset('icons/class.svg') }}" alt="Clases" class=" ml-1 w-5 h-5">
                                    </a>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-2">
                                        <a :href="`/rooms/${room.id}/edit`"
                                            class="inline-flex items-center justify-center px-3 py-1 hover:bg-[#84b6f4]/30 rounded-lg text-xs font-medium transition w-full sm:w-auto">
                                            <img src="{{ asset('icons/edit.svg') }}" alt="Editar" class=" ml-1 w-5 h-5">
                                        </a>

                                        {{-- IMPORTANTE: usar class="form-eliminar" para SweetAlert de confirmación --}}
                                        <form :action="`/rooms/${room.id}`" method="POST" class="form-eliminar">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center px-3 py-1 hover:bg-[#84b6f4]/30 rounded-lg text-xs font-medium transition w-full sm:w-auto">
                                                <img src="{{ asset('icons/trash.svg') }}" alt="Borrar"
                                                    class=" ml-1 w-4 h-4">
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
            <p class="mt-6 text-center text-[#4d82bc] dark:text-gray-400">
                😕 No se encontraron salas que coincidan con la búsqueda.
            </p>
        </template>

        {{-- Paginación (server-side) --}}
        <div class="mt-6">
            {{ $rooms->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
