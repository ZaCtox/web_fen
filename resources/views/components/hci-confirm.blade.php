@props([
    'title' => 'Confirmar acción',
    'message' => '¿Estás seguro de que quieres realizar esta acción?',
    'confirmText' => 'Confirmar',
    'cancelText' => 'Cancelar',
    'type' => 'warning', // success, warning, danger
    'icon' => true
])

@php
$colors = [
    'success' => [
        'bg' => 'bg-green-50 dark:bg-green-900/20',
        'border' => 'border-green-200 dark:border-green-700',
        'text' => 'text-green-800 dark:text-green-200',
        'icon' => 'text-green-500',
        'button' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500'
    ],
    'warning' => [
        'bg' => 'bg-yellow-50 dark:bg-yellow-900/20',
        'border' => 'border-yellow-200 dark:border-yellow-700',
        'text' => 'text-yellow-800 dark:text-yellow-200',
        'icon' => 'text-yellow-500',
        'button' => 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500'
    ],
    'danger' => [
        'bg' => 'bg-red-50 dark:bg-red-900/20',
        'border' => 'border-red-200 dark:border-red-700',
        'text' => 'text-red-800 dark:text-red-200',
        'icon' => 'text-red-500',
        'button' => 'bg-red-600 hover:bg-red-700 focus:ring-red-500'
    ]
];

$colorScheme = $colors[$type] ?? $colors['warning'];
@endphp

<div 
    x-data="{ 
        show: false,
        confirm() {
            this.show = false;
            this.$dispatch('confirmed');
        },
        cancel() {
            this.show = false;
            this.$dispatch('cancelled');
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-95"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-95"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
    style="display: none;"
>
    <div class="hci-lift bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full {{ $colorScheme['bg'] }} {{ $colorScheme['border'] }} border">
        <div class="p-6">
            <div class="flex items-start">
                @if($icon)
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 {{ $colorScheme['icon'] }} hci-rotate" fill="currentColor" viewBox="0 0 20 20">
                            @if($type === 'success')
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            @elseif($type === 'danger')
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            @else
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            @endif
                        </svg>
                    </div>
                @endif

                <div class="ml-3 flex-1">
                    <h3 class="text-lg font-medium {{ $colorScheme['text'] }} hci-focus-ring">
                        {{ $title }}
                    </h3>
                    <div class="mt-2 {{ $colorScheme['text'] }}">
                        {{ $message }}
                    </div>
                </div>
            </div>

            <div class="mt-6 flex space-x-3 justify-end">
                <button 
                    @click="cancel()"
                    class="hci-button hci-touch px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                >
                    {{ $cancelText }}
                </button>
                <button 
                    @click="confirm()"
                    class="hci-button hci-touch px-4 py-2 text-sm font-medium text-white {{ $colorScheme['button'] }} rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2"
                >
                    {{ $confirmText }}
                </button>
            </div>
        </div>
    </div>
</div>



