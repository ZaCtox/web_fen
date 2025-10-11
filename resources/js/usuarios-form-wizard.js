// JavaScript para el formulario wizard de Usuarios
// Ley de Hick-Hyman: Navegación por pasos del formulario
let currentStep = 1;
let totalSteps = 3; // Se ajustará dinámicamente según si es edición o registro

document.addEventListener('DOMContentLoaded', function() {
    // Solo buscar errores de validación de Laravel, no errores de UI
    const laravelErrors = document.querySelector('.hci-form-section .hci-field-error');
    if (laravelErrors) {
        const errorSection = laravelErrors.closest('.hci-form-section');
        if (errorSection && errorSection.dataset.step) {
            const errorStep = parseInt(errorSection.dataset.step) || 1;
            currentStep = errorStep;
        }
    }
    
    // Detectar si estamos en modo edición
    const isEditing = document.querySelector('form').action.includes('usuarios/');
    totalSteps = isEditing ? 2 : 3;
    
    // En modo edición, ocultar el paso 3
    if (isEditing) {
        const step3Element = document.querySelector('[data-step="3"]');
        if (step3Element) {
            step3Element.style.display = 'none';
        }
    }
    
    // Inicializar formulario
    showStep(currentStep);
    updateProgress(currentStep);
    
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
        showStep(currentStep);
        updateProgress(currentStep);
    }
}

// Navegación directa por clic en el progreso lateral
window.navigateToStep = function(step) {
    const isEditing = document.querySelector('form').action.includes('usuarios/');
    
    // En modo edición, no permitir navegar al paso 3 (no existe)
    if (isEditing && step === 3) {
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
    // Ocultar todas las secciones
    const sections = document.querySelectorAll('.hci-form-section');
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
    
    // Usar el helper global para actualizar el progreso visual
    if (window.updateWizardProgressSteps) {
        window.updateWizardProgressSteps(step);
    }
    
    // Si estamos en el paso del resumen, actualizar el contenido
    if (getSectionId(step) === 'resumen') {
        updateSummary();
    }
}

function updateProgress(step) {
    const progressBar = document.getElementById('progress-bar');
    const progressPercentage = document.getElementById('progress-percentage');
    const currentStepText = document.getElementById('current-step');
    
    const percentage = (step / totalSteps) * 100;
    
    if (progressBar) progressBar.style.height = percentage + '%';
    if (progressPercentage) progressPercentage.textContent = Math.round(percentage) + '%';
    if (currentStepText) currentStepText.textContent = `Paso ${step} de ${totalSteps}`;
    
    // Controlar visibilidad de botones
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const submitBtn = document.getElementById('submit-btn');
    
    if (prevBtn) prevBtn.style.display = step > 1 ? 'flex' : 'none';
    if (nextBtn) nextBtn.style.display = step < totalSteps ? 'flex' : 'none';
    if (submitBtn) submitBtn.style.display = step === totalSteps ? 'flex' : 'none';
}

function getSectionId(step) {
    // Detectar si estamos en modo edición
    const isEditing = document.querySelector('form').action.includes('usuarios/');
    
    if (isEditing) {
        // En edición: personal (con rol incluido), resumen (2 pasos)
        const sectionIds = ['personal', 'resumen'];
        return sectionIds[step - 1];
    } else {
        // En registro: personal (con rol incluido), notificacion, resumen (3 pasos)
        const sectionIds = ['personal', 'notificacion', 'resumen'];
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
    if (validateCurrentStep()) {
        // Mostrar overlay de loading global
        if (!document.getElementById('form-loading-overlay')) {
            const overlay = document.createElement('div');
            overlay.id = 'form-loading-overlay';
            overlay.className = 'loading-overlay';
            overlay.innerHTML = `
                <div class="loading-overlay-content">
                    <div class="inline-block w-12 h-12 animate-spin rounded-full border-4 border-solid border-[#4d82bc] border-r-transparent"></div>
                    <p class="text-gray-700 dark:text-gray-300 font-medium">Procesando...</p>
                </div>
            `;
            document.body.appendChild(overlay);
        }
        
        // Submit del formulario
        document.querySelector('.hci-form').submit();
    }
}