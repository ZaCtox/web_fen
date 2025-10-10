{{-- Crear Clase con Wizard HCI --}}
@section('title', 'Crear Clase')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]"></h2>
    </x-slot>

        {{-- Breadcrumb --}}
    <x-hci-breadcrumb 
        :items="[
            ['label' => 'Inicio', 'url' => route('dashboard')],
            ['label' => 'Clases', 'url' => route('clases.index')],
            ['label' => 'Nueva Clase', 'url' => '#']
        ]"
    />

    <div class="p-6 max-w-full mx-auto">
        @include('clases.form-wizard', [
            'agrupados' => $agrupados,
            'rooms' => $rooms,
            'periods' => $periods ?? [],
        ])
        <script>
            window.AGRUPADOS = @json($agrupados);
        </script>
    </div>
</x-app-layout>


