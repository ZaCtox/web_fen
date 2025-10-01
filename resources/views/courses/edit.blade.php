<<<<<<< Updated upstream
{{-- Editar Curso.blade.php --}}
@section('title', 'Editar Curso')
=======
<<<<<<< Updated upstream
=======
{{-- Editar Curso.blade.php --}}
@section('title', 'Editar Curso')

>>>>>>> Stashed changes
>>>>>>> Stashed changes
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
            Editar Curso
        </h2>
    </x-slot>

    <div class="mt-3 p-6 max-w-2xl mx-auto bg-white dark:bg-gray-900 rounded-xl shadow-md">
        <form action="{{ route('courses.update', $course) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            @include('courses.form')

<<<<<<< Updated upstream
            <a href="{{ route('courses.index') }}"
                class="inline-block mt-4 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 dark:bg-gray-600 dark:hover:bg-gray-700 dark:text-white rounded">
                ← Volver
            </a>
=======
<<<<<<< Updated upstream
            <div class="mb-4">
                <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Nombre del Curso
                </label>
                <input type="text" name="nombre" id="nombre" required value="{{ old('nombre', $course->nombre) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-white">
            </div>
>>>>>>> Stashed changes

            <button type="submit" class="mt-6 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Actualizar
            </button>
        </form>
    </div>
<<<<<<< Updated upstream
    @vite(['resources/js/courses/form.js'])
</x-app-layout>
<script>
    window.PERIODS = @json($periods);
</script>
=======
</x-app-layout>
=======
            {{-- Formulario compartido --}}
            @include('courses.form')
        </form>
    </div>

    @vite(['resources/js/courses/form.js'])
</x-app-layout>

<script>
    window.PERIODS = @json($periods);
</script>
>>>>>>> Stashed changes
>>>>>>> Stashed changes
