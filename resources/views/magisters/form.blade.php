<form x-data="{ submitting:false }" x-on:submit="submitting=true"
    action="{{ isset($magister) ? route('magisters.update', $magister) : route('magisters.store') }}" method="POST"
<<<<<<< Updated upstream
    class="space-y-4">
=======
    class="space-y-6 bg-white dark:bg-gray-900 p-6 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">

>>>>>>> Stashed changes
    @csrf
    @if(isset($magister))
        @method('PUT')
    @endif

<<<<<<< Updated upstream
=======
    {{-- Nombre --}}
>>>>>>> Stashed changes
    <div>
        <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Nombre del Programa
        </label>
        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $magister->nombre ?? '') }}" required
<<<<<<< Updated upstream
            maxlength="150" autofocus class="mt-1 block w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500
                   dark:bg-gray-700 dark:text-white dark:border-gray-600" placeholder="Ej: Economía">
=======
            maxlength="150" autofocus class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white px-3 py-2
                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
            placeholder="Ej: Economía">
>>>>>>> Stashed changes
        @error('nombre')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

<<<<<<< Updated upstream
    <div x-data="{ color: '{{ old('color', $magister->color ?? '#3b82f6') }}' }">
        <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Color</label>
        <div class="flex items-center gap-3">
            <!-- Selector de color -->
            <input type="color" :value="color" @input="color = $event.target.value"
                class="w-16 h-10 border-gray-300 rounded-md shadow-sm">

            <!-- Campo de texto hexadecimal editable -->
            <input type="text" name="color" x-model="color"
                class="w-28 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white px-2 py-1 text-sm"
=======
    {{-- Color --}}
    <div x-data="{ color: '{{ old('color', $magister->color ?? '#3b82f6') }}' }">
        <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Color</label>
        <div class="flex items-center gap-3 mt-1">
            <input type="color" :value="color" @input="color = $event.target.value"
                class="w-16 h-10 border rounded-md shadow-sm cursor-pointer">

            <input type="text" name="color" x-model="color"
                class="w-28 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
>>>>>>> Stashed changes
                placeholder="#3b82f6">
        </div>
    </div>

    {{-- Encargado --}}
    <div>
        <label for="encargado" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Encargado</label>
        <input type="text" name="encargado" id="encargado" value="{{ old('encargado', $magister->encargado ?? '') }}"
<<<<<<< Updated upstream
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
=======
            class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
            placeholder="Nombre encargado">

    </div>

    {{-- Asistente --}}
    <div>
        <label for="asistente" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Asistente</label>
        <input type="text" name="asistente" id="asistente" value="{{ old('asistente', $magister->asistente ?? '') }}"
            class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
            placeholder="Nombre asistente">
    </div>

    {{-- Teléfono Asistente --}}
    <div>
        <label for="telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teléfono
            Asitente</label>
        <input type="text" name="telefono" id="telefono" placeholder="Ej: 712000000 (fijo) o 912345678 (celular)"
            maxlength="9" pattern="^(712\d{6}|9\d{8})$"
            title="Ingrese un teléfono válido: fijo 712XXXXXX o celular 9XXXXXXXX"
            class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white px-3 py-2"
            required>

    </div>

    {{-- Anexo --}}
    <div>
        <label for="anexo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Anexo</label>
        <input type="text" name="anexo" id="anexo" value="{{ old('anexo', $magister->anexo ?? '') }}"
            class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white px-3 py-2"
            placeholder="Número de anexo">
    </div>

    {{-- Correo Asistente --}}
    <div>
        <label for="correo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo Asistente</label>
        <input type="email" name="correo" id="correo" value="{{ old('correo', $magister->correo ?? '') }}"
            class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
            placeholder="Ej: correo@utalca.cl">
    </div>

    {{-- Botones --}}
    <div class="mt-6 flex justify-between items-center">
        <a href="{{ route('magisters.index') }}" class="inline-block bg-[#4d82bc] hover:bg-[#005187] 
               text-white px-4 py-2 rounded-md shadow-md transition">
            <img src="{{ asset('icons/back.svg') }}" alt="back" class="w-5 h-5">
        </a>
        <button type="submit" class="inline-flex items-center justify-center bg-[#005187] hover:bg-[#4d82bc] 
                                   text-white px-4 py-2 rounded-lg shadow text-sm font-medium 
                                   transition transform hover:scale-105">
            <img src="{{ asset('icons/save.svg') }}" alt="Guardar" class="w-5 h-5">
>>>>>>> Stashed changes
        </button>
    </div>
</form>