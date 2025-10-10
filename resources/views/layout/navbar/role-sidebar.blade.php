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
        <a href="{{ route('admin.iot-dashboard') }}" class="menu-item {{ Request::is('admin/iot-dashboard*') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i>
            <span class="menu-item-text">IoT Dashboard</span>
        </a>
        <a href="{{ route('admin.iot-management') }}" class="menu-item {{ Request::is('admin/iot-management*') ? 'active' : '' }}">
            <i class="fas fa-wifi"></i>
            <span class="menu-item-text">Manajemen IoT</span>
        </a>
    @elseif($roleId == 3) {{-- Teacher --}}
        <a href="{{ route('teacher.dashboard') }}" class="menu-item {{ Request::is('teacher/dashboard*') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i>
            <span class="menu-item-text">Dashboard</span>
        </a>
        <a href="{{ route('teacher.push-notification') }}" class="menu-item {{ Request::is('teacher/push-notification*') ? 'active' : '' }}">
            <i class="fas fa-bell"></i>
            <span class="menu-item-text">Push Notifikasi</span>
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
            <a href="{{ route('superadmin.iot-dashboard') }}" class="menu-item {{ Request::is('superadmin/iot-dashboard*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span class="menu-item-text">IoT Dashboard</span>
            </a>
            <a href="{{ route('superadmin.iot-tasks') }}" class="menu-item {{ Request::is('superadmin/iot-tasks*') ? 'active' : '' }}">
                <i class="fas fa-tasks"></i>
                <span class="menu-item-text">Tugas IoT</span>
            </a>
            <a href="{{ route('superadmin.iot-research') }}" class="menu-item {{ Request::is('superadmin/iot-research*') ? 'active' : '' }}">
                <i class="fas fa-flask"></i>
                <span class="menu-item-text">Penelitian IoT</span>
            </a>
        @elseif($roleId == 2) {{-- Admin --}}
            <a href="{{ route('iot.tugas') }}" class="menu-item {{ Request::is('iot/tugas*') ? 'active' : '' }}">
                <i class="fas fa-server"></i>
                <span class="menu-item-text">Tugas IoT</span>
            </a>
            <a href="{{ route('iot.research-projects') }}" class="menu-item {{ Request::is('iot/research-projects*') ? 'active' : '' }}">
                <i class="fas fa-wave-square"></i>
                <span class="menu-item-text">Penelitian IoT</span>
            </a>
        @elseif($roleId == 3) {{-- Teacher --}}
            <a href="{{ route('teacher.iot.dashboard') }}" class="menu-item {{ Request::is('teacher/iot/dashboard*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span class="menu-item-text">IoT Dashboard</span>
            </a>
            <a href="{{ route('iot.tugas') }}" class="menu-item {{ Request::is('iot/tugas*') ? 'active' : '' }}">
                <i class="fas fa-tasks"></i>
                <span class="menu-item-text">Tugas IoT</span>
            </a>
            <a href="{{ route('teacher.iot.research-projects') }}" class="menu-item {{ Request::is('teacher/iot/research-projects*') ? 'active' : '' }}">
                <i class="fas fa-flask"></i>
                <span class="menu-item-text">Penelitian IoT</span>
            </a>
        @elseif($roleId == 4) {{-- Student --}}
            <a href="{{ route('student.iot') }}" class="menu-item {{ Request::is('student/iot*') ? 'active' : '' }}">
                <i class="fas fa-microchip"></i>
                <span class="menu-item-text">Penelitian IoT</span>
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
    @elseif($roleId == 3) {{-- Teacher --}}
        <a href="{{ route('teacher.settings') }}" class="menu-item {{ Request::is('teacher/settings*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i>
            <span class="menu-item-text">Pengaturan</span>
        </a>
        <a href="{{ route('teacher.help') }}" class="menu-item {{ Request::is('teacher/help*') ? 'active' : '' }}">
            <i class="fas fa-question-circle"></i>
            <span class="menu-item-text">Bantuan</span>
        </a>
    @elseif($roleId == 4) {{-- Student --}}
        <a href="{{ route('student.profile') }}" class="menu-item {{ Request::is('student/profile*') ? 'active' : '' }}">
            <i class="fas fa-user"></i>
            <span class="menu-item-text">Profile</span>
        </a>
        <a href="{{ route('notifications.index') }}" class="menu-item {{ Request::is('notifications*') ? 'active' : '' }}">
            <i class="fas fa-bell"></i>
            <span class="menu-item-text">Notifikasi</span>
        </a>
    @endif
</div>
