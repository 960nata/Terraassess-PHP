<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Terra Assessment - Super Admin')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- WARNING: Tailwind CDN should not be used in production -->
    <!-- For production, install Tailwind CSS as PostCSS plugin or use Tailwind CLI -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Quill.js for Rich Text Editor -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    screens: {
                        'md': '768px',
                    }
                }
            }
        }
    </script>
    <link href="{{ asset('css/superadmin-dashboard.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        /* Settings and Logout buttons */
        .settings-button, .logout-button {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            background: transparent;
        }
        
        .settings-button {
            color: #6b7280;
        }
        
        .settings-button:hover {
            background-color: #f3f4f6;
            color: #374151;
        }
        
        .logout-button {
            color: #dc2626;
        }
        
        .logout-button:hover {
            background-color: #fef2f2;
            color: #b91c1c;
        }
        
        .profile-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .user-avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
            color: white;
            background-color: #8b5cf6;
        }
        
        .user-info {
            display: flex;
            flex-direction: column;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 0.875rem;
            color: #111827;
        }
        
        .user-role {
            font-size: 0.75rem;
            color: #6b7280;
        }
        
        @media (max-width: 640px) {
            .settings-button span, .logout-button span {
                display: none;
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
                <div class="logo-icon">
                    <i class="fas fa-crown"></i>
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

            <!-- User Info and Settings -->
            <div class="profile-container">
                <div class="user-profile">
                    <div class="user-avatar">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo" class="w-full h-full rounded-full object-cover">
                        @else
                            {{ substr($user->name ?? 'SA', 0, 2) }}
                        @endif
                    </div>
                    <div class="user-info hidden sm:block">
                        <div class="user-name">{{ $user->name ?? 'User' }}</div>
                        <div class="user-role">
                            @if($user->roles_id == 1)
                                Super Admin
                            @elseif($user->roles_id == 2)
                                Admin
                            @elseif($user->roles_id == 3)
                                Guru
                            @elseif($user->roles_id == 4)
                                Siswa
                            @else
                                User
                            @endif
                        </div>
                    </div>
                </div>
                <a href="{{ route('superadmin.settings') }}" class="settings-button">
                    <i class="fas fa-cog"></i>
                    <span class="hidden sm:block">Pengaturan</span>
                </a>
                <a href="{{ route('logout.get') }}" class="logout-button">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="hidden sm:block">Logout</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-menu">
            <div class="menu-section">
                <div class="menu-section-title">Menu Utama</div>
                <a href="{{ route('superadmin.dashboard') }}" class="menu-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="menu-item-text">Dashboard</span>
                </a>
                <a href="{{ route('superadmin.push-notification') }}" class="menu-item {{ request()->routeIs('superadmin.push-notification') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i>
                    <span class="menu-item-text">Push Notifikasi</span>
                </a>
                <a href="{{ route('superadmin.iot-management') }}" class="menu-item {{ request()->routeIs('superadmin.iot-management') ? 'active' : '' }}">
                    <i class="fas fa-wifi"></i>
                    <span class="menu-item-text">Manajemen IoT</span>
                </a>
                <a href="{{ route('iot.tugas') }}" class="menu-item {{ request()->routeIs('iot.tugas*') ? 'active' : '' }}">
                    <i class="fas fa-server"></i>
                    <span class="menu-item-text">Tugas IoT</span>
                </a>
                <a href="{{ route('iot.research-projects') }}" class="menu-item {{ request()->routeIs('iot.research-projects*') ? 'active' : '' }}">
                    <i class="fas fa-wave-square"></i>
                    <span class="menu-item-text">Penelitian IoT</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Manajemen</div>
                @include('components.rbac-sidebar')
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Analitik</div>
                <a href="{{ route('superadmin.reports') }}" class="menu-item {{ request()->routeIs('superadmin.reports*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span class="menu-item-text">Laporan</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Pengaturan</div>
                <a href="{{ route('superadmin.settings') }}" class="menu-item {{ request()->routeIs('superadmin.settings') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span class="menu-item-text">Pengaturan</span>
                </a>
                <a href="{{ route('superadmin.help') }}" class="menu-item {{ request()->routeIs('superadmin.help') ? 'active' : '' }}">
                    <i class="fas fa-question-circle"></i>
                    <span class="menu-item-text">Bantuan</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    
    <script>
        // Sidebar functions are now handled by external superadmin-dashboard.js
        // This prevents conflicts and ensures consistent behavior

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
            console.log('Initializing notification system...');
            
            notificationDropdown = document.getElementById('notificationDropdown');
            notificationBadge = document.getElementById('notificationBadge');
            notificationList = document.getElementById('notificationList');
            
            // Debug: Check if elements exist
            console.log('Notification dropdown:', notificationDropdown);
            console.log('Notification badge:', notificationBadge);
            console.log('Notification list:', notificationList);
            
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
            console.log('Loading notifications...');
            if (!notificationList) {
                console.error('Notification list element not found');
                return;
            }
            
            fetch('/api/notifications/latest')
                .then(response => {
                    console.log('API response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Notifications data:', data);
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
            console.log('Updating unread count...');
            fetch('/api/notifications/unread-count')
                .then(response => {
                    console.log('Unread count API response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Unread count data:', data);
                    if (notificationBadge) {
                        if (data.count > 0) {
                            notificationBadge.textContent = data.count;
                            notificationBadge.style.display = 'flex';
                            notificationBadge.classList.remove('read');
                            console.log('Badge updated with count:', data.count);
                        } else {
                            notificationBadge.style.display = 'none';
                            notificationBadge.classList.add('read');
                            console.log('Badge hidden - no unread notifications');
                        }
                    } else {
                        console.error('Notification badge element not found');
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
    </script>
    
    @yield('scripts')
</body>
</html>
