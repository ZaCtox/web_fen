<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
            Editar Usuario
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg p-6">
            <form method="POST" action="{{ route('usuarios.update', $usuario) }}">
                @csrf
                @method('PUT')

                {{-- Nombre --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4]">Nombre</label>
                    <input type="text" name="name" value="{{ old('name', $usuario->name) }}"
                        class="block w-full mt-1 px-3 py-2 border border-[#c4dafa] rounded-lg shadow-sm focus:ring-2 focus:ring-[#84b6f4] focus:outline-none dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                {{-- Correo --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4]">Correo</label>
                    <input type="email" name="email" value="{{ old('email', $usuario->email) }}"
                        class="block w-full mt-1 px-3 py-2 border border-[#c4dafa] rounded-lg shadow-sm focus:ring-2 focus:ring-[#84b6f4] focus:outline-none dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                {{-- Rol --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4]">Rol</label>
                    <select name="rol"
                        class="block w-full mt-1 px-3 py-2 border border-[#c4dafa] rounded-lg shadow-sm focus:ring-2 focus:ring-[#84b6f4] focus:outline-none dark:bg-gray-700 dark:text-white dark:border-gray-600">
                        <option value="docente" {{ old('rol', $usuario->rol) == 'docente' ? 'selected' : '' }}>Docente
                        </option>
                        <option value="asistente" {{ old('rol', $usuario->rol) == 'asistente' ? 'selected' : '' }}>
                            Asistente
                        </option>
                        <option value="director_magister" {{ old('rol', $usuario->rol) == 'director_magister' ? 'selected' : '' }}>Director Magíster</option>
                        <option value="director_administrativo" {{ old('rol', $usuario->rol) == 'director_administrativo' ? 'selected' : '' }}>Director Administrativo</option>
                        <option value="auxiliar" {{ old('rol', $usuario->rol) == 'auxiliar' ? 'selected' : '' }}>Auxiliar
                        </option>
                    </select>
                    <x-input-error :messages="$errors->get('rol')" class="mt-2" />
                </div>

                {{-- Botones --}}
                <div class="mt-6 flex justify-between items-center">
                    <x-back :href="route('usuarios.index')">
                        <img src="{{ asset('icons/back.svg') }}" alt="Volver" class="w-5 h-5">
                    </x-back>

                    <x-save type="submit" class="bg-green-600 hover:bg-green-700">
                        <img src="{{ asset('icons/save.svg') }}" alt="Ok" class="w-5 h-5">
                    </x-save>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>