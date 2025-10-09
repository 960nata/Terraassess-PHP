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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'galaxy-primary': '#8b5cf6',
                        'galaxy-secondary': '#3b82f6',
                        'galaxy-accent': '#ec4899',
                    }
                }
            }
        }
    </script>
    
    <!-- Custom Galaxy Stars CSS -->
    <style>
        .stars-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(ellipse at 20% 30%, rgba(139, 92, 246, 0.4) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 70%, rgba(59, 130, 246, 0.3) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 50%, rgba(236, 72, 153, 0.2) 0%, transparent 50%),
                linear-gradient(135deg, #0c0c2e 0%, #1a1a3e 25%, #2d1b69 50%, #0f0f23 75%, #000000 100%);
            z-index: -1;
            overflow: hidden;
        }
        
        .nebula-1 {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(ellipse at 20% 30%, rgba(139, 92, 246, 0.15) 0%, transparent 60%);
            animation: nebulaFloat 30s ease-in-out infinite;
        }
        
        .nebula-2 {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(ellipse at 80% 70%, rgba(59, 130, 246, 0.12) 0%, transparent 60%);
            animation: nebulaFloat 35s ease-in-out infinite reverse;
        }
        
        .nebula-3 {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(ellipse at 50% 20%, rgba(236, 72, 153, 0.08) 0%, transparent 70%);
            animation: nebulaFloat 40s ease-in-out infinite;
        }
        
        .star {
            position: absolute;
            background: white;
            border-radius: 50%;
            animation: twinkle 2s ease-in-out infinite alternate;
        }
        
        .star:nth-child(4) { top: 10%; left: 20%; width: 2px; height: 2px; animation-delay: 0s; }
        .star:nth-child(5) { top: 20%; left: 80%; width: 1px; height: 1px; animation-delay: 1s; }
        .star:nth-child(6) { top: 30%; left: 40%; width: 3px; height: 3px; animation-delay: 2s; }
        .star:nth-child(7) { top: 40%; left: 60%; width: 1px; height: 1px; animation-delay: 0.5s; }
        .star:nth-child(8) { top: 50%; left: 10%; width: 2px; height: 2px; animation-delay: 1.5s; }
        .star:nth-child(9) { top: 60%; left: 90%; width: 1px; height: 1px; animation-delay: 2.5s; }
        .star:nth-child(10) { top: 70%; left: 30%; width: 2px; height: 2px; animation-delay: 0.8s; }
        .star:nth-child(11) { top: 80%; left: 70%; width: 1px; height: 1px; animation-delay: 1.8s; }
        .star:nth-child(12) { top: 90%; left: 50%; width: 3px; height: 3px; animation-delay: 2.2s; }
        .star:nth-child(13) { top: 15%; left: 50%; width: 1px; height: 1px; animation-delay: 0.3s; }
        .star:nth-child(14) { top: 25%; left: 15%; width: 2px; height: 2px; animation-delay: 1.2s; }
        .star:nth-child(15) { top: 35%; left: 85%; width: 1px; height: 1px; animation-delay: 2.8s; }
        .star:nth-child(16) { top: 45%; left: 25%; width: 2px; height: 2px; animation-delay: 0.7s; }
        .star:nth-child(17) { top: 55%; left: 75%; width: 1px; height: 1px; animation-delay: 1.7s; }
        .star:nth-child(18) { top: 65%; left: 5%; width: 3px; height: 3px; animation-delay: 2.3s; }
        .star:nth-child(19) { top: 75%; left: 95%; width: 1px; height: 1px; animation-delay: 0.9s; }
        .star:nth-child(20) { top: 85%; left: 35%; width: 2px; height: 2px; animation-delay: 1.9s; }
        .star:nth-child(21) { top: 95%; left: 65%; width: 1px; height: 1px; animation-delay: 2.7s; }
        .star:nth-child(22) { top: 5%; left: 70%; width: 2px; height: 2px; animation-delay: 1.1s; }
        .star:nth-child(23) { top: 12%; left: 35%; width: 1px; height: 1px; animation-delay: 2.1s; }
        .star:nth-child(24) { top: 18%; left: 90%; width: 2px; height: 2px; animation-delay: 0.4s; }
        .star:nth-child(25) { top: 22%; left: 5%; width: 1px; height: 1px; animation-delay: 1.6s; }
        
        @keyframes nebulaFloat {
            0%, 100% { 
                transform: translateY(0px) rotate(0deg) scale(1); 
                opacity: 0.8;
            }
            50% { 
                transform: translateY(-30px) rotate(180deg) scale(1.1); 
                opacity: 1;
            }
        }
        
        @keyframes twinkle {
            0% { 
                opacity: 0.3; 
                transform: scale(1); 
            }
            100% { 
                opacity: 1; 
                transform: scale(1.3); 
            }
        }
        
        /* Fallback CSS untuk memastikan styling bekerja */
        .text-white {
            color: #ffffff !important;
        }
        
        .text-white\/90 {
            color: rgba(255, 255, 255, 0.9) !important;
        }
        
        .text-white\/60 {
            color: rgba(255, 255, 255, 0.6) !important;
        }
        
        .text-white\/70 {
            color: rgba(255, 255, 255, 0.7) !important;
        }
        
        .bg-white\/10 {
            background-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        .bg-white\/15 {
            background-color: rgba(255, 255, 255, 0.15) !important;
        }
        
        .bg-white\/20 {
            background-color: rgba(255, 255, 255, 0.2) !important;
        }
        
        .border-white\/20 {
            border-color: rgba(255, 255, 255, 0.2) !important;
        }
        
        .backdrop-blur-md {
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
        }
        
        .backdrop-blur-xl {
            backdrop-filter: blur(24px) !important;
            -webkit-backdrop-filter: blur(24px) !important;
        }
        
        /* Fallback untuk browser yang tidak mendukung backdrop-filter */
        @supports not (backdrop-filter: blur(12px)) {
            .backdrop-blur-md {
                background-color: rgba(255, 255, 255, 0.2) !important;
            }
            .backdrop-blur-xl {
                background-color: rgba(255, 255, 255, 0.3) !important;
            }
        }
    </style>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="font-sans antialiased">
    <!-- Galaxy Stars Background -->
    <div class="stars-background">
        <div class="nebula-1"></div>
        <div class="nebula-2"></div>
        <div class="nebula-3"></div>
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
        <div class="star"></div>
        <div class="star"></div>
    </div>

    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50 px-6 py-4" style="background: rgba(0, 0, 0, 0.2); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="/" class="flex items-center space-x-3 group">
                <div class="w-12 h-12 bg-gradient-to-br from-galaxy-primary to-galaxy-secondary rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-lg" style="box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);">
                    <i class="fas fa-satellite-dish text-white text-xl"></i>
                </div>
                <span class="text-white font-bold text-2xl font-sans" style="color: #ffffff !important; text-shadow: 0 2px 8px rgba(0, 0, 0, 0.7); font-weight: 700 !important;">Terra Assessment</span>
            </a>
            <button onclick="openLoginPopup()" class="bg-white/15 backdrop-blur-md border border-white/30 text-white px-8 py-3 rounded-full font-semibold hover:bg-white/25 hover:scale-105 transition-all duration-300 flex items-center space-x-3 shadow-lg" style="box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);">
                <i class="fas fa-sign-in-alt text-lg"></i>
                <span class="text-lg">Login</span>
            </button>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="min-h-screen flex items-center justify-center px-6 relative">
        <div class="max-w-6xl mx-auto text-center">
            <h1 class="text-7xl md:text-8xl lg:text-9xl font-bold text-white mb-8 leading-tight" style="color: #ffffff !important; text-shadow: 0 6px 20px rgba(139, 92, 246, 0.5), 0 0 40px rgba(59, 130, 246, 0.3); font-weight: 900 !important; letter-spacing: -0.02em;">
                Terra Assessment
            </h1>
            <p class="text-2xl md:text-3xl lg:text-4xl text-white/95 mb-16 max-w-5xl mx-auto leading-relaxed font-medium" style="text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5); line-height: 1.4;">
                Platform monitoring IoT terdepan untuk penelitian dan pembelajaran. Kelola data sensor, analisis real-time, dan eksplorasi teknologi IoT dengan antarmuka yang intuitif dan powerful.
            </p>
            <button onclick="openLoginPopup()" class="bg-gradient-to-r from-galaxy-primary to-galaxy-secondary text-white px-12 py-5 rounded-full font-bold text-xl hover:scale-110 hover:shadow-2xl hover:shadow-purple-500/30 transition-all duration-300 flex items-center space-x-4 mx-auto group" style="box-shadow: 0 10px 30px rgba(139, 92, 246, 0.4);">
                <i class="fas fa-rocket text-2xl group-hover:rotate-12 transition-transform duration-300"></i>
                <span class="text-xl">Mulai Sekarang</span>
            </button>
        </div>
    </section>

    <!-- Login Modal -->
    <div id="loginPopup" class="fixed inset-0 z-50 flex items-center justify-center p-4 opacity-0 invisible transition-all duration-300">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/70 backdrop-blur-md"></div>
        
        <!-- Modal -->
        <div class="relative w-full max-w-md bg-gray-900/90 backdrop-blur-xl border border-white/30 rounded-2xl shadow-2xl transform scale-95 transition-transform duration-300" style="box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);">
            <!-- Close Button -->
            <button onclick="closeLoginPopup()" class="absolute top-4 right-4 w-10 h-10 flex items-center justify-center rounded-full bg-white/15 hover:bg-white/25 transition-colors">
                <i class="fas fa-times text-white/80 text-lg"></i>
            </button>
            
            <!-- Header -->
            <div class="px-8 pt-8 pb-6 text-center">
                <h2 class="text-3xl font-bold text-white mb-3" style="text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);">Selamat Datang Kembali</h2>
                <p class="text-white/70 text-base">Masuk ke akun Terra Assessment Anda</p>
            </div>
            
            <!-- Form -->
            <form class="px-8 pb-8" method="POST" action="{{ route('authenticate') }}" id="loginForm">
                @csrf
                
                <!-- Email -->
                <div class="mb-6">
                    <label class="block text-white/95 font-semibold mb-3 text-base">Email Address</label>
                    <input type="email" name="email" required placeholder="contoh@email.com" value="{{ old('email') }}"
                           class="w-full px-5 py-4 bg-white/15 border border-white/30 rounded-xl text-white placeholder-white/50 focus:border-galaxy-primary focus:bg-white/20 focus:outline-none text-lg transition-all duration-300" style="box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                </div>
                
                <!-- Password -->
                <div class="mb-8">
                    <label class="block text-white/95 font-semibold mb-3 text-base">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required placeholder="Masukkan password Anda"
                               class="w-full px-5 py-4 bg-white/15 border border-white/30 rounded-xl text-white placeholder-white/50 focus:border-galaxy-primary focus:bg-white/20 focus:outline-none pr-12 text-lg transition-all duration-300" style="box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                        <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white/50 hover:text-white/80 transition-colors">
                            <i class="fas fa-eye text-lg" id="passwordToggleIcon"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Login Button -->
                <button type="submit" class="w-full bg-gradient-to-r from-galaxy-primary to-galaxy-secondary text-white py-4 px-6 rounded-xl font-bold text-lg hover:shadow-2xl hover:shadow-purple-500/25 transition-all duration-300 flex items-center justify-center space-x-3" style="box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);">
                    <i class="fas fa-rocket text-xl"></i>
                    <span>Masuk ke Akun</span>
                </button>
                
                <!-- Register Link -->
                <div class="mt-6 text-center">
                    <p class="text-white/70 text-base">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="text-galaxy-primary font-semibold hover:text-galaxy-secondary hover:underline transition-colors duration-300">
                            Daftar Sekarang
                        </a>
                    </p>
                </div>
                
                <!-- Error Messages -->
                @if(isset($hasAdmin) && $hasAdmin == 0)
                    <div class="mt-6 p-4 bg-yellow-500/15 border border-yellow-500/30 rounded-xl text-yellow-300 text-base" style="box-shadow: 0 4px 15px rgba(234, 179, 8, 0.1);">
                        <i class="fas fa-exclamation-triangle mr-3 text-lg"></i>
                        Akun <strong>Admin</strong> belum dibuat, 
                        <a href="{{ route('adminRegister') }}" class="underline hover:text-yellow-200 transition-colors">Buat sekarang</a>
                    </div>
                @endif
                
                @if(session('login-error'))
                    <div class="mt-6 p-4 bg-red-500/15 border border-red-500/30 rounded-xl text-red-300 text-base" style="box-shadow: 0 4px 15px rgba(239, 68, 68, 0.1);">
                        <i class="fas fa-times-circle mr-3 text-lg"></i>
                        {{ session('login-error') }}
                    </div>
                @endif
                
                @if(session('register-success'))
                    <div class="mt-6 p-4 bg-green-500/15 border border-green-500/30 rounded-xl text-green-300 text-base" style="box-shadow: 0 4px 15px rgba(34, 197, 94, 0.1);">
                        <i class="fas fa-check-circle mr-3 text-lg"></i>
                        {{ session('register-success') }}
                    </div>
                @endif
                
                @if(session('logout-success'))
                    <div class="mt-6 p-4 bg-blue-500/15 border border-blue-500/30 rounded-xl text-blue-300 text-base" style="box-shadow: 0 4px 15px rgba(59, 130, 246, 0.1);">
                        <i class="fas fa-sign-out-alt mr-3 text-lg"></i>
                        {{ session('logout-success') }}
                    </div>
                @endif
            </form>
        </div>
    </div>
    
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
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin text-xl"></i><span>Memproses...</span>';
            submitBtn.disabled = true;
            
            // Simulate loading for better UX
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        });
        
        // Close on backdrop click
        document.getElementById('loginPopup').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLoginPopup();
            }
        });
        
        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLoginPopup();
            }
        });
    </script>
</body>
</html>
