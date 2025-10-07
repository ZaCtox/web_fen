{{-- Salas de Postgrado FEN --}}
@section('title', 'Salas')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
            Información de salas
        </h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto" x-data="{
            search: '',
            rooms: @js($rooms),
            get filtradas() {
                const q = this.search.toLowerCase();
                return this.rooms.filter(r => 
                    r.name.toLowerCase().includes(q) ||
                    r.location.toLowerCase().includes(q) ||
                    (r.description || '').toLowerCase().includes(q)
                );
            }
        }">

        <!-- Buscador -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <div class="flex w-full sm:w-auto gap-3 items-center">
                <input x-model="search" type="text" placeholder="Buscar por nombre, ubicación o descripción..." class="w-full sm:w-[350px] px-4 py-2 rounded-lg border border-gray-300 
                           dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                <button type="button" @click="search=''" class="px-3 py-2 rounded-lg bg-gray-200 text-gray-800 
                           dark:bg-gray-700 dark:text-gray-100">
                    Limpiar
                </button>
            </div>
        </div>

        <!-- Sin resultados -->
        <template x-if="filtradas.length === 0">
            <div class="rounded-lg border border-dashed p-6 text-center text-gray-500 dark:text-gray-300">
                😕 No hay salas que coincidan con tu búsqueda.
            </div>
        </template>

        {{-- Tabla --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
                <thead class="bg-[#c4dafa] dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-center font-semibold text-[#005187]">Nombre</th>
                        <th class="px-4 py-2 text-center font-semibold text-[#005187]">Ubicación</th>
                        <th class="px-4 py-2 text-center font-semibold text-[#005187]">Capacidad</th>
                        <th class="px-4 py-2 text-center font-semibold text-[#005187]">Descripción</th>
                        <th class="px-4 py-2 text-center font-semibold text-[#005187]">Ver ficha</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="room in filtradas" :key="room.id">
                        <tr
                            class="border-b border-gray-200 dark:border-gray-600 
                                   hover:bg-[#e3f2fd] dark:hover:bg-gray-700 
                                   hover:border-l-4 hover:border-l-[#4d82bc]
                                   hover:-translate-y-0.5 hover:shadow-md
                                   transition-all duration-200 group cursor-pointer">
                            <td class="px-4 py-2 text-center font-medium group-hover:text-[#005187] transition-colors" x-text="room.name"></td>
                            <td class="px-4 py-2 text-center" x-text="room.location"></td>
                            <td class="px-4 py-2 text-center" x-text="room.capacity"></td>
                            <td class="px-4 py-2 text-center text-sm text-gray-600 dark:text-gray-400" x-text="room.description"></td>
                            <td class="px-4 py-2 text-center">
                                <a :href="'{{ route('public.rooms.show', ':id') }}'.replace(':id', room.id)"
                                    class="inline-flex items-center justify-center px-3 py-2 rounded-full hover:bg-[#005187] text-white shadow transition-all duration-200"
                                    title="Ver ficha de la sala">
                                    <img src="{{ asset('icons/ficha.svg') }}" alt="back" class="w-5 h-5">
                                </a>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
    </div>

    {{-- Footer --}}
    <footer>
        @include('components.footer')
    </footer>
</x-app-layout>