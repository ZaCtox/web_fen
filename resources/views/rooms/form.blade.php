{{-- Formulario de Salas con Principios HCI --}}
@section('title', 'Formulario Salas')

@php($editing = isset($room))

{{-- Breadcrumb (Ley de Jakob) --}}
<x-hci-breadcrumb 
    :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Salas', 'url' => route('rooms.index')],
        ['label' => $editing ? 'Editar Sala' : 'Nueva Sala', 'url' => '#']
    ]"
/>

{{-- Contenedor principal con principios HCI --}}
<div class="hci-container">
    <div class="hci-section">
        <h1 class="hci-heading-1">
            {{ $editing ? '✏️ Editar Sala' : '➕ Nueva Sala' }}
        </h1>
        <p class="hci-text">
            {{ $editing ? 'Modifica la información de la sala.' : 'Registra una nueva sala con información organizada.' }}
        </p>
    </div>

    <form class="hci-form" method="POST" action="{{ isset($room) ? route('rooms.update', $room) : route('rooms.store') }}">
        @csrf
        @if(isset($room)) @method('PUT') @endif

        {{-- Grupo de campos (Ley de Miller - máximo 4 campos) --}}
        <x-hci-form-group 
            title="Información Básica de la Sala" 
            description="Datos principales de la sala"
            icon="🏫"
            :columns="2"
            variant="info"
        >
            <x-hci-field 
                name="name"
                label="Nombre de la Sala"
                placeholder="Ej: Sala A-101, Laboratorio 1, Auditorio Principal"
                value="{{ old('name', $room->name ?? '') }}"
                :required="true"
                icon="🏫"
                help="Nombre único que identifique la sala"
                maxlength="100"
            />

            <x-hci-field 
                name="location"
                label="Ubicación"
                placeholder="Ej: Edificio A, Piso 2, Ala Norte"
                value="{{ old('location', $room->location ?? '') }}"
                icon="📍"
                help="Ubicación física dentro del campus"
                maxlength="150"
            />
        </x-hci-form-group>

        <x-hci-form-group 
            title="Capacidad y Descripción" 
            description="Detalles adicionales de la sala"
            icon="👥"
            :columns="2"
            variant="success"
        >
            <x-hci-field 
                name="capacity"
                type="number"
                label="Capacidad"
                placeholder="Ej: 30, 50, 100"
                value="{{ old('capacity', $room->capacity ?? '') }}"
                :required="true"
                icon="👥"
                help="Número máximo de personas que puede albergar"
                min="1"
                max="1000"
            />

            <x-hci-field 
                name="description"
                type="textarea"
                label="Descripción"
                placeholder="Describe características especiales, equipamiento, o cualquier información relevante..."
                value="{{ old('description', $room->description ?? '') }}"
                icon="📝"
                help="Información adicional sobre la sala"
                rows="3"
            />
        </x-hci-form-group>

        {{-- Información progresiva (Ley de Miller) --}}
        <div class="hci-progressive-disclosure">
            <div class="hci-progressive-section">
                <div class="hci-progressive-header" onclick="toggleSection(this)">
                    <span class="font-medium">⚙️ Condiciones de la Sala (Opcional)</span>
                    <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
                <div class="hci-progressive-content">
                    <x-hci-form-group 
                        title="Estado y Características" 
                        :columns="2"
                    >
                        <x-hci-field 
                            name="status"
                            type="select"
                            label="Estado de la Sala"
                            icon="🟢"
                            help="Estado actual de la sala"
                        >
                            <option value="disponible" {{ old('status', $room->status ?? '') == 'disponible' ? 'selected' : '' }}>
                                🟢 Disponible
                            </option>
                            <option value="ocupada" {{ old('status', $room->status ?? '') == 'ocupada' ? 'selected' : '' }}>
                                🔴 Ocupada
                            </option>
                            <option value="mantenimiento" {{ old('status', $room->status ?? '') == 'mantenimiento' ? 'selected' : '' }}>
                                🟡 En Mantenimiento
                            </option>
                            <option value="fuera_servicio" {{ old('status', $room->status ?? '') == 'fuera_servicio' ? 'selected' : '' }}>
                                ⚫ Fuera de Servicio
                            </option>
                        </x-hci-field>

                        <x-hci-field 
                            name="type"
                            type="select"
                            label="Tipo de Sala"
                            icon="🏛️"
                            help="Tipo de sala según su uso"
                        >
                            <option value="aula" {{ old('type', $room->type ?? '') == 'aula' ? 'selected' : '' }}>
                                🏫 Aula
                            </option>
                            <option value="laboratorio" {{ old('type', $room->type ?? '') == 'laboratorio' ? 'selected' : '' }}>
                                🔬 Laboratorio
                            </option>
                            <option value="auditorio" {{ old('type', $room->type ?? '') == 'auditorio' ? 'selected' : '' }}>
                                🎭 Auditorio
                            </option>
                            <option value="sala_reunion" {{ old('type', $room->type ?? '') == 'sala_reunion' ? 'selected' : '' }}>
                                🤝 Sala de Reunión
                            </option>
                        </x-hci-field>
                    </x-hci-form-group>

                    {{-- Checkboxes para condiciones --}}
                    <div class="mt-4">
                        <h4 class="hci-heading-4 mb-3">🔧 Equipamiento y Condiciones</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @php
                                $condiciones = [
                                    'calefaccion' => '🔥 Calefacción',
                                    'energia_electrica' => '⚡ Energía Eléctrica',
                                    'existe_aseo' => '🚻 Aseo Disponible',
                                    'plumones' => '🖊️ Plumones',
                                    'borrador' => '🧽 Borrador',
                                    'pizarra_limpia' => '📝 Pizarra Limpia',
                                    'computador_funcional' => '💻 Computador Funcional',
                                    'cables_computador' => '🔌 Cables del Computador',
                                    'control_remoto_camara' => '📹 Control Remoto de Cámara',
                                    'televisor_funcional' => '📺 Televisor Funcional',
                                ];
                            @endphp

                            @foreach ($condiciones as $campo => $label)
                                <label class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <input type="checkbox" name="{{ $campo }}" id="{{ $campo }}"
                                        {{ old($campo, $room->$campo ?? false) ? 'checked' : '' }}
                                        class="hci-checkbox">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Navegación (Ley de Jakob) --}}
        <div class="flex justify-between items-center mt-8">
            <x-hci-button 
                href="{{ route('rooms.index') }}" 
                variant="secondary" 
                icon="⬅️"
                icon-position="left"
            >
                Volver
            </x-hci-button>
            
            <x-hci-button 
                type="submit" 
                variant="primary" 
                icon="💾"
                icon-position="left"
            >
                {{ $editing ? 'Actualizar Sala' : 'Crear Sala' }}
            </x-hci-button>
        </div>
    </form>
