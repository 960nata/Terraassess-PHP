<!-- Header -->
<header class="header">
    <div class="header-left">
        <button class="menu-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="logo-text">Terra Assessment</div>
        </div>
    </div>
    <div class="header-right">
        <!-- Notifications -->
        <div class="notification-container">
            <button class="notification-btn" onclick="toggleNotifications()">
                <i class="fas fa-bell"></i>
                <span class="notification-badge" id="notificationBadge">0</span>
            </button>
            
            <!-- Notification Dropdown -->
            <div class="notification-dropdown" id="notificationDropdown" style="display: none;">
                <div class="notification-header">
                    <h4>Notifikasi</h4>
                    <div class="notification-actions">
                        <button class="mark-all-read-btn" onclick="markAllAsRead()">Tandai Semua</button>
                        <a href="{{ route('notifications.index') }}" class="view-all-btn">Lihat Semua</a>
                    </div>
                </div>
                <div class="notification-list" id="notificationList">
                    <div class="notification-loading">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p>Memuat notifikasi...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Profile Dropdown -->
        <div class="user-profile-container">
            <div class="user-profile" onclick="toggleProfileDropdown()">
                <div class="user-avatar">
                    <img src="{{ Auth::user()->gambar ? asset('storage/' . Auth::user()->gambar) : asset('asset/icons/profile-women.svg') }}" 
                         alt="Profile" class="avatar-img">
                </div>
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">Siswa</div>
                </div>
                <i class="fas fa-chevron-down profile-dropdown-arrow"></i>
            </div>
            
            <!-- Profile Dropdown -->
            <div class="profile-dropdown" id="profileDropdown" style="display: none;">
                <a href="{{ route('student.profile') }}" class="profile-dropdown-item">
                    <i class="fas fa-user"></i>
                    <span>Profil</span>
                </a>
                <a href="{{ route('notifications.index') }}" class="profile-dropdown-item">
                    <i class="fas fa-bell"></i>
                    <span>Notifikasi</span>
                </a>
                <div class="profile-dropdown-divider"></div>
                <a href="{{ route('logout') }}" class="profile-dropdown-item logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </div>
</header>
