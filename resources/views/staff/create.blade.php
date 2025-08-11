<!-- resources/views/staff/create.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Nuevo miembro del staff</h2>
    </x-slot>

    <div class="p-6 max-w-3xl mx-auto">
        @include('staff._form')
    </div>
</x-app-layout>
