import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', () => {
    // 🔴 Confirmación antes de eliminar
    document.querySelectorAll('.form-eliminar').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // ✅ Alerta de éxito (editar, crear o eliminar)
    const success = document.querySelector('meta[name="session-success"]')?.content;
    if (success) {
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: success,
            timer: 2000,
            showConfirmButton: false
        });
    }
});


