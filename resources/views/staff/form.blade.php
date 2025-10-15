{{-- Formulario de Equipo Optimizado con Principios HCI --}}
@section('title', isset($staff) ? 'Editar miembro del Equipo' : 'Crear miembro del Equipo')

@php
    $editing = isset($staff);
@endphp

{{-- Layout genérico del wizard con datos reactivos --}}
<div x-data="{
    formData: {
        nombre: '{{ old('nombre', $staff->nombre ?? '') }}',
        cargo: '{{ old('cargo', $staff->cargo ?? '') }}',
        email: '{{ old('email', $staff->email ?? '') }}',
        telefono: '{{ old('telefono', $staff->telefono ?? '') }}',
        anexo: '{{ old('anexo', $staff->anexo ?? '') }}'
    }
}">
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
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nombre Completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                        name="nombre"
                               id="nombre"
                               x-model="formData.nombre"
                        placeholder="Ej: Juan Pérez González"
                               required
                        maxlength="150"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition">
                    </div>

                    <div class="md:col-span-2 lg:col-span-3">
                        <label for="cargo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Cargo <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                               name="cargo" 
                               id="cargo"
                               x-model="formData.cargo"
                               placeholder="Ej: Coordinador de Postgrado y Magísteres"
                               required
                               maxlength="200"
                               rows="2"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition resize-none"></textarea>
                    </div>

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
                    <div class="w-full">
                        {{-- Preview de la foto actual --}}
                        <div class="flex justify-center mb-4">
                            <img id="foto-preview" 
                                 src="{{ isset($staff) && $staff->foto ? $staff->foto_perfil : (isset($staff) ? $staff->generateAvatarUrl() : 'https://ui-avatars.com/api/?name=Staff&background=4d82bc&color=ffffff&size=300&bold=true&font-size=0.4') }}"
                                 alt="Preview" 
                                 class="w-32 h-32 rounded-full object-cover border-4 border-[#84b6f4] shadow-lg">
                        </div>

                        {{-- Área de drag & drop con mejor accesibilidad --}}
                        <div id="foto-drop-zone" 
                             class="hci-file-drop-zone mb-4"
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

                        {{-- Botón para eliminar foto existente (solo en edición) --}}
                        @if($editing && isset($staff) && $staff->foto)
                            <form action="{{ route('staff.delete-foto', $staff) }}" method="POST" class="mt-4" id="delete-foto-form">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                        onclick="if(confirm('¿Estás seguro de que quieres eliminar la foto actual? Se generará un avatar con las iniciales.')) { document.getElementById('delete-foto-form').submit(); }"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-[#e57373] hover:bg-[#d32f2f] text-white rounded-lg shadow-md transition-all duration-200 font-semibold text-sm hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Eliminar Foto Actual
                                </button>
                            </form>
                        @endif

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
                                    $colorActual = old('avatar_color', ($staff->avatar_color ?? null)) ?? '4d82bc';
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

                {{-- Sección 3: Información de Contacto --}}
                <x-hci-form-section 
                    :step="3" 
                    title="Información de Contacto" 
                    description="Datos de contacto del miembro del equipo"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z'/></svg>"
                    section-id="contacto"
                    :editing="$editing"
                >
                    <div x-data="{ 
                        emailTouched: false,
                        validateEmail() {
                            const emailRegex = /^[a-zA-Z0-9._%+-]+@utalca\.cl$/;
                            return emailRegex.test(formData.email) || formData.email === '';
                        }
                    }">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Correo Electrónico Institucional <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email"
                               x-model="formData.email"
                               @blur="emailTouched = true"
                               placeholder="ejemplo@utalca.cl"
                               required
                               pattern="^[a-zA-Z0-9._%+-]+@utalca\.cl$"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition"
                               :class="emailTouched && !validateEmail() ? 'border-red-500' : ''">
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400" x-show="formData.email === ''">
                            💡 <strong>Importante:</strong> Debe ser un correo institucional @utalca.cl
                        </p>
                        <p class="mt-2 text-xs text-green-600 dark:text-green-400" x-show="validateEmail() && formData.email !== ''" x-cloak>
                            ✓ Email institucional válido: <span x-text="formData.email"></span>
                        </p>
                        <p x-show="emailTouched && !validateEmail() && formData.email !== ''" 
                           class="mt-1 text-sm text-red-600 dark:text-red-400"
                           x-cloak>
                            ⚠️ El correo debe ser del dominio @utalca.cl
                        </p>
                    </div>

                    <div x-data="{ 
                        touched: false,
                        formatPhone() {
                            // Solo formatear números chilenos automáticamente
                            // Si ya tiene +, asumir que es internacional y no tocar
                            if (formData.telefono.startsWith('+') && !formData.telefono.startsWith('+56')) {
                                return; // Es internacional, no formatear
                            }
                            
                            // Limpiar todo excepto números y +
                            let cleaned = formData.telefono.replace(/[^\d+]/g, '');
                            
                            // Si empieza con 56, agregar +
                            if (cleaned.startsWith('56') && !cleaned.startsWith('+')) {
                                cleaned = '+' + cleaned;
                            }
                            
                            // Si no tiene código de país Y ya escribió suficientes números, asumir Chile
                            if (!cleaned.startsWith('+') && cleaned.length >= 8) {
                                cleaned = '+56' + cleaned;
                            }
                            
                            // Solo formatear números chilenos completos
                            if (cleaned.startsWith('+56')) {
                                // Formatear celular: +56 9 1234 5678 (9 dígitos después de +56)
                                if (cleaned.match(/^\+56(9\d{8})$/)) {
                                    cleaned = cleaned.replace(/^\+56(9)(\d{4})(\d{4})$/, '+56 $1 $2 $3');
                                }
                                // Formatear fijo 9 dígitos: +56 71 220 0200 (ej: Talca)
                                else if (cleaned.match(/^\+56([2-7]\d{8})$/)) {
                                    cleaned = cleaned.replace(/^\+56(\d{2})(\d{3})(\d{4})$/, '+56 $1 $2 $3');
                                }
                                // Formatear fijo 8 dígitos: +56 75 234 567 (otras regiones)
                                else if (cleaned.match(/^\+56([2-7]\d{7})$/)) {
                                    cleaned = cleaned.replace(/^\+56(\d{2})(\d{3})(\d{3})$/, '+56 $1 $2 $3');
                                }
                            }
                            
                            formData.telefono = cleaned;
                        },
                        validatePhone() {
                            if (formData.telefono === '') return true;
                            
                            // Celular Chile: +56 9 XXXX XXXX (9 dígitos)
                            const celularChile = /^\+56\s9\s\d{4}\s\d{4}$/;
                            // Fijo Chile 9 dígitos: +56 71 220 0200 (ej: Talca)
                            const fijoChile9 = /^\+56\s[2-7]\d\s\d{3}\s\d{4}$/;
                            // Fijo Chile 8 dígitos: +56 75 234 567 (otras regiones)
                            const fijoChile8 = /^\+56\s[2-7]\d\s\d{3}\s\d{3}$/;
                            // Internacional: + seguido de código de país (1-3 dígitos) y número (mínimo 7 dígitos)
                            const internacional = /^\+\d{1,3}\s?\d{7,15}$/;
                            
                            return celularChile.test(formData.telefono) || fijoChile9.test(formData.telefono) || fijoChile8.test(formData.telefono) || internacional.test(formData.telefono);
                        },
                        get phoneType() {
                            if (formData.telefono === '') return '';
                            if (/^\+56\s9/.test(formData.telefono)) return 'celular-chile';
                            if (/^\+56\s[2-7]/.test(formData.telefono)) return 'fijo-chile';
                            if (/^\+\d{1,3}/.test(formData.telefono) && !formData.telefono.startsWith('+56')) return 'internacional';
                            return '';
                        }
                    }">
                        <label for="telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Teléfono <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" 
                               name="telefono" 
                               id="telefono"
                               x-model="formData.telefono"
                               @blur="formatPhone(); touched = true"
                               placeholder="+56 9 1234 5678 o +1 555 1234567"
                               required
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition"
                               :class="touched && !validatePhone() ? 'border-red-500' : ''">
                        
                        <!-- Ayuda dinámica según el tipo -->
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400" x-show="formData.telefono === ''">
                            💡 <strong>Formato:</strong> Chile: 9 dígitos (ej: 912345678) • Internacional: +código país + número (ej: +1 5551234567)
                        </p>
                        <p class="mt-2 text-xs text-green-600 dark:text-green-400" x-show="phoneType === 'celular-chile' && validatePhone()" x-cloak>
                            ✓ Celular Chile: <span x-text="formData.telefono"></span>
                        </p>
                        <p class="mt-2 text-xs text-green-600 dark:text-green-400" x-show="phoneType === 'fijo-chile' && validatePhone()" x-cloak>
                            ✓ Teléfono fijo Chile: <span x-text="formData.telefono"></span>
                        </p>
                        <p class="mt-2 text-xs text-blue-600 dark:text-blue-400" x-show="phoneType === 'internacional' && validatePhone()" x-cloak>
                            🌍 Teléfono internacional: <span x-text="formData.telefono"></span>
                        </p>
                        
                        <!-- Error solo después de blur y si no valida -->
                        <p x-show="touched && !validatePhone() && formData.telefono !== ''" 
                           class="mt-1 text-sm text-red-600 dark:text-red-400"
                           x-cloak>
                            ⚠️ Formato inválido. Chile: 9 dígitos (ej: 912345678). Internacional: +código + número (ej: +1 5551234567).
                        </p>
                    </div>
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
                    <div>
                        <label for="anexo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Anexo
                        </label>
                        <input type="text" 
                               name="anexo" 
                               id="anexo"
                               x-model="formData.anexo"
                               placeholder="Ej: 1234"
                               maxlength="5"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent transition">
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            💡 Número interno de la universidad
                        </p>
                    </div>
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Nombre Completo -->
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Nombre Completo</span>
                                <p class="text-gray-900 dark:text-white font-medium" x-text="formData.nombre || 'No ingresado'"></p>
                            </div>

                            <!-- Cargo -->
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Cargo</span>
                                <p class="text-gray-900 dark:text-white font-medium" x-text="formData.cargo || 'No ingresado'"></p>
                            </div>

                            <!-- Email -->
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Email</span>
                                <p class="text-gray-900 dark:text-white font-medium break-words" x-text="formData.email || 'No ingresado'"></p>
                            </div>

                            <!-- Teléfono -->
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Teléfono</span>
                                <p class="text-gray-900 dark:text-white font-medium" x-text="formData.telefono || 'No ingresado'"></p>
                            </div>

                            <!-- Anexo -->
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Anexo</span>
                                <p class="text-gray-900 dark:text-white font-medium" x-text="formData.anexo || 'No especificado'"></p>
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
</div>

{{-- Incluir CSS de Cropper.js --}}
@push('styles')
    <!-- Estilos del cropper eliminados - ya no los necesitamos -->
@endpush

{{-- Incluir JavaScript del wizard --}}
@push('scripts')
    @vite('resources/js/staff-form-wizard.js')
@endpush


