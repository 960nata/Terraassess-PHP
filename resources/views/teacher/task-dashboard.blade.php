@extends('layouts.unified-layout-new')

@section('title', 'Dashboard Tugas')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">Dashboard Tugas</h1>
                <p class="mt-2 text-gray-300">Buat dan kelola berbagai jenis tugas untuk siswa Anda</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('teacher.tasks.management') }}" class="btn btn-outline">
                    <i class="ph-list mr-2"></i>
                    Kelola Tugas
                </a>
            </div>
        </div>
    </div>

    <!-- Task Creation Cards -->
    <div class="task-cards-grid">
        <!-- Pilihan Ganda Card -->
        <div class="task-card" onclick="createTask(1)">
            <div class="task-card-icon">
                <i class="ph-check-square text-4xl"></i>
            </div>
            <div class="task-card-content">
                <h3 class="task-card-title">Tugas Pilihan Ganda</h3>
                <p class="task-card-description">Buat kuis dengan jawaban A, B, C, D yang dapat dinilai otomatis</p>
                <div class="task-features">
                    <span class="feature-tag">Auto Grading</span>
                    <span class="feature-tag">Instant Feedback</span>
                </div>
            </div>
            <div class="task-card-footer">
                <div class="task-count">0</div>
                <div class="task-actions">
                    <button class="btn btn-sm btn-primary" onclick="event.stopPropagation(); createTask(1)">
                        Buat Baru
                    </button>
                    <button class="btn btn-sm btn-outline" onclick="event.stopPropagation(); window.location.href='{{ route('teacher.tasks.management') }}'">
                        Kelola
                    </button>
                </div>
            </div>
        </div>

        <!-- Esai Card -->
        <div class="task-card" onclick="createTask(2)">
            <div class="task-card-icon">
                <i class="ph-file-text text-4xl"></i>
            </div>
            <div class="task-card-content">
                <h3 class="task-card-title">Tugas Esai</h3>
                <p class="task-card-description">Buat tugas esai yang memungkinkan siswa menulis jawaban panjang</p>
                <div class="task-features">
                    <span class="feature-tag">Text Input</span>
                    <span class="feature-tag">Manual Grading</span>
                </div>
            </div>
            <div class="task-card-footer">
                <div class="task-count">0</div>
                <div class="task-actions">
                    <button class="btn btn-sm btn-primary" onclick="event.stopPropagation(); createTask(2)">
                        Buat Baru
                    </button>
                    <button class="btn btn-sm btn-outline" onclick="event.stopPropagation(); window.location.href='{{ route('teacher.tasks.management') }}'">
                        Kelola
                    </button>
                </div>
            </div>
        </div>

        <!-- Mandiri Card -->
        <div class="task-card" onclick="createTask(3)">
            <div class="task-card-icon">
                <i class="ph-user text-4xl"></i>
            </div>
            <div class="task-card-content">
                <h3 class="task-card-title">Tugas Mandiri</h3>
                <p class="task-card-description">Buat tugas individual dengan opsi upload file atau ketik langsung</p>
                <div class="task-features">
                    <span class="feature-tag">File Upload</span>
                    <span class="feature-tag">Flexible</span>
                </div>
            </div>
            <div class="task-card-footer">
                <div class="task-count">0</div>
                <div class="task-actions">
                    <button class="btn btn-sm btn-primary" onclick="event.stopPropagation(); createTask(3)">
                        Buat Baru
                    </button>
                    <button class="btn btn-sm btn-outline" onclick="event.stopPropagation(); window.location.href='{{ route('teacher.tasks.management') }}'">
                        Kelola
                    </button>
                </div>
            </div>
        </div>

        <!-- Kelompok Card -->
        <div class="task-card" onclick="createTask(4)">
            <div class="task-card-icon">
                <i class="ph-users text-4xl"></i>
            </div>
            <div class="task-card-content">
                <h3 class="task-card-title">Tugas Kelompok</h3>
                <p class="task-card-description">Buat tugas kolaboratif dengan sistem penilaian antar-rekan</p>
                <div class="task-features">
                    <span class="feature-tag">Collaboration</span>
                    <span class="feature-tag">Peer Assessment</span>
                </div>
            </div>
            <div class="task-card-footer">
                <div class="task-count">0</div>
                <div class="task-actions">
                    <button class="btn btn-sm btn-primary" onclick="event.stopPropagation(); createTask(4)">
                        Buat Baru
                    </button>
                    <button class="btn btn-sm btn-outline" onclick="event.stopPropagation(); window.location.href='{{ route('teacher.tasks.management') }}'">
                        Kelola
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tasks -->
    <div class="mt-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-white">Tugas Terbaru</h2>
            <a href="{{ route('teacher.tasks.management') }}" class="text-blue-400 hover:text-blue-300 text-sm">
                Lihat Semua
            </a>
        </div>
        
        <div class="recent-tasks-grid">
            @forelse($recentTasks as $task)
            <div class="recent-task-card">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="font-medium text-white">{{ $task->name }}</h4>
                        <p class="text-sm text-gray-400 mt-1">{{ $task->tipe_tugas }}</p>
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="ph-calendar mr-1"></i>
                            {{ $task->due ? $task->due->format('d M Y, H:i') : 'Tidak ada tenggat' }}
                        </p>
                    </div>
                    <div class="task-status-badge status-{{ strtolower($task->status_tugas) }}">
                        {{ $task->status_tugas }}
                    </div>
                </div>
                <div class="mt-3">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $task->progress_percentage ?? 0 }}%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $task->submitted_count ?? 0 }}/{{ $task->total_students ?? 0 }} siswa mengumpulkan
                    </p>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-400">
                <i class="ph-clipboard-text text-4xl mb-2"></i>
                <p>Belum ada tugas yang dibuat</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<style>
