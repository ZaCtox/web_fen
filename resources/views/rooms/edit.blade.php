{{-- Editar Sala.blade.php --}}
@section('title', 'Editar Sala')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Editar Sala</h2>
    </x-slot>

    <div class="p-6 max-w-full mx-auto">
        @include('rooms.form-wizard', ['room' => $room])
    </div>
</x-app-layout>
