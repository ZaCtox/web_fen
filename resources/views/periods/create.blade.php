{{-- Crear Periodo.blade.php --}}
@section('title', 'Crear Periodo Académico')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Nuevo periodo Académico</h2>
    </x-slot>

    <div class="p-6 max-w-2xl mx-auto">
        <form action="{{ route('periods.store') }}" method="POST">
            @csrf
            @include('periods.form', ['period' => null])

        <div class="mt-6 flex justify-between items-center">
            <a href="{{ route('periods.index') }}"
               class="inline-block bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                ⬅️ Volver a Períodos
            </a>

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                Guardar
            </button>
        </div>
        </form>
    </div>
</x-app-layout>
