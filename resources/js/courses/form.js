document.addEventListener('DOMContentLoaded', () => {
    const periods = window.PERIODS || [];
    const romanos = { 1: 'I', 2: 'II', 3: 'III', 4: 'IV', 5: 'V', 6: 'VI' };

    const anioSelect = document.getElementById('anio');
    const numeroSelect = document.getElementById('numero');
    const periodIdInput = document.getElementById('period_id');

    let numeroPreseleccionado = null;

    function actualizarTrimestresDisponibles() {
        const anioSeleccionado = anioSelect.value;
        numeroSelect.innerHTML = '<option value="">-- Selecciona un trimestre --</option>';

        if (!anioSeleccionado) return;

        const trimestres = periods
            .filter(p => p.anio == anioSeleccionado)
            .map(p => ({ numero: p.numero, id: p.id }))
            .sort((a, b) => a.numero - b.numero);

        trimestres.forEach(t => {
            const option = document.createElement('option');
            option.value = t.numero;
            option.textContent = `Trimestre ${romanos[t.numero] || t.numero}`;
            numeroSelect.appendChild(option);
        });

        // Si hay trimestre preseleccionado, seleccionarlo ahora
        if (numeroPreseleccionado) {
            numeroSelect.value = numeroPreseleccionado;
            numeroPreseleccionado = null;
        }

        actualizarPeriodId();
    }

    function actualizarPeriodId() {
        const anio = anioSelect.value;
        const numero = numeroSelect.value;
        const periodo = periods.find(p => p.anio == anio && p.numero == numero);
        periodIdInput.value = periodo ? periodo.id : '';
    }

    anioSelect.addEventListener('change', () => {
        actualizarTrimestresDisponibles();
    });

    numeroSelect.addEventListener('change', actualizarPeriodId);

    // Si estamos en modo edición, prellenar
    const periodId = periodIdInput.value;
    if (periodId) {
        const periodoActual = periods.find(p => p.id == periodId);
        if (periodoActual) {
            anioSelect.value = periodoActual.anio;
            numeroPreseleccionado = periodoActual.numero;
            actualizarTrimestresDisponibles();
        }
    }
});
