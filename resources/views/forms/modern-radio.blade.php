{{-- Modern Radio Component --}}
@props([
    'label' => null,
    'value' => '',
    'checked' => false,
    'disabled' => false,
    'class' => ''
])

@php
    $radioClasses = 'h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 ' . $class;
    
    if ($disabled) {
        $radioClasses .= ' opacity-50 cursor-not-allowed';
    }
@endphp

<div class="flex items-center">
    <input 
        type="radio"
        value="{{ $value }}"
        {{ $attributes->merge([
            'class' => $radioClasses,
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
