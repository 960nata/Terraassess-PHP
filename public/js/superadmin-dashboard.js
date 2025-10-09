// Super Admin Dashboard JavaScript
console.log('Super Admin Dashboard JS loaded successfully');

// Sidebar functionality
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

// Initialize sidebar state
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    if (sidebar) {
        if (window.innerWidth > 1024) {
            // Desktop: sidebar terbuka by default
            sidebar.classList.remove('collapsed');
        } else {
            // Mobile: sidebar tertutup by default
            sidebar.classList.add('collapsed');
        }
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

// Dark mode functionality
function toggleDarkMode() {
    const html = document.documentElement;
    const icon = document.getElementById('darkModeIcon');
    const isDark = html.classList.contains('dark');
    
    if (isDark) {
        html.classList.remove('dark');
        if (icon) icon.className = 'fas fa-moon';
        localStorage.setItem('darkMode', 'false');
    } else {
        html.classList.add('dark');
        if (icon) icon.className = 'fas fa-sun';
        localStorage.setItem('darkMode', 'true');
    }
}

// Initialize dark mode
function initializeDarkMode() {
    const savedDarkMode = localStorage.getItem('darkMode');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const icon = document.getElementById('darkModeIcon');
    
    if (savedDarkMode === 'true' || (savedDarkMode === null && prefersDark)) {
        document.documentElement.classList.add('dark');
        if (icon) icon.className = 'fas fa-sun';
    } else {
        document.documentElement.classList.remove('dark');
        if (icon) icon.className = 'fas fa-moon';
    }
}

// Initialize dark mode on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeDarkMode();
});

// Profile dropdown functionality
function toggleProfile() {
    const profileDropdown = document.getElementById('profileDropdown');
    const notificationDropdown = document.getElementById('notificationDropdown');
    
    // Close notification dropdown if open
    if (notificationDropdown) {
        notificationDropdown.style.display = 'none';
    }
    
    // Toggle profile dropdown
    if (profileDropdown) {
        if (profileDropdown.classList.contains('hidden')) {
            profileDropdown.classList.remove('hidden');
            profileDropdown.style.display = 'block';
        } else {
            profileDropdown.classList.add('hidden');
            profileDropdown.style.display = 'none';
        }
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
