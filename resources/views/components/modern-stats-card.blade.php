@props([
    'title' => '',
    'value' => '',
    'change' => '',
    'changeType' => 'positive',
    'icon' => 'ph-chart-line',
    'color' => 'primary'
])

@php
    $colorClasses = [
        'primary' => 'bg-primary-500 text-white',
        'success' => 'bg-green-500 text-white',
        'accent' => 'bg-blue-500 text-white',
        'warning' => 'bg-yellow-500 text-white',
        'danger' => 'bg-red-500 text-white',
        'info' => 'bg-cyan-500 text-white'
    ];
    
    $changeClasses = [
        'positive' => 'text-green-600 bg-green-100',
        'negative' => 'text-red-600 bg-red-100',
        'neutral' => 'text-gray-600 bg-gray-100'
    ];
    
    $iconClass = $colorClasses[$color] ?? $colorClasses['primary'];
    $changeClass = $changeClasses[$changeType] ?? $changeClasses['positive'];
@endphp

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">{{ $title }}</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $value }}</p>
            @if($change)
                <div class="flex items-center mt-2">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $changeClass }}">
                        {{ $change }}
                    </span>
                </div>
            @endif
        </div>
        <div class="flex-shrink-0">
            <div class="w-12 h-12 rounded-lg {{ $iconClass }} flex items-center justify-center">
                <i class="{{ $icon }} text-xl"></i>
            </div>
        </div>
    </div>
</div>
