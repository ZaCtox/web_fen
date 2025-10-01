<<<<<<< Updated upstream
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
=======
{{-- resources/views/usuarios/index.blade.php --}}
@section('title', 'Usuarios')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
>>>>>>> Stashed changes
            Listado de Usuarios
        </h2>
    </x-slot>

<<<<<<< Updated upstream
    @php
        // Si no pasas $rol desde el controlador, define aquí de forma segura:
        $esAdmin = auth()->user()?->rol === 'administrativo';
    @endphp

    @if($esAdmin)
        <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full text-sm text-gray-700 dark:text-gray-200">
                    <thead class="bg-gray-100 dark:bg-gray-700">
=======
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
            <a href="{{ route('register') }}"
                class="inline-block bg-[#005187] hover:bg-[#4d82bc] text-white font-medium px-4 py-2 rounded-lg shadow transition duration-200">
                <img src="{{ asset('icons/agregar.svg') }}" alt="nuevo usuario" class="w-5 h-5">
            </a>

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
>>>>>>> Stashed changes
                        <tr>
                            <th class="px-4 py-2 text-left">Nombre</th>
                            <th class="px-4 py-2 text-left">Correo</th>
                            <th class="px-4 py-2 text-left">Rol</th>
<<<<<<< Updated upstream
                            <th class="px-4 py-2 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $usuario)
                            <tr class="border-b border-gray-200 dark:border-gray-600">
                                <td class="px-4 py-2">{{ $usuario->name }}</td>
                                <td class="px-4 py-2">{{ $usuario->email }}</td>
                                @php $rolTexto = ucwords(str_replace('_', ' ', $usuario->rol)); @endphp
                                <td class="px-4 py-2">{{ $rolTexto }}</td>
                                <td class="px-4 py-2">
                                    <div class="flex flex-wrap justify-end gap-2">
                                        @if(auth()->id() !== $usuario->id)
                                            {{-- Editar --}}
                                            <a href="{{ route('usuarios.edit', $usuario) }}"
                                               class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200"
                                               title="Editar usuario">✏️</a>

                                            {{-- Eliminar (usa SweetAlert desde alerts.js) --}}
                                            <form action="{{ route('usuarios.destroy', $usuario) }}"
                                                  method="POST"
                                                  class="form-eliminar">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                        class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded hover:bg-red-200"
                                                        title="Eliminar usuario">🗑️</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No hay usuarios registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
=======
                            <th class="px-4 py-2 text-right w-40">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="usuario in filtrados" :key="usuario.id">
                            <tr
                                class="border-b border-[#c4dafa]/60 dark:border-gray-600 hover:bg-[#c4dafa]/20 transition">
                                <td class="px-4 py-2 font-medium" x-text="usuario.name"></td>
                                <td class="px-4 py-2" x-text="usuario.email"></td>
                                <td class="px-4 py-2 capitalize" x-text="usuario.rol.replace('_',' ')"></td>
                                <td class="px-4 py-2">
                                    <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-2"
                                        x-show="usuario.id !== authId">
                                        {{-- Editar --}}
                                        <a :href="`/usuarios/${usuario.id}/edit`"
                                            class="inline-flex items-center justify-center px-3 py-1 hover:bg-[#84b6f4]/30 rounded-lg text-xs font-medium transition w-full sm:w-auto">
                                            <img src="{{ asset('icons/edit.svg') }}" alt="Editar" class="w-5 h-5">
                                        </a>

                                        {{-- Eliminar --}}
                                        <form :action="`/usuarios/${usuario.id}`" method="POST" class="form-eliminar">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center px-3 py-1 hover:bg-[#84b6f4]/30 rounded-lg text-xs font-medium transition w-full sm:w-auto">
                                                <img src="{{ asset('icons/trash.svg') }}" alt="Eliminar"
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
>>>>>>> Stashed changes
</x-app-layout>