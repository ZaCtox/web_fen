<section class="space-y-6">
    <header>
<<<<<<< Updated upstream
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
<<<<<<< Updated upstream
            {{ __('Actualizar Contraseña') }}
=======
            {{ __('Update Password') }}
=======
        <h2 class="text-lg font-semibold text-[#005187] dark:text-[#84b6f4]">
            {{ __('Actualizar Contraseña') }}
>>>>>>> Stashed changes
>>>>>>> Stashed changes
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Asegúrate de usar una contraseña larga y aleatoria para mantener tu cuenta segura.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        {{-- Contraseña actual --}}
        <div>
<<<<<<< Updated upstream
            <x-input-label for="update_password_current_password" :value="__('Contraseña Actual')" />
=======
<<<<<<< Updated upstream
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
>>>>>>> Stashed changes
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
=======
            <x-input-label for="update_password_current_password" :value="__('Contraseña Actual')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password"
                class="mt-1 block w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white focus:border-[#005187] focus:ring focus:ring-[#005187]/50"
                autocomplete="current-password" />
>>>>>>> Stashed changes
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        {{-- Nueva contraseña --}}
        <div>
<<<<<<< Updated upstream
            <x-input-label for="update_password_password" :value="__('Nueva Contraseña')" />
=======
<<<<<<< Updated upstream
            <x-input-label for="update_password_password" :value="__('New Password')" />
>>>>>>> Stashed changes
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
=======
            <x-input-label for="update_password_password" :value="__('Nueva Contraseña')" />
            <x-text-input id="update_password_password" name="password" type="password"
                class="mt-1 block w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white focus:border-[#005187] focus:ring focus:ring-[#005187]/50"
                autocomplete="new-password" />
>>>>>>> Stashed changes
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        {{-- Confirmar nueva contraseña --}}
        <div>
<<<<<<< Updated upstream
            <x-input-label for="update_password_password_confirmation" :value="__('Confirmar Contraseña')" />
=======
<<<<<<< Updated upstream
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
>>>>>>> Stashed changes
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
=======
            <x-input-label for="update_password_password_confirmation" :value="__('Confirmar Contraseña')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="mt-1 block w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white focus:border-[#005187] focus:ring focus:ring-[#005187]/50"
                autocomplete="new-password" />
>>>>>>> Stashed changes
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Botón y feedback --}}
        <div class="flex items-center gap-4">
<<<<<<< Updated upstream
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>
=======
<<<<<<< Updated upstream
            <x-primary-button>{{ __('Save') }}</x-primary-button>
>>>>>>> Stashed changes

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
<<<<<<< Updated upstream
                >{{ __('Guardado.') }}</p>
=======
                >{{ __('Saved.') }}</p>
=======
            <button type="submit" class="inline-flex items-center justify-center bg-[#005187] hover:bg-[#4d82bc] 
               text-white px-4 py-2 rounded-lg shadow text-sm font-medium 
               transition transform hover:scale-105">
                <img src="{{ asset('icons/save.svg') }}" alt="Guardar" class="w-5 h-5 mr-2">
            </button>


            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 dark:text-green-400">
                    {{ __('Guardado.') }}
                </p>
>>>>>>> Stashed changes
>>>>>>> Stashed changes
            @endif
        </div>
    </form>
</section>