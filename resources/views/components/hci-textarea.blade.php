{{-- Componente HCI Textarea - Ley de Prägnanz --}}
@props([
    'name' => '',
    'label' => '',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'icon' => null,
    'help' => null,
    'rows' => 3,
    'class' => '',
    'style' => '',
    'id' => null,
    'maxlength' => null
])

@php
    $fieldId = $id ?: ($name ?: 'field_' . uniqid());
    $fieldName = $name ?: 'field';
    $fieldValue = old($name, $value);
    $isRequired = $required;
    $fieldClasses = 'hci-textarea ' . $class;
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
    
    <textarea 
        id="{{ $fieldId }}" 
        name="{{ $fieldName }}" 
        {{ $isRequired ? 'required' : '' }} 
        class="{{ $fieldClasses }}" 
        placeholder="{{ $placeholder }}"
        rows="{{ $rows }}"
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
        @if($style) style="{{ $style }}" @endif
        {{ $attributes }}
    >{{ $fieldValue }}</textarea>
    
    @if($help)
        <p class="hci-help-text">{{ $help }}</p>
    @endif
    
    @error($name)
        <p class="hci-field-error">{{ $message }}</p>
    @enderror
</div>
