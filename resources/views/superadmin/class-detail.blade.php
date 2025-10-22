@extends('layouts.unified-layout')

@section('title', $title)

@section('content')
<div class="page-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">
                <i class="fas fa-chart-bar"></i>
                Detail Kelas: {{ $class->name }}
            </h1>
            <p class="page-subtitle">{{ $class->code }} - {{ $class->level }} {{ $class->major }}</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('superadmin.class-management') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
            <button class="btn-primary" onclick="editClass({{ $class->id }})">
                <i class="fas fa-edit"></i>
                Edit Kelas
            </button>
        </div>
    </div>

    <!-- Class Information -->
    <div class="class-info-section">
        <div class="info-card">
            <h3>Informasi Kelas</h3>
            <div class="info-grid">
                <div class="info-item">
                    <label>Nama Kelas:</label>
                    <span>{{ $class->name }}</span>
                </div>
                <div class="info-item">
                    <label>Kode Kelas:</label>
                    <span>{{ $class->code }}</span>
                </div>
                <div class="info-item">
                    <label>Tingkat:</label>
                    <span class="level-badge">{{ $class->level }}</span>
                </div>
                <div class="info-item">
                    <label>Jurusan:</label>
                    <span class="major-badge">{{ $class->major }}</span>
                </div>
                <div class="info-item">
                    <label>Status:</label>
                    <span class="status-badge {{ $class->is_active ? 'active' : 'inactive' }}">
                        {{ $class->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>
                <div class="info-item">
                    <label>Dibuat:</label>
                    <span>{{ $class->created_at->format('d M Y H:i') }}</span>
                </div>
            </div>
            @if($class->description)
            <div class="description-section">
                <label>Deskripsi:</label>
                <p>{{ $class->description }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalStudents }}</div>
                <div class="stat-label">Total Siswa</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalSubjects }}</div>
                <div class="stat-label">Mata Pelajaran</div>
            </div>
        </div>
    </div>

    <!-- Students List -->
    @if($class->students->count() > 0)
    <div class="table-container">
        <div class="table-header">
            <h3>Daftar Siswa</h3>
        </div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIS</th>
                        <th>Email</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($class->students as $index => $student)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->nis_nip ?? 'N/A' }}</td>
                        <td>{{ $student->email }}</td>
                        <td>
                            <span class="status-badge {{ $student->status === 'active' ? 'active' : 'inactive' }}">
                                {{ ucfirst($student->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="empty-state">
        <i class="fas fa-users"></i>
        <h3>Belum Ada Siswa</h3>
        <p>Kelas ini belum memiliki siswa yang terdaftar.</p>
    </div>
    @endif

    <!-- Subjects List -->
    @if($class->kelasMapel->count() > 0)
    <div class="table-container">
        <div class="table-header">
            <h3>Mata Pelajaran</h3>
        </div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($class->kelasMapel as $index => $kelasMapel)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $kelasMapel->mapel->name ?? 'N/A' }}</td>
                        <td>
                            @if($kelasMapel->editorAccess->isNotEmpty())
                                {{ $kelasMapel->editorAccess->first()->user->name ?? 'N/A' }}
                            @else
                                Belum ditugaskan
                            @endif
                        </td>
                        <td>
                            <span class="status-badge active">Aktif</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="empty-state">
        <i class="fas fa-book"></i>
        <h3>Belum Ada Mata Pelajaran</h3>
        <p>Kelas ini belum memiliki mata pelajaran yang ditugaskan.</p>
    </div>
    @endif
</div>

<style>
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

.header-actions {
    display: flex;
    gap: 1rem;
}

.class-info-section {
    margin-bottom: 2rem;
}

.info-card {
    background: #1e293b;
    border-radius: 1rem;
    padding: 2rem;
    border: 1px solid #334155;
}

.info-card h3 {
    color: #ffffff;
    font-size: 1.25rem;
    margin-bottom: 1.5rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item label {
    color: #94a3b8;
    font-size: 0.9rem;
    font-weight: 600;
}

.info-item span {
    color: #ffffff;
    font-size: 1rem;
}

.description-section {
    margin-top: 1rem;
}

.description-section label {
    color: #94a3b8;
    font-size: 0.9rem;
    font-weight: 600;
    display: block;
    margin-bottom: 0.5rem;
}

.description-section p {
    color: #e2e8f0;
    line-height: 1.6;
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

.table-container {
    background: #1e293b;
    border-radius: 1rem;
    padding: 1.5rem;
    border: 1px solid #334155;
    margin-bottom: 2rem;
}

.table-header {
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

.level-badge, .major-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    background: #667eea;
    color: white;
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

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #94a3b8;
    background: #1e293b;
    border-radius: 1rem;
    border: 1px solid #334155;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.btn-primary, .btn-secondary {
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

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-primary:hover {
    background: #5a67d8;
}

.btn-secondary:hover {
    background: #4a5568;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .header-actions {
        width: 100%;
        justify-content: flex-start;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .table-wrapper {
        overflow-x: auto;
    }
}
</style>

<script>
function editClass(classId) {
    // Redirect to edit page or open modal
    window.location.href = `/superadmin/class-management/${classId}/edit`;
}
</script>
@endsection
