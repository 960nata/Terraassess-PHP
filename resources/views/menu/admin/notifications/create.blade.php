@extends('layouts.unified-layout')

@section('title', $title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus mr-2"></i>
                        Buat Notifikasi Baru
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('admin.notifications.store') }}" method="POST" id="notificationForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Judul Notifikasi -->
                                <div class="form-group">
                                    <label for="title" class="form-label">
                                        <i class="fas fa-heading mr-1"></i>
                                        Judul Notifikasi <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title') }}"
                                           placeholder="Masukkan judul notifikasi"
                                           maxlength="255"
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Excerpt -->
                                <div class="form-group">
                                    <label for="excerpt" class="form-label">
                                        <i class="fas fa-quote-left mr-1"></i>
                                        Ringkasan (Opsional)
                                    </label>
                                    <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                              id="excerpt" 
                                              name="excerpt" 
                                              rows="2"
                                              placeholder="Masukkan ringkasan singkat notifikasi"
                                              maxlength="500">{{ old('excerpt') }}</textarea>
                                    <small class="form-text text-muted">Maksimal 500 karakter</small>
                                    @error('excerpt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Isi Notifikasi -->
                                <div class="form-group">
                                    <label for="body" class="form-label">
                                        <i class="fas fa-align-left mr-1"></i>
                                        Isi Notifikasi <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('body') is-invalid @enderror" 
                                              id="body" 
                                              name="body" 
                                              rows="6"
                                              placeholder="Masukkan isi notifikasi"
                                              required>{{ old('body') }}</textarea>
                                    @error('body')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <!-- Tipe Notifikasi -->
                                <div class="form-group">
                                    <label for="type" class="form-label">
                                        <i class="fas fa-tag mr-1"></i>
                                        Tipe Notifikasi <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('type') is-invalid @enderror" 
                                            id="type" 
                                            name="type" 
                                            required>
                                        <option value="">Pilih tipe notifikasi</option>
                                        <option value="info" {{ old('type') == 'info' ? 'selected' : '' }}>
                                            <i class="fas fa-info-circle"></i> Informasi
                                        </option>
                                        <option value="warning" {{ old('type') == 'warning' ? 'selected' : '' }}>
                                            <i class="fas fa-exclamation-triangle"></i> Peringatan
                                        </option>
                                        <option value="success" {{ old('type') == 'success' ? 'selected' : '' }}>
                                            <i class="fas fa-check-circle"></i> Sukses
                                        </option>
                                        <option value="error" {{ old('type') == 'error' ? 'selected' : '' }}>
                                            <i class="fas fa-times-circle"></i> Error
                                        </option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Target Pengiriman -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-users mr-1"></i>
                                        Target Pengiriman <span class="text-danger">*</span>
                                    </label>
                                    
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="target_type" 
                                               id="target_all" 
                                               value="all" 
                                               {{ old('target_type', 'all') == 'all' ? 'checked' : '' }}
                                               onchange="toggleTargetOptions()">
                                        <label class="form-check-label" for="target_all">
                                            <i class="fas fa-broadcast-tower text-primary mr-1"></i>
                                            Semua User (Broadcast)
                                        </label>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="target_type" 
                                               id="target_role" 
                                               value="role" 
                                               {{ old('target_type') == 'role' ? 'checked' : '' }}
                                               onchange="toggleTargetOptions()">
                                        <label class="form-check-label" for="target_role">
                                            <i class="fas fa-user-tag text-info mr-1"></i>
                                            Berdasarkan Role
                                        </label>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="target_type" 
                                               id="target_specific" 
                                               value="specific" 
                                               {{ old('target_type') == 'specific' ? 'checked' : '' }}
                                               onchange="toggleTargetOptions()">
                                        <label class="form-check-label" for="target_specific">
                                            <i class="fas fa-user text-warning mr-1"></i>
                                            User Tertentu
                                        </label>
                                    </div>
                                    
                                    @error('target_type')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Pilihan Role -->
                                <div class="form-group" id="roleSelection" style="display: none;">
                                    <label for="target_role" class="form-label">
                                        <i class="fas fa-user-tag mr-1"></i>
                                        Pilih Role
                                    </label>
                                    <select class="form-control @error('target_role') is-invalid @enderror" 
                                            id="target_role_select" 
                                            name="target_role">
                                        <option value="">Pilih role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role['id'] }}" {{ old('target_role') == $role['id'] ? 'selected' : '' }}>
                                                {{ $role['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('target_role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Pilihan User -->
                                <div class="form-group" id="userSelection" style="display: none;">
                                    <label for="target_users" class="form-label">
                                        <i class="fas fa-users mr-1"></i>
                                        Pilih User
                                    </label>
                                    <select class="form-control @error('target_users') is-invalid @enderror" 
                                            id="target_users" 
                                            name="target_users[]" 
                                            multiple>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" 
                                                    {{ in_array($user->id, old('target_users', [])) ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Gunakan Ctrl+Click untuk memilih multiple user</small>
                                    @error('target_users')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Preview -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-eye mr-1"></i>
                                        Preview
                                    </label>
                                    <div class="border rounded p-3" id="preview" style="background-color: #f8f9fa;">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-bell text-primary mr-2"></i>
                                            <strong id="previewTitle">Judul Notifikasi</strong>
                                        </div>
                                        <p class="text-muted mb-2" id="previewExcerpt">Ringkasan notifikasi akan muncul di sini</p>
                                        <p class="mb-0" id="previewBody">Isi notifikasi akan muncul di sini</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group text-right">
                                    <button type="button" class="btn btn-secondary mr-2" onclick="history.back()">
                                        <i class="fas fa-times mr-1"></i>
                                        Batal
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane mr-1"></i>
                                        Kirim Notifikasi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleTargetOptions() {
    const targetAll = document.getElementById('target_all');
    const targetRole = document.getElementById('target_role');
    const targetSpecific = document.getElementById('target_specific');
    
    const roleSelection = document.getElementById('roleSelection');
    const userSelection = document.getElementById('userSelection');
    
    if (targetAll.checked) {
        roleSelection.style.display = 'none';
        userSelection.style.display = 'none';
    } else if (targetRole.checked) {
        roleSelection.style.display = 'block';
        userSelection.style.display = 'none';
    } else if (targetSpecific.checked) {
        roleSelection.style.display = 'none';
        userSelection.style.display = 'block';
    }
}

// Update preview
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('title');
    const excerptInput = document.getElementById('excerpt');
    const bodyInput = document.getElementById('body');
    const typeSelect = document.getElementById('type');
    
    const previewTitle = document.getElementById('previewTitle');
    const previewExcerpt = document.getElementById('previewExcerpt');
    const previewBody = document.getElementById('previewBody');
    
    function updatePreview() {
        previewTitle.textContent = titleInput.value || 'Judul Notifikasi';
        previewExcerpt.textContent = excerptInput.value || 'Ringkasan notifikasi akan muncul di sini';
        previewBody.textContent = bodyInput.value || 'Isi notifikasi akan muncul di sini';
        
        // Update preview style based on type
        const preview = document.getElementById('preview');
        const type = typeSelect.value;
        const typeColors = {
            'info': 'border-left: 4px solid #007bff;',
            'warning': 'border-left: 4px solid #ffc107;',
            'success': 'border-left: 4px solid #28a745;',
            'error': 'border-left: 4px solid #dc3545;'
        };
        
        preview.style.cssText = 'background-color: #f8f9fa; ' + (typeColors[type] || '');
    }
    
    titleInput.addEventListener('input', updatePreview);
    excerptInput.addEventListener('input', updatePreview);
    bodyInput.addEventListener('input', updatePreview);
    typeSelect.addEventListener('change', updatePreview);
    
    // Initialize
    toggleTargetOptions();
    updatePreview();
});

// Form validation
document.getElementById('notificationForm').addEventListener('submit', function(e) {
    const targetType = document.querySelector('input[name="target_type"]:checked').value;
    const targetRole = document.getElementById('target_role_select');
    const targetUsers = document.getElementById('target_users');
    
    if (targetType === 'role' && !targetRole.value) {
        e.preventDefault();
        alert('Pilih role target terlebih dahulu');
        return;
    }
    
    if (targetType === 'specific' && targetUsers.selectedOptions.length === 0) {
        e.preventDefault();
        alert('Pilih minimal satu user target');
        return;
    }
});
</script>
@endsection
