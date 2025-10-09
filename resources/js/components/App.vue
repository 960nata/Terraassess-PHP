<template>
  <div id="app">
    <!-- Loading Spinner -->
    <div v-if="loading" class="loading-overlay">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <!-- Navigation - Only show for authenticated routes -->
    <nav v-if="showNavigation" class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container-fluid">
        <router-link to="/dashboard" class="navbar-brand">
          <i class="fas fa-graduation-cap me-2"></i>
          Elass 2
        </router-link>
        
        <button 
          class="navbar-toggler" 
          type="button" 
          data-bs-toggle="collapse" 
          data-bs-target="#navbarNav"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <router-link to="/dashboard" class="nav-link" active-class="active">
                <i class="fas fa-tachometer-alt me-1"></i>
                Dashboard
              </router-link>
            </li>
            <li class="nav-item">
              <router-link to="/tugas" class="nav-link" active-class="active">
                <i class="fas fa-tasks me-1"></i>
                Tugas
              </router-link>
            </li>
            <li class="nav-item">
              <router-link to="/ujian" class="nav-link" active-class="active">
                <i class="fas fa-clipboard-check me-1"></i>
                Ujian
              </router-link>
            </li>
            <li class="nav-item">
              <router-link to="/materi" class="nav-link" active-class="active">
                <i class="fas fa-book me-1"></i>
                Materi
              </router-link>
            </li>
          </ul>
          
          <ul class="navbar-nav">
            <li class="nav-item dropdown">
              <a 
                class="nav-link dropdown-toggle" 
                href="#" 
                id="navbarDropdown" 
                role="button" 
                data-bs-toggle="dropdown"
              >
                <i class="fas fa-user me-1"></i>
                {{ user.name }}
              </a>
              <ul class="dropdown-menu">
                <li>
                  <router-link to="/profile" class="dropdown-item">
                    <i class="fas fa-user-cog me-2"></i>
                    Profile
                  </router-link>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <a href="#" @click="logout" class="dropdown-item">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    Logout
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main :class="showNavigation ? 'container-fluid py-4' : ''">
      <!-- Alert Messages -->
      <div v-if="alert.show" class="alert alert-dismissible fade show" :class="alert.type" role="alert">
        <i :class="alert.icon" class="me-2"></i>
        {{ alert.message }}
        <button 
          type="button" 
          class="btn-close" 
          @click="hideAlert"
        ></button>
      </div>

      <!-- Router View -->
      <router-view />
    </main>

    <!-- Footer - Only show for authenticated routes -->
    <footer v-if="showNavigation" class="bg-light text-center text-muted py-3 mt-5">
      <div class="container">
        <p class="mb-0">&copy; 2024 Elass 2. All rights reserved.</p>
      </div>
    </footer>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'

export default {
  name: 'App',
  setup() {
    const router = useRouter()
    const route = useRoute()
    const loading = ref(false)
    const user = ref({})
    const alert = ref({
      show: false,
      type: 'alert-info',
      icon: 'fas fa-info-circle',
      message: ''
    })

    // Show navigation only for authenticated routes
    const showNavigation = computed(() => {
      return route.meta.requiresAuth
    })

    // Show alert
    const showAlert = (message, type = 'info') => {
      const alertTypes = {
        'success': { class: 'alert-success', icon: 'fas fa-check-circle' },
        'error': { class: 'alert-danger', icon: 'fas fa-exclamation-circle' },
        'warning': { class: 'alert-warning', icon: 'fas fa-exclamation-triangle' },
        'info': { class: 'alert-info', icon: 'fas fa-info-circle' }
      }

      alert.value = {
        show: true,
        type: alertTypes[type].class,
        icon: alertTypes[type].icon,
        message: message
      }

      // Auto hide after 5 seconds
      setTimeout(() => {
        hideAlert()
      }, 5000)
    }

    // Hide alert
    const hideAlert = () => {
      alert.value.show = false
    }

    // Logout
    const logout = async () => {
      try {
        loading.value = true
        await axios.post('/logout')
        localStorage.removeItem('auth_token')
        localStorage.removeItem('user_role')
        window.location.href = '/login'
      } catch (error) {
        showAlert('Gagal logout', 'error')
      } finally {
        loading.value = false
      }
    }

    // Get user data
    const getUserData = async () => {
      try {
        const response = await axios.get('/user')
        user.value = response.data
      } catch (error) {
        console.error('Failed to get user data:', error)
      }
    }

    // Setup axios interceptors
    const setupInterceptors = () => {
      // Request interceptor
      axios.interceptors.request.use(
        config => {
          loading.value = true
          return config
        },
        error => {
          loading.value = false
          return Promise.reject(error)
        }
      )

      // Response interceptor
      axios.interceptors.response.use(
        response => {
          loading.value = false
          return response
        },
        error => {
          loading.value = false
          
          if (error.response?.status === 401) {
            localStorage.removeItem('auth_token')
            localStorage.removeItem('user_role')
            window.location.href = '/login'
            return
          }

          if (error.response?.status === 422) {
            const errors = error.response.data.errors
            const firstError = Object.values(errors)[0][0]
            showAlert(firstError, 'error')
            return
          }

          if (error.response?.status >= 500) {
            showAlert('Terjadi kesalahan pada server', 'error')
            return
          }

          return Promise.reject(error)
        }
      )
    }

    onMounted(() => {
      setupInterceptors()
      getUserData()
    })

    return {
      loading,
      user,
      alert,
      showNavigation,
      showAlert,
      hideAlert,
      logout
    }
  }
}
</script>

<style scoped>
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

.navbar-brand {
  font-weight: 600;
  font-size: 1.25rem;
}

.nav-link {
  font-weight: 500;
  transition: all 0.3s ease;
}

.nav-link:hover {
  transform: translateY(-1px);
}

.nav-link.active {
  background: rgba(255, 255, 255, 0.1);
  border-radius: 0.375rem;
}

.dropdown-item {
  transition: all 0.3s ease;
}

.dropdown-item:hover {
  background: #f8f9fa;
  transform: translateX(5px);
}

.alert {
  border: none;
  border-radius: 0.5rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

footer {
  margin-top: auto;
}

#app {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

main {
  flex: 1;
}
</style>
