{{-- Formulario de Emergencias con Principios HCI --}}
@section('title', isset($emergency) ? 'Editar Emergencia' : 'Crear Emergencia')

@php
    $editing = isset($emergency);
@endphp

{{-- Contenedor principal con principios HCI --}}
<div class="hci-container">
    <div class="hci-section">
        <h1 class="hci-heading-1 flex items-center">
            @if($editing)
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                </svg>
                Editar Emergencia
            @else
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                Nueva Emergencia
            @endif
        </h1>
        <p class="hci-text">
            {{ $editing ? 'Modifica la información de la emergencia.' : 'Crea una nueva alerta de emergencia para notificar a la comunidad.' }}
        </p>
    </div>

    {{-- Layout principal con progreso lateral --}}
    <div class="hci-wizard-layout">
        {{-- Barra de progreso lateral izquierda --}}
        <x-emergency-progress-sidebar />

        {{-- Contenido principal del formulario --}}
        <div class="hci-form-content">
            <form class="hci-form" method="POST" action="{{ $editing ? route('emergencies.update', $emergency) : route('emergencies.store') }}">
                @csrf
                @if($editing) @method('PUT') @endif

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
                >
                     <x-hci-field 
                         name="title"
                         label="Título de la Emergencia"
                         placeholder="Ej: Mantenimiento programado del sistema"
                         value="{{ old('title', $emergency->title ?? '') }}"
                         :required="true"
                         icon=""
                         help=""
                         maxlength="100"
                         style="width: 450px !important;"
                     />
                </x-hci-form-section>

                {{-- Sección 2: Detalles del Mensaje --}}
                <x-hci-form-section 
                    :step="2" 
                    title="Detalles del Mensaje" 
                    description="Información detallada para la comunidad"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z' clip-rule='evenodd'/></svg>"
                    section-id="mensaje"
                    :editing="$editing"
                >
                    <div class="hci-field">
                        <label for="message" class="hci-label">
                            Mensaje de Emergencia
                            <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            id="message" 
                            name="message" 
                            required 
                            class="hci-textarea w-full" 
                            placeholder="Describe detalladamente la situación de emergencia, incluyendo instrucciones específicas para la comunidad..."
                            rows="6"
                        >{{ old('message', $emergency->message ?? '') }}</textarea>
                        <p class="hci-help-text">Proporciona información clara y detallada sobre la emergencia y las acciones que debe tomar la comunidad.</p>
                        @error('message')
                            <p class="hci-field-error">{{ $message }}</p>
                        @enderror
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
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Título - 1 columna -->
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Título</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="resumen-titulo">{{ old('title', $emergency->title ?? '') }}</p>
                            </div>

                            <!-- Mensaje - 1 columna -->
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Mensaje</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg whitespace-pre-wrap" id="resumen-mensaje">{{ old('message', $emergency->message ?? '') }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-6 p-4 bg-[#fcffff] dark:bg-gray-800 rounded-lg border border-[#84b6f4]/20">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <strong>Nota:</strong> Revisa que toda la información sea correcta antes de proceder. 
                                {{ $editing ? 'Los cambios se aplicarán inmediatamente.' : 'Se creará una nueva alerta de emergencia que será visible para toda la comunidad.' }}
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
    aria-label="Ayuda con el formulario de emergencias"
/>

{{-- Incluir JavaScript del wizard --}}
@push('scripts')
    @vite('resources/js/emergency-form-wizard.js')
@endpush



