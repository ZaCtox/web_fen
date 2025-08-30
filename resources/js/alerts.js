// resources/js/alerts.js
document.addEventListener('DOMContentLoaded', () => {
    if (typeof Swal === 'undefined') return;

    const getMeta = (name) => document.querySelector(`meta[name="${name}"]`)?.getAttribute('content');

    const success = getMeta('session-success');
    const error = getMeta('session-error') || getMeta('session-validate-error'); // 👈 añade validación
    const warn = getMeta('session-warning'); // opcional si agregas ese meta

    // Toast de éxito (arriba derecha)
    if (success) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            timer: 2200,
            timerProgressBar: true,
            showConfirmButton: false
        });
        Toast.fire({ icon: 'success', title: success });
    }

    // Modal de error
    if (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error,
            confirmButtonText: 'Aceptar'
        });
    }

    // Advertencia (opcional)
    if (warn) {
        Swal.fire({
            icon: 'warning',
            title: 'Aviso',
            text: warn,
            confirmButtonText: 'Entendido'
        });
    }

    // Confirmación de eliminación (delegación)
    document.addEventListener('submit', function (e) {
        const form = e.target;
        if (!form.matches('form.form-eliminar')) return;
        if (form.dataset.submitting === 'true') return;

        e.preventDefault();

        const msgAttr = form.getAttribute('data-confirm');
        const msgHidden = form.querySelector('input[name="__confirm_msg"]')?.value;
        const msg = msgAttr || msgHidden || 'Esta acción no se puede deshacer.';

        Swal.fire({
            title: '¿Estás seguro?',
            text: msg,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.dataset.submitting = 'true';
                form.submit();
            }
        });
    }, true);
});
