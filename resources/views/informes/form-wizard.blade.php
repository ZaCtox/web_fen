{{-- Formulario de Informes con Principios HCI --}}
@section('title', isset($informe) ? 'Editar Informe' : 'Crear Informe')

@php
    $editing = isset($informe);
@endphp

{{-- Contenedor principal con principios HCI --}}
<div class="hci-container">
    <div class="hci-section">
        <h1 class="hci-heading-1 flex items-center">
            @if($editing)
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                </svg>
                Editar Informe
            @else
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                </svg>
                Nuevo Informe
            @endif
        </h1>
        <p class="hci-text">
            {{ $editing ? 'Modifica la información del informe.' : 'Sube un nuevo informe al sistema con información organizada.' }}
        </p>
    </div>

    {{-- Layout principal con progreso lateral --}}
    <div class="hci-wizard-layout">
        {{-- Barra de progreso lateral izquierda --}}
        <x-informes-progress-sidebar />

        {{-- Contenido principal del formulario --}}
        <div class="hci-form-content">
            <form class="hci-form" method="POST" action="{{ $editing ? route('informes.update', $informe->id) : route('informes.store') }}" enctype="multipart/form-data">
                @csrf
                @if($editing) @method('PUT') @endif

{{-- Paso 1: Información Básica --}}
<x-hci-form-section 
    :step="1" 
    title="Información Básica" 
    description="Define el nombre y archivo del informe"
    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>"
    section-id="informacion"
    :editing="$editing ?? false"
    :is-active="true"
    :is-first="true"
    style="display: block;"
>
     {{-- ⚙️ Layout mejorado con mejor alineación --}}
     {{-- Primera fila: Nombre y Tipo alineados como en cursos --}}
     <div class="flex flex-col md:flex-row md:items-start md:gap-16">
            <div class="w-full md:w-auto">
                <x-hci-field 
                    name="nombre" 
                    type="text" 
                    label="Nombre del Informe" 
                    :required="true"
                    icon=""
                    help="Título descriptivo del informe (máximo 150 caracteres)"
                    value="{{ old('nombre', $informe->nombre ?? '') }}"
                    maxlength="150"
                    style="width: 300px !important;"
                />
            </div>

            <div class="w-full md:flex-grow">
                <x-hci-field 
                    name="tipo"
                    type="select"
                    label="Tipo de Registro"
                    :required="true"
                    help="Categoriza el tipo de registro para mejor organización"
                    style="min-width: 300px !important;"
                >
                    <option value="">Selecciona un tipo</option>
                    <option value="calendario" {{ old('tipo', $informe->tipo ?? '') === 'calendario' ? 'selected' : '' }}>Calendario</option>
                    <option value="academico" {{ old('tipo', $informe->tipo ?? '') === 'academico' ? 'selected' : '' }}>Académico</option>
                    <option value="administrativo" {{ old('tipo', $informe->tipo ?? '') === 'administrativo' ? 'selected' : '' }}>Administrativo</option>
                    <option value="general" {{ old('tipo', $informe->tipo ?? '') === 'general' ? 'selected' : '' }}>General</option>
                </x-hci-field>
            </div>
        </div>

</x-hci-form-section>


                {{-- Paso 2: Archivo y Destinatario --}}
                <x-hci-form-section 
                    :step="2" 
                    title="Archivo y Destinatario" 
                    description="Sube el archivo y especifica el destinatario"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z'/></svg>"
                    section-id="archivo"
                    :editing="$editing ?? false"
                    style="display: block;"
                >
                    {{-- Columna única con ambos elementos --}}
                    <div style="width: 350px !important;">
                        {{-- Campo Dirigido a --}}
                        <div class="mb-6">
                            <x-hci-field 
                                name="magister_id" 
                                type="select" 
                                label="Dirigido a" 
                                :required="false"
                                icon=""
                                help="Selecciona un programa específico o deja en blanco para todos"
                            >
                                <option value="">Todos los programas</option>
                                @foreach($magisters as $magister)
                                    <option value="{{ $magister->id }}" 
                                        {{ old('magister_id', $informe->magister_id ?? '') == $magister->id ? 'selected' : '' }}
                                        style="white-space: normal; word-wrap: break-word; max-width: 100%;">
                                        {{ $magister->nombre }}
                                    </option>
                                @endforeach
                            </x-hci-field>
                        </div>

                        {{-- Área de drag & drop --}}
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
                    title="Resumen" 
                    description="Revisa la información antes de guardar"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>"
                    section-id="resumen"
                    :editing="$editing ?? false"
                    :is-last="true"
                    style="display: none;"
                >
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Nombre del Informe</h4>
                            <p id="summary-nombre" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">--</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Archivo</h4>
                            <p id="summary-archivo" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">--</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg md:col-span-2">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Dirigido a</h4>
                            <p id="summary-destinatario" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">--</p>
                        </div>
                    </div>
                </x-hci-form-section>
            </form>
        </div>
    </div>
</div>
