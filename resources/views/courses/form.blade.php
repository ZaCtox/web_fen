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
            Magíster
        </label>
        <select name="magister_id" id="magister_id"
                class="form-select mt-1 w-full dark:bg-gray-700 dark:text-white" required>
            <option value="">-- Selecciona un Magíster --</option>
            @foreach($magisters as $magister)
                <option value="{{ $magister->id }}"
                    {{ old('magister_id', $course->magister_id ?? $selectedMagisterId ?? '') == $magister->id ? 'selected' : '' }}>
                    {{ $magister->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="period_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Período Académico
        </label>
        <select name="period_id" id="period_id"
                class="form-select mt-1 w-full dark:bg-gray-800 dark:text-white" required>
            <option value="">-- Selecciona un período --</option>
            @foreach ($periods as $period)
                <option value="{{ $period->id }}"
                    {{ old('period_id', $course->period_id ?? '') == $period->id ? 'selected' : '' }}>
                    Año: {{ $period->anio }} | Trimestre: {{ $period->numero }}
                </option>
            @endforeach
        </select>
    </div>
</div>
