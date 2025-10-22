{{-- Formulario de Clases con Wizard de Sesiones --}}
@section('title', isset($clase) ? 'Editar Clase' : 'Crear Clase')

@php
    $editing = isset($clase);
    
    // Definir los pasos del wizard
    $wizardSteps = $editing 
        ? [
            ['title' => 'Información General', 'description' => 'Datos básicos de la clase'],
            ['title' => 'Resumen', 'description' => 'Revisar y confirmar']
          ]
        : [
            ['title' => 'Información General', 'description' => 'Programa, asignatura y encargado'],
            ['title' => 'Configuración de Sesiones', 'description' => 'Define cuántas sesiones y cuándo'],
            ['title' => 'Detalles por Sesión', 'description' => 'Configura cada sesión individual'],
            ['title' => 'Resumen', 'description' => 'Revisar y confirmar']
          ];
@endphp

<x-hci-wizard-layout
    title="Clase"
    :editing="$editing"
    createDescription="Configura una nueva clase académica con sus sesiones."
    editDescription="Modifica la información general de la clase."
    sidebarComponent="clases-progress-sidebar"
    :formAction="$editing ? route('clases.update', $clase) : route('clases.store')"
    :formMethod="$editing ? 'PUT' : 'POST'"
    formId="form-clase"
