@php
    $userRole = $userRole ?? 'superadmin';
@endphp

<div class="page-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">
                <i class="fas fa-database"></i>
                Mata Pelajaran
            </h1>
            <p class="page-subtitle">Kelola data mata pelajaran</p>
        </div>
        <div class="header-actions">
            <button class="btn-primary" onclick="openCreateSubjectModal()">
                <i class="fas fa-plus"></i>
                Tambah Mata Pelajaran
            </button>
        </div>
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
            $userRole === 'superadmin' ? route('superadmin.subject-management.store') : 
            ($userRole === 'admin' ? route('admin.subject-management.store') : route('teacher.subject-management.store'))
        }}" method="POST" class="modal-form">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Nama Mata Pelajaran</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="code">Kode Mata Pelajaran</label>
                    <input type="text" id="code" name="code">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="kategori">Kategori</label>
                    <select id="kategori" name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option value="akademik">Akademik</option>
                        <option value="sains">Sains</option>
                        <option value="bahasa">Bahasa</option>
                        <option value="sosial">Sosial</option>
                        <option value="seni">Seni</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="is_active">Status</label>
                    <select id="is_active" name="is_active">
                        <option value="1" selected>Aktif</option>
                        <option value="0">Tidak Aktif</option>
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

<!-- Edit Subject Modal -->
<div id="editSubjectModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Mata Pelajaran</h3>
            <button class="modal-close" onclick="closeEditSubjectModal()">&times;</button>
        </div>
        <form id="editSubjectForm" class="modal-form">
            @csrf
            @method('PUT')
            <div class="form-row">
                <div class="form-group">
                    <label for="edit_name">Nama Mata Pelajaran</label>
                    <input type="text" id="edit_name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="edit_code">Kode Mata Pelajaran</label>
                    <input type="text" id="edit_code" name="code">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="edit_kategori">Kategori</label>
                    <select id="edit_kategori" name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option value="akademik">Akademik</option>
                        <option value="sains">Sains</option>
                        <option value="bahasa">Bahasa</option>
                        <option value="sosial">Sosial</option>
                        <option value="seni">Seni</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit_is_active">Status</label>
                    <select id="edit_is_active" name="is_active">
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="edit_description">Deskripsi</label>
                <textarea id="edit_description" name="description" rows="3"></textarea>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="closeEditSubjectModal()">Batal</button>
                <button type="submit" class="btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- View Subject Modal -->
<div id="viewSubjectModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Detail Mata Pelajaran</h3>
            <button class="modal-close" onclick="closeViewSubjectModal()">&times;</button>
        </div>
        <div class="view-content">
            <div class="view-section">
                <h4>Informasi Umum</h4>
                <div class="view-grid">
                    <div class="view-item">
                        <label>Nama Mata Pelajaran:</label>
                        <span id="view_name">-</span>
                    </div>
                    <div class="view-item">
                        <label>Kode:</label>
                        <span id="view_code">-</span>
                    </div>
                    <div class="view-item">
                        <label>Kategori:</label>
                        <span id="view_kategori">-</span>
                    </div>
                    <div class="view-item">
                        <label>Status:</label>
                        <span id="view_status">-</span>
                    </div>
                </div>
            </div>
            <div class="view-section">
                <h4>Deskripsi</h4>
                <p id="view_description">-</p>
            </div>
            <div class="view-section">
                <h4>Statistik</h4>
                <div class="view-grid">
                    <div class="view-item">
                        <label>Jumlah Kelas:</label>
                        <span id="view_classes_count">-</span>
                    </div>
                    <div class="view-item">
                        <label>Jumlah Guru:</label>
                        <span id="view_teachers_count">-</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="button" class="btn-secondary" onclick="closeViewSubjectModal()">Tutup</button>
        </div>
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

/* View Modal Styles */
.view-content {
    padding: 1rem 0;
}

.view-section {
    margin-bottom: 2rem;
}

.view-section h4 {
    color: #ffffff;
    font-size: 1.1rem;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #334155;
}

.view-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.view-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.view-item label {
    font-weight: 600;
    color: #94a3b8;
    font-size: 0.9rem;
}

.view-item span {
    color: #ffffff;
    font-size: 1rem;
}

.view-item p {
    color: #e2e8f0;
    line-height: 1.6;
    margin: 0;
}

/* Loading and Error States */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.error-message {
    background: #ef4444;
    color: white;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    font-size: 0.9rem;
}

