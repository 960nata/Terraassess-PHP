@props([
    'title' => '',
    'value' => '',
    'change' => '',
    'changeType' => 'positive',
    'icon' => 'ph-chart-line'
])

@php
    $changeClasses = [
        'positive' => 'text-green-400',
        'negative' => 'text-red-400',
        'neutral' => 'text-gray-400'
    ];
    
    $changeClass = $changeClasses[$changeType] ?? $changeClasses['positive'];
@endphp

<div class="space-stat-card space-fade-in">
    <div class="flex items-center justify-between mb-4">
        <div class="space-stat-icon">
            <i class="{{ $icon }} text-2xl"></i>
        </div>
        @if($change)
            <div class="space-stat-trend {{ $changeClass }}">
                <i class="ph-trend-up text-lg"></i>
            </div>
        @endif
    </div>
    
    <div class="space-stat-content">
        <div class="space-stat-number">{{ $value }}</div>
        <div class="space-stat-label">{{ $title }}</div>
        @if($change)
            <div class="space-stat-description">{{ $change }}</div>
        @endif
    </div>
</div>
