{{-- Modern Input Component --}}
@props([
    'type' => 'text',
    'label' => null,
    'error' => null,
    'help' => null,
    'required' => false,
    'disabled' => false,
    'placeholder' => null,
    'class' => ''
])

@php
    $inputClasses = 'form-input';
    
    if ($error) {
        $inputClasses .= ' error';
    }
    
    if ($disabled) {
        $inputClasses .= ' opacity-50 cursor-not-allowed';
    }
    
    $inputClasses .= ' ' . $class;
@endphp

<div class="form-group">
    @if($label)
        <label class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-error-500">*</span>
            @endif
        </label>
    @endif
    
    @if($type === 'textarea')
        <textarea 
            {{ $attributes->merge([
                'class' => $inputClasses . ' form-textarea',
                'placeholder' => $placeholder,
                'disabled' => $disabled,
                'required' => $required
            ]) }}
        >{{ $attributes->get('value') }}</textarea>
    @elseif($type === 'select')
        <select 
            {{ $attributes->merge([
                'class' => $inputClasses . ' form-select',
                'disabled' => $disabled,
                'required' => $required
            ]) }}
        >
            @if($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif
            {{ $slot }}
        </select>
    @else
        <input 
            type="{{ $type }}"
            {{ $attributes->merge([
                'class' => $inputClasses,
                'placeholder' => $placeholder,
                'disabled' => $disabled,
                'required' => $required
            ]) }}
        />
    @endif
    
    @if($error)
        <div class="form-error">{{ $error }}</div>
    @endif
    
    @if($help)
        <div class="form-help">{{ $help }}</div>
    @endif
</div>
