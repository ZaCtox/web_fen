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
    const hora = entryData?.hora || '';
    const escala = entryData?.escala || '';
    const programa = entryData?.programa || '';
    const area = entryData?.area || '';
    const tarea = entryData?.tarea || '';
    
    // Construir opciones de salas
    let roomOptions = '<option value="">Seleccione una sala específica</option>';
    if (window.dailyReportsData && window.dailyReportsData.rooms) {
        window.dailyReportsData.rooms.forEach(room => {
            const selected = room.id == roomId ? 'selected' : '';
            roomOptions += '<option value="' + room.id + '" ' + selected + '>' + room.name + '</option>';
        });
    }
    
    // Construir opciones de programas de magister
    let programaOptions = '<option value="">Seleccione un programa</option>';
    if (window.dailyReportsData && window.dailyReportsData.magisters) {
        window.dailyReportsData.magisters.forEach(magister => {
            const selected = magister.nombre == programa ? 'selected' : '';
            programaOptions += '<option value="' + magister.nombre + '" ' + selected + '>' + magister.nombre + '</option>';
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
            
            // Indicador de severidad
            '<div class="mb-6 p-4 bg-gradient-to-r from-teal-50 to-red-50 dark:from-gray-800 dark:to-gray-700 rounded-lg border border-gray-200 dark:border-gray-600" onclick="clearSeveritySelection(' + contadorEntradas + ')" style="cursor: pointer;">' +
                '<h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Indicador de Severidad <span class="text-red-500">*</span></h5>' +
                (escala ? '' : '<div class="severity-error-message text-sm text-red-500 mb-3 font-medium">⚠️ Debe seleccionar un nivel de severidad</div>') +
                
                // Iconos arriba (cada uno ocupa 2 números)
                '<div class="flex justify-center mb-3">' +
                    '<div class="flex w-full justify-between px-4">' +
                        '<div class="flex-1 flex justify-center"><img src="/icons/normal.svg" class="w-8 h-8" alt="Normal"></div>' +
                        '<div class="flex-1 flex justify-center"><img src="/icons/leve.svg" class="w-8 h-8" alt="Leve"></div>' +
                        '<div class="flex-1 flex justify-center"><img src="/icons/moderado.svg" class="w-8 h-8" alt="Moderado"></div>' +
                        '<div class="flex-1 flex justify-center"><img src="/icons/fuerte.svg" class="w-8 h-8" alt="Fuerte"></div>' +
                        '<div class="flex-1 flex justify-center"><img src="/icons/critico.svg" class="w-8 h-8" alt="Crítico"></div>' +
                    '</div>' +
                '</div>' +
                
                // Números en el medio
                '<div class="flex items-center justify-center mb-3">' +
                    '<div class="flex w-full bg-white dark:bg-gray-600 p-2 rounded-lg shadow-inner">' +
                        Array.from({length: 10}, (_, i) => {
                            const num = i + 1;
                            let color = '';
                            let emoji = '😐';
                            let label = '';
                            
                            if (num === 1) { color = '#4DBCC6'; emoji = '😊'; label = 'Normal'; }
                            else if (num === 2) { color = '#3C9EAA'; emoji = '😊'; label = 'Normal'; }
                            else if (num === 3) { color = '#8B8232'; emoji = '🙂'; label = 'Leve'; }
                            else if (num === 4) { color = '#B4A53C'; emoji = '🙂'; label = 'Leve'; }
                            else if (num === 5) { color = '#FFCC00'; emoji = '😐'; label = 'Moderado'; }
                            else if (num === 6) { color = '#FF9900'; emoji = '😐'; label = 'Moderado'; }
                            else if (num === 7) { color = '#FF6600'; emoji = '😟'; label = 'Fuerte'; }
                            else if (num === 8) { color = '#FF3300'; emoji = '😟'; label = 'Fuerte'; }
                            else if (num === 9) { color = '#FF0000'; emoji = '😡'; label = 'Crítico'; }
                            else { color = '#CC0000'; emoji = '😡'; label = 'Crítico'; }
                            
                            const selected = escala == num ? 'ring-2 ring-blue-500 shadow-lg' : '';
                            return '<div class="flex-1 h-12 rounded-sm ' + selected + ' flex items-center justify-center text-lg font-bold text-white cursor-pointer hover:scale-105 transition-all duration-200" style="background-color: ' + color + ';" onclick="event.stopPropagation(); selectSeverity(' + contadorEntradas + ', ' + num + ')" title="' + num + ' - ' + label + '">' + num + '</div>';
                        }).join('') +
                    '</div>' +
                '</div>' +
                
                // Texto abajo (más grande)
                '<div class="flex justify-between text-sm font-medium text-gray-700 dark:text-gray-300 px-2">' +
                    '<span>Normal</span>' +
                    '<span>Leve</span>' +
                    '<span>Moderado</span>' +
                    '<span>Fuerte</span>' +
                    '<span>Crítico</span>' +
                '</div>' +
                
                // Mostrar selección actual
                (escala ? 
                    '<div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900 rounded-lg border border-blue-200 dark:border-blue-700">' +
                        '<div class="flex items-center justify-center gap-3">' +
                            '<div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-lg" style="background-color: ' + 
                            (escala === 1 ? '#4DBCC6' : escala === 2 ? '#3C9EAA' : escala === 3 ? '#8B8232' : escala === 4 ? '#B4A53C' : 
                             escala === 5 ? '#FFCC00' : escala === 6 ? '#FF9900' : escala === 7 ? '#FF6600' : escala === 8 ? '#FF3300' : 
                             escala === 9 ? '#FF0000' : '#CC0000') + ';">' + escala + '</div>' +
                            '<div class="text-center">' +
                                '<div class="text-sm font-semibold text-blue-700 dark:text-blue-300">Seleccionado: ' + escala + '</div>' +
                                '<div class="text-xs text-blue-600 dark:text-blue-400">' + 
                                (escala <= 2 ? 'Normal' : escala <= 4 ? 'Leve' : escala <= 6 ? 'Moderado' : escala <= 8 ? 'Fuerte' : 'Crítico') + 
                                '</div>' +
                            '</div>' +
                            '<button type="button" onclick="clearSeveritySelection(' + contadorEntradas + ')" class="text-red-500 hover:text-red-700 text-sm font-medium">✕ Limpiar</button>' +
                        '</div>' +
                    '</div>' : '') +
                
                '<input type="hidden" name="entries[' + contadorEntradas + '][escala]" id="escala-' + contadorEntradas + '" value="' + escala + '">' +
            '</div>' +
            
            // Campos de horario, programa y área
            '<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">' +
                '<div>' +
                    '<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Horario <span class="text-red-500">*</span></label>' +
                    '<input type="text" name="entries[' + contadorEntradas + '][hora]" value="' + hora + '" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent dark:bg-gray-700 dark:text-gray-200 transition-all duration-200" placeholder="Ej: 08:30 – 08:50" required>' +
                '</div>' +
                '<div>' +
                    '<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Programa (Magister) <span class="text-red-500">*</span></label>' +
                    '<select name="entries[' + contadorEntradas + '][programa]" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent dark:bg-gray-700 dark:text-gray-200 transition-all duration-200" required>' +
                        programaOptions +
                    '</select>' +
                '</div>' +
                '<div>' +
                    '<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Área <span class="text-red-500">*</span></label>' +
                    '<input type="text" name="entries[' + contadorEntradas + '][area]" value="' + area + '" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent dark:bg-gray-700 dark:text-gray-200 transition-all duration-200" placeholder="Ej: TERRENO" required>' +
                '</div>' +
            '</div>' +
            
            // Ubicación
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
            
            // Observación y tarea
            '<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">' +
                '<div>' +
                    '<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Observación <span class="text-red-500">*</span></label>' +
                    '<textarea name="entries[' + contadorEntradas + '][observation]" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent dark:bg-gray-700 dark:text-gray-200 transition-all duration-200" placeholder="Describe lo observado (mínimo 5 caracteres)..." required>' + observation + '</textarea>' +
                '</div>' +
                '<div>' +
                    '<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tarea (Opcional)</label>' +
                    '<textarea name="entries[' + contadorEntradas + '][tarea]" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-[#4d82bc] focus:border-transparent dark:bg-gray-700 dark:text-gray-200 transition-all duration-200" placeholder="Tarea relacionada...">' + tarea + '</textarea>' +
                '</div>' +
            '</div>' +
            
            // Imagen
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

// Función global para seleccionar severidad
window.selectSeverity = function(entryIndex, severity) {
    const input = document.getElementById('escala-' + entryIndex);
    if (input) {
        input.value = severity;
        
        // Actualizar visualmente la selección
        const container = document.querySelector(`[data-index="${entryIndex}"]`);
        if (container) {
            const severityButtons = container.querySelectorAll('[onclick*="selectSeverity"]');
            severityButtons.forEach(btn => {
                btn.classList.remove('ring-2', 'ring-blue-500');
            });
            
            const selectedBtn = container.querySelector(`[onclick="selectSeverity(${entryIndex}, ${severity})"]`);
            if (selectedBtn) {
                selectedBtn.classList.add('ring-2', 'ring-blue-500');
            }
            
            // Ocultar mensaje de error
            const errorDiv = container.querySelector('.severity-error-message');
            if (errorDiv) {
                errorDiv.style.display = 'none';
            }
            
            // Actualizar o crear el indicador de selección
            updateSeveritySelectionIndicator(entryIndex, severity);
        }
    }
};

// Función para actualizar el indicador de selección
function updateSeveritySelectionIndicator(entryIndex, severity) {
    const container = document.querySelector(`[data-index="${entryIndex}"]`);
    if (!container) return;
    
    const severitySection = container.querySelector('[onclick*="clearSeveritySelection"]');
    if (!severitySection) return;
    
    // Remover indicador existente
    const existingIndicator = severitySection.querySelector('.severity-selection-indicator');
    if (existingIndicator) {
        existingIndicator.remove();
    }
    
    // Crear nuevo indicador
    const indicator = document.createElement('div');
    indicator.className = 'severity-selection-indicator mt-4 p-3 bg-blue-50 dark:bg-blue-900 rounded-lg border border-blue-200 dark:border-blue-700';
    
    let color = '';
    let label = '';
    
    if (severity === 1) { color = '#4DBCC6'; label = 'Normal'; }
    else if (severity === 2) { color = '#3C9EAA'; label = 'Normal'; }
    else if (severity === 3) { color = '#8B8232'; label = 'Leve'; }
    else if (severity === 4) { color = '#B4A53C'; label = 'Leve'; }
    else if (severity === 5) { color = '#FFCC00'; label = 'Moderado'; }
    else if (severity === 6) { color = '#FF9900'; label = 'Moderado'; }
    else if (severity === 7) { color = '#FF6600'; label = 'Fuerte'; }
    else if (severity === 8) { color = '#FF3300'; label = 'Fuerte'; }
    else if (severity === 9) { color = '#FF0000'; label = 'Crítico'; }
    else { color = '#CC0000'; label = 'Crítico'; }
    
    indicator.innerHTML = `
        <div class="flex items-center justify-center gap-3">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-lg" style="background-color: ${color};">${severity}</div>
            <div class="text-center">
                <div class="text-sm font-semibold text-blue-700 dark:text-blue-300">Seleccionado: ${severity}</div>
                <div class="text-xs text-blue-600 dark:text-blue-400">${label}</div>
            </div>
            <button type="button" onclick="clearSeveritySelection(${entryIndex})" class="text-red-500 hover:text-red-700 text-sm font-medium">✕ Limpiar</button>
        </div>
    `;
    
    severitySection.appendChild(indicator);
}

// Función para deseleccionar severidad al hacer clic afuera
window.clearSeveritySelection = function(entryIndex) {
    const input = document.getElementById('escala-' + entryIndex);
    if (input) {
        input.value = '';
        
        // Limpiar selección visual
        const container = document.querySelector(`[data-index="${entryIndex}"]`);
        if (container) {
            const severityButtons = container.querySelectorAll('[onclick*="selectSeverity"]');
            severityButtons.forEach(btn => {
                btn.classList.remove('ring-2', 'ring-blue-500');
            });
            
            // Remover indicador de selección
            const existingIndicator = container.querySelector('.severity-selection-indicator');
            if (existingIndicator) {
                existingIndicator.remove();
            }
            
            // Mostrar mensaje de error
            const errorDiv = container.querySelector('.severity-error-message');
            if (errorDiv) {
                errorDiv.style.display = 'block';
            } else {
                // Crear mensaje de error si no existe
                const severitySection = container.querySelector('[onclick*="clearSeveritySelection"]');
                if (severitySection) {
                    const existingError = severitySection.querySelector('.severity-error-message');
                    if (!existingError) {
                        const errorMessage = document.createElement('div');
                        errorMessage.className = 'severity-error-message text-sm text-red-500 mb-3 font-medium';
                        errorMessage.innerHTML = '⚠️ Debe seleccionar un nivel de severidad';
                        severitySection.insertBefore(errorMessage, severitySection.children[1]);
                    }
                }
            }
        }
    }
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

