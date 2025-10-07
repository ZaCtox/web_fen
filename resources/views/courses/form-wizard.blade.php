{{-- Formulario de Courses con Principios HCI - Estructura Wizard --}}
@section('title', isset($course) ? 'Editar Curso' : 'Crear Curso')

@php
    $editing = isset($course);
@endphp

{{-- Breadcrumb (Ley de Jakob) --}}
<x-hci-breadcrumb 
    :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Cursos', 'url' => route('courses.index')],
        ['label' => $editing ? 'Editar Curso' : 'Nuevo Curso', 'url' => '#']
    ]"
/>

{{-- Contenedor principal con principios HCI --}}
<div class="hci-container">
    <div class="hci-section">
        <h1 class="hci-heading-1 flex items-center">
            @if($editing)
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                </svg>
                Editar Curso
            @else
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                </svg>
                Nuevo Curso
            @endif
        </h1>
        <p class="hci-text">
            {{ $editing ? 'Modifica la información del curso académico.' : 'Crea un nuevo curso con información organizada y estructurada.' }}
        </p>
    </div>

    {{-- Layout principal con progreso lateral --}}
    <div class="hci-wizard-layout">
        {{-- Barra de progreso lateral izquierda --}}
        <x-courses-progress-sidebar />

        {{-- Contenido principal del formulario --}}
        <div class="hci-form-content">
            <form class="hci-form" method="POST" action="{{ $editing ? route('courses.update', $course) : route('courses.store') }}">
                @csrf
                @if($editing) @method('PUT') @endif

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
                            style="width: 300px !important;"
                        >
                            <option value="">-- Selecciona un Programa --</option>
                            @foreach($magisters as $magister)
                                <option value="{{ $magister->id }}" {{ old('magister_id', $course->magister_id ?? $selectedMagisterId ?? '') == $magister->id ? 'selected' : '' }}>
                                    {{ $magister->nombre }}
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
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
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
            </form>
        </div>
    </div>
</div>

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
