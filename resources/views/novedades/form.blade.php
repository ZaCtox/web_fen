{{-- Formulario de Novedades Optimizado con Principios HCI --}}
@section('title', isset($novedad) ? 'Editar Novedad' : 'Crear Novedad')

@php
    $editing = isset($novedad);
@endphp

{{-- Layout genérico del wizard --}}
<x-hci-wizard-layout 
    title="Novedad"
    :editing="$editing"
    createDescription="Registra una nueva novedad con información organizada."
    editDescription="Modifica la información de la novedad."
    sidebarComponent="novedades-progress-sidebar"
    :formAction="$editing ? route('novedades.update', $novedad) : route('novedades.store')"
    :formMethod="$editing ? 'PUT' : 'POST'"
    formEnctype="multipart/form-data"
>

                {{-- Sección 1: Información Básica --}}
                <x-hci-form-section 
                    :step="1" 
                    title="Información Básica" 
                    description="Título y contenido de la novedad"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z' clip-rule='evenodd'/></svg>"
                    section-id="basica"
                    :is-active="true"
                    :is-first="true"
                    :editing="$editing"
                >
                    <x-hci-field 
                        name="titulo"
                        label="Título de la Novedad"
                        placeholder="Ej: Inicio del Año Académico 2025"
                        value="{{ old('titulo', $novedad->titulo ?? '') }}"
                        :required="true"
                        icon="📰"
                        help="Título descriptivo y atractivo para la novedad"
                        maxlength="255"
                    />

                    <x-hci-field 
                        name="contenido"
                        type="textarea"
                        label="Contenido"
                        placeholder="Describe los detalles de la novedad..."
                        value="{{ old('contenido', $novedad->contenido ?? '') }}"
                        :required="true"
                        rows="6"
                        help="Contenido completo de la novedad con todos los detalles importantes"
                        maxlength="2000"
                    />

                    <x-hci-field 
                        name="imagen"
                        type="file"
                        label="Imagen (opcional)"
                        help="Imagen que acompañará la novedad (máximo 2MB)"
                        accept="image/*"
                    />

                    @if($editing && $novedad->imagen)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Imagen Actual</label>
                            <img src="{{ $novedad->imagen }}" alt="Imagen actual" class="w-32 h-32 object-cover rounded-lg border">
                        </div>
                    @endif
                </x-hci-form-section>

                {{-- Sección 2: Configuración --}}
                <x-hci-form-section 
                    :step="2" 
                    title="Configuración" 
                    description="Tipo de novedad y visibilidad"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z' clip-rule='evenodd'/></svg>"
                    section-id="configuracion"
                    :editing="$editing"
                >
                    <x-hci-field 
                        name="tipo_novedad"
                        type="select"
                        label="Tipo de Novedad"
                        :options="[
                            'academica' => 'Académica',
                            'evento' => 'Evento',
                            'admision' => 'Admisión',
                            'institucional' => 'Institucional',
                            'administrativa' => 'Administrativa',
                            'sistema' => 'Sistema',
                            'oportunidad' => 'Oportunidad',
                            'servicio' => 'Servicio',
                            'mantenimiento' => 'Mantenimiento'
                        ]"
                        value="{{ old('tipo_novedad', $novedad->tipo_novedad ?? '') }}"
                        :required="true"
                        icon="🏷️"
                        help="Selecciona el tipo que mejor describe la novedad"
                    />

                    <x-hci-field 
                        name="magister_id"
                        type="select"
                        label="Programa (opcional)"
                        :options="['' => 'Todos los programas'] + \App\Models\Magister::orderBy('nombre')->pluck('nombre', 'id')->toArray()"
                        value="{{ old('magister_id', $novedad->magister_id ?? '') }}"
                        icon="🎓"
                        help="Asocia la novedad a un programa específico"
                    />

                    <div class="hci-field">
                        <label class="hci-checkbox-label flex items-center">
                            <input type="checkbox" 
                                   name="visible_publico" 
                                   value="1"
                                   {{ old('visible_publico', $novedad->visible_publico ?? true) ? 'checked' : '' }}
                                   class="hci-checkbox rounded border-gray-300 text-blue-600">
                            <span class="ml-2">Visible al público</span>
                        </label>
                        <p class="hci-help-text">Si está marcado, la novedad será visible en el sitio público</p>
                    </div>

                    <div class="hci-field">
                        <label class="hci-checkbox-label flex items-center">
                            <input type="checkbox" 
                                   name="es_urgente" 
                                   value="1"
                                   {{ old('es_urgente', $novedad->es_urgente ?? false) ? 'checked' : '' }}
                                   class="hci-checkbox rounded border-gray-300 text-red-600">
                            <span class="ml-2">Novedad urgente</span>
                        </label>
                        <p class="hci-help-text">Las novedades urgentes se destacan visualmente</p>
                    </div>

                    <x-hci-field 
                        name="fecha_expiracion"
                        type="datetime-local"
                        label="Fecha de Expiración (opcional)"
                        value="{{ old('fecha_expiracion', $novedad && $novedad->fecha_expiracion && $novedad->fecha_expiracion instanceof \Carbon\Carbon ? $novedad->fecha_expiracion->format('Y-m-d\TH:i') : '') }}"
                        help="Fecha y hora en que la novedad expirará automáticamente"
                    />
                </x-hci-form-section>

                {{-- Sección 3: Diseño --}}
                <x-hci-form-section 
                    :step="3" 
                    title="Personalización Visual" 
                    description="Icono y colores para la novedad"
                    icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z' clip-rule='evenodd'/></svg>"
                    section-id="diseno"
                    :editing="$editing"
                >
                    <x-hci-field 
                        name="icono"
                        label="Icono"
                        placeholder="📰"
                        value="{{ old('icono', $novedad->icono ?? '📰') }}"
                        icon="🎨"
                        help="Emoji o icono que represente la novedad"
                        maxlength="10"
                    />

                    <x-hci-field 
                        name="color"
                        type="select"
                        label="Color de la Novedad"
                        :options="[
                            'blue' => 'Azul',
                            'green' => 'Verde',
                            'yellow' => 'Amarillo',
                            'red' => 'Rojo',
                            'purple' => 'Morado',
                            'indigo' => 'Índigo'
                        ]"
                        value="{{ old('color', $novedad->color ?? 'blue') }}"
                        :required="true"
                        icon="🎨"
                        help="Color que identificará visualmente la novedad"
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
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- Título -->
                            <div class="md:col-span-2 bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Título</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="resumen-titulo">{{ old('titulo', $novedad->titulo ?? '') }}</p>
                            </div>

                            <!-- Tipo -->
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Tipo</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="resumen-tipo">{{ old('tipo_novedad', $novedad->tipo_novedad ?? 'No especificado') }}</p>
                            </div>

                            <!-- Contenido - ancho completo -->
                            <div class="md:col-span-3 bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Contenido</span>
                                <p class="text-gray-900 dark:text-white text-base" id="resumen-contenido">{{ old('contenido', $novedad->contenido ?? '') }}</p>
                            </div>

                            <!-- Icono -->
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Icono</span>
                                <p class="text-gray-900 dark:text-white font-medium text-3xl" id="resumen-icono">{{ old('icono', $novedad->icono ?? '📰') }}</p>
                            </div>

                            <!-- Color -->
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Color</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg capitalize" id="resumen-color">{{ old('color', $novedad->color ?? 'blue') }}</p>
                            </div>

                            <!-- Urgente -->
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Urgente</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="resumen-urgente">{{ old('es_urgente', $novedad->es_urgente ?? false) ? 'Sí' : 'No' }}</p>
                            </div>

                            <!-- Visibilidad -->
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Visibilidad</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="resumen-visibilidad">{{ old('visible_publico', $novedad->visible_publico ?? true) ? 'Público' : 'Privado' }}</p>
                            </div>

                            <!-- Programa -->
                            <div class="md:col-span-2 bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Programa</span>
                                <p class="text-gray-900 dark:text-white font-medium text-lg" id="resumen-programa">
                                    @if($editing && $novedad->magister)
                                        {{ $novedad->magister->nombre }}
                                    @else
                                        No especificado
                                    @endif
                                </p>
                            </div>

                            <!-- Expiración -->
                            <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                                <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4] block mb-2">Expiración</span>
                                <p class="text-gray-900 dark:text-white font-medium text-base" id="resumen-expiracion">
                                    @if($editing && $novedad->fecha_expiracion && $novedad->fecha_expiracion instanceof \Carbon\Carbon)
                                        {{ $novedad->fecha_expiracion->format('d/m/Y H:i') }}
                                    @else
                                        Sin expiración
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-[#fcffff] dark:bg-gray-800 rounded-lg border border-[#84b6f4]/20">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                <strong>Nota:</strong> Revisa que toda la información sea correcta antes de proceder. 
                                {{ $editing ? 'Los cambios se aplicarán inmediatamente.' : 'Se creará una nueva novedad.' }}
                            </p>
                        </div>
                    </div>
                </x-hci-form-section>
</x-hci-wizard-layout>

{{-- Incluir JavaScript del wizard --}}
@push('scripts')
    @vite('resources/js/novedades-form-wizard.js')
@endpush



