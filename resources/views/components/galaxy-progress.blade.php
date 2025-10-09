@props([
    'value' => 0,
    'max' => 100,
    'size' => 'md',
    'variant' => 'primary',
    'showLabel' => true,
    'animated' => false,
    'striped' => false
])

@php
    $percentage = min(100, max(0, ($value / $max) * 100));
    $sizeClasses = [
        'sm' => 'h-2',
        'md' => 'h-3',
        'lg' => 'h-4',
        'xl' => 'h-6'
    ];
    
    $variantClasses = [
        'primary' => 'bg-galaxy-primary',
        'secondary' => 'bg-galaxy-secondary',
        'success' => 'bg-galaxy-success',
        'warning' => 'bg-galaxy-warning',
        'error' => 'bg-galaxy-error',
        'accent' => 'bg-galaxy-accent'
    ];
    
    $barClasses = $variantClasses[$variant] ?? $variantClasses['primary'];
    
    if ($animated) {
        $barClasses .= ' galaxy-progress-animated';
    }
    
    if ($striped) {
        $barClasses .= ' galaxy-progress-striped';
    }
@endphp

<div class="galaxy-progress">
    @if($showLabel)
        <div class="flex justify-between items-center mb-2">
            <span class="text-sm font-medium text-gray-300">Progress</span>
            <span class="text-sm text-gray-400">{{ round($percentage) }}%</span>
        </div>
    @endif
    
    <div class="galaxy-progress-track {{ $sizeClasses[$size] }} bg-gray-700 rounded-full overflow-hidden">
        <div 
            class="galaxy-progress-bar {{ $barClasses }} h-full rounded-full transition-all duration-300 ease-out"
            style="width: {{ $percentage }}%"
            role="progressbar"
            aria-valuenow="{{ $value }}"
            aria-valuemin="0"
            aria-valuemax="{{ $max }}"
        ></div>
    </div>
</div>

<style>
.galaxy-progress-striped {
    background-image: linear-gradient(
        45deg,
        rgba(255, 255, 255, 0.15) 25%,
        transparent 25%,
        transparent 50%,
        rgba(255, 255, 255, 0.15) 50%,
        rgba(255, 255, 255, 0.15) 75%,
        transparent 75%,
        transparent
    );
    background-size: 1rem 1rem;
}

.galaxy-progress-animated .galaxy-progress-bar {
    animation: galaxy-progress-stripes 1s linear infinite;
}

@keyframes galaxy-progress-stripes {
    0% {
        background-position: 1rem 0;
    }
    100% {
        background-position: 0 0;
    }
}
</style>
