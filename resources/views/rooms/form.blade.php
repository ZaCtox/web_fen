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


{{-- Botón de guardar --}}
<button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
    {{ $submitText ?? 'Guardar' }}
</button>

