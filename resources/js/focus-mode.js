/* ==========================================
   FOCUS MODE - HCI Principles
   - Distraction-Free interface
   - Keyboard shortcuts
   - Persistent state
   ========================================== */

class FocusMode {
    constructor() {
        this.isActive = false;
        this.storageKey = 'focusModeActive';
        this.init();
    }

    init() {
        // Crear elementos UI
        this.createToggleButton();
        this.createIndicator();
        this.createHint();
        this.createBackdrop();
        
        // Configurar event listeners
        this.setupEventListeners();
        
        // Restaurar estado previo
        this.restoreState();
    }

    /**
     * Crear botón toggle
     */
    createToggleButton() {
        const button = document.createElement('button');
        button.className = 'focus-mode-toggle';
        button.setAttribute('aria-label', 'Activar modo foco');
        button.setAttribute('data-tooltip', 'Modo Foco (F11)');
        button.innerHTML = `
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="9" y1="9" x2="15" y2="9"></line>
                <line x1="9" y1="15" x2="15" y2="15"></line>
            </svg>
        `;
        
        button.addEventListener('click', () => this.toggle());
        document.body.appendChild(button);
        
        this.toggleButton = button;
    }

    /**
     * Crear indicador visual
     */
    createIndicator() {
        const indicator = document.createElement('div');
        indicator.className = 'focus-mode-indicator';
        indicator.setAttribute('role', 'status');
        indicator.setAttribute('aria-live', 'polite');
        indicator.innerHTML = `
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                <path d="M8 2a6 6 0 100 12A6 6 0 008 2zm0 1a5 5 0 110 10A5 5 0 018 3z"/>
                <circle cx="8" cy="8" r="2"/>
            </svg>
            <span>Modo Foco Activo</span>
        `;
        
        document.body.appendChild(indicator);
        this.indicator = indicator;
    }

    /**
     * Crear hint de atajos
     */
    createHint() {
        const hint = document.createElement('div');
        hint.className = 'focus-mode-hint';
        hint.innerHTML = `
            <span class="focus-mode-hint-text">
                Presiona <kbd>F11</kbd> o <kbd>Ctrl</kbd>+<kbd>Shift</kbd>+<kbd>F</kbd> para salir
            </span>
            <button class="focus-mode-hint-close" aria-label="Cerrar sugerencia" title="Cerrar">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </button>
        `;
        
        document.body.appendChild(hint);
        this.hint = hint;
        
        // Agregar event listener al botón de cerrar
        const closeButton = hint.querySelector('.focus-mode-hint-close');
        closeButton.addEventListener('click', () => {
            hint.style.display = 'none';
            // Guardar preferencia en localStorage
            try {
                localStorage.setItem('focusModeHintClosed', 'true');
            } catch (e) {
                console.warn('No se pudo guardar la preferencia del hint:', e);
            }
        });
        
        // Verificar si el usuario ya cerró el hint previamente
        try {
            const hintClosed = localStorage.getItem('focusModeHintClosed');
            if (hintClosed === 'true') {
                hint.style.display = 'none';
            }
        } catch (e) {
            console.warn('No se pudo recuperar la preferencia del hint:', e);
        }
    }

    /**
     * Crear backdrop sutil
     */
    createBackdrop() {
        const backdrop = document.createElement('div');
        backdrop.className = 'focus-mode-backdrop';
        document.body.appendChild(backdrop);
        this.backdrop = backdrop;
    }

    /**
     * Configurar event listeners
     */
    setupEventListeners() {
        // Atajo de teclado: F11
        document.addEventListener('keydown', (e) => {
            if (e.key === 'F11') {
                e.preventDefault();
                this.toggle();
            }
            
            // Atajo alternativo: Ctrl+Shift+F
            if (e.ctrlKey && e.shiftKey && e.key === 'F') {
                e.preventDefault();
                this.toggle();
            }
            
            // ESC para salir
            if (e.key === 'Escape' && this.isActive) {
                this.deactivate();
            }
        });

        // Guardar estado al salir
        window.addEventListener('beforeunload', () => {
            this.saveState();
        });

        // Detectar cambio de ruta (SPA) para mantener estado
        window.addEventListener('popstate', () => {
            if (this.isActive) {
                // Aplicar clase de nuevo después de navegación
                setTimeout(() => {
                    document.body.classList.add('focus-mode');
                }, 100);
            }
        });
    }

