@props([
    'type' => 'no-data', // no-data, no-results, error
    'icon' => null,
    'title' => '',
    'message' => '',
    'actionText' => null,
    'actionUrl' => null,
    'actionIcon' => null,
    'secondaryActionText' => null,
    'secondaryActionUrl' => null,
    'secondaryActionIcon' => null
])

@php
    // Iconos predeterminados según el tipo
    $defaultIcons = [
        'no-data' => '📊',
        'no-results' => '🔍',
        'error' => '⚠️',
        'success' => '✅',
        'info' => 'ℹ️'
    ];
    
    $displayIcon = $icon ?? ($defaultIcons[$type] ?? '📋');
@endphp

<div class="empty-state-container" data-type="{{ $type }}">
    <div class="empty-state-content">
        {{-- Icono --}}
        <div class="empty-state-icon">
            {{ $displayIcon }}
        </div>

        {{-- Título --}}
        @if($title)
            <h3 class="empty-state-title">
                {{ $title }}
            </h3>
        @endif

        {{-- Mensaje --}}
        @if($message)
            <p class="empty-state-message">
                {{ $message }}
            </p>
        @endif

        {{-- Slot para contenido personalizado --}}
        @if($slot->isNotEmpty())
            <div class="empty-state-custom">
                {{ $slot }}
            </div>
        @endif

        {{-- Acciones --}}
        @if($actionText || $secondaryActionText)
            <div class="empty-state-actions">
                {{-- Acción primaria --}}
                @if($actionText && $actionUrl)
                    <a href="{{ $actionUrl }}" 
                       class="empty-state-button empty-state-button-primary hci-button hci-lift hci-focus-ring">
                        @if($actionIcon)
                            <span class="mr-2">{{ $actionIcon }}</span>
                        @endif
                        {{ $actionText }}
                    </a>
                @endif

                {{-- Acción secundaria --}}
                @if($secondaryActionText && $secondaryActionUrl)
                    <a href="{{ $secondaryActionUrl }}" 
                       class="empty-state-button empty-state-button-secondary hci-button hci-lift hci-focus-ring">
                        @if($secondaryActionIcon)
                            <span class="mr-2">{{ $secondaryActionIcon }}</span>
                        @endif
                        {{ $secondaryActionText }}
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>




