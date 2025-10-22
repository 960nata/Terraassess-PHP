@props([
    'type' => 'text',
    'name' => '',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'rows' => 3,
    'class' => ''
])

@php
    $baseClass = 'block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200';
    
    if ($type === 'textarea') {
        $inputClass = $baseClass . ' resize-vertical';
    } else {
        $inputClass = $baseClass;
    }
    
    if ($required) {
        $inputClass .= ' required';
    }
    
    if ($disabled) {
        $inputClass .= ' bg-gray-100 dark:bg-gray-600 cursor-not-allowed';
    }
    
    if ($readonly) {
        $inputClass .= ' bg-gray-50 dark:bg-gray-600';
    }
    
    $inputClass .= ' ' . $class;
@endphp

@if($type === 'textarea')
    <textarea 
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        rows="{{ $rows }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $readonly ? 'readonly' : '' }}
        class="{{ $inputClass }}"
        {{ $attributes->except(['class', 'type', 'name', 'value', 'placeholder', 'required', 'disabled', 'readonly', 'rows']) }}
    >{{ $value ?: $slot }}</textarea>
@elseif($type === 'select')
    <select 
        name="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        class="{{ $inputClass }}"
        {{ $attributes->except(['class', 'type', 'name', 'value', 'placeholder', 'required', 'disabled', 'readonly', 'rows']) }}
    >
        {{ $slot }}
    </select>
@else
    <input 
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $readonly ? 'readonly' : '' }}
        class="{{ $inputClass }}"
        {{ $attributes->except(['class', 'type', 'name', 'value', 'placeholder', 'required', 'disabled', 'readonly', 'rows']) }}
    />
@endif
