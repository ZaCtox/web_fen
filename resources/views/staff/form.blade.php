{{-- Formulario de Staff Optimizado con Principios HCI --}}
@section('title', isset($staff) ? 'Editar miembro del Staff' : 'Crear miembro del Staff')

@php
    $editing = isset($staff);
@endphp

{{-- Layout genérico del wizard --}}
<x-hci-wizard-layout 
    title="Miembro del Equipo"
    :editing="$editing"
    createDescription="Registra un nuevo miembro del equipo con información organizada."
    editDescription="Modifica la información del miembro del equipo."
    sidebarComponent="staff-progress-sidebar"
    :formAction="$editing ? route('staff.update', $staff) : route('staff.store')"
    :formMethod="$editing ? 'PUT' : 'POST'"
    formEnctype="multipart/form-data"
>

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

                {{-- Sección 2: Foto de Perfil --}}
                <x-hci-form-section 
                    :step="2" 
                    title="Foto de Perfil" 
                    description="Sube una foto del miembro o se generará un avatar automático"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z' clip-rule='evenodd'/></svg>"
                    section-id="foto"
                    :editing="$editing"
                >
                    <div style="max-width: 500px;">
                        {{-- Preview de la foto actual --}}
                        <div class="flex justify-center mb-4">
                            <img id="foto-preview" 
                                 src="{{ isset($staff) && $staff->foto ? $staff->foto_perfil : 'https://ui-avatars.com/api/?name=Foto&background=84b6f4&color=000000&size=300&bold=true&font-size=0.4' }}"
                                 alt="Preview" 
                                 class="w-32 h-32 rounded-full object-cover border-4 border-[#84b6f4] shadow-lg">
                        </div>

                        {{-- Área de drag & drop --}}
                        <div id="foto-drop-zone" class="hci-file-drop-zone"
                             ondrop="handleFotoDrop(event)" 
                             ondragover="handleDragOver(event)" 
                             ondragleave="handleDragLeave(event)"
                             onclick="document.getElementById('foto-input').click()">
                            <div class="hci-file-drop-content">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <span id="foto-drop-text">Arrastra tu foto aquí</span>
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    O haz clic para seleccionar una imagen
                                </p>
                                <p class="text-xs text-gray-400 mt-2">
                                    JPG, JPEG, PNG, WEBP • Máximo 2MB
                                </p>
                            </div>
                        </div>

                        <input type="file" 
                               name="foto" 
                               id="foto-input" 
                               class="hidden" 
                               accept="image/jpeg,image/jpg,image/png,image/webp"
                               onchange="handleFotoSelect(event)">
                        
                        <div id="foto-preview-info" class="hidden mt-3 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span id="foto-name" class="text-sm font-medium text-green-700 dark:text-green-300"></span>
                                <button type="button" onclick="clearFoto()" class="ml-auto text-red-500 hover:text-red-700">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        @error('foto')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror

                        <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                            💡 <strong>Nota:</strong> Si no subes una foto, se generará automáticamente un avatar con las iniciales del nombre.
                        </p>
                    </div>
                </x-hci-form-section>

                {{-- Sección 3: Información de Contacto --}}
                <x-hci-form-section 
                    :step="3" 
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

                {{-- Sección 4: Información Adicional --}}
                <x-hci-form-section 
                    :step="4" 
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

                {{-- Sección 5: Resumen Final --}}
                <x-hci-form-section 
                    :step="5" 
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
</x-hci-wizard-layout>

{{-- Incluir CSS de Cropper.js --}}
@push('styles')
    <!-- Estilos del cropper eliminados - ya no los necesitamos -->
@endpush

{{-- Incluir JavaScript del wizard --}}
@push('scripts')
    @vite('resources/js/staff-form-wizard.js')
@endpush


