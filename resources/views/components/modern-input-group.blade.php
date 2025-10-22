@props([
    'prepend' => null,
    'append' => null,
    'class' => ''
])

<div class="relative {{ $class }}">
    @if($prepend)
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <span class="text-gray-500 dark:text-gray-400 text-sm">{{ $prepend }}</span>
        </div>
    @endif
    
    <div class="{{ $prepend ? 'pl-12' : '' }} {{ $append ? 'pr-12' : '' }}">
        {{ $slot }}
    </div>
    
    @if($append)
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <span class="text-gray-500 dark:text-gray-400 text-sm">{{ $append }}</span>
        </div>
    @endif
</div>
