@props([
    'user' => null,
    'notifications' => [],
    'totalNotifications' => 0,
    'readNotifications' => 0,
    'unreadNotifications' => 0,
    'urgentNotifications' => 0,
    'userRole' => 'superadmin'
])

<div class="notification-management-container">
    <!-- Modern Header -->
    <div class="notification-header">
        <div class="header-content">
            <div class="header-left">
                <button onclick="history.back()" class="back-btn">
                    <i class="ph-arrow-left"></i>
                </button>
                <div class="header-text">
                    <h1 class="header-title">Notifikasi</h1>
                    <p class="header-subtitle">Kelola notifikasi dan pesan</p>
                </div>
            </div>
            <div class="header-actions">
                <button onclick="refreshNotifications()" class="action-btn">
                    <i class="ph-arrow-clockwise"></i>
                </button>
                @if($userRole === 'superadmin' || $userRole === 'admin' || $userRole === 'teacher')
                    <button onclick="openCreateModal()" class="action-btn primary">
                        <i class="ph-plus"></i>
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-bell"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $totalNotifications }}</div>
                <div class="stat-label">Total</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="ph-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $readNotifications }}</div>
                <div class="stat-label">Dibaca</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="ph-envelope"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $unreadNotifications }}</div>
                <div class="stat-label">Belum Dibaca</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon danger">
                <i class="ph-warning"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $urgentNotifications }}</div>
                <div class="stat-label">Penting</div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-row">
            <div class="search-container">
                <i class="ph-magnifying-glass search-icon"></i>
                <input type="text" id="searchInput" placeholder="Cari notifikasi..." class="search-input">
            </div>
            <button onclick="toggleFilterMenu()" class="filter-toggle-btn">
                <i class="ph-funnel"></i>
            </button>
        </div>
        
        <div id="filterMenu" class="filter-menu hidden">
            <div class="filter-group">
                <label class="filter-label">Tipe Notifikasi</label>
                <select id="typeFilter" class="filter-select">
                    <option value="">Semua Tipe</option>
                    <option value="info">Informasi</option>
                    <option value="warning">Peringatan</option>
                    <option value="success">Sukses</option>
                    <option value="error">Error</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Status</label>
                <div class="filter-buttons">
                    <button class="filter-btn active" onclick="filterByStatus('all')">Semua</button>
                    <button class="filter-btn" onclick="filterByStatus('unread')">Belum Dibaca</button>
                    <button class="filter-btn" onclick="filterByStatus('read')">Dibaca</button>
                </div>
            </div>
            <div class="filter-actions">
                <button onclick="applyFilters()" class="apply-btn">Terapkan Filter</button>
                <button onclick="clearFilters()" class="clear-btn">Hapus Filter</button>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="notifications-list">
        @forelse($notifications ?? [] as $notification)
            <div class="notification-item {{ $notification->is_read ? 'read' : 'unread' }}" 
                 data-id="{{ $notification->id }}"
                 data-type="{{ $notification->type }}">
                <div class="notification-content">
                    <div class="notification-header-item">
                        <div class="notification-type-badge {{ $notification->type }}">
                            @php
                                $typeIcons = [
                                    'info' => 'fa-info-circle',
                                    'warning' => 'fa-exclamation-triangle',
                                    'success' => 'fa-check-circle',
                                    'error' => 'fa-times-circle'
                                ];
                            @endphp
                            <i class="fas {{ $typeIcons[$notification->type] ?? 'fa-bell' }}"></i>
                        </div>
                        <div class="notification-time">
                            {{ $notification->created_at->diffForHumans() }}
                        </div>
                    </div>
                    
                    <div class="notification-body">
                        <h3 class="notification-title">{{ $notification->title }}</h3>
                        <p class="notification-message">{{ Str::limit($notification->body, 100) }}</p>
                        
                        @if($notification->user)
                            <div class="notification-sender">
                                <i class="ph-user"></i>
                                <span>{{ $notification->user->name }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="notification-actions">
                        @if(!$notification->is_read)
                            <button onclick="markAsRead('{{ $notification->id }}')" class="action-btn small">
                                <i class="ph-check"></i>
                                <span>Tandai Dibaca</span>
                            </button>
                        @endif
                        <button onclick="viewNotification('{{ $notification->id }}')" class="action-btn small">
                            <i class="ph-eye"></i>
                            <span>Lihat</span>
                        </button>
                        @if($userRole === 'superadmin' || $userRole === 'admin' || $userRole === 'teacher')
                            <button onclick="deleteNotification('{{ $notification->id }}')" class="action-btn small danger">
                                <i class="ph-trash"></i>
                                <span>Hapus</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-bell-slash"></i>
                </div>
                <h3 class="empty-title">Belum Ada Notifikasi</h3>
                <p class="empty-message">Belum ada notifikasi yang diterima.</p>
                @if($userRole === 'superadmin' || $userRole === 'admin' || $userRole === 'teacher')
                    <button onclick="openCreateModal()" class="empty-action-btn">
                        <i class="ph-plus"></i>
                        Buat Notifikasi Pertama
                    </button>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($notifications) && is_object($notifications) && method_exists($notifications, 'hasPages') && $notifications->hasPages())
        <div class="pagination">
            {{ $notifications->links() }}
        </div>
    @endif
