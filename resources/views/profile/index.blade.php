<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-[#005187] dark:text-[#84b6f4]">
            {{ __('Mi Perfil') }}
        </h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Mi Perfil', 'url' => '#']
    ]" />

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Información de usuario --}}
        <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <h3 class="text-lg font-semibold text-[#005187] dark:text-[#84b6f4]">Información de la Cuenta</h3>
            <p class="mt-2 text-gray-600 dark:text-gray-400"><span class="font-semibold text-[#4d82bc] dark:text-[#84b6f4]">Nombre:</span>
                {{ $user->name }}</p>
            <p class="mt-1 text-gray-600 dark:text-gray-400"><span class="font-semibold text-[#4d82bc] dark:text-[#84b6f4]">Correo:</span>
                {{ $user->email }}</p>
            <p class="mt-1 text-gray-600 dark:text-gray-400"><span class="font-semibold text-[#4d82bc] dark:text-[#84b6f4]">Rol:</span>
                {{ ucfirst(str_replace('_', ' ', $user->rol)) }}</p>
        </div>

        {{-- Actualización de contraseña --}}
        <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="max-w-xl space-y-4">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Eliminar cuenta --}}
{{--         <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="max-w-xl space-y-4">
                @include('profile.partials.delete-user-form')
            </div>
        </div> --}}

    </div>
</x-app-layout>


