{{-- 
    Componente HCI Action Button - Ley de Fitts
    Botones de acción para tablas (Editar, Eliminar, Ver, etc.)
--}}
@props([
    'type' => 'button',  // button, link, submit
    'variant' => 'edit', // edit, delete, view, custom
    'href' => null,
    'icon' => null,
    'tooltip' => '',
    'formAction' => null,
    'formMethod' => 'POST',
])

@php
    // Colores según variante
    $variantClasses = match($variant) {
        'edit' => 'bg-[#84b6f4] hover:bg-[#84b6f4]/80 text-white',
        'delete' => 'bg-[#e57373] hover:bg-[#f28b82] text-white',
        'view' => 'bg-[#4d82bc] hover:bg-[#005187] text-white',
        'download' => 'bg-[#005187] hover:bg-[#4d82bc] text-white',
        'success' => 'bg-[#66bb6a] hover:bg-[#4caf50] text-white',
        'warning' => 'bg-[#ffa726] hover:bg-[#ff9800] text-white',
        'info' => 'bg-[#42a5f5] hover:bg-[#2196f3] text-white',
        default => $attributes->get('class', 'bg-[#84b6f4] hover:bg-[#84b6f4]/80 text-white')
    };
    
    // Icono por defecto según variante
    if (!$icon) {
        $icon = match($variant) {
            'edit' => 'edit.svg',
            'delete' => 'trashw.svg',
            'view' => 'ver.svg',
            'download' => 'download.svg',
            'success' => 'check.svg',
            default => 'edit.svg'
        };
    }
    
        // Tamaño del ícono según variante
        $iconSize = match($variant) {
            'delete' => 'w-4 h-4',
            'download' => 'w-4 h-4',
            default => 'w-4 h-4'
        };
    
    // Clases base
    $baseClasses = 'inline-flex items-center justify-center w-10 px-3 py-2 rounded-lg text-xs font-medium transition';
    $classes = "$baseClasses $variantClasses";
@endphp

@if($type === 'link' && $href)
    {{-- Botón como enlace --}}
    <a href="{{ $href }}" 
       {{ $attributes->merge(['class' => $classes]) }}
       @if($tooltip) title="{{ $tooltip }}" @endif>
        <img src="{{ asset('icons/' . $icon) }}" alt="{{ $tooltip }}" class="{{ $iconSize }}">
    </a>
@elseif($formAction)
    {{-- Botón dentro de formulario (típico para eliminar) --}}
    <form action="{{ $formAction }}" method="POST" class="inline {{ $attributes->get('class') }}" {{ $attributes->except('class') }}>
        @csrf
        @if($formMethod !== 'POST')
            @method($formMethod)
        @endif
        <button type="submit" 
                class="{{ $classes }}"
                @if($tooltip) title="{{ $tooltip }}" @endif>
            <img src="{{ asset('icons/' . $icon) }}" alt="{{ $tooltip }}" class="{{ $iconSize }}">
        </button>
    </form>
@else
    {{-- Botón normal --}}
    <button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }}
            @if($tooltip) title="{{ $tooltip }}" @endif>
        <img src="{{ asset('icons/' . $icon) }}" alt="{{ $tooltip }}" class="{{ $iconSize }}">
    </button>
@endif




