@props([
    'id' => null,
    'title' => null,
    'size' => 'md',
    'closable' => true,
    'backdrop' => true
])

@php
    $modalId = $id ?? 'galaxy-modal-' . uniqid();
    $sizeClasses = [
        'sm' => 'max-w-md',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
        'full' => 'max-w-full mx-4'
    ];
@endphp

<!-- Modal Backdrop -->
@if($backdrop)
<div id="{{ $modalId }}-backdrop" class="galaxy-modal-backdrop hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-40"></div>
@endif

<!-- Modal -->
<div 
    id="{{ $modalId }}"
    class="galaxy-modal hidden fixed inset-0 z-50 overflow-y-auto"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $modalId }}-title"
>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="galaxy-modal-content {{ $sizeClasses[$size] }} w-full">
            <div class="galaxy-card">
                @if($title || $closable)
                    <div class="galaxy-card-header flex items-center justify-between">
                        @if($title)
                            <h3 id="{{ $modalId }}-title" class="galaxy-card-title text-lg">
                                {{ $title }}
                            </h3>
                        @endif
                        @if($closable)
                            <button 
                                type="button" 
                                class="galaxy-btn-ghost p-2"
                                onclick="galaxyModalClose('{{ $modalId }}')"
                                aria-label="Close modal"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                @endif
                
                <div class="galaxy-card-content">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function galaxyModalOpen(modalId) {
    const modal = document.getElementById(modalId);
    const backdrop = document.getElementById(modalId + '-backdrop');
    
    if (modal) {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        
        if (backdrop) {
            backdrop.classList.remove('hidden');
        }
        
        // Focus management
        const focusableElements = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
        if (focusableElements.length > 0) {
            focusableElements[0].focus();
        }
    }
}

function galaxyModalClose(modalId) {
    const modal = document.getElementById(modalId);
    const backdrop = document.getElementById(modalId + '-backdrop');
    
    if (modal) {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        
        if (backdrop) {
            backdrop.classList.add('hidden');
        }
    }
}

// Close on backdrop click
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('galaxy-modal-backdrop')) {
        const modal = e.target.previousElementSibling;
        if (modal && modal.classList.contains('galaxy-modal')) {
            galaxyModalClose(modal.id);
        }
    }
});

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const openModal = document.querySelector('.galaxy-modal:not(.hidden)');
        if (openModal) {
            galaxyModalClose(openModal.id);
        }
    }
});
</script>
