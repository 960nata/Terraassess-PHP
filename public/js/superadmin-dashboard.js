// Super Admin Dashboard JavaScript

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mobileOverlay = document.getElementById('mobileOverlay');
    const mainContent = document.querySelector('.main-content');
    
    if (window.innerWidth <= 1024) {
        // Mobile behavior - toggle sidebar visibility
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
    
    if (sidebar) sidebar.classList.add('collapsed');
    if (mobileOverlay) mobileOverlay.classList.remove('active');
    if (mainContent) mainContent.classList.remove('sidebar-open');
}

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

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const mobileOverlay = document.getElementById('mobileOverlay');
    const mainContent = document.querySelector('.main-content');
    
    if (window.innerWidth <= 1024) {
        // Mobile - hide sidebar by default
        if (sidebar) sidebar.classList.add('collapsed');
        if (mobileOverlay) mobileOverlay.classList.remove('active');
        if (mainContent) mainContent.classList.remove('sidebar-open');
    } else {
        // Desktop - show sidebar by default
        if (sidebar) sidebar.classList.remove('collapsed');
        if (mobileOverlay) mobileOverlay.classList.remove('active');
        if (mainContent) mainContent.classList.remove('sidebar-open');
    }
});

// Card click handlers
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card');
    
    cards.forEach(card => {
        card.addEventListener('click', function() {
            const cardTitle = this.querySelector('.card-title').textContent;
            
            // Add visual feedback
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
            
            // Here you can add navigation logic based on card title
            console.log('Card clicked:', cardTitle);
            
            // Example navigation (you can customize this based on your routes)
            switch(cardTitle) {
                case 'Push Notifikasi':
                    // Navigate to notifications
                    break;
                case 'Manajemen Tugas':
                    // Navigate to task management
                    break;
                case 'Manajemen Pengguna':
                    // Navigate to user management
                    break;
                case 'Manajemen Mata Pelajaran':
                    // Navigate to subject management
                    break;
                case 'Ujian':
                    // Navigate to exams
                    break;
                case 'Penelitian IoT':
                    // Navigate to IoT research
                    break;
                case 'Materi':
                    // Navigate to materials
                    break;
                case 'Manajemen IoT':
                    // Navigate to IoT management
                    break;
                case 'Manajemen Ujian':
                    // Navigate to exam management
                    break;
                case 'Manajemen Kelas':
                    // Navigate to class management
                    break;
                case 'Tugas':
                    // Navigate to tasks
                    break;
                case 'Tugas IoT':
                    // Navigate to IoT tasks
                    break;
                case 'Notifikasi':
                    // Navigate to notifications
                    break;
            }
        });
    });
});

// Notification badge click handler
document.addEventListener('DOMContentLoaded', function() {
    const notificationIcon = document.querySelector('.notification-icon');
    
    if (notificationIcon) {
        notificationIcon.addEventListener('click', function() {
            // Add notification panel toggle logic here
            console.log('Notification icon clicked');
        });
    }
});

// User profile dropdown handler
document.addEventListener('DOMContentLoaded', function() {
    const userProfile = document.querySelector('.user-profile');
    
    if (userProfile) {
        userProfile.addEventListener('click', function() {
            // Add user profile dropdown logic here
            console.log('User profile clicked');
        });
    }
});