    /**
     * Toggle modo foco
     */
    toggle() {
        if (this.isActive) {
            this.deactivate();
        } else {
            this.activate();
        }
    }

    /**
     * Activar modo foco
     */
    activate() {
        // Agregar clase de transición
        document.body.classList.add('focus-mode-transitioning');
        
        // Activar modo foco
        setTimeout(() => {
            document.body.classList.add('focus-mode');
            document.body.classList.remove('focus-mode-transitioning');
            
            this.isActive = true;
            this.saveState();
            
            // Actualizar botón
            this.toggleButton.setAttribute('aria-label', 'Desactivar modo foco');
            this.toggleButton.setAttribute('data-tooltip', 'Salir del Modo Foco (F11)');
            
            // Scroll al top suavemente
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            // Mostrar notificación
            if (window.toast) {
                window.toast.info('Modo Foco Activado', 'Presiona F11 o ESC para salir', {
                    duration: 3000
                });
            }
            
            // Emitir evento personalizado
            window.dispatchEvent(new CustomEvent('focusModeChanged', {
                detail: { active: true }
            }));
            
            // Analytics (opcional)
            this.trackEvent('focus_mode_activated');
            
        }, 50);
    }

    /**
     * Desactivar modo foco
     */
    deactivate() {
        // Agregar clase de transición
        document.body.classList.add('focus-mode-transitioning');
        
        // Desactivar modo foco
        setTimeout(() => {
            document.body.classList.remove('focus-mode');
            document.body.classList.remove('focus-mode-transitioning');
            
            this.isActive = false;
            this.saveState();
            
            // Actualizar botón
            this.toggleButton.setAttribute('aria-label', 'Activar modo foco');
            this.toggleButton.setAttribute('data-tooltip', 'Modo Foco (F11)');
            
            // Emitir evento personalizado
            window.dispatchEvent(new CustomEvent('focusModeChanged', {
                detail: { active: false }
            }));
            
            // Analytics (opcional)
            this.trackEvent('focus_mode_deactivated');
            
        }, 50);
    }

    /**
     * Guardar estado en localStorage
     */
    saveState() {
        try {
            localStorage.setItem(this.storageKey, JSON.stringify(this.isActive));
        } catch (e) {
            console.warn('No se pudo guardar el estado del modo foco:', e);
        }
    }

    /**
     * Restaurar estado desde localStorage
     */
    restoreState() {
        try {
            const saved = localStorage.getItem(this.storageKey);
            if (saved !== null) {
                const wasActive = JSON.parse(saved);
                
                // No restaurar automáticamente, solo si el usuario lo prefiere
                // Para evitar confusión al volver a la app
                // Si quieres restaurar automáticamente, descomenta:
                // if (wasActive) {
                //     this.activate();
                // }
            }
        } catch (e) {
            console.warn('No se pudo restaurar el estado del modo foco:', e);
        }
    }

    /**
     * Track analytics (opcional)
     */
    trackEvent(eventName) {
        // Integración con Google Analytics, Plausible, etc.
        if (typeof gtag !== 'undefined') {
            gtag('event', eventName, {
                'event_category': 'focus_mode',
                'event_label': this.isActive ? 'activated' : 'deactivated'
            });
        }
    }

    /**
     * API pública para otros scripts
     */
    getState() {
        return this.isActive;
    }

    /**
     * Forzar activación/desactivación desde código
     */
    setActive(active) {
        if (active && !this.isActive) {
            this.activate();
        } else if (!active && this.isActive) {
            this.deactivate();
        }
    }
}

// Inicializar modo foco cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.focusMode = new FocusMode();
});

// Exportar para uso en módulos
export default FocusMode;

