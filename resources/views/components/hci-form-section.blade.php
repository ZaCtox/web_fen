{{-- Componente genérico para sección de formularios HCI --}}
@props([
    'step',
    'title',
    'description',
    'icon',
    'sectionId',
    'isActive' => false,
    'isFirst' => false,
    'isLast' => false,
    'editing' => false,
    // Permite ajustar clases extra al contenedor interno (alineaciones/gaps personalizados por sección)
    'contentClass' => ''
])

<div id="{{ $sectionId }}" class="hci-form-section {{ $isActive ? 'active' : '' }}" data-step="{{ $step }}">
    <div class="hci-step-card">
        <div class="hci-step-header">
            <div class="hci-step-icon">
                @if($icon)
                    {!! $icon !!}
                @else
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                            clip-rule="evenodd" />
                    </svg>
                @endif
            </div>
            <div class="hci-step-info">
                <h2 class="hci-step-title">{{ $title }}</h2>
                <p class="hci-step-description">{{ $description }}</p>
            </div>
            <div class="hci-step-number">{{ $step }}</div>
        </div>

        <div class="hci-step-content">
            @if($sectionId === 'resumen')
                {{-- Permite que el resumen tenga su propio ancho completo --}}
                <div class="w-full {{ $contentClass }}">
                    {{ $slot }}
                </div>
            @elseif($sectionId === 'notificacion')
                {{-- Excepción: notificación con ancho completo --}}
                <div class="w-full {{ $contentClass }}">
                    {{ $slot }}
                </div>
            @elseif($sectionId === 'equipamiento')
                {{-- Excepción: equipamiento con contenedor ancho tipo tarjeta --}}
                <div class="w-full {{ $contentClass }}">
                    <div class="rounded-xl border border-[#c4dafa]/60 bg-[#fcffff] p-6 shadow-sm">
                        {{ $slot }}
                    </div>
                </div>
            @elseif($sectionId === 'detalles-sesiones')
                {{-- Excepción: detalles de sesiones con ancho completo --}}
                <div class="{{ $contentClass ?: 'w-full' }}">
                    {{ $slot }}
                </div>
            @else
                <div class="hci-form-grid {{ $contentClass }}">
                    {{ $slot }}
                </div>
            @endif
        </div>

        {{-- Navegación de la sección --}}
        <div class="hci-step-navigation">
            <button type="button" class="hci-button hci-button-secondary" {{ $isFirst ? 'disabled' : '' }}
                onclick="{{ $isFirst ? '' : 'prevStep()' }}">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </button>

            @if($isLast)
                <button type="button" class="hci-button hci-button-primary hci-button-success" onclick="submitForm()">
                    <img src="{{ asset('icons/save.svg') }}" alt="Guardar" class="w-6 h-6">
                </button>
            @else
                <button type="button" class="hci-button hci-button-primary" onclick="nextStep()">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            @endif
        </div>
    </div>
</div>



