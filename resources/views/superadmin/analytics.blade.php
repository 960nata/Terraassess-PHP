@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Analitik Dashboard')

@section('styles')
<style>
.analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .analytics-card {
            background-color: #1e293b;
            border-radius: 1rem;
            padding: 2rem;
            border: 1px solid #334155;
        }

        .analytics-card h3 {
            color: #ffffff;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 1rem;
        }

        .metric-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .metric-card {
            background-color: #1e293b;
            border-radius: 1rem;
            padding: 1.5rem;
            border: 1px solid #334155;
            text-align: center;
            transition: all 0.3s ease;
        }

        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.2);
        }

        .metric-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.5rem;
        }

        .metric-label {
            color: #94a3b8;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .metric-change {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
        }

        .metric-change.positive {
            background-color: #10b981;
            color: #ffffff;
        }

        .metric-change.negative {
            background-color: #ef4444;
            color: #ffffff;
        }

        .metric-change.neutral {
            background-color: #6b7280;
            color: #ffffff;
        }

        .analytics-filters {
            background-color: #1e293b;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid #334155;
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #ffffff;
            font-size: 0.9rem;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #2a2a3e;
            border: 2px solid #333;
            border-radius: 8px;
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            background: #333;
        }

        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: #ffffff;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #334155;
            color: #ffffff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #475569;
        }

        .top-performers {
            background-color: #1e293b;
            border-radius: 1rem;
            padding: 2rem;
            border: 1px solid #334155;
            margin-bottom: 2rem;
        }

        .performer-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            background-color: #2a2a3e;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .performer-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .performer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(45deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 600;
        }

        .performer-details h4 {
            color: #ffffff;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .performer-details p {
            color: #94a3b8;
            font-size: 0.875rem;
        }

        .performer-score {
            background: linear-gradient(45deg, #10b981, #059669);
            color: #ffffff;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .analytics-grid {
                grid-template-columns: 1fr;
            }
            
            .metric-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .filter-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 1024px) {
            .sidebar {
                position: fixed !important;
                top: 70px !important;
                left: 0 !important;
                height: calc(100vh - 70px) !important;
                width: 280px !important;
                z-index: 999 !important;
                transform: translateX(-100%) !important;
                transition: transform 0.3s ease !important;
                background: #1e293b !important;
            }
            
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
            }
            
            .mobile-overlay {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                background: rgba(0, 0, 0, 0.5) !important;
                z-index: 998 !important;
                display: none !important;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 280px !important;
                z-index: 999 !important;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 100% !important;
            }
        }
</style>
@endsection

