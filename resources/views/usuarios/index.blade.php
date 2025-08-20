<x-app-layout>
    <x-slot name="header">
        @if(session('success'))
            <meta name="session-success" content="{{ session('success') }}">
        @endif

        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Listado de Usuarios</h2>
    </x-slot>
    <div id="success-message" data-message="{{ session('success') }}"></div>
    @if($rol === 'administrativo')
        <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full text-sm text-gray-700 dark:text-gray-200">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Nombre</th>
                            <th class="px-4 py-2 text-left">Correo</th>
                            <th class="px-4 py-2 text-left">Rol</th>
                            <th class="px-4 py-2 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $usuario)
                            <tr class="border-b border-gray-200 dark:border-gray-600">
                                <td class="px-4 py-2">{{ $usuario->name }}</td>
                                <td class="px-4 py-2">{{ $usuario->email }}</td>
                                @php
                                    $rolTexto = ucwords(str_replace('_', ' ', $usuario->rol));
                                @endphp
                                <td class="px-4 py-2">{{ $rolTexto }}</td>
                                <td class="px-4 py-2">
                                    <div class="flex flex-wrap justify-end gap-2">
                                        @if(Auth::id() !== $usuario->id)
                                            {{-- Botón Editar --}}
                                            <a href="{{ route('usuarios.edit', $usuario) }}"
                                                class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200"
                                                title="Editar usuario">
                                                ✏️
                                            </a>

                                            {{-- Botón Eliminar --}}
                                            <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST"
                                                class="form-eliminar">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded hover:bg-red-200"
                                                    title="Eliminar usuario">
                                                    🗑️
                                                </button>
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
    @vite(['resources/js/usuarios.js'])
</x-app-layout>