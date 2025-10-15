{{-- Crear Novedad.blade.php --}}
@section('title', 'Crear Novedad')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">📰 Nueva Novedad</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb 
        :items="[
            ['label' => 'Inicio', 'url' => route('dashboard')],
            ['label' => 'Gestión de Novedades', 'url' => route('novedades.index')],
            ['label' => 'Nueva Novedad', 'url' => '#']
        ]"
    />

    <div class="p-6 max-w-full mx-auto">
        @include('novedades.form', ['novedad' => null])
    </div>
</x-app-layout>

