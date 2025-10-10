{{-- Notification Bell Component --}}
<div class="notification-bell position-relative">
    <button class="btn btn-link text-decoration-none position-relative" 
            type="button" 
            data-bs-toggle="dropdown" 
            aria-expanded="false"
            id="notificationBell">
        <i class="fas fa-bell fa-lg"></i>
        @if($unreadCount > 0)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
        </span>
        @endif
    </button>
    
    <ul class="dropdown-menu dropdown-menu-end notification-dropdown" style="width: 350px; max-height: 400px; overflow-y: auto;">
        <li class="dropdown-header d-flex justify-content-between align-items-center">
            <span>Notifikasi</span>
            @if($unreadCount > 0)
            <button class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()">
                Tandai Semua Dibaca
            </button>
            @endif
        </li>
        
        @if($notifications->count() > 0)
            @foreach($notifications as $notification)
            <li>
                <div class="notification-item {{ !$notification->is_read ? 'unread' : '' }}" 
                     onclick="markAsRead({{ $notification->id }})">
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
                            <h6 class="mb-1">{{ $notification->title }}</h6>
                            <p class="mb-1 text-muted small">{{ Str::limit($notification->message, 100) }}</p>
                            <small class="text-muted">
                                {{ $notification->created_at->diffForHumans() }}
                            </small>
                        </div>
                        @if(!$notification->is_read)
                        <div class="notification-indicator">
                            <span class="badge bg-primary rounded-pill"></span>
                        </div>
                        @endif
                    </div>
                </div>
            </li>
            @endforeach
            
            <li class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item text-center" href="{{ route('notifications.index') }}">
                    Lihat Semua Notifikasi
                </a>
            </li>
        @else
            <li class="text-center py-3">
                <i class="fas fa-bell-slash text-muted fa-2x mb-2"></i>
                <p class="text-muted mb-0">Tidak ada notifikasi</p>
            </li>
        @endif
    </ul>
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
            // Update UI
            const notificationItem = document.querySelector(`[onclick="markAsRead(${notificationId})"]`);
            if (notificationItem) {
                notificationItem.classList.remove('unread');
                const indicator = notificationItem.querySelector('.notification-indicator');
                if (indicator) {
                    indicator.remove();
                }
            }
            
            // Update unread count
            updateUnreadCount();
        }
    })
    .catch(error => console.error('Error:', error));
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
            // Update UI
            document.querySelectorAll('.notification-item.unread').forEach(item => {
                item.classList.remove('unread');
                const indicator = item.querySelector('.notification-indicator');
                if (indicator) {
                    indicator.remove();
                }
            });
            
            // Hide mark all button
            const markAllBtn = document.querySelector('[onclick="markAllAsRead()"]');
            if (markAllBtn) {
                markAllBtn.style.display = 'none';
            }
            
            updateUnreadCount();
        }
    })
    .catch(error => console.error('Error:', error));
}

function updateUnreadCount() {
    fetch('/api/notifications/unread-count')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.notification-bell .badge');
            if (data.count > 0) {
                badge.textContent = data.count > 99 ? '99+' : data.count;
                badge.style.display = 'block';
            } else {
                badge.style.display = 'none';
            }
        })
        .catch(error => console.error('Error:', error));
}

// Auto-refresh notifications every 30 seconds
setInterval(() => {
    updateUnreadCount();
}, 30000);
</script>

<style>
.notification-dropdown {
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.notification-item {
    padding: 12px 16px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: background-color 0.2s;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-item.unread {
    background-color: #e3f2fd;
    border-left: 3px solid #2196f3;
}

.notification-content h6 {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 4px;
}

.notification-content p {
    font-size: 13px;
    line-height: 1.4;
}

.notification-indicator {
    display: flex;
    align-items: center;
}

.notification-indicator .badge {
    width: 8px;
    height: 8px;
    padding: 0;
}

.notification-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
}

@media (max-width: 768px) {
    .notification-dropdown {
        width: 300px !important;
    }
    
    .notification-item {
        padding: 10px 12px;
    }
}
</style>
