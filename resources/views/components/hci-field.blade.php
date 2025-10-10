{{-- Componente HCI Field - Ley de Prägnanz --}}
@props([
    'name' => '',
    'label' => '',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'icon' => null,
    'help' => null,
    'options' => [],
    'rows' => 3,
    'class' => '',
    'style' => '',
    'id' => null
])

@php
    $fieldId = $id ?: ($name ?: 'field_' . uniqid());
    $fieldName = $name ?: 'field';
    $fieldValue = old($name, $value);
    $isRequired = $required;
    $fieldClasses = 'hci-input';
    
    if ($type === 'textarea') {
        $fieldClasses = 'hci-textarea';
    } elseif ($type === 'select') {
        $fieldClasses = 'hci-select';
    }
    
    // Combinar clases base con clases personalizadas
    $fieldClasses = $fieldClasses . ' ' . $class;
    
    // Combinar estilos
    $fieldStyles = $style;
@endphp

<div class="hci-field" @if($fieldId) data-field-id="{{ $fieldId }}" @endif>
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
    
    @if($type === 'textarea')
        <textarea 
            id="{{ $fieldId }}" 
            name="{{ $fieldName }}" 
            {{ $isRequired ? 'required' : '' }} 
            class="{{ $fieldClasses }}" 
            placeholder="{{ $placeholder }}"
            rows="{{ $rows }}"
            @if($fieldStyles) style="{{ $fieldStyles }}" @endif
            {{ $attributes }}
        >{{ $fieldValue }}</textarea>
    @elseif($type === 'select')
        <select 
            id="{{ $fieldId }}" 
            name="{{ $fieldName }}" 
            {{ $isRequired ? 'required' : '' }} 
            class="{{ $fieldClasses }}"
            @if($fieldStyles) style="{{ $fieldStyles }}" @endif
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
    @else
        <input 
            type="{{ $type }}" 
            id="{{ $fieldId }}" 
            name="{{ $fieldName }}" 
            value="{{ $fieldValue }}" 
            {{ $isRequired ? 'required' : '' }} 
            class="{{ $fieldClasses }}" 
            placeholder="{{ $placeholder }}"
            @if($fieldStyles) style="{{ $fieldStyles }}" @endif
            {{ $attributes }}
        >
    @endif
    
    @if($help)
        <p class="hci-help-text">{{ $help }}</p>
    @endif
    
    @error($name)
        <p class="hci-field-error">{{ $message }}</p>
    @enderror
</div>



