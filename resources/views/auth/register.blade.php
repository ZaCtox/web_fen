<x-guest-layout>
    {{-- Metas para toasts (las usa alerts.js) --}}
    @if(session('success'))
        <meta name="session-success" content="{{ session('success') }}">
    @endif
    @if(session('error'))
        <meta name="session-error" content="{{ session('error') }}">
    @endif

    <div class="max-w-xl mx-auto space-y-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white text-center">
            Registro de Usuario
        </h2>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            {{-- Nombre --}}
            <div>
                <x-input-label for="name" :value="__('Nombre')" />
                <x-text-input id="name" name="name" type="text" class="block mt-1 w-full"
                    :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            {{-- Correo --}}
            <div>
                <x-input-label for="email" :value="__('Correo Electrónico')" />
                <x-text-input id="email" name="email" type="email" class="block mt-1 w-full"
                    :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            {{-- Rol --}}
            <div>
                <x-input-label for="rol" :value="__('Rol')" />
                <select id="rol" name="rol" required
                    class="block mt-1 w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Selecciona un rol</option>
                    <option value="docente" {{ old('rol') == 'docente' ? 'selected' : '' }}>Docente</option>
                    <option value="asistente" {{ old('rol') == 'asistente' ? 'selected' : '' }}>Asistente</option>
                    <option value="director_magister" {{ old('rol') == 'director_magister' ? 'selected' : '' }}>Director Magíster</option>
                    <option value="director_administrativo" {{ old('rol') == 'director_administrativo' ? 'selected' : '' }}>Director Administrativo</option>
                    <option value="auxiliar" {{ old('rol') == 'auxiliar' ? 'selected' : '' }}>Auxiliar</option>
                </select>
                <x-input-error :messages="$errors->get('rol')" class="mt-2" />
            </div>

            {{-- Contraseña --}}
            <div x-data="{ show: false }">
                <x-input-label for="password" :value="__('Contraseña')" />
                <div class="relative">
                    <x-text-input id="password" name="password"
                        type="password"
                        x-bind:type="show ? 'text' : 'password'"
                        class="block mt-1 w-full pr-10"
                        required autocomplete="new-password" />

                    <button type="button" @click="show = !show"
                        :aria-pressed="show"
                        :title="show ? 'Ocultar contraseña' : 'Mostrar contraseña'"
                        class="absolute inset-y-0 right-2 mt-1 flex items-center p-1 rounded bg-white-200 hover:bg-white-300 dark:bg-white-600 dark:hover:bg-white-500 dark:text-white">
                        {{-- 👁 Mostrar --}}
                        <svg x-show="!show" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.574 3.008 9.963 7.183.07.207.07.43 0 .637C20.574 16.49 16.638 19.5 12 19.5c-4.64 0-8.577-3.008-9.964-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{-- 🙈 Ocultar --}}
                        <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3.98 8.223A10.477 10.477 0 002.036 12.32c1.387 4.173 5.324 7.183 9.964 7.183 1.855 0 3.59-.5 5.065-1.377M6.228 6.228A9.967 9.967 0 0112 4.5c4.64 0 8.577 3.01 9.964 7.183a10.5 10.5 0 01-4.306 5.493M6.228 6.228L3 3m3.228 3.228L21 21" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            {{-- Confirmar Contraseña --}}
            <div x-data="{ show: false }" class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
                <div class="relative">
                    <x-text-input id="password_confirmation" name="password_confirmation"
                        type="password"
                        x-bind:type="show ? 'text' : 'password'"
                        class="block mt-1 w-full pr-10"
                        required autocomplete="new-password" />

                    <button type="button" @click="show = !show"
                        :aria-pressed="show"
                        :title="show ? 'Ocultar contraseña' : 'Mostrar contraseña'"
                        class="absolute inset-y-0 right-2 mt-1 flex items-center p-1 rounded bg-white-200 hover:bg-white-300 dark:bg-white-600 dark:hover:bg-white-500 dark:text-white">
                        {{-- 👁 Mostrar --}}
                        <svg x-show="!show" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.574 3.008 9.963 7.183.07.207.07.43 0 .637C20.574 16.49 16.638 19.5 12 19.5c-4.64 0-8.577-3.008-9.964-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{-- 🙈 Ocultar --}}
                        <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3.98 8.223A10.477 10.477 0 002.036 12.32c1.387 4.173 5.324 7.183 9.964 7.183 1.855 0 3.59-.5 5.065-1.377M6.228 6.228A9.967 9.967 0 0112 4.5c4.64 0 8.577 3.01 9.964 7.183a10.5 10.5 0 01-4.306 5.493M6.228 6.228L3 3m3.228 3.228L21 21" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            {{-- Acciones --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-6">
                <a href="{{ route('dashboard') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium text-center">
                    ← Volver
                </a>
                <x-primary-button class="px-4 py-2">
                    {{ __('Registrar') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
