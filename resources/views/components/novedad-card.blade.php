@props(['novedad'])

@php
// Mapeo de colores
$colorMap = [
    'blue' => [
        'bg' => 'bg-blue-50 dark:bg-blue-900/20',
        'border' => 'border-blue-400',
        'ring' => 'ring-2 ring-blue-300 dark:ring-blue-700',
        'badge_bg' => 'bg-blue-100 dark:bg-blue-900',
        'text' => 'text-blue-800 dark:text-blue-200',
        'badge' => 'bg-blue-200 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'content' => 'text-blue-600 dark:text-blue-300',
        'time' => 'text-blue-500 dark:text-blue-400',
        'icon' => 'text-blue-600 dark:text-blue-400',
    ],
    'green' => [
        'bg' => 'bg-green-50 dark:bg-green-900/20',
        'border' => 'border-green-400',
        'ring' => 'ring-2 ring-green-300 dark:ring-green-700',
        'badge_bg' => 'bg-green-100 dark:bg-green-900',
        'text' => 'text-green-800 dark:text-green-200',
        'badge' => 'bg-green-200 text-green-800 dark:bg-green-900 dark:text-green-200',
        'content' => 'text-green-600 dark:text-green-300',
        'time' => 'text-green-500 dark:text-green-400',
        'icon' => 'text-green-600 dark:text-green-400',
    ],
    'yellow' => [
        'bg' => 'bg-yellow-50 dark:bg-yellow-900/20',
        'border' => 'border-yellow-400',
        'ring' => 'ring-2 ring-yellow-300 dark:ring-yellow-700',
        'badge_bg' => 'bg-yellow-100 dark:bg-yellow-900',
        'text' => 'text-yellow-800 dark:text-yellow-200',
        'badge' => 'bg-yellow-200 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        'content' => 'text-yellow-600 dark:text-yellow-300',
        'time' => 'text-yellow-500 dark:text-yellow-400',
        'icon' => 'text-yellow-600 dark:text-yellow-400',
    ],
    'red' => [
        'bg' => 'bg-red-50 dark:bg-red-900/20',
        'border' => 'border-red-400',
        'ring' => 'ring-2 ring-red-300 dark:ring-red-700',
        'badge_bg' => 'bg-red-100 dark:bg-red-900',
        'text' => 'text-red-800 dark:text-red-200',
        'badge' => 'bg-red-200 text-red-800 dark:bg-red-900 dark:text-red-200',
        'content' => 'text-red-600 dark:text-red-300',
        'time' => 'text-red-500 dark:text-red-400',
        'icon' => 'text-red-600 dark:text-red-400',
    ],
    'purple' => [
        'bg' => 'bg-purple-50 dark:bg-purple-900/20',
        'border' => 'border-purple-400',
        'ring' => 'ring-2 ring-purple-300 dark:ring-purple-700',
        'badge_bg' => 'bg-purple-100 dark:bg-purple-900',
        'text' => 'text-purple-800 dark:text-purple-200',
        'badge' => 'bg-purple-200 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
        'content' => 'text-purple-600 dark:text-purple-300',
        'time' => 'text-purple-500 dark:text-purple-400',
        'icon' => 'text-purple-600 dark:text-purple-400',
    ],
];

$colors = $colorMap[$novedad->color] ?? $colorMap['purple'];

// Iconos SVG
$icons = [
    'warning' => '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>',
    'check' => '<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>',
    'info' => '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>',
    'calendar' => '<path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>',
    'alert' => '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>',
];

$iconPath = $icons[$novedad->icono] ?? $icons['info'];
@endphp

<div class="flex items-start p-3 rounded-lg border-l-4 {{ $colors['bg'] }} {{ $colors['border'] }} {{ $novedad->es_urgente ? $colors['ring'] : '' }}">
    {{-- Icono --}}
    <div class="flex-shrink-0">
        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $colors['badge_bg'] }}">
            <svg class="w-4 h-4 {{ $colors['icon'] }}" fill="currentColor" viewBox="0 0 20 20">
                {!! $iconPath !!}
            </svg>
        </div>
    </div>
    
    {{-- Contenido --}}
    <div class="ml-3 flex-1">
        <p class="text-sm font-medium {{ $colors['text'] }}">
            {{ $novedad->titulo }}
            @if($novedad->es_urgente)
                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $colors['badge'] }}">
                    Urgente
                </span>
            @endif
        </p>
        
        <p class="text-sm {{ $colors['content'] }} mt-1">
            {{ $novedad->contenido }}
            @if($novedad->acciones && count($novedad->acciones) > 0)
                @foreach($novedad->acciones as $accion)
                    @php
                        $actionColors = $colorMap[$accion['color']] ?? $colorMap['blue'];
                    @endphp
                    <a href="{{ $accion['url'] }}" class="ml-2 underline hover:no-underline font-medium {{ $actionColors['icon'] }}">
                        {{ $accion['texto'] }}
                    </a>
                @endforeach
            @endif
        </p>
        
        <p class="text-xs {{ $colors['time'] }} mt-1">
            {{ isset($novedad->created_at) ? $novedad->created_at->diffForHumans() : 'Ahora' }}
        </p>
    </div>
</div>

