<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terra Assessment - Profil Admin</title>
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
                    <i class="fas fa-user-shield"></i>
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
                                <div class="notification-title">Sistem Aktif</div>
                                <div class="notification-message">Sistem TerraAssessment berjalan dengan baik</div>
                                <div class="notification-time">Baru saja</div>
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
                    <div class="user-avatar">{{ substr($user->name ?? 'A', 0, 2) }}</div>
                    <div class="user-info">
                        <div class="user-name">{{ $user->name ?? 'Admin' }}</div>
                        <div class="user-role">Admin</div>
                    </div>
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </div>
                <div class="profile-dropdown" id="profileDropdown">
                    <div class="profile-dropdown-header">
                        <div class="profile-dropdown-avatar">{{ substr($user->name ?? 'A', 0, 2) }}</div>
                        <div class="profile-dropdown-info">
                            <div class="profile-dropdown-name">{{ $user->name ?? 'Admin' }}</div>
                            <div class="profile-dropdown-role">Admin</div>
                        </div>
                    </div>
                    <div class="profile-dropdown-menu">
                        <a href="{{ route('admin.profile') }}" class="profile-dropdown-item">
                            <i class="fas fa-user"></i>
                            <span>Profil</span>
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
                <a href="{{ route('admin.dashboard') }}" class="menu-item">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="menu-item-text">Dashboard</span>
                </a>
                <a href="{{ route('admin.push-notification') }}" class="menu-item">
                    <i class="fas fa-bell"></i>
                    <span class="menu-item-text">Push Notifikasi</span>
                </a>
                <a href="{{ route('admin.iot-management') }}" class="menu-item">
                    <i class="fas fa-wifi"></i>
                    <span class="menu-item-text">Manajemen IoT</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Manajemen</div>
                <a href="{{ route('superadmin.user-management') }}" class="menu-item">
                    <i class="fas fa-users"></i>
                    <span class="menu-item-text">Manajemen Pengguna</span>
                </a>
                <a href="{{ route('superadmin.class-management') }}" class="menu-item">
                    <i class="fas fa-school"></i>
                    <span class="menu-item-text">Manajemen Kelas</span>
                </a>
                <a href="{{ route('superadmin.subject-management') }}" class="menu-item">
                    <i class="fas fa-book-open"></i>
                    <span class="menu-item-text">Mata Pelajaran</span>
                </a>
                <a href="{{ route('superadmin.material-management') }}" class="menu-item">
                    <i class="fas fa-book"></i>
                    <span class="menu-item-text">Materi</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Aktivitas</div>
                <a href="{{ route('superadmin.task-management') }}" class="menu-item">
                    <i class="fas fa-clipboard-text"></i>
                    <span class="menu-item-text">Manajemen Tugas</span>
                </a>
                <a href="{{ route('superadmin.exam-management') }}" class="menu-item">
                    <i class="fas fa-exam"></i>
                    <span class="menu-item-text">Manajemen Ujian</span>
                </a>
                <a href="{{ route('notifications.user') }}" class="menu-item">
                    <i class="fas fa-bell"></i>
                    <span class="menu-item-text">Notifikasi</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Bantuan</div>
                <a href="{{ route('admin.help') }}" class="menu-item">
                    <i class="fas fa-question-circle"></i>
                    <span class="menu-item-text">Bantuan</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="content-header">
            <div class="content-title">
                <h1>Profil Admin</h1>
                <p>Kelola informasi profil Anda</p>
            </div>
        </div>

        <div class="content-body">
            <div class="profile-container-main">
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="profile-avatar-container">
                            <div class="profile-avatar">
                                @if($user->gambar)
                                    <img src="{{ asset('storage/' . $user->gambar) }}" alt="Profile Photo" class="profile-avatar-img">
                                @else
                                    {{ substr($user->name ?? 'A', 0, 2) }}
                                @endif
                            </div>
                            <button class="change-photo-btn" onclick="document.getElementById('photoInput').click()">
                                <i class="fas fa-camera"></i>
                            </button>
                            <input type="file" id="photoInput" accept="image/*" style="display: none;" onchange="uploadPhoto(this)">
                        </div>
                        <div class="profile-info">
                            <h2 class="profile-name">{{ $user->name ?? 'Admin' }}</h2>
                            <p class="profile-role">Admin</p>
                            <p class="profile-email">{{ $user->email ?? 'admin@terraassessment.com' }}</p>
                        </div>
                    </div>

                    <div class="profile-content">
                        <form action="{{ route('admin.profile.update') }}" method="POST" class="profile-form">
                            @csrf
                            <div class="form-group">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" id="name" name="name" class="form-input" value="{{ $user->name ?? '' }}" required>
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-input" value="{{ $user->email ?? '' }}" required>
                            </div>

                            <div class="form-group">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="text" id="phone" name="phone" class="form-input" value="{{ $user->phone ?? '' }}">
                            </div>

                            <div class="form-group">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea id="bio" name="bio" class="form-textarea" rows="4" placeholder="Ceritakan tentang diri Anda...">{{ $user->bio ?? '' }}</textarea>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i>
                                    Simpan Perubahan
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="fas fa-undo"></i>
                                    Reset
                                </button>
                            </div>
                        </form>
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
            const mobileOverlay = document.getElementById('mobileOverlay');
            const mainContent = document.querySelector('.main-content');
            
            if (window.innerWidth <= 1024) {
                sidebar.classList.toggle('collapsed');
                if (mobileOverlay) mobileOverlay.classList.toggle('active');
                if (mainContent) mainContent.classList.toggle('sidebar-open');
            } else {
                sidebar.classList.toggle('collapsed');
            }
        }

        // Close sidebar on mobile
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');
            const mainContent = document.querySelector('.main-content');
            
            sidebar.classList.remove('collapsed');
            if (mobileOverlay) mobileOverlay.classList.remove('active');
            if (mainContent) mainContent.classList.remove('sidebar-open');
        }

        // Toggle profile dropdown
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('active');
        }

        // Toggle notification dropdown
        function toggleNotificationDropdown() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('active');
        }

        // Mark all notifications as read
        function markAllAsRead() {
            const unreadItems = document.querySelectorAll('.notification-item.unread');
            unreadItems.forEach(item => {
                item.classList.remove('unread');
            });
            
            const badge = document.querySelector('.notification-badge');
            if (badge) {
                badge.style.display = 'none';
            }
        }

        // Upload photo
        function uploadPhoto(input) {
            if (input.files && input.files[0]) {
                const formData = new FormData();
                formData.append('photo', input.files[0]);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                fetch('{{ route("admin.profile.upload-photo") }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Gagal mengupload foto: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengupload foto');
                });
            }
        }

        // Reset form
        function resetForm() {
            document.querySelector('.profile-form').reset();
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const profileDropdown = document.getElementById('profileDropdown');
            const notificationDropdown = document.getElementById('notificationDropdown');
            const profileContainer = document.querySelector('.profile-container');
            const notificationContainer = document.querySelector('.notification-container');

            if (!profileContainer.contains(event.target)) {
                profileDropdown.classList.remove('active');
            }

            if (!notificationContainer.contains(event.target)) {
                notificationDropdown.classList.remove('active');
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');
            const mainContent = document.querySelector('.main-content');
            
            if (window.innerWidth > 1024) {
                sidebar.classList.remove('collapsed');
                if (mobileOverlay) mobileOverlay.classList.remove('active');
                if (mainContent) mainContent.classList.remove('sidebar-open');
            }
        });
    </script>
</body>
</html>


