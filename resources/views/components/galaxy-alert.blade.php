@props([
    'type' => 'info',
    'title' => null,
    'dismissible' => true,
    'id' => null
])

@php
    $alertId = $id ?? 'galaxy-alert-' . uniqid();
    
    $typeClasses = [
        'info' => 'galaxy-alert-info',
        'success' => 'galaxy-alert-success',
        'warning' => 'galaxy-alert-warning',
        'error' => 'galaxy-alert-error',
    ];
    
    $iconClasses = [
        'info' => 'text-blue-400',
        'success' => 'text-green-400',
        'warning' => 'text-yellow-400',
        'error' => 'text-red-400',
    ];
    
    $icons = [
        'info' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
        'success' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
        'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>',
        'error' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
    ];
@endphp

<div 
    id="{{ $alertId }}"
    class="galaxy-alert {{ $typeClasses[$type] }} p-4 rounded-lg border-l-4 mb-4"
    role="alert"
>
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <svg class="w-5 h-5 {{ $iconClasses[$type] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $icons[$type] !!}
            </svg>
        </div>
        
        <div class="ml-3 flex-1">
            @if($title)
                <h3 class="text-sm font-medium text-white mb-1">
                    {{ $title }}
                </h3>
            @endif
            
            <div class="text-sm text-gray-300">
                {{ $slot }}
            </div>
        </div>
        
        @if($dismissible)
            <div class="ml-auto pl-3">
                <button 
                    type="button"
                    class="galaxy-alert-dismiss inline-flex text-gray-400 hover:text-white focus:outline-none focus:text-white transition ease-in-out duration-150"
                    onclick="galaxyAlertDismiss('{{ $alertId }}')"
                    aria-label="Dismiss alert"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif
    </div>
</div>

<style>
.galaxy-alert-info {
    background: rgba(59, 130, 246, 0.1);
    border-color: #3b82f6;
}

.galaxy-alert-success {
    background: rgba(16, 185, 129, 0.1);
    border-color: #10b981;
}

.galaxy-alert-warning {
    background: rgba(245, 158, 11, 0.1);
    border-color: #f59e0b;
}

.galaxy-alert-error {
    background: rgba(239, 68, 68, 0.1);
    border-color: #ef4444;
}
</style>

<script>
function galaxyAlertDismiss(alertId) {
    const alert = document.getElementById(alertId);
    if (alert) {
        alert.style.opacity = '0';
        alert.style.transform = 'translateX(100%)';
        
        setTimeout(() => {
            alert.remove();
        }, 300);
    }
}

// Auto-dismiss after 5 seconds (optional)
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.galaxy-alert[data-auto-dismiss]');
    alerts.forEach(alert => {
        setTimeout(() => {
            galaxyAlertDismiss(alert.id);
        }, 5000);
    });
});
</script>
