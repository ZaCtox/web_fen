@csrf

{{-- Datos generales de la sala --}}
<div class="mb-4">
    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
    <input type="text" name="name" id="name" required value="{{ old('name', $room->name ?? '') }}"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-white">
</div>

<div class="mb-4">
    <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ubicación</label>
    <input type="text" name="location" id="location" value="{{ old('location', $room->location ?? '') }}"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-white">
</div>

<div class="mb-4">
    <label for="capacity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Capacidad</label>
    <input type="number" name="capacity" id="capacity" min="1" value="{{ old('capacity', $room->capacity ?? '') }}"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-white">
</div>

<div class="mb-4">
    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
    <textarea name="description" id="description" rows="3"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-white">{{ old('description', $room->description ?? '') }}</textarea>
</div>

<hr class="my-6 border-gray-300 dark:border-gray-600">
<h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Usos Académicos</h3>

<div id="usos-container" class="space-y-4">
    <div class="grid grid-cols-6 gap-2">
        {{-- Periodo --}}
        <select name="usos[0][period_id]" required class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
            <option value="">Periodo</option>
            @foreach($periodos as $p)
                <option value="{{ $p->id }}">{{ $p->nombre_completo }}</option>
            @endforeach
        </select>

        {{-- Día --}}
        <select name="usos[0][dia]" required class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
            <option value="">Día</option>
            <option value="Viernes">Viernes</option>
            <option value="Sábado">Sábado</option>
        </select>

        {{-- Hora inicio --}}
        <input type="time" name="usos[0][hora_inicio]" required class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">

        {{-- Hora fin --}}
        <input type="time" name="usos[0][hora_fin]" required class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">

        {{-- Magíster --}}
        <select class="magister-select px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
            <option value="">Magíster</option>
            @foreach($cursos->groupBy('programa') as $programa => $asignaturas)
                <option value="{{ $programa }}">{{ $programa }}</option>
            @endforeach
        </select>

        {{-- Curso (asignatura) --}}
        <select name="usos[0][course_id]" required class="course-select px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
            <option value="">Asignatura</option>
        </select>
    </div>
</div>

<button type="button" id="add-uso" class="mt-2 bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded">
    + Añadir uso
</button>

<hr class="my-6 border-gray-300 dark:border-gray-600">

{{-- Botón de guardar --}}
<button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
    {{ $submitText ?? 'Guardar' }}
</button>

{{-- Script para clonar --}}
<script>
    const cursosPorMagister = @json($cursos->groupBy('programa')->map(function ($items) {
        return $items->map(fn($c) => ['id' => $c->id, 'nombre' => $c->nombre]);
    }));

    let usoIndex = 1;
    const periodosOptions = `
        <option value="">Periodo</option>
        @foreach($periodos as $p)
            <option value="{{ $p->id }}">{{ $p->nombre_completo }}</option>
        @endforeach
    `;

    const magisterOptions = `
        <option value="">Magíster</option>
        @foreach($cursos->groupBy('programa') as $programa => $_)
            <option value="{{ $programa }}">{{ $programa }}</option>
        @endforeach
    `;

    function bindCourseFilter(magSelect, courseSelect) {
        magSelect.addEventListener('change', function () {
            const mag = this.value;
            courseSelect.innerHTML = '<option value="">Asignatura</option>';
            if (cursosPorMagister[mag]) {
                cursosPorMagister[mag].forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.id;
                    opt.textContent = c.nombre;
                    courseSelect.appendChild(opt);
                });
            }
        });
    }

    document.getElementById('add-uso').addEventListener('click', () => {
        const container = document.getElementById('usos-container');
        const div = document.createElement('div');
        div.classList.add('grid', 'grid-cols-6', 'gap-2', 'mt-2');
        div.innerHTML = `
            <select name="usos[${usoIndex}][period_id]" class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white" required>
                ${periodosOptions}
            </select>
            <select name="usos[${usoIndex}][dia]" class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white" required>
                <option value="">Día</option>
                <option value="Viernes">Viernes</option>
                <option value="Sábado">Sábado</option>
            </select>
            <input type="time" name="usos[${usoIndex}][hora_inicio]" class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white" required>
            <input type="time" name="usos[${usoIndex}][hora_fin]" class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white" required>
            <select class="magister-select px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
                ${magisterOptions}
            </select>
            <select name="usos[${usoIndex}][course_id]" class="course-select px-3 py-2 rounded border dark:bg-gray-700 dark:text-white" required>
                <option value="">Asignatura</option>
            </select>
        `;
        container.appendChild(div);
        bindCourseFilter(div.querySelector('.magister-select'), div.querySelector('.course-select'));
        usoIndex++;
    });

    document.querySelectorAll('.magister-select').forEach((mag, i) => {
        bindCourseFilter(mag, document.querySelectorAll('.course-select')[i]);
    });
</script>
