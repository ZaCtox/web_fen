{{-- Editar Sala.blade.php --}}
@section('title', 'Editar Sala')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Editar Sala</h2>
    </x-slot>

    <div class="p-6">
        <form action="{{ route('rooms.update', $room) }}" method="POST">
            @method('PUT')
            @include('rooms.form', ['submitText' => 'Actualizar Sala'])
        </form>
    </div>
</x-app-layout>
