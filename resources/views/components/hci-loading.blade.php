@props([
    'type' => 'spinner', // spinner, dots, pulse, skeleton
    'size' => 'md', // sm, md, lg, xl
    'text' => null,
    'overlay' => false,
    'color' => 'primary' // primary, white, gray
])

@php
$sizes = [
    'sm' => 'w-4 h-4',
    'md' => 'w-6 h-6',
    'lg' => 'w-8 h-8',
    'xl' => 'w-12 h-12'
];

$colors = [
    'primary' => 'border-[#4d82bc]',
    'white' => 'border-white',
    'gray' => 'border-gray-300'
];

$sizeClass = $sizes[$size] ?? $sizes['md'];
$colorClass = $colors[$color] ?? $colors['primary'];
@endphp

<div 
    @if($overlay)
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    @else
        class="flex items-center justify-center {{ $attributes->get('class') }}"
    @endif
    {{ $attributes->except('class') }}
>
    <div class="flex flex-col items-center space-y-3">
        @if($type === 'spinner')
            <div class="hci-spinner {{ $sizeClass }} {{ $colorClass }}"></div>
        @elseif($type === 'dots')
            <div class="flex space-x-1">
                <div class="hci-loading-dot w-2 h-2 bg-[#4d82bc] rounded-full animate-bounce"></div>
                <div class="hci-loading-dot w-2 h-2 bg-[#4d82bc] rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                <div class="hci-loading-dot w-2 h-2 bg-[#4d82bc] rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
            </div>
        @elseif($type === 'pulse')
            <div class="hci-loading-pulse {{ $sizeClass }} bg-[#4d82bc] rounded-full animate-pulse"></div>
        @elseif($type === 'skeleton')
            <div class="hci-skeleton-advanced w-32 h-4"></div>
        @endif

        @if($text)
            <p class="text-sm text-gray-600 dark:text-gray-400 animate-pulse">{{ $text }}</p>
        @endif
    </div>
</div>
