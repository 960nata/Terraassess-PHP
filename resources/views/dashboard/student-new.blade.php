@extends('layout.template.dashboard-template')

@section('dashboard-cards')
    <a href="{{ route('student.tasks') }}" class="card">
        <div class="card-icon blue">
            <i class="fas fa-book"></i>
        </div>
        <h3 class="card-title">Tugas Saya</h3>
        <p class="card-description">Lihat dan kerjakan tugas yang diberikan</p>
    </a>

    <a href="{{ route('student.exams') }}" class="card">
        <div class="card-icon green">
            <i class="fas fa-bullseye"></i>
        </div>
        <h3 class="card-title">Ujian Saya</h3>
        <p class="card-description">Ikuti ujian yang telah dijadwalkan</p>
    </a>

    <a href="{{ route('student.materials') }}" class="card">
        <div class="card-icon purple">
            <i class="fas fa-file-alt"></i>
        </div>
        <h3 class="card-title">Materi Saya</h3>
        <p class="card-description">Akses materi pembelajaran kelas</p>
    </a>

    <a href="{{ route('student.iot-research') }}" class="card">
        <div class="card-icon orange">
            <i class="fas fa-microscope"></i>
        </div>
        <h3 class="card-title">Penelitian IoT</h3>
        <p class="card-description">Lakukan penelitian menggunakan perangkat IoT</p>
    </a>

    <a href="{{ route('student.class-management') }}" class="card">
        <div class="card-icon teal">
            <i class="fas fa-users"></i>
        </div>
        <h3 class="card-title">Kelas Saya</h3>
        <p class="card-description">Lihat informasi kelas dan teman sekelas</p>
    </a>

    <a href="{{ route('student.profile') }}" class="card">
        <div class="card-icon red">
            <i class="fas fa-user"></i>
        </div>
        <h3 class="card-title">Profile Saya</h3>
        <p class="card-description">Kelola informasi profil dan pengaturan akun</p>
    </a>

    <a href="{{ route('notifications.index') }}" class="card">
        <div class="card-icon yellow">
            <i class="fas fa-bell"></i>
        </div>
        <h3 class="card-title">Notifikasi</h3>
        <p class="card-description">Lihat notifikasi dan pengumuman terbaru</p>
    </a>
@endsection

@php
    $roleTitle = 'Siswa';
    $roleIcon = 'fas fa-graduation-cap';
    $roleInitial = 'S';
    $roleDescription = 'Akses pembelajaran dan penelitian IoT';
    $welcomeMessage = 'Sebagai Siswa, Anda dapat mengakses materi pembelajaran, mengerjakan tugas, dan melakukan penelitian IoT.';
    $permissionsTitle = 'Hak Akses Siswa';
    $permissions = [
        'Mengakses materi pembelajaran',
        'Mengerjakan tugas dan ujian',
        'Melakukan penelitian IoT',
        'Melihat nilai dan progress'
    ];
    $responsibilitiesTitle = 'Tanggung Jawab';
    $responsibilities = [
        'Mengerjakan tugas tepat waktu',
        'Mengikuti ujian sesuai jadwal',
        'Melakukan penelitian IoT',
        'Mengikuti pembelajaran dengan baik'
    ];
    $profileRoute = route('student.profile');
    $settingsRoute = route('student.settings');
    $role = 'student';
    $roleId = 4;
    $roleColor = 'orange';
@endphp