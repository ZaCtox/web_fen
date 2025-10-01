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

            {{-- Botones de acción --}}
            <div class="mt-6 flex justify-between items-center">
                <a href="{{ route('periods.index') }}"
                    class="inline-block bg-[#4d82bc] hover:bg-[#005187] text-white px-4 py-2 rounded-md shadow-md transition">
                    <img src="{{ asset('icons/back.svg') }}" alt="back" class="w-5 h-5">
                </a>
                <button type="submit"
                    class="inline-flex items-center justify-center bg-[#005187] hover:bg-[#4d82bc] 
                           text-white px-4 py-2 rounded-lg shadow text-sm font-medium 
                           transition transform hover:scale-105">
                    <img src="{{ asset('icons/save.svg') }}" alt="Guardar" class="w-5 h-5">
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
