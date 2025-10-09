@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'disabled' => false,
    'type' => 'button'
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 font-medium transition-all duration-200 ease-in-out cursor-pointer border-none';
    
    $variantClasses = [
        'primary' => 'galaxy-btn-primary',
        'secondary' => 'galaxy-btn-secondary',
        'ghost' => 'galaxy-btn-ghost'
    ];
    
    $sizeClasses = [
        'sm' => 'px-3 py-2 text-sm h-8',
        'md' => 'px-6 py-3 text-sm h-10',
        'lg' => 'px-8 py-4 text-base h-12'
    ];
    
    $classes = $baseClasses . ' ' . $variantClasses[$variant] . ' ' . $sizeClasses[$size];
    
    if ($disabled) {
        $classes .= ' opacity-50 cursor-not-allowed pointer-events-none';
    }
@endphp

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => $classes]) }}
    @if($disabled) disabled @endif
>
    @if($icon)
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {!! $icon !!}
        </svg>
    @endif
    
    {{ $slot }}
</button>
