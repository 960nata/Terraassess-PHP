@extends('layouts.unified-layout-new')

@section('title', 'Pengaturan - Terra Assessment')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-cog"></i>
        Pengaturan
    </h1>
    <p class="page-description">Kelola pengaturan akun dan preferensi Anda</p>
</div>

<div class="settings-container">
    <div class="settings-grid">
        <!-- Profile Settings -->
        <div class="settings-card">
            <div class="settings-card-header">
                <h3 class="settings-card-title">
                    <i class="fas fa-user"></i>
                    Profil
                </h3>
            </div>
            <div class="settings-card-content">
                <div class="profile-info">
                    <div class="profile-avatar">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/photos/' . $user->profile_photo) }}" alt="Profile Photo" class="avatar-img">
                        @else
                            <div class="avatar-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </div>
                    <div class="profile-details">
                        <h4 class="profile-name">{{ $user->name }}</h4>
                        <p class="profile-email">{{ $user->email }}</p>
                        <p class="profile-role">Siswa</p>
                    </div>
                </div>
                <div class="settings-actions">
                    <a href="{{ route('student.profile') }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i>
                        Edit Profil
                    </a>
                </div>
            </div>
        </div>

        <!-- Account Settings -->
        <div class="settings-card">
            <div class="settings-card-header">
                <h3 class="settings-card-title">
                    <i class="fas fa-shield-alt"></i>
                    Keamanan Akun
                </h3>
            </div>
            <div class="settings-card-content">
                <div class="settings-item">
                    <div class="settings-item-info">
                        <h4 class="settings-item-title">Email</h4>
                        <p class="settings-item-description">Alamat email untuk login dan notifikasi</p>
                    </div>
                    <div class="settings-item-value">
                        <span class="settings-value">{{ $user->email }}</span>
                    </div>
                </div>
                
                <div class="settings-item">
                    <div class="settings-item-info">
                        <h4 class="settings-item-title">Kata Sandi</h4>
                        <p class="settings-item-description">Ubah kata sandi untuk keamanan akun</p>
                    </div>
                    <div class="settings-item-action">
                        <button class="btn btn-outline" onclick="changePassword()">
                            <i class="fas fa-key"></i>
                            Ubah Kata Sandi
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Settings -->
        <div class="settings-card">
            <div class="settings-card-header">
                <h3 class="settings-card-title">
                    <i class="fas fa-bell"></i>
                    Notifikasi
                </h3>
            </div>
            <div class="settings-card-content">
                <div class="settings-item">
                    <div class="settings-item-info">
                        <h4 class="settings-item-title">Notifikasi Email</h4>
                        <p class="settings-item-description">Terima notifikasi melalui email</p>
                    </div>
                    <div class="settings-item-action">
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
                
                <div class="settings-item">
                    <div class="settings-item-info">
                        <h4 class="settings-item-title">Notifikasi Tugas</h4>
                        <p class="settings-item-description">Dapatkan notifikasi untuk tugas baru</p>
                    </div>
                    <div class="settings-item-action">
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
                
                <div class="settings-item">
                    <div class="settings-item-info">
                        <h4 class="settings-item-title">Notifikasi Ujian</h4>
                        <p class="settings-item-description">Dapatkan notifikasi untuk ujian baru</p>
                    </div>
                    <div class="settings-item-action">
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Monitoring -->
        <div class="settings-card">
            <div class="settings-card-header">
                <h3 class="settings-card-title">
                    <i class="fas fa-chart-line"></i>
                    Monitoring Aktivitas
                </h3>
            </div>
            <div class="settings-card-content">
                <div class="settings-item">
                    <div class="settings-item-info">
                        <h4 class="settings-item-title">Status Online</h4>
                        <p class="settings-item-description">Status online Anda akan selalu terlihat untuk monitoring aktivitas pembelajaran</p>
                    </div>
                    <div class="settings-item-value">
                        @if($user->isOnline())
                            <span class="status-indicator online">
                                <i class="fas fa-circle"></i>
                                Online
                            </span>
                        @else
                            <span class="status-indicator offline">
                                <i class="fas fa-circle"></i>
                                Offline
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="settings-item">
                    <div class="settings-item-info">
                        <h4 class="settings-item-title">Aktivitas Terakhir</h4>
                        <p class="settings-item-description">Waktu terakhir Anda aktif di sistem</p>
                    </div>
                    <div class="settings-item-value">
                        <span class="settings-value">
                            @if($user->last_activity_at)
                                {{ $user->last_activity_at->format('d M Y, H:i') }}
                            @else
                                Belum pernah aktif
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional-styles')
<style>
/* Dark theme compatible settings styles */
.settings-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0;
}

