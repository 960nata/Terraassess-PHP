@props([
    'title' => null,
    'subtitle' => null,
    'icon' => null,
    'color' => 'primary',
    'class' => '',
    'headerClass' => '',
    'bodyClass' => '',
    'footerClass' => '',
    'clickable' => false,
    'href' => null
])

@php
    $colorClasses = [
        'primary' => 'border-primary-200 bg-primary-50',
        'secondary' => 'border-secondary-200 bg-secondary-50',
        'success' => 'border-success-200 bg-success-50',
        'warning' => 'border-warning-200 bg-warning-50',
        'error' => 'border-error-200 bg-error-50',
        'info' => 'border-info-200 bg-info-50',
    ];
    
    $iconColors = [
        'primary' => 'text-primary-600',
        'secondary' => 'text-secondary-600',
        'success' => 'text-success-600',
        'warning' => 'text-warning-600',
        'error' => 'text-error-600',
        'info' => 'text-info-600',
    ];
    
    $colorClass = $colorClasses[$color] ?? $colorClasses['primary'];
    $iconColor = $iconColors[$color] ?? $iconColors['primary'];
    
    $cardClasses = 'unified-card ' . $class;
    if ($clickable) {
        $cardClasses .= ' cursor-pointer hover:shadow-lg transition-shadow';
    }
@endphp

@if($href)
    <a href="{{ $href }}" class="{{ $cardClasses }}">
@else
    <div class="{{ $cardClasses }}">
@endif

    @if($title || $subtitle || $icon)
        <div class="unified-card-header {{ $headerClass }}">
            <div class="flex items-center gap-3">
                @if($icon)
                    <div class="w-10 h-10 rounded-lg {{ $colorClass }} flex items-center justify-center">
                        <i class="{{ $icon }} {{ $iconColor }}"></i>
                    </div>
                @endif
                <div class="flex-1">
                    @if($title)
                        <h3 class="text-lg font-semibold text-secondary-900">{{ $title }}</h3>
                    @endif
                    @if($subtitle)
                        <p class="text-sm text-secondary-500 mt-1">{{ $subtitle }}</p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="unified-card-body {{ $bodyClass }}">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="unified-card-footer {{ $footerClass }}">
            {{ $footer }}
        </div>
    @endif

@if($href)
    </a>
@else
    </div>
@endif
