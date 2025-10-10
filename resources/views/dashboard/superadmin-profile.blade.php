<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terra Assessment - Profil Super Admin</title>
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
                <a href="{{ route('superadmin.settings') }}" class="menu-item">
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
                <i class="fas fa-user"></i>
                Profil Super Admin
            </h1>
            <p class="page-description">Kelola informasi profil dan data pribadi Anda</p>
        </div>

        <div class="profile-container-main">
            <!-- Profile Header -->
            <div class="profile-header">
                <div class="profile-avatar-large">
                    <div class="avatar-circle">
                        {{ substr($user->name ?? 'SA', 0, 2) }}
                    </div>
                    <button class="change-avatar-btn">
                        <i class="fas fa-camera"></i>
                        <span>Ubah Foto</span>
                    </button>
                </div>
                <div class="profile-info">
                    <h2 class="profile-name">{{ $user->name ?? 'Super Admin' }}</h2>
                    <p class="profile-role">Super Administrator</p>
                    <p class="profile-email">{{ $user->email ?? 'superadmin@terraassessment.com' }}</p>
                    <div class="profile-status">
                        <span class="status-badge active">
                            <i class="fas fa-circle"></i>
                            Aktif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="profile-content">
                <div class="profile-tabs">
                    <button class="tab-button active" onclick="switchTab('personal')">
                        <i class="fas fa-user"></i>
                        Informasi Pribadi
                    </button>
                    <button class="tab-button" onclick="switchTab('security')">
                        <i class="fas fa-shield-alt"></i>
                        Keamanan
                    </button>
                    <button class="tab-button" onclick="switchTab('preferences')">
                        <i class="fas fa-cog"></i>
                        Preferensi
                    </button>
                </div>

                <!-- Personal Information Tab -->
                <div class="tab-content active" id="personal-tab">
                    <div class="form-section">
                        <h3 class="section-title">Informasi Dasar</h3>
                        <form class="profile-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name">Nama Lengkap</label>
                                    <input type="text" id="name" name="name" value="{{ $user->name ?? 'Super Admin' }}" class="form-input">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" value="{{ $user->email ?? 'superadmin@terraassessment.com' }}" class="form-input">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="phone">Nomor Telepon</label>
                                    <input type="tel" id="phone" name="phone" value="+62 812 3456 7890" class="form-input">
                                </div>
                                <div class="form-group">
                                    <label for="position">Posisi</label>
                                    <input type="text" id="position" name="position" value="Super Administrator" class="form-input" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="bio">Bio</label>
                                <textarea id="bio" name="bio" class="form-textarea" rows="4" placeholder="Ceritakan tentang diri Anda...">Sebagai Super Administrator, saya bertanggung jawab untuk mengelola seluruh sistem Terra Assessment dan memastikan keamanan serta performa optimal.</textarea>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn btn-secondary">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Security Tab -->
                <div class="tab-content" id="security-tab">
                    <div class="form-section">
                        <h3 class="section-title">Keamanan Akun</h3>
                        <div class="security-item">
                            <div class="security-info">
                                <h4>Kata Sandi</h4>
                                <p>Terakhir diubah 30 hari yang lalu</p>
                            </div>
                            <button class="btn btn-outline">Ubah Kata Sandi</button>
                        </div>
                        <div class="security-item">
                            <div class="security-info">
                                <h4>Autentikasi Dua Faktor</h4>
                                <p>Tambahkan lapisan keamanan ekstra</p>
                            </div>
                            <button class="btn btn-outline">Aktifkan 2FA</button>
                        </div>
                        <div class="security-item">
                            <div class="security-info">
                                <h4>Sesi Aktif</h4>
                                <p>Kelola perangkat yang terhubung</p>
                            </div>
                            <button class="btn btn-outline">Lihat Sesi</button>
                        </div>
                    </div>
                </div>

                <!-- Preferences Tab -->
                <div class="tab-content" id="preferences-tab">
                    <div class="form-section">
                        <h3 class="section-title">Preferensi Sistem</h3>
                        <div class="preference-item">
                            <div class="preference-info">
                                <h4>Bahasa</h4>
                                <p>Pilih bahasa antarmuka</p>
                            </div>
                            <select class="form-select">
                                <option value="id">Bahasa Indonesia</option>
                                <option value="en">English</option>
                            </select>
                        </div>
                        <div class="preference-item">
                            <div class="preference-info">
                                <h4>Zona Waktu</h4>
                                <p>Atur zona waktu untuk tampilan</p>
                            </div>
                            <select class="form-select">
                                <option value="WIB">WIB (UTC+7)</option>
                                <option value="WITA">WITA (UTC+8)</option>
                                <option value="WIT">WIT (UTC+9)</option>
                            </select>
                        </div>
                        <div class="preference-item">
                            <div class="preference-info">
                                <h4>Notifikasi Email</h4>
                                <p>Terima notifikasi melalui email</p>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
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

        // Tab switching functionality
        function switchTab(tabName) {
            // Remove active class from all tabs and content
            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            // Add active class to selected tab and content
            document.querySelector(`[onclick="switchTab('${tabName}')"]`).classList.add('active');
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

