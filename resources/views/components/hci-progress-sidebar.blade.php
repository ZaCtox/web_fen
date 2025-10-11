{{-- 
    Componente Sidebar de Progreso Genérico
    Props:
    - steps: Array de pasos con estructura:
      [
        ['title' => 'Información Básica', 'description' => 'Nombre del curso'],
        ['title' => 'Programa y Período', 'description' => 'Año y trimestre'],
        ['title' => 'Resumen', 'description' => 'Revisar y confirmar']
      ]
--}}

@props(['steps' => []])

@php
    $totalSteps = count($steps);
    $initialPercentage = $totalSteps > 0 ? round(100 / $totalSteps) : 0;
@endphp

{{-- Barra de progreso lateral genérica --}}
<div class="hci-progress-sidebar">
    <div class="hci-progress-sidebar-header">
        <h3 class="hci-heading-3 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
            </svg>
            Progreso
        </h3>
        <div class="hci-progress-stats">
            <span id="current-step">Paso 1 de {{ $totalSteps }}</span>
            <span id="progress-percentage">{{ $initialPercentage }}%</span>
        </div>
    </div>
    
    {{-- Barra de progreso vertical --}}
    <div class="hci-progress-vertical">
        <div class="hci-progress-line">
            <div id="progress-bar" class="hci-progress-fill-vertical" style="height: {{ $initialPercentage }}%"></div>
        </div>
        
        {{-- Pasos del progreso vertical (generados dinámicamente) --}}
        <div class="hci-progress-steps-vertical">
            @foreach($steps as $index => $step)
                @php
                    $stepNumber = $index + 1;
                    $isActive = $stepNumber === 1;
                @endphp
                
                <div class="hci-progress-step-vertical {{ $isActive ? 'active' : '' }}" 
                     data-step="{{ $stepNumber }}" 
                     onclick="navigateToStep({{ $stepNumber }})">
                    <div class="hci-progress-step-circle-vertical">
                        <span class="hci-progress-step-number">{{ $stepNumber }}</span>
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="hci-progress-step-content-vertical">
                        <span class="hci-progress-step-title">{{ $step['title'] ?? "Paso {$stepNumber}" }}</span>
                        <span class="hci-progress-step-desc">{{ $step['description'] ?? '' }}</span>
                    </div>
                </div>
            @endforeach
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

