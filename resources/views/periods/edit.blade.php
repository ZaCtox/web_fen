{{-- Editar Periodo Academico.blade.php --}}
@section('title', 'Editar Periodo Académico')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-[#005187] dark:text-[#c4dafa]">
            Editar Periodo Académico</h2>
    </x-slot>

<<<<<<< Updated upstream
<div class="p-6 max-w-2xl mx-auto">
    <form action="{{ route('periods.update', $period) }}" method="POST">
        @csrf
        @method('PUT')
        @include('periods.form', ['period' => $period])

        <div class="mt-6 flex justify-between items-center">
            <a href="{{ route('periods.index') }}"
               class="inline-block bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                ⬅️ Volver a Períodos
            </a>

=======
    <div class="p-6 max-w-2xl mx-auto">
        <form action="{{ route('periods.update', $period) }}" method="POST">
            @csrf
            @method('PUT')
            @include('periods.form', ['period' => $period])
<<<<<<< Updated upstream
>>>>>>> Stashed changes
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                Actualizar
            </button>
<<<<<<< Updated upstream
        </div>
    </form>
</div>

=======
        </form>
    </div>
=======

            <div class="mt-6 flex justify-between items-center">
        </form>
    </div>

>>>>>>> Stashed changes
>>>>>>> Stashed changes
</x-app-layout>