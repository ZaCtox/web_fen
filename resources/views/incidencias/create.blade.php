{{-- Crear Incidencias con Wizard HCI --}}
@section('title', 'Nuevo Informe')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Nuevo Informe</h2>
    </x-slot>
        <div class="p-6 max-w-full mx-auto">
@include('incidencias.form-wizard', ['editing' => false])
</div>
</x-app-layout>

    @vite('resources/js/incidencias-form-wizard.js')
