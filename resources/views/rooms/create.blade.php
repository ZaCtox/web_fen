{{-- Crear Sala.blade.php --}}
@section('title', 'Crear Sala')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Registrar Nueva Sala</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Salas', 'url' => route('rooms.index')],
        ['label' => 'Nueva Sala', 'url' => '#']
    ]" />

    <div class="p-6 max-w-full mx-auto">
        @include('rooms.form-wizard')
    </div>
</x-app-layout>
