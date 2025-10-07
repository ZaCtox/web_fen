{{-- Crear Período con Wizard HCI --}}
@section('title', 'Crear Período Académico')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Registrar Nuevo Período</h2>
    </x-slot>

    <div class="p-6 max-w-full mx-auto">
        @include('periods.form-wizard', ['editing' => false])
    </div>
</x-app-layout>

{{-- Cargar JavaScript del wizard --}}
@vite('resources/js/periods-form-wizard.js')
