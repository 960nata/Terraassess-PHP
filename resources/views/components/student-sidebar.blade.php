<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-menu">
        <div class="menu-section">
            <div class="menu-section-title">Menu Utama</div>
            <a href="{{ route('student.dashboard') }}" class="menu-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span class="menu-item-text">Dashboard</span>
            </a>
            <a href="{{ route('student.tugas') }}" class="menu-item {{ request()->routeIs('student.tugas*') ? 'active' : '' }}">
                <i class="fas fa-tasks"></i>
                <span class="menu-item-text">Tugas</span>
            </a>
            <a href="{{ route('student.materi') }}" class="menu-item {{ request()->routeIs('student.materi*') ? 'active' : '' }}">
                <i class="fas fa-book"></i>
                <span class="menu-item-text">Materi</span>
            </a>
            <a href="{{ route('student.ujian') }}" class="menu-item {{ request()->routeIs('student.ujian*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i>
                <span class="menu-item-text">Ujian</span>
            </a>
        </div>

        <div class="menu-section">
            <div class="menu-section-title">Kelas & Pembelajaran</div>
            <a href="{{ route('student.class-management') }}" class="menu-item {{ request()->routeIs('student.class-management*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span class="menu-item-text">Kelas Saya</span>
            </a>
            <a href="{{ route('student.iot') }}" class="menu-item {{ request()->routeIs('student.iot*') ? 'active' : '' }}">
                <i class="fas fa-microchip"></i>
                <span class="menu-item-text">Penelitian IoT</span>
            </a>
        </div>

        <div class="menu-section">
            <div class="menu-section-title">Pengaturan</div>
            <a href="{{ route('student.profile') }}" class="menu-item {{ request()->routeIs('student.profile*') ? 'active' : '' }}">
                <i class="fas fa-user"></i>
                <span class="menu-item-text">Profile</span>
            </a>
            <a href="{{ route('notifications.index') }}" class="menu-item {{ request()->routeIs('notifications.index*') ? 'active' : '' }}">
                <i class="fas fa-bell"></i>
                <span class="menu-item-text">Notifikasi</span>
            </a>
        </div>
    </div>
</nav>
