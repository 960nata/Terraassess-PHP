@extends('layouts.unified-layout-new')

@section('title', 'Detail Tugas - ' . $task->name)

@section('content')
<div class="page-header">
    <div class="header-content">
        <div class="header-icon">
            <i class="ph-clipboard-text"></i>
        </div>
        <div class="header-text">
            <h1 class="page-title">{{ $task->name }}</h1>
            <p class="page-description">
                {{ $task->KelasMapel->Kelas->name ?? 'N/A' }} - {{ $task->KelasMapel->Mapel->name ?? 'N/A' }}
            </p>
        </div>
    </div>
    <div class="header-actions">
        <a href="{{ route('teacher.task-management') }}" class="btn btn-secondary">
            <i class="ph-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<!-- Task Overview -->
<div class="task-overview">
    <div class="overview-card">
        <div class="card-header">
            <h3><i class="ph-info"></i> Informasi Tugas</h3>
        </div>
        <div class="card-content">
            <div class="info-grid">
                <div class="info-item">
                    <label>Status</label>
                    <span class="status-badge status-{{ $task->isHidden == 0 ? 'active' : 'draft' }}">
                        {{ $task->isHidden == 0 ? 'Aktif' : 'Draft' }}
                    </span>
                </div>
                <div class="info-item">
                    <label>Deadline</label>
                    <span>{{ $task->due ? \Carbon\Carbon::parse($task->due)->format('d M Y H:i') : 'Tidak ada' }}</span>
                </div>
                <div class="info-item">
                    <label>Tipe Tugas</label>
                    <span class="task-type-badge type-{{ $task->tipe }}">
                        @switch($task->tipe)
                            @case(1) Pilihan Ganda @break
                            @case(2) Esai @break
                            @case(3) Mandiri @break
                            @case(4) Kelompok @break
                            @default Tidak Diketahui
                        @endswitch
                    </span>
                </div>
                <div class="info-item">
                    <label>Total Siswa</label>
                    <span>{{ $totalStudents }} siswa</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon completed">
            <i class="ph-check-circle"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $submittedCount }}</h3>
            <p>Telah Dikumpulkan</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon pending">
            <i class="ph-clock"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $pendingCount }}</h3>
            <p>Belum Dikumpulkan</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon graded">
            <i class="ph-star"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $gradedCount }}</h3>
            <p>Sudah Dinilai</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon average">
            <i class="ph-trend-up"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $averageScore }}%</h3>
            <p>Rata-rata Nilai</p>
        </div>
    </div>
</div>

<!-- Filter and Search -->
<div class="filter-section">
    <div class="filter-form">
        <div class="filter-row">
            <div class="filter-group">
                <label for="filter_status">Status Pengumpulan</label>
                <select id="filter_status" onchange="filterSubmissions()">
                    <option value="">Semua Status</option>
                    <option value="submitted">Sudah Dikumpulkan</option>
                    <option value="pending">Belum Dikumpulkan</option>
                    <option value="graded">Sudah Dinilai</option>
                    <option value="ungraded">Belum Dinilai</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="filter_score">Rentang Nilai</label>
                <select id="filter_score" onchange="filterSubmissions()">
                    <option value="">Semua Nilai</option>
                    <option value="excellent">90-100 (Sangat Baik)</option>
                    <option value="good">80-89 (Baik)</option>
                    <option value="fair">70-79 (Cukup)</option>
                    <option value="poor">0-69 (Kurang)</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="search_student">Cari Siswa</label>
                <input type="text" id="search_student" placeholder="Nama siswa..." onkeyup="searchStudents()">
            </div>
        </div>
    </div>
</div>

