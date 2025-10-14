// JavaScript para el formulario wizard de Reportes Diarios
// Ley de Hick-Hyman: Navegación por pasos del formulario

let currentStep = 1;
const totalSteps = 3;
let contadorEntradas = 0;

// Función para agregar entrada al contenedor
window.addEntryToContainer = function(entryData = null) {
    contadorEntradas++;
    const container = document.getElementById('entradas-container');
    
    const locationType = entryData?.location_type || '';
    const roomId = entryData?.room_id || '';
    const locationDetail = entryData?.location_detail || '';
    const observation = entryData?.observation || '';
    const photoUrl = entryData?.photo_url || '';
    
    // Construir opciones de salas
    let roomOptions = '<option value="">Seleccione una sala específica</option>';
    if (window.dailyReportsData && window.dailyReportsData.rooms) {
        window.dailyReportsData.rooms.forEach(room => {
            const selected = room.id == roomId ? 'selected' : '';
            roomOptions += '<option value="' + room.id + '" ' + selected + '>' + room.name + '</option>';
        });
    }
    
    // Construir HTML de la entrada
    const entradaHtml = 
        '<div class="entrada-item border border-gray-200 dark:border-gray-600 rounded-lg p-4 mb-4 bg-gray-50 dark:bg-gray-700" data-index="' + contadorEntradas + '">' +
            '<div class="flex justify-between items-center mb-4">' +
                '<h4 class="text-md font-medium text-gray-900 dark:text-gray-100">Observación #' + contadorEntradas + '</h4>' +
                '<button type="button" onclick="this.closest(\'.entrada-item\').remove()" class="hci-button hci-lift hci-focus-ring p-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-all duration-200" title="Eliminar observación">' +
                    '<img src="/icons/trashw.svg" alt="Eliminar" class="w-4 h-4">' +
                '</button>' +
            '</div>' +
            '<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">' +
                '<div>' +
                    '<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de Ubicación <span class="text-red-500">*</span></label>' +
                    '<select name="entries[' + contadorEntradas + '][location_type]" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent dark:bg-gray-700 dark:text-gray-200 transition-all duration-200" onchange="toggleLocationFields' + contadorEntradas + '(this)" required>' +
                        '<option value="">Seleccione el tipo de ubicación</option>' +
                        '<option value="Sala"' + (locationType === 'Sala' ? ' selected' : '') + '>Sala de Clases</option>' +
                        '<option value="Baño"' + (locationType === 'Baño' ? ' selected' : '') + '>Baño</option>' +
                        '<option value="Pasillo"' + (locationType === 'Pasillo' ? ' selected' : '') + '>Pasillo</option>' +
                        '<option value="Laboratorio"' + (locationType === 'Laboratorio' ? ' selected' : '') + '>Laboratorio</option>' +
                        '<option value="Oficina"' + (locationType === 'Oficina' ? ' selected' : '') + '>Oficina</option>' +
                        '<option value="Otro"' + (locationType === 'Otro' ? ' selected' : '') + '>Otro</option>' +
                    '</select>' +
                '</div>' +
                '<div id="sala-field-' + contadorEntradas + '" class="' + (locationType === 'Sala' ? '' : 'hidden') + '">' +
                    '<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Seleccionar Sala</label>' +
                    '<select name="entries[' + contadorEntradas + '][room_id]" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent dark:bg-gray-700 dark:text-gray-200 transition-all duration-200">' +
                        roomOptions +
                    '</select>' +
                '</div>' +
                '<div id="detalle-field-' + contadorEntradas + '" class="' + (['Baño', 'Pasillo', 'Laboratorio', 'Oficina', 'Otro'].includes(locationType) ? '' : 'hidden') + '">' +
                    '<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Detalle de Ubicación</label>' +
                    '<input type="text" name="entries[' + contadorEntradas + '][location_detail]" value="' + locationDetail + '" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent dark:bg-gray-700 dark:text-gray-200 transition-all duration-200" placeholder="Ej: Baño primer piso, Pasillo edificio A">' +
                '</div>' +
            '</div>' +
            '<div class="mb-4">' +
                '<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Observación <span class="text-red-500">*</span></label>' +
                '<textarea name="entries[' + contadorEntradas + '][observation]" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent dark:bg-gray-700 dark:text-gray-200 transition-all duration-200" placeholder="Describe lo observado (mínimo 5 caracteres)..." required>' + observation + '</textarea>' +
            '</div>' +
            (photoUrl ? 
                '<div class="mb-4">' +
                    '<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Imagen Actual</label>' +
                    '<img src="' + photoUrl + '" alt="Imagen actual" class="max-w-xs h-auto rounded-lg shadow-md">' +
                '</div>' : '') +
            '<div>' +
                '<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">' + (photoUrl ? 'Reemplazar Imagen (Opcional)' : 'Evidencia Fotográfica (Opcional)') + '</label>' +
                '<div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-[#4d82bc] transition-colors duration-200">' +
                    '<div class="space-y-1 text-center">' +
                        '<svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">' +
                            '<path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />' +
                        '</svg>' +
                        '<div class="flex text-sm text-gray-600 dark:text-gray-400">' +
                            '<label for="photo-' + contadorEntradas + '" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-[#4d82bc] hover:text-[#005187] focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-[#4d82bc]">' +
                                '<span>Subir una foto</span>' +
                                '<input id="photo-' + contadorEntradas + '" name="entries[' + contadorEntradas + '][photo]" type="file" accept="image/*" class="sr-only" onchange="previewImage' + contadorEntradas + '(this)">' +
                            '</label>' +
                            '<p class="pl-1">o arrastra y suelta</p>' +
                        '</div>' +
                        '<p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF hasta 10MB</p>' +
                    '</div>' +
                '</div>' +
                '<div id="image-preview-' + contadorEntradas + '" class="mt-4 text-center hidden">' +
                    '<img id="preview-img-' + contadorEntradas + '" src="" alt="Preview" class="mx-auto h-32 w-32 object-cover rounded-lg shadow">' +
                    '<p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Vista previa</p>' +
                '</div>' +
            '</div>' +
        '</div>';
    
    container.insertAdjacentHTML('beforeend', entradaHtml);
    
    // Crear función toggle específica para esta entrada
    window['toggleLocationFields' + contadorEntradas] = function(select) {
        const index = contadorEntradas;
        const locationType = select.value;
        const salaField = document.getElementById('sala-field-' + index);
        const detalleField = document.getElementById('detalle-field-' + index);
        
        salaField.classList.add('hidden');
        detalleField.classList.add('hidden');
        
        if (locationType === 'Sala') {
            salaField.classList.remove('hidden');
        } else if (['Baño', 'Pasillo', 'Laboratorio', 'Oficina', 'Otro'].includes(locationType)) {
            detalleField.classList.remove('hidden');
        }
    };
    
    // Crear función preview específica para esta entrada
    window['previewImage' + contadorEntradas] = function(input) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('image-preview-' + contadorEntradas);
                const img = document.getElementById('preview-img-' + contadorEntradas);
                img.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    };
};

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
        window.showUnsavedChangesModal(window.location.origin + '/daily-reports');
    } else {
        window.location.href = window.location.origin + '/daily-reports';
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
    const sectionIds = ['informacion', 'observaciones', 'resumen'];
    return sectionIds[step - 1];
}

function validateCurrentStep() {
    const currentSection = document.getElementById(getSectionId(currentStep));
    if (!currentSection) return true;
    
    // Si estamos en el paso 3 (resumen), no validar campos
    if (currentStep === 3) {
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
    
    // Validación especial para el paso 2 (observaciones)
    if (currentStep === 2) {
        const entries = document.querySelectorAll('.entrada-item');
        if (entries.length === 0) {
            showStepError('Debe agregar al menos una observación.');
            isValid = false;
        }
    }
    
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
    console.log('submitForm called');
    if (validateCurrentStep()) {
        console.log('Validation passed, submitting form');
        
        // Submit del formulario directamente sin overlay
        const form = document.querySelector('.hci-form');
        console.log('Form found:', form);
        form.submit();
    } else {
        console.log('Validation failed');
    }
}

// Inicializar entradas cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar entradas existentes o crear una nueva
    if (window.dailyReportsData && window.dailyReportsData.entries) {
        if (window.dailyReportsData.entries.length === 0) {
            window.addEntryToContainer();
        } else {
            window.dailyReportsData.entries.forEach(entry => {
                window.addEntryToContainer(entry);
            });
        }
    }
});