</div>

<!-- Create Notification Modal -->
@if($userRole === 'superadmin' || $userRole === 'admin' || $userRole === 'teacher')
<div id="createNotificationModal" class="modal hidden">
    <div class="modal-backdrop" onclick="closeCreateModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="ph-paper-plane-tilt"></i>
                Buat Notifikasi
            </h3>
            <button onclick="closeCreateModal()" class="modal-close-btn">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="createNotificationForm" method="POST" action="{{
            $userRole === 'superadmin' ? route('superadmin.push-notification.send') :
            ($userRole === 'admin' ? route('admin.push-notification.send') : route('teacher.push-notification.send'))
        }}" class="modal-body">
            @csrf
            <div class="form-group">
                <label class="form-label">Judul Notifikasi</label>
                <input type="text" name="title" class="form-input" placeholder="Masukkan judul notifikasi" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Tipe Notifikasi</label>
                <select name="type" class="form-input" required>
                    <option value="info">Informasi</option>
                    <option value="warning">Peringatan</option>
                    <option value="success">Sukses</option>
                    <option value="error">Error</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Pesan</label>
                <textarea name="body" class="form-input form-textarea" placeholder="Tulis pesan notifikasi..." required></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Penerima</label>
                <select name="recipient_type" class="form-input" onchange="updateRecipients()" required>
                    @if($userRole === 'teacher')
                        <option value="my_students">Siswa di Kelas Saya</option>
                        <option value="specific_students">Pilih Siswa Spesifik</option>
                    @else
                        <option value="all">Semua Pengguna</option>
                        <option value="students">Siswa Saja</option>
                        <option value="teachers">Guru Saja</option>
                        <option value="admins">Admin Saja</option>
                        <option value="specific">Pilih Spesifik</option>
                    @endif
                </select>
            </div>
            
            <div id="specificRecipients" class="form-group hidden">
                <label class="form-label">Pilih Penerima Spesifik</label>
                <div class="recipients-list">
                    @if($userRole === 'teacher')
                        @foreach($students ?? [] as $student)
                            <label class="recipient-item">
                                <input type="checkbox" name="specific_students[]" value="{{ $student->id }}" class="recipient-checkbox">
                                <div class="recipient-info">
                                    <div class="recipient-name">{{ $student->name }}</div>
                                    <div class="recipient-email">{{ $student->kelas->nama_kelas ?? 'Kelas tidak tersedia' }}</div>
                                </div>
                            </label>
                        @endforeach
                    @else
                        @foreach($users ?? [] as $user)
                            <label class="recipient-item">
                                <input type="checkbox" name="specific_users[]" value="{{ $user->id }}" class="recipient-checkbox">
                                <div class="recipient-info">
                                    <div class="recipient-name">{{ $user->name }}</div>
                                    <div class="recipient-email">{{ $user->email }}</div>
                                </div>
                            </label>
                        @endforeach
                    @endif
                </div>
            </div>
        </form>
        
        <div class="modal-footer">
            <button onclick="closeCreateModal()" class="modal-btn secondary">
                <i class="fas fa-times"></i>
                Batal
            </button>
            <button onclick="submitCreateForm()" class="modal-btn primary">
                <i class="ph-paper-plane-tilt"></i>
                Kirim
            </button>
        </div>
    </div>
</div>
@endif

<!-- Notification Detail Modal -->
<div id="notificationDetailModal" class="modal hidden">
    <div class="modal-backdrop" onclick="closeDetailModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-bell"></i>
                Detail Notifikasi
            </h3>
            <button onclick="closeDetailModal()" class="modal-close-btn">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <div id="notificationDetailContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
        
        <div class="modal-footer">
            <button onclick="closeDetailModal()" class="modal-btn secondary">
                <i class="fas fa-times"></i>
                Tutup
            </button>
        </div>
    </div>
</div>

<style>
/* Modern Notification Management Styles */
.notification-management-container {
    padding: var(--space-6);
    background: var(--secondary-50);
    min-height: 100vh;
    max-width: 1200px;
    margin: 0 auto;
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .notification-management-container {
        background: var(--secondary-900);
    }
}

/* Header */
.notification-header {
    background: var(--white);
    border: 1px solid var(--secondary-200);
    border-radius: var(--radius-xl);
    padding: var(--space-6);
    margin-bottom: var(--space-6);
    box-shadow: var(--shadow-sm);
    position: sticky;
    top: var(--space-4);
    z-index: 10;
}

@media (prefers-color-scheme: dark) {
    .notification-header {
        background: var(--secondary-800);
        border-color: var(--secondary-700);
    }
}

.header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: var(--space-4);
}

.header-left {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    flex: 1;
}

.back-btn {
    width: 44px;
    height: 44px;
    background: var(--primary-50);
    border: 1px solid var(--primary-200);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-600);
    font-size: 18px;
    transition: all 0.2s ease;
    cursor: pointer;
}

