{{-- Editar Programa.blade.php --}}
@section('title', 'Editar Programa')
<x-app-layout>
    <x-slot name="header">
<<<<<<< Updated upstream
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Editar Programa</h2>
=======
<<<<<<< Updated upstream
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            {{ isset($magister) ? 'Editar Magíster' : 'Crear Magíster' }}
        </h2>
=======
        <h2 class="text-2xl font-bold text-[#005187] dark:text-[#c4dafa]">Editar Programa</h2>
>>>>>>> Stashed changes
>>>>>>> Stashed changes
    </x-slot>

    <div class="p-6 max-w-xl mx-auto">
        @if (session('success'))
            <div class="mb-4 rounded border border-green-300 bg-green-50 dark:bg-green-900/30
                        text-green-800 dark:text-green-200 px-4 py-2">
                {{ session('success') }}
            </div>
        @endif

        @include('magisters.form', ['magister' => $magister])
    </div>
</x-app-layout>
