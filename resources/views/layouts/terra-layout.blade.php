@php
    $roleId = $roleId ?? Auth()->user()->roles_id;
    $role = $role ?? 'superadmin';
    $user = $user ?? Auth()->user();
    
    // Role configuration
    $roleConfig = [
        1 => ['title' => 'Super Admin', 'icon' => 'fas fa-crown', 'initial' => 'SA', 'color' => 'primary'],
        2 => ['title' => 'Admin', 'icon' => 'fas fa-user-shield', 'initial' => 'AD', 'color' => 'info'],
        3 => ['title' => 'Guru', 'icon' => 'fas fa-chalkboard-teacher', 'initial' => 'GU', 'color' => 'success'],
        4 => ['title' => 'Siswa', 'icon' => 'fas fa-user-graduate', 'initial' => 'SI', 'color' => 'warning']
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
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script>
        // Custom JavaScript initialization
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
    
    <!-- Flexible Layout Styles -->
    <style>
        /* Flexible Layout System */
        .main-content-wrapper {
            margin-left: 0;
            width: 100%;
        }
        
        /* Desktop: Sidebar visible by default */
        @media (min-width: 1024px) {
            .main-content-wrapper {
                margin-left: 16rem; /* 256px = w-64 */
            }
            
            /* When sidebar is collapsed on desktop */
            .main-content-wrapper.sidebar-collapsed {
                margin-left: 0;
            }
            
            /* When sidebar is expanded on desktop */
            .main-content-wrapper:not(.sidebar-collapsed) {
                margin-left: 16rem;
            }
        }
        
        /* Mobile: Sidebar hidden by default */
        @media (max-width: 1023px) {
            .main-content-wrapper {
                margin-left: 0;
            }
            
            /* When sidebar is open on mobile */
            .main-content-wrapper.sidebar-open {
                margin-left: 0; /* Keep content full width on mobile */
            }
        }
        
        /* Sidebar state management */
        #sidebar[data-sidebar="expanded"] {
            transform: translateX(0);
        }
        
        #sidebar[data-sidebar="collapsed"] {
            transform: translateX(-100%);
        }
        
        @media (min-width: 1024px) {
            #sidebar[data-sidebar="collapsed"] {
                transform: translateX(-100%);
            }
            
            #sidebar[data-sidebar="expanded"] {
                transform: translateX(0);
            }
        }
        
        /* Smooth transitions */
        #sidebar,
        .main-content-wrapper {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Mobile overlay improvements */
        #mobileOverlay {
            transition: opacity 0.3s ease-in-out;
        }
        
        #mobileOverlay.hidden {
            opacity: 0;
            pointer-events: none;
        }
        
        #mobileOverlay:not(.hidden) {
            opacity: 1;
            pointer-events: auto;
        }
    </style>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('styles')
