{{-- Demo de Principios HCI --}}
@section('title', 'Demo - Principios HCI')

{{-- Breadcrumb (Ley de Jakob) --}}
<div class="py-4">
    <x-hci-breadcrumb 
        :items="[
            ['label' => 'Dashboard', 'url' => route('dashboard'), 'icon' => '📊'],
            ['label' => 'Demo HCI', 'url' => '#', 'icon' => '🧠']
        ]"
    />
</div>

{{-- Contenedor principal con principios HCI --}}
<div class="hci-container">
    <div class="hci-section">
        <h1 class="hci-heading-1">🧠 Demo de Principios HCI</h1>
        <p class="hci-text">
            Demostración de los principios de HCI (Human-Computer Interaction) implementados en la plataforma.
        </p>
    </div>

    {{-- Ley de Hick-Hyman: Menús simplificados --}}
    <div class="hci-card">
        <div class="hci-card-header">
            <h2 class="hci-heading-2">🎯 Ley de Hick-Hyman: Menús Simplificados</h2>
        </div>
        <div class="hci-card-content">
            <p class="hci-text mb-4">
                Menús con máximo 5 opciones para reducir la carga cognitiva.
            </p>
            <nav class="hci-menu">
                <a href="#" class="hci-menu-item active">
                    <i class="hci-menu-icon">📊</i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="hci-menu-item">
                    <i class="hci-menu-icon">👥</i>
                    <span>Usuarios</span>
                </a>
                <a href="#" class="hci-menu-item">
                    <i class="hci-menu-icon">📚</i>
                    <span>Cursos</span>
                </a>
                <a href="#" class="hci-menu-item">
                    <i class="hci-menu-icon">🏫</i>
                    <span>Salas</span>
                </a>
                <a href="#" class="hci-menu-item">
                    <i class="hci-menu-icon">🎓</i>
                    <span>Clases</span>
                </a>
            </nav>
        </div>
    </div>

    {{-- Ley de Fitts: Botones grandes --}}
    <div class="hci-card">
        <div class="hci-card-header">
            <h2 class="hci-heading-2">🎯 Ley de Fitts: Botones Grandes y Accesibles</h2>
        </div>
        <div class="hci-card-content">
            <p class="hci-text mb-4">
                Botones con mínimo 48px de altura para facilitar la interacción.
            </p>
            <div class="flex flex-wrap gap-4">
                <x-hci-button variant="primary" icon="💾" icon-position="left">
                    Guardar
                </x-hci-button>
                <x-hci-button variant="secondary" icon="✏️" icon-position="left">
                    Editar
                </x-hci-button>
                <x-hci-button variant="success" icon="✅" icon-position="left">
                    Aprobar
                </x-hci-button>
                <x-hci-button variant="danger" icon="❌" icon-position="left">
                    Eliminar
                </x-hci-button>
            </div>
        </div>
    </div>

    {{-- Ley de Miller: Agrupación de campos --}}
    <div class="hci-card">
        <div class="hci-card-header">
            <h2 class="hci-heading-2">🎯 Ley de Miller: Agrupación en Chunks</h2>
        </div>
        <div class="hci-card-content">
            <p class="hci-text mb-4">
                Campos agrupados en chunks de 3-4 elementos para facilitar el procesamiento.
            </p>
            
            <x-hci-form-group 
                title="Información Personal" 
                description="Datos básicos del usuario"
                icon="👤"
                :columns="2"
                variant="info"
            >
                <x-hci-field 
                    name="demo_nombre"
                    label="Nombre Completo"
                    placeholder="Ej: Juan Pérez"
                    icon="👤"
                    help="Nombre completo del usuario"
                />
                <x-hci-field 
                    name="demo_email"
                    type="email"
                    label="Correo Electrónico"
                    placeholder="ejemplo@correo.com"
                    icon="📧"
                    help="Correo electrónico válido"
                />
            </x-hci-form-group>

            <x-hci-form-group 
                title="Información de Contacto" 
                description="Datos de contacto"
                icon="📞"
                :columns="2"
                variant="success"
            >
                <x-hci-field 
                    name="demo_telefono"
                    label="Teléfono"
                    placeholder="+56 9 12345678"
                    icon="📱"
                    help="Número de teléfono"
                />
                <x-hci-field 
                    name="demo_direccion"
                    label="Dirección"
                    placeholder="Calle 123, Ciudad"
                    icon="📍"
                    help="Dirección completa"
                />
            </x-hci-form-group>
        </div>
    </div>

    {{-- Ley de Prägnanz: Diseño limpio --}}
    <div class="hci-card">
        <div class="hci-card-header">
            <h2 class="hci-heading-2">🎯 Ley de Prägnanz: Diseño Limpio y Minimalista</h2>
        </div>
        <div class="hci-card-content">
            <p class="hci-text mb-4">
                Diseño limpio con alineación consistente y jerarquía visual clara.
            </p>
            
            <div class="hci-grid cols-3 gap-6">
                <div class="hci-card">
                    <div class="hci-card-content">
                        <h3 class="hci-heading-3">📊 Estadísticas</h3>
                        <p class="hci-text-small">Información estadística del sistema</p>
                    </div>
                </div>
                <div class="hci-card">
                    <div class="hci-card-content">
                        <h3 class="hci-heading-3">👥 Usuarios</h3>
                        <p class="hci-text-small">Gestión de usuarios del sistema</p>
                    </div>
                </div>
                <div class="hci-card">
                    <div class="hci-card-content">
                        <h3 class="hci-heading-3">📚 Cursos</h3>
                        <p class="hci-text-small">Administración de cursos</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Ley de Jakob: Patrones familiares --}}
    <div class="hci-card">
        <div class="hci-card-header">
            <h2 class="hci-heading-2">🎯 Ley de Jakob: Patrones Familiares</h2>
        </div>
        <div class="hci-card-content">
            <p class="hci-text mb-4">
                Patrones de navegación familiares y reconocibles.
            </p>
            
            <div class="hci-progressive-disclosure">
                <div class="hci-progressive-section">
                    <div class="hci-progressive-header" onclick="toggleSection(this)">
                        <span class="font-medium">📝 Información Progresiva</span>
                        <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="hci-progressive-content">
                        <p class="hci-text">
                            Esta sección se expande para mostrar información adicional cuando el usuario la necesita.
                            Esto reduce la carga cognitiva inicial y permite una exploración progresiva.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FAB para ayuda (Ley de Fitts) --}}
    <x-hci-button 
        fab="true" 
        icon="❓"
        href="#"
        aria-label="Ayuda con el demo"
    />
</div>

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



