@props([
    'title' => '',
    'value' => '',
    'change' => null,
    'changeType' => 'neutral',
    'icon' => null,
    'color' => 'primary',
    'href' => null,
    'class' => ''
])

@php
    $colorClasses = [
        'primary' => [
            'bg' => 'bg-primary-50',
            'icon' => 'bg-primary-600',
            'text' => 'text-primary-600',
            'border' => 'border-primary-200'
        ],
        'secondary' => [
            'bg' => 'bg-secondary-50',
            'icon' => 'bg-secondary-600',
            'text' => 'text-secondary-600',
            'border' => 'border-secondary-200'
        ],
        'success' => [
            'bg' => 'bg-success-50',
            'icon' => 'bg-success-600',
            'text' => 'text-success-600',
            'border' => 'border-success-200'
        ],
        'warning' => [
            'bg' => 'bg-warning-50',
            'icon' => 'bg-warning-600',
            'text' => 'text-warning-600',
            'border' => 'border-warning-200'
        ],
        'error' => [
            'bg' => 'bg-error-50',
            'icon' => 'bg-error-600',
            'text' => 'text-error-600',
            'border' => 'border-error-200'
        ],
        'info' => [
            'bg' => 'bg-info-50',
            'icon' => 'bg-info-600',
            'text' => 'text-info-600',
            'border' => 'border-info-200'
        ],
    ];
    
    $changeColors = [
        'positive' => 'text-success-600 bg-success-50',
        'negative' => 'text-error-600 bg-error-50',
        'neutral' => 'text-secondary-600 bg-secondary-50',
    ];
    
    $colors = $colorClasses[$color] ?? $colorClasses['primary'];
    $changeColor = $changeColors[$changeType] ?? $changeColors['neutral'];
    
    $cardClasses = 'unified-card hover:shadow-lg transition-all duration-200 ' . $class;
    if ($href) {
        $cardClasses .= ' cursor-pointer';
    }
@endphp

@if($href)
    <a href="{{ $href }}" class="{{ $cardClasses }}">
@else
    <div class="{{ $cardClasses }}">
@endif

    <div class="unified-card-body">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-secondary-600 mb-1">{{ $title }}</p>
                <p class="text-2xl font-bold text-secondary-900">{{ $value }}</p>
                @if($change)
                    <div class="flex items-center mt-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $changeColor }}">
                            @if($changeType === 'positive')
                                <i class="fas fa-arrow-up mr-1"></i>
                            @elseif($changeType === 'negative')
                                <i class="fas fa-arrow-down mr-1"></i>
                            @else
                                <i class="fas fa-minus mr-1"></i>
                            @endif
                            {{ $change }}
                        </span>
                    </div>
                @endif
            </div>
            @if($icon)
                <div class="w-12 h-12 {{ $colors['bg'] }} rounded-lg flex items-center justify-center">
                    <i class="{{ $icon }} {{ $colors['text'] }} text-lg"></i>
                </div>
            @endif
        </div>
    </div>

@if($href)
    </a>
@else
    </div>
@endif
