<template>
  <div v-if="isOpen" class="modal-overlay" @click="closeModal">
    <div class="modal-container" @click.stop>
      <!-- Modal Header -->
      <ModalHeader 
        :title="title" 
        @close="closeModal" 
      />
      
      <!-- Modal Body -->
      <div class="modal-body">
        <slot></slot>
      </div>
      
      <!-- Modal Footer (optional) -->
      <div v-if="showFooter" class="modal-footer">
        <slot name="footer">
          <button 
            @click="closeModal" 
            class="btn btn-secondary"
          >
            Cancel
          </button>
          <button 
            @click="handleConfirm" 
            class="btn btn-primary"
          >
            Confirm
          </button>
        </slot>
      </div>
    </div>
  </div>
</template>

<script>
import ModalHeader from './ModalHeader.vue'

export default {
  name: 'ExampleModal',
  components: {
    ModalHeader
  },
  props: {
    isOpen: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: 'Modal Title'
    },
    showFooter: {
      type: Boolean,
      default: true
    }
  },
  emits: ['close', 'confirm'],
  methods: {
    closeModal() {
      this.$emit('close')
    },
    handleConfirm() {
      this.$emit('confirm')
    }
  }
}
</script>

<style scoped>
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
  z-index: 1000;
}

.modal-container {
  background: white;
  border-radius: 8px;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  max-width: 500px;
  width: 90%;
  max-height: 90vh;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.modal-body {
  padding: 24px;
  flex: 1;
  overflow-y: auto;
}

.modal-footer {
  padding: 16px 24px;
  border-top: 1px solid #e5e7eb;
  display: flex;
  justify-content: flex-end;
  gap: 12px;
}

.btn {
  padding: 8px 16px;
  border-radius: 6px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
  border: none;
}

.btn-secondary {
  background: #f3f4f6;
  color: #374151;
}

.btn-secondary:hover {
  background: #e5e7eb;
}

.btn-primary {
  background: #3b82f6;
  color: white;
}

.btn-primary:hover {
  background: #2563eb;
}
</style>
