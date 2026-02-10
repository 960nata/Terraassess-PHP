<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terra Assessment - Teacher Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css'])
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
</head>
<body>
    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay" onclick="closeSidebar()"></div>

    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <button class="menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="logo-text hidden sm:block">Terra Assessment</div>
            </div>
        </div>
        <div class="header-right">
            <!-- Notification Dropdown -->
            <div class="notification-container">
                <button class="notification-btn" onclick="toggleNotificationDropdown()">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge" id="notificationBadge" style="display: none;"></span>
                </button>
                
                <!-- Notification Dropdown -->
                <div class="notification-dropdown" id="notificationDropdown" style="display: none;">
                    <div class="notification-header">
                        <h4>Notifikasi</h4>
                        <div class="notification-actions">
                            <button class="mark-all-read-btn" onclick="markAllAsRead()" title="Tandai Semua Dibaca">
                                <i class="fas fa-check-double"></i>
                            </button>
                            <a href="{{ route('notifications.index') }}" class="view-all-btn" title="Lihat Semua">
                                <i class="fas fa-list"></i>
                            </a>
                        </div>
                    </div>
                    <div class="notification-list" id="notificationList">
                        <div class="notification-loading">
                            <i class="fas fa-spinner fa-spin"></i>
                            <span>Memuat notifikasi...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="profile-container">
                <div class="user-profile" onclick="toggleProfileDropdown()">
                    <div class="user-avatar">{{ substr($user->name ?? 'T', 0, 2) }}</div>
                    <div class="user-info">
                        <div class="user-name">{{ $user->name ?? 'Teacher' }}</div>
                        <div class="user-role">Teacher</div>
                    </div>
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </div>
                <div class="profile-dropdown" id="profileDropdown">
                    <div class="profile-dropdown-header">
                        <div class="profile-dropdown-avatar">{{ substr($user->name ?? 'T', 0, 2) }}</div>
                        <div class="profile-dropdown-info">
                            <div class="profile-dropdown-name">{{ $user->name ?? 'Teacher' }}</div>
                            <div class="profile-dropdown-role">Teacher</div>
                        </div>
                    </div>
                    <div class="profile-dropdown-menu">
                        <a href="{{ route('teacher.profile') }}" class="profile-dropdown-item">
                            <i class="fas fa-user"></i>
                            <span>Profil</span>
                        </a>

                        <div class="profile-dropdown-divider"></div>
                        <a href="{{ route('logout.get') }}" class="profile-dropdown-item logout">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-menu">
            <div class="menu-section">
                <div class="menu-section-title">Menu Utama</div>
                <a href="{{ route('teacher.dashboard') }}" class="menu-item active">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="menu-item-text">Dashboard</span>
                </a>
                <a href="{{ route('teacher.iot.dashboard') }}" class="menu-item">
                    <i class="fas fa-wifi"></i>
                    <span class="menu-item-text">Manajemen IoT</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Manajemen</div>
                <a href="{{ route('teacher.tugas') }}" class="menu-item">
                    <i class="fas fa-clipboard-text"></i>
                    <span class="menu-item-text">Manajemen Tugas</span>
                </a>
                <a href="{{ route('teacher.ujian') }}" class="menu-item">
                    <i class="fas fa-clipboard-check"></i>
                    <span class="menu-item-text">Manajemen Ujian</span>
                </a>
                <a href="{{ route('teacher.materi') }}" class="menu-item">
                    <i class="fas fa-book"></i>
                    <span class="menu-item-text">Manajemen Materi</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Analitik</div>
                <a href="{{ route('teacher.reports') }}" class="menu-item">
                    <i class="fas fa-chart-line"></i>
                    <span class="menu-item-text">Laporan</span>
                </a>
            </div>


        </div>
    </nav>

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

        <div class="dashboard-grid font-poppins">
            <!-- Row 1 -->
            <x-modern-card class="hover:scale-[1.02] transition-transform">
                <a href="{{ route('teacher.tugas') }}" class="flex flex-col h-full">
                    <div class="card-icon blue mb-4">
                        <i class="fas fa-clipboard-text"></i>
                    </div>
                    <h3 class="card-title text-xl font-bold text-white mb-2">Manajemen Tugas</h3>
                    <p class="card-description text-gray-400 text-sm">Buat, edit, dan kelola tugas untuk siswa Anda</p>
                </a>
            </x-modern-card>

            <x-modern-card class="hover:scale-[1.02] transition-transform">
                <a href="{{ route('teacher.ujian') }}" class="flex flex-col h-full">
                    <div class="card-icon green mb-4">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <h3 class="card-title text-xl font-bold text-white mb-2">Manajemen Ujian</h3>
                    <p class="card-description text-gray-400 text-sm">Buat dan kelola ujian untuk evaluasi siswa</p>
                </a>
            </x-modern-card>

            <!-- Row 2 -->
            <x-modern-card class="hover:scale-[1.02] transition-transform">
                <a href="{{ route('teacher.materi') }}" class="flex flex-col h-full">
                    <div class="card-icon purple mb-4">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3 class="card-title text-xl font-bold text-white mb-2">Manajemen Materi</h3>
                    <p class="card-description text-gray-400 text-sm">Buat dan kelola materi pembelajaran</p>
                </a>
            </x-modern-card>

            <x-modern-card class="hover:scale-[1.02] transition-transform">
                <a href="{{ route('teacher.iot.dashboard') }}" class="flex flex-col h-full">
                    <div class="card-icon orange mb-4">
                        <i class="fas fa-wifi"></i>
                    </div>
                    <h3 class="card-title text-xl font-bold text-white mb-2">Manajemen IoT</h3>
                    <p class="card-description text-gray-400 text-sm">Daftarkan perangkat IoT dan monitor data sensor</p>
                </a>
            </x-modern-card>

            <!-- Row 3 -->
            <x-modern-card class="hover:scale-[1.02] transition-transform">
                <a href="{{ route('teacher.reports') }}" class="flex flex-col h-full">
                    <div class="card-icon blue mb-4">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="card-title text-xl font-bold text-white mb-2">Laporan</h3>
                    <p class="card-description text-gray-400 text-sm">Lihat laporan performa siswa dan kelas</p>
                </a>
            </x-modern-card>

            <!-- Row 4 -->

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
</body>
</html>