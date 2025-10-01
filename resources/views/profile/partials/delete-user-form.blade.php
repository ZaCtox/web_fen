<section class="space-y-6">
    <header>
<<<<<<< Updated upstream
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
<<<<<<< Updated upstream
            {{ __('Eliminar Cuenta') }}
=======
            {{ __('Delete Account') }}
=======
        <h2 class="text-lg font-semibold text-[#005187] dark:text-[#84b6f4]">
            {{ __('Eliminar Cuenta') }}
>>>>>>> Stashed changes
>>>>>>> Stashed changes
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Una vez que tu cuenta sea eliminada, todos sus recursos y datos se eliminarán de forma permanente. Antes de eliminar tu cuenta, asegúrate de descargar cualquier dato o información que desees conservar.') }}
        </p>
    </header>

    {{-- Botón principal --}}
    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
<<<<<<< Updated upstream
    >{{ __('Eliminar Cuenta') }}</x-danger-button>
=======
<<<<<<< Updated upstream
    >{{ __('Delete Account') }}</x-danger-button>
=======
        class="bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-lg shadow transition transform hover:scale-105"
    >
        {{ __('Eliminar Cuenta') }}
    </x-danger-button>
>>>>>>> Stashed changes
>>>>>>> Stashed changes

    {{-- Modal de confirmación --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-4">
            @csrf
            @method('delete')

<<<<<<< Updated upstream
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('¿Estás seguro de que deseas eliminar tu cuenta?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Una vez que tu cuenta sea eliminada, todos sus recursos y datos se eliminarán permanentemente. Por favor, ingresa tu contraseña para confirmar que deseas eliminar tu cuenta de forma permanente.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Contraseña') }}" class="sr-only" />

=======
            <h2 class="text-lg font-semibold text-[#005187] dark:text-[#84b6f4]">
                {{ __('¿Estás seguro de que deseas eliminar tu cuenta?') }}
            </h2>

            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('Una vez que tu cuenta sea eliminada, todos sus recursos y datos se eliminarán permanentemente. Por favor, ingresa tu contraseña para confirmar que deseas eliminar tu cuenta de forma permanente.') }}
            </p>

            {{-- Input de contraseña --}}
            <div class="mt-4">
                <x-input-label for="password" value="{{ __('Contraseña') }}" class="sr-only" />
>>>>>>> Stashed changes
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
<<<<<<< Updated upstream
                    class="mt-1 block w-3/4"
<<<<<<< Updated upstream
                    placeholder="{{ __('Contraseña') }}"
=======
                    placeholder="{{ __('Password') }}"
=======
                    class="mt-1 block w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white focus:border-[#005187] focus:ring focus:ring-[#005187]/50"
                    placeholder="{{ __('Contraseña') }}"
>>>>>>> Stashed changes
>>>>>>> Stashed changes
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

<<<<<<< Updated upstream
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
<<<<<<< Updated upstream
                    {{ __('Eliminar Cuenta') }}
=======
                    {{ __('Delete Account') }}
=======
            {{-- Botones --}}
            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button
                    x-on:click="$dispatch('close')"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-medium px-4 py-2 rounded-lg shadow transition"
                >
                    {{ __('Cancelar') }}
                </x-secondary-button>

                <x-danger-button
                    class="bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-lg shadow transition transform hover:scale-105"
                >
                    {{ __('Eliminar Cuenta') }}
>>>>>>> Stashed changes
>>>>>>> Stashed changes
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
