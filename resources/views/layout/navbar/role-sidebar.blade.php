@php
    $roleId = $roleId ?? Auth()->user()->roles_id;
    $role = $role ?? 'superadmin';
@endphp

<!-- Quick Access -->
<div class="menu-section">
    <div class="menu-section-title">Menu Utama</div>
    
    @if($roleId == 1) {{-- Super Admin --}}
        <a href="{{ route('superadmin.dashboard') }}" class="menu-item {{ Request::is('superadmin/dashboard*') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i>
            <span class="menu-item-text">Dashboard</span>
        </a>
        <a href="{{ route('superadmin.push-notification') }}" class="menu-item {{ Request::is('superadmin/push-notification*') ? 'active' : '' }}">
            <i class="fas fa-bell"></i>
            <span class="menu-item-text">Push Notifikasi</span>
        </a>
    @elseif($roleId == 2) {{-- Admin --}}
        <a href="{{ route('admin.dashboard') }}" class="menu-item {{ Request::is('admin/dashboard*') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i>
            <span class="menu-item-text">Dashboard</span>
        </a>
        <a href="{{ route('admin.push-notification') }}" class="menu-item {{ Request::is('admin/push-notification*') ? 'active' : '' }}">
            <i class="fas fa-bell"></i>
            <span class="menu-item-text">Push Notifikasi</span>
        </a>
    @elseif($roleId == 3) {{-- Teacher --}}
        <a href="{{ route('teacher.dashboard') }}" class="menu-item {{ Request::is('teacher/dashboard*') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i>
            <span class="menu-item-text">Dashboard</span>
        </a>
    @elseif($roleId == 4) {{-- Student --}}
        <a href="{{ route('student.dashboard') }}" class="menu-item {{ Request::is('student/dashboard*') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i>
            <span class="menu-item-text">Dashboard</span>
        </a>
        <a href="{{ route('student.tugas') }}" class="menu-item {{ Request::is('student/tugas*') ? 'active' : '' }}">
            <i class="fas fa-tasks"></i>
            <span class="menu-item-text">Tugas</span>
        </a>
        <a href="{{ route('student.materi') }}" class="menu-item {{ Request::is('student/materi*') ? 'active' : '' }}">
            <i class="fas fa-book"></i>
            <span class="menu-item-text">Materi</span>
        </a>
        <a href="{{ route('student.ujian') }}" class="menu-item {{ Request::is('student/ujian*') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i>
            <span class="menu-item-text">Ujian</span>
        </a>
        <a href="{{ route('student.class-management') }}" class="menu-item {{ Request::is('student/class-management*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span class="menu-item-text">Kelas Saya</span>
        </a>
        <a href="{{ route('student.complaints.index') }}" class="menu-item {{ Request::is('student/complaints*') ? 'active' : '' }}">
            <i class="fas fa-exclamation-triangle"></i>
            <span class="menu-item-text">Pengaduan</span>
        </a>
    @endif
</div>

