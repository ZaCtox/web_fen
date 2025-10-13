{{-- Formulario de Mallas Curriculares con Wizard Genérico --}}
@section('title', isset($mallaCurricular) ? 'Editar Malla Curricular' : 'Crear Malla Curricular')

@php
    $editing = isset($mallaCurricular);
    
    // Definir los pasos del wizard
    $wizardSteps = [
        ['title' => 'Información Básica', 'description' => 'Datos principales'],
        ['title' => 'Período de Vigencia', 'description' => 'Años de validez'],
        ['title' => 'Descripción y Estado', 'description' => 'Detalles y activación'],
        ['title' => 'Resumen', 'description' => 'Revisar y confirmar']
    ];
@endphp

<x-hci-wizard-layout
    title="Malla Curricular"
    :editing="$editing"
    createDescription="Crea una nueva versión de malla curricular con información organizada."
    editDescription="Modifica la información de la malla curricular."
    :steps="$wizardSteps"
    :formAction="$editing ? route('mallas-curriculares.update', $mallaCurricular) : route('mallas-curriculares.store')"
    :formMethod="$editing ? 'PUT' : 'POST'"
>
    {{-- Sección 1: Información Básica --}}
    <x-hci-form-section 
                    :step="1" 
                    title="Información Básica" 
                    description="Datos principales de la malla curricular"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z' clip-rule='evenodd'/></svg>"
                    section-id="basica"
                    content-class="grid-cols-1 gap-6"
                    :is-active="true"
                    :is-first="true"
                    :editing="$editing"
                >
        <x-hci-field 
            name="magister_id"
            type="select"
            label="Programa de Magíster"
            :required="true"
            icon=""
            help="Selecciona el programa al que pertenece esta malla"
        >
        <option value="">-- Selecciona un Programa --</option>
        @foreach($magisters as $magister)
            <option value="{{ $magister->id }}" {{ old('magister_id', $mallaCurricular->magister_id ?? '') == $magister->id ? 'selected' : '' }}>
                {{ $magister->nombre }}
            </option>
        @endforeach
        </x-hci-field>

        <x-hci-field 
            name="nombre"
            label="Nombre de la Malla"
            placeholder="Ej: Malla Curricular 2024-2026"
            value="{{ old('nombre', $mallaCurricular->nombre ?? '') }}"
            :required="true"
            icon=""
            help="Nombre descriptivo de la versión de la malla"
            maxlength="255"
        />

        <x-hci-field 
            name="codigo"
            label="Código"
            placeholder="Ej: GSS-2024-V1"
            value="{{ old('codigo', $mallaCurricular->codigo ?? '') }}"
            :required="true"
            icon=""
            help="Código único (formato: SIGLAS-AÑO-VERSION)"
            maxlength="50"
        />
    </x-hci-form-section>

                {{-- Sección 2: Periodo de Vigencia --}}
                <x-hci-form-section 
                    :step="2" 
                    title="Periodo de Vigencia" 
                    description="Define el periodo en que esta malla curricular estará vigente"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z' clip-rule='evenodd'/></svg>"
                    section-id="vigencia"
                    content-class="grid-cols-1 md:grid-cols-2 gap-6"
                    :editing="$editing"
                >
        <x-hci-field 
            name="año_inicio"
            type="number"
            label="Año de Inicio"
            placeholder="{{ date('Y') }}"
            value="{{ old('año_inicio', $mallaCurricular->año_inicio ?? date('Y')) }}"
            :required="true"
            icon=""
            help="Año en que comienza la vigencia de esta malla"
            min="2020"
            max="2100"
        />

        <x-hci-field 
            name="año_fin"
            type="number"
            label="Año de Fin (Opcional)"
            placeholder="Dejar vacío si no tiene fin"
            value="{{ old('año_fin', $mallaCurricular->año_fin ?? '') }}"
            icon=""
            help="Año en que termina la vigencia (opcional)"
            min="2020"
            max="2100"
        />

        <div class="col-span-full p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-700">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-blue-800 dark:text-blue-200">
                    <strong>Nota:</strong> Si no defines un año de fin, la malla estará vigente indefinidamente hasta que la desactives.
                </p>
            </div>
        </div>
    </x-hci-form-section>

                {{-- Sección 3: Descripción y Estado --}}
                <x-hci-form-section 
                    :step="3" 
                    title="Descripción y Estado" 
                    description="Información adicional y configuración de disponibilidad"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z' clip-rule='evenodd'/></svg>"
                    section-id="descripcion"
                    content-class="w-full"
                    :editing="$editing"
                >
        <x-hci-field 
            name="descripcion"
            type="textarea"
            label="Descripción"
            placeholder="Describe las características principales de esta versión de la malla curricular, cambios respecto a versiones anteriores, etc."
            value="{{ old('descripcion', $mallaCurricular->descripcion ?? '') }}"
            icon=""
            help="Información detallada sobre esta malla curricular"
            rows="6"
        />

        <div class="flex items-start gap-3 p-4 bg-[#c4dafa]/30 dark:bg-[#84b6f4]/10 rounded-lg border border-[#84b6f4]/30 mt-6">
            <input type="checkbox" 
                   id="activa" 
                   name="activa" 
                   value="1"
                   {{ old('activa', $mallaCurricular->activa ?? true) ? 'checked' : '' }}
                   class="mt-1 w-5 h-5 text-[#4d82bc] bg-gray-100 border-gray-300 rounded focus:ring-[#4d82bc] dark:bg-gray-700 dark:border-gray-600">
            <div class="flex-1">
                <label for="activa" class="block text-sm font-medium text-[#005187] dark:text-[#84b6f4] cursor-pointer">
                    Malla Curricular Activa
                </label>
                <p class="text-xs text-[#005187]/70 dark:text-[#84b6f4]/70 mt-1">
                    Solo las mallas activas estarán disponibles para asignar a nuevos cursos
                </p>
            </div>
        </div>
    </x-hci-form-section>

                {{-- Sección 4: Resumen --}}
                <x-hci-form-section 
                    :step="4" 
                    title="Resumen" 
                    description="Revisa y confirma los datos antes de enviar"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>"
                    section-id="resumen"
                    content-class="w-full"
                    :is-last="true"
                    :editing="$editing"
                >
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 rounded-xl p-6 border border-blue-200 dark:border-gray-600">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Resumen de Malla Curricular</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Revisa la información antes de confirmar</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-lg">📚</span>
                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Programa</h4>
                    </div>
                    <p id="summary-magister" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">—</p>
                </div>
                
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-lg">📋</span>
                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Nombre</h4>
                    </div>
                    <p id="summary-nombre" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">—</p>
                </div>
                
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-lg">🔖</span>
                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Código</h4>
                    </div>
                    <p id="summary-codigo" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">—</p>
                </div>
                
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-lg">📅</span>
                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Vigencia</h4>
                    </div>
                    <p id="summary-vigencia" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">—</p>
                </div>
                
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-lg">⚡</span>
                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Estado</h4>
                    </div>
                    <p id="summary-estado" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">—</p>
                </div>
            </div>
        </div>
    </x-hci-form-section>
</x-hci-wizard-layout>

{{-- Scripts del formulario --}}
@push('scripts')
    @vite('resources/js/mallas-curriculares-form-wizard.js')
@endpush




