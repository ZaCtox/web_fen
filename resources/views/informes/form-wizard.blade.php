{{-- Formulario de Informes con Principios HCI --}}
@section('title', isset($informe) ? 'Editar Informe' : 'Crear Informe')

@php
    $editing = isset($informe);
@endphp

{{-- Layout genérico del wizard --}}
<x-hci-wizard-layout 
    title="Registro"
    :editing="$editing"
    createDescription="Sube un nuevo informe al sistema con información organizada."
    editDescription="Modifica la información del informe."
    sidebarComponent="informes-progress-sidebar"
    :formAction="$editing ? route('informes.update', $informe->id) : route('informes.store')"
    :formMethod="$editing ? 'PUT' : 'POST'"
    formEnctype="multipart/form-data"
>

{{-- Paso 1: Información Básica --}}
<x-hci-form-section 
    :step="1" 
    title="Información Básica" 
    description="Define el nombre y tipo del informe"
    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z' clip-rule='evenodd'/></svg>"
    section-id="informacion"
    :editing="$editing ?? false"
    :is-active="true"
    :is-first="true"
    contentClass="grid-cols-1 md:grid-cols-2 gap-6"
    style="display: block;"
>
    <x-hci-field 
        name="nombre" 
        type="text" 
        label="Nombre del Informe" 
        :required="true"
        help="Título descriptivo del informe (máximo 150 caracteres)"
        value="{{ old('nombre', $informe->nombre ?? '') }}"
        maxlength="150"
    />

    <x-hci-field 
        name="tipo"
        type="select"
        label="Tipo de Registro"
        :required="true"
        help="Categoriza el tipo de registro para mejor organización"
    >
        <option value="">Selecciona un tipo</option>
        <option value="calendario" {{ old('tipo', $informe->tipo ?? '') === 'calendario' ? 'selected' : '' }}>Calendario</option>
        <option value="academico" {{ old('tipo', $informe->tipo ?? '') === 'academico' ? 'selected' : '' }}>Académico</option>
        <option value="administrativo" {{ old('tipo', $informe->tipo ?? '') === 'administrativo' ? 'selected' : '' }}>Administrativo</option>
        <option value="general" {{ old('tipo', $informe->tipo ?? '') === 'general' ? 'selected' : '' }}>General</option>
    </x-hci-field>

</x-hci-form-section>


                {{-- Paso 2: Archivo y Destinatario --}}
                <x-hci-form-section 
                    :step="2" 
                    title="Archivo y Destinatario" 
                    description="Sube el archivo y especifica el destinatario"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z'/></svg>"
                    section-id="archivo"
                    :editing="$editing ?? false"
                    contentClass="grid-cols-1 gap-6"
                    style="display: block;"
                >
                    {{-- Campo Dirigido a --}}
                    <div class="w-full">
                        <x-hci-field 
                            name="magister_id" 
                            type="select" 
                            label="Dirigido a" 
                            :required="false"
                            help="Selecciona un programa específico o deja en blanco para todos"
                        >
                            <option value="">Todos los programas</option>
                            @foreach($magisters as $magister)
                                <option value="{{ $magister->id }}" 
                                    {{ old('magister_id', $informe->magister_id ?? '') == $magister->id ? 'selected' : '' }}>
                                    {{ $magister->nombre }}
                                </option>
                            @endforeach
                        </x-hci-field>
                    </div>

                    {{-- Área de drag & drop --}}
                    <div class="w-full">
                        <div id="file-drop-zone" class="hci-file-drop-zone"
                         ondrop="handleFileDrop(event)" 
                         ondragover="handleDragOver(event)" 
                         ondragleave="handleDragLeave(event)"
                         onclick="document.getElementById('archivo-input').click()">
                            <div class="hci-file-drop-content">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <span id="file-drop-text">Arrastra tu archivo aquí</span>
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $editing ? 'O haz clic para seleccionar un nuevo archivo' : 'O haz clic para seleccionar un archivo' }}
                                </p>
                                <p class="text-xs text-gray-400 mt-2">
                                    Formatos: PDF, DOC, DOCX • Máximo 4MB
                                </p>
                            </div>
                        </div>

                        <input type="file" 
                               name="archivo" 
                               id="archivo-input" 
                               class="hidden" 
                               accept=".pdf,.doc,.docx"
                               onchange="handleFileSelect(event)"
                               {{ !$editing ? 'required' : '' }}>
                        
                        <div id="file-preview" class="hidden mt-3 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span id="file-name" class="text-sm font-medium text-green-700 dark:text-green-300"></span>
                                <button type="button" onclick="clearFile()" class="ml-auto text-red-500 hover:text-red-700">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        @error('archivo') 
                            <p class="hci-field-error">{{ $message }}</p> 
                        @enderror
                    </div>
                </x-hci-form-section>

                {{-- Paso 3: Resumen --}}
                <x-hci-form-section 
                    :step="3" 
                    title="Resumen y Confirmación" 
                    description="Revisa la información antes de guardar"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z' clip-rule='evenodd'/></svg>"
                    section-id="resumen"
                    :editing="$editing ?? false"
                    :is-last="true"
                    style="display: none;"
                >
                    <div class="bg-[#c4dafa]/30 dark:bg-[#84b6f4]/10 rounded-lg p-6 border border-[#84b6f4]/30 w-full">
                        <div class="space-y-6">
                            {{-- Nombre del Informe --}}
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-[#4d82bc]/10 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-[#4d82bc]" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-1">Nombre del Informe</span>
                                        <p id="summary-nombre" class="text-gray-900 dark:text-white font-semibold text-lg">--</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Tipo --}}
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-[#4d82bc]/10 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-[#4d82bc]" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-1">Tipo de Registro</span>
                                        <p id="summary-tipo" class="text-gray-900 dark:text-white font-semibold text-lg">--</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Archivo --}}
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-[#4d82bc]/10 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-[#4d82bc]" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-1">Archivo</span>
                                        <p id="summary-archivo" class="text-gray-900 dark:text-white font-semibold text-lg">--</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Dirigido a --}}
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-[#4d82bc]/10 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-[#4d82bc]" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-1">Dirigido a</span>
                                        <p id="summary-destinatario" class="text-gray-900 dark:text-white font-semibold text-lg">--</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Nota de Advertencia --}}
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-1">
                                            <strong>📋 Información:</strong>
                                        </p>
                                        <p class="text-sm text-blue-700 dark:text-blue-400">
                                            {{ $editing ? 'Los cambios se aplicarán al informe actual y estarán disponibles para descarga.' : 'El informe será visible para los programas seleccionados una vez guardado.' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-hci-form-section>
</x-hci-wizard-layout>

{{-- Incluir JavaScript del wizard --}}
@push('scripts')
    @vite('resources/js/informes-form-wizard.js')
@endpush



