{{-- Modern Card Component --}}
@props([
    'variant' => 'default',
    'elevated' => false,
    'glass' => false,
    'hover' => true,
    'class' => ''
])

@php
    $cardClasses = 'card';
    
    if ($variant === 'elevated' || $elevated) {
        $cardClasses .= ' card-elevated';
    }
    
    if ($variant === 'glass' || $glass) {
        $cardClasses .= ' card-glass';
    }
    
    if ($variant === 'flat') {
        $cardClasses .= ' card-flat';
    }
    
    if (!$hover) {
        $cardClasses .= ' no-hover';
    }
    
    $cardClasses .= ' ' . $class;
@endphp

<div {{ $attributes->merge(['class' => $cardClasses]) }}>
    @if(isset($header))
        <div class="card-header">
            {{ $header }}
        </div>
    @endif
    
    @if(isset($body))
        <div class="card-body">
            {{ $body }}
        </div>
    @else
        {{ $slot }}
    @endif
    
    @if(isset($footer))
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif
</div>

<style>
.no-hover:hover {
    transform: none !important;
    box-shadow: inherit !important;
}
</style>
