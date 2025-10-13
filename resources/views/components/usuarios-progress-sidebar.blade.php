{{-- Barra de progreso lateral para formulario de Usuarios --}}
<div class="hci-progress-sidebar">
    <div class="hci-progress-sidebar-header">
        <h3 class="hci-heading-3 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
            </svg>
            Progreso
        </h3>
        <div class="hci-progress-stats">
            <span id="current-step">Paso 1 de 2</span>
            <span id="progress-percentage">50%</span>
        </div>
    </div>
    
    {{-- Barra de progreso vertical --}}
    <div class="hci-progress-vertical">
        <div class="hci-progress-line">
            <div id="progress-bar" class="hci-progress-fill-vertical" style="height: 50%"></div>
        </div>
        
        {{-- Pasos del progreso vertical --}}
        <div class="hci-progress-steps-vertical">
            {{-- Paso 1: Información del Usuario (icono de usuario) --}}
            <div class="hci-progress-step-vertical active" data-step="1" onclick="navigateToStep(1)">
                <div class="hci-progress-step-circle-vertical">
                    <span class="hci-progress-step-number">1</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="hci-progress-step-content-vertical">
                    <span class="hci-progress-step-title">Información del Usuario</span>
                    <span class="hci-progress-step-desc">Datos básicos y rol</span>
                </div>
            </div>
            
            {{-- Paso 2: Resumen (icono de check) --}}
            <div class="hci-progress-step-vertical" data-step="2" onclick="navigateToStep(2)">
                <div class="hci-progress-step-circle-vertical">
                    <span class="hci-progress-step-number">2</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="hci-progress-step-content-vertical">
                    <span class="hci-progress-step-title">Resumen</span>
                    <span class="hci-progress-step-desc">Revisar y confirmar</span>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Botón de cancelar --}}
    <div class="hci-cancel-section">
        <button type="button" class="hci-button hci-button-danger" onclick="cancelForm()">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
</div>
