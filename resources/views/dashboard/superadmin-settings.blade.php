<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terra Assessment - Pengaturan Super Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/superadmin-dashboard.css') }}" rel="stylesheet">
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
                <div class="logo-text">Terra Assessment</div>
            </div>
        </div>
        <div class="header-right">
            <!-- Notification Dropdown -->
            <div class="notification-container">
                <button class="notification-icon" onclick="toggleNotificationDropdown()">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
                <div class="notification-dropdown" id="notificationDropdown">
                    <div class="notification-header">
                        <h3>Notifikasi</h3>
                        <button class="mark-all-read" onclick="markAllAsRead()">Tandai Semua Dibaca</button>
                    </div>
                    <div class="notification-list">
                        <div class="notification-item unread">
                            <div class="notification-icon-small">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-title">Admin Baru Ditambahkan</div>
                                <div class="notification-message">Admin baru "John Doe" telah ditambahkan ke sistem</div>
                                <div class="notification-time">2 menit yang lalu</div>
                            </div>
                        </div>
                        <div class="notification-item unread">
                            <div class="notification-icon-small">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-title">Sistem Maintenance</div>
                                <div class="notification-message">Jadwal maintenance sistem akan dilakukan malam ini</div>
                                <div class="notification-time">1 jam yang lalu</div>
                            </div>
                        </div>
                        <div class="notification-item">
                            <div class="notification-icon-small">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-title">Backup Berhasil</div>
                                <div class="notification-message">Backup data harian telah selesai</div>
                                <div class="notification-time">3 jam yang lalu</div>
                            </div>
                        </div>
                    </div>
                    <div class="notification-footer">
                        <a href="#" class="view-all-notifications">Lihat Semua Notifikasi</a>
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="profile-container">
                <div class="user-profile" onclick="toggleProfileDropdown()">
                    <div class="user-avatar">{{ substr($user->name ?? 'SA', 0, 2) }}</div>
                    <div class="user-info">
                        <div class="user-name">{{ $user->name ?? 'Super Admin' }}</div>
                        <div class="user-role">Super Admin</div>
                    </div>
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </div>
                <div class="profile-dropdown" id="profileDropdown">
                    <div class="profile-dropdown-header">
                        <div class="profile-dropdown-avatar">{{ substr($user->name ?? 'SA', 0, 2) }}</div>
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
                <a href="{{ route('superadmin.dashboard') }}" class="menu-item">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="menu-item-text">Dashboard</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-bell"></i>
                    <span class="menu-item-text">Push Notifikasi</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-wifi"></i>
                    <span class="menu-item-text">Manajemen IoT</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Manajemen</div>
                <a href="#" class="menu-item">
                    <i class="fas fa-book"></i>
                    <span class="menu-item-text">Manajemen Tugas</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-bullseye"></i>
                    <span class="menu-item-text">Manajemen Ujian</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-user-shield"></i>
                    <span class="menu-item-text">Manajemen Admin</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-users"></i>
                    <span class="menu-item-text">Manajemen Pengguna</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-chart-bar"></i>
                    <span class="menu-item-text">Manajemen Kelas</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-database"></i>
                    <span class="menu-item-text">Mata Pelajaran</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="fas fa-file-alt"></i>
                    <span class="menu-item-text">Manajemen Materi</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Analitik</div>
                <a href="#" class="menu-item">
                    <i class="fas fa-chart-line"></i>
                    <span class="menu-item-text">Laporan</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Pengaturan</div>
                <a href="{{ route('superadmin.settings') }}" class="menu-item active">
                    <i class="fas fa-cog"></i>
                    <span class="menu-item-text">Pengaturan</span>
                </a>
                <a href="#" class="menu-item">
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
                <i class="fas fa-cog"></i>
                Pengaturan Super Admin
            </h1>
            <p class="page-description">Kelola konfigurasi sistem dan preferensi aplikasi</p>
        </div>

        <div class="settings-container">
            <!-- Settings Navigation -->
            <div class="settings-nav">
                <button class="settings-nav-item active" onclick="switchSettingsTab('general')">
                    <i class="fas fa-cog"></i>
                    <span>Umum</span>
                </button>
                <button class="settings-nav-item" onclick="switchSettingsTab('security')">
                    <i class="fas fa-shield-alt"></i>
                    <span>Keamanan</span>
                </button>
                <button class="settings-nav-item" onclick="switchSettingsTab('notifications')">
                    <i class="fas fa-bell"></i>
                    <span>Notifikasi</span>
                </button>
                <button class="settings-nav-item" onclick="switchSettingsTab('system')">
                    <i class="fas fa-server"></i>
                    <span>Sistem</span>
                </button>
            </div>

            <!-- Settings Content -->
            <div class="settings-content">
                <!-- General Settings -->
                <div class="settings-tab active" id="general-tab">
                    <div class="settings-section">
                        <h3 class="section-title">Pengaturan Umum</h3>
                        <form class="settings-form">
                            <div class="form-group">
                                <label for="site_name">Nama Situs</label>
                                <input type="text" id="site_name" name="site_name" value="Terra Assessment" class="form-input">
                            </div>
                            <div class="form-group">
                                <label for="site_description">Deskripsi Situs</label>
                                <textarea id="site_description" name="site_description" class="form-textarea" rows="3">Platform pembelajaran digital terintegrasi dengan IoT untuk pendidikan modern</textarea>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="timezone">Zona Waktu</label>
                                    <select id="timezone" name="timezone" class="form-select">
                                        <option value="WIB">WIB (UTC+7)</option>
                                        <option value="WITA">WITA (UTC+8)</option>
                                        <option value="WIT">WIT (UTC+9)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="language">Bahasa</label>
                                    <select id="language" name="language" class="form-select">
                                        <option value="id">Bahasa Indonesia</option>
                                        <option value="en">English</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn btn-secondary">Reset</button>
                                <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="settings-tab" id="security-tab">
                    <div class="settings-section">
                        <h3 class="section-title">Pengaturan Keamanan</h3>
                        
                        <div class="security-card">
                            <div class="security-header">
                                <h4>Kata Sandi</h4>
                                <span class="security-status">Aktif</span>
                            </div>
                            <p class="security-description">Terakhir diubah 30 hari yang lalu</p>
                            <button class="btn btn-outline">Ubah Kata Sandi</button>
                        </div>

                        <div class="security-card">
                            <div class="security-header">
                                <h4>Autentikasi Dua Faktor</h4>
                                <span class="security-status inactive">Tidak Aktif</span>
                            </div>
                            <p class="security-description">Tambahkan lapisan keamanan ekstra untuk akun Anda</p>
                            <button class="btn btn-primary">Aktifkan 2FA</button>
                        </div>

                        <div class="security-card">
                            <div class="security-header">
                                <h4>Sesi Aktif</h4>
                                <span class="security-status">3 Sesi</span>
                            </div>
                            <p class="security-description">Kelola perangkat yang terhubung ke akun Anda</p>
                            <button class="btn btn-outline">Kelola Sesi</button>
                        </div>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="settings-tab" id="notifications-tab">
                    <div class="settings-section">
                        <h3 class="section-title">Pengaturan Notifikasi</h3>
                        
                        <div class="notification-group">
                            <h4>Email Notifications</h4>
                            <div class="notification-item">
                                <div class="notification-info">
                                    <h5>Notifikasi Sistem</h5>
                                    <p>Terima notifikasi tentang perubahan sistem</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" checked>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                            <div class="notification-item">
                                <div class="notification-info">
                                    <h5>Notifikasi Keamanan</h5>
                                    <p>Terima notifikasi tentang aktivitas keamanan</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" checked>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                            <div class="notification-item">
                                <div class="notification-info">
                                    <h5>Laporan Harian</h5>
                                    <p>Terima laporan aktivitas harian</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox">
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>

                        <div class="notification-group">
                            <h4>Push Notifications</h4>
                            <div class="notification-item">
                                <div class="notification-info">
                                    <h5>Notifikasi Real-time</h5>
                                    <p>Terima notifikasi langsung di browser</p>
                                </div>
                                <label class="toggle-switch">
                                    <input type="checkbox" checked>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Settings -->
                <div class="settings-tab" id="system-tab">
                    <div class="settings-section">
                        <h3 class="section-title">Pengaturan Sistem</h3>
                        
                        <div class="system-card">
                            <div class="system-header">
                                <h4>Maintenance Mode</h4>
                                <span class="system-status">Tidak Aktif</span>
                            </div>
                            <p class="system-description">Aktifkan mode maintenance untuk perawatan sistem</p>
                            <button class="btn btn-outline">Kelola Maintenance</button>
                        </div>

                        <div class="system-card">
                            <div class="system-header">
                                <h4>Backup Otomatis</h4>
                                <span class="system-status">Aktif</span>
                            </div>
                            <p class="system-description">Backup otomatis setiap hari pada pukul 02:00 WIB</p>
                            <button class="btn btn-outline">Ubah Jadwal</button>
                        </div>

                        <div class="system-card">
                            <div class="system-header">
                                <h4>Log Sistem</h4>
                                <span class="system-status">Aktif</span>
                            </div>
                            <p class="system-description">Mencatat semua aktivitas sistem untuk audit</p>
                            <button class="btn btn-outline">Lihat Log</button>
                        </div>

                        <div class="system-card danger">
                            <div class="system-header">
                                <h4>Reset Sistem</h4>
                                <span class="system-status danger">Berbahaya</span>
                            </div>
                            <p class="system-description">Reset semua pengaturan ke default (TIDAK DAPAT DIBATALKAN)</p>
                            <button class="btn btn-danger">Reset Sistem</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/superadmin-dashboard.js') }}"></script>
    
    <script>
        // Toggle sidebar for mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        }

        // Toggle notification dropdown
        function toggleNotificationDropdown() {
            const dropdown = document.getElementById('notificationDropdown');
            const profileDropdown = document.getElementById('profileDropdown');
            
            // Close profile dropdown if open
            profileDropdown.classList.remove('active');
            
            // Toggle notification dropdown
            dropdown.classList.toggle('active');
        }

        // Toggle profile dropdown
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            const notificationDropdown = document.getElementById('notificationDropdown');
            
            // Close notification dropdown if open
            notificationDropdown.classList.remove('active');
            
            // Toggle profile dropdown
            dropdown.classList.toggle('active');
        }

        // Mark all notifications as read
        function markAllAsRead() {
            const unreadItems = document.querySelectorAll('.notification-item.unread');
            unreadItems.forEach(item => {
                item.classList.remove('unread');
            });
            
            // Update badge count
            const badge = document.querySelector('.notification-badge');
            badge.textContent = '0';
            badge.style.display = 'none';
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

        // Settings tab switching functionality
        function switchSettingsTab(tabName) {
            // Remove active class from all nav items and tabs
            document.querySelectorAll('.settings-nav-item').forEach(item => item.classList.remove('active'));
            document.querySelectorAll('.settings-tab').forEach(tab => tab.classList.remove('active'));
            
            // Add active class to selected nav item and tab
            document.querySelector(`[onclick="switchSettingsTab('${tabName}')"]`).classList.add('active');
            document.getElementById(`${tabName}-tab`).classList.add('active');
        }

        // Mobile responsive behavior
        function handleMobileDropdowns() {
            const notificationDropdown = document.getElementById('notificationDropdown');
            const profileDropdown = document.getElementById('profileDropdown');
            
            if (window.innerWidth <= 768) {
                // On mobile, make dropdowns full-width and slide from right
                notificationDropdown.classList.add('mobile-dropdown');
                profileDropdown.classList.add('mobile-dropdown');
            } else {
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

