{{-- Crear Curso.blade.php --}}
@section('title', 'Crear Curso')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">Crear Curso</h2>
    </x-slot>

    <div class="mt-3 p-6 max-w-2xl mx-auto bg-white dark:bg-gray-900 rounded-xl shadow-md">
        <form action="{{ route('courses.store') }}" method="POST">
            @csrf
            @include('courses.form')

            <a href="{{ route('courses.index') }}"
                class="inline-block mt-4 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 dark:bg-gray-600 dark:hover:bg-gray-700 dark:text-white rounded">
                ← Volver
            </a>

            <button type="submit" class="mt-6 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                Guardar Curso
            </button>
        </form>
    </div>
    @vite(['resources/js/courses/form.js'])
</x-app-layout>

<script>
    window.PERIODS = @json($periods);
</script>