>
    {{-- Paso 1: Información General --}}
    <x-hci-form-section 
        :step="1"
        title="Información General"
        description="Selecciona el programa, asignatura y encargado"
        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z' clip-rule='evenodd'/></svg>"
        section-id="general"
        content-class="grid-cols-1 gap-6"
        :is-active="true"
        :is-first="true"
        :editing="$editing"
    >
        @php $selectedMagister = isset($clase) ? optional($clase->course->magister)->nombre : old('magister'); @endphp
        <x-hci-field name="magister" type="select" label="Programa" :required="true" id="magister" icon="" help="Primero selecciona el programa" data-agrupados='@json($agrupados ?? [])'>
            <option value="">-- Selecciona un Programa --</option>
            @foreach(($agrupados ?? []) as $magNombre => $cursos)
                <option value="{{ $magNombre }}" {{ ($selectedMagister == $magNombre) ? 'selected' : '' }}>{{ $magNombre }}</option>
            @endforeach
        </x-hci-field>

        <x-hci-field name="course_id" type="select" label="Asignatura" :required="true" id="course_id" icon="" help="Luego elige la asignatura"> 
            <option value="">-- Selecciona una Asignatura --</option>
            @php
                $selectedMagister = isset($clase) ? optional($clase->course->magister)->nombre : null;
            @endphp
            @if(isset($agrupados) && $selectedMagister && isset($agrupados[$selectedMagister]))
                @foreach($agrupados[$selectedMagister] as $c)
                    <option value="{{ $c['id'] }}" data-period_id="{{ $c['period_id'] }}" data-periodo="{{ $c['periodo'] }}" data-period_numero="{{ $c['numero'] }}" data-period_anio="{{ $c['anio'] }}" {{ old('course_id', $clase->course_id ?? '') == $c['id'] ? 'selected' : '' }}>{{ $c['nombre'] }}</option>
                @endforeach
            @endif
        </x-hci-field>

        <input type="hidden" name="period_id" id="period_id" value="{{ old('period_id', $clase->period_id ?? '') }}">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-hci-field name="anio" label="Año" id="anio" icon="" disabled="true" help="Se completa automáticamente" />
            <x-hci-field name="trimestre" label="Trimestre" id="trimestre" icon="" disabled="true" help="Se completa automáticamente" />
        </div>

        <x-hci-field name="encargado" label="Encargado (Profesor)" :required="true" id="encargado" icon="" placeholder="Ej: Margarita Pereira" value="{{ old('encargado', $clase->encargado ?? '') }}" help="Profesor responsable de la clase" />

        <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 rounded">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-sm text-blue-800 dark:text-blue-200 font-medium">
                        Las salas y enlaces de Zoom se configurarán individualmente para cada sesión
                    </p>
                    <p class="text-xs text-blue-600 dark:text-blue-300 mt-1">
                        En el paso de configuración de sesiones podrás asignar sala y Zoom específicos
                    </p>
                </div>
            </div>
        </div>
    </x-hci-form-section>

    @if(!$editing)
    {{-- Paso 2: Configuración de Sesiones (solo en creación) --}}
    <x-hci-form-section 
        :step="2"
        title="Configuración de Sesiones"
        description="Define cuántas sesiones tendrá la clase"
        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z'/></svg>"
        section-id="config-sesiones"
        content-class="grid-cols-1 gap-6"
        :editing="false"
    >
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-hci-field name="num_sesiones" type="number" label="Número de Sesiones" :required="true" id="num_sesiones" icon="" min="1" max="50" value="{{ old('num_sesiones', 8) }}" help="¿Cuántas sesiones tendrá esta clase?" />
            
            <x-hci-field name="fecha_inicio" type="date" label="Fecha de Inicio" :required="true" id="fecha_inicio" icon="" value="{{ old('fecha_inicio', date('Y-m-d')) }}" help="Fecha de la primera sesión" />
        </div>

        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                Días de la Semana <span class="text-red-500">*</span>
            </label>
            <div class="flex gap-4">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="dias_semana[]" value="Viernes" id="dia_viernes" class="hci-checkbox" {{ (is_array(old('dias_semana')) && in_array('Viernes', old('dias_semana'))) ? 'checked' : '' }}>
                    <span class="text-gray-700 dark:text-gray-300">Viernes</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="dias_semana[]" value="Sábado" id="dia_sabado" class="hci-checkbox" {{ (is_array(old('dias_semana')) && in_array('Sábado', old('dias_semana'))) ? 'checked' : '' }}>
                    <span class="text-gray-700 dark:text-gray-300">Sábado</span>
                </label>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Selecciona los días en que se realizarán las clases</p>
        </div>

        <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 rounded">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-sm text-blue-800 dark:text-blue-200 font-medium">
                        En el siguiente paso podrás configurar los detalles de cada sesión individualmente
                    </p>
                    <p class="text-xs text-blue-600 dark:text-blue-300 mt-1">
                        Fecha, horario, modalidad, sala y Zoom específicos para cada sesión
                    </p>
                </div>
            </div>
        </div>
    </x-hci-form-section>

    {{-- Paso 3: Detalles por Sesión (solo en creación) --}}
    <x-hci-form-section 
        :step="3"
        title="Detalles por Sesión"
        description="Configura la fecha, horario, modalidad y ubicación de cada sesión"
        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M9 2a1 1 0 000 2h2a1 1 0 100-2H9z'/><path fill-rule='evenodd' d='M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z' clip-rule='evenodd'/></svg>"
        section-id="detalles-sesiones"
        :editing="false"
        content-class="w-full"
    >
        <div id="sesiones-container" class="w-full space-y-6">
            {{-- Las sesiones se generarán dinámicamente aquí --}}
        </div>
    </x-hci-form-section>
    @endif

    {{-- Paso 4 (o 2 si es edición): Resumen --}}
    <x-hci-form-section 
        :step="$editing ? 2 : 4"
        title="Resumen"
        description="Revisa toda la información antes de guardar"
        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z' clip-rule='evenodd'/></svg>"
        section-id="resumen"
        content-class="w-full"
        :editing="$editing"
        :is-last="true"
    >
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-700 rounded-xl p-6 border border-blue-200 dark:border-gray-600">
            {{-- Información General --}}
            <div class="mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Información General</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-lg">🎓</span>
                            <h4 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Programa</h4>
                        </div>
                        <p class="text-sm font-bold text-[#005187] dark:text-[#84b6f4]" id="resumen-programa">—</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-lg">📚</span>
                            <h4 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Asignatura</h4>
                        </div>
                        <p class="text-sm font-bold text-[#005187] dark:text-[#84b6f4]" id="resumen-curso">—</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-lg">📅</span>
                            <h4 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Período</h4>
                        </div>
                        <p class="text-sm font-bold text-[#005187] dark:text-[#84b6f4]" id="resumen-periodo">—</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-lg">👨‍🏫</span>
                            <h4 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Encargado</h4>
                        </div>
                        <p class="text-sm font-bold text-[#005187] dark:text-[#84b6f4]" id="resumen-encargado">—</p>
                    </div>
                </div>
            </div>

            @if(!$editing)
            {{-- Resumen de Sesiones --}}
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Sesiones Configuradas (<span id="resumen-total-sesiones">0</span>)
                    </h3>
                </div>
                <div id="resumen-sesiones-lista" class="space-y-3">
                    {{-- Se llenará dinámicamente --}}
                </div>
            </div>
            @endif
        </div>
    </x-hci-form-section>
</x-hci-wizard-layout>

{{-- Scripts específicos del formulario --}}
@push('scripts')
    @vite(['resources/js/clases/form.js', 'resources/js/clases-form-wizard.js'])
@endpush
