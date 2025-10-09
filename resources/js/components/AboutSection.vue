<template>
  <section 
    class="py-20 px-4"
    :class="isDark ? 'bg-gray-900' : 'bg-gray-50'"
  >
    <div class="container mx-auto max-w-6xl">
      <!-- Section Header -->
      <div class="text-center mb-16">
        <h2 
          class="text-4xl md:text-5xl font-bold mb-6"
          :class="isDark ? 'text-white' : 'text-gray-900'"
        >
          Tentang Aplikasi
        </h2>
        <p 
          class="text-xl max-w-3xl mx-auto"
          :class="isDark ? 'text-gray-300' : 'text-gray-600'"
        >
          Elass adalah platform monitoring IoT yang dirancang khusus untuk mendukung 
          penelitian siswa dalam bidang pertanian dan lingkungan.
        </p>
      </div>

      <!-- Features Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Feature 1: Monitoring Suhu Tanah -->
        <div 
          class="group p-8 rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl"
          :class="isDark ? 'bg-gray-800 hover:bg-gray-700' : 'bg-white hover:bg-gray-50'"
        >
          <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-orange-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
          </div>
          <h3 
            class="text-2xl font-bold mb-4"
            :class="isDark ? 'text-white' : 'text-gray-900'"
          >
            Monitoring Suhu Tanah
          </h3>
          <p 
            class="text-lg leading-relaxed"
            :class="isDark ? 'text-gray-300' : 'text-gray-600'"
          >
            Pantau suhu tanah secara real-time dengan sensor IoT yang akurat. 
            Data dapat diakses kapan saja untuk analisis penelitian.
          </p>
        </div>

        <!-- Feature 2: Kelembaban -->
        <div 
          class="group p-8 rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl"
          :class="isDark ? 'bg-gray-800 hover:bg-gray-700' : 'bg-white hover:bg-gray-50'"
        >
          <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
            </svg>
          </div>
          <h3 
            class="text-2xl font-bold mb-4"
            :class="isDark ? 'text-white' : 'text-gray-900'"
          >
            Monitoring Kelembaban
          </h3>
          <p 
            class="text-lg leading-relaxed"
            :class="isDark ? 'text-gray-300' : 'text-gray-600'"
          >
            Ukur tingkat kelembaban tanah dan udara dengan presisi tinggi. 
            Data historis tersedia untuk analisis tren jangka panjang.
          </p>
        </div>

        <!-- Feature 3: Humus -->
        <div 
          class="group p-8 rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl"
          :class="isDark ? 'bg-gray-800 hover:bg-gray-700' : 'bg-white hover:bg-gray-50'"
        >
          <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
          </div>
          <h3 
            class="text-2xl font-bold mb-4"
            :class="isDark ? 'text-white' : 'text-gray-900'"
          >
            Analisis Kandungan Humus
          </h3>
          <p 
            class="text-lg leading-relaxed"
            :class="isDark ? 'text-gray-300' : 'text-gray-600'"
          >
            Evaluasi kandungan humus tanah untuk menentukan kesuburan. 
            Rekomendasi perbaikan tanah berdasarkan data yang dikumpulkan.
          </p>
        </div>

        <!-- Feature 4: Hasil Penelitian -->
        <div 
          class="group p-8 rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl"
          :class="isDark ? 'bg-gray-800 hover:bg-gray-700' : 'bg-white hover:bg-gray-50'"
        >
          <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
          </div>
          <h3 
            class="text-2xl font-bold mb-4"
            :class="isDark ? 'text-white' : 'text-gray-900'"
          >
            Manajemen Hasil Penelitian
          </h3>
          <p 
            class="text-lg leading-relaxed"
            :class="isDark ? 'text-gray-300' : 'text-gray-600'"
          >
            Kelola dan dokumentasikan hasil penelitian siswa dengan mudah. 
            Export data dalam berbagai format untuk presentasi dan laporan.
          </p>
        </div>

        <!-- Feature 5: Dashboard Analytics -->
        <div 
          class="group p-8 rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl"
          :class="isDark ? 'bg-gray-800 hover:bg-gray-700' : 'bg-white hover:bg-gray-50'"
        >
          <div class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
          </div>
          <h3 
            class="text-2xl font-bold mb-4"
            :class="isDark ? 'text-white' : 'text-gray-900'"
          >
            Dashboard Analytics
          </h3>
          <p 
            class="text-lg leading-relaxed"
            :class="isDark ? 'text-gray-300' : 'text-gray-600'"
          >
            Visualisasi data yang interaktif dan mudah dipahami. 
            Grafik real-time dan laporan otomatis untuk analisis mendalam.
          </p>
        </div>

        <!-- Feature 6: Notifikasi Real-time -->
        <div 
          class="group p-8 rounded-2xl transition-all duration-300 transform hover:scale-105 hover:shadow-2xl"
          :class="isDark ? 'bg-gray-800 hover:bg-gray-700' : 'bg-white hover:bg-gray-50'"
        >
          <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12 7H4.828zM4.828 17l2.586-2.586a2 2 0 012.828 0L12 17H4.828z"></path>
            </svg>
          </div>
          <h3 
            class="text-2xl font-bold mb-4"
            :class="isDark ? 'text-white' : 'text-gray-900'"
          >
            Notifikasi Real-time
          </h3>
          <p 
            class="text-lg leading-relaxed"
            :class="isDark ? 'text-gray-300' : 'text-gray-600'"
          >
            Dapatkan notifikasi instan ketika ada perubahan kondisi lingkungan. 
            Alert otomatis untuk parameter yang melebihi batas normal.
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { ref, onMounted } from 'vue'

export default {
  name: 'AboutSection',
  setup() {
    const isDark = ref(false)

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
      isDark
    }
  }
}
</script>
