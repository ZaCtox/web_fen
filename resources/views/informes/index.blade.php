{{-- Lista de Informes --}}
@section('title', 'Registros')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Registros</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Registros', 'url' => '#']
    ]" />

    <div class="py-6 max-w-7xl mx-auto px-4" x-data="{
        search: '',
        selectedMagister: '',
        selectedUser: '',
        selectedTipo: '',
        informes: @js($informes),
        magisters: @js($informes->pluck('magister')->unique()->filter()->values()),
        users: @js($informes->pluck('user')->unique()->filter()->values()),
        tipos: ['calendario', 'academico', 'administrativo', 'general'],
        get filtrados() {
            const q = this.search.toLowerCase();
            return this.informes.filter(i =>
                (i.nombre.toLowerCase().includes(q) ||
                 (i.magister ? i.magister.nombre.toLowerCase() : 'todos').includes(q) ||
                 (i.user ? i.user.name.toLowerCase() : '').includes(q)) &&
                (this.selectedMagister === '' || (i.magister && i.magister.nombre === this.selectedMagister)) &&
                (this.selectedUser === '' || (i.user && i.user.name === this.selectedUser)) &&
                (this.selectedTipo === '' || i.tipo === this.selectedTipo)
            );
        }
    }">

        <!-- Controles -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <a href="{{ route('informes.create') }}"
                class="inline-flex items-center justify-center gap-2 bg-[#4d82bc] hover:bg-[#005187] text-white px-6 py-3 rounded-lg shadow-md transition-all duration-200 font-semibold text-sm hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 hci-button-ripple hci-glow"
                aria-label="Agregar nuevo registro">
                <img src="{{ asset('icons/agregar.svg') }}" alt="" class="w-5 h-5">
                Agregar Registro
            </a>
            
            <div class="flex gap-3 items-center w-full sm:w-auto">
                <div class="relative flex-1 sm:flex-initial">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <img src="{{ asset('icons/filtro.svg') }}" alt="" class="h-5 w-5 opacity-60">
                    </div>
                    <input x-model="search" 
                           type="text" 
                           role="search"
                           aria-label="Buscar informes por nombre"
                           placeholder="Buscar por nombre"
                           class="w-full sm:w-[250px] pl-10 pr-4 py-3 rounded-lg border border-[#84b6f4] bg-[#fcffff] dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition hci-input-focus">
                </div>
                
                <select x-model="selectedMagister" 
                        class="px-4 py-3 pr-10 rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition text-sm font-medium min-w-[180px] hci-focus-ring"
                        aria-label="Filtrar por programa">
                    <option value="">Todos los programas</option>
                    <template x-for="m in magisters" :key="m.id">
                        <option x-text="m.nombre" :value="m.nombre"></option>
                    </template>
                </select>

                <select x-model="selectedUser" 
                        class="px-4 py-3 pr-10 rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition text-sm font-medium min-w-[160px] hci-focus-ring"
                        aria-label="Filtrar por autor">
                    <option value="">Todos los autores</option>
                    <template x-for="u in users" :key="u.id">
                        <option x-text="u.name" :value="u.name"></option>
                    </template>
                </select>

                <select x-model="selectedTipo" 
                        class="px-4 py-3 pr-10 rounded-lg border border-[#84b6f4] bg-white dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition text-sm font-medium min-w-[160px] hci-focus-ring"
                        aria-label="Filtrar por tipo">
                    <option value="">Todos los tipos</option>
                    <template x-for="tipo in tipos" :key="tipo">
                        <option :value="tipo" x-text="tipo.charAt(0).toUpperCase() + tipo.slice(1)"></option>
                    </template>
                </select>
                
                <button type="button" 
                        @click="search=''; selectedMagister=''; selectedUser=''; selectedTipo=''"
                        class="p-3 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 hover:scale-105 hci-button-ripple hci-glow"
                        title="Limpiar búsqueda y filtros"
                        aria-label="Limpiar búsqueda y filtros">
                    <img src="{{ asset('icons/filterw.svg') }}" alt="" class="w-5 h-5">
                </button>
            </div>
        </div>

        {{-- Sin resultados --}}
        <template x-if="filtrados.length === 0">
            <div>
                <x-empty-state
                    type="no-results"
                    icon="📄"
                    title="No se encontraron informes"
                    message="Intenta con otros términos de búsqueda o verifica los filtros aplicados."
                    secondaryActionText="Limpiar Búsqueda"
                    secondaryActionUrl="{{ route('informes.index') }}"
                    secondaryActionIcon="🔄"
                />
            </div>
        </template>

        {{-- Tabla de informes --}}
        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg" x-show="filtrados.length > 0">
            <table class="min-w-full table-auto text-sm text-[#005187] dark:text-[#fcffff]">
                <thead class="bg-[#c4dafa]/50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">Nombre</th>
                        <th class="px-4 py-2 text-left">Tipo</th>
                        <th class="px-4 py-2 text-left">Dirigido a</th>
                        <th class="px-4 py-2 text-left">Subido por</th>
                        <th class="px-4 py-2 text-left">Fecha</th>
                        <th class="px-4 py-2 text-center">Acciones</th>
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
                            <td class="px-4 py-2" x-text="informe.magister ? informe.magister.nombre : 'Todos'"></td>
                            <td class="px-4 py-2" x-text="informe.user ? informe.user.name : '—'"></td>
                            <td class="px-4 py-2"
                                x-text="new Date(informe.created_at).toLocaleString('es-CL', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' })">
                            </td>
                            <td class="px-4 py-2">
                                <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-2">
                                    {{-- Descargar --}}
                                    <a :href="'{{ url('informes/download') }}/' + informe.id"
                                        class="inline-flex items-center justify-center w-12 px-4 py-2.5 bg-[#4d82bc] hover:bg-[#005187] text-white rounded-lg text-xs font-medium transition focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-1"
                                        title="Descargar informe">
                                        <img src="{{ asset('icons/download.svg') }}" alt="Descargar" class="w-6 h-6">
                                    </a>

                                    {{-- Editar --}}
                                    <a :href="'{{ url('informes') }}/' + informe.id + '/edit'"
                                        class="inline-flex items-center justify-center w-12 px-4 py-2.5 bg-[#84b6f4] hover:bg-[#84b6f4]/80 text-white rounded-lg text-xs font-medium transition focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-1"
                                        title="Editar informe">
                                        <img src="{{ asset('icons/editw.svg') }}" alt="Editar" class="w-6 h-6">
                                    </a>

                                    {{-- Eliminar --}}
                                    <form :action="'{{ url('informes') }}/' + informe.id" method="POST"
                                        class="form-eliminar inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center w-12 px-4 py-2.5 bg-[#e57373] hover:bg-[#f28b82] text-white rounded-lg text-xs font-medium transition focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-1"
                                            title="Eliminar informe">
                                            <img src="{{ asset('icons/trashw.svg') }}" alt="Eliminar" class="w-6 h-6">
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>



