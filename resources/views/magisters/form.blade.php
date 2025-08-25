<form x-data="{ submitting:false }" x-on:submit="submitting=true"
    action="{{ isset($magister) ? route('magisters.update', $magister) : route('magisters.store') }}" method="POST"
    class="space-y-4">
    @csrf
    @if(isset($magister))
        @method('PUT')
    @endif

    <div>
        <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Nombre del Magíster
        </label>
        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $magister->nombre ?? '') }}" required
            maxlength="150" autofocus class="mt-1 block w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500
                   dark:bg-gray-700 dark:text-white dark:border-gray-600" placeholder="Ej: Economía">
        @error('nombre')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div x-data="{ color: '{{ old('color', $magister->color ?? '#3b82f6') }}' }">
        <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Color</label>
        <div class="flex items-center gap-3">
            <!-- Selector de color -->
            <input type="color" :value="color" @input="color = $event.target.value"
                class="w-16 h-10 border-gray-300 rounded-md shadow-sm">

            <!-- Campo de texto hexadecimal editable -->
            <input type="text" name="color" x-model="color"
                class="w-28 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white px-2 py-1 text-sm"
                placeholder="#3b82f6">
        </div>
    </div>

    {{-- Encargado --}}
    <div>
        <label for="encargado" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Encargado</label>
        <input type="text" name="encargado" id="encargado" value="{{ old('encargado', $magister->encargado ?? '') }}"
            class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
    </div>

    {{-- Teléfono --}}
    <div>
        <label for="telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teléfono</label>
        <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $magister->telefono ?? '') }}"
            class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
    </div>

    {{-- Correo --}}
    <div>
        <label for="correo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo</label>
        <input type="email" name="correo" id="correo" value="{{ old('correo', $magister->correo ?? '') }}"
            class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
    </div>



    <div class="pt-4 flex items-center justify-between">
        <a href="{{ route('magisters.index') }}" class="inline-flex items-center justify-center px-4 py-2 rounded
                  bg-gray-500 hover:bg-gray-600 text-white transition">
            ⬅️ Volver
        </a>

        <button type="submit" x-bind:disabled="submitting" class="inline-flex items-center justify-center px-4 py-2 rounded
                       bg-blue-600 hover:bg-blue-700 disabled:opacity-60 disabled:cursor-not-allowed
                       text-white transition">
            <span x-show="!submitting">{{ isset($magister) ? 'Actualizar' : 'Crear' }}</span>
            <span x-show="submitting">Guardando…</span>
        </button>
    </div>
</form>