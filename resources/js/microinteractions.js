/**
 * 🎨 MICROINTERACCIONES HCI
 * Implementa las leyes de HCI a través de microinteracciones
 */

class Microinteractions {
    constructor() {
        this.init();
    }

    init() {
        this.setupButtonRipples();
        this.setupFormAnimations();
        this.setupCardHovers();
        this.setupLoadingStates();
        this.setupScrollAnimations();
        this.setupNotificationAnimations();
        this.setupKeyboardNavigation();
        this.setupTouchGestures();
        this.setupAdvancedHovers();
        this.setupFocusAnimations();
        this.setupSkeletonLoading();
        this.setupToastNotifications();
        this.setupSwipeGestures();
        this.setupParallaxEffects();
        this.setupMorphingAnimations();
    }

    /**
     * 🎯 Ley de Fitts - Botones grandes y accesibles
     */
    setupButtonRipples() {
        document.addEventListener('click', (e) => {
            const button = e.target.closest('.hci-button-ripple');
            if (!button) return;

            const ripple = document.createElement('span');
            const rect = button.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
            `;

            button.style.position = 'relative';
            button.style.overflow = 'hidden';
            button.appendChild(ripple);

            setTimeout(() => ripple.remove(), 600);
        });

        // Agregar CSS para la animación ripple
        if (!document.querySelector('#ripple-styles')) {
            const style = document.createElement('style');
            style.id = 'ripple-styles';
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }
    }

    /**
     * 📝 Ley de Miller - Formularios con agrupación lógica
     */
    setupFormAnimations() {
        // Focus animations para inputs
        document.addEventListener('focusin', (e) => {
            if (e.target.matches('.hci-input')) {
                e.target.classList.add('hci-focus');
                this.animateLabel(e.target);
            }
        });

        document.addEventListener('focusout', (e) => {
            if (e.target.matches('.hci-input')) {
                e.target.classList.remove('hci-focus');
            }
        });

        // Validación en tiempo real
        document.addEventListener('input', (e) => {
            if (e.target.matches('.hci-input')) {
                this.validateField(e.target);
            }
        });
    }

    animateLabel(input) {
        const label = input.previousElementSibling;
        if (label && label.classList.contains('hci-label')) {
            label.style.transform = 'translateY(-20px) scale(0.9)';
            label.style.color = '#3b82f6';
        }
    }

    validateField(input) {
        const value = input.value.trim();
        const isValid = this.isValidField(input, value);
        
        input.classList.remove('error', 'success');
        
        if (value.length > 0) {
            input.classList.add(isValid ? 'success' : 'error');
        }
    }

    isValidField(input, value) {
        const type = input.type;
        const required = input.hasAttribute('required');
        
        if (required && !value) return false;
        if (type === 'email') return this.isValidEmail(value);
        if (type === 'tel') return this.isValidPhone(value);
        if (input.minLength && value.length < input.minLength) return false;
        
        return true;
    }

    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    isValidPhone(phone) {
        return /^[\+]?[1-9][\d]{0,15}$/.test(phone.replace(/\s/g, ''));
    }

    /**
     * 🎨 Ley de Prägnanz - Animaciones limpias
     */
    setupCardHovers() {
        document.addEventListener('mouseenter', (e) => {
            if (e.target.closest('.hci-card-hover')) {
                const card = e.target.closest('.hci-card-hover');
                card.style.transform = 'translateY(-4px)';
                card.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.1)';
            }
        }, true);

        document.addEventListener('mouseleave', (e) => {
            if (e.target.closest('.hci-card-hover')) {
                const card = e.target.closest('.hci-card-hover');
                card.style.transform = 'translateY(0)';
                card.style.boxShadow = '';
            }
        }, true);
    }

    /**
     * ⏳ Estados de carga
     */
    setupLoadingStates() {
        // Interceptar envíos de formularios
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (form.classList.contains('hci-form')) {
                this.showFormLoading(form);
            }
        });

        // Interceptar clics en botones de envío
        document.addEventListener('click', (e) => {
            if (e.target.matches('button[type="submit"]')) {
                this.showButtonLoading(e.target);
            }
        });
    }

    showFormLoading(form) {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.classList.add('hci-loading');
            submitBtn.disabled = true;
        }
    }

    showButtonLoading(button) {
        button.classList.add('hci-loading');
        button.disabled = true;
        
        // Simular carga (en producción esto se manejaría con la respuesta real)
        setTimeout(() => {
            button.classList.remove('hci-loading');
            button.disabled = false;
        }, 2000);
    }

    /**
     * 📜 Animaciones de scroll
     */
    setupScrollAnimations() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('hci-fade-in');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        // Observar elementos con clase hci-animate-on-scroll
        document.querySelectorAll('.hci-animate-on-scroll').forEach(el => {
            observer.observe(el);
        });
    }

    /**
     * 🔔 Animaciones de notificaciones
     */
    setupNotificationAnimations() {
        // Observar cambios en notificaciones
        const notificationObserver = new MutationObserver((mutations) => {
            mutations.forEach(mutation => {
                if (mutation.type === 'childList') {
                    mutation.addedNodes.forEach(node => {
                        if (node.nodeType === 1 && node.classList.contains('hci-notification')) {
                            this.animateNotification(node);
                        }
                    });
                }
            });
        });

        // Observar el contenedor de notificaciones
        const notificationContainer = document.querySelector('.hci-notifications');
        if (notificationContainer) {
            notificationObserver.observe(notificationContainer, {
                childList: true,
                subtree: true
            });
        }
    }

    animateNotification(notification) {
        notification.classList.add('hci-bounce-in');
        
        // Auto-remove después de 5 segundos
        setTimeout(() => {
            notification.style.animation = 'hci-fade-out 0.3s ease-in';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }

    /**
     * ⌨️ Navegación por teclado
     */
    setupKeyboardNavigation() {
        document.addEventListener('keydown', (e) => {
            // Escape para cerrar modales
            if (e.key === 'Escape') {
                const modal = document.querySelector('.hci-modal.open');
                if (modal) {
                    this.closeModal(modal);
                }
            }

            // Enter para activar botones
            if (e.key === 'Enter' && e.target.matches('.hci-button')) {
                e.target.click();
            }

            // Tab navigation mejorado
            if (e.key === 'Tab') {
                this.handleTabNavigation(e);
            }
        });
    }

    handleTabNavigation(e) {
        const focusableElements = document.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];

        if (e.shiftKey && document.activeElement === firstElement) {
            e.preventDefault();
            lastElement.focus();
        } else if (!e.shiftKey && document.activeElement === lastElement) {
            e.preventDefault();
            firstElement.focus();
        }
    }

    /**
     * 📱 Gestos táctiles
     */
    setupTouchGestures() {
        let startY = 0;
        let startX = 0;

        document.addEventListener('touchstart', (e) => {
            startY = e.touches[0].clientY;
            startX = e.touches[0].clientX;
        });

        document.addEventListener('touchmove', (e) => {
            if (!startY || !startX) return;

            const currentY = e.touches[0].clientY;
            const currentX = e.touches[0].clientX;
            const diffY = startY - currentY;
            const diffX = startX - currentX;

            // Swipe up para cerrar modales
            if (Math.abs(diffY) > Math.abs(diffX) && diffY > 50) {
                const modal = document.querySelector('.hci-modal.open');
                if (modal) {
                    this.closeModal(modal);
                }
            }

            startY = null;
            startX = null;
        });
    }

    closeModal(modal) {
        modal.classList.remove('open');
        modal.classList.add('hci-modal-exit-active');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 200);
    }

    /**
     * 🎯 Utilidades
     */
    static showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `hci-toast hci-toast-${type} hci-bounce-in`;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'hci-fade-out 0.3s ease-in';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    static showLoading(element) {
        element.classList.add('hci-loading');
    }

    static hideLoading(element) {
        element.classList.remove('hci-loading');
    }

    /**
     * 🎨 Efectos de Hover Avanzados
     */
    setupAdvancedHovers() {
        // Hover con elevación 3D
        document.querySelectorAll('.hci-lift').forEach(element => {
            element.addEventListener('mouseenter', () => {
                element.style.transform = 'translateY(-2px) translateZ(0)';
                element.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.15)';
            });

            element.addEventListener('mouseleave', () => {
                element.style.transform = 'translateY(0) translateZ(0)';
                element.style.boxShadow = '';
            });

            element.addEventListener('mousedown', () => {
                element.style.transform = 'translateY(0) translateZ(0)';
                element.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.1)';
            });
        });

        // Hover con rotación sutil
        document.querySelectorAll('.hci-rotate').forEach(element => {
            element.addEventListener('mouseenter', () => {
                element.style.transform = 'rotate(2deg) scale(1.02)';
            });

            element.addEventListener('mouseleave', () => {
                element.style.transform = 'rotate(0deg) scale(1)';
            });
        });

        // Hover con brillo
        document.querySelectorAll('.hci-glow').forEach(element => {
            element.addEventListener('mouseenter', () => {
                element.style.boxShadow = '0 0 20px rgba(77, 130, 188, 0.4)';
                element.style.transform = 'scale(1.02)';
            });

            element.addEventListener('mouseleave', () => {
                element.style.boxShadow = '';
                element.style.transform = 'scale(1)';
            });
        });
    }

    /**
     * 🎯 Efectos de Focus Mejorados
     */
    setupFocusAnimations() {
        document.querySelectorAll('.hci-focus-pulse').forEach(element => {
            element.addEventListener('focus', () => {
                element.classList.add('hci-pulse-focus');
                setTimeout(() => {
                    element.classList.remove('hci-pulse-focus');
                }, 600);
            });
        });

        document.querySelectorAll('.hci-focus-ring').forEach(element => {
            element.addEventListener('focus', () => {
                element.style.transform = 'scale(1.01)';
            });

            element.addEventListener('blur', () => {
                element.style.transform = 'scale(1)';
            });
        });
    }

    /**
     * 🎪 Skeleton Loading Avanzado
     */
    setupSkeletonLoading() {
        // Crear skeleton loading dinámico
        window.createSkeletonLoader = (element, type = 'text') => {
            const skeleton = document.createElement('div');
            skeleton.className = 'hci-skeleton-advanced';
            
            switch(type) {
                case 'text':
                    skeleton.style.height = '20px';
                    skeleton.style.width = '100%';
                    break;
                case 'avatar':
                    skeleton.style.height = '40px';
                    skeleton.style.width = '40px';
                    skeleton.style.borderRadius = '50%';
                    break;
                case 'card':
                    skeleton.style.height = '200px';
                    skeleton.style.width = '100%';
                    break;
            }

            element.innerHTML = '';
            element.appendChild(skeleton);
        };

        // Remover skeleton loading
        window.removeSkeletonLoader = (element, content) => {
            element.innerHTML = content;
        };
    }

    /**
     * 🔔 Toast Notifications Avanzadas
     */
    setupToastNotifications() {
        window.showToast = (message, type = 'info', duration = 3000) => {
            const toast = document.createElement('div');
            toast.className = `hci-toast hci-toast-enter fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm`;
            
            const colors = {
                success: 'bg-green-500 text-white',
                error: 'bg-red-500 text-white',
                warning: 'bg-yellow-500 text-white',
                info: 'bg-blue-500 text-white'
            };

            toast.className += ` ${colors[type] || colors.info}`;
            toast.textContent = message;

            document.body.appendChild(toast);

            // Auto remove
            setTimeout(() => {
                toast.classList.remove('hci-toast-enter');
                toast.classList.add('hci-toast-exit');
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, duration);
        };
    }

    /**
     * 📱 Gestos de Deslizamiento
     */
    setupSwipeGestures() {
        let startX, startY, endX, endY;

        document.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        });

        document.addEventListener('touchend', (e) => {
            endX = e.changedTouches[0].clientX;
            endY = e.changedTouches[0].clientY;

            const deltaX = endX - startX;
            const deltaY = endY - startY;

            // Detectar swipe horizontal
            if (Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(deltaX) > 50) {
                const swipeElement = e.target.closest('.hci-swipe');
                if (swipeElement) {
                    if (deltaX > 0) {
                        swipeElement.classList.add('hci-swipe-right');
                    } else {
                        swipeElement.classList.add('hci-swipe-left');
                    }
                }
            }
        });
    }

    /**
     * 🌟 Efectos de Parallax
     */
    setupParallaxEffects() {
        const parallaxElements = document.querySelectorAll('.hci-parallax');
        
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;

            parallaxElements.forEach(element => {
                element.style.transform = `translateY(${rate}px)`;
            });
        });
    }

    /**
     * 🎭 Animaciones de Morfing
     */
    setupMorphingAnimations() {
        document.querySelectorAll('.hci-morph').forEach(element => {
            element.addEventListener('click', () => {
                element.classList.add('hci-morphing');
                setTimeout(() => {
                    element.classList.remove('hci-morphing');
                }, 600);
            });
        });
    }

    /**
     * 🎯 Utilidades de Microinteracciones
     */
    static addHoverEffect(element, effect = 'lift') {
        element.classList.add(`hci-${effect}`);
    }

    static addFocusEffect(element, effect = 'ring') {
        element.classList.add(`hci-focus-${effect}`);
    }

    static addTransitionEffect(element, effect = 'smooth') {
        element.classList.add(`hci-${effect}`);
    }

    static animateElement(element, animation) {
        element.classList.add(`hci-${animation}`);
        setTimeout(() => {
            element.classList.remove(`hci-${animation}`);
        }, 1000);
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new Microinteractions();
});

// Exportar para uso global
window.Microinteractions = Microinteractions;
