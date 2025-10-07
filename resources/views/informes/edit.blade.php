{{-- Editar Informe con Wizard HCI --}}
@section('title', 'Editar Informe')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Editar Informe</h2>
    </x-slot>

    <div class="p-6 max-w-full mx-auto">
        @include('informes.form-wizard', ['editing' => true, 'informe' => $informe, 'magisters' => $magisters])
    </div>
</x-app-layout>

{{-- Cargar JavaScript del wizard --}}
@vite('resources/js/informes-form-wizard.js')
