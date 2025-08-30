@props(['color', 'icon', 'label', 'count'])

<div class="{{ $color }} text-white p-5 rounded-lg shadow flex items-center gap-4">
    <div class="text-3xl">{{ $icon }}</div>
    <div>
        <p class="text-lg font-bold">{{ $count }}</p>
        <p class="text-sm">{{ $label }}</p>
    </div>
</div>
