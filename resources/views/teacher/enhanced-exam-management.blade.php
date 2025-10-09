@extends('layouts.unified-layout-new')

@section('title', 'Manajemen Ujian Guru')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="ph-exam"></i>
        Manajemen Ujian
    </h1>
    <p class="page-description">Kelola ujian dan evaluasi pembelajaran siswa dengan tracking progress dan feedback</p>
</div>

<!-- Welcome Banner -->
<div class="welcome-banner">
    <div class="welcome-icon">
        <i class="ph-exam"></i>
    </div>
    <div class="welcome-content">
        <h3 class="welcome-title">Manajemen Ujian Terintegrasi</h3>
        <p class="welcome-description">
            Buat, kelola, dan pantau ujian untuk siswa Anda. 
            Lihat progress real-time, berikan feedback, dan analisis performa siswa dengan mudah.
        </p>
    </div>
</div>

<!-- Statistics -->
<div class="dashboard-grid">
    <div class="card">
        <div class="card-icon blue">
            <i class="ph-exam"></i>
        </div>
        <h3 class="card-title">Total Ujian</h3>
        <p class="card-description">{{ $totalExams }} ujian telah dibuat</p>
    </div>
    <div class="card">
        <div class="card-icon green">
            <i class="ph-check-circle"></i>
        </div>
        <h3 class="card-title">Ujian Aktif</h3>
        <p class="card-description">{{ $activeExams }} ujian sedang berlangsung</p>
    </div>
    <div class="card">
        <div class="card-icon purple">
            <i class="ph-clock"></i>
        </div>
        <h3 class="card-title">Ujian Selesai</h3>
        <p class="card-description">{{ $completedExams }} ujian telah selesai</p>
    </div>
    <div class="card">
        <div class="card-icon orange">
            <i class="ph-users"></i>
        </div>
        <h3 class="card-title">Total Peserta</h3>
        <p class="card-description">{{ $totalParticipants }} siswa</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="system-info">
    <h3 class="system-title">
        <i class="ph-plus"></i> Buat Ujian Baru
    </h3>
    
    <form action="{{ route('teacher.enhanced-exam-management.create') }}" method="POST">
        @csrf
        
        <div class="form-row">
            <div class="form-group">
                <label for="exam_title">Judul Ujian</label>
                <input type="text" id="exam_title" name="exam_title" placeholder="Masukkan judul ujian" required>
            </div>
            
            <div class="form-group">
                <label for="class_id">Kelas</label>
                <select id="class_id" name="class_id" required>
                    <option value="">Pilih kelas</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="subject_id">Mata Pelajaran</label>
                <select id="subject_id" name="subject_id" required>
                    <option value="">Pilih mata pelajaran</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label for="exam_type">Tipe Ujian</label>
                <select id="exam_type" name="exam_type" required>
                    <option value="">Pilih tipe ujian</option>
                    <option value="multiple_choice">Pilihan Ganda</option>
                    <option value="essay">Essay</option>
                    <option value="mixed">Campuran</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="duration">Durasi (menit)</label>
                <input type="number" id="duration" name="duration" min="1" max="300" placeholder="Durasi ujian" required>
            </div>
            
            <div class="form-group">
                <label for="max_score">Nilai Maksimal</label>
                <input type="number" id="max_score" name="max_score" min="1" max="100" placeholder="Nilai maksimal" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="due_date">Tanggal Deadline</label>
                <input type="datetime-local" id="due_date" name="due_date" required>
            </div>
            
            <div class="form-group">
                <label for="is_hidden">Status</label>
                <select id="is_hidden" name="is_hidden" required>
                    <option value="0">Publikasikan</option>
                    <option value="1">Simpan sebagai Draft</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="exam_description">Deskripsi Ujian</label>
            <textarea id="exam_description" name="exam_description" rows="3" placeholder="Deskripsi ujian (opsional)"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="ph-plus"></i> Buat Ujian
        </button>
    </form>
</div>

