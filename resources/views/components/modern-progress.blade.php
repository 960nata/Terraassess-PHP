{{-- Modern Progress Component --}}
@props([
    'value' => 0,
    'max' => 100,
    'size' => 'md',
    'color' => 'primary',
    'showLabel' => true,
    'animated' => false,
    'class' => ''
])

@php
    $percentage = min(100, max(0, ($value / $max) * 100));
    
    $sizeClasses = [
        'sm' => 'h-2',
        'md' => 'h-3',
        'lg' => 'h-4'
    ];
    
    $colorClasses = [
        'primary' => 'bg-primary-500',
        'success' => 'bg-success-500',
        'warning' => 'bg-warning-500',
        'error' => 'bg-error-500',
        'accent' => 'bg-accent-500'
    ];
    
    $progressClass = $sizeClasses[$size] ?? $sizeClasses['md'];
    $barClass = $colorClasses[$color] ?? $colorClasses['primary'];
    $containerClass = 'w-full bg-gray-200 rounded-full dark:bg-gray-700 ' . $progressClass . ' ' . $class;
@endphp

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    @if($showLabel)
        <div class="flex justify-between items-center mb-2">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ $attributes->get('label', 'Progress') }}
            </span>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ number_format($percentage, 1) }}%
            </span>
        </div>
    @endif
    
    <div class="{{ $containerClass }}">
        <div 
            class="{{ $barClass }} rounded-full transition-all duration-300 ease-in-out {{ $animated ? 'animate-pulse' : '' }}"
            style="width: {{ $percentage }}%"
            role="progressbar"
            aria-valuenow="{{ $value }}"
            aria-valuemin="0"
            aria-valuemax="{{ $max }}"
        ></div>
    </div>
    
    @if($attributes->get('description'))
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
            {{ $attributes->get('description') }}
        </p>
    @endif
</div>
