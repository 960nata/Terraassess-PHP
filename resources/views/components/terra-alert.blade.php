{{-- Terra Alert Component --}}
@props([
    'type' => 'info',
    'title' => null,
    'dismissible' => false,
    'class' => ''
])

@php
    $alertClasses = 'terra-alert';
    
    switch($type) {
        case 'success':
            $alertClasses .= ' terra-alert-success';
            break;
        case 'warning':
            $alertClasses .= ' terra-alert-warning';
            break;
        case 'error':
            $alertClasses .= ' terra-alert-error';
            break;
        case 'info':
        default:
            $alertClasses .= ' terra-alert-info';
            break;
    }
    
    $alertClasses .= ' ' . $class;
    
    $icons = [
        'success' => 'fas fa-check-circle',
        'warning' => 'fas fa-exclamation-triangle',
        'error' => 'fas fa-times-circle',
        'info' => 'fas fa-info-circle'
    ];
    
    $icon = $icons[$type] ?? $icons['info'];
@endphp

<div {{ $attributes->merge(['class' => $alertClasses, 'role' => 'alert']) }}>
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <i class="{{ $icon }} text-lg"></i>
        </div>
        
        <div class="ml-3 flex-1">
            @if($title)
                <h3 class="text-sm font-medium mb-1">{{ $title }}</h3>
            @endif
            
            <div class="text-sm">
                {{ $slot }}
            </div>
        </div>
        
        @if($dismissible)
            <div class="ml-auto pl-3">
                <button type="button" class="inline-flex text-current opacity-50 hover:opacity-75 focus:outline-none focus:opacity-100" onclick="this.parentElement.parentElement.parentElement.remove()">
                    <span class="sr-only">Tutup</span>
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        @endif
    </div>
</div>

