window.initAsignaturaAutofill = function () {
    fetch('/magisteres_completo.json')
        .then(res => res.json())
        .then(data => {
            const asignaturasPorMagister = data.por_magister;
            const detalles = data.detalles;

            const magisterSelects = document.querySelectorAll('.magister-select');
            const asignaturaSelects = document.querySelectorAll('.asignatura-select');

            magisterSelects.forEach((magSelect, index) => {
                const asigSelect = asignaturaSelects[index];

                magSelect.addEventListener('change', function () {
                    const magister = this.value;
                    asigSelect.innerHTML = '<option value="">Asignatura</option>';

                    if (asignaturasPorMagister[magister]) {
                        asignaturasPorMagister[magister].forEach(asignatura => {
                            const opt = document.createElement('option');
                            opt.value = asignatura;
                            opt.textContent = asignatura;
                            asigSelect.appendChild(opt);
                        });
                    }
                });

                asigSelect.addEventListener('change', function () {
                    const asignatura = this.value;
                    const usoContainer = this.closest('.grid');

                    const yearInput = usoContainer.querySelector(`input[name^="usos"][name$="[year]"]`);
                    const trimestreInput = usoContainer.querySelector(`input[name^="usos"][name$="[trimestre]"]`);

                    if (detalles[asignatura]) {
                        yearInput.value = detalles[asignatura].año;
                        trimestreInput.value = detalles[asignatura].trimestre;
                    }
                });
            });
        });
};

// Ejecutar automáticamente al cargar
document.addEventListener('DOMContentLoaded', () => {
    if (window.initAsignaturaAutofill) {
        window.initAsignaturaAutofill();
    }
});
