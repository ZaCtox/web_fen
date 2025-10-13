// JavaScript para el formulario wizard de Usuarios
// Ley de Hick-Hyman: Navegación por pasos del formulario
let currentStep = 1;
let totalSteps = 4; // Se ajustará dinámicamente según si es edición o registro
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
    
    // Detectar si estamos en modo edición usando el campo oculto
    const isEditingInput = document.getElementById('is-editing');
    const isEditing = isEditingInput && isEditingInput.value === '1';
    totalSteps = isEditing ? 3 : 4;
    
    // En modo edición, ocultar el paso 3 (Notificación) del sidebar
    if (isEditing) {
        const step3Sidebar = document.querySelector('.hci-progress-sidebar [data-step="3"]');
        if (step3Sidebar) {
            step3Sidebar.style.display = 'none';
        }
        
        // Renumerar el paso 4 como paso 3 en edición y actualizar data-step
        const step4Sidebar = document.querySelector('.hci-progress-sidebar [data-step="4"]');
        if (step4Sidebar) {
            const stepNumber = step4Sidebar.querySelector('.hci-progress-step-number');
            if (stepNumber) {
                stepNumber.textContent = '3';
            }
            step4Sidebar.setAttribute('data-step', '3');
            step4Sidebar.setAttribute('onclick', 'navigateToStep(3)');
        }
        
        // Crear una función personalizada para actualizar el progreso en modo edición
        window.updateWizardProgressSteps = function(currentStep) {
            const progressSteps = document.querySelectorAll('.hci-progress-step-vertical:not([style*="display: none"])');
            
            progressSteps.forEach((stepElement, index) => {
                const stepNumber = index + 1;
                
                // Remover todas las clases de estado primero
                stepElement.classList.remove('completed', 'active');
                
                if (stepNumber < currentStep) {
                    // Pasos anteriores: completados (verde con checkmark ✓)
                    stepElement.classList.add('completed');
                } else if (stepNumber === currentStep) {
                    // Paso actual: activo (azul con número)
                    stepElement.classList.add('active');
                }
                // Pasos futuros: sin clase especial (gris con número)
            });
        };
        
        // Actualizar el texto del header
        const currentStepText = document.getElementById('current-step');
        const progressPercentage = document.getElementById('progress-percentage');
        if (currentStepText) currentStepText.textContent = 'Paso 1 de 3';
        if (progressPercentage) progressPercentage.textContent = '33%';
        
        // Actualizar la barra de progreso
        const progressBar = document.getElementById('progress-bar');
        if (progressBar) progressBar.style.height = '33%';
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
    const isEditingInput = document.getElementById('is-editing');
    const isEditing = isEditingInput && isEditingInput.value === '1';
    
    // No permitir navegar a pasos que no existen
    if (step > totalSteps) {
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
    // Detectar si estamos en modo edición usando el campo oculto
    const isEditingInput = document.getElementById('is-editing');
    const isEditing = isEditingInput && isEditingInput.value === '1';
    
    if (isEditing) {
        // En edición: personal, foto, resumen (3 pasos)
        const sectionIds = ['personal', 'foto', 'resumen'];
        return sectionIds[step - 1] || 'resumen';
    } else {
        // En registro: personal, foto, notificacion, resumen (4 pasos)
        const sectionIds = ['personal', 'foto', 'notificacion', 'resumen'];
        return sectionIds[step - 1] || 'resumen';
    }
}

function validateCurrentStep() {
    const currentSection = document.getElementById(getSectionId(currentStep));
    
    // Si no se encuentra la sección, no validar
    if (!currentSection) {
        return true;
    }
    
    // Si estamos en el paso de resumen, no validar campos (es solo visualización)
    if (getSectionId(currentStep) === 'resumen') {
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
    
    // Detectar si estamos en modo edición usando el campo oculto
    const isEditingInput = document.getElementById('is-editing');
    const isEditing = isEditingInput && isEditingInput.value === '1';
    
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
    console.log('submitForm called, currentStep:', currentStep);
    
    if (validateCurrentStep()) {
        console.log('Validation passed, submitting form');
        
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
        const form = document.querySelector('form.hci-form');
        if (form) {
            console.log('Submitting form:', form.action, form.method);
            form.submit();
        } else {
            console.error('Form not found!');
            // Fallback: buscar cualquier formulario en la página
            const fallbackForm = document.querySelector('form');
            if (fallbackForm) {
                console.log('Using fallback form:', fallbackForm.action, fallbackForm.method);
                fallbackForm.submit();
            } else {
                console.error('No form found at all!');
            }
        }
    } else {
        console.log('Validation failed');
    }
}

// ==================== FUNCIONES DE DRAG & DROP PARA FOTO ====================

window.handleDragOver = function(e) {
    e.preventDefault();
    e.stopPropagation();
    const dropZone = document.getElementById('foto-drop-zone');
    if (dropZone) {
        dropZone.classList.add('hci-file-drop-active');
    }
}

window.handleDragLeave = function(e) {
    e.preventDefault();
    e.stopPropagation();
    const dropZone = document.getElementById('foto-drop-zone');
    if (dropZone) {
        dropZone.classList.remove('hci-file-drop-active');
    }
}

window.handleFotoDrop = function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const dropZone = document.getElementById('foto-drop-zone');
    if (dropZone) {
        dropZone.classList.remove('hci-file-drop-active');
    }
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        handleFotoFile(files[0]);
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

window.clearFoto = function() {
    document.getElementById('foto-input').value = '';
    document.getElementById('foto-preview-info').classList.add('hidden');
    document.getElementById('foto-drop-text').textContent = 'Arrastra tu foto aquí';
    
    // Restablecer preview a avatar por defecto
    const defaultAvatar = 'https://ui-avatars.com/api/?name=Foto&background=84b6f4&color=000000&size=300&bold=true&font-size=0.4';
    document.getElementById('foto-preview').src = defaultAvatar;
    
    currentFile = null;
}