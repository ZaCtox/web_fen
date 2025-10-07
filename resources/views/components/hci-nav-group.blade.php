@props(['title', 'icon' => null, 'collapsible' => false, 'defaultOpen' => true])

@php
$groupId = 'nav-group-' . Str::slug($title);
@endphp

<div class="mb-6" x-data="{ open: {{ $defaultOpen ? 'true' : 'false' }} }">
    <!-- Header del grupo -->
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center">
            @if($icon)
                <img src="{{ asset('icons/' . $icon . '.svg') }}" alt="" class="w-4 h-4 mr-2 text-gray-500">
            @endif
            <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                {{ $title }}
            </h3>
        </div>
        
        @if($collapsible)
            <button @click="open = !open" class="p-1 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        @endif
    </div>
    
    <!-- Contenido del grupo -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200" 
         x-transition:enter-start="opacity-0 transform -translate-y-2" 
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="space-y-1">
        {{ $slot }}
    </div>
</div>
