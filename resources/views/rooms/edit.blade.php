<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Editar Sala</h2>
    </x-slot>

    <div class="p-6">
        <form action="{{ route('rooms.update', $room) }}" method="POST">
            @method('PUT')
            @include('rooms.form', ['submitText' => 'Actualizar Sala'])
        </form>
    </div>
</x-app-layout>