.back-btn:hover {
    background: var(--primary-100);
    border-color: var(--primary-300);
    transform: translateX(-2px);
}

.header-text {
    flex: 1;
}

.header-title {
    font-size: var(--text-h2);
    font-weight: var(--font-semibold);
    color: var(--secondary-900);
    margin: 0;
    line-height: 1.3;
}

.header-subtitle {
    font-size: var(--text-small);
    color: var(--secondary-500);
    margin: 0;
    margin-top: var(--space-1);
}

.header-actions {
    display: flex;
    gap: var(--space-2);
}

.action-btn {
    width: 44px;
    height: 44px;
    background: var(--secondary-100);
    border: 1px solid var(--secondary-200);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--secondary-600);
    font-size: 16px;
    transition: all 0.2s ease;
    cursor: pointer;
}

.action-btn.primary {
    background: var(--primary-500);
    border-color: var(--primary-500);
    color: var(--white);
}

.action-btn:hover {
    background: var(--secondary-200);
    transform: scale(1.05);
}

.action-btn.primary:hover {
    background: var(--primary-600);
    border-color: var(--primary-600);
}

@media (prefers-color-scheme: dark) {
    .header-title {
        color: var(--secondary-50);
    }
    
    .header-subtitle {
        color: var(--secondary-400);
    }
    
    .back-btn {
        background: var(--primary-900);
        border-color: var(--primary-700);
        color: var(--primary-400);
    }
    
    .back-btn:hover {
        background: var(--primary-800);
        border-color: var(--primary-600);
    }
    
    .action-btn {
        background: var(--secondary-700);
        border-color: var(--secondary-600);
        color: var(--secondary-300);
    }
    
    .action-btn:hover {
        background: var(--secondary-600);
    }
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--space-4);
    margin-bottom: var(--space-6);
}

.stat-card {
    background: var(--white);
    border: 1px solid var(--secondary-200);
    border-radius: var(--radius-xl);
    padding: var(--space-5);
    display: flex;
    align-items: center;
    gap: var(--space-4);
    transition: all 0.2s ease;
    box-shadow: var(--shadow-sm);
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    border-color: var(--primary-200);
}

.stat-icon {
    width: 48px;
    height: 48px;
    background: var(--primary-50);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-600);
    font-size: 20px;
    flex-shrink: 0;
}

.stat-icon.success {
    background: var(--success-50);
    color: var(--success-600);
}

.stat-icon.warning {
    background: var(--warning-50);
    color: var(--warning-600);
}

.stat-icon.danger {
    background: var(--error-50);
    color: var(--error-600);
}

.stat-content {
    flex: 1;
    min-width: 0;
}

.stat-number {
    font-size: var(--text-h3);
    font-weight: var(--font-bold);
    color: var(--secondary-900);
    line-height: 1.2;
    margin: 0;
}

.stat-label {
    font-size: var(--text-caption);
    color: var(--secondary-500);
    margin: 0;
    margin-top: var(--space-1);
    font-weight: var(--font-medium);
}

@media (prefers-color-scheme: dark) {
    .stat-card {
        background: var(--secondary-800);
        border-color: var(--secondary-700);
    }
    
    .stat-card:hover {
        border-color: var(--primary-600);
    }
    
    .stat-number {
        color: var(--secondary-50);
    }
    
    .stat-label {
        color: var(--secondary-400);
    }
}

@media (min-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: var(--space-6);
    }
}

/* Filter Section */
.filter-section {
    background: var(--white);
    border: 1px solid var(--secondary-200);
    border-radius: var(--radius-xl);
    padding: var(--space-5);
    margin-bottom: var(--space-6);
    box-shadow: var(--shadow-sm);
}

@media (prefers-color-scheme: dark) {
    .filter-section {
        background: var(--secondary-800);
        border-color: var(--secondary-700);
    }
}

.filter-row {
    display: flex;
    gap: var(--space-3);
    align-items: center;
}

.search-container {
    flex: 1;
    position: relative;
}

.search-icon {
    position: absolute;
    left: var(--space-3);
    top: 50%;
    transform: translateY(-50%);
    color: var(--secondary-500);
    font-size: 16px;
    z-index: 1;
}

.search-input {
    width: 100%;
    padding: var(--space-3) var(--space-3) var(--space-3) 44px;
    background: var(--secondary-50);
    border: 1px solid var(--secondary-200);
    border-radius: var(--radius-lg);
    color: var(--secondary-900);
    font-size: var(--text-small);
    transition: all 0.2s ease;
}

.search-input:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px var(--primary-100);
    background: var(--white);
}

.filter-toggle-btn {
    width: 44px;
    height: 44px;
    background: var(--secondary-100);
    border: 1px solid var(--secondary-200);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--secondary-600);
    font-size: 16px;
    transition: all 0.2s ease;
    cursor: pointer;
}

.filter-toggle-btn:hover {
    background: var(--secondary-200);
    border-color: var(--secondary-300);
    color: var(--secondary-700);
}

.filter-menu {
    background: var(--secondary-50);
    border: 1px solid var(--secondary-200);
    border-radius: var(--radius-xl);
    padding: var(--space-5);
    margin-top: var(--space-4);
    transition: all 0.2s ease;
    box-shadow: var(--shadow-sm);
}

