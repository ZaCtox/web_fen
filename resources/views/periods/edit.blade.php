{{-- Editar Período con Wizard HCI --}}
@section('title', 'Editar Período Académico')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Editar Período Académico</h2>
    </x-slot>

        {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Periodos', 'url' => route('periods.index')],
        ['label' => 'Editar Periodo', 'url' => '#']
    ]" />

    <div class="p-6 max-w-full mx-auto">
        @include('periods.form-wizard', ['editing' => true, 'period' => $period])
    </div>
</x-app-layout>

{{-- Cargar JavaScript del wizard --}}
@vite('resources/js/periods-form-wizard.js')
