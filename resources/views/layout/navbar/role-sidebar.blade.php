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
        <a href="{{ route('superadmin.iot-management') }}" class="menu-item {{ Request::is('superadmin/iot-management*') ? 'active' : '' }}">
            <i class="fas fa-wifi"></i>
            <span class="menu-item-text">Manajemen IoT</span>
        </a>
        <a href="{{ route('iot.tugas') }}" class="menu-item {{ Request::is('iot/tugas*') ? 'active' : '' }}">
            <i class="fas fa-server"></i>
            <span class="menu-item-text">Tugas IoT</span>
        </a>
        <a href="{{ route('iot.research-projects') }}" class="menu-item {{ Request::is('iot/research-projects*') ? 'active' : '' }}">
            <i class="fas fa-wave-square"></i>
            <span class="menu-item-text">Penelitian IoT</span>
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
        <a href="{{ route('admin.iot-management') }}" class="menu-item {{ Request::is('admin/iot-management*') ? 'active' : '' }}">
            <i class="fas fa-wifi"></i>
            <span class="menu-item-text">Manajemen IoT</span>
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
        <a href="{{ route('student.tasks') }}" class="menu-item {{ Request::is('student/tasks*') ? 'active' : '' }}">
            <i class="fas fa-tasks"></i>
            <span class="menu-item-text">Tugas Saya</span>
        </a>
        <a href="{{ route('student.exams') }}" class="menu-item {{ Request::is('student/exams*') ? 'active' : '' }}">
            <i class="fas fa-clipboard-check"></i>
            <span class="menu-item-text">Ujian Saya</span>
        </a>
        <a href="{{ route('student.materials') }}" class="menu-item {{ Request::is('student/materials*') ? 'active' : '' }}">
            <i class="fas fa-book"></i>
            <span class="menu-item-text">Materi Saya</span>
        </a>
        <a href="{{ route('student.iot') }}" class="menu-item {{ Request::is('student/iot*') ? 'active' : '' }}">
            <i class="fas fa-microchip"></i>
            <span class="menu-item-text">IoT Projects</span>
        </a>
    @endif
</div>

@if($roleId == 4) {{-- Student --}}
<div class="menu-section">
    <div class="menu-section-title">IoT & Data</div>
    <a href="{{ route('student.iot-data') }}" class="menu-item {{ Request::is('student/iot-data*') ? 'active' : '' }}">
        <i class="fas fa-database"></i>
        <span class="menu-item-text">Data IoT</span>
    </a>
</div>

<div class="menu-section">
    <div class="menu-section-title">Pembelajaran</div>
    <a href="{{ route('student.grades') }}" class="menu-item {{ Request::is('student/grades*') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i>
        <span class="menu-item-text">Nilai Saya</span>
    </a>
    <a href="{{ route('student.schedule') }}" class="menu-item {{ Request::is('student/schedule*') ? 'active' : '' }}">
        <i class="fas fa-calendar-alt"></i>
        <span class="menu-item-text">Jadwal</span>
    </a>
    <a href="{{ route('student.assignments') }}" class="menu-item {{ Request::is('student/assignments*') ? 'active' : '' }}">
        <i class="fas fa-clipboard-list"></i>
        <span class="menu-item-text">Penugasan</span>
    </a>
    <a href="{{ route('student.progress') }}" class="menu-item {{ Request::is('student/progress*') ? 'active' : '' }}">
        <i class="fas fa-chart-pie"></i>
        <span class="menu-item-text">Progress</span>
    </a>
</div>
@endif

<!-- Management Section -->
@if($roleId == 1 || $roleId == 2) {{-- Super Admin & Admin --}}
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
        @else {{-- Admin --}}
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
        @endif
    </div>
@endif

<!-- IoT & Research Section -->
@if($roleId == 1 || $roleId == 2 || $roleId == 3 || $roleId == 4) {{-- All roles --}}
    <div class="menu-section">
        <div class="menu-section-title">IoT & Penelitian</div>
        
        @if($roleId == 1) {{-- Super Admin --}}
            <a href="{{ route('iot.tugas') }}" class="menu-item {{ Request::is('iot/tugas*') ? 'active' : '' }}">
                <i class="fas fa-server"></i>
                <span class="menu-item-text">Tugas IoT</span>
            </a>
            <a href="{{ route('iot.research-projects') }}" class="menu-item {{ Request::is('iot/research-projects*') ? 'active' : '' }}">
                <i class="fas fa-wave-square"></i>
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
            <a href="{{ route('teacher.iot.dashboard') }}" class="menu-item {{ Request::is('teacher/iot*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span class="menu-item-text">IoT Dashboard</span>
            </a>
            <a href="{{ route('teacher.iot.devices') }}" class="menu-item {{ Request::is('teacher/iot/devices*') ? 'active' : '' }}">
                <i class="fas fa-device-mobile"></i>
                <span class="menu-item-text">Devices</span>
            </a>
            <a href="{{ route('teacher.iot.sensor-data') }}" class="menu-item {{ Request::is('teacher/iot/sensor-data*') ? 'active' : '' }}">
                <i class="fas fa-thermometer"></i>
                <span class="menu-item-text">Sensor Data</span>
            </a>
        @elseif($roleId == 4) {{-- Student --}}
            <a href="{{ route('student.iot') }}" class="menu-item {{ Request::is('student/iot*') ? 'active' : '' }}">
                <i class="fas fa-microscope"></i>
                <span class="menu-item-text">Penelitian IoT</span>
            </a>
        @endif
    </div>
@endif

<!-- Analytics & Reports -->
@if($roleId == 1 || $roleId == 2 || $roleId == 3) {{-- Super Admin, Admin, Teacher --}}
    <div class="menu-section">
        <div class="menu-section-title">Analitik</div>
        
        @if($roleId == 1) {{-- Super Admin --}}
            <a href="{{ route('superadmin.reports') }}" class="menu-item {{ Request::is('superadmin/reports*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span class="menu-item-text">Laporan</span>
            </a>
        @elseif($roleId == 2) {{-- Admin --}}
            <a href="{{ route('admin.reports') }}" class="menu-item {{ Request::is('admin/reports*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span class="menu-item-text">Laporan</span>
            </a>
        @elseif($roleId == 3) {{-- Teacher --}}
            <a href="{{ route('teacher.reports') }}" class="menu-item {{ Request::is('teacher/reports*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span class="menu-item-text">Laporan</span>
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
        <a href="{{ route('student.settings') }}" class="menu-item {{ Request::is('student/settings*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i>
            <span class="menu-item-text">Pengaturan</span>
        </a>
        <a href="{{ route('student.help') }}" class="menu-item {{ Request::is('student/help*') ? 'active' : '' }}">
            <i class="fas fa-question-circle"></i>
            <span class="menu-item-text">Bantuan</span>
        </a>
    @endif
</div>
