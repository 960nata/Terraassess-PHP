<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Terra Assessment - Platform Monitoring IoT</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Orbitron:wght@400;500;600;700;800;900&family=Exo+2:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#8b5cf6',
                        'secondary': '#3b82f6',
                        'accent': '#ec4899',
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                        'orbitron': ['Orbitron', 'monospace'],
                        'exo': ['Exo 2', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles untuk efek dramatis -->
    <style>
        /* Background Galaxy dengan efek dramatis */
        body {
            background: 
                radial-gradient(ellipse at 20% 30%, rgba(139, 92, 246, 0.4) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 70%, rgba(59, 130, 246, 0.3) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 50%, rgba(236, 72, 153, 0.2) 0%, transparent 50%),
                linear-gradient(135deg, #0c0c2e 0%, #1a1a3e 25%, #2d1b69 50%, #0f0f23 75%, #000000 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Animated stars background */
        .stars-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }
        
        .star {
            position: absolute;
            background: white;
            border-radius: 50%;
        }
        
        .star:nth-child(1) { top: 10%; left: 20%; width: 2px; height: 2px; animation-delay: 0s; }
        .star:nth-child(2) { top: 20%; left: 80%; width: 1px; height: 1px; animation-delay: 0.5s; }
        .star:nth-child(3) { top: 30%; left: 40%; width: 3px; height: 3px; animation-delay: 1s; }
        .star:nth-child(4) { top: 40%; left: 60%; width: 1px; height: 1px; animation-delay: 1.5s; }
        .star:nth-child(5) { top: 50%; left: 10%; width: 2px; height: 2px; animation-delay: 2s; }
        .star:nth-child(6) { top: 60%; left: 90%; width: 1px; height: 1px; animation-delay: 0.3s; }
        .star:nth-child(7) { top: 70%; left: 30%; width: 2px; height: 2px; animation-delay: 0.8s; }
        .star:nth-child(8) { top: 80%; left: 70%; width: 1px; height: 1px; animation-delay: 1.3s; }
        .star:nth-child(9) { top: 90%; left: 50%; width: 3px; height: 3px; animation-delay: 1.8s; }
        .star:nth-child(10) { top: 15%; left: 50%; width: 1px; height: 1px; animation-delay: 0.2s; }
        .star:nth-child(11) { top: 25%; left: 15%; width: 2px; height: 2px; animation-delay: 0.7s; }
        .star:nth-child(12) { top: 35%; left: 85%; width: 1px; height: 1px; animation-delay: 1.2s; }
        .star:nth-child(13) { top: 45%; left: 25%; width: 2px; height: 2px; animation-delay: 1.7s; }
        .star:nth-child(14) { top: 55%; left: 75%; width: 1px; height: 1px; animation-delay: 0.4s; }
        .star:nth-child(15) { top: 65%; left: 5%; width: 3px; height: 3px; animation-delay: 0.9s; }
        .star:nth-child(16) { top: 75%; left: 95%; width: 1px; height: 1px; animation-delay: 1.4s; }
        .star:nth-child(17) { top: 85%; left: 35%; width: 2px; height: 2px; animation-delay: 1.9s; }
        .star:nth-child(18) { top: 95%; left: 65%; width: 1px; height: 1px; animation-delay: 0.6s; }
        .star:nth-child(19) { top: 5%; left: 70%; width: 2px; height: 2px; animation-delay: 1.1s; }
        .star:nth-child(20) { top: 12%; left: 45%; width: 1px; height: 1px; animation-delay: 1.6s; }
        
        
        /* Floating nebula effects */
        .nebula-1 {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(ellipse at 20% 30%, rgba(139, 92, 246, 0.15) 0%, transparent 60%);
            z-index: -1;
        }
        
        .nebula-2 {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(ellipse at 80% 70%, rgba(59, 130, 246, 0.12) 0%, transparent 60%);
            z-index: -1;
        }
        
        .nebula-3 {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(ellipse at 50% 20%, rgba(236, 72, 153, 0.08) 0%, transparent 70%);
            z-index: -1;
        }
        
        /* Hero title dengan efek gradient dramatis */
        .hero-title {
            background: linear-gradient(135deg, #8b5cf6 0%, #3b82f6 50%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            color: #ffffff;
            text-shadow: 0 0 30px rgba(139, 92, 246, 0.5);
            font-family: 'Orbitron', monospace;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        
        
        /* Button dengan efek hover yang dramatis */
        .action-button {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #8b5cf6 0%, #3b82f6 100%);
            box-shadow: 0 10px 30px rgba(139, 92, 246, 0.4);
            transition: all 0.3s ease;
        }
        
        .action-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .action-button:hover::before {
            left: 100%;
        }
        
        .action-button:hover {
            transform: scale(1.05) translateY(-2px);
            box-shadow: 0 15px 40px rgba(139, 92, 246, 0.6);
        }
        
        /* Header dengan efek glassmorphism yang lebih dramatis */
        .header-glass {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        
        /* Modal dengan efek yang lebih dramatis */
        .modal-glass {
            background: rgba(17, 24, 39, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        }
        
        /* Input dengan efek focus yang dramatis */
        .input-focus:focus {
            border-color: #a78bfa;
            background-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 20px rgba(167, 139, 250, 0.3);
            transform: scale(1.02);
        }
        
        /* Floating animation untuk elemen */
        
        @supports not (-webkit-background-clip: text) {
            .hero-title {
                color: #8b5cf6 !important;
                background: none !important;
            }
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        
        
        /* New Animations */
        
        /* Particle Animation */
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
        }
        
        
        /* Enhanced Glass Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }
        
        /* Counter Animation */
        .counter {
            background: linear-gradient(135deg, #8b5cf6, #3b82f6, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Enhanced Button Hover Effects */
        .action-button {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #8b5cf6 0%, #3b82f6 100%);
            box-shadow: 
                0 10px 30px rgba(139, 92, 246, 0.4),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .action-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s ease;
        }
        
        .action-button:hover::before {
            left: 100%;
        }
        
        .action-button:hover {
            transform: scale(1.05) translateY(-3px);
            box-shadow: 
                0 20px 40px rgba(139, 92, 246, 0.6),
                0 0 0 1px rgba(255, 255, 255, 0.2);
        }
        
        /* Scroll indicator */
        .scroll-indicator {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
        }
        
    </style>
</head>
<body class="font-inter antialiased min-h-screen">
    <!-- Animated Background -->
    <div class="stars-container">
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
    </div>
    
    <!-- Floating Nebula Effects -->
    <div class="nebula-1"></div>
    <div class="nebula-2"></div>
    <div class="nebula-3"></div>
    
    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50 px-4 py-3 sm:py-4 header-glass border-b border-white/10">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="/" class="flex items-center space-x-2 sm:space-x-3 group">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center bg-gradient-to-r from-purple-500 to-blue-500 shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-satellite-dish text-white text-lg sm:text-xl"></i>
                </div>
                <span class="text-white font-bold text-lg sm:text-2xl font-orbitron hidden sm:block">Terra Assessment</span>
            </a>
            <button onclick="openLoginPopup()" class="bg-white/15 backdrop-blur-md border border-white/30 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-full font-semibold hover:bg-white/25 hover:scale-105 transition-all duration-300 flex items-center space-x-2 sm:space-x-3">
                <i class="fas fa-sign-in-alt text-sm sm:text-base"></i>
                <span class="hidden sm:inline">Login</span>
            </button>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 relative pt-16 sm:pt-20 overflow-hidden" style="padding-top: 80px; padding-bottom: 60px;">
        <div class="max-w-7xl mx-auto text-center relative z-10">
            
            
            <!-- Main Title dengan efek yang lebih dramatis -->
            <div class="relative mb-6 sm:mb-8">
                <h1 class="font-black hero-title leading-tight relative font-orbitron text-4xl sm:text-6xl md:text-8xl lg:text-9xl xl:text-[130px]">
                    <span class="block">Terra</span>
                    <span class="block bg-gradient-to-r from-purple-400 via-blue-400 to-pink-400 bg-clip-text text-transparent">
                        Assessment
                    </span>
                </h1>
                <!-- Glow effect behind title -->
                <div class="absolute inset-0 font-black text-purple-500/20 blur-3xl -z-10 font-orbitron text-4xl sm:text-6xl md:text-8xl lg:text-9xl xl:text-[130px]">
                    <span class="block">Terra</span>
                    <span class="block">Assessment</span>
                </div>
            </div>
            
            <!-- Enhanced Subtitle -->
            <div class="relative mb-8 sm:mb-12">
                <p class="text-base sm:text-lg md:text-xl text-white/70 max-w-4xl mx-auto mt-4 px-4" style="animation-delay: 0.6s;">
                    Kelola data sensor, analisis real-time, dan eksplorasi teknologi IoT dengan antarmuka yang intuitif dan powerful.
                </p>
            </div>
            
            <!-- Enhanced Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 justify-center items-center mb-12 sm:mb-16 px-4" style="animation-delay: 0.8s;">
                <button onclick="openLoginPopup()" class="group relative w-full sm:w-auto action-button text-white px-6 sm:px-10 py-4 sm:py-5 rounded-2xl font-bold text-lg sm:text-xl hover:scale-110 transition-all duration-500 flex items-center justify-center space-x-3 sm:space-x-4 shadow-2xl overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-600 via-blue-600 to-pink-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <i class="fas fa-rocket text-xl sm:text-2xl relative z-10"></i>
                    <span class="relative z-10">Mulai Sekarang</span>
                    <div class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                </button>
                
                <button onclick="scrollToFeatures()" class="group w-full sm:w-auto bg-white/10 backdrop-blur-md border border-white/30 text-white px-6 sm:px-10 py-4 sm:py-5 rounded-2xl font-semibold text-lg sm:text-xl hover:bg-white/20 hover:scale-105 transition-all duration-300 flex items-center justify-center space-x-3 sm:space-x-4">
                    <i class="fas fa-play text-lg sm:text-xl group-hover:animate-pulse"></i>
                    <span>Lihat Demo</span>
                </button>
            </div>
            
            
        </div>
    </section>


    <!-- CTA Section -->
    <section class="py-12 sm:py-20 px-4 relative">
        <div class="max-w-4xl mx-auto text-center">
            <div class="glass-effect rounded-3xl p-6 sm:p-12 relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-purple-500/20 via-transparent to-blue-500/20"></div>
                </div>
                
                <h2 class="text-2xl sm:text-4xl md:text-5xl font-bold text-white mb-4 sm:mb-6 relative z-10 px-4">
                    Siap Memulai <span class="bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">Perjalanan IoT</span> Anda?
                </h2>
                <p class="text-base sm:text-xl text-white/80 mb-6 sm:mb-8 max-w-2xl mx-auto relative z-10 px-4" style="animation-delay: 0.2s;">
                    Bergabunglah dengan ribuan peneliti dan developer yang sudah menggunakan Terra Assessment untuk proyek IoT mereka
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center relative z-10 px-4" style="animation-delay: 0.4s;">
                    <button onclick="openLoginPopup()" class="group relative w-full sm:w-auto action-button text-white px-6 sm:px-10 py-4 sm:py-5 rounded-2xl font-bold text-lg sm:text-xl hover:scale-110 transition-all duration-500 flex items-center justify-center space-x-3 sm:space-x-4 shadow-2xl overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-purple-600 via-blue-600 to-pink-600 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <i class="fas fa-rocket text-xl sm:text-2xl relative z-10"></i>
                        <span class="relative z-10">Mulai Sekarang</span>
                        <div class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                    </button>
                    
                    <button onclick="scrollToFeatures()" class="group w-full sm:w-auto bg-white/10 backdrop-blur-md border border-white/30 text-white px-6 sm:px-10 py-4 sm:py-5 rounded-2xl font-semibold text-lg sm:text-xl hover:bg-white/20 hover:scale-105 transition-all duration-300 flex items-center justify-center space-x-3 sm:space-x-4">
                        <i class="fas fa-info-circle text-lg sm:text-xl group-hover:animate-pulse"></i>
                        <span>Pelajari Lebih Lanjut</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Login Modal -->
    <div id="loginPopup" class="fixed inset-0 z-50 flex items-center justify-center p-4 opacity-0 invisible transition-all duration-300">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/70 backdrop-blur-md" onclick="closeLoginPopup()"></div>
        
        <!-- Modal -->
        <div class="relative w-full max-w-md modal-glass rounded-2xl shadow-2xl transform scale-95 transition-all duration-300 max-h-[90vh] overflow-y-auto">
            <!-- Close Button -->
            <button onclick="closeLoginPopup()" class="absolute top-3 right-3 sm:top-4 sm:right-4 w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center rounded-full bg-white/15 hover:bg-white/25 transition-all duration-200">
                <i class="fas fa-times text-white/80 text-sm sm:text-lg"></i>
            </button>
            
            <!-- Header -->
            <div class="px-4 sm:px-8 pt-6 sm:pt-8 pb-4 sm:pb-6 text-center">
                <h2 class="text-2xl sm:text-3xl font-bold text-white mb-2 sm:mb-3">Selamat Datang Kembali</h2>
                <p class="text-sm sm:text-base text-white/70">Masuk ke akun Terra Assessment Anda</p>
            </div>
            
            <!-- Form -->
            <form class="px-4 sm:px-8 pb-6 sm:pb-8" method="POST" action="{{ route('authenticate') }}" id="loginForm">
                @csrf
                
                <!-- Email -->
                <div class="mb-4 sm:mb-6">
                    <label class="block text-white/95 font-semibold mb-2 sm:mb-3 text-sm sm:text-base">Email Address</label>
                    <input type="email" name="email" required placeholder="contoh@email.com" value="{{ old('email') }}"
                           class="w-full px-4 sm:px-5 py-3 sm:py-4 bg-white/15 border border-white/30 rounded-xl text-white placeholder-white/50 focus:border-purple-400 focus:bg-white/20 focus:outline-none text-base sm:text-lg transition-all duration-300 input-focus">
                </div>
                
                <!-- Password -->
                <div class="mb-6 sm:mb-8">
                    <label class="block text-white/95 font-semibold mb-2 sm:mb-3 text-sm sm:text-base">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required placeholder="Masukkan password Anda"
                               class="w-full px-4 sm:px-5 py-3 sm:py-4 bg-white/15 border border-white/30 rounded-xl text-white placeholder-white/50 focus:border-purple-400 focus:bg-white/20 focus:outline-none pr-10 sm:pr-12 text-base sm:text-lg transition-all duration-300 input-focus">
                        <button type="button" onclick="togglePassword()" class="absolute right-3 sm:right-4 top-1/2 transform -translate-y-1/2 text-white/50 hover:text-white/80 transition-colors">
                            <i class="fas fa-eye text-base sm:text-lg" id="passwordToggleIcon"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Login Button -->
                <button type="submit" class="w-full action-button text-white py-3 sm:py-4 px-4 sm:px-6 rounded-xl font-bold text-base sm:text-lg hover:shadow-2xl transition-all duration-300 flex items-center justify-center space-x-2 sm:space-x-3">
                    <i class="fas fa-rocket text-sm sm:text-base"></i>
                    <span>Masuk ke Akun</span>
                </button>
                
                <!-- Demo Accounts Info -->
                <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-white/5 border border-white/10 rounded-xl">
                    <h4 class="text-white font-semibold mb-2 sm:mb-3 text-center text-xs sm:text-sm">🔐 AKUN SISTEM TERRA ASSESSMENT</h4>
                    <div class="space-y-1 sm:space-y-2 text-xs sm:text-sm text-white/70">
                        <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0">
                            <span>Super Admin:</span>
                            <span class="text-purple-300 text-xs sm:text-sm break-all">superadmin@terraassessment.com</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0">
                            <span>Admin:</span>
                            <span class="text-blue-300 text-xs sm:text-sm break-all">admin@terraassessment.com</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0">
                            <span>Guru:</span>
                            <span class="text-green-300 text-xs sm:text-sm break-all">guru@terraassessment.com</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0">
                            <span>Siswa:</span>
                            <span class="text-yellow-300 text-xs sm:text-sm break-all">siswa@terraassessment.com</span>
                        </div>
                        <div class="text-center mt-2 text-white/50 text-xs sm:text-sm">
                            <span>Password: <strong>superadmin123</strong> | <strong>admin123</strong> | <strong>guru123</strong> | <strong>siswa123</strong></span>
                        </div>
                    </div>
                </div>
                
                <!-- Error Messages -->
                @if(isset($hasAdmin) && $hasAdmin == 0)
                    <div class="mt-6 p-4 bg-yellow-500/15 border border-yellow-500/30 rounded-xl flex items-center gap-3 text-yellow-300 text-sm">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        <div>
                            Akun <strong>Admin</strong> belum dibuat, 
                            <a href="{{ route('adminRegister') }}" class="underline hover:no-underline">Buat sekarang</a>
                        </div>
                    </div>
                @endif
                
                @if(session('login-error'))
                    <div class="mt-6 p-4 bg-red-500/15 border border-red-500/30 rounded-xl flex items-center gap-3 text-red-300 text-sm">
                        <i class="fas fa-times-circle text-red-400"></i>
                        {{ session('login-error') }}
                    </div>
                @endif
                
                @if(session('register-success'))
                    <div class="mt-6 p-4 bg-green-500/15 border border-green-500/30 rounded-xl flex items-center gap-3 text-green-300 text-sm">
                        <i class="fas fa-check-circle text-green-400"></i>
                        {{ session('register-success') }}
                    </div>
                @endif
                
                @if(session('logout-success'))
                    <div class="mt-6 p-4 bg-blue-500/15 border border-blue-500/30 rounded-xl flex items-center gap-3 text-blue-300 text-sm">
                        <i class="fas fa-sign-out-alt text-blue-400"></i>
                        {{ session('logout-success') }}
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="relative py-16 px-4 border-t border-white/10">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <!-- Brand -->
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-gradient-to-r from-purple-500 to-blue-500 shadow-lg">
                            <i class="fas fa-satellite-dish text-white text-xl"></i>
                        </div>
                        <span class="text-white font-bold text-2xl font-orbitron">Terra Assessment</span>
                    </div>
                    <p class="text-white/70 text-lg leading-relaxed mb-6 max-w-md">
                        Platform monitoring IoT terdepan untuk penelitian dan pembelajaran. 
                        Kelola data sensor dengan mudah dan efisien.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-white/20 transition-colors">
                            <i class="fab fa-twitter text-white"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-white/20 transition-colors">
                            <i class="fab fa-github text-white"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-white/20 transition-colors">
                            <i class="fab fa-linkedin text-white"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-white/20 transition-colors">
                            <i class="fab fa-youtube text-white"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-white font-bold text-xl mb-6">Quick Links</h3>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-white/70 hover:text-white transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="text-white/70 hover:text-white transition-colors">Fitur</a></li>
                        <li><a href="#" class="text-white/70 hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="#" class="text-white/70 hover:text-white transition-colors">Dokumentasi</a></li>
                        <li><a href="#" class="text-white/70 hover:text-white transition-colors">API</a></li>
                    </ul>
                </div>
                
                <!-- Support -->
                <div>
                    <h3 class="text-white font-bold text-xl mb-6">Support</h3>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-white/70 hover:text-white transition-colors">Help Center</a></li>
                        <li><a href="#" class="text-white/70 hover:text-white transition-colors">Community</a></li>
                        <li><a href="#" class="text-white/70 hover:text-white transition-colors">Contact Us</a></li>
                        <li><a href="#" class="text-white/70 hover:text-white transition-colors">Status</a></li>
                        <li><a href="#" class="text-white/70 hover:text-white transition-colors">Changelog</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Bottom Bar -->
            <div class="pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center">
                <p class="text-white/50 text-sm mb-4 md:mb-0">
                    © 2024 Terra Assessment. All rights reserved.
                </p>
                <div class="flex space-x-6 text-sm">
                    <a href="#" class="text-white/50 hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="text-white/50 hover:text-white transition-colors">Terms of Service</a>
                    <a href="#" class="text-white/50 hover:text-white transition-colors">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>
    
    <script>
        // Login Popup Functions
        function openLoginPopup() {
            const modal = document.getElementById('loginPopup');
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
        
        function closeLoginPopup() {
            const modal = document.getElementById('loginPopup');
            modal.querySelector('.relative').style.transform = 'scale(0.95)';
            setTimeout(() => {
                modal.classList.add('opacity-0', 'invisible');
                modal.classList.remove('opacity-100', 'visible');
                document.body.style.overflow = 'auto';
            }, 150);
        }
        
        // Password Toggle
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
        
        // Form Submission
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
                closeLoginPopup();
            }
        });
        
        // Counter Animation
        function animateCounters() {
            const counters = document.querySelectorAll('.counter');
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-target'));
                const duration = 2000; // 2 seconds
                const increment = target / (duration / 16); // 60fps
                let current = 0;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    counter.textContent = Math.floor(current);
                }, 16);
            });
        }
        
        // Scroll to Features
        function scrollToFeatures() {
            const featuresElement = document.getElementById('features');
            if (featuresElement) {
                featuresElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            } else {
                // Fallback: scroll to first section with content
                const firstSection = document.querySelector('section:not(:first-child)');
                if (firstSection) {
                    firstSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        }
        
        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('');
                    
                    // Trigger counter animation when stats section is visible
                    if (entry.target.classList.contains('counter')) {
                        animateCounters();
                    }
                }
            });
        }, observerOptions);
        
        // Observe elements for animation
        document.addEventListener('DOMContentLoaded', function() {
            // Observe animated elements
            const animatedElements = document.querySelectorAll('.animate-on-scroll, .fade-in, .slide-up');
            animatedElements.forEach(el => observer.observe(el));
            
            // Add scroll indicator
            const scrollIndicator = document.createElement('div');
            scrollIndicator.className = 'scroll-indicator';
            scrollIndicator.innerHTML = '<i class="fas fa-chevron-down text-white/60 text-2xl"></i>';
            document.querySelector('section').appendChild(scrollIndicator);
            
            // Add click handler for scroll indicator
            scrollIndicator.addEventListener('click', scrollToFeatures);
        });
        
        
        // Add hover effects to feature cards
        document.addEventListener('DOMContentLoaded', function() {
            const featureCards = document.querySelectorAll('.glass-effect');
            featureCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.05) translateY(-10px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1) translateY(0)';
                });
            });
        });
    </script>
</body>
</html>