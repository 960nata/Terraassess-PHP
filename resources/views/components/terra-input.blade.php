{{-- Terra Input Component --}}
@props([
    'label' => null,
    'error' => null,
    'help' => null,
    'required' => false,
    'type' => 'text',
    'placeholder' => '',
    'class' => ''
])

@php
    $inputClasses = 'terra-input';
    
    if ($error) {
        $inputClasses .= ' border-error-500 focus:border-error-500 focus:ring-error-200';
    }
    
    $inputClasses .= ' ' . $class;
@endphp

<div class="terra-form-group">
    @if($label)
        <label class="terra-label">
            {{ $label }}
            @if($required)
                <span class="text-error-500">*</span>
            @endif
        </label>
    @endif
    
    @if($type === 'textarea')
        <textarea 
            {{ $attributes->merge(['class' => $inputClasses . ' terra-textarea', 'placeholder' => $placeholder, 'required' => $required]) }}
        >{{ $attributes->get('value') }}</textarea>
    @elseif($type === 'select')
        <select 
            {{ $attributes->merge(['class' => $inputClasses . ' terra-select', 'required' => $required]) }}
        >
            @if($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif
            {{ $slot }}
        </select>
    @else
        <input 
            type="{{ $type }}"
            {{ $attributes->merge(['class' => $inputClasses, 'placeholder' => $placeholder, 'required' => $required]) }}
        />
    @endif
    
    @if($help && !$error)
        <p class="terra-text-xs text-secondary-500 mt-1">{{ $help }}</p>
    @endif
    
    @if($error)
        <p class="terra-text-xs text-error-600 mt-1">
            <i class="fas fa-exclamation-circle mr-1"></i>
            {{ $error }}
        </p>
    @endif
</div>

