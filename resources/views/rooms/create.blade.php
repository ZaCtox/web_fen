<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Registrar Nueva Sala</h2>
    </x-slot>

    <div class="p-6">
        <form action="{{ route('rooms.store') }}" method="POST">
            @include('rooms.form', ['submitText' => 'Crear Sala'])
        </form>
    </div>
</x-app-layout>
