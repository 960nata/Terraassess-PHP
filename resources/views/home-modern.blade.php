<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terra Assessment - Platform Monitoring IoT</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        secondary: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Design System -->
    <link href="{{ asset('css/terra-design-system.css') }}" rel="stylesheet">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gradient-to-br from-primary-50 via-white to-secondary-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-md border-b border-secondary-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-satellite-dish text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-secondary-900">Terra Assessment</h1>
                        <p class="text-xs text-secondary-500">IoT Monitoring Platform</p>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-secondary-600 hover:text-primary-600 transition-colors">Fitur</a>
                    <a href="#about" class="text-secondary-600 hover:text-primary-600 transition-colors">Tentang</a>
                    <a href="#contact" class="text-secondary-600 hover:text-primary-600 transition-colors">Kontak</a>
                </nav>
                
                <!-- Login Button -->
                <button onclick="openLoginModal()" class="terra-btn terra-btn-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Login</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative py-20 lg:py-32 overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(14, 165, 233, 0.3) 1px, transparent 0); background-size: 20px 20px;"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center">
                <!-- Main Heading -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-secondary-900 mb-6">
                    Platform Monitoring
                    <span class="text-primary-600">IoT</span>
                    Terdepan
                </h1>
                
                <!-- Subtitle -->
                <p class="text-lg sm:text-xl text-secondary-600 max-w-3xl mx-auto mb-8 leading-relaxed">
                    Kelola data sensor, analisis real-time, dan eksplorasi teknologi IoT dengan antarmuka yang intuitif dan powerful untuk penelitian dan pembelajaran.
                </p>
                
                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
                    <button onclick="openLoginModal()" class="terra-btn terra-btn-primary terra-btn-lg w-full sm:w-auto">
                        <i class="fas fa-rocket"></i>
                        <span>Mulai Sekarang</span>
                    </button>
                    <button onclick="scrollToFeatures()" class="terra-btn terra-btn-outline terra-btn-lg w-full sm:w-auto">
                        <i class="fas fa-play"></i>
                        <span>Lihat Demo</span>
                    </button>
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-4xl mx-auto">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-primary-600 mb-2">1000+</div>
                        <div class="text-sm text-secondary-600">Pengguna Aktif</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-primary-600 mb-2">50+</div>
                        <div class="text-sm text-secondary-600">Proyek IoT</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-primary-600 mb-2">99.9%</div>
                        <div class="text-sm text-secondary-600">Uptime</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-primary-600 mb-2">24/7</div>
                        <div class="text-sm text-secondary-600">Support</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-secondary-900 mb-4">
                    Fitur Unggulan
                </h2>
                <p class="text-lg text-secondary-600 max-w-2xl mx-auto">
                    Platform lengkap untuk monitoring dan analisis data IoT dengan teknologi terdepan
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="terra-card terra-animate-fade-in">
                    <div class="terra-card-body text-center">
                        <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-chart-line text-primary-600 text-2xl"></i>
                        </div>
                        <h3 class="terra-card-title">Analisis Real-time</h3>
                        <p class="terra-card-description">
                            Monitor data sensor secara real-time dengan dashboard interaktif dan visualisasi yang mudah dipahami.
                        </p>
                    </div>
                </div>
                
                <!-- Feature 2 -->
                <div class="terra-card terra-animate-fade-in">
                    <div class="terra-card-body text-center">
                        <div class="w-16 h-16 bg-success-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-database text-success-600 text-2xl"></i>
                        </div>
                        <h3 class="terra-card-title">Manajemen Data</h3>
                        <p class="terra-card-description">
                            Kelola dan simpan data sensor dengan aman, dengan backup otomatis dan recovery yang cepat.
                        </p>
                    </div>
                </div>
                
                <!-- Feature 3 -->
                <div class="terra-card terra-animate-fade-in">
                    <div class="terra-card-body text-center">
                        <div class="w-16 h-16 bg-warning-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-mobile-alt text-warning-600 text-2xl"></i>
                        </div>
                        <h3 class="terra-card-title">Responsive Design</h3>
                        <p class="terra-card-description">
                            Akses platform dari perangkat apapun dengan desain yang responsif dan user-friendly.
                        </p>
                    </div>
                </div>
                
                <!-- Feature 4 -->
                <div class="terra-card terra-animate-fade-in">
                    <div class="terra-card-body text-center">
                        <div class="w-16 h-16 bg-info-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-shield-alt text-info-600 text-2xl"></i>
                        </div>
                        <h3 class="terra-card-title">Keamanan Tinggi</h3>
                        <p class="terra-card-description">
                            Data Anda terlindungi dengan enkripsi end-to-end dan sistem keamanan berlapis.
                        </p>
                    </div>
                </div>
                
                <!-- Feature 5 -->
                <div class="terra-card terra-animate-fade-in">
                    <div class="terra-card-body text-center">
                        <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-users text-purple-600 text-2xl"></i>
                        </div>
                        <h3 class="terra-card-title">Kolaborasi Tim</h3>
                        <p class="terra-card-description">
                            Bekerja sama dengan tim dalam proyek IoT dengan fitur sharing dan permission yang fleksibel.
                        </p>
                    </div>
                </div>
                
                <!-- Feature 6 -->
                <div class="terra-card terra-animate-fade-in">
                    <div class="terra-card-body text-center">
                        <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-cog text-red-600 text-2xl"></i>
                        </div>
                        <h3 class="terra-card-title">Integrasi Mudah</h3>
                        <p class="terra-card-description">
                            Integrasikan dengan berbagai platform dan API dengan mudah menggunakan dokumentasi lengkap.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-primary-600">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">
                Siap Memulai Perjalanan IoT Anda?
            </h2>
            <p class="text-lg text-primary-100 mb-8 max-w-2xl mx-auto">
                Bergabunglah dengan ribuan peneliti dan developer yang sudah menggunakan Terra Assessment untuk proyek IoT mereka.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="openLoginModal()" class="terra-btn bg-white text-primary-600 hover:bg-primary-50 terra-btn-lg w-full sm:w-auto">
                    <i class="fas fa-rocket"></i>
                    <span>Mulai Sekarang</span>
                </button>
                <button onclick="scrollToFeatures()" class="terra-btn border-white text-white hover:bg-white hover:text-primary-600 terra-btn-lg w-full sm:w-auto">
                    <i class="fas fa-info-circle"></i>
                    <span>Pelajari Lebih Lanjut</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-secondary-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <!-- Brand -->
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-12 h-12 bg-primary-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-satellite-dish text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Terra Assessment</h3>
                            <p class="text-sm text-secondary-400">IoT Monitoring Platform</p>
                        </div>
                    </div>
                    <p class="text-secondary-300 mb-6 max-w-md">
                        Platform monitoring IoT terdepan untuk penelitian dan pembelajaran. 
                        Kelola data sensor dengan mudah dan efisien.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-secondary-800 rounded-lg flex items-center justify-center hover:bg-primary-600 transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-secondary-800 rounded-lg flex items-center justify-center hover:bg-primary-600 transition-colors">
                            <i class="fab fa-github"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-secondary-800 rounded-lg flex items-center justify-center hover:bg-primary-600 transition-colors">
                            <i class="fab fa-linkedin"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-secondary-800 rounded-lg flex items-center justify-center hover:bg-primary-600 transition-colors">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-3">
                        <li><a href="#about" class="text-secondary-300 hover:text-white transition-colors">Tentang Kami</a></li>
                        <li><a href="#features" class="text-secondary-300 hover:text-white transition-colors">Fitur</a></li>
                        <li><a href="#" class="text-secondary-300 hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="#" class="text-secondary-300 hover:text-white transition-colors">Dokumentasi</a></li>
                        <li><a href="#" class="text-secondary-300 hover:text-white transition-colors">API</a></li>
                    </ul>
                </div>
                
                <!-- Support -->
                <div>
                    <h4 class="font-semibold mb-4">Support</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-secondary-300 hover:text-white transition-colors">Help Center</a></li>
                        <li><a href="#" class="text-secondary-300 hover:text-white transition-colors">Community</a></li>
                        <li><a href="#contact" class="text-secondary-300 hover:text-white transition-colors">Contact Us</a></li>
                        <li><a href="#" class="text-secondary-300 hover:text-white transition-colors">Status</a></li>
                        <li><a href="#" class="text-secondary-300 hover:text-white transition-colors">Changelog</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Bottom Bar -->
            <div class="pt-8 border-t border-secondary-800 flex flex-col md:flex-row justify-between items-center">
                <p class="text-secondary-400 text-sm mb-4 md:mb-0">
                    © 2024 Terra Assessment. All rights reserved.
                </p>
                <div class="flex space-x-6 text-sm">
                    <a href="#" class="text-secondary-400 hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="text-secondary-400 hover:text-white transition-colors">Terms of Service</a>
                    <a href="#" class="text-secondary-400 hover:text-white transition-colors">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Login Modal -->
    <div id="loginModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 opacity-0 invisible transition-all duration-300">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeLoginModal()"></div>
        
        <!-- Modal -->
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl transform scale-95 transition-all duration-300 max-h-[90vh] overflow-y-auto">
            <!-- Close Button -->
            <button onclick="closeLoginModal()" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-secondary-100 hover:bg-secondary-200 transition-colors">
                <i class="fas fa-times text-secondary-600 text-sm"></i>
            </button>
            
            <!-- Header -->
            <div class="px-6 pt-6 pb-4 text-center">
                <h2 class="text-2xl font-bold text-secondary-900 mb-2">Selamat Datang Kembali</h2>
                <p class="text-sm text-secondary-600">Masuk ke akun Terra Assessment Anda</p>
            </div>
            
            <!-- Form -->
            <form class="px-6 pb-6" method="POST" action="{{ route('authenticate') }}" id="loginForm">
                @csrf
                
                <!-- Email -->
                <div class="terra-form-group">
                    <label class="terra-label">Email Address</label>
                    <input type="email" name="email" required placeholder="contoh@email.com" value="{{ old('email') }}"
                           class="terra-input">
                </div>
                
                <!-- Password -->
                <div class="terra-form-group">
                    <label class="terra-label">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required placeholder="Masukkan password Anda"
                               class="terra-input pr-10">
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-secondary-400 hover:text-secondary-600">
                            <i class="fas fa-eye text-sm" id="passwordToggleIcon"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Login Button -->
                <button type="submit" class="terra-btn terra-btn-primary w-full">
                    <i class="fas fa-rocket"></i>
                    <span>Masuk ke Akun</span>
                </button>
                
                <!-- Demo Accounts Info -->
                <div class="mt-6 p-4 bg-secondary-50 border border-secondary-200 rounded-lg">
                    <h4 class="text-secondary-900 font-semibold mb-3 text-center text-sm">🔐 AKUN SISTEM TERRA ASSESSMENT</h4>
                    <div class="space-y-2 text-xs text-secondary-600">
                        <div class="flex justify-between">
                            <span>Super Admin:</span>
                            <span class="text-primary-600 break-all">superadmin@terraassessment.com</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Admin:</span>
                            <span class="text-info-600 break-all">admin@terraassessment.com</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Guru:</span>
                            <span class="text-success-600 break-all">guru@terraassessment.com</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Siswa:</span>
                            <span class="text-warning-600 break-all">siswa@terraassessment.com</span>
                        </div>
                        <div class="text-center mt-2 text-secondary-500">
                            <span>Password: <strong>superadmin123</strong> | <strong>admin123</strong> | <strong>guru123</strong> | <strong>siswa123</strong></span>
                        </div>
                    </div>
                </div>
                
                <!-- Error Messages -->
                @if(isset($hasAdmin) && $hasAdmin == 0)
                    <div class="terra-alert terra-alert-warning mt-4">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Akun <strong>Admin</strong> belum dibuat, 
                        <a href="{{ route('adminRegister') }}" class="underline hover:no-underline">Buat sekarang</a>
                    </div>
                @endif
                
                @if(session('login-error'))
                    <div class="terra-alert terra-alert-error mt-4">
                        <i class="fas fa-times-circle mr-2"></i>
                        {{ session('login-error') }}
                    </div>
                @endif
                
                @if(session('register-success'))
                    <div class="terra-alert terra-alert-success mt-4">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('register-success') }}
                    </div>
                @endif
                
                @if(session('logout-success'))
                    <div class="terra-alert terra-alert-info mt-4">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        {{ session('logout-success') }}
                    </div>
                @endif
            </form>
        </div>
    </div>

    <script>
        // Modal functionality
        function openLoginModal() {
            const modal = document.getElementById('loginModal');
            modal.classList.remove('opacity-0', 'invisible');
            modal.classList.add('opacity-100', 'visible');
            modal.querySelector('.relative').style.transform = 'scale(1)';
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                const emailInput = document.querySelector('input[name="email"]');
                if (emailInput) {
                    emailInput.focus();
                }
            }, 200);
        }
        
        function closeLoginModal() {
            const modal = document.getElementById('loginModal');
            modal.querySelector('.relative').style.transform = 'scale(0.95)';
            setTimeout(() => {
                modal.classList.add('opacity-0', 'invisible');
                modal.classList.remove('opacity-100', 'visible');
                document.body.style.overflow = 'auto';
            }, 150);
        }
        
        // Password toggle
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('passwordToggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        // Form submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.querySelector('input[name="email"]').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                e.preventDefault();
                alert('Mohon lengkapi email dan password!');
                return;
            }
            
            const submitBtn = document.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Memproses...</span>';
            submitBtn.disabled = true;
        });
        
        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLoginModal();
            }
        });
        
        // Smooth scroll
        function scrollToFeatures() {
            document.getElementById('features').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
        
        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('terra-animate-fade-in');
                }
            });
        }, observerOptions);
        
        // Observe elements for animation
        document.addEventListener('DOMContentLoaded', function() {
            const animatedElements = document.querySelectorAll('.terra-card');
            animatedElements.forEach(el => observer.observe(el));
        });
    </script>
</body>
</html>

