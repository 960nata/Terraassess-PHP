@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Pengaturan Admin')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/settings-page.css') }}">
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-cog"></i>
        Pengaturan Admin
    </h1>
    <p class="page-description">Kelola konfigurasi dan pengaturan sistem Terra Assessment</p>
</div>

<div class="settings-grid">
    <!-- Profile Settings -->
    <div class="settings-card">
        <h3>
            <i class="fas fa-user"></i>
            Profil Pengguna
        </h3>
        
        <div class="profile-photo-section">
            @if($user->profile_photo)
                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo" class="profile-photo">
            @else
                <div class="profile-photo-placeholder">
                    {{ substr($user->name ?? 'AD', 0, 2) }}
                </div>
            @endif
            
            <div class="photo-actions">
                <button class="btn-secondary" onclick="document.getElementById('photo-upload').click()">
                    <i class="fas fa-upload"></i>
                    Upload Foto
                </button>
                @if($user->profile_photo)
                    <button class="btn-danger" onclick="deleteProfilePhoto()">
                        <i class="fas fa-trash"></i>
                        Hapus
                    </button>
                @endif
            </div>
            
            <form id="photo-upload-form" action="{{ route('profile.upload-photo') }}" method="POST" enctype="multipart/form-data" style="display: none;">
                @csrf
                <input type="file" id="photo-upload" name="profile_photo" accept="image/*" onchange="uploadProfilePhoto()">
            </form>
        </div>
        
        <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input type="text" id="name" name="name" value="{{ $user->name ?? '' }}" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ $user->email ?? '' }}" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="phone">Nomor Telepon</label>
            <input type="tel" id="phone" name="phone" value="{{ $user->phone ?? '' }}" class="form-control">
        </div>
        
        <button class="btn-primary" onclick="updateProfile()">
            <i class="fas fa-save"></i>
            Simpan Perubahan
        </button>
    </div>

    <!-- Security Settings -->
    <div class="settings-card">
        <h3>
            <i class="fas fa-shield-alt"></i>
            Keamanan
        </h3>
        
        <div class="form-group">
            <label for="current-password">Password Saat Ini</label>
            <input type="password" id="current-password" name="current_password" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="new-password">Password Baru</label>
            <input type="password" id="new-password" name="new_password" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="confirm-password">Konfirmasi Password Baru</label>
            <input type="password" id="confirm-password" name="confirm_password" class="form-control">
        </div>
        
        <button class="btn-primary" onclick="updatePassword()">
            <i class="fas fa-key"></i>
            Ubah Password
        </button>
    </div>

    <!-- Notification Settings -->
    <div class="settings-card">
        <h3>
            <i class="fas fa-bell"></i>
            Notifikasi
        </h3>
        
        <div class="setting-item">
            <div class="setting-info">
                <h4>Notifikasi Email</h4>
                <p>Terima notifikasi melalui email</p>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" checked>
                <span class="toggle-slider"></span>
            </label>
        </div>
        
        <div class="setting-item">
            <div class="setting-info">
                <h4>Notifikasi Push</h4>
                <p>Terima notifikasi push di browser</p>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" checked>
                <span class="toggle-slider"></span>
            </label>
        </div>
        
        <div class="setting-item">
            <div class="setting-info">
                <h4>Notifikasi Tugas Baru</h4>
                <p>Dapatkan notifikasi ketika ada tugas baru</p>
            </div>
            <label class="toggle-switch">
                <input type="checkbox" checked>
                <span class="toggle-slider"></span>
            </label>
        </div>
        
        <div class="setting-item">
            <div class="setting-info">
                <h4>Notifikasi Ujian</h4>
                <p>Dapatkan notifikasi tentang ujian yang akan datang</p>
            </div>
            <label class="toggle-switch">
                <input type="checkbox">
                <span class="toggle-slider"></span>
            </label>
        </div>
    </div>

    <!-- System Settings -->
    <div class="settings-card">
        <h3>
            <i class="fas fa-cogs"></i>
            Pengaturan Sistem
        </h3>
        
        <div class="form-group">
            <label for="timezone">Zona Waktu</label>
            <select id="timezone" name="timezone" class="form-control">
                <option value="Asia/Jakarta" selected>Asia/Jakarta (WIB)</option>
                <option value="Asia/Makassar">Asia/Makassar (WITA)</option>
                <option value="Asia/Jayapura">Asia/Jayapura (WIT)</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="language">Bahasa</label>
            <select id="language" name="language" class="form-control">
                <option value="id" selected>Bahasa Indonesia</option>
                <option value="en">English</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="theme">Tema</label>
            <select id="theme" name="theme" class="form-control">
                <option value="dark" selected>Dark Mode</option>
                <option value="light">Light Mode</option>
                <option value="auto">Auto (Sesuai Sistem)</option>
            </select>
        </div>
        
        <button class="btn-primary" onclick="updateSystemSettings()">
            <i class="fas fa-save"></i>
            Simpan Pengaturan
        </button>
    </div>

    <!-- Data Management -->
    <div class="settings-card">
        <h3>
            <i class="fas fa-database"></i>
            Manajemen Data
        </h3>
        
        <div class="data-actions">
            <button class="btn-secondary" onclick="exportData()">
                <i class="fas fa-download"></i>
                Export Data
            </button>
            
            <button class="btn-warning" onclick="backupData()">
                <i class="fas fa-archive"></i>
                Backup Data
            </button>
            
            <button class="btn-danger" onclick="clearCache()">
                <i class="fas fa-broom"></i>
                Clear Cache
            </button>
        </div>
        
        <div class="storage-info">
            <h4>Informasi Penyimpanan</h4>
            <div class="storage-item">
                <span>Database Size:</span>
                <span>2.5 GB</span>
            </div>
            <div class="storage-item">
                <span>File Storage:</span>
                <span>1.2 GB</span>
            </div>
            <div class="storage-item">
                <span>Cache Size:</span>
                <span>150 MB</span>
            </div>
        </div>
    </div>

    <!-- Account Actions -->
    <div class="settings-card danger-card">
        <h3>
            <i class="fas fa-exclamation-triangle"></i>
            Aksi Akun
        </h3>
        
        <div class="danger-actions">
            <button class="btn-danger" onclick="logoutAllDevices()">
                <i class="fas fa-sign-out-alt"></i>
                Logout dari Semua Perangkat
            </button>
            
            <button class="btn-danger" onclick="deleteAccount()">
                <i class="fas fa-trash"></i>
                Hapus Akun
            </button>
        </div>
        
        <p class="danger-warning">
            <i class="fas fa-warning"></i>
            Aksi di atas tidak dapat dibatalkan. Pastikan Anda yakin sebelum melanjutkan.
        </p>
    </div>
