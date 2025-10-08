{{-- Crear Período con Wizard HCI --}}
@section('title', 'Crear Período Académico')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Registrar Nuevo Período</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Periodos', 'url' => route('periods.index')],
        ['label' => 'Nuevo Periodo', 'url' => '#']
    ]" />

    <div class="p-6 max-w-full mx-auto">
        @include('periods.form-wizard', ['editing' => false])
    </div>
</x-app-layout>

{{-- Cargar JavaScript del wizard --}}
@vite('resources/js/periods-form-wizard.js')