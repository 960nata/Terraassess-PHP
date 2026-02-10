@extends('layout.template.dashboard-template')

@section('dashboard-cards')
    <a href="{{ route('superadmin.push-notification') }}" class="card">
        <div class="card-icon blue">
            <i class="fas fa-bell"></i>
        </div>
        <h3 class="card-title">Push Notifikasi</h3>
        <p class="card-description">Kirim notifikasi ke semua pengguna, kelas, atau pengguna spesifik</p>
    </a>


    <a href="{{ route('superadmin.task-management') }}" class="card">
        <div class="card-icon purple">
            <i class="fas fa-book"></i>
        </div>
        <h3 class="card-title">Manajemen Tugas</h3>
        <p class="card-description">Kelola tugas per kelas dengan kategorisasi dan tingkat kesulitan</p>
    </a>

    <a href="{{ route('superadmin.exam-management') }}" class="card">
        <div class="card-icon orange">
            <i class="fas fa-bullseye"></i>
        </div>
        <h3 class="card-title">Manajemen Ujian</h3>
        <p class="card-description">Buat, edit, dan kelola ujian dengan fitur lengkap</p>
    </a>

    <a href="{{ route('superadmin.user-management') }}" class="card">
        <div class="card-icon blue">
            <i class="fas fa-users"></i>
        </div>
        <h3 class="card-title">Manajemen Pengguna</h3>
        <p class="card-description">Kelola semua pengguna sistem (Admin, Guru, Siswa)</p>
    </a>

    <a href="{{ route('superadmin.class-management') }}" class="card">
        <div class="card-icon green">
            <i class="fas fa-chart-bar"></i>
        </div>
        <h3 class="card-title">Manajemen Kelas</h3>
        <p class="card-description">Buat dan kelola semua kelas di sistem</p>
    </a>

    <a href="{{ route('superadmin.subject-management') }}" class="card">
        <div class="card-icon purple">
            <i class="fas fa-database"></i>
        </div>
        <h3 class="card-title">Mata Pelajaran</h3>
        <p class="card-description">Tambah dan kelola mata pelajaran</p>
    </a>



    <a href="{{ route('superadmin.material-management') }}" class="card">
        <div class="card-icon green">
            <i class="fas fa-file-alt"></i>
        </div>
        <h3 class="card-title">Materi</h3>
        <p class="card-description">Kelola materi pembelajaran dan konten</p>
    </a>

    <a href="{{ route('superadmin.exam-management') }}" class="card">
        <div class="card-icon red">
            <i class="fas fa-clipboard-check"></i>
        </div>
        <h3 class="card-title">Ujian</h3>
        <p class="card-description">Kelola semua ujian sistem</p>
    </a>

    <a href="{{ route('superadmin.reports') }}" class="card">
        <div class="card-icon red">
            <i class="fas fa-chart-line"></i>
        </div>
        <h3 class="card-title">Laporan</h3>
        <p class="card-description">Lihat laporan dan analitik sistem</p>
    </a>
@endsection

@php
    $roleTitle = 'Super Admin';
    $roleIcon = 'fas fa-crown';
    $roleInitial = 'SA';
    $roleDescription = 'Kontrol penuh atas sistem Terra Assessment';
    $welcomeMessage = 'Sebagai Super Admin, Anda memiliki akses penuh untuk mengelola seluruh sistem.';
    $permissionsTitle = 'Hak Akses Super Admin';
    $permissions = [
        'Kelola semua pengguna sistem',
        'Akses ke semua fitur aplikasi',
        'Konfigurasi sistem global',
        'Monitoring aktivitas pengguna'
    ];
    $responsibilitiesTitle = 'Tanggung Jawab';
    $responsibilities = [
        'Memastikan keamanan sistem',
        'Mengelola data pengguna',
        'Konfigurasi aplikasi',
        'Backup dan maintenance'
    ];
    $profileRoute = route('superadmin.profile');
    $settingsRoute = route('superadmin.settings');
    $role = 'superadmin';
    $roleId = 1;
    $roleColor = 'purple';
@endphp
