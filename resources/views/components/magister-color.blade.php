@props(['name', 'color'])

<div class="flex items-center gap-2">
    <span class="w-4 h-4 inline-block rounded-full {{ $color }}"></span>
    <span class="text-sm text-gray-800 dark:text-gray-200">{{ $name }}</span>
</div>
