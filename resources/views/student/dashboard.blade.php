@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Student Dashboard')

@section('additional-styles')
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
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
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
        
        .card-icon.teal {
            background: linear-gradient(135deg, #14b8a6, #0d9488);
            color: white;
        }
        
        .card:hover .card-icon {
            transform: scale(1.1);
            }
            
            .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: white;
            margin-bottom: 0.5rem;
            }
            
            .card-description {
            color: #94a3b8;
            font-size: 0.875rem;
            line-height: 1.5;
            flex-grow: 1;
        }
        
        .welcome-banner {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(147, 51, 234, 0.1));
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 16px;
            padding: 2rem;
            margin: 2rem 0;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .welcome-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            flex-shrink: 0;
        }
        
        .welcome-content {
            flex-grow: 1;
        }
        
        .welcome-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
        }
        
        .welcome-description {
            color: #94a3b8;
            font-size: 1rem;
            line-height: 1.6;
        }
        
        .page-header {
            margin-bottom: 2rem;
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .page-title i {
            color: #3b82f6;
        }
        
        .page-description {
            color: #94a3b8;
            font-size: 1.125rem;
        }
        
        /* Dashboard Tabs Section */
        .dashboard-tabs-section {
            margin-top: 3rem;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            overflow: hidden;
        }
        
        .tabs-header {
            background: rgba(30, 41, 59, 0.8);
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .tabs-title {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }
        
        .tabs-nav {
            display: flex;
            gap: 0.5rem;
        }
        
        .tab-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #94a3b8;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .tab-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateY(-2px);
        }
        
        .tab-btn.active {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            border-color: #3b82f6;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }
        
        .tab-count {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            min-width: 20px;
            text-align: center;
        }
        
        .tab-btn.active .tab-count {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .tabs-content {
            padding: 2rem;
        }
        
        .tab-pane {
            display: none;
        }
        
        .tab-pane.active {
            display: block;
        }
        
        .list-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .list-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s ease;
        }
        
        .list-item:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        
        .list-item-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            flex-shrink: 0;
        }
        
        .list-item-icon.tugas {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }
        
        .list-item-icon.materi {
            background: linear-gradient(135deg, #10b981, #059669);
        }
        
        .list-item-icon.ujian {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        }
        
        .list-item-content {
            flex-grow: 1;
        }
        
        .list-item-title {
            color: white;
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .list-item-description {
            color: #94a3b8;
                font-size: 0.875rem;
            line-height: 1.5;
            margin-bottom: 0.75rem;
            }
            
            .list-item-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .meta-item i {
            color: #94a3b8;
        }
        
        .meta-item.status-pending {
            color: #f59e0b;
        }
        
        .meta-item.status-submitted {
            color: #10b981;
        }
        
        .meta-item.status-graded {
            color: #3b82f6;
        }
        
        .meta-item.status-completed {
            color: #10b981;
        }
        
        .list-item-actions {
            flex-shrink: 0;
        }
        
        .btn-action {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
            color: white;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #64748b;
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #475569;
        }
        
        .empty-state h3 {
            color: #94a3b8;
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
        }
        
        .empty-state p {
            color: #64748b;
            font-size: 0.875rem;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .welcome-banner {
                flex-direction: column;
                text-align: center;
                padding: 1.5rem;
            }
            
            .welcome-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
            
            .tabs-header {
            flex-direction: column;
                align-items: stretch;
                padding: 1rem;
            }
            
            .tabs-nav {
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .tab-btn {
                flex: 1;
                min-width: 100px;
                justify-content: center;
            }
            
            .list-item {
                padding: 1rem;
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }
            
            .list-item-meta {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .list-item-actions {
                align-self: stretch;
            }
            
            .btn-action {
                justify-content: center;
            }
        }
    </style>
@endsection

@section('content')
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-graduation-cap"></i>
                Student Dashboard
            </h1>
            <p class="page-description">Selamat datang di platform pembelajaran Terra Assessment</p>
        </div>

        <div class="welcome-banner">
            <div class="welcome-icon">
                <i class="fas fa-star"></i>
            </div>
            <div class="welcome-content">
                <h2 class="welcome-title">Selamat datang, {{ Auth::user()->name }}!</h2>
                <p class="welcome-description">Mari mulai perjalanan pembelajaran Anda dengan mengakses materi, tugas, dan ujian yang tersedia.</p>
            </div>
        </div>

        <div class="dashboard-grid">
            <!-- Row 1 -->
            <a href="{{ route('student.tugas') }}" class="card">
                <div class="card-icon blue">
                    <i class="fas fa-tasks"></i>
                </div>
                <h3 class="card-title">Tugas & Assignment</h3>
                <p class="card-description">Lihat dan kerjakan tugas yang diberikan oleh pengajar</p>
            </a>

            <a href="{{ route('student.materi') }}" class="card">
                <div class="card-icon green">
                    <i class="fas fa-book"></i>
                </div>
                <h3 class="card-title">Materi Pembelajaran</h3>
                <p class="card-description">Akses materi pembelajaran dan sumber daya edukatif</p>
            </a>

            <a href="{{ route('student.ujian') }}" class="card">
                <div class="card-icon purple">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3 class="card-title">Ujian & Test</h3>
                <p class="card-description">Ikuti ujian dan test untuk mengukur pemahaman Anda</p>
            </a>

            <!-- Row 2 -->
            <a href="{{ route('student.class-management') }}" class="card">
                <div class="card-icon orange">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="card-title">Kelas Saya</h3>
            <p class="card-description">Kelola kelas dan lihat informasi kelas yang Anda ikuti</p>
            </a>

            <a href="{{ route('student.iot') }}" class="card">
                <div class="card-icon teal">
                <i class="fas fa-microchip"></i>
                </div>
            <h3 class="card-title">Penelitian IoT</h3>
            <p class="card-description">Eksplorasi proyek penelitian IoT dan teknologi terbaru</p>
            </a>

            <a href="{{ route('student.profile') }}" class="card">
                <div class="card-icon red">
                    <i class="fas fa-user"></i>
                </div>
                <h3 class="card-title">Profile Saya</h3>
                <p class="card-description">Kelola informasi profil dan pengaturan akun Anda</p>
            </a>

            <a href="{{ route('student.notifications') }}" class="card">
                <div class="card-icon yellow">
                    <i class="fas fa-bell"></i>
                </div>
                <h3 class="card-title">Notifikasi</h3>
                <p class="card-description">Lihat notifikasi dan pengumuman terbaru</p>
            </a>
        </div>

    <!-- Dashboard Tabs Section -->
    <div class="dashboard-tabs-section">
            <div class="tabs-header">
            <h2 class="tabs-title">Aktivitas Terbaru</h2>
                <div class="tabs-nav">
                <button class="tab-btn active" onclick="switchTab('tugas')" id="tab-tugas">
                        <i class="fas fa-tasks"></i>
                        <span>Tugas</span>
                    <span class="tab-count">{{ $tugasTerbaru->count() }}</span>
                    </button>
                <button class="tab-btn" onclick="switchTab('materi')" id="tab-materi">
                        <i class="fas fa-book"></i>
                        <span>Materi</span>
                    <span class="tab-count">{{ $materiTerbaru->count() }}</span>
                    </button>
                <button class="tab-btn" onclick="switchTab('ujian')" id="tab-ujian">
                        <i class="fas fa-file-alt"></i>
                        <span>Ujian</span>
                    <span class="tab-count">{{ $ujianTerbaru->count() }}</span>
                    </button>
                </div>
            </div>

        <div class="tabs-content">
            <!-- Tugas Tab -->
            <div class="tab-pane active" id="pane-tugas">
                <div class="list-container">
                    @if($tugasTerbaru->count() > 0)
                        @foreach($tugasTerbaru as $tugasItem)
                            @php
                                $userTugas = \App\Models\UserTugas::where('user_id', auth()->id())
                                    ->where('tugas_id', $tugasItem->id)
                                    ->first();
                                $status = $userTugas ? $userTugas->status : 'pending';
                                $deadline = \Carbon\Carbon::parse($tugasItem->due);
                                $now = \Carbon\Carbon::now();
                                $isOverdue = $now->gt($deadline);
                                $isSoon = $now->diffInDays($deadline) <= 2 && !$isOverdue;
                            @endphp
                        <div class="list-item">
                                <div class="list-item-icon tugas">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <div class="list-item-content">
                                    <h4 class="list-item-title">{{ $tugasItem->name }}</h4>
                                    <p class="list-item-description">{{ Str::limit($tugasItem->content, 100) }}</p>
                                <div class="list-item-meta">
                                        <span class="meta-item">
                                            <i class="fas fa-book"></i>
                                            {{ $tugasItem->mapel_name }}
                                        </span>
                                        <span class="meta-item">
                                            <i class="fas fa-calendar"></i>
                                            {{ $deadline->format('d M Y, H:i') }}
                                        </span>
                                        <span class="meta-item status-{{ $status }}">
                                            @if($status === 'pending')
                                                @if($isOverdue)
                                                    Terlambat
                                                @elseif($isSoon)
                                                    Segera Deadline
                                                @else
                                                    Belum Dikerjakan
                                                @endif
                                            @elseif($status === 'submitted')
                                                Sudah Dikumpulkan
                                            @elseif($status === 'graded')
                                                Sudah Dinilai
                                            @endif
                                        </span>
                                </div>
                            </div>
                                <div class="list-item-actions">
                                    <a href="{{ route('student.tugas') }}" class="btn-action">
                                        <i class="fas fa-eye"></i>
                                        Lihat Detail
                                </a>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-tasks"></i>
                            <h3>Tidak ada tugas</h3>
                            <p>Belum ada tugas yang diberikan untuk Anda</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Materi Tab -->
            <div class="tab-pane" id="pane-materi">
                <div class="list-container">
                    @if($materiTerbaru->count() > 0)
                        @foreach($materiTerbaru as $materiItem)
                        <div class="list-item">
                                <div class="list-item-icon materi">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="list-item-content">
                                    <h4 class="list-item-title">{{ $materiItem->name }}</h4>
                                    <p class="list-item-description">{{ Str::limit($materiItem->content, 100) }}</p>
                                <div class="list-item-meta">
                                        <span class="meta-item">
                                            <i class="fas fa-book"></i>
                                            {{ $materiItem->mapel_name }}
                                        </span>
                                        <span class="meta-item">
                                            <i class="fas fa-calendar"></i>
                                            {{ \Carbon\Carbon::parse($materiItem->created_at)->format('d M Y') }}
                                        </span>
                                        <span class="meta-item">
                                            <i class="fas fa-user"></i>
                                            Pengajar
                                        </span>
                                </div>
                            </div>
                                <div class="list-item-actions">
                                    <a href="{{ route('student.materi.detail', $materiItem->id) }}" class="btn-action">
                                        <i class="fas fa-eye"></i>
                                        Lihat Materi
                                </a>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-book"></i>
                            <h3>Tidak ada materi</h3>
                            <p>Belum ada materi pembelajaran yang tersedia</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Ujian Tab -->
            <div class="tab-pane" id="pane-ujian">
                <div class="list-container">
                    @if($ujianTerbaru->count() > 0)
                        @foreach($ujianTerbaru as $ujianItem)
                            @php
                                $userUjian = \App\Models\UserUjian::where('user_id', auth()->id())
                                    ->where('ujian_id', $ujianItem->id)
                                    ->first();
                                $status = $userUjian ? $userUjian->status : 'pending';
                                $deadline = \Carbon\Carbon::parse($ujianItem->due);
                                $now = \Carbon\Carbon::now();
                                $isOverdue = $now->gt($deadline);
                                $isSoon = $now->diffInDays($deadline) <= 2 && !$isOverdue;
                            @endphp
                        <div class="list-item">
                                <div class="list-item-icon ujian">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="list-item-content">
                                    <h4 class="list-item-title">{{ $ujianItem->name }}</h4>
                                    <p class="list-item-description">{{ $ujianItem->tipe ?? 'Ujian' }}</p>
                                <div class="list-item-meta">
                                        <span class="meta-item">
                                            <i class="fas fa-book"></i>
                                            {{ $ujianItem->mapel_name }}
                                        </span>
                                        <span class="meta-item">
                                            <i class="fas fa-calendar"></i>
                                            {{ $deadline->format('d M Y, H:i') }}
                                        </span>
                                        <span class="meta-item status-{{ $status }}">
                                            @if($status === 'pending')
                                                @if($isOverdue)
                                                    Terlambat
                                                @elseif($isSoon)
                                                    Segera Deadline
                                                @else
                                                    Belum Dikerjakan
                                                @endif
                                            @elseif($status === 'completed')
                                                Sudah Selesai
                                            @elseif($status === 'graded')
                                                Sudah Dinilai
                                            @endif
                                        </span>
                                </div>
                            </div>
                                <div class="list-item-actions">
                                    <a href="{{ route('student.ujian') }}" class="btn-action">
                                        <i class="fas fa-eye"></i>
                                        Lihat Detail
                                </a>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-file-alt"></i>
                            <h3>Tidak ada ujian</h3>
                            <p>Belum ada ujian yang tersedia untuk Anda</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('additional-scripts')
    <script>
        // Tab switching functionality
        function switchTab(tabName) {
            // Remove active class from all tabs and panes
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('active');
            });
            
            // Add active class to selected tab and pane
            document.getElementById('tab-' + tabName).classList.add('active');
            document.getElementById('pane-' + tabName).classList.add('active');
        }
    </script>
@endsection