.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 24px;
}

.settings-card {
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.settings-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.4);
}

.settings-card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
}

.settings-card-title {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.settings-card-content {
    padding: 24px;
}

.profile-info {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
}

.profile-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    overflow: hidden;
    background: rgba(51, 65, 85, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    font-size: 24px;
    color: #94a3b8;
}

.profile-details h4 {
    margin: 0 0 4px 0;
    font-size: 18px;
    font-weight: 600;
    color: #f8fafc;
}

.profile-email {
    margin: 0 0 4px 0;
    color: #cbd5e1;
    font-size: 14px;
}

.profile-role {
    margin: 0;
    color: #94a3b8;
    font-size: 12px;
    text-transform: uppercase;
    font-weight: 500;
}

.settings-actions {
    display: flex;
    gap: 12px;
}

.settings-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 0;
    border-bottom: 1px solid rgba(71, 85, 105, 0.3);
}

.settings-item:last-child {
    border-bottom: none;
}

.settings-item-info {
    flex: 1;
}

.settings-item-title {
    margin: 0 0 4px 0;
    font-size: 16px;
    font-weight: 600;
    color: #f8fafc;
}

.settings-item-description {
    margin: 0;
    font-size: 14px;
    color: #cbd5e1;
}

.settings-item-value {
    flex: 0 0 auto;
}

.settings-value {
    font-size: 14px;
    color: #cbd5e1;
    font-weight: 500;
}

.settings-item-action {
    flex: 0 0 auto;
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
    background-color: #475569;
    transition: .4s;
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
    transition: .4s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background-color: #667eea;
}

input:checked + .toggle-slider:before {
    transform: translateX(26px);
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-outline {
    background: transparent;
    color: #667eea;
    border: 2px solid #667eea;
}

.btn-outline:hover {
    background: #667eea;
    color: white;
}

.status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.status-indicator.online {
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
}

.status-indicator.online i {
    color: #22c55e;
    font-size: 8px;
}

.status-indicator.offline {
    background: rgba(156, 163, 175, 0.2);
    color: #9ca3af;
}

.status-indicator.offline i {
    color: #9ca3af;
    font-size: 8px;
}

/* Page header styling to match unified layout */
.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #f8fafc;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.page-title i {
    color: #667eea;
}

.page-description {
    color: #cbd5e1;
    font-size: 1.125rem;
}

@media (max-width: 768px) {
    .settings-grid {
        grid-template-columns: 1fr;
    }
    
    .settings-container {
        padding: 16px;
    }
    
    .profile-info {
        flex-direction: column;
        text-align: center;
    }
    
    .settings-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    
    .settings-item-action {
        align-self: flex-end;
    }
}
</style>
@endsection

@section('additional-scripts')
<script>
function changePassword() {
    // Implement change password functionality
    alert('Fitur ubah kata sandi akan segera tersedia');
}

// Handle toggle switches
document.querySelectorAll('.toggle-switch input').forEach(toggle => {
    toggle.addEventListener('change', function() {
        // Save settings to backend
        console.log('Setting changed:', this.checked);
    });
});

// Auto-refresh status every 30 seconds
setInterval(function() {
    // Update last activity timestamp
    fetch('{{ route("student.settings") }}', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    }).then(response => {
        if (response.ok) {
            // Reload the page to update status
            location.reload();
        }
    }).catch(error => {
        console.log('Status update failed:', error);
    });
}, 30000); // 30 seconds
</script>
@endsection
