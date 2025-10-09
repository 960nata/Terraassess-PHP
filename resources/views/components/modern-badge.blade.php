{{-- Modern Badge Component --}}
@props([
    'variant' => 'primary',
    'size' => 'md',
    'class' => ''
])

@php
    $badgeClasses = 'badge badge-' . $variant;
    
    if ($size === 'sm') {
        $badgeClasses .= ' text-xs px-2 py-1';
    } elseif ($size === 'lg') {
        $badgeClasses .= ' text-sm px-4 py-2';
    }
    
    $badgeClasses .= ' ' . $class;
@endphp

<span {{ $attributes->merge(['class' => $badgeClasses]) }}>
    {{ $slot }}
</span>
