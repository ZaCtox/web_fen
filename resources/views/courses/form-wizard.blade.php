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
            style="width: 500px !important;"
        />
    </x-hci-form-section>

    {{-- Sección 2: Programa y Período --}}
    <x-hci-form-section 
        :step="2" 
        title="Programa y Período" 
        description="Asignación del programa y período académico (Año 1: Trimestres I, II, III | Año 2: Trimestres IV, V, VI)"
        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>"
        section-id="programa"
        :editing="$editing"
    >
        <div class="space-y-8">
                        <x-hci-field 
                            name="magister_id"
                            type="select"
                            label="Programa Académico"
                            :required="true"
                            icon=""
                            help="Selecciona el programa al que pertenece el curso"
                            id="magister_id"
                            style="width: 300px !important;"
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
                            label="Malla Curricular"
                            icon=""
                            help="Opcional: Asigna este curso a una versión específica de malla curricular"
                            id="malla_curricular_id"
                            style="width: 400px !important;"
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

                        <div class="flex flex-col md:flex-row md:items-start md:gap-16">
    <div class="w-full md:w-auto">
        <x-hci-field 
            name="anio"
            type="select"
            label="Año Académico"
            :required="true"
            icon=""
            help="Selecciona el año del programa"
            id="anio"
            style="width: 200px !important;"
        >
            <option value="">-- Selecciona un Año --</option>
            <option value="1" {{ old('anio', $course->period->anio ?? '') == '1' ? 'selected' : '' }}>Año 1</option>
            <option value="2" {{ old('anio', $course->period->anio ?? '') == '2' ? 'selected' : '' }}>Año 2</option>
        </x-hci-field>
    </div>

    <div class="w-full md:flex-grow">
        <x-hci-field 
            name="numero"
            type="select"
            label="Trimestre"
            :required="true"
            icon=""
            help="Selecciona el trimestre del año"
            id="numero"
            style="min-width: 300px !important;"
        >
            <option value="">-- Selecciona un trimestre --</option>
        </x-hci-field>
    </div>
</div>

            </div>

            {{-- Campo oculto para period_id --}}
            <input type="hidden" name="period_id" id="period_id" value="{{ old('period_id', $course->period_id ?? '') }}">
        </div>
    </x-hci-form-section>


    {{-- Sección 3: Resumen y Confirmación --}}
    <x-hci-form-section 
        :step="3" 
        title="Resumen y Confirmación" 
        description="Revisa la información antes de guardar"
        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z' clip-rule='evenodd'/></svg>"
        section-id="resumen"
        :is-last="true"
        :editing="$editing"
    >
        <div class="bg-[#c4dafa]/30 dark:bg-[#84b6f4]/10 rounded-lg p-6 border border-[#84b6f4]/30 w-full">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Nombre del Curso - 2 columnas -->
                            <div class="md:col-span-2 bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Nombre del Curso</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="resumen-nombre">{{ old('nombre', $course->nombre ?? '') }}</p>
                            </div>

                            <!-- Programa - 1 columna -->
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Programa</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="resumen-programa">{{ old('magister_id', $course->magister_id ?? '') }}</p>
                            </div>

                            <!-- Malla Curricular - 1 columna -->
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Malla Curricular</span>
                                <p class="text-gray-900 dark:text-white font-medium text-sm" id="resumen-malla">Sin malla específica</p>
                            </div>

                            <!-- Año - 1 columna -->
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Año</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="resumen-anio">{{ old('anio', $course->period->anio ?? '') }}</p>
                            </div>

                            <!-- Trimestre - 1 columna -->
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Trimestre</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="resumen-trimestre">{{ old('trimestre', $course->period->numero ?? '') }}</p>
                            </div>
            </div>
            
            <div class="mt-6 p-4 bg-[#fcffff] dark:bg-gray-800 rounded-lg border border-[#84b6f4]/20">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    <strong>Nota:</strong> Revisa que toda la información sea correcta antes de proceder. 
                    {{ $editing ? 'Los cambios se aplicarán inmediatamente.' : 'Se creará un nuevo curso académico.' }}
                </p>
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



