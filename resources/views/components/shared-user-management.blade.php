@php
    $userRole = $userRole ?? 'superadmin';
@endphp

<div class="page-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-users"></i>
            Manajemen Pengguna
        </h1>
        <p class="page-description">Kelola data pengguna sistem</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
                <div class="stat-label">Total Pengguna</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalTeachers ?? 0 }}</div>
                <div class="stat-label">Guru</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalStudents ?? 0 }}</div>
                <div class="stat-label">Siswa</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalAdmins ?? 0 }}</div>
                <div class="stat-label">Admin</div>
            </div>
        </div>
    </div>

    <!-- User Filters -->
    <div class="user-filters">
        <form action="{{ 
            $userRole === 'superadmin' ? route('superadmin.user-management.filter') : 
            ($userRole === 'admin' ? route('superadmin.user-management.filter') : route('teacher.user-management.filter'))
        }}" method="GET" class="filter-form">
            <div class="filter-row">
                <div class="form-group">
                    <label for="filter_role">Role</label>
                    <select id="filter_role" name="filter_role">
                        <option value="">Semua Role</option>
                        @if($userRole === 'superadmin')
                            <option value="1" {{ request('filter_role') == '1' ? 'selected' : '' }}>Super Admin</option>
                            <option value="2" {{ request('filter_role') == '2' ? 'selected' : '' }}>Admin</option>
                        @endif
                        <option value="3" {{ request('filter_role') == '3' ? 'selected' : '' }}>Guru</option>
                        <option value="4" {{ request('filter_role') == '4' ? 'selected' : '' }}>Siswa</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="filter_status">Status</label>
                    <select id="filter_status" name="filter_status">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('filter_status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('filter_status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="filter_search">Cari</label>
                    <input type="text" id="filter_search" name="filter_search" placeholder="Nama atau email..." value="{{ request('filter_search') }}">
                </div>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-search"></i>
                    Filter
                </button>
                <a href="{{ 
                    $userRole === 'superadmin' ? route('superadmin.user-management') : 
                    ($userRole === 'admin' ? route('superadmin.user-management') : route('teacher.user-management'))
                }}" class="btn-clear">
                    <i class="fas fa-times"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="table-container">
        <div class="table-header">
            <h3>Daftar Pengguna</h3>
            <div class="table-actions">
                <button class="btn-export" onclick="exportUsers()">
                    <i class="fas fa-download"></i>
                    Export
                </button>
            </div>
        </div>
        
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Avatar</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Terakhir Login</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users ?? [] as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="user-avatar">
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}">
                                    @else
                                        <div class="avatar-placeholder">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="user-info">
                                    <div class="user-name">{{ $user->name }}</div>
                                    <div class="user-id">ID: {{ $user->id }}</div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="role-badge role-{{ $user->roles_id }}">
                                    @switch($user->roles_id)
                                        @case(1) Super Admin @break
                                        @case(2) Admin @break
                                        @case(3) Guru @break
                                        @case(4) Siswa @break
                                        @default Unknown
                                    @endswitch
                                </span>
                            </td>
                            <td>
                                <span class="status-badge {{ $user->is_active ? 'active' : 'inactive' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td>{{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : 'Belum pernah' }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-view" onclick="viewUser('{{ $user->id }}')" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    @if(\App\Services\GranularRbacService::canEdit('user-management'))
                                        <button class="btn-edit" onclick="editUser('{{ $user->id }}')" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endif
                                    
                                    @if(\App\Services\GranularRbacService::canDelete('user-management'))
                                        @if($userRole === 'superadmin' || ($userRole === 'admin' && $user->roles_id != 1))
                                            <button class="btn-delete" onclick="deleteUser('{{ $user->id }}')" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-users"></i>
                                    <p>Tidak ada data pengguna</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create User Modal -->
