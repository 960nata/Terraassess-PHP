<template>
  <div class="min-h-screen">
    <!-- Welcome Animation -->
    <WelcomeAnimation />
    
    <!-- Header -->
    <Header @open-login-modal="openLoginModal" />
    
    <!-- Hero Section -->
    <HeroSection @open-login-modal="openLoginModal" />
    
    <!-- About Section -->
    <AboutSection />
    
    <!-- Footer -->
    <Footer />
    
    <!-- Login Modal -->
    <LoginModal 
      :is-open="isLoginModalOpen" 
      @close-modal="closeLoginModal"
      @login-success="handleLoginSuccess"
    />
  </div>
</template>

<script>
import { ref } from 'vue'
import WelcomeAnimation from './WelcomeAnimation.vue'
import Header from './Header.vue'
import HeroSection from './HeroSection.vue'
import AboutSection from './AboutSection.vue'
import Footer from './Footer.vue'
import LoginModal from './LoginModal.vue'

export default {
  name: 'HomePage',
  components: {
    WelcomeAnimation,
    Header,
    HeroSection,
    AboutSection,
    Footer,
    LoginModal
  },
  setup() {
    const isLoginModalOpen = ref(false)

    const openLoginModal = () => {
      isLoginModalOpen.value = true
    }

    const closeLoginModal = () => {
      isLoginModalOpen.value = false
    }

    const handleLoginSuccess = (formData) => {
      console.log('Login successful:', formData)
      // Here you would typically redirect to dashboard or handle login
      // For now, we'll just show a success message
      alert('Login berhasil! Selamat datang di Elass.')
    }

    return {
      isLoginModalOpen,
      openLoginModal,
      closeLoginModal,
      handleLoginSuccess
    }
  }
}
</script>