.success-message {
    background: #10b981;
    color: white;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    font-size: 0.9rem;
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
    
    .view-grid {
        grid-template-columns: 1fr;
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
    // Show loading state
    const modal = document.getElementById('viewSubjectModal');
    modal.style.display = 'block';
    
    // Clear previous content
    document.getElementById('view_name').textContent = '-';
    document.getElementById('view_code').textContent = '-';
    document.getElementById('view_kategori').textContent = '-';
    document.getElementById('view_status').textContent = '-';
    document.getElementById('view_description').textContent = '-';
    document.getElementById('view_classes_count').textContent = '-';
    document.getElementById('view_teachers_count').textContent = '-';
    
    // Fetch subject details
    fetch(`/superadmin/subject-management/${subjectId}/edit`)
        .then(response => response.json())
        .then(data => {
            // Populate view modal
            document.getElementById('view_name').textContent = data.name || '-';
            document.getElementById('view_code').textContent = data.code || '-';
            document.getElementById('view_kategori').textContent = data.kategori || '-';
            document.getElementById('view_status').textContent = data.is_active ? 'Aktif' : 'Tidak Aktif';
            document.getElementById('view_description').textContent = data.description || 'Tidak ada deskripsi';
            
            // Get additional data from the table row
            const row = document.querySelector(`button[onclick="viewSubject('${subjectId}')"]`).closest('tr');
            if (row) {
                const classesCount = row.cells[6]?.textContent || '0';
                const teachersCount = row.cells[5]?.querySelector('.teacher-name') ? '1+' : '0';
                
                document.getElementById('view_classes_count').textContent = classesCount;
                document.getElementById('view_teachers_count').textContent = teachersCount;
            }
        })
        .catch(error => {
            console.error('Error fetching subject details:', error);
            alert('Gagal memuat detail mata pelajaran');
        });
}

function editSubject(subjectId) {
    // Show loading state
    const modal = document.getElementById('editSubjectModal');
    modal.style.display = 'block';
    
    // Clear previous content
    document.getElementById('edit_name').value = '';
    document.getElementById('edit_code').value = '';
    document.getElementById('edit_kategori').value = '';
    document.getElementById('edit_is_active').value = '1';
    document.getElementById('edit_description').value = '';
    
    // Fetch subject details
    fetch(`/superadmin/subject-management/${subjectId}/edit`)
        .then(response => response.json())
        .then(data => {
            // Populate edit form
            document.getElementById('edit_name').value = data.name || '';
            document.getElementById('edit_code').value = data.code || '';
            document.getElementById('edit_kategori').value = data.kategori || '';
            document.getElementById('edit_is_active').value = data.is_active ? '1' : '0';
            document.getElementById('edit_description').value = data.description || '';
            
            // Set form action
            document.getElementById('editSubjectForm').action = `/superadmin/subject-management/${subjectId}`;
        })
        .catch(error => {
            console.error('Error fetching subject details:', error);
            alert('Gagal memuat data mata pelajaran');
        });
}

function deleteSubject(subjectId) {
    if (confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')) {
        // Show loading state
        const row = document.querySelector(`button[onclick="deleteSubject('${subjectId}')"]`).closest('tr');
        if (row) {
            row.classList.add('loading');
        }
        
        // Make delete request
        fetch(`/superadmin/subject-management/${subjectId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove row from table
                if (row) {
                    row.remove();
                }
                // Show success message
                showMessage(data.message, 'success');
            } else {
                // Show error message
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error deleting subject:', error);
            showMessage('Gagal menghapus mata pelajaran', 'error');
        })
        .finally(() => {
            // Remove loading state
            if (row) {
                row.classList.remove('loading');
            }
        });
    }
}

function closeEditSubjectModal() {
    document.getElementById('editSubjectModal').style.display = 'none';
}

function closeViewSubjectModal() {
    document.getElementById('viewSubjectModal').style.display = 'none';
}

function showMessage(message, type) {
    // Remove existing messages
    const existingMessages = document.querySelectorAll('.error-message, .success-message');
    existingMessages.forEach(msg => msg.remove());
    
    // Create new message
    const messageDiv = document.createElement('div');
    messageDiv.className = type === 'success' ? 'success-message' : 'error-message';
    messageDiv.textContent = message;
    
    // Insert at top of page container
    const pageContainer = document.querySelector('.page-container');
    pageContainer.insertBefore(messageDiv, pageContainer.firstChild);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        messageDiv.remove();
    }, 5000);
}

function exportSubjects() {
    // Implementation for exporting subjects
    console.log('Export subjects');
}

// Handle edit form submission
document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.getElementById('editSubjectForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const subjectId = this.action.split('/').pop();
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Menyimpan...';
            submitBtn.disabled = true;
            
            fetch(this.action, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeEditSubjectModal();
                    showMessage(data.message, 'success');
                    // Reload page to show updated data
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showMessage(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error updating subject:', error);
                showMessage('Gagal memperbarui mata pelajaran', 'error');
            })
            .finally(() => {
                // Reset button state
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    }
});

// Close modal when clicking outside
window.onclick = function(event) {
    const createModal = document.getElementById('createSubjectModal');
    const editModal = document.getElementById('editSubjectModal');
    const viewModal = document.getElementById('viewSubjectModal');
    
    if (event.target === createModal) {
        closeCreateSubjectModal();
    } else if (event.target === editModal) {
        closeEditSubjectModal();
    } else if (event.target === viewModal) {
        closeViewSubjectModal();
    }
}
</script>
