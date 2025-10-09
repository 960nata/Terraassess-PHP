@extends('layouts.unified-layout-new')

@section('title', 'Manajemen Tugas - Terra Assessment')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-tasks"></i>
        Manajemen Tugas
    </h1>
    <p class="page-description">Kelola dan pantau tugas Anda</p>
</div>

<div class="task-management-container">
    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-controls">
            <div class="filter-group">
                <label for="statusFilter" class="filter-label">Status</label>
                <select id="statusFilter" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="pending">Belum Dikerjakan</option>
                    <option value="in_progress">Sedang Dikerjakan</option>
                    <option value="submitted">Sudah Dikumpulkan</option>
                    <option value="graded">Sudah Dinilai</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="typeFilter" class="filter-label">Tipe Tugas</label>
                <select id="typeFilter" class="filter-select">
                    <option value="">Semua Tipe</option>
                    <option value="essay">Essay</option>
                    <option value="multiple_choice">Pilihan Ganda</option>
                    <option value="individual">Tugas Mandiri</option>
                    <option value="group">Tugas Kelompok</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="subjectFilter" class="filter-label">Mata Pelajaran</label>
                <select id="subjectFilter" class="filter-select">
                    <option value="">Semua Mata Pelajaran</option>
                    <option value="matematika">Matematika</option>
                    <option value="fisika">Fisika</option>
                    <option value="kimia">Kimia</option>
                    <option value="biologi">Biologi</option>
                </select>
            </div>
            
            <div class="filter-actions">
                <button class="btn btn-primary" onclick="applyFilters()">
                    <i class="fas fa-filter"></i>
                    Terapkan Filter
                </button>
                <button class="btn btn-outline" onclick="resetFilters()">
                    <i class="fas fa-undo"></i>
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Task Statistics -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">12</div>
                    <div class="stat-label">Total Tugas</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">3</div>
                    <div class="stat-label">Belum Dikerjakan</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">8</div>
                    <div class="stat-label">Sudah Dikumpulkan</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">85</div>
                    <div class="stat-label">Rata-rata Nilai</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Task List -->
    <div class="task-list-section">
        <div class="section-header">
            <h2 class="section-title">Daftar Tugas</h2>
            <div class="view-controls">
                <button class="view-btn active" data-view="grid">
                    <i class="fas fa-th"></i>
                </button>
                <button class="view-btn" data-view="list">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
        
        <div class="task-list" id="taskList">
            <!-- Sample Tasks -->
            <div class="task-card">
                <div class="task-header">
                    <div class="task-title">
                        <h3>Analisis Data IoT</h3>
                        <span class="task-subject">Fisika</span>
                    </div>
                    <div class="task-status pending">
                        <i class="fas fa-clock"></i>
                        Belum Dikerjakan
                    </div>
                </div>
                
                <div class="task-content">
                    <p class="task-description">
                        Analisis data sensor IoT dari percobaan fisika dan buat laporan hasil pengamatan.
                    </p>
                    
                    <div class="task-meta">
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <span>Deadline: 15 Jan 2024</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-user"></i>
                            <span>Guru: Pak Budi</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-tag"></i>
                            <span>Tugas Mandiri</span>
                        </div>
                    </div>
                </div>
                
                <div class="task-actions">
                    <button class="btn btn-primary">
                        <i class="fas fa-play"></i>
                        Mulai Kerjakan
                    </button>
                    <button class="btn btn-outline">
                        <i class="fas fa-eye"></i>
                        Lihat Detail
                    </button>
                </div>
            </div>
            
            <div class="task-card">
                <div class="task-header">
                    <div class="task-title">
                        <h3>Quiz Matematika</h3>
                        <span class="task-subject">Matematika</span>
                    </div>
                    <div class="task-status submitted">
                        <i class="fas fa-check-circle"></i>
                        Sudah Dikumpulkan
                    </div>
                </div>
                
                <div class="task-content">
                    <p class="task-description">
                        Quiz tentang aljabar linear dan matriks.
                    </p>
                    
                    <div class="task-meta">
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <span>Deadline: 10 Jan 2024</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-user"></i>
                            <span>Guru: Bu Sari</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-tag"></i>
                            <span>Pilihan Ganda</span>
                        </div>
                    </div>
                </div>
                
                <div class="task-actions">
                    <button class="btn btn-success">
                        <i class="fas fa-eye"></i>
                        Lihat Hasil
                    </button>
                    <button class="btn btn-outline">
                        <i class="fas fa-download"></i>
                        Download
                    </button>
                </div>
            </div>
            
            <div class="task-card">
                <div class="task-header">
                    <div class="task-title">
                        <h3>Proyek Kelompok Kimia</h3>
                        <span class="task-subject">Kimia</span>
                    </div>
                    <div class="task-status in_progress">
                        <i class="fas fa-spinner"></i>
                        Sedang Dikerjakan
                    </div>
                </div>
                
                <div class="task-content">
                    <p class="task-description">
                        Buat proyek tentang reaksi kimia dalam kehidupan sehari-hari.
                    </p>
                    
                    <div class="task-meta">
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <span>Deadline: 20 Jan 2024</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-user"></i>
                            <span>Guru: Pak Ahmad</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-tag"></i>
                            <span>Tugas Kelompok</span>
                        </div>
                    </div>
                </div>
                
                <div class="task-actions">
                    <button class="btn btn-warning">
                        <i class="fas fa-edit"></i>
                        Lanjutkan
                    </button>
                    <button class="btn btn-outline">
                        <i class="fas fa-users"></i>
                        Tim
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional-styles')
<style>
.task-management-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.filter-section {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.filter-controls {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-label {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.filter-select {
    padding: 10px 12px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    background: white;
    transition: border-color 0.2s ease;
}

.filter-select:focus {
    outline: none;
    border-color: #667eea;
}

.filter-actions {
    display: flex;
    gap: 12px;
}

.stats-section {
    margin-bottom: 32px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 28px;
    font-weight: 700;
    color: #1f2937;
    line-height: 1;
}

.stat-label {
    font-size: 14px;
    color: #6b7280;
    margin-top: 4px;
}

.task-list-section {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid #e5e7eb;
}

.section-title {
    font-size: 20px;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.view-controls {
    display: flex;
    gap: 8px;
}

.view-btn {
    width: 36px;
    height: 36px;
    border: 2px solid #e5e7eb;
    background: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.view-btn.active {
    background: #667eea;
    border-color: #667eea;
    color: white;
}

.task-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 20px;
}

.task-card {
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.2s ease;
}

.task-card:hover {
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
}

.task-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
}

.task-title h3 {
    font-size: 18px;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 4px 0;
}

.task-subject {
    font-size: 12px;
    color: #6b7280;
    background: #f3f4f6;
    padding: 4px 8px;
    border-radius: 6px;
}

.task-status {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 500;
    padding: 6px 12px;
    border-radius: 20px;
}

.task-status.pending {
    background: #fef3c7;
    color: #d97706;
}

.task-status.in_progress {
    background: #dbeafe;
    color: #2563eb;
}

.task-status.submitted {
    background: #d1fae5;
    color: #059669;
}

.task-status.graded {
    background: #e0e7ff;
    color: #7c3aed;
}

.task-description {
    color: #6b7280;
    font-size: 14px;
    line-height: 1.5;
    margin-bottom: 16px;
}

.task-meta {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 16px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #6b7280;
}

.meta-item i {
    width: 16px;
    text-align: center;
}

.task-actions {
    display: flex;
    gap: 12px;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-outline {
    background: transparent;
    color: #667eea;
    border: 2px solid #667eea;
}

.btn-outline:hover {
    background: #667eea;
    color: white;
}

.btn-success {
    background: #10b981;
    color: white;
}

.btn-success:hover {
    background: #059669;
}

.btn-warning {
    background: #f59e0b;
    color: white;
}

.btn-warning:hover {
    background: #d97706;
}

@media (max-width: 768px) {
    .task-list {
        grid-template-columns: 1fr;
    }
    
    .filter-controls {
        grid-template-columns: 1fr;
    }
    
    .filter-actions {
        flex-direction: column;
    }
    
    .task-header {
        flex-direction: column;
        gap: 12px;
    }
    
    .task-actions {
        flex-direction: column;
    }
}
</style>
@endsection

@section('additional-scripts')
<script>
function applyFilters() {
    const statusFilter = document.getElementById('statusFilter').value;
    const typeFilter = document.getElementById('typeFilter').value;
    const subjectFilter = document.getElementById('subjectFilter').value;
    
    console.log('Applying filters:', { statusFilter, typeFilter, subjectFilter });
    // Implement filter logic here
}

function resetFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('typeFilter').value = '';
    document.getElementById('subjectFilter').value = '';
    
    console.log('Filters reset');
    // Implement reset logic here
}

// View toggle functionality
document.querySelectorAll('.view-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const view = this.dataset.view;
        const taskList = document.getElementById('taskList');
        
        if (view === 'list') {
            taskList.style.gridTemplateColumns = '1fr';
        } else {
            taskList.style.gridTemplateColumns = 'repeat(auto-fill, minmax(400px, 1fr))';
        }
    });
});
</script>
@endsection
