@extends('layouts.unified-layout-new')

@section('title', $title)

@section('content')
<div class="superadmin-container">
    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-tasks me-2"></i>Manajemen Tugas
        </h1>
        <p class="page-description">Kelola semua jenis tugas dan pantau progress siswa</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-list-alt"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalTugas }}</div>
                <div class="stat-label">Total Tugas</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $tugasPilihanGanda }}</div>
                <div class="stat-label">Pilihan Ganda</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-edit"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $tugasEssay }}</div>
                <div class="stat-label">Essay</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $tugasMandiri }}</div>
                <div class="stat-label">Mandiri</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $tugasKelompok }}</div>
                <div class="stat-label">Kelompok</div>
            </div>
        </div>
    </div>

    <!-- Task Type Cards -->
    <div class="task-types-section">
        <h2 class="section-title">
            <i class="fas fa-plus-circle me-2"></i>Buat Tugas Baru
        </h2>
        
        <div class="task-cards-grid">
            <!-- Pilihan Ganda Card -->
            <div class="task-card" onclick="window.location.href='{{ route('superadmin.tugas.create', 1) }}'">
                <div class="task-card-header">
                    <div class="task-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="task-type">Pilihan Ganda</div>
                </div>
                <div class="task-card-body">
                    <p class="task-description">Buat soal pilihan ganda dengan opsi A, B, C, D</p>
                    <div class="task-features">
                        <span class="feature-tag">Auto Grading</span>
                        <span class="feature-tag">Multiple Choice</span>
                    </div>
                </div>
                <div class="task-card-footer">
                    <span class="task-count">{{ $tugasPilihanGanda }} tugas</span>
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>

            <!-- Essay Card -->
            <div class="task-card" onclick="window.location.href='{{ route('superadmin.tugas.create', 2) }}'">
                <div class="task-card-header">
                    <div class="task-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="task-type">Essay</div>
                </div>
                <div class="task-card-body">
                    <p class="task-description">Tugas essay dengan penilaian manual oleh guru</p>
                    <div class="task-features">
                        <span class="feature-tag">Manual Grading</span>
                        <span class="feature-tag">Text Input</span>
                    </div>
                </div>
                <div class="task-card-footer">
                    <span class="task-count">{{ $tugasEssay }} tugas</span>
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>

            <!-- Mandiri Card -->
            <div class="task-card" onclick="window.location.href='{{ route('superadmin.tugas.create', 3) }}'">
                <div class="task-card-header">
                    <div class="task-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="task-type">Mandiri</div>
                </div>
                <div class="task-card-body">
                    <p class="task-description">Tugas individual dengan upload file atau input manual</p>
                    <div class="task-features">
                        <span class="feature-tag">File Upload</span>
                        <span class="feature-tag">Individual</span>
                    </div>
                </div>
                <div class="task-card-footer">
                    <span class="task-count">{{ $tugasMandiri }} tugas</span>
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>

            <!-- Kelompok Card -->
            <div class="task-card" onclick="window.location.href='{{ route('superadmin.tugas.create', 4) }}'">
                <div class="task-card-header">
                    <div class="task-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="task-type">Kelompok</div>
                </div>
                <div class="task-card-body">
                    <p class="task-description">Tugas kelompok dengan penilaian antar kelompok</p>
                    <div class="task-features">
                        <span class="feature-tag">Peer Review</span>
                        <span class="feature-tag">Group Work</span>
                    </div>
                </div>
                <div class="task-card-footer">
                    <span class="task-count">{{ $tugasKelompok }} tugas</span>
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tasks Section -->
    <div class="recent-tasks-section">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-clock me-2"></i>Tugas Terbaru
            </h2>
            <a href="{{ route('superadmin.tugas.index') }}" class="view-all-btn">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="tasks-list">
            @forelse($tugasTerbaru as $tugas)
            <div class="task-item">
                <div class="task-info">
                    <div class="task-title">{{ $tugas->name }}</div>
                    <div class="task-meta">
                        <span class="task-type-badge">{{ $tugas->tipe_tugas }}</span>
                        <span class="task-class">{{ $tugas->KelasMapel->Kelas->name ?? 'N/A' }}</span>
                        <span class="task-subject">{{ $tugas->KelasMapel->Mapel->name ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="task-actions">
                    <a href="{{ route('superadmin.tugas.show', $tugas->id) }}" class="btn-action btn-view">
                        <i class="fas fa-eye"></i> Lihat
                    </a>
                    <a href="{{ route('superadmin.tugas.edit', $tugas->id) }}" class="btn-action btn-edit">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <button type="button" class="btn-action btn-delete" onclick="deleteTask({{ $tugas->id }}, '{{ $tugas->name }}')">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Belum ada tugas yang dibuat</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Student Progress Section -->
    <div class="progress-section">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-chart-line me-2"></i>Progress Siswa
            </h2>
        </div>
        
        <div class="progress-list">
            @forelse($progressSiswa as $progress)
            <div class="progress-item">
                <div class="progress-info">
                    <div class="student-name">{{ $progress->user->name }}</div>
                    <div class="task-name">{{ $progress->tugas->name }}</div>
                </div>
                <div class="progress-status">
                    <span class="status-badge status-{{ $progress->status }}">{{ ucfirst($progress->status) }}</span>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $progress->progress_percentage }}%"></div>
                    </div>
                    <span class="progress-percentage">{{ $progress->progress_percentage }}%</span>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-chart-line"></i>
                <p>Belum ada progress siswa</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Konfirmasi Hapus Tugas</h3>
            <span class="close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p>Apakah Anda yakin ingin menghapus tugas <strong id="taskNameToDelete"></strong>?</p>
            <p class="warning-text">Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait tugas ini.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeDeleteModal()">Batal</button>
            <button type="button" class="btn-confirm-delete" onclick="confirmDelete()">Ya, Hapus</button>
        </div>
    </div>
