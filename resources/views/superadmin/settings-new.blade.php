@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Pengaturan')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/settings-page.css') }}">
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-cog"></i>
        Pengaturan Sistem
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
                    {{ substr($user->name ?? 'SA', 0, 2) }}
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
    </div>

    <!-- System Settings -->
    <div class="settings-card">
        <h3>
            <i class="fas fa-cogs"></i>
            Pengaturan Sistem
        </h3>
        
        <div class="form-group">
            <label for="site_name">Nama Situs</label>
            <input type="text" id="site_name" name="site_name" value="Terra Assessment" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="site_description">Deskripsi Situs</label>
            <textarea id="site_description" name="site_description" class="form-control" rows="3">Platform Assessment Terintegrasi dengan IoT</textarea>
        </div>
        
        <div class="form-group">
            <label for="maintenance_mode">Mode Maintenance</label>
            <div class="toggle-switch">
                <input type="checkbox" id="maintenance_mode" name="maintenance_mode">
                <span class="toggle-slider"></span>
            </div>
        </div>
    </div>

    <!-- Notification Settings -->
    <div class="settings-card">
        <h3>
            <i class="fas fa-bell"></i>
            Pengaturan Notifikasi
        </h3>
        
        <div class="form-group">
            <label for="email_notifications">Notifikasi Email</label>
            <div class="toggle-switch">
                <input type="checkbox" id="email_notifications" name="email_notifications" checked>
                <span class="toggle-slider"></span>
            </div>
        </div>
        
        <div class="form-group">
            <label for="push_notifications">Push Notifications</label>
            <div class="toggle-switch">
                <input type="checkbox" id="push_notifications" name="push_notifications" checked>
                <span class="toggle-slider"></span>
            </div>
        </div>
        
        <div class="form-group">
            <label for="notification_frequency">Frekuensi Notifikasi</label>
            <select id="notification_frequency" name="notification_frequency" class="form-control">
                <option value="immediate">Seketika</option>
                <option value="hourly">Per Jam</option>
                <option value="daily">Harian</option>
                <option value="weekly">Mingguan</option>
            </select>
        </div>
    </div>

    <!-- Security Settings -->
    <div class="settings-card">
        <h3>
            <i class="fas fa-shield-alt"></i>
            Pengaturan Keamanan
        </h3>
        
        <div class="form-group">
            <label for="session_timeout">Timeout Sesi (menit)</label>
            <input type="number" id="session_timeout" name="session_timeout" value="120" class="form-control" min="5" max="1440">
        </div>
        
        <div class="form-group">
            <label for="max_login_attempts">Maksimal Percobaan Login</label>
            <input type="number" id="max_login_attempts" name="max_login_attempts" value="5" class="form-control" min="3" max="10">
        </div>
        
        <div class="form-group">
            <label for="password_policy">Kebijakan Password</label>
            <select id="password_policy" name="password_policy" class="form-control">
                <option value="basic">Dasar (8 karakter)</option>
                <option value="medium">Sedang (8+ dengan angka)</option>
                <option value="strong">Kuat (8+ dengan angka, huruf besar, simbol)</option>
            </select>
        </div>
    </div>

    <!-- IoT Settings -->
    <div class="settings-card">
        <h3>
            <i class="fas fa-wifi"></i>
            Pengaturan IoT
        </h3>
        
        <div class="form-group">
            <label for="iot_enabled">Aktifkan IoT</label>
            <div class="toggle-switch">
                <input type="checkbox" id="iot_enabled" name="iot_enabled" checked>
                <span class="toggle-slider"></span>
            </div>
        </div>
        
        <div class="form-group">
            <label for="iot_server_url">URL Server IoT</label>
            <input type="url" id="iot_server_url" name="iot_server_url" value="http://localhost:3000" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="iot_api_key">API Key IoT</label>
            <input type="password" id="iot_api_key" name="iot_api_key" class="form-control" placeholder="Masukkan API Key">
        </div>
    </div>

    <!-- Database Settings -->
    <div class="settings-card">
        <h3>
            <i class="fas fa-database"></i>
            Pengaturan Database
        </h3>
        
        <div class="form-group">
            <label>Status Database</label>
            <div class="status-indicator status-active">
                <i class="fas fa-check-circle"></i>
                Terhubung
            </div>
        </div>
        
        <div class="form-group">
            <label for="backup_frequency">Frekuensi Backup</label>
            <select id="backup_frequency" name="backup_frequency" class="form-control">
                <option value="daily">Harian</option>
                <option value="weekly">Mingguan</option>
                <option value="monthly">Bulanan</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="backup_retention">Retensi Backup (hari)</label>
            <input type="number" id="backup_retention" name="backup_retention" value="30" class="form-control" min="7" max="365">
        </div>
    </div>
</div>

<div class="settings-actions">
    <button class="btn-primary" onclick="saveSettings()">
        <i class="fas fa-save"></i>
        Simpan Pengaturan
    </button>
    
    <button class="btn-secondary" onclick="resetSettings()">
        <i class="fas fa-undo"></i>
        Reset ke Default
    </button>
    
    <button class="btn-danger" onclick="resetSystem()">
        <i class="fas fa-exclamation-triangle"></i>
        Reset Sistem
    </button>
</div>

<script>
function uploadProfilePhoto() {
    const form = document.getElementById('photo-upload-form');
    const formData = new FormData(form);
    
    fetch('{{ route("profile.upload-photo") }}', {
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
            alert('Error: ' + data.message);
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
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus foto');
        });
    }
}

function saveSettings() {
    // Implement save settings logic
    alert('Pengaturan berhasil disimpan!');
}

function resetSettings() {
    if (confirm('Apakah Anda yakin ingin mereset pengaturan ke default?')) {
        // Implement reset settings logic
        alert('Pengaturan telah direset ke default!');
    }
}

function resetSystem() {
    if (confirm('PERINGATAN: Tindakan ini akan mereset seluruh sistem. Apakah Anda yakin?')) {
        if (confirm('Ini adalah konfirmasi terakhir. Tindakan ini TIDAK DAPAT DIBATALKAN!')) {
            // Implement system reset logic
            alert('Sistem sedang direset...');
        }
    }
}
</script>
@endsection
