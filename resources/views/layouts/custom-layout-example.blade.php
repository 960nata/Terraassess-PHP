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
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Terra Assessment - ' . $roleTitle)</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Include header styles -->
    @include('components.unified-header-styles')
    
    <!-- Include custom CSS -->
    <link href="{{ asset('css/superadmin-dashboard.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('styles')
</head>
<body>
    <!-- Include mobile overlay -->
    @include('components.mobile-overlay')
    
    <!-- Include unified header -->
    @include('components.unified-header')
    
    <!-- Include unified sidebar -->
    @include('components.unified-sidebar')

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Include JavaScript -->
    <script src="{{ asset('js/superadmin-dashboard.js') }}"></script>
    @include('components.unified-header-scripts')
    
    @yield('scripts')
</body>
</html>
