<!-- Modal Component -->
<div class="modal-overlay" id="modalOverlay" onclick="closeModal()">
    <div class="modal-container" onclick="event.stopPropagation()">
                <div class="modal-header">
            <h3 class="modal-title">
                @if(isset($icon))
                    <i class="{{ $icon }}"></i>
                @endif
                {{ $title ?? 'Modal' }}
            </h3>
            <button class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
                </div>
            
            <div class="modal-body">
                {{ $slot }}
            </div>
            
        @if(isset($footer) && $footer)
                <div class="modal-footer">
                @if(isset($actions) && is_array($actions))
                    @foreach($actions as $action)
                        <button class="btn {{ $action['class'] ?? 'btn-secondary' }}" 
                                @if(isset($action['onclick'])) onclick="{{ $action['onclick'] }}" @endif>
                            @if(isset($action['icon']))
                                <i class="{{ $action['icon'] }}"></i>
                            @endif
                            {{ $action['text'] ?? 'Action' }}
                            </button>
                        @endforeach
                    @else
                    <button class="btn btn-secondary" onclick="closeModal()">Tutup</button>
                    <button class="btn btn-primary" onclick="submitModal()">Simpan</button>
                    @endif
                </div>
            @endif
        </div>
    </div>

<style>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    padding: 1rem;
}

.modal-overlay.show {
    opacity: 1;
    visibility: visible;
}

.modal-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    max-width: 90vw;
    max-height: 90vh;
    width: 100%;
    max-width: 500px;
    overflow: hidden;
    transform: scale(0.9) translateY(20px);
    transition: all 0.3s ease;
}

.modal-overlay.show .modal-container {
    transform: scale(1) translateY(0);
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    background: #f9fafb;
}

.modal-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.modal-title i {
    color: #3b82f6;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.25rem;
    color: #6b7280;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.modal-close:hover {
    background: #e5e7eb;
    color: #374151;
}

.modal-body {
    padding: 1.5rem;
    max-height: 60vh;
    overflow-y: auto;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    padding: 1.5rem;
    border-top: 1px solid #e5e7eb;
    background: #f9fafb;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
    text-decoration: none;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
    transform: translateY(-1px);
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
    transform: translateY(-1px);
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
    transform: translateY(-1px);
}

.btn-success {
    background: #10b981;
    color: white;
}

.btn-success:hover {
    background: #059669;
    transform: translateY(-1px);
}

/* Modal sizes */
.modal-sm .modal-container {
    max-width: 400px;
}

.modal-lg .modal-container {
    max-width: 800px;
}

.modal-xl .modal-container {
    max-width: 1200px;
}

/* Animation variants */
.modal-fade .modal-container {
    transform: translateY(-50px);
}

.modal-slide .modal-container {
    transform: translateX(100%);
}

.modal-overlay.show .modal-fade .modal-container,
.modal-overlay.show .modal-slide .modal-container {
    transform: translateY(0) translateX(0);
}

@media (max-width: 768px) {
    .modal-container {
        max-width: 95vw;
        margin: 0.5rem;
    }
    
    .modal-header,
    .modal-body,
    .modal-footer {
        padding: 1rem;
    }
    
    .modal-footer {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
function openModal(modalId = 'modalOverlay') {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId = 'modalOverlay') {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = 'auto';
    }
}

function submitModal(modalId = 'modalOverlay') {
    // Override this function in your specific modal
    console.log('Modal submitted');
    closeModal(modalId);
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});

// Auto-close modal after form submission
function autoCloseModal(modalId = 'modalOverlay', delay = 1000) {
    setTimeout(() => {
        closeModal(modalId);
    }, delay);
}
</script>