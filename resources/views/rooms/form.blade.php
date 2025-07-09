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

{{-- Usos académicos --}}
<hr class="my-6 border-gray-300 dark:border-gray-600">
<h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Usos Académicos</h3>

<div id="usos-container" class="space-y-4">
    <div class="grid grid-cols-7 gap-2">
        <input type="number" name="usos[0][year]" placeholder="Año" min="1" max="5"
            class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">

        <input type="number" name="usos[0][trimestre]" placeholder="Trimestre" min="1" max="6"
            class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">

        <select name="usos[0][magister]" class="magister-select px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
            <option value="">Magíster</option>
            <option value="Economía">Economía</option>
            <option value="Gestión de Sistemas de Salud">Gestión de Sistemas de Salud</option>
            <option value="Gestión y Políticas Públicas">Gestión y Políticas Públicas</option>
            <option value="Dirección y Planificación Tributaria">Dirección y Planificación Tributaria</option>
        </select>

        <select name="usos[0][subject]" class="asignatura-select px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
            <option value="">Asignatura</option>
        </select>

        <select name="usos[0][dia]" class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
            <option value="">Día</option>
            <option value="Viernes">Viernes</option>
            <option value="Sábado">Sábado</option>
        </select>

        <input type="text" name="usos[0][horario]" placeholder="08:30-10:50"
            class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
    </div>
</div>

{{-- Botón para añadir usos --}}
<button type="button" id="add-uso" class="mt-2 bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded">
    + Añadir uso
</button>

<hr class="my-6 border-gray-300 dark:border-gray-600">

{{-- Botón de guardar --}}
<button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
    {{ $submitText ?? 'Guardar' }}
</button>

{{-- Script para clonar nuevas filas --}}
<script>
    let usoIndex = {{ isset($room) ? $room->usages->count() : 1 }};
    document.getElementById('add-uso').addEventListener('click', () => {
        const container = document.getElementById('usos-container');
        const div = document.createElement('div');
        div.classList.add('grid', 'grid-cols-7', 'gap-2', 'mt-2');
        div.innerHTML = `
            <input type="number" name="usos[${usoIndex}][year]" placeholder="Año" min="1" max="5"
                class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
            <input type="number" name="usos[${usoIndex}][trimestre]" placeholder="Trimestre" min="1" max="6"
                class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
            <select name="usos[${usoIndex}][magister]" class="magister-select px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
                <option value="">Magíster</option>
                <option value="Economía">Economía</option>
                <option value="Gestión de Sistemas de Salud">Gestión de Sistemas de Salud</option>
                <option value="Gestión y Políticas Públicas">Gestión y Políticas Públicas</option>
                <option value="Dirección y Planificación Tributaria">Dirección y Planificación Tributaria</option>
            </select>
            <select name="usos[${usoIndex}][subject]" class="asignatura-select px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
                <option value="">Asignatura</option>
            </select>
            <select name="usos[${usoIndex}][dia]" class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
                <option value="">Día</option>
                <option value="Viernes">Viernes</option>
                <option value="Sábado">Sábado</option>
            </select>
            <input type="text" name="usos[${usoIndex}][horario]" placeholder="08:30-10:50"
                class="px-3 py-2 rounded border dark:bg-gray-700 dark:text-white">
        `;
        container.appendChild(div);
        usoIndex++;

        // Reinicializa el comportamiento dinámico
        if (window.initAsignaturaAutofill) {
            window.initAsignaturaAutofill();
        }
    });
</script>
