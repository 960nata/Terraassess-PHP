<template>
  <div 
    v-if="showWelcome"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90"
    @click="closeWelcome"
  >
    <div class="text-center">
      <h1 
        class="text-6xl md:text-8xl font-bold text-white animate-bounce-in"
        :class="{ 'animate-fade-out': isClosing }"
      >
        Welcome
      </h1>
      <p 
        class="text-xl md:text-2xl text-gray-300 mt-4 animate-slide-up"
        :class="{ 'animate-fade-out': isClosing }"
      >
        Selamat datang di Elass
      </p>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'

export default {
  name: 'WelcomeAnimation',
  setup() {
    const showWelcome = ref(true)
    const isClosing = ref(false)

    const closeWelcome = () => {
      isClosing.value = true
      setTimeout(() => {
        showWelcome.value = false
      }, 500)
    }

    onMounted(() => {
      // Auto close after 3 seconds
      setTimeout(() => {
        closeWelcome()
      }, 3000)
    })

    return {
      showWelcome,
      isClosing,
      closeWelcome
    }
  }
}
</script>
