{{-- Formulario de Cursos.blade.php --}}
@php($editing = isset($course))

<div class="space-y-6">
    {{-- Nombre del curso --}}
    <div>
        <label for="nombre" class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">
            Nombre del Curso
        </label>
        <input type="text" name="nombre" id="nombre"
            class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 dark:focus:ring-blue-600 transition"
            value="{{ old('nombre', $course->nombre ?? '') }}" required placeholder="Ej: Economía Aplicada">
    </div>

    {{-- Programa --}}
    <div>
        <label for="magister_id" class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">
            Programa
        </label>
        <select name="magister_id" id="magister_id"
            class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 dark:focus:ring-blue-600 transition"
            required>
            <option value="">-- Selecciona un Programa --</option>
            @foreach($magisters as $magister)
                <option value="{{ $magister->id }}" {{ old('magister_id', $course->magister_id ?? $selectedMagisterId ?? '') == $magister->id ? 'selected' : '' }}>
                    {{ $magister->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Año --}}
    <div>
        <label for="anio" class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">
            Año
        </label>
        <select id="anio"
            class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 dark:focus:ring-blue-600 transition">
            <option value="">-- Selecciona un año --</option>
            @foreach ($periods->pluck('anio')->unique() as $anio)
                <option value="{{ $anio }}">{{ $anio }}</option>
            @endforeach
        </select>
    </div>

    {{-- Trimestre --}}
    <div>
        <label for="numero" class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">
            Trimestre
        </label>
        <select id="numero"
            class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 dark:focus:ring-blue-600 transition">
            <option value="">-- Selecciona un trimestre --</option>
            {{-- Se llenará dinámicamente con JS --}}
        </select>
    </div>

    {{-- Hidden field para period --}}
    <input type="hidden" name="period_id" id="period_id" value="{{ old('period_id', $course->period_id ?? '') }}">

    {{-- Botones --}}
    <div class="mt-6 flex justify-between items-center">
        <a href="{{ route('courses.index') }}" class="inline-block bg-[#4d82bc] hover:bg-[#005187] 
               text-white px-4 py-2 rounded-md shadow-md transition">
            <img src="{{ asset('icons/back.svg') }}" alt="back" class="w-5 h-5">
        </a>
        <button type="submit" class="inline-flex items-center justify-center bg-[#005187] hover:bg-[#4d82bc] 
                                   text-white px-4 py-2 rounded-lg shadow text-sm font-medium 
                                   transition transform hover:scale-105">
            <img src="{{ asset('icons/save.svg') }}" alt="Guardar" class="w-5 h-5">
        </button>
    </div>
</div>
