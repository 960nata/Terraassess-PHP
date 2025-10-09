{{-- Modern Checkbox Component --}}
@props([
    'label' => null,
    'checked' => false,
    'disabled' => false,
    'class' => ''
])

@php
    $checkboxClasses = 'h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded ' . $class;
    
    if ($disabled) {
        $checkboxClasses .= ' opacity-50 cursor-not-allowed';
    }
@endphp

<div class="flex items-center">
    <input 
        type="checkbox"
        {{ $attributes->merge([
            'class' => $checkboxClasses,
            'checked' => $checked,
            'disabled' => $disabled
        ]) }}
    >
    
    @if($label)
        <label class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
            {{ $label }}
        </label>
    @endif
</div>
