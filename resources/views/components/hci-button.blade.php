{{-- Componente HCI Button - Ley de Fitts --}}
@props([
    'variant' => 'primary',
    'type' => 'button',
    'icon' => null,
    'iconPosition' => 'left',
    'href' => null,
    'fab' => false,
    'loading' => false,
    'disabled' => false
])

@php
    $baseClasses = 'hci-button';
    $variantClasses = match($variant) {
    'primary' => 'hci-button-primary',
    'secondary' => 'hci-button-secondary',
    'danger' => 'hci-button-danger',
    'success' => 'hci-button-success',
    'compact' => 'px-2 py-1 text-sm rounded-md flex items-center gap-1 bg-blue-100 hover:bg-blue-200 text-blue-800 transition-all duration-200',
    default => 'hci-button-primary'
    };

    
    $fabClasses = $fab ? 'hci-button-fab' : '';
    $disabledClasses = $disabled ? 'opacity-50 cursor-not-allowed' : '';
    
    $classes = implode(' ', array_filter([$baseClasses, $variantClasses, $fabClasses, $disabledClasses]));
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon && $iconPosition === 'left')
            <span class="mr-2">{{ $icon }}</span>
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <span class="ml-2">{{ $icon }}</span>
        @endif
    </a>
@else
    <button 
        type="{{ $type }}" 
        {{ $attributes->merge(['class' => $classes]) }}
        @if($disabled) disabled @endif
    >
        @if($loading)
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @elseif($icon && $iconPosition === 'left')
            <span class="mr-2">{{ $icon }}</span>
        @endif
        
        {{ $slot }}
        
        @if($icon && $iconPosition === 'right' && !$loading)
            <span class="ml-2">{{ $icon }}</span>
        @endif
    </button>
@endif