.task-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
    width: 100%;
    box-sizing: border-box;
}

@media (max-width: 768px) {
    .task-cards-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 1rem;
        display: grid !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
}

@media (max-width: 480px) {
    .task-cards-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 0.75rem;
        display: grid !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
}

.task-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    text-align: center;
    min-height: 280px;
    position: relative;
    width: 100%;
    box-sizing: border-box;
}

.task-card:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(59, 130, 246, 0.5);
    transform: translateY(-2px);
}

.task-card-icon {
    color: #3b82f6;
    margin-bottom: 1rem;
}

.task-card-content {
    flex: 1;
    margin-bottom: 1rem;
}

.task-card-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: white;
    margin-bottom: 0.5rem;
}

.task-card-description {
    color: #94a3b8;
    font-size: 0.875rem;
    line-height: 1.5;
}

.task-card-action {
    color: #3b82f6;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.task-card:hover .task-card-action {
    opacity: 1;
}

.recent-tasks-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
}

.recent-task-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 1rem;
}

.task-status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-aktif {
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
}

.status-draft {
    background: rgba(156, 163, 175, 0.2);
    color: #9ca3af;
}

.status-terlambat {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.progress-bar {
    width: 100%;
    height: 4px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 2px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #3b82f6, #1d4ed8);
    transition: width 0.3s ease;
}

/* New styling for enhanced task cards */
.task-features {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    justify-content: center;
    margin-top: 1rem;
}

.feature-tag {
    background: rgba(59, 130, 246, 0.2);
    color: #60a5fa;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.task-card-footer {
    margin-top: auto;
    padding-top: 1rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.task-count {
    font-size: 2rem;
    font-weight: 700;
    color: #3b82f6;
    text-align: center;
}

.task-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.task-actions .btn {
    flex: 1;
    font-size: 0.75rem;
    padding: 0.5rem 0.75rem;
}

/* Mobile optimizations */
@media (max-width: 768px) {
    .task-cards-grid {
        display: grid !important;
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 1rem !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    
    .task-card {
        min-height: 250px;
        padding: 1rem;
        width: 100%;
        display: flex !important;
        flex-direction: column !important;
        box-sizing: border-box !important;
        margin: 0 !important;
    }
    
    .task-card-title {
        font-size: 1rem;
    }
    
    .task-card-description {
        font-size: 0.8rem;
    }
    
    .task-count {
        font-size: 1.5rem;
    }
    
    .feature-tag {
        font-size: 0.7rem;
        padding: 0.2rem 0.5rem;
    }
    
    .task-actions .btn {
        font-size: 0.7rem;
        padding: 0.4rem 0.6rem;
    }
}

@media (max-width: 480px) {
    .task-cards-grid {
        display: grid !important;
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 0.75rem !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }
    
    .task-card {
        min-height: 220px;
        padding: 0.75rem;
        width: 100%;
        display: flex !important;
        flex-direction: column !important;
        box-sizing: border-box !important;
        margin: 0 !important;
    }
    
    .task-card-title {
        font-size: 0.9rem;
    }
    
    .task-card-description {
        font-size: 0.75rem;
        line-height: 1.4;
    }
    
    .task-count {
        font-size: 1.25rem;
    }
    
    .task-features {
        margin-top: 0.5rem;
    }
    
    .feature-tag {
        font-size: 0.65rem;
        padding: 0.15rem 0.4rem;
    }
    
    .task-actions {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .task-actions .btn {
        font-size: 0.65rem;
        padding: 0.35rem 0.5rem;
    }
}
</style>

<script>
function createTask(tipe) {
    const routes = {
        1: '{{ route("teacher.tasks.create", ["tipe" => 1]) }}',
        2: '{{ route("teacher.tasks.create", ["tipe" => 2]) }}',
        3: '{{ route("teacher.tasks.create", ["tipe" => 3]) }}',
        4: '{{ route("teacher.tasks.create", ["tipe" => 4]) }}'
    };
    
    window.location.href = routes[tipe];
}
</script>
@endsection
