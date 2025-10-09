<template>
  <div 
    v-if="isOpen"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    @click="closeModal"
  >
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>
    
    <!-- Modal -->
    <div 
      class="relative w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl transform transition-all duration-300"
      :class="isOpen ? 'scale-100 opacity-100' : 'scale-95 opacity-0'"
      @click.stop
    >
      <!-- Header -->
      <div class="modal-header">
        <h2 class="modal-title">
          Masuk ke Elass
        </h2>
        <button
          @click="closeModal"
          class="modal-close-btn"
          type="button"
          aria-label="Close modal"
        >
          <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
      
      <!-- Form -->
      <form @submit.prevent="handleLogin" class="p-6 space-y-6">
        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Email
          </label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            required
            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors"
            placeholder="masukkan@email.com"
          />
        </div>
        
        <!-- Password -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Password
          </label>
          <div class="relative">
            <input
              id="password"
              v-model="form.password"
              :type="showPassword ? 'text' : 'password'"
              required
              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors pr-12"
              placeholder="Masukkan password"
            />
            <button
              type="button"
              @click="togglePassword"
              class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300"
            >
              <svg v-if="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
              </svg>
            </button>
          </div>
        </div>
        
        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
          <label class="flex items-center">
            <input
              v-model="form.remember"
              type="checkbox"
              class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
            />
            <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">
              Ingat saya
            </span>
          </label>
          <a href="#" class="text-sm text-blue-600 hover:text-blue-500 dark:text-blue-400">
            Lupa password?
          </a>
        </div>
        
        <!-- Submit Button -->
        <button
          type="submit"
          :disabled="isLoading"
          class="w-full py-3 px-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-105"
        >
          <span v-if="isLoading" class="flex items-center justify-center">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memproses...
          </span>
          <span v-else>Masuk</span>
        </button>
        
        <!-- Register Link -->
        <div class="text-center">
          <p class="text-sm text-gray-600 dark:text-gray-300">
            Belum punya akun?
            <a href="#" class="text-blue-600 hover:text-blue-500 dark:text-blue-400 font-medium">
              Daftar sekarang
            </a>
          </p>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { ref, reactive } from 'vue'
import axios from 'axios'

export default {
  name: 'LoginModal',
  props: {
    isOpen: {
      type: Boolean,
      default: false
    }
  },
  emits: ['close-modal', 'login-success'],
  setup(props, { emit }) {
    const form = reactive({
      email: '',
      password: '',
      remember: false
    })
    
    const showPassword = ref(false)
    const isLoading = ref(false)

    const togglePassword = () => {
      showPassword.value = !showPassword.value
    }

    const closeModal = () => {
      emit('close-modal')
    }

    const handleLogin = async () => {
      isLoading.value = true
      
      try {
        const response = await axios.post('/login', {
          email: form.email,
          password: form.password
        })
        
        // Store token and user data
        localStorage.setItem('auth_token', response.data.token)
        localStorage.setItem('user_role', response.data.user.roles_id)
        
        // Emit success event
        emit('login-success', response.data.user)
        
        // Close modal
        closeModal()
        
        // Reset form
        form.email = ''
        form.password = ''
        form.remember = false
        
        // Reload page to update authentication state
        window.location.reload()
        
      } catch (error) {
        console.error('Login error:', error)
        if (error.response?.data?.message) {
          alert(error.response.data.message)
        } else {
          alert('Login gagal. Silakan coba lagi.')
        }
      } finally {
        isLoading.value = false
      }
    }

    return {
      form,
      showPassword,
      isLoading,
      togglePassword,
      closeModal,
      handleLogin
    }
  }
}
</script>

<style scoped>
/* Modal Header Styles */
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
