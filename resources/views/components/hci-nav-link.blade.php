@props(['active', 'icon' => null, 'badge' => null, 'description' => null])

@php
$baseClasses = 'group relative flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2';
$activeClasses = 'bg-[#4d82bc] text-white shadow-lg ring-2 ring-[#4d82bc] ring-opacity-50';
$inactiveClasses = 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-[#4d82bc] dark:hover:text-[#4d82bc] focus:ring-[#4d82bc]';
$classes = ($active ?? false) ? $baseClasses . ' ' . $activeClasses : $baseClasses . ' ' . $inactiveClasses;
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
        <div class="flex-shrink-0 mr-3">
           <img width="50" height="50" src="https://img.icons8.com/ios/50/appointment-reminders--v1.png" alt="appointment-reminders--v1"/>
        </div>
    @endif
    
    <div class="flex-1 min-w-0">
        <div class="flex items-center justify-between">
            <span class="truncate">{{ $slot }}</span>
            @if($badge)
                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 animate-pulse">
                    {{ $badge }}
                </span>
            @endif
        </div>
        @if($description)
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 truncate">{{ $description }}</p>
        @endif
    </div>
    
    <!-- Indicador de hover -->
    <div class="absolute inset-0 rounded-lg bg-gradient-to-r from-[#4d82bc] to-[#005187] opacity-0 group-hover:opacity-10 transition-opacity duration-200"></div>
</a>