</div>

<style>
.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.settings-card {
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 2rem;
}

.settings-card h3 {
    color: white;
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0 0 1.5rem 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.profile-photo-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
}

.profile-photo {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 1rem;
}

.profile-photo-placeholder {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 1rem;
}

.photo-actions {
    display: flex;
    gap: 0.75rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    color: white;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 6px;
    color: white;
    font-size: 1rem;
}

.form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.btn-primary, .btn-secondary, .btn-danger, .btn-warning {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.btn-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.btn-warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.setting-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.setting-item:last-child {
    border-bottom: none;
}

.setting-info h4 {
    color: white;
    font-size: 1rem;
    font-weight: 500;
    margin: 0;
}

.setting-info p {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.875rem;
    margin: 0.25rem 0 0 0;
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(255, 255, 255, 0.2);
    transition: 0.3s;
    border-radius: 24px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background-color: #3b82f6;
}

input:checked + .toggle-slider:before {
    transform: translateX(26px);
}

.data-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
}

.storage-info {
    background: rgba(255, 255, 255, 0.05);
    padding: 1.5rem;
    border-radius: 8px;
}

.storage-info h4 {
    color: white;
    font-size: 1rem;
    font-weight: 500;
    margin: 0 0 1rem 0;
}

.storage-item {
    display: flex;
    justify-content: space-between;
    color: rgba(255, 255, 255, 0.7);
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.storage-item:last-child {
    border-bottom: none;
}

.danger-card {
    border-color: rgba(239, 68, 68, 0.3);
}

.danger-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.danger-warning {
    color: rgba(239, 68, 68, 0.8);
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0;
}

@media (max-width: 768px) {
    .settings-grid {
        grid-template-columns: 1fr;
    }
    
    .photo-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .data-actions {
        flex-direction: column;
    }
}
</style>

<script>
function uploadProfilePhoto() {
    const form = document.getElementById('photo-upload-form');
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal mengupload foto: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupload foto');
    });
}

