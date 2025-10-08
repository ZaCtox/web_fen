// JavaScript para el formulario wizard de Informes
// Ley de Hick-Hyman: Navegación por pasos del formulario
let currentStep = 1;
const totalSteps = 3;

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Informes form wizard loaded');
    
    const sections = document.querySelectorAll('.hci-form-section');
    const progressSteps = document.querySelectorAll('.hci-progress-step');
    const progressBar = document.getElementById('progress-bar');
    const progressPercentage = document.getElementById('progress-percentage');
    const currentStepText = document.getElementById('current-step');
    
    console.log('📋 Found sections:', sections.length);
    console.log('📊 Found progress steps:', progressSteps.length);
    
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
    if (window.hasUnsavedChanges && window.hasUnsavedChanges()) {
        window.showUnsavedChangesModal(window.location.origin + '/informes');
    } else {
        window.location.href = window.location.origin + '/informes';
    }
}

function showStep(step) {
    console.log('🎯 Showing step:', step);
    
    const sections = document.querySelectorAll('.hci-form-section');
    const progressSteps = document.querySelectorAll('.hci-progress-step-vertical');
    
    console.log('📋 Total sections found:', sections.length);
    
    // Ocultar todas las secciones
    sections.forEach(section => {
        section.classList.remove('active');
        console.log('❌ Hiding section:', section.id);
    });
    
    // Mostrar sección actual
    const sectionId = getSectionId(step);
    console.log('🔍 Looking for section ID:', sectionId);
    
    const currentSection = document.getElementById(sectionId);
    if (currentSection) {
        currentSection.classList.add('active');
        console.log('✅ Showing section:', sectionId);
        currentSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } else {
        console.error('❌ Section not found:', sectionId);
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
    const nombre = document.querySelector('input[name="nombre"]')?.value || '';
    const archivo = document.querySelector('input[name="archivo"]')?.files?.[0]?.name || '';
    const magister = document.querySelector('select[name="magister_id"]')?.selectedOptions?.[0]?.text || '';
    
    // Actualizar elementos del resumen
    const resumenNombre = document.getElementById('summary-nombre');
    const resumenArchivo = document.getElementById('summary-archivo');
    const resumenDestinatario = document.getElementById('summary-destinatario');
    
    if (resumenNombre) resumenNombre.textContent = nombre || 'No especificado';
    if (resumenArchivo) resumenArchivo.textContent = archivo || 'Sin archivo seleccionado';
    if (resumenDestinatario) resumenDestinatario.textContent = magister || 'Todos los programas';
}

// Función para enviar el formulario
window.submitForm = function() {
    // Validar el paso actual antes de enviar
    if (validateCurrentStep()) {
        // Enviar el formulario
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