{{-- Editar Staff.blade.php --}}
@section('title', 'Editar miembro')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Editar miembro</h2>
    </x-slot>

    <div class="p-6 max-w-3xl mx-auto">
        @include('staff.form', ['staff' => $staff])
    </div>
</x-app-layout>
