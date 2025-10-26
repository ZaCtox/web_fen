{{-- Formulario de Magisters con Principios HCI - Estructura Wizard --}}
@section('title', isset($magister) ? 'Editar Programa' : 'Crear Programa')

@php
    $editing = isset($magister);
@endphp

{{-- Breadcrumb (Ley de Jakob) --}}
<x-hci-breadcrumb 
    :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Módulos', 'url' => route('courses.index')],
        ['label' => 'Programas', 'url' => route('magisters.index')],
        ['label' => $editing ? 'Editar Programa' : 'Nuevo Programa', 'url' => '#']
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
                Editar Programa
            @else
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                </svg>
                Nuevo Programa
            @endif
        </h1>
        <p class="hci-text">
            {{ $editing ? 'Modifica la información del programa académico.' : 'Crea un nuevo programa con información organizada y estructurada.' }}
        </p>
    </div>

    {{-- Layout principal con progreso lateral --}}
    <div class="hci-wizard-layout">
        {{-- Barra de progreso lateral izquierda --}}
        <x-magisters-progress-sidebar />

        {{-- Contenido principal del formulario --}}
        <div class="hci-form-content">
            <form class="hci-form" method="POST" action="{{ $editing ? route('magisters.update', $magister) : route('magisters.store') }}">
                @csrf
                @if($editing) @method('PUT') @endif

                {{-- Sección 1: Información Básica --}}
                <x-hci-form-section 
                    :step="1" 
                    title="Información Básica" 
                    description="Datos principales del programa"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z' clip-rule='evenodd'/></svg>"
                    section-id="basica"
                    :is-active="true"
                    :is-first="true"
                    :editing="$editing"
                >
                    <div class="space-y-6">
                        <x-hci-field 
                            name="nombre"
                            label="Nombre del Programa"
                            placeholder="Ej: Economía"
                            value="{{ old('nombre', $magister->nombre ?? '') }}"
                            :required="true"
                            help="Nombre descriptivo del programa académico"
                            maxlength="150"
                            style="width: 500px !important;"
                        />

                        <div class="flex flex-col md:flex-row md:items-start md:gap-8">
                            <div class="w-full md:w-auto">
                                <x-hci-field 
                                    name="color"
                                    type="color"
                                    label="Color del Programa"
                                    value="{{ old('color', $magister->color ?? '#3b82f6') }}"
                                    help="Selecciona un color representativo"
                                    style="width: 200px !important;"
                                />
                            </div>
                        </div>
                    </div>
                </x-hci-form-section>

                {{-- Sección 2: Personal Académico --}}
                <x-hci-form-section 
                    :step="2" 
                    title="Personal Académico" 
                    description="Información del encargado y asistente del programa"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z'/></svg>"
                    section-id="personal"
                    :editing="$editing"
                >
<div class="space-y-8">
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between lg:gap-32">
        {{-- Encargado del Programa --}}
        <div class="w-full lg:w-[45%]">
            <x-hci-field 
                name="encargado"
                label="Encargado del Programa"
                placeholder="Nombre del encargado"
                value="{{ old('encargado', $magister->encargado ?? '') }}"
                help="Persona responsable del programa"
                style="width: 200px !important;"
            />
        </div>

        {{-- Asistente del Programa --}}
        <div class="w-full lg:w-[45%]">
            <x-hci-field 
                name="asistente"
                label="Asistente del Programa"
                placeholder="Nombre del asistente"
                value="{{ old('asistente', $magister->asistente ?? '') }}"
                help="Persona de apoyo administrativo"
                style="width: 250px !important;"
            />
        </div>
    </div>
