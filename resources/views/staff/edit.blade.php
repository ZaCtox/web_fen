{{-- Editar Staff.blade.php --}}
@section('title', 'Editar miembro')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#c9e4ff]">Editar Miembro: {{ $staff->nombre }}</h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb 
        :items="[
            ['label' => 'Inicio', 'url' => route('dashboard')],
            ['label' => 'Nuestro Equipo', 'url' => route('staff.index')],
            ['label' => 'Editar Miembro', 'url' => '#']
        ]"
    />

    <div class="p-6 max-w-full mx-auto">
        @include('staff.form', ['staff' => $staff])
    </div>
</x-app-layout>



