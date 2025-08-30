<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium">Año Académico</label>
        <select id="anio-select" name="anio" class="form-select mt-1 w-full rounded">
            @for ($i = 1; $i <= 2; $i++)
                <option value="{{ $i }}" {{ old('anio', optional($period)->anio) == $i ? 'selected' : '' }}>Año {{ $i }}</option>
            @endfor
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium">Trimestre</label>
        <select id="numero-select" name="numero" class="form-select mt-1 w-full rounded">
            {{-- Este contenido se rellenará dinámicamente --}}
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium">Fecha de Inicio</label>
        <input type="date" name="fecha_inicio" class="form-input mt-1 w-full rounded"
            value="{{ old('fecha_inicio', optional($period)->fecha_inicio ? optional($period)->fecha_inicio->format('Y-m-d') : '') }}">
    </div>

    <div>
        <label class="block text-sm font-medium">Fecha de Término</label>
        <input type="date" name="fecha_fin" class="form-input mt-1 w-full rounded"
            value="{{ old('fecha_fin', optional($period)->fecha_fin ? optional($period)->fecha_fin->format('Y-m-d') : '') }}">
    </div>
</div>


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
        actualizarTrimestres(); // Llamada inicial al cargar la vista
    });
</script>
