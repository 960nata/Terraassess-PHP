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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Terra Assessment - ' . $roleTitle)</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- WARNING: Tailwind CDN should not be used in production -->
    <!-- For production, install Tailwind CSS as PostCSS plugin or use Tailwind CLI -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Quill.js for Rich Text Editor -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    
    @include('components.unified-header-styles')
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    screens: {
                        'md': '768px',
                    }
                }
            }
        }
    </script>
    <link href="{{ asset('css/superadmin-dashboard.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('styles')
</head>
<body>
    @include('components.mobile-overlay')
    @include('components.unified-header')
    @include('components.unified-sidebar')

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    @include('components.unified-header-scripts')
    <script src="{{ asset('js/superadmin-dashboard.js') }}"></script>
    
    @yield('scripts')
</body>
</html>
