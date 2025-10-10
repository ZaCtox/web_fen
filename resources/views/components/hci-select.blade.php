{{-- Componente HCI Select - Ley de Prägnanz --}}
@props([
    'name' => '',
    'label' => '',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'icon' => null,
    'help' => null,
    'options' => [],
    'class' => '',
    'style' => '',
    'id' => null
])

@php
    $fieldId = $id ?: ($name ?: 'field_' . uniqid());
    $fieldName = $name ?: 'field';
    $fieldValue = old($name, $value);
    $isRequired = $required;
    $fieldClasses = 'hci-select ' . $class;
@endphp

<div class="hci-field">
    @if($label)
        <label for="{{ $fieldId }}" class="hci-label">
            @if($icon)
                <span class="mr-2">{{ $icon }}</span>
            @endif
            {{ $label }}
            @if($isRequired)
                <span class="text-red-500 ml-1">*</span>
            @endif
        </label>
    @endif
    
    <select 
        id="{{ $fieldId }}" 
        name="{{ $fieldName }}" 
        {{ $isRequired ? 'required' : '' }} 
        class="{{ $fieldClasses }}"
        @if($style) style="{{ $style }}" @endif
        {{ $attributes }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" {{ $fieldValue == $optionValue ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
        @endforeach
        {{ $slot }}
    </select>
    
    @if($help)
        <p class="hci-help-text">{{ $help }}</p>
    @endif
    
    @error($name)
        <p class="hci-field-error">{{ $message }}</p>
    @enderror
</div>