.filter-menu.hidden {
    display: none;
}

.filter-group {
    margin-bottom: var(--space-4);
}

.filter-group:last-child {
    margin-bottom: 0;
}

.filter-label {
    display: block;
    font-size: var(--text-small);
    font-weight: var(--font-medium);
    color: var(--secondary-700);
    margin-bottom: var(--space-2);
}

.filter-select {
    width: 100%;
    padding: var(--space-3);
    background: var(--white);
    border: 1px solid var(--secondary-200);
    border-radius: var(--radius-lg);
    color: var(--secondary-900);
    font-size: var(--text-small);
    transition: all 0.2s ease;
}

.filter-select:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px var(--primary-100);
}

.filter-buttons {
    display: flex;
    gap: var(--space-2);
    flex-wrap: wrap;
}

.filter-btn {
    padding: var(--space-2) var(--space-4);
    background: var(--secondary-100);
    border: 1px solid var(--secondary-200);
    border-radius: var(--radius-lg);
    color: var(--secondary-600);
    font-size: var(--text-caption);
    font-weight: var(--font-medium);
    cursor: pointer;
    transition: all 0.2s ease;
}

.filter-btn.active {
    background: var(--primary-500);
    border-color: var(--primary-500);
    color: var(--white);
}

.filter-btn:hover:not(.active) {
    background: var(--secondary-200);
    border-color: var(--secondary-300);
    color: var(--secondary-700);
}

.filter-actions {
    display: flex;
    gap: var(--space-3);
    margin-top: var(--space-4);
}

.apply-btn, .clear-btn {
    flex: 1;
    padding: var(--space-3) var(--space-4);
    border-radius: var(--radius-lg);
    font-size: var(--text-small);
    font-weight: var(--font-medium);
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid transparent;
}

.apply-btn {
    background: var(--primary-500);
    border-color: var(--primary-500);
    color: var(--white);
}

.apply-btn:hover {
    background: var(--primary-600);
    border-color: var(--primary-600);
}

.clear-btn {
    background: var(--secondary-100);
    border-color: var(--secondary-200);
    color: var(--secondary-600);
}

.clear-btn:hover {
    background: var(--secondary-200);
    border-color: var(--secondary-300);
    color: var(--secondary-700);
}

@media (prefers-color-scheme: dark) {
    .search-input {
        background: var(--secondary-800);
        border-color: var(--secondary-700);
        color: var(--secondary-100);
    }
    
    .search-input:focus {
        background: var(--secondary-700);
        border-color: var(--primary-500);
    }
    
    .filter-toggle-btn {
        background: var(--secondary-700);
        border-color: var(--secondary-600);
        color: var(--secondary-300);
    }
    
    .filter-toggle-btn:hover {
        background: var(--secondary-600);
        color: var(--secondary-100);
    }
    
    .filter-menu {
        background: var(--secondary-800);
        border-color: var(--secondary-700);
    }
    
    .filter-label {
        color: var(--secondary-300);
    }
    
    .filter-select {
        background: var(--secondary-700);
        border-color: var(--secondary-600);
        color: var(--secondary-100);
    }
    
    .filter-select:focus {
        background: var(--secondary-600);
        border-color: var(--primary-500);
    }
    
    .filter-btn {
        background: var(--secondary-700);
        border-color: var(--secondary-600);
        color: var(--secondary-300);
    }
    
    .filter-btn:hover:not(.active) {
        background: var(--secondary-600);
        color: var(--secondary-100);
    }
    
    .clear-btn {
        background: var(--secondary-700);
        border-color: var(--secondary-600);
        color: var(--secondary-300);
    }
    
    .clear-btn:hover {
        background: var(--secondary-600);
        color: var(--secondary-100);
    }
}

/* Notifications List */
.notifications-list {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
    margin-bottom: var(--space-6);
}

.notification-item {
    background: var(--white);
    border: 1px solid var(--secondary-200);
    border-radius: var(--radius-xl);
    transition: all 0.2s ease;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.notification-item.unread {
    border-left: 4px solid var(--primary-500);
    box-shadow: var(--shadow-md);
}

.notification-item.read {
    opacity: 0.8;
}

.notification-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-200);
}

.notification-content {
    padding: var(--space-5);
}

.notification-header-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: var(--space-3);
}

.notification-type-badge {
    width: 36px;
    height: 36px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    color: var(--white);
    flex-shrink: 0;
}

.notification-type-badge.info {
    background: var(--primary-500);
}

.notification-type-badge.warning {
    background: var(--warning-500);
}

.notification-type-badge.success {
    background: var(--success-500);
}

.notification-type-badge.error {
    background: var(--error-500);
}

.notification-time {
    font-size: var(--text-caption);
    color: var(--secondary-500);
    font-weight: var(--font-medium);
}

.notification-body {
    margin-bottom: var(--space-4);
}

.notification-title {
    font-size: var(--text-body);
    font-weight: var(--font-semibold);
    color: var(--secondary-900);
    margin: 0 0 var(--space-2) 0;
    line-height: 1.4;
}

