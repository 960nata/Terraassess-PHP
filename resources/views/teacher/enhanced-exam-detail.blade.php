@extends('layouts.unified-layout-new')

@section('title', $title)

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="ph-exam"></i>
        {{ $ujian->name }}
    </h1>
    <div class="page-actions">
        <a href="{{ route('teacher.enhanced-exam-management') }}" class="btn btn-outline-secondary">
            <i class="ph-arrow-left"></i> Kembali
        </a>
        <button onclick="editExam({{ $ujian->id }})" class="btn btn-outline-warning">
            <i class="ph-pencil"></i> Edit
        </button>
    </div>
</div>

<!-- Exam Info -->
<div class="exam-info-card">
    <div class="exam-info-header">
        <h3>Informasi Ujian</h3>
        <span class="exam-status status-{{ $ujian->status_color }}">
            {{ $ujian->status_text }}
        </span>
    </div>
    
    <div class="exam-info-grid">
        <div class="info-item">
            <i class="ph-graduation-cap"></i>
            <div>
                <span class="info-label">Kelas & Mata Pelajaran</span>
                <span class="info-value">{{ $ujian->kelasMapel->kelas->name }} - {{ $ujian->kelasMapel->mapel->name }}</span>
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
        
        <div class="info-item">
            <i class="ph-star"></i>
            <div>
                <span class="info-label">Nilai Maksimal</span>
                <span class="info-value">{{ $ujian->max_score ?? 100 }} Poin</span>
            </div>
        </div>
        
        <div class="info-item">
            <i class="ph-users"></i>
            <div>
                <span class="info-label">Total Peserta</span>
                <span class="info-value">{{ $stats['total_students'] }} Siswa</span>
            </div>
        </div>
    </div>
    
    @if($ujian->content)
    <div class="exam-description">
        <h4>Deskripsi Ujian</h4>
        <p>{{ $ujian->content }}</p>
    </div>
    @endif
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon not-started">
            <i class="ph-circle"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['not_started'] }}</h3>
            <p>Belum Dimulai</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon in-progress">
            <i class="ph-clock"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['in_progress'] }}</h3>
            <p>Sedang Mengerjakan</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon completed">
            <i class="ph-check-circle"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['completed'] }}</h3>
            <p>Selesai Dikerjakan</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon submitted">
            <i class="ph-paper-plane"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['submitted'] }}</h3>
            <p>Sudah Submit</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon graded">
            <i class="ph-star"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['graded'] }}</h3>
            <p>Sudah Dinilai</p>
        </div>
    </div>
</div>

<!-- Progress Table -->
<div class="progress-section">
    <div class="section-header">
        <h3>Progress Siswa</h3>
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
                    <th>Nilai</th>
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
                            @php
                                $feedback = $feedbackData->where('user_id', $progress->user_id)->first();
                            @endphp
                            @if($feedback && $feedback->score)
                                <div class="score-info">
                                    <span class="score-value">{{ $feedback->score }}/{{ $feedback->max_score }}</span>
                                    <span class="score-grade grade-{{ $feedback->grade_color }}">{{ $feedback->grade }}</span>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
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

<!-- Feedback Modal -->
<div id="feedbackModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Berikan Feedback</h3>
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
.exam-info-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
}

.exam-info-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.exam-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
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

.exam-description {
    padding-top: 1rem;
    border-top: 1px solid #e5e7eb;
}

.exam-description h4 {
    margin-bottom: 0.5rem;
    color: #374151;
}

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

.stat-icon.not-started { background: #f3f4f6; color: #6b7280; }
.stat-icon.in-progress { background: #fef3c7; color: #d97706; }
.stat-icon.completed { background: #dcfce7; color: #059669; }
.stat-icon.submitted { background: #dbeafe; color: #2563eb; }
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

.score-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.score-value {
    font-weight: 600;
    color: #374151;
}

.score-grade {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.125rem 0.5rem;
    border-radius: 12px;
    text-align: center;
}

.grade-success { background: #dcfce7; color: #166534; }
.grade-primary { background: #dbeafe; color: #1e40af; }
.grade-warning { background: #fef3c7; color: #92400e; }
.grade-info { background: #dbeafe; color: #1e40af; }
.grade-danger { background: #fee2e2; color: #991b1b; }

.action-buttons {
    display: flex;
    gap: 0.25rem;
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
    .exam-info-grid {
        grid-template-columns: 1fr;
    }
    
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
let currentUjianId = {{ $ujian->id }};
let currentUserId = null;

function editExam(examId) {
    window.location.href = "{{ route('teacher.enhanced-exam-management.edit', '') }}/" + examId;
}

function viewStudentProgress(ujianId, userId) {
    window.location.href = "{{ route('teacher.enhanced-exam-management.student-progress', ['', '']) }}/" + ujianId + "/" + userId;
}

function giveFeedback(ujianId, userId) {
    currentUjianId = ujianId;
    currentUserId = userId;
    document.getElementById('feedbackModal').style.display = 'block';
}

function closeFeedbackModal() {
    document.getElementById('feedbackModal').style.display = 'none';
    document.getElementById('feedbackForm').reset();
    currentUserId = null;
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

function exportProgress() {
    // TODO: Implement export functionality
    alert('Fitur export akan segera tersedia');
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
