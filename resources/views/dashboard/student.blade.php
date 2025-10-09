@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - Student Dashboard')

@section('additional-styles')
    <style>
        /* Notification Toast Styles */
        .notification-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 12px 16px;
            color: white;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            z-index: 9999;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
        }

        .notification-toast.show {
            opacity: 1;
            transform: translateX(0);
        }

        .notification-toast.success {
            border-left: 4px solid #22c55e;
        }

        .notification-toast.error {
            border-left: 4px solid #ef4444;
        }

        .notification-toast.info {
            border-left: 4px solid #3b82f6;
        }

        /* Notification Actions */
        .notification-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .mark-all-read-btn {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #3b82f6;
            padding: 6px 8px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .mark-all-read-btn:hover {
            background: rgba(59, 130, 246, 0.2);
            border-color: rgba(59, 130, 246, 0.5);
            color: #60a5fa;
        }

        .view-all-btn {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #22c55e;
            padding: 6px 8px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .view-all-btn:hover {
            background: rgba(34, 197, 94, 0.2);
            border-color: rgba(34, 197, 94, 0.5);
            color: #16a34a;
        }

        /* Dashboard Grid Layout */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 3rem;
        }

        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 0.875rem;
            }
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: repeat(2, 1fr);
                grid-template-rows: repeat(6, 1fr);
                gap: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            .dashboard-grid {
                grid-template-columns: repeat(2, 1fr);
                grid-template-rows: repeat(6, 1fr);
                gap: 0.5rem;
            }
        }

        /* Card Styling for 4x3 Layout */
        .card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            min-height: 160px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-decoration: none;
            color: inherit;
        }

        .card:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(59, 130, 246, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .card-icon.blue {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
        }

        .card-icon.green {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .card-icon.purple {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
        }

        .card-icon.orange {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .card-icon.red {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: white;
            margin-bottom: 0.5rem;
        }

        .card-description {
            color: #94a3b8;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        /* Mobile Card Adjustments */
        @media (max-width: 768px) {
            .card {
                min-height: 140px;
                padding: 1rem;
            }

            .card-icon {
                width: 40px;
                height: 40px;
                font-size: 1.25rem;
                margin-bottom: 0.75rem;
            }

            .card-title {
                font-size: 1rem;
            }

            .card-description {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .card {
                min-height: 130px;
                padding: 0.75rem;
            }

            .card-icon {
                width: 32px;
                height: 32px;
                font-size: 1rem;
                margin-bottom: 0.5rem;
            }

            .card-title {
                font-size: 0.85rem;
                line-height: 1.3;
            }

            .card-description {
                font-size: 0.7rem;
                line-height: 1.4;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-graduation-cap"></i>
            Student Dashboard
        </h1>
        <p class="page-description">Kelola sistem Terra Assessment dengan akses student</p>
    </div>

        <div class="welcome-banner">
            <div class="welcome-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="welcome-content">
                <h2 class="welcome-title">Selamat datang, {{ $user->name ?? 'Student' }}!</h2>
                <p class="welcome-description">Sebagai Student, Anda memiliki akses untuk mengelola sistem Terra Assessment.</p>
            </div>
        </div>

        <div class="dashboard-grid">
            <!-- Row 1 -->

            <a href="{{ route('student.tasks') }}" class="card">
                <div class="card-icon blue">
                    <i class="fas fa-tasks"></i>
                </div>
                <h3 class="card-title">Tugas Saya</h3>
                <p class="card-description">Lihat dan kerjakan tugas yang diberikan</p>
            </a>

            <!-- Row 2 -->
            <a href="{{ route('student.exams') }}" class="card">
                <div class="card-icon green">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h3 class="card-title">Ujian Saya</h3>
                <p class="card-description">Ikuti ujian yang telah dijadwalkan</p>
            </a>

            <a href="{{ route('student.materials') }}" class="card">
                <div class="card-icon purple">
                    <i class="fas fa-book"></i>
                </div>
                <h3 class="card-title">Materi Saya</h3>
                <p class="card-description">Akses materi pembelajaran kelas</p>
            </a>

            <!-- Row 3 -->
            <a href="{{ route('student.iot') }}" class="card">
                <div class="card-icon orange">
                    <i class="fas fa-microchip"></i>
                </div>
                <h3 class="card-title">IoT Projects</h3>
                <p class="card-description">Lakukan penelitian menggunakan perangkat IoT</p>
            </a>

            <a href="{{ route('student.class-management') }}" class="card">
                <div class="card-icon blue">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="card-title">Kelas Saya</h3>
                <p class="card-description">Lihat informasi kelas dan teman sekelas</p>
            </a>

            <!-- Row 4 -->
            <a href="{{ route('student.grades') }}" class="card">
                <div class="card-icon green">
                    <i class="fas fa-star"></i>
                </div>
                <h3 class="card-title">Nilai Saya</h3>
                <p class="card-description">Lihat nilai tugas dan ujian</p>
            </a>


        </div>

        <div class="system-info">
            <div class="info-section">
                <h3 class="info-title">Hak Akses Siswa</h3>
                <ul class="info-list">
                    <li>Mengakses materi pembelajaran</li>
                    <li>Mengerjakan tugas dan ujian</li>
                    <li>Melakukan penelitian IoT</li>
                    <li>Melihat nilai dan progress</li>
                </ul>
            </div>

            <div class="info-section">
                <h3 class="info-title">Tanggung Jawab</h3>
                <ul class="info-list">
                    <li>Mengerjakan tugas tepat waktu</li>
                    <li>Mengikuti ujian sesuai jadwal</li>
                    <li>Berpartisipasi dalam pembelajaran</li>
                    <li>Menjaga keamanan akun</li>
                </ul>
            </div>
        </div>
@endsection

@section('scripts')
    <script>
        // Toggle sidebar for mobile - using the same logic as external JS
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');
            const mainContent = document.querySelector('.main-content');
            
            if (window.innerWidth <= 1024) {
                // Mobile behavior
                sidebar.classList.toggle('collapsed');
                mobileOverlay.classList.toggle('active');
                mainContent.classList.toggle('sidebar-open');
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
            mobileOverlay.classList.remove('active');
            mainContent.classList.remove('sidebar-open');
        }

        // Notification functionality
        let notificationDropdown = null;
        let notificationBadge = null;
        let notificationList = null;
        let isDropdownOpen = false;

        document.addEventListener('DOMContentLoaded', function() {
            notificationDropdown = document.getElementById('notificationDropdown');
            notificationBadge = document.getElementById('notificationBadge');
            notificationList = document.getElementById('notificationList');
            
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
            if (!notificationList) return;
            
            fetch('/api/notifications/latest')
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        notificationList.innerHTML = `
                            <div class="notification-loading">
                                <i class="ph-bell-slash"></i>
                                <span>Tidak ada notifikasi</span>
                            </div>
                        `;
                    } else {
                        notificationList.innerHTML = data.map(notification => `
                            <div class="notification-item ${!notification.is_read ? 'unread' : ''}" 
                                 onclick="markAsRead(${notification.id})">
                                <div class="notification-icon-small">
                                    <i class="ph-${getNotificationIcon(notification.type)}"></i>
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
                            <i class="ph-warning"></i>
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
            fetch('/api/notifications/unread-count')
                .then(response => response.json())
                .then(data => {
                    if (notificationBadge) {
                        if (data.count > 0) {
                            notificationBadge.textContent = data.count;
                            notificationBadge.style.display = 'flex';
                        } else {
                            notificationBadge.style.display = 'none';
                        }
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
                    
                    // Hide notification badge
                    if (notificationBadge) {
                        notificationBadge.style.display = 'none';
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
                <i class="ph-${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info'}"></i>
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

        // Toggle profile dropdown
        // Profile dropdown removed - using direct buttons now

        // Close notification dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const notificationContainer = document.querySelector('.notification-container');
            
            if (!notificationContainer.contains(event.target)) {
                document.getElementById('notificationDropdown').classList.remove('active');
            }
        });

        // Mobile responsive behavior
        function handleMobileDropdowns() {
            const notificationDropdown = document.getElementById('notificationDropdown');
            const profileDropdown = document.getElementById('profileDropdown');
            
            if (window.innerWidth <= 768) {
                // On mobile, make dropdowns full-width and slide from right
                if (notificationDropdown.classList.contains('active')) {
                    notificationDropdown.classList.add('mobile-dropdown');
                }
                if (profileDropdown.classList.contains('active')) {
                    profileDropdown.classList.add('mobile-dropdown');
                }
            } else {
                // On desktop, remove mobile classes and reset positioning
                notificationDropdown.classList.remove('mobile-dropdown');
                profileDropdown.classList.remove('mobile-dropdown');
            }
        }

        // Initialize mobile behavior
        handleMobileDropdowns();
        window.addEventListener('resize', handleMobileDropdowns);
    </script>
@endsection