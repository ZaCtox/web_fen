{{-- Formulario de Mallas Curriculares con Principios HCI - Estructura Wizard --}}
@section('title', isset($mallaCurricular) ? 'Editar Malla Curricular' : 'Crear Malla Curricular')

@php
    $editing = isset($mallaCurricular);
@endphp

{{-- Contenedor principal con principios HCI --}}
<div class="hci-container">
    <div class="hci-section">
        <h1 class="hci-heading-1 flex items-center">
            @if($editing)
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                </svg>
                Editar Malla Curricular
            @else
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                </svg>
                Nueva Malla Curricular
            @endif
        </h1>
        <p class="hci-text">
            {{ $editing ? 'Modifica la información de la malla curricular.' : 'Crea una nueva versión de malla curricular con información organizada.' }}
        </p>
    </div>

    {{-- Layout principal con progreso lateral --}}
    <div class="hci-wizard-layout">
        {{-- Barra de progreso lateral izquierda --}}
        <x-mallas-curriculares-progress-sidebar />

        {{-- Contenido principal del formulario --}}
        <div class="hci-form-content">
            <form class="hci-form" method="POST" action="{{ $editing ? route('mallas-curriculares.update', $mallaCurricular) : route('mallas-curriculares.store') }}">
                @csrf
                @if($editing) @method('PUT') @endif

                {{-- Sección 1: Información Básica --}}
                <x-hci-form-section 
                    :step="1" 
                    title="Información Básica" 
                    description="Datos principales de la malla curricular"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z' clip-rule='evenodd'/></svg>"
                    section-id="basica"
                    :is-active="true"
                    :is-first="true"
                    :editing="$editing"
                >
                    <div class="space-y-8">
                        <x-hci-field 
                            name="magister_id"
                            type="select"
                            label="Programa de Magíster"
                            :required="true"
                            icon=""
                            help="Selecciona el programa al que pertenece esta malla"
                            style="width: 400px !important;"
                        >
                            <option value="">-- Selecciona un Programa --</option>
                            @foreach($magisters as $magister)
                                <option value="{{ $magister->id }}" {{ old('magister_id', $mallaCurricular->magister_id ?? '') == $magister->id ? 'selected' : '' }}>
                                    {{ $magister->nombre }}
                                </option>
                            @endforeach
                        </x-hci-field>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
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
                        </div>
                    </div>
                </x-hci-form-section>

                {{-- Sección 2: Periodo de Vigencia --}}
                <x-hci-form-section 
                    :step="2" 
                    title="Periodo de Vigencia" 
                    description="Define el periodo en que esta malla curricular estará vigente"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z' clip-rule='evenodd'/></svg>"
                    section-id="vigencia"
                    :editing="$editing"
                >
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
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
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <p class="text-sm text-blue-800 dark:text-blue-200">
                            💡 <strong>Nota:</strong> Si no defines un año de fin, la malla estará vigente indefinidamente hasta que la desactives.
                        </p>
                    </div>
                </x-hci-form-section>

                {{-- Sección 3: Descripción y Estado --}}
                <x-hci-form-section 
                    :step="3" 
                    title="Descripción y Estado" 
                    description="Información adicional y configuración de disponibilidad"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z' clip-rule='evenodd'/></svg>"
                    section-id="descripcion"
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
                        rows="5"
                    />

                    <div class="mt-6 flex items-start gap-3 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
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
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Solo las mallas activas estarán disponibles para asignar a nuevos cursos
                            </p>
                        </div>
                    </div>
                </x-hci-form-section>

                {{-- Resumen final --}}
                <div class="hci-summary">
                    <h3 class="hci-summary-title">📋 Resumen de la Malla Curricular</h3>
                    <ul class="hci-summary-list">
                        <li><strong>Programa:</strong> <span id="summary-magister">No seleccionado</span></li>
                        <li><strong>Nombre:</strong> <span id="summary-nombre">No ingresado</span></li>
                        <li><strong>Código:</strong> <span id="summary-codigo">No ingresado</span></li>
                        <li><strong>Vigencia:</strong> <span id="summary-vigencia">No definida</span></li>
                        <li><strong>Estado:</strong> <span id="summary-estado">Inactiva</span></li>
                    </ul>
                </div>

                {{-- Botones de acción --}}
                <div class="hci-actions">
                    <a href="{{ route('mallas-curriculares.index') }}" class="hci-button-secondary">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                        </svg>
                        Cancelar
                    </a>
                    <button type="submit" class="hci-button-primary">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        {{ $editing ? 'Actualizar Malla' : 'Crear Malla' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Scripts del formulario --}}
@push('scripts')
    @vite(['resources/js/alerts.js'])
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Actualizar resumen en tiempo real
            const magisterSelect = document.querySelector('[name="magister_id"]');
            const nombreInput = document.querySelector('[name="nombre"]');
            const codigoInput = document.querySelector('[name="codigo"]');
            const añoInicioInput = document.querySelector('[name="año_inicio"]');
            const añoFinInput = document.querySelector('[name="año_fin"]');
            const activaCheckbox = document.querySelector('[name="activa"]');

            function updateSummary() {
                // Magister
                const magisterText = magisterSelect.options[magisterSelect.selectedIndex]?.text || 'No seleccionado';
                document.getElementById('summary-magister').textContent = magisterText;

                // Nombre
                document.getElementById('summary-nombre').textContent = nombreInput.value || 'No ingresado';

                // Código
                document.getElementById('summary-codigo').textContent = codigoInput.value || 'No ingresado';

                // Vigencia
                const inicio = añoInicioInput.value;
                const fin = añoFinInput.value;
                let vigencia = 'No definida';
                if (inicio) {
                    vigencia = inicio + (fin ? ` - ${fin}` : ' - Actualidad');
                }
                document.getElementById('summary-vigencia').textContent = vigencia;

                // Estado
                document.getElementById('summary-estado').textContent = activaCheckbox.checked ? '✅ Activa' : '❌ Inactiva';
            }

            // Event listeners
            magisterSelect?.addEventListener('change', updateSummary);
            nombreInput?.addEventListener('input', updateSummary);
            codigoInput?.addEventListener('input', updateSummary);
            añoInicioInput?.addEventListener('input', updateSummary);
            añoFinInput?.addEventListener('input', updateSummary);
            activaCheckbox?.addEventListener('change', updateSummary);

            // Inicializar resumen
            updateSummary();
        });
    </script>
@endpush




