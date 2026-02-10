{{-- Modern Form Group Component --}}
@props([
    'label' => null,
    'required' => false,
    'error' => null,
    'help' => null,
    'class' => ''
])

@php
    $groupClasses = 'space-y-2 ' . $class;
@endphp

<div {{ $attributes->merge(['class' => $groupClasses]) }}>
    @if($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ $label }}
            @if($required)
                <span class="text-red-500 ml-1">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        {{ $slot }}
    </div>
    
    @if($error)
        <p class="text-sm text-red-600 dark:text-red-400 flex items-center">
            <i class="ph-warning text-sm mr-1"></i>
            {{ $error }}
        </p>
    @endif
    
    @if($help)
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $help }}</p>
    @endif
</div>
