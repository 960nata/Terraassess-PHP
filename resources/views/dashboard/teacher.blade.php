@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - Teacher Dashboard')

@section('content')
<style>
        /* Notification Toast Styles */
        .notification-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 12px 16px;
            color: white;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            z-index: 9999;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
        }

        .notification-toast.show {
            opacity: 1;
            transform: translateX(0);
        }

        .notification-toast.success {
            border-left: 4px solid #22c55e;
        }

        .notification-toast.error {
            border-left: 4px solid #ef4444;
        }

        .notification-toast.info {
            border-left: 4px solid #3b82f6;
        }

        /* Notification Actions */
        .notification-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .mark-all-read-btn {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #3b82f6;
            padding: 6px 8px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .mark-all-read-btn:hover {
            background: rgba(59, 130, 246, 0.2);
            border-color: rgba(59, 130, 246, 0.5);
            color: #60a5fa;
        }

        .view-all-btn {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #22c55e;
            padding: 6px 8px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .view-all-btn:hover {
            background: rgba(34, 197, 94, 0.2);
            border-color: rgba(34, 197, 94, 0.5);
            color: #16a34a;
        }

        /* Dashboard Grid Layout */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 3rem;
        }

        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 0.875rem;
            }
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: repeat(2, 1fr);
                grid-template-rows: repeat(6, 1fr);
                gap: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            .dashboard-grid {
                grid-template-columns: repeat(2, 1fr);
                grid-template-rows: repeat(6, 1fr);
                gap: 0.5rem;
            }
        }

        /* Card Styling for 4x3 Layout */
        .card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            min-height: 160px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-decoration: none;
            color: inherit;
        }

        .card:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(59, 130, 246, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .card-icon.blue {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
        }

        .card-icon.green {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .card-icon.purple {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
        }

        .card-icon.orange {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .card-icon.red {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: white;
            margin-bottom: 0.5rem;
        }

        .card-description {
            color: #94a3b8;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        /* Mobile Card Adjustments */
        @media (max-width: 768px) {
            .card {
                min-height: 140px;
                padding: 1rem;
            }

            .card-icon {
                width: 40px;
                height: 40px;
                font-size: 1.25rem;
                margin-bottom: 0.75rem;
            }

            .card-title {
                font-size: 1rem;
            }

            .card-description {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .card {
                min-height: 130px;
                padding: 0.75rem;
            }

            .card-icon {
                width: 32px;
                height: 32px;
                font-size: 1rem;
                margin-bottom: 0.5rem;
            }

            .card-title {
                font-size: 0.85rem;
                line-height: 1.3;
            }

            .card-description {
                font-size: 0.7rem;
                line-height: 1.4;
            }
        }
    </style>

    <!-- Main Content -->
    <main class="main-content">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-chalkboard-teacher"></i>
                Teacher Dashboard
            </h1>
            <p class="page-description">Kelola sistem Terra Assessment dengan akses teacher</p>
        </div>

        <div class="welcome-banner">
            <div class="welcome-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="welcome-content">
                <h2 class="welcome-title">Selamat datang, Guru!</h2>
                <p class="welcome-description">Kelola tugas, ujian, dan materi pembelajaran untuk siswa Anda.</p>
            </div>
        </div>

        <div class="dashboard-grid">
            <!-- Row 1 -->
            <a href="{{ route('teacher.tugas') }}" class="card">
                <div class="card-icon blue">
                    <i class="fas fa-clipboard-text"></i>
                </div>
                <h3 class="card-title">Manajemen Tugas</h3>
                <p class="card-description">Buat, edit, dan kelola tugas untuk siswa Anda</p>
            </a>

            <a href="{{ route('teacher.ujian') }}" class="card">
                <div class="card-icon green">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h3 class="card-title">Manajemen Ujian</h3>
                <p class="card-description">Buat dan kelola ujian untuk evaluasi siswa</p>
            </a>

            <!-- Row 2 -->
            <a href="{{ route('teacher.materi') }}" class="card">
                <div class="card-icon purple">
                    <i class="fas fa-book"></i>
                </div>
                <h3 class="card-title">Manajemen Materi</h3>
                <p class="card-description">Buat dan kelola materi pembelajaran</p>
            </a>

            <a href="{{ route('teacher.iot.dashboard') }}" class="card">
                <div class="card-icon orange">
                    <i class="fas fa-wifi"></i>
                </div>
                <h3 class="card-title">Manajemen IoT</h3>
                <p class="card-description">Daftarkan perangkat IoT dan monitor data sensor</p>
            </a>

            <!-- Row 3 -->
            <a href="{{ route('teacher.reports') }}" class="card">
                <div class="card-icon blue">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="card-title">Laporan</h3>
                <p class="card-description">Lihat laporan performa siswa dan kelas</p>
            </a>

            <a href="{{ route('iot.tugas') }}" class="card">
                <div class="card-icon green">
                    <i class="fas fa-clipboard-text"></i>
                </div>
                <h3 class="card-title">Tugas IoT</h3>
                <p class="card-description">Buat dan kelola tugas penelitian IoT</p>
            </a>

            <!-- Row 4 -->
            <a href="{{ route('iot.research-projects') }}" class="card">
                <div class="card-icon purple">
                    <i class="fas fa-flask"></i>
                </div>
                <h3 class="card-title">Penelitian IoT</h3>
                <p class="card-description">Lihat hasil penelitian IoT siswa</p>
            </a>

            <a href="{{ route('teacher.settings') }}" class="card">
                <div class="card-icon blue">
                    <i class="fas fa-cog"></i>
                </div>
                <h3 class="card-title">Pengaturan</h3>
                <p class="card-description">Kelola pengaturan akun dan preferensi</p>
            </a>

        </div>

        <div class="system-info">
            <div class="info-section">
                <h3 class="info-title">Hak Akses Guru</h3>
                <ul class="info-list">
                    <li>Membuat dan mengelola tugas untuk siswa</li>
                    <li>Membuat dan mengelola ujian evaluasi</li>
                    <li>Membuat dan mengelola materi pembelajaran</li>
                    <li>Mengakses dashboard IoT untuk penelitian</li>
                    <li>Melihat laporan performa siswa</li>
                </ul>
            </div>

            <div class="info-section">
                <h3 class="info-title">Tanggung Jawab</h3>
                <ul class="info-list">
                    <li>Menyiapkan materi pembelajaran yang berkualitas</li>
                    <li>Membuat tugas dan ujian yang sesuai kurikulum</li>
                    <li>Memantau perkembangan belajar siswa</li>
                    <li>Memberikan feedback dan evaluasi</li>
                    <li>Menggunakan teknologi IoT untuk pembelajaran</li>
                </ul>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/teacher-dashboard.js') }}"></script>
@endsection