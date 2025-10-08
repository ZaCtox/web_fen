// JavaScript para el formulario wizard de Novedades
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

    // Preview de la novedad en tiempo real
    const tituloInput = document.querySelector('input[name="titulo"]');
    const contenidoInput = document.querySelector('textarea[name="contenido"]');
    const iconoInput = document.querySelector('input[name="icono"]');
    const colorInput = document.querySelector('select[name="color"]');
    
    if (tituloInput) tituloInput.addEventListener('input', updatePreview);
    if (contenidoInput) contenidoInput.addEventListener('input', updatePreview);
    if (iconoInput) iconoInput.addEventListener('input', updatePreview);
    if (colorInput) colorInput.addEventListener('change', updatePreview);
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
        window.showUnsavedChangesModal(window.location.origin + '/novedades');
    } else {
        window.location.href = window.location.origin + '/novedades';
    }
}

function showStep(step) {
    console.log(`🎬 showStep() llamado con paso: ${step}`);
    const sections = document.querySelectorAll('.hci-form-section');
    const progressSteps = document.querySelectorAll('.hci-progress-step-vertical');
    
    console.log(`📄 Secciones encontradas: ${sections.length}`);
    console.log(`📊 Pasos de progreso encontrados: ${progressSteps.length}`);
    
    // Ocultar todas las secciones
    sections.forEach(section => {
        section.classList.remove('active');
    });
    
    // Mostrar sección actual
    const sectionId = getSectionId(step);
    console.log(`🔍 Buscando sección con ID: ${sectionId}`);
    const currentSection = document.getElementById(sectionId);
    console.log(`📄 Sección encontrada:`, currentSection);
    
    if (currentSection) {
        currentSection.classList.add('active');
        console.log(`✅ Sección ${sectionId} marcada como activa`);
        currentSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        console.log(`📜 Scroll realizado`);
    } else {
        console.error(`❌ No se encontró la sección con ID: ${sectionId}`);
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
    console.log(`📊 Progreso vertical actualizado`);
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
    const sectionIds = ['basica', 'configuracion', 'diseno', 'resumen'];
    return sectionIds[step - 1];
}

function validateCurrentStep() {
    console.log(`🔍 validateCurrentStep() - Paso ${currentStep}`);
    const sectionId = getSectionId(currentStep);
    console.log(`📋 ID de sección: ${sectionId}`);
    
    const currentSection = document.getElementById(sectionId);
    console.log(`📄 Sección encontrada:`, currentSection);
    
    const requiredFields = currentSection?.querySelectorAll('input[required], select[required], textarea[required]');
    console.log(`📝 Campos requeridos encontrados:`, requiredFields?.length || 0);
    
    let isValid = true;
    
    if (requiredFields) {
        requiredFields.forEach((field, index) => {
            console.log(`🔍 Campo ${index + 1}:`, field.name, '=', field.value);
            if (!field.value.trim()) {
                console.log(`❌ Campo ${field.name} está vacío`);
                validateField(field);
                isValid = false;
            } else {
                console.log(`✅ Campo ${field.name} tiene valor`);
                clearFieldError(field);
            }
        });
    }
    
    console.log(`🎯 Paso ${currentStep} es válido:`, isValid);
    
    if (!isValid) {
        console.log('❌ Mostrando error de validación');
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
    const contenido = document.querySelector('textarea[name="contenido"]')?.value || '';
    const tipo = document.querySelector('select[name="tipo_novedad"]')?.value || '';
    const esUrgente = document.querySelector('input[name="es_urgente"]')?.checked || false;
    const visiblePublico = document.querySelector('input[name="visible_publico"]')?.checked || false;
    const icono = document.querySelector('input[name="icono"]')?.value || '📰';
    const color = document.querySelector('select[name="color"]')?.value || 'blue';
    const magister = document.querySelector('select[name="magister_id"]')?.selectedOptions[0]?.text || 'No especificado';
    
    // Actualizar elementos del resumen
    const resumenTitulo = document.getElementById('resumen-titulo');
    const resumenContenido = document.getElementById('resumen-contenido');
    const resumenTipo = document.getElementById('resumen-tipo');
    const resumenUrgente = document.getElementById('resumen-urgente');
    const resumenVisibilidad = document.getElementById('resumen-visibilidad');
    const resumenIcono = document.getElementById('resumen-icono');
    const resumenColor = document.getElementById('resumen-color');
    const resumenPrograma = document.getElementById('resumen-programa');
    
    if (resumenTitulo) resumenTitulo.textContent = titulo || 'No especificado';
    if (resumenContenido) resumenContenido.textContent = contenido || 'No especificado';
    if (resumenTipo) resumenTipo.textContent = tipo || 'No especificado';
    if (resumenUrgente) resumenUrgente.textContent = esUrgente ? 'Sí' : 'No';
    if (resumenVisibilidad) resumenVisibilidad.textContent = visiblePublico ? 'Público' : 'Privado';
    if (resumenIcono) resumenIcono.textContent = icono || '📰';
    if (resumenColor) resumenColor.textContent = color || 'blue';
    if (resumenPrograma) resumenPrograma.textContent = magister || 'Todos los programas';
}

// Función para actualizar el preview de la novedad
function updatePreview() {
    const titulo = document.querySelector('input[name="titulo"]')?.value || 'Título de la novedad';
    const contenido = document.querySelector('textarea[name="contenido"]')?.value || 'Contenido de la novedad...';
    const icono = document.querySelector('input[name="icono"]')?.value || '📰';
    const color = document.querySelector('select[name="color"]')?.value || 'blue';
    
    const previewElement = document.getElementById('novedad-preview');
    if (previewElement) {
        const colorClasses = {
            'blue': 'bg-blue-500',
            'green': 'bg-green-500',
            'yellow': 'bg-yellow-500',
            'red': 'bg-red-500',
            'purple': 'bg-purple-500',
            'indigo': 'bg-indigo-500'
        };
        
        previewElement.innerHTML = `
            <div class="bg-white border rounded-lg p-4 shadow-md">
                <div class="flex items-center mb-2">
                    <span class="text-2xl mr-3">${icono}</span>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">${titulo}</h3>
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium ${colorClasses[color]} text-white">
                            ${color}
                        </span>
                    </div>
                </div>
                <p class="text-sm text-gray-600">${contenido.substring(0, 150)}${contenido.length > 150 ? '...' : ''}</p>
            </div>
        `;
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
