{{-- Crear Clase con Wizard HCI --}}
@section('title', 'Crear Clase')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Registrar Nueva Clase</h2>
    </x-slot>

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