<!-- Exam List with Progress -->
<div class="system-info">
    <h3 class="system-title">
        <i class="ph-list"></i> Daftar Ujian
    </h3>
    
    @if($examsWithProgress->count() > 0)
        <div class="exam-grid">
            @foreach($examsWithProgress as $examData)
                @php
                    $exam = $examData['exam'];
                    $participants = $examData['participants'];
                    $completed = $examData['completed'];
                    $inProgress = $examData['in_progress'];
                    $graded = $examData['graded'];
                    $completionRate = $examData['completion_rate'];
                @endphp
                
                <div class="exam-card">
                    <div class="exam-header">
                        <h4 class="exam-title">{{ $exam->name }}</h4>
                        <span class="exam-status status-{{ $exam->status_color }}">
                            {{ $exam->status_text }}
                        </span>
                    </div>
                    
                    <div class="exam-info">
                        <div class="exam-detail">
                            <i class="ph-graduation-cap"></i>
                            <span>{{ $exam->kelasMapel->kelas->name }} - {{ $exam->kelasMapel->mapel->name }}</span>
                        </div>
                        <div class="exam-detail">
                            <i class="ph-clock"></i>
                            <span>Deadline: {{ $exam->due->format('d M Y H:i') }}</span>
                        </div>
                        <div class="exam-detail">
                            <i class="ph-timer"></i>
                            <span>Durasi: {{ $exam->getDurationFormatted() }}</span>
                        </div>
                        <div class="exam-detail">
                            <i class="ph-question"></i>
                            <span>{{ $exam->total_soal_count }} Soal</span>
                        </div>
                    </div>

                    <!-- Progress Section -->
                    <div class="exam-progress">
                        <div class="progress-header">
                            <span class="progress-label">Progress Siswa</span>
                            <span class="progress-percentage">{{ $completionRate }}%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $completionRate }}%"></div>
                        </div>
                        <div class="progress-stats">
                            <div class="stat">
                                <span class="stat-label">Total:</span>
                                <span class="stat-value">{{ $participants }}</span>
                            </div>
                            <div class="stat">
                                <span class="stat-label">Selesai:</span>
                                <span class="stat-value text-success">{{ $completed }}</span>
                            </div>
                            <div class="stat">
                                <span class="stat-label">Sedang:</span>
                                <span class="stat-value text-warning">{{ $inProgress }}</span>
                            </div>
                            <div class="stat">
                                <span class="stat-label">Dinilai:</span>
                                <span class="stat-value text-primary">{{ $graded }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="exam-actions">
                        <button onclick="viewExam({{ $exam->id }})" class="btn btn-sm btn-outline-primary">
                            <i class="ph-eye"></i> Lihat
                        </button>
                        <button onclick="viewProgress({{ $exam->id }})" class="btn btn-sm btn-outline-info">
                            <i class="ph-chart-line"></i> Progress
                        </button>
                        <button onclick="viewResults({{ $exam->id }})" class="btn btn-sm btn-outline-success">
                            <i class="ph-check-circle"></i> Hasil
                        </button>
                        <button onclick="editExam({{ $exam->id }})" class="btn btn-sm btn-outline-warning">
                            <i class="ph-pencil"></i> Edit
                        </button>
                        <button onclick="deleteExam({{ $exam->id }})" class="btn btn-sm btn-outline-danger">
                            <i class="ph-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="ph-exam"></i>
            <h3>Belum Ada Ujian</h3>
            <p>Mulai buat ujian pertama Anda untuk siswa</p>
        </div>
    @endif
</div>

@endsection

@section('styles')
<style>
.exam-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}

.exam-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
}

.exam-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}

.exam-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.exam-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.exam-status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
}

.status-success { background: #dcfce7; color: #166534; }
.status-warning { background: #fef3c7; color: #92400e; }
.status-danger { background: #fee2e2; color: #991b1b; }
.status-secondary { background: #f3f4f6; color: #374151; }

.exam-info {
    margin-bottom: 1rem;
}

.exam-detail {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
    color: #6b7280;
}

.exam-detail i {
    margin-right: 0.5rem;
    width: 16px;
    color: #9ca3af;
}

.exam-progress {
    background: #f8fafc;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.progress-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
}

.progress-percentage {
    font-size: 0.875rem;
    font-weight: 600;
    color: #059669;
}

.progress-bar {
    height: 8px;
    background: #e5e7eb;
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 0.75rem;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #10b981, #059669);
    transition: width 0.3s ease;
}

.progress-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.5rem;
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
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
}

.exam-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
    border-radius: 6px;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: #6b7280;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h3 {
    margin-bottom: 0.5rem;
    color: #374151;
}

@media (max-width: 768px) {
    .exam-grid {
        grid-template-columns: 1fr;
    }
    
    .exam-actions {
        justify-content: center;
    }
    
    .progress-stats {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
@endsection

@section('scripts')
<script>
function viewExam(examId) {
    window.location.href = "{{ route('teacher.enhanced-exam-management.show', '') }}/" + examId;
}

function viewProgress(examId) {
    window.location.href = "{{ route('teacher.enhanced-exam-management.progress', '') }}/" + examId;
}

function viewResults(examId) {
    window.location.href = "{{ route('teacher.enhanced-exam-management.results', '') }}/" + examId;
}

function editExam(examId) {
    window.location.href = "{{ route('teacher.enhanced-exam-management.edit', '') }}/" + examId;
}

function deleteExam(examId) {
    if (confirm('Apakah Anda yakin ingin menghapus ujian ini? Tindakan ini tidak dapat dibatalkan.')) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('teacher.enhanced-exam-management.destroy', '') }}/" + examId;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
