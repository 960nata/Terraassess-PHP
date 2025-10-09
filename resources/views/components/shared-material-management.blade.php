@php
    $userRole = $userRole ?? 'superadmin';
@endphp

<div class="page-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-file-alt"></i>
            Manajemen Materi
        </h1>
        <p class="page-description">Kelola materi pembelajaran</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalMaterials ?? 0 }}</div>
                <div class="stat-label">Total Materi</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $publishedMaterials ?? 0 }}</div>
                <div class="stat-label">Materi Dipublikasi</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-eye"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalViews ?? 0 }}</div>
                <div class="stat-label">Total Dilihat</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-download"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalDownloads ?? 0 }}</div>
                <div class="stat-label">Total Download</div>
            </div>
        </div>
    </div>

    <!-- Material Filters -->
    <div class="material-filters">
        <form action="{{ 
            $userRole === 'superadmin' ? route('superadmin.material-management.filter') : 
            ($userRole === 'admin' ? route('superadmin.material-management.filter') : route('teacher.material-management.filter'))
        }}" method="GET" class="filter-form">
            <div class="filter-row">
                <div class="form-group">
                    <label for="filter_subject">Mata Pelajaran</label>
                    <select id="filter_subject" name="filter_subject">
                        <option value="">Semua Mata Pelajaran</option>
                        @foreach($subjects ?? [] as $subject)
                            <option value="{{ $subject->id }}" {{ request('filter_subject') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="filter_class">Kelas</label>
                    <select id="filter_class" name="filter_class">
                        <option value="">Semua Kelas</option>
                        @foreach($classes ?? [] as $class)
                            <option value="{{ $class->id }}" {{ request('filter_class') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="filter_type">Tipe</label>
                    <select id="filter_type" name="filter_type">
                        <option value="">Semua Tipe</option>
                        <option value="pdf" {{ request('filter_type') == 'pdf' ? 'selected' : '' }}>PDF</option>
                        <option value="video" {{ request('filter_type') == 'video' ? 'selected' : '' }}>Video</option>
                        <option value="image" {{ request('filter_type') == 'image' ? 'selected' : '' }}>Gambar</option>
                        <option value="document" {{ request('filter_type') == 'document' ? 'selected' : '' }}>Dokumen</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="filter_search">Cari</label>
                    <input type="text" id="filter_search" name="filter_search" placeholder="Judul materi..." value="{{ request('filter_search') }}">
                </div>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-search"></i>
                    Filter
                </button>
                <a href="{{ 
                    $userRole === 'superadmin' ? route('superadmin.material-management') : 
                    ($userRole === 'admin' ? route('superadmin.material-management') : route('teacher.material-management'))
                }}" class="btn-clear">
                    <i class="fas fa-times"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Materials Table -->
    <div class="table-container">
        <div class="table-header">
            <h3>Daftar Materi</h3>
            <div class="table-actions">
                <button class="btn-export" onclick="exportMaterials()">
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
                        <th>Thumbnail</th>
                        <th>Judul</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Tipe</th>
                        <th>Ukuran</th>
                        <th>Dilihat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials ?? [] as $index => $material)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="material-thumbnail">
                                    @if($material->thumbnail)
                                        <img src="{{ asset('storage/' . $material->thumbnail) }}" alt="{{ $material->title }}">
                                    @else
                                        <div class="thumbnail-placeholder">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="material-info">
                                    <div class="material-title">{{ $material->title }}</div>
                                    <div class="material-description">{{ Str::limit($material->description, 50) }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="subject-badge">{{ $material->subject->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="class-badge">{{ $material->class->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="type-badge type-{{ $material->type }}">
                                    @switch($material->type)
                                        @case('pdf') PDF @break
                                        @case('video') Video @break
                                        @case('image') Gambar @break
                                        @case('document') Dokumen @break
                                        @default {{ ucfirst($material->type) }}
                                    @endswitch
                                </span>
                            </td>
                            <td>
                                <span class="file-size">{{ $material->formatted_file_size ?? '0 B' }}</span>
                            </td>
                            <td>
                                <span class="view-count">{{ $material->views ?? 0 }}</span>
                            </td>
                            <td>
                                <span class="status-badge {{ $material->status === 'published' ? 'published' : 'draft' }}">
                                    {{ $material->status === 'published' ? 'Dipublikasi' : 'Draft' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-view" onclick="viewMaterial('{{ $material->id }}')" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-edit" onclick="editMaterial('{{ $material->id }}')" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-download" onclick="downloadMaterial('{{ $material->id }}')" title="Download">
                                        <i class="fas fa-download"></i>
                                    </button>
                                    <button class="btn-delete" onclick="deleteMaterial('{{ $material->id }}')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-file-alt"></i>
                                    <p>Tidak ada data materi</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


<style>
/* Material Management Styles */
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

.material-filters {
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

.material-thumbnail {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    overflow: hidden;
}

.material-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.thumbnail-placeholder {
    width: 100%;
    height: 100%;
    background: #2a2a3e;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #667eea;
    font-size: 1.5rem;
}

.material-info {
    display: flex;
    flex-direction: column;
}

.material-title {
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 0.25rem;
}

.material-description {
    font-size: 0.8rem;
    color: #94a3b8;
}

.subject-badge, .class-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    background: #667eea;
    color: white;
}

.type-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.type-pdf { background: #ef4444; color: white; }
.type-video { background: #3b82f6; color: white; }
.type-image { background: #10b981; color: white; }
.type-document { background: #f59e0b; color: white; }

.file-size, .view-count {
    color: #94a3b8;
    font-size: 0.9rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-badge.published {
    background: #10b981;
    color: white;
}

.status-badge.draft {
    background: #6b7280;
    color: white;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-view, .btn-edit, .btn-download, .btn-delete {
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

.btn-download {
    background: #10b981;
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

.btn-primary {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #667eea;
    color: white;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary:hover {
    background: #5a67d8;
    color: white;
    text-decoration: none;
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
function viewMaterial(materialId) {
    // Implementation for viewing material
    console.log('View material:', materialId);
}

function editMaterial(materialId) {
    // Implementation for editing material
    console.log('Edit material:', materialId);
}

function downloadMaterial(materialId) {
    // Implementation for downloading material
    console.log('Download material:', materialId);
}

function deleteMaterial(materialId) {
    if (confirm('Apakah Anda yakin ingin menghapus materi ini?')) {
        // Implementation for deleting material
        console.log('Delete material:', materialId);
    }
}

function exportMaterials() {
    // Implementation for exporting materials
    console.log('Export materials');
}
</script>
