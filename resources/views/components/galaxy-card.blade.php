@props([
    'title' => null,
    'subtitle' => null,
    'icon' => null,
    'hover' => true
])

@php
    $classes = 'galaxy-card';
    if (!$hover) {
        $classes .= ' hover:transform-none hover:shadow-none';
    }
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if($title || $subtitle || $icon)
        <div class="galaxy-card-header">
            @if($title)
                <h3 class="galaxy-card-title">
                    @if($icon)
                        <svg class="galaxy-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $icon !!}
                        </svg>
                    @endif
                    {{ $title }}
                </h3>
            @endif
            
            @if($subtitle)
                <p class="galaxy-card-subtitle">{{ $subtitle }}</p>
            @endif
        </div>
    @endif
    
    <div class="galaxy-card-content">
        {{ $slot }}
    </div>
</div>
