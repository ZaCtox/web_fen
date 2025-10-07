{{-- Crear Informe con Wizard HCI --}}
@section('title', 'Nuevo Informe')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Nuevo Informe</h2>
    </x-slot>

    <div class="p-6 max-w-full mx-auto">
        @include('informes.form-wizard', ['editing' => false, 'magisters' => $magisters])
    </div>
</x-app-layout>

{{-- Cargar JavaScript del wizard --}}
@vite('resources/js/informes-form-wizard.js')
