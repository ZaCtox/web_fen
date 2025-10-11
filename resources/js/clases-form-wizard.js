// Wizard de Clases con Sesiones Múltiples
let currentStep = 1;
let totalSteps = 4;
let sesionesData = [];

// ============================================
// FUNCIONES DE NAVEGACIÓN (deben estar disponibles globalmente)
// ============================================
window.nextStep = function() {
    if (currentStep < totalSteps && validateCurrentStep()) {
        currentStep++;
        showStep(currentStep);
        updateProgress(currentStep);
        
        // Si avanza al paso 3, generar sesiones
        if (currentStep === 3) {
            generarSesiones();
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

window.navigateToStep = function(step) {
    if (step <= currentStep || validateCurrentStep()) {
        currentStep = step;
        showStep(step);
        updateProgress(step);
    }
}

window.cancelForm = function() { 
    if (window.hasUnsavedChanges && window.hasUnsavedChanges()) {
        window.showUnsavedChangesModal(window.location.origin + '/clases');
    } else {
        window.location.href = window.location.origin + '/clases';
    }
}

window.submitForm = function() { 
    console.log('🚀 submitForm() llamado');
    console.log('📍 currentStep:', currentStep, 'totalSteps:', totalSteps);
    
    if (validateCurrentStep()) { 
        console.log('✅ Validación pasada');
        
        const form = document.querySelector('.hci-form');
        if (!form) {
            console.error('❌ No se encontró el formulario .hci-form');
            return;
        }
        
        console.log('📋 Formulario encontrado:', form);
        console.log('📋 Action:', form.action);
        console.log('📋 Method:', form.method);
        
        // Revisar datos del formulario
        const formData = new FormData(form);
        console.log('📦 Datos del formulario:');
        for (let [key, value] of formData.entries()) {
            console.log(`  ${key}:`, value);
        }
        
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
        
        console.log('🔄 Enviando formulario...');
        form.submit();
        console.log('✅ form.submit() ejecutado');
    } else {
        console.warn('⚠️ Validación falló, no se puede enviar el formulario');
    }
}

function showStep(step) {
    document.querySelectorAll('.hci-form-section').forEach(s => s.classList.remove('active'));
    const sec = document.getElementById(getSectionId(step));
    if (sec) {
        sec.classList.add('active');
        sec.scrollIntoView({behavior:'smooth', block:'start'});
    }
    
    if (window.updateWizardProgressSteps) {
        window.updateWizardProgressSteps(step);
    }
}

function updateProgress(step) {
    const pct = (step/totalSteps)*100;
    const bar = document.getElementById('progress-bar');
    if (bar) bar.style.height = pct+'%';
    
    const txt = document.getElementById('current-step');
    if (txt) txt.textContent = `Paso ${step} de ${totalSteps}`;
    
    const per = document.getElementById('progress-percentage');
    if (per) per.textContent = Math.round(pct)+'%';
    
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
    const editing = totalSteps === 2;
    if (editing) {
        return ['general', 'resumen'][step-1];
    }
    return ['general', 'config-sesiones', 'detalles-sesiones', 'resumen'][step-1];
}

function validateCurrentStep() {
    console.log('🔍 Validando paso:', currentStep);
    const sec = document.getElementById(getSectionId(currentStep));
    if (!sec) {
        console.log('⚠️ Sección no encontrada para paso', currentStep);
        return true;
    }

    const reqs = sec.querySelectorAll('input[required], select[required], textarea[required]');
    console.log(`📋 Campos requeridos encontrados: ${reqs.length}`);
    let ok = true;
    
    reqs.forEach(f => {
        // Especial handling para checkboxes
        if (f.type === 'checkbox' && f.name === 'dias_semana[]') {
            const anyChecked = document.querySelectorAll('input[name="dias_semana[]"]:checked').length > 0;
            console.log(`☑️ Días seleccionados:`, anyChecked);
            if (!anyChecked) {
                ok = false;
                showStepError('Debes seleccionar al menos un día de la semana.');
            }
            return;
        }

        if (!f.value.trim()) {
            console.log(`❌ Campo vacío: ${f.name || f.id}`);
            ok = false;
            validateField(f);
        } else {
            console.log(`✅ Campo OK: ${f.name || f.id} = ${f.value}`);
            clearFieldError(f);
        }
    });
    
    // Validación especial para el paso 3 (sesiones)
    if (currentStep === 3) {
        console.log('🔍 Validando sesiones...');
        const sesionesValidas = validarTodasLasSesiones();
        console.log(`📊 Sesiones válidas: ${sesionesValidas}`);
        if (!sesionesValidas) {
            ok = false;
        }
    }
    
    if (!ok) {
        console.log('❌ Validación falló');
        showStepError('Completa los campos requeridos.');
    } else {
        console.log('✅ Validación exitosa para paso', currentStep);
    }
    
    return ok;
}

function showStepError(msg) {
    let d = document.getElementById('step-error-message');
    if (!d) {
        d = document.createElement('div');
        d.id = 'step-error-message';
        d.className = 'hci-error-message';
        const container = document.querySelector('.hci-container');
        if (container) {
            container.insertBefore(d, document.querySelector('.hci-wizard-layout'));
        }
    }
    d.innerHTML = `<div class="hci-error-content"><span class="hci-error-icon">⚠️</span><span class="hci-error-text">${msg}</span></div>`;
    setTimeout(() => d && d.remove(), 4000);
}

function validateField(f) {
    const ok = f.checkValidity();
    const wrap = f.closest('.hci-field');
    const err = wrap?.querySelector('.hci-field-error');
    
    if (!ok) {
        f.classList.add('border-red-500');
        f.classList.remove('border-gray-300');
        if (!err) {
            const e = document.createElement('div');
            e.className = 'hci-field-error';
            e.textContent = f.validationMessage || 'Campo requerido';
            wrap?.appendChild(e);
        }
    } else {
        clearFieldError(f);
    }
}

function clearFieldError(f) {
    f.classList.remove('border-red-500');
    f.classList.add('border-gray-300');
    const wrap = f.closest('.hci-field');
    const err = wrap?.querySelector('.hci-field-error');
    if (err) err.remove();
}

// ============================================
// GENERACIÓN DINÁMICA DE SESIONES
// ============================================

function generarSesiones() {
    const numSesiones = parseInt(document.getElementById('num_sesiones')?.value) || 0;
    const fechaInicio = document.getElementById('fecha_inicio')?.value;
    const diasSeleccionados = Array.from(document.querySelectorAll('input[name="dias_semana[]"]:checked')).map(cb => cb.value);

    if (!numSesiones || !fechaInicio || diasSeleccionados.length === 0) {
        return;
    }

    // Calcular fechas de las sesiones
    const fechas = calcularFechasSesiones(fechaInicio, diasSeleccionados, numSesiones);
    sesionesData = fechas;

    // Renderizar el HTML de las sesiones
    renderizarSesiones(fechas);
    
    // Actualizar resumen
    updateSummary();
}

function calcularFechasSesiones(fechaInicio, diasSeleccionados, numSesiones) {
    const fechas = [];
    let fecha = new Date(fechaInicio + 'T00:00:00');
    const diasSemanaMap = { 'Viernes': 5, 'Sábado': 6 };
    const diasNumeros = diasSeleccionados.map(d => diasSemanaMap[d]).sort();
    
    let sesionesGeneradas = 0;
    
    // Ajustar la fecha inicial al primer día seleccionado
    while (sesionesGeneradas < numSesiones) {
        const diaSemana = fecha.getDay();
        
        if (diasNumeros.includes(diaSemana)) {
            const diaNombre = Object.keys(diasSemanaMap).find(key => diasSemanaMap[key] === diaSemana);
            fechas.push({
                fecha: fecha.toISOString().split('T')[0],
                dia: diaNombre,
                numero: sesionesGeneradas + 1
            });
            sesionesGeneradas++;
        }
        
        fecha.setDate(fecha.getDate() + 1);
    }
    
    return fechas;
}

function renderizarSesiones(fechas) {
    const container = document.getElementById('sesiones-container');
    if (!container) return;

    const roomIdGlobal = document.getElementById('room_id')?.value || '';
    const urlZoomGlobal = document.getElementById('url_zoom')?.value || '';
    const periodId = document.getElementById('period_id')?.value || '';

    container.innerHTML = fechas.map((sesion, index) => `
        <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg p-6" data-sesion-index="${index}">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Sesión #${sesion.numero} - ${sesion.dia} ${formatearFecha(sesion.fecha)}
                </h4>
                <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200">
                    ${sesion.dia}
                </span>
            </div>
            
            <input type="hidden" name="sesiones[${index}][fecha]" value="${sesion.fecha}">
            <input type="hidden" name="sesiones[${index}][dia]" value="${sesion.dia}">
            <input type="hidden" name="sesiones[${index}][estado]" value="pendiente">
            <input type="hidden" name="sesiones[${index}][numero_sesion]" value="${sesion.numero}">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="hci-field">
                    <label class="hci-label" for="sesiones_${index}_hora_inicio">
                        Hora Inicio <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="sesiones[${index}][hora_inicio]" id="sesiones_${index}_hora_inicio" 
                           class="hci-input" required
                           value="${sesion.dia === 'Viernes' ? '18:30' : '09:00'}">
                </div>
                
                <div class="hci-field">
                    <label class="hci-label" for="sesiones_${index}_hora_fin">
                        Hora Fin <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="sesiones[${index}][hora_fin]" id="sesiones_${index}_hora_fin" 
                           class="hci-input" required
                           value="${sesion.dia === 'Viernes' ? '21:30' : '14:00'}">
                </div>
                
                <div class="hci-field">
                    <label class="hci-label" for="sesiones_${index}_modalidad">
                        Modalidad <span class="text-red-500">*</span>
                    </label>
                    <select name="sesiones[${index}][modalidad]" id="sesiones_${index}_modalidad" 
                            class="hci-select sesion-modalidad" data-index="${index}" required>
                        <option value="">-- Seleccionar --</option>
                        <option value="presencial">Presencial</option>
                        <option value="hibrida" ${sesion.dia === 'Sábado' ? 'selected' : ''}>Híbrida</option>
                        <option value="online" ${sesion.dia === 'Viernes' ? 'selected' : ''}>Online</option>
                    </select>
                </div>
                
                <div class="hci-field sesion-room-field" data-index="${index}" style="display: ${sesion.dia === 'Viernes' ? 'none' : 'block'};">
                    <label class="hci-label" for="sesiones_${index}_room_id">
                        Sala ${sesion.dia === 'Sábado' || sesion.dia === 'Viernes' ? '' : '<span class="text-red-500">*</span>'}
                    </label>
                    <select name="sesiones[${index}][room_id]" id="sesiones_${index}_room_id" 
                            class="hci-select sesion-room-select">
                        <option value="">-- Usar sala principal --</option>
                        ${getRoomsOptions(roomIdGlobal)}
                    </select>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Dejar vacío para usar la sala principal</p>
                </div>
                
                <div class="hci-field md:col-span-2">
                    <label class="hci-label" for="sesiones_${index}_url_zoom">
                        URL Zoom ${sesion.dia === 'Viernes' ? '<span class="text-red-500">*</span>' : ''}
                    </label>
                    <input type="url" name="sesiones[${index}][url_zoom]" id="sesiones_${index}_url_zoom" 
                           class="hci-input sesion-zoom-input" placeholder="https://zoom.us/j/..."
                           value="${urlZoomGlobal}">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Dejar vacío para usar el Zoom principal</p>
                </div>
                
                <div class="hci-field md:col-span-3">
                    <label class="hci-label" for="sesiones_${index}_observaciones">
                        Observaciones (opcional)
                    </label>
                    <textarea name="sesiones[${index}][observaciones]" id="sesiones_${index}_observaciones" 
                              class="hci-input" rows="2" 
                              placeholder="Notas adicionales sobre esta sesión..."></textarea>
                </div>
            </div>
            
            {{-- Botones para ver horarios y salas disponibles --}}
            <div class="mt-4 flex gap-2">
                <button type="button" 
                        class="btn-ver-horarios px-3 py-2 text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors flex items-center gap-2"
                        data-index="${index}"
                        title="Ver horarios disponibles en esta sala">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    Ver horarios
                </button>
                <button type="button" 
                        class="btn-ver-salas px-3 py-2 text-xs font-medium text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/40 transition-colors flex items-center gap-2"
                        data-index="${index}"
                        title="Ver salas disponibles en este horario">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    Ver salas
                </button>
            </div>
            
            {{-- Slots de horarios disponibles --}}
            <div id="horarios_${index}" class="mt-3 hidden">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <h5 class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        Horarios disponibles (haz clic para seleccionar):
                    </h5>
                    <div id="slots_${index}" class="flex flex-wrap gap-2"></div>
                </div>
            </div>
            
            {{-- Salas disponibles --}}
            <div id="salas_${index}" class="mt-3 hidden">
                <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                    <h5 class="text-sm font-semibold text-purple-900 dark:text-purple-100 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        Salas disponibles (haz clic para seleccionar):
                    </h5>
                    <div id="salas_list_${index}" class="grid grid-cols-1 md:grid-cols-2 gap-2"></div>
                </div>
            </div>
            
            {{-- Indicador de disponibilidad --}}
            <div id="disponibilidad_${index}" class="mt-4 hidden">
                <div class="flex items-center gap-2 p-3 rounded-lg disponibilidad-loading hidden">
                    <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Verificando disponibilidad...</span>
                </div>
                
                <div class="flex items-start gap-2 p-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 disponibilidad-disponible hidden">
                    <svg class="w-5 h-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">Sala disponible</p>
                        <p class="text-xs text-green-600 dark:text-green-300 mt-1">No hay conflictos de horario</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-2 p-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 disponibilidad-ocupada hidden">
                    <svg class="w-5 h-5 text-red-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">Sala ocupada</p>
                        <p class="text-xs text-red-600 dark:text-red-300 mt-1 disponibilidad-conflictos"></p>
                    </div>
                </div>
            </div>
        </div>
    `).join('');

    // Agregar event listeners para las modalidades
    container.querySelectorAll('.sesion-modalidad').forEach(select => {
        select.addEventListener('change', (e) => {
            const index = e.target.dataset.index;
            toggleSalaZoomPorModalidad(index);
            verificarDisponibilidadSesion(index, periodId, roomIdGlobal);
        });
    });

    // Event listeners para verificar disponibilidad cuando cambian campos relevantes
    fechas.forEach((_, index) => {
        const horaInicio = document.getElementById(`sesiones_${index}_hora_inicio`);
        const horaFin = document.getElementById(`sesiones_${index}_hora_fin`);
        const sala = document.getElementById(`sesiones_${index}_room_id`);
        
        if (horaInicio) horaInicio.addEventListener('change', () => verificarDisponibilidadSesion(index, periodId, roomIdGlobal));
        if (horaFin) horaFin.addEventListener('change', () => verificarDisponibilidadSesion(index, periodId, roomIdGlobal));
        if (sala) sala.addEventListener('change', () => verificarDisponibilidadSesion(index, periodId, roomIdGlobal));
        
        // Inicializar
        toggleSalaZoomPorModalidad(index);
        verificarDisponibilidadSesion(index, periodId, roomIdGlobal);
    });

    // Event listeners para botones de ver horarios y salas disponibles
    container.querySelectorAll('.btn-ver-horarios').forEach(btn => {
        btn.addEventListener('click', () => {
            const index = parseInt(btn.dataset.index);
            mostrarHorariosDisponibles(index, periodId, roomIdGlobal);
        });
    });

    container.querySelectorAll('.btn-ver-salas').forEach(btn => {
        btn.addEventListener('click', () => {
            const index = parseInt(btn.dataset.index);
            mostrarSalasDisponibles(index, periodId, roomIdGlobal);
        });
    });
}

function getRoomsOptions(selectedId) {
    const roomSelect = document.getElementById('room_id');
    if (!roomSelect) return '';
    
    return Array.from(roomSelect.options)
        .filter(opt => opt.value !== '')
        .map(opt => `<option value="${opt.value}" ${opt.value === selectedId ? 'selected' : ''}>${opt.text}</option>`)
        .join('');
}

function toggleSalaZoomPorModalidad(index) {
    const modalidadSelect = document.getElementById(`sesiones_${index}_modalidad`);
    const modalidad = modalidadSelect?.value;
    
    const roomField = document.querySelector(`.sesion-room-field[data-index="${index}"]`);
    const roomSelect = document.getElementById(`sesiones_${index}_room_id`);
    const zoomInput = document.getElementById(`sesiones_${index}_url_zoom`);
    
    if (modalidad === 'online') {
        // Online: ocultar sala, zoom requerido
        if (roomField) roomField.style.display = 'none';
        if (roomSelect) roomSelect.removeAttribute('required');
        if (zoomInput) {
            zoomInput.setAttribute('required', 'required');
            const label = zoomInput.closest('.hci-field')?.querySelector('label');
            if (label) label.innerHTML = 'URL Zoom <span class="text-red-500">*</span>';
        }
    } else if (modalidad === 'hibrida') {
        // Híbrida: sala visible pero no requerida, zoom visible pero no requerido
        if (roomField) roomField.style.display = 'block';
        if (roomSelect) roomSelect.removeAttribute('required');
        if (zoomInput) {
            zoomInput.removeAttribute('required');
            const label = zoomInput.closest('.hci-field')?.querySelector('label');
            if (label) label.innerHTML = 'URL Zoom';
        }
    } else if (modalidad === 'presencial') {
        // Presencial: sala visible pero no requerida, zoom no requerido
        if (roomField) roomField.style.display = 'block';
        if (roomSelect) roomSelect.removeAttribute('required');
        if (zoomInput) {
            zoomInput.removeAttribute('required');
            const label = zoomInput.closest('.hci-field')?.querySelector('label');
            if (label) label.innerHTML = 'URL Zoom';
        }
    }
}

function validarTodasLasSesiones() {
    let todasValidas = true;
    
    sesionesData.forEach((_, index) => {
        const horaInicio = document.getElementById(`sesiones_${index}_hora_inicio`)?.value;
        const horaFin = document.getElementById(`sesiones_${index}_hora_fin`)?.value;
        const modalidad = document.getElementById(`sesiones_${index}_modalidad`)?.value;
        
        if (!horaInicio || !horaFin || !modalidad) {
            todasValidas = false;
        }
        
        if (horaInicio && horaFin && horaInicio >= horaFin) {
            todasValidas = false;
            showStepError(`Sesión ${index + 1}: La hora fin debe ser posterior a la hora inicio`);
        }
    });
    
    return todasValidas;
}

function formatearFecha(fechaStr) {
    const fecha = new Date(fechaStr + 'T00:00:00');
    return fecha.toLocaleDateString('es-CL', { day: '2-digit', month: '2-digit', year: 'numeric' });
}

// ============================================
// ACTUALIZACIÓN DEL RESUMEN
// ============================================

function updateSummary() {
    const getSelText = (sel) => sel?.options?.[sel.selectedIndex]?.textContent?.trim() || '';
    
    // Información General
    const programa = getSelText(document.getElementById('magister'));
    const curso = getSelText(document.getElementById('course_id'));
    const anio = document.getElementById('anio')?.value || '';
    const trimestre = document.getElementById('trimestre')?.value || '';
    const periodo = anio && trimestre ? `${anio} - Trimestre ${trimestre}` : '—';
    const encargado = document.getElementById('encargado')?.value || '';
    const salaPrincipal = getSelText(document.getElementById('room_id')) || 'Sin sala asignada';
    const zoomPrincipal = document.getElementById('url_zoom')?.value || 'No asignado';
    
    // Actualizar resumen general
    const byId = id => document.getElementById(id);
    if (byId('resumen-programa')) byId('resumen-programa').textContent = programa || '—';
    if (byId('resumen-curso')) byId('resumen-curso').textContent = curso || '—';
    if (byId('resumen-periodo')) byId('resumen-periodo').textContent = periodo;
    if (byId('resumen-encargado')) byId('resumen-encargado').textContent = encargado || '—';
    if (byId('resumen-sala-principal')) byId('resumen-sala-principal').textContent = salaPrincipal;
    if (byId('resumen-zoom-principal')) byId('resumen-zoom-principal').textContent = zoomPrincipal;
    
    // Actualizar resumen de sesiones
    const totalSesionesEl = byId('resumen-total-sesiones');
    if (totalSesionesEl) totalSesionesEl.textContent = sesionesData.length;
    
    const resumenSesionesLista = byId('resumen-sesiones-lista');
    if (resumenSesionesLista && sesionesData.length > 0) {
        resumenSesionesLista.innerHTML = sesionesData.map((sesion, index) => {
            const horaInicio = document.getElementById(`sesiones_${index}_hora_inicio`)?.value || '—';
            const horaFin = document.getElementById(`sesiones_${index}_hora_fin`)?.value || '—';
            const modalidad = document.getElementById(`sesiones_${index}_modalidad`)?.value || '—';
            const modalidadBadgeClass = {
                'online': 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200',
                'presencial': 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200',
                'hibrida': 'bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-200'
            }[modalidad] || 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200';
            
            return `
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/30 rounded border border-gray-200 dark:border-gray-600">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            Sesión ${sesion.numero} - ${sesion.dia} ${formatearFecha(sesion.fecha)}
                        </p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                            ${horaInicio} - ${horaFin}
                        </p>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded ${modalidadBadgeClass}">
                        ${modalidad.charAt(0).toUpperCase() + modalidad.slice(1)}
                    </span>
                </div>
            `;
        }).join('');
    }
}

// ============================================
// VERIFICACIÓN DE DISPONIBILIDAD DE SALA
// ============================================

let disponibilidadTimers = {};
let horariosCache = {};

async function verificarDisponibilidadSesion(index, periodId, roomIdGlobal) {
    const modalidad = document.getElementById(`sesiones_${index}_modalidad`)?.value;
    const horaInicio = document.getElementById(`sesiones_${index}_hora_inicio`)?.value;
    const horaFin = document.getElementById(`sesiones_${index}_hora_fin`)?.value;
    const roomIdSesion = document.getElementById(`sesiones_${index}_room_id`)?.value;
    const roomId = roomIdSesion || roomIdGlobal;
    const fecha = sesionesData[index]?.fecha;
    const dia = sesionesData[index]?.dia;
    
    const container = document.getElementById(`disponibilidad_${index}`);
    if (!container) return;
    
    const loadingEl = container.querySelector('.disponibilidad-loading');
    const disponibleEl = container.querySelector('.disponibilidad-disponible');
    const ocupadaEl = container.querySelector('.disponibilidad-ocupada');
    
    // Ocultar todos
    const hideAll = () => {
        container.classList.add('hidden');
        loadingEl.classList.add('hidden');
        disponibleEl.classList.add('hidden');
        ocupadaEl.classList.add('hidden');
    };
    
    // Si es online o faltan datos, no verificar
    if (modalidad === 'online' || !horaInicio || !horaFin || !roomId || !periodId || !fecha) {
        hideAll();
        return;
    }
    
    // Debounce: cancelar verificación anterior
    if (disponibilidadTimers[index]) {
        clearTimeout(disponibilidadTimers[index]);
    }
    
    disponibilidadTimers[index] = setTimeout(async () => {
        try {
            // Mostrar loading
            container.classList.remove('hidden');
            hideAll();
            container.classList.remove('hidden');
            loadingEl.classList.remove('hidden');
            
            const params = new URLSearchParams({
                period_id: periodId,
                room_id: roomId,
                dia: dia,
                hora_inicio: horaInicio,
                hora_fin: horaFin,
                modality: modalidad
            });
            
            const response = await fetch(`/salas/disponibilidad?${params}`, {
                headers: { 'Accept': 'application/json' }
            });
            
            const data = await response.json();
            
            // Ocultar loading
            loadingEl.classList.add('hidden');
            
            if (data.available) {
                disponibleEl.classList.remove('hidden');
            } else {
                ocupadaEl.classList.remove('hidden');
                const conflictosEl = ocupadaEl.querySelector('.disponibilidad-conflictos');
                if (conflictosEl && data.conflicts && data.conflicts.length > 0) {
                    const conflictosHTML = data.conflicts.map(c => {
                        const modalidadBadge = c.modalidad === 'online' ? '🌐' : 
                                             c.modalidad === 'hibrida' ? '🔀' : '🏢';
                        
                        // Formatear fecha correctamente
                        let fechaFormateada = c.fecha;
                        try {
                            const fecha = new Date(c.fecha);
                            fechaFormateada = fecha.toLocaleDateString('es-CL', { 
                                year: 'numeric', 
                                month: '2-digit', 
                                day: '2-digit' 
                            });
                        } catch (e) {
                            console.error('Error formateando fecha:', e);
                        }
                        
                        return `
                            <div class="mt-2 text-xs">
                                ${modalidadBadge} <strong>${c.course_nombre}</strong><br>
                                👤 ${c.encargado}<br>
                                🕐 ${c.hora_inicio} - ${c.hora_fin} (${c.dia})<br>
                                📅 Sesión del ${fechaFormateada}
                            </div>
                        `;
                    }).join('<hr class="my-2 border-red-200 dark:border-red-700">');
                    conflictosEl.innerHTML = `<strong>⚠️ Conflicto de horario detectado:</strong>${conflictosHTML}
                        <p class="mt-2 text-xs italic">Esta sala ya tiene una clase programada en el mismo día y horario.</p>`;
                } else {
                    conflictosEl.textContent = 'Hay un conflicto de horario en esta sala';
                }
            }
            
        } catch (error) {
            console.error('Error al verificar disponibilidad:', error);
            hideAll();
        }
    }, 500); // 500ms de debounce
}

// ============================================
// SUGERENCIAS DE HORARIOS Y SALAS DISPONIBLES
// ============================================

async function mostrarHorariosDisponibles(index, periodId, roomIdGlobal) {
    const modalidad = document.getElementById(`sesiones_${index}_modalidad`)?.value;
    const roomIdSesion = document.getElementById(`sesiones_${index}_room_id`)?.value;
    const roomId = roomIdSesion || roomIdGlobal;
    const dia = sesionesData[index]?.dia;
    
    const horariosContainer = document.getElementById(`horarios_${index}`);
    const slotsContainer = document.getElementById(`slots_${index}`);
    
    if (!horariosContainer || !slotsContainer) return;
    
    // Si es online o no hay sala, no mostrar
    if (modalidad === 'online' || !roomId || !dia) {
        horariosContainer.classList.add('hidden');
        return;
    }
    
    try {
        // Mostrar loading
        slotsContainer.innerHTML = '<span class="text-sm text-gray-600 dark:text-gray-400">Buscando horarios disponibles...</span>';
        horariosContainer.classList.remove('hidden');
        
        const params = new URLSearchParams({
            period_id: periodId,
            room_id: roomId,
            dia: dia,
            modality: modalidad,
            desde: '08:00',
            hasta: '22:00',
            min_block: 60,
            blocks: 1
        });
        
        const response = await fetch(`/salas/horarios?${params}`, {
            headers: { 'Accept': 'application/json' }
        });
        
        const data = await response.json();
        
        if (data.slots && data.slots.length > 0) {
            slotsContainer.innerHTML = data.slots.map(slot => {
                // Generar opciones cada 15 minutos dentro del slot
                const opciones = [];
                let cursor = slot.start;
                
                while (addMinutes(cursor, 60) <= slot.end) {
                    const inicio = cursor;
                    const fin = addMinutes(cursor, 180); // 3 horas por defecto
                    
                    if (fin <= slot.end) {
                        opciones.push({ inicio, fin });
                    }
                    cursor = addMinutes(cursor, 15);
                }
                
                return opciones.map(op => `
                    <button type="button"
                            class="slot-btn px-3 py-2 text-sm font-medium text-green-700 dark:text-green-300 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/40 transition-colors"
                            data-index="${index}"
                            data-inicio="${op.inicio}"
                            data-fin="${op.fin}"
                            title="Haz clic para usar este horario">
                        🕐 ${op.inicio} - ${op.fin}
                    </button>
                `).join('');
            }).join('');
            
            // Agregar event listeners a los botones de slots
            slotsContainer.querySelectorAll('.slot-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const idx = parseInt(btn.dataset.index);
                    const inicio = btn.dataset.inicio;
                    const fin = btn.dataset.fin;
                    
                    document.getElementById(`sesiones_${idx}_hora_inicio`).value = inicio;
                    document.getElementById(`sesiones_${idx}_hora_fin`).value = fin;
                    
                    // Ocultar horarios y verificar disponibilidad
                    horariosContainer.classList.add('hidden');
                    verificarDisponibilidadSesion(idx, periodId, roomIdGlobal);
                    
                    // Feedback visual
                    btn.classList.add('ring-2', 'ring-green-500');
                    setTimeout(() => btn.classList.remove('ring-2', 'ring-green-500'), 1000);
                });
            });
        } else {
            slotsContainer.innerHTML = '<span class="text-sm text-yellow-600 dark:text-yellow-400">⚠️ No hay horarios disponibles con estas características</span>';
        }
    } catch (error) {
        console.error('Error al obtener horarios disponibles:', error);
        slotsContainer.innerHTML = '<span class="text-sm text-red-600 dark:text-red-400">❌ Error al cargar horarios</span>';
    }
}

