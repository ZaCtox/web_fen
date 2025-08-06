@csrf

{{-- 🏫 Datos generales --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
        <input type="text" name="name" id="name" required value="{{ old('name', $room->name ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-800 dark:text-white focus:ring-fen-red focus:border-fen-red">
    </div>

    <div>
        <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ubicación</label>
        <input type="text" name="location" id="location" value="{{ old('location', $room->location ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-800 dark:text-white focus:ring-fen-red focus:border-fen-red">
    </div>

    <div>
        <label for="capacity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Capacidad</label>
        <input type="number" name="capacity" id="capacity" min="1" value="{{ old('capacity', $room->capacity ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-800 dark:text-white focus:ring-fen-red focus:border-fen-red">
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
        <textarea name="description" id="description" rows="3"
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm dark:bg-gray-800 dark:text-white focus:ring-fen-red focus:border-fen-red">{{ old('description', $room->description ?? '') }}</textarea>
    </div>
</div>

{{-- ⚙️ Condiciones de la Sala --}}
<hr class="my-6 border-gray-300 dark:border-gray-600">
<h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200 border-b pb-2">⚙️ Condiciones de la Sala</h3>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @php
        $condiciones = [
            'calefaccion' => 'Calefacción',
            'energia_electrica' => 'Energía Eléctrica',
            'existe_aseo' => 'Aseo Disponible',
            'plumones' => 'Plumones',
            'borrador' => 'Borrador',
            'pizarra_limpia' => 'Pizarra Limpia',
            'computador_funcional' => 'Computador Funcional',
            'cables_computador' => 'Cables del Computador',
            'control_remoto_camara' => 'Control Remoto de Cámara',
            'televisor_funcional' => 'Televisor Funcional',
        ];
    @endphp

    @foreach ($condiciones as $campo => $label)
        <label class="flex items-center space-x-2">
            <input type="checkbox" name="{{ $campo }}" id="{{ $campo }}"
                {{ old($campo, $room->$campo ?? false) ? 'checked' : '' }}
                class="rounded border-gray-300 dark:border-gray-600 text-fen-red focus:ring-fen-red dark:bg-gray-800 dark:text-white">
            <span class="text-sm text-gray-800 dark:text-gray-200">{{ $label }}</span>
        </label>
    @endforeach
</div>


{{-- 📚 Usos Académicos --}}
<hr class="my-6 border-gray-300 dark:border-gray-600">
<h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200 border-b pb-2">📚 Usos Académicos</h3>

{{-- Aquí puedes agregar campos relacionados a los usos académicos (checkboxes, selects, etc.) --}}

{{-- 💾 Botón de guardar --}}
<div class="mt-6">
    <!-- Tailwind que usa tus variables CSS -->
<x-button-fen>💾 Guardar</x-button-fen>

</div>