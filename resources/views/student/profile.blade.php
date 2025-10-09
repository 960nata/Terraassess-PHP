@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - Profile Siswa')

@section('styles')
<link href="{{ asset('asset/css/student-profile.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-user"></i>
        Profile Saya
    </h1>
    <p class="page-description">Kelola informasi dan pengaturan akun Anda</p>
</div>

<!-- Profile Header -->
<div class="profile-header">
    <div class="profile-info">
        <div class="profile-avatar-section">
            <img src="{{ $user->gambar ? asset('storage/' . $user->gambar) : asset('asset/icons/profile-women.svg') }}" 
                 alt="Profile Picture" 
                 class="profile-avatar">
            <h3 class="profile-name">{{ $user->name }}</h3>
            <p class="profile-email">{{ $user->email }}</p>
        </div>
        <div class="profile-stats">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-number">{{ $user->materi_count ?? 0 }}</div>
                <div class="stat-label">Materi Dibaca</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="stat-number">{{ $user->tugas_count ?? 0 }}</div>
                <div class="stat-label">Tugas Selesai</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="stat-number">{{ $user->ujian_count ?? 0 }}</div>
                <div class="stat-label">Ujian Selesai</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-microchip"></i>
                </div>
                <div class="stat-number">{{ $user->iot_count ?? 0 }}</div>
                <div class="stat-label">Data IoT</div>
            </div>
        </div>
    </div>
</div>

<!-- Navigation Tabs -->
<div class="tabs-section">
    <div class="tabs-nav">
        <button class="tab-btn active" onclick="switchTab('profile')">
            <i class="fas fa-user"></i>
            <span>Informasi Profile</span>
        </button>
        <button class="tab-btn" onclick="switchTab('password')">
            <i class="fas fa-lock"></i>
            <span>Ganti Password</span>
        </button>
        <button class="tab-btn" onclick="switchTab('photo')">
            <i class="fas fa-camera"></i>
            <span>Upload Foto</span>
        </button>
        <button class="tab-btn" onclick="switchTab('about')">
            <i class="fas fa-info-circle"></i>
            <span>Tentang</span>
        </button>
    </div>
</div>

<!-- Tab Content -->
<div class="tab-content active" id="profile">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                <i class="fas fa-user"></i>
                Informasi Profile
            </h4>
        </div>
        <div class="card-body">
            <form action="{{ route('student.update-profile') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="phone" class="form-label">Nomor Telepon</label>
                        <input type="text" class="form-control" id="phone" name="phone" 
                               value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="nis" class="form-label">NIS</label>
                        <input type="text" class="form-control" id="nis" name="nis" 
                               value="{{ old('nis', $user->nis) }}" readonly>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="about" class="form-label">Tentang Saya</label>
                        <textarea class="form-control" id="about" name="about" rows="4" 
                                  placeholder="Ceritakan tentang diri Anda...">{{ old('about', $user->about) }}</textarea>
                        @error('about')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Tab -->
