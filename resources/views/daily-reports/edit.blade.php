{{-- Editar Reporte Diario --}}
@section('title', 'Editar Reporte Diario')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Editar Reporte Diario: {{ $dailyReport->title }}</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb 
        :items="[
            ['label' => 'Inicio', 'url' => route('dashboard')],
            ['label' => 'Reportes Diarios', 'url' => route('daily-reports.index')],
            ['label' => 'Editar Reporte', 'url' => '#']
        ]"
    />

    <div class="p-6 max-w-full mx-auto">
        @include('daily-reports.form', ['dailyReport' => $dailyReport])
    </div>
</x-app-layout>