</div>

{{-- FAB para ayuda (Ley de Fitts) --}}
<x-hci-button 
    fab="true" 
    icon="❓"
    href="#"
    aria-label="Ayuda con el formulario"
/>

<script>
// Funcionalidad para información progresiva
function toggleSection(header) {
    const section = header.parentElement;
    const isExpanded = section.classList.contains('expanded');
    
    if (isExpanded) {
        section.classList.remove('expanded');
        header.querySelector('svg').style.transform = 'rotate(0deg)';
    } else {
        section.classList.add('expanded');
        header.querySelector('svg').style.transform = 'rotate(180deg)';
    }
}

// Validación en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.hci-input, .hci-select, .hci-textarea');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            clearFieldError(this);
        });
    });
});

function validateField(field) {
    const isValid = field.checkValidity();
    const fieldContainer = field.closest('.hci-field');
    const errorElement = fieldContainer?.querySelector('.hci-field-error');
    
    if (!isValid) {
        field.classList.add('border-red-500');
        field.classList.remove('border-gray-300');
        
        if (!errorElement) {
            const error = document.createElement('div');
            error.className = 'hci-field-error';
            error.textContent = field.validationMessage || 'Este campo es requerido';
            fieldContainer?.appendChild(error);
        }
    } else {
        clearFieldError(field);
    }
}

function clearFieldError(field) {
    field.classList.remove('border-red-500');
    field.classList.add('border-gray-300');
    
    const fieldContainer = field.closest('.hci-field');
    const errorElement = fieldContainer?.querySelector('.hci-field-error');
    if (errorElement) {
        errorElement.remove();
    }
}
</script>



