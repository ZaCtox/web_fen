{{-- Crear Emergencia con Principios HCI --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]"></h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Emergencias', 'url' => route('emergencies.index')],
        ['label' => 'Nueva Emergencia', 'url' => '#']
    ]" />

    <div class="p-6 max-w-full mx-auto">
        @include('emergencies.form')
    </div>
</x-app-layout>


