<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium">Año Académico</label>
        <select name="anio" class="form-select mt-1 w-full rounded">
            @for ($i = 1; $i <= 2; $i++)
                <option value="{{ $i }}" {{ old('anio', optional($period)->anio) == $i ? 'selected' : '' }}>Año {{ $i }}</option>
            @endfor
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium">Trimestre</label>
        <select name="numero" class="form-select mt-1 w-full rounded">
            <option value="1" {{ old('numero', optional($period)->numero) == 1 ? 'selected' : '' }}>Trimestre 1</option>
            <option value="2" {{ old('numero', optional($period)->numero) == 2 ? 'selected' : '' }}>Trimestre 2</option>
            <option value="3" {{ old('numero', optional($period)->numero) == 3 ? 'selected' : '' }}>Trimestre 3</option>
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

    <div>
        <label class="inline-flex items-center">
            <input type="checkbox" name="activo" value="1" class="form-checkbox"
                {{ old('activo', optional($period)->activo) ? 'checked' : '' }}>
            <span class="ml-2">Activo</span>
        </label>
    </div>
</div>
