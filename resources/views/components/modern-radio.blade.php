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

<div class="modern-radio-container">
    <input 
        type="radio" 
        name="{{ $name }}" 
        value="{{ $value }}" 
        id="{{ $id }}"
        class="modern-radio-input"
        {{ $checked ? 'checked' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => '']) }}
    >
    <label for="{{ $id }}" class="modern-radio-label">
        <span class="modern-radio-custom"></span>
        <span class="modern-radio-text">{{ $label }}</span>
    </label>
</div>

<style>
.modern-radio-container {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
}

.modern-radio-input {
    display: none;
}

.modern-radio-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-size: 0.9rem;
    color: #374151;
    transition: all 0.2s ease;
}

.modern-radio-label:hover {
    color: #1f2937;
}

.modern-radio-custom {
    width: 20px;
    height: 20px;
    border: 2px solid #d1d5db;
    border-radius: 50%;
    margin-right: 0.75rem;
    position: relative;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.modern-radio-input:checked + .modern-radio-label .modern-radio-custom {
    border-color: #3b82f6;
    background-color: #3b82f6;
}

.modern-radio-input:checked + .modern-radio-label .modern-radio-custom::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 8px;
    height: 8px;
    background-color: white;
    border-radius: 50%;
}

.modern-radio-input:disabled + .modern-radio-label {
    opacity: 0.5;
    cursor: not-allowed;
}

.modern-radio-input:disabled + .modern-radio-label .modern-radio-custom {
    background-color: #f3f4f6;
    border-color: #d1d5db;
}

.modern-radio-text {
    font-weight: 500;
    line-height: 1.4;
}

/* Focus styles */
.modern-radio-input:focus + .modern-radio-label .modern-radio-custom {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Hover styles */
.modern-radio-label:hover .modern-radio-custom {
    border-color: #9ca3af;
}

.modern-radio-input:checked + .modern-radio-label:hover .modern-radio-custom {
    border-color: #2563eb;
    background-color: #2563eb;
}
</style>
