// ================================
// 🧠 Principios de HCI - JavaScript
// Implementación de Leyes de Hick-Hyman, Fitts, Miller, Prägnanz, Jakob
// ================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('🧠 HCI Principles JS loaded successfully!');
    
    // ================================
    // 🎯 Ley de Hick-Hyman: Menús simplificados
    // ================================
    initializeHciMenus();
    
    // ================================
    // 🎯 Ley de Fitts: Botones grandes y accesibles
    // ================================
    initializeHciButtons();
    
    // ================================
    // 🎯 Ley de Miller: Información progresiva
    // ================================
    initializeProgressiveDisclosure();
    
    // ================================
    // 🎯 Ley de Jakob: Validación familiar
    // ================================
    initializeFormValidation();
    
    // ================================
    // 🎯 Ley de Prägnanz: Animaciones suaves
    // ================================
    initializeAnimations();
});

// ================================
// 🎯 Ley de Hick-Hyman: Menús simplificados
// ================================
function initializeHciMenus() {
    const hciMenuItems = document.querySelectorAll('.hci-menu-item');
    
    hciMenuItems.forEach(item => {
        // Activar elemento actual
        if (item.href === window.location.href) {
            item.classList.add('active');
        }
        
        // Manejar clics
        item.addEventListener('click', function(e) {
            // Remover activo de todos los elementos
            hciMenuItems.forEach(i => i.classList.remove('active'));
            // Activar elemento clickeado
            this.classList.add('active');
        });
        
        // Efecto hover mejorado
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(4px)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
}

// ================================
// 🎯 Ley de Fitts: Botones grandes y accesibles
// ================================
function initializeHciButtons() {
    const hciButtons = document.querySelectorAll('.hci-button');
    
    hciButtons.forEach(button => {
        // Asegurar tamaño mínimo
        if (button.offsetHeight < 48) {
            button.style.minHeight = '48px';
        }
        if (button.offsetWidth < 48) {
            button.style.minWidth = '48px';
        }
        
        // Efectos de hover mejorados
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '';
        });
        
        // Feedback táctil
        button.addEventListener('mousedown', function() {
            this.style.transform = 'translateY(0)';
        });
        
        button.addEventListener('mouseup', function() {
            this.style.transform = 'translateY(-2px)';
        });
    });
}

// ================================
// 🎯 Ley de Miller: Información progresiva
// ================================
function initializeProgressiveDisclosure() {
    const progressiveHeaders = document.querySelectorAll('.hci-progressive-header');
    
    progressiveHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const section = this.parentElement;
            const content = section.querySelector('.hci-progressive-content');
            const icon = this.querySelector('svg');
            
            if (section.classList.contains('expanded')) {
                // Colapsar
                section.classList.remove('expanded');
                content.style.display = 'none';
                if (icon) {
                    icon.style.transform = 'rotate(0deg)';
                }
            } else {
                // Expandir
                section.classList.add('expanded');
                content.style.display = 'block';
                if (icon) {
                    icon.style.transform = 'rotate(180deg)';
                }
                
                // Animación suave
                content.style.opacity = '0';
                content.style.transform = 'translateY(-10px)';
                
                setTimeout(() => {
                    content.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    content.style.opacity = '1';
                    content.style.transform = 'translateY(0)';
                }, 10);
            }
        });
    });
}

// ================================
// 🎯 Ley de Jakob: Validación familiar
// ================================
function initializeFormValidation() {
    const formInputs = document.querySelectorAll('.hci-input, .hci-select, .hci-textarea');
    
    formInputs.forEach(input => {
        // Validación en tiempo real
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            clearFieldError(this);
        });
        
        // Validación en tiempo real para campos requeridos
        if (input.hasAttribute('required')) {
            input.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    this.classList.remove('border-red-500');
                    this.classList.add('border-green-500');
                } else {
                    this.classList.remove('border-green-500');
                }
            });
        }
    });
}

// Función para validar campos
function validateField(field) {
    const isValid = field.checkValidity();
    const fieldContainer = field.closest('.hci-field');
    const errorElement = fieldContainer?.querySelector('.hci-field-error');
    
    if (!isValid) {
        field.classList.add('border-red-500');
        field.classList.remove('border-gray-300', 'border-green-500');
        
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

// Función para limpiar errores
function clearFieldError(field) {
    field.classList.remove('border-red-500');
    field.classList.add('border-gray-300');
    
    const fieldContainer = field.closest('.hci-field');
    const errorElement = fieldContainer?.querySelector('.hci-field-error');
    if (errorElement) {
        errorElement.remove();
    }
}

// ================================
// 🎯 Ley de Prägnanz: Animaciones suaves
// ================================
function initializeAnimations() {
    // Animación de entrada para elementos HCI
    const hciElements = document.querySelectorAll('.hci-form-group, .hci-card, .hci-button');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('hci-fade-in');
            }
        });
    }, {
        threshold: 0.1
    });
    
    hciElements.forEach(element => {
        observer.observe(element);
    });
}

// ================================
// 🎯 Funciones utilitarias HCI
// ================================

// Función para actualizar resumen en tiempo real
function updateFormSummary() {
    const inputs = document.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('input', updateSummary);
        input.addEventListener('change', updateSummary);
    });
    
    updateSummary(); // Actualizar al cargar
}

function updateSummary() {
    // Esta función se personalizará según el formulario específico
    console.log('📊 Summary updated');
}

// Función para mostrar notificaciones HCI
function showHciNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 hci-${type} hci-fade-in`;
    
    const icon = type === 'success' ? '✅' : type === 'error' ? '❌' : type === 'warning' ? '⚠️' : 'ℹ️';
    notification.innerHTML = `
        <div class="flex items-center space-x-2">
            <span class="text-lg">${icon}</span>
            <span class="font-medium">${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remover después de 3 segundos
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Función para manejar envío de formularios HCI
function handleHciFormSubmit(form) {
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    
    // Mostrar estado de carga
    submitButton.disabled = true;
    submitButton.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Procesando...
    `;
    
    // Simular envío (esto se reemplazará con la lógica real)
    setTimeout(() => {
        submitButton.disabled = false;
        submitButton.textContent = originalText;
        showHciNotification('Formulario enviado exitosamente', 'success');
    }, 2000);
}

// ================================
// 🎯 Inicialización global
// ================================
window.HCI = {
    validateField,
    clearFieldError,
    updateFormSummary,
    showHciNotification,
    handleHciFormSubmit
};

// ================================
// 🎯 Event listeners globales
// ================================
document.addEventListener('DOMContentLoaded', function() {
    // Manejar envío de formularios HCI
    const hciForms = document.querySelectorAll('.hci-form');
    hciForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            handleHciFormSubmit(this);
        });
    });
    
    // Manejar clics en botones de navegación
    const wizardNextButtons = document.querySelectorAll('[data-wizard-next]');
    const wizardPrevButtons = document.querySelectorAll('[data-wizard-prev]');
    
    wizardNextButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Lógica de navegación del wizard
            console.log('➡️ Next step');
        });
    });
    
    wizardPrevButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Lógica de navegación del wizard
            console.log('⬅️ Previous step');
        });
    });
});

console.log('🧠 HCI Principles JavaScript initialized successfully!');