</head>
<body class="bg-secondary-50 font-sans antialiased">
    <!-- Mobile Overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden" id="mobileOverlay" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    <aside class="fixed left-0 top-0 h-full w-64 bg-white border-r border-secondary-200 z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out" id="sidebar" data-sidebar="collapsed">
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between p-6 border-b border-secondary-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-satellite-dish text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-secondary-900">Terra Assessment</h1>
                    <p class="text-xs text-secondary-500">{{ $roleTitle }}</p>
                </div>
            </div>
            <button class="lg:hidden p-2 rounded-lg hover:bg-secondary-100" onclick="closeSidebar()">
                <i class="fas fa-times text-secondary-600"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-4 space-y-2">
            <!-- Dashboard -->
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
            
            <a href="{{ route($dashboardRoute) }}" class="terra-nav-item {{ Request::is($dashboardPattern) ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>

            <!-- Role-specific Navigation -->
            @if($roleId == 1) {{-- Super Admin --}}
                <a href="{{ route('superadmin.push-notification') }}" class="terra-nav-item {{ Request::is('superadmin/push-notification*') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i>
                    <span>Push Notifikasi</span>
                </a>
            @elseif($roleId == 2) {{-- Admin --}}
                <a href="{{ route('admin.push-notification') }}" class="terra-nav-item {{ Request::is('admin/push-notification*') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i>
                    <span>Push Notifikasi</span>
                </a>
            @elseif($roleId == 3) {{-- Teacher --}}
                <a href="{{ route('teacher.tasks.management') }}" class="terra-nav-item {{ Request::is('teacher/tasks*') ? 'active' : '' }}">
                    <i class="fas fa-tasks"></i>
                    <span>Manajemen Tugas</span>
                </a>
                <a href="{{ route('teacher.exam-management') }}" class="terra-nav-item {{ Request::is('teacher/exam-management*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Manajemen Ujian</span>
                </a>
                <a href="{{ route('teacher.materi') }}" class="terra-nav-item {{ Request::is('teacher/materi*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i>
                    <span>Manajemen Materi</span>
                </a>
            @elseif($roleId == 4) {{-- Student --}}
                <a href="{{ route('student.tugas') }}" class="terra-nav-item {{ Request::is('student/tugas*') ? 'active' : '' }}">
                    <i class="fas fa-tasks"></i>
                    <span>Tugas Saya</span>
                </a>
                <a href="{{ route('student.ujian') }}" class="terra-nav-item {{ Request::is('student/ujian*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Ujian Saya</span>
                </a>
                <a href="{{ route('student.materi') }}" class="terra-nav-item {{ Request::is('student/materi*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i>
                    <span>Materi Saya</span>
                </a>
            @endif

            <!-- Management Section -->
            @if($roleId != 4) {{-- Not for Student --}}
                <div class="pt-4">
                    <h3 class="px-4 text-xs font-semibold text-secondary-500 uppercase tracking-wider mb-2">Manajemen</h3>
                    @include('components.rbac-sidebar')
                </div>
            @endif

            <!-- IoT & Research Section -->
            <div class="pt-4">
                <h3 class="px-4 text-xs font-semibold text-secondary-500 uppercase tracking-wider mb-2">IoT & Penelitian</h3>
                
                @if($roleId == 1 || $roleId == 2) {{-- Super Admin & Admin --}}
                    <a href="{{ route('iot.tugas') }}" class="terra-nav-item {{ Request::is('iot/tugas*') ? 'active' : '' }}">
                        <i class="fas fa-server"></i>
                        <span>Tugas IoT</span>
                    </a>
                    <a href="{{ route('iot.research-projects') }}" class="terra-nav-item {{ Request::is('iot/research-projects*') ? 'active' : '' }}">
                        <i class="fas fa-wave-square"></i>
                        <span>Penelitian IoT</span>
                    </a>
                @elseif($roleId == 3) {{-- Teacher --}}
                    <a href="{{ route('teacher.iot.dashboard') }}" class="terra-nav-item {{ Request::is('teacher/iot*') ? 'active' : '' }}">
                        <i class="fas fa-wifi"></i>
                        <span>Manajemen IoT</span>
                    </a>
                @elseif($roleId == 4) {{-- Student --}}
                    <a href="{{ route('student.iot') }}" class="terra-nav-item {{ Request::is('student/iot*') ? 'active' : '' }}">
                        <i class="fas fa-microscope"></i>
                        <span>Penelitian IoT</span>
                    </a>
                @endif
            </div>

            <!-- Student Additional Features -->
            @if($roleId == 4) {{-- Student --}}
                <div class="pt-4">
                    <h3 class="px-4 text-xs font-semibold text-secondary-500 uppercase tracking-wider mb-2">Fitur Tambahan</h3>
                    
                    <a href="{{ route('iot.sensor-data') }}" class="terra-nav-item {{ Request::is('iot/sensor-data*') ? 'active' : '' }}">
                        <i class="fas fa-database"></i>
                        <span>Data IoT</span>
                    </a>
                    
                    <a href="{{ route('student.reports') }}" class="terra-nav-item {{ Request::is('student/reports*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Nilai Saya</span>
                    </a>
                    
                    <a href="{{ route('student.class-management') }}" class="terra-nav-item {{ Request::is('student/class-management*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Jadwal</span>
                    </a>
                    
                    <a href="{{ route('student.tasks') }}" class="terra-nav-item {{ Request::is('student/tasks*') ? 'active' : '' }}">
                        <i class="fas fa-list-alt"></i>
                        <span>Penugasan</span>
                    </a>
                    
                    <a href="{{ route('student.reports') }}" class="terra-nav-item {{ Request::is('student/progress*') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie"></i>
                        <span>Progress</span>
                    </a>
                    
                    <a href="{{ route('student.notifications') }}" class="terra-nav-item {{ Request::is('student/notifications*') ? 'active' : '' }}">
                        <i class="fas fa-bell"></i>
                        <span>Notifikasi</span>
                    </a>
                </div>
            @endif

            <!-- Analytics & Reports -->
            @if($roleId == 1 || $roleId == 2 || $roleId == 3)
                <div class="pt-4">
                    <h3 class="px-4 text-xs font-semibold text-secondary-500 uppercase tracking-wider mb-2">Analitik</h3>
                    
                    @if($roleId == 1) {{-- Super Admin --}}
                        <a href="{{ route('superadmin.reports') }}" class="terra-nav-item {{ Request::is('superadmin/reports*') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i>
                            <span>Laporan</span>
                        </a>
                    @elseif($roleId == 2) {{-- Admin --}}
                        <a href="{{ route('admin.reports') }}" class="terra-nav-item {{ Request::is('admin/reports*') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i>
                            <span>Laporan</span>
                        </a>
                    @elseif($roleId == 3) {{-- Teacher --}}
                        <a href="{{ route('teacher.reports') }}" class="terra-nav-item {{ Request::is('teacher/reports*') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i>
                            <span>Laporan</span>
                        </a>
                    @endif
                </div>
            @endif

            <!-- Settings Section -->
            <div class="pt-4">
                <h3 class="px-4 text-xs font-semibold text-secondary-500 uppercase tracking-wider mb-2">Pengaturan</h3>
                
                @if($roleId == 1) {{-- Super Admin --}}
                    <a href="{{ route('superadmin.settings') }}" class="terra-nav-item {{ Request::is('superadmin/settings*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                    </a>
                    <a href="{{ route('superadmin.help') }}" class="terra-nav-item {{ Request::is('superadmin/help*') ? 'active' : '' }}">
                        <i class="fas fa-question-circle"></i>
                        <span>Bantuan</span>
                    </a>
                @elseif($roleId == 2) {{-- Admin --}}
                    <a href="{{ route('admin.settings') }}" class="terra-nav-item {{ Request::is('admin/settings*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                    </a>
                    <a href="{{ route('admin.help') }}" class="terra-nav-item {{ Request::is('admin/help*') ? 'active' : '' }}">
                        <i class="fas fa-question-circle"></i>
                        <span>Bantuan</span>
                    </a>
                @elseif($roleId == 3) {{-- Teacher --}}
                    <a href="{{ route('teacher.settings') }}" class="terra-nav-item {{ Request::is('teacher/settings*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                    </a>
                    <a href="{{ route('teacher.help') }}" class="terra-nav-item {{ Request::is('teacher/help*') ? 'active' : '' }}">
                        <i class="fas fa-question-circle"></i>
                        <span>Bantuan</span>
                    </a>
                @elseif($roleId == 4) {{-- Student --}}
                    <a href="{{ route('student.settings') }}" class="terra-nav-item {{ Request::is('student/settings*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan</span>
                    </a>
                    <a href="{{ route('student.help') }}" class="terra-nav-item {{ Request::is('student/help*') ? 'active' : '' }}">
                        <i class="fas fa-question-circle"></i>
                        <span>Bantuan</span>
                    </a>
                @endif
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content-wrapper min-h-screen transition-all duration-300 ease-in-out" id="mainContent">
        <!-- Header -->
        <header class="bg-white border-b border-secondary-200 sticky top-0 z-30">
            <div class="flex items-center justify-between px-6 py-4">
                <!-- Left side -->
                <div class="flex items-center space-x-4">
                    <!-- Mobile menu button -->
                    <button class="lg:hidden p-2 rounded-lg hover:bg-secondary-100" onclick="toggleSidebar()">
                        <i class="fas fa-bars text-secondary-600"></i>
                    </button>
                    
                    <!-- Desktop sidebar toggle button -->
                    <button class="hidden lg:block p-2 rounded-lg hover:bg-secondary-100" onclick="toggleSidebar()" title="Toggle Sidebar">
                        <i class="fas fa-bars text-secondary-600"></i>
                    </button>
                    
                    <div>
                        <h2 class="text-xl font-semibold text-secondary-900">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-sm text-secondary-500">@yield('page-description', 'Selamat datang di Terra Assessment')</p>
                    </div>
                </div>

                <!-- Right side -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <div class="relative">
                        <button class="p-2 rounded-lg hover:bg-secondary-100 relative" onclick="toggleNotifications()">
                            <i class="fas fa-bell text-secondary-600"></i>
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-primary-600 text-white text-xs rounded-full flex items-center justify-center hidden" id="notificationBadge">0</span>
                        </button>
                        
                        <!-- Notification Dropdown -->
                        <div class="absolute right-0 mt-2 w-80 bg-white border border-secondary-200 rounded-lg shadow-lg z-50 hidden" id="notificationDropdown">
                            <div class="p-4 border-b border-secondary-200">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold text-secondary-900">Notifikasi</h3>
                                    <div class="flex space-x-2">
                                        <button class="text-xs text-primary-600 hover:text-primary-700" onclick="markAllAsRead()">
                                            Tandai Semua Dibaca
                                        </button>
                                        <a href="{{ route('notifications.user') }}" class="text-xs text-primary-600 hover:text-primary-700">
                                            Lihat Semua
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="max-h-96 overflow-y-auto" id="notificationList">
                                <div class="p-4 text-center text-secondary-500">
                                    <i class="fas fa-spinner fa-spin mb-2"></i>
                                    <p>Memuat notifikasi...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button class="flex items-center space-x-3 p-2 rounded-lg hover:bg-secondary-100" onclick="toggleProfile()">
                            <div class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                @if($user->profile_photo)
                                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile" class="w-full h-full rounded-full object-cover">
                                @else
                                    {{ substr($user->name ?? $roleInitial, 0, 2) }}
                                @endif
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-medium text-secondary-900">{{ $user->name ?? $roleTitle }}</p>
                                <p class="text-xs text-secondary-500">{{ $roleTitle }}</p>
                            </div>
                            <i class="fas fa-chevron-down text-secondary-400 text-xs"></i>
                        </button>
                        
                        <!-- Profile Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-48 bg-white border border-secondary-200 rounded-lg shadow-lg z-50 hidden" id="profileDropdown">
                            <div class="p-4 border-b border-secondary-200">
                                <p class="font-medium text-secondary-900">{{ $user->name ?? $roleTitle }}</p>
                                <p class="text-sm text-secondary-500">{{ $user->email ?? '' }}</p>
                            </div>
                            <div class="py-2">
                                <a href="{{ $profileRoute }}" class="flex items-center px-4 py-2 text-sm text-secondary-700 hover:bg-secondary-100">
                                    <i class="fas fa-user w-4 mr-3"></i>
                                    Profil
                                </a>
                                @if($roleId != 2)
                                <a href="{{ $settingsRoute }}" class="flex items-center px-4 py-2 text-sm text-secondary-700 hover:bg-secondary-100">
                                    <i class="fas fa-cog w-4 mr-3"></i>
                                    Pengaturan
                                </a>
                                @endif
                                <div class="border-t border-secondary-200 my-2"></div>
                                <a href="{{ route('logout.get') }}" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt w-4 mr-3"></i>
                                    Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-6">
            @include('components.access-denied-notification')
            @yield('content')
        </main>
    </div>

    <script>
        // Enhanced Sidebar functionality with state management
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            const mainContent = document.getElementById('mainContent');
            const isMobile = window.innerWidth < 1024;
            
            if (isMobile) {
                // Mobile behavior
                const isExpanded = sidebar.getAttribute('data-sidebar') === 'expanded';
                
                if (isExpanded) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            } else {
                // Desktop behavior - toggle sidebar state
                const isCollapsed = sidebar.getAttribute('data-sidebar') === 'collapsed';
                
                if (isCollapsed) {
                    expandSidebar();
                } else {
                    collapseSidebar();
                }
            }
        }

        function openSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.setAttribute('data-sidebar', 'expanded');
            overlay.classList.remove('hidden');
            mainContent.classList.add('sidebar-open');
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.setAttribute('data-sidebar', 'collapsed');
            overlay.classList.add('hidden');
            mainContent.classList.remove('sidebar-open');
        }

        function expandSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.setAttribute('data-sidebar', 'expanded');
            mainContent.classList.remove('sidebar-collapsed');
            
            // Save state to localStorage
            localStorage.setItem('sidebarState', 'expanded');
        }

        function collapseSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.setAttribute('data-sidebar', 'collapsed');
            mainContent.classList.add('sidebar-collapsed');
            
            // Save state to localStorage
            localStorage.setItem('sidebarState', 'collapsed');
        }

        // Initialize sidebar state on page load
        function initializeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const isMobile = window.innerWidth < 1024;
            
            if (isMobile) {
                // Mobile: always start collapsed
                sidebar.setAttribute('data-sidebar', 'collapsed');
                mainContent.classList.remove('sidebar-open', 'sidebar-collapsed');
            } else {
                // Desktop: restore from localStorage or default to expanded
                const savedState = localStorage.getItem('sidebarState') || 'expanded';
                sidebar.setAttribute('data-sidebar', savedState);
                
                if (savedState === 'collapsed') {
                    mainContent.classList.add('sidebar-collapsed');
                } else {
                    mainContent.classList.remove('sidebar-collapsed');
                }
            }
            
            // Force a reflow to ensure proper positioning
            sidebar.offsetHeight;
        }

        // Handle window resize with debouncing
        let resizeTimeout;
        function handleResize() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('mobileOverlay');
                const mainContent = document.getElementById('mainContent');
                const isMobile = window.innerWidth < 1024;
                
                if (isMobile) {
                    // Switch to mobile mode
                    sidebar.setAttribute('data-sidebar', 'collapsed');
                    overlay.classList.add('hidden');
                    mainContent.classList.remove('sidebar-open', 'sidebar-collapsed');
                } else {
                    // Switch to desktop mode
                    const savedState = localStorage.getItem('sidebarState') || 'expanded';
                    sidebar.setAttribute('data-sidebar', savedState);
                    overlay.classList.add('hidden');
                    
                    if (savedState === 'collapsed') {
                        mainContent.classList.add('sidebar-collapsed');
                    } else {
                        mainContent.classList.remove('sidebar-collapsed');
                    }
                }
                
                // Force a reflow to ensure proper positioning
                sidebar.offsetHeight;
            }, 100);
        }

        // Notification functionality
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('hidden');
            
            if (!dropdown.classList.contains('hidden')) {
                loadNotifications();
            }
        }

        function loadNotifications() {
            const notificationList = document.getElementById('notificationList');
            
            fetch('/api/notifications/latest')
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        notificationList.innerHTML = `
                            <div class="p-4 text-center text-secondary-500">
                                <i class="fas fa-bell-slash mb-2"></i>
                                <p>Tidak ada notifikasi</p>
                            </div>
                        `;
                    } else {
                        notificationList.innerHTML = data.map(notification => `
                            <div class="p-4 border-b border-secondary-100 hover:bg-secondary-50 cursor-pointer" onclick="markAsRead(${notification.id})">
                                <div class="flex items-start space-x-3">
                                    <div class="w-2 h-2 bg-primary-600 rounded-full mt-2 ${notification.is_read ? 'hidden' : ''}"></div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-secondary-900">${notification.title}</p>
                                        <p class="text-xs text-secondary-500 mt-1">${notification.excerpt || notification.body.substring(0, 80) + '...'}</p>
                                        <p class="text-xs text-secondary-400 mt-1">${formatTime(notification.created_at)}</p>
                                    </div>
                                </div>
                            </div>
                        `).join('');
                    }
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    notificationList.innerHTML = `
                        <div class="p-4 text-center text-red-500">
                            <i class="fas fa-exclamation-triangle mb-2"></i>
                            <p>Gagal memuat notifikasi</p>
                        </div>
                    `;
                });
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
                    updateUnreadCount();
                    loadNotifications();
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
                    updateUnreadCount();
                    loadNotifications();
                }
            })
            .catch(error => console.error('Error marking all notifications as read:', error));
        }

        function updateUnreadCount() {
            fetch('/api/notifications/unread-count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notificationBadge');
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                })
                .catch(error => console.error('Error updating unread count:', error));
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

        // Profile dropdown functionality
        function toggleProfile() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const notificationDropdown = document.getElementById('notificationDropdown');
            const profileDropdown = document.getElementById('profileDropdown');
            const notificationBtn = event.target.closest('[onclick="toggleNotifications()"]');
            const profileBtn = event.target.closest('[onclick="toggleProfile()"]');
            
            if (!notificationBtn && !notificationDropdown.contains(event.target)) {
                notificationDropdown.classList.add('hidden');
            }
            
            if (!profileBtn && !profileDropdown.contains(event.target)) {
                profileDropdown.classList.add('hidden');
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize sidebar state
            initializeSidebar();
            
            // Update notifications
            updateUnreadCount();
            
            // Auto refresh notifications every 30 seconds
            setInterval(updateUnreadCount, 30000);
        });

        // Handle window resize
        window.addEventListener('resize', handleResize);
    </script>
    
    @yield('scripts')
</body>
</html>
