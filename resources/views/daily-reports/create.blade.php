@section('title', 'Nuevo Reporte Diario')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-[#005187] dark:text-[#84b6f4]">
            Nuevo Reporte Diario
        </h2>
    </x-slot>

    {{-- Breadcrumb --}}
    <x-hci-breadcrumb :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Reportes Diarios', 'url' => route('daily-reports.index')],
        ['label' => 'Nuevo Reporte', 'url' => '#']
    ]" />

    <div class="p-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <form action="{{ route('daily-reports.store') }}" method="POST" enctype="multipart/form-data" id="daily-report-form">
                @csrf

                {{-- Información del Reporte --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Información del Reporte</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Título del Reporte</label>
                            <input type="text" name="title" 
                                   value="{{ old('title', $tituloSugerido) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent dark:bg-gray-700 dark:text-gray-200 transition-all duration-200" 
                                   placeholder="Ej: Reporte Jueves 29 de Octubre 2025"
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha del Reporte</label>
                            <input type="date" name="report_date" 
                                   value="{{ old('report_date', $today) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent dark:bg-gray-700 dark:text-gray-200 transition-all duration-200" 
                                   required>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Resumen General (Opcional)</label>
                        <textarea name="summary" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent dark:bg-gray-700 dark:text-gray-200 transition-all duration-200"
                                  placeholder="Resumen general de las observaciones del día...">{{ old('summary') }}</textarea>
                    </div>
                </div>

                {{-- Entradas del Reporte --}}
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Observaciones del Día</h3>
                        <button type="button" 
                                onclick="agregarEntrada()"
                                class="hci-button hci-lift hci-focus-ring inline-flex items-center gap-2 px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition-all duration-200">
                            <img src="{{ asset('icons/agregar.svg') }}" alt="Agregar" class="w-4 h-4">
                            <span>Agregar Observación</span>
                        </button>
                    </div>
                    
                    <div id="entradas-container">
                        {{-- Las entradas se agregarán dinámicamente aquí --}}
                    </div>
                </div>

                {{-- Botones --}}
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-600">
                    <a href="{{ route('daily-reports.index') }}"
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
        let contadorEntradas = 0;
        const rooms = @json($rooms);

        function agregarEntrada() {
            contadorEntradas++;
            const container = document.getElementById('entradas-container');
            
            const entradaHtml = `
                <div class="entrada-item border border-gray-200 dark:border-gray-600 rounded-lg p-4 mb-4 bg-gray-50 dark:bg-gray-700" data-index="${contadorEntradas}">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-md font-medium text-gray-900 dark:text-gray-100">Observación #${contadorEntradas}</h4>
                        <button type="button" 
                                onclick="eliminarEntrada(${contadorEntradas})"
                                class="hci-button hci-lift hci-focus-ring p-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all duration-200"
                                title="Eliminar observación">
                            <img src="{{ asset('icons/trash.svg') }}" alt="Eliminar" class="w-4 h-4">
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de Ubicación</label>
                            <select name="entries[${contadorEntradas}][location_type]" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent dark:bg-gray-700 dark:text-gray-200 transition-all duration-200" 
                                    onchange="toggleLocationFields(${contadorEntradas})"
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
                        
                        <div id="sala-field-${contadorEntradas}" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Seleccionar Sala</label>
                            <select name="entries[${contadorEntradas}][room_id]" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent dark:bg-gray-700 dark:text-gray-200 transition-all duration-200">
                                <option value="">Seleccione una sala específica</option>
                                ${rooms.map(room => `<option value="${room.id}">${room.name}</option>`).join('')}
                            </select>
                        </div>
                        
                        <div id="detalle-field-${contadorEntradas}" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Detalle de Ubicación</label>
                            <input type="text" name="entries[${contadorEntradas}][location_detail]" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent dark:bg-gray-700 dark:text-gray-200 transition-all duration-200"
                                   placeholder="Ej: Baño primer piso, Pasillo edificio A">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Observación</label>
                        <textarea name="entries[${contadorEntradas}][observation]" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent dark:bg-gray-700 dark:text-gray-200 transition-all duration-200"
                                  placeholder="Describe lo observado (mínimo 5 caracteres)..."
                                  required></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Evidencia Fotográfica (Opcional)</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-[#4d82bc] transition-colors duration-200">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                    <label for="photo-${contadorEntradas}" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-[#4d82bc] hover:text-[#005187] focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-[#4d82bc]">
                                        <span>Subir una foto</span>
                                        <input id="photo-${contadorEntradas}" name="entries[${contadorEntradas}][photo]" type="file" accept="image/*" class="sr-only" onchange="previewImage(${contadorEntradas}, this)">
                                    </label>
                                    <p class="pl-1">o arrastra y suelta</p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF hasta 10MB</p>
                            </div>
                        </div>
                        <div id="image-preview-${contadorEntradas}" class="mt-4 text-center hidden">
                            <img id="preview-img-${contadorEntradas}" src="" alt="Preview" class="mx-auto h-32 w-32 object-cover rounded-lg shadow">
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Vista previa</p>
                        </div>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', entradaHtml);
        }

        function eliminarEntrada(index) {
            const entrada = document.querySelector(`[data-index="${index}"]`);
            if (entrada) {
                entrada.remove();
            }
        }

        function toggleLocationFields(index) {
            const locationType = document.querySelector(`select[name="entries[${index}][location_type]"]`).value;
            const salaField = document.getElementById(`sala-field-${index}`);
            const detalleField = document.getElementById(`detalle-field-${index}`);
            
            salaField.classList.add('hidden');
            detalleField.classList.add('hidden');
            
            if (locationType === 'Sala') {
                salaField.classList.remove('hidden');
            } else if (['Baño', 'Pasillo', 'Laboratorio', 'Oficina', 'Otro'].includes(locationType)) {
                detalleField.classList.remove('hidden');
            }
        }

        function previewImage(index, input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById(`image-preview-${index}`);
                    const img = document.getElementById(`preview-img-${index}`);
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        // Agregar una entrada inicial
        document.addEventListener('DOMContentLoaded', function() {
            agregarEntrada();
        });
    </script>
    @endpush
</x-app-layout>
