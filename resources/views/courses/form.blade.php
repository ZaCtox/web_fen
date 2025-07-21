<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium">Nombre del Curso</label>
        <input type="text" name="nombre" class="form-input mt-1 w-full rounded"
               value="{{ old('nombre', optional($course)->nombre) }}" required>
    </div>

    <div>
        <label class="block text-sm font-medium">Programa</label>
        <input type="text" name="programa" class="form-input mt-1 w-full rounded"
               value="{{ old('programa', optional($course)->programa) }}" required>
    </div>
</div>