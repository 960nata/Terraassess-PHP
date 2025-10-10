<script>
    // Sidebar functionality
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobileOverlay');
        
        sidebar.classList.toggle('open');
        overlay.classList.toggle('show');
    }

    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobileOverlay');
        
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    }

    // Profile dropdown functionality
    function toggleProfileDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    }

    // Notification functionality
    function toggleNotifications() {
        const dropdown = document.getElementById('notificationDropdown');
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        
        // Load notifications when opened
        if (dropdown.style.display === 'block') {
            loadNotifications();
        }
    }

    function loadNotifications() {
        const notificationList = document.getElementById('notificationList');
        
        // Show loading state
        notificationList.innerHTML = `
            <div class="notification-loading">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Memuat notifikasi...</p>
            </div>
        `;

        // Fetch notifications
        fetch('/api/notifications/latest')
            .then(response => response.json())
            .then(data => {
                if (data.notifications && data.notifications.length > 0) {
                    displayNotifications(data.notifications);
                    updateNotificationBadge(data.unread_count || 0);
                } else {
                    notificationList.innerHTML = `
                        <div class="notification-empty">
                            <i class="fas fa-bell-slash"></i>
                            <p>Tidak ada notifikasi</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                notificationList.innerHTML = `
                    <div class="notification-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>Gagal memuat notifikasi</p>
                    </div>
                `;
            });
    }

    function displayNotifications(notifications) {
        const notificationList = document.getElementById('notificationList');
        
        notificationList.innerHTML = notifications.map(notification => `
            <div class="notification-item ${notification.read_at ? '' : 'unread'}" onclick="markAsRead(${notification.id})">
                <div class="notification-icon">
                    <i class="fas fa-${getNotificationIcon(notification.type)}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">${notification.title}</div>
                    <div class="notification-message">${notification.message}</div>
                    <div class="notification-time">${formatTime(notification.created_at)}</div>
                </div>
            </div>
        `).join('');
    }

    function getNotificationIcon(type) {
        const icons = {
            'task': 'tasks',
            'exam': 'file-alt',
            'material': 'book',
            'general': 'info-circle',
            'warning': 'exclamation-triangle',
            'success': 'check-circle'
        };
        return icons[type] || 'bell';
    }

    function formatTime(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffInMinutes = Math.floor((now - date) / (1000 * 60));
        
        if (diffInMinutes < 1) return 'Baru saja';
        if (diffInMinutes < 60) return `${diffInMinutes} menit yang lalu`;
        
        const diffInHours = Math.floor(diffInMinutes / 60);
        if (diffInHours < 24) return `${diffInHours} jam yang lalu`;
        
        const diffInDays = Math.floor(diffInHours / 24);
        if (diffInDays < 7) return `${diffInDays} hari yang lalu`;
        
        return date.toLocaleDateString('id-ID');
    }

    function markAsRead(notificationId) {
        fetch(`/notifications/${notificationId}/mark-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove unread class
                const notificationItem = document.querySelector(`[onclick="markAsRead(${notificationId})"]`);
                if (notificationItem) {
                    notificationItem.classList.remove('unread');
                }
                // Update badge count
                updateNotificationBadge(data.unread_count || 0);
            }
        })
        .catch(error => console.error('Error marking notification as read:', error));
    }

    function markAllAsRead() {
        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove unread class from all items
                document.querySelectorAll('.notification-item.unread').forEach(item => {
                    item.classList.remove('unread');
                });
                // Update badge count
                updateNotificationBadge(0);
            }
        })
        .catch(error => console.error('Error marking all notifications as read:', error));
    }

    function updateNotificationBadge(count) {
        const badge = document.getElementById('notificationBadge');
        if (badge) {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'flex' : 'none';
        }
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        const profileContainer = document.querySelector('.user-profile-container');
        const profileDropdown = document.getElementById('profileDropdown');
        const notificationContainer = document.querySelector('.notification-container');
        const notificationDropdown = document.getElementById('notificationDropdown');
        
        if (profileContainer && !profileContainer.contains(event.target)) {
            profileDropdown.style.display = 'none';
        }
        
        if (notificationContainer && !notificationContainer.contains(event.target)) {
            notificationDropdown.style.display = 'none';
        }
    });

    // Close sidebar on window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            closeSidebar();
        }
    });

    // Load notification count on page load
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/api/notifications/unread-count')
            .then(response => response.json())
            .then(data => {
                updateNotificationBadge(data.count || 0);
            })
            .catch(error => console.error('Error loading notification count:', error));
    });
</script>
