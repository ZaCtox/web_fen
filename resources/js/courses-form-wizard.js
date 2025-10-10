// JavaScript para el formulario wizard de Courses
// Ley de Hick-Hyman: Navegación por pasos del formulario
let currentStep = 1;
const totalSteps = 3; // 3 pasos para courses

document.addEventListener('DOMContentLoaded', function() {
    const sections = document.querySelectorAll('.hci-form-section');
    const progressSteps = document.querySelectorAll('.hci-progress-step-vertical');
    const progressBar = document.getElementById('progress-bar');
    const progressPercentage = document.getElementById('progress-percentage');
    const currentStepText = document.getElementById('current-step');
    
    // Inicializar formulario
    showStep(1);
    updateProgress(1);
    
    // Lógica de períodos (copiada del formulario original)
    const periods = window.PERIODS || [];
    const romanos = { 1: 'I', 2: 'II', 3: 'III', 4: 'IV', 5: 'V', 6: 'VI' };

    const anioSelect = document.getElementById('anio');
    const numeroSelect = document.getElementById('numero');
    const periodIdInput = document.getElementById('period_id');
    const magisterSelect = document.getElementById('magister_id');
    const mallaSelect = document.getElementById('malla_curricular_id');

    let numeroPreseleccionado = null;

    // Filtrar mallas según el magíster seleccionado
    function actualizarMallasDisponibles() {
        if (!magisterSelect || !mallaSelect) return;
        
        const magisterSeleccionado = magisterSelect.value;
        const options = mallaSelect.querySelectorAll('option');
        
        options.forEach(option => {
            if (option.value === '') {
                option.style.display = 'block'; // Siempre mostrar "Sin malla específica"
                return;
            }
            
            const mallaMagisterId = option.getAttribute('data-magister');
            if (mallaMagisterId === magisterSeleccionado) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
                if (option.selected) {
                    mallaSelect.value = ''; // Limpiar selección si no corresponde
                }
            }
        });
    }

    // Event listener para magíster
    if (magisterSelect) {
        magisterSelect.addEventListener('change', () => {
            actualizarMallasDisponibles();
            updateSummary();
        });
        
        // Inicializar al cargar
        actualizarMallasDisponibles();
    }

    function actualizarTrimestresDisponibles() {
        const anioSeleccionado = anioSelect.value;
        numeroSelect.innerHTML = '<option value="">-- Selecciona un trimestre --</option>';

        if (!anioSeleccionado) return;

        const trimestres = periods
            .filter(p => p.anio == anioSeleccionado)
            .map(p => ({ numero: p.numero, id: p.id }))
            .sort((a, b) => a.numero - b.numero);

        trimestres.forEach(t => {
            const option = document.createElement('option');
            option.value = t.numero;
            option.textContent = `Trimestre ${romanos[t.numero] || t.numero}`;
            numeroSelect.appendChild(option);
        });

        // Si hay trimestre preseleccionado, seleccionarlo ahora
        if (numeroPreseleccionado) {
            numeroSelect.value = numeroPreseleccionado;
            numeroPreseleccionado = null;
        }

        window.actualizarPeriodId();
    }

    // Función global para actualizar period_id
    window.actualizarPeriodId = function() {
        const anio = anioSelect.value;
        const numero = numeroSelect.value;
        const periodo = periods.find(p => p.anio == anio && p.numero == numero);
        periodIdInput.value = periodo ? periodo.id : '';
        
        // Debug: Mostrar en consola para verificar
        console.log('Actualizando period_id:', {
            anio: anio,
            numero: numero,
            periodId: periodIdInput.value,
            periodo: periodo
        });
    }

    if (anioSelect && numeroSelect && periodIdInput) {
        anioSelect.addEventListener('change', () => {
            actualizarTrimestresDisponibles();
            updateSummary(); // Actualizar resumen en tiempo real
        });

        numeroSelect.addEventListener('change', () => {
            window.actualizarPeriodId();
            updateSummary(); // Actualizar resumen en tiempo real
        });

        // Si estamos en modo edición, prellenar
        const periodId = periodIdInput.value;
        if (periodId) {
            const periodoActual = periods.find(p => p.id == periodId);
            if (periodoActual) {
                anioSelect.value = periodoActual.anio;
                numeroPreseleccionado = periodoActual.numero;
                actualizarTrimestresDisponibles();
            }
        }
    }
    
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
        window.showUnsavedChangesModal(window.location.origin + '/courses');
    } else {
        window.location.href = window.location.origin + '/courses';
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
    const sectionIds = ['basica', 'programa', 'resumen'];
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
    const magisterId = document.querySelector('select[name="magister_id"]')?.value || '';
    const anio = document.querySelector('select[name="anio"]')?.value || '';
    const numero = document.querySelector('select[name="numero"]')?.value || '';
    
    // Obtener texto de las opciones seleccionadas
    const magisterSelect = document.querySelector('select[name="magister_id"]');
    const mallaSelect = document.querySelector('select[name="malla_curricular_id"]');
    const anioSelect = document.querySelector('select[name="anio"]');
    const numeroSelect = document.querySelector('select[name="numero"]');
    
    const programaTexto = magisterSelect?.selectedOptions[0]?.text || 'No seleccionado';
    const mallaTexto = mallaSelect?.selectedOptions[0]?.text || 'Sin malla específica';
    const anioTexto = anioSelect?.selectedOptions[0]?.text || 'No seleccionado';
    const trimestreTexto = numeroSelect?.selectedOptions[0]?.text || 'No seleccionado';
    
    // Actualizar elementos del resumen
    const resumenNombre = document.getElementById('resumen-nombre');
    const resumenPrograma = document.getElementById('resumen-programa');
    const resumenMalla = document.getElementById('resumen-malla');
    const resumenAnio = document.getElementById('resumen-anio');
    const resumenTrimestre = document.getElementById('resumen-trimestre');
    
    if (resumenNombre) resumenNombre.textContent = nombre || 'No especificado';
    if (resumenPrograma) resumenPrograma.textContent = programaTexto;
    if (resumenMalla) resumenMalla.textContent = mallaTexto;
    if (resumenAnio) resumenAnio.textContent = anioTexto;
    if (resumenTrimestre) resumenTrimestre.textContent = trimestreTexto;
}

    // Función para enviar el formulario
    window.submitForm = function() {
        // Validar el paso actual antes de enviar
        if (validateCurrentStep()) {
            // Asegurar que period_id esté actualizado antes de enviar
            if (typeof window.actualizarPeriodId === 'function') {
                window.actualizarPeriodId();
            }
            
            // Enviar el formulario
            document.querySelector('.hci-form').submit();
        }
    }