.notification-message {
    font-size: var(--text-small);
    color: var(--secondary-600);
    line-height: 1.5;
    margin: 0 0 var(--space-2) 0;
}

.notification-sender {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    font-size: var(--text-caption);
    color: var(--secondary-500);
    font-weight: var(--font-medium);
}

.notification-actions {
    display: flex;
    gap: var(--space-2);
    flex-wrap: wrap;
}

.action-btn.small {
    padding: var(--space-2) var(--space-3);
    font-size: var(--text-caption);
    height: auto;
    width: auto;
    min-width: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-1);
    font-weight: var(--font-medium);
}

.action-btn.small span {
    font-size: var(--text-caption);
}

.action-btn.danger {
    background: var(--error-50);
    border-color: var(--error-200);
    color: var(--error-600);
}

.action-btn.danger:hover {
    background: var(--error-100);
    border-color: var(--error-300);
    color: var(--error-700);
}

@media (prefers-color-scheme: dark) {
    .notification-item {
        background: var(--secondary-800);
        border-color: var(--secondary-700);
    }
    
    .notification-item:hover {
        border-color: var(--primary-600);
    }
    
    .notification-title {
        color: var(--secondary-50);
    }
    
    .notification-message {
        color: var(--secondary-300);
    }
    
    .notification-sender {
        color: var(--secondary-400);
    }
    
    .notification-time {
        color: var(--secondary-400);
    }
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: var(--space-16) var(--space-6);
    background: var(--white);
    border: 1px solid var(--secondary-200);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-sm);
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: var(--secondary-100);
    border-radius: var(--radius-2xl);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--space-6);
    color: var(--secondary-400);
    font-size: 32px;
}

.empty-title {
    font-size: var(--text-h3);
    font-weight: var(--font-semibold);
    color: var(--secondary-700);
    margin: 0 0 var(--space-2) 0;
}

.empty-message {
    font-size: var(--text-small);
    color: var(--secondary-500);
    margin: 0 0 var(--space-6) 0;
    line-height: 1.5;
}

.empty-action-btn {
    background: var(--primary-500);
    border: 1px solid var(--primary-500);
    color: var(--white);
    padding: var(--space-3) var(--space-6);
    border-radius: var(--radius-lg);
    font-size: var(--text-small);
    font-weight: var(--font-medium);
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
}

.empty-action-btn:hover {
    background: var(--primary-600);
    border-color: var(--primary-600);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

@media (prefers-color-scheme: dark) {
    .empty-state {
        background: var(--secondary-800);
        border-color: var(--secondary-700);
    }
    
    .empty-icon {
        background: var(--secondary-700);
        color: var(--secondary-500);
    }
    
    .empty-title {
        color: var(--secondary-200);
    }
    
    .empty-message {
        color: var(--secondary-400);
    }
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    margin-top: var(--space-6);
}

.pagination .pagination {
    display: flex;
    gap: var(--space-2);
    align-items: center;
}

.pagination .page-link {
    padding: var(--space-2) var(--space-3);
    background: var(--white);
    border: 1px solid var(--secondary-200);
    border-radius: var(--radius-lg);
    color: var(--secondary-600);
    text-decoration: none;
    font-size: var(--text-small);
    font-weight: var(--font-medium);
    transition: all 0.2s ease;
    min-width: 40px;
    text-align: center;
}

.pagination .page-link:hover {
    background: var(--secondary-100);
    border-color: var(--secondary-300);
    color: var(--secondary-700);
}

.pagination .page-item.active .page-link {
    background: var(--primary-500);
    border-color: var(--primary-500);
    color: var(--white);
}

@media (prefers-color-scheme: dark) {
    .pagination .page-link {
        background: var(--secondary-800);
        border-color: var(--secondary-700);
        color: var(--secondary-300);
    }
    
    .pagination .page-link:hover {
        background: var(--secondary-700);
        color: var(--secondary-100);
    }
}

/* Icon Font Fix - Ensure proper loading */
.ph, .fas, .fa {
    display: inline-block;
    font-style: normal;
    font-variant: normal;
    text-rendering: auto;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* Ensure Phosphor Icons load correctly */
.ph {
    font-family: "Phosphor", "PhosphorIcons", sans-serif;
}

/* Ensure Font Awesome load correctly */
.fas, .fa {
    font-family: "Font Awesome 6 Free", "Font Awesome 6 Pro", "FontAwesome", sans-serif;
}

/* Icon fallback styles */
.icon-fallback .fas.fa-bell::before {
    content: "🔔";
}

.icon-fallback .fas.fa-arrow-left::before {
    content: "←";
}

.icon-fallback .fas.fa-arrow-clockwise::before {
    content: "↻";
}

.icon-fallback .fas.fa-plus::before {
    content: "+";
}

.icon-fallback .fas.fa-check-circle::before {
    content: "✓";
}

.icon-fallback .fas.fa-envelope::before {
    content: "✉";
}

.icon-fallback .fas.fa-warning::before {
    content: "⚠";
}

.icon-fallback .fas.fa-info-circle::before {
    content: "ℹ";
}

.icon-fallback .fas.fa-exclamation-triangle::before {
    content: "⚠";
}

.icon-fallback .fas.fa-times-circle::before {
    content: "✕";
}

.icon-fallback .fas.fa-bell-slash::before {
    content: "🔕";
}

.icon-fallback .fas.fa-user::before {
    content: "👤";
}

.icon-fallback .fas.fa-check::before {
    content: "✓";
}

.icon-fallback .fas.fa-eye::before {
    content: "👁";
}

.icon-fallback .fas.fa-trash::before {
    content: "🗑";
}

.icon-fallback .fas.fa-times::before {
    content: "×";
}

.icon-fallback .ph-arrow-left::before {
    content: "←";
}

.icon-fallback .ph-arrow-clockwise::before {
    content: "↻";
}

.icon-fallback .ph-plus::before {
    content: "+";
}

.icon-fallback .ph-check-circle::before {
    content: "✓";
}

.icon-fallback .ph-envelope::before {
    content: "✉";
}

.icon-fallback .ph-warning::before {
    content: "⚠";
}

.icon-fallback .ph-user::before {
    content: "👤";
}

.icon-fallback .ph-check::before {
    content: "✓";
}

.icon-fallback .ph-eye::before {
    content: "👁";
}

.icon-fallback .ph-trash::before {
    content: "🗑";
}

.icon-fallback .ph-paper-plane-tilt::before {
    content: "✈";
}

.icon-fallback .ph-magnifying-glass::before {
    content: "🔍";
}

.icon-fallback .ph-funnel::before {
    content: "🔽";
}

/* Modern Modal */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: var(--space-4);
    animation: fadeIn 0.2s ease-out;
}

.modal.hidden {
    display: none;
}

.modal-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    z-index: 1;
    animation: fadeIn 0.2s ease-out;
}

