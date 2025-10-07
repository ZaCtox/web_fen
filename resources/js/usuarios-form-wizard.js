// JavaScript para el formulario wizard de Usuarios
// Ley de Hick-Hyman: Navegación por pasos del formulario
let currentStep = 1;
let totalSteps = 4; // Se ajustará dinámicamente según si es edición o registro

document.addEventListener('DOMContentLoaded', function() {
    const sections = document.querySelectorAll('.hci-form-section');
    const progressSteps = document.querySelectorAll('.hci-progress-step');
    const progressBar = document.getElementById('progress-bar');
    const progressPercentage = document.getElementById('progress-percentage');
    const currentStepText = document.getElementById('current-step');
    
    // Detectar si estamos en modo edición
    const isEditing = document.querySelector('form').action.includes('usuarios/');
    totalSteps = isEditing ? 3 : 4;
    
    // En modo edición, desactivar el paso 3 en el progreso lateral
    if (isEditing) {
        const step3Element = document.querySelector('[data-step="3"]');
        if (step3Element) {
            step3Element.style.opacity = '0.5';
            step3Element.style.pointerEvents = 'none';
            step3Element.style.cursor = 'not-allowed';
            
            // Agregar mensaje de que no hay cambios
            const step3Content = step3Element.querySelector('.hci-progress-step-content-vertical');
            if (step3Content) {
                const originalTitle = step3Content.querySelector('.hci-progress-step-title');
                if (originalTitle) {
                    originalTitle.textContent = 'Sin cambios (Edición)';
                }
                const originalDesc = step3Content.querySelector('.hci-progress-step-desc');
                if (originalDesc) {
                    originalDesc.textContent = 'No aplica en edición';
                }
            }
        }
    }
    
    // Inicializar formulario
    showStep(1);
    updateProgress(1);
    
    // Validación en tiempo real
    const inputs = document.querySelectorAll('.hci-input, .hci-select, .hci-textarea');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            clearFieldError(this);
            updateSummary(); // Actualizar resumen en tiempo real
        });
    });
});

// Navegación entre pasos
window.nextStep = function() {
    if (validateCurrentStep()) {
        if (currentStep < totalSteps) {
            currentStep++;
            
            // En modo edición, saltar el paso 3 (adicional) y ir directo al resumen
            const isEditing = document.querySelector('form').action.includes('usuarios/');
            if (isEditing && currentStep === 3) {
                currentStep = 4; // Saltar al resumen
            }
            
            showStep(currentStep);
            updateProgress(currentStep);
        }
    }
}

window.prevStep = function() {
    if (currentStep > 1) {
        currentStep--;
        
        // En modo edición, si estamos en el resumen (paso 4), ir al paso 2 (contacto)
        const isEditing = document.querySelector('form').action.includes('usuarios/');
        if (isEditing && currentStep === 3) {
            currentStep = 2; // Saltar el paso 3 y ir al paso 2
        }
        
        showStep(currentStep);
        updateProgress(currentStep);
    }
}

// Navegación directa por clic en el progreso lateral
window.navigateToStep = function(step) {
    const isEditing = document.querySelector('form').action.includes('usuarios/');
    
    // En modo edición, si se intenta ir al paso 3, ir al paso 4 (resumen)
    if (isEditing && step === 3) {
        showStepNotification('En modo edición, el paso 3 no es necesario. Redirigiendo al resumen...');
        setTimeout(() => {
            navigateToStep(4);
        }, 1000);
        return;
    }
    
    if (step <= currentStep || validateCurrentStep()) {
        currentStep = step;
        showStep(currentStep);
        updateProgress(currentStep);
    }
}

// Función para cancelar el formulario
window.cancelForm = function() {
    if (window.hasUnsavedChanges && window.hasUnsavedChanges()) {
        window.showUnsavedChangesModal(window.location.origin + '/usuarios');
    } else {
        window.location.href = window.location.origin + '/usuarios';
    }
}

