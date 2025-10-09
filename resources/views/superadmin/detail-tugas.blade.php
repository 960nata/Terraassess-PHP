@extends('layouts.unified-layout-new')

@section('title', $title)

@section('content')
<div class="superadmin-container">
    <!-- Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-info">
                <h1 class="page-title">
                    <i class="fas fa-tasks me-2"></i>{{ $tugas->name }}
                </h1>
                <div class="task-meta">
                    <span class="task-type-badge">{{ $tugas->tipe_tugas }}</span>
                    <span class="task-class">{{ $tugas->KelasMapel->Kelas->name ?? 'N/A' }}</span>
                    <span class="task-subject">{{ $tugas->KelasMapel->Mapel->name ?? 'N/A' }}</span>
                    <span class="task-status status-{{ strtolower(str_replace(' ', '_', $tugas->status_tugas)) }}">{{ $tugas->status_tugas }}</span>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('superadmin.tugas.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                @if($tugas->tipe == 4)
                <a href="{{ route('superadmin.tugas.penilaian-kelompok', $tugas->id) }}" class="btn-primary">
                    <i class="fas fa-star"></i> Penilaian Kelompok
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Task Overview -->
    <div class="task-overview">
        <div class="overview-cards">
            <div class="overview-card">
                <div class="card-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-content">
                    <div class="card-value">{{ $progressSiswa->count() }}</div>
                    <div class="card-label">Total Siswa</div>
                </div>
            </div>
            
            <div class="overview-card">
                <div class="card-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="card-content">
                    <div class="card-value">{{ $progressSiswa->where('status', 'submitted')->count() }}</div>
                    <div class="card-label">Sudah Submit</div>
                </div>
            </div>
            
            <div class="overview-card">
                <div class="card-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="card-content">
                    <div class="card-value">{{ $progressSiswa->where('status', 'in_progress')->count() }}</div>
                    <div class="card-label">Sedang Mengerjakan</div>
                </div>
            </div>
            
            <div class="overview-card">
                <div class="card-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="card-content">
                    <div class="card-value">{{ $progressSiswa->where('status', 'not_started')->count() }}</div>
                    <div class="card-label">Belum Mulai</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Details -->
    <div class="task-details">
        <div class="details-section">
            <h3 class="section-title">Deskripsi Tugas</h3>
            <div class="task-description">
                {!! nl2br(e($tugas->content)) !!}
            </div>
            
            <div class="task-info">
                <div class="info-item">
                    <i class="fas fa-calendar"></i>
                    <span>Batas Waktu: {{ $tugas->due ? \Carbon\Carbon::parse($tugas->due)->format('d M Y H:i') : 'Tidak ada' }}</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <span>Dibuat: {{ $tugas->created_at->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Progress -->
    <div class="student-progress-section">
        <div class="section-header">
            <h3 class="section-title">Progress Siswa</h3>
            <div class="progress-filters">
                <select id="statusFilter" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="not_started">Belum Mulai</option>
                    <option value="in_progress">Sedang Mengerjakan</option>
                    <option value="submitted">Sudah Submit</option>
                    <option value="graded">Sudah Dinilai</option>
                </select>
            </div>
        </div>
        
        <div class="progress-list">
            @forelse($progressSiswa as $progress)
            <div class="progress-item" data-status="{{ $progress->status }}">
                <div class="student-info">
                    <div class="student-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="student-details">
                        <div class="student-name">{{ $progress->user->name }}</div>
                        <div class="student-email">{{ $progress->user->email }}</div>
                    </div>
                </div>
                
                <div class="progress-info">
                    <div class="progress-status">
                        <span class="status-badge status-{{ $progress->status }}">
                            {{ ucfirst(str_replace('_', ' ', $progress->status)) }}
                        </span>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $progress->progress_percentage }}%"></div>
                        </div>
                        <span class="progress-percentage">{{ $progress->progress_percentage }}%</span>
                    </div>
                    
                    <div class="progress-dates">
                        @if($progress->started_at)
                            <small>Mulai: {{ $progress->started_at->format('d M H:i') }}</small>
                        @endif
                        @if($progress->submitted_at)
                            <small>Submit: {{ $progress->submitted_at->format('d M H:i') }}</small>
                        @endif
                    </div>
                </div>
                
                <div class="progress-actions">
                    @if($progress->status === 'submitted' || $progress->status === 'graded')
                        <button class="btn-action btn-feedback" data-student-id="{{ $progress->user->id }}" data-student-name="{{ $progress->user->name }}">
                            <i class="fas fa-comment"></i> Feedback
                        </button>
                    @endif
                    
                    @if($progress->final_score)
                        <span class="final-score">{{ $progress->final_score }}</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <p>Belum ada progress siswa</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Feedbacks Section -->
    @if($feedbacks->count() > 0)
    <div class="feedbacks-section">
        <h3 class="section-title">Feedback yang Diberikan</h3>
        <div class="feedbacks-list">
            @foreach($feedbacks as $feedback)
            <div class="feedback-item">
                <div class="feedback-header">
                    <div class="feedback-student">
                        <strong>{{ $feedback->user->name }}</strong>
                        @if($feedback->rating)
                            <div class="feedback-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $feedback->rating ? 'active' : '' }}"></i>
                                @endfor
                            </div>
                        @endif
                    </div>
                    <div class="feedback-meta">
                        <span>Oleh: {{ $feedback->guru->name }}</span>
                        <span>{{ $feedback->created_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
                <div class="feedback-content">
                    {{ $feedback->feedback }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Feedback Modal -->
<div id="feedbackModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Berikan Feedback</h3>
            <span class="close">&times;</span>
        </div>
        <form id="feedbackForm" method="POST" action="{{ route('superadmin.tugas.feedback') }}">
            @csrf
            <input type="hidden" name="tugas_id" value="{{ $tugas->id }}">
            <input type="hidden" name="user_id" id="feedback_user_id">
            
            <div class="modal-body">
                <div class="form-group">
                    <label for="feedback_text">Feedback</label>
                    <textarea id="feedback_text" name="feedback" rows="4" placeholder="Masukkan feedback untuk siswa" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="feedback_rating">Rating (Opsional)</label>
                    <div class="rating-input">
                        @for($i = 1; $i <= 5; $i++)
                            <input type="radio" name="rating" value="{{ $i }}" id="rating{{ $i }}">
                            <label for="rating{{ $i }}" class="star-label">
                                <i class="fas fa-star"></i>
                            </label>
                        @endfor
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeFeedbackModal()">Batal</button>
                <button type="submit" class="btn-primary">Kirim Feedback</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Detail Task Styles */
.superadmin-container {
    padding: 2rem;
    max-width: 1400px;
    margin: 0 auto;
}

.page-header {
    margin-bottom: 2rem;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 1rem;
}

.task-meta {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.task-type-badge {
    background: #e6fffa;
    color: #065f46;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.task-class, .task-subject {
    color: #718096;
    font-size: 0.9rem;
}

.task-status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-draft { background: #f3f4f6; color: #374151; }
.status-aktif { background: #d1fae5; color: #065f46; }
.status-terlambat { background: #fee2e2; color: #dc2626; }

.header-actions {
    display: flex;
    gap: 1rem;
    flex-shrink: 0;
}

/* Overview Cards */
.task-overview {
    margin-bottom: 2rem;
}

.overview-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.overview-card {
    background: rgba(30, 41, 59, 0.8);
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.card-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.card-value {
    font-size: 2rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 0.25rem;
}

.card-label {
    color: #718096;
    font-size: 0.9rem;
}

/* Task Details */
.task-details {
    background: rgba(30, 41, 59, 0.8);
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
    overflow: hidden;
}

.details-section {
    padding: 2rem;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1a202c;
    margin-bottom: 1rem;
}

.task-description {
    color: #4a5568;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.task-info {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #718096;
}

/* Student Progress */
.student-progress-section {
    background: rgba(30, 41, 59, 0.8);
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
    overflow: hidden;
}

.section-header {
    padding: 2rem 2rem 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.progress-filters {
    display: flex;
    gap: 1rem;
}

.filter-select {
    padding: 0.5rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    background: rgba(30, 41, 59, 0.8);
}

.progress-list {
    padding: 0 2rem 2rem;
}

.progress-item {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.progress-item:last-child {
    border-bottom: none;
}

.student-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    min-width: 200px;
}

.student-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f7fafc;
    color: #4a5568;
    display: flex;
    align-items: center;
    justify-content: center;
}

.student-name {
    font-weight: 600;
    color: #1a202c;
    margin-bottom: 0.25rem;
}

.student-email {
    color: #718096;
    font-size: 0.9rem;
}

.progress-info {
    flex: 1;
    min-width: 300px;
}

.progress-status {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.5rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    min-width: 100px;
    text-align: center;
}

.status-not_started { background: #f3f4f6; color: #374151; }
.status-in_progress { background: #fef3c7; color: #92400e; }
.status-submitted { background: #d1fae5; color: #065f46; }
.status-graded { background: #dbeafe; color: #1e40af; }

.progress-bar {
    flex: 1;
    height: 8px;
    background: #e2e8f0;
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: #10b981;
    transition: width 0.3s ease;
}

.progress-percentage {
    color: #718096;
    font-size: 0.9rem;
    font-weight: 500;
    min-width: 40px;
}

.progress-dates {
    display: flex;
    gap: 1rem;
    color: #718096;
    font-size: 0.8rem;
}

.progress-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.btn-action {
    background: #667eea;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.9rem;
    border: none;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn-action:hover {
    background: #5a67d8;
}

.final-score {
    background: #10b981;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-weight: 600;
}

/* Feedbacks */
.feedbacks-section {
    background: rgba(30, 41, 59, 0.8);
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 2rem;
}

.feedbacks-list {
    margin-top: 1rem;
}

.feedback-item {
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.feedback-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.feedback-student {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.feedback-rating {
    display: flex;
    gap: 0.25rem;
}

.feedback-rating .fas.fa-star.active {
    color: #fbbf24;
}

.feedback-meta {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    color: #718096;
    font-size: 0.9rem;
}

.feedback-content {
    color: #4a5568;
    line-height: 1.5;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: rgba(30, 41, 59, 0.8);
    margin: 5% auto;
    padding: 0;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.modal-header {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: #1a202c;
}

.close {
    color: #718096;
    font-size: 1.5rem;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: #4a5568;
}

.modal-body {
    padding: 2rem;
}

.rating-input {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.rating-input input[type="radio"] {
    display: none;
}

.star-label {
    cursor: pointer;
    color: #d1d5db;
    font-size: 1.5rem;
    transition: color 0.2s ease;
}

.rating-input input[type="radio"]:checked ~ .star-label,
.star-label:hover {
    color: #fbbf24;
}

.modal-footer {
    padding: 1.5rem 2rem;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .superadmin-container {
        padding: 1rem;
    }
    
    .header-content {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .overview-cards {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .progress-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .progress-status {
        width: 100%;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
}

@media (max-width: 480px) {
    .overview-cards {
        grid-template-columns: 1fr;
    }
    
    .task-meta {
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status filter
    const statusFilter = document.getElementById('statusFilter');
    const progressItems = document.querySelectorAll('.progress-item');
    
    statusFilter.addEventListener('change', function() {
        const selectedStatus = this.value;
        
        progressItems.forEach(item => {
            if (selectedStatus === '' || item.dataset.status === selectedStatus) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });
    
    // Feedback modal
    const feedbackModal = document.getElementById('feedbackModal');
    const feedbackButtons = document.querySelectorAll('.btn-feedback');
    const closeBtn = document.querySelector('.close');
    const feedbackForm = document.getElementById('feedbackForm');
    const feedbackUserId = document.getElementById('feedback_user_id');
    
    feedbackButtons.forEach(button => {
        button.addEventListener('click', function() {
            const studentId = this.dataset.studentId;
            const studentName = this.dataset.studentName;
            
            feedbackUserId.value = studentId;
            document.querySelector('.modal-header h3').textContent = `Berikan Feedback untuk ${studentName}`;
            feedbackModal.style.display = 'block';
        });
    });
    
    closeBtn.addEventListener('click', closeFeedbackModal);
    
    window.addEventListener('click', function(event) {
        if (event.target === feedbackModal) {
            closeFeedbackModal();
        }
    });
    
    function closeFeedbackModal() {
        feedbackModal.style.display = 'none';
        feedbackForm.reset();
    }
});
</script>
@endsection
