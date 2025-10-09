<template>
  <section class="min-h-screen flex items-center justify-center relative overflow-hidden">
    <!-- Background -->
    <div class="absolute inset-0 nebula-bg"></div>
    
    <!-- Content -->
    <div class="relative z-10 text-center px-4 max-w-6xl mx-auto">
      <!-- Main Title -->
      <h1 
        class="text-5xl md:text-7xl lg:text-8xl font-bold mb-6 animate-bounce-in"
        :class="isDark ? 'text-white' : 'text-gray-900'"
      >
        <span class="bg-gradient-to-r from-blue-400 via-purple-500 to-pink-500 bg-clip-text text-transparent">
          Elass
        </span>
      </h1>
      
      <!-- Subtitle -->
      <p 
        class="text-xl md:text-2xl lg:text-3xl mb-8 animate-slide-up"
        :class="isDark ? 'text-gray-300' : 'text-gray-600'"
      >
        Platform Monitoring IoT untuk Penelitian Siswa
      </p>
      
      <!-- Description -->
      <p 
        class="text-lg md:text-xl mb-12 max-w-3xl mx-auto animate-slide-up"
        :class="isDark ? 'text-gray-400' : 'text-gray-500'"
        style="animation-delay: 0.2s"
      >
        Pantau suhu tanah, kelembaban, dan humus secara real-time. 
        Kelola hasil penelitian siswa dengan mudah dan efisien.
      </p>
      
      <!-- CTA Button -->
      <button
        @click="openLoginModal"
        class="px-8 py-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white text-lg font-semibold rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 hover:shadow-2xl animate-slide-up"
        style="animation-delay: 0.4s"
      >
        Mulai Sekarang
      </button>
      
      <!-- Features Preview -->
      <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8 animate-slide-up" style="animation-delay: 0.6s">
        <div 
          class="p-6 rounded-xl glass"
          :class="isDark ? 'glass-dark' : 'glass'"
        >
          <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
          </div>
          <h3 class="text-lg font-semibold mb-2" :class="isDark ? 'text-white' : 'text-gray-900'">
            Monitoring Real-time
          </h3>
          <p class="text-sm" :class="isDark ? 'text-gray-300' : 'text-gray-600'">
            Pantau data sensor IoT secara langsung
          </p>
        </div>
        
        <div 
          class="p-6 rounded-xl glass"
          :class="isDark ? 'glass-dark' : 'glass'"
        >
          <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
          </div>
          <h3 class="text-lg font-semibold mb-2" :class="isDark ? 'text-white' : 'text-gray-900'">
            Data Penelitian
          </h3>
          <p class="text-sm" :class="isDark ? 'text-gray-300' : 'text-gray-600'">
            Kelola dan analisis hasil penelitian siswa
          </p>
        </div>
        
        <div 
          class="p-6 rounded-xl glass"
          :class="isDark ? 'glass-dark' : 'glass'"
        >
          <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <h3 class="text-lg font-semibold mb-2" :class="isDark ? 'text-white' : 'text-gray-900'">
            Analisis Cerdas
          </h3>
          <p class="text-sm" :class="isDark ? 'text-gray-300' : 'text-gray-600'">
            AI-powered insights untuk penelitian
          </p>
        </div>
      </div>
    </div>
    
    <!-- Floating elements -->
    <div class="absolute top-20 left-10 w-20 h-20 bg-blue-500 rounded-full opacity-20 animate-float"></div>
    <div class="absolute bottom-20 right-10 w-16 h-16 bg-purple-500 rounded-full opacity-20 animate-float" style="animation-delay: 1s"></div>
    <div class="absolute top-1/2 left-20 w-12 h-12 bg-pink-500 rounded-full opacity-20 animate-float" style="animation-delay: 2s"></div>
  </section>
</template>

<script>
import { ref, onMounted } from 'vue'

export default {
  name: 'HeroSection',
  emits: ['open-login-modal'],
  setup(props, { emit }) {
    const isDark = ref(false)

    const openLoginModal = () => {
      emit('open-login-modal')
    }

    onMounted(() => {
      // Check for dark mode
      isDark.value = document.documentElement.classList.contains('dark')
      
      // Watch for dark mode changes
      const observer = new MutationObserver(() => {
        isDark.value = document.documentElement.classList.contains('dark')
      })
      
      observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
      })
    })

    return {
      isDark,
      openLoginModal
    }
  }
}
</script>