<div id="createUserModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="modal-title">
                <h3>Tambah Pengguna Baru</h3>
                <p>Buat akun pengguna baru untuk sistem</p>
            </div>
            <button class="modal-close" onclick="closeCreateUserModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form action="{{ 
            $userRole === 'superadmin' ? route('superadmin.user-management.create') : 
            ($userRole === 'admin' ? route('superadmin.user-management.create') : route('teacher.user-management.create'))
        }}" method="POST" class="modal-form" id="createUserForm">
            @csrf
            
            <!-- Progress Indicator -->
            <div class="form-progress">
                <div class="progress-step active" data-step="1">
                    <div class="step-number">1</div>
                    <div class="step-label">Informasi Dasar</div>
                </div>
                <div class="progress-step" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-label">Role & Akses</div>
                </div>
                <div class="progress-step" data-step="3">
                    <div class="step-number">3</div>
                    <div class="step-label">Konfirmasi</div>
                </div>
            </div>

            <!-- Step 1: Basic Information -->
            <div class="form-step active" data-step="1">
                <div class="step-content">
                    <h4><i class="fas fa-user"></i> Informasi Dasar</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">
                                <i class="fas fa-user"></i>
                                Nama Lengkap
                            </label>
                            <input type="text" id="name" name="name" required placeholder="Masukkan nama lengkap">
                            <div class="field-hint">Nama lengkap akan ditampilkan di sistem</div>
                        </div>
                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i>
                                Email
                            </label>
                            <input type="email" id="email" name="email" required placeholder="contoh@email.com">
                            <div class="field-hint">Email akan digunakan untuk login</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">
                                <i class="fas fa-lock"></i>
                                Password
                            </label>
                            <div class="password-input">
                                <input type="password" id="password" name="password" required placeholder="Minimal 8 karakter">
                                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="field-hint">Password minimal 8 karakter</div>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">
                                <i class="fas fa-lock"></i>
                                Konfirmasi Password
                            </label>
                            <div class="password-input">
                                <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password">
                                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Role & Additional Info -->
            <div class="form-step" data-step="2">
                <div class="step-content">
                    <h4><i class="fas fa-user-tag"></i> Role & Informasi Tambahan</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="roles_id">
                                <i class="fas fa-user-shield"></i>
                                Role Pengguna
                            </label>
                            <select id="roles_id" name="roles_id" required onchange="toggleRoleFields()">
                                <option value="">Pilih Role</option>
                                @if($userRole === 'superadmin')
                                    <option value="1">Super Admin</option>
                                    <option value="2">Admin</option>
                                @endif
                                <option value="3">Guru</option>
                                <option value="4">Siswa</option>
                            </select>
                            <div class="field-hint">Pilih role sesuai dengan posisi pengguna</div>
                        </div>
                        <div class="form-group">
                            <label for="phone">
                                <i class="fas fa-phone"></i>
                                Nomor Telepon
                            </label>
                            <input type="tel" id="phone" name="phone" placeholder="08xxxxxxxxxx">
                            <div class="field-hint">Nomor telepon (opsional)</div>
                        </div>
                    </div>
                    
                    <!-- Additional Fields for Guru -->
                    <div class="role-specific-fields" id="guru-fields" style="display: none;">
                        <div class="role-section">
                            <div class="role-header">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <h5>Informasi Guru</h5>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="nip">
                                        <i class="fas fa-id-card"></i>
                                        NIP (Nomor Induk Pegawai)
                                    </label>
                                    <input type="text" id="nip" name="nip" placeholder="Masukkan NIP">
                                    <div class="field-hint">Nomor Induk Pegawai yang valid</div>
                                </div>
                                <div class="form-group">
                                    <label for="subject">
                                        <i class="fas fa-book"></i>
                                        Mata Pelajaran
                                    </label>
                                    <input type="text" id="subject" name="subject" placeholder="Contoh: Matematika, Bahasa Indonesia">
                                    <div class="field-hint">Mata pelajaran yang diajar</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Fields for Siswa -->
                    <div class="role-specific-fields" id="siswa-fields" style="display: none;">
                        <div class="role-section">
                            <div class="role-header">
                                <i class="fas fa-user-graduate"></i>
                                <h5>Informasi Siswa</h5>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="nisn">
                                        <i class="fas fa-id-card"></i>
                                        NISN (Nomor Induk Siswa Nasional)
                                    </label>
                                    <input type="text" id="nisn" name="nisn" placeholder="Masukkan NISN">
                                    <div class="field-hint">Nomor Induk Siswa Nasional yang valid</div>
                                </div>
                                <div class="form-group">
                                    <label for="class">
                                        <i class="fas fa-graduation-cap"></i>
                                        Kelas
                                    </label>
                                    <input type="text" id="class" name="class" placeholder="Contoh: X IPA 1, XII IPS 2">
                                    <div class="field-hint">Kelas saat ini</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Confirmation -->
            <div class="form-step" data-step="3">
                <div class="step-content">
                    <h4><i class="fas fa-check-circle"></i> Konfirmasi Data</h4>
                    <div class="confirmation-summary">
                        <div class="summary-item">
                            <i class="fas fa-user"></i>
                            <div>
                                <strong>Nama:</strong>
                                <span id="summary-name">-</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <strong>Email:</strong>
                                <span id="summary-email">-</span>
                            </div>
                        </div>
                        <div class="summary-item">
                            <i class="fas fa-user-shield"></i>
                            <div>
                                <strong>Role:</strong>
                                <span id="summary-role">-</span>
                            </div>
                        </div>
                        <div class="summary-item" id="summary-nip" style="display: none;">
                            <i class="fas fa-id-card"></i>
                            <div>
                                <strong>NIP:</strong>
                                <span id="summary-nip-value">-</span>
                            </div>
                        </div>
                        <div class="summary-item" id="summary-nisn" style="display: none;">
                            <i class="fas fa-id-card"></i>
                            <div>
                                <strong>NISN:</strong>
                                <span id="summary-nisn-value">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="closeCreateUserModal()">
                    <i class="fas fa-times"></i>
                    Batal
                </button>
                <button type="button" class="btn-outline" id="prevStep" onclick="previousStep()" style="display: none;">
                    <i class="fas fa-arrow-left"></i>
                    Sebelumnya
                </button>
                <button type="button" class="btn-primary" id="nextStep" onclick="nextStep()">
                    Selanjutnya
                    <i class="fas fa-arrow-right"></i>
                </button>
                <button type="submit" class="btn-success" id="submitBtn" style="display: none;">
                    <i class="fas fa-save"></i>
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* User Management Styles */
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

