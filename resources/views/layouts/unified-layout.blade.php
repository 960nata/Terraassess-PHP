@php
    $roleId = $roleId ?? Auth()->user()->roles_id;
    $role = $role ?? 'superadmin';
    $user = $user ?? Auth()->user();
    
    // Role configuration
    $roleConfig = [
        1 => ['title' => 'Super Admin', 'icon' => 'fas fa-crown', 'initial' => 'SA', 'color' => 'purple'],
        2 => ['title' => 'Admin', 'icon' => 'fas fa-user-shield', 'initial' => 'AD', 'color' => 'blue'],
        3 => ['title' => 'Guru', 'icon' => 'fas fa-chalkboard-teacher', 'initial' => 'GU', 'color' => 'green'],
        4 => ['title' => 'Siswa', 'icon' => 'fas fa-user-graduate', 'initial' => 'SI', 'color' => 'orange']
    ];
    
    $currentRole = $roleConfig[$roleId] ?? $roleConfig[1];
    $roleTitle = $currentRole['title'];
    $roleIcon = $currentRole['icon'];
    $roleInitial = $currentRole['initial'];
    $roleColor = $currentRole['color'];
    
    // Profile and settings routes based on role
    $profileRoutes = [
        1 => 'superadmin.profile',
        2 => 'admin.profile', 
        3 => 'teacher.profile',
        4 => 'student.profile'
    ];
    
    $settingsRoutes = [
        1 => 'superadmin.settings',
        3 => 'teacher.settings', 
        4 => 'student.settings'
    ];
    
    $profileRoute = route($profileRoutes[$roleId] ?? 'superadmin.profile');
    $settingsRoute = $roleId == 2 ? null : route($settingsRoutes[$roleId] ?? 'superadmin.settings');
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Terra Assessment - ' . $roleTitle)</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.0.16/src/phosphor.css" rel="stylesheet">
    <!-- Custom CSS instead of Tailwind CDN -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="{{ asset('css/superadmin-dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/galaxy-theme.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Dark mode styles */
        .dark {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-tertiary: #334155;
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --border-primary: #334155;
            --border-secondary: #475569;
        }
        
        .dark body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
        }
        
        .dark .header {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-primary) !important;
        }
        
        .dark .sidebar {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-primary) !important;
        }
        
        .dark .main-content {
            background-color: var(--bg-primary) !important;
        }
        
        .dark .logo-text {
            color: var(--text-primary) !important;
        }
        
        .dark .menu-item {
            color: var(--text-secondary) !important;
        }
        
        .dark .menu-item:hover {
            background-color: var(--bg-tertiary) !important;
            color: var(--text-primary) !important;
        }
        
        .dark .menu-item.active {
            background-color: var(--bg-tertiary) !important;
            color: var(--text-primary) !important;
        }
        
        .dark .menu-section-title {
            color: var(--text-muted) !important;
        }
        
        .dark .notification-btn {
            color: var(--text-secondary) !important;
        }
        
        .dark .notification-btn:hover {
            background-color: var(--bg-tertiary) !important;
        }
        
        .dark .profile-dropdown-button {
            color: var(--text-primary) !important;
        }
        
        .dark .profile-dropdown-button:hover {
            background-color: var(--bg-tertiary) !important;
        }
        
        .dark .notification-dropdown {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-primary) !important;
        }
        
        .dark .profile-dropdown-menu {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-primary) !important;
        }
        
        .dark .notification-item {
            color: var(--text-primary) !important;
        }
        
        .dark .notification-item:hover {
            background-color: var(--bg-tertiary) !important;
        }
        
        .dark .notification-title {
            color: var(--text-primary) !important;
        }
        
        .dark .notification-excerpt {
            color: var(--text-secondary) !important;
        }
        
        .dark .notification-time {
            color: var(--text-muted) !important;
        }
        
        /* Modern Profile Dropdown Styles */
        .profile-dropdown-container {
            position: relative;
        }

        .profile-dropdown-button {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0.75rem;
            background: rgba(30, 41, 59, 0.3);
            border: 1px solid rgba(71, 85, 105, 0.2);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .profile-dropdown-button:hover {
            background: rgba(51, 65, 85, 0.9);
            border-color: rgba(71, 85, 105, 0.5);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            transform: translateY(-1px);
        }

        .profile-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
            color: white;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .profile-name {
            font-weight: 600;
            font-size: 0.875rem;
            color: #f8fafc;
            line-height: 1.2;
        }

        .profile-role {
            font-size: 0.75rem;
            color: #cbd5e1;
            line-height: 1.2;
        }

        .profile-arrow {
            color: #cbd5e1;
            font-size: 0.75rem;
            transition: transform 0.3s ease;
        }

        .profile-dropdown-button:hover .profile-arrow {
            transform: rotate(180deg);
        }

        .profile-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 0.5rem;
            min-width: 280px;
            max-width: 320px;
            width: auto;
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(71, 85, 105, 0.4);
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.05);
            z-index: 1000;
            overflow: hidden;
            backdrop-filter: blur(25px) saturate(180%);
            -webkit-backdrop-filter: blur(25px) saturate(180%);
            animation: dropdownFadeIn 0.3s ease-out;
        }

        @keyframes dropdownFadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px) scale(0.95);
                backdrop-filter: blur(0px);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
                backdrop-filter: blur(25px) saturate(180%);
            }
        }

        .profile-dropdown-header {
            padding: 1rem;
            border-bottom: 1px solid rgba(71, 85, 105, 0.2);
            background: rgba(51, 65, 85, 0.8);
        }

        .profile-dropdown-user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .profile-dropdown-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
            color: white;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .profile-dropdown-details h6 {
            margin: 0 0 0.25rem 0;
            color: #f8fafc;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .profile-dropdown-details span {
            font-size: 0.75rem;
            color: #cbd5e1;
            word-break: break-all;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }

        .profile-dropdown-items {
            padding: 0.5rem 0;
        }

        .profile-dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: #e2e8f0;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .profile-dropdown-item:hover {
            background: rgba(71, 85, 105, 0.3);
            color: #f8fafc;
            text-decoration: none;
            padding-left: 1.25rem;
        }

        .profile-dropdown-item i {
            width: 16px;
            text-align: center;
            color: #94a3b8;
        }

        .profile-dropdown-item:hover i {
            color: #f8fafc;
        }

        .profile-dropdown-item.logout {
            color: #f87171;
        }

        .profile-dropdown-item.logout:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
        }

        .profile-dropdown-item.logout i {
            color: #f87171;
        }

        .profile-dropdown-divider {
            height: 1px;
            background: rgba(71, 85, 105, 0.3);
            margin: 0.5rem 0;
        }

        /* Fix any white cards or elements that might interfere */
        .profile-dropdown-container * {
            box-sizing: border-box;
        }

        /* Ensure no white backgrounds interfere - FORCE DARK THEME */
        .profile-dropdown-container,
        .profile-dropdown-container *,
        .profile-dropdown-button,
        .profile-dropdown-button *,
        .profile-dropdown-menu,
        .profile-dropdown-menu * {
            background: transparent !important;
        }

        .profile-dropdown-button {
            background: rgba(30, 41, 59, 0.3) !important;
        }

        .profile-dropdown-button:hover {
            background: rgba(51, 65, 85, 0.6) !important;
        }

        .profile-dropdown-menu {
            background: rgba(30, 41, 59, 0.7) !important;
        }

        .profile-dropdown-header {
            background: rgba(51, 65, 85, 0.8) !important;
        }

        /* Force all text to be light colored */
        .profile-dropdown-container * {
            color: #f8fafc !important;
        }

        .profile-dropdown-container .profile-role,
        .profile-dropdown-container .profile-arrow,
        .profile-dropdown-container .profile-dropdown-details span {
            color: #cbd5e1 !important;
        }

        .profile-dropdown-container .profile-dropdown-item.logout {
            color: #f87171 !important;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .profile-info {
                display: none;
            }
            
            .profile-dropdown-menu {
                min-width: 250px;
                max-width: 280px;
                right: -10px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay" onclick="closeSidebar()"></div>

    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <button class="menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="logo">
                <div class="logo-icon {{ $roleColor }}">
                    <i class="{{ $roleIcon }}"></i>
                </div>
                <div class="logo-text hidden sm:block">Terra Assessment</div>
            </div>
        </div>
        <div class="header-right">
            <!-- Notification Dropdown -->
            <div class="notification-container">
                <button class="notification-btn" onclick="toggleNotificationDropdown()">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge" id="notificationBadge" style="display: none;"></span>
                </button>
                
                <!-- Notification Dropdown -->
                <div class="notification-dropdown" id="notificationDropdown" style="display: none;">
                    <div class="notification-header">
                        <h4>Notifikasi</h4>
                        <div class="notification-actions">
                            <button class="mark-all-read-btn" onclick="markAllAsRead()" title="Tandai Semua Dibaca">
                                <i class="fas fa-check-double"></i>
                            </button>
                            <a href="{{ route('notifications.user') }}" class="view-all-btn" title="Lihat Semua">
                                <i class="fas fa-list"></i>
                            </a>
                        </div>
                    </div>
                    <div class="notification-list" id="notificationList">
                        <div class="notification-loading">
                            <i class="fas fa-spinner fa-spin"></i>
                            <span>Memuat notifikasi...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="profile-dropdown-container">
                <button class="profile-dropdown-button" onclick="toggleProfile()">
                    <div class="profile-avatar">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                        @else
                            {{ substr($user->name ?? $roleInitial, 0, 2) }}
                        @endif
                    </div>
                    <div class="profile-info">
                        <div class="profile-name">{{ $user->name ?? $roleTitle }}</div>
                        <div class="profile-role">{{ $roleTitle }}</div>
                    </div>
                    <i class="fas fa-chevron-down profile-arrow"></i>
                </button>
                
                <!-- Profile Dropdown Menu -->
                <div class="profile-dropdown-menu hidden" id="profileDropdown">
                    <div class="profile-dropdown-header">
                        <div class="profile-dropdown-user-info">
                            <div class="profile-dropdown-avatar">
                                @if($user->profile_photo)
                                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                @else
                                    {{ substr($user->name ?? $roleInitial, 0, 2) }}
                                @endif
                            </div>
                            <div class="profile-dropdown-details">
                                <h6>{{ $user->name ?? $roleTitle }}</h6>
                                <span>{{ $user->email ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="profile-dropdown-items">
                        <a href="{{ $profileRoute }}" class="profile-dropdown-item">
                            <i class="fas fa-user"></i>
                            Profil
                        </a>
                        @if($roleId != 2)
                        <a href="{{ $settingsRoute }}" class="profile-dropdown-item">
                            <i class="fas fa-cog"></i>
                            Pengaturan
                        </a>
                        @endif
                        <div class="profile-dropdown-divider"></div>
                        <a href="{{ route('logout.get') }}" class="profile-dropdown-item logout">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-menu">
            <div class="menu-section">
                <div class="menu-section-title">Menu Utama</div>
                
                {{-- Dynamic Dashboard Menu Based on Role --}}
                @php
                    $dashboardRoutes = [
                        1 => 'superadmin.dashboard',
                        2 => 'admin.dashboard', 
                        3 => 'teacher.dashboard',
                        4 => 'student.dashboard'
                    ];
                    $dashboardRoute = $dashboardRoutes[$roleId] ?? 'dashboard';
                    $dashboardPatterns = [
                        1 => 'superadmin/dashboard*',
                        2 => 'admin/dashboard*',
                        3 => 'teacher/dashboard*', 
                        4 => 'student/dashboard*'
                    ];
                    $dashboardPattern = $dashboardPatterns[$roleId] ?? 'dashboard*';
                @endphp
                
                <a href="{{ route($dashboardRoute) }}" class="menu-item {{ Request::is($dashboardPattern) ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="menu-item-text">Dashboard</span>
                </a>
                
                {{-- Push Notifikasi - All roles --}}
                @if($roleId == 1) {{-- Super Admin --}}
                    <a href="{{ route('superadmin.push-notification') }}" class="menu-item {{ Request::is('superadmin/push-notification*') ? 'active' : '' }}">
                        <i class="fas fa-bell"></i>
                        <span class="menu-item-text">Push Notifikasi</span>
                    </a>
                @elseif($roleId == 2) {{-- Admin --}}
                    <a href="{{ route('admin.push-notification') }}" class="menu-item {{ Request::is('admin/push-notification*') ? 'active' : '' }}">
                        <i class="fas fa-bell"></i>
                        <span class="menu-item-text">Push Notifikasi</span>
                    </a>
                @elseif($roleId == 3) {{-- Teacher --}}
                    <a href="{{ route('teacher.push-notification') }}" class="menu-item {{ Request::is('teacher/push-notification*') ? 'active' : '' }}">
                        <i class="fas fa-bell"></i>
                        <span class="menu-item-text">Push Notifikasi</span>
                    </a>
                @endif
            </div>

            <!-- Management Section - RBAC Controlled -->
            @if($roleId != 4) {{-- Not for Student --}}
                <div class="menu-section">
                    <div class="menu-section-title">Manajemen</div>
                    
                    @include('components.rbac-sidebar')
                </div>
            @endif

            <!-- IoT & Research Section - All roles -->
            <div class="menu-section">
                <div class="menu-section-title">IoT & Penelitian</div>
                
                {{-- Manajemen IoT - All roles --}}
                @if($roleId == 1) {{-- Super Admin --}}
                    <a href="{{ route('superadmin.iot-management') }}" class="menu-item {{ Request::is('superadmin/iot-management*') ? 'active' : '' }}">
                        <i class="fas fa-wifi"></i>
                        <span class="menu-item-text">Manajemen IoT</span>
                    </a>
                    <a href="{{ route('iot.tugas') }}" class="menu-item {{ Request::is('iot/tugas*') ? 'active' : '' }}">
                        <i class="fas fa-server"></i>
                        <span class="menu-item-text">Tugas IoT</span>
                    </a>
                    <a href="{{ route('iot.research-projects') }}" class="menu-item {{ Request::is('iot/research-projects*') ? 'active' : '' }}">
                        <i class="fas fa-wave-square"></i>
                        <span class="menu-item-text">Penelitian IoT</span>
                    </a>
                @elseif($roleId == 2) {{-- Admin --}}
                    <a href="{{ route('admin.iot-management') }}" class="menu-item {{ Request::is('admin/iot-management*') ? 'active' : '' }}">
                        <i class="fas fa-wifi"></i>
                        <span class="menu-item-text">Manajemen IoT</span>
                    </a>
                @elseif($roleId == 3) {{-- Teacher --}}
                    <a href="{{ route('teacher.iot.dashboard') }}" class="menu-item {{ Request::is('teacher/iot*') ? 'active' : '' }}">
                        <i class="fas fa-wifi"></i>
                        <span class="menu-item-text">Manajemen IoT</span>
                    </a>
                @elseif($roleId == 4) {{-- Student --}}
                    <a href="{{ route('student.iot') }}" class="menu-item {{ Request::is('student/iot*') ? 'active' : '' }}">
                        <i class="fas fa-wifi"></i>
                        <span class="menu-item-text">Manajemen IoT</span>
                    </a>
                @endif
            </div>

            <!-- Analytics & Reports - All roles -->
            <div class="menu-section">
                <div class="menu-section-title">Analitik</div>
                
                {{-- Laporan - All roles --}}
                @if($roleId == 1) {{-- Super Admin --}}
                    <a href="{{ route('superadmin.reports') }}" class="menu-item {{ Request::is('superadmin/reports*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span class="menu-item-text">Laporan</span>
                    </a>
                @elseif($roleId == 2) {{-- Admin --}}
                    <a href="{{ route('admin.reports') }}" class="menu-item {{ Request::is('admin/reports*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span class="menu-item-text">Laporan</span>
                    </a>
                @elseif($roleId == 3) {{-- Teacher --}}
                    <a href="{{ route('teacher.reports') }}" class="menu-item {{ Request::is('teacher/reports*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span class="menu-item-text">Laporan</span>
                    </a>
                @elseif($roleId == 4) {{-- Student --}}
                    <a href="{{ route('student.reports') }}" class="menu-item {{ Request::is('student/reports*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span class="menu-item-text">Laporan</span>
                    </a>
                @endif
            </div>

            <!-- Settings Section - All roles -->
            <div class="menu-section">
                <div class="menu-section-title">Pengaturan</div>
                
                @if($roleId == 1) {{-- Super Admin --}}
                    <a href="{{ route('superadmin.settings') }}" class="menu-item {{ Request::is('superadmin/settings*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span class="menu-item-text">Pengaturan</span>
                    </a>
                    <a href="{{ route('superadmin.help') }}" class="menu-item {{ Request::is('superadmin/help*') ? 'active' : '' }}">
                        <i class="fas fa-question-circle"></i>
                        <span class="menu-item-text">Bantuan</span>
                    </a>
                @elseif($roleId == 2) {{-- Admin --}}
                    <a href="{{ route('admin.settings') }}" class="menu-item {{ Request::is('admin/settings*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span class="menu-item-text">Pengaturan</span>
                    </a>
                    <a href="{{ route('admin.help') }}" class="menu-item {{ Request::is('admin/help*') ? 'active' : '' }}">
                        <i class="fas fa-question-circle"></i>
                        <span class="menu-item-text">Bantuan</span>
                    </a>
                @elseif($roleId == 3) {{-- Teacher --}}
                    <a href="{{ route('teacher.settings') }}" class="menu-item {{ Request::is('teacher/settings*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span class="menu-item-text">Pengaturan</span>
                    </a>
                    <a href="{{ route('teacher.help') }}" class="menu-item {{ Request::is('teacher/help*') ? 'active' : '' }}">
                        <i class="fas fa-question-circle"></i>
                        <span class="menu-item-text">Bantuan</span>
                    </a>
                @elseif($roleId == 4) {{-- Student --}}
                    <a href="{{ route('student.settings') }}" class="menu-item {{ Request::is('student/settings*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span class="menu-item-text">Pengaturan</span>
                    </a>
                    <a href="{{ route('student.help') }}" class="menu-item {{ Request::is('student/help*') ? 'active' : '' }}">
                        <i class="fas fa-question-circle"></i>
                        <span class="menu-item-text">Bantuan</span>
                    </a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @include('components.access-denied-notification')
        @yield('content')
    </main>

    <script src="{{ asset('js/superadmin-dashboard.js') }}"></script>
    
    <script>
        // Toggle sidebar - ULTRA FORCE LOGIC
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');
            const mainContent = document.querySelector('.main-content');
            
            console.log('=== TOGGLE SIDEBAR DEBUG ===');
            console.log('Window width:', window.innerWidth);
            console.log('Sidebar element:', sidebar);
            console.log('Before toggle - classes:', sidebar.className);
            console.log('Before toggle - collapsed:', sidebar.classList.contains('collapsed'));
            
            if (window.innerWidth <= 768) {
                // Mobile behavior - ULTRA FORCE LOGIC
                const isCollapsed = sidebar.classList.contains('collapsed');
                
                if (isCollapsed) {
                    // Show sidebar - SAME AS FORCE SHOW
                    sidebar.classList.remove('collapsed');
                    
                    // Force all possible CSS properties - SAME AS FORCE SHOW
                    sidebar.style.setProperty('transform', 'translateX(0)', 'important');
                    sidebar.style.setProperty('visibility', 'visible', 'important');
                    sidebar.style.setProperty('opacity', '1', 'important');
                    sidebar.style.setProperty('display', 'block', 'important');
                    sidebar.style.setProperty('position', 'fixed', 'important');
                    sidebar.style.setProperty('top', '70px', 'important');
                    sidebar.style.setProperty('left', '0', 'important');
                    sidebar.style.setProperty('width', '260px', 'important');
                    sidebar.style.setProperty('height', 'calc(100vh - 70px)', 'important');
                    sidebar.style.setProperty('z-index', '9999', 'important');
                    sidebar.style.setProperty('background', 'rgba(15, 23, 42, 0.95)', 'important');
                    
                    // Force overlay - SAME AS FORCE SHOW
                    if (mobileOverlay) {
                        mobileOverlay.classList.add('active');
                        mobileOverlay.style.setProperty('display', 'block', 'important');
                        mobileOverlay.style.setProperty('background', 'rgba(0, 0, 0, 0.5)', 'important');
                    }
                    if (mainContent) mainContent.classList.add('sidebar-open');
                    
                    console.log('Mobile sidebar SHOWN - SAME AS FORCE SHOW');
                } else {
                    // Hide sidebar - SAME AS FORCE SHOW
                    sidebar.classList.add('collapsed');
                    
                    // Force all possible CSS properties - SAME AS FORCE SHOW
                    sidebar.style.setProperty('transform', 'translateX(-100%)', 'important');
                    sidebar.style.setProperty('visibility', 'hidden', 'important');
                    sidebar.style.setProperty('opacity', '0', 'important');
                    sidebar.style.setProperty('display', 'block', 'important');
                    
                    // Force overlay - SAME AS FORCE SHOW
                    if (mobileOverlay) {
                        mobileOverlay.classList.remove('active');
                        mobileOverlay.style.setProperty('display', 'none', 'important');
                    }
                    if (mainContent) mainContent.classList.remove('sidebar-open');
                    
                    console.log('Mobile sidebar HIDDEN - SAME AS FORCE SHOW');
                }
                
                console.log('After toggle - classes:', sidebar.className);
                console.log('After toggle - collapsed:', sidebar.classList.contains('collapsed'));
                
                // Check computed styles
                const computedStyle = window.getComputedStyle(sidebar);
                console.log('Computed transform:', computedStyle.transform);
                console.log('Computed visibility:', computedStyle.visibility);
                console.log('Computed opacity:', computedStyle.opacity);
                console.log('Computed display:', computedStyle.display);
                console.log('Computed position:', computedStyle.position);
                console.log('Computed z-index:', computedStyle.zIndex);
            } else {
                // Desktop behavior
                sidebar.classList.toggle('collapsed');
                console.log('Desktop sidebar toggled');
            }
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');
            const mainContent = document.querySelector('.main-content');
            
            sidebar.classList.add('collapsed');
            if (mobileOverlay) mobileOverlay.classList.remove('active');
            if (mainContent) mainContent.classList.remove('sidebar-open');
            
            // FORCE CSS with inline styles as backup
            if (window.innerWidth <= 768) {
                sidebar.style.setProperty('transform', 'translateX(-100%)', 'important');
                sidebar.style.setProperty('visibility', 'hidden', 'important');
                sidebar.style.setProperty('opacity', '0', 'important');
                sidebar.style.setProperty('display', 'block', 'important');
            }
        }


        // Notification functionality
        let notificationDropdown = null;
        let notificationBadge = null;
        let notificationList = null;
        let isDropdownOpen = false;

        // Close sidebar on mobile when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.querySelector('.menu-toggle');
            const mobileOverlay = document.getElementById('mobileOverlay');
            
            if (window.innerWidth <= 768 && 
                sidebar && 
                !sidebar.contains(event.target) && 
                menuToggle && 
                !menuToggle.contains(event.target) &&
                mobileOverlay && 
                !mobileOverlay.contains(event.target)) {
                closeSidebar();
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');
            const mainContent = document.querySelector('.main-content');
            
            if (window.innerWidth > 768) {
                // Desktop - show sidebar by default
                if (sidebar) {
                    sidebar.classList.remove('collapsed');
                    sidebar.style.transform = '';
                    sidebar.style.visibility = '';
                    sidebar.style.opacity = '';
                }
                if (mobileOverlay) mobileOverlay.classList.remove('active');
                if (mainContent) mainContent.classList.remove('sidebar-open');
                } else {
                    // Mobile - hide sidebar by default
                    if (sidebar) {
                        sidebar.classList.add('collapsed');
                        sidebar.style.setProperty('transform', 'translateX(-100%)', 'important');
                        sidebar.style.setProperty('visibility', 'hidden', 'important');
                        sidebar.style.setProperty('opacity', '0', 'important');
                        sidebar.style.setProperty('display', 'block', 'important');
                    }
                if (mobileOverlay) mobileOverlay.classList.remove('active');
                if (mainContent) mainContent.classList.remove('sidebar-open');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize sidebar state
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                if (window.innerWidth > 768) {
                    // Desktop: sidebar terbuka by default
                    sidebar.classList.remove('collapsed');
                    sidebar.style.transform = '';
                    sidebar.style.visibility = '';
                    sidebar.style.opacity = '';
                } else {
                    // Mobile: sidebar tertutup by default
                    sidebar.classList.add('collapsed');
                    sidebar.style.setProperty('transform', 'translateX(-100%)', 'important');
                    sidebar.style.setProperty('visibility', 'hidden', 'important');
                    sidebar.style.setProperty('opacity', '0', 'important');
                    sidebar.style.setProperty('display', 'block', 'important');
                }
            }
            
            notificationDropdown = document.getElementById('notificationDropdown');
            notificationBadge = document.getElementById('notificationBadge');
            notificationList = document.getElementById('notificationList');
            
            // Load initial notifications
            loadNotifications();
            updateUnreadCount();
            
            // Auto refresh every 30 seconds
            setInterval(function() {
                loadNotifications();
                updateUnreadCount();
            }, 30000);
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (isDropdownOpen && !e.target.closest('.notification-container')) {
                    closeNotificationDropdown();
                }
            });
        });

        function toggleNotificationDropdown() {
            if (isDropdownOpen) {
                closeNotificationDropdown();
            } else {
                openNotificationDropdown();
            }
        }

        function openNotificationDropdown() {
            if (notificationDropdown) {
                notificationDropdown.style.display = 'block';
                isDropdownOpen = true;
                loadNotifications();
                
                // Add mobile class if on mobile
                if (window.innerWidth <= 768) {
                    notificationDropdown.classList.add('mobile-dropdown');
                }
            }
        }

        function closeNotificationDropdown() {
            if (notificationDropdown) {
                notificationDropdown.style.display = 'none';
                isDropdownOpen = false;
                notificationDropdown.classList.remove('mobile-dropdown');
            }
        }

        function loadNotifications() {
            if (!notificationList) return;
            
            fetch('/api/notifications/latest')
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        notificationList.innerHTML = `
                            <div class="notification-loading">
                                <i class="fas fa-bell-slash"></i>
                                <span>Tidak ada notifikasi</span>
                            </div>
                        `;
                    } else {
                        notificationList.innerHTML = data.map(notification => `
                            <div class="notification-item ${!notification.is_read ? 'unread' : ''}" 
                                 onclick="markAsRead(${notification.id})">
                                <div class="notification-icon-small">
                                    <i class="fas fa-${getNotificationIcon(notification.type)}"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-title">${notification.title}</div>
                                    <div class="notification-excerpt">${notification.excerpt || notification.body.substring(0, 80) + '...'}</div>
                                    <div class="notification-time">${formatTime(notification.created_at)}</div>
                                </div>
                            </div>
                        `).join('');
                    }
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    notificationList.innerHTML = `
                        <div class="notification-loading">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>Gagal memuat notifikasi</span>
                        </div>
                    `;
                });
        }

        function getNotificationIcon(type) {
            const icons = {
                'info': 'info',
                'warning': 'warning',
                'success': 'check-circle',
                'error': 'x-circle'
            };
            return icons[type] || 'bell';
        }

        function updateUnreadCount() {
            fetch('/api/notifications/unread-count')
                .then(response => response.json())
                .then(data => {
                    if (notificationBadge) {
                        if (data.count > 0) {
                            notificationBadge.textContent = data.count;
                            notificationBadge.style.display = 'flex';
                            notificationBadge.classList.remove('read');
                        } else {
                            notificationBadge.style.display = 'none';
                            notificationBadge.classList.add('read');
                        }
                    }
                })
                .catch(error => console.error('Error updating unread count:', error));
        }

        function markAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/mark-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI
                    const notificationItem = document.querySelector(`[onclick="markAsRead(${notificationId})"]`);
                    if (notificationItem) {
                        notificationItem.classList.remove('unread');
                    }
                    
                    // Update unread count
                    updateUnreadCount();
                }
            })
            .catch(error => console.error('Error marking notification as read:', error));
        }

        function markAllAsRead() {
            fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI - remove unread class from all items
                    const unreadItems = document.querySelectorAll('.notification-item.unread');
                    unreadItems.forEach(item => {
                        item.classList.remove('unread');
                    });
                    
                    // Hide notification badge and mark as read
                    if (notificationBadge) {
                        notificationBadge.style.display = 'none';
                        notificationBadge.classList.add('read');
                    }
                    
                    // Reload notifications to get updated data
                    loadNotifications();
                    
                    // Show success message
                    showNotification('Semua notifikasi telah ditandai sebagai dibaca', 'success');
                }
            })
            .catch(error => {
                console.error('Error marking all notifications as read:', error);
                showNotification('Gagal menandai notifikasi sebagai dibaca', 'error');
            });
        }

        function showNotification(message, type = 'info') {
            // Create notification toast
            const toast = document.createElement('div');
            toast.className = `notification-toast ${type}`;
            toast.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'times-circle' : 'info-circle'}"></i>
                <span>${message}</span>
            `;
            
            // Add to body
            document.body.appendChild(toast);
            
            // Show toast
            setTimeout(() => toast.classList.add('show'), 100);
            
            // Remove toast after 3 seconds
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function formatTime(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffInMinutes = Math.floor((now - date) / (1000 * 60));
            
            if (diffInMinutes < 1) return 'Baru saja';
            if (diffInMinutes < 60) return `${diffInMinutes} menit yang lalu`;
            
            const diffInHours = Math.floor(diffInMinutes / 60);
            if (diffInHours < 24) return `${diffInHours} jam yang lalu`;
            
            const diffInDays = Math.floor(diffInHours / 24);
            if (diffInDays < 7) return `${diffInDays} hari yang lalu`;
            
            return date.toLocaleDateString('id-ID');
        }

        // Profile dropdown removed - using direct buttons now

        // Close notification dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const notificationContainer = document.querySelector('.notification-container');
            
            if (!notificationContainer.contains(event.target)) {
                document.getElementById('notificationDropdown').classList.remove('active');
            }
        });

        // Mobile responsive behavior
        function handleMobileDropdowns() {
            const notificationDropdown = document.getElementById('notificationDropdown');
            const profileDropdown = document.getElementById('profileDropdown');
            
            if (window.innerWidth <= 768) {
                // On mobile, make dropdowns full-width and slide from right
                if (notificationDropdown.classList.contains('active')) {
                    notificationDropdown.classList.add('mobile-dropdown');
                }
                if (profileDropdown.classList.contains('active')) {
                    profileDropdown.classList.add('mobile-dropdown');
                }
            } else {
                // On desktop, remove mobile classes and reset positioning
                notificationDropdown.classList.remove('mobile-dropdown');
                profileDropdown.classList.remove('mobile-dropdown');
            }
        }

        // Initialize mobile behavior
        handleMobileDropdowns();
        window.addEventListener('resize', handleMobileDropdowns);

        // Profile dropdown functionality
        function toggleProfile() {
            const profileDropdown = document.getElementById('profileDropdown');
            const notificationDropdown = document.getElementById('notificationDropdown');
            
            // Close notification dropdown if open
            if (notificationDropdown) {
                notificationDropdown.style.display = 'none';
            }
            
            // Toggle profile dropdown
            if (profileDropdown.classList.contains('hidden')) {
                profileDropdown.classList.remove('hidden');
                profileDropdown.style.display = 'block';
            } else {
                profileDropdown.classList.add('hidden');
                profileDropdown.style.display = 'none';
            }
        }

        // Close profile dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const profileDropdown = document.getElementById('profileDropdown');
            const profileButton = event.target.closest('.profile-dropdown-button');
            const profileContainer = event.target.closest('.profile-dropdown-container');
            
            if (profileDropdown && !profileContainer) {
                profileDropdown.classList.add('hidden');
                profileDropdown.style.display = 'none';
            }
        });

        // Close dropdown when pressing Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const profileDropdown = document.getElementById('profileDropdown');
                if (profileDropdown && !profileDropdown.classList.contains('hidden')) {
                    profileDropdown.classList.add('hidden');
                    profileDropdown.style.display = 'none';
                }
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>
