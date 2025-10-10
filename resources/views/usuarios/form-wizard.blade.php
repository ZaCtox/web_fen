{{-- Formulario de Usuarios con Principios HCI --}}
@section('title', isset($usuario) ? 'Editar Usuario' : 'Nuevo Usuario')

@php
    $editing = isset($usuario);
@endphp

{{-- Contenedor principal con principios HCI --}}
<div class="hci-container">
    <div class="hci-section">
        <h1 class="hci-heading-1 flex items-center">
            @if($editing)
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                </svg>
                Editar Usuario
            @else
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                </svg>
                Nuevo Usuario
            @endif
        </h1>
        <p class="hci-text">
            {{ $editing ? 'Modifica la información del usuario.' : 'Registra un nuevo usuario en el sistema.' }}
        </p>
    </div>

    {{-- Layout principal con progreso lateral --}}
    <div class="hci-wizard-layout">
        {{-- Barra de progreso lateral izquierda --}}
        <x-staff-progress-sidebar />

        {{-- Contenido principal del formulario --}}
        <div class="hci-form-content">
            <form class="hci-form" method="POST" action="{{ $editing ? route('usuarios.update', $usuario) : route('register') }}">
                @csrf
                @if($editing) @method('PUT') @endif

                {{-- Paso 1: Información Personal --}}
                <x-hci-form-section 
                    :step="1" 
                    title="Información Personal" 
                    description="Datos básicos del usuario"
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
                </x-hci-form-section>

                {{-- Paso 2: Rol y Permisos --}}
                <x-hci-form-section 
                    :step="2" 
                    title="Rol y Permisos" 
                    description="Asignación de rol y permisos del usuario"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z' clip-rule='evenodd'/><path d='M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z'/></svg>"
                    section-id="contacto"
                    :editing="$editing ?? false"
                    style="display: none;"
                >
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
                    {{-- Paso 3: Información Adicional (solo para registro) --}}
                    <x-hci-form-section 
                        :step="3" 
                        title="Información Adicional" 
                        description="Notificaciones y confirmación de cuenta"
                        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z'/><path d='M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z'/></svg>"
                        section-id="adicional"
                        :editing="$editing ?? false"
                        style="display: none;"
                    >
                        {{-- Información sobre el correo --}}
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-blue-500 mt-0.5 mr-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                                <div>
                                    <h4 class="text-lg font-medium text-blue-800 dark:text-blue-200 mb-2">
                                        Notificación por Correo Electrónico
                                    </h4>
                                    <p class="text-blue-700 dark:text-blue-300 mb-4">
                                        Se enviará un correo electrónico de confirmación a <strong id="email-notification" class="text-blue-900 dark:text-blue-100">{{ old('email', '') }}</strong> 
                                        con los detalles de acceso una vez que se cree la cuenta.
                                    </p>
                                    <div class="bg-blue-100 dark:bg-blue-800/30 rounded-lg p-4">
                                        <h5 class="font-medium text-blue-900 dark:text-blue-100 mb-2">¿Qué incluye el correo?</h5>
                                        <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                                            <li>• Contraseña temporal generada automáticamente</li>
                                            <li>• Instrucciones para el primer acceso</li>
                                            <li>• Enlace para cambiar la contraseña</li>
                                            <li>• Información de contacto para soporte</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-hci-form-section>

                    {{-- Paso 4: Resumen --}}
                    <x-hci-form-section 
                        :step="4" 
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

            </form>
        </div>
    </div>
</div>

@push('scripts')
    @vite('resources/js/usuarios-form-wizard.js')
@endpush



