@props([
    'method' => 'POST',
    'action' => '',
    'enctype' => 'application/x-www-form-urlencoded'
])

<form 
    method="{{ $method === 'GET' ? 'GET' : 'POST' }}"
    action="{{ $action }}"
    enctype="{{ $enctype }}"
    class="modern-form"
    {{ $attributes }}
>
    @if($method !== 'GET')
        @csrf
    @endif
    
    @if($method === 'PUT' || $method === 'PATCH' || $method === 'DELETE')
        @method($method)
    @endif
    
    {{ $slot }}
</form>

<style>
.modern-form {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 2rem;
    max-width: 100%;
}

.modern-form .form-group {
    margin-bottom: 1.5rem;
}

.modern-form .form-group:last-child {
    margin-bottom: 0;
}

.modern-form .form-label {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.modern-form .form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    background: white;
}

.modern-form .form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.modern-form .form-control.is-invalid {
    border-color: #ef4444;
}

.modern-form .form-control.is-valid {
    border-color: #10b981;
}

.modern-form .invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.8rem;
    color: #ef4444;
}

.modern-form .valid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.8rem;
    color: #10b981;
}

.modern-form .form-text {
    margin-top: 0.25rem;
    font-size: 0.8rem;
    color: #6b7280;
}

.modern-form .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
    text-decoration: none;
    justify-content: center;
}

.modern-form .btn:hover {
    transform: translateY(-1px);
}

.modern-form .btn-primary {
    background: #3b82f6;
    color: white;
}

.modern-form .btn-primary:hover {
    background: #2563eb;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.modern-form .btn-secondary {
    background: #6b7280;
    color: white;
}

.modern-form .btn-secondary:hover {
    background: #4b5563;
    box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
}

.modern-form .btn-success {
    background: #10b981;
    color: white;
}

.modern-form .btn-success:hover {
    background: #059669;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.modern-form .btn-danger {
    background: #ef4444;
    color: white;
}

.modern-form .btn-danger:hover {
    background: #dc2626;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.modern-form .btn-outline {
    background: transparent;
    border: 2px solid #d1d5db;
    color: #374151;
}

.modern-form .btn-outline:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
}

.modern-form .form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e5e7eb;
}

@media (max-width: 768px) {
    .modern-form {
        padding: 1.5rem;
    }
    
    .modern-form .form-actions {
        flex-direction: column;
    }
    
    .modern-form .btn {
        width: 100%;
    }
}
</style>
