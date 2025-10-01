@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-semibold text-[#005187] dark:text-[#84b6f4]']) }}>
    {{ $value ?? $slot }}
</label>
