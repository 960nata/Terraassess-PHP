@props([
    'label' => '',
    'required' => false,
    'help' => '',
    'error' => '',
    'id' => null
])

@php
    $id = $id ?? Str::random(8);
@endphp

<div class="modern-form-group" {{ $attributes }}>
    @if($label)
        <label for="{{ $id }}" class="modern-form-label">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="modern-form-input-wrapper">
        {{ $slot }}
    </div>
    
    @if($help)
        <div class="modern-form-help">
            {{ $help }}
        </div>
    @endif
    
    @if($error)
        <div class="modern-form-error">
            {{ $error }}
        </div>
    @endif
</div>

<style>
.modern-form-group {
    margin-bottom: 1.5rem;
}

.modern-form-group:last-child {
    margin-bottom: 0;
}

.modern-form-label {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    line-height: 1.4;
}

.modern-form-input-wrapper {
    position: relative;
}

.modern-form-input-wrapper .form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    background: white;
}

.modern-form-input-wrapper .form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.modern-form-input-wrapper .form-control.is-invalid {
    border-color: #ef4444;
}

.modern-form-input-wrapper .form-control.is-valid {
    border-color: #10b981;
}

.modern-form-help {
    margin-top: 0.25rem;
    font-size: 0.8rem;
    color: #6b7280;
    line-height: 1.4;
}

.modern-form-error {
    margin-top: 0.25rem;
    font-size: 0.8rem;
    color: #ef4444;
    line-height: 1.4;
}

.text-red-500 {
    color: #ef4444;
}

/* Input with icon */
.modern-form-input-wrapper.has-icon .form-control {
    padding-left: 2.5rem;
}

.modern-form-input-wrapper .input-icon {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
    font-size: 1rem;
    pointer-events: none;
}

/* Input with button */
.modern-form-input-wrapper.has-button {
    display: flex;
    gap: 0.5rem;
}

.modern-form-input-wrapper.has-button .form-control {
    flex: 1;
}

.modern-form-input-wrapper.has-button .btn {
    flex-shrink: 0;
}

/* Select styling */
.modern-form-input-wrapper select.form-control {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
    appearance: none;
}

/* Textarea styling */
.modern-form-input-wrapper textarea.form-control {
    resize: vertical;
    min-height: 100px;
}

/* File input styling */
.modern-form-input-wrapper input[type="file"].form-control {
    padding: 0.5rem;
}

.modern-form-input-wrapper input[type="file"].form-control::-webkit-file-upload-button {
    background: #f3f4f6;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 0.5rem 1rem;
    margin-right: 0.75rem;
    cursor: pointer;
    font-size: 0.875rem;
    color: #374151;
    transition: all 0.2s ease;
}

.modern-form-input-wrapper input[type="file"].form-control::-webkit-file-upload-button:hover {
    background: #e5e7eb;
}

/* Checkbox and radio styling */
.modern-form-input-wrapper .modern-radio-container,
.modern-form-input-wrapper .modern-checkbox-container {
    margin-bottom: 0;
}

.modern-form-input-wrapper .space-y-3 > * + * {
    margin-top: 0.75rem;
}

/* Responsive design */
@media (max-width: 768px) {
    .modern-form-input-wrapper.has-button {
        flex-direction: column;
    }
    
    .modern-form-input-wrapper.has-button .btn {
        width: 100%;
    }
}
</style>
