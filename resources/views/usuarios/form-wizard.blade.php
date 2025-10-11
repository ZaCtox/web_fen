{{-- Formulario de Usuarios con Principios HCI --}}
@section('title', isset($usuario) ? 'Editar Usuario' : 'Nuevo Usuario')

@php
    $editing = isset($usuario);
@endphp

{{-- Layout genérico del wizard --}}
<x-hci-wizard-layout 
    title="Usuario"
    :editing="$editing"
    createDescription="Registra un nuevo usuario en el sistema."
    editDescription="Modifica la información del usuario."
    sidebarComponent="usuarios-progress-sidebar"
    :formAction="$editing ? route('usuarios.update', $usuario) : route('register')"
    :formMethod="$editing ? 'PUT' : 'POST'"
>

                {{-- Paso 1: Información Personal y Rol --}}
                <x-hci-form-section 
                    :step="1" 
                    title="Información del Usuario" 
                    description="Datos básicos y rol del usuario"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z' clip-rule='evenodd'/></svg>"
                    section-id="personal"
                    :editing="$editing ?? false"
                    :is-active="true"
                    :is-first="true"
                    style="display: block;"
                >
                    <x-hci-field 
                        name="name" 
                        type="text" 
                        label="Nombre Completo" 
                        :required="true"
                        icon=""
                        help="Nombre y apellidos del usuario"
                        value="{{ old('name', $usuario->name ?? '') }}"
                        style="width: 100% !important;"
                    />

                    <x-hci-field 
                        name="email" 
                        type="email" 
                        label="Correo Electrónico" 
                        :required="true"
                        icon=""
                        help="Dirección de correo electrónico válida"
                        value="{{ old('email', $usuario->email ?? '') }}"
                        style="width: 100% !important;"
                    />

                    <x-hci-field 
                        name="rol" 
                        type="select" 
                        label="Rol del Usuario" 
                        :required="true"
                        icon=""
                        help="Selecciona el rol que tendrá el usuario en el sistema"
                        style="width: 100% !important;"
                    >
                        <option value="">-- Selecciona un rol --</option>
                        <option value="director_programa" {{ old('rol', $usuario->rol ?? '') == 'director_programa' ? 'selected' : '' }}>Director de Programa</option>
                        <option value="asistente_programa" {{ old('rol', $usuario->rol ?? '') == 'asistente_programa' ? 'selected' : '' }}>Asistente de Programa</option>
                        <option value="docente" {{ old('rol', $usuario->rol ?? '') == 'docente' ? 'selected' : '' }}>Docente</option>
                        <option value="técnico" {{ old('rol', $usuario->rol ?? '') == 'técnico' ? 'selected' : '' }}>Técnico</option>
                        <option value="auxiliar" {{ old('rol', $usuario->rol ?? '') == 'auxiliar' ? 'selected' : '' }}>Auxiliar</option>
                        <option value="asistente_postgrado" {{ old('rol', $usuario->rol ?? '') == 'asistente_postgrado' ? 'selected' : '' }}>Asistente de Postgrado</option>
                        <option value="director_magister" {{ old('rol', $usuario->rol ?? '') == 'director_magister' ? 'selected' : '' }}>Director Magíster</option>
                        <option value="director_administrativo" {{ old('rol', $usuario->rol ?? '') == 'director_administrativo' ? 'selected' : '' }}>Director Administrativo</option>
                    </x-hci-field>
                </x-hci-form-section>

                @if(!$editing)
                    {{-- Paso 2: Notificación de Correo (solo para registro) --}}
                    <x-hci-form-section 
                        :step="2" 
                        title="Notificación de Cuenta" 
                        description="Se enviará un correo con las credenciales de acceso"
                        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z'/><path d='M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z'/></svg>"
                        section-id="notificacion"
                        :editing="$editing ?? false"
                        style="display: none;"
                    >
                        {{-- Información sobre el correo --}}
                        <div class="w-full max-w-none bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-8">
                            <div class="flex items-start">
                                <svg class="w-8 h-8 text-blue-500 mt-1 mr-6 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                                <div class="flex-1">
                                    <h4 class="text-2xl font-semibold text-blue-800 dark:text-blue-200 mb-4">
                                        Notificación por Correo Electrónico
                                    </h4>
                                    <p class="text-lg text-blue-700 dark:text-blue-300 mb-6 leading-relaxed">
                                        Se enviará un correo electrónico de confirmación a <strong id="email-notification" class="text-blue-900 dark:text-blue-100">{{ old('email', '') }}</strong> 
                                        con los detalles de acceso una vez que se cree la cuenta.
                                    </p>
                                    <div class="bg-blue-100 dark:bg-blue-800/30 rounded-lg p-6">
                                        <h5 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-4">¿Qué incluye el correo?</h5>
                                        <ul class="text-base text-blue-800 dark:text-blue-200 space-y-3">
                                            <li class="flex items-start">
                                                <span class="mr-2">•</span>
                                                <span>Contraseña temporal generada automáticamente</span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="mr-2">•</span>
                                                <span>Instrucciones para el primer acceso</span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="mr-2">•</span>
                                                <span>Enlace para cambiar la contraseña</span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="mr-2">•</span>
                                                <span>Información de contacto para soporte</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-hci-form-section>

                    {{-- Paso 3: Resumen (registro) --}}
                    <x-hci-form-section 
                        :step="3" 
                        title="Resumen" 
                        description="Revisa la información antes de crear el usuario"
                        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>"
                        section-id="resumen"
                        :editing="$editing ?? false"
                        :is-last="true"
                        style="display: none;"
                    >
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Nombre</h4>
                                <p id="summary-name" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">--</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Correo</h4>
                                <p id="summary-email" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">--</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Rol</h4>
                                <p id="summary-rol" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">--</p>
                            </div>
                        </div>
                    </x-hci-form-section>
                @else
                    {{-- Paso 3: Resumen (para edición) --}}
                    <x-hci-form-section 
                        :step="3" 
                        title="Resumen" 
                        description="Revisa la información antes de actualizar el usuario"
                        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>"
                        section-id="resumen"
                        :editing="$editing ?? false"
                        :is-last="true"
                        style="display: none;"
                    >
                        <div class="bg-[#c4dafa]/30 dark:bg-[#84b6f4]/10 rounded-lg p-6 border border-[#84b6f4]/30 w-full">                        
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <!-- Nombre - 1 columna -->
                                <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                    <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Nombre Completo</span>
                                    <p class="text-gray-900 dark:text-white font-medium text-lg" id="summary-name">{{ old('name', $usuario->name ?? '') }}</p>
                                </div>

                                <!-- Email - 1 columna -->
                                <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                    <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Email</span>
                                    <p class="text-gray-900 dark:text-white font-medium text-lg break-words" id="summary-email">{{ old('email', $usuario->email ?? '') }}</p>
                                </div>

                                <!-- Rol - 1 columna -->
                                <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                    <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Rol</span>
                                    <p class="text-gray-900 dark:text-white font-medium text-lg" id="summary-rol">{{ old('rol', $usuario->rol ?? '') }}</p>
                                </div>

                                <!-- Contraseña - 1 columna -->
                                <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                    <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Contraseña</span>
                                    <p class="text-gray-900 dark:text-white font-medium text-lg" id="summary-password">{{ $editing ? 'No se modifica' : '••••••••' }}</p>
                                </div>
                            </div>

                            <div class="mt-6 p-4 bg-[#fcffff] dark:bg-gray-800 rounded-lg border border-[#84b6f4]/20">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Nota:</strong> Revisa que toda la información sea correcta antes de proceder. 
                                    {{ $editing ? 'Los cambios se aplicarán inmediatamente.' : 'Se creará un nuevo usuario.' }}
                                </p>
                            </div>
                        </div>
                    </x-hci-form-section>
                @endif

                @if($editing)
                    {{-- Paso 2: Resumen (edición) --}}
                    <x-hci-form-section 
                        :step="2" 
                        title="Resumen" 
                        description="Revisa los cambios antes de actualizar el usuario"
                        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>"
                        section-id="resumen"
                        :editing="$editing ?? false"
                        :is-last="true"
                        style="display: none;"
                    >
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Nombre</h4>
                                <p id="summary-name" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">--</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Correo Electrónico</h4>
                                <p id="summary-email" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">--</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">Rol</h4>
                                <p id="summary-rol" class="text-lg font-bold text-[#005187] dark:text-[#84b6f4]">--</p>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-[#fcffff] dark:bg-gray-800 rounded-lg border border-[#84b6f4]/20">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <strong>Nota:</strong> Revisa que toda la información sea correcta antes de proceder. Los cambios se aplicarán inmediatamente.
                            </p>
                        </div>
                    </x-hci-form-section>
                @endif
</x-hci-wizard-layout>

@push('scripts')
    @vite('resources/js/usuarios-form-wizard.js')
@endpush