<!-- Students Submissions Table -->
<div class="submissions-section">
    <div class="section-header">
        <h3><i class="ph-users"></i> Daftar Siswa & Pengumpulan</h3>
        <div class="section-actions">
            <button class="btn btn-primary" onclick="gradeAll()">
                <i class="ph-star"></i> Nilai Semua
            </button>
            <button class="btn btn-success" onclick="exportGrades()">
                <i class="ph-download"></i> Export Nilai
            </button>
        </div>
    </div>
    
    <div class="submissions-table">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Status</th>
                    <th>Tanggal Submit</th>
                    <th>Nilai</th>
                    <th>Feedback</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="submissions-tbody">
                @foreach($submissions as $index => $submission)
                <tr class="submission-row" data-status="{{ $submission['status'] }}" data-score="{{ $submission['score'] ?? 0 }}">
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div class="student-info">
                            <div class="student-avatar">
                                <i class="ph-user"></i>
                            </div>
                            <div class="student-details">
                                <div class="student-name">{{ $submission['student_name'] }}</div>
                                <div class="student-id">{{ $submission['student_id'] }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="status-badge status-{{ $submission['status'] }}">
                            {{ ucfirst($submission['status']) }}
                        </span>
                    </td>
                    <td>
                        {{ $submission['submitted_at'] ? \Carbon\Carbon::parse($submission['submitted_at'])->format('d M Y H:i') : '-' }}
                    </td>
                    <td>
                        @if($submission['score'] !== null)
                            <span class="score-badge score-{{ $submission['score'] >= 80 ? 'excellent' : ($submission['score'] >= 70 ? 'good' : ($submission['score'] >= 60 ? 'fair' : 'poor')) }}">
                                {{ $submission['score'] }}%
                            </span>
                        @else
                            <span class="score-badge score-ungraded">Belum dinilai</span>
                        @endif
                    </td>
                    <td>
                        @if($submission['feedback'])
                            <span class="feedback-indicator">
                                <i class="ph-chat-circle"></i> Ada feedback
                            </span>
                        @else
                            <span class="feedback-indicator no-feedback">
                                <i class="ph-chat-circle-dashed"></i> Belum ada
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons">
                            @if($submission['status'] == 'submitted')
                                <button class="btn btn-primary btn-sm" onclick="viewSubmission({{ $submission['id'] }})">
                                    <i class="ph-eye"></i> Lihat
                                </button>
                                <button class="btn btn-warning btn-sm" onclick="gradeSubmission({{ $submission['id'] }})">
                                    <i class="ph-star"></i> Nilai
                                </button>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>
                                    <i class="ph-clock"></i> Belum submit
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Grading Modal -->
<div id="gradingModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="ph-star"></i> Penilaian Tugas</h3>
            <button class="modal-close" onclick="closeGradingModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="grading-form">
                <div class="student-info-section">
                    <h4 id="grading-student-name">Nama Siswa</h4>
                    <p id="grading-student-id">ID Siswa</p>
                </div>
                
                <div class="submission-content">
                    <h5>Jawaban Siswa:</h5>
                    <div id="submission-answers" class="submission-answers">
                        <!-- Submission content will be loaded here -->
                    </div>
                </div>
                
                <div class="grading-section">
                    <div class="score-input">
                        <label for="score">Nilai (0-100)</label>
                        <input type="number" id="score" min="0" max="100" placeholder="Masukkan nilai">
                    </div>
                    
                    <div class="feedback-input">
                        <label for="feedback">Feedback untuk Siswa</label>
                        <textarea id="feedback" rows="4" placeholder="Berikan masukan yang membangun untuk siswa..."></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeGradingModal()">Batal</button>
            <button class="btn btn-primary" onclick="saveGrade()">Simpan Nilai</button>
        </div>
    </div>
</div>

<!-- Submission View Modal -->
<div id="submissionModal" class="modal">
    <div class="modal-content large">
        <div class="modal-header">
            <h3><i class="ph-eye"></i> Lihat Pengumpulan</h3>
            <button class="modal-close" onclick="closeSubmissionModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="submission-view">
                <div class="submission-header">
                    <h4 id="view-student-name">Nama Siswa</h4>
                    <div class="submission-meta">
                        <span id="view-submit-date">Tanggal Submit</span>
                        <span id="view-current-score">Nilai Saat Ini</span>
                    </div>
                </div>
                
                <div class="submission-content-view">
                    <div id="submission-content-display">
                        <!-- Submission content will be displayed here -->
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeSubmissionModal()">Tutup</button>
            <button class="btn btn-primary" onclick="gradeFromView()">Nilai Tugas Ini</button>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* Task Detail Management Styles */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    color: white;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.header-icon {
    font-size: 2rem;
    opacity: 0.9;
}

.page-title {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
}

.page-description {
    margin: 0.25rem 0 0 0;
    opacity: 0.9;
    font-size: 0.9rem;
}

.task-overview {
    margin-bottom: 2rem;
}

.overview-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.card-header {
    background: #f8fafc;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.card-header h3 {
    margin: 0;
    color: #2d3748;
    font-size: 1.1rem;
}

.card-content {
    padding: 1.5rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.info-item label {
    font-size: 0.8rem;
    color: #64748b;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.info-item span {
    font-size: 0.95rem;
    color: #2d3748;
    font-weight: 500;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-active {
    background: #dcfce7;
    color: #166534;
}

.status-draft {
    background: #fef3c7;
    color: #92400e;
}

.status-submitted {
    background: #dbeafe;
    color: #1e40af;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-graded {
    background: #dcfce7;
    color: #166534;
}

.task-type-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    background: #e0e7ff;
    color: #3730a3;
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
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-icon.completed {
    background: #10b981;
}

.stat-icon.pending {
    background: #f59e0b;
}

.stat-icon.graded {
    background: #8b5cf6;
}

.stat-icon.average {
    background: #06b6d4;
}

.stat-content h3 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
}

.stat-content p {
    margin: 0.25rem 0 0 0;
    color: #64748b;
    font-size: 0.9rem;
}

.filter-section {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-group label {
    font-size: 0.9rem;
    font-weight: 500;
    color: #374151;
}

.filter-group select,
.filter-group input {
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.9rem;
}

.submissions-section {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.section-header h3 {
    margin: 0;
    color: #2d3748;
    font-size: 1.1rem;
}

.section-actions {
    display: flex;
    gap: 0.5rem;
}

.submissions-table {
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th {
    background: #f8fafc;
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: #374151;
    border-bottom: 1px solid #e2e8f0;
}

.table td {
    padding: 1rem;
    border-bottom: 1px solid #f1f5f9;
}

.student-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.student-avatar {
    width: 2.5rem;
    height: 2.5rem;
    background: #e0e7ff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #3730a3;
}

.student-name {
    font-weight: 500;
    color: #2d3748;
}

.student-id {
    font-size: 0.8rem;
    color: #64748b;
}

.score-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.score-excellent {
    background: #dcfce7;
    color: #166534;
}

.score-good {
    background: #dbeafe;
    color: #1e40af;
}

.score-fair {
    background: #fef3c7;
    color: #92400e;
}

.score-poor {
    background: #fee2e2;
    color: #dc2626;
}

.score-ungraded {
    background: #f1f5f9;
    color: #64748b;
}

.feedback-indicator {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.8rem;
    color: #10b981;
}

.feedback-indicator.no-feedback {
    color: #64748b;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 500;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-warning {
    background: #f59e0b;
    color: white;
}

.btn-warning:hover {
    background: #d97706;
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
}

.btn-success {
    background: #10b981;
    color: white;
}

.btn-success:hover {
    background: #059669;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
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
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 0;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-content.large {
    max-width: 800px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.modal-header h3 {
    margin: 0;
    color: #2d3748;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #64748b;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
    padding: 1.5rem;
    border-top: 1px solid #e2e8f0;
}

.grading-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.student-info-section {
    background: #f8fafc;
    padding: 1rem;
    border-radius: 8px;
}

.submission-answers {
    background: #f8fafc;
    padding: 1rem;
    border-radius: 8px;
    min-height: 200px;
    border: 1px solid #e2e8f0;
}

.grading-section {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.score-input,
.feedback-input {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.score-input input,
.feedback-input textarea {
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 0.9rem;
}

.feedback-input textarea {
    resize: vertical;
    min-height: 100px;
}

.submission-view {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.submission-header {
    background: #f8fafc;
    padding: 1rem;
    border-radius: 8px;
}

.submission-meta {
    display: flex;
    gap: 1rem;
    margin-top: 0.5rem;
    font-size: 0.9rem;
    color: #64748b;
}

.submission-content-view {
    background: #f8fafc;
    padding: 1rem;
    border-radius: 8px;
    min-height: 300px;
    border: 1px solid #e2e8f0;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .filter-row {
        grid-template-columns: 1fr;
    }
    
    .section-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style>
@endpush

@push('scripts')
<script>
let currentSubmissionId = null;

// Filter functions
function filterSubmissions() {
    const statusFilter = document.getElementById('filter_status').value;
    const scoreFilter = document.getElementById('filter_score').value;
    const rows = document.querySelectorAll('.submission-row');
    
    rows.forEach(row => {
        let showRow = true;
        
        // Status filter
        if (statusFilter) {
            const status = row.dataset.status;
            if (statusFilter === 'submitted' && status !== 'submitted') showRow = false;
            if (statusFilter === 'pending' && status !== 'pending') showRow = false;
            if (statusFilter === 'graded' && (!row.querySelector('.score-badge:not(.score-ungraded)'))) showRow = false;
            if (statusFilter === 'ungraded' && row.querySelector('.score-badge:not(.score-ungraded)')) showRow = false;
        }
        
        // Score filter
        if (scoreFilter && showRow) {
            const score = parseInt(row.dataset.score) || 0;
            if (scoreFilter === 'excellent' && (score < 90 || score > 100)) showRow = false;
            if (scoreFilter === 'good' && (score < 80 || score >= 90)) showRow = false;
            if (scoreFilter === 'fair' && (score < 70 || score >= 80)) showRow = false;
            if (scoreFilter === 'poor' && score >= 70) showRow = false;
        }
        
        row.style.display = showRow ? '' : 'none';
    });
}

function searchStudents() {
    const searchTerm = document.getElementById('search_student').value.toLowerCase();
    const rows = document.querySelectorAll('.submission-row');
    
    rows.forEach(row => {
        const studentName = row.querySelector('.student-name').textContent.toLowerCase();
        const studentId = row.querySelector('.student-id').textContent.toLowerCase();
        
        if (studentName.includes(searchTerm) || studentId.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Modal functions
function viewSubmission(submissionId) {
    currentSubmissionId = submissionId;
    
    // Load submission data (this would be an AJAX call in real implementation)
    document.getElementById('view-student-name').textContent = 'Nama Siswa';
    document.getElementById('view-submit-date').textContent = 'Tanggal Submit';
    document.getElementById('view-current-score').textContent = 'Nilai: 85%';
    document.getElementById('submission-content-display').innerHTML = '<p>Jawaban siswa akan ditampilkan di sini...</p>';
    
    document.getElementById('submissionModal').style.display = 'block';
}

function closeSubmissionModal() {
    document.getElementById('submissionModal').style.display = 'none';
    currentSubmissionId = null;
}

function gradeFromView() {
    closeSubmissionModal();
    gradeSubmission(currentSubmissionId);
}

function gradeSubmission(submissionId) {
    currentSubmissionId = submissionId;
    
    // Load submission data for grading
    document.getElementById('grading-student-name').textContent = 'Nama Siswa';
    document.getElementById('grading-student-id').textContent = 'ID: 12345';
    document.getElementById('submission-answers').innerHTML = '<p>Jawaban siswa akan ditampilkan di sini...</p>';
    document.getElementById('score').value = '';
    document.getElementById('feedback').value = '';
    
    document.getElementById('gradingModal').style.display = 'block';
}

function closeGradingModal() {
    document.getElementById('gradingModal').style.display = 'none';
    currentSubmissionId = null;
}

function saveGrade() {
    const score = document.getElementById('score').value;
    const feedback = document.getElementById('feedback').value;
    
    if (!score || score < 0 || score > 100) {
        alert('Masukkan nilai yang valid (0-100)');
        return;
    }
    
    // Here you would make an AJAX call to save the grade
    console.log('Saving grade:', {
        submissionId: currentSubmissionId,
        score: score,
        feedback: feedback
    });
    
    // Show success message
    alert('Nilai berhasil disimpan!');
    
    // Close modal and refresh data
    closeGradingModal();
    // In real implementation, you would refresh the table data here
}

function gradeAll() {
    if (confirm('Apakah Anda yakin ingin menilai semua tugas yang belum dinilai?')) {
        // Implementation for grading all submissions
        console.log('Grading all submissions...');
    }
}

function exportGrades() {
    // Implementation for exporting grades
    console.log('Exporting grades...');
    alert('Fitur export akan segera tersedia!');
}

// Close modals when clicking outside
window.onclick = function(event) {
    const gradingModal = document.getElementById('gradingModal');
    const submissionModal = document.getElementById('submissionModal');
    
    if (event.target === gradingModal) {
        closeGradingModal();
    }
    if (event.target === submissionModal) {
        closeSubmissionModal();
    }
}
</script>
@endpush