.user-filters {
    background: #1e293b;
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid #334155;
}

.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #ffffff;
    font-size: 0.9rem;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 0.75rem 1rem;
    background: #2a2a3e;
    border: 2px solid #333;
    border-radius: 8px;
    color: #ffffff;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #667eea;
    background: #333;
}

.filter-actions {
    display: flex;
    gap: 1rem;
}

.btn-filter, .btn-clear {
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

.btn-filter {
    background: #667eea;
    color: white;
}

.btn-clear {
    background: #6b7280;
    color: white;
}

.table-container {
    background: #1e293b;
    border-radius: 1rem;
    padding: 1.5rem;
    border: 1px solid #334155;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
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

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
}

.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: #667eea;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
}

.user-info {
    display: flex;
    flex-direction: column;
}

.user-name {
    font-weight: 600;
    color: #ffffff;
}

.user-id {
    font-size: 0.8rem;
    color: #94a3b8;
}

.role-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.role-1 { background: #fbbf24; color: #000; }
.role-2 { background: #3b82f6; color: white; }
.role-3 { background: #10b981; color: white; }
.role-4 { background: #f59e0b; color: white; }

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

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-view, .btn-edit, .btn-delete {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-view {
    background: #3b82f6;
    color: white;
}

.btn-edit {
    background: #f59e0b;
    color: white;
}

.btn-delete {
    background: #ef4444;
    color: white;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #94a3b8;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    backdrop-filter: blur(10px);
    z-index: 1000;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border-radius: 1.5rem;
    padding: 0;
    width: 95%;
    max-width: 600px;
    border: 1px solid #334155;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    animation: slideUp 0.4s ease;
    overflow: hidden;
}

@keyframes slideUp {
    from { 
        opacity: 0;
        transform: translate(-50%, -40%);
    }
    to { 
        opacity: 1;
        transform: translate(-50%, -50%);
    }
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    position: relative;
    overflow: hidden;
}

.modal-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.modal-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.3);
    position: relative;
    z-index: 1;
}

.modal-title {
    flex: 1;
    position: relative;
    z-index: 1;
}

.modal-title h3 {
    color: #ffffff;
    font-size: 1.5rem;
    margin: 0 0 0.5rem 0;
    font-weight: 700;
}

.modal-title p {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
    margin: 0;
}

.modal-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    position: relative;
    z-index: 1;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.1);
}

.modal-form {
    padding: 2rem;
}

/* Progress Indicator */
.form-progress {
    display: flex;
    justify-content: center;
    margin-bottom: 2rem;
    position: relative;
}

.form-progress::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 10%;
    right: 10%;
    height: 2px;
    background: #334155;
    z-index: 1;
}

.progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    flex: 1;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #334155;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
}

.step-label {
    font-size: 0.8rem;
    color: #94a3b8;
    text-align: center;
    font-weight: 500;
}

.progress-step.active .step-number {
    background: #667eea;
    color: white;
    transform: scale(1.1);
}

.progress-step.active .step-label {
    color: #667eea;
    font-weight: 600;
}

/* Form Steps */
.form-step {
    display: none;
    animation: slideIn 0.3s ease;
}

.form-step.active {
    display: block;
}

@keyframes slideIn {
    from { 
        opacity: 0;
        transform: translateX(20px);
    }
    to { 
        opacity: 1;
        transform: translateX(0);
    }
}

.step-content h4 {
    color: #ffffff;
    font-size: 1.1rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.step-content h4 i {
    color: #667eea;
}

/* Enhanced Form Groups */
.form-group {
    margin-bottom: 1.5rem;
    position: relative;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #ffffff;
    font-size: 0.9rem;
}

.form-group label i {
    color: #667eea;
    width: 16px;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 0.875rem 1rem;
    background: #2a2a3e;
    border: 2px solid #334155;
    border-radius: 10px;
    color: #ffffff;
    font-size: 1rem;
    transition: all 0.3s ease;
    position: relative;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #667eea;
    background: #333;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    transform: translateY(-1px);
}

.field-hint {
    font-size: 0.8rem;
    color: #94a3b8;
    margin-top: 0.25rem;
    font-style: italic;
}

/* Password Input */
.password-input {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 0.5rem;
    transition: color 0.3s ease;
}

.password-toggle:hover {
    color: #667eea;
}

/* Role Specific Fields */
.role-specific-fields {
    margin-top: 1.5rem;
    padding: 1.5rem;
    background: #0f172a;
    border-radius: 10px;
    border: 1px solid #334155;
    animation: fadeInUp 0.3s ease;
}

@keyframes fadeInUp {
    from { 
        opacity: 0;
        transform: translateY(10px);
    }
    to { 
        opacity: 1;
        transform: translateY(0);
    }
}

.role-section {
    position: relative;
}

.role-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #334155;
}

.role-header i {
    font-size: 1.2rem;
    color: #667eea;
}

.role-header h5 {
    color: #ffffff;
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
}

/* Confirmation Summary */
.confirmation-summary {
    background: #0f172a;
    border-radius: 10px;
    padding: 1.5rem;
    border: 1px solid #334155;
}

