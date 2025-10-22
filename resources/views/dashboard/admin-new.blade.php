@extends('layout.template.dashboard-template')

@section('dashboard-cards')
    <a href="{{ route('admin.push-notification') }}" class="card">
        <div class="card-icon blue">
            <i class="fas fa-bell"></i>
        </div>
        <h3 class="card-title">Push Notifikasi</h3>
        <p class="card-description">Kirim notifikasi ke pengguna dan kelas</p>
    </a>


    <a href="{{ route('superadmin.task-management') }}" class="card">
        <div class="card-icon purple">
            <i class="fas fa-book"></i>
        </div>
        <h3 class="card-title">Manajemen Tugas</h3>
        <p class="card-description">Kelola tugas per kelas dan mata pelajaran</p>
    </a>

    <a href="{{ route('superadmin.exam-management') }}" class="card">
        <div class="card-icon orange">
            <i class="fas fa-bullseye"></i>
        </div>
        <h3 class="card-title">Manajemen Ujian</h3>
        <p class="card-description">Buat dan kelola ujian untuk kelas</p>
    </a>

    <a href="{{ route('superadmin.user-management') }}" class="card">
        <div class="card-icon blue">
            <i class="fas fa-users"></i>
        </div>
        <h3 class="card-title">Manajemen Pengguna</h3>
        <p class="card-description">Kelola pengguna sistem (Guru, Siswa)</p>
    </a>

    <a href="{{ route('superadmin.class-management') }}" class="card">
        <div class="card-icon green">
            <i class="fas fa-chart-bar"></i>
        </div>
        <h3 class="card-title">Manajemen Kelas</h3>
        <p class="card-description">Buat dan kelola kelas di sistem</p>
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
        <p class="card-description">Kelola materi pembelajaran</p>
    </a>

    <a href="{{ route('superadmin.reports') }}" class="card">
        <div class="card-icon red">
            <i class="fas fa-chart-line"></i>
        </div>
        <h3 class="card-title">Laporan</h3>
        <p class="card-description">Lihat laporan dan analitik</p>
    </a>

@endsection

@php
    $roleTitle = 'Admin';
    $roleIcon = 'fas fa-user-shield';
    $roleInitial = 'A';
    $roleDescription = 'Kelola sistem Terra Assessment dengan akses admin';
    $welcomeMessage = 'Sebagai Admin, Anda memiliki akses untuk mengelola sistem Terra Assessment.';
    $permissionsTitle = 'Hak Akses Admin';
    $permissions = [
        'Kelola pengguna sistem (Guru dan Siswa)',
        'Akses ke fitur manajemen',
        'Konfigurasi sistem',
        'Monitoring aktivitas pengguna'
    ];
    $responsibilitiesTitle = 'Tanggung Jawab';
    $responsibilities = [
        'Memastikan keamanan sistem',
        'Mengelola data pengguna',
        'Konfigurasi aplikasi',
        'Backup dan maintenance'
    ];
    $profileRoute = route('admin.profile');
    $role = 'admin';
    $roleId = 2;
    $roleColor = 'blue';
@endphp