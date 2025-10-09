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
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-bell"></i>
            Push Notifikasi
        </h1>
        <p class="page-description">Kelola dan kirim notifikasi ke pengguna</p>
    </div>

    <!-- Mobile Stats Cards -->
    <div class="stats-grid-mobile">
        <div class="stat-card-mobile">
            <div class="stat-icon">
                <i class="ph-bell"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $totalNotifications }}</div>
                <div class="stat-label">Total</div>
            </div>
        </div>
        <div class="stat-card-mobile">
            <div class="stat-icon success">
                <i class="ph-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $readNotifications }}</div>
                <div class="stat-label">Dibaca</div>
            </div>
        </div>
        <div class="stat-card-mobile">
            <div class="stat-icon warning">
                <i class="ph-envelope"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $unreadNotifications }}</div>
                <div class="stat-label">Belum Dibaca</div>
            </div>
        </div>
        <div class="stat-card-mobile">
            <div class="stat-icon danger">
                <i class="ph-warning"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $urgentNotifications }}</div>
                <div class="stat-label">Penting</div>
            </div>
        </div>
    </div>

    <!-- Mobile Filter Section -->
    <div class="filter-section-mobile">
        <div class="filter-row">
            <div class="search-container">
                <i class="ph-magnifying-glass search-icon"></i>
                <input type="text" id="mobileSearchInput" placeholder="Cari notifikasi..." class="search-input-mobile">
            </div>
            <button onclick="toggleFilterMenu()" class="filter-toggle-btn">
                <i class="ph-funnel"></i>
            </button>
        </div>
        
        <div id="filterMenu" class="filter-menu-mobile hidden">
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

    <!-- Mobile Notifications List -->
    <div class="notifications-list-mobile">
        @forelse($notifications ?? [] as $notification)
            <div class="notification-item-mobile {{ $notification->is_read ? 'read' : 'unread' }}" 
                 data-id="{{ $notification->id }}"
                 data-type="{{ $notification->type }}">
                <div class="notification-content-mobile">
                    <div class="notification-header-item">
                        <div class="notification-type-badge {{ $notification->type }}">
                            @php
                                $typeIcons = [
                                    'info' => 'ph-info',
                                    'warning' => 'ph-warning',
                                    'success' => 'ph-check-circle',
                                    'error' => 'ph-x-circle'
                                ];
                            @endphp
                            <i class="{{ $typeIcons[$notification->type] ?? 'ph-bell' }}"></i>
                        </div>
                        <div class="notification-time-mobile">
                            {{ $notification->created_at->diffForHumans() }}
                        </div>
                    </div>
                    
                    <div class="notification-body-mobile">
                        <h3 class="notification-title-mobile">{{ $notification->title }}</h3>
                        <p class="notification-message-mobile">{{ Str::limit($notification->body, 100) }}</p>
                        
                        @if($notification->user)
                            <div class="notification-sender-mobile">
                                <i class="ph-user"></i>
                                <span>{{ $notification->user->name }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="notification-actions-mobile">
                        @if(!$notification->is_read)
                            <button onclick="markAsRead('{{ $notification->id }}')" class="action-btn-mobile small">
                                <i class="ph-check"></i>
                                <span>Tandai Dibaca</span>
                            </button>
                        @endif
                        <button onclick="viewNotification('{{ $notification->id }}')" class="action-btn-mobile small">
                            <i class="ph-eye"></i>
                            <span>Lihat</span>
                        </button>
                        @if($userRole === 'superadmin' || $userRole === 'admin')
                            <button onclick="deleteNotification('{{ $notification->id }}')" class="action-btn-mobile small danger">
                                <i class="ph-trash"></i>
                                <span>Hapus</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state-mobile">
                <div class="empty-icon">
                    <i class="ph-bell-slash"></i>
                </div>
                <h3 class="empty-title">Belum Ada Notifikasi</h3>
                <p class="empty-message">Belum ada notifikasi yang diterima.</p>
                @if($userRole === 'superadmin' || $userRole === 'admin')
                    <button onclick="openCreateModal()" class="empty-action-btn">
                        <i class="ph-plus"></i>
                        Buat Notifikasi Pertama
                    </button>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Mobile Pagination -->
    @if(isset($notifications) && is_object($notifications) && method_exists($notifications, 'hasPages') && $notifications->hasPages())
        <div class="pagination-mobile">
            {{ $notifications->links() }}
        </div>
    @endif
</div>

<!-- Create Notification Modal (Mobile) -->
@if($userRole === 'superadmin' || $userRole === 'admin')
<div id="createNotificationModal" class="modal-mobile hidden">
    <div class="modal-backdrop" onclick="closeCreateModal()"></div>
    <div class="modal-content-mobile">
        <div class="modal-header-mobile">
            <h3 class="modal-title-mobile">
                <i class="ph-paper-plane-tilt"></i>
                Buat Notifikasi
            </h3>
            <button onclick="closeCreateModal()" class="modal-close-btn">
                <i class="ph-x"></i>
            </button>
        </div>
        
        <form id="createNotificationForm" method="POST" action="{{
            $userRole === 'superadmin' ? route('superadmin.push-notification.send') :
            ($userRole === 'admin' ? route('superadmin.push-notification.send') : route('teacher.push-notification.send'))
        }}" class="modal-body-mobile">
            @csrf
            <div class="form-group-mobile">
                <label class="form-label-mobile">Judul Notifikasi</label>
                <input type="text" name="title" class="form-input-mobile" placeholder="Masukkan judul notifikasi" required>
            </div>
            
            <div class="form-group-mobile">
                <label class="form-label-mobile">Tipe Notifikasi</label>
                <select name="type" class="form-input-mobile" required>
                    <option value="info">Informasi</option>
                    <option value="warning">Peringatan</option>
                    <option value="success">Sukses</option>
                    <option value="error">Error</option>
                </select>
            </div>
            
            <div class="form-group-mobile">
                <label class="form-label-mobile">Pesan</label>
                <textarea name="body" class="form-input-mobile form-textarea-mobile" placeholder="Tulis pesan notifikasi..." required></textarea>
            </div>
            
            <div class="form-group-mobile">
                <label class="form-label-mobile">Penerima</label>
                <select name="recipient_type" class="form-input-mobile" onchange="updateRecipients()" required>
                    <option value="all">Semua Pengguna</option>
                    <option value="students">Siswa Saja</option>
                    <option value="teachers">Guru Saja</option>
                    <option value="admins">Admin Saja</option>
                    <option value="specific">Pilih Spesifik</option>
                </select>
            </div>
            
            <div id="specificRecipients" class="form-group-mobile hidden">
                <label class="form-label-mobile">Pilih Penerima Spesifik</label>
                <div class="recipients-list-mobile">
                    @foreach($users ?? [] as $user)
                        <label class="recipient-item-mobile">
                            <input type="checkbox" name="specific_users[]" value="{{ $user->id }}" class="recipient-checkbox">
                            <div class="recipient-info">
                                <div class="recipient-name">{{ $user->name }}</div>
                                <div class="recipient-email">{{ $user->email }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </form>
        
        <div class="modal-footer-mobile">
            <button onclick="closeCreateModal()" class="modal-btn-mobile secondary">
                <i class="ph-x"></i>
                Batal
            </button>
            <button onclick="submitCreateForm()" class="modal-btn-mobile primary">
                <i class="ph-paper-plane-tilt"></i>
                Kirim
            </button>
        </div>
    </div>
</div>
@endif

<!-- Notification Detail Modal (Mobile) -->
<div id="notificationDetailModal" class="modal-mobile hidden">
    <div class="modal-backdrop" onclick="closeDetailModal()"></div>
    <div class="modal-content-mobile">
        <div class="modal-header-mobile">
            <h3 class="modal-title-mobile">
                <i class="ph-bell"></i>
                Detail Notifikasi
            </h3>
            <button onclick="closeDetailModal()" class="modal-close-btn">
                <i class="ph-x"></i>
            </button>
        </div>
        
        <div class="modal-body-mobile">
            <div id="notificationDetailContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
        
        <div class="modal-footer-mobile">
            <button onclick="closeDetailModal()" class="modal-btn-mobile secondary">
                <i class="ph-x"></i>
                Tutup
            </button>
        </div>
    </div>
</div>

<style>
/* Enhanced Mobile-First Notification Styles */
.notification-management-container {
    padding: 0;
    background: #0f172a;
    min-height: 100vh;
    animation: fadeIn 0.6s ease-out;
}

/* Ensure Phosphor Icons are visible */
.ph, [class^="ph-"], [class*=" ph-"] {
    display: inline-block !important;
    font-style: normal !important;
    font-variant: normal !important;
    text-rendering: auto !important;
    -webkit-font-smoothing: antialiased !important;
    -moz-osx-font-smoothing: grayscale !important;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Page Header Styles */
.page-header {
    margin-bottom: 2rem;
}

.page-title {
    color: #ffffff;
    font-size: 1.875rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.page-description {
    color: #94a3b8;
    font-size: 1rem;
    margin: 0;
}
/* Stats Grid Styles */

/* Stats Grid Mobile Styles */
.stats-grid-mobile {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card-mobile {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    border: 1px solid #475569;
    border-radius: 16px;
    padding: 20px 16px;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    animation: slideInUp 0.6s ease-out;
    animation-fill-mode: both;
}

.stat-card-mobile:nth-child(1) { animation-delay: 0.1s; }
.stat-card-mobile:nth-child(2) { animation-delay: 0.2s; }
.stat-card-mobile:nth-child(3) { animation-delay: 0.3s; }
.stat-card-mobile:nth-child(4) { animation-delay: 0.4s; }

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stat-card-mobile:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4);
    border-color: rgba(59, 130, 246, 0.3);
}

.stat-card-mobile::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, #3b82f6, #10b981, #f59e0b, #ef4444);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.stat-card-mobile:hover::before {
    opacity: 1;
}

.stat-icon {
    width: 40px;
    height: 40px;
    background: rgba(59, 130, 246, 0.1);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #3b82f6;
    font-size: 18px;
}

.stat-icon i {
    display: inline-block !important;
    font-size: 18px !important;
    color: inherit !important;
}

.stat-icon.success {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.stat-icon.warning {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}

.stat-icon.danger {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 24px;
    font-weight: 700;
    color: #ffffff;
    line-height: 1;
}

.stat-label {
    font-size: 12px;
    color: #94a3b8;
    margin-top: 2px;
}

/* Enhanced Mobile Filter Section */
.filter-section-mobile {
    padding: 0 16px 20px;
    background: rgba(15, 23, 42, 0.5);
    margin: 0 16px 16px;
    border-radius: 12px;
    border: 1px solid rgba(71, 85, 105, 0.3);
}

.filter-row {
    display: flex;
    gap: 8px;
    align-items: center;
}

.search-container {
    flex: 1;
    position: relative;
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
    font-size: 16px;
    display: inline-block !important;
    z-index: 10;
}

.search-input-mobile {
    width: 100%;
    padding: 12px 12px 12px 40px;
    background: #0f172a;
    border: 1px solid #475569;
    border-radius: 10px;
    color: #e2e8f0;
    font-size: 14px;
    transition: all 0.3s ease;
}

.search-input-mobile:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.filter-toggle-btn {
    width: 44px;
    height: 44px;
    background: #374151;
    border: 1px solid #4b5563;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    font-size: 16px;
    transition: all 0.3s ease;
}

.filter-toggle-btn i {
    display: inline-block !important;
    font-size: 16px !important;
    color: inherit !important;
}

.filter-toggle-btn:hover {
    background: #4b5563;
    color: #d1d5db;
}

.filter-menu-mobile {
    background: #1e293b;
    border: 1px solid #475569;
    border-radius: 12px;
    padding: 16px;
    margin-top: 12px;
    transition: all 0.3s ease;
}

.filter-menu-mobile.hidden {
    display: none;
}

.filter-group {
    margin-bottom: 16px;
}

.filter-label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #e2e8f0;
    margin-bottom: 8px;
}

.filter-select {
    width: 100%;
    padding: 10px 12px;
    background: #0f172a;
    border: 1px solid #475569;
    border-radius: 8px;
    color: #e2e8f0;
    font-size: 14px;
}

.filter-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.filter-btn {
    padding: 8px 16px;
    background: #374151;
    border: 1px solid #4b5563;
    border-radius: 8px;
    color: #9ca3af;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-btn.active {
    background: #3b82f6;
    border-color: #3b82f6;
    color: white;
}

.filter-btn:hover {
    background: #4b5563;
    color: #d1d5db;
}

.filter-actions {
    display: flex;
    gap: 8px;
    margin-top: 16px;
}

.apply-btn, .clear-btn {
    flex: 1;
    padding: 10px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.apply-btn {
    background: #3b82f6;
    border: 1px solid #3b82f6;
    color: white;
}

.apply-btn:hover {
    background: #1d4ed8;
}

.clear-btn {
    background: #374151;
    border: 1px solid #4b5563;
    color: #9ca3af;
}

.clear-btn:hover {
    background: #4b5563;
    color: #d1d5db;
}

/* Enhanced Mobile Notifications List */
.notifications-list-mobile {
    padding: 0 16px 20px;
}

.notification-item-mobile {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    border: 1px solid #475569;
    border-radius: 16px;
    margin-bottom: 16px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    position: relative;
    animation: slideInLeft 0.6s ease-out;
    animation-fill-mode: both;
}

.notification-item-mobile:nth-child(1) { animation-delay: 0.1s; }
.notification-item-mobile:nth-child(2) { animation-delay: 0.2s; }
.notification-item-mobile:nth-child(3) { animation-delay: 0.3s; }
.notification-item-mobile:nth-child(4) { animation-delay: 0.4s; }
.notification-item-mobile:nth-child(5) { animation-delay: 0.5s; }

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.notification-item-mobile.unread {
    border-left: 4px solid #3b82f6;
}

.notification-item-mobile.read {
    opacity: 0.8;
}

.notification-item-mobile:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4);
    border-color: rgba(59, 130, 246, 0.3);
}