<!-- Management Section -->
@if($roleId == 1 || $roleId == 2 || $roleId == 3) {{-- Super Admin, Admin & Teacher --}}
    <div class="menu-section">
        <div class="menu-section-title">Manajemen</div>
        
        @if($roleId == 1) {{-- Super Admin only --}}
            <a href="{{ route('superadmin.task-management') }}" class="menu-item {{ Request::is('superadmin/task-management*') ? 'active' : '' }}">
                <i class="fas fa-book"></i>
                <span class="menu-item-text">Manajemen Tugas</span>
            </a>
            <a href="{{ route('superadmin.exam-management') }}" class="menu-item {{ Request::is('superadmin/exam-management*') ? 'active' : '' }}">
                <i class="fas fa-bullseye"></i>
                <span class="menu-item-text">Manajemen Ujian</span>
            </a>
            <a href="{{ route('superadmin.user-management') }}" class="menu-item {{ Request::is('superadmin/user-management*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span class="menu-item-text">Manajemen Pengguna</span>
            </a>
            <a href="{{ route('superadmin.class-management') }}" class="menu-item {{ Request::is('superadmin/class-management*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i>
                <span class="menu-item-text">Manajemen Kelas</span>
            </a>
            <a href="{{ route('superadmin.subject-management') }}" class="menu-item {{ Request::is('superadmin/subject-management*') ? 'active' : '' }}">
                <i class="fas fa-database"></i>
                <span class="menu-item-text">Mata Pelajaran</span>
            </a>
            <a href="{{ route('superadmin.material-management') }}" class="menu-item {{ Request::is('superadmin/material-management*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i>
                <span class="menu-item-text">Manajemen Materi</span>
            </a>
            <a href="{{ route('superadmin.complaints.index') }}" class="menu-item {{ Request::is('superadmin/complaints*') ? 'active' : '' }}">
                <i class="fas fa-exclamation-triangle"></i>
                <span class="menu-item-text">Kelola Pengaduan</span>
            </a>
        @elseif($roleId == 2) {{-- Admin --}}
            <a href="{{ route('admin.task-management') }}" class="menu-item {{ Request::is('admin/task-management*') ? 'active' : '' }}">
                <i class="fas fa-book"></i>
                <span class="menu-item-text">Manajemen Tugas</span>
            </a>
            <a href="{{ route('admin.exam-management') }}" class="menu-item {{ Request::is('admin/exam-management*') ? 'active' : '' }}">
                <i class="fas fa-bullseye"></i>
                <span class="menu-item-text">Manajemen Ujian</span>
            </a>
            <a href="{{ route('superadmin.user-management') }}" class="menu-item {{ Request::is('superadmin/user-management*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span class="menu-item-text">Manajemen Pengguna</span>
            </a>
            <a href="{{ route('admin.class-management') }}" class="menu-item {{ Request::is('admin/class-management*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i>
                <span class="menu-item-text">Manajemen Kelas</span>
            </a>
            <a href="{{ route('admin.subject-management') }}" class="menu-item {{ Request::is('admin/subject-management*') ? 'active' : '' }}">
                <i class="fas fa-database"></i>
                <span class="menu-item-text">Mata Pelajaran</span>
            </a>
            <a href="{{ route('admin.material-management') }}" class="menu-item {{ Request::is('admin/material-management*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i>
                <span class="menu-item-text">Manajemen Materi</span>
            </a>
            <a href="{{ route('admin.complaints.index') }}" class="menu-item {{ Request::is('admin/complaints*') ? 'active' : '' }}">
                <i class="fas fa-exclamation-triangle"></i>
                <span class="menu-item-text">Kelola Pengaduan</span>
            </a>
        @elseif($roleId == 3) {{-- Teacher --}}
            <a href="{{ route('teacher.tasks') }}" class="menu-item {{ Request::is('teacher/tasks*') ? 'active' : '' }}">
                <i class="fas fa-book"></i>
                <span class="menu-item-text">Manajemen Tugas</span>
            </a>
            <a href="{{ route('teacher.exam-management') }}" class="menu-item {{ Request::is('teacher/exam-management*') ? 'active' : '' }}">
                <i class="fas fa-bullseye"></i>
                <span class="menu-item-text">Manajemen Ujian</span>
            </a>
            <a href="{{ route('teacher.material-management') }}" class="menu-item {{ Request::is('teacher/material-management*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i>
                <span class="menu-item-text">Manajemen Materi</span>
            </a>
        @endif
    </div>
@endif

<!-- IoT & Research Section -->
@if($roleId == 1 || $roleId == 2 || $roleId == 3 || $roleId == 4) {{-- All roles --}}
    <div class="menu-section">
        <div class="menu-section-title">IoT & Penelitian</div>
        
        @if($roleId == 1) {{-- Super Admin --}}
            <a href="{{ route('superadmin.esp8266-status') }}" class="menu-item {{ Request::is('superadmin/esp8266-status*') ? 'active' : '' }}">
                <i class="fas fa-microchip"></i>
                <span class="menu-item-text">ESP8266 Status</span>
            </a>
        @elseif($roleId == 2) {{-- Admin --}}
            <a href="{{ route('admin.esp8266-status') }}" class="menu-item {{ Request::is('admin/esp8266-status*') ? 'active' : '' }}">
                <i class="fas fa-microchip"></i>
                <span class="menu-item-text">ESP8266 Status</span>
            </a>
        @elseif($roleId == 3) {{-- Teacher --}}
            <a href="{{ route('teacher.esp8266-status') }}" class="menu-item {{ Request::is('teacher/esp8266-status*') ? 'active' : '' }}">
                <i class="fas fa-microchip"></i>
                <span class="menu-item-text">ESP8266 Status</span>
            </a>
        @elseif($roleId == 4) {{-- Student --}}
            <a href="{{ route('student.esp8266-status') }}" class="menu-item {{ Request::is('student/esp8266-status*') ? 'active' : '' }}">
                <i class="fas fa-microchip"></i>
                <span class="menu-item-text">ESP8266 Status</span>
            </a>
        @endif
    </div>
