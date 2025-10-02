{{-- Crear Periodo.blade.php --}}
@section('title', 'Crear Periodo Académico')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-[#005187] dark:text-[#c4dafa]">
            Nuevo Periodo Académico
        </h2>
    </x-slot>

    <div class="p-6 max-w-2xl mx-auto bg-[#fcffff] dark:bg-gray-800 rounded-xl shadow-md">

        <form action="{{ route('periods.store') }}" method="POST">
            @csrf

            {{-- Incluir el formulario reutilizable --}}
            @include('periods.form', ['period' => null])

        </form>
    </div>
</x-app-layout>
