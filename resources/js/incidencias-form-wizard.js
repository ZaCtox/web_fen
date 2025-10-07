// JavaScript para el formulario wizard de Incidencias
// Ley de Hick-Hyman: Navegación por pasos del formulario
let currentStep = 1;
const totalSteps = 4;

document.addEventListener('DOMContentLoaded', function() {
    const sections = document.querySelectorAll('.hci-form-section');
    const progressSteps = document.querySelectorAll('.hci-progress-step');
    const progressBar = document.getElementById('progress-bar');
    const progressPercentage = document.getElementById('progress-percentage');
    const currentStepText = document.getElementById('current-step');
    
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
    if (step <= currentStep || validateCurrentStep()) {
        currentStep = step;
        showStep(currentStep);
        updateProgress(currentStep);
    }
}

// Función para cancelar el formulario
window.cancelForm = function() {
    if (confirm('¿Estás seguro de que quieres cancelar? Se perderán todos los datos ingresados.')) {
        window.location.href = window.location.origin + '/incidencias';
    }
}

function showStep(step) {
    const sections = document.querySelectorAll('.hci-form-section');
    const progressSteps = document.querySelectorAll('.hci-progress-step-vertical');
    
    // Ocultar todas las secciones
    sections.forEach(section => {
        section.classList.remove('active');
    });
    
    // Mostrar sección actual
    const currentSection = document.getElementById(getSectionId(step));
    if (currentSection) {
        currentSection.classList.add('active');
        currentSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
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
    const sectionIds = ['basica', 'ubicacion', 'evidencia', 'resumen'];
    return sectionIds[step - 1];
}

function validateCurrentStep() {
    const currentSection = document.getElementById(getSectionId(currentStep));
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
    const titulo = document.querySelector('input[name="titulo"]')?.value || '';
    const descripcion = document.querySelector('textarea[name="descripcion"]')?.value || '';
    const sala = document.querySelector('select[name="room_id"]')?.selectedOptions?.[0]?.text || '';
    const imagen = document.querySelector('input[name="imagen"]')?.files?.[0]?.name || '';
    const ticket = document.querySelector('input[name="nro_ticket"]')?.value || '';
    
    // Actualizar elementos del resumen
    const resumenTitulo = document.getElementById('summary-titulo');
    const resumenDescripcion = document.getElementById('summary-descripcion');
    const resumenSala = document.getElementById('summary-sala');
    const resumenImagen = document.getElementById('summary-imagen');
    const resumenTicket = document.getElementById('summary-ticket');
    
    if (resumenTitulo) resumenTitulo.textContent = titulo || 'No especificado';
    if (resumenDescripcion) resumenDescripcion.textContent = descripcion || 'No especificado';
    if (resumenSala) resumenSala.textContent = sala || 'No especificado';
    if (resumenImagen) resumenImagen.textContent = imagen || 'Sin imagen';
    if (resumenTicket) resumenTicket.textContent = ticket || 'Sin ticket';
}

// Función para enviar el formulario
window.submitForm = function() {
    // Validar el paso actual antes de enviar
    if (validateCurrentStep()) {
        // Enviar el formulario
        document.querySelector('.hci-form').submit();
    }
}
