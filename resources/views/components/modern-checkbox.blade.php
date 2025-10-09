@props([
    'name' => '',
    'value' => '',
    'label' => '',
    'checked' => false,
    'disabled' => false,
    'id' => null
])

@php
    $id = $id ?? $name . '_' . $value;
    $checked = $checked || old($name) == $value;
@endphp

<div class="modern-checkbox-container">
    <input 
        type="checkbox" 
        name="{{ $name }}" 
        value="{{ $value }}" 
        id="{{ $id }}"
        class="modern-checkbox-input"
        {{ $checked ? 'checked' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => '']) }}
    >
    <label for="{{ $id }}" class="modern-checkbox-label">
        <span class="modern-checkbox-custom"></span>
        <span class="modern-checkbox-text">{{ $label }}</span>
    </label>
</div>

<style>
.modern-checkbox-container {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
}

.modern-checkbox-input {
    display: none;
}

.modern-checkbox-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-size: 0.9rem;
    color: #374151;
    transition: all 0.2s ease;
}

.modern-checkbox-label:hover {
    color: #1f2937;
}

.modern-checkbox-custom {
    width: 20px;
    height: 20px;
    border: 2px solid #d1d5db;
    border-radius: 4px;
    margin-right: 0.75rem;
    position: relative;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.modern-checkbox-input:checked + .modern-checkbox-label .modern-checkbox-custom {
    border-color: #3b82f6;
    background-color: #3b82f6;
}

.modern-checkbox-input:checked + .modern-checkbox-label .modern-checkbox-custom::after {
    content: '';
    position: absolute;
    top: 2px;
    left: 6px;
    width: 4px;
    height: 8px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.modern-checkbox-input:disabled + .modern-checkbox-label {
    opacity: 0.5;
    cursor: not-allowed;
}

.modern-checkbox-input:disabled + .modern-checkbox-label .modern-checkbox-custom {
    background-color: #f3f4f6;
    border-color: #d1d5db;
}

.modern-checkbox-text {
    font-weight: 500;
    line-height: 1.4;
}

/* Focus styles */
.modern-checkbox-input:focus + .modern-checkbox-label .modern-checkbox-custom {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Hover styles */
.modern-checkbox-label:hover .modern-checkbox-custom {
    border-color: #9ca3af;
}

.modern-checkbox-input:checked + .modern-checkbox-label:hover .modern-checkbox-custom {
    border-color: #2563eb;
    background-color: #2563eb;
}
</style>
