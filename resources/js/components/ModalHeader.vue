<template>
  <div class="modal-header">
    <h3 class="modal-title">{{ title }}</h3>
    <button 
      @click="$emit('close')" 
      class="modal-close-btn"
      type="button"
      aria-label="Close modal"
    >
      <svg 
        class="close-icon" 
        fill="none" 
        stroke="currentColor" 
        viewBox="0 0 24 24"
      >
        <path 
          stroke-linecap="round" 
          stroke-linejoin="round" 
          stroke-width="2" 
          d="M6 18L18 6M6 6l12 12"
        />
      </svg>
    </button>
  </div>
</template>

<script>
export default {
  name: 'ModalHeader',
  props: {
    title: {
      type: String,
      required: true
    }
  },
  emits: ['close']
}
</script>

<style scoped>
.modal-header {
  height: 60px;
  border-bottom: 1px solid #e5e7eb;
  padding: 0 24px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-title {
  font-size: 18px;
  font-weight: 600;
  color: #111827;
  margin: 0;
}

.modal-close-btn {
  width: 24px;
  height: 24px;
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #9ca3af;
  transition: color 0.2s ease;
}

.modal-close-btn:hover {
  color: #4b5563;
}

.close-icon {
  width: 100%;
  height: 100%;
}
</style>
