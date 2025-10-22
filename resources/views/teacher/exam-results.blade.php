@extends('layouts.unified-layout')

@section('title', 'Hasil Ujian: ' . $ujian->name)

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="ph-check-circle"></i>
        Hasil Ujian: {{ $ujian->name }}
    </h1>
    <div class="page-actions">
        <a href="{{ route('teacher.enhanced-exam-management.show', $ujian->id) }}" class="btn btn-outline-secondary">
            <i class="ph-arrow-left"></i> Kembali ke Detail
        </a>
        <button onclick="exportResults()" class="btn btn-outline-primary">
            <i class="ph-download"></i> Export Hasil
        </button>
    </div>
</div>

<!-- Results Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon total">
            <i class="ph-users"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $results->count() }}</h3>
            <p>Total Peserta</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon completed">
            <i class="ph-check-circle"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $results->where('status', 'completed')->count() }}</h3>
            <p>Selesai Dikerjakan</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon submitted">
            <i class="ph-paper-plane"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $results->where('status', 'submitted')->count() }}</h3>
            <p>Sudah Submit</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon graded">
            <i class="ph-star"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $results->where('status', 'graded')->count() }}</h3>
            <p>Sudah Dinilai</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon average">
            <i class="ph-chart-line"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $averageScore ?? '0' }}</h3>
            <p>Rata-rata Nilai</p>
        </div>
    </div>
</div>

