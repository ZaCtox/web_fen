{{-- Editar Clase con Wizard HCI --}}
@section('title', 'Editar Clase')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#c4dafa]"></h2>
    </x-slot>

            {{-- Breadcrumb --}}
    <x-hci-breadcrumb 
        :items="[
            ['label' => 'Inicio', 'url' => route('dashboard')],
            ['label' => 'Clases', 'url' => route('clases.index')],
            ['label' => 'Editar Clase', 'url' => '#']
        ]"
    />

    <div class="p-6 max-w-full mx-auto">
        @include('clases.form-wizard', [
            'clase' => $clase,
            'agrupados' => $agrupados,
            'courses' => $courses,
            'rooms' => $rooms,
            'periods' => $periods,
        ])
        <script>
            window.AGRUPADOS = @json($agrupados);
        </script>
    </div>
</x-app-layout>