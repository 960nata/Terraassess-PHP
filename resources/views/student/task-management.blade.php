@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Manajemen Tugas Student')

@section('styles')
<style>
    .student-task-dashboard {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .student-task-dashboard::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    .dashboard-header {
        position: relative;
        z-index: 2;
    }

    .dashboard-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .dashboard-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 2rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #10b981, #059669);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.25);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .task-filters {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid #475569;
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    }

    .filter-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr auto;
        gap: 1.5rem;
        align-items: end;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.75rem;
        font-weight: 600;
        color: #ffffff;
        font-size: 0.95rem;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 1rem 1.25rem;
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        color: #ffffff;
        font-size: 1rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #667eea;
        background: rgba(255, 255, 255, 0.15);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
    }

    .form-group input::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }

    .btn-primary {
        background: linear-gradient(45deg, #667eea, #764ba2);
        color: #ffffff;
        border: none;
        padding: 1rem 2rem;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .tasks-container {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        border-radius: 20px;
        padding: 2rem;
        border: 1px solid #475569;
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    }

    .tasks-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #475569;
    }

    .tasks-title {
        color: #ffffff;
        font-size: 1.5rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .task-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
    }

    .task-card {
        background: linear-gradient(135deg, #2a2a3e 0%, #1e293b 100%);
        border: 1px solid #475569;
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .task-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2);
    }

    .task-card:hover {
        transform: translateY(-5px);
        background: linear-gradient(135deg, #334155 0%, #2a2a3e 100%);
        border-color: #667eea;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .task-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .task-title {
        color: #ffffff;
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
        flex: 1;
        line-height: 1.3;
    }

    .task-badges {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .badge {
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-pending {
        background: linear-gradient(45deg, #f59e0b, #d97706);
        color: white;
    }

    .badge-completed {
        background: linear-gradient(45deg, #10b981, #059669);
        color: white;
    }

    .badge-overdue {
        background: linear-gradient(45deg, #ef4444, #dc2626);
        color: white;
    }

    .badge-easy {
        background: linear-gradient(45deg, #10b981, #059669);
        color: white;
    }

    .badge-medium {
        background: linear-gradient(45deg, #f59e0b, #d97706);
        color: white;
    }

    .badge-hard {
        background: linear-gradient(45deg, #ef4444, #dc2626);
        color: white;
    }

    .task-description {
        color: #94a3b8;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
        line-height: 1.5;
    }

    .task-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #cbd5e1;
        font-size: 0.875rem;
    }

    .info-item i {
        color: #667eea;
        width: 16px;
        font-size: 1rem;
    }

    .task-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex: 1;
        justify-content: center;
        min-width: 120px;
    }

    .btn-start {
        background: linear-gradient(45deg, #10b981, #059669);
        color: white;
    }

    .btn-start:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
    }

    .btn-view {
        background: linear-gradient(45deg, #3b82f6, #2563eb);
        color: white;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
    }

    .btn-submit {
        background: linear-gradient(45deg, #8b5cf6, #7c3aed);
        color: white;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(139, 92, 246, 0.3);
    }

    .no-tasks {
        text-align: center;
        padding: 4rem 2rem;
        color: #94a3b8;
    }

    .no-tasks-icon {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }

    .no-tasks h3 {
        color: #ffffff;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .no-tasks p {
        font-size: 1rem;
        line-height: 1.6;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .dashboard-title {
            font-size: 2rem;
        }

        .filter-row {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .task-cards {
            grid-template-columns: 1fr;
        }

        .task-info {
            grid-template-columns: 1fr;
        }

        .task-actions {
            flex-direction: column;
        }

        .btn-action {
            flex: none;
            width: 100%;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .student-task-dashboard {
            padding: 1.5rem;
        }

        .tasks-container {
            padding: 1.5rem;
        }
    }

    /* Animation for cards */
    .task-card {
        animation: slideInUp 0.6s ease-out;
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Staggered animation */
    .task-card:nth-child(1) { animation-delay: 0.1s; }
    .task-card:nth-child(2) { animation-delay: 0.2s; }
    .task-card:nth-child(3) { animation-delay: 0.3s; }
    .task-card:nth-child(4) { animation-delay: 0.4s; }
    .task-card:nth-child(5) { animation-delay: 0.5s; }
    .task-card:nth-child(6) { animation-delay: 0.6s; }
</style>
@endsection

@section('content')
<div class="student-task-dashboard">
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            <i class="fas fa-tasks"></i>
            Manajemen Tugas
        </h1>
        <p class="dashboard-subtitle">Kelola dan kerjakan tugas-tugas Anda dengan mudah</p>
        
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $totalTasks ?? 0 }}</div>
                <div class="stat-label">Total Tugas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $pendingTasks ?? 0 }}</div>
                <div class="stat-label">Belum Dikerjakan</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $completedTasks ?? 0 }}</div>
                <div class="stat-label">Selesai</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $overdueTasks ?? 0 }}</div>
                <div class="stat-label">Terlambat</div>
            </div>
        </div>
    </div>
</div>

<!-- Task Filters -->
<div class="task-filters">
    <form action="{{ route('student.task-management') }}" method="GET" class="filter-form">
        <div class="filter-row">
            <div class="form-group">
                <label for="filter_subject">Mata Pelajaran</label>
                <select id="filter_subject" name="filter_subject">
                    <option value="">Semua Mata Pelajaran</option>
                    @if(isset($subjects))
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ (isset($filters['filter_subject']) && $filters['filter_subject'] == $subject->id) ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            
            <div class="form-group">
                <label for="filter_status">Status</label>
                <select id="filter_status" name="filter_status">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ (isset($filters['filter_status']) && $filters['filter_status'] == 'pending') ? 'selected' : '' }}>Belum Dikerjakan</option>
                    <option value="completed" {{ (isset($filters['filter_status']) && $filters['filter_status'] == 'completed') ? 'selected' : '' }}>Selesai</option>
                    <option value="overdue" {{ (isset($filters['filter_status']) && $filters['filter_status'] == 'overdue') ? 'selected' : '' }}>Terlambat</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="filter_difficulty">Tingkat Kesulitan</label>
                <select id="filter_difficulty" name="filter_difficulty">
                    <option value="">Semua Tingkat</option>
                    <option value="easy" {{ (isset($filters['filter_difficulty']) && $filters['filter_difficulty'] == 'easy') ? 'selected' : '' }}>Mudah</option>
                    <option value="medium" {{ (isset($filters['filter_difficulty']) && $filters['filter_difficulty'] == 'medium') ? 'selected' : '' }}>Sedang</option>
                    <option value="hard" {{ (isset($filters['filter_difficulty']) && $filters['filter_difficulty'] == 'hard') ? 'selected' : '' }}>Sulit</option>
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

<!-- Tasks Container -->
<div class="tasks-container">
    <div class="tasks-header">
        <h2 class="tasks-title">
            <i class="fas fa-list-check"></i>
            Daftar Tugas Saya
        </h2>
    </div>
    
    @if(isset($tasks) && $tasks->count() > 0)
        <div class="task-cards">
            @foreach($tasks as $task)
                <div class="task-card">
                    <div class="task-card-header">
                        <h3 class="task-title">{{ $task->name }}</h3>
                        <div class="task-badges">
                            @php
                                $difficultyMap = [1 => 'easy', 2 => 'medium', 3 => 'hard'];
                                $difficultyLabels = ['easy' => 'Mudah', 'medium' => 'Sedang', 'hard' => 'Sulit'];
                                $difficulty = $difficultyMap[$task->tipe] ?? 'medium';
                                
                                $status = 'pending';
                                if($task->submissions && $task->submissions->count() > 0) {
                                    $status = 'completed';
                                } elseif($task->due && $task->due < now()) {
                                    $status = 'overdue';
                                }
                            @endphp
                            
                            <span class="badge badge-{{ $difficulty }}">
                                {{ $difficultyLabels[$difficulty] }}
                            </span>
                            
                            <span class="badge badge-{{ $status }}">
                                @if($status == 'pending')
                                    Belum Dikerjakan
                                @elseif($status == 'completed')
                                    Selesai
                                @else
                                    Terlambat
                                @endif
                            </span>
                        </div>
                    </div>
                    
                    @if($task->content)
                        <p class="task-description">{{ Str::limit($task->content, 120) }}</p>
                    @endif
                    
                    <div class="task-info">
                        <div class="info-item">
                            <i class="fas fa-book"></i>
                            <span>{{ $task->KelasMapel->Mapel->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-graduation-cap"></i>
                            <span>{{ $task->KelasMapel->Kelas->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-calendar"></i>
                            <span>{{ $task->due ? \Carbon\Carbon::parse($task->due)->format('d M Y H:i') : 'Tidak ada deadline' }}</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-clock"></i>
                            <span>{{ $task->created_at ? \Carbon\Carbon::parse($task->created_at)->format('d M Y') : 'N/A' }}</span>
                        </div>
                    </div>
                    
                    <div class="task-actions">
                        @if($status == 'pending')
                            <button class="btn-action btn-start" onclick="startTask({{ $task->id }})">
                                <i class="fas fa-play"></i>
                                Mulai Kerjakan
                            </button>
                        @elseif($status == 'completed')
                            <button class="btn-action btn-view" onclick="viewTask({{ $task->id }})">
                                <i class="fas fa-eye"></i>
                                Lihat Hasil
                            </button>
                        @else
                            <button class="btn-action btn-submit" onclick="submitTask({{ $task->id }})">
                                <i class="fas fa-paper-plane"></i>
                                Submit Tugas
                            </button>
                        @endif
                        
                        <button class="btn-action btn-view" onclick="viewTaskDetail({{ $task->id }})">
                            <i class="fas fa-info-circle"></i>
                            Detail
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
            <p>Anda belum memiliki tugas yang ditugaskan. Silakan hubungi guru atau admin untuk mendapatkan tugas.</p>
        </div>
    @endif
</div>

<script>
// Task action functions
function startTask(taskId) {
    // Redirect to task taking page
    window.location.href = "{{ url('student/tugas') }}/" + taskId + "/kerjakan";
}

function viewTask(taskId) {
    // Redirect to task view page
    window.location.href = "{{ url('student/tugas') }}/" + taskId;
}

function submitTask(taskId) {
    // Redirect to task submission page
    window.location.href = "{{ url('student/tugas') }}/" + taskId + "/submit";
}

function viewTaskDetail(taskId) {
    // Redirect to task detail page
    window.location.href = "{{ url('student/tugas') }}/" + taskId + "/detail";
}

// Auto-refresh stats every 30 seconds
setInterval(function() {
    // You can implement AJAX refresh here if needed
}, 30000);

// Add smooth scrolling for better UX
document.addEventListener('DOMContentLoaded', function() {
    // Add loading animation
    const cards = document.querySelectorAll('.task-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
});
</script>
@endsection
