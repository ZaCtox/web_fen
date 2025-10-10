{{-- Formulario de Staff Optimizado con Principios HCI --}}
@section('title', isset($staff) ? 'Editar miembro del Staff' : 'Crear miembro del Staff')

@php
    $editing = isset($staff);
@endphp

{{-- Contenedor principal con principios HCI --}}
<div class="hci-container">
    <div class="hci-section">
        <h1 class="hci-heading-1 flex items-center">
            @if($editing)
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                </svg>
                Editar Miembro del Equipo
            @else
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                </svg>
                Crear Miembro del Equipo
            @endif
        </h1>
        <p class="hci-text">
            {{ $editing ? 'Modifica la información del miembro del equipo.' : 'Registra un nuevo miembro del equipo con información organizada.' }}
        </p>
    </div>

    {{-- Layout principal con progreso lateral --}}
    <div class="hci-wizard-layout">
        {{-- Barra de progreso lateral izquierda --}}
        <x-staff-progress-sidebar />

        {{-- Contenido principal del formulario --}}
        <div class="hci-form-content">
            <form class="hci-form" method="POST" action="{{ $editing ? route('staff.update', $staff) : route('staff.store') }}">
    @csrf
    @if($editing) @method('PUT') @endif

                {{-- Sección 1: Información Personal --}}
                <x-hci-form-section 
                    :step="1" 
                    title="Información Personal" 
                    description="Datos básicos del miembro del equipo"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z' clip-rule='evenodd'/></svg>"
                    section-id="personal"
                    :is-active="true"
                    :is-first="true"
                    :editing="$editing"
                >
                    <x-hci-field 
                        name="nombre"
                        label="Nombre Completo"
                        placeholder="Ej: Juan Pérez González"
                        value="{{ old('nombre', $staff->nombre ?? '') }}"
                        :required="true"
                        icon=""
                        help=""
                        maxlength="150"
                    />

                <x-hci-field 
                    name="cargo"
                    label="Cargo"
                    placeholder="Ej: Coordinador Académico"
                    value="{{ old('cargo', $staff->cargo ?? '') }}"
                    :required="true"
                    icon=""
                    help=""
                    maxlength="100"
                    style="width: 400px !important;"
                />

                </x-hci-form-section>

                {{-- Sección 2: Información de Contacto --}}
                <x-hci-form-section 
                    :step="2" 
                    title="Información de Contacto" 
                    description="Datos de contacto del miembro del equipo"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z'/></svg>"
                    section-id="contacto"
                    :editing="$editing"
                >
                    <x-hci-field 
                        name="email"
                        type="email"
                        label="Correo Electrónico institucional"
                        placeholder="ejemplo@utalca.cl"
                        value="{{ old('email', $staff->email ?? '') }}"
                        :required="true"
                        icon=""
                        help=""
                    />

                    <x-hci-field 
                        name="telefono"
                        label="Teléfono"
                        placeholder=""
                        value="{{ old('telefono', $staff->telefono ?? '') }}"
                        :required="true"
                        icon=""
                        help="Ejemplo celular: +56 9 12345678 | Ejemplo fijo: +56 712345678"
                        pattern="^(\+56\s?9\d{8}|\+56\s?712\d{6}|9\d{8}|712\d{6})$"
                    />
                </x-hci-form-section>

                {{-- Sección 3: Información Adicional --}}
                <x-hci-form-section 
                    :step="3" 
                    title="Información Adicional" 
                    description="Datos complementarios del usuario"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z' clip-rule='evenodd'/></svg>"
                    section-id="adicional"
                    :editing="$editing"
                >
                    <x-hci-field 
                        name="anexo"
                        label="Anexo"
                        placeholder="Ej: 1234"
                        value="{{ old('anexo', $staff->anexo ?? '') }}"
                        icon=""
                        help="Número interno de la universidad"
                        maxlength="5"
                    />
                </x-hci-form-section>

                {{-- Sección 4: Resumen Final --}}
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
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                            <!-- Nombre Completo - 1 columna -->
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Nombre Completo</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="resumen-nombre">{{ old('nombre', $staff->nombre ?? '') }}</p>
                            </div>

                            <!-- Cargo - 2 columnas para más espacio -->
                            <div class="md:col-span-1 lg:col-span-2 bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Cargo</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="resumen-cargo">{{ old('cargo', $staff->cargo ?? '') }}</p>
                            </div>

                            <!-- Email - 2 columnas para más espacio -->
                            <div class="md:col-span-1 lg:col-span-2 bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Email</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg break-words" id="resumen-email">{{ old('email', $staff->email ?? '') }}</p>
                            </div>

                            <!-- Teléfono - 2 columnas para más espacio -->
                            <div class="md:col-span-1 lg:col-span-2 bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Teléfono</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="resumen-telefono">{{ old('telefono', $staff->telefono ?? '') }}</p>
                            </div>

                            <!-- Anexo - 1 columna -->
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Anexo</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="resumen-anexo">{{ old('anexo', $staff->anexo ?? 'No especificado') }}</p>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-[#fcffff] dark:bg-gray-800 rounded-lg border border-[#84b6f4]/20">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <strong>Nota:</strong> Revisa que toda la información sea correcta antes de proceder. 
                                {{ $editing ? 'Los cambios se aplicarán inmediatamente.' : 'Se creará un nuevo miembro del equipo.' }}
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
    aria-label="Ayuda con el formulario"
/>

{{-- Incluir JavaScript del wizard --}}
@push('scripts')
    @vite('resources/js/staff-form-wizard.js')
@endpush


