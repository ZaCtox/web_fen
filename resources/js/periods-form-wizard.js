// Periods Form Wizard JavaScript
// Ley de Hick-Hyman: Navegación por pasos del formulario
let currentStep = 1;
const totalSteps = 3;

// Opciones de trimestres por año
const opcionesTrimestre = {
    1: [1, 2, 3],
    2: [4, 5, 6]
};

// Función para actualizar trimestres dinámicamente
function actualizarTrimestres() {
    const anioSelect = document.getElementById('anio-select');
    const numeroSelect = document.getElementById('numero-select');
    
    if (!anioSelect || !numeroSelect) return;
    
    const anio = parseInt(anioSelect.value);
    const trimestres = opcionesTrimestre[anio] || [];
    
    numeroSelect.innerHTML = '<option value="">-- Selecciona un trimestre --</option>';
    
    trimestres.forEach(function (num) {
        const option = document.createElement('option');
        option.value = num;
        option.textContent = 'Trimestre ' + num;
        numeroSelect.appendChild(option);
    });
    
    // Actualizar resumen
    updateSummary();
}

// Función para actualizar el resumen
function updateSummary() {
    const anioSelect = document.getElementById('anio-select');
    const numeroSelect = document.getElementById('numero-select');
    const fechaInicio = document.querySelector('input[name="fecha_inicio"]');
    const fechaFin = document.querySelector('input[name="fecha_fin"]');
    
    // Año académico
    const summaryAnio = document.getElementById('summary-anio');
    if (summaryAnio && anioSelect) {
        summaryAnio.textContent = anioSelect.value ? `Año ${anioSelect.value}` : '--';
    }
    
    // Trimestre
    const summaryTrimestre = document.getElementById('summary-trimestre');
    if (summaryTrimestre && numeroSelect) {
        const trimestre = numeroSelect.value;
        if (trimestre) {
            const romano = {1: 'I', 2: 'II', 3: 'III', 4: 'IV', 5: 'V', 6: 'VI'}[trimestre];
            summaryTrimestre.textContent = `Trimestre ${romano}`;
        } else {
            summaryTrimestre.textContent = '--';
        }
    }
    
    // Fecha de inicio
    const summaryFechaInicio = document.getElementById('summary-fecha-inicio');
    if (summaryFechaInicio && fechaInicio) {
        summaryFechaInicio.textContent = fechaInicio.value ? 
            new Date(fechaInicio.value).toLocaleDateString('es-CL') : '--';
    }
    
    // Fecha de término
    const summaryFechaFin = document.getElementById('summary-fecha-fin');
    if (summaryFechaFin && fechaFin) {
        summaryFechaFin.textContent = fechaFin.value ? 
            new Date(fechaFin.value).toLocaleDateString('es-CL') : '--';
    }
}

// Función para mostrar el paso actual
function showStep(step) {
    const sections = document.querySelectorAll('.hci-form-section');
    const progressSteps = document.querySelectorAll('.hci-progress-step-vertical');
    
    // Ocultar todas las secciones (usar clases en lugar de inline styles)
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
    
    // Actualizar progreso
    updateProgress(step);
}

// Función para actualizar la barra de progreso
function updateProgress(step) {
    const progressBar = document.getElementById('progress-bar');
    const progressPercentage = document.getElementById('progress-percentage');
    const currentStepText = document.getElementById('current-step');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    
    const percentage = (step / totalSteps) * 100;
    
    if (progressBar) progressBar.style.height = percentage + '%';
    if (progressPercentage) progressPercentage.textContent = Math.round(percentage) + '%';
    if (currentStepText) currentStepText.textContent = `Paso ${step} de ${totalSteps}`;
    
    // Actualizar botones
    if (prevBtn) {
        prevBtn.style.display = step === 1 ? 'none' : 'inline-flex';
    }
    if (nextBtn) {
        nextBtn.style.display = step === totalSteps ? 'none' : 'inline-flex';
    }
    if (submitBtn) {
        submitBtn.style.display = step === totalSteps ? 'inline-flex' : 'none';
    }
}

// Función para obtener el ID de la sección
function getSectionId(step) {
    const sectionIds = ['informacion', 'fechas', 'resumen'];
    return sectionIds[step - 1] || 'informacion';
}

// Navegación entre pasos
window.nextStep = function() {
    if (validateCurrentStep()) {
        if (currentStep < totalSteps) {
            currentStep++;
            showStep(currentStep);
            updateSummary(); // Actualizar resumen al avanzar
        }
    }
}

window.prevStep = function() {
    if (currentStep > 1) {
        currentStep--;
        showStep(currentStep);
    }
}

// Navegación directa por clic en el progreso lateral
window.navigateToStep = function(step) {
    if (step <= currentStep || validateCurrentStep()) {
        currentStep = step;
        showStep(currentStep);
    }
}

// Función para cancelar el formulario
window.cancelForm = function() {
    if (window.hasUnsavedChanges && window.hasUnsavedChanges()) {
        window.showUnsavedChangesModal(window.location.origin + '/periods');
    } else {
        window.location.href = window.location.origin + '/periods';
    }
}

// Función para validar el paso actual
function validateCurrentStep() {
    const currentSection = document.getElementById(getSectionId(currentStep));
    if (!currentSection) return true;
    
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
    // Crear o actualizar mensaje de error
    let errorDiv = document.getElementById('step-error-message');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.id = 'step-error-message';
        errorDiv.className = 'hci-error-message';
        const container = document.querySelector('.hci-container');
        if (container) {
            container.insertBefore(errorDiv, document.querySelector('.hci-wizard-layout'));
        }
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

// Función para enviar el formulario
window.submitForm = function() {
    // Validar el paso actual antes de enviar
    if (validateCurrentStep()) {
        // Enviar el formulario
        document.querySelector('.hci-form').submit();
    }
}

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Configurar event listeners para actualizar trimestres
    const anioSelect = document.getElementById('anio-select');
    if (anioSelect) {
        anioSelect.addEventListener('change', actualizarTrimestres);
    }
    
    // Validación en tiempo real
    const inputs = document.querySelectorAll('.hci-input, .hci-select, .hci-textarea, input, select, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required')) {
                validateField(this);
            }
        });
        
        input.addEventListener('input', function() {
            clearFieldError(this);
            updateSummary(); // Actualizar resumen en tiempo real
        });
        
        input.addEventListener('change', updateSummary);
    });
    
    // Inicializar trimestres si hay un año seleccionado
    actualizarTrimestres();
    
    // Inicializar formulario
    showStep(1);
    updateSummary();
});
