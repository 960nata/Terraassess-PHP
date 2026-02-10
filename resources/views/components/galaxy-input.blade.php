@props([
    'type' => 'text',
    'name' => '',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'class' => ''
])

@php
    $baseClass = 'block w-full px-4 py-3 bg-transparent border border-purple-300 dark:border-purple-600 rounded-lg text-white placeholder-purple-300 dark:placeholder-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 backdrop-blur-sm';
    
    if ($required) {
        $baseClass .= ' required';
    }
    
    if ($disabled) {
        $baseClass .= ' opacity-50 cursor-not-allowed';
    }
    
    if ($readonly) {
        $baseClass .= ' bg-purple-900 bg-opacity-20';
    }
    
    $baseClass .= ' ' . $class;
@endphp

<input 
    type="{{ $type }}"
    name="{{ $name }}"
    value="{{ $value }}"
    placeholder="{{ $placeholder }}"
    {{ $required ? 'required' : '' }}
    {{ $disabled ? 'disabled' : '' }}
    {{ $readonly ? 'readonly' : '' }}
    class="{{ $baseClass }}"
    {{ $attributes->except(['class', 'type', 'name', 'value', 'placeholder', 'required', 'disabled', 'readonly']) }}
/>