function showStep(step) {
    const sections = document.querySelectorAll('.hci-form-section');
    const progressSteps = document.querySelectorAll('.hci-progress-step-vertical');
    
    // Detectar si estamos en modo edición
    const isEditing = document.querySelector('form').action.includes('usuarios/');
    
    // En modo edición, si se intenta ir al paso 3, redirigir al paso 4 y mostrar notificación
    if (isEditing && step === 3) {
        showStep(4); // Ir al resumen
        showStepNotification('Este paso no es necesario en modo edición. Se ha redirigido al resumen.');
        return;
    }
    
    // Ocultar todas las secciones
    sections.forEach(section => {
        section.classList.remove('active');
    });
    
    // Mostrar sección actual
    const currentSection = document.getElementById(getSectionId(step));
    if (currentSection) {
        currentSection.classList.add('active');
        currentSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } else {
        console.warn(`No se encontró la sección para el paso ${step}: ${getSectionId(step)}`);
    }
    
    // Actualizar pasos del progreso vertical
    progressSteps.forEach((progressStep, index) => {
        if (index + 1 <= step) {
            progressStep.classList.add('completed');
            progressStep.classList.add('active');
        } else {
            progressStep.classList.remove('completed');
            progressStep.classList.remove('active');
        }
    });
}

function updateProgress(step) {
    const progressBar = document.getElementById('progress-bar');
    const progressPercentage = document.getElementById('progress-percentage');
    const currentStepText = document.getElementById('current-step');
    
    const percentage = (step / totalSteps) * 100;
    
    if (progressBar) progressBar.style.height = percentage + '%';
    if (progressPercentage) progressPercentage.textContent = Math.round(percentage) + '%';
    if (currentStepText) currentStepText.textContent = `Paso ${step} de ${totalSteps}`;
}

function getSectionId(step) {
    // Detectar si estamos en modo edición
    const isEditing = document.querySelector('form').action.includes('usuarios/');
    
    if (isEditing) {
        // En edición: personal, contacto, resumen (3 pasos)
        // Paso 1: personal, Paso 2: contacto, Paso 3: resumen
        const sectionIds = ['personal', 'contacto', 'resumen'];
        return sectionIds[step - 1];
    } else {
        // En registro: personal, contacto, adicional, resumen (4 pasos)
        const sectionIds = ['personal', 'contacto', 'adicional', 'resumen'];
        return sectionIds[step - 1];
    }
}

function validateCurrentStep() {
    const currentSection = document.getElementById(getSectionId(currentStep));
    
    // Si no se encuentra la sección, no validar
    if (!currentSection) {
        return true;
    }
    
    const requiredFields = currentSection.querySelectorAll('input[required], select[required], textarea[required]');
    
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            validateField(field);
            isValid = false;
        } else {
            clearFieldError(field);
        }
    });
    
    if (!isValid) {
        // Mostrar mensaje de error
        showStepError('Por favor, completa todos los campos requeridos antes de continuar.');
    }
    
    return isValid;
}

function showStepError(message) {
    // Crear o actualizar mensaje de error
    let errorDiv = document.getElementById('step-error-message');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.id = 'step-error-message';
        errorDiv.className = 'hci-error-message';
        document.querySelector('.hci-container').insertBefore(errorDiv, document.querySelector('.hci-wizard-layout'));
    }
    
    errorDiv.innerHTML = `
        <div class="hci-error-content">
            <span class="hci-error-icon">⚠️</span>
            <span class="hci-error-text">${message}</span>
        </div>
    `;
    
    // Ocultar mensaje después de 5 segundos
    setTimeout(() => {
        if (errorDiv) errorDiv.remove();
    }, 5000);
}

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

// Función para actualizar el resumen en tiempo real
function updateSummary() {
    const name = document.querySelector('input[name="name"]')?.value || '';
    const email = document.querySelector('input[name="email"]')?.value || '';
    const rol = document.querySelector('select[name="rol"]')?.value || '';
    
    // Detectar si estamos en modo edición
    const isEditing = document.querySelector('form').action.includes('usuarios/');
    
    // Actualizar elementos del resumen
    const resumenName = document.getElementById('summary-name');
    const resumenEmail = document.getElementById('summary-email');
    const resumenRol = document.getElementById('summary-rol');
    const resumenPassword = document.getElementById('summary-password');
    
    if (resumenName) resumenName.textContent = name || 'No especificado';
    if (resumenEmail) resumenEmail.textContent = email || 'No especificado';
    if (resumenRol) resumenRol.textContent = rol || 'No especificado';
    
    if (resumenPassword) {
        if (isEditing) {
            resumenPassword.textContent = 'No se modifica';
        } else {
            resumenPassword.textContent = 'Se generará automáticamente';
        }
    }
    
    // Actualizar notificación de correo (solo en registro)
    const emailNotification = document.getElementById('email-notification');
    if (emailNotification) {
        emailNotification.textContent = email || 'el correo especificado';
    }
}

// Función para enviar el formulario
window.submitForm = function() {
    // Validar el paso actual antes de enviar
    if (validateCurrentStep()) {
        // Enviar el formulario
        document.querySelector('.hci-form').submit();
    }
}