@extends('layouts.unified-layout')

@section('title', 'Profil Guru - Terra Assessment')

@section('styles')
<style>
    /* Premium Profile Styles */
    .profile-banner {
        height: 200px;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        border-radius: 20px;
        position: relative;
        margin-bottom: 80px;
        box-shadow: 0 10px 30px rgba(59, 130, 246, 0.2);
    }

    .profile-header-content {
        position: absolute;
        bottom: -60px;
        left: 40px;
        display: flex;
        align-items: flex-end;
        gap: 24px;
        width: calc(100% - 80px);
    }

    .profile-avatar-wrapper {
        position: relative;
        width: 150px;
        height: 150px;
    }

    .profile-avatar-large {
        width: 100%;
        height: 100%;
        border-radius: 30px;
        border: 6px solid #1e293b;
        background: #334155;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: 700;
        color: white;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }

    .profile-avatar-img {
        width: 100%;
        height: 100%;
        object-cover: cover;
    }

    .btn-edit-avatar {
        position: absolute;
        bottom: 10px;
        right: 10px;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #3b82f6;
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(59, 130, 246, 0.4);
    }

    .btn-edit-avatar:hover {
        background: #2563eb;
        transform: scale(1.1);
    }

    .profile-info {
        padding-bottom: 20px;
        flex: 1;
    }

    .profile-name {
        font-size: 2rem;
        font-weight: 700;
        color: white;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .profile-role-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(4px);
        border-radius: 20px;
        color: white;
        font-size: 0.875rem;
        margin-top: 8px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Tab Container */
    .profile-tabs-container {
        display: flex;
        gap: 30px;
        margin-bottom: 24px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 0 10px;
    }

    .tab-item {
        padding: 12px 20px;
        color: #94a3b8;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .tab-item:hover {
        color: white;
    }

    .tab-item.active {
        color: #3b82f6;
    }

    .tab-item.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 3px;
        background: #3b82f6;
        border-radius: 3px 3px 0 0;
    }

    /* Card Panels */
    .profile-card {
        background: rgba(30, 41, 59, 0.5);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: white;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: #3b82f6;
    }

    /* Form Styles */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        color: #94a3b8;
        font-size: 0.875rem;
        margin-bottom: 8px;
    }

    .form-input {
        width: 100%;
        background: rgba(15, 23, 42, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 12px 16px;
        color: white;
        transition: all 0.3s ease;
    }

    .form-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        outline: none;
    }

    .form-textarea {
        width: 100%;
        background: rgba(15, 23, 42, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 12px 16px;
        color: white;
        resize: vertical;
    }

    /* Security items */
    .security-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px;
        background: rgba(15, 23, 42, 0.3);
        border-radius: 12px;
        margin-bottom: 12px;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-top: 20px;
    }

    .stat-mini-card {
        padding: 20px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 16px;
        text-align: center;
    }

    .stat-mini-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: white;
        display: block;
    }

    .stat-mini-label {
        color: #64748b;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-top: 4px;
    }

    @media (max-width: 768px) {
        .profile-header-content {
            flex-direction: column;
            align-items: center;
            text-align: center;
            left: 0;
            width: 100%;
        }
        .form-grid {
            grid-template-columns: 1fr;
        }
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Premium Banner -->
    <div class="profile-banner">
        <div class="profile-header-content">
            <div class="profile-avatar-wrapper">
                <div class="profile-avatar-large">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Avatar" class="profile-avatar-img">
                    @else
                        {{ substr($user->name ?? 'G', 0, 2) }}
                    @endif
                </div>
                <button class="btn-edit-avatar" onclick="document.getElementById('photoInput').click()">
                    <i class="fas fa-camera"></i>
                </button>
                <input type="file" id="photoInput" accept="image/*" style="display: none;" onchange="uploadPhoto(this)">
            </div>
            <div class="profile-info">
                <h1 class="profile-name">{{ $user->name ?? 'Guru' }}</h1>
                <div class="profile-role-badge">
                    <i class="fas fa-chalkboard-teacher"></i>
                    Teacher / Guru
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Tabs -->
    <div class="profile-tabs-container">
        <div class="tab-item active" onclick="switchTab('personal')">
            <i class="fas fa-user-circle mr-2"></i>Informasi Pribadi
        </div>
        <div class="tab-item" onclick="switchTab('security')">
            <i class="fas fa-shield-alt mr-2"></i>Keamanan
        </div>
        <div class="tab-item" onclick="switchTab('stats')">
            <i class="fas fa-chart-pie mr-2"></i>Statistik
        </div>
    </div>

    <!-- Personal Info Tab -->
    <div id="personal-tab" class="profile-tab-content">
        <div class="profile-card">
            <h3 class="section-title">
                <i class="fas fa-id-card"></i>
                Data Profil Lengkap
            </h3>
            <form action="{{ route('teacher.profile.update') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-input" value="{{ $user->name }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat Email</label>
                        <input type="email" name="email" class="form-input" value="{{ $user->email }}" required>
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="phone" class="form-input" value="{{ $user->phone ?? '' }}" placeholder="Contoh: 08123456789">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status Akun</label>
                        <input type="text" class="form-input opacity-50" value="Aktif" readonly disabled>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Bio Singkat</label>
                    <textarea name="bio" class="form-textarea" rows="4" placeholder="Ceritakan sedikit tentang Anda sebagai pengajar...">{{ $user->bio ?? '' }}</textarea>
                </div>
                <div class="flex justify-end gap-3 mt-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl transition-all flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Security Tab -->
    <div id="security-tab" class="profile-tab-content hidden">
        <div class="profile-card">
            <h3 class="section-title">
                <i class="fas fa-lock"></i>
                Keamanan & Akses
            </h3>
            <div class="security-item">
                <div>
                    <h4 class="text-white font-medium">Kata Sandi</h4>
                    <p class="text-gray-400 text-sm">Ganti kata sandi secara berkala untuk keamanan.</p>
                </div>
                <button class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg transition-all" onclick="togglePasswordModal()">
                    Ubah Password
                </button>
            </div>
            <div class="security-item opacity-50">
                <div>
                    <h4 class="text-white font-medium">Autentikasi 2 Faktor (Coming Soon)</h4>
                    <p class="text-gray-400 text-sm">Lindungi akun Anda dengan lapisan keamanan extra.</p>
                </div>
                <div class="w-12 h-6 bg-slate-800 rounded-full relative">
                    <div class="absolute left-1 top-1 w-4 h-4 bg-slate-600 rounded-full"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Tab -->
    <div id="stats-tab" class="profile-tab-content hidden">
        <div class="profile-card">
            <h3 class="section-title">
                <i class="fas fa-chart-bar"></i>
                Aktivitas Pengajaran
            </h3>
            <div class="stats-grid">
                <div class="stat-mini-card">
                    <span class="stat-mini-value">{{ $stats['total_tasks'] ?? 0 }}</span>
                    <span class="stat-mini-label">Total Tugas</span>
                </div>
                <div class="stat-mini-card">
                    <span class="stat-mini-value">{{ $stats['total_exams'] ?? 0 }}</span>
                    <span class="stat-mini-label">Total Ujian</span>
                </div>
                <div class="stat-mini-card">
                    <span class="stat-mini-value">{{ $stats['total_materials'] ?? 0 }}</span>
                    <span class="stat-mini-label">Materi</span>
                </div>
                <div class="stat-mini-card">
                    <span class="stat-mini-value">{{ $stats['total_students'] ?? 0 }}</span>
                    <span class="stat-mini-label">Total Siswa</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Password Change Modal (Stub) -->
<div id="passwordModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-[9999] flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md p-6 shadow-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-white text-xl font-bold">Ubah Password</h3>
            <button onclick="togglePasswordModal()" class="text-gray-400 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('teacher.settings.update-password') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div class="form-group">
                    <label class="form-label">Password Saat Ini</label>
                    <input type="password" name="current_password" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="new_password" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="new_password_confirmation" class="form-input" required>
                </div>
            </div>
            <div class="flex flex-col gap-3 mt-8">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold transition-all">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function switchTab(tab) {
        // Hide all
        document.querySelectorAll('.profile-tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-item').forEach(el => el.classList.remove('active'));
        
        // Show selected
        document.getElementById(tab + '-tab').classList.remove('hidden');
        event.currentTarget.classList.add('active');
    }

    function togglePasswordModal() {
        const modal = document.getElementById('passwordModal');
        modal.classList.toggle('hidden');
    }

    function uploadPhoto(input) {
        if (input.files && input.files[0]) {
            const formData = new FormData();
            formData.append('photo', input.files[0]);
            formData.append('_token', '{{ csrf_token() }}');

            // Show loading state
            const avatar = document.querySelector('.profile-avatar-large');
            const originalHTML = avatar.innerHTML;
            avatar.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch('{{ route("teacher.profile.upload-photo") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    avatar.innerHTML = originalHTML;
                    alert('Gagal mengupload foto: ' + data.message);
                }
            })
            .catch(error => {
                avatar.innerHTML = originalHTML;
                console.error('Error:', error);
            });
        }
    }

    // Success notification from session
    @if(session('success'))
        showNotification('{{ session("success") }}', 'success');
    @endif
</script>
@endsection
