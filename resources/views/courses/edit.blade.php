<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Editar Curso</h2>
    </x-slot>

    <div class="p-6 max-w-2xl mx-auto">
        <form action="{{ route('courses.update', $course) }}" method="POST">
            @csrf @method('PUT')
            @include('courses.form', ['course' => $course])
            <button type="submit"
                    class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                Actualizar
            </button>
        </form>
    </div>
</x-app-layout>
