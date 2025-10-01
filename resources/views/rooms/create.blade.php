{{-- Crear Sala.blade.php --}}
@section('title', 'Crear Sala')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Registrar Nueva Sala</h2>
    </x-slot>

    <div class="p-6">
        <form action="{{ route('rooms.store') }}" method="POST">
            @include('rooms.form', ['submitText' => 'Crear Sala'])
        </form>
    </div>
</x-app-layout>
