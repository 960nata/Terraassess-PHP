@extends('layouts.unified-layout-new')

@section('title', 'Manajemen Penugasan Guru')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-chalkboard-teacher"></i>
        Manajemen Penugasan Guru
    </h1>
    <p class="page-description">Kelola penugasan guru ke kelas-mata pelajaran</p>
</div>

<!-- Quick Stats -->
<div class="quick-stats">
    <div class="stat-item">
        <div class="stat-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $assignments->total() }}</h3>
            <p>Total Penugasan</p>
        </div>
    </div>
    
    <div class="stat-item">
        <div class="stat-icon">
            <i class="fas fa-chalkboard"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $classes->count() }}</h3>
            <p>Total Kelas</p>
        </div>
    </div>
    
    <div class="stat-item">
        <div class="stat-icon">
            <i class="fas fa-book"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $subjects->count() }}</h3>
            <p>Total Mata Pelajaran</p>
        </div>
    </div>
    
    <div class="stat-item">
        <div class="stat-icon">
            <i class="fas fa-user-tie"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $teachers->count() }}</h3>
            <p>Total Guru</p>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="action-buttons">
    <button class="btn btn-primary" onclick="openAssignTeacherModal()">
        <i class="fas fa-plus"></i>
        Tugaskan Guru
    </button>
    <a href="{{ route('admin.teacher-assignments.dashboard') }}" class="btn btn-info">
        <i class="fas fa-chart-bar"></i>
        Dashboard
    </a>
    <a href="{{ route('admin.teacher-assignments.export') }}" class="btn btn-success">
        <i class="fas fa-download"></i>
        Export
    </a>
    <button class="btn btn-warning" onclick="showImportModal()">
        <i class="fas fa-upload"></i>
        Import
    </button>
</div>

<!-- Assignments Table -->
@include('components.teacher-assignments-table')

<!-- Include Modal -->
@include('components.assign-teacher-modal')

<!-- Import Modal -->
<div id="importModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-upload"></i>
                Import Penugasan Guru
            </h3>
            <button type="button" class="modal-close" onclick="closeImportModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="importForm" method="POST" action="{{ route('admin.teacher-assignments.import') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="importFile" class="form-label">Pilih File Excel</label>
                    <input type="file" id="importFile" name="file" accept=".xlsx,.xls,.csv" required>
                    <small class="form-text">Format: Excel (.xlsx, .xls) atau CSV (.csv)</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Template File</label>
                    <a href="{{ route('admin.teacher-assignments.export') }}" class="btn btn-sm btn-outline">
                        <i class="fas fa-download"></i>
                        Download Template
                    </a>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeImportModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-upload"></i> Import
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.quick-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-item {
    background: linear-gradient(135deg, #1e293b, #334155);
    border-radius: 8px;
    padding: 1rem;
    border: 1px solid #475569;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
}

.stat-content h3 {
    color: #ffffff;
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0;
}

.stat-content p {
    color: #94a3b8;
    margin: 0;
    font-size: 0.9rem;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    border: none;
}

.btn-primary {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #1d4ed8, #1e40af);
    transform: translateY(-1px);
}

.btn-info {
    background: linear-gradient(135deg, #06b6d4, #0891b2);
    color: white;
}

.btn-info:hover {
    background: linear-gradient(135deg, #0891b2, #0e7490);
    transform: translateY(-1px);
}

.btn-success {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.btn-success:hover {
    background: linear-gradient(135deg, #059669, #047857);
    transform: translateY(-1px);
}

.btn-warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #d97706, #b45309);
    transform: translateY(-1px);
}

.btn-outline {
    background: transparent;
    border: 1px solid #334155;
    color: #94a3b8;
}

.btn-outline:hover {
    background: #334155;
    color: #ffffff;
}

@media (max-width: 768px) {
    .quick-stats {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style>

<script>
// Show import modal
function showImportModal() {
    document.getElementById('importModal').style.display = 'flex';
}

// Close import modal
function closeImportModal() {
    document.getElementById('importModal').style.display = 'none';
    document.getElementById('importForm').reset();
}

// Handle import form submission
document.getElementById('importForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Importing...';
    submitBtn.disabled = true;
    
    try {
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Data berhasil diimport!', 'success');
            closeImportModal();
            location.reload();
        } else {
            showNotification(result.message || 'Gagal mengimport data', 'error');
        }
        
    } catch (error) {
        console.error('Import error:', error);
        showNotification('Terjadi kesalahan saat mengimport data', 'error');
    } finally {
        submitBtn.innerHTML = '<i class="fas fa-upload"></i> Import';
        submitBtn.disabled = false;
    }
});

// Notification function
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification-toast ${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => notification.classList.add('show'), 100);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>
@endsection
