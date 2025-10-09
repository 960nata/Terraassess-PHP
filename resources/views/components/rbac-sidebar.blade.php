@php
use App\Services\SimpleAccessControl;
@endphp

{{-- Simplified access control - Sidebar as main access control --}}
{{-- User Management - Only Admin & Superadmin --}}
@if(SimpleAccessControl::canAccessMenu('user-management'))
    <a href="{{ route('superadmin.user-management') }}" class="menu-item {{ Request::is('superadmin/user-management*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
        <span class="menu-item-text">Manajemen Pengguna</span>
    </a>
@endif

{{-- Class Management - Only Admin & Superadmin --}}
@if(SimpleAccessControl::canAccessMenu('class-management'))
    <a href="{{ route('superadmin.class-management') }}" class="menu-item {{ Request::is('superadmin/class-management*') ? 'active' : '' }}">
        <i class="fas fa-school"></i>
        <span class="menu-item-text">Manajemen Kelas</span>
    </a>
@endif

{{-- Subject Management - Only Admin & Superadmin --}}
@if(SimpleAccessControl::canAccessMenu('subject-management'))
    <a href="{{ route('superadmin.subject-management') }}" class="menu-item {{ Request::is('superadmin/subject-management*') ? 'active' : '' }}">
        <i class="fas fa-book-open"></i>
        <span class="menu-item-text">Manajemen Mata Pelajaran</span>
    </a>
@endif

{{-- Teacher Assignment Management - Only Admin & Superadmin --}}
@if(SimpleAccessControl::canAccessMenu('teacher-assignments'))
    <a href="{{ route('admin.teacher-assignments') }}" class="menu-item {{ Request::is('admin/teacher-assignments*') ? 'active' : '' }}">
        <i class="fas fa-chalkboard-teacher"></i>
        <span class="menu-item-text">Manajemen Penugasan Guru</span>
    </a>
@endif

{{-- Task Management - All roles --}}
@if(SimpleAccessControl::canAccessMenu('task-management'))
    <a href="{{ route('superadmin.task-management') }}" class="menu-item {{ Request::is('superadmin/task-management*') ? 'active' : '' }}">
        <i class="fas fa-tasks"></i>
        <span class="menu-item-text">Manajemen Tugas</span>
    </a>
@endif

{{-- Exam Management - All roles --}}
@if(SimpleAccessControl::canAccessMenu('exam-management'))
    <a href="{{ route('superadmin.exam-management') }}" class="menu-item {{ Request::is('superadmin/exam-management*') ? 'active' : '' }}">
        <i class="fas fa-clipboard-check"></i>
        <span class="menu-item-text">Manajemen Ujian</span>
    </a>
@endif

{{-- Material Management - All roles --}}
@if(SimpleAccessControl::canAccessMenu('material-management'))
    <a href="{{ route('superadmin.material-management') }}" class="menu-item {{ Request::is('superadmin/material-management*') ? 'active' : '' }}">
        <i class="fas fa-file-alt"></i>
        <span class="menu-item-text">Manajemen Materi</span>
    </a>
@endif

{{-- IoT Management moved to IoT & Penelitian section in role-sidebar --}}

{{-- Reports and Analytics moved to Analytics section to avoid duplication --}}

{{-- Push Notification - Removed duplicate, already in main menu section --}}
