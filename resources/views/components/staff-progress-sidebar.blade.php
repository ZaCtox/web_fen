{{-- Barra de progreso lateral para formulario de Staff --}}
<div class="hci-progress-sidebar">
    <div class="hci-progress-sidebar-header">
        <h3 class="hci-heading-3 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
            </svg>
            Progreso
        </h3>
                <div class="hci-progress-stats">
            <span id="current-step">Paso 1 de 5</span>
            <span id="progress-percentage">20%</span>
        </div>
    </div>
    
    {{-- Barra de progreso vertical --}}
    <div class="hci-progress-vertical">
        <div class="hci-progress-line">
            <div id="progress-bar" class="hci-progress-fill-vertical" style="height: 20%"></div>
        </div>
        
        {{-- Pasos del progreso vertical --}}
        <div class="hci-progress-steps-vertical">
            {{-- Paso 1: Información Personal (icono de usuario) --}}
            <div class="hci-progress-step-vertical active" data-step="1" onclick="navigateToStep(1)">
                <div class="hci-progress-step-circle-vertical">
                    <span class="hci-progress-step-number">1</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="hci-progress-step-content-vertical">
                    <span class="hci-progress-step-title">Información Personal</span>
                    <span class="hci-progress-step-desc">Datos básicos</span>
                </div>
            </div>
            
            {{-- Paso 2: Foto de Perfil (icono de imagen) --}}
            <div class="hci-progress-step-vertical" data-step="2" onclick="navigateToStep(2)">
                <div class="hci-progress-step-circle-vertical">
                    <span class="hci-progress-step-number">2</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="hci-progress-step-content-vertical">
                    <span class="hci-progress-step-title">Foto de Perfil</span>
                    <span class="hci-progress-step-desc">Imagen del miembro</span>
                </div>
            </div>
            
            {{-- Paso 3: Información de Contacto (icono de teléfono) --}}
            <div class="hci-progress-step-vertical" data-step="3" onclick="navigateToStep(3)">
                <div class="hci-progress-step-circle-vertical">
                    <span class="hci-progress-step-number">3</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                    </svg>
                </div>
                <div class="hci-progress-step-content-vertical">
                    <span class="hci-progress-step-title">Información de Contacto</span>
                    <span class="hci-progress-step-desc">Datos de contacto</span>
                </div>
            </div>
            
            {{-- Paso 4: Información Adicional (icono de hashtag/anexo) --}}
            <div class="hci-progress-step-vertical" data-step="4" onclick="navigateToStep(4)">
                <div class="hci-progress-step-circle-vertical">
                    <span class="hci-progress-step-number">4</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.243 3.03a1 1 0 01.727 1.213L9.53 6h2.94l.56-2.243a1 1 0 111.94.486L14.53 6H17a1 1 0 110 2h-2.97l-1 4H15a1 1 0 110 2h-2.47l-.56 2.242a1 1 0 11-1.94-.485L10.47 14H7.53l-.56 2.242a1 1 0 11-1.94-.485L5.47 14H3a1 1 0 110-2h2.97l1-4H5a1 1 0 110-2h2.47l.56-2.243a1 1 0 011.213-.727zM9.03 8l-1 4h2.938l1-4H9.031z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="hci-progress-step-content-vertical">
                    <span class="hci-progress-step-title">Información Adicional</span>
                    <span class="hci-progress-step-desc">Datos complementarios</span>
                </div>
            </div>
            
            {{-- Paso 5: Resumen (icono de check) --}}
            <div class="hci-progress-step-vertical" data-step="5" onclick="navigateToStep(5)">
                <div class="hci-progress-step-circle-vertical">
                    <span class="hci-progress-step-number">5</span>
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



