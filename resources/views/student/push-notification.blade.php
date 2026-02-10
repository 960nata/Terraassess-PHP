@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Notifikasi Saya')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">🔔 Notifikasi Saya</h2>
                    <p class="text-muted mb-0">Kelola notifikasi Anda</p>
                </div>
                <div>
                    <button class="btn btn-outline-primary me-2" onclick="markAllAsRead()">
                        <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                    </button>
                    <button class="btn btn-outline-danger" onclick="clearAllNotifications()">
                        <i class="fas fa-trash"></i> Hapus Semua
                    </button>
                </div>
            </div>

            {{-- Statistics Cards --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $totalNotifications }}</h4>
                                    <p class="mb-0">Total Notifikasi</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-bell fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $unreadNotifications }}</h4>
                                    <p class="mb-0">Belum Dibaca</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-envelope fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $readNotifications }}</h4>
                                    <p class="mb-0">Sudah Dibaca</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $urgentNotifications }}</h4>
                                    <p class="mb-0">Penting</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Tabs --}}
            <ul class="nav nav-tabs mb-4" id="notificationTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                        Semua ({{ $notifications->total() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="unread-tab" data-bs-toggle="tab" data-bs-target="#unread" type="button" role="tab">
                        Belum Dibaca ({{ $notifications->filter(function($notification) { return !$notification->is_read; })->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="read-tab" data-bs-toggle="tab" data-bs-target="#read" type="button" role="tab">
                        Sudah Dibaca ({{ $notifications->filter(function($notification) { return $notification->is_read; })->count() }})
                    </button>
                </li>
            </ul>

            {{-- Tab Content --}}
            <div class="tab-content" id="notificationTabContent">
                {{-- All Notifications --}}
                <div class="tab-pane fade show active" id="all" role="tabpanel">
                    @if($notifications->total() > 0)
                        <div class="notification-list">
                            @foreach($notifications as $notification)
                            <div class="notification-card {{ !$notification->is_read ? 'unread' : '' }}" 
                                 data-notification-id="{{ $notification->id }}">
                                <div class="d-flex">
                                    <div class="notification-icon me-3">
                                        @switch($notification->type)
                                            @case('success')
                                                <i class="fas fa-check-circle text-success"></i>
                                                @break
                                            @case('warning')
                                                <i class="fas fa-exclamation-triangle text-warning"></i>
                                                @break
                                            @case('error')
                                                <i class="fas fa-times-circle text-danger"></i>
                                                @break
                                            @default
                                                <i class="fas fa-info-circle text-info"></i>
                                        @endswitch
                                    </div>
                                    <div class="notification-content flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h5 class="mb-1">{{ $notification->title }}</h5>
                                                <p class="mb-2 text-muted">{{ $notification->body }}</p>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                            <div class="notification-actions">
                                                @if(!$notification->is_read)
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        onclick="markAsRead({{ $notification->id }})">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                @endif
                                                <button class="btn btn-sm btn-outline-danger" 
                                                        onclick="deleteNotification({{ $notification->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        {{-- Pagination --}}
                        <div class="d-flex justify-content-center mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash text-muted fa-4x mb-3"></i>
                            <h4 class="text-muted">Tidak ada notifikasi</h4>
                            <p class="text-muted">Anda akan menerima notifikasi ketika ada aktivitas baru.</p>
                        </div>
                    @endif
                </div>

                {{-- Unread Notifications --}}
                <div class="tab-pane fade" id="unread" role="tabpanel">
                    @php $unreadNotifications = $notifications->filter(function($notification) { return !$notification->is_read; }); @endphp
                    @if($unreadNotifications->count() > 0)
                        <div class="notification-list">
                            @foreach($unreadNotifications as $notification)
                            <div class="notification-card unread" data-notification-id="{{ $notification->id }}">
                                <div class="d-flex">
                                    <div class="notification-icon me-3">
                                        @switch($notification->type)
                                            @case('success')
                                                <i class="fas fa-check-circle text-success"></i>
                                                @break
                                            @case('warning')
                                                <i class="fas fa-exclamation-triangle text-warning"></i>
                                                @break
                                            @case('error')
                                                <i class="fas fa-times-circle text-danger"></i>
                                                @break
                                            @default
                                                <i class="fas fa-info-circle text-info"></i>
                                        @endswitch
                                    </div>
                                    <div class="notification-content flex-grow-1">
                                        <h5 class="mb-1">{{ $notification->title }}</h5>
                                        <p class="mb-2 text-muted">{{ $notification->body }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <div class="notification-actions">
                                        <button class="btn btn-sm btn-outline-primary" 
                                                onclick="markAsRead({{ $notification->id }})">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" 
                                                onclick="deleteNotification({{ $notification->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
                            <h4 class="text-success">Semua notifikasi sudah dibaca</h4>
                            <p class="text-muted">Tidak ada notifikasi yang belum dibaca.</p>
                        </div>
                    @endif
                </div>

                {{-- Read Notifications --}}
                <div class="tab-pane fade" id="read" role="tabpanel">
                    @php $readNotifications = $notifications->filter(function($notification) { return $notification->is_read; }); @endphp
                    @if($readNotifications->count() > 0)
                        <div class="notification-list">
                            @foreach($readNotifications as $notification)
                            <div class="notification-card" data-notification-id="{{ $notification->id }}">
                                <div class="d-flex">
                                    <div class="notification-icon me-3">
                                        @switch($notification->type)
                                            @case('success')
                                                <i class="fas fa-check-circle text-success"></i>
                                                @break
                                            @case('warning')
                                                <i class="fas fa-exclamation-triangle text-warning"></i>
                                                @break
                                            @case('error')
                                                <i class="fas fa-times-circle text-danger"></i>
                                                @break
                                            @default
                                                <i class="fas fa-info-circle text-info"></i>
                                        @endswitch
                                    </div>
                                    <div class="notification-content flex-grow-1">
                                        <h5 class="mb-1">{{ $notification->title }}</h5>
                                        <p class="mb-2 text-muted">{{ $notification->body }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                            @if($notification->read_at)
                                            | <i class="fas fa-check"></i> Dibaca {{ $notification->read_at->diffForHumans() }}
                                            @endif
                                        </small>
                                    </div>
                                    <div class="notification-actions">
                                        <button class="btn btn-sm btn-outline-danger" 
                                                onclick="deleteNotification({{ $notification->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-history text-muted fa-4x mb-3"></i>
                            <h4 class="text-muted">Belum ada notifikasi yang dibaca</h4>
                            <p class="text-muted">Notifikasi yang sudah dibaca akan muncul di sini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const notificationCard = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationCard) {
                notificationCard.classList.remove('unread');
                const markReadBtn = notificationCard.querySelector('[onclick*="markAsRead"]');
                if (markReadBtn) {
                    markReadBtn.remove();
                }
            }
            showToast('Notifikasi ditandai sebagai dibaca', 'success');
            // Update counters
            updateCounters();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Gagal menandai notifikasi', 'error');
    });
}

function markAllAsRead() {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelectorAll('.notification-card.unread').forEach(card => {
                card.classList.remove('unread');
                const markReadBtn = card.querySelector('[onclick*="markAsRead"]');
                if (markReadBtn) {
                    markReadBtn.remove();
                }
            });
            showToast('Semua notifikasi ditandai sebagai dibaca', 'success');
            // Update counters
            updateCounters();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Gagal menandai semua notifikasi', 'error');
    });
}

