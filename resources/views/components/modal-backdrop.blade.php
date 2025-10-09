{{-- Modal Backdrop Component --}}
{{-- Usage: Include this component in your modal implementation --}}

<div class="modal-backdrop" onclick="closeModal()"></div>

<script>
function closeModal() {
    // Find the modal backdrop and remove it
    const backdrop = document.querySelector('.modal-backdrop');
    if (backdrop) {
        backdrop.remove();
    }
    
    // Also close any associated modal content
    const modal = document.querySelector('.modal-content');
    if (modal) {
        modal.remove();
    }
}

// Optional: Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});
</script>
