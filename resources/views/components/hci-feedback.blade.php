@props([
    'type' => 'info', // success, error, warning, info
    'title' => null,
    'message' => '',
    'dismissible' => true,
    'autoHide' => false,
    'duration' => 5000,
    'icon' => true,
    'size' => 'md' // sm, md, lg
])

@php
$colors = [
    'success' => [
        'bg' => 'bg-green-50 dark:bg-green-900/20',
        'border' => 'border-green-200 dark:border-green-700',
        'text' => 'text-green-800 dark:text-green-200',
        'icon' => 'text-green-500',
        'iconName' => 'check-circle'
    ],
    'error' => [
        'bg' => 'bg-red-50 dark:bg-red-900/20',
        'border' => 'border-red-200 dark:border-red-700',
        'text' => 'text-red-800 dark:text-red-200',
        'icon' => 'text-red-500',
        'iconName' => 'exclamation-circle'
    ],
    'warning' => [
        'bg' => 'bg-yellow-50 dark:bg-yellow-900/20',
        'border' => 'border-yellow-200 dark:border-yellow-700',
        'text' => 'text-yellow-800 dark:text-yellow-200',
        'icon' => 'text-yellow-500',
        'iconName' => 'exclamation-triangle'
    ],
    'info' => [
        'bg' => 'bg-blue-50 dark:bg-blue-900/20',
        'border' => 'border-blue-200 dark:border-blue-700',
        'text' => 'text-blue-800 dark:text-blue-200',
        'icon' => 'text-blue-500',
        'iconName' => 'information-circle'
    ]
];

$sizes = [
    'sm' => 'p-3 text-sm',
    'md' => 'p-4 text-base',
    'lg' => 'p-6 text-lg'
];

$colorScheme = $colors[$type] ?? $colors['info'];
$sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<div 
    x-data="{ 
        show: true, 
        autoHide: @js($autoHide),
        duration: @js($duration)
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-95"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-95"
    @if($autoHide)
        x-init="setTimeout(() => show = false, duration)"
    @endif
    class="hci-feedback hci-slide-up {{ $colorScheme['bg'] }} {{ $colorScheme['border'] }} border rounded-lg shadow-sm {{ $sizeClass }} {{ $attributes->get('class') }}"
    {{ $attributes->except('class') }}
>
    <div class="flex items-start">
        @if($icon)
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 {{ $colorScheme['icon'] }} hci-rotate" fill="currentColor" viewBox="0 0 20 20">
                    @if($colorScheme['iconName'] === 'check-circle')
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    @elseif($colorScheme['iconName'] === 'exclamation-circle')
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    @elseif($colorScheme['iconName'] === 'exclamation-triangle')
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    @else
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    @endif
                </svg>
            </div>
        @endif

        <div class="ml-3 flex-1">
            @if($title)
                <h3 class="text-sm font-medium {{ $colorScheme['text'] }} hci-focus-ring">
                    {{ $title }}
                </h3>
            @endif
            
            <div class="{{ $title ? 'mt-1' : '' }} {{ $colorScheme['text'] }}">
                {{ $message }}
            </div>
        </div>

        @if($dismissible)
            <div class="ml-3 flex-shrink-0">
                <button 
                    @click="show = false"
                    class="hci-button hci-touch inline-flex {{ $colorScheme['text'] }} hover:opacity-75 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-{{ $type === 'error' ? 'red' : ($type === 'warning' ? 'yellow' : ($type === 'success' ? 'green' : 'blue')) }}-500 rounded-md p-1.5"
                >
                    <span class="sr-only">Cerrar</span>
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        @endif
    </div>
</div>



