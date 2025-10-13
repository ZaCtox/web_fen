{{-- Formulario de Emergencias con Principios HCI --}}
@section('title', isset($emergency) ? 'Editar Emergencia' : 'Crear Emergencia')

@php
    $editing = isset($emergency);
    $wizardSteps = [
        ['title' => 'Información Básica', 'description' => 'Título de la emergencia'],
        ['title' => 'Detalles del Mensaje', 'description' => 'Descripción detallada'],
        ['title' => 'Resumen', 'description' => 'Revisar y confirmar']
    ];
@endphp

{{-- Layout genérico del wizard --}}
<x-hci-wizard-layout 
    title="Emergencia"
    :editing="$editing"
    createDescription="Crea una nueva alerta de emergencia para notificar a la comunidad."
    editDescription="Modifica la información de la emergencia."
    sidebarComponent="emergency-progress-sidebar"
    :formAction="$editing ? route('emergencies.update', $emergency) : route('emergencies.store')"
    :formMethod="$editing ? 'PUT' : 'POST'"
>

    {{-- Sección 1: Información Básica --}}
    <x-hci-form-section 
        :step="1" 
        title="Información Básica" 
        description="Datos principales de la emergencia"
        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z' clip-rule='evenodd'/></svg>"
        section-id="basica"
        :is-active="true"
        :is-first="true"
        :editing="$editing"
        contentClass="grid-cols-1 gap-6"
    >
        <div class="w-full">
            <x-hci-field 
                name="title"
                label="Título de la Emergencia"
                placeholder="Ej: Mantenimiento programado del sistema"
                value="{{ old('title', $emergency->title ?? '') }}"
                :required="true"
                help="Título breve y descriptivo de la emergencia"
                maxlength="100"
            />
        </div>
    </x-hci-form-section>

    {{-- Sección 2: Detalles del Mensaje --}}
    <x-hci-form-section 
        :step="2" 
        title="Detalles del Mensaje" 
        description="Información detallada para la comunidad"
        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z' clip-rule='evenodd'/></svg>"
        section-id="mensaje"
        :editing="$editing"
        contentClass="grid-cols-1 gap-6"
    >
        <div class="w-full">
            <x-hci-field 
                name="message"
                type="textarea"
                label="Mensaje de Emergencia"
                placeholder="Describe detalladamente la situación de emergencia, incluyendo instrucciones específicas para la comunidad..."
                value="{{ old('message', $emergency->message ?? '') }}"
                :required="true"
                help="Proporciona información clara y detallada sobre la emergencia y las acciones que debe tomar la comunidad."
                rows="8"
            />
        </div>
    </x-hci-form-section>

    {{-- Sección 3: Resumen y Confirmación --}}
    <x-hci-form-section 
        :step="3" 
        title="Resumen y Confirmación" 
        description="Revisa la información antes de publicar"
        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z' clip-rule='evenodd'/></svg>"
        section-id="resumen"
        :is-last="true"
        :editing="$editing"
    >
        <div class="bg-[#c4dafa]/30 dark:bg-[#84b6f4]/10 rounded-lg p-6 border border-[#84b6f4]/30 w-full">
            <div class="space-y-6">
                {{-- Título --}}
                <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-[#4d82bc]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#4d82bc]" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-1">Título de la Emergencia</span>
                            <p class="text-gray-900 dark:text-white font-semibold text-lg" id="resumen-titulo">{{ old('title', $emergency->title ?? 'Sin título') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Mensaje --}}
                <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-[#4d82bc]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#4d82bc]" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-1">Mensaje Detallado</span>
                            <p class="text-gray-900 dark:text-white whitespace-pre-wrap leading-relaxed" id="resumen-mensaje">{{ old('message', $emergency->message ?? 'Sin mensaje') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Nota de Advertencia --}}
                <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 border border-yellow-200 dark:border-yellow-800">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-yellow-800 dark:text-yellow-300 mb-1">
                                <strong>⚠️ Importante:</strong>
                            </p>
                            <p class="text-sm text-yellow-700 dark:text-yellow-400">
                                {{ $editing ? 'Los cambios se aplicarán inmediatamente y serán visibles para toda la comunidad.' : 'Se creará una nueva alerta de emergencia que será visible para toda la comunidad de forma inmediata.' }}
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
    @vite('resources/js/emergency-form-wizard.js')
@endpush



