// JavaScript para el formulario wizard de Mallas Curriculares
let currentStep = 1;
const totalSteps = 4; // 4 pasos para mallas curriculares

document.addEventListener('DOMContentLoaded', function() {
    // Si hay errores de validación (JS o Laravel), ir al primer paso con error
    const firstErrorSection = document.querySelector('.hci-form-section .hci-field-error, .hci-form-section .text-red-600, .hci-form-section .border-red-500');
    if (firstErrorSection) {
        const errorSection = firstErrorSection.closest('.hci-form-section');
        if (errorSection && errorSection.dataset.step) {
            const errorStep = parseInt(errorSection.dataset.step) || 1;
            currentStep = errorStep;
        }
    }
    
    // Inicializar formulario
    showStep(currentStep);
    updateProgress(currentStep);
    
    // Actualizar resumen en tiempo real
    const magisterSelect = document.querySelector('[name="magister_id"]');
    const nombreInput = document.querySelector('[name="nombre"]');
    const codigoInput = document.querySelector('[name="codigo"]');
    const añoInicioInput = document.querySelector('[name="año_inicio"]');
    const añoFinInput = document.querySelector('[name="año_fin"]');
    const activaCheckbox = document.querySelector('[name="activa"]');

    // Event listeners para actualizar resumen
    magisterSelect?.addEventListener('change', updateSummary);
    nombreInput?.addEventListener('input', updateSummary);
    codigoInput?.addEventListener('input', updateSummary);
    añoInicioInput?.addEventListener('input', updateSummary);
    añoFinInput?.addEventListener('input', updateSummary);
    activaCheckbox?.addEventListener('change', updateSummary);

    // Inicializar resumen
    updateSummary();
});

// Navegación
window.nextStep = function() {
    if (validateCurrentStep() && currentStep < totalSteps) {
        currentStep++;
        showStep(currentStep);
        updateProgress(currentStep);
    }
}

window.prevStep = function() {
    if (currentStep > 1) {
        currentStep--;
        showStep(currentStep);
        updateProgress(currentStep);
    }
}

window.navigateToStep = function(step) {
    if (step <= currentStep || validateCurrentStep()) {
        currentStep = step;
        showStep(currentStep);
        updateProgress(currentStep);
    }
}

window.cancelForm = function() {
    if (window.hasUnsavedChanges && window.hasUnsavedChanges()) {
        window.showUnsavedChangesModal(window.location.origin + '/mallas-curriculares');
    } else {
        window.location.href = window.location.origin + '/mallas-curriculares';
    }
}

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
        
        // Enviar el formulario
        document.querySelector('.hci-form').submit();
    }
}

function showStep(step) {
    const sections = document.querySelectorAll('.hci-form-section');

    sections.forEach(s => s.classList.remove('active'));
    const currentSection = document.getElementById(getSectionId(step));
    if (currentSection) {
        currentSection.classList.add('active');
        currentSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // Actualizar estado visual del sidebar (verde=completado, azul=actual, gris=pendiente)
    if (window.updateWizardProgressSteps) {
        window.updateWizardProgressSteps(step);
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
    
    if (prevBtn) {
        prevBtn.style.display = step > 1 ? 'flex' : 'none';
    }
    
    if (nextBtn && submitBtn) {
        if (step === totalSteps) {
            nextBtn.classList.add('hidden');
            submitBtn.classList.remove('hidden');
        } else {
            nextBtn.classList.remove('hidden');
            submitBtn.classList.add('hidden');
        }
    }
}

function getSectionId(step) {
    const sectionIds = ['basica', 'vigencia', 'descripcion', 'resumen'];
    return sectionIds[step - 1];
}

function validateCurrentStep() {
    const currentSection = document.getElementById(getSectionId(currentStep));
    if (!currentSection) return true;

    const requiredInputs = currentSection.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;

    requiredInputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            validateField(input);
        } else {
            clearFieldError(input);
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
    }, 4000);
}

function validateField(field) {
    const isValid = field.checkValidity();
    const fieldWrapper = field.closest('.hci-field');
    const existingError = fieldWrapper?.querySelector('.hci-field-error');
    
    if (!isValid) {
        field.classList.add('border-red-500');
        field.classList.remove('border-gray-300');
        
        if (!existingError) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'hci-field-error';
            errorDiv.textContent = field.validationMessage || 'Este campo es requerido';
            fieldWrapper?.appendChild(errorDiv);
        }
    } else {
        clearFieldError(field);
    }
}

function clearFieldError(field) {
    field.classList.remove('border-red-500');
    field.classList.add('border-gray-300');
    const fieldWrapper = field.closest('.hci-field');
    const errorDiv = fieldWrapper?.querySelector('.hci-field-error');
    if (errorDiv) errorDiv.remove();
}

function updateSummary() {
    // Magister
    const magisterSelect = document.querySelector('[name="magister_id"]');
    const magisterText = magisterSelect?.options[magisterSelect.selectedIndex]?.text || 'No seleccionado';
    const summaryMagister = document.getElementById('summary-magister');
    if (summaryMagister) summaryMagister.textContent = magisterText;

    // Nombre
    const nombreInput = document.querySelector('[name="nombre"]');
    const summaryNombre = document.getElementById('summary-nombre');
    if (summaryNombre) summaryNombre.textContent = nombreInput?.value || 'No ingresado';

    // Código
    const codigoInput = document.querySelector('[name="codigo"]');
    const summaryCodigo = document.getElementById('summary-codigo');
    if (summaryCodigo) summaryCodigo.textContent = codigoInput?.value || 'No ingresado';

    // Vigencia
    const añoInicioInput = document.querySelector('[name="año_inicio"]');
    const añoFinInput = document.querySelector('[name="año_fin"]');
    const inicio = añoInicioInput?.value;
    const fin = añoFinInput?.value;
    let vigencia = 'No definida';
    if (inicio) {
        vigencia = inicio + (fin ? ` - ${fin}` : ' - Actualidad');
    }
    const summaryVigencia = document.getElementById('summary-vigencia');
    if (summaryVigencia) summaryVigencia.textContent = vigencia;

    // Estado
    const activaCheckbox = document.querySelector('[name="activa"]');
    const summaryEstado = document.getElementById('summary-estado');
    if (summaryEstado) summaryEstado.textContent = activaCheckbox?.checked ? '✅ Activa' : '❌ Inactiva';
}

