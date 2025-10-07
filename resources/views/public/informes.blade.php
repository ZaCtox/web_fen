@section('title', 'Archivos')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Archivos</h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto" x-data="{
        search: '',
        selectedMagister: '',
        selectedUser: '',
        informes: @js($informes),
        magisters: @js($informes->pluck('magister')->unique()->filter()->values()),
        users: @js($informes->pluck('user')->unique()->filter()->values()),
        get filtrados() {
            const q = this.search.toLowerCase();
            return this.informes.filter(i =>
                (i.nombre.toLowerCase().includes(q) ||
                 (i.magister ? i.magister.nombre.toLowerCase() : 'todos').includes(q) ||
                 (i.user ? i.user.name.toLowerCase() : '').includes(q)) &&
                (this.selectedMagister === '' || (i.magister && i.magister.nombre === this.selectedMagister)) &&
                (this.selectedUser === '' || (i.user && i.user.name === this.selectedUser))
            );
        }
    }">

        {{-- 🔍 Filtros --}}
        <div class="mb-4 grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4]">Buscar:</label>
                <input type="text" x-model="search" placeholder="Nombre del archivo..."
                    class="w-full rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-800 text-[#005187] dark:text-[#84b6f4] px-3 py-2 focus:ring-[#4d82bc] focus:border-[#4d82bc]">
            </div>

            <div>
                <label class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4]">Programa:</label>
                <select x-model="selectedMagister"
                    class="w-full rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-800 text-[#005187] dark:text-[#84b6f4] px-3 py-2 focus:ring-[#4d82bc] focus:border-[#4d82bc]">
                    <option value="">Todos</option>
                    <template x-for="m in magisters" :key="m.id">
                        <option x-text="m.nombre" :value="m.nombre"></option>
                    </template>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4]">Autor:</label>
                <select x-model="selectedUser"
                    class="w-full rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-800 text-[#005187] dark:text-[#84b6f4] px-3 py-2 focus:ring-[#4d82bc] focus:border-[#4d82bc]">
                    <option value="">Todos</option>
                    <template x-for="u in users" :key="u.id">
                        <option x-text="u.name" :value="u.name"></option>
                    </template>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" @click="search=''; selectedMagister=''; selectedUser=''"
                    class="flex justify-center items-center bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow transition-all duration-200">
                    <img src="{{ asset('icons/filtro.svg') }}" class="w-6 h-6" alt="Filtro">
                </button>
            </div>
        </div>

        {{-- Sin resultados --}}
        <template x-if="filtrados.length === 0">
            <div class="rounded-lg border border-dashed p-6 text-center text-gray-500 dark:text-gray-300">
                😕 No hay archivos que coincidan con tu búsqueda.
            </div>
        </template>

        {{-- Tabla --}}
        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg">
            <table class="min-w-full text-sm text-left text-[#005187] dark:text-[#fcffff]">
                <thead class="bg-[#c4dafa] dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2">Nombre</th>
                        <th class="px-4 py-2">Dirigido a</th>
                        <th class="px-4 py-2">Subido por</th>
                        <th class="px-4 py-2">Fecha</th>
                        <th class="px-4 py-2 text-center">Descargar</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="informe in filtrados" :key="informe.id">
                        <tr
                            class="border-t border-[#c4dafa]/40 dark:border-gray-700 
                                   hover:bg-[#e3f2fd] dark:hover:bg-gray-700 
                                   hover:border-l-4 hover:border-l-[#4d82bc]
                                   hover:-translate-y-0.5 hover:shadow-md
                                   transition-all duration-200 group cursor-pointer">
                            <td class="px-4 py-2 font-medium group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200" x-text="informe.nombre"></td>
                            <td class="px-4 py-2" x-text="informe.magister ? informe.magister.nombre : 'Todos'"></td>
                            <td class="px-4 py-2" x-text="informe.user ? informe.user.name : '—'"></td>
                            <td class="px-4 py-2"
                                x-text="new Date(informe.created_at).toLocaleString('es-CL', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' })">
                            </td>
                            <td class="px-4 py-2 text-center">
                                <a :href="'{{ url('Archivos-FEN/download') }}/' + informe.id"
                                    class="inline-block bg-[#005187] hover:bg-[#4d82bc] text-white font-medium px-3 py-1 rounded-lg shadow transition duration-200">
                                    <img src="{{ asset('icons/download.svg') }}" alt="Descargar" class="w-4 h-4">
                                </a>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>