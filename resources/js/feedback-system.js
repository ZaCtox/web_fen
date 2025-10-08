/**
 * 🔔 SISTEMA DE FEEDBACK VISUAL HCI
 * Maneja notificaciones, loading states, confirmaciones y validaciones
 */

class FeedbackSystem {
    constructor() {
        this.init();
    }

    init() {
        this.setupGlobalNotifications();
        this.setupFormValidation();
        this.setupLoadingStates();
        this.setupConfirmations();
    }

    /**
     * 🔔 Sistema de Notificaciones Global (Integrado con Toast System)
     */
    setupGlobalNotifications() {
        // Función global para mostrar notificaciones usando el sistema de toasts
        window.showNotification = (options) => {
            const type = options.type || 'info';
            const title = options.title || '';
            const message = options.message || '';
            const actions = options.actions || [];
            
            // Usar el sistema de toasts mejorado
            if (window.toast) {
                return window.toast.show(type, title, message, {
                    duration: options.duration || 5000,
                    actions: actions
                });
            }

            // Fallback a evento personalizado si toast no está disponible
            window.dispatchEvent(new CustomEvent('notification', {
                detail: { type, title, message, ...options }
            }));
        };

        // Funciones de conveniencia que usan el sistema de toasts
        window.showSuccess = (message, title = '¡Éxito!') => {
            if (window.toast) {
                return window.toast.success(title, message);
            }
            window.showNotification({ type: 'success', title, message });
        };

        window.showError = (message, title = 'Error') => {
            if (window.toast) {
                return window.toast.error(title, message);
            }
            window.showNotification({ type: 'error', title, message });
        };

        window.showWarning = (message, title = 'Advertencia') => {
            if (window.toast) {
                return window.toast.warning(title, message);
            }
            window.showNotification({ type: 'warning', title, message });
        };

        window.showInfo = (message, title = 'Información') => {
            if (window.toast) {
                return window.toast.info(title, message);
            }
            window.showNotification({ type: 'info', title, message });
        };
    }