async function mostrarSalasDisponibles(index, periodId, roomIdGlobal) {
    const modalidad = document.getElementById(`sesiones_${index}_modalidad`)?.value;
    const horaInicio = document.getElementById(`sesiones_${index}_hora_inicio`)?.value;
    const horaFin = document.getElementById(`sesiones_${index}_hora_fin`)?.value;
    const dia = sesionesData[index]?.dia;
    
    const salasContainer = document.getElementById(`salas_${index}`);
    const salasListContainer = document.getElementById(`salas_list_${index}`);
    
    if (!salasContainer || !salasListContainer) return;
    
    // Si es online o faltan horarios, no mostrar
    if (modalidad === 'online' || !horaInicio || !horaFin || !dia) {
        salasContainer.classList.add('hidden');
        if (modalidad === 'online') {
            salasListContainer.innerHTML = '<span class="text-sm text-gray-600 dark:text-gray-400">Las clases online no requieren sala física</span>';
            salasContainer.classList.remove('hidden');
        }
        return;
    }
    
    try {
        // Mostrar loading
        salasListContainer.innerHTML = '<span class="text-sm text-gray-600 dark:text-gray-400">Buscando salas disponibles...</span>';
        salasContainer.classList.remove('hidden');
        
        const params = new URLSearchParams({
            dia: dia,
            hora_inicio: horaInicio,
            hora_fin: horaFin,
            modalidad: modalidad
        });
        
        const response = await fetch(`/salas/disponibles?${params}`, {
            headers: { 'Accept': 'application/json' }
        });
        
        const data = await response.json();
        
        if (data.salas && data.salas.length > 0) {
            salasListContainer.innerHTML = data.salas.map(sala => `
                <button type="button"
                        class="sala-btn text-left p-3 bg-white dark:bg-gray-800 border-2 border-purple-200 dark:border-purple-700 rounded-lg hover:border-purple-400 dark:hover:border-purple-500 hover:shadow-md transition-all"
                        data-index="${index}"
                        data-sala-id="${sala.id}"
                        data-sala-name="${sala.name}"
                        title="Haz clic para seleccionar esta sala">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="font-semibold text-purple-900 dark:text-purple-100">${sala.name}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                📍 ${sala.location}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                👥 Capacidad: ${sala.capacity} personas
                            </p>
                        </div>
                        <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </button>
            `).join('');
            
            // Agregar event listeners a los botones de salas
            salasListContainer.querySelectorAll('.sala-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const idx = parseInt(btn.dataset.index);
                    const salaId = btn.dataset.salaId;
                    const salaName = btn.dataset.salaName;
                    
                    // Seleccionar la sala
                    const salaSelect = document.getElementById(`sesiones_${idx}_room_id`);
                    if (salaSelect) {
                        salaSelect.value = salaId;
                    }
                    
                    // Ocultar salas y verificar disponibilidad
                    salasContainer.classList.add('hidden');
                    verificarDisponibilidadSesion(idx, periodId, roomIdGlobal);
                    
                    // Feedback visual
                    btn.classList.add('ring-2', 'ring-purple-500');
                    setTimeout(() => btn.classList.remove('ring-2', 'ring-purple-500'), 1000);
                    
                    // Notificación
                    console.log(`✅ Sala seleccionada: ${salaName}`);
                });
            });
        } else {
            salasListContainer.innerHTML = `
                <div class="col-span-2 text-center p-6">
                    <svg class="w-12 h-12 mx-auto text-yellow-500 mb-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-yellow-600 dark:text-yellow-400 font-medium">No hay salas disponibles</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Todas las salas están ocupadas en este horario (${horaInicio} - ${horaFin})</p>
                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-2">💡 Prueba cambiando el horario o día</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error al obtener salas disponibles:', error);
        salasListContainer.innerHTML = '<span class="text-sm text-red-600 dark:text-red-400">❌ Error al cargar salas</span>';
    }
}

function addMinutes(hhmm, mins) {
    const [H, M] = (hhmm || '00:00').split(':').map(Number);
    const d = new Date(2000, 1, 1, H, M, 0);
    d.setMinutes(d.getMinutes() + mins);
    const pad = n => (n < 10 ? '0' + n : '' + n);
    return `${pad(d.getHours())}:${pad(d.getMinutes())}`;
}

// ============================================
// NOTA: La lógica de Magister -> Cursos -> Período
// está manejada por resources/js/clases/form.js
// ============================================

// ============================================
// INICIALIZACIÓN
// ============================================
document.addEventListener('DOMContentLoaded', () => {
    const editing = document.querySelector('[data-editing="true"]') !== null;
    totalSteps = editing ? 2 : 4;

    // Prevenir submit del formulario con Enter (excepto en textareas)
    const form = document.querySelector('.hci-form');
    if (form) {
        form.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
                return false;
            }
        });
    }

    // Si hay errores de validación, ir al primer paso con error
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

    // Event listeners
    const inputs = document.querySelectorAll('.hci-input, .hci-select, .hci-textarea, select, input');
    inputs.forEach(el => el.addEventListener('input', updateSummary));
    document.addEventListener('change', updateSummary);

    // Listeners específicos para generación de sesiones
    if (!editing) {
        const numSesionesInput = document.getElementById('num_sesiones');
        const fechaInicioInput = document.getElementById('fecha_inicio');
        const diasCheckboxes = document.querySelectorAll('input[name="dias_semana[]"]');

        [numSesionesInput, fechaInicioInput, ...diasCheckboxes].forEach(el => {
            if (el) el.addEventListener('change', generarSesiones);
        });
    }

    updateSummary();
});
