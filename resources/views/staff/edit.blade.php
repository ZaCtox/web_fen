<!-- resources/views/staff/edit.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Editar miembro</h2>
    </x-slot>

    <div class="p-6 max-w-3xl mx-auto">
        @include('staff._form', ['staff' => $staff])
    </div>
</x-app-layout>
