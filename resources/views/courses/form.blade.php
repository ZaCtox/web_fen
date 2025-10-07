{{-- Formulario de Cursos con Principios HCI --}}
@php($editing = isset($course))

{{-- Breadcrumb (Ley de Jakob) --}}
<x-hci-breadcrumb 
    :items="[
        ['label' => 'Inicio', 'url' => route('dashboard')],
        ['label' => 'Cursos', 'url' => route('courses.index')],
        ['label' => $editing ? 'Editar Curso' : 'Nuevo Curso', 'url' => '#']
    ]"
/>

{{-- Contenedor principal con principios HCI --}}
<div class="hci-container">
    <div class="hci-section">
        <h1 class="hci-heading-1">
            {{ $editing ? '✏️ Editar Curso' : '➕ Nuevo Curso' }}
        </h1>
        <p class="hci-text">
            {{ $editing ? 'Modifica la información del curso académico.' : 'Crea un nuevo curso con información organizada.' }}
        </p>
    </div>

    <form class="hci-form" method="POST" action="{{ $editing ? route('courses.update', $course) : route('courses.store') }}">
        @csrf
        @if($editing) @method('PUT') @endif

        {{-- Grupo de campos (Ley de Miller - máximo 4 campos) --}}
        <x-hci-form-group 
            title="Información del Curso" 
            description="Datos básicos del curso académico"
            icon="📚"
            :columns="2"
            variant="info"
        >
            <x-hci-field 
                name="nombre"
                label="Nombre del Curso"
                placeholder="Ej: Economía Aplicada"
                value="{{ old('nombre', $course->nombre ?? '') }}"
                :required="true"
                icon="📚"
                help="Nombre descriptivo del curso"
                maxlength="150"
            />

            <x-hci-field 
                name="magister_id"
                type="select"
                label="Programa"
                :required="true"
                icon="🎓"
                help="Programa académico al que pertenece el curso"
            >
                <option value="">-- Selecciona un Programa --</option>
                @foreach($magisters as $magister)
                    <option value="{{ $magister->id }}" {{ old('magister_id', $course->magister_id ?? $selectedMagisterId ?? '') == $magister->id ? 'selected' : '' }}>
                        {{ $magister->nombre }}
                    </option>
                @endforeach
            </x-hci-field>
        </x-hci-form-group>

        <x-hci-form-group 
            title="Período Académico" 
            description="Define el período en que se impartirá el curso"
            icon="📅"
            :columns="2"
            variant="success"
        >
            <x-hci-field 
                name="anio"
                type="select"
                label="Año"
                icon="📅"
                help="Año académico del curso"
            >
                <option value="">-- Selecciona un año --</option>
                @foreach ($periods->pluck('anio')->unique() as $anio)
                    <option value="{{ $anio }}">{{ $anio }}</option>
                @endforeach
            </x-hci-field>

            <x-hci-field 
                name="numero"
                type="select"
                label="Trimestre"
                icon="📊"
                help="Trimestre académico"
            >
                <option value="">-- Selecciona un trimestre --</option>
                {{-- Se llenará dinámicamente con JS --}}
            </x-hci-field>
        </x-hci-form-group>

        {{-- Hidden field para period --}}
        <input type="hidden" name="period_id" id="period_id" value="{{ old('period_id', $course->period_id ?? '') }}">

        {{-- Información progresiva (Ley de Miller) --}}
        <div class="hci-progressive-disclosure">
            <div class="hci-progressive-section">
                <div class="hci-progressive-header" onclick="toggleSection(this)">
                    <span class="font-medium">📝 Información Adicional (Opcional)</span>
                    <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
                <div class="hci-progressive-content">
                    <x-hci-form-group 
                        title="Detalles del Curso" 
                        :columns="2"
                    >
                        <x-hci-field 
                            name="codigo"
                            label="Código del Curso"
                            placeholder="Ej: ECO-101"
                            value="{{ old('codigo', $course->codigo ?? '') }}"
                            icon="🔢"
                            help="Código único del curso"
                            maxlength="20"
                        />

                        <x-hci-field 
                            name="creditos"
                            type="number"
                            label="Créditos"
                            placeholder="Ej: 3"
                            value="{{ old('creditos', $course->creditos ?? '') }}"
                            icon="⭐"
                            help="Número de créditos académicos"
                            min="1"
                            max="10"
                        />
                    </x-hci-form-group>
                </div>
            </div>
        </div>

        {{-- Navegación (Ley de Jakob) --}}
        <div class="flex justify-between items-center mt-8">
            <x-hci-button 
                href="{{ route('courses.index') }}" 
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
                {{ $editing ? 'Actualizar Curso' : 'Crear Curso' }}
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