</div>

                </x-hci-form-section>


                {{-- Sección 3: Información de Contacto de la Asistente --}}
                <x-hci-form-section 
                    :step="3" 
                    title="Información de Contacto de la Asistente" 
                    description="Datos de contacto de la asistente del programa"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z'/><path d='M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z'/></svg>"
                    section-id="contacto"
                    :editing="$editing"
                >
                    <div class="space-y-8">
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between lg:gap-32">
        {{-- Teléfono de la Asistente --}}
        <div class="w-full lg:w-[45%]">
            <x-hci-field 
                name="telefono"
                type="tel"
                label="Teléfono Asistente"
                placeholder="Ej: 712000000 (fijo) o 912345678 (celular)"
                value="{{ old('telefono', $magister->telefono ?? '') }}"
                :required="true"
                help="Teléfono fijo (712XXXXXX) o celular (9XXXXXXXX)"
                maxlength="9"
                pattern="^(712\d{6}|9\d{8})$"
                style="width: 200px !important;"
            />
        </div>

        {{-- Anexo de la Asistente --}}
        <div class="w-full lg:w-[45%] mt-5">
            <x-hci-field 
                name="anexo"
                label="Anexo"
                placeholder="Número de anexo"
                value="{{ old('anexo', $magister->anexo ?? '') }}"
                help="Número de anexo interno"
                style="width: 100px !important;"
            />
        </div>
    </div>

    {{-- Correo Electrónico de la Asistente --}}
    <x-hci-field 
        name="correo"
        type="email"
        label="Correo Asistente"
        placeholder="Ej: asistente@utalca.cl"
        value="{{ old('correo', $magister->correo ?? '') }}"
        help="Correo electrónico de contacto"
        style="width: 300px !important;"
    />
</div>

                </x-hci-form-section>

                {{-- Sección 4: Resumen y Confirmación --}}
                <x-hci-form-section 
                    :step="4" 
                    title="Resumen y Confirmación" 
                    description="Revisa la información antes de guardar"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z' clip-rule='evenodd'/></svg>"
                    section-id="resumen"
                    :is-last="true"
                    :editing="$editing"
                >
                    <div class="bg-[#c4dafa]/30 dark:bg-[#84b6f4]/10 rounded-lg p-6 border border-[#84b6f4]/30 w-full">                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- Nombre del Programa - 2 columnas -->
                            <div class="md:col-span-2 bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Nombre del Programa</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="resumen-nombre">{{ old('nombre', $magister->nombre ?? '') }}</p>
                            </div>

                            <!-- Color - 1 columna -->
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Color</span>
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 rounded border border-gray-300" id="resumen-color" style="background-color: {{ old('color', $magister->color ?? '#3b82f6') }}"></div>
                                    <span class="text-gray-900 dark:text-white font-medium" id="resumen-color-text">{{ old('color', $magister->color ?? '#3b82f6') }}</span>
                                </div>
                            </div>

                            <!-- Encargado - 1 columna -->
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Encargado</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="resumen-encargado">{{ old('encargado', $magister->encargado ?? '') }}</p>
                            </div>

                            <!-- Asistente - 1 columna -->
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Asistente</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="resumen-asistente">{{ old('asistente', $magister->asistente ?? '') }}</p>
                            </div>

                            <!-- Teléfono - 1 columna -->
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Teléfono Asistente</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="resumen-telefono">{{ old('telefono', $magister->telefono ?? '') }}</p>
                            </div>

                            <!-- Anexo - 1 columna -->
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Anexo Asistente</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="resumen-anexo">{{ old('anexo', $magister->anexo ?? '') }}</p>
                            </div>

                            <!-- Correo - 2 columnas -->
                            <div class="md:col-span-2 bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Correo Asistente</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg break-words" id="resumen-correo">{{ old('correo', $magister->correo ?? '') }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-6 p-4 bg-[#fcffff] dark:bg-gray-800 rounded-lg border border-[#84b6f4]/20">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <strong>Nota:</strong> Revisa que toda la información sea correcta antes de proceder. 
                                {{ $editing ? 'Los cambios se aplicarán inmediatamente.' : 'Se creará un nuevo programa académico.' }}
                            </p>
                        </div>
                    </div>
                </x-hci-form-section>
            </form>
        </div>
    </div>
</div>

{{-- Incluir JavaScript del wizard --}}
@push('scripts')
    @vite('resources/js/magisters-form-wizard.js')
@endpush
