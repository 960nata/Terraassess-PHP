<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Terra Assessment - Research Projects</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.0.16/src/phosphor.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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
        /* Ensure dashboard grid layout works properly */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        
        .card {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
            min-height: 160px;
            display: flex;
            flex-direction: column;
        }
        
        .card:hover {
            transform: translateY(-5px);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }
        
        .card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 1.5rem;
            color: white;
        }
        
        .card-icon.blue { background-color: #3b82f6; }
        .card-icon.green { background-color: #10b981; }
        .card-icon.purple { background-color: #8b5cf6; }
        .card-icon.orange { background-color: #f59e0b; }
        .card-icon.teal { background-color: #14b8a6; }
        .card-icon.red { background-color: #ef4444; }
        
        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
        }
        
        .card-description {
            color: #94a3b8;
            font-size: 0.875rem;
            line-height: 1.5;
            flex-grow: 1;
        }

        /* Project Card Styles */
        .project-card {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .project-card:hover {
            background: rgba(15, 23, 42, 0.8);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .project-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .project-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
        }

        .project-status {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .project-description {
            color: #94a3b8;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .project-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .detail-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 1rem;
        }

        .detail-label {
            color: #94a3b8;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .detail-value {
            color: white;
            font-weight: 600;
            font-size: 1rem;
        }

        .project-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .action-btn {
            background: rgba(59, 130, 246, 0.2);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #3b82f6;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .action-btn:hover {
            background: rgba(59, 130, 246, 0.3);
            border-color: rgba(59, 130, 246, 0.5);
            color: #60a5fa;
            transform: translateY(-1px);
        }

        .action-btn.secondary {
            background: rgba(107, 114, 128, 0.2);
            border-color: rgba(107, 114, 128, 0.3);
            color: #9ca3af;
        }

        .action-btn.secondary:hover {
            background: rgba(107, 114, 128, 0.3);
            border-color: rgba(107, 114, 128, 0.5);
            color: #d1d5db;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            opacity: 0.5;
        }

        .empty-state h3 {
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .empty-state p {
            font-size: 1rem;
            line-height: 1.6;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 0.75rem;
            }
            
            .card {
                min-height: 120px;
                padding: 0.75rem;
            }
            
            .card-icon {
                width: 32px;
                height: 32px;
                font-size: 1rem;
                margin-bottom: 0.5rem;
            }
            
            .card-title {
                font-size: 0.875rem;
                margin-bottom: 0.25rem;
            }
            
            .card-description {
                font-size: 0.75rem;
                line-height: 1.2;
            }

            .project-header {
                flex-direction: column;
                gap: 1rem;
            }

            .project-details {
                grid-template-columns: 1fr;
            }

            .project-actions {
                flex-direction: column;
            }

            .action-btn {
                justify-content: center;
            }
        }

        /* Header Styles */
        .header {
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .menu-toggle {
            background: none;
            border: none;
            color: white;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .menu-toggle:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .logo-text {
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* Profile Dropdown Styles */
        .user-profile-container {
            position: relative;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-profile:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            overflow: hidden;
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .user-role {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.75rem;
        }

        .profile-dropdown-arrow {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.75rem;
            transition: transform 0.3s ease;
        }

        .user-profile:hover .profile-dropdown-arrow {
            transform: rotate(180deg);
        }

        .profile-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            min-width: 200px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            margin-top: 0.5rem;
            overflow: hidden;
        }

        .profile-dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: white;
            text-decoration: none;
            transition: background-color 0.3s ease;
            font-size: 0.875rem;
        }

        .profile-dropdown-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .profile-dropdown-item.logout {
            color: #ef4444;
        }

        .profile-dropdown-item.logout:hover {
            background: rgba(239, 68, 68, 0.1);
        }

        .profile-dropdown-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
            margin: 0.5rem 0;
        }

        /* Mobile responsiveness for header */
        @media (max-width: 768px) {
            .header {
                padding: 1rem;
            }
            
            .logo-text {
                display: none;
            }
            
            .profile-dropdown {
                min-width: 180px;
                right: -20px;
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
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="logo-text">Terra Assessment</div>
            </div>
        </div>
        <div class="header-right">
            <!-- User Profile Dropdown -->
            <div class="user-profile-container">
                <div class="user-profile" onclick="toggleProfileDropdown()">
                    <div class="user-avatar">
                        <img src="{{ Auth::user()->gambar ? asset('storage/' . Auth::user()->gambar) : asset('asset/icons/profile-women.svg') }}" 
                             alt="Profile" class="avatar-img">
                    </div>
                    <div class="user-info">
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-role">Siswa</div>
                    </div>
                    <i class="fas fa-chevron-down profile-dropdown-arrow"></i>
                </div>
                
                <!-- Profile Dropdown -->
                <div class="profile-dropdown" id="profileDropdown" style="display: none;">
                    <a href="{{ route('student.profile') }}" class="profile-dropdown-item">
                        <i class="fas fa-user"></i>
                        <span>Profil</span>
                    </a>
                    <a href="{{ route('notifications.user') }}" class="profile-dropdown-item">
                        <i class="fas fa-bell"></i>
                        <span>Notifikasi</span>
                    </a>
                    <div class="profile-dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="profile-dropdown-item logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-menu">
            <div class="menu-section">
                <div class="menu-section-title">Menu Utama</div>
                <a href="{{ route('student.dashboard') }}" class="menu-item">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="menu-item-text">Dashboard</span>
                </a>
                <a href="{{ route('student.tugas') }}" class="menu-item">
                    <i class="fas fa-tasks"></i>
                    <span class="menu-item-text">Tugas</span>
                </a>
                <a href="{{ route('student.materi') }}" class="menu-item">
                    <i class="fas fa-book"></i>
                    <span class="menu-item-text">Materi</span>
                </a>
                <a href="{{ route('student.ujian') }}" class="menu-item">
                    <i class="fas fa-file-alt"></i>
                    <span class="menu-item-text">Ujian</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Kelas & Pembelajaran</div>
                <a href="{{ route('student.class-management') }}" class="menu-item">
                    <i class="fas fa-users"></i>
                    <span class="menu-item-text">Kelas Saya</span>
                </a>
                <a href="{{ route('iot.research-projects') }}" class="menu-item active">
                    <i class="fas fa-flask"></i>
                    <span class="menu-item-text">Research Projects</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Pengaturan</div>
                <a href="{{ route('student.profile') }}" class="menu-item">
                    <i class="fas fa-user"></i>
                    <span class="menu-item-text">Profile</span>
                </a>
                <a href="{{ route('notifications.user') }}" class="menu-item">
                    <i class="fas fa-bell"></i>
                    <span class="menu-item-text">Notifikasi</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-flask"></i>
                Research Projects
            </h1>
            <p class="page-description">Jelajahi proyek penelitian IoT yang tersedia untuk kelas Anda</p>
        </div>

        @if($projects->count() > 0)
            @foreach($projects as $project)
            <div class="project-card">
                <div class="project-header">
                    <div>
                        <h2 class="project-title">{{ $project->project_name }}</h2>
                        <span class="project-status">Aktif</span>
                    </div>
                </div>

                <p class="project-description">
                    {{ $project->description ?: 'Proyek penelitian IoT untuk mengumpulkan dan menganalisis data sensor dari lingkungan sekitar.' }}
                </p>

                <div class="project-details">
                    <div class="detail-item">
                        <div class="detail-label">Kelas</div>
                        <div class="detail-value">{{ $project->kelas->name ?? 'Unknown' }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Guru Pembimbing</div>
                        <div class="detail-value">{{ $project->teacher->name ?? 'Unknown' }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Tanggal Mulai</div>
                        <div class="detail-value">{{ \Carbon\Carbon::parse($project->start_date)->format('d M Y') }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">Berlangsung</div>
                    </div>
                </div>

                <div class="project-actions">
                    <a href="#" class="action-btn" onclick="viewProjectData({{ $project->id }})">
                        <i class="fas fa-chart-line"></i>
                        Lihat Data
                    </a>
                    <a href="#" class="action-btn secondary" onclick="viewProjectDetails({{ $project->id }})">
                        <i class="fas fa-info-circle"></i>
                        Detail Proyek
                    </a>
                </div>
            </div>
            @endforeach
        @else
            <div class="empty-state">
                <i class="fas fa-flask"></i>
                <h3>Belum Ada Proyek Penelitian</h3>
                <p>Belum ada proyek penelitian IoT yang tersedia untuk kelas Anda. Silakan hubungi guru untuk informasi lebih lanjut.</p>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="dashboard-grid">
            <a href="{{ route('student.dashboard') }}" class="card">
                <div class="card-icon blue">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <h3 class="card-title">Dashboard</h3>
                <p class="card-description">Kembali ke dashboard utama</p>
            </a>

            <a href="{{ route('student.tugas') }}" class="card">
                <div class="card-icon green">
                    <i class="fas fa-tasks"></i>
                </div>
                <h3 class="card-title">Tugas</h3>
                <p class="card-description">Lihat tugas yang tersedia</p>
            </a>

            <a href="{{ route('student.materi') }}" class="card">
                <div class="card-icon purple">
                    <i class="fas fa-book"></i>
                </div>
                <h3 class="card-title">Materi</h3>
                <p class="card-description">Akses materi pembelajaran</p>
            </a>
        </div>
    </main>

    <script src="{{ asset('js/superadmin-dashboard.js') }}"></script>
    <script>
        // Profile dropdown functionality
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            
            if (dropdown.style.display === 'none' || dropdown.style.display === '') {
                dropdown.style.display = 'block';
            } else {
                dropdown.style.display = 'none';
            }
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const profileContainer = document.querySelector('.user-profile-container');
            
            if (!profileContainer.contains(event.target)) {
                document.getElementById('profileDropdown').style.display = 'none';
            }
        });

        // View project data
        function viewProjectData(projectId) {
            alert('Fitur lihat data proyek akan segera tersedia untuk proyek ID: ' + projectId);
        }

        // View project details
        function viewProjectDetails(projectId) {
            alert('Fitur detail proyek akan segera tersedia untuk proyek ID: ' + projectId);
        }
    </script>
</body>
</html>
