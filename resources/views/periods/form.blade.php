<<<<<<< Updated upstream
{{-- Formulario de Periodo.blade.php --}}
@section('title', 'Formulario Periodo Académico')
<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium">Año Académico</label>
        <select id="anio-select" name="anio" class="form-select mt-1 w-full rounded">
=======
<<<<<<< Updated upstream
<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium">Año Académico</label>
        <select name="anio" class="form-select mt-1 w-full rounded">
=======
{{-- Formulario de Periodo.blade.php --}}
@section('title', 'Formulario Periodo Académico')
<div class="space-y-6 max-w-md mx-auto p-6 bg-[#fcffff] dark:bg-gray-800 rounded-xl shadow-md">

    <div>
        <label class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">Año Académico</label>
        <select id="anio-select" name="anio"
            class="mt-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] transition">
>>>>>>> Stashed changes
>>>>>>> Stashed changes
            @for ($i = 1; $i <= 2; $i++)
                <option value="{{ $i }}" {{ old('anio', optional($period)->anio) == $i ? 'selected' : '' }}>
                    Año {{ $i }}
                </option>
            @endfor
        </select>
    </div>

    <div>
<<<<<<< Updated upstream
        <label class="block text-sm font-medium">Trimestre</label>
<<<<<<< Updated upstream
        <select id="numero-select" name="numero" class="form-select mt-1 w-full rounded">
            {{-- Este contenido se rellenará dinámicamente --}}
=======
        <select name="numero" class="form-select mt-1 w-full rounded">
            <option value="1" {{ old('numero', optional($period)->numero) == 1 ? 'selected' : '' }}>Trimestre 1</option>
            <option value="2" {{ old('numero', optional($period)->numero) == 2 ? 'selected' : '' }}>Trimestre 2</option>
            <option value="3" {{ old('numero', optional($period)->numero) == 3 ? 'selected' : '' }}>Trimestre 3</option>
=======
        <label class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">Trimestre</label>
        <select id="numero-select" name="numero"
            class="mt-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] transition">
            {{-- Se rellenará dinámicamente --}}
>>>>>>> Stashed changes
>>>>>>> Stashed changes
        </select>
    </div>

    <div>
        <label class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">Fecha de Inicio</label>
        <input type="date" name="fecha_inicio"
            class="mt-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] transition"
            value="{{ old('fecha_inicio', optional($period)->fecha_inicio ? optional($period)->fecha_inicio->format('Y-m-d') : '') }}">
    </div>

    <div>
        <label class="block text-sm font-semibold text-[#005187] dark:text-[#c4dafa]">Fecha de Término</label>
        <input type="date" name="fecha_fin"
            class="mt-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] transition"
            value="{{ old('fecha_fin', optional($period)->fecha_fin ? optional($period)->fecha_fin->format('Y-m-d') : '') }}">
    </div>
<<<<<<< Updated upstream
</div>

=======
<<<<<<< Updated upstream

    <div>
        <label class="inline-flex items-center">
            <input type="checkbox" name="activo" value="1" class="form-checkbox"
                {{ old('activo', optional($period)->activo) ? 'checked' : '' }}>
            <span class="ml-2">Activo</span>
        </label>
    </div>
</div>
=======
    <div class="mt-6 flex justify-between items-center">
        <a href="{{ route('periods.index') }}" class="inline-block bg-[#4d82bc] hover:bg-[#005187] 
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
>>>>>>> Stashed changes

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const anioSelect = document.getElementById('anio-select');
        const numeroSelect = document.getElementById('numero-select');

        const opcionesTrimestre = {
            1: [1, 2, 3],
            2: [4, 5, 6]
        };

        function actualizarTrimestres() {
            const anio = parseInt(anioSelect.value);
            const trimestres = opcionesTrimestre[anio] || [];

            numeroSelect.innerHTML = '';

            trimestres.forEach(function (num) {
                const option = document.createElement('option');
                option.value = num;
                option.textContent = 'Trimestre ' + num;
                if ({{ old('numero', optional($period)->numero) ?? 'null' }} == num) {
                    option.selected = true;
                }
                numeroSelect.appendChild(option);
            });
        }

        anioSelect.addEventListener('change', actualizarTrimestres);
<<<<<<< Updated upstream
        actualizarTrimestres(); // Llamada inicial al cargar la vista
    });
</script>
=======
        actualizarTrimestres(); // Inicial al cargar
    });
</script>
>>>>>>> Stashed changes
>>>>>>> Stashed changes
