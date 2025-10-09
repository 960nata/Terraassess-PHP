@php
    $roleId = $roleId ?? Auth()->user()->roles_id;
    $user = $user ?? Auth()->user();
    
    // Role configuration
    $roleConfig = [
        1 => ['title' => 'Super Admin', 'icon' => 'fas fa-crown', 'initial' => 'SA', 'color' => 'purple'],
        2 => ['title' => 'Admin', 'icon' => 'fas fa-user-shield', 'initial' => 'AD', 'color' => 'blue'],
        3 => ['title' => 'Guru', 'icon' => 'fas fa-chalkboard-teacher', 'initial' => 'GU', 'color' => 'green'],
        4 => ['title' => 'Siswa', 'icon' => 'fas fa-user-graduate', 'initial' => 'SI', 'color' => 'orange']
    ];
    
    $currentRole = $roleConfig[$roleId] ?? $roleConfig[1];
    $roleTitle = $currentRole['title'];
    $roleIcon = $currentRole['icon'];
    $roleInitial = $currentRole['initial'];
    $roleColor = $currentRole['color'];
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contoh Penggunaan Komponen Header & Sidebar - Terra Assessment</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Include header styles -->
    @include('components.unified-header-styles')
    
    <!-- Include custom CSS -->
    <link href="{{ asset('css/superadmin-dashboard.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        /* Custom styles untuk halaman ini */
        .example-content {
            padding: 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: calc(100vh - 70px);
            color: white;
        }
        
        .example-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .example-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-align: center;
        }
        
        .example-description {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
            text-align: center;
            opacity: 0.9;
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .feature-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #fbbf24;
        }
        
        .feature-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .feature-description {
            opacity: 0.8;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <!-- Include mobile overlay -->
    @include('components.mobile-overlay')
    
    <!-- Include unified header -->
    @include('components.unified-header')
    
    <!-- Include unified sidebar -->
    @include('components.unified-sidebar')

    <!-- Main Content -->
    <main class="main-content">
        <div class="example-content">
            <div class="example-card">
                <h1 class="example-title">
                    <i class="fas fa-puzzle-piece"></i>
                    Komponen Header & Sidebar Terpisah
                </h1>
                <p class="example-description">
                    Halaman ini menunjukkan bagaimana menggunakan komponen header dan sidebar yang telah dipisahkan 
                    dari layout utama. Komponen ini dapat digunakan di halaman manapun dengan mudah.
                </p>
                
                <div class="feature-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-code"></i>
                        </div>
                        <h3 class="feature-title">Mudah Digunakan</h3>
                        <p class="feature-description">
                            Cukup include komponen header dan sidebar di halaman manapun dengan satu baris kode.
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-sync-alt"></i>
                        </div>
                        <h3 class="feature-title">Konsisten</h3>
                        <p class="feature-description">
                            Semua halaman menggunakan header dan sidebar yang sama, memastikan konsistensi UI.
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3 class="feature-title">Responsive</h3>
                        <p class="feature-description">
                            Header dan sidebar otomatis menyesuaikan dengan ukuran layar (desktop, tablet, mobile).
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h3 class="feature-title">Dapat Dikustomisasi</h3>
                        <p class="feature-description">
                            Komponen dapat disesuaikan dengan kebutuhan halaman tertentu tanpa mengubah komponen utama.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="example-card">
                <h2 style="font-size: 1.5rem; margin-bottom: 1rem;">
                    <i class="fas fa-info-circle"></i>
                    Cara Penggunaan
                </h2>
                <div style="background: rgba(0, 0, 0, 0.2); padding: 1.5rem; border-radius: 8px; font-family: monospace; font-size: 0.9rem; line-height: 1.6;">
                    <div style="color: #10b981; margin-bottom: 1rem;">// 1. Include komponen di bagian head</div>
                    <div style="margin-bottom: 0.5rem;">@include('components.unified-header-styles')</div>
                    
                    <div style="color: #10b981; margin: 1rem 0;">// 2. Include komponen di body</div>
                    <div style="margin-bottom: 0.5rem;">@include('components.mobile-overlay')</div>
                    <div style="margin-bottom: 0.5rem;">@include('components.unified-header')</div>
                    <div style="margin-bottom: 0.5rem;">@include('components.unified-sidebar')</div>
                    
                    <div style="color: #10b981; margin: 1rem 0;">// 3. Include JavaScript di bagian bawah</div>
                    <div style="margin-bottom: 0.5rem;">@include('components.unified-header-scripts')</div>
                </div>
            </div>
        </div>
    </main>

    <!-- Include JavaScript -->
    <script src="{{ asset('js/superadmin-dashboard.js') }}"></script>
    @include('components.unified-header-scripts')
</body>
</html>
