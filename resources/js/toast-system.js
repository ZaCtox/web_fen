/* ==========================================
   TOAST SYSTEM - HCI Principles
   - Visual Feedback
   - Non-intrusive notifications
   - Auto-dismiss with progress
   ========================================== */

class ToastSystem {
    constructor(options = {}) {
        this.options = {
            position: options.position || 'top-right',
            duration: options.duration || 5000,
            maxToasts: options.maxToasts || 5,
            pauseOnHover: options.pauseOnHover !== false,
        };

        this.toasts = [];
        this.container = null;
        this.init();
    }

    init() {
        // Crear contenedor si no existe
        this.container = document.querySelector('.toast-container');
        
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.className = `toast-container ${this.options.position}`;
            document.body.appendChild(this.container);
        }
    }

    /**
     * Mostrar un toast
     * @param {string} type - success, error, warning, info
     * @param {string} title - Título del toast
     * @param {string} message - Mensaje del toast
     * @param {object} options - Opciones adicionales
     */
    show(type, title, message = '', options = {}) {
        // Limitar número de toasts
        if (this.toasts.length >= this.options.maxToasts) {
            this.remove(this.toasts[0]);
        }

        const duration = options.duration || this.options.duration;
        const actions = options.actions || [];

        // Crear elemento del toast
        const toast = this.createToast(type, title, message, actions, duration);
        
        // Agregar al contenedor
        this.container.appendChild(toast.element);
        this.toasts.push(toast);

        // Auto-cerrar
        if (duration > 0) {
            this.startAutoClose(toast, duration);
        }

        return toast;
    }

    /**
     * Crear elemento del toast
     */
    createToast(type, title, message, actions, duration) {
        const toastElement = document.createElement('div');
        toastElement.className = `toast toast-${type}`;
        toastElement.setAttribute('role', 'alert');
        toastElement.setAttribute('aria-live', 'polite');

        // Icono
        const icon = this.getIcon(type);
        
        // HTML del toast
        toastElement.innerHTML = `
            <div class="toast-icon">${icon}</div>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                ${message ? `<div class="toast-message">${message}</div>` : ''}
                ${actions.length > 0 ? this.renderActions(actions) : ''}
            </div>
            <button class="toast-close" aria-label="Cerrar">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor">
                    <path d="M12 4L4 12M4 4l8 8" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
            ${duration > 0 ? '<div class="toast-progress"></div>' : ''}
        `;

        const toast = {
            element: toastElement,
            type,
            timer: null,
            pauseTimer: null,
            startTime: null,
            remainingTime: duration,
        };

        // Event listeners
        this.attachEvents(toast);

        return toast;
    }

    /**
     * Obtener icono según tipo
     */
    getIcon(type) {
        const icons = {
            success: '<svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>',
            error: '<svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>',
            warning: '<svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>',
            info: '<svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>',
        };
        return icons[type] || icons.info;
    }

    /**
     * Renderizar botones de acción
     */
    renderActions(actions) {
        const actionsHTML = actions.map(action => {
            const type = action.primary ? 'primary' : 'secondary';
            return `<button class="toast-action toast-action-${type}" data-action="${action.label}">${action.label}</button>`;
        }).join('');

        return `<div class="toast-actions">${actionsHTML}</div>`;
    }

    /**
     * Adjuntar event listeners
     */
    attachEvents(toast) {
        const { element } = toast;

        // Botón cerrar
        const closeBtn = element.querySelector('.toast-close');
        closeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.remove(toast);
        });

        // Pausar/reanudar al hover
        if (this.options.pauseOnHover && toast.remainingTime > 0) {
            element.addEventListener('mouseenter', () => this.pause(toast));
            element.addEventListener('mouseleave', () => this.resume(toast));
        }

        // Botones de acción
        const actionButtons = element.querySelectorAll('.toast-action');
        actionButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const actionLabel = btn.dataset.action;
                // Emitir evento personalizado
                element.dispatchEvent(new CustomEvent('toastAction', {
                    detail: { action: actionLabel }
                }));
                this.remove(toast);
            });
        });

        // Click en el toast para cerrar
        element.addEventListener('click', () => this.remove(toast));
    }

    /**
     * Iniciar auto-cierre con progress bar
     */
    startAutoClose(toast, duration) {
        const progressBar = toast.element.querySelector('.toast-progress');
        
        if (progressBar) {
            progressBar.style.width = '100%';
            progressBar.style.transitionDuration = `${duration}ms`;
            
            // Forzar reflow para que la animación funcione
            void progressBar.offsetWidth;
            
            progressBar.style.width = '0%';
        }

        toast.startTime = Date.now();
        toast.timer = setTimeout(() => {
            this.remove(toast);
        }, duration);
    }

    /**
     * Pausar auto-cierre
     */
    pause(toast) {
        if (!toast.timer) return;

        clearTimeout(toast.timer);
        toast.remainingTime = toast.remainingTime - (Date.now() - toast.startTime);

        const progressBar = toast.element.querySelector('.toast-progress');
        if (progressBar) {
            const computedStyle = window.getComputedStyle(progressBar);
            progressBar.style.width = computedStyle.width;
            progressBar.style.transitionDuration = '0s';
        }
    }

    /**
     * Reanudar auto-cierre
     */
    resume(toast) {
        if (toast.remainingTime <= 0) return;

        const progressBar = toast.element.querySelector('.toast-progress');
        if (progressBar) {
            progressBar.style.transitionDuration = `${toast.remainingTime}ms`;
            
            // Forzar reflow
            void progressBar.offsetWidth;
            
            progressBar.style.width = '0%';
        }

        toast.startTime = Date.now();
        toast.timer = setTimeout(() => {
            this.remove(toast);
        }, toast.remainingTime);
    }

    /**
     * Remover toast
     */
    remove(toast) {
        if (!toast || !toast.element) return;

        // Limpiar timers
        if (toast.timer) clearTimeout(toast.timer);
        if (toast.pauseTimer) clearTimeout(toast.pauseTimer);

        // Animación de salida
        toast.element.classList.add('toast-removing');

        setTimeout(() => {
            if (toast.element && toast.element.parentNode) {
                toast.element.remove();
            }
            
            // Remover del array
            const index = this.toasts.indexOf(toast);
            if (index > -1) {
                this.toasts.splice(index, 1);
            }
        }, 200);
    }

    /**
     * Métodos de conveniencia
     */
    success(title, message, options) {
        return this.show('success', title, message, options);
    }

    error(title, message, options) {
        return this.show('error', title, message, options);
    }

    warning(title, message, options) {
        return this.show('warning', title, message, options);
    }

    info(title, message, options) {
        return this.show('info', title, message, options);
    }

    /**
     * Limpiar todos los toasts
     */
    clear() {
        this.toasts.forEach(toast => this.remove(toast));
    }

    /**
     * Cambiar posición
     */
    setPosition(position) {
        this.options.position = position;
        this.container.className = `toast-container ${position}`;
    }
}

// Inicializar sistema global
window.toast = new ToastSystem({
    position: 'top-right',
    duration: 5000,
    maxToasts: 5,
});

// Integración con Laravel Session Messages
document.addEventListener('DOMContentLoaded', function() {
    // Success message
    const successMeta = document.querySelector('meta[name="session-success"]');
    if (successMeta) {
        const message = successMeta.content;
        toast.success('¡Éxito!', message);
        successMeta.remove();
    }

    // Error message
    const errorMeta = document.querySelector('meta[name="session-error"]');
    if (errorMeta) {
        const message = errorMeta.content;
        toast.error('Error', message);
        errorMeta.remove();
    }

    // Warning message
    const warningMeta = document.querySelector('meta[name="session-warning"]');
    if (warningMeta) {
        const message = warningMeta.content;
        toast.warning('Advertencia', message);
        warningMeta.remove();
    }

    // Info message
    const infoMeta = document.querySelector('meta[name="session-info"]');
    if (infoMeta) {
        const message = infoMeta.content;
        toast.info('Información', message);
        infoMeta.remove();
    }
});

// Exportar para uso en módulos
export default ToastSystem;