<div class="tab-content" id="password">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                <i class="fas fa-lock"></i>
                Ganti Password
            </h4>
        </div>
        <div class="card-body">
            <form action="{{ route('student.update-password') }}" method="POST">
                @csrf
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="current_password" class="form-label">Password Lama</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        @error('password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-key"></i>
                        <span>Ganti Password</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upload Photo Tab -->
<div class="tab-content" id="photo">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                <i class="fas fa-camera"></i>
                Upload Foto Profile
            </h4>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <form action="{{ route('student.update-photo') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="photo-upload-container">
                    <!-- Current Photo Section -->
                    <div class="current-photo-section">
                        <h5 class="section-title">
                            <i class="fas fa-user-circle"></i>
                            Foto Saat Ini
                        </h5>
                        <div class="current-photo-display">
                            <img src="{{ $user->gambar ? asset('storage/' . $user->gambar) : asset('asset/icons/profile-women.svg') }}" 
                                 alt="Current Photo" 
                                 class="current-avatar">
                            <div class="photo-info">
                                <p class="photo-status">Foto Profile Aktif</p>
                                <p class="photo-details">Klik area upload untuk mengganti foto</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Upload Section -->
                    <div class="upload-section">
                        <h5 class="section-title">
                            <i class="fas fa-cloud-upload-alt"></i>
                            Upload Foto Baru
                        </h5>
                        <div class="upload-area" onclick="document.getElementById('photo').click()">
                            <div class="upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="upload-content">
                                <h6>Klik untuk upload foto atau drag & drop</h6>
                                <p>Format yang didukung: JPG, JPEG, PNG</p>
                                <p class="file-size">Maksimal ukuran: 2MB</p>
                            </div>
                            <input type="file" name="photo" id="photo" class="d-none" 
                                   accept="image/jpeg,image/jpg,image/png">
                        </div>
                        
                        <!-- Preview Section -->
                        <div id="photo-preview" class="photo-preview-section">
                            <div class="preview-placeholder">
                                <i class="fas fa-image"></i>
                                <p>Tidak ada file yang dipilih</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload"></i>
                        <span>Upload Foto</span>
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="resetPhotoUpload()">
                        <i class="fas fa-undo"></i>
                        <span>Reset</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- About Tab -->
<div class="tab-content" id="about">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                <i class="fas fa-info-circle"></i>
                Tentang Aplikasi
            </h4>
        </div>
        <div class="card-body">
            <div class="about-content">
                <div class="about-section">
                    <h5>Terra Assessment</h5>
                    <p>
                        Terra Assessment adalah platform pembelajaran berbasis IoT yang dirancang khusus untuk 
                        mendukung proses belajar mengajar dengan teknologi terdepan. Aplikasi ini memungkinkan 
                        siswa untuk mengakses materi, mengerjakan tugas, mengikuti ujian, dan melakukan penelitian IoT 
                        dalam satu platform yang terintegrasi.
                    </p>
                    
                    <h6>Fitur Utama:</h6>
                    <ul>
                        <li>Dashboard interaktif dengan analisis nilai</li>
                        <li>Manajemen tugas dan ujian online</li>
                        <li>Akses materi pembelajaran digital</li>
                        <li>Penelitian IoT terintegrasi</li>
                        <li>Feedback real-time dari guru</li>
                        <li>Statistik pembelajaran personal</li>
                    </ul>
                </div>
                
                <div class="account-info">
                    <h5>Informasi Akun</h5>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Nama:</span>
                            <span class="info-value">{{ $user->name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email:</span>
                            <span class="info-value">{{ $user->email }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">NIS:</span>
                            <span class="info-value">{{ $user->nis }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Role:</span>
                            <span class="info-value">Siswa</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Bergabung:</span>
                            <span class="info-value">{{ $user->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="support-info">
                        <h6>Kontak Support</h6>
                        <p>
                            Jika Anda mengalami masalah atau membutuhkan bantuan, 
                            silakan hubungi administrator atau guru Anda.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Tab switching functionality
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab content
    document.getElementById(tabName).classList.add('active');
    
    // Add active class to clicked button
    event.target.closest('.tab-btn').classList.add('active');
}

// Photo upload handling
document.getElementById('photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('photo-preview');
    
    if (file) {
        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 2MB.');
            this.value = '';
            return;
        }
        
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            alert('Format file tidak didukung. Gunakan JPG, JPEG, atau PNG.');
            this.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div class="preview-container">
                    <div class="preview-image">
                        <img src="${e.target.result}" alt="Preview" class="preview-avatar">
                        <div class="preview-overlay">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="preview-info">
                        <p class="preview-filename">${file.name}</p>
                        <p class="preview-size">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                        <p class="preview-status">Siap untuk diupload</p>
                    </div>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    } else {
        showPreviewPlaceholder();
    }
});

// Show preview placeholder
function showPreviewPlaceholder() {
    const preview = document.getElementById('photo-preview');
    preview.innerHTML = `
        <div class="preview-placeholder">
            <i class="fas fa-image"></i>
            <p>Tidak ada file yang dipilih</p>
        </div>
    `;
}

// Reset photo upload
function resetPhotoUpload() {
    document.getElementById('photo').value = '';
    showPreviewPlaceholder();
}

// Drag and drop functionality for photo upload
const uploadArea = document.querySelector('.upload-area');

uploadArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('dragover');
});

uploadArea.addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
});

uploadArea.addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        document.getElementById('photo').files = files;
        document.getElementById('photo').dispatchEvent(new Event('change'));
    }
});
</script>
@endsection