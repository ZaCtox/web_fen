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
    formEnctype="multipart/form-data"
>
    {{-- Variable para JavaScript --}}
    <input type="hidden" id="is-editing" value="{{ $editing ? '1' : '0' }}">

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
                    contentClass="grid-cols-1 md:grid-cols-2 gap-6"
                >
                    <x-hci-field 
                        name="name" 
                        type="text" 
                        label="Nombre Completo" 
                        :required="true"
                        help="Nombre y apellidos del usuario"
                        value="{{ old('name', $usuario->name ?? '') }}"
                    />

                    <x-hci-field 
                        name="email" 
                        type="email" 
                        label="Correo Electrónico" 
                        :required="true"
                        help="Dirección de correo electrónico válida"
                        value="{{ old('email', $usuario->email ?? '') }}"
                    />

                    <x-hci-field 
                        name="rol" 
                        type="select" 
                        label="Rol del Usuario" 
                        :required="!$editing"
                        help="{{ $editing ? 'El rol actual del usuario' : 'Selecciona el rol que tendrá el usuario en el sistema' }}"
                    >
                        <option value="">-- Selecciona un rol --</option>
                        <option value="director_administrativo" {{ old('rol', $usuario->rol ?? '') == 'director_administrativo' ? 'selected' : '' }}>Director Administrativo</option>
                        <option value="decano" {{ old('rol', $usuario->rol ?? '') == 'decano' ? 'selected' : '' }}>Decano</option>
                        <option value="director_programa" {{ old('rol', $usuario->rol ?? '') == 'director_programa' ? 'selected' : '' }}>Director de Programa</option>
                        <option value="asistente_programa" {{ old('rol', $usuario->rol ?? '') == 'asistente_programa' ? 'selected' : '' }}>Asistente de Programa</option>
                        <option value="docente" {{ old('rol', $usuario->rol ?? '') == 'docente' ? 'selected' : '' }}>Docente</option>
                        <option value="técnico" {{ old('rol', $usuario->rol ?? '') == 'técnico' ? 'selected' : '' }}>Técnico</option>
                        <option value="auxiliar" {{ old('rol', $usuario->rol ?? '') == 'auxiliar' ? 'selected' : '' }}>Auxiliar</option>
                        <option value="asistente_postgrado" {{ old('rol', $usuario->rol ?? '') == 'asistente_postgrado' ? 'selected' : '' }}>Asistente de Postgrado</option>
                    </x-hci-field>
                </x-hci-form-section>

                {{-- Paso 2: Foto de Perfil --}}
                <x-hci-form-section 
                    :step="2" 
                    title="Foto de Perfil" 
                    description="Agrega una foto para el usuario"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z' clip-rule='evenodd'/></svg>"
                    section-id="foto"
                    :editing="$editing ?? false"
                    style="display: none;"
                >
                    <div class="w-full">
                        {{-- Preview de la foto --}}
                        <div class="flex justify-center mb-4">
                            <img id="foto-preview" 
                                 src="{{ isset($usuario) ? ($usuario->foto ?? $usuario->generateAvatarUrl()) : 'https://ui-avatars.com/api/?name=Foto&background=84b6f4&color=000000&size=300&bold=true&font-size=0.4' }}" 
                                 alt="Preview" 
                                 class="w-32 h-32 rounded-full object-cover border-4 border-[#84b6f4] shadow-lg">
                        </div>

                        {{-- Área de drag & drop --}}
                        <div id="foto-drop-zone" 
                             class="hci-file-drop-zone"
                             role="button"
                             tabindex="0"
                             aria-label="Área para subir foto de perfil. Arrastra una imagen aquí o presiona Enter para seleccionar un archivo"
                             ondrop="handleFotoDrop(event)" 
                             ondragover="handleDragOver(event)" 
                             ondragleave="handleDragLeave(event)"
                             onclick="document.getElementById('foto-input').click()"
                             onkeydown="if(event.key==='Enter' || event.key===' ') { event.preventDefault(); document.getElementById('foto-input').click(); }">
                            <div class="hci-file-drop-content">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <span id="foto-drop-text">Arrastra tu foto aquí</span>
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    O haz clic (o presiona Enter) para seleccionar una imagen
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

                        {{-- Selector de Color del Avatar --}}
                        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                Color del Avatar (opcional)
                            </label>
                            
                            <div class="flex flex-wrap gap-3 mb-2">
                                @php
                                    $colores = [
                                        '005187' => 'Azul oscuro',
                                        '4d82bc' => 'Azul medio',
                                        '84b6f4' => 'Azul claro',
                                        '00acc1' => 'Cyan',
                                        '66bb6a' => 'Verde',
                                        'ffa726' => 'Naranja',
                                        'ef5350' => 'Rojo',
                                        'ffca28' => 'Amarillo',
                                        'ab47bc' => 'Morado',
                                        '78909c' => 'Gris',
                                    ];
                                    $colorActual = old('avatar_color', $usuario->avatar_color ?? null) ?? '4d82bc';
                                @endphp
                                
                                @foreach($colores as $codigo => $nombre)
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" 
                                               name="avatar_color" 
                                               value="{{ $codigo }}"
                                               class="sr-only peer"
                                               {{ $colorActual === $codigo ? 'checked' : '' }}
                                               onchange="updateAvatarPreviewColor('{{ $codigo }}')">
                                        <div class="w-10 h-10 rounded-lg border-4 transition-all duration-200 peer-checked:border-gray-900 dark:peer-checked:border-white peer-checked:scale-110 border-gray-300 dark:border-gray-600 hover:scale-105 shadow-md" 
                                             style="background-color: #{{ $codigo }};"
                                             title="{{ $nombre }}">
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Si no subes una foto, se generará un avatar con las iniciales usando este color.
                            </p>
                        </div>
                    </div>
                </x-hci-form-section>

                @if(!$editing)
                    {{-- Paso 3: Notificación de Correo (solo para registro) --}}
                    <x-hci-form-section 
                        :step="3" 
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

                    {{-- Paso 4: Resumen (registro) --}}
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
                        <div class="bg-[#c4dafa]/30 dark:bg-[#84b6f4]/10 rounded-lg p-6 border border-[#84b6f4]/30 w-full">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                    <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Nombre</span>
                                    <p id="summary-name" class="text-gray-900 dark:text-white font-medium text-lg">--</p>
                                </div>
                                <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                    <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Correo</span>
                                    <p id="summary-email" class="text-gray-900 dark:text-white font-medium text-lg break-words">--</p>
                                </div>
                                <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                    <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Rol</span>
                                    <p id="summary-rol" class="text-gray-900 dark:text-white font-medium text-lg">--</p>
                                </div>
                            </div>

                            <div class="mt-6 p-4 bg-[#fcffff] dark:bg-gray-800 rounded-lg border border-[#84b6f4]/20">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Nota:</strong> Revisa que toda la información sea correcta antes de proceder. Se creará un nuevo usuario.
                                </p>
                            </div>
                        </div>
                    </x-hci-form-section>
                @else
                    {{-- Paso 3: Resumen (edición) --}}
                    <x-hci-form-section 
                        :step="3" 
                        title="Resumen" 
                        description="Revisa los cambios antes de actualizar el usuario"
                        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>"
                        section-id="resumen"
                        :editing="$editing ?? false"
                        :is-last="true"
                        style="display: none;"
                    >
                        <div class="bg-[#c4dafa]/30 dark:bg-[#84b6f4]/10 rounded-lg p-6 border border-[#84b6f4]/30 w-full">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                    <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Nombre Completo</span>
                                    <p id="summary-name" class="text-gray-900 dark:text-white font-medium text-lg">{{ old('name', $usuario->name ?? '') }}</p>
                                </div>
                                <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                    <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Correo Electrónico</span>
                                    <p id="summary-email" class="text-gray-900 dark:text-white font-medium text-lg break-words">{{ old('email', $usuario->email ?? '') }}</p>
                                </div>
                                <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                    <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Rol</span>
                                    <p id="summary-rol" class="text-gray-900 dark:text-white font-medium text-lg">{{ old('rol', $usuario->rol ?? '') }}</p>
                                </div>
                            </div>

                            <div class="mt-6 p-4 bg-[#fcffff] dark:bg-gray-800 rounded-lg border border-[#84b6f4]/20">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Nota:</strong> Revisa que toda la información sea correcta antes de proceder. Los cambios se aplicarán inmediatamente.
                                </p>
                            </div>
                        </div>
                    </x-hci-form-section>
                @endif
</x-hci-wizard-layout>

@push('scripts')
    @vite('resources/js/usuarios-form-wizard.js')
@endpush