function deleteProfilePhoto() {
    if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
        fetch('{{ route("profile.delete-photo") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menghapus foto: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus foto');
        });
    }
}

function updateProfile() {
    const formData = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value
    };
    
    fetch('{{ route("admin.profile.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Profil berhasil diperbarui');
        } else {
            alert('Gagal memperbarui profil: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memperbarui profil');
    });
}

function updatePassword() {
    const formData = {
        current_password: document.getElementById('current-password').value,
        new_password: document.getElementById('new-password').value,
        confirm_password: document.getElementById('confirm-password').value
    };
    
    if (formData.new_password !== formData.confirm_password) {
        alert('Password baru dan konfirmasi password tidak cocok');
        return;
    }
    
    fetch('{{ route("admin.profile.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Password berhasil diubah');
            document.getElementById('current-password').value = '';
            document.getElementById('new-password').value = '';
            document.getElementById('confirm-password').value = '';
        } else {
            alert('Gagal mengubah password: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengubah password');
    });
}

function updateSystemSettings() {
    const formData = {
        timezone: document.getElementById('timezone').value,
        language: document.getElementById('language').value,
        theme: document.getElementById('theme').value
    };
    
    fetch('{{ route("admin.settings.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Pengaturan sistem berhasil disimpan');
        } else {
            alert('Gagal menyimpan pengaturan: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan pengaturan');
    });
}

function exportData() {
    if (confirm('Apakah Anda yakin ingin mengexport data?')) {
        window.location.href = '{{ route("admin.data.export") }}';
    }
}

function backupData() {
    if (confirm('Apakah Anda yakin ingin membuat backup data?')) {
        fetch('{{ route("admin.data.backup") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Backup berhasil dibuat');
            } else {
                alert('Gagal membuat backup: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat membuat backup');
        });
    }
}

function clearCache() {
    if (confirm('Apakah Anda yakin ingin menghapus cache?')) {
        fetch('{{ route("admin.cache.clear") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cache berhasil dihapus');
            } else {
                alert('Gagal menghapus cache: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus cache');
        });
    }
}

function logoutAllDevices() {
    if (confirm('Apakah Anda yakin ingin logout dari semua perangkat?')) {
        fetch('{{ route("admin.logout.all") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Berhasil logout dari semua perangkat');
                window.location.href = '{{ route("login") }}';
            } else {
                alert('Gagal logout: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat logout');
        });
    }
}

function deleteAccount() {
    if (confirm('PERINGATAN: Tindakan ini akan menghapus akun Anda secara permanen dan tidak dapat dibatalkan. Apakah Anda yakin?')) {
        const confirmation = prompt('Ketik "HAPUS" untuk konfirmasi:');
        if (confirmation === 'HAPUS') {
            fetch('{{ route("admin.account.delete") }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Akun berhasil dihapus');
                    window.location.href = '{{ route("login") }}';
                } else {
                    alert('Gagal menghapus akun: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus akun');
            });
        }
    }
}
</script>
@endsection
