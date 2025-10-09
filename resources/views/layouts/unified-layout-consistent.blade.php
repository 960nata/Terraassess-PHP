<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Terra Assessment')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/css/unified-design-system.css', 'resources/js/app.js'])
    
    @yield('styles')
</head>
<body class="h-full">
    @php
        $user = auth()->user();
        $roleId = $user->roles_id ?? 0;
        $roleName = '';
        $roleIcon = '';
        $roleColor = '';
        
        switch($roleId) {
            case 1:
                $roleName = 'Super Admin';
                $roleIcon = 'fas fa-crown';
                $roleColor = 'role-superadmin';
                break;
            case 2:
                $roleName = 'Admin';
                $roleIcon = 'fas fa-user-shield';
                $roleColor = 'role-admin';
                break;
            case 3:
                $roleName = 'Teacher';
                $roleIcon = 'fas fa-chalkboard-teacher';
                $roleColor = 'role-teacher';
                break;
            case 4:
                $roleName = 'Student';
                $roleIcon = 'fas fa-user-graduate';
                $roleColor = 'role-student';
                break;
            default:
                $roleName = 'User';
                $roleIcon = 'fas fa-user';
                $roleColor = 'role-user';
        }
    @endphp

    <div class="unified-layout {{ $roleColor }}">
        <!-- Mobile Overlay -->
        <div class="mobile-overlay" id="mobileOverlay" onclick="closeSidebar()"></div>

        <!-- Sidebar -->
        <aside class="unified-sidebar" id="sidebar">
            <!-- Sidebar Header -->
            <div class="sidebar-header">
                <a href="{{ route('dashboard') }}" class="sidebar-logo">
                    <div class="sidebar-logo-icon">
                        <i class="fas fa-satellite-dish"></i>
                    </div>
                    <div>
                        <div class="sidebar-logo-text">Terra Assessment</div>
                        <div class="sidebar-role-badge">{{ $roleName }}</div>
                    </div>
                </a>
                <button class="sidebar-toggle" onclick="closeSidebar()">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="sidebar-nav">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <div class="nav-item-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <span class="nav-item-text">Dashboard</span>
                </a>

                @if($roleId == 1 || $roleId == 2)
                    <!-- Super Admin & Admin Menu -->
                    <a href="{{ route('user-management') }}" class="nav-item {{ request()->routeIs('user-management') ? 'active' : '' }}">
                        <div class="nav-item-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="nav-item-text">User Management</span>
                    </a>

                    <a href="{{ route('class-management') }}" class="nav-item {{ request()->routeIs('class-management') ? 'active' : '' }}">
                        <div class="nav-item-icon">
                            <i class="fas fa-chalkboard"></i>
                        </div>
                        <span class="nav-item-text">Class Management</span>
                    </a>

                    <a href="{{ route('subject-management') }}" class="nav-item {{ request()->routeIs('subject-management') ? 'active' : '' }}">
                        <div class="nav-item-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <span class="nav-item-text">Subject Management</span>
                    </a>
                @endif

                @if($roleId == 1 || $roleId == 2 || $roleId == 3)
                    <!-- Task Management -->
                    <a href="{{ $roleId == 1 ? route('superadmin.task-management') : ($roleId == 2 ? route('admin.task-management') : route('teacher.task-management')) }}" 
                       class="nav-item {{ request()->routeIs('*task-management*') ? 'active' : '' }}">
                        <div class="nav-item-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <span class="nav-item-text">Task Management</span>
                    </a>

                    <!-- Material Management -->
                    <a href="{{ $roleId == 1 ? route('superadmin.material-management') : ($roleId == 2 ? route('admin.material-management') : route('teacher.material-management')) }}" 
                       class="nav-item {{ request()->routeIs('*material-management*') ? 'active' : '' }}">
                        <div class="nav-item-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <span class="nav-item-text">Material Management</span>
                    </a>

                    <!-- IoT Management -->
                    <a href="{{ $roleId == 1 ? route('superadmin.iot-management') : ($roleId == 2 ? route('admin.iot-management') : route('teacher.iot')) }}" 
                       class="nav-item {{ request()->routeIs('*iot*') ? 'active' : '' }}">
                        <div class="nav-item-icon">
                            <i class="fas fa-microchip"></i>
                        </div>
                        <span class="nav-item-text">IoT Management</span>
                    </a>
                @endif

                @if($roleId == 4)
                    <!-- Student Menu -->
                    <a href="{{ route('student.tugas') }}" class="nav-item {{ request()->routeIs('student.tugas*') ? 'active' : '' }}">
                        <div class="nav-item-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <span class="nav-item-text">My Tasks</span>
                    </a>

                    <a href="{{ route('student.ujian') }}" class="nav-item {{ request()->routeIs('student.ujian*') ? 'active' : '' }}">
                        <div class="nav-item-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <span class="nav-item-text">My Exams</span>
                    </a>

                    <a href="{{ route('student.materi') }}" class="nav-item {{ request()->routeIs('student.materi*') ? 'active' : '' }}">
                        <div class="nav-item-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <span class="nav-item-text">My Materials</span>
                    </a>

                    <a href="{{ route('student.iot') }}" class="nav-item {{ request()->routeIs('student.iot*') ? 'active' : '' }}">
                        <div class="nav-item-icon">
                            <i class="fas fa-microchip"></i>
                        </div>
                        <span class="nav-item-text">IoT Projects</span>
                    </a>
                @endif

                @if($roleId == 1 || $roleId == 2)
                    <!-- Reports -->
                    <a href="{{ route('reports') }}" class="nav-item {{ request()->routeIs('reports') ? 'active' : '' }}">
                        <div class="nav-item-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <span class="nav-item-text">Reports</span>
                    </a>

                    <!-- Analytics -->
                    <a href="{{ route('analytics') }}" class="nav-item {{ request()->routeIs('analytics') ? 'active' : '' }}">
                        <div class="nav-item-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span class="nav-item-text">Analytics</span>
                    </a>
                @endif

                <!-- Profile -->
                <a href="{{ route('profile') }}" class="nav-item {{ request()->routeIs('profile') ? 'active' : '' }}">
                    <div class="nav-item-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <span class="nav-item-text">Profile</span>
                </a>

                <!-- Help -->
                <a href="{{ route('help') }}" class="nav-item {{ request()->routeIs('help') ? 'active' : '' }}">
                    <div class="nav-item-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <span class="nav-item-text">Help</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="unified-main" id="mainContent">
            <!-- Header -->
            <header class="unified-header">
                <div class="header-left">
                    <button class="header-toggle" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="header-title">
                        <h1>@yield('page-title', 'Dashboard')</h1>
                        <p class="header-subtitle">@yield('page-description', 'Selamat datang di Terra Assessment')</p>
                    </div>
                </div>

                <div class="header-right">
                    <!-- Notifications -->
                    <div class="relative">
                        <button class="unified-btn unified-btn-secondary" onclick="toggleNotifications()">
                            <i class="fas fa-bell"></i>
                            <span class="nav-item-badge" id="notificationBadge" style="display: none;">0</span>
                        </button>
                    </div>

                    <!-- User Profile Dropdown -->
                    <div class="relative">
                        <button class="flex items-center gap-3 p-2 rounded-lg hover:bg-secondary-100 transition-colors" onclick="toggleProfileDropdown()">
                            <div class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <div class="hidden md:block text-left">
                                <div class="text-sm font-medium text-secondary-900">{{ $user->name }}</div>
                                <div class="text-xs text-secondary-500">{{ $roleName }}</div>
                            </div>
                            <i class="fas fa-chevron-down text-secondary-400 text-xs"></i>
                        </button>

                        <!-- Profile Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-secondary-200 py-1 z-50 hidden" id="profileDropdown">
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-secondary-700 hover:bg-secondary-100">
                                <i class="fas fa-user mr-2"></i>Profile
                            </a>
                            <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm text-secondary-700 hover:bg-secondary-100">
                                <i class="fas fa-cog mr-2"></i>Settings
                            </a>
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-error-600 hover:bg-error-50">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="unified-content">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- JavaScript -->
    <script>
        // Sidebar Toggle Functions
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('mainContent');
            const overlay = document.getElementById('mobileOverlay');
            
            if (window.innerWidth <= 1024) {
                // Mobile behavior
                sidebar.classList.toggle('mobile-open');
                overlay.classList.toggle('active');
            } else {
                // Desktop behavior
                sidebar.classList.toggle('collapsed');
                main.classList.toggle('sidebar-collapsed');
            }
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('mainContent');
            const overlay = document.getElementById('mobileOverlay');
            
            sidebar.classList.remove('mobile-open', 'collapsed');
            main.classList.remove('sidebar-collapsed');
            overlay.classList.remove('active');
        }

        // Profile Dropdown Toggle
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('hidden');
        }

        // Notifications Toggle
        function toggleNotifications() {
            // Implementation for notifications
            console.log('Toggle notifications');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const profileDropdown = document.getElementById('profileDropdown');
            const profileButton = event.target.closest('[onclick="toggleProfileDropdown()"]');
            
            if (!profileButton && !profileDropdown.contains(event.target)) {
                profileDropdown.classList.add('hidden');
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('mainContent');
            const overlay = document.getElementById('mobileOverlay');
            
            if (window.innerWidth > 1024) {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
            } else {
                sidebar.classList.remove('collapsed');
                main.classList.remove('sidebar-collapsed');
            }
        });

        // Initialize sidebar state
        document.addEventListener('DOMContentLoaded', function() {
            // Check if sidebar should be collapsed by default on desktop
            if (window.innerWidth > 1024) {
                const savedState = localStorage.getItem('sidebarCollapsed');
                if (savedState === 'true') {
                    toggleSidebar();
                }
            }
        });

        // Save sidebar state
        function saveSidebarState() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth > 1024) {
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            }
        }

        // Update toggle function to save state
        const originalToggleSidebar = toggleSidebar;
        toggleSidebar = function() {
            originalToggleSidebar();
            setTimeout(saveSidebarState, 300); // Save after transition
        };
    </script>

    @yield('scripts')
</body>
</html>
