{{-- Formulario de Cursos.blade.php --}}
@php($editing = isset($course))

<div class="space-y-4">
    <div>
        <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Nombre del Curso
        </label>
        <input type="text" name="nombre" id="nombre"
            class="form-input mt-1 w-full rounded dark:bg-gray-700 dark:text-white"
            value="{{ old('nombre', $course->nombre ?? '') }}" required>
    </div>

    <div>
        <label for="magister_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Programa
        </label>
        <select name="magister_id" id="magister_id" class="form-select mt-1 w-full dark:bg-gray-700 dark:text-white"
            required>
            <option value="">-- Selecciona un Programa --</option>
            @foreach($magisters as $magister)
                <option value="{{ $magister->id }}" {{ old('magister_id', $course->magister_id ?? $selectedMagisterId ?? '') == $magister->id ? 'selected' : '' }}>
                    {{ $magister->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="anio" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Año
        </label>
        <select id="anio" class="form-select mt-1 w-full dark:bg-gray-700 dark:text-white">
            <option value="">-- Selecciona un año --</option>
            @foreach ($periods->pluck('anio')->unique() as $anio)
                <option value="{{ $anio }}">{{ $anio }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="numero" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Trimestre
        </label>
        <select id="numero" class="form-select mt-1 w-full dark:bg-gray-700 dark:text-white">
            <option value="">-- Selecciona un trimestre --</option>
            {{-- Las opciones se llenarán por JS --}}
        </select>
    </div>

    <input type="hidden" name="period_id" id="period_id" value="{{ old('period_id', $course->period_id ?? '') }}">
</div>
