@extends('layout.template.dashboard-template')

@section('dashboard-cards')
    <a href="{{ route('superadmin.task-management') }}" class="card">
        <div class="card-icon blue">
            <i class="fas fa-book"></i>
        </div>
        <h3 class="card-title">Manajemen Tugas</h3>
        <p class="card-description">Kelola tugas yang telah Anda buat</p>
    </a>

    <a href="{{ route('superadmin.exam-management') }}" class="card">
        <div class="card-icon green">
            <i class="fas fa-bullseye"></i>
        </div>
        <h3 class="card-title">Manajemen Ujian</h3>
        <p class="card-description">Buat dan kelola ujian untuk kelas Anda</p>
    </a>

    <a href="{{ route('superadmin.material-management') }}" class="card">
        <div class="card-icon purple">
            <i class="fas fa-file-alt"></i>
        </div>
        <h3 class="card-title">Manajemen Materi</h3>
        <p class="card-description">Kelola materi pembelajaran untuk kelas</p>
    </a>


    <a href="{{ route('superadmin.reports') }}" class="card">
        <div class="card-icon purple">
            <i class="fas fa-chart-line"></i>
        </div>
        <h3 class="card-title">Laporan</h3>
        <p class="card-description">Lihat laporan performa siswa dan kelas</p>
    </a>

    <a href="{{ route('teacher.settings') }}" class="card">
        <div class="card-icon red">
            <i class="fas fa-cog"></i>
        </div>
        <h3 class="card-title">Pengaturan</h3>
        <p class="card-description">Konfigurasi profil dan preferensi</p>
    </a>

    <a href="{{ route('teacher.help') }}" class="card">
        <div class="card-icon red">
            <i class="fas fa-question-circle"></i>
        </div>
        <h3 class="card-title">Bantuan</h3>
        <p class="card-description">Panduan penggunaan sistem</p>
    </a>
@endsection

@php
    $roleTitle = 'Guru';
    $roleIcon = 'fas fa-chalkboard-teacher';
    $roleInitial = 'G';
    $roleDescription = 'Kelola pembelajaran dan kelas Anda';
    $welcomeMessage = 'Sebagai Guru, Anda dapat mengelola pembelajaran, tugas, dan ujian untuk kelas Anda.';
    $permissionsTitle = 'Hak Akses Guru';
    $permissions = [
        'Mengelola kelas yang diajar',
        'Membuat tugas dan ujian',
        'Mengelola materi pembelajaran',
        'Memantau data IoT untuk penelitian'
    ];
    $responsibilitiesTitle = 'Tanggung Jawab';
    $responsibilities = [
        'Menyiapkan materi pembelajaran',
        'Membuat dan menilai tugas',
        'Mengelola ujian',
        'Memantau perkembangan siswa'
    ];
    $profileRoute = route('teacher.profile');
    $settingsRoute = route('teacher.settings');
    $role = 'teacher';
    $roleId = 3;
    $roleColor = 'green';
@endphp