    /**
     * ✅ Validación de Formularios en Tiempo Real
     */
    setupFormValidation() {
        // Validar formularios automáticamente
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (form.classList.contains('hci-form')) {
                this.validateForm(form);
            }
        });

        // Validar campos individuales
        document.addEventListener('blur', (e) => {
            const field = e.target;
            if (field.classList.contains('hci-field')) {
                this.validateField(field);
            }
        }, true);
    }

    /**
     * ⏳ Estados de Loading
     */
    setupLoadingStates() {
        // Mostrar loading en formularios
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (form.classList.contains('hci-form')) {
                this.showFormLoading(form);
            }
        });

        // Mostrar loading en botones
        document.addEventListener('click', (e) => {
            const button = e.target.closest('.hci-loading-button');
            if (button) {
                this.showButtonLoading(button);
            }
        });
    }

    /**
     * ❓ Confirmaciones de Acciones
     */
    setupConfirmations() {
        // Confirmar acciones destructivas
        document.addEventListener('click', (e) => {
            const button = e.target.closest('.hci-confirm-button');
            if (button) {
                e.preventDefault();
                this.showConfirmation(button);
            }
        });
    }

    /**
     * 🔔 Mostrar Notificación
     */
    showNotification(options) {
        window.showNotification(options);
    }

    /**
     * ✅ Validar Formulario
     */
    validateForm(form) {
        const fields = form.querySelectorAll('.hci-field[required]');
        let isValid = true;
        const errors = [];

        fields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
                errors.push(`${field.name || 'Campo'} es obligatorio`);
            }
        });

        if (!isValid) {
            this.showError('Por favor, completa todos los campos obligatorios', 'Formulario incompleto');
        }

        return isValid;
    }

    /**
     * ✅ Validar Campo Individual
     */
    validateField(field) {
        const value = field.value.trim();
        const rules = field.dataset.rules ? field.dataset.rules.split(',') : [];
        let isValid = true;
        const errors = [];

        // Validar reglas
        rules.forEach(rule => {
            if (rule === 'required' && !value) {
                errors.push('Este campo es obligatorio');
                isValid = false;
            } else if (rule.startsWith('min:') && value.length < parseInt(rule.split(':')[1])) {
                errors.push(`Mínimo ${rule.split(':')[1]} caracteres`);
                isValid = false;
            } else if (rule.startsWith('max:') && value.length > parseInt(rule.split(':')[1])) {
                errors.push(`Máximo ${rule.split(':')[1]} caracteres`);
                isValid = false;
            } else if (rule === 'email' && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                errors.push('Formato de email inválido');
                isValid = false;
            }
        });

        // Mostrar errores
        this.showFieldErrors(field, errors);
        return isValid;
    }

    /**
     * ❌ Mostrar Errores de Campo
     */
    showFieldErrors(field, errors) {
        // Remover errores existentes
        const existingErrors = field.parentNode.querySelectorAll('.hci-field-error');
        existingErrors.forEach(error => error.remove());

        // Mostrar nuevos errores
        if (errors.length > 0) {
            const errorContainer = document.createElement('div');
            errorContainer.className = 'hci-field-error mt-1 space-y-1';
            
            errors.forEach(error => {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'flex items-center text-sm text-red-600 dark:text-red-400';
                errorDiv.innerHTML = `
                    <svg class="h-4 w-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>${error}</span>
                `;
                errorContainer.appendChild(errorDiv);
            });

            field.parentNode.appendChild(errorContainer);
        }
    }

    /**
     * ⏳ Mostrar Loading en Formulario
     */
    showFormLoading(form) {
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            this.showButtonLoading(submitButton);
        }

        // Deshabilitar todos los campos
        const fields = form.querySelectorAll('input, select, textarea, button');
        fields.forEach(field => {
            field.disabled = true;
        });
    }

    /**
     * ⏳ Mostrar Loading en Botón
     */
    showButtonLoading(button) {
        const originalText = button.textContent;
        const originalHTML = button.innerHTML;
        
        button.innerHTML = `
            <div class="flex items-center">
                <div class="hci-spinner w-4 h-4 mr-2"></div>
                <span>Procesando...</span>
            </div>
        `;
        button.disabled = true;

        // Restaurar después de 3 segundos (o cuando se complete la acción)
        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.disabled = false;
        }, 3000);
    }

    /**
     * ❓ Mostrar Confirmación
     */
    showConfirmation(button) {
        const action = button.dataset.action || 'realizar esta acción';
        const type = button.dataset.confirmType || 'warning';
        const title = button.dataset.confirmTitle || 'Confirmar acción';
        const message = button.dataset.confirmMessage || `¿Estás seguro de que quieres ${action}?`;

        // Crear modal de confirmación
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="hci-lift bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-yellow-500 hci-rotate" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">${title}</h3>
                        <div class="mt-2 text-gray-600 dark:text-gray-400">${message}</div>
                    </div>
                </div>
                <div class="mt-6 flex space-x-3 justify-end">
                    <button class="hci-button hci-touch px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500" onclick="this.closest('.fixed').remove()">
                        Cancelar
                    </button>
                    <button class="hci-button hci-touch px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 rounded-md" onclick="
                        this.closest('.fixed').remove();
                        ${button.onclick ? button.onclick.toString() : 'button.click()'}
                    ">
                        Confirmar
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
    }

    /**
     * 🎯 Utilidades
     */
    static showToast(message, type = 'info', duration = 3000) {
        window.showNotification({ type, message, duration });
    }

    static showSuccess(message, title = 'Éxito') {
        window.showSuccess(message, title);
    }

    static showError(message, title = 'Error') {
        window.showError(message, title);
    }

    static showWarning(message, title = 'Advertencia') {
        window.showWarning(message, title);
    }

    static showInfo(message, title = 'Información') {
        window.showInfo(message, title);
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new FeedbackSystem();
});

// Exportar para uso global
window.FeedbackSystem = FeedbackSystem;