@endif


<!-- Analytics & Reports -->
@if($roleId == 1 || $roleId == 2 || $roleId == 3) {{-- Super Admin, Admin, Teacher --}}
    <div class="menu-section">
        <div class="menu-section-title">Laporan & Analitik</div>
        
        @if($roleId == 1) {{-- Super Admin --}}
            <a href="{{ route('superadmin.reports') }}" class="menu-item {{ Request::is('superadmin/reports*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span class="menu-item-text">Laporan</span>
            </a>
            <a href="{{ route('superadmin.analytics') }}" class="menu-item {{ Request::is('superadmin/analytics*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i>
                <span class="menu-item-text">Analytics</span>
            </a>
        @elseif($roleId == 2) {{-- Admin --}}
            <a href="{{ route('superadmin.reports') }}" class="menu-item {{ Request::is('superadmin/reports*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span class="menu-item-text">Laporan</span>
            </a>
            <a href="{{ route('admin.analytics') }}" class="menu-item {{ Request::is('admin/analytics*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i>
                <span class="menu-item-text">Analitik</span>
            </a>
        @elseif($roleId == 3) {{-- Teacher --}}
            <a href="{{ route('teacher.reports') }}" class="menu-item {{ Request::is('teacher/reports*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span class="menu-item-text">Laporan</span>
            </a>
            <a href="{{ route('teacher.analytics') }}" class="menu-item {{ Request::is('teacher/analytics*') ? 'active' : '' }}">
                <i class="fas fa-chart-bar"></i>
                <span class="menu-item-text">Analitik</span>
            </a>
        @endif
    </div>
@endif

<!-- Settings Section -->
<div class="menu-section">
    <div class="menu-section-title">Pengaturan</div>
    
    @if($roleId == 1) {{-- Super Admin --}}
        <a href="{{ route('superadmin.settings') }}" class="menu-item {{ Request::is('superadmin/settings*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i>
            <span class="menu-item-text">Pengaturan</span>
        </a>
        <a href="{{ route('superadmin.help') }}" class="menu-item {{ Request::is('superadmin/help*') ? 'active' : '' }}">
            <i class="fas fa-question-circle"></i>
            <span class="menu-item-text">Bantuan</span>
        </a>
    @elseif($roleId == 2) {{-- Admin --}}
        <a href="{{ route('admin.settings') }}" class="menu-item {{ Request::is('admin/settings*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i>
            <span class="menu-item-text">Pengaturan</span>
        </a>
        <a href="{{ route('admin.help') }}" class="menu-item {{ Request::is('admin/help*') ? 'active' : '' }}">
            <i class="fas fa-question-circle"></i>
            <span class="menu-item-text">Bantuan</span>
        </a>
    @elseif($roleId == 3) {{-- Teacher - No Settings/Help as per request --}}
    @elseif($roleId == 4) {{-- Student --}}
        <a href="{{ route('student.profile') }}" class="menu-item {{ Request::is('student/profile*') ? 'active' : '' }}">
            <i class="fas fa-user"></i>
            <span class="menu-item-text">Profile</span>
        </a>
        <a href="{{ route('student.notifications') }}" class="menu-item {{ Request::is('student/notifications*') ? 'active' : '' }}">
            <i class="fas fa-bell"></i>
            <span class="menu-item-text">Notifikasi</span>
        </a>
    @endif
</div>
