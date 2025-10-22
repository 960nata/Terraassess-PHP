@props([
    'variant' => 'primary',
    'size' => 'md',
    'class' => ''
])

@php
    $baseClass = 'inline-flex items-center font-medium rounded-full';
    
    $variantClasses = [
        'primary' => 'bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200',
        'secondary' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
        'success' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'danger' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        'info' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'accent' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200'
    ];
    
    $sizeClasses = [
        'sm' => 'px-2 py-1 text-xs',
        'md' => 'px-2.5 py-0.5 text-sm',
        'lg' => 'px-3 py-1 text-base'
    ];
    
    $badgeClass = $baseClass . ' ' . ($variantClasses[$variant] ?? $variantClasses['primary']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']);
    $badgeClass .= ' ' . $class;
@endphp

<span class="{{ $badgeClass }}" {{ $attributes->except(['class', 'variant', 'size']) }}>
    {{ $slot }}
</span>
