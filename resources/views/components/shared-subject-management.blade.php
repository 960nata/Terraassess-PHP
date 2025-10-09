@php
    $userRole = $userRole ?? 'superadmin';
@endphp

<div class="page-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-database"></i>
            Mata Pelajaran
        </h1>
        <p class="page-description">Kelola data mata pelajaran</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-database"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalSubjects ?? 0 }}</div>
                <div class="stat-label">Total Mata Pelajaran</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalTeachers ?? 0 }}</div>
                <div class="stat-label">Guru Mengajar</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalMaterials ?? 0 }}</div>
                <div class="stat-label">Materi Tersedia</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalTasks ?? 0 }}</div>
                <div class="stat-label">Tugas Aktif</div>
            </div>
        </div>
    </div>

    <!-- Subject Filters -->
    <div class="subject-filters">
        <form action="{{ 
            $userRole === 'superadmin' ? route('superadmin.subject-management.filter') : 
            ($userRole === 'admin' ? route('superadmin.subject-management.filter') : route('teacher.subject-management.filter'))
        }}" method="GET" class="filter-form">
            <div class="filter-row">
                <div class="form-group">
                    <label for="filter_category">Kategori</label>
                    <select id="filter_category" name="filter_category">
                        <option value="">Semua Kategori</option>
                        <option value="Umum" {{ request('filter_category') == 'Umum' ? 'selected' : '' }}>Umum</option>
                        <option value="IPA" {{ request('filter_category') == 'IPA' ? 'selected' : '' }}>IPA</option>
                        <option value="IPS" {{ request('filter_category') == 'IPS' ? 'selected' : '' }}>IPS</option>
                        <option value="Bahasa" {{ request('filter_category') == 'Bahasa' ? 'selected' : '' }}>Bahasa</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="filter_level">Tingkat</label>
                    <select id="filter_level" name="filter_level">
                        <option value="">Semua Tingkat</option>
                        <option value="X" {{ request('filter_level') == 'X' ? 'selected' : '' }}>Kelas X</option>
                        <option value="XI" {{ request('filter_level') == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                        <option value="XII" {{ request('filter_level') == 'XII' ? 'selected' : '' }}>Kelas XII</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="filter_search">Cari</label>
                    <input type="text" id="filter_search" name="filter_search" placeholder="Nama mata pelajaran..." value="{{ request('filter_search') }}">
                </div>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-search"></i>
                    Filter
                </button>
                <a href="{{ 
                    $userRole === 'superadmin' ? route('superadmin.subject-management') : 
                    ($userRole === 'admin' ? route('superadmin.subject-management') : route('teacher.subject-management'))
                }}" class="btn-clear">
                    <i class="fas fa-times"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Subjects Table -->
    <div class="table-container">
        <div class="table-header">
            <h3>Daftar Mata Pelajaran</h3>
            <div class="table-actions">
                <button class="btn-export" onclick="exportSubjects()">
                    <i class="fas fa-download"></i>
                    Export
                </button>
            </div>
        </div>
        
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Mata Pelajaran</th>
                        <th>Kategori</th>
                        <th>Tingkat</th>
                        <th>Guru Pengajar</th>
                        <th>Materi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects ?? [] as $index => $subject)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span class="subject-code">{{ $subject->code ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="subject-info">
                                    <div class="subject-name">{{ $subject->name }}</div>
                                    <div class="subject-description">{{ $subject->description ?? 'Tidak ada deskripsi' }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="category-badge">{{ $subject->category ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="level-badge">{{ $subject->level ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="teacher-info">
                                    @if($subject->teachers && $subject->teachers->count() > 0)
                                        <div class="teacher-name">{{ $subject->teachers->first()->name }}</div>
                                        @if($subject->teachers->count() > 1)
                                            <div class="teacher-count">+{{ $subject->teachers->count() - 1 }} lainnya</div>
                                        @endif
                                    @else
                                        <span class="no-teacher">Belum ditugaskan</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="material-count">{{ $subject->materials_count ?? 0 }} materi</span>
                            </td>
                            <td>
                                <span class="status-badge {{ $subject->is_active ? 'active' : 'inactive' }}">
                                    {{ $subject->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-view" onclick="viewSubject('{{ $subject->id }}')" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-edit" onclick="editSubject('{{ $subject->id }}')" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-delete" onclick="deleteSubject('{{ $subject->id }}')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-database"></i>
                                    <p>Tidak ada data mata pelajaran</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Subject Modal -->
<div id="createSubjectModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Tambah Mata Pelajaran Baru</h3>
            <button class="modal-close" onclick="closeCreateSubjectModal()">&times;</button>
        </div>
        <form action="{{ 
            $userRole === 'superadmin' ? route('superadmin.subject-management.create') : 
            ($userRole === 'admin' ? route('superadmin.subject-management.create') : route('teacher.subject-management.create'))
        }}" method="POST" class="modal-form">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Nama Mata Pelajaran</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="code">Kode Mata Pelajaran</label>
                    <input type="text" id="code" name="code" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="category">Kategori</label>
                    <select id="category" name="category" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Umum">Umum</option>
                        <option value="IPA">IPA</option>
                        <option value="IPS">IPS</option>
                        <option value="Bahasa">Bahasa</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="level">Tingkat</label>
                    <select id="level" name="level" required>
                        <option value="">Pilih Tingkat</option>
                        <option value="X">Kelas X</option>
                        <option value="XI">Kelas XI</option>
                        <option value="XII">Kelas XII</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="3"></textarea>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="closeCreateSubjectModal()">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Subject Management Styles */
.page-container {
    padding: 2rem;
    background: #0f172a;
    min-height: 100vh;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.header-content h1 {
    color: #ffffff;
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.header-content p {
    color: #94a3b8;
    font-size: 1rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: #1e293b;
    border-radius: 1rem;
    padding: 1.5rem;
    border: 1px solid #334155;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #667eea;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 0.25rem;
}

.stat-label {
    color: #94a3b8;
    font-size: 0.9rem;
}

.subject-filters {
    background: #1e293b;
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid #334155;
}

.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #ffffff;
    font-size: 0.9rem;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    background: #2a2a3e;
    border: 2px solid #333;
    border-radius: 8px;
    color: #ffffff;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #667eea;
    background: #333;
}

.filter-actions {
    display: flex;
    gap: 1rem;
}

.btn-filter, .btn-clear {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-filter {
    background: #667eea;
    color: white;
}

.btn-clear {
    background: #6b7280;
    color: white;
}

.table-container {
    background: #1e293b;
    border-radius: 1rem;
    padding: 1.5rem;
    border: 1px solid #334155;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.table-header h3 {
    color: #ffffff;
    font-size: 1.25rem;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #334155;
}

.data-table th {
    background: #2a2a3e;
    color: #ffffff;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.data-table td {
    color: #e2e8f0;
}

.subject-code {
    font-family: monospace;
    background: #2a2a3e;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.8rem;
    color: #10b981;
}

.subject-info {
    display: flex;
    flex-direction: column;
}

.subject-name {
    font-weight: 600;
    color: #ffffff;
}

.subject-description {
    font-size: 0.8rem;
    color: #94a3b8;
    margin-top: 0.25rem;
}

.category-badge, .level-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    background: #667eea;
    color: white;
}

.teacher-info {
    display: flex;
    flex-direction: column;
}

.teacher-name {
    font-weight: 600;
    color: #ffffff;
}

.teacher-count {
    font-size: 0.8rem;
    color: #94a3b8;
}

.no-teacher {
    color: #6b7280;
    font-style: italic;
}

.material-count {
    color: #10b981;
    font-weight: 600;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-badge.active {
    background: #10b981;
    color: white;
}

.status-badge.inactive {
    background: #6b7280;
    color: white;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-view, .btn-edit, .btn-delete {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-view {
    background: #3b82f6;
    color: white;
}

.btn-edit {
    background: #f59e0b;
    color: white;
}

.btn-delete {
    background: #ef4444;
    color: white;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #94a3b8;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 1000;
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #1e293b;
    border-radius: 1rem;
    padding: 2rem;
    width: 90%;
    max-width: 500px;
    border: 1px solid #334155;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.modal-header h3 {
    color: #ffffff;
    font-size: 1.25rem;
}

.modal-close {
    background: none;
    border: none;
    color: #94a3b8;
    font-size: 1.5rem;
    cursor: pointer;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 1.5rem;
}

.btn-primary, .btn-secondary {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .filter-row {
        grid-template-columns: 1fr;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .table-wrapper {
        overflow-x: auto;
    }
}
</style>

<script>
function openCreateSubjectModal() {
    document.getElementById('createSubjectModal').style.display = 'block';
}

function closeCreateSubjectModal() {
    document.getElementById('createSubjectModal').style.display = 'none';
}

function viewSubject(subjectId) {
    // Implementation for viewing subject details
    console.log('View subject:', subjectId);
}

function editSubject(subjectId) {
    // Implementation for editing subject
    console.log('Edit subject:', subjectId);
}

function deleteSubject(subjectId) {
    if (confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')) {
        // Implementation for deleting subject
        console.log('Delete subject:', subjectId);
    }
}

function exportSubjects() {
    // Implementation for exporting subjects
    console.log('Export subjects');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('createSubjectModal');
    if (event.target === modal) {
        closeCreateSubjectModal();
    }
}
</script>