function deleteNotification(notificationId) {
    if (confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) {
        fetch(`/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const notificationCard = document.querySelector(`[data-notification-id="${notificationId}"]`);
                if (notificationCard) {
                    notificationCard.remove();
                }
                showToast('Notifikasi berhasil dihapus', 'success');
                // Update counters
                updateCounters();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Gagal menghapus notifikasi', 'error');
        });
    }
}

function clearAllNotifications() {
    if (confirm('Apakah Anda yakin ingin menghapus semua notifikasi?')) {
        fetch('/notifications', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Gagal menghapus semua notifikasi', 'error');
        });
    }
}

function updateCounters() {
    // Update tab counters
    const unreadCount = document.querySelectorAll('.notification-card.unread').length;
    const readCount = document.querySelectorAll('.notification-card:not(.unread)').length;
    const totalCount = unreadCount + readCount;
    
    document.querySelector('#all-tab').textContent = `Semua (${totalCount})`;
    document.querySelector('#unread-tab').textContent = `Belum Dibaca (${unreadCount})`;
    document.querySelector('#read-tab').textContent = `Sudah Dibaca (${readCount})`;
}

function showToast(message, type = 'info') {
    // Simple toast implementation
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type} position-fixed`;
    toast.style.top = '20px';
    toast.style.right = '20px';
    toast.style.zIndex = '9999';
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>

<style>
.notification-card {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}

.notification-card:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transform: translateY(-1px);
}

.notification-card.unread {
    border-left: 4px solid #007bff;
    background-color: #f8f9ff;
}

.notification-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #f8f9fa;
}

.notification-content h5 {
    font-size: 16px;
    font-weight: 600;
    color: #333;
}

.notification-content p {
    font-size: 14px;
    line-height: 1.5;
    color: #666;
}

.notification-actions {
    display: flex;
    gap: 5px;
}

.notification-actions .btn {
    padding: 5px 10px;
    font-size: 12px;
}

.nav-tabs .nav-link {
    border: none;
    color: #666;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    color: #007bff;
    border-bottom: 2px solid #007bff;
    background: none;
}

.notification-list {
    max-height: 600px;
    overflow-y: auto;
}

@media (max-width: 768px) {
    .notification-card {
        padding: 15px;
    }
    
    .notification-actions {
        flex-direction: column;
    }
    
    .notification-actions .btn {
        width: 100%;
        margin-bottom: 5px;
    }
}
</style>
@endsection
