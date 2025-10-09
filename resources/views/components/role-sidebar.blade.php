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
        <a href="{{ route('admin.iot-management') }}" class="menu-item {{ Request::is('admin/iot-management*') ? 'active' : '' }}">
            <i class="fas fa-wifi"></i>
            <span class="menu-item-text">Manajemen IoT</span>
        </a>
    @elseif($roleId == 3) {{-- Teacher --}}
        <a href="{{ route('teacher.dashboard') }}" class="menu-item {{ Request::is('teacher/dashboard*') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i>
            <span class="menu-item-text">Dashboard</span>
        </a>
        <a href="{{ route('teacher.tasks.management') }}" class="menu-item {{ Request::is('teacher/tasks*') ? 'active' : '' }}">
            <i class="fas fa-tasks"></i>
            <span class="menu-item-text">Tugas Saya</span>
        </a>
        <a href="{{ route('teacher.exams') }}" class="menu-item {{ Request::is('teacher/exams*') ? 'active' : '' }}">
            <i class="fas fa-clipboard-check"></i>
            <span class="menu-item-text">Ujian Saya</span>
        </a>
        <a href="{{ route('teacher.materials') }}" class="menu-item {{ Request::is('teacher/materials*') ? 'active' : '' }}">
            <i class="fas fa-book"></i>
            <span class="menu-item-text">Materi Saya</span>
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

@if($roleId == 1 || $roleId == 2) {{-- Super Admin & Admin --}}
<div class="menu-section">
    <div class="menu-section-title">Manajemen</div>
    @include('components.rbac-sidebar')
</div>
@endif

@if($roleId == 1) {{-- Super Admin Only --}}
<div class="menu-section">
    <div class="menu-section-title">IoT & Penelitian</div>
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
</div>
@elseif($roleId == 3) {{-- Teacher --}}
<div class="menu-section">
    <div class="menu-section-title">IoT & Penelitian</div>
    <a href="{{ route('teacher.iot.dashboard') }}" class="menu-item {{ Request::is('teacher/iot*') ? 'active' : '' }}">
        <i class="fas fa-wifi"></i>
        <span class="menu-item-text">Manajemen IoT</span>
    </a>
</div>
@endif

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

<!-- Notifications section removed for students - not relevant -->
@endif

@if($roleId == 1 || $roleId == 2) {{-- Super Admin & Admin --}}
<div class="menu-section">
    <div class="menu-section-title">Analitik</div>
    <a href="{{ route('superadmin.analytics') }}" class="menu-item {{ Request::is('superadmin/analytics*') ? 'active' : '' }}">
        <i class="fas fa-chart-bar"></i>
        <span class="menu-item-text">Analitik</span>
    </a>
    <a href="{{ route('superadmin.reports') }}" class="menu-item {{ Request::is('superadmin/reports*') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i>
        <span class="menu-item-text">Laporan</span>
    </a>
</div>
@elseif($roleId == 3) {{-- Teacher --}}
<div class="menu-section">
    <div class="menu-section-title">Analitik</div>
    <a href="{{ route('teacher.reports') }}" class="menu-item {{ Request::is('teacher/reports*') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i>
        <span class="menu-item-text">Laporan</span>
    </a>
</div>
@endif

<div class="menu-section">
    <div class="menu-section-title">Pengaturan</div>
    @if($roleId == 1)
        <a href="{{ route('superadmin.settings') }}" class="menu-item {{ Request::is('superadmin/settings*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i>
            <span class="menu-item-text">Pengaturan</span>
        </a>
    @elseif($roleId == 2)
        <a href="{{ route('admin.settings') }}" class="menu-item {{ Request::is('admin/settings*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i>
            <span class="menu-item-text">Pengaturan</span>
        </a>
    @elseif($roleId == 3)
        <a href="{{ route('teacher.settings') }}" class="menu-item {{ Request::is('teacher/settings*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i>
            <span class="menu-item-text">Pengaturan</span>
        </a>
    @elseif($roleId == 4)
        <a href="{{ route('student.settings') }}" class="menu-item {{ Request::is('student/settings*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i>
            <span class="menu-item-text">Pengaturan</span>
        </a>
    @endif
    
    @if($roleId == 1)
        <a href="{{ route('superadmin.help') }}" class="menu-item {{ Request::is('superadmin/help*') ? 'active' : '' }}">
            <i class="fas fa-question-circle"></i>
            <span class="menu-item-text">Bantuan</span>
        </a>
    @elseif($roleId == 2)
        <a href="{{ route('admin.help') }}" class="menu-item {{ Request::is('admin/help*') ? 'active' : '' }}">
            <i class="fas fa-question-circle"></i>
            <span class="menu-item-text">Bantuan</span>
        </a>
    @elseif($roleId == 3)
        <a href="{{ route('teacher.help') }}" class="menu-item {{ Request::is('teacher/help*') ? 'active' : '' }}">
            <i class="fas fa-question-circle"></i>
            <span class="menu-item-text">Bantuan</span>
        </a>
    @elseif($roleId == 4)
        <a href="{{ route('student.help') }}" class="menu-item {{ Request::is('student/help*') ? 'active' : '' }}">
            <i class="fas fa-question-circle"></i>
            <span class="menu-item-text">Bantuan</span>
        </a>
    @endif
</div>
