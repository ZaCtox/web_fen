{{-- Editar Periodo Academico.blade.php --}}
@section('title', 'Editar Periodo Académico')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-[#005187] dark:text-[#c4dafa]">
            Editar Periodo Académico
        </h2>
    </x-slot>

    <div class="p-6 max-w-2xl mx-auto bg-[#fcffff] dark:bg-gray-800 rounded-xl shadow-md">

        <form action="{{ route('periods.update', $period) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Incluir el formulario reutilizable --}}
            @include('periods.form', ['period' => $period])
        </form>
    </div>
</x-app-layout>
