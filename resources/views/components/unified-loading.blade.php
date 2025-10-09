@props([
    'size' => 'md',
    'text' => 'Loading...',
    'overlay' => false
])

@php
    $sizeClasses = [
        'sm' => 'w-4 h-4',
        'md' => 'w-8 h-8',
        'lg' => 'w-12 h-12',
        'xl' => 'w-16 h-16'
    ];
@endphp

@if($overlay)
    <div class="fixed inset-0 bg-white bg-opacity-75 flex items-center justify-center z-50">
        <div class="text-center">
            <div class="inline-block animate-spin rounded-full {{ $sizeClasses[$size] }} border-b-2 border-primary-600"></div>
            @if($text)
                <p class="mt-2 text-sm text-secondary-600">{{ $text }}</p>
            @endif
        </div>
    </div>
@else
    <div class="flex items-center justify-center">
        <div class="inline-block animate-spin rounded-full {{ $sizeClasses[$size] }} border-b-2 border-primary-600"></div>
        @if($text)
            <span class="ml-2 text-sm text-secondary-600">{{ $text }}</span>
        @endif
    </div>
@endif
