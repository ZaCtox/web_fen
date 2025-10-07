{{-- Barra de progreso lateral para formulario de Salas --}}
<div class="hci-progress-sidebar">
    <div class="hci-progress-sidebar-header">
        <h3 class="hci-heading-3 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
            </svg>
            Progreso
        </h3>
        <div class="hci-progress-stats">
            <span id="current-step">Paso 1 de 4</span>
            <span id="progress-percentage">25%</span>
        </div>
    </div>
    
    {{-- Barra de progreso vertical --}}
    <div class="hci-progress-vertical">
        <div class="hci-progress-line">
            <div id="progress-bar" class="hci-progress-fill-vertical" style="height: 33%"></div>
        </div>
        
        {{-- Pasos del progreso vertical --}}
        <div class="hci-progress-steps-vertical">
            <div class="hci-progress-step-vertical active" data-step="1" onclick="navigateToStep(1)">
                <div class="hci-progress-step-circle-vertical">
                    <span class="hci-progress-step-number">1</span>
                </div>
                <div class="hci-progress-step-content-vertical">
                    <span class="hci-progress-step-title">Información Básica</span>
                    <span class="hci-progress-step-desc">Nombre y ubicación</span>
                </div>
            </div>
            
            <div class="hci-progress-step-vertical" data-step="2" onclick="navigateToStep(2)">
                <div class="hci-progress-step-circle-vertical">
                    <span class="hci-progress-step-number">2</span>
                </div>
                <div class="hci-progress-step-content-vertical">
                    <span class="hci-progress-step-title">Detalles</span>
                    <span class="hci-progress-step-desc">Capacidad y condiciones</span>
                </div>
            </div>
            
            <div class="hci-progress-step-vertical" data-step="3" onclick="navigateToStep(3)">
                <div class="hci-progress-step-circle-vertical">
                    <span class="hci-progress-step-number">3</span>
                </div>
                <div class="hci-progress-step-content-vertical">
                    <span class="hci-progress-step-title">Equipamiento</span>
                    <span class="hci-progress-step-desc">Condiciones disponibles</span>
                </div>
            </div>

            <div class="hci-progress-step-vertical" data-step="4" onclick="navigateToStep(4)">
                <div class="hci-progress-step-circle-vertical">
                    <span class="hci-progress-step-number">4</span>
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
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
</div>


