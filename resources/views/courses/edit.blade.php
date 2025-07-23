<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Editar Curso</h2>
    </x-slot>

    <div class="p-6 max-w-xl mx-auto">
        <form action="{{ route('courses.update', $course) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Nombre del Curso
                </label>
                <input type="text" name="nombre" id="nombre" required value="{{ old('nombre', $course->nombre) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-white">
            </div>

            <div class="mb-4">
                <label for="magister_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Magíster
                </label>
                <select name="magister_id" id="magister_id"
                    class="form-select mt-1 w-full dark:bg-gray-700 dark:text-white" required>
                    @foreach($magisters as $magister)
                        <option value="{{ $magister->id }}" {{ $course->magister_id == $magister->id ? 'selected' : '' }}>
                            {{ $magister->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Período Académico</label>
                <select name="period_id" class="rounded w-full dark:bg-gray-800 dark:text-white" required>
                    <option value="">-- Selecciona un período --</option>
                    @foreach ($periods as $period)
                        <option value="{{ $period->id }}" {{ old('period_id', $course->period_id ?? '') == $period->id ? 'selected' : '' }}>
                            Año: {{ $period->anio }} | Trimestre: {{ $period->numero }}
                        </option>
                    @endforeach
                </select>
            </div>


            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Actualizar
            </button>
        </form>
    </div>
</x-app-layout>