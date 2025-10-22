@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'disabled' => false,
    'class' => ''
])

@php
    $baseClass = 'inline-flex items-center justify-center font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-300 backdrop-blur-sm';
    
    $variantClasses = [
        'primary' => 'bg-gradient-to-r from-purple-600 to-blue-600 text-white hover:from-purple-700 hover:to-blue-700 focus:ring-purple-500 shadow-lg hover:shadow-xl',
        'secondary' => 'bg-gradient-to-r from-gray-600 to-gray-700 text-white hover:from-gray-700 hover:to-gray-800 focus:ring-gray-500 shadow-lg hover:shadow-xl',
        'success' => 'bg-gradient-to-r from-green-600 to-emerald-600 text-white hover:from-green-700 hover:to-emerald-700 focus:ring-green-500 shadow-lg hover:shadow-xl',
        'danger' => 'bg-gradient-to-r from-red-600 to-pink-600 text-white hover:from-red-700 hover:to-pink-700 focus:ring-red-500 shadow-lg hover:shadow-xl',
        'warning' => 'bg-gradient-to-r from-yellow-600 to-orange-600 text-white hover:from-yellow-700 hover:to-orange-700 focus:ring-yellow-500 shadow-lg hover:shadow-xl',
        'outline' => 'border-2 border-purple-500 text-purple-300 hover:bg-purple-500 hover:text-white focus:ring-purple-500'
    ];
    
    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base'
    ];
    
    $buttonClass = $baseClass . ' ' . ($variantClasses[$variant] ?? $variantClasses['primary']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']);
    
    if ($disabled) {
        $buttonClass .= ' opacity-50 cursor-not-allowed';
    }
    
    $buttonClass .= ' ' . $class;
@endphp

<button 
    type="{{ $type }}"
    {{ $disabled ? 'disabled' : '' }}
    class="{{ $buttonClass }}"
    {{ $attributes->except(['class', 'type', 'variant', 'size', 'disabled']) }}
>
    {{ $slot }}
</button>
