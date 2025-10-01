<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
            Editar Usuario
        </h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg p-6">
            <form method="POST" action="{{ route('usuarios.update', $user) }}">
                @csrf
                @method('PUT')

                {{-- Nombre --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4]">Nombre</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="block w-full mt-1 px-3 py-2 border border-[#c4dafa] rounded-lg shadow-sm focus:ring-2 focus:ring-[#84b6f4] focus:outline-none dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                {{-- Correo --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4]">Correo</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="block w-full mt-1 px-3 py-2 border border-[#c4dafa] rounded-lg shadow-sm focus:ring-2 focus:ring-[#84b6f4] focus:outline-none dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                {{-- Rol --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4]">Rol</label>
                    <select name="rol"
                        class="block w-full mt-1 px-3 py-2 border border-[#c4dafa] rounded-lg shadow-sm focus:ring-2 focus:ring-[#84b6f4] focus:outline-none dark:bg-gray-700 dark:text-white dark:border-gray-600">
                        <option value="docente" {{ old('rol', $user->rol) == 'docente' ? 'selected' : '' }}>Docente
                        </option>
                        <option value="asistente" {{ old('rol', $user->rol) == 'asistente' ? 'selected' : '' }}>Asistente
                        </option>
                        <option value="director_magister" {{ old('rol', $user->rol) == 'director_magister' ? 'selected' : '' }}>Director Magíster</option>
                        <option value="director_administrativo" {{ old('rol', $user->rol) == 'director_administrativo' ? 'selected' : '' }}>Director Administrativo</option>
                        <option value="auxiliar" {{ old('rol', $user->rol) == 'auxiliar' ? 'selected' : '' }}>Auxiliar
                        </option>
                    </select>
                    <x-input-error :messages="$errors->get('rol')" class="mt-2" />
                </div>

                {{-- Botones --}}
                <div class="mt-6 flex justify-between items-center">
                    <a href="{{ route('usuarios.index') }}"
                        class="inline-block bg-[#4d82bc] hover:bg-[#005187] text-white px-4 py-2 rounded-md shadow-md transition flex items-center gap-2">
                        <img src="{{ asset('icons/back.svg') }}" alt="back" class="w-5 h-5">
                    </a>

                    <button type="submit"
                        class="inline-flex items-center justify-center bg-[#005187] hover:bg-[#4d82bc] text-white px-4 py-2 rounded-lg shadow text-sm font-medium transition transform hover:scale-105 gap-2">
                        <img src="{{ asset('icons/save.svg') }}" alt="Guardar" class="w-5 h-5">
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>