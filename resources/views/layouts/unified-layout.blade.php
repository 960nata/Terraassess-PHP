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
    <!-- Fallback Font Awesome CDN -->
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/css/all.min.css" rel="stylesheet" onerror="this.onerror=null;this.href='https://use.fontawesome.com/releases/v6.0.0/css/all.css';">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Orbitron:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/skeleton-loader.css') }}" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                    },
                    screens: {
                        'md': '768px',
                    }
                }
            }
        }
    </script>
    <link href="{{ asset('css/superadmin-dashboard.css') }}" rel="stylesheet">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('styles')
    <style>
        body {
            font-family: 'Poppins', sans-serif !important;
        }

        /* Responsive Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr); /* Mobile default */
            gap: 1rem;
            margin-bottom: 2rem;
        }

        @media (min-width: 640px) { /* Tablet Portrait */
            .dashboard-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.25rem;
            }
        }

        @media (min-width: 1024px) { /* Desktop */
            .dashboard-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 1.5rem;
            }
        }

        @media (min-width: 1280px) { /* Large Desktop */
            .dashboard-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: #ffffff;
            margin-right: 15px;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 1rem;
            vertical-align: middle;
        }
        .btn-back:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(-3px);
            color: #ffffff;
        }

        /* Fluid Typography */
        h1, .page-title { font-size: clamp(1.5rem, 4vw, 2.25rem) !important; }
        h2, .section-title { font-size: clamp(1.25rem, 3vw, 1.875rem) !important; }
        h3, .card-title { font-size: clamp(1rem, 2.5vw, 1.25rem) !important; }
        p, .card-description { font-size: clamp(0.875rem, 2vw, 1rem) !important; }

        /* Mobile Container Padding */
        .main-content {
            padding: 1rem;
        }
        @media (min-width: 640px) {
            .main-content {
                padding: 2rem;
            }
        }
    </style>
