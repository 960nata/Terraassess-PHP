@props([
    'title' => 'Title',
    'value' => '0',
    'change' => '+0%',
    'changeType' => 'positive',
    'icon' => 'ph-chart-line',
    'color' => 'primary'
])

<div class="space-stat-card space-fade-in">
    <div class="flex items-center justify-between mb-4">
        <div class="space-stat-icon">
            <i class="{{ $icon }}"></i>
        </div>
        <div class="text-right">
            <div class="space-stat-value">{{ $value }}</div>
            <div class="space-stat-label">{{ $title }}</div>
        </div>
    </div>
    
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            @if($changeType === 'positive')
                <i class="ph-trend-up text-green-400 text-sm"></i>
                <span class="text-green-400 text-sm font-medium">{{ $change }}</span>
            @elseif($changeType === 'negative')
                <i class="ph-trend-down text-red-400 text-sm"></i>
                <span class="text-red-400 text-sm font-medium">{{ $change }}</span>
            @else
                <i class="ph-minus text-gray-400 text-sm"></i>
                <span class="text-gray-400 text-sm font-medium">{{ $change }}</span>
            @endif
        </div>
        
        <div class="text-xs text-gray-400">
            vs last month
        </div>
    </div>
</div>
