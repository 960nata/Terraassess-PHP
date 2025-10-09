{{-- Modern Alert Component --}}
@props([
    'type' => 'info',
    'dismissible' => false,
    'class' => ''
])

@php
    $alertClasses = 'alert alert-' . $type . ' ' . $class;
@endphp

<div {{ $attributes->merge(['class' => $alertClasses]) }}>
    <div class="flex items-start">
        @if($type === 'success')
            <i class="ph-check-circle text-success-600 text-lg mr-3 mt-0.5"></i>
        @elseif($type === 'warning')
            <i class="ph-warning text-warning-600 text-lg mr-3 mt-0.5"></i>
        @elseif($type === 'error')
            <i class="ph-x-circle text-error-600 text-lg mr-3 mt-0.5"></i>
        @else
            <i class="ph-info text-accent-600 text-lg mr-3 mt-0.5"></i>
        @endif
        
        <div class="flex-1">
            {{ $slot }}
        </div>
        
        @if($dismissible)
            <button type="button" class="ml-3 text-gray-400 hover:text-gray-600" onclick="this.parentElement.parentElement.parentElement.remove()">
                <i class="ph-x text-lg"></i>
            </button>
        @endif
    </div>
</div>