.notification-content-mobile {
    padding: 20px;
}

.notification-header-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}

.notification-type-badge {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    color: white;
}

.notification-type-badge i {
    display: inline-block !important;
    font-size: 14px !important;
    color: white !important;
}

.notification-type-badge.info {
    background: #3b82f6;
}

.notification-type-badge.warning {
    background: #f59e0b;
}

.notification-type-badge.success {
    background: #10b981;
}

.notification-type-badge.error {
    background: #ef4444;
}

.notification-time-mobile {
    font-size: 12px;
    color: #94a3b8;
}

.notification-body-mobile {
    margin-bottom: 16px;
}

.notification-title-mobile {
    font-size: 16px;
    font-weight: 600;
    color: #e2e8f0;
    margin: 0 0 8px 0;
    line-height: 1.3;
}

.notification-message-mobile {
    font-size: 14px;
    color: #cbd5e1;
    line-height: 1.4;
    margin: 0 0 8px 0;
}

.notification-sender-mobile {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #94a3b8;
}

.notification-sender-mobile i {
    display: inline-block !important;
    font-size: 12px !important;
    color: #94a3b8 !important;
}

.notification-actions-mobile {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.action-btn-mobile.small {
    padding: 8px 12px;
    font-size: 12px;
    height: auto;
    width: auto;
    min-width: 80px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.action-btn-mobile.small i {
    display: inline-block !important;
    font-size: 12px !important;
    color: inherit !important;
}

.action-btn-mobile.small span {
    font-size: 11px;
}

.action-btn-mobile.small span i {
    display: inline-block !important;
    font-size: 11px !important;
    color: inherit !important;
}

.action-btn-mobile.danger {
    background: rgba(239, 68, 68, 0.1);
    border-color: rgba(239, 68, 68, 0.3);
    color: #ef4444;
}

.action-btn-mobile.danger:hover {
    background: rgba(239, 68, 68, 0.2);
}

/* Empty State */
.empty-state-mobile {
    text-align: center;
    padding: 48px 16px;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: rgba(148, 163, 184, 0.1);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
    color: #94a3b8;
    font-size: 32px;
}

.empty-icon i {
    display: inline-block !important;
    font-size: 32px !important;
    color: #94a3b8 !important;
}

.empty-title {
    font-size: 18px;
    font-weight: 600;
    color: #e2e8f0;
    margin: 0 0 8px 0;
}

.empty-message {
    font-size: 14px;
    color: #94a3b8;
    margin: 0 0 24px 0;
}

.empty-action-btn {
    background: #3b82f6;
    border: 1px solid #3b82f6;
    color: white;
    padding: 12px 24px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.empty-action-btn i {
    display: inline-block !important;
    font-size: 14px !important;
    color: white !important;
}

.empty-action-btn:hover {
    background: #1d4ed8;
    transform: translateY(-2px);
}

/* Mobile Pagination */
.pagination-mobile {
    padding: 16px;
    display: flex;
    justify-content: center;
}

.pagination-mobile .pagination {
    display: flex;
    gap: 8px;
    align-items: center;
}

.pagination-mobile .page-link {
    padding: 8px 12px;
    background: #374151;
    border: 1px solid #4b5563;
    border-radius: 8px;
    color: #9ca3af;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s ease;
}

.pagination-mobile .page-link:hover {
    background: #4b5563;
    color: #d1d5db;
}

.pagination-mobile .page-item.active .page-link {
    background: #3b82f6;
    border-color: #3b82f6;
    color: white;
}

/* Mobile Modal */
.modal-mobile {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 50;
    display: flex;
    align-items: flex-end;
    justify-content: center;
}

.modal-mobile.hidden {
    display: none;
}

.modal-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
}

.modal-content-mobile {
    background: #1e293b;
    border-radius: 20px 20px 0 0;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    z-index: 1;
    animation: slideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);
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

/* Loading Animation */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.loading {
    animation: pulse 2s infinite;
}

/* Smooth Transitions */
* {
    transition: color 0.3s ease, background-color 0.3s ease, border-color 0.3s ease;
}

.modal-header-mobile {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px;
    border-bottom: 1px solid #475569;
    position: sticky;
    top: 0;
    background: #1e293b;
    z-index: 10;
}

.modal-title-mobile {
    font-size: 18px;
    font-weight: 600;
    color: #e2e8f0;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.modal-title-mobile i {
    display: inline-block !important;
    font-size: 18px !important;
    color: #3b82f6 !important;
}

.modal-close-btn {
    width: 32px;
    height: 32px;
    background: rgba(148, 163, 184, 0.1);
    border: none;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.modal-close-btn i {
    display: inline-block !important;
    font-size: 16px !important;
    color: inherit !important;
}

.modal-close-btn:hover {
    background: rgba(148, 163, 184, 0.2);
    color: #e2e8f0;
}

.modal-body-mobile {
    padding: 20px;
}

.form-group-mobile {
    margin-bottom: 20px;
}

.form-label-mobile {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #e2e8f0;
    margin-bottom: 8px;
}

.form-input-mobile {
    width: 100%;
    padding: 12px 16px;
    background: #0f172a;
    border: 1px solid #475569;
    border-radius: 10px;
    color: #e2e8f0;
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-input-mobile:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-textarea-mobile {
    min-height: 100px;
    resize: vertical;
}

.recipients-list-mobile {
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #475569;
    border-radius: 10px;
    padding: 8px;
}

.recipient-item-mobile {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.recipient-item-mobile:hover {
    background: rgba(148, 163, 184, 0.1);
}

.recipient-checkbox {
    width: 16px;
    height: 16px;
    accent-color: #3b82f6;
}

.recipient-info {
    flex: 1;
}

.recipient-name {
    font-size: 14px;
    font-weight: 500;
    color: #e2e8f0;
}

.recipient-email {
    font-size: 12px;
    color: #94a3b8;
}

.modal-footer-mobile {
    display: flex;
    gap: 12px;
    padding: 20px;
    border-top: 1px solid #475569;
    position: sticky;
    bottom: 0;
    background: #1e293b;
}

.modal-btn-mobile {
    flex: 1;
    padding: 12px 16px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.modal-btn-mobile i {
    display: inline-block !important;
    font-size: 14px !important;
    color: inherit !important;
}

.modal-btn-mobile.secondary {
    background: #374151;
    border: 1px solid #4b5563;
    color: #9ca3af;
}

.modal-btn-mobile.secondary:hover {
    background: #4b5563;
    color: #d1d5db;
}

.modal-btn-mobile.primary {
    background: #3b82f6;
    border: 1px solid #3b82f6;
    color: white;
}

.modal-btn-mobile.primary:hover {
    background: #1d4ed8;
}

/* Enhanced Responsive Design */
@media (min-width: 480px) {
    .stats-grid-mobile {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        padding: 24px 20px;
    }
    
    .stat-card-mobile {
        padding: 24px 20px;
    }
    
    .notification-content-mobile {
        padding: 24px;
    }
}

@media (min-width: 768px) {
    .stats-grid-mobile {
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
        padding: 32px 24px;
    }
    
    .stat-card-mobile {
        padding: 28px 24px;
    }
    
    .filter-section-mobile {
        margin: 0 24px 24px;
        padding: 24px;
    }
    
    .notifications-list-mobile {
        padding: 0 24px 24px;
    }
    
    .notification-content-mobile {
        padding: 28px;
    }
    
    .modal-content-mobile {
        max-width: 600px;
        border-radius: 20px;
        margin: 20px;
    }
    
    .modal-mobile {
        align-items: center;
    }
    
    .header-actions {
        gap: 12px;
    }
    
    .action-btn-mobile {
        width: 48px;
        height: 48px;
        font-size: 18px;
    }
}

@media (min-width: 1024px) {
    .notification-management-container {
        padding: 32px;
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .notification-header-mobile {
        border-radius: 16px;
        margin-bottom: 32px;
        padding: 24px 32px;
    }
    
    .stats-grid-mobile {
        margin-bottom: 32px;
        padding: 32px;
    }
    
    .filter-section-mobile {
        margin: 0 32px 32px;
        padding: 32px;
    }
    
    .notifications-list-mobile {
        padding: 0 32px 32px;
    }
    
    .notification-item-mobile {
        margin-bottom: 20px;
    }
    
    .notification-content-mobile {
        padding: 32px;
    }
    
    .header-title {
        font-size: 24px;
    }
    
    .header-subtitle {
        font-size: 16px;
    }
}

@media (min-width: 1280px) {
    .notification-management-container {
        padding: 40px;
    }
    
    .stats-grid-mobile {
        padding: 40px;
    }
    
    .filter-section-mobile {
        margin: 0 40px 40px;
        padding: 40px;
    }
    
    .notifications-list-mobile {
        padding: 0 40px 40px;
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
    const searchTerm = document.getElementById('mobileSearchInput').value.toLowerCase();
    const typeFilter = document.getElementById('typeFilter').value;
    
    currentFilters.search = searchTerm;
    currentFilters.type = typeFilter;
    
    filterNotifications();
    document.getElementById('filterMenu').classList.add('hidden');
}

function clearFilters() {
    document.getElementById('mobileSearchInput').value = '';
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
    const notifications = document.querySelectorAll('.notification-item-mobile');
    
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
document.getElementById('mobileSearchInput').addEventListener('input', function() {
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
                
                const markButton = notificationItem.querySelector('.action-btn-mobile.small');
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
                            <i class="${getTypeIcon(data.type)}"></i>
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
    
    if (recipientType === 'specific') {
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
}

function submitCreateForm() {
    document.getElementById('createNotificationForm').submit();
}

function getTypeIcon(type) {
    const icons = {
        'info': 'ph-info',
        'warning': 'ph-warning',
        'success': 'ph-check-circle',
        'error': 'ph-x-circle'
    };
    return icons[type] || 'ph-bell';
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
