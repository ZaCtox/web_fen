{{-- Formulario de Incidencias con Wizard Genérico --}}
@section('title', isset($incident) ? 'Editar Incidencia' : 'Crear Incidencia')

@php
    $editing = isset($incident);
    
    // Definir los pasos del wizard
    $wizardSteps = [
        ['title' => 'Información Básica', 'description' => 'Describe el problema'],
        ['title' => 'Ubicación', 'description' => 'Especifica dónde ocurrió'],
        ['title' => 'Evidencia', 'description' => 'Adjunta evidencia'],
        ['title' => 'Resumen', 'description' => 'Revisar información']
    ];
@endphp

<x-hci-wizard-layout
    title="Incidencia"
    :editing="$editing"
    createDescription="Reporta un problema o incidencia en el sistema."
    editDescription="Modifica la información de la incidencia."
    sidebarComponent="incidencias-progress-sidebar"
    :formAction="$editing ? route('incidencias.update', $incident->id) : route('incidencias.store')"
    :formMethod="$editing ? 'PUT' : 'POST'"
    formEnctype="multipart/form-data"
>

    {{-- Sección 1: Información Básica --}}
    <x-hci-form-section 
        :step="1" 
        title="Información Básica" 
        description="Describe el problema o incidencia"
        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z' clip-rule='evenodd'/></svg>"
        section-id="basica"
        :is-active="true"
        :is-first="true"
        :editing="$editing"
        contentClass="grid-cols-1 gap-6"
    >
        <div class="w-full">
            <x-hci-field 
                name="titulo" 
                type="text" 
                label="Título de la Incidencia" 
                :required="true"
                help="Título descriptivo del problema (máximo 100 caracteres)"
                value="{{ old('titulo', $incident->titulo ?? '') }}"
                maxlength="100"
            />
        </div>

        <div class="w-full">
            <x-hci-field 
                name="descripcion" 
                type="textarea" 
                label="Descripción del Problema" 
                :required="true"
                help="Describe detalladamente el problema encontrado"
                value="{{ old('descripcion', $incident->descripcion ?? '') }}"
                rows="6"
            />
        </div>
    </x-hci-form-section>

    {{-- Sección 2: Ubicación --}}
    <x-hci-form-section 
        :step="2" 
        title="Ubicación" 
        description="Especifica dónde ocurrió el problema"
        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z' clip-rule='evenodd'/></svg>"
        section-id="ubicacion"
        :editing="$editing"
        contentClass="grid-cols-1 gap-6"
    >
        <div class="w-full">
            <x-hci-field 
                name="magister_id" 
                type="select" 
                label="Programa" 
                :required="false"
                help="Selecciona el programa asociado a la incidencia (opcional)"
            >
                <option value="">-- Selecciona un Programa --</option>
                @foreach($magisters as $magister)
                    <option value="{{ $magister->id }}" 
                        {{ old('magister_id', $incident->magister_id ?? '') == $magister->id ? 'selected' : '' }}>
                        {{ $magister->nombre }}
                    </option>
                @endforeach
            </x-hci-field>
        </div>

        <div class="w-full">
            <x-hci-field 
                name="room_id" 
                type="select" 
                label="Sala Afectada" 
                :required="true"
                help="Selecciona la sala donde ocurrió el problema"
            >
                <option value="">-- Selecciona una Sala --</option>
                @foreach($salas as $sala)
                    <option value="{{ $sala->id }}" 
                        {{ old('room_id', $incident->room_id ?? '') == $sala->id ? 'selected' : '' }}>
                        {{ $sala->name }} ({{ $sala->location }})
                    </option>
                @endforeach
            </x-hci-field>
        </div>
    </x-hci-form-section>

    {{-- Sección 3: Evidencia --}}
    <x-hci-form-section 
        :step="3" 
        title="Evidencia" 
        description="Adjunta evidencia del problema"
        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z' clip-rule='evenodd'/></svg>"
        section-id="evidencia"
        :editing="$editing"
        contentClass="grid-cols-1 gap-6"
    >
        {{-- Área de drag & drop para imagen --}}
        <div class="w-full">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Foto del Problema
            </label>
            <div id="image-drop-zone" class="hci-file-drop-zone"
                 ondrop="handleImageDrop(event)" 
                 ondragover="handleDragOver(event)" 
                 ondragleave="handleDragLeave(event)"
                 onclick="document.getElementById('imagen-input').click()">
                <div class="hci-file-drop-content">
                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <span id="image-drop-text">Arrastra tu imagen aquí</span>
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $editing ? 'O haz clic para seleccionar una nueva imagen' : 'O haz clic para seleccionar una imagen' }}
                    </p>
                    <p class="text-xs text-gray-400 mt-2">
                        Formatos: JPG, PNG, WEBP • Máximo 2MB
                    </p>
                </div>
            </div>

            <input type="file" 
                   name="imagen" 
                   id="imagen-input" 
                   class="hidden" 
                   accept="image/*"
                   onchange="handleImageSelect(event)">
            
            <div id="image-preview" class="hidden mt-3 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <div class="flex items-center gap-3">
                    <img id="preview-thumbnail" src="" alt="Vista previa" class="w-16 h-16 object-cover rounded-lg">
                    <div class="flex-1">
                        <svg class="w-5 h-5 text-green-500 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span id="image-name" class="text-sm font-medium text-green-700 dark:text-green-300"></span>
                    </div>
                    <button type="button" onclick="clearImage()" class="text-red-500 hover:text-red-700">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                Adjunta una imagen que muestre el problema (opcional)
            </p>
            
            @error('imagen') 
                <p class="hci-field-error">{{ $message }}</p> 
            @enderror
        </div>

        <div class="w-full">
            <x-hci-field 
                name="nro_ticket" 
                type="text" 
                label="N° Ticket Jira" 
                :required="false"
                help="Número del ticket en Jira (opcional)"
                value="{{ old('nro_ticket', $incident->nro_ticket ?? '') }}"
                placeholder="Ej: 2364552"
            />
        </div>
    </x-hci-form-section>

    {{-- Sección 4: Resumen --}}
    <x-hci-form-section 
        :step="4" 
        title="Resumen y Confirmación" 
        description="Revisa la información antes de enviar"
        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z' clip-rule='evenodd'/></svg>"
        section-id="resumen"
        :is-last="true"
        :editing="$editing"
    >
        <div class="bg-[#c4dafa]/30 dark:bg-[#84b6f4]/10 rounded-lg p-6 border border-[#84b6f4]/30 w-full">
            <div class="space-y-6">
                {{-- Título --}}
                <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-[#4d82bc]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#4d82bc]" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-1">Título de la Incidencia</span>
                            <p id="summary-titulo" class="text-gray-900 dark:text-white font-semibold text-lg">--</p>
                        </div>
                    </div>
                </div>

                {{-- Descripción --}}
                <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-[#4d82bc]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#4d82bc]" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-1">Descripción del Problema</span>
                            <p id="summary-descripcion" class="text-gray-900 dark:text-white whitespace-pre-wrap leading-relaxed">--</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Programa --}}
                    <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-[#4d82bc]/10 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#4d82bc]" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-1">Programa</span>
                                <p id="summary-programa" class="text-gray-900 dark:text-white font-semibold">--</p>
                            </div>
                        </div>
                    </div>

                    {{-- Sala --}}
                    <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-[#4d82bc]/10 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#4d82bc]" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-1">Sala Afectada</span>
                                <p id="summary-sala" class="text-gray-900 dark:text-white font-semibold">--</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Imagen --}}
                    <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-[#4d82bc]/10 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#4d82bc]" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-1">Evidencia Fotográfica</span>
                                <p id="summary-imagen" class="text-gray-900 dark:text-white font-semibold">--</p>
                            </div>
                        </div>
                    </div>

                    {{-- Ticket --}}
                    <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-[#4d82bc]/10 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#4d82bc]" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-1">Ticket Jira</span>
                                <p id="summary-ticket" class="text-gray-900 dark:text-white font-semibold">--</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Nota de Advertencia --}}
                <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 border border-yellow-200 dark:border-yellow-800">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-yellow-800 dark:text-yellow-300 mb-1">
                                <strong>⚠️ Importante:</strong>
                            </p>
                            <p class="text-sm text-yellow-700 dark:text-yellow-400">
                                {{ $editing ? 'Los cambios se aplicarán a la incidencia actual.' : 'Se creará una nueva incidencia que será visible para el equipo correspondiente.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-hci-form-section>
</x-hci-wizard-layout>

@push('scripts')
    @vite('resources/js/incidencias-form-wizard.js')
@endpush



