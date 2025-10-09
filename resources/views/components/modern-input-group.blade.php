@props([
    'prepend' => '',
    'append' => '',
    'size' => 'md'
])

<div class="modern-input-group modern-input-group-{{ $size }}" {{ $attributes }}>
    @if($prepend)
        <div class="modern-input-group-prepend">
            {{ $prepend }}
        </div>
    @endif
    
    <div class="modern-input-group-content">
        {{ $slot }}
    </div>
    
    @if($append)
        <div class="modern-input-group-append">
            {{ $append }}
        </div>
    @endif
</div>

<style>
.modern-input-group {
    display: flex;
    align-items: stretch;
    width: 100%;
}

.modern-input-group-content {
    flex: 1;
    position: relative;
}

.modern-input-group-content .form-control {
    width: 100%;
    border-radius: 0;
    border-right: none;
    border-left: none;
}

.modern-input-group-prepend,
.modern-input-group-append {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    background: #f9fafb;
    border: 2px solid #e5e7eb;
    color: #6b7280;
    font-size: 0.9rem;
    white-space: nowrap;
}

.modern-input-group-prepend {
    border-right: none;
    border-top-left-radius: 8px;
    border-bottom-left-radius: 8px;
}

.modern-input-group-append {
    border-left: none;
    border-top-right-radius: 8px;
    border-bottom-right-radius: 8px;
}

.modern-input-group:focus-within .modern-input-group-prepend,
.modern-input-group:focus-within .modern-input-group-append {
    border-color: #3b82f6;
    background: #eff6ff;
    color: #1d4ed8;
}

.modern-input-group:focus-within .modern-input-group-content .form-control {
    border-color: #3b82f6;
    box-shadow: none;
}

/* Size variants */
.modern-input-group-sm .modern-input-group-prepend,
.modern-input-group-sm .modern-input-group-append {
    padding: 0.5rem 0.75rem;
    font-size: 0.8rem;
}

.modern-input-group-sm .modern-input-group-content .form-control {
    padding: 0.5rem 0.75rem;
    font-size: 0.8rem;
}

.modern-input-group-lg .modern-input-group-prepend,
.modern-input-group-lg .modern-input-group-append {
    padding: 1rem 1.25rem;
    font-size: 1rem;
}

.modern-input-group-lg .modern-input-group-content .form-control {
    padding: 1rem 1.25rem;
    font-size: 1rem;
}

/* Button in append/prepend */
.modern-input-group-prepend .btn,
.modern-input-group-append .btn {
    margin: -0.75rem -1rem;
    border-radius: 0;
    border: none;
    background: transparent;
    color: inherit;
    padding: 0.75rem 1rem;
}

.modern-input-group-prepend .btn:first-child {
    border-top-left-radius: 6px;
    border-bottom-left-radius: 6px;
}

.modern-input-group-append .btn:last-child {
    border-top-right-radius: 6px;
    border-bottom-right-radius: 6px;
}

.modern-input-group-prepend .btn:hover,
.modern-input-group-append .btn:hover {
    background: #f3f4f6;
    color: #374151;
}

/* Icon in append/prepend */
.modern-input-group-prepend .icon,
.modern-input-group-append .icon {
    font-size: 1rem;
    line-height: 1;
}

/* Responsive design */
@media (max-width: 768px) {
    .modern-input-group {
        flex-direction: column;
    }
    
    .modern-input-group-prepend,
    .modern-input-group-append {
        border-radius: 8px 8px 0 0;
        border-bottom: none;
        justify-content: center;
    }
    
    .modern-input-group-append {
        border-radius: 0 0 8px 8px;
        border-top: none;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .modern-input-group-content .form-control {
        border-radius: 0;
        border-left: 2px solid #e5e7eb;
        border-right: 2px solid #e5e7eb;
    }
    
    .modern-input-group:focus-within .modern-input-group-prepend,
    .modern-input-group:focus-within .modern-input-group-append {
        border-bottom-color: #3b82f6;
    }
    
    .modern-input-group:focus-within .modern-input-group-append {
        border-top-color: #3b82f6;
    }
}
</style>