.modal-content {
    background: var(--white);
    border: 1px solid var(--secondary-200);
    border-radius: var(--radius-2xl);
    width: 100%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    z-index: 10;
    animation: scaleIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: var(--shadow-2xl);
}

@media (prefers-color-scheme: dark) {
    .modal-content {
        background: var(--secondary-800);
        border-color: var(--secondary-700);
    }
}

@media (max-width: 768px) {
    .modal {
        align-items: flex-end;
        padding: 0;
    }
    
    .modal-content {
        border-radius: var(--radius-2xl) var(--radius-2xl) 0 0;
        max-width: 100%;
        animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideUp {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--space-6);
    border-bottom: 1px solid var(--secondary-200);
    position: sticky;
    top: 0;
    background: var(--white);
    z-index: 10;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.modal-title {
    font-size: var(--text-h3);
    font-weight: var(--font-semibold);
    color: var(--secondary-900);
    margin: 0;
    display: flex;
    align-items: center;
    gap: var(--space-3);
    letter-spacing: -0.01em;
}

.modal-close-btn {
    width: 40px;
    height: 40px;
    background: var(--secondary-100);
    border: 1px solid var(--secondary-200);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--secondary-500);
    font-size: 18px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.modal-close-btn:hover {
    background: var(--secondary-200);
    border-color: var(--secondary-300);
    color: var(--secondary-700);
    transform: scale(1.05);
}

.modal-body {
    padding: var(--space-6);
}

@media (prefers-color-scheme: dark) {
    .modal-header {
        background: var(--secondary-800);
        border-color: var(--secondary-700);
    }
    
    .modal-title {
        color: var(--secondary-50);
    }
    
    .modal-close-btn {
        background: var(--secondary-700);
        border-color: var(--secondary-600);
        color: var(--secondary-300);
    }
    
    .modal-close-btn:hover {
        background: var(--secondary-600);
        color: var(--secondary-100);
    }
}

.form-group {
    margin-bottom: var(--space-6);
}

.form-group:last-child {
    margin-bottom: 0;
}

.form-label {
    display: block;
    font-size: var(--text-small);
    font-weight: var(--font-semibold);
    color: var(--secondary-700);
    margin-bottom: var(--space-3);
    letter-spacing: -0.01em;
}

.form-input {
    width: 100%;
    padding: var(--space-4);
    background: var(--white);
    border: 1px solid var(--secondary-200);
    border-radius: var(--radius-lg);
    color: var(--secondary-900);
    font-size: var(--text-body);
    line-height: 1.5;
    transition: all 0.2s ease;
    box-shadow: var(--shadow-sm);
}

.form-input:hover {
    border-color: var(--secondary-300);
    background: var(--secondary-50);
}

.form-input:focus {
    outline: none;
    border-color: var(--primary-500);
    background: var(--white);
    box-shadow: 0 0 0 3px var(--primary-100);
    transform: translateY(-1px);
}

.form-input::placeholder {
    color: var(--secondary-400);
    font-weight: var(--font-normal);
}

.form-textarea {
    min-height: 120px;
    resize: vertical;
    line-height: 1.6;
    font-family: inherit;
}

@media (prefers-color-scheme: dark) {
    .form-label {
        color: var(--secondary-300);
    }
    
    .form-input {
        background: var(--secondary-700);
        border-color: var(--secondary-600);
        color: var(--secondary-100);
    }
    
    .form-input:hover {
        background: var(--secondary-600);
        border-color: var(--secondary-500);
    }
    
    .form-input:focus {
        background: var(--secondary-600);
        border-color: var(--primary-500);
    }
    
    .form-input::placeholder {
        color: var(--secondary-400);
    }
}

.recipients-list {
    max-height: 240px;
    overflow-y: auto;
    border: 1px solid var(--secondary-200);
    border-radius: var(--radius-lg);
    padding: var(--space-3);
    background: var(--secondary-50);
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
}

.recipient-item {
    display: flex;
    align-items: center;
    gap: var(--space-4);
    padding: var(--space-3) var(--space-4);
    border-radius: var(--radius-lg);
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid transparent;
    margin-bottom: var(--space-2);
}

.recipient-item:last-child {
    margin-bottom: 0;
}

.recipient-item:hover {
    background: var(--primary-50);
    border-color: var(--primary-200);
    transform: translateY(-1px);
}

.recipient-checkbox {
    width: 18px;
    height: 18px;
    accent-color: var(--primary-500);
    border-radius: var(--radius-base);
}

.recipient-info {
    flex: 1;
}

.recipient-name {
    font-size: var(--text-body);
    font-weight: var(--font-medium);
    color: var(--secondary-700);
}

.recipient-email {
    font-size: var(--text-small);
    color: var(--secondary-500);
    margin-top: var(--space-1);
}

.modal-footer {
    display: flex;
    gap: var(--space-4);
    padding: var(--space-6);
    border-top: 1px solid var(--secondary-200);
    position: sticky;
    bottom: 0;
    background: var(--white);
    box-shadow: 0 -1px 3px rgba(0, 0, 0, 0.1);
}

.modal-btn {
    flex: 1;
    padding: var(--space-4) var(--space-6);
    border-radius: var(--radius-lg);
    font-size: var(--text-body);
    font-weight: var(--font-semibold);
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-2);
    border: 1px solid transparent;
    position: relative;
    overflow: hidden;
}

.modal-btn.secondary {
    background: var(--secondary-100);
    border-color: var(--secondary-200);
    color: var(--secondary-600);
}

.modal-btn.secondary:hover {
    background: var(--secondary-200);
    border-color: var(--secondary-300);
    color: var(--secondary-700);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.modal-btn.primary {
    background: var(--primary-500);
    border-color: var(--primary-500);
    color: var(--white);
    box-shadow: var(--shadow-md);
}

.modal-btn.primary:hover {
    background: var(--primary-600);
    border-color: var(--primary-600);
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.modal-btn.primary:active {
    transform: translateY(0);
    box-shadow: var(--shadow-md);
}

@media (prefers-color-scheme: dark) {
    .recipients-list {
        background: var(--secondary-700);
        border-color: var(--secondary-600);
    }
    
    .recipient-item:hover {
        background: var(--primary-900);
        border-color: var(--primary-700);
    }
    
    .recipient-name {
        color: var(--secondary-200);
    }
    
    .recipient-email {
        color: var(--secondary-400);
    }
    
    .modal-footer {
        background: var(--secondary-800);
        border-color: var(--secondary-700);
    }
    
    .modal-btn.secondary {
        background: var(--secondary-700);
        border-color: var(--secondary-600);
        color: var(--secondary-300);
    }
    
    .modal-btn.secondary:hover {
        background: var(--secondary-600);
        color: var(--secondary-100);
    }
}

/* Responsive Design */
@media (min-width: 768px) {
    .notification-management-container {
        padding: var(--space-8);
    }
}

@media (min-width: 1024px) {
    .notification-management-container {
        padding: var(--space-10);
    }
    
    .stats-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: var(--space-6);
    }
    
    .notification-item {
        max-width: none;
    }
}
</style>

<script>
// Mobile Notification Management JavaScript
let currentFilters = {
    search: '',
    type: '',
    status: 'all'
};

// Filter functionality
function toggleFilterMenu() {
    const menu = document.getElementById('filterMenu');
    menu.classList.toggle('hidden');
}

function applyFilters() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const typeFilter = document.getElementById('typeFilter').value;
    
    currentFilters.search = searchTerm;
    currentFilters.type = typeFilter;
    
    filterNotifications();
    document.getElementById('filterMenu').classList.add('hidden');
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('typeFilter').value = '';
    
    // Reset filter buttons
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelector('.filter-btn').classList.add('active');
    
    currentFilters = {
        search: '',
        type: '',
        status: 'all'
    };
    
    filterNotifications();
    document.getElementById('filterMenu').classList.add('hidden');
}

function filterByStatus(status) {
    // Update active filter button
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    currentFilters.status = status;
    filterNotifications();
}

function filterNotifications() {
    const notifications = document.querySelectorAll('.notification-item');
    
    notifications.forEach(notification => {
        const text = notification.textContent.toLowerCase();
        const type = notification.dataset.type;
        const isRead = notification.classList.contains('read');
        
        let show = true;
        
        // Search filter
        if (currentFilters.search && !text.includes(currentFilters.search)) {
            show = false;
        }
        
        // Type filter
        if (currentFilters.type && type !== currentFilters.type) {
            show = false;
        }
        
        // Status filter
        if (currentFilters.status === 'read' && !isRead) {
            show = false;
        } else if (currentFilters.status === 'unread' && isRead) {
            show = false;
        }
        
        notification.style.display = show ? '' : 'none';
    });
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    currentFilters.search = this.value.toLowerCase();
    filterNotifications();
});

