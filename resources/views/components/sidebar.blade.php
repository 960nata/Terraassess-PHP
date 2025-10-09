@php
    $roleName = 'User';
    switch(auth()->user()->roles_id) {
        case 1: $roleName = 'Super Admin'; break;
        case 2: $roleName = 'Admin'; break;
        case 3: $roleName = 'Teacher'; break;
        case 4: $roleName = 'Student'; break;
    }
@endphp

<!-- Responsive Sidebar -->
<div class="sidebar" id="mainSidebar">
    <!-- Sidebar Content -->
    <div class="sidebar-content">
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="sidebar-logo">
                <div class="sidebar-logo-icon">
                    <i class="fas fa-rocket"></i>
                </div>
                <span class="sidebar-logo-text">Terra Assessment</span>
            </a>
            <button class="sidebar-toggle" type="button">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- Navigation Menu -->
        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <div class="nav-item-icon">
                    <i class="fas fa-home"></i>
                </div>
                <span class="nav-item-text">Dashboard</span>
                <div class="nav-item-tooltip">Dashboard</div>
            </a>

            <a href="{{ route('classes.index') }}" class="nav-item {{ request()->routeIs('classes.*') ? 'active' : '' }}">
                <div class="nav-item-icon">
                    <i class="fas fa-chalkboard"></i>
                </div>
                <span class="nav-item-text">Classes</span>
                <div class="nav-item-tooltip">Classes</div>
            </a>

            <a href="{{ route('subjects.index') }}" class="nav-item {{ request()->routeIs('subjects.*') ? 'active' : '' }}">
                <div class="nav-item-icon">
                    <i class="fas fa-book"></i>
                </div>
                <span class="nav-item-text">Subjects</span>
                <div class="nav-item-tooltip">Subjects</div>
            </a>

            <a href="{{ route('materials.index') }}" class="nav-item {{ request()->routeIs('materials.*') ? 'active' : '' }}">
                <div class="nav-item-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <span class="nav-item-text">Materials</span>
                <div class="nav-item-tooltip">Materials</div>
            </a>

            <a href="{{ route('assignments.index') }}" class="nav-item {{ request()->routeIs('assignments.*') ? 'active' : '' }}">
                <div class="nav-item-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <span class="nav-item-text">Assignments</span>
                <div class="nav-item-tooltip">Assignments</div>
            </a>

            <a href="{{ route('exams.index') }}" class="nav-item {{ request()->routeIs('exams.*') ? 'active' : '' }}">
                <div class="nav-item-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <span class="nav-item-text">Exams</span>
                <div class="nav-item-tooltip">Exams</div>
            </a>

            <a href="{{ route('students.index') }}" class="nav-item {{ request()->routeIs('students.*') ? 'active' : '' }}">
                <div class="nav-item-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <span class="nav-item-text">Students</span>
                <div class="nav-item-tooltip">Students</div>
            </a>

            <a href="{{ route('teachers.index') }}" class="nav-item {{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                <div class="nav-item-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <span class="nav-item-text">Teachers</span>
                <div class="nav-item-tooltip">Teachers</div>
            </a>

            @if(auth()->user()->roles_id == 1 || auth()->user()->roles_id == 2)
            <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <div class="nav-item-icon">
                    <i class="fas fa-users"></i>
                </div>
                <span class="nav-item-text">Users</span>
                <div class="nav-item-tooltip">Users</div>
            </a>
            @endif

            <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <div class="nav-item-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <span class="nav-item-text">Reports</span>
                <div class="nav-item-tooltip">Reports</div>
            </a>

            <a href="{{ route('settings.index') }}" class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <div class="nav-item-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <span class="nav-item-text">Settings</span>
                <div class="nav-item-tooltip">Settings</div>
            </a>
        </nav>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <!-- User Profile -->
            <div class="sidebar-user" onclick="toggleUserMenu()">
                <div class="sidebar-user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                    <div class="sidebar-user-role">
                        @switch(auth()->user()->roles_id)
                            @case(1)
                                Super Admin
                                @break
                            @case(2)
                                Admin
                                @break
                            @case(3)
                                Teacher
                                @break
                            @case(4)
                                Student
                                @break
                            @default
                                User
                        @endswitch
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Overlay -->
<div class="sidebar-overlay"></div>

<!-- Include Sidebar Styles -->
<link rel="stylesheet" href="{{ asset('asset/css/sidebar.css') }}">

<!-- Include Sidebar Script -->
<script src="{{ asset('asset/js/sidebar.js') }}"></script>

<script>
// User menu toggle function
function toggleUserMenu() {
    // Add your user menu logic here
    console.log('User menu clicked');
}

// Initialize sidebar with user data
document.addEventListener('DOMContentLoaded', function() {
    if (window.sidebar) {
        window.sidebar.updateUserInfo({
            name: '{{ auth()->user()->name }}',
            role: '{{ $roleName }}',
            avatar: '{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}'
        });
    }
});
</script>
