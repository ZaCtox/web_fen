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
    :steps="$wizardSteps"
    :formAction="$editing ? route('incidencias.update', $incident->id) : route('incidencias.store')"
    :formMethod="$editing ? 'PUT' : 'POST'"
    :formDataAttributes="['enctype' => 'multipart/form-data']"
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
    >
                    <div class="space-y-8">
                        {{-- Campo de título --}}
                        <div class="w-full">
                            <x-hci-field 
                                name="titulo" 
                                type="text" 
                                label="Título de la Incidencia" 
                                :required="true"
                                icon=""
                                help="Título descriptivo del problema (máximo 100 caracteres)"
                                value="{{ old('titulo', $incident->titulo ?? '') }}"
                                maxlength="100"
                                style="width: 100% !important;"
                            />
                        </div>

                        {{-- Campo de descripción --}}
                        <div class="w-full">
                            <x-hci-field 
                                name="descripcion" 
                                type="textarea" 
                                label="Descripción del Problema" 
                                :required="true"
                                icon=""
                                help="Describe detalladamente el problema encontrado"
                                value="{{ old('descripcion', $incident->descripcion ?? '') }}"
                                rows="4"
                                style="width: 100% !important;"
                            />
                        </div>
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
    >
                    <div class="w-full">
                        <x-hci-field 
                            name="room_id" 
                            type="select" 
                            label="Sala Afectada" 
                            :required="true"
                            icon=""
                            help="Selecciona la sala donde ocurrió el problema"
                            style="width: 100% !important;"
                        >
                            <option value="">-- Selecciona una Sala --</option>
                            @foreach($salas as $sala)
                                <option value="{{ $sala->id }}" 
                                    {{ old('room_id', $incident->room_id ?? '') == $sala->id ? 'selected' : '' }}
                                    style="white-space: normal; word-wrap: break-word; max-width: 100%;">
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
    >
                    <div class="space-y-8">
                        {{-- Campo de imagen --}}
                        <div class="w-full">
                            <x-hci-field 
                                name="imagen" 
                                type="file" 
                                label="Foto del Problema" 
                                :required="false"
                                icon=""
                                help="Adjunta una imagen que muestre el problema (opcional)"
                                accept="image/*"
                            />
                        </div>

                        {{-- Campo de ticket Jira --}}
                        <div class="w-full">
                            <x-hci-field 
                                name="nro_ticket" 
                                type="text" 
                                label="N° Ticket Jira" 
                                :required="false"
                                icon=""
                                help="Número del ticket en Jira (opcional)"
                                value="{{ old('nro_ticket', $incident->nro_ticket ?? '') }}"
                                placeholder="Ej: 2364552"
                                style="width: 100% !important;"
                            />
                        </div>
                    </div>
                </x-hci-form-section>

    {{-- Sección 4: Resumen --}}
    <x-hci-form-section 
        :step="4" 
        title="Resumen" 
        description="Revisa la información antes de enviar"
        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>"
        section-id="resumen"
        :is-last="true"
        :editing="$editing"
    >
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Título</h4>
                            <p id="summary-titulo" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">--</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Sala Afectada</h4>
                            <p id="summary-sala" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">--</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg md:col-span-2">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Descripción</h4>
                            <p id="summary-descripcion" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">--</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Imagen</h4>
                            <p id="summary-imagen" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">--</p>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Ticket Jira</h4>
                            <p id="summary-ticket" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">--</p>
                        </div>
                    </div>
    </x-hci-form-section>
</x-hci-wizard-layout>

@push('scripts')
    @vite('resources/js/incidencias-form-wizard.js')
@endpush