@section('content')
<div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-chart-line"></i>
                Analitik Dashboard
            </h1>
            <p class="page-description">Analisis performa dan statistik sistem pembelajaran</p>
        </div>

        <!-- Key Metrics -->
        <div class="metric-grid">
            <div class="metric-card">
                <div class="metric-value">{{ $totalUsers ?? 0 }}</div>
                <div class="metric-label">Total Pengguna</div>
                <div class="metric-change positive">+12% dari bulan lalu</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ $activeUsers ?? 0 }}</div>
                <div class="metric-label">Pengguna Aktif</div>
                <div class="metric-change positive">+8% dari bulan lalu</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ $totalTasks ?? 0 }}</div>
                <div class="metric-label">Total Tugas</div>
                <div class="metric-change positive">+15% dari bulan lalu</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ $completedTasks ?? 0 }}</div>
                <div class="metric-label">Tugas Selesai</div>
                <div class="metric-change positive">+22% dari bulan lalu</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ $averageScore ?? 0 }}%</div>
                <div class="metric-label">Rata-rata Nilai</div>
                <div class="metric-change positive">+5% dari bulan lalu</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ $totalClasses ?? 0 }}</div>
                <div class="metric-label">Total Kelas</div>
                <div class="metric-change neutral">0% dari bulan lalu</div>
            </div>
        </div>

        <!-- Analytics Filters -->
        <div class="analytics-filters">
            <h2 style="color: #ffffff; margin-bottom: 1.5rem; font-size: 1.25rem;">
                <i class="fas fa-filter me-2"></i>Filter Analitik
            </h2>
            
            <form action="{{ route('superadmin.analytics.filter') }}" method="GET">
                <div class="filter-row">
                    <div class="form-group">
                        <label for="filter_period">Periode</label>
                        <select id="filter_period" name="filter_period">
                            <option value="7" {{ (isset($filters['filter_period']) && $filters['filter_period'] == '7') ? 'selected' : '' }}>7 Hari Terakhir</option>
                            <option value="30" {{ (isset($filters['filter_period']) && $filters['filter_period'] == '30') ? 'selected' : '' }}>30 Hari Terakhir</option>
                            <option value="90" {{ (isset($filters['filter_period']) && $filters['filter_period'] == '90') ? 'selected' : '' }}>90 Hari Terakhir</option>
                            <option value="365" {{ (isset($filters['filter_period']) && $filters['filter_period'] == '365') ? 'selected' : '' }}>1 Tahun Terakhir</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="filter_class">Kelas</label>
                        <select id="filter_class" name="filter_class">
                            <option value="">Semua Kelas</option>
                            @foreach($classes ?? [] as $class)
                                <option value="{{ $class->id }}" {{ (isset($filters['filter_class']) && $filters['filter_class'] == $class->id) ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="filter_subject">Mata Pelajaran</label>
                        <select id="filter_subject" name="filter_subject">
                            <option value="">Semua Mata Pelajaran</option>
                            @foreach($subjects ?? [] as $subject)
                                <option value="{{ $subject->id }}" {{ (isset($filters['filter_subject']) && $filters['filter_subject'] == $subject->id) ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="filter_type">Tipe Analitik</label>
                        <select id="filter_type" name="filter_type">
                            <option value="overview" {{ (isset($filters['filter_type']) && $filters['filter_type'] == 'overview') ? 'selected' : '' }}>Overview</option>
                            <option value="performance" {{ (isset($filters['filter_type']) && $filters['filter_type'] == 'performance') ? 'selected' : '' }}>Performa</option>
                            <option value="engagement" {{ (isset($filters['filter_type']) && $filters['filter_type'] == 'engagement') ? 'selected' : '' }}>Engagement</option>
                            <option value="learning" {{ (isset($filters['filter_type']) && $filters['filter_type'] == 'learning') ? 'selected' : '' }}>Pembelajaran</option>
                        </select>
                    </div>
                </div>
                
                <div class="filter-actions" style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-search"></i>
                        Terapkan Filter
                    </button>
                    <a href="{{ route('superadmin.analytics') }}" class="btn-secondary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-times"></i>
                        Reset Filter
                    </a>
                </div>
            </form>
        </div>

        <!-- Analytics Charts -->
        <div class="analytics-grid">
            <!-- User Activity Chart -->
            <div class="analytics-card">
                <h3><i class="fas fa-users"></i>Aktivitas Pengguna</h3>
                <div class="chart-container">
                    <canvas id="userActivityChart"></canvas>
                </div>
            </div>

            <!-- Task Completion Chart -->
            <div class="analytics-card">
                <h3><i class="fas fa-tasks"></i>Penyelesaian Tugas</h3>
                <div class="chart-container">
                    <canvas id="taskCompletionChart"></canvas>
                </div>
            </div>

            <!-- Performance Distribution -->
            <div class="analytics-card">
                <h3><i class="fas fa-chart-pie"></i>Distribusi Performa</h3>
                <div class="chart-container">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>

            <!-- Learning Progress -->
            <div class="analytics-card">
                <h3><i class="fas fa-graduation-cap"></i>Progress Pembelajaran</h3>
                <div class="chart-container">
                    <canvas id="learningProgressChart"></canvas>
                </div>
            </div>

            <!-- Class Performance -->
            <div class="analytics-card">
                <h3><i class="fas fa-chalkboard"></i>Performa Kelas</h3>
                <div class="chart-container">
                    <canvas id="classPerformanceChart"></canvas>
                </div>
            </div>

            <!-- Subject Popularity -->
            <div class="analytics-card">
                <h3><i class="fas fa-book"></i>Popularitas Mata Pelajaran</h3>
                <div class="chart-container">
                    <canvas id="subjectPopularityChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Performers -->
        <div class="top-performers">
            <h2 style="color: #ffffff; margin-bottom: 1.5rem; font-size: 1.25rem;">
                <i class="fas fa-trophy"></i>Top Performers
            </h2>
            
            <div class="performer-item">
                <div class="performer-info">
                    <div class="performer-avatar">JD</div>
                    <div class="performer-details">
                        <h4>John Doe</h4>
                        <p>Kelas 10A - Matematika</p>
                    </div>
                </div>
                <div class="performer-score">95%</div>
            </div>
            
            <div class="performer-item">
                <div class="performer-info">
                    <div class="performer-avatar">JS</div>
                    <div class="performer-details">
                        <h4>Jane Smith</h4>
                        <p>Kelas 10B - Fisika</p>
                    </div>
                </div>
                <div class="performer-score">92%</div>
            </div>
            
            <div class="performer-item">
                <div class="performer-info">
                    <div class="performer-avatar">MJ</div>
                    <div class="performer-details">
                        <h4>Mike Johnson</h4>
                        <p>Kelas 11A - Kimia</p>
                    </div>
                </div>
                <div class="performer-score">89%</div>
            </div>
            
            <div class="performer-item">
                <div class="performer-info">
                    <div class="performer-avatar">SW</div>
                    <div class="performer-details">
                        <h4>Sarah Wilson</h4>
                        <p>Kelas 10C - Biologi</p>
                    </div>
                </div>
                <div class="performer-score">87%</div>
            </div>
            
            <div class="performer-item">
                <div class="performer-info">
                    <div class="performer-avatar">DB</div>
                    <div class="performer-details">
                        <h4>David Brown</h4>
                        <p>Kelas 11B - Matematika</p>
                    </div>
                </div>
                <div class="performer-score">85%</div>
            </div>
        </div>
@endsection
