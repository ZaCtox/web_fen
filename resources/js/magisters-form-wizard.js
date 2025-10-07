// JavaScript para el formulario wizard de Magisters
// Ley de Hick-Hyman: Navegación por pasos del formulario
let currentStep = 1;
const totalSteps = 4; // 4 pasos para magisters

document.addEventListener('DOMContentLoaded', function() {
    const sections = document.querySelectorAll('.hci-form-section');
    const progressSteps = document.querySelectorAll('.hci-progress-step-vertical');
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

    // Manejo especial para el campo de color
    const colorInput = document.querySelector('input[name="color"]');
    if (colorInput) {
        colorInput.addEventListener('change', function() {
            updateSummary();
        });
    }
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
        window.location.href = window.location.origin + '/magisters';
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
    const sectionIds = ['basica', 'personal', 'contacto', 'resumen'];
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
        showStepError('Por favor, completa todos los campos requeridos antes de continuar.');
    }
    
    return isValid;
}

function showStepError(message) {
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
    const nombre = document.querySelector('input[name="nombre"]')?.value || '';
    const color = document.querySelector('input[name="color"]')?.value || '#3b82f6';
    const encargado = document.querySelector('input[name="encargado"]')?.value || '';
    const asistente = document.querySelector('input[name="asistente"]')?.value || '';
    const telefono = document.querySelector('input[name="telefono"]')?.value || '';
    const anexo = document.querySelector('input[name="anexo"]')?.value || '';
    const correo = document.querySelector('input[name="correo"]')?.value || '';
    
    // Actualizar elementos del resumen
    const resumenNombre = document.getElementById('resumen-nombre');
    const resumenColor = document.getElementById('resumen-color');
    const resumenColorText = document.getElementById('resumen-color-text');
    const resumenEncargado = document.getElementById('resumen-encargado');
    const resumenAsistente = document.getElementById('resumen-asistente');
    const resumenTelefono = document.getElementById('resumen-telefono');
    const resumenAnexo = document.getElementById('resumen-anexo');
    const resumenCorreo = document.getElementById('resumen-correo');
    
    if (resumenNombre) resumenNombre.textContent = nombre || 'No especificado';
    if (resumenColor) resumenColor.style.backgroundColor = color;
    if (resumenColorText) resumenColorText.textContent = color;
    if (resumenEncargado) resumenEncargado.textContent = encargado || 'No especificado';
    if (resumenAsistente) resumenAsistente.textContent = asistente || 'No especificado';
    if (resumenTelefono) resumenTelefono.textContent = telefono || 'No especificado';
    if (resumenAnexo) resumenAnexo.textContent = anexo || 'No especificado';
    if (resumenCorreo) resumenCorreo.textContent = correo || 'No especificado';
}

// Función para enviar el formulario
window.submitForm = function() {
    // Validar el paso actual antes de enviar
    if (validateCurrentStep()) {
        // Enviar el formulario
        document.querySelector('.hci-form').submit();
    }
}
