{{-- Terra Button Component --}}
@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'iconPosition' => 'left',
    'loading' => false,
    'disabled' => false,
    'class' => ''
])

@php
    $buttonClasses = 'terra-btn';
    
    // Variant classes
    switch($variant) {
        case 'primary':
            $buttonClasses .= ' terra-btn-primary';
            break;
        case 'secondary':
            $buttonClasses .= ' terra-btn-secondary';
            break;
        case 'outline':
            $buttonClasses .= ' terra-btn-outline';
            break;
        case 'ghost':
            $buttonClasses .= ' terra-btn-ghost';
            break;
        default:
            $buttonClasses .= ' terra-btn-primary';
    }
    
    // Size classes
    switch($size) {
        case 'sm':
            $buttonClasses .= ' terra-btn-sm';
            break;
        case 'lg':
            $buttonClasses .= ' terra-btn-lg';
            break;
        case 'xl':
            $buttonClasses .= ' terra-btn-xl';
            break;
    }
    
    if ($disabled || $loading) {
        $buttonClasses .= ' opacity-50 cursor-not-allowed';
    }
    
    $buttonClasses .= ' ' . $class;
@endphp

<button {{ $attributes->merge(['class' => $buttonClasses, 'disabled' => $disabled || $loading]) }}>
    @if($loading)
        <i class="fas fa-spinner fa-spin"></i>
    @elseif($icon && $iconPosition === 'left')
        <i class="{{ $icon }}"></i>
    @endif
    
    @if($slot->isNotEmpty())
        <span>{{ $slot }}</span>
    @endif
    
    @if($icon && $iconPosition === 'right')
        <i class="{{ $icon }}"></i>
    @endif
</button>

