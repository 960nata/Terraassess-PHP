{{-- Modern Input Group Component --}}
@props([
    'prepend' => null,
    'append' => null,
    'class' => ''
])

@php
    $groupClasses = 'relative ' . $class;
@endphp

<div {{ $attributes->merge(['class' => $groupClasses]) }}>
    @if($prepend)
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <span class="text-gray-500 sm:text-sm">{{ $prepend }}</span>
        </div>
    @endif
    
    {{ $slot }}
    
    @if($append)
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <span class="text-gray-500 sm:text-sm">{{ $append }}</span>
        </div>
    @endif
</div>
