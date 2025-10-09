@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Manajemen Tugas')

@section('styles')
<style>
.task-filters {
            background-color: #1e293b;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid #334155;
        }

        .filter-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr auto;
            gap: 1rem;
            align-items: end;
        }

        .form-group {
            margin-bottom: 0;
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

        .btn-success {
            background: #10b981;
            color: #ffffff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background: #059669;
        }

        .btn-warning {
            background: #f59e0b;
            color: #ffffff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-warning:hover {
            background: #d97706;
        }

        .btn-danger {
            background: #ef4444;
            color: #ffffff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .tasks-table {
            background-color: #1e293b;
            border-radius: 1rem;
            padding: 2rem;
            border: 1px solid #334155;
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #334155;
        }

        .table th {
            background-color: #2a2a3e;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table td {
            color: #cbd5e1;
        }

        .table tbody tr:hover {
            background-color: #2a2a3e;
        }

        /* Mobile Cards */
        .mobile-cards {
            display: none;
        }

        .task-card {
            background-color: #1e293b;
            border: 1px solid #334155;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .task-card:hover {
            background-color: #2a2a3e;
            border-color: #475569;
        }

        .task-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .task-card-title {
            color: #ffffff;
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
            flex: 1;
        }

        .task-card-badges {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .task-card-description {
            color: #94a3b8;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        .task-card-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #cbd5e1;
            font-size: 0.875rem;
        }

        .info-item i {
            color: #667eea;
            width: 16px;
        }

        .task-card-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .task-card-actions button {
            flex: 1;
            min-width: 80px;
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
        }

        .task-title {
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.25rem;
        }

        .task-description {
            color: #94a3b8;
            font-size: 0.875rem;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-active {
            background-color: #10b981;
            color: #ffffff;
        }

        .status-draft {
            background-color: #f59e0b;
            color: #ffffff;
        }

        .status-completed {
            background-color: #6b7280;
            color: #ffffff;
        }

        .difficulty-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .difficulty-easy {
            background-color: #10b981;
            color: #ffffff;
        }

        .difficulty-medium {
            background-color: #f59e0b;
            color: #ffffff;
        }

        .difficulty-hard {
            background-color: #ef4444;
            color: #ffffff;
        }

        .category-badge {
            background-color: #3b82f6;
            color: #ffffff;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .task-actions {
            display: flex;
            gap: 0.5rem;
        }



        /* Task Type Cards - Modern Design */
        .task-type-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .task-type-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .task-type-card:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(59, 130, 246, 0.5);
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }

        .task-type-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .task-type-card.essay::before {
            background: linear-gradient(90deg, #8b5cf6, #a855f7);
        }

        .task-type-card.individual::before {
            background: linear-gradient(90deg, #f59e0b, #d97706);
        }

        .task-type-card.group::before {
            background: linear-gradient(90deg, #10b981, #059669);
        }

        .task-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .task-card-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .task-type-card.essay .task-card-icon {
            background: linear-gradient(135deg, #8b5cf6, #a855f7);
            box-shadow: 0 8px 20px rgba(139, 92, 246, 0.3);
        }

        .task-type-card.individual .task-card-icon {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
        }

        .task-type-card.group .task-card-icon {
            background: linear-gradient(135deg, #10b981, #059669);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }

        .task-card-stats {
            text-align: right;
        }

        .task-count {
            display: block;
            font-size: 1.75rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1;
        }

        .task-label {
            font-size: 0.875rem;
            color: #94a3b8;
            margin-top: 0.25rem;
        }

        .task-card-content {
            margin-bottom: 1.5rem;
        }

        .task-type-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.75rem;
            line-height: 1.3;
        }

        .task-type-description {
            color: #94a3b8;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .task-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .task-card-action {
            color: #667eea;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .task-type-card.essay .task-card-action {
            color: #8b5cf6;
        }

        .task-type-card.individual .task-card-action {
            color: #f59e0b;
        }

        .task-type-card.group .task-card-action {
            color: #10b981;
        }

        .arrow-icon {
            transition: transform 0.3s ease;
        }

        .task-type-card:hover .arrow-icon {
            transform: translateX(4px);
        }

        /* Tablet Responsive - 2x2 Grid */
        @media (max-width: 1024px) {
            .task-type-cards {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.25rem;
            }
        }

        @media (max-width: 768px) {
            .filter-row {
                grid-template-columns: 1fr 1fr;
                gap: 0.75rem;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .task-type-cards {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
            
            .task-actions {
                flex-direction: column;
            }

            /* Show mobile cards, hide desktop table */
            .desktop-table {
                display: none;
            }
            
            .mobile-cards {
                display: block;
            }
            
            .task-card-info {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
            
            .task-card-actions {
                flex-direction: column;
            }
            
            .task-card-actions button {
                flex: none;
                width: 100%;
            }
        }

        /* Mobile - 2x2 Grid (tetap 2x2) */
        @media (max-width: 480px) {
            .filter-row {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
            
            .task-type-cards {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
            }
            
            .task-type-card {
                padding: 1.5rem;
            }
            
            .task-type-title {
                font-size: 1rem;
            }
            
            .task-type-description {
                font-size: 0.8rem;
            }

            /* Mobile cards adjustments */
            .task-card {
                padding: 1rem;
            }
            
            .task-card-title {
                font-size: 1rem;
            }
            
            .task-card-description {
                font-size: 0.85rem;
            }
        }

        /* Very Small Mobile - Tetap 2x2 */
        @media (max-width: 360px) {
            .task-type-cards {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
            }
            
            .task-type-card {
                padding: 1rem;
            }
            
            .task-type-title {
                font-size: 0.9rem;
            }
            
            .task-type-description {
                font-size: 0.75rem;
            }
        }

        /* SIMPLE MOBILE SIDEBAR - GUARANTEED TO WORK */
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
                <i class="fas fa-book"></i>
                Manajemen Tugas
            </h1>
            <p class="page-description">Kelola tugas per kelas dengan kategorisasi dan tingkat kesulitan</p>
        </div>


        <!-- Task Type Cards -->
        <div class="task-type-cards">
            <div class="task-type-card" onclick="createMultipleChoiceTask()">
                <div class="task-card-header">
                    <div class="task-card-icon">
                        <i class="fas fa-list-ul"></i>
                    </div>
                    <div class="task-card-stats">
                        <span class="task-count">{{ $tugasPilihanGanda ?? 0 }}</span>
                        <span class="task-label">Tugas</span>
                    </div>
                </div>
                <div class="task-card-content">
                    <h3 class="task-type-title">Pilihan Ganda</h3>
                    <p class="task-type-description">Buat tugas dengan soal pilihan ganda untuk evaluasi cepat</p>
                </div>
                <div class="task-card-footer">
                    <span class="task-card-action">Buat Tugas <i class="fas fa-arrow-right arrow-icon"></i></span>
                </div>
            </div>

            <div class="task-type-card essay" onclick="createEssayTask()">
                <div class="task-card-header">
                    <div class="task-card-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="task-card-stats">
                        <span class="task-count">{{ $tugasEssay ?? 0 }}</span>
                        <span class="task-label">Tugas</span>
                    </div>
                </div>
                <div class="task-card-content">
                    <h3 class="task-type-title">Essay</h3>
                    <p class="task-type-description">Buat tugas essay untuk penilaian mendalam</p>
                </div>
                <div class="task-card-footer">
                    <span class="task-card-action">Buat Tugas <i class="fas fa-arrow-right arrow-icon"></i></span>
                </div>
            </div>

            <div class="task-type-card individual" onclick="createIndividualTask()">
                <div class="task-card-header">
                    <div class="task-card-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="task-card-stats">
                        <span class="task-count">{{ $tugasMandiri ?? 0 }}</span>
                        <span class="task-label">Tugas</span>
                    </div>
                </div>
                <div class="task-card-content">
                    <h3 class="task-type-title">Mandiri</h3>
                    <p class="task-type-description">Buat tugas individual untuk pembelajaran mandiri</p>
                </div>
                <div class="task-card-footer">
                    <span class="task-card-action">Buat Tugas <i class="fas fa-arrow-right arrow-icon"></i></span>
                </div>
            </div>

            <div class="task-type-card group" onclick="createGroupTask()">
                <div class="task-card-header">
                    <div class="task-card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="task-card-stats">
                        <span class="task-count">{{ $tugasKelompok ?? 0 }}</span>
                        <span class="task-label">Tugas</span>
                    </div>
                </div>
                <div class="task-card-content">
                    <h3 class="task-type-title">Kelompok</h3>
                    <p class="task-type-description">Buat tugas kelompok untuk kolaborasi</p>
                </div>
                <div class="task-card-footer">
                    <span class="task-card-action">Buat Tugas <i class="fas fa-arrow-right arrow-icon"></i></span>
                </div>
            </div>
        </div>


        <!-- Task Filters - Simplified -->
        <div class="task-filters">
            <form action="{{ route('task-management') }}" method="GET" class="filter-form">
                <div class="filter-row">
                    <div class="form-group">
                        <select id="filter_class" name="filter_class">
                            <option value="">Semua Kelas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ (isset($filters['filter_class']) && $filters['filter_class'] == $class->id) ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <select id="filter_subject" name="filter_subject">
                            <option value="">Semua Mata Pelajaran</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ (isset($filters['filter_subject']) && $filters['filter_subject'] == $subject->id) ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <select id="filter_status" name="filter_status">
                            <option value="">Semua Status</option>
                            <option value="active" {{ (isset($filters['filter_status']) && $filters['filter_status'] == 'active') ? 'selected' : '' }}>Aktif</option>
                            <option value="draft" {{ (isset($filters['filter_status']) && $filters['filter_status'] == 'draft') ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-search"></i>
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tasks Table -->
        <div class="tasks-table">
            <h2 style="color: #ffffff; margin-bottom: 1.5rem; font-size: 1.25rem;">
                <i class="fas fa-list me-2"></i>Daftar Tugas
            </h2>
            
            @if($tasks->count() > 0)
                <!-- Desktop Table -->
                <div class="table-responsive desktop-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Judul Tugas</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>Kesulitan</th>
                                <th>Status</th>
                                <th>Deadline</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                                <tr>
                                    <td>
                                        <div class="task-title">{{ $task->name }}</div>
                                        <div class="task-description">{{ Str::limit($task->content, 100) }}</div>
                                    </td>
                                    <td>{{ $task->KelasMapel->Kelas->name ?? 'N/A' }}</td>
                                    <td>{{ $task->KelasMapel->Mapel->name ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $difficultyMap = [1 => 'easy', 2 => 'medium', 3 => 'hard'];
                                            $difficultyLabels = ['easy' => 'Mudah', 'medium' => 'Sedang', 'hard' => 'Sulit'];
                                            $difficulty = $difficultyMap[$task->tipe] ?? 'medium';
                                        @endphp
                                        <span class="difficulty-badge difficulty-{{ $difficulty }}">
                                            {{ $difficultyLabels[$difficulty] }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($task->isHidden == 0)
                                            @if($task->due && $task->due < now())
                                                <span class="status-badge status-completed">Selesai</span>
                                            @else
                                                <span class="status-badge status-active">Aktif</span>
                                            @endif
                                        @else
                                            <span class="status-badge status-draft">Draft</span>
                                        @endif
                                    </td>
                                    <td>{{ $task->due ? \Carbon\Carbon::parse($task->due)->format('d M Y H:i') : 'N/A' }}</td>
                                    <td>
                                        <div class="task-actions">
                                            <button class="btn-secondary" onclick="editTask({{ $task->id }})">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn-success" onclick="viewSubmissions({{ $task->id }})">
                                                <i class="fas fa-eye"></i> Lihat
                                            </button>
                                            @if($task->isHidden == 1)
                                                <button class="btn-warning" onclick="publishTask({{ $task->id }})">
                                                    <i class="fas fa-paper-plane"></i> Publikasi
                                                </button>
                                            @endif
                                            <button class="btn-danger" onclick="deleteTask({{ $task->id }})">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="mobile-cards">
                    @foreach($tasks as $task)
                        <div class="task-card">
                            <div class="task-card-header">
                                <h3 class="task-card-title">{{ $task->name }}</h3>
                                <div class="task-card-badges">
                                    @php
                                        $difficultyMap = [1 => 'easy', 2 => 'medium', 3 => 'hard'];
                                        $difficultyLabels = ['easy' => 'Mudah', 'medium' => 'Sedang', 'hard' => 'Sulit'];
                                        $difficulty = $difficultyMap[$task->tipe] ?? 'medium';
                                    @endphp
                                    <span class="difficulty-badge difficulty-{{ $difficulty }}">
                                        {{ $difficultyLabels[$difficulty] }}
                                    </span>
                                    @if($task->isHidden == 0)
                                        @if($task->due && $task->due < now())
                                            <span class="status-badge status-completed">Selesai</span>
                                        @else
                                            <span class="status-badge status-active">Aktif</span>
                                        @endif
                                    @else
                                        <span class="status-badge status-draft">Draft</span>
                                    @endif
                                </div>
                            </div>
                            
                            @if($task->content)
                                <p class="task-card-description">{{ Str::limit($task->content, 100) }}</p>
                            @endif
                            
                            <div class="task-card-info">
                                <div class="info-item">
                                    <i class="fas fa-graduation-cap"></i>
                                    <span>{{ $task->KelasMapel->Kelas->name ?? 'N/A' }}</span>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-book"></i>
                                    <span>{{ $task->KelasMapel->Mapel->name ?? 'N/A' }}</span>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-calendar"></i>
                                    <span>{{ $task->due ? \Carbon\Carbon::parse($task->due)->format('d M Y H:i') : 'N/A' }}</span>
                                </div>
                            </div>
                            
                            <div class="task-card-actions">
                                <button class="btn-secondary" onclick="editTask({{ $task->id }})">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn-success" onclick="viewSubmissions({{ $task->id }})">
                                    <i class="fas fa-eye"></i> Lihat
                                </button>
                                @if($task->isHidden == 1)
                                    <button class="btn-warning" onclick="publishTask({{ $task->id }})">
                                        <i class="fas fa-paper-plane"></i> Publikasi
                                    </button>
                                @endif
                                <button class="btn-danger" onclick="deleteTask({{ $task->id }})">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-tasks">
                    <div class="no-tasks-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h3>Belum Ada Tugas</h3>
                    <p>Mulai buat tugas pertama Anda dengan memilih salah satu jenis tugas di atas.</p>
                </div>
            @endif
        </div>

<script>
// Task creation functions
function createMultipleChoiceTask() {
    window.location.href = "{{ route('superadmin.tugas.create', 1) }}";
}

function createEssayTask() {
    window.location.href = "{{ route('superadmin.tugas.create', 2) }}";
}

function createIndividualTask() {
    window.location.href = "{{ route('superadmin.tugas.create', 3) }}";
}

function createGroupTask() {
    window.location.href = "{{ route('superadmin.tugas.create', 4) }}";
}

// Task action functions
function editTask(taskId) {
    console.log('Edit task:', taskId);
    // Implement edit functionality
}

function viewSubmissions(taskId) {
    console.log('View submissions for task:', taskId);
    // Implement view submissions functionality
}

function publishTask(taskId) {
    console.log('Publish task:', taskId);
    // Implement publish functionality
}

function deleteTask(taskId) {
    if (confirm('Apakah Anda yakin ingin menghapus tugas ini?')) {
        console.log('Delete task:', taskId);
        // Implement delete functionality
    }
}
</script>
@endsection