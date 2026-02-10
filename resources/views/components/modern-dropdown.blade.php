@props([
    'trigger' => null,
    'position' => 'bottom-right',
    'class' => ''
])

@php
    $positionClasses = [
        'bottom-right' => 'top-full right-0',
        'bottom-left' => 'top-full left-0',
        'top-right' => 'bottom-full right-0',
        'top-left' => 'bottom-full left-0'
    ];
    
    $positionClass = $positionClasses[$position] ?? $positionClasses['bottom-right'];
@endphp

<div class="relative inline-block text-left {{ $class }}" x-data="{ open: false }" @click.away="open = false">
    <div @click="open = !open">
        {{ $trigger ?: $slot }}
    </div>
    
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute {{ $positionClass }} z-10 mt-2 w-56 origin-top-right rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
        style="display: none;"
    >
        <div class="py-1" role="menu" aria-orientation="vertical">
            {{ $dropdown }}
        </div>
    </div>
</div>
