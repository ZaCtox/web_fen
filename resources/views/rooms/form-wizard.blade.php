{{-- Formulario de Salas con Wizard Genérico --}}
@section('title', isset($room) ? 'Editar Sala' : 'Crear Sala')

@php
    $editing = isset($room);
    
    // Definir los pasos del wizard
    $wizardSteps = [
        ['title' => 'Información Básica', 'description' => 'Nombre y ubicación'],
        ['title' => 'Detalles', 'description' => 'Capacidad y condiciones'],
        ['title' => 'Equipamiento', 'description' => 'Condiciones disponibles'],
        ['title' => 'Resumen', 'description' => 'Revisar y confirmar']
    ];
@endphp

<x-hci-wizard-layout
    title="Sala"
    :editing="$editing"
    createDescription="Crea una nueva sala con información organizada y estructurada."
    editDescription="Modifica la información de la sala."
    :steps="$wizardSteps"
    :formAction="$editing ? route('rooms.update', $room) : route('rooms.store')"
    :formMethod="$editing ? 'PUT' : 'POST'"
>
    {{-- Sección 1: Información Básica --}}
    <x-hci-form-section 
        :step="1" 
        title="Información Básica" 
        description="Datos principales de la sala"
        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z' clip-rule='evenodd'/></svg>"
        section-id="basica"
        :is-active="true"
        :is-first="true"
        :editing="$editing"
    >
        <div class="space-y-6">
            <x-hci-field 
                name="name"
                label="Nombre de la Sala"
                placeholder="Ej: Sala A-101, Laboratorio 1, Auditorio Principal"
                value="{{ old('name', $room->name ?? '') }}"
                :required="true"
                icon=""
                help="Nombre único que identifique la sala"
                maxlength="100"
            />

            <x-hci-field 
                name="location"
                label="Ubicación"
                placeholder="Ej: Edificio A, Piso 2, Ala Norte"
                value="{{ old('location', $room->location ?? '') }}"
                icon=""
                help="Ubicación física dentro del campus"
                maxlength="150"
            />
        </div>
    </x-hci-form-section>

    {{-- Sección 2: Detalles --}}
    <x-hci-form-section 
        :step="2" 
        title="Detalles" 
        description="Capacidad y descripción"
        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>"
        section-id="detalles"
        content-class="grid-cols-1 md:grid-cols-2 gap-6"
        :editing="$editing"
    >
        <x-hci-field 
            name="capacity"
            type="number"
            label="Capacidad"
            placeholder="Ej: 30, 50, 100"
            value="{{ old('capacity', $room->capacity ?? '') }}"
            :required="true"
            icon=""
            help="Número máximo de personas"
            min="1"
            max="1000"
        />

        <x-hci-field 
            name="description"
            type="textarea"
            label="Descripción"
            placeholder="Características especiales, equipamiento, etc."
            value="{{ old('description', $room->description ?? '') }}"
            icon=""
            help="Información adicional sobre la sala"
            rows="4"
        />
    </x-hci-form-section>

    {{-- Sección 3: Equipamiento --}}
    <x-hci-form-section 
        :step="3" 
        title="Equipamiento" 
        description="Selecciona las condiciones disponibles"
        icon="<svg class='w-8 h-8' fill='currentColor' viewBox='0 0 20 20'><path d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'/></svg>"
        section-id="equipamiento"
        content-class="w-full"
        :editing="$editing"
    >
        @php
            $condiciones = [
                'calefaccion' => 'Calefacción',
                'energia_electrica' => 'Energía Eléctrica',
                'existe_aseo' => 'Aseo Disponible',
                'plumones' => 'Plumones',
                'borrador' => 'Borrador',
                'pizarra_limpia' => 'Pizarra Limpia',
                'computador_funcional' => 'Computador Funcional',
                'cables_computador' => 'Cables del Computador',
                'control_remoto_camara' => 'Control Remoto de Cámara',
                'televisor_funcional' => 'Televisor Funcional',
            ];
        @endphp
        <div class="rounded-xl border border-[#c4dafa]/60 bg-[#fcffff] p-6 shadow-sm">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach ($condiciones as $campo => $label)
                <label class="flex items-center space-x-3 p-3 rounded-lg border border-[#c4dafa]/50 hover:bg-[#c4dafa]/20 transition-colors">
                    <input type="checkbox" name="{{ $campo }}" id="{{ $campo }}"
                        {{ old($campo, $room->$campo ?? false) ? 'checked' : '' }}
                        class="hci-checkbox">
                    <span class="text-sm text-[#005187] leading-snug">{{ $label }}</span>
                </label>
            @endforeach
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
        content-class="w-full"
        :is-last="true"
        :editing="$editing"
    >
        <div class="bg-[#c4dafa]/30 dark:bg-[#84b6f4]/10 rounded-lg p-6 border border-[#84b6f4]/30">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-lg">🏫</span>
                        <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4]">Nombre</span>
                    </div>
                    <p class="text-gray-900 dark:text-white font-medium" id="resumen-name">—</p>
                </div>
                <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-lg">📍</span>
                        <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4]">Ubicación</span>
                    </div>
                    <p class="text-gray-900 dark:text-white font-medium" id="resumen-location">—</p>
                </div>
                <div class="bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-lg">👥</span>
                        <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4]">Capacidad</span>
                    </div>
                    <p class="text-gray-900 dark:text-white font-medium" id="resumen-capacity">—</p>
                </div>
                <div class="md:col-span-2 lg:col-span-3 bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-lg">📝</span>
                        <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4]">Descripción</span>
                    </div>
                    <p class="text-gray-900 dark:text-white font-medium" id="resumen-description">—</p>
                </div>
                <div class="md:col-span-2 lg:col-span-3 bg-[#fcffff] dark:bg-gray-800 rounded-lg p-4 border border-[#84b6f4]/20">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-lg">🔧</span>
                        <span class="text-sm font-medium text-[#4d82bc] dark:text-[#84b6f4]">Equipamiento</span>
                    </div>
                    <div id="resumen-equipamiento" class="flex flex-wrap gap-2">—</div>
                </div>
            </div>

            <div class="mt-6 p-4 bg-[#fcffff] dark:bg-gray-800 rounded-lg border border-[#84b6f4]/20">
                <div class="flex items-center gap-2">
                    <span class="text-lg">ℹ️</span>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>Nota:</strong> Revisa que toda la información sea correcta antes de guardar.
                    </p>
                </div>
            </div>
        </div>
    </x-hci-form-section>
</x-hci-wizard-layout>

@push('scripts')
    @vite('resources/js/rooms-form-wizard.js')
@endpush

