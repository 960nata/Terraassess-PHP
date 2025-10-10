<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terra Assessment - Super Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.0.16/src/phosphor.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css'])
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
    <style>
        /* Notification Toast Styles */
        .notification-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 12px 16px;
            color: white;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            z-index: 9999;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
        }

        .notification-toast.show {
            opacity: 1;
            transform: translateX(0);
        }

        .notification-toast.success {
            border-left: 4px solid #22c55e;
        }

        .notification-toast.error {
            border-left: 4px solid #ef4444;
        }

        .notification-toast.info {
            border-left: 4px solid #3b82f6;
        }

        /* Notification Actions */
        .notification-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .mark-all-read-btn {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #3b82f6;
            padding: 6px 8px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .mark-all-read-btn:hover {
            background: rgba(59, 130, 246, 0.2);
            border-color: rgba(59, 130, 246, 0.5);
            color: #60a5fa;
        }

        .view-all-btn {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #22c55e;
            padding: 6px 8px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .view-all-btn:hover {
            background: rgba(34, 197, 94, 0.2);
            border-color: rgba(34, 197, 94, 0.5);
            color: #16a34a;
        }

        /* Dashboard Grid Layout */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 3rem;
        }

        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 0.875rem;
            }
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: repeat(2, 1fr);
                grid-template-rows: repeat(6, 1fr);
                gap: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            .dashboard-grid {
                grid-template-columns: repeat(2, 1fr);
                grid-template-rows: repeat(6, 1fr);
                gap: 0.5rem;
            }
        }

        /* Card Styling for 4x3 Layout */
        .card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            min-height: 160px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-decoration: none;
            color: inherit;
        }

        .card:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(59, 130, 246, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .card-icon.blue {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
        }

        .card-icon.green {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .card-icon.purple {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
        }

        .card-icon.orange {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .card-icon.red {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: white;
            margin-bottom: 0.5rem;
        }

        .card-description {
            color: #94a3b8;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        /* Mobile Card Adjustments */
        @media (max-width: 768px) {
            .card {
                min-height: 140px;
                padding: 1rem;
            }

            .card-icon {
                width: 40px;
                height: 40px;
                font-size: 1.25rem;
                margin-bottom: 0.75rem;
            }

            .card-title {
                font-size: 1rem;
            }

            .card-description {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .card {
                min-height: 130px;
                padding: 0.75rem;
            }

            .card-icon {
                width: 32px;
                height: 32px;
                font-size: 1rem;
                margin-bottom: 0.5rem;
            }

            .card-title {
                font-size: 0.85rem;
                line-height: 1.3;
            }

            .card-description {
                font-size: 0.7rem;
                line-height: 1.4;
            }
        }
    </style>
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
                    <i class="ph-bell"></i>
                    <span class="notification-badge" id="notificationBadge" style="display: none;"></span>
                </button>
                
                <!-- Notification Dropdown -->
                <div class="notification-dropdown" id="notificationDropdown" style="display: none;">
                    <div class="notification-header">
                        <h4>Notifikasi</h4>
                        <div class="notification-actions">
                            <button class="mark-all-read-btn" onclick="markAllAsRead()" title="Tandai Semua Dibaca">
                                <i class="ph-check-double"></i>
                            </button>
                            <a href="{{ route('notifications.index') }}" class="view-all-btn" title="Lihat Semua">
                                <i class="ph-list"></i>
                            </a>
                        </div>
                    </div>
                    <div class="notification-list" id="notificationList">
                        <div class="notification-loading">
                            <i class="ph-spinner ph-spin"></i>
                            <span>Memuat notifikasi...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="profile-container">
                <div class="user-profile" onclick="toggleProfileDropdown()">
                    <div class="user-avatar">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo" class="w-full h-full rounded-full object-cover">
                        @else
                            {{ substr($user->name ?? 'SA', 0, 2) }}
                        @endif
                    </div>
                    <div class="user-info hidden sm:block">
                        <div class="user-name">{{ $user->name ?? 'Super Admin' }}</div>
                        <div class="user-role">Super Admin</div>
                    </div>
                    <i class="fas fa-chevron-down dropdown-icon hidden sm:block"></i>
                </div>
                <div class="profile-dropdown" id="profileDropdown">
                    <div class="profile-dropdown-header">
                        <div class="profile-dropdown-avatar">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo" class="w-full h-full rounded-full object-cover">
                            @else
                                {{ substr($user->name ?? 'SA', 0, 2) }}
                            @endif
                        </div>
                        <div class="profile-dropdown-info">
                            <div class="profile-dropdown-name">{{ $user->name ?? 'Super Admin' }}</div>
                            <div class="profile-dropdown-role">Super Admin</div>
                        </div>
                    </div>
                    <div class="profile-dropdown-menu">
                        <a href="{{ route('superadmin.profile') }}" class="profile-dropdown-item">
                            <i class="fas fa-user"></i>
                            <span>Profil</span>
                        </a>
                        <a href="{{ route('superadmin.settings') }}" class="profile-dropdown-item">
                            <i class="fas fa-cog"></i>
                            <span>Pengaturan</span>
                        </a>
                        <div class="profile-dropdown-divider"></div>
                        <a href="{{ route('logout.get') }}" class="profile-dropdown-item logout">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
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
                <a href="{{ route('superadmin.dashboard') }}" class="menu-item active">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="menu-item-text">Dashboard</span>
                </a>
                <a href="{{ route('superadmin.push-notification') }}" class="menu-item">
                    <i class="fas fa-bell"></i>
                    <span class="menu-item-text">Push Notifikasi</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Manajemen</div>
                <a href="{{ route('superadmin.tugas.index') }}" class="menu-item">
                    <i class="fas fa-book"></i>
                    <span class="menu-item-text">Manajemen Tugas</span>
                </a>
                <a href="{{ route('superadmin.exam-management') }}" class="menu-item">
                    <i class="fas fa-bullseye"></i>
                    <span class="menu-item-text">Manajemen Ujian</span>
                </a>
                <a href="{{ route('superadmin.user-management') }}" class="menu-item">
                    <i class="fas fa-users"></i>
                    <span class="menu-item-text">Manajemen Pengguna</span>
                </a>
                <a href="{{ route('superadmin.class-management') }}" class="menu-item">
                    <i class="fas fa-chart-bar"></i>
                    <span class="menu-item-text">Manajemen Kelas</span>
                </a>
                <a href="{{ route('superadmin.subject-management') }}" class="menu-item">
                    <i class="fas fa-database"></i>
                    <span class="menu-item-text">Mata Pelajaran</span>
                </a>
                <a href="{{ route('superadmin.material-management') }}" class="menu-item">
                    <i class="fas fa-file-alt"></i>
                    <span class="menu-item-text">Manajemen Materi</span>
                </a>
                <a href="{{ route('superadmin.iot-management') }}" class="menu-item">
                    <i class="fas fa-wifi"></i>
                    <span class="menu-item-text">Manajemen IoT</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">IoT & Penelitian</div>
                <a href="{{ route('superadmin.iot-tasks') }}" class="menu-item">
                    <i class="fas fa-tasks"></i>
                    <span class="menu-item-text">Tugas IoT</span>
                </a>
                <a href="{{ route('superadmin.iot-research') }}" class="menu-item">
                    <i class="fas fa-flask"></i>
                    <span class="menu-item-text">Penelitian IoT</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Laporan & Analitik</div>
                <a href="{{ route('superadmin.reports') }}" class="menu-item">
                    <i class="fas fa-chart-line"></i>
                    <span class="menu-item-text">Laporan</span>
                </a>
                <a href="{{ route('superadmin.analytics') }}" class="menu-item">
                    <i class="fas fa-chart-bar"></i>
                    <span class="menu-item-text">Analytics</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Pengaturan</div>
                <a href="{{ route('superadmin.settings') }}" class="menu-item">
                    <i class="fas fa-cog"></i>
                    <span class="menu-item-text">Pengaturan</span>
                </a>
                <a href="{{ route('superadmin.help') }}" class="menu-item">
                    <i class="fas fa-question-circle"></i>
                    <span class="menu-item-text">Bantuan</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-crown"></i>
                Super Admin Dashboard
            </h1>
            <p class="page-description">Kontrol penuh atas sistem Terra Assessment</p>
        </div>

        <div class="welcome-banner">
            <div class="welcome-icon">
                <i class="fas fa-exclamation"></i>
            </div>
            <div class="welcome-content">
                <h2 class="welcome-title">Selamat datang, Super Admin!</h2>
                <p class="welcome-description">Sebagai Super Admin, Anda memiliki akses penuh untuk mengelola seluruh sistem.</p>
            </div>
        </div>

        <div class="dashboard-grid">
            <!-- Row 1 -->
            <a href="{{ route('superadmin.push-notification') }}" class="card">
                <div class="card-icon blue">
                    <i class="fas fa-bell"></i>
                </div>
                <h3 class="card-title">Push Notifikasi</h3>
                <p class="card-description">Kirim notifikasi ke semua pengguna, kelas, atau pengguna spesifik</p>
            </a>

            <a href="{{ route('superadmin.iot-management') }}" class="card">
                <div class="card-icon green">
                    <i class="fas fa-wifi"></i>
                </div>
                <h3 class="card-title">Manajemen IoT</h3>
                <p class="card-description">Daftarkan perangkat IoT, test konektivitas, dan monitor data sensor</p>
            </a>

            <!-- Row 2 -->
            <a href="{{ route('superadmin.tugas.index') }}" class="card">
                <div class="card-icon purple">
                    <i class="fas fa-book"></i>
                </div>
                <h3 class="card-title">Manajemen Tugas</h3>
                <p class="card-description">Kelola tugas per kelas dengan kategorisasi dan tingkat kesulitan</p>
            </a>

            <a href="{{ route('superadmin.exam-management') }}" class="card">
                <div class="card-icon orange">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3 class="card-title">Manajemen Ujian</h3>
                <p class="card-description">Buat, edit, dan kelola ujian dengan fitur lengkap</p>
            </a>

            <!-- Row 3 -->
            <a href="{{ route('superadmin.user-management') }}" class="card">
                <div class="card-icon blue">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="card-title">Manajemen Pengguna</h3>
                <p class="card-description">Kelola semua pengguna sistem (Admin, Guru, Siswa)</p>
            </a>

            <a href="{{ route('superadmin.class-management') }}" class="card">
                <div class="card-icon green">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3 class="card-title">Manajemen Kelas</h3>
                <p class="card-description">Buat dan kelola semua kelas di sistem</p>
            </a>

            <!-- Row 4 -->
            <a href="{{ route('superadmin.subject-management') }}" class="card">
                <div class="card-icon purple">
                    <i class="fas fa-database"></i>
                </div>
                <h3 class="card-title">Mata Pelajaran</h3>
                <p class="card-description">Tambah dan kelola mata pelajaran</p>
            </a>

            <a href="{{ route('iot.tugas') }}" class="card">
                <div class="card-icon blue">
                    <i class="fas fa-server"></i>
                </div>
                <h3 class="card-title">Tugas IoT</h3>
                <p class="card-description">Buat dan kelola tugas penelitian IoT</p>
            </a>

            <!-- Row 5 -->
            <a href="{{ route('iot.research-projects') }}" class="card">
                <div class="card-icon purple">
                    <i class="fas fa-wave-square"></i>
                </div>
                <h3 class="card-title">Penelitian IoT</h3>
                <p class="card-description">Lihat hasil penelitian IoT siswa</p>
            </a>

            <a href="{{ route('superadmin.material-management') }}" class="card">
                <div class="card-icon green">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3 class="card-title">Materi</h3>
                <p class="card-description">Kelola materi pembelajaran dan konten</p>
            </a>

            <!-- Row 6 -->
            <a href="{{ route('superadmin.exam-management') }}" class="card">
                <div class="card-icon red">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h3 class="card-title">Ujian</h3>
                <p class="card-description">Kelola semua ujian sistem</p>
            </a>

            <a href="{{ route('superadmin.push-notification') }}" class="card">
                <div class="card-icon red">
                    <i class="fas fa-cog"></i>
                </div>
                <h3 class="card-title">Notifikasi</h3>
                <p class="card-description">Kelola notifikasi sistem</p>
            </a>
        </div>

        <div class="system-info">
            <div class="info-section">
                <h3 class="info-title">Hak Akses Super Admin</h3>
                <ul class="info-list">
                    <li>Kelola semua pengguna sistem</li>
                    <li>Akses ke semua fitur aplikasi</li>
                    <li>Konfigurasi sistem global</li>
                    <li>Monitoring aktivitas pengguna</li>
                </ul>
            </div>

            <div class="info-section">
                <h3 class="info-title">Tanggung Jawab</h3>
                <ul class="info-list">
                    <li>Memastikan keamanan sistem</li>
                    <li>Mengelola data pengguna</li>
                    <li>Konfigurasi aplikasi</li>
                    <li>Backup dan maintenance</li>
                </ul>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/superadmin-dashboard.js') }}"></script>
    
    <script>
        // Toggle sidebar for mobile - using the same logic as external JS
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');
            const mainContent = document.querySelector('.main-content');
            
            if (window.innerWidth <= 1024) {
                // Mobile behavior
                sidebar.classList.toggle('collapsed');
                mobileOverlay.classList.toggle('active');
                mainContent.classList.toggle('sidebar-open');
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
                                <i class="ph-bell-slash"></i>
                                <span>Tidak ada notifikasi</span>
                            </div>
                        `;
                    } else {
                        notificationList.innerHTML = data.map(notification => `
                            <div class="notification-item ${!notification.is_read ? 'unread' : ''}" 
                                 onclick="markAsRead(${notification.id})">
                                <div class="notification-icon-small">
                                    <i class="ph-${getNotificationIcon(notification.type)}"></i>
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
                            <i class="ph-warning"></i>
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
                        } else {
                            notificationBadge.style.display = 'none';
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
                    
                    // Hide notification badge
                    if (notificationBadge) {
                        notificationBadge.style.display = 'none';
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
                <i class="ph-${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info'}"></i>
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
</body>
</html>