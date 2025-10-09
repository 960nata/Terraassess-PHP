<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terra Assessment - Pengaturan Super Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/superadmin-dashboard.css') }}" rel="stylesheet">

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

