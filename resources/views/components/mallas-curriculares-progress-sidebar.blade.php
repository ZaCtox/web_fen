{{-- Progress Sidebar para Mallas Curriculares --}}
<div class="hci-progress-sidebar">
    <div class="hci-progress-header">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
        </svg>
        <span>Progreso</span>
    </div>

    <div class="hci-progress-steps">
        {{-- Paso 1: Información Básica --}}
        <div class="hci-progress-step" data-step="1">
            <div class="hci-step-indicator">
                <span class="hci-step-number">1</span>
                <div class="hci-step-connector"></div>
            </div>
            <div class="hci-step-content">
                <div class="hci-step-title">Información Básica</div>
                <div class="hci-step-description">Programa, nombre y código</div>
            </div>
        </div>

        {{-- Paso 2: Periodo de Vigencia --}}
        <div class="hci-progress-step" data-step="2">
            <div class="hci-step-indicator">
                <span class="hci-step-number">2</span>
                <div class="hci-step-connector"></div>
            </div>
            <div class="hci-step-content">
                <div class="hci-step-title">Periodo de Vigencia</div>
                <div class="hci-step-description">Años de inicio y fin</div>
            </div>
        </div>

        {{-- Paso 3: Descripción y Estado --}}
        <div class="hci-progress-step" data-step="3">
            <div class="hci-step-indicator">
                <span class="hci-step-number">3</span>
            </div>
            <div class="hci-step-content">
                <div class="hci-step-title">Descripción y Estado</div>
                <div class="hci-step-description">Detalles y disponibilidad</div>
            </div>
        </div>
    </div>

    {{-- Información adicional --}}
    <div class="hci-progress-info">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        <p>Completa todos los pasos para crear la malla curricular.</p>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sections = document.querySelectorAll('.hci-form-section');
        const progressSteps = document.querySelectorAll('.hci-progress-step');

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const sectionId = entry.target.getAttribute('data-section-id');
                        const step = entry.target.getAttribute('data-step');
                        
                        // Actualizar pasos del sidebar
                        progressSteps.forEach(ps => {
                            const psStep = ps.getAttribute('data-step');
                            if (psStep === step) {
                                ps.classList.add('active');
                            } else if (parseInt(psStep) < parseInt(step)) {
                                ps.classList.add('completed');
                                ps.classList.remove('active');
                            } else {
                                ps.classList.remove('active', 'completed');
                            }
                        });
                    }
                });
            },
            {
                threshold: 0.5,
                rootMargin: '-100px 0px -100px 0px'
            }
        );

        sections.forEach(section => observer.observe(section));
    });
</script>
@endpush




