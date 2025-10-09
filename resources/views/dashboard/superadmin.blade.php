@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - Super Admin Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-crown"></i>
        Super Admin Dashboard
    </h1>
    <p class="page-description">Kontrol penuh atas sistem Terra Assessment</p>
</div>

<div class="welcome-banner">
    <div class="welcome-icon">
        <i class="fas fa-exclamation"></i>
    </div>
    <div class="welcome-content">
        <h2 class="welcome-title">Selamat datang, Super Admin!</h2>
        <p class="welcome-description">Sebagai Super Admin, Anda memiliki akses penuh untuk mengelola seluruh sistem.</p>
    </div>
</div>

<div class="dashboard-grid">
    <!-- Row 1 -->
    <a href="{{ route('superadmin.push-notification') }}" class="card">
        <div class="card-icon blue">
            <i class="fas fa-bell"></i>
        </div>
        <h3 class="card-title">Push Notifikasi</h3>
        <p class="card-description">Kirim notifikasi ke semua pengguna, kelas, atau pengguna spesifik</p>
    </a>

    <a href="{{ route('superadmin.iot-management') }}" class="card">
        <div class="card-icon green">
            <i class="fas fa-wifi"></i>
        </div>
        <h3 class="card-title">Manajemen IoT</h3>
        <p class="card-description">Daftarkan perangkat IoT, test konektivitas, dan monitor data sensor</p>
    </a>

    <!-- Row 2 -->
    <a href="{{ route('superadmin.tugas.index') }}" class="card">
        <div class="card-icon purple">
            <i class="fas fa-book"></i>
        </div>
        <h3 class="card-title">Manajemen Tugas</h3>
        <p class="card-description">Kelola tugas, ujian, dan materi pembelajaran</p>
    </a>

    <a href="{{ route('superadmin.users.index') }}" class="card">
        <div class="card-icon orange">
            <i class="fas fa-users"></i>
        </div>
        <h3 class="card-title">Manajemen Pengguna</h3>
        <p class="card-description">Kelola akun pengguna, guru, dan siswa</p>
    </a>

    <!-- Row 3 -->
    <a href="{{ route('superadmin.classes.index') }}" class="card">
        <div class="card-icon red">
            <i class="fas fa-chalkboard"></i>
        </div>
        <h3 class="card-title">Manajemen Kelas</h3>
        <p class="card-description">Kelola kelas, mata pelajaran, dan jadwal</p>
    </a>

    <a href="{{ route('superadmin.subjects.index') }}" class="card">
        <div class="card-icon yellow">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <h3 class="card-title">Manajemen Mata Pelajaran</h3>
        <p class="card-description">Kelola mata pelajaran dan kurikulum</p>
    </a>

    <!-- Row 4 -->
    <a href="{{ route('superadmin.exams.index') }}" class="card">
        <div class="card-icon pink">
            <i class="fas fa-clipboard-check"></i>
        </div>
        <h3 class="card-title">Manajemen Ujian</h3>
        <p class="card-description">Kelola ujian, soal, dan penilaian</p>
    </a>

    <a href="{{ route('superadmin.materials.index') }}" class="card">
        <div class="card-icon indigo">
            <i class="fas fa-file-alt"></i>
        </div>
        <h3 class="card-title">Manajemen Materi</h3>
        <p class="card-description">Kelola materi pembelajaran dan dokumen</p>
    </a>

    <!-- Row 5 -->
    <a href="{{ route('superadmin.analytics') }}" class="card">
        <div class="card-icon blue">
            <i class="fas fa-chart-line"></i>
        </div>
        <h3 class="card-title">Analitik & Laporan</h3>
        <p class="card-description">Lihat statistik dan laporan sistem</p>
    </a>

    <a href="{{ route('superadmin.settings') }}" class="card">
        <div class="card-icon green">
            <i class="fas fa-cog"></i>
        </div>
        <h3 class="card-title">Pengaturan Sistem</h3>
        <p class="card-description">Konfigurasi sistem dan preferensi</p>
    </a>
</div>
@endsection