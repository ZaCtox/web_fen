@section('title', 'Registros')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Archivos y Documentos</h2>
    </x-slot>

    <div class="p-6 max-w-7xl mx-auto" x-data="{
        search: '{{ request('search') }}',
        selectedTipo: '{{ request('tipo') }}',
        selectedMagister: '{{ request('magister_id') }}',
        selectedUser: '{{ request('user_id') }}',
        informes: @js($informes),
        magisters: @js($magisters),
        users: @js($users),
        tipos: @js($tipos),
        get filtrados() {
            const q = this.search.toLowerCase();
            return this.informes.filter(i =>
                (i.nombre.toLowerCase().includes(q) ||
                 (i.descripcion && i.descripcion.toLowerCase().includes(q))) &&
                (this.selectedTipo === '' || i.tipo === this.selectedTipo) &&
                (this.selectedMagister === '' || (i.magister_id && i.magister_id.toString() === this.selectedMagister)) &&
                (this.selectedUser === '' || (i.user_id && i.user_id.toString() === this.selectedUser))
            );
        },
        get hasFilters() {
            return this.search !== '' || this.selectedTipo !== '' || this.selectedMagister !== '' || this.selectedUser !== '';
        }
    }">

        {{-- 🔍 Filtros --}}
        <div class="mb-4 grid grid-cols-1 sm:grid-cols-5 gap-4 items-end">
            <div>
                <label for="search-informes" class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4] mb-2">Buscar:</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <img src="{{ asset('icons/filtro.svg') }}" alt="Buscar" class="h-4 w-4 text-gray-400">
                    </div>
                    <input id="search-informes" 
                           type="text" 
                           x-model="search" 
                           placeholder="Nombre del archivo..."
                           class="w-full pl-10 pr-3 py-2 rounded-lg border border-[#84b6f4] bg-[#fcffff] dark:bg-gray-800 text-[#005187] dark:text-[#84b6f4] focus:ring-[#4d82bc] focus:border-[#4d82bc] transition">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4]">Tipo:</label>
                <select x-model="selectedTipo"
                    class="w-full rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-800 text-[#005187] dark:text-[#84b6f4] px-3 py-2 focus:ring-[#4d82bc] focus:border-[#4d82bc]">
                    <option value="">Todos</option>
                    <template x-for="[key, label] in Object.entries(tipos)" :key="key">
                        <option :value="key" x-text="label"></option>
                    </template>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4]">Programa:</label>
                <select x-model="selectedMagister"
                    class="w-full rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-800 text-[#005187] dark:text-[#84b6f4] px-3 py-2 focus:ring-[#4d82bc] focus:border-[#4d82bc]">
                    <option value="">Todos</option>
                    <template x-for="m in magisters" :key="m.id">
                        <option :value="m.id" x-text="m.nombre"></option>
                    </template>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4]">Autor:</label>
                <select x-model="selectedUser"
                    class="w-full rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-800 text-[#005187] dark:text-[#84b6f4] px-3 py-2 focus:ring-[#4d82bc] focus:border-[#4d82bc]">
                    <option value="">Todos</option>
                    <template x-for="u in users" :key="u.id">
                        <option :value="u.id" x-text="u.name"></option>
                    </template>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" @click="search=''; selectedTipo=''; selectedMagister=''; selectedUser=''"
                    class="bg-[#84b6f4] hover:bg-[#005187] text-[#005187] px-4 py-2 rounded-lg shadow text-sm transition transform hover:scale-105"
                    title="Limpiar filtros">
                    <img src="{{ asset('icons/filterw.svg') }}" class="w-5 h-5" alt="Limpiar filtros">
                </button>
            </div>
        </div>

        {{-- Información de resultados --}}
        <div class="mb-4" x-show="filtrados.length > 0">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Mostrando <span x-text="filtrados.length"></span> archivos
                <span x-show="hasFilters">para los filtros seleccionados</span>
            </p>
        </div>

        {{-- Sin resultados - Solo cuando hay filtros activos --}}
        <template x-if="filtrados.length === 0 && hasFilters">
            <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <span class="text-6xl">🔍</span>
                <h3 class="text-xl text-[#005187] dark:text-[#4d82bc] font-semibold mt-4">
                    No hay archivos que coincidan con tu búsqueda
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">
                    Intenta ajustar los filtros o buscar con otros términos
                </p>
            </div>
        </template>

        {{-- Sin archivos - Solo cuando no hay filtros y no hay archivos --}}
        <template x-if="filtrados.length === 0 && !hasFilters && informes.length === 0">
            <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <span class="text-6xl">📁</span>
                <h3 class="text-xl text-[#005187] dark:text-[#4d82bc] font-semibold mt-4">
                    No hay archivos disponibles
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">
                    Los archivos se publicarán próximamente
                </p>
            </div>
        </template>

        {{-- Tabla --}}
        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg" x-show="filtrados.length > 0">
            <table class="min-w-full text-sm text-left text-[#005187] dark:text-[#fcffff]">
                <thead class="bg-[#c4dafa] dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2">Nombre</th>
                        <th class="px-4 py-2">Tipo</th>
                        <th class="px-4 py-2">Programa</th>
                        <th class="px-4 py-2">Autor</th>
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
                            <td class="px-4 py-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                      :class="{
                                          'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300': informe.tipo === 'calendario',
                                          'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300': informe.tipo === 'academico',
                                          'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300': informe.tipo === 'administrativo',
                                          'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300': informe.tipo === 'general'
                                      }"
                                      x-text="informe.tipo ? informe.tipo.charAt(0).toUpperCase() + informe.tipo.slice(1) : 'General'">
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                <template x-if="informe.magister">
                                    <span x-text="informe.magister.nombre"></span>
                                </template>
                                <template x-if="!informe.magister">
                                    <span>Todos</span>
                                </template>
                            </td>
                            <td class="px-4 py-2">
                                <template x-if="informe.user">
                                    <span x-text="informe.user.name"></span>
                                </template>
                                <template x-if="!informe.user">
                                    <span>—</span>
                                </template>
                            </td>
                            <td class="px-4 py-2"
                                x-text="new Date(informe.created_at).toLocaleString('es-CL', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' })">
                            </td>
                            <td class="px-4 py-2 text-center">
                                <a :href="'{{ url('Archivos-FEN/download') }}/' + informe.id"
                                    class="inline-flex items-center justify-center w-10 px-3 py-2 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg text-xs font-medium transition"
                                    title="Descargar archivo">
                                    <img src="{{ asset('icons/download.svg') }}" alt="Descargar" class="w-4 h-4">
                                </a>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Footer --}}
    <footer>
        @include('components.footer')
    </footer>
</x-app-layout>
