<x-guest-layout>
    {{-- Metas para toasts (las usa alerts.js) --}}
    @if(session('success'))
        <meta name="session-success" content="{{ session('success') }}">
    @endif
    @if(session('error'))
        <meta name="session-error" content="{{ session('error') }}">
    @endif
    @if($errors->any())
        <meta name="session-validate-error" content="{{ $errors->first() }}">
    @endif

    <div class="max-w-xl mx-auto space-y-6">
        <h2 class="text-2xl font-bold text-[#005187] dark:text-[#84b6f4] text-center">
            Registro de Usuario
        </h2>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            {{-- Nombre --}}
            <div>
                <x-input-label for="name" :value="__('Nombre')" />
                <x-text-input id="name" name="name" type="text" class="block mt-1 w-full" :value="old('name')" required
                    autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            {{-- Correo --}}
            <div>
                <x-input-label for="email" :value="__('Correo Electrónico')" />
                <x-text-input id="email" name="email" type="email" class="block mt-1 w-full" :value="old('email')"
                    required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            {{-- Rol --}}
            <div>
                <x-input-label for="rol" :value="__('Rol')" />
                <select id="rol" name="rol" required
                    class="block mt-1 w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white focus:border-[#84b6f4] focus:ring-[#84b6f4]">
                    <option value="">Selecciona un rol</option>
                    <option value="director_programa" {{ old('rol') == 'director_programa' ? 'selected' : '' }}>Director de Programa</option>
                    <option value="asistente_programa" {{ old('rol') == 'asistente_programa' ? 'selected' : '' }}>Asistente de Programa</option>
                    <option value="docente" {{ old('rol') == 'docente' ? 'selected' : '' }}>Docente</option>
                    <option value="tecnico" {{ old('rol') == 'tecnico' ? 'selected' : '' }}>Técnico</option>
                    <option value="auxiliar" {{ old('rol') == 'auxiliar' ? 'selected' : '' }}>Auxiliar</option>
                    <option value="asistente_postgrado" {{ old('rol') == 'asistente_postgrado' ? 'selected' : '' }}>Asistente de Postgrado</option>
                </select>
                <x-input-error :messages="$errors->get('rol')" class="mt-2" />
            </div>

            {{-- Acciones con estilo --}}
            <div class="mt-6 flex justify-between items-center">
                <a href="{{ route('usuarios.index') }}" class="inline-block bg-[#4d82bc] hover:bg-[#005187] 
                           text-white px-4 py-2 rounded-md shadow-md transition">
                    <img src="{{ asset('icons/back.svg') }}" alt="back" class="w-5 h-5">
                </a>
                <button type="submit" class="inline-flex items-center justify-center bg-[#005187] hover:bg-[#4d82bc] 
                                               text-white px-4 py-2 rounded-lg shadow text-sm font-medium 
                                               transition transform hover:scale-105">
                    <img src="{{ asset('icons/save.svg') }}" alt="Guardar" class="w-5 h-5">
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
