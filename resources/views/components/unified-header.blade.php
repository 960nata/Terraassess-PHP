@php
    $roleId = $roleId ?? Auth()->user()->roles_id;
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
        2 => 'admin.settings',
        3 => 'teacher.settings', 
        4 => 'student.settings'
    ];
    
    $profileRoute = route($profileRoutes[$roleId] ?? 'superadmin.profile');
    $settingsRoute = route($settingsRoutes[$roleId] ?? 'superadmin.settings');
@endphp

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
            <div class="logo-text">Terra Assessment</div>
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

        <!-- Profile Dropdown -->
        <div class="profile-dropdown-container">
            <button class="profile-dropdown-button" onclick="toggleProfile()">
                <div class="profile-avatar">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                    @else
                        {{ $roleInitial }}
                    @endif
                </div>
                <div class="profile-info">
                    <div class="profile-name">{{ $user->name ?? 'User' }}</div>
                    <div class="profile-role">{{ $roleTitle }}</div>
                </div>
                <i class="fas fa-chevron-down profile-arrow"></i>
            </button>
            
            <!-- Profile Dropdown Menu -->
            <div class="profile-dropdown-menu hidden" id="profileDropdown">
                <div class="profile-dropdown-header">
                    <div class="profile-dropdown-user-info">
                        <div class="profile-dropdown-avatar">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                            @else
                                {{ $roleInitial }}
                            @endif
                        </div>
                        <div class="profile-dropdown-details">
                            <h6>{{ $user->name ?? 'User' }}</h6>
                            <span>{{ $user->email ?? '' }}</span>
                        </div>
                    </div>
                </div>
                <div class="profile-dropdown-items">
                    <a href="{{ $profileRoute }}" class="profile-dropdown-item">
                        <i class="fas fa-user"></i>
                        Profil
                    </a>
                    @if($roleId != 2)
                    <a href="{{ $settingsRoute }}" class="profile-dropdown-item">
                        <i class="fas fa-cog"></i>
                        Pengaturan
                    </a>
                    @endif
                    <div class="profile-dropdown-divider"></div>
                    <a href="{{ route('logout.get') }}" class="profile-dropdown-item logout">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
