@php
    $userRole = $userRole ?? 'superadmin';
@endphp

<div class="page-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-chart-bar"></i>
            Manajemen Kelas
        </h1>
        <p class="page-description">Kelola data kelas dan mata pelajaran</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-chart-bar"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalClasses ?? 0 }}</div>
                <div class="stat-label">Total Kelas</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalStudents ?? 0 }}</div>
                <div class="stat-label">Total Siswa</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalSubjects ?? 0 }}</div>
                <div class="stat-label">Mata Pelajaran</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalTeachers ?? 0 }}</div>
                <div class="stat-label">Guru Aktif</div>
            </div>
        </div>
    </div>

    <!-- Class Filters -->
    <div class="class-filters">
        <form action="{{ 
            $userRole === 'superadmin' ? route('superadmin.class-management.filter') : 
            ($userRole === 'admin' ? route('superadmin.class-management.filter') : route('teacher.class-management.filter'))
        }}" method="GET" class="filter-form">
            <div class="filter-row">
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
                    <label for="filter_major">Jurusan</label>
                    <select id="filter_major" name="filter_major">
                        <option value="">Semua Jurusan</option>
                        <option value="IPA" {{ request('filter_major') == 'IPA' ? 'selected' : '' }}>IPA</option>
                        <option value="IPS" {{ request('filter_major') == 'IPS' ? 'selected' : '' }}>IPS</option>
                        <option value="Bahasa" {{ request('filter_major') == 'Bahasa' ? 'selected' : '' }}>Bahasa</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="filter_search">Cari</label>
                    <input type="text" id="filter_search" name="filter_search" placeholder="Nama kelas..." value="{{ request('filter_search') }}">
                </div>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-search"></i>
                    Filter
                </button>
                <a href="{{ 
                    $userRole === 'superadmin' ? route('superadmin.class-management') : 
                    ($userRole === 'admin' ? route('superadmin.class-management') : route('teacher.class-management'))
                }}" class="btn-clear">
                    <i class="fas fa-times"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Classes Table -->
    <div class="table-container">
        <div class="table-header">
            <h3>Daftar Kelas</h3>
            <div class="table-actions">
                <button class="btn-export" onclick="exportClasses()">
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
                        <th>Nama Kelas</th>
                        <th>Tingkat</th>
                        <th>Jurusan</th>
                        <th>Wali Kelas</th>
                        <th>Jumlah Siswa</th>
                        <th>Mata Pelajaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classes ?? [] as $index => $class)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="class-info">
                                    <div class="class-name">{{ $class->name }}</div>
                                    <div class="class-code">{{ $class->code ?? 'N/A' }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="level-badge">{{ $class->level ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="major-badge">{{ $class->major ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="teacher-info">
                                    @if($class->wali_kelas)
                                        <div class="teacher-name">{{ $class->wali_kelas->name }}</div>
                                        <div class="teacher-email">{{ $class->wali_kelas->email }}</div>
                                    @else
                                        <span class="no-teacher">Belum ditugaskan</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="student-count">{{ $class->students_count ?? 0 }} siswa</span>
                            </td>
                            <td>
                                <span class="subject-count">{{ $class->subjects_count ?? 0 }} mapel</span>
                            </td>
                            <td>
                                <span class="status-badge {{ $class->is_active ? 'active' : 'inactive' }}">
                                    {{ $class->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-view" onclick="viewClass('{{ $class->id }}')" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-edit" onclick="editClass('{{ $class->id }}')" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-delete" onclick="deleteClass('{{ $class->id }}')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-chart-bar"></i>
                                    <p>Tidak ada data kelas</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Class Modal -->
<div id="createClassModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Tambah Kelas Baru</h3>
            <button class="modal-close" onclick="closeCreateClassModal()">&times;</button>
        </div>
        <form action="{{ 
            $userRole === 'superadmin' ? route('superadmin.class-management.create') : 
            ($userRole === 'admin' ? route('superadmin.class-management.create') : route('teacher.class-management.create'))
        }}" method="POST" class="modal-form">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Nama Kelas</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="code">Kode Kelas</label>
                    <input type="text" id="code" name="code" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="level">Tingkat</label>
                    <select id="level" name="level" required>
                        <option value="">Pilih Tingkat</option>
                        <option value="X">Kelas X</option>
                        <option value="XI">Kelas XI</option>
                        <option value="XII">Kelas XII</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="major">Jurusan</label>
                    <select id="major" name="major" required>
                        <option value="">Pilih Jurusan</option>
                        <option value="IPA">IPA</option>
                        <option value="IPS">IPS</option>
                        <option value="Bahasa">Bahasa</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="3"></textarea>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="closeCreateClassModal()">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Class Management Styles */
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

.class-filters {
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

.class-info {
    display: flex;
    flex-direction: column;
}

.class-name {
    font-weight: 600;
    color: #ffffff;
}

.class-code {
    font-size: 0.8rem;
    color: #94a3b8;
}

.level-badge, .major-badge {
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

.teacher-email {
    font-size: 0.8rem;
    color: #94a3b8;
}

.no-teacher {
    color: #6b7280;
    font-style: italic;
}

.student-count, .subject-count {
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
function openCreateClassModal() {
    document.getElementById('createClassModal').style.display = 'block';
}

function closeCreateClassModal() {
    document.getElementById('createClassModal').style.display = 'none';
}

function viewClass(classId) {
    // Implementation for viewing class details
    console.log('View class:', classId);
}

function editClass(classId) {
    // Implementation for editing class
    console.log('Edit class:', classId);
}

function deleteClass(classId) {
    if (confirm('Apakah Anda yakin ingin menghapus kelas ini?')) {
        // Implementation for deleting class
        console.log('Delete class:', classId);
    }
}

function exportClasses() {
    // Implementation for exporting classes
    console.log('Export classes');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('createClassModal');
    if (event.target === modal) {
        closeCreateClassModal();
    }
}
</script>
