{{-- Formulario de Períodos con Wizard Genérico --}}
@section('title', isset($period) ? 'Editar Período' : 'Crear Período')

@php
    $editing = isset($period);
    
    // Definir los pasos del wizard
    $wizardSteps = [
        ['title' => 'Información Básica', 'description' => 'Año y trimestre'],
        ['title' => 'Fechas del Período', 'description' => 'Inicio y término'],
        ['title' => 'Resumen', 'description' => 'Revisar información']
    ];
@endphp

<x-hci-wizard-layout
    title="Período"
    :editing="$editing"
    createDescription="Registra un nuevo período académico con información organizada."
    editDescription="Modifica la información del período académico."
    :steps="$wizardSteps"
    :formAction="$editing ? route('periods.update', $period) : route('periods.store')"
    :formMethod="$editing ? 'PUT' : 'POST'"
>

    {{-- Sección 1: Información Básica --}}
    <x-hci-form-section 
        :step="1" 
        title="Información Básica" 
        description="Define el año académico y trimestre del período"
        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>"
        section-id="informacion"
        content-class="grid-cols-1 md:grid-cols-3 gap-6"
        :is-active="true"
        :is-first="true"
        :editing="$editing"
    >
        <x-hci-field 
            name="anio" 
            type="select" 
            label="Año Académico" 
            :required="true"
            icon=""
            help="Selecciona el año del programa"
            id="anio-select"
        >
            <option value="">-- Selecciona un Año --</option>
            <option value="1" {{ old('anio', $period->anio ?? '') == '1' ? 'selected' : '' }}>Año 1</option>
            <option value="2" {{ old('anio', $period->anio ?? '') == '2' ? 'selected' : '' }}>Año 2</option>
        </x-hci-field>

        <x-hci-field 
            name="cohorte" 
            type="select" 
            label="Cohorte Académica" 
            :required="true"
            icon=""
            help="Selecciona la cohorte académica"
            id="ciclo-select"
        >
            <option value="">-- Selecciona una cohorte --</option>
            <option value="2025-2026" {{ old('cohorte', $period->cohorte ?? '') == '2025-2026' ? 'selected' : '' }}>2025-2026 (Actual)</option>
            <option value="2024-2025" {{ old('cohorte', $period->cohorte ?? '') == '2024-2025' ? 'selected' : '' }}>2024-2025 (Pasada)</option>
            <option value="2026-2027" {{ old('cohorte', $period->cohorte ?? '') == '2026-2027' ? 'selected' : '' }}>2026-2027 (Futura)</option>
        </x-hci-field>

        <x-hci-field 
            name="numero" 
            type="select" 
            label="Trimestre" 
            :required="true"
            icon=""
            help="Selecciona el trimestre del año"
            id="numero-select"
            value="{{ old('numero', $period->numero ?? '') }}"
        >
            <option value="">-- Selecciona un trimestre --</option>
            @php
                $anioSeleccionado = old('anio', $period->anio ?? '');
                $trimestres = $anioSeleccionado == '1' ? [1, 2, 3] : ($anioSeleccionado == '2' ? [4, 5, 6] : []);
            @endphp
            @foreach($trimestres as $trimestre)
                <option value="{{ $trimestre }}" {{ old('numero', $period->numero ?? '') == $trimestre ? 'selected' : '' }}>
                    Trimestre {{ $trimestre }}
                </option>
            @endforeach
        </x-hci-field>
    </x-hci-form-section>

    {{-- Sección 2: Fechas del Período --}}
    <x-hci-form-section 
        :step="2" 
        title="Fechas del Período" 
        description="Establece las fechas de inicio y término del período académico"
        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zM4 7h12v9a1 1 0 01-1 1H5a1 1 0 01-1-1V7z'/></svg>"
        section-id="fechas"
        content-class="grid-cols-1 md:grid-cols-2 gap-6"
        :editing="$editing"
    >
        <x-hci-field 
            name="fecha_inicio" 
            type="date" 
            label="Fecha de Inicio" 
            :required="true"
            icon=""
            help="Fecha de inicio del período académico"
            value="{{ old('fecha_inicio', $period?->fecha_inicio?->format('Y-m-d') ?? '') }}"
        />

        <x-hci-field 
            name="fecha_fin" 
            type="date" 
            label="Fecha de Término" 
            :required="true"
            icon=""
            help="Fecha de término del período académico"
            value="{{ old('fecha_fin', $period?->fecha_fin?->format('Y-m-d') ?? '') }}"
        />
    </x-hci-form-section>

    {{-- Sección 3: Resumen --}}
    <x-hci-form-section 
        :step="3" 
        title="Resumen" 
        description="Revisa la información antes de guardar"
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
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Resumen del Período Académico</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Revisa la información antes de confirmar</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-lg">📚</span>
                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Año Académico</h4>
                    </div>
                    <p id="summary-anio" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">--</p>
                </div>
                
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-lg">🔢</span>
                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Trimestre</h4>
                    </div>
                    <p id="summary-trimestre" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">--</p>
                </div>
                
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-lg">📅</span>
                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Fecha de Inicio</h4>
                    </div>
                    <p id="summary-fecha-inicio" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">--</p>
                </div>
                
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-lg">🏁</span>
                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Fecha de Término</h4>
                    </div>
                    <p id="summary-fecha-fin" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">--</p>
                </div>
            </div>
        </div>
    </x-hci-form-section>
</x-hci-wizard-layout>

@push('scripts')
    @vite('resources/js/periods-form-wizard.js')
@endpush






