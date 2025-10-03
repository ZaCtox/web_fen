{{-- Lista de Informes --}}
@section('title', 'Informes')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Registros</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4" x-data="{
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

        {{-- Cabecera: Agregar + Filtros (horizontal en desktop, vertical en móvil) --}}
        <div class="mb-4 flex flex-wrap items-end justify-between gap-4">
            {{-- Botón Agregar --}}
            <div>
                <a href="{{ route('informes.create') }}"
                   class="inline-flex items-center gap-2 bg-[#005187] hover:bg-[#4d82bc] text-white font-medium px-4 py-2 rounded-lg shadow transition transform hover:scale-105">
                    <img src="{{ asset('icons/agregar.svg') }}" alt="Agregar" class="w-5 h-5">
                </a>
            </div>

            {{-- Filtros --}}
            <div class="flex flex-wrap gap-4 flex-1 justify-end">
                <div class="min-w-[180px]">
                    <label class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4]">Buscar:</label>
                    <input type="text" x-model="search" placeholder="Nombre del informe..."
                        class="w-full rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-800 text-[#005187] dark:text-[#84b6f4] px-3 py-2 focus:ring-[#4d82bc] focus:border-[#4d82bc]">
                </div>

                <div class="min-w-[300px]">
                    <label class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4]">Programa:</label>
                    <select x-model="selectedMagister"
                        class="w-full rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-800 text-[#005187] dark:text-[#84b6f4] px-3 py-2 focus:ring-[#4d82bc] focus:border-[#4d82bc]">
                        <option value="">Todos</option>
                        <template x-for="m in magisters" :key="m.id">
                            <option x-text="m.nombre" :value="m.nombre"></option>
                        </template>
                    </select>
                </div>

                <div class="min-w-[200px]">
                    <label class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4]">Autor:</label>
                    <select x-model="selectedUser"
                        class="w-full rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-800 text-[#005187] dark:text-[#84b6f4] px-3 py-2 focus:ring-[#4d82bc] focus:border-[#4d82bc]">
                        <option value="">Todos</option>
                        <template x-for="u in users" :key="u.id">
                            <option x-text="u.name" :value="u.name"></option>
                        </template>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="button" @click="search=''; selectedMagister=''; selectedUser=''"
                        class="flex justify-center items-center bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow transition-all duration-200">
                        <img src="{{ asset('icons/filtro.svg') }}" class="w-6 h-6" alt="Filtro">
                    </button>
                </div>
            </div>
        </div>

        {{-- Sin resultados --}}
        <template x-if="filtrados.length === 0">
            <div class="rounded-lg border border-dashed p-6 text-center text-gray-500 dark:text-gray-300">
                😕 No hay informes que coincidan con tu búsqueda.
            </div>
        </template>

        {{-- Tabla de informes --}}
        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg" x-show="filtrados.length > 0">
            <table class="min-w-full table-auto text-sm text-[#005187] dark:text-[#fcffff]">
                <thead class="bg-[#c4dafa]/50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">Nombre</th>
                        <th class="px-4 py-2 text-left">Dirigido a</th>
                        <th class="px-4 py-2 text-left">Subido por</th>
                        <th class="px-4 py-2 text-left">Fecha</th>
                        <th class="px-4 py-2 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="informe in filtrados" :key="informe.id">
                        <tr
                            class="border-t border-[#c4dafa]/40 dark:border-gray-700 hover:bg-[#c4dafa]/20 dark:hover:bg-gray-700 transition">
                            <td class="px-4 py-2 font-medium" x-text="informe.nombre"></td>
                            <td class="px-4 py-2" x-text="informe.magister ? informe.magister.nombre : 'Todos'"></td>
                            <td class="px-4 py-2" x-text="informe.user ? informe.user.name : '—'"></td>
                            <td class="px-4 py-2"
                                x-text="new Date(informe.created_at).toLocaleString('es-CL', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' })">
                            </td>
                            <td class="px-4 py-2 flex justify-center gap-2">
                                {{-- Descargar --}}
                                <a :href="'{{ url('informes/download') }}/' + informe.id"
                                    class="inline-block bg-[#005187] hover:bg-[#4d82bc] text-white font-medium px-3 py-1 rounded-lg shadow transition duration-200">
                                    <img src="{{ asset('icons/download.svg') }}" alt="Descargar" class="w-5 h-5">
                                </a>

                                {{-- Editar --}}
                                <a :href="'{{ url('informes') }}/' + informe.id + '/edit'"
                                    class="inline-block bg-[#005187] hover:bg-[#4d82bc] text-white font-medium px-3 py-1 rounded-lg shadow transition duration-200">
                                    <img src="{{ asset('icons/editw.svg') }}" alt="Editar" class="w-5 h-5">
                                </a>

                                {{-- Eliminar --}}
                                <form :action="'{{ url('informes') }}/' + informe.id" method="POST"
                                    class="form-eliminar">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                       class="inline-flex items-center justify-center px-3 py-1 bg-[#e57373] hover:bg-[#f28b82] text-white rounded-lg text-xs font-medium transition w-full sm:w-auto">
                                        <img src="{{ asset('icons/trashw.svg') }}" alt="Eliminar" class="w-4 h-4">
                                    </button>
                                </form>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
