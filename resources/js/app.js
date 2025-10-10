import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import { createPinia } from 'pinia'
import axios from 'axios'
import './bootstrap'

// Import components
import App from './components/App.vue'
import HomePage from './components/HomePage.vue'
import DashboardCharts from './components/DashboardCharts.vue'

// Configure axios
axios.defaults.baseURL = '/api'
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.withCredentials = true

// Add token to requests
const token = document.querySelector('meta[name="csrf-token"]')
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
}

// Add auth token
const authToken = localStorage.getItem('auth_token')
if (authToken) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${authToken}`
}

// Response interceptor for token refresh
axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            localStorage.removeItem('auth_token')
            window.location.href = '/login'
        }
        return Promise.reject(error)
    }
)

// Router configuration
const routes = [
    {
        path: '/',
        name: 'home',
        component: HomePage
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

// Navigation guard
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('auth_token')
    const userRole = localStorage.getItem('user_role')
    
    if (to.meta.requiresAuth && !token) {
        next('/login')
        return
    }
    
    if (to.meta.roles && !to.meta.roles.includes(userRole)) {
        next('/')
        return
    }
    
    next()
})

// Create app
const app = createApp(App)
const pinia = createPinia()

app.use(router)
app.use(pinia)

// Global properties
app.config.globalProperties.$http = axios

// Global components
app.component('dashboard-charts', DashboardCharts)

// Mount app
app.mount('#app')