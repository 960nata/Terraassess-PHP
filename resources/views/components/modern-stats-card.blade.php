{{-- Modern Stats Card Component --}}
@props([
    'title' => '',
    'value' => '',
    'change' => null,
    'changeType' => 'positive',
    'icon' => null,
    'color' => 'primary',
    'class' => ''
])

@php
    $colorClasses = [
        'primary' => 'bg-primary-500 text-white',
        'success' => 'bg-success-500 text-white',
        'warning' => 'bg-warning-500 text-white',
        'error' => 'bg-error-500 text-white',
        'accent' => 'bg-accent-500 text-white',
        'secondary' => 'bg-secondary-500 text-white'
    ];
    
    $iconBgClass = $colorClasses[$color] ?? $colorClasses['primary'];
    $cardClass = 'card hover:shadow-lg transition-all duration-300 ' . $class;
@endphp

<div {{ $attributes->merge(['class' => $cardClass]) }}>
    <div class="card-body">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                    {{ $title }}
                </p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $value }}
                </p>
                @if($change)
                    <div class="flex items-center mt-2">
                        @if($changeType === 'positive')
                            <i class="ph-trend-up text-success-500 text-sm mr-1"></i>
                            <span class="text-sm text-success-600 font-medium">+{{ $change }}</span>
                        @elseif($changeType === 'negative')
                            <i class="ph-trend-down text-error-500 text-sm mr-1"></i>
                            <span class="text-sm text-error-600 font-medium">{{ $change }}</span>
                        @else
                            <i class="ph-minus text-gray-500 text-sm mr-1"></i>
                            <span class="text-sm text-gray-600 font-medium">{{ $change }}</span>
                        @endif
                        <span class="text-sm text-gray-500 ml-1">vs last period</span>
                    </div>
                @endif
            </div>
            
            @if($icon)
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 {{ $iconBgClass }} rounded-lg flex items-center justify-center">
                        <i class="{{ $icon }} text-xl"></i>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
