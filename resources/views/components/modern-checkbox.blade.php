@props([
    'name' => '',
    'value' => '1',
    'label' => '',
    'checked' => false,
    'disabled' => false,
    'class' => ''
])

<div class="flex items-center {{ $class }}">
    <input 
        type="checkbox"
        name="{{ $name }}"
        value="{{ $value }}"
        id="{{ $name }}"
        {{ $checked ? 'checked' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded"
        {{ $attributes->except(['class', 'name', 'value', 'label', 'checked', 'disabled']) }}
    />
    <label for="{{ $name }}" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
        {{ $label }}
    </label>
</div>
