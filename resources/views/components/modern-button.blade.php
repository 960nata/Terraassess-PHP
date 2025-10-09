{{-- Modern Button Component --}}
@props([
    'variant' => 'primary',
    'size' => 'md',
    'loading' => false,
    'disabled' => false,
    'icon' => null,
    'iconPosition' => 'left',
    'class' => ''
])

@php
    $buttonClasses = 'btn btn-' . $variant;
    
    if ($size === 'sm') {
        $buttonClasses .= ' btn-sm';
    } elseif ($size === 'lg') {
        $buttonClasses .= ' btn-lg';
    } elseif ($size === 'xl') {
        $buttonClasses .= ' btn-xl';
    }
    
    if ($loading || $disabled) {
        $buttonClasses .= ' opacity-50 cursor-not-allowed';
    }
    
    $buttonClasses .= ' ' . $class;
@endphp

<button {{ $attributes->merge([
    'class' => $buttonClasses,
    'disabled' => $disabled || $loading
]) }}>
    @if($loading)
        <span class="spinner spinner-sm"></span>
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
