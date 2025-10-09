@extends('layouts.unified-layout-new')

@section('title', 'Progress Ujian: ' . $ujian->name)

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="ph-chart-line"></i>
        Progress Ujian: {{ $ujian->name }}
    </h1>
    <div class="page-actions">
        <a href="{{ route('teacher.enhanced-exam-management.show', $ujian->id) }}" class="btn btn-outline-secondary">
            <i class="ph-arrow-left"></i> Kembali ke Detail
        </a>
    </div>
</div>

<!-- Progress Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon total">
            <i class="ph-users"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $progressData->count() }}</h3>
            <p>Total Siswa</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon not-started">
            <i class="ph-circle"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $progressData->where('status', 'not_started')->count() }}</h3>
            <p>Belum Dimulai</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon in-progress">
            <i class="ph-clock"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $progressData->where('status', 'in_progress')->count() }}</h3>
            <p>Sedang Mengerjakan</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon completed">
            <i class="ph-check-circle"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $progressData->where('status', 'completed')->count() }}</h3>
            <p>Selesai Dikerjakan</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon graded">
            <i class="ph-star"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $progressData->where('status', 'graded')->count() }}</h3>
            <p>Sudah Dinilai</p>
        </div>
    </div>
</div>

<!-- Progress Table -->
<div class="progress-section">
    <div class="section-header">
        <h3>Detail Progress Siswa</h3>
        <div class="section-actions">
            <button onclick="exportProgress()" class="btn btn-outline-primary btn-sm">
                <i class="ph-download"></i> Export
            </button>
        </div>
    </div>
    
    <div class="table-container">
        <table class="progress-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th>Durasi</th>
                    <th>Soal Dikerjakan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($progressData as $index => $progress)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="student-info">
                                <div class="student-avatar">
                                    {{ substr($progress->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="student-name">{{ $progress->user->name }}</div>
                                    <div class="student-email">{{ $progress->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $progress->status_badge }}">
                                {{ $progress->status_text }}
                            </span>
                        </td>
                        <td>
                            <div class="progress-container">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $progress->progress_bar_width }}%"></div>
                                </div>
                                <span class="progress-text">{{ $progress->progress_percentage }}%</span>
                            </div>
                        </td>
                        <td>
                            @if($progress->started_at)
                                {{ $progress->started_at->format('d M Y H:i') }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($progress->completed_at)
                                {{ $progress->completed_at->format('d M Y H:i') }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($progress->time_spent)
                                {{ $progress->time_spent_formatted }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="question-stats">
                                <span class="answered">{{ $progress->answered_questions }}</span>
                                <span class="separator">/</span>
                                <span class="total">{{ $progress->total_questions }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button onclick="viewStudentProgress({{ $ujian->id }}, {{ $progress->user_id }})" 
                                        class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                    <i class="ph-eye"></i>
                                </button>
                                @if($progress->status === 'completed' || $progress->status === 'submitted')
                                    <button onclick="giveFeedback({{ $ujian->id }}, {{ $progress->user_id }})" 
                                            class="btn btn-sm btn-outline-success" title="Berikan Feedback">
                                        <i class="ph-chat-circle"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">
                            <i class="ph-users"></i>
                            <p>Belum ada siswa yang mengerjakan ujian ini</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('styles')
<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.5rem;
}

.stat-icon.total { background: #f3f4f6; color: #6b7280; }
.stat-icon.not-started { background: #f3f4f6; color: #6b7280; }
.stat-icon.in-progress { background: #fef3c7; color: #d97706; }
.stat-icon.completed { background: #dcfce7; color: #059669; }
.stat-icon.graded { background: #e0e7ff; color: #7c3aed; }

.stat-content h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0 0 0.25rem 0;
    color: #374151;
}

.stat-content p {
    margin: 0;
    color: #6b7280;
    font-size: 0.875rem;
}

.progress-section {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.table-container {
    overflow-x: auto;
}

.progress-table {
    width: 100%;
    border-collapse: collapse;
}

.progress-table th,
.progress-table td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.progress-table th {
    background: #f8fafc;
    font-weight: 600;
    color: #374151;
    font-size: 0.875rem;
}

.student-info {
    display: flex;
    align-items: center;
}

.student-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #3b82f6;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    margin-right: 0.75rem;
    font-size: 0.875rem;
}

.student-name {
    font-weight: 600;
    color: #374151;
}

.student-email {
    font-size: 0.75rem;
    color: #6b7280;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
}

.status-not_started { background: #f3f4f6; color: #6b7280; }
.status-in_progress { background: #fef3c7; color: #d97706; }
.status-completed { background: #dcfce7; color: #059669; }
.status-submitted { background: #dbeafe; color: #2563eb; }
.status-graded { background: #e0e7ff; color: #7c3aed; }

.progress-container {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.progress-bar {
    flex: 1;
    height: 8px;
    background: #e5e7eb;
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #10b981, #059669);
    transition: width 0.3s ease;
}

.progress-text {
    font-size: 0.75rem;
    font-weight: 600;
    color: #059669;
    min-width: 40px;
}

.question-stats {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.answered {
    font-weight: 600;
    color: #059669;
}

.separator {
    color: #6b7280;
}

.total {
    color: #374151;
}

.action-buttons {
    display: flex;
    gap: 0.25rem;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .progress-table {
        font-size: 0.875rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style>
@endsection

@section('scripts')
<script>
function viewStudentProgress(ujianId, userId) {
    window.location.href = "{{ route('teacher.enhanced-exam-management.student-progress', ['', '']) }}/" + ujianId + "/" + userId;
}

function giveFeedback(ujianId, userId) {
    window.location.href = "{{ route('teacher.enhanced-exam-management.show', '') }}/" + ujianId + "?action=feedback&user=" + userId;
}

function exportProgress() {
    // TODO: Implement export functionality
    alert('Fitur export akan segera tersedia');
}
</script>
@endsection
