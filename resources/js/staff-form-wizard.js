// JavaScript para el formulario wizard de Staff
// Ley de Hick-Hyman: Navegación por pasos del formulario
// import Cropper from 'cropperjs'; // Ya no necesitamos cropper

let currentStep = 1;
const totalSteps = 5;
let currentFile = null;

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
    
    updateSummary();
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
        window.showUnsavedChangesModal(window.location.origin + '/equipo');
    } else {
        window.location.href = window.location.origin + '/equipo';
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
    const sectionIds = ['personal', 'foto', 'contacto', 'adicional', 'resumen'];
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
    const cargo = document.querySelector('input[name="cargo"]')?.value || '';
    const email = document.querySelector('input[name="email"]')?.value || '';
    const telefono = document.querySelector('input[name="telefono"]')?.value || '';
    const anexo = document.querySelector('input[name="anexo"]')?.value || 'No especificado';
    
    // Actualizar elementos del resumen
    const resumenNombre = document.getElementById('resumen-nombre');
    const resumenCargo = document.getElementById('resumen-cargo');
    const resumenEmail = document.getElementById('resumen-email');
    const resumenTelefono = document.getElementById('resumen-telefono');
    const resumenAnexo = document.getElementById('resumen-anexo');
    
    if (resumenNombre) resumenNombre.textContent = nombre || 'No especificado';
    if (resumenCargo) resumenCargo.textContent = cargo || 'No especificado';
    if (resumenEmail) resumenEmail.textContent = email || 'No especificado';
    if (resumenTelefono) resumenTelefono.textContent = telefono || 'No especificado';
    if (resumenAnexo) resumenAnexo.textContent = anexo || 'No especificado';
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

// ===== FUNCIONES PARA FOTO CON CROPPER =====

// Drag & Drop handlers
window.handleDragOver = function(e) {
    e.preventDefault();
    e.currentTarget.classList.add('drag-over');
}

window.handleDragLeave = function(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('drag-over');
}

window.handleFotoDrop = function(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('drag-over');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        const file = files[0];
        handleFotoFile(file);
    }
}

window.handleFotoSelect = function(e) {
    const file = e.target.files[0];
    if (file) {
        handleFotoFile(file);
    }
}

function validateImageFile(file) {
    // Validar tipo de archivo
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    if (!allowedTypes.includes(file.type)) {
        alert('Por favor, selecciona una imagen JPG, PNG o WEBP.');
        return false;
    }
    
    // Validar tamaño (2MB)
    if (file.size > 2 * 1024 * 1024) {
        alert('La imagen no puede exceder los 2MB.');
        return false;
    }
    
    return true;
}

function handleFotoFile(file) {
    if (validateImageFile(file)) {
        // Actualizar el input file directamente
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        document.getElementById('foto-input').files = dataTransfer.files;
        
        // Crear preview URL
        const previewUrl = URL.createObjectURL(file);
        document.getElementById('foto-preview').src = previewUrl;
        
        // Mostrar información del archivo
        document.getElementById('foto-name').textContent = file.name;
        document.getElementById('foto-preview-info').classList.remove('hidden');
        document.getElementById('foto-drop-text').textContent = 'Foto seleccionada';
        
        currentFile = file;
    }
}

// Función eliminada - ya no necesitamos el cropper

// Todas las funciones del cropper eliminadas

window.clearFoto = function() {
    document.getElementById('foto-input').value = '';
    document.getElementById('foto-preview-info').classList.add('hidden');
    document.getElementById('foto-drop-text').textContent = 'Arrastra tu foto aquí';
    
    // Restablecer preview a avatar por defecto
    const defaultAvatar = 'https://ui-avatars.com/api/?name=Foto&background=84b6f4&color=000000&size=300&bold=true&font-size=0.4';
    document.getElementById('foto-preview').src = defaultAvatar;
    
    currentFile = null;
}

// Función para actualizar el preview del avatar cuando se selecciona un color
window.updateAvatarPreviewColor = function(color) {
    const nameInput = document.querySelector('input[name="nombre"]');
    if (!nameInput) return;
    
    const name = nameInput.value || 'Staff';
    const words = name.trim().split(' ');
    let initials = '';
    
    if (words.length >= 2) {
        initials = words[0].charAt(0).toUpperCase() + words[1].charAt(0).toUpperCase();
    } else {
        initials = name.substring(0, 2).toUpperCase();
    }
    
    const avatarUrl = `https://ui-avatars.com/api/?name=${initials}&background=${color}&color=ffffff&size=300&bold=true&font-size=0.4`;
    const preview = document.getElementById('foto-preview');
    
    if (preview && !currentFile) {
        preview.src = avatarUrl + '&_t=' + Date.now();
    }
}
