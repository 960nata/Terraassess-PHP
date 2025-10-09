@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - Profil Super Admin')

@section('styles')
<style>
/* Profile Page Styles */
.profile-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
    padding-top: 6rem; /* Account for fixed header */
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .profile-container {
        padding: 1rem;
        padding-top: 5rem; /* Account for fixed header on mobile */
    }
    
    .profile-header {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .profile-avatar-section {
        align-self: center;
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
    
    .security-item,
    .preference-item {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .security-info,
    .preference-info {
        text-align: center;
    }
}

.profile-header {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.9) 0%, rgba(51, 65, 85, 0.8) 100%);
    backdrop-filter: blur(10px);
    border-radius: 1rem;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid rgba(51, 65, 85, 0.5);
    display: flex;
    align-items: center;
    gap: 2rem;
    min-height: 200px;
}

.profile-avatar-section {
    position: relative;
    flex-shrink: 0;
}

.profile-avatar-large {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: 700;
    color: white;
    position: relative;
    border: 4px solid rgba(255, 255, 255, 0.2);
}

.profile-avatar-large img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.change-avatar-btn {
    position: absolute;
    bottom: -5px;
    right: -5px;
    background: #10b981;
    color: white;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.change-avatar-btn:hover {
    background: #059669;
    transform: scale(1.1);
}

.profile-info {
    flex: 1;
}

.profile-name {
    font-size: 2rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 0.5rem;
}

.profile-role {
    font-size: 1.1rem;
    color: #cbd5e1;
    margin-bottom: 0.25rem;
}

.profile-email {
    font-size: 1rem;
    color: #94a3b8;
    margin-bottom: 1rem;
}

.profile-status {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.status-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
}

.status-badge.active {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.status-badge i {
    font-size: 0.75rem;
}

/* Profile Content */
.profile-content {
    background: rgba(30, 41, 59, 0.6);
    backdrop-filter: blur(10px);
    border-radius: 1rem;
    padding: 2rem;
    border: 1px solid rgba(51, 65, 85, 0.5);
    overflow: hidden;
    position: relative;
}

.profile-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 2rem;
    border-bottom: 1px solid rgba(51, 65, 85, 0.5);
    overflow-x: auto;
    padding-bottom: 0.5rem;
}

.tab-button {
    background: none;
    border: none;
    padding: 1rem 1.5rem;
    color: #94a3b8;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    border-radius: 0.5rem 0.5rem 0 0;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    white-space: nowrap;
    flex-shrink: 0;
}

.tab-button:hover {
    color: #ffffff;
    background: rgba(51, 65, 85, 0.3);
}

.tab-button.active {
    color: #ffffff;
    background: rgba(102, 126, 234, 0.2);
    border-bottom: 2px solid #667eea;
}

.tab-content {
    display: none;
    animation: fadeIn 0.3s ease-in-out;
}

.tab-content.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Form Styles */
.form-section {
    margin-bottom: 2rem;
    background: rgba(30, 41, 59, 0.3);
    padding: 1.5rem;
    border-radius: 0.75rem;
    border: 1px solid rgba(51, 65, 85, 0.3);
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-title::before {
    content: '';
    width: 4px;
    height: 24px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 2px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
    align-items: start;
}

.form-group {
    margin-bottom: 1.5rem;
    display: flex;
    flex-direction: column;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #ffffff;
    font-size: 0.9rem;
}

.form-input,
.form-textarea,
.form-select {
    width: 100%;
    padding: 0.75rem 1rem;
    background: rgba(42, 42, 62, 0.8);
    border: 2px solid rgba(51, 65, 85, 0.5);
    border-radius: 8px;
    color: #ffffff;
    font-size: 1rem;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.form-input:focus,
.form-textarea:focus,
.form-select:focus {
    outline: none;
    border-color: #667eea;
    background: rgba(51, 65, 85, 0.8);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-textarea {
    min-height: 100px;
    resize: vertical;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    justify-content: center;
    min-width: 120px;
}

.btn-primary {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: #ffffff;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    background: #334155;
    color: #ffffff;
}

.btn-secondary:hover {
    background: #475569;
}

.btn-outline {
    background: transparent;
    color: #667eea;
    border: 2px solid #667eea;
}

.btn-outline:hover {
    background: #667eea;
    color: #ffffff;
}

/* Security Items */
.security-item,
.preference-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    background: rgba(30, 41, 59, 0.2);
    border-radius: 0.75rem;
    border: 1px solid rgba(51, 65, 85, 0.3);
    margin-bottom: 1rem;
}

.security-info,
.preference-info {
    flex: 1;
}

.security-info h4,
.preference-info h4 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 0.25rem;
}

.security-info p,
.preference-info p {
    color: #94a3b8;
    font-size: 0.9rem;
}

/* Toggle Switch */
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
    flex-shrink: 0;
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
    background-color: #334155;
    transition: 0.3s;
    border-radius: 24px;
    border: 1px solid rgba(51, 65, 85, 0.5);
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
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

input:checked + .toggle-slider {
    background-color: #10b981;
    border-color: #059669;
}

input:checked + .toggle-slider:before {
    transform: translateX(26px);
    box-shadow: 0 2px 6px rgba(16, 185, 129, 0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    .profile-container {
        padding: 1rem;
        padding-top: 5rem;
    }
    
    .profile-header {
        flex-direction: column;
        text-align: center;
        gap: 1.5rem;
        padding: 1.5rem;
    }
    
    .profile-tabs {
        flex-direction: column;
        gap: 0.25rem;
        overflow-x: visible;
    }
    
    .tab-button {
        justify-content: center;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .form-actions {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .security-item,
    .preference-item {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
        padding: 1rem;
    }
}

@media (max-width: 480px) {
    .profile-avatar-large {
        width: 100px;
        height: 100px;
        font-size: 2rem;
    }
    
    .profile-container {
        padding: 0.75rem;
        padding-top: 4.5rem;
    }
    
    .profile-name {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }
    
    .profile-content {
        padding: 1.5rem;
        margin: 0;
    }
}
</style>
@endsection

@section('content')
<div class="profile-container">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-avatar-section">
            <div class="profile-avatar-large">
                @if($user->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo">
                @else
                    {{ substr($user->name ?? 'SA', 0, 2) }}
                @endif
            </div>
            <button class="change-avatar-btn" onclick="document.getElementById('photo-upload').click()">
                <i class="fas fa-camera"></i>
            </button>
            <input type="file" id="photo-upload" accept="image/*" style="display: none;" onchange="uploadProfilePhoto()">
        </div>
        <div class="profile-info">
            <h2 class="profile-name">{{ $user->name ?? 'Super Admin' }}</h2>
            <p class="profile-role">Super Administrator</p>
            <p class="profile-email">{{ $user->email ?? 'superadmin@terraassessment.com' }}</p>
            <div class="profile-status">
                <span class="status-badge active">
                    <i class="fas fa-circle"></i>
                    Aktif
                </span>
            </div>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="profile-content">
        <div class="profile-tabs">
            <button class="tab-button active" onclick="switchTab('personal')">
                <i class="fas fa-user"></i>
                Informasi Pribadi
            </button>
            <button class="tab-button" onclick="switchTab('security')">
                <i class="fas fa-shield-alt"></i>
                Keamanan
            </button>
            <button class="tab-button" onclick="switchTab('preferences')">
                <i class="fas fa-cog"></i>
                Preferensi
            </button>
        </div>

        <!-- Personal Information Tab -->
        <div class="tab-content active" id="personal-tab">
            <div class="form-section">
                <h3 class="section-title">Informasi Dasar</h3>
                <form class="profile-form" action="{{ route('superadmin.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" id="name" name="name" value="{{ $user->name ?? 'Super Admin' }}" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="{{ $user->email ?? 'superadmin@terraassessment.com' }}" class="form-input" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Nomor Telepon</label>
                            <input type="tel" id="phone" name="phone" value="{{ $user->phone ?? '+62 812 3456 7890' }}" class="form-input">
                        </div>
                        <div class="form-group">
                            <label for="position">Posisi</label>
                            <input type="text" id="position" name="position" value="Super Administrator" class="form-input" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <textarea id="bio" name="bio" class="form-textarea" rows="4" placeholder="Ceritakan tentang diri Anda...">{{ $user->bio ?? 'Sebagai Super Administrator, saya bertanggung jawab untuk mengelola seluruh sistem Terra Assessment dan memastikan keamanan serta performa optimal.' }}</textarea>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Security Tab -->
        <div class="tab-content" id="security-tab">
            <div class="form-section">
                <h3 class="section-title">Keamanan Akun</h3>
                <div class="security-item">
                    <div class="security-info">
                        <h4>Kata Sandi</h4>
                        <p>Terakhir diubah {{ $user->password_changed_at ? $user->password_changed_at->diffForHumans() : '30 hari yang lalu' }}</p>
                    </div>
                    <button class="btn btn-outline" onclick="changePassword()">
                        <i class="fas fa-key"></i>
                        Ubah Kata Sandi
                    </button>
                </div>
                <div class="security-item">
                    <div class="security-info">
                        <h4>Autentikasi Dua Faktor</h4>
                        <p>Tambahkan lapisan keamanan ekstra</p>
                    </div>
                    <button class="btn btn-outline" onclick="enable2FA()">
                        <i class="fas fa-shield-alt"></i>
                        Aktifkan 2FA
                    </button>
                </div>
                <div class="security-item">
                    <div class="security-info">
                        <h4>Sesi Aktif</h4>
                        <p>Kelola perangkat yang terhubung</p>
                    </div>
                    <button class="btn btn-outline" onclick="viewSessions()">
                        <i class="fas fa-desktop"></i>
                        Lihat Sesi
                    </button>
                </div>
            </div>
        </div>

        <!-- Preferences Tab -->
        <div class="tab-content" id="preferences-tab">
            <div class="form-section">
                <h3 class="section-title">Preferensi Sistem</h3>
                <div class="preference-item">
                    <div class="preference-info">
                        <h4>Bahasa</h4>
                        <p>Pilih bahasa antarmuka</p>
                    </div>
                    <select class="form-select" name="language">
                        <option value="id" {{ ($user->language ?? 'id') == 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                        <option value="en" {{ ($user->language ?? 'id') == 'en' ? 'selected' : '' }}>English</option>
                    </select>
                </div>
                <div class="preference-item">
                    <div class="preference-info">
                        <h4>Zona Waktu</h4>
                        <p>Atur zona waktu untuk tampilan</p>
                    </div>
                    <select class="form-select" name="timezone">
                        <option value="WIB" {{ ($user->timezone ?? 'WIB') == 'WIB' ? 'selected' : '' }}>WIB (UTC+7)</option>
                        <option value="WITA" {{ ($user->timezone ?? 'WIB') == 'WITA' ? 'selected' : '' }}>WITA (UTC+8)</option>
                        <option value="WIT" {{ ($user->timezone ?? 'WIB') == 'WIT' ? 'selected' : '' }}>WIT (UTC+9)</option>
                    </select>
                </div>
                <div class="preference-item">
                    <div class="preference-info">
                        <h4>Notifikasi Email</h4>
                        <p>Terima notifikasi melalui email</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="email_notifications" {{ ($user->email_notifications ?? true) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Tab switching functionality
function switchTab(tabName) {
    // Remove active class from all tabs and content
    document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    
    // Add active class to selected tab and content
    document.querySelector(`[onclick="switchTab('${tabName}')"]`).classList.add('active');
    document.getElementById(`${tabName}-tab`).classList.add('active');
}

// Form handling
function resetForm() {
    // Reset form to original values
    document.getElementById('name').value = '{{ $user->name ?? "Super Admin" }}';
    document.getElementById('email').value = '{{ $user->email ?? "superadmin@terraassessment.com" }}';
    document.getElementById('phone').value = '{{ $user->phone ?? "+62 812 3456 7890" }}';
    document.getElementById('bio').value = '{{ $user->bio ?? "Sebagai Super Administrator, saya bertanggung jawab untuk mengelola seluruh sistem Terra Assessment dan memastikan keamanan serta performa optimal." }}';
}

// Profile photo upload
function uploadProfilePhoto() {
    const fileInput = document.getElementById('photo-upload');
    const file = fileInput.files[0];
    
    if (file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Harap pilih file gambar yang valid');
            return;
        }
        
        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran file maksimal 5MB');
            return;
        }
        
        // Show loading
        const avatar = document.querySelector('.profile-avatar-large');
        const originalContent = avatar.innerHTML;
        avatar.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        // Create form data
        const formData = new FormData();
        formData.append('profile_photo', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        // Upload file
        fetch('{{ route("superadmin.profile.upload-photo") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update avatar
                avatar.innerHTML = `<img src="${data.photo_url}" alt="Profile Photo">`;
                showNotification('Foto profil berhasil diubah', 'success');
            } else {
                avatar.innerHTML = originalContent;
                showNotification(data.message || 'Gagal mengubah foto profil', 'error');
            }
        })
        .catch(error => {
            avatar.innerHTML = originalContent;
            showNotification('Terjadi kesalahan saat mengubah foto profil', 'error');
            console.error('Error:', error);
        });
    }
}

// Security functions
function changePassword() {
    // Show change password modal or redirect
    window.location.href = '{{ route("superadmin.profile.change-password") }}';
}

function enable2FA() {
    // Show 2FA setup modal or redirect
    window.location.href = '{{ route("superadmin.profile.2fa") }}';
}

function viewSessions() {
    // Show active sessions modal or redirect
    window.location.href = '{{ route("superadmin.profile.sessions") }}';
}

// Notification function
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification-toast ${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
        <span>${message}</span>
    `;
    
    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;
    
    // Add to body
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Form submission with loading state
document.addEventListener('DOMContentLoaded', function() {
    const profileForm = document.querySelector('.profile-form');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            submitBtn.disabled = true;
            
            // Reset after 3 seconds (in case of error)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        });
    }
});
</script>
@endsection

@php
    $roleTitle = 'Super Admin';
    $roleIcon = 'fas fa-crown';
    $roleInitial = 'SA';
    $roleDescription = 'Kontrol penuh atas sistem Terra Assessment';
    $welcomeMessage = 'Sebagai Super Admin, Anda memiliki akses penuh untuk mengelola seluruh sistem.';
    $permissionsTitle = 'Hak Akses Super Admin';
    $permissions = [
        'Kelola semua pengguna sistem',
        'Akses ke semua fitur aplikasi',
        'Konfigurasi sistem global',
        'Monitoring aktivitas pengguna'
    ];
    $responsibilitiesTitle = 'Tanggung Jawab';
    $responsibilities = [
        'Memastikan keamanan sistem',
        'Mengelola data pengguna',
        'Konfigurasi aplikasi',
        'Backup dan maintenance'
    ];
    $profileRoute = route('superadmin.profile');
    $settingsRoute = route('superadmin.settings');
    $role = 'superadmin';
    $roleId = 1;
    $roleColor = 'purple';
@endphp
