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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="font-sans antialiased">
    <!-- Stars Background -->
    <div class="stars-background">
        <!-- Nebula Effects -->
        <div class="nebula-1"></div>
        <div class="nebula-2"></div>
        
        <!-- Stars -->
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
    <header class="fixed top-0 left-0 right-0 z-50 px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="/" class="flex items-center space-x-3 group">
                <div class="w-10 h-10 bg-gradient-to-br from-galaxy-primary to-galaxy-secondary rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-satellite-dish text-white text-lg"></i>
                </div>
                <span class="text-white font-bold text-xl font-sans">Terra Assessment</span>
            </a>
            <button onclick="openLoginPopup()" class="bg-white/10 backdrop-blur-md border border-white/20 text-white px-6 py-3 rounded-full font-medium hover:bg-white/20 hover:scale-105 transition-all duration-300 flex items-center space-x-2">
                <i class="fas fa-sign-in-alt"></i>
                <span>Login</span>
            </button>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="min-h-screen flex items-center justify-center px-6 relative">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-6xl md:text-7xl font-bold text-white mb-6 font-sans leading-tight">
                <span class="bg-gradient-to-r from-galaxy-primary via-galaxy-secondary to-galaxy-accent bg-clip-text text-transparent">
                    Terra Assessment
                </span>
            </h1>
            <p class="text-xl md:text-2xl text-white/90 mb-12 max-w-3xl mx-auto leading-relaxed font-sans">
                Platform monitoring IoT terdepan untuk penelitian dan pembelajaran. 
                Kelola data sensor, analisis real-time, dan eksplorasi teknologi IoT 
                dengan antarmuka yang intuitif dan powerful.
            </p>
            <button onclick="openLoginPopup()" class="bg-gradient-to-r from-galaxy-primary to-galaxy-secondary text-white px-8 py-4 rounded-full font-bold text-lg hover:scale-105 hover:shadow-2xl hover:shadow-galaxy-primary/25 transition-all duration-300 flex items-center space-x-3 mx-auto group">
                <i class="fas fa-rocket group-hover:rotate-12 transition-transform duration-300"></i>
                <span>Mulai Sekarang</span>
            </button>
        </div>
    </section>

    <!-- Login Popup -->
    <div id="loginPopup" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 opacity-0 invisible transition-all duration-300 p-5">
        <div class="bg-gray-800 rounded-2xl w-full max-w-md max-h-[90vh] overflow-y-auto relative transform scale-95 transition-transform duration-300">
            <!-- Header -->
            <div class="flex justify-between items-start p-8 pb-4 border-b border-gray-700">
                <div class="flex-1">
                    <h2 class="text-white text-2xl font-bold mb-2">Selamat Datang</h2>
                    <p class="text-gray-400">Masuk ke Terra Assessment</p>
                </div>
                <button onclick="closeLoginPopup()" class="text-gray-400 hover:text-white text-2xl w-9 h-9 flex items-center justify-center rounded-full hover:bg-gray-700 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Form -->
            <form class="p-8" method="POST" action="{{ route('authenticate') }}" id="loginForm">
                @csrf
                
                <div class="mb-6">
                    <label for="email" class="block text-white font-semibold mb-2 text-sm">Email Address</label>
                    <input type="email" id="email" name="email" required 
                           placeholder="superadmin@terraassess.com" value="{{ old('email') }}"
                           class="w-full px-4 py-3 bg-gray-700 border-2 border-gray-600 rounded-lg text-white text-base transition-all focus:border-galaxy-primary focus:bg-gray-600 focus:outline-none">
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block text-white font-semibold mb-2 text-sm">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required 
                               placeholder="**********"
                               class="w-full px-4 py-3 bg-gray-700 border-2 border-gray-600 rounded-lg text-white text-base transition-all focus:border-galaxy-primary focus:bg-gray-600 focus:outline-none pr-12">
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-white transition-colors">
                            <i class="fas fa-eye" id="passwordToggleIcon"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-4 mb-6">
                    <button type="button" onclick="closeLoginPopup()" class="flex-1 bg-gray-600 text-white py-3 px-4 rounded-lg font-semibold text-base hover:bg-gray-500 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 bg-gradient-to-r from-galaxy-primary to-galaxy-secondary text-white py-3 px-4 rounded-lg font-semibold text-base hover:shadow-lg hover:shadow-galaxy-primary/25 transition-all">
                        Masuk
                    </button>
                </div>
                
                <!-- Register Link -->
                <div class="text-center">
                    <p class="text-gray-400 text-sm">
                        Belum punya akun? 
                        <a href="{{ route('register') }}" class="text-galaxy-primary font-semibold hover:underline">
                            Daftar sekarang
                        </a>
                    </p>
                </div>
                
                <!-- Error Messages -->
                @if(isset($hasAdmin) && $hasAdmin == 0)
                    <div class="mt-4 p-3 bg-yellow-900/20 border-l-4 border-yellow-500 rounded flex items-center gap-2 text-yellow-400 text-sm">
                        <i class="fas fa-exclamation-triangle"></i>
                        Akun <strong>Admin</strong> belum dibuat, 
                        <a href="{{ route('adminRegister') }}" class="underline">Buat sekarang</a>
                    </div>
                @endif
                
                @if(session('login-error'))
                    <div class="mt-4 p-3 bg-red-900/20 border-l-4 border-red-500 rounded flex items-center gap-2 text-red-400 text-sm">
                        <i class="fas fa-times-circle"></i>
                        {{ session('login-error') }}
                    </div>
                @endif
                
                @if(session('register-success'))
                    <div class="mt-4 p-3 bg-green-900/20 border-l-4 border-green-500 rounded flex items-center gap-2 text-green-400 text-sm">
                        <i class="fas fa-check-circle"></i>
                        {{ session('register-success') }}
                    </div>
                @endif
                
                @if(session('logout-success'))
                    <div class="mt-4 p-3 bg-blue-900/20 border-l-4 border-blue-500 rounded flex items-center gap-2 text-blue-400 text-sm">
                        <i class="fas fa-sign-out-alt"></i>
                        {{ session('logout-success') }}
                    </div>
                @endif
            </form>
        </div>
    </div>
    
    <script>
        // Login Popup Functions
        function openLoginPopup() {
            document.getElementById('loginPopup').classList.remove('opacity-0', 'invisible', 'scale-95');
            document.getElementById('loginPopup').classList.add('opacity-100', 'visible', 'scale-100');
            document.body.style.overflow = 'hidden';
        }
        
        function closeLoginPopup() {
            document.getElementById('loginPopup').classList.add('opacity-0', 'invisible', 'scale-95');
            document.getElementById('loginPopup').classList.remove('opacity-100', 'visible', 'scale-100');
            document.body.style.overflow = 'auto';
        }
        
        // Password Toggle Function
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
        
        // Form Submission Handler
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                e.preventDefault();
                alert('Mohon lengkapi email dan password!');
                return;
            }
            
            // Add loading state
            const submitBtn = document.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            submitBtn.disabled = true;
            
            // Re-enable button after 3 seconds (in case of error)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        });
        
        // Close popup when clicking outside
        document.getElementById('loginPopup').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLoginPopup();
            }
        });
        
        // Close popup with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLoginPopup();
            }
        });
        
        // Auto-focus email input when popup opens
        document.getElementById('loginPopup').addEventListener('transitionend', function(e) {
            if (e.target.classList.contains('scale-100')) {
                document.getElementById('email').focus();
            }
        });
    </script>
</body>
</html>
