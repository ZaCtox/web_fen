{{-- Formulario de Mallas Curriculares con Wizard Genérico --}}
@section('title', isset($mallaCurricular) ? 'Editar Malla Curricular' : 'Crear Malla Curricular')

@php
    $editing = isset($mallaCurricular);
    
    // Definir los pasos del wizard
    $wizardSteps = [
        ['title' => 'Información Básica', 'description' => 'Datos principales'],
        ['title' => 'Período de Vigencia', 'description' => 'Años de validez'],
        ['title' => 'Descripción y Estado', 'description' => 'Detalles y activación'],
        ['title' => 'Resumen', 'description' => 'Revisar y confirmar']
    ];
@endphp

<x-hci-wizard-layout
    title="Malla Curricular"
    :editing="$editing"
    createDescription="Crea una nueva versión de malla curricular con información organizada."
    editDescription="Modifica la información de la malla curricular."
    :steps="$wizardSteps"
    :formAction="$editing ? route('mallas-curriculares.update', $mallaCurricular) : route('mallas-curriculares.store')"
    :formMethod="$editing ? 'PUT' : 'POST'"
>
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
                    <div class="space-y-6">
                        <div class="w-full max-w-2xl">
                            <x-hci-field 
                                name="magister_id"
                                type="select"
                                label="Programa de Magíster"
                                :required="true"
                                icon=""
                                help="Selecciona el programa al que pertenece esta malla"
                                class="w-full"
                                style="min-width: 350px !important;"
                            >
                            <option value="">-- Selecciona un Programa --</option>
                            @foreach($magisters as $magister)
                                <option value="{{ $magister->id }}" {{ old('magister_id', $mallaCurricular->magister_id ?? '') == $magister->id ? 'selected' : '' }}>
                                    {{ $magister->nombre }}
                                </option>
                            @endforeach
                            </x-hci-field>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <div class="w-full">
                                <x-hci-field 
                                    name="nombre"
                                    label="Nombre de la Malla"
                                    placeholder="Ej: Malla Curricular 2024-2026"
                                    value="{{ old('nombre', $mallaCurricular->nombre ?? '') }}"
                                    :required="true"
                                    icon=""
                                    help="Nombre descriptivo de la versión de la malla"
                                    maxlength="255"
                                    class="w-full"
                                    style="min-width: 350px !important;"
                                />
                            </div>

                            <div class="w-full">
                                <x-hci-field 
                                    name="codigo"
                                    label="Código"
                                    placeholder="Ej: GSS-2024-V1"
                                    value="{{ old('codigo', $mallaCurricular->codigo ?? '') }}"
                                    :required="true"
                                    icon=""
                                    help="Código único (formato: SIGLAS-AÑO-VERSION)"
                                    maxlength="50"
                                    class="w-full"
                                    style="min-width: 350px !important;"
                                />
                            </div>
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
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 gap-6">
                            <div class="w-full">
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
                                    class="w-full"
                                    style="min-width: 250px !important;"
                                />
                            </div>

                            <div class="w-full">
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
                                    class="w-full"
                                    style="min-width: 250px !important;"
                                />
                            </div>
                        </div>

                        <div class="p-4 bg-[#c4dafa]/30 dark:bg-[#84b6f4]/10 rounded-lg border border-[#84b6f4]/30">
                            <p class="text-sm text-[#005187] dark:text-[#84b6f4]">
                                💡 <strong>Nota:</strong> Si no defines un año de fin, la malla estará vigente indefinidamente hasta que la desactives.
                            </p>
                        </div>
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
                    <div class="space-y-6">
                        <div class="w-full">
                            <x-hci-field 
                                name="descripcion"
                                type="textarea"
                                label="Descripción"
                                placeholder="Describe las características principales de esta versión de la malla curricular, cambios respecto a versiones anteriores, etc."
                                value="{{ old('descripcion', $mallaCurricular->descripcion ?? '') }}"
                                icon=""
                                help="Información detallada sobre esta malla curricular"
                                rows="5"
                                class="w-full"
                                style="min-width: 500px !important;"
                            />
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-[#c4dafa]/30 dark:bg-[#84b6f4]/10 rounded-lg border border-[#84b6f4]/30">
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
                                <p class="text-xs text-[#005187]/70 dark:text-[#84b6f4]/70 mt-1">
                                    Solo las mallas activas estarán disponibles para asignar a nuevos cursos
                                </p>
                            </div>
                        </div>
                    </div>
                </x-hci-form-section>

                {{-- Sección 4: Resumen --}}
                <x-hci-form-section 
                    :step="4" 
                    title="Resumen" 
                    description="Revisa y confirma los datos antes de enviar"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>"
                    section-id="resumen"
                    :is-last="true"
                    :editing="$editing"
                >
                    <div class="bg-[#c4dafa]/30 dark:bg-[#84b6f4]/10 rounded-lg p-6 border border-[#84b6f4]/30 w-full">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Programa</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="summary-magister">—</p>
                            </div>
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Nombre</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="summary-nombre">—</p>
                            </div>
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Código</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="summary-codigo">—</p>
                            </div>
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Vigencia</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="summary-vigencia">—</p>
                            </div>
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Estado</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="summary-estado">—</p>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-[#fcffff] dark:bg-gray-800 rounded-lg border border-[#84b6f4]/20">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <strong>Nota:</strong> Revisa que toda la información sea correcta antes de guardar.
                            </p>
                        </div>
                    </div>
                </x-hci-form-section>
</x-hci-wizard-layout>

{{-- Scripts del formulario --}}
@push('scripts')
    @vite('resources/js/mallas-curriculares-form-wizard.js')
@endpush




