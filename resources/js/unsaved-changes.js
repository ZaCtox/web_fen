/**
 * Unsaved Changes Detector - Prevención de Pérdida de Datos
 * Detecta cambios en formularios y alerta al usuario antes de salir
 */

document.addEventListener('DOMContentLoaded', function() {
    let hasUnsavedChanges = false;
    const forms = document.querySelectorAll('form:not(.no-unsaved-check)');
    
    // ==========================================
    // 1. Detectar Cambios en Formularios
    // ==========================================
    forms.forEach(form => {
        // Ignorar formularios de búsqueda, filtros, y logout
        if (form.method.toLowerCase() === 'get' || 
            form.action.includes('logout') ||
            form.classList.contains('form-eliminar') ||
            form.id === 'form-filtros') {
            return;
        }
        
        const inputs = form.querySelectorAll('input:not([type="hidden"]):not([type="submit"]), select, textarea');
        
        inputs.forEach(input => {
            // Guardar valor inicial
            const initialValue = input.type === 'checkbox' ? input.checked : input.value;
            input.dataset.initialValue = initialValue;
            
            // Detectar cambios
            input.addEventListener('input', function() {
                checkForChanges(form);
            });
            
            input.addEventListener('change', function() {
                checkForChanges(form);
            });
        });
        
        // Marcar como guardado al enviar
        form.addEventListener('submit', function() {
            hasUnsavedChanges = false;
            removeUnsavedIndicator();
        });
    });
    
    function checkForChanges(form) {
        const inputs = form.querySelectorAll('input:not([type="hidden"]):not([type="submit"]), select, textarea');
        let changed = false;
        
        inputs.forEach(input => {
            const currentValue = input.type === 'checkbox' ? input.checked : input.value;
            const initialValue = input.dataset.initialValue;
            
            if (String(currentValue) !== String(initialValue)) {
                changed = true;
            }
        });
        
        hasUnsavedChanges = changed;
        
        if (changed) {
            showUnsavedIndicator();
        } else {
            removeUnsavedIndicator();
        }
    }
    
    // ==========================================
    // 2. Indicador Visual de Cambios Pendientes
    // ==========================================
    function showUnsavedIndicator() {
        // Buscar o crear indicador
        let indicator = document.getElementById('unsaved-changes-indicator');
        
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.id = 'unsaved-changes-indicator';
            indicator.className = 'unsaved-indicator';
            indicator.innerHTML = `
                <div class="flex items-center gap-2">
                    <div class="unsaved-dot"></div>
                    <span class="text-sm font-medium">Cambios sin guardar</span>
                </div>
            `;
            
            // Insertarlo en el header
            const header = document.querySelector('header');
            if (header) {
                header.appendChild(indicator);
            }
        }
        
        // Mostrar con animación
        setTimeout(() => indicator.classList.add('show'), 10);
    }
    
    function removeUnsavedIndicator() {
        const indicator = document.getElementById('unsaved-changes-indicator');
        if (indicator) {
            indicator.classList.remove('show');
            setTimeout(() => indicator.remove(), 300);
        }
    }
    
    // ==========================================
    // 3. Prevenir Navegación con Cambios (SweetAlert2)
    // ==========================================
    // Prevenir navegación interna (links)
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        
        if (!link || !hasUnsavedChanges) return;
        
        // Excepciones
        if (link.classList.contains('no-unsaved-check') ||
            link.closest('form') ||
            link.href.includes('#') ||
            link.target === '_blank') {
            return;
        }
        
        e.preventDefault();
        
        showUnsavedChangesModal(link.href);
    }, true);
    
    // ==========================================
    // 4. Función Global para Modal de Cambios sin Guardar
    // ==========================================
    window.showUnsavedChangesModal = function(targetUrl = null) {
        Swal.fire({
            title: 'Cambios sin Guardar',
            html: `
                <div class="text-left space-y-3">
                    <div class="flex items-center gap-3 p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-700">
                        <svg class="w-8 h-8 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-gray-100">Tienes cambios sin guardar</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Si sales ahora, perderás los cambios realizados.</p>
                        </div>
                    </div>
                </div>
            `,
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>',
            denyButtonText: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
            cancelButtonText: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            confirmButtonColor: '#22c55e',
            denyButtonColor: '#e57373',
            cancelButtonColor: '#6b7280',
            reverseButtons: true,
            buttonsStyling: true,
            customClass: {
                confirmButton: 'swal2-icon-button',
                denyButton: 'swal2-icon-button',
                cancelButton: 'swal2-icon-button'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Guardar antes de salir
                const submitButton = document.querySelector('button[type="submit"]:not([style*="display: none"])');
                if (submitButton) {
                    hasUnsavedChanges = false;
                    submitButton.click();
                    if (targetUrl) {
                        setTimeout(() => {
                            window.location.href = targetUrl;
                        }, 500);
                    }
                }
            } else if (result.isDenied) {
                // Salir sin guardar
                hasUnsavedChanges = false;
                if (targetUrl) {
                    window.location.href = targetUrl;
                }
            }
        });
    };
    
    // Hacer hasUnsavedChanges accesible globalmente
    window.hasUnsavedChanges = function() {
        return hasUnsavedChanges;
    };
    
    window.clearUnsavedChanges = function() {
        hasUnsavedChanges = false;
        removeUnsavedIndicator();
    };
    
    // ==========================================
    // 5. Atajos de Teclado Relacionados
    // ==========================================
    document.addEventListener('keydown', function(e) {
        // Ctrl+S ya está implementado en keyboard-shortcuts.js
        // Aquí agregamos feedback específico para unsaved changes
        if ((e.ctrlKey || e.metaKey) && e.key === 's' && hasUnsavedChanges) {
            // El formulario se guardará
            hasUnsavedChanges = false;
            removeUnsavedIndicator();
        }
    });
});
