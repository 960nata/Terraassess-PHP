<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Terra Assessment')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/student-header.css') }}" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .main-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }
        
        .sidebar.collapsed {
            transform: translateX(-100%);
        }
        
        .sidebar-header {
            padding: 24px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #1f2937;
        }
        
        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }
        
        .logo-text {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
        }
        
        .sidebar-menu {
            padding: 16px 0;
        }
        
        .menu-section {
            margin-bottom: 24px;
        }
        
        .menu-section-title {
            padding: 0 24px 8px;
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 24px;
            color: #6b7280;
            text-decoration: none;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }
        
        .menu-item:hover {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            border-left-color: #667eea;
        }
        
        .menu-item.active {
            background: rgba(102, 126, 234, 0.15);
            color: #667eea;
            border-left-color: #667eea;
            font-weight: 500;
        }
        
        .menu-item i {
            width: 20px;
            text-align: center;
        }
        
        .main-content {
            flex: 1;
            margin-left: 280px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            min-height: 100vh;
        }
        
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            color: #6b7280;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        
        .menu-toggle:hover {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            border-radius: 12px;
            background: rgba(102, 126, 234, 0.1);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .user-profile:hover {
            background: rgba(102, 126, 234, 0.2);
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            font-weight: 600;
        }
        
        .user-info {
            display: flex;
            flex-direction: column;
        }
        
        .user-name {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
        }
        
        .user-role {
            font-size: 12px;
            color: #6b7280;
        }
        
        .content {
            padding: 24px;
        }
        
        .page-header {
            margin-bottom: 32px;
        }
        
        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .page-description {
            font-size: 16px;
            color: #6b7280;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .menu-toggle {
                display: block;
            }
            
            .content {
                padding: 16px;
            }
            
            .page-title {
                font-size: 24px;
            }
        }
    </style>
    
    @yield('additional-styles')
</head>
<body>
    <div class="main-container">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('student.dashboard') }}" class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="logo-text">Terra Assessment</div>
                </a>
            </div>
            
            <div class="sidebar-menu">
                <div class="menu-section">
                    <div class="menu-section-title">Menu Utama</div>
                    
                    <a href="{{ route('student.dashboard') }}" class="menu-item {{ Request::is('student/dashboard*') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="{{ route('student.tasks') }}" class="menu-item {{ Request::is('student/tasks*') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <span>Tugas Saya</span>
                    </a>
                    
                    <a href="{{ route('student.exams') }}" class="menu-item {{ Request::is('student/exams*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-check"></i>
                        <span>Ujian Saya</span>
                    </a>
                    
                    <a href="{{ route('student.materials') }}" class="menu-item {{ Request::is('student/materials*') ? 'active' : '' }}">
                        <i class="fas fa-book"></i>
                        <span>Materi Saya</span>
                    </a>
                    
                    <a href="{{ route('student.iot') }}" class="menu-item {{ Request::is('student/iot*') ? 'active' : '' }}">
                        <i class="fas fa-microchip"></i>
                        <span>IoT Projects</span>
                    </a>
                </div>
                
                <div class="menu-section">
                    <div class="menu-section-title">Penelitian</div>
                    
                    <a href="{{ route('student.iot-research') }}" class="menu-item {{ Request::is('student/iot-research*') ? 'active' : '' }}">
                        <i class="fas fa-microscope"></i>
                        <span>IoT Research</span>
                    </a>
                </div>
                
                <div class="menu-section">
                    <div class="menu-section-title">Pengaturan</div>
                    
                    <a href="{{ route('student.settings') }}" class="menu-item {{ Request::is('student/settings*') || Request::is('student/profile*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                    </a>
                    
                    <!-- Notifications removed for students - not relevant -->
                </div>
            </div>
        </nav>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                
                <div class="header-right">
                    <div class="user-profile" onclick="toggleUserMenu()">
                        <div class="user-avatar">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="user-info">
                            <div class="user-name">{{ auth()->user()->name }}</div>
                            <div class="user-role">Siswa</div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <div class="content">
                @yield('content')
            </div>
        </main>
    </div>
    
    <!-- Scripts -->
    <script>
        // Toggle sidebar on mobile
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.getElementById('menuToggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !menuToggle.contains(event.target)) {
                sidebar.classList.remove('open');
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth > 768) {
                sidebar.classList.remove('open');
            }
        });
        
        function toggleUserMenu() {
            // Implement user menu dropdown
            console.log('User menu clicked');
        }
    </script>
    
    @yield('additional-scripts')
</body>
</html>
