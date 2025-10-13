{{-- Crear Staff.blade.php --}}
@section('title', 'Crear miembro')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#c9e4ff]">Agregar Nuevo Miembro del Equipo</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb 
        :items="[
            ['label' => 'Inicio', 'url' => route('dashboard')],
            ['label' => 'Nuestro Equipo', 'url' => route('staff.index')],
            ['label' => 'Nuevo Miembro', 'url' => '#']
        ]"
    />

    <div class="p-6 max-w-full mx-auto">
        @include('staff.form')
    </div>
</x-app-layout>