<!-- Results Table -->
<div class="results-section">
    <div class="section-header">
        <h3>Hasil Ujian Siswa</h3>
        <div class="section-actions">
            <div class="filter-group">
                <select id="statusFilter" onchange="filterResults()">
                    <option value="">Semua Status</option>
                    <option value="completed">Selesai Dikerjakan</option>
                    <option value="submitted">Sudah Submit</option>
                    <option value="graded">Sudah Dinilai</option>
                </select>
                <select id="gradeFilter" onchange="filterResults()">
                    <option value="">Semua Nilai</option>
                    <option value="A">A (90-100)</option>
                    <option value="B">B (80-89)</option>
                    <option value="C">C (70-79)</option>
                    <option value="D">D (60-69)</option>
                    <option value="E">E (<60)</option>
                </select>
            </div>
        </div>
    </div>
    
    <div class="table-container">
        <table class="results-table" id="resultsTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Status</th>
                    <th>Nilai</th>
                    <th>Grade</th>
                    <th>Waktu Pengerjaan</th>
                    <th>Feedback</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $index => $result)
                    @php
                        $feedback = $feedbackData->get($result->user_id);
                    @endphp
                    <tr data-status="{{ $result->status }}" data-grade="{{ $feedback ? $feedback->grade : '' }}">
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="student-info">
                                <div class="student-avatar">
                                    {{ substr($result->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="student-name">{{ $result->user->name }}</div>
                                    <div class="student-email">{{ $result->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $result->status_badge }}">
                                {{ $result->status_text }}
                            </span>
                        </td>
                        <td>
                            @if($feedback && $feedback->score)
                                <div class="score-info">
                                    <span class="score-value">{{ $feedback->score }}/{{ $feedback->max_score }}</span>
                                    <span class="score-percentage">({{ $feedback->percentage }}%)</span>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($feedback && $feedback->grade)
                                <span class="grade-badge grade-{{ $feedback->grade_color }}">
                                    {{ $feedback->grade }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($result->time_spent)
                                {{ $result->time_spent_formatted }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($feedback && $feedback->feedback_text)
                                <div class="feedback-preview">
                                    <i class="ph-chat-circle"></i>
                                    <span>{{ Str::limit($feedback->feedback_text, 50) }}</span>
                                </div>
                            @else
                                <span class="text-muted">Belum ada</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button onclick="viewStudentProgress({{ $ujian->id }}, {{ $result->user_id }})" 
                                        class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                    <i class="ph-eye"></i>
                                </button>
                                @if($result->status === 'completed' || $result->status === 'submitted')
                                    <button onclick="giveFeedback({{ $ujian->id }}, {{ $result->user_id }})" 
                                            class="btn btn-sm btn-outline-success" title="Berikan Feedback">
                                        <i class="ph-chat-circle"></i>
                                    </button>
                                @endif
                                @if($feedback)
                                    <button onclick="viewFeedback({{ $ujian->id }}, {{ $result->user_id }})" 
                                            class="btn btn-sm btn-outline-info" title="Lihat Feedback">
                                        <i class="ph-note"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            <i class="ph-users"></i>
                            <p>Belum ada hasil ujian</p>
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
.stat-icon.completed { background: #dcfce7; color: #059669; }
.stat-icon.submitted { background: #dbeafe; color: #2563eb; }
.stat-icon.graded { background: #e0e7ff; color: #7c3aed; }
.stat-icon.average { background: #fef3c7; color: #d97706; }

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

.results-section {
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

.filter-group {
    display: flex;
    gap: 0.75rem;
}

.filter-group select {
    padding: 0.5rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.875rem;
}

.table-container {
    overflow-x: auto;
}

.results-table {
    width: 100%;
    border-collapse: collapse;
}

.results-table th,
.results-table td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.results-table th {
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

.status-completed { background: #dcfce7; color: #059669; }
.status-submitted { background: #dbeafe; color: #2563eb; }
.status-graded { background: #e0e7ff; color: #7c3aed; }

.score-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.score-value {
    font-weight: 600;
    color: #374151;
}

.score-percentage {
    font-size: 0.75rem;
    color: #6b7280;
}

.grade-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-align: center;
}

.grade-success { background: #dcfce7; color: #166534; }
.grade-primary { background: #dbeafe; color: #1e40af; }
.grade-warning { background: #fef3c7; color: #92400e; }
.grade-info { background: #dbeafe; color: #1e40af; }
.grade-danger { background: #fee2e2; color: #991b1b; }

.feedback-preview {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    font-size: 0.875rem;
}

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
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .results-table {
        font-size: 0.875rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .filter-group {
        flex-direction: column;
    }
}
</style>
@endsection

@section('scripts')
<script>
let currentUjianId = {{ $ujian->id }};
let currentUserId = null;

function viewStudentProgress(ujianId, userId) {
    window.location.href = "{{ route('teacher.enhanced-exam-management.student-progress', ['', '']) }}/" + ujianId + "/" + userId;
}

function giveFeedback(ujianId, userId) {
    currentUjianId = ujianId;
    currentUserId = userId;
    document.getElementById('feedbackModal').style.display = 'block';
}

function viewFeedback(ujianId, userId) {
    // Implement view feedback modal
    try {
        // Set current user ID
        currentUserId = userId;
        
        // Fetch existing feedback from server
        fetch(`/teacher/exam-results/${ujianId}/feedback/${userId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Populate modal with existing feedback
                    document.getElementById('feedbackText').value = data.feedback || '';
                    document.getElementById('feedbackScore').value = data.score || '';
                    
                    // Show modal
                    document.getElementById('feedbackModal').style.display = 'block';
                    
                    // Update modal title
                    document.querySelector('#feedbackModal .modal-title').textContent = 
                        `Lihat Feedback - ${data.studentName || 'Siswa'}`;
                } else {
                    alert('Tidak dapat memuat feedback: ' + (data.message || 'Terjadi kesalahan'));
                }
            })
            .catch(error => {
                console.error('Error loading feedback:', error);
                alert('Terjadi kesalahan saat memuat feedback');
            });
    } catch (error) {
        console.error('Error in viewFeedback:', error);
        alert('Terjadi kesalahan saat membuka feedback');
    }
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

function filterResults() {
    const statusFilter = document.getElementById('statusFilter').value;
    const gradeFilter = document.getElementById('gradeFilter').value;
    const table = document.getElementById('resultsTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const status = row.getAttribute('data-status');
        const grade = row.getAttribute('data-grade');
        
        let showRow = true;
        
        if (statusFilter && status !== statusFilter) {
            showRow = false;
        }
        
        if (gradeFilter && grade !== gradeFilter) {
            showRow = false;
        }
        
        row.style.display = showRow ? '' : 'none';
    }
}

function exportResults() {
    // Export exam results to CSV
    try {
        // Get current exam ID from the page
        const examId = currentUjianId;
        
        if (!examId) {
            alert('Tidak dapat menemukan ID ujian');
            return;
        }
        
        // Create export URL
        const exportUrl = `/teacher/exam-results/${examId}/export`;
        
        // Create a temporary link to trigger download
        const link = document.createElement('a');
        link.href = exportUrl;
        link.download = `hasil-ujian-${examId}-${new Date().toISOString().slice(0, 10)}.csv`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        console.log('Export exam results initiated');
    } catch (error) {
        console.error('Error exporting exam results:', error);
        alert('Terjadi kesalahan saat mengexport data. Silakan coba lagi.');
    }
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
