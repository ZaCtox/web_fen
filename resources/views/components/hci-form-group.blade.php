{{-- Componente HCI Form Group - Ley de Miller y Prägnanz --}}
@props([
    'title' => null,
    'description' => null,
    'icon' => null,
    'columns' => 2,
    'variant' => 'info'
])

@php
    $baseClasses = 'hci-form-group';
    $variantClasses = match($variant) {
        'info' => 'variant-info',
        'success' => 'variant-success',
        'warning' => 'variant-warning',
        'danger' => 'variant-danger',
        default => 'variant-info'
    };
    
    $columnClasses = match($columns) {
        1 => 'md:grid-cols-1',
        2 => 'md:grid-cols-2',
        3 => 'md:grid-cols-3',
        4 => 'md:grid-cols-4',
        default => 'md:grid-cols-2'
    };
    
    $classes = implode(' ', [$baseClasses, $variantClasses]);
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if($title)
        <h3 class="hci-form-group-title">
            @if($icon)
                <span class="mr-3">{{ $icon }}</span>
            @endif
            {{ $title }}
        </h3>
    @endif
    
    @if($description)
        <p class="hci-form-group-description">{{ $description }}</p>
    @endif
    
    <div class="hci-form-row {{ $columnClasses }}">
        {{ $slot }}
    </div>
</div>



