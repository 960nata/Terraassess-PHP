{{-- Complete Modal Example with Backdrop --}}
{{-- This demonstrates how to use the modal backdrop component --}}

<!-- Modal Trigger Button -->
<button onclick="openModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
    Open Modal
</button>

<!-- Modal Structure -->
<div id="modal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <!-- Backdrop -->
    <div class="modal-backdrop" onclick="closeModal()"></div>
    
    <!-- Modal Content -->
    <div class="modal-content bg-white rounded-lg shadow-xl max-w-md w-full mx-4 relative z-10">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Modal Title</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <p class="text-gray-600 mb-4">
                This is a modal with the backdrop you requested. Click outside the modal or press Escape to close it.
            </p>
            <div class="flex justify-end space-x-2">
                <button onclick="closeModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    Cancel
                </button>
                <button onclick="closeModal()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openModal() {
    const modal = document.getElementById('modal');
    modal.classList.remove('hidden');
    // Prevent body scroll when modal is open
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    const modal = document.getElementById('modal');
    modal.classList.add('hidden');
    // Restore body scroll
    document.body.style.overflow = 'auto';
}

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});
</script>

<style>
/* Additional modal content styles */
.modal-content {
    animation: modalSlideIn 200ms ease;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: scale(0.95) translateY(-10px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}
</style>
