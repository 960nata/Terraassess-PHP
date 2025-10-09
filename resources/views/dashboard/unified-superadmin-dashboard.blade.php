@extends('layouts.unified-layout-new')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="ph-crown"></i>
        Super Admin Dashboard
    </h1>
    <p class="page-description">
        Selamat datang di dashboard Super Admin Terra Assessment. Kelola seluruh sistem dengan kontrol penuh.
    </p>
</div>

<!-- Welcome Banner -->
<div class="welcome-banner">
    <div class="welcome-icon">
        <i class="ph-crown"></i>
    </div>
    <div class="welcome-content">
        <h3 class="welcome-title">Selamat Datang, {{ auth()->user()->name }}!</h3>
        <p class="welcome-description">
            Sebagai Super Admin, Anda memiliki akses penuh untuk mengelola seluruh sistem Terra Assessment. 
            Monitor performa, kelola pengguna, dan pastikan sistem berjalan optimal.
        </p>
    </div>
</div>

<!-- Dashboard Grid -->
<div class="dashboard-grid">
    <!-- Push Notifikasi -->
    <a href="{{ route('superadmin.push-notification') }}" class="card">
        <div class="card-icon blue">
            <i class="ph-bell"></i>
        </div>
        <h3 class="card-title">Push Notifikasi</h3>
        <p class="card-description">
            Kelola notifikasi sistem. Kirim pengumuman penting ke semua pengguna.
        </p>
    </a>

    <!-- Manajemen IoT -->
    <a href="{{ route('superadmin.iot-management') }}" class="card">
        <div class="card-icon green">
            <i class="ph-wifi"></i>
        </div>
        <h3 class="card-title">Manajemen IoT</h3>
        <p class="card-description">
            Kelola seluruh sistem IoT. Monitor perangkat dan konfigurasi global.
        </p>
    </a>

    <!-- Tugas Saya -->
    <a href="{{ route('superadmin.tugas.index') }}" class="card">
        <div class="card-icon purple">
            <i class="ph-clipboard-text"></i>
        </div>
        <h3 class="card-title">Tugas Saya</h3>
        <p class="card-description">
            Kelola tugas sistem. Monitor dan atur tugas untuk semua level pengguna.
        </p>
    </a>

    <!-- Ujian Saya -->
    <a href="{{ route('superadmin.exam-management') }}" class="card">
        <div class="card-icon orange">
            <i class="ph-exam"></i>
        </div>
        <h3 class="card-title">Ujian Saya</h3>
        <p class="card-description">
            Kelola ujian sistem. Atur jadwal dan monitor ujian di seluruh platform.
        </p>
    </a>

    <!-- Materi Saya -->
    <a href="{{ route('superadmin.material-management') }}" class="card">
        <div class="card-icon red">
            <i class="ph-book"></i>
        </div>
        <h3 class="card-title">Materi Saya</h3>
        <p class="card-description">
            Kelola materi pembelajaran. Monitor dan atur konten untuk semua mata pelajaran.
        </p>
    </a>

    <!-- Manajemen Pengguna -->
    <a href="{{ route('superadmin.user-management') }}" class="card">
        <div class="card-icon blue">
            <i class="ph-users"></i>
        </div>
        <h3 class="card-title">Manajemen Pengguna</h3>
        <p class="card-description">
            Kelola akun pengguna. Tambah, edit, dan atur permission untuk semua role.
        </p>
    </a>

    <!-- Manajemen Kelas -->
    <a href="{{ route('superadmin.class-management') }}" class="card">
        <div class="card-icon green">
            <i class="ph-buildings"></i>
        </div>
        <h3 class="card-title">Manajemen Kelas</h3>
        <p class="card-description">
            Kelola kelas dan struktur organisasi. Atur pembagian kelas dan jadwal.
        </p>
    </a>

    <!-- Mata Pelajaran -->
    <a href="{{ route('superadmin.subject-management') }}" class="card">
        <div class="card-icon purple">
            <i class="ph-book-open"></i>
        </div>
        <h3 class="card-title">Mata Pelajaran</h3>
        <p class="card-description">
            Kelola mata pelajaran. Tambah, edit, dan atur kurikulum pembelajaran.
        </p>
    </a>

    <!-- IoT Dashboard -->
    <a href="{{ route('iot.dashboard') }}" class="card">
        <div class="card-icon orange">
            <i class="ph-chart-line"></i>
        </div>
        <h3 class="card-title">IoT Dashboard</h3>
        <p class="card-description">
            Monitor data IoT global. Analisis performa sistem dan perangkat.
        </p>
    </a>

    <!-- Devices -->
    <a href="{{ route('iot.devices') }}" class="card">
        <div class="card-icon red">
            <i class="ph-device-mobile"></i>
        </div>
        <h3 class="card-title">Devices</h3>
        <p class="card-description">
            Kelola perangkat IoT. Monitor status dan konfigurasi semua device.
        </p>
    </a>

    <!-- Sensor Data -->
    <a href="{{ route('iot.sensor-data') }}" class="card">
        <div class="card-icon blue">
            <i class="ph-database"></i>
        </div>
        <h3 class="card-title">Sensor Data</h3>
        <p class="card-description">
            Monitor data sensor real-time. Analisis dan export data global.
        </p>
    </a>

    <!-- Analitik -->
    <a href="{{ route('superadmin.reports') }}" class="card">
        <div class="card-icon green">
            <i class="ph-chart-bar"></i>
        </div>
        <h3 class="card-title">Analitik</h3>
        <p class="card-description">
            Analisis performa sistem. Monitor statistik dan trend penggunaan.
        </p>
    </a>

    <!-- Laporan -->
    <a href="{{ route('superadmin.reports') }}" class="card">
        <div class="card-icon purple">
            <i class="ph-file-text"></i>
        </div>
        <h3 class="card-title">Laporan</h3>
        <p class="card-description">
            Generate laporan sistem. Export data lengkap untuk analisis mendalam.
        </p>
    </a>

    <!-- Pengaturan -->
    <a href="{{ route('superadmin.settings') }}" class="card">
        <div class="card-icon orange">
            <i class="ph-gear"></i>
        </div>
        <h3 class="card-title">Pengaturan</h3>
        <p class="card-description">
            Konfigurasi sistem global. Atur parameter dan preferensi platform.
        </p>
    </a>

    <!-- Bantuan -->
    <a href="{{ route('superadmin.help') }}" class="card">
        <div class="card-icon red">
            <i class="ph-question"></i>
        </div>
        <h3 class="card-title">Bantuan</h3>
        <p class="card-description">
            Dapatkan bantuan dan dokumentasi. Akses panduan Super Admin lengkap.
        </p>
    </a>
</div>

<!-- System Info -->
<div class="system-info">
    <div class="info-section">
        <h3 class="info-title">Statistik Sistem</h3>
        <ul class="info-list">
            <li>Total Pengguna: 1,247</li>
            <li>Guru Aktif: 45</li>
            <li>Siswa Aktif: 1,156</li>
            <li>Perangkat IoT Terhubung: 23</li>
        </ul>
    </div>
    
    <div class="info-section">
        <h3 class="info-title">Aktivitas Sistem</h3>
        <ul class="info-list">
            <li>Login hari ini: 234 pengguna</li>
            <li>Tugas yang dikumpulkan: 89</li>
            <li>Ujian yang sedang berlangsung: 12</li>
            <li>Data IoT diterima: 2,456</li>
        </ul>
    </div>
    
    <div class="info-section">
        <h3 class="info-title">Status Sistem</h3>
        <ul class="info-list">
            <li>Server Status: Online</li>
            <li>Database: Healthy</li>
            <li>IoT Gateway: Connected</li>
            <li>Last Backup: 2 jam yang lalu</li>
        </ul>
    </div>
</div>
@endsection
