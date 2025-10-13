// JavaScript para el formulario wizard de Salas
let currentStep = 1;
const totalSteps = 4; // 4 pasos para salas

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
    
    showStep(currentStep);
    updateProgress(currentStep);

    const inputs = document.querySelectorAll('.hci-input, .hci-select, .hci-textarea');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            clearFieldError(this);
            updateSummary();
        });
        input.addEventListener('blur', function() {
            validateField(this);
        });
    });

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
        window.showUnsavedChangesModal(window.location.origin + '/rooms');
    } else {
        window.location.href = window.location.origin + '/rooms';
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
        
        // Submit del formulario
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
    const sectionIds = ['basica', 'detalles', 'equipamiento', 'resumen'];
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
    if (!isValid) showStepError('Por favor, completa los campos requeridos antes de continuar.');
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
    setTimeout(() => errorDiv && errorDiv.remove(), 5000);
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
    if (errorElement) errorElement.remove();
}

function updateSummary() {
    const name = document.querySelector('input[name="name"]')?.value || '';
    const location = document.querySelector('input[name="location"]')?.value || '';
    const capacity = document.querySelector('input[name="capacity"]')?.value || '';
    const status = '';
    const description = document.querySelector('textarea[name="description"]')?.value || '';

    const byId = id => document.getElementById(id);
    if (byId('resumen-name')) byId('resumen-name').textContent = name || 'No especificado';
    if (byId('resumen-location')) byId('resumen-location').textContent = location || 'No especificado';
    if (byId('resumen-capacity')) byId('resumen-capacity').textContent = capacity || 'No especificado';
    if (byId('resumen-description')) byId('resumen-description').textContent = description || 'No especificado';

    // Equipamiento seleccionado
    const equipamientos = [
        { name: 'calefaccion', label: 'Calefacción' },
        { name: 'energia_electrica', label: 'Energía Eléctrica' },
        { name: 'existe_aseo', label: 'Aseo Disponible' },
        { name: 'plumones', label: 'Plumones' },
        { name: 'borrador', label: 'Borrador' },
        { name: 'pizarra_limpia', label: 'Pizarra Limpia' },
        { name: 'computador_funcional', label: 'Computador Funcional' },
        { name: 'cables_computador', label: 'Cables del Computador' },
        { name: 'control_remoto_camara', label: 'Control Remoto de Cámara' },
        { name: 'televisor_funcional', label: 'Televisor Funcional' },
    ];
    const cont = byId('resumen-equipamiento');
    if (cont) {
        const seleccionados = equipamientos.filter(e => document.querySelector(`input[name="${e.name}"]`)?.checked);
        cont.innerHTML = '';
        if (seleccionados.length === 0) {
            cont.textContent = 'No especificado';
        } else {
            seleccionados.forEach(e => {
                const chip = document.createElement('span');
                chip.className = 'inline-block px-2 py-1 rounded-md bg-[#c4dafa]/40 text-[#005187] text-xs';
                chip.textContent = e.label;
                cont.appendChild(chip);
            });
        }
    }
}

