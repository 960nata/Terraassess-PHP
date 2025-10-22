@extends('layouts.unified-layout')

@section('title', $title)

@section('content')
<div class="superadmin-container">
    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ $title }}
        </h1>
        <p class="page-description">Kelola dan tanggapi pengaduan dari siswa</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon bg-blue-500">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['total'] }}</h3>
                <p class="stat-label">Total Pengaduan</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-yellow-500">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['pending'] }}</h3>
                <p class="stat-label">Menunggu</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-blue-500">
                <i class="fas fa-cog"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['in_progress'] }}</h3>
                <p class="stat-label">Diproses</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-green-500">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['resolved'] }}</h3>
                <p class="stat-label">Selesai</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon bg-red-500">
                <i class="fas fa-exclamation"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $stats['high_priority'] }}</h3>
                <p class="stat-label">Prioritas Tinggi</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filter Pengaduan</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('superadmin.complaints.index') }}" class="filter-form">
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Status</label>
                        <select name="status" class="filter-select">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="in_progress" {{ ($filters['status'] ?? '') == 'in_progress' ? 'selected' : '' }}>Diproses</option>
                            <option value="resolved" {{ ($filters['status'] ?? '') == 'resolved' ? 'selected' : '' }}>Selesai</option>
                            <option value="closed" {{ ($filters['status'] ?? '') == 'closed' ? 'selected' : '' }}>Ditutup</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Kategori</label>
                        <select name="category" class="filter-select">
                            <option value="">Semua Kategori</option>
                            <option value="akademik" {{ ($filters['category'] ?? '') == 'akademik' ? 'selected' : '' }}>Akademik</option>
                            <option value="fasilitas" {{ ($filters['category'] ?? '') == 'fasilitas' ? 'selected' : '' }}>Fasilitas</option>
                            <option value="bullying" {{ ($filters['category'] ?? '') == 'bullying' ? 'selected' : '' }}>Bullying</option>
                            <option value="lainnya" {{ ($filters['category'] ?? '') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Prioritas</label>
                        <select name="priority" class="filter-select">
                            <option value="">Semua Prioritas</option>
                            <option value="low" {{ ($filters['priority'] ?? '') == 'low' ? 'selected' : '' }}>Rendah</option>
                            <option value="medium" {{ ($filters['priority'] ?? '') == 'medium' ? 'selected' : '' }}>Sedang</option>
                            <option value="high" {{ ($filters['priority'] ?? '') == 'high' ? 'selected' : '' }}>Tinggi</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Pencarian</label>
                        <input type="text" name="search" class="filter-input" 
                               value="{{ $filters['search'] ?? '' }}" 
                               placeholder="Cari berdasarkan judul, isi, atau nama siswa">
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn-filter">
                            <i class="fas fa-search"></i>
                            Filter
                        </button>
                        <a href="{{ route('superadmin.complaints.index') }}" class="btn-reset">
                            <i class="fas fa-times"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Complaints List -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Pengaduan</h3>
        </div>
        <div class="card-body">
            @if($complaints->count() > 0)
                <div class="table-responsive">
                    <table class="complaints-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Siswa</th>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Prioritas</th>
                                <th>Tanggal</th>
                                <th>Balasan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($complaints as $complaint)
                                <tr class="complaint-row">
                                    <td class="complaint-id">#{{ $complaint->id }}</td>
                                    <td class="complaint-student">
                                        <div class="student-info">
                                            <div class="student-name">{{ $complaint->user->name }}</div>
                                            <div class="student-class">{{ $complaint->user->kelas->name ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td class="complaint-subject">
                                        <div class="subject-text">{{ Str::limit($complaint->subject, 50) }}</div>
                                        <div class="subject-preview">{{ Str::limit($complaint->message, 80) }}</div>
                                    </td>
                                    <td class="complaint-category">
                                        <span class="category-badge">{{ $complaint->category_text }}</span>
                                    </td>
                                    <td class="complaint-status">
                                        <span class="status-badge status-{{ $complaint->status }}">{{ $complaint->status_text }}</span>
                                    </td>
                                    <td class="complaint-priority">
                                        <span class="priority-badge priority-{{ $complaint->priority }}">{{ $complaint->priority_text }}</span>
                                    </td>
                                    <td class="complaint-date">
                                        <div class="date-info">
                                            <div class="date-text">{{ $complaint->created_at->format('d M Y') }}</div>
                                            <div class="time-text">{{ $complaint->created_at->format('H:i') }}</div>
                                        </div>
                                    </td>
                                    <td class="complaint-replies">
                                        <span class="replies-count">{{ $complaint->replies->count() }}</span>
                                    </td>
                                    <td class="complaint-actions">
                                        <a href="{{ route('superadmin.complaints.show', $complaint->id) }}" 
                                           class="btn-action btn-view">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('superadmin.complaints.destroy', $complaint->id) }}" 
                                              method="POST" class="inline-form"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengaduan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $complaints->appends(request()->query())->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h3 class="empty-title">Tidak Ada Pengaduan</h3>
                    <p class="empty-description">Belum ada pengaduan yang sesuai dengan filter yang dipilih</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* SuperAdmin Container */
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
    color: #f8fafc;
    margin-bottom: 0.5rem;
}

.page-description {
    color: #94a3b8;
    font-size: 1.1rem;
}

/* Statistics */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: rgba(30, 41, 59, 0.8);
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    border: 1px solid rgba(51, 65, 85, 0.5);
}

