@extends('layouts.unified-layout')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="ph-gauge"></i>
        Dashboard Guru
    </h1>
    <p class="page-description">
        Selamat datang di dashboard guru Terra Assessment. Kelola tugas, ujian, materi, dan IoT dengan mudah.
    </p>
</div>

<!-- Welcome Banner -->
<div class="welcome-banner">
    <div class="welcome-icon">
        <i class="ph-chalkboard-teacher"></i>
    </div>
    <div class="welcome-content">
        <h3 class="welcome-title">Selamat Datang, {{ auth()->user()->name }}!</h3>
        <p class="welcome-description">
            Anda dapat mengelola semua aktivitas mengajar Anda dari dashboard ini. 
            Mulai dari membuat tugas, mengelola ujian, hingga memantau data IoT siswa.
        </p>
    </div>
</div>

<!-- Dashboard Grid -->
<div class="dashboard-grid">
    <!-- Tugas Saya -->
    <a href="{{ route('teacher.tugas') }}" class="card">
        <div class="card-icon blue">
            <i class="ph-clipboard-text"></i>
        </div>
        <h3 class="card-title">Tugas Saya</h3>
        <p class="card-description">
            Kelola dan buat tugas untuk siswa Anda. Pantau progress dan nilai siswa.
        </p>
    </a>

    <!-- Ujian Saya -->
    <a href="{{ route('teacher.ujian') }}" class="card">
        <div class="card-icon green">
            <i class="ph-exam"></i>
        </div>
        <h3 class="card-title">Ujian Saya</h3>
        <p class="card-description">
            Buat dan kelola ujian online. Pantau hasil dan analisis performa siswa.
        </p>
    </a>

    <!-- Materi Saya -->
    <a href="{{ route('teacher.materi') }}" class="card">
        <div class="card-icon purple">
            <i class="ph-book"></i>
        </div>
        <h3 class="card-title">Materi Saya</h3>
        <p class="card-description">
            Upload dan kelola materi pembelajaran. Buat konten interaktif untuk siswa.
        </p>
    </a>

    <!-- IoT Dashboard -->
    <a href="{{ route('iot.dashboard') }}" class="card">
        <div class="card-icon orange">
            <i class="ph-chart-line"></i>
        </div>
        <h3 class="card-title">IoT Dashboard</h3>
        <p class="card-description">
            Pantau data sensor IoT dari eksperimen siswa. Analisis hasil penelitian.
        </p>
    </a>

    <!-- Devices -->
    <a href="{{ route('iot.devices') }}" class="card">
        <div class="card-icon red">
            <i class="ph-device-mobile"></i>
        </div>
        <h3 class="card-title">Devices</h3>
        <p class="card-description">
            Kelola perangkat IoT yang terhubung. Monitor status dan konfigurasi.
        </p>
    </a>

    <!-- Sensor Data -->
    <a href="{{ route('iot.sensor-data') }}" class="card">
        <div class="card-icon blue">
            <i class="ph-database"></i>
        </div>
        <h3 class="card-title">Sensor Data</h3>
        <p class="card-description">
            Lihat dan analisis data sensor real-time. Export data untuk analisis.
        </p>
    </a>

    <!-- Analitik -->
    <a href="{{ route('teacher.reports') }}" class="card">
        <div class="card-icon green">
            <i class="ph-chart-bar"></i>
        </div>
        <h3 class="card-title">Analitik</h3>
        <p class="card-description">
            Lihat laporan dan analisis performa siswa. Pantau progress pembelajaran.
        </p>
    </a>

    <!-- Laporan -->
    <a href="{{ route('teacher.reports') }}" class="card">
        <div class="card-icon purple">
            <i class="ph-file-text"></i>
        </div>
        <h3 class="card-title">Laporan</h3>
        <p class="card-description">
            Generate laporan detail untuk siswa dan orang tua. Export data lengkap.
        </p>
    </a>

    <!-- Pengaturan -->
    <a href="{{ route('teacher.settings') }}" class="card">
        <div class="card-icon orange">
            <i class="ph-gear"></i>
        </div>
        <h3 class="card-title">Pengaturan</h3>
        <p class="card-description">
            Konfigurasi profil dan preferensi. Kelola notifikasi dan pengaturan akun.
        </p>
    </a>

    <!-- Bantuan -->
    <a href="{{ route('teacher.help') }}" class="card">
        <div class="card-icon red">
            <i class="ph-question"></i>
        </div>
        <h3 class="card-title">Bantuan</h3>
        <p class="card-description">
            Dapatkan bantuan dan panduan penggunaan. Hubungi support jika diperlukan.
        </p>
    </a>
</div>

<!-- System Info -->
<div class="system-info">
    <div class="info-section">
        <h3 class="info-title">Statistik Hari Ini</h3>
        <ul class="info-list">
            <li>Tugas yang perlu diperiksa: 5</li>
            <li>Ujian yang sedang berlangsung: 2</li>
            <li>Materi baru yang diupload: 3</li>
            <li>Data IoT yang diterima: 127</li>
        </ul>
    </div>
    
    <div class="info-section">
        <h3 class="info-title">Aktivitas Terbaru</h3>
        <ul class="info-list">
            <li>Siswa A mengumpulkan tugas Matematika</li>
            <li>Ujian Fisika dimulai 10 menit yang lalu</li>
            <li>Data sensor suhu diterima dari Device #3</li>
            <li>Materi baru "Optika" berhasil diupload</li>
        </ul>
    </div>
</div>
@endsection
