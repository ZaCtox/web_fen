{{-- Formulario de Cursos con Wizard Genérico --}}
@section('title', isset($course) ? 'Editar Curso' : 'Crear Curso')

@php
    $editing = isset($course);
    
    // Definir los pasos del wizard
    $wizardSteps = [
        ['title' => 'Información Básica', 'description' => 'Nombre del curso'],
        ['title' => 'Programa y Período', 'description' => 'Asignación académica'],
        ['title' => 'Resumen', 'description' => 'Revisar y confirmar']
    ];
@endphp

<x-hci-wizard-layout
    title="Curso"
    :editing="$editing"
    createDescription="Crea un nuevo curso con información organizada y estructurada."
    editDescription="Modifica la información del curso académico."
    :steps="$wizardSteps"
    :formAction="$editing ? route('courses.update', $course) : route('courses.store')"
    :formMethod="$editing ? 'PUT' : 'POST'"
>
    {{-- Sección 1: Información Básica --}}
    <x-hci-form-section 
        :step="1" 
        title="Información Básica" 
        description="Datos principales del curso"
        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z' clip-rule='evenodd'/></svg>"
        section-id="basica"
        content-class="w-full"
        :is-active="true"
        :is-first="true"
        :editing="$editing"
    >
        <x-hci-field 
            name="nombre"
            label="Nombre del Curso"
            placeholder="Ej: Economía Aplicada"
            value="{{ old('nombre', $course->nombre ?? '') }}"
            :required="true"
            icon=""
            help="Nombre descriptivo del curso académico"
            maxlength="150"
        />
    </x-hci-form-section>

    {{-- Sección 2: Programa y Período --}}
    <x-hci-form-section 
        :step="2" 
        title="Programa y Período" 
        description="Asignación del programa y período académico (Año 1: Trimestres I, II, III | Año 2: Trimestres IV, V, VI)"
        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>"
        section-id="programa"
        content-class="grid-cols-1 gap-6"
        :editing="$editing"
    >
        <x-hci-field 
            name="magister_id"
            type="select"
            label="Programa Académico"
            :required="true"
            icon=""
            help="Selecciona el programa al que pertenece el curso"
            id="magister_id"
        >
            <option value="">-- Selecciona un Programa --</option>
            @foreach($magisters as $magister)
                <option value="{{ $magister->id }}" {{ old('magister_id', $course->magister_id ?? $selectedMagisterId ?? '') == $magister->id ? 'selected' : '' }}>
                    {{ $magister->nombre }}
                </option>
            @endforeach
        </x-hci-field>

        <x-hci-field 
            name="malla_curricular_id"
            type="select"
            label="Malla Curricular (Opcional)"
            icon=""
            help="Opcional: Asigna este curso a una versión específica de malla curricular"
            id="malla_curricular_id"
        >
            <option value="">-- Sin malla específica --</option>
            @foreach($mallas as $malla)
                <option value="{{ $malla->id }}" 
                        data-magister="{{ $malla->magister_id }}"
                        {{ old('malla_curricular_id', $course->malla_curricular_id ?? $selectedMallaId ?? '') == $malla->id ? 'selected' : '' }}
                        style="display: none;">
                    {{ $malla->nombre }} ({{ $malla->codigo }})
                </option>
            @endforeach
        </x-hci-field>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-hci-field 
                name="anio"
                type="select"
                label="Año Académico"
                :required="true"
                icon=""
                help="Selecciona el año del programa"
                id="anio"
            >
                <option value="">-- Selecciona un Año --</option>
                <option value="1" {{ old('anio', $course->period->anio ?? '') == '1' ? 'selected' : '' }}>Año 1</option>
                <option value="2" {{ old('anio', $course->period->anio ?? '') == '2' ? 'selected' : '' }}>Año 2</option>
            </x-hci-field>

            <x-hci-field 
                name="numero"
                type="select"
                label="Trimestre"
                :required="true"
                icon=""
                help="Selecciona el trimestre del año"
                id="numero"
            >
                <option value="">-- Selecciona un trimestre --</option>
            </x-hci-field>
        </div>

        {{-- Campo oculto para period_id --}}
        <input type="hidden" name="period_id" id="period_id" value="{{ old('period_id', $course->period_id ?? '') }}">
    </x-hci-form-section>


    {{-- Sección 3: Resumen y Confirmación --}}
    <x-hci-form-section 
        :step="3" 
        title="Resumen y Confirmación" 
        description="Revisa la información antes de guardar"
        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z' clip-rule='evenodd'/></svg>"
        section-id="resumen"
        content-class="w-full"
        :is-last="true"
        :editing="$editing"
    >
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 rounded-xl p-6 border border-blue-200 dark:border-gray-600">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Resumen del Curso</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Revisa la información antes de confirmar</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nombre del Curso - 2 columnas -->
                <div class="md:col-span-2 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-lg">📚</span>
                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Nombre del Curso</h4>
                    </div>
                    <p id="resumen-nombre" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">{{ old('nombre', $course->nombre ?? '') }}</p>
                </div>

                <!-- Programa -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-lg">🎓</span>
                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Programa</h4>
                    </div>
                    <p id="resumen-programa" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">{{ old('magister_id', $course->magister_id ?? '') }}</p>
                </div>

                <!-- Malla Curricular -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-lg">📋</span>
                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Malla Curricular</h4>
                    </div>
                    <p id="resumen-malla" class="text-sm font-bold text-[#005187] dark:text-[#84b6f4]">Sin malla específica</p>
                </div>

                <!-- Año -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-lg">📊</span>
                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Año</h4>
                    </div>
                    <p id="resumen-anio" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">{{ old('anio', $course->period->anio ?? '') }}</p>
                </div>

                <!-- Trimestre -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-lg">🔢</span>
                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Trimestre</h4>
                    </div>
                    <p id="resumen-trimestre" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">{{ old('trimestre', $course->period->numero ?? '') }}</p>
                </div>
            </div>
        </div>
    </x-hci-form-section>
</x-hci-wizard-layout>

{{-- FAB para ayuda (Ley de Fitts) --}}
<x-hci-button 
    fab="true" 
    icon="❓"
    href="#"
    aria-label="Ayuda con el formulario de cursos"
/>

{{-- Incluir JavaScript del wizard --}}
@push('scripts')
    @vite('resources/js/courses-form-wizard.js')
@endpush



