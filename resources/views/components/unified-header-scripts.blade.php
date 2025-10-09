<script>
    // Sidebar functions
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        const mainContent = document.querySelector('.main-content');
        
        if (window.innerWidth <= 1024) {
            // Mobile behavior
            sidebar.classList.toggle('collapsed');
            if (mobileOverlay) mobileOverlay.classList.toggle('active');
            if (mainContent) mainContent.classList.toggle('sidebar-open');
        } else {
            // Desktop behavior
            sidebar.classList.toggle('collapsed');
        }
    }

    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        const mainContent = document.querySelector('.main-content');
        
        sidebar.classList.add('collapsed');
        if (mobileOverlay) mobileOverlay.classList.remove('active');
        if (mainContent) mainContent.classList.remove('sidebar-open');
    }

    // Notification functionality
    let notificationDropdown = null;
    let notificationBadge = null;
    let notificationList = null;
    let isDropdownOpen = false;

    // Close sidebar on mobile when clicking outside
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const menuToggle = document.querySelector('.menu-toggle');
        const mobileOverlay = document.getElementById('mobileOverlay');
        
        if (window.innerWidth <= 1024 && 
            sidebar && 
            !sidebar.contains(event.target) && 
            menuToggle && 
            !menuToggle.contains(event.target) &&
            mobileOverlay && 
            !mobileOverlay.contains(event.target)) {
            closeSidebar();
        }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        const mainContent = document.querySelector('.main-content');
        
        if (window.innerWidth > 1024) {
            // Desktop - show sidebar by default
            if (sidebar) sidebar.classList.remove('collapsed');
            if (mobileOverlay) mobileOverlay.classList.remove('active');
            if (mainContent) mainContent.classList.remove('sidebar-open');
        } else {
            // Mobile - hide sidebar by default
            if (sidebar) sidebar.classList.add('collapsed');
            if (mobileOverlay) mobileOverlay.classList.remove('active');
            if (mainContent) mainContent.classList.remove('sidebar-open');
        }
    });


    document.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing notification system...');
        
        notificationDropdown = document.getElementById('notificationDropdown');
        notificationBadge = document.getElementById('notificationBadge');
        notificationList = document.getElementById('notificationList');
        
        // Debug: Check if elements exist
        console.log('Notification dropdown:', notificationDropdown);
        console.log('Notification badge:', notificationBadge);
        console.log('Notification list:', notificationList);
        
        // Load initial notifications
        loadNotifications();
        updateUnreadCount();
        
        // Auto refresh every 30 seconds
        setInterval(function() {
            loadNotifications();
            updateUnreadCount();
        }, 30000);
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (isDropdownOpen && !e.target.closest('.notification-container')) {
                closeNotificationDropdown();
            }
        });
    });

    function toggleNotificationDropdown() {
        if (isDropdownOpen) {
            closeNotificationDropdown();
        } else {
            openNotificationDropdown();
        }
    }

    function openNotificationDropdown() {
        if (notificationDropdown) {
            notificationDropdown.style.display = 'block';
            isDropdownOpen = true;
            loadNotifications();
            
            // Add mobile class if on mobile
            if (window.innerWidth <= 768) {
                notificationDropdown.classList.add('mobile-dropdown');
            }
        }
    }

    function closeNotificationDropdown() {
        if (notificationDropdown) {
            notificationDropdown.style.display = 'none';
            isDropdownOpen = false;
            notificationDropdown.classList.remove('mobile-dropdown');
        }
    }

    function loadNotifications() {
        console.log('Loading notifications...');
        if (!notificationList) {
            console.error('Notification list element not found');
            return;
        }
        
        fetch('/api/notifications/latest')
            .then(response => {
                console.log('API response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Notifications data:', data);
                if (data.length === 0) {
                    notificationList.innerHTML = `
                        <div class="notification-loading">
                            <i class="fas fa-bell-slash"></i>
                            <span>Tidak ada notifikasi</span>
                        </div>
                    `;
                } else {
                    notificationList.innerHTML = data.map(notification => `
                        <div class="notification-item ${!notification.is_read ? 'unread' : ''}" 
                             onclick="markAsRead(${notification.id})">
                            <div class="notification-icon-small">
                                <i class="fas fa-${getNotificationIcon(notification.type)}"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-title">${notification.title}</div>
                                <div class="notification-excerpt">${notification.excerpt || notification.body.substring(0, 80) + '...'}</div>
                                <div class="notification-time">${formatTime(notification.created_at)}</div>
                            </div>
                        </div>
                    `).join('');
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                notificationList.innerHTML = `
                    <div class="notification-loading">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Gagal memuat notifikasi</span>
                    </div>
                `;
            });
    }

    function getNotificationIcon(type) {
        const icons = {
            'info': 'info',
            'warning': 'warning',
            'success': 'check-circle',
            'error': 'x-circle'
        };
        return icons[type] || 'bell';
    }

    function updateUnreadCount() {
        console.log('Updating unread count...');
        fetch('/api/notifications/unread-count')
            .then(response => {
                console.log('Unread count API response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Unread count data:', data);
                if (notificationBadge) {
                    if (data.count > 0) {
                        notificationBadge.textContent = data.count;
                        notificationBadge.style.display = 'flex';
                        notificationBadge.classList.remove('read');
                        console.log('Badge updated with count:', data.count);
                    } else {
                        notificationBadge.style.display = 'none';
                        notificationBadge.classList.add('read');
                        console.log('Badge hidden - no unread notifications');
                    }
                } else {
                    console.error('Notification badge element not found');
                }
            })
            .catch(error => console.error('Error updating unread count:', error));
    }

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
                // Update UI
                const notificationItem = document.querySelector(`[onclick="markAsRead(${notificationId})"]`);
                if (notificationItem) {
                    notificationItem.classList.remove('unread');
                }
                
                // Update unread count
                updateUnreadCount();
            }
        })
        .catch(error => console.error('Error marking notification as read:', error));
    }

    function markAllAsRead() {
        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI - remove unread class from all items
                const unreadItems = document.querySelectorAll('.notification-item.unread');
                unreadItems.forEach(item => {
                    item.classList.remove('unread');
                });
                
                // Hide notification badge and mark as read
                if (notificationBadge) {
                    notificationBadge.style.display = 'none';
                    notificationBadge.classList.add('read');
                }
                
                // Reload notifications to get updated data
                loadNotifications();
                
                // Show success message
                showNotification('Semua notifikasi telah ditandai sebagai dibaca', 'success');
            }
        })
        .catch(error => {
            console.error('Error marking all notifications as read:', error);
            showNotification('Gagal menandai notifikasi sebagai dibaca', 'error');
        });
    }

    function showNotification(message, type = 'info') {
        // Create notification toast
        const toast = document.createElement('div');
        toast.className = `notification-toast ${type}`;
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'times-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        `;
        
        // Add to body
        document.body.appendChild(toast);
        
        // Show toast
        setTimeout(() => toast.classList.add('show'), 100);
        
        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
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

    // Profile dropdown functionality
    function toggleProfile() {
        const profileDropdown = document.getElementById('profileDropdown');
        const notificationDropdown = document.getElementById('notificationDropdown');
        
        // Close notification dropdown if open
        if (notificationDropdown) {
            notificationDropdown.style.display = 'none';
        }
        
        // Toggle profile dropdown
        if (profileDropdown.classList.contains('hidden')) {
            profileDropdown.classList.remove('hidden');
            profileDropdown.style.display = 'block';
        } else {
            profileDropdown.classList.add('hidden');
            profileDropdown.style.display = 'none';
        }
    }

    // Close profile dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const profileDropdown = document.getElementById('profileDropdown');
        const profileButton = event.target.closest('.profile-dropdown-button');
        const profileContainer = event.target.closest('.profile-dropdown-container');
        
        if (profileDropdown && !profileContainer) {
            profileDropdown.classList.add('hidden');
            profileDropdown.style.display = 'none';
        }
    });

    // Close dropdown when pressing Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const profileDropdown = document.getElementById('profileDropdown');
            if (profileDropdown && !profileDropdown.classList.contains('hidden')) {
                profileDropdown.classList.add('hidden');
                profileDropdown.style.display = 'none';
            }
        }
    });
</script>
