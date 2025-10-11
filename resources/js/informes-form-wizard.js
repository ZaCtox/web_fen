// JavaScript para el formulario wizard de Informes
// Ley de Hick-Hyman: Navegación por pasos del formulario
let currentStep = 1;
const totalSteps = 3;

document.addEventListener('DOMContentLoaded', function() {
    // Verificar si hay errores de validación de Laravel
    const hasErrors = document.querySelector('.error, .invalid-feedback, [class*="error"]');
    if (hasErrors) {
        // Encontrar el primer paso con errores
        const firstErrorField = document.querySelector('input.error, select.error, textarea.error');
        if (firstErrorField) {
            const errorSection = firstErrorField.closest('.hci-form-section');
            if (errorSection) {
                const stepAttr = errorSection.getAttribute('data-step');
                if (stepAttr) {
                    currentStep = parseInt(stepAttr);
                }
            }
        }
    }
    
    // Inicializar formulario
    showStep(currentStep);
    
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
    
    // Event listeners para los selects
    const tipoSelect = document.querySelector('select[name="tipo"]');
    const magisterSelect = document.querySelector('select[name="magister_id"]');
    
    if (tipoSelect) {
        tipoSelect.addEventListener('change', updateSummary);
    }
    if (magisterSelect) {
        magisterSelect.addEventListener('change', updateSummary);
    }
});

// Navegación entre pasos
window.nextStep = function() {
    if (validateCurrentStep()) {
        if (currentStep < totalSteps) {
            currentStep++;
            showStep(currentStep);
            updateProgress();
        }
    }
}

window.prevStep = function() {
    if (currentStep > 1) {
        currentStep--;
        showStep(currentStep);
        updateProgress();
    }
}

// Navegación directa por clic en el progreso lateral
window.navigateToStep = function(step) {
    if (step <= currentStep || validateCurrentStep()) {
        currentStep = step;
        showStep(currentStep);
        updateProgress();
    }
}

// Función para cancelar el formulario
window.cancelForm = function() {
    if (window.hasUnsavedChanges && window.hasUnsavedChanges()) {
        window.showUnsavedChangesModal(window.location.origin + '/informes');
    } else {
        window.location.href = window.location.origin + '/informes';
    }
}

function showStep(step) {
    // Ocultar todas las secciones
    const sections = document.querySelectorAll('.hci-form-section');
    sections.forEach(section => {
        section.classList.remove('active');
    });
    
    // Mostrar sección actual
    const sectionId = getSectionId(step);
    const currentSection = document.getElementById(sectionId);
    if (currentSection) {
        currentSection.classList.add('active');
        currentSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    
    // Usar el helper global para actualizar el progreso
    if (window.updateWizardProgressSteps) {
        window.updateWizardProgressSteps(step);
    }
    
    // Si estamos en el paso del resumen, actualizar el contenido
    if (sectionId === 'resumen') {
        updateSummary();
    }
}

function updateProgress() {
    const progressBar = document.getElementById('progress-bar');
    const progressPercentage = document.getElementById('progress-percentage');
    const currentStepText = document.getElementById('current-step');
    
    const percentage = (currentStep / totalSteps) * 100;
    
    if (progressBar) progressBar.style.height = percentage + '%';
    if (progressPercentage) progressPercentage.textContent = Math.round(percentage) + '%';
    if (currentStepText) currentStepText.textContent = `Paso ${currentStep} de ${totalSteps}`;
    
    // Controlar visibilidad de botones
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const submitBtn = document.getElementById('submit-btn');
    
    if (prevBtn) prevBtn.style.display = currentStep > 1 ? 'flex' : 'none';
    if (nextBtn) nextBtn.style.display = currentStep < totalSteps ? 'flex' : 'none';
    if (submitBtn) submitBtn.style.display = currentStep === totalSteps ? 'flex' : 'none';
}

function getSectionId(step) {
    const sectionIds = ['informacion', 'archivo', 'resumen'];
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
    const nombreInput = document.querySelector('input[name="nombre"]');
    const tipoSelect = document.querySelector('select[name="tipo"]');
    const archivoInput = document.querySelector('input[name="archivo"]');
    const magisterSelect = document.querySelector('select[name="magister_id"]');
    
    const nombre = nombreInput?.value || '';
    const archivo = archivoInput?.files?.[0]?.name || '';
    
    // Obtener el texto del tipo seleccionado
    let tipoText = 'No especificado';
    if (tipoSelect && tipoSelect.value) {
        const selectedOption = tipoSelect.options[tipoSelect.selectedIndex];
        tipoText = selectedOption?.text || tipoSelect.value;
    }
    
    // Obtener el texto del magister seleccionado
    let magisterText = 'Todos los programas';
    if (magisterSelect && magisterSelect.value) {
        const selectedOption = magisterSelect.options[magisterSelect.selectedIndex];
        magisterText = selectedOption?.text || magisterSelect.value;
    }
    
    // Actualizar elementos del resumen
    const resumenNombre = document.getElementById('summary-nombre');
    const resumenTipo = document.getElementById('summary-tipo');
    const resumenArchivo = document.getElementById('summary-archivo');
    const resumenDestinatario = document.getElementById('summary-destinatario');
    
    if (resumenNombre) resumenNombre.textContent = nombre || 'No especificado';
    if (resumenTipo) resumenTipo.textContent = tipoText;
    if (resumenArchivo) resumenArchivo.textContent = archivo || 'Sin archivo seleccionado';
    if (resumenDestinatario) resumenDestinatario.textContent = magisterText;
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

// Funciones para drag & drop
function handleDragOver(e) {
    e.preventDefault();
    e.currentTarget.classList.add('drag-over');
}

function handleDragLeave(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('drag-over');
}

function handleFileDrop(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('drag-over');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        const fileInput = document.getElementById('archivo-input');
        fileInput.files = files;
        handleFileSelect({ target: fileInput });
    }
}

function handleFileSelect(e) {
    const file = e.target.files[0];
    if (file) {
        // Validar tipo de archivo
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!allowedTypes.includes(file.type)) {
            alert('Por favor, selecciona un archivo PDF, DOC o DOCX.');
            e.target.value = '';
            return;
        }
        
        // Validar tamaño (4MB)
        if (file.size > 4 * 1024 * 1024) {
            alert('El archivo no puede exceder los 4MB.');
            e.target.value = '';
            return;
        }
        
        // Mostrar preview
        document.getElementById('file-name').textContent = file.name;
        document.getElementById('file-preview').classList.remove('hidden');
        document.getElementById('file-drop-text').textContent = 'Archivo seleccionado';
        
        // Actualizar resumen
        updateSummary();
    }
}

function clearFile() {
    document.getElementById('archivo-input').value = '';
    document.getElementById('file-preview').classList.add('hidden');
    document.getElementById('file-drop-text').textContent = 'Arrastra tu archivo aquí';
    updateSummary();
}

// Hacer funciones globales
window.handleDragOver = handleDragOver;
window.handleDragLeave = handleDragLeave;
window.handleFileDrop = handleFileDrop;
window.handleFileSelect = handleFileSelect;
window.clearFile = clearFile;