.summary-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid #334155;
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-item i {
    width: 40px;
    height: 40px;
    background: #667eea;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.9rem;
}

.summary-item div {
    flex: 1;
}

.summary-item strong {
    color: #ffffff;
    display: block;
    margin-bottom: 0.25rem;
}

.summary-item span {
    color: #94a3b8;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: space-between;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #334155;
}

.btn-primary, .btn-secondary, .btn-outline, .btn-success {
    padding: 0.875rem 1.5rem;
    border-radius: 10px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-1px);
}

.btn-outline {
    background: transparent;
    color: #667eea;
    border: 2px solid #667eea;
}

.btn-outline:hover {
    background: #667eea;
    color: white;
    transform: translateY(-1px);
}

.btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .filter-row {
        grid-template-columns: 1fr;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .table-wrapper {
        overflow-x: auto;
    }
    
    .modal-content {
        width: 98%;
        max-width: none;
        margin: 1rem;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    .modal-header {
        padding: 1.5rem;
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .modal-icon {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }
    
    .modal-title h3 {
        font-size: 1.3rem;
    }
    
    .modal-form {
        padding: 1.5rem;
    }
    
    .form-progress {
        margin-bottom: 1.5rem;
    }
    
    .progress-step {
        flex: 1;
    }
    
    .step-label {
        font-size: 0.7rem;
    }
    
    .form-actions {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .btn-primary, .btn-secondary, .btn-outline, .btn-success {
        width: 100%;
        justify-content: center;
    }
    
    .role-specific-fields {
        padding: 1rem;
    }
    
    .confirmation-summary {
        padding: 1rem;
    }
    
    .summary-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
        padding: 0.75rem 0;
    }
    
    .summary-item i {
        width: 30px;
        height: 30px;
        font-size: 0.8rem;
    }
}
</style>

<script>
let currentStep = 1;
const totalSteps = 3;

function openCreateUserModal() {
    document.getElementById('createUserModal').style.display = 'block';
    resetForm();
}

function closeCreateUserModal() {
    document.getElementById('createUserModal').style.display = 'none';
    resetForm();
}

function resetForm() {
    currentStep = 1;
    updateStepDisplay();
    document.getElementById('createUserForm').reset();
    hideAllRoleFields();
    updateSummary();
}

function updateStepDisplay() {
    // Update progress steps
    document.querySelectorAll('.progress-step').forEach((step, index) => {
        if (index + 1 <= currentStep) {
            step.classList.add('active');
        } else {
            step.classList.remove('active');
        }
    });

    // Update form steps
    document.querySelectorAll('.form-step').forEach((step, index) => {
        if (index + 1 === currentStep) {
            step.classList.add('active');
        } else {
            step.classList.remove('active');
        }
    });

    // Update buttons
    const prevBtn = document.getElementById('prevStep');
    const nextBtn = document.getElementById('nextStep');
    const submitBtn = document.getElementById('submitBtn');

    prevBtn.style.display = currentStep > 1 ? 'flex' : 'none';
    
    if (currentStep === totalSteps) {
        nextBtn.style.display = 'none';
        submitBtn.style.display = 'flex';
    } else {
        nextBtn.style.display = 'flex';
        submitBtn.style.display = 'none';
    }
}

function nextStep() {
    if (validateCurrentStep()) {
        if (currentStep < totalSteps) {
            currentStep++;
            updateStepDisplay();
            updateSummary();
        }
    }
}

function previousStep() {
    if (currentStep > 1) {
        currentStep--;
        updateStepDisplay();
    }
}

function validateCurrentStep() {
    const currentStepElement = document.querySelector(`.form-step[data-step="${currentStep}"]`);
    const requiredFields = currentStepElement.querySelectorAll('input[required], select[required]');
    
    let isValid = true;
    let firstInvalidField = null;

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            if (!firstInvalidField) {
                firstInvalidField = field;
            }
            field.style.borderColor = '#ef4444';
            field.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';
        } else {
            field.style.borderColor = '#334155';
            field.style.boxShadow = 'none';
        }
    });

    // Special validation for step 1
    if (currentStep === 1) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        
        if (password !== confirmPassword) {
            isValid = false;
            document.getElementById('password_confirmation').style.borderColor = '#ef4444';
            document.getElementById('password_confirmation').style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';
            alert('Password dan konfirmasi password tidak sama!');
        }
    }

    if (!isValid && firstInvalidField) {
        firstInvalidField.focus();
        firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    return isValid;
}

