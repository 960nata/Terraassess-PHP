{{-- Terra Card Component --}}
@props([
    'variant' => 'default',
    'elevated' => false,
    'hover' => true,
    'class' => '',
    'header' => null,
    'footer' => null
])

@php
    $cardClasses = 'terra-card';
    
    if ($elevated) {
        $cardClasses .= ' shadow-lg';
    }
    
    if (!$hover) {
        $cardClasses .= ' no-hover';
    }
    
    $cardClasses .= ' ' . $class;
@endphp

<div {{ $attributes->merge(['class' => $cardClasses]) }}>
    @if($header)
        <div class="terra-card-header">
            {{ $header }}
        </div>
    @endif
    
    <div class="terra-card-body">
        {{ $slot }}
    </div>
    
    @if($footer)
        <div class="terra-card-footer">
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

