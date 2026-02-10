@extends('layouts.unified-layout')

@section('title', 'Progress Siswa: ' . $student->name)

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="ph-user"></i>
        Progress Siswa: {{ $student->name }}
    </h1>
    <div class="page-actions">
        <a href="{{ route('teacher.enhanced-exam-management.show', $ujian->id) }}" class="btn btn-outline-secondary">
            <i class="ph-arrow-left"></i> Kembali ke Detail Ujian
        </a>
    </div>
</div>

<!-- Student Info -->
<div class="student-info-card">
    <div class="student-header">
        <div class="student-avatar-large">
            {{ substr($student->name, 0, 1) }}
        </div>
        <div class="student-details">
            <h3>{{ $student->name }}</h3>
            <p class="student-email">{{ $student->email }}</p>
            <p class="student-class">{{ $ujian->kelasMapel->kelas->name }} - {{ $ujian->kelasMapel->mapel->name }}</p>
        </div>
        <div class="student-status">
            <span class="status-badge status-{{ $progress->status_badge }}">
                {{ $progress->status_text }}
            </span>
        </div>
    </div>
</div>

<!-- Progress Overview -->
<div class="progress-overview">
    <div class="progress-card">
        <div class="progress-header">
            <h4>Progress Pengerjaan</h4>
            <span class="progress-percentage">{{ $progress->progress_percentage }}%</span>
        </div>
        <div class="progress-bar-large">
            <div class="progress-fill" style="width: {{ $progress->progress_bar_width }}%"></div>
        </div>
        <div class="progress-stats">
            <div class="stat">
                <span class="stat-label">Soal Dikerjakan</span>
                <span class="stat-value">{{ $progress->answered_questions }}/{{ $progress->total_questions }}</span>
            </div>
            <div class="stat">
                <span class="stat-label">Waktu Pengerjaan</span>
                <span class="stat-value">{{ $progress->time_spent_formatted ?? '-' }}</span>
            </div>
        </div>
    </div>
    
    <div class="timeline-card">
        <h4>Timeline Pengerjaan</h4>
        <div class="timeline">
            <div class="timeline-item {{ $progress->started_at ? 'completed' : '' }}">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <h5>Mulai Mengerjakan</h5>
                    <p>{{ $progress->started_at ? $progress->started_at->format('d M Y H:i') : 'Belum dimulai' }}</p>
                </div>
            </div>
            
            <div class="timeline-item {{ $progress->completed_at ? 'completed' : '' }}">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <h5>Selesai Mengerjakan</h5>
                    <p>{{ $progress->completed_at ? $progress->completed_at->format('d M Y H:i') : 'Belum selesai' }}</p>
                </div>
            </div>
            
            <div class="timeline-item {{ $progress->status === 'graded' ? 'completed' : '' }}">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <h5>Sudah Dinilai</h5>
                    <p>{{ $progress->status === 'graded' ? 'Sudah dinilai' : 'Belum dinilai' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Exam Details -->
<div class="exam-details">
    <h3>Detail Ujian</h3>
    <div class="exam-info-grid">
        <div class="info-item">
            <i class="ph-exam"></i>
            <div>
                <span class="info-label">Judul Ujian</span>
                <span class="info-value">{{ $ujian->name }}</span>
            </div>
        </div>
        
        <div class="info-item">
            <i class="ph-clock"></i>
            <div>
                <span class="info-label">Deadline</span>
                <span class="info-value">{{ $ujian->due->format('d M Y H:i') }}</span>
            </div>
        </div>
        
        <div class="info-item">
            <i class="ph-timer"></i>
            <div>
                <span class="info-label">Durasi</span>
                <span class="info-value">{{ $ujian->getDurationFormatted() }}</span>
            </div>
        </div>
        
        <div class="info-item">
            <i class="ph-question"></i>
            <div>
                <span class="info-label">Jumlah Soal</span>
                <span class="info-value">{{ $ujian->total_soal_count }} Soal</span>
            </div>
        </div>
    </div>
</div>

<!-- Feedback Section -->
@if($feedback)
<div class="feedback-section">
    <h3>Feedback & Penilaian</h3>
    <div class="feedback-card">
        <div class="feedback-header">
            <div class="score-display">
                <span class="score-value">{{ $feedback->score }}/{{ $feedback->max_score }}</span>
                <span class="score-percentage">({{ $feedback->percentage }}%)</span>
            </div>
            <div class="grade-display">
                <span class="grade-badge grade-{{ $feedback->grade_color }}">
                    {{ $feedback->grade }}
                </span>
            </div>
        </div>
        
        @if($feedback->feedback_text)
        <div class="feedback-content">
            <h5>Feedback</h5>
            <p>{{ $feedback->feedback_text }}</p>
        </div>
        @endif
        
        @if($feedback->strengths)
        <div class="feedback-content">
            <h5>Kelebihan</h5>
            <p>{{ $feedback->strengths }}</p>
        </div>
        @endif
        
        @if($feedback->weaknesses)
        <div class="feedback-content">
            <h5>Kekurangan</h5>
            <p>{{ $feedback->weaknesses }}</p>
        </div>
        @endif
        
        @if($feedback->suggestions)
        <div class="feedback-content">
            <h5>Saran</h5>
            <p>{{ $feedback->suggestions }}</p>
        </div>
        @endif
        
        @if($feedback->rating)
        <div class="feedback-content">
            <h5>Rating</h5>
            <div class="rating-display">
                {!! $feedback->rating_stars !!}
            </div>
        </div>
        @endif
        
        <div class="feedback-footer">
            <small class="text-muted">
                Dinilai oleh: {{ $feedback->teacher->name }} 
                pada {{ $feedback->graded_at->format('d M Y H:i') }}
            </small>
        </div>
    </div>
</div>
@else
<div class="feedback-section">
    <h3>Feedback & Penilaian</h3>
    <div class="no-feedback">
        <i class="ph-chat-circle"></i>
        <p>Belum ada feedback untuk siswa ini</p>
        @if($progress->status === 'completed' || $progress->status === 'submitted')
            <button onclick="giveFeedback({{ $ujian->id }}, {{ $student->id }})" class="btn btn-primary">
                <i class="ph-chat-circle"></i> Berikan Feedback
            </button>
        @endif
    </div>
</div>
@endif

<!-- Feedback Modal -->
<div id="feedbackModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Berikan Feedback untuk {{ $student->name }}</h3>
            <button onclick="closeFeedbackModal()" class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="feedbackForm">
                @csrf
                <div class="form-group">
                    <label for="score">Nilai</label>
                    <input type="number" id="score" name="score" min="0" max="100" step="0.01" required>
                </div>
                
                <div class="form-group">
                    <label for="max_score">Nilai Maksimal</label>
                    <input type="number" id="max_score" name="max_score" min="1" value="{{ $ujian->max_score ?? 100 }}" required>
                </div>
                
                <div class="form-group">
                    <label for="feedback_text">Feedback</label>
                    <textarea id="feedback_text" name="feedback_text" rows="3" placeholder="Berikan feedback untuk siswa..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="strengths">Kelebihan</label>
                    <textarea id="strengths" name="strengths" rows="2" placeholder="Apa yang sudah baik..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="weaknesses">Kekurangan</label>
                    <textarea id="weaknesses" name="weaknesses" rows="2" placeholder="Apa yang perlu diperbaiki..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="suggestions">Saran</label>
                    <textarea id="suggestions" name="suggestions" rows="2" placeholder="Saran untuk perbaikan..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="rating">Rating (1-5)</label>
                    <select id="rating" name="rating">
                        <option value="">Pilih rating</option>
                        <option value="1">1 - Sangat Kurang</option>
                        <option value="2">2 - Kurang</option>
                        <option value="3">3 - Cukup</option>
                        <option value="4">4 - Baik</option>
                        <option value="5">5 - Sangat Baik</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button onclick="closeFeedbackModal()" class="btn btn-secondary">Batal</button>
            <button onclick="submitFeedback()" class="btn btn-primary">Simpan Feedback</button>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
.student-info-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
}

.student-header {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.student-avatar-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 2rem;
}

.student-details h3 {
    margin: 0 0 0.5rem 0;
    color: #374151;
    font-size: 1.5rem;
}

.student-email {
    color: #6b7280;
    margin: 0 0 0.25rem 0;
}

.student-class {
    color: #9ca3af;
    margin: 0;
    font-size: 0.875rem;
}

.student-status {
    margin-left: auto;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-not_started { background: #f3f4f6; color: #6b7280; }
.status-in_progress { background: #fef3c7; color: #d97706; }
.status-completed { background: #dcfce7; color: #059669; }
.status-submitted { background: #dbeafe; color: #2563eb; }
.status-graded { background: #e0e7ff; color: #7c3aed; }

.progress-overview {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.progress-card, .timeline-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
}

.progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.progress-header h4 {
    margin: 0;
    color: #374151;
}

.progress-percentage {
    font-size: 1.5rem;
    font-weight: 700;
    color: #059669;
}

.progress-bar-large {
    height: 12px;
    background: #e5e7eb;
    border-radius: 6px;
    overflow: hidden;
    margin-bottom: 1rem;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #10b981, #059669);
    transition: width 0.3s ease;
}

.progress-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.stat {
    text-align: center;
}

.stat-label {
    display: block;
    font-size: 0.75rem;
    color: #6b7280;
    margin-bottom: 0.25rem;
}

.stat-value {
    display: block;
    font-size: 1rem;
    font-weight: 600;
    color: #374151;
}

.timeline {
    position: relative;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e5e7eb;
}

.timeline-item {
    position: relative;
    padding-left: 3rem;
    margin-bottom: 1.5rem;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
}

.timeline-item.completed .timeline-marker {
    background: #10b981;
    color: white;
}

.timeline-item.completed .timeline-marker::after {
    content: '✓';
    font-weight: bold;
}

.timeline-content h5 {
    margin: 0 0 0.25rem 0;
    color: #374151;
    font-size: 0.875rem;
}

.timeline-content p {
    margin: 0;
    color: #6b7280;
    font-size: 0.75rem;
}

.exam-details {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
}

.exam-details h3 {
    margin: 0 0 1rem 0;
    color: #374151;
}

.exam-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.info-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    background: #f8fafc;
    border-radius: 8px;
}

.info-item i {
    font-size: 1.25rem;
    color: #6b7280;
    margin-right: 0.75rem;
    width: 24px;
}

.info-label {
    display: block;
    font-size: 0.75rem;
    color: #6b7280;
    margin-bottom: 0.25rem;
}

.info-value {
    display: block;
    font-weight: 600;
    color: #374151;
}

.feedback-section {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
}

.feedback-section h3 {
    margin: 0 0 1rem 0;
    color: #374151;
}

.feedback-card {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 1.5rem;
}

.feedback-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.score-display {
    text-align: center;
}

.score-value {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: #374151;
}

.score-percentage {
    display: block;
    font-size: 0.875rem;
    color: #6b7280;
}

.grade-badge {
    padding: 0.5rem 1rem;
    border-radius: 12px;
    font-size: 1.25rem;
    font-weight: 700;
    text-align: center;
}

.grade-success { background: #dcfce7; color: #166534; }
.grade-primary { background: #dbeafe; color: #1e40af; }
.grade-warning { background: #fef3c7; color: #92400e; }
.grade-info { background: #dbeafe; color: #1e40af; }
.grade-danger { background: #fee2e2; color: #991b1b; }

.feedback-content {
    margin-bottom: 1rem;
}

.feedback-content h5 {
    margin: 0 0 0.5rem 0;
    color: #374151;
    font-size: 0.875rem;
}

.feedback-content p {
    margin: 0;
    color: #6b7280;
    line-height: 1.5;
}

.rating-display {
    color: #fbbf24;
}

.feedback-footer {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e5e7eb;
}

.no-feedback {
    text-align: center;
    padding: 2rem;
    color: #6b7280;
}

.no-feedback i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.no-feedback p {
    margin-bottom: 1rem;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 0;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    max-height: 80vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.modal-header h3 {
    margin: 0;
    color: #374151;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #6b7280;
    cursor: pointer;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    padding: 1.5rem;
    border-top: 1px solid #e5e7eb;
}

@media (max-width: 768px) {
    .progress-overview {
        grid-template-columns: 1fr;
    }
    
    .student-header {
        flex-direction: column;
        text-align: center;
    }
    
    .student-status {
        margin-left: 0;
        margin-top: 1rem;
    }
    
    .exam-info-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

@section('scripts')
<script>
let currentUjianId = {{ $ujian->id }};
let currentUserId = {{ $student->id }};

function giveFeedback(ujianId, userId) {
    currentUjianId = ujianId;
    currentUserId = userId;
    document.getElementById('feedbackModal').style.display = 'block';
}

function closeFeedbackModal() {
    document.getElementById('feedbackModal').style.display = 'none';
    document.getElementById('feedbackForm').reset();
}

function submitFeedback() {
    if (!currentUserId) return;
    
    const form = document.getElementById('feedbackForm');
    const formData = new FormData(form);
    
    fetch(`/teacher/enhanced-exam-management/${currentUjianId}/feedback/${currentUserId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeFeedbackModal();
            location.reload();
        } else {
            alert('Gagal memberikan feedback: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memberikan feedback');
    });
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('feedbackModal');
    if (event.target === modal) {
        closeFeedbackModal();
    }
}
</script>
@endsection
