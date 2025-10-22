/**
 * Loading States - Efecto Doherty
 * Mejora la percepción de velocidad con feedback visual instantáneo
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // 1. Loading States para Formularios
    // ==========================================
    const forms = document.querySelectorAll('form:not(.no-loading)');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // No mostrar loading para formularios de eliminación (se maneja en alerts.js)
            if (form.classList.contains('form-eliminar')) {
                return;
            }
            
            const submitButton = form.querySelector('button[type="submit"]');
            
            if (submitButton && !submitButton.classList.contains('btn-loading')) {
                // Agregar clase de loading al botón
                submitButton.classList.add('btn-loading');
                submitButton.disabled = true;
                
                // Agregar clase al formulario
                form.classList.add('form-submitting');
                
                // Crear overlay de loading si no existe
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
            }
        });
    });
    
    // ==========================================
    // 2. Loading States para Confirmaciones
    // ==========================================
    document.querySelectorAll('.hci-confirm-button').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const button = this.querySelector('button[type="submit"]');
            const title = this.dataset.confirmTitle || '¿Estás seguro?';
            const text = this.dataset.confirmText || 'Esta acción no se puede deshacer.';
            const confirmText = this.dataset.confirmButton || 'Sí, eliminar';
            
            const result = await Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e57373',
                cancelButtonColor: '#6b7280',
                confirmButtonText: confirmText,
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            });
            
            if (result.isConfirmed) {
                // Mostrar loading
                Swal.fire({
                    title: 'Procesando...',
                    html: '<div class="flex justify-center"><div class="inline-block w-12 h-12 animate-spin rounded-full border-4 border-solid border-[#4d82bc] border-r-transparent"></div></div>',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                });
                
                // Enviar formulario
                this.submit();
            }
        });
    });
    
    // ==========================================
    // 3. Loading States para Enlaces/Botones AJAX
    // ==========================================
    document.querySelectorAll('.ajax-button').forEach(button => {
        button.addEventListener('click', function(e) {
            if (!this.classList.contains('btn-loading')) {
                this.classList.add('btn-loading');
                this.disabled = true;
            }
        });
    });
    
    // ==========================================
    // 4. Reset Loading States on Page Load
    // ==========================================
    window.addEventListener('pageshow', function(event) {
        // Remover todos los loading states
        document.querySelectorAll('.btn-loading').forEach(btn => {
            btn.classList.remove('btn-loading');
            btn.disabled = false;
        });
        
        document.querySelectorAll('.form-submitting').forEach(form => {
            form.classList.remove('form-submitting');
        });
        
        const overlay = document.getElementById('form-loading-overlay');
        if (overlay) overlay.remove();
    });
    
    // ==========================================
    // 5. Función Global para Mostrar Loading
    // ==========================================
    window.showLoading = function(message = 'Cargando...') {
        Swal.fire({
            title: message,
            html: '<div class="flex justify-center"><div class="inline-block w-12 h-12 animate-spin rounded-full border-4 border-solid border-[#4d82bc] border-r-transparent"></div></div>',
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false
        });
    };
    
    window.hideLoading = function() {
        Swal.close();
    };
    
    // ==========================================
    // 6. Loading para Links que Redirigen
    // ==========================================
    document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"]):not(.no-loading)').forEach(link => {
        // Solo para links internos que no sean anchors
        if (link.hostname === window.location.hostname) {
            link.addEventListener('click', function(e) {
                // Pequeño delay para mostrar feedback
                const href = this.getAttribute('href');
                if (href && href !== '#' && !this.classList.contains('no-loading')) {
                    // Mostrar indicador visual sutil
                    this.style.opacity = '0.6';
                }
            });
        }
    });
});
