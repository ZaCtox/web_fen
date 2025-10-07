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
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
            <x-agregar :href="route('register')">
                <img src="{{ asset('icons/agregar.svg') }}" alt="Volver" class="w-5 h-5">
            </x-agregar>


            {{-- Búsqueda --}}
            <div class="w-full sm:w-1/2">
                <label class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4] mb-1">
                    Buscar por nombre, correo o rol:
                </label>
                <input type="text" x-model="search" placeholder="Ej: Juan Pérez o docente" class="w-full px-3 py-2 border border-[#c4dafa] rounded-lg shadow-sm focus:ring-2 focus:ring-[#84b6f4] focus:outline-none
                           dark:bg-gray-800 dark:text-white dark:border-gray-600" />
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
                                        <a :href="`/usuarios/${usuario.id}/edit`" class="hci-button hci-lift hci-focus-ring inline-flex items-center justify-center 
              w-8 px-2 py-2 bg-[#84b6f4] hover:bg-[#84b6f4]/80 
              rounded-lg text-xs font-medium transition-all duration-200">
                                            <img src="{{ asset('icons/editw.svg') }}" alt="Editar" class="w-4 h-4">
                                        </a>

                                        {{-- Eliminar --}}
                                        <form :action="`/usuarios/${usuario.id}`" method="POST" class="form-eliminar hci-confirm-button"
                                            data-confirm-title="Eliminar Usuario"
                                            data-confirm-message="¿Estás seguro de que quieres eliminar este usuario? Esta acción no se puede deshacer."
                                            data-confirm-type="danger">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="hci-button hci-lift hci-focus-ring inline-flex items-center justify-center 
                       w-8 px-2 py-2 bg-[#e57373] hover:bg-[#f28b82] 
                       rounded-lg text-xs font-medium transition-all duration-200">
                                                <img src="{{ asset('icons/trashw.svg') }}" alt="Eliminar"
                                                    class="w-4 h-4">
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
            <p class="mt-6 text-center text-[#4d82bc] dark:text-gray-400">
                😕 No se encontraron usuarios que coincidan con la búsqueda.
            </p>
        </template>
    </div>
</x-app-layout>