</head>
<body class="font-poppins antialiased">
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
                            <a href="{{ route('notifications.index') }}" class="view-all-btn" title="Lihat Semua">
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
            <div class="profile-container">
                <div class="user-profile" onclick="toggleProfileDropdown()">
                    <div class="user-avatar {{ $roleColor }}">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo" class="w-full h-full rounded-full object-cover">
                        @else
                            {{ substr($user->name ?? $roleInitial, 0, 2) }}
                        @endif
                    </div>
                    <div class="user-info hidden sm:block">
                        <div class="user-name">{{ $user->name ?? $roleTitle }}</div>
                        <div class="user-role">{{ $roleTitle }}</div>
                    </div>
                    <i class="fas fa-chevron-down dropdown-icon hidden sm:block"></i>
                </div>
                <div class="profile-dropdown" id="profileDropdown">
                    <div class="profile-dropdown-header">
                        <div class="profile-dropdown-avatar {{ $roleColor }}">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo" class="w-full h-full rounded-full object-cover">
                            @else
                                {{ substr($user->name ?? $roleInitial, 0, 2) }}
                            @endif
                        </div>
                        <div class="profile-dropdown-info">
                            <div class="profile-dropdown-name">{{ $user->name ?? $roleTitle }}</div>
                            <div class="profile-dropdown-role">{{ $roleTitle }}</div>
                        </div>
                    </div>
                    <div class="profile-dropdown-menu">
                        <a href="{{ $profileRoute }}" class="profile-dropdown-item">
                            <i class="fas fa-user"></i>
                            <span>Profil</span>
                        </a>
                        @if($roleId != 2 && $roleId != 3) {{-- Hide settings for Admin and Teacher roles --}}
                        <a href="{{ $settingsRoute }}" class="profile-dropdown-item">
                            <i class="fas fa-cog"></i>
                            <span>Pengaturan</span>
                        </a>
                        @endif
                        <div class="profile-dropdown-divider"></div>
                        <a href="#" class="profile-dropdown-item logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-menu">
            @include('layout.navbar.role-sidebar', ['roleId' => $roleId])
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @include('components.access-denied-notification')
        @yield('content')
    </main>

    <script src="{{ asset('js/superadmin-dashboard.js') }}"></script>
    
    <script>
        // Check if Font Awesome is loaded and add fallback if needed
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const testIcon = document.querySelector('.fas.fa-bell');
                if (testIcon) {
                    const computedStyle = window.getComputedStyle(testIcon, '::before');
                    const content = computedStyle.content;
                    
                    // If Font Awesome is not loaded (content is 'none' or empty), add fallback class
                    if (content === 'none' || content === '""' || content === '') {
                        console.warn('Font Awesome not loaded, using emoji fallbacks');
                        document.body.classList.add('fontawesome-fallback');
                    } else {
                        console.log('Font Awesome loaded successfully');
                    }
                }
            }, 1000);
        });
    </script>
    
    <script>
        // Toggle sidebar for mobile - using the same logic as external JS
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');
            const mainContent = document.querySelector('.main-content');
            
            if (window.innerWidth <= 1024) {
                // Mobile behavior - toggle collapsed class
                sidebar.classList.toggle('collapsed');
                if (mobileOverlay) mobileOverlay.classList.toggle('active');
                if (mainContent) mainContent.classList.toggle('sidebar-open');
                
                // Debug log
                console.log('Mobile sidebar toggled. Collapsed:', sidebar.classList.contains('collapsed'));
                console.log('Sidebar classes:', sidebar.className);
                console.log('Mobile overlay active:', mobileOverlay ? mobileOverlay.classList.contains('active') : 'No overlay');
            } else {
                // Desktop behavior
                sidebar.classList.toggle('collapsed');
            }
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');
            const mainContent = document.querySelector('.main-content');
            
            sidebar.classList.add('collapsed');
            mobileOverlay.classList.remove('active');
            mainContent.classList.remove('sidebar-open');
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
            
            if (window.innerWidth <= 1024 && 
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
            
            if (window.innerWidth > 1024) {
                // Desktop - show sidebar by default
                if (sidebar) sidebar.classList.remove('collapsed');
                if (mobileOverlay) mobileOverlay.classList.remove('active');
                if (mainContent) mainContent.classList.remove('sidebar-open');
            } else {
                // Mobile - hide sidebar by default
                if (sidebar) sidebar.classList.add('collapsed');
                if (mobileOverlay) mobileOverlay.classList.remove('active');
                if (mainContent) mainContent.classList.remove('sidebar-open');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
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

        // Toggle profile dropdown
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            const notificationDropdown = document.getElementById('notificationDropdown');
            
            // Close notification dropdown if open
            notificationDropdown.classList.remove('active');
            closeNotificationDropdown();
            
            // Toggle profile dropdown
            dropdown.classList.toggle('active');
            
            // Add mobile class if on mobile
            if (window.innerWidth <= 768) {
                dropdown.classList.add('mobile-dropdown');
            } else {
                dropdown.classList.remove('mobile-dropdown');
            }
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const notificationContainer = document.querySelector('.notification-container');
            const profileContainer = document.querySelector('.profile-container');
            
            if (!notificationContainer.contains(event.target)) {
                document.getElementById('notificationDropdown').classList.remove('active');
            }
            
            if (!profileContainer.contains(event.target)) {
                document.getElementById('profileDropdown').classList.remove('active');
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
    </script>
    
    <!-- Page Loader Component -->
    @include('components.page-loader')
    
    <!-- Page Loader JavaScript -->
    <script>
        // Page Loader Controller
        class PageLoader {
            constructor() {
                this.loader = document.getElementById('pageLoader');
                this.minDisplayTime = 500; // Minimum display time in ms
                this.startTime = null;
                this.isVisible = false;
                this.init();
            }
            
            init() {
                // Set theme based on user role
                this.setTheme('{{ $roleColor }}');
                
                // Attach to all internal links
                this.attachToLinks();
                
                // Handle browser back/forward
                window.addEventListener('popstate', () => {
                    this.show();
                });
                
                // Hide loader when page is fully loaded
                window.addEventListener('load', () => {
                    this.hide();
                });
            }
            
            setTheme(color) {
                if (this.loader) {
                    this.loader.className = `page-loader theme-${color}`;
                }
            }
            
            show() {
                if (this.loader && !this.isVisible) {
                    this.isVisible = true;
                    this.startTime = Date.now();
                    this.loader.classList.add('show');
                    this.loader.style.display = 'flex';
                    
                    // Animate progress bar
                    this.animateProgress();
                }
            }
            
            hide() {
                if (this.loader && this.isVisible) {
                    const elapsed = Date.now() - this.startTime;
                    const remaining = Math.max(0, this.minDisplayTime - elapsed);
                    
                    setTimeout(() => {
                        this.loader.classList.remove('show');
                        this.loader.style.display = 'none';
                        this.isVisible = false;
                    }, remaining);
                }
            }
            
            animateProgress() {
                const progressBar = this.loader.querySelector('.progress-bar');
                if (progressBar) {
                    progressBar.style.animation = 'none';
                    progressBar.offsetHeight; // Trigger reflow
                    progressBar.style.animation = 'progress 2s ease-in-out infinite';
                }
            }
            
            attachToLinks() {
                // Attach to all internal navigation links
                const links = document.querySelectorAll('a[href^="/"], a[href^="./"], a[href^="../"]');
                links.forEach(link => {
                    // Skip external links, mailto, tel, etc.
                    if (link.href.includes('mailto:') || 
                        link.href.includes('tel:') || 
                        link.href.includes('javascript:') ||
                        link.target === '_blank') {
                        return;
                    }
                    
                    link.addEventListener('click', (e) => {
                        // Don't show loader for hash links
                        if (link.getAttribute('href').startsWith('#')) {
                            return;
                        }
                        
                        this.show();
                    });
                });
                
                // Attach to form submissions
                const forms = document.querySelectorAll('form');
                forms.forEach(form => {
                    form.addEventListener('submit', () => {
                        this.show();
                    });
                });
            }
        }
        
        // Initialize page loader when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            window.pageLoader = new PageLoader();
        });
        
        // Global functions for manual control
        function showPageLoader() {
            if (window.pageLoader) {
                window.pageLoader.show();
            }
        }
        
        function hidePageLoader() {
            if (window.pageLoader) {
                window.pageLoader.hide();
            }
        }
        
        // AJAX helper for showing loader during requests
        function fetchWithLoader(url, options = {}) {
            showPageLoader();
            
            return fetch(url, options)
                .finally(() => {
                    hidePageLoader();
                });
        }
    </script>
    
    @yield('scripts')
</body>
</html>