function toggleRoleFields() {
    const roleSelect = document.getElementById('roles_id');
    const selectedRole = roleSelect.value;
    
    hideAllRoleFields();
    
    if (selectedRole === '3') { // Guru
        document.getElementById('guru-fields').style.display = 'block';
        document.getElementById('nip').required = true;
        document.getElementById('subject').required = true;
    } else if (selectedRole === '4') { // Siswa
        document.getElementById('siswa-fields').style.display = 'block';
        document.getElementById('nisn').required = true;
        document.getElementById('class').required = true;
    }
    
    updateSummary();
}

function hideAllRoleFields() {
    document.getElementById('guru-fields').style.display = 'none';
    document.getElementById('siswa-fields').style.display = 'none';
    
    // Remove required attributes
    document.getElementById('nip').required = false;
    document.getElementById('subject').required = false;
    document.getElementById('nisn').required = false;
    document.getElementById('class').required = false;
}

function updateSummary() {
    const name = document.getElementById('name').value || '-';
    const email = document.getElementById('email').value || '-';
    const roleSelect = document.getElementById('roles_id');
    const selectedRole = roleSelect.value;
    const nip = document.getElementById('nip').value || '-';
    const nisn = document.getElementById('nisn').value || '-';
    
    document.getElementById('summary-name').textContent = name;
    document.getElementById('summary-email').textContent = email;
    
    // Update role display
    let roleText = '-';
    if (selectedRole) {
        const roleOptions = {
            '1': 'Super Admin',
            '2': 'Admin',
            '3': 'Guru',
            '4': 'Siswa'
        };
        roleText = roleOptions[selectedRole] || '-';
    }
    document.getElementById('summary-role').textContent = roleText;
    
    // Show/hide role-specific fields in summary
    if (selectedRole === '3') { // Guru
        document.getElementById('summary-nip').style.display = 'flex';
        document.getElementById('summary-nisn').style.display = 'none';
        document.getElementById('summary-nip-value').textContent = nip;
    } else if (selectedRole === '4') { // Siswa
        document.getElementById('summary-nip').style.display = 'none';
        document.getElementById('summary-nisn').style.display = 'flex';
        document.getElementById('summary-nisn-value').textContent = nisn;
    } else {
        document.getElementById('summary-nip').style.display = 'none';
        document.getElementById('summary-nisn').style.display = 'none';
    }
}

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const button = field.parentNode.querySelector('.password-toggle');
    const icon = button.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function viewUser(userId) {
    // Implementation for viewing user details
    console.log('View user:', userId);
}

function editUser(userId) {
    // Implementation for editing user
    console.log('Edit user:', userId);
}

function deleteUser(userId) {
    if (confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) {
        // Implementation for deleting user
        console.log('Delete user:', userId);
    }
}

function exportUsers() {
    // Implementation for exporting users
    console.log('Export users');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('createUserModal');
    if (event.target === modal) {
        closeCreateUserModal();
    }
}

// Add event listeners for real-time validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createUserForm');
    if (form) {
        // Add input event listeners for real-time updates
        form.addEventListener('input', function() {
            updateSummary();
        });
        
        // Add change event listener for role selection
        const roleSelect = document.getElementById('roles_id');
        if (roleSelect) {
            roleSelect.addEventListener('change', toggleRoleFields);
        }
    }
});
</script>