</div>

<script>
let taskToDelete = null;

function deleteTask(taskId, taskName) {
    taskToDelete = taskId;
    document.getElementById('taskNameToDelete').textContent = taskName;
    document.getElementById('deleteModal').style.display = 'block';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    taskToDelete = null;
}

function confirmDelete() {
    if (taskToDelete) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("superadmin.tugas.destroy", ":id") }}'.replace(':id', taskToDelete);
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add method override for DELETE
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('deleteModal');
    if (event.target === modal) {
        closeDeleteModal();
    }
}
</script>

<style>
/* Task Management Styles */
.superadmin-container {
    padding: 2rem;
    max-width: 1400px;
    margin: 0 auto;
}

.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 0.5rem;
}

.page-description {
    color: #718096;
    font-size: 1.1rem;
}

/* Statistics Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    font-size: 2.5rem;
    opacity: 0.8;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

/* Task Types Section */
.task-types-section {
    margin-bottom: 3rem;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1a202c;
    margin-bottom: 1.5rem;
}

.task-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

/* Mobile Responsive - 2x2 Grid */
@media (max-width: 768px) {
    .task-cards-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .task-card {
        padding: 1rem;
    }
    
    .task-type {
        font-size: 1rem;
    }
    
    .task-description {
        font-size: 0.8rem;
    }
}

/* Extra Small Mobile - Single Column */
@media (max-width: 480px) {
    .task-cards-grid {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
}

.task-card {
    background: rgba(30, 41, 59, 0.8);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border: 2px solid transparent;
    cursor: pointer;
    transition: all 0.3s ease;
}

.task-card:hover {
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.task-card-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.task-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.task-card:nth-child(1) .task-icon { background: #10b981; }
.task-card:nth-child(2) .task-icon { background: #3b82f6; }
.task-card:nth-child(3) .task-icon { background: #f59e0b; }
.task-card:nth-child(4) .task-icon { background: #8b5cf6; }

.task-type {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1a202c;
}

.task-description {
    color: #718096;
    margin-bottom: 1rem;
    line-height: 1.5;
}

.task-features {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.feature-tag {
    background: #f7fafc;
    color: #4a5568;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    border: 1px solid #e2e8f0;
}

.task-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #718096;
    font-size: 0.9rem;
}

/* Recent Tasks Section */
.recent-tasks-section {
    margin-bottom: 3rem;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.view-all-btn {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: color 0.3s ease;
}

.view-all-btn:hover {
    color: #5a67d8;
}

.tasks-list {
    background: rgba(30, 41, 59, 0.8);
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.task-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.task-item:last-child {
    border-bottom: none;
}

.task-title {
    font-weight: 600;
    color: #1a202c;
    margin-bottom: 0.5rem;
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

.btn-action {
    background: #667eea;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    border: none;
    cursor: pointer;
    margin-right: 0.5rem;
}

.btn-action:hover {
    background: #5a67d8;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
}

.btn-view {
    background: #667eea;
}

.btn-view:hover {
    background: #5a67d8;
}

.btn-edit {
    background: #f59e0b;
}

.btn-edit:hover {
    background: #d97706;
    box-shadow: 0 4px 8px rgba(245, 158, 11, 0.3);
}

.btn-delete {
    background: #ef4444;
}

.btn-delete:hover {
    background: #dc2626;
    box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
}

/* Modal Styles */
.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: #1e293b;
    border-radius: 12px;
    padding: 0;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid rgba(51, 65, 85, 0.5);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    color: #e2e8f0;
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
}

.close {
    color: #94a3b8;
    font-size: 1.5rem;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.2s ease;
}

.close:hover {
    color: #ef4444;
}

.modal-body {
    padding: 1.5rem;
}

.modal-body p {
    color: #e2e8f0;
    margin-bottom: 1rem;
    line-height: 1.6;
}

.warning-text {
    color: #fbbf24;
    font-size: 0.9rem;
    background: rgba(251, 191, 36, 0.1);
    padding: 0.75rem;
    border-radius: 6px;
    border-left: 4px solid #fbbf24;
}

.modal-footer {
    padding: 1.5rem;
    border-top: 1px solid rgba(51, 65, 85, 0.5);
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
}

.btn-cancel {
    background: #6b7280;
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-cancel:hover {
    background: #4b5563;
}

.btn-confirm-delete {
    background: #ef4444;
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-confirm-delete:hover {
    background: #dc2626;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
}

/* Progress Section */
.progress-section {
    margin-bottom: 3rem;
}

.progress-list {
    background: rgba(30, 41, 59, 0.8);
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.progress-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.progress-item:last-child {
    border-bottom: none;
}

.student-name {
    font-weight: 600;
    color: #1a202c;
    margin-bottom: 0.25rem;
}

.task-name {
    color: #718096;
    font-size: 0.9rem;
}

.progress-status {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-submitted { background: #d1fae5; color: #065f46; }
.status-in_progress { background: #fef3c7; color: #92400e; }
.status-not_started { background: #f3f4f6; color: #374151; }

.progress-bar {
    width: 100px;
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
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem;
    color: #718096;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Responsive Design */
@media (max-width: 768px) {
    .superadmin-container {
        padding: 1rem;
    }
    
    .task-cards-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .task-item, .progress-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .progress-status {
        width: 100%;
        justify-content: space-between;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .task-meta {
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>
@endsection
