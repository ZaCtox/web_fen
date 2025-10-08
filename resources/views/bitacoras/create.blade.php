@section('title', 'Nuevo Reporte')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
            Nuevo Reporte de Bitácora
        </h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Bitácoras', 'url' => route('bitacoras.index')],
        ['label' => 'Nuevo Reporte', 'url' => '#']
    ]" />

    <div class="p-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <form action="{{ route('bitacoras.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Título --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Título del Reporte</label>
                    <input type="text" name="titulo" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent dark:bg-gray-700 dark:text-gray-200 transition-all duration-200" 
                           placeholder="Ej: Reporte de mantenimiento sala FEN-1"
                           required>
                </div>

                {{-- Descripción --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descripción del Reporte</label>
                    <textarea name="descripcion" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent dark:bg-gray-700 dark:text-gray-200 transition-all duration-200"
                              placeholder="Describe detalladamente lo observado, el problema encontrado o la situación reportada..."></textarea>
                </div>

                {{-- Ubicación --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de Ubicación</label>
                    <select id="ubicacion" name="ubicacion" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent dark:bg-gray-700 dark:text-gray-200 transition-all duration-200" 
                            required>
                        <option value="">Seleccione el tipo de ubicación</option>
                        <option value="Sala">Sala de Clases</option>
                        <option value="Baño">Baño</option>
                        <option value="Pasillo">Pasillo</option>
                        <option value="Laboratorio">Laboratorio</option>
                        <option value="Oficina">Oficina</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>

                {{-- Si elige Sala -> mostrar rooms --}}
                <div id="salaField" class="mb-6 hidden">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Seleccionar Sala</label>
                    <select name="room_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent dark:bg-gray-700 dark:text-gray-200 transition-all duration-200">
                        <option value="">Seleccione una sala específica</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Si elige Baño, Pasillo u Otro -> mostrar detalle --}}
                <div id="detalleUbicacionField" class="mb-6 hidden">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Detalle de Ubicación</label>
                    <input type="text" name="detalle_ubicacion" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent dark:bg-gray-700 dark:text-gray-200 transition-all duration-200"
                           placeholder="Ej: Baño primer piso, Pasillo edificio A, Laboratorio de computación">
                </div>

                {{-- Foto --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Evidencia Fotográfica (Opcional)</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-[#4d82bc] transition-colors duration-200">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                <label for="foto" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-[#4d82bc] hover:text-[#005187] focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-[#4d82bc]">
                                    <span>Subir una foto</span>
                                    <input id="foto" name="foto" type="file" accept="image/*" class="sr-only">
                                </label>
                                <p class="pl-1">o arrastra y suelta</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF hasta 10MB</p>
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-600">
                    <a href="{{ route('bitacoras.index') }}"
                       class="hci-button hci-lift hci-focus-ring inline-flex items-center gap-2 px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg shadow transition-all duration-200">
                        <img src="{{ asset('icons/back.svg') }}" alt="Cancelar" class="w-4 h-4">
                        <span>Cancelar</span>
                    </a>
                    <button type="submit"
                            class="hci-button hci-lift hci-focus-ring inline-flex items-center gap-2 px-4 py-2 bg-[#005187] hover:bg-[#4d82bc] text-white rounded-lg shadow transition-all duration-200">
                        <img src="{{ asset('icons/save.svg') }}" alt="Guardar" class="w-4 h-4">
                        <span>Crear Reporte</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('ubicacion').addEventListener('change', function () {
            const salaField = document.getElementById('salaField');
            const detalleField = document.getElementById('detalleUbicacionField');

            salaField.classList.add('hidden');
            detalleField.classList.add('hidden');

            if (this.value === 'Sala') {
                salaField.classList.remove('hidden');
            } else if (['Baño', 'Pasillo', 'Laboratorio', 'Oficina', 'Otro'].includes(this.value)) {
                detalleField.classList.remove('hidden');
            }
        });

        // Preview de imagen
        document.getElementById('foto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Crear preview si no existe
                    let preview = document.getElementById('image-preview');
                    if (!preview) {
                        preview = document.createElement('div');
                        preview.id = 'image-preview';
                        preview.className = 'mt-4 text-center';
                        document.querySelector('input[name="foto"]').parentNode.parentNode.appendChild(preview);
                    }
                    preview.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" class="mx-auto h-32 w-32 object-cover rounded-lg shadow">
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Vista previa</p>
                    `;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
    @endpush
</x-app-layout>
