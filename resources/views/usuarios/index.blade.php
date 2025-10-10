{{-- resources/views/usuarios/index.blade.php --}}
@section('title', 'Usuarios')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
            Listado de Usuarios
        </h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Usuarios', 'url' => '#']
    ]" />

    <div class="p-6" x-data="{
            search: '',
            usuarios: @js($usuarios->toArray()),
            authId: {{ auth()->id() }},
            get filtrados() {
                return this.usuarios.filter(u =>
                    u.name.toLowerCase().includes(this.search.toLowerCase()) ||
                    u.email.toLowerCase().includes(this.search.toLowerCase()) ||
                    u.rol.toLowerCase().includes(this.search.toLowerCase())
                );
            }
        }">

        {{-- Header con botón agregar y búsqueda --}}
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
            {{-- Botón Agregar --}}
            <a href="{{ route('register') }}"
                class="hci-button hci-lift hci-focus-ring inline-flex items-center gap-2 bg-[#4d82bc] hover:bg-[#005187] text-white px-4 py-2 rounded-lg shadow transition-all duration-200"
                title="Agregar nuevo usuario">
                <img src="{{ asset('icons/agregar.svg') }}" alt="Agregar" class="w-5 h-5">
            </a>

            {{-- Búsqueda --}}
            <div class="w-full sm:w-1/2">
                <label for="search" class="block text-sm font-semibold text-[#005187] dark:text-[#84b6f4] mb-2">
                    Buscar:
                </label>
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <img src="{{ asset('icons/filtro.svg') }}" alt="Buscar" class="h-4 w-4">
                        </div>
                        <input type="text" 
                               id="search"
                               x-model="search" 
                               placeholder="Nombre, correo o rol..." 
                               class="w-full pl-10 pr-3 py-2 border border-[#84b6f4] bg-[#fcffff] rounded-lg shadow-sm focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition dark:bg-gray-800 dark:text-white">
                    </div>
                    <button type="button" 
                            @click="search=''"
                            class="px-3 py-2 bg-[#84b6f4] hover:bg-[#005187] text-[#005187] rounded-lg shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2 transform hover:scale-105"
                            title="Limpiar búsqueda"
                            aria-label="Limpiar búsqueda">
                        <img src="{{ asset('icons/filterw.svg') }}" alt="Limpiar" class="w-5 h-5">
                    </button>
                </div>
            </div>
        </div>

        {{-- Tabla --}}
        <template x-if="filtrados.length > 0">
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto text-sm text-[#005187] dark:text-[#fcffff]">
                    <thead class="bg-[#c4dafa]/50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Nombre</th>
                            <th class="px-4 py-2 text-left">Correo</th>
                            <th class="px-4 py-2 text-left">Rol</th>
                            <th class="px-4 py-2 text-right w-40">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="usuario in filtrados" :key="usuario.id">
                            <tr
                                class="border-b border-[#c4dafa]/60 dark:border-gray-600 
                                       hover:bg-[#e3f2fd] dark:hover:bg-gray-700 
                                       hover:border-l-4 hover:border-l-[#4d82bc]
                                       hover:-translate-y-0.5 hover:shadow-md
                                       transition-all duration-200 group cursor-pointer">
                                <td class="px-4 py-2 font-medium group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200" x-text="usuario.name"></td>
                                <td class="px-4 py-2 group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200" x-text="usuario.email"></td>
                                <td class="px-4 py-2 capitalize group-hover:text-[#005187] dark:group-hover:text-[#84b6f4] transition-colors duration-200" x-text="usuario.rol.replace('_',' ')"></td>
                                <td class="px-4 py-2">
                                    <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-2"
                                        x-show="usuario.id !== authId">
                                        {{-- Editar --}}
                                        <a :href="`/usuarios/${usuario.id}/edit`"
                                            class="inline-flex items-center justify-center w-12 px-4 py-2.5 bg-[#84b6f4] hover:bg-[#4d82bc] text-white rounded-lg text-xs font-medium transition focus:outline-none focus:ring-2 focus:ring-[#84b6f4] focus:ring-offset-1"
                                            title="Editar usuario">
                                            <img src="{{ asset('icons/editw.svg') }}" alt="Editar" class="w-6 h-6">
                                        </a>

                                        {{-- Eliminar --}}
                                        <form :action="`/usuarios/${usuario.id}`" method="POST" 
                                              class="form-eliminar inline"
                                              data-confirm="¿Estás seguro de que quieres eliminar este usuario? Esta acción no se puede deshacer.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center w-12 px-4 py-2.5 bg-[#e57373] hover:bg-[#f28b82] text-white rounded-lg text-xs font-medium transition focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-1"
                                                title="Eliminar usuario">
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
        </template>

        {{-- Sin resultados --}}
        <template x-if="filtrados.length === 0">
            <div>
                <x-empty-state
                    type="no-results"
                    icon="👤"
                    title="No se encontraron usuarios"
                    message="Intenta con otros términos de búsqueda o verifica los filtros de rol."
                    secondaryActionText="Limpiar Búsqueda"
                    secondaryActionUrl="{{ route('usuarios.index') }}"
                    secondaryActionIcon="🔄"
                />
            </div>
        </template>
    </div>
</x-app-layout>