.stat-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #f8fafc;
    margin: 0;
}

.stat-label {
    color: #94a3b8;
    font-size: 0.875rem;
    margin: 0;
}

/* Card */
.card {
    background: rgba(30, 41, 59, 0.8);
    border-radius: 12px;
    border: 1px solid rgba(51, 65, 85, 0.5);
    margin-bottom: 2rem;
}

.card-header {
    padding: 1.5rem;
    border-bottom: 1px solid rgba(51, 65, 85, 0.5);
}

.card-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #e2e8f0;
    margin: 0;
}

.card-body {
    padding: 1.5rem;
}

/* Filters */
.filter-form {
    margin-bottom: 0;
}

.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #e2e8f0;
    margin-bottom: 0.5rem;
}

.filter-select, .filter-input {
    padding: 0.75rem;
    border: 1px solid rgba(51, 65, 85, 0.5);
    border-radius: 8px;
    background: rgba(15, 23, 42, 0.8);
    color: #e2e8f0;
    font-size: 0.875rem;
}

.filter-select:focus, .filter-input:focus {
    outline: none;
    border-color: #3b82f6;
}

.filter-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-filter, .btn-reset {
    padding: 0.75rem 1rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn-filter {
    background: #3b82f6;
    color: white;
    border: none;
    cursor: pointer;
}

.btn-filter:hover {
    background: #2563eb;
}

.btn-reset {
    background: #6b7280;
    color: white;
}

.btn-reset:hover {
    background: #4b5563;
}

/* Table */
.table-responsive {
    overflow-x: auto;
}

.complaints-table {
    width: 100%;
    border-collapse: collapse;
}

.complaints-table th {
    background: rgba(15, 23, 42, 0.8);
    color: #e2e8f0;
    font-weight: 600;
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid rgba(51, 65, 85, 0.5);
}

.complaints-table td {
    padding: 1rem;
    border-bottom: 1px solid rgba(51, 65, 85, 0.3);
    color: #e2e8f0;
}

.complaint-row:hover {
    background: rgba(51, 65, 85, 0.2);
}

.complaint-id {
    font-weight: 600;
    color: #3b82f6;
}

.student-info {
    display: flex;
    flex-direction: column;
}

.student-name {
    font-weight: 500;
    color: #f8fafc;
}

.student-class {
    font-size: 0.75rem;
    color: #94a3b8;
}

.subject-text {
    font-weight: 500;
    color: #f8fafc;
    margin-bottom: 0.25rem;
}

.subject-preview {
    font-size: 0.75rem;
    color: #94a3b8;
}

.category-badge {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
}

.status-badge, .priority-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-pending {
    background: rgba(245, 158, 11, 0.2);
    color: #f59e0b;
}

.status-in_progress {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
}

.status-resolved {
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
}

.status-closed {
    background: rgba(107, 114, 128, 0.2);
    color: #6b7280;
}

.priority-low {
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
}

.priority-medium {
    background: rgba(245, 158, 11, 0.2);
    color: #f59e0b;
}

.priority-high {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.date-info {
    display: flex;
    flex-direction: column;
}

.date-text {
    font-weight: 500;
    color: #f8fafc;
}

.time-text {
    font-size: 0.75rem;
    color: #94a3b8;
}

.replies-count {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
}

.complaint-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-action {
    padding: 0.5rem;
    border-radius: 4px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-view {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
}

.btn-view:hover {
    background: rgba(59, 130, 246, 0.3);
}

.btn-delete {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.btn-delete:hover {
    background: rgba(239, 68, 68, 0.3);
}

.inline-form {
    display: inline;
}

/* Pagination */
.pagination-wrapper {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem;
}

.empty-icon {
    font-size: 4rem;
    color: #6b7280;
    margin-bottom: 1rem;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #e2e8f0;
    margin-bottom: 0.5rem;
}

.empty-description {
    color: #94a3b8;
}

/* Responsive */
@media (max-width: 768px) {
    .filter-row {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>
@endsection
