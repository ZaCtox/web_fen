<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Editar Usuario</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
            <form method="POST" action="{{ route('usuarios.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nombre</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full mt-1 rounded border-gray-300 dark:bg-gray-700 dark:text-white">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Correo</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full mt-1 rounded border-gray-300 dark:bg-gray-700 dark:text-white">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Rol</label>
                    <select name="rol" class="w-full mt-1 rounded border-gray-300 dark:bg-gray-700 dark:text-white">
                        <option value="docente" {{ old('rol') == 'docente' ? 'selected' : '' }}>Docente</option>
                        <option value="asistente" {{ old('rol') == 'asistente' ? 'selected' : '' }}>Asistente</option>
                        <option value="director_magister" {{ old('rol') == 'director_magister' ? 'selected' : '' }}>
                            Director Magíster</option>
                        <option value="director_administrativo" {{ old('rol') == 'director_administrativo' ? 'selected' : '' }}>Director Administrativo</option>
                        <option value="auxiliar" {{ old('rol') == 'auxiliar' ? 'selected' : '' }}>Auxiliar</option>
                    </select>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Guardar Cambios
                    </button>
                    <a href="{{ route('usuarios.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>