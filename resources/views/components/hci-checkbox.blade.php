{{-- Componente HCI Checkbox - Ley de Prägnanz --}}
@props([
    'name' => '',
    'label' => '',
    'value' => '',
    'checked' => false,
    'required' => false,
    'icon' => null,
    'help' => null,
    'class' => '',
    'style' => '',
    'id' => null
])

@php
    $fieldId = $id ?: ($name ?: 'field_' . uniqid());
    $fieldName = $name ?: 'field';
    $fieldValue = $value ?: '1';
    $isRequired = $required;
    $isChecked = old($name, $checked);
    $fieldClasses = 'hci-checkbox ' . $class;
@endphp

<div class="hci-field">
    <div class="hci-checkbox-container">
        <input 
            type="checkbox" 
            id="{{ $fieldId }}" 
            name="{{ $fieldName }}" 
            value="{{ $fieldValue }}"
            {{ $isRequired ? 'required' : '' }} 
            {{ $isChecked ? 'checked' : '' }}
            class="{{ $fieldClasses }}"
            @if($style) style="{{ $style }}" @endif
            {{ $attributes }}
        >
        
        @if($label)
            <label for="{{ $fieldId }}" class="hci-checkbox-label">
                @if($icon)
                    <span class="mr-2">{{ $icon }}</span>
                @endif
                {{ $label }}
                @if($isRequired)
                    <span class="text-red-500 ml-1">*</span>
                @endif
            </label>
        @endif
    </div>
    
    @if($help)
        <p class="hci-help-text">{{ $help }}</p>
    @endif
    
    @error($name)
        <p class="hci-field-error">{{ $message }}</p>
    @enderror
</div>