// Notification actions
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const notificationItem = document.querySelector(`[data-id="${notificationId}"]`);
            if (notificationItem) {
                notificationItem.classList.remove('unread');
                notificationItem.classList.add('read');
                
                const markButton = notificationItem.querySelector('.action-btn.small');
                if (markButton && markButton.textContent.includes('Tandai Dibaca')) {
                    markButton.remove();
                }
            }
            updateUnreadCount();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal menandai notifikasi sebagai dibaca');
    });
}

function viewNotification(notificationId) {
    // Fetch notification details via AJAX
    fetch(`/admin/notifications/${notificationId}`)
        .then(response => response.json())
        .then(data => {
            const content = document.getElementById('notificationDetailContent');
            content.innerHTML = `
                <div class="notification-detail-mobile">
                    <div class="detail-header">
                        <div class="detail-type-badge ${data.type}">
                            <i class="ph ${getTypeIcon(data.type)}"></i>
                            ${data.type.charAt(0).toUpperCase() + data.type.slice(1)}
                        </div>
                        <div class="detail-time">${new Date(data.created_at).toLocaleString('id-ID')}</div>
                    </div>
                    <div class="detail-content">
                        <h3 class="detail-title">${data.title}</h3>
                        <div class="detail-body">${data.body}</div>
                        ${data.user ? `<div class="detail-sender"><i class="ph-user"></i> ${data.user.name}</div>` : ''}
                        <div class="detail-status">
                            <span class="status-badge ${data.is_read ? 'read' : 'unread'}">
                                ${data.is_read ? 'Sudah Dibaca' : 'Belum Dibaca'}
                            </span>
                        </div>
                    </div>
                </div>
            `;
            openDetailModal();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat detail notifikasi');
        });
}

