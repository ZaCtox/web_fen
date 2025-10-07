{{-- Componente Loading Spinner - Efecto Doherty --}}
@props(['size' => 'md', 'color' => 'blue'])

@php
    $sizeClasses = [
        'sm' => 'w-4 h-4',
        'md' => 'w-8 h-8',
        'lg' => 'w-12 h-12',
        'xl' => 'w-16 h-16'
    ];
    
    $colorClasses = [
        'blue' => 'border-[#4d82bc]',
        'white' => 'border-white',
        'gray' => 'border-gray-500'
    ];
    
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
    $colorClass = $colorClasses[$color] ?? $colorClasses['blue'];
@endphp

<div class="inline-block {{ $sizeClass }} animate-spin rounded-full border-4 border-solid {{ $colorClass }} border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]" role="status">
    <span class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Cargando...</span>
</div>
