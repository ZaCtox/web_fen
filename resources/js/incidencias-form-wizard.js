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
    if (window.hasUnsavedChanges && window.hasUnsavedChanges()) {
        window.showUnsavedChangesModal(window.location.origin + '/incidencias');
    } else {
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
    
    // Usar el helper global para actualizar el progreso
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
    const programa = document.querySelector('select[name="magister_id"]')?.selectedOptions?.[0]?.text || '';
    const sala = document.querySelector('select[name="room_id"]')?.selectedOptions?.[0]?.text || '';
    const imagen = document.querySelector('input[name="imagen"]')?.files?.[0]?.name || '';
    const ticket = document.querySelector('input[name="nro_ticket"]')?.value || '';
    
    // Actualizar elementos del resumen
    const resumenTitulo = document.getElementById('summary-titulo');
    const resumenDescripcion = document.getElementById('summary-descripcion');
    const resumenPrograma = document.getElementById('summary-programa');
    const resumenSala = document.getElementById('summary-sala');
    const resumenImagen = document.getElementById('summary-imagen');
    const resumenTicket = document.getElementById('summary-ticket');
    
    if (resumenTitulo) resumenTitulo.textContent = titulo || 'No especificado';
    if (resumenDescripcion) resumenDescripcion.textContent = descripcion || 'No especificado';
    if (resumenPrograma) resumenPrograma.textContent = programa || 'No especificado';
    if (resumenSala) resumenSala.textContent = sala || 'No especificado';
    if (resumenImagen) resumenImagen.textContent = imagen || 'Sin imagen';
    if (resumenTicket) resumenTicket.textContent = ticket || 'Sin ticket';
}

// Función para enviar el formulario
window.submitForm = function() {
    // Validar el paso actual antes de enviar
    if (validateCurrentStep()) {
        // Mostrar overlay de loading
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

// Funciones para drag & drop de imágenes
function handleDragOver(e) {
    e.preventDefault();
    e.currentTarget.classList.add('drag-over');
}

function handleDragLeave(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('drag-over');
}

function handleImageDrop(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('drag-over');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        const file = files[0];
        handleImageFile(file);
    }
}

function handleImageSelect(e) {
    const file = e.target.files[0];
    if (file) {
        handleImageFile(file);
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

function handleImageFile(file) {
    if (validateImageFile(file)) {
        // Actualizar el input file
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        document.getElementById('imagen-input').files = dataTransfer.files;
        
        // Crear preview URL
        const previewUrl = URL.createObjectURL(file);
        const thumbnail = document.getElementById('preview-thumbnail');
        if (thumbnail) {
            thumbnail.src = previewUrl;
        }
        
        // Mostrar información del archivo
        const imageName = document.getElementById('image-name');
        if (imageName) {
            imageName.textContent = file.name;
        }
        
        const imagePreview = document.getElementById('image-preview');
        if (imagePreview) {
            imagePreview.classList.remove('hidden');
        }
        
        const dropText = document.getElementById('image-drop-text');
        if (dropText) {
            dropText.textContent = 'Imagen seleccionada';
        }
        
        // Actualizar resumen
        updateSummary();
    }
}

function clearImage() {
    const imageInput = document.getElementById('imagen-input');
    if (imageInput) {
        imageInput.value = '';
    }
    
    const imagePreview = document.getElementById('image-preview');
    if (imagePreview) {
        imagePreview.classList.add('hidden');
    }
    
    const dropText = document.getElementById('image-drop-text');
    if (dropText) {
        dropText.textContent = 'Arrastra tu imagen aquí';
    }
    
    updateSummary();
}

// Hacer funciones globales
window.handleDragOver = handleDragOver;
window.handleDragLeave = handleDragLeave;
window.handleImageDrop = handleImageDrop;
window.handleImageSelect = handleImageSelect;
window.clearImage = clearImage;