function deleteNotification(notificationId) {
    if (confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/notifications/${notificationId}`;
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        const tokenField = document.createElement('input');
        tokenField.type = 'hidden';
        tokenField.name = '_token';
        tokenField.value = '{{ csrf_token() }}';
        
        form.appendChild(methodField);
        form.appendChild(tokenField);
        document.body.appendChild(form);
        form.submit();
    }
}

function refreshNotifications() {
    location.reload();
}

// Modal functions
function openCreateModal() {
    document.getElementById('createNotificationModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeCreateModal() {
    document.getElementById('createNotificationModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    clearCreateForm();
}

function openDetailModal() {
    document.getElementById('notificationDetailModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDetailModal() {
    document.getElementById('notificationDetailModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function updateRecipients() {
    const recipientType = document.querySelector('select[name="recipient_type"]').value;
    const specificRecipients = document.getElementById('specificRecipients');
    
    if (recipientType === 'specific' || recipientType === 'specific_students') {
        specificRecipients.classList.remove('hidden');
    } else {
        specificRecipients.classList.add('hidden');
    }
}

function clearCreateForm() {
    document.getElementById('createNotificationForm').reset();
    document.getElementById('specificRecipients').classList.add('hidden');
    document.querySelectorAll('input[name="specific_users[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    document.querySelectorAll('input[name="specific_students[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
}

function submitCreateForm() {
    document.getElementById('createNotificationForm').submit();
}

function getTypeIcon(type) {
    const icons = {
        'info': 'fa-info-circle',
        'warning': 'fa-exclamation-triangle',
        'success': 'fa-check-circle',
        'error': 'fa-times-circle'
    };
    return icons[type] || 'fa-bell';
}

function updateUnreadCount() {
    fetch('/api/notifications/unread-count')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.notification-badge');
            if (badge) {
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.style.display = 'inline';
                } else {
                    badge.style.display = 'none';
                }
            }
        })
        .catch(error => console.error('Error updating unread count:', error));
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateUnreadCount();
    
    // Check icon loading
    setTimeout(function() {
        const testIcon = document.querySelector('.fas, .ph');
        if (testIcon) {
            const computedStyle = window.getComputedStyle(testIcon, '::before');
            const content = computedStyle.content;
            
            // If icons are not loading, add fallback class
            if (content === 'none' || content === '""' || content === '') {
                console.warn('Icons not loading, using fallback');
                document.body.classList.add('icon-fallback');
            } else {
                console.log('Icons loaded successfully');
            }
        }
    }, 1000);
    
    // Close modals when clicking outside
    document.getElementById('createNotificationModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCreateModal();
        }
    });
    
    document.getElementById('notificationDetailModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDetailModal();
        }
    });
    
    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCreateModal();
            closeDetailModal();
        }
    });
    
    // Auto refresh unread count every 30 seconds
    setInterval(updateUnreadCount, 30000);
});
</script>
