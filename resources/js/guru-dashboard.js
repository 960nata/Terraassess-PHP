// Guru Dashboard JavaScript
// Same functionality as Super Admin Dashboard

class GuruDashboard {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initializeComponents();
        this.setupSearchAndFilter();
        this.setupModals();
        this.setupDataTables();
    }

    setupEventListeners() {
        // Sidebar toggle
        const sidebarToggle = document.querySelector('.menu-toggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', this.toggleSidebar.bind(this));
        }

        // Notification bell
        const notificationBell = document.querySelector('.notification-icon');
        if (notificationBell) {
            notificationBell.addEventListener('click', this.showNotifications.bind(this));
        }

        // User profile dropdown
        const userProfile = document.querySelector('.user-profile');
        if (userProfile) {
            userProfile.addEventListener('click', this.toggleUserMenu.bind(this));
        }

        // Close modals on outside click
        document.addEventListener('click', this.handleOutsideClick.bind(this));

        // Keyboard shortcuts
        document.addEventListener('keydown', this.handleKeyboardShortcuts.bind(this));
    }

    initializeComponents() {
        // Initialize tooltips
        this.initTooltips();
        
        // Initialize charts if any
        this.initCharts();
        
        // Initialize animations
        this.initAnimations();
    }

    setupSearchAndFilter() {
        // Search functionality
        const searchInputs = document.querySelectorAll('.search-input');
        searchInputs.forEach(input => {
            input.addEventListener('input', this.debounce(this.handleSearch.bind(this), 300));
        });

        // Filter functionality
        const filterSelects = document.querySelectorAll('.filter-select');
        filterSelects.forEach(select => {
            select.addEventListener('change', this.handleFilterChange.bind(this));
        });

        // Clear filters
        const clearFiltersBtn = document.querySelector('.clear-all-filters');
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', this.clearAllFilters.bind(this));
        }
    }

    setupModals() {
        // Modal open/close functionality
        window.openModal = this.openModal.bind(this);
        window.closeModal = this.closeModal.bind(this);
        
        // Modal form submissions
        const modalForms = document.querySelectorAll('.modal form');
        modalForms.forEach(form => {
            form.addEventListener('submit', this.handleModalFormSubmit.bind(this));
        });
    }

    setupDataTables() {
        // Data table interactions
        const dataTables = document.querySelectorAll('.data-table');
        dataTables.forEach(table => {
            this.setupDataTable(table);
        });
    }

    // Sidebar Methods
    toggleSidebar() {
        const sidebar = document.getElementById('modernSidebar');
        const mainContent = document.getElementById('mainContent');
        
        if (sidebar && mainContent) {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
            // Save state to localStorage
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        }
    }

    // Notification Methods
    showNotifications() {
        // Implementation for showing notifications
        console.log('Showing notifications');
        this.showToast('Notifikasi', 'Fitur notifikasi akan segera tersedia', 'info');
    }

    // User Menu Methods
    toggleUserMenu() {
        // Implementation for user menu dropdown
        console.log('Toggling user menu');
    }

    // Search and Filter Methods
    handleSearch(event) {
        const searchValue = event.target.value;
        const searchParam = new URLSearchParams(window.location.search);
        
        if (searchValue) {
            searchParam.set('search', searchValue);
        } else {
            searchParam.delete('search');
        }
        
        // Update URL without page reload
        const newUrl = window.location.pathname + '?' + searchParam.toString();
        window.history.pushState({}, '', newUrl);
        
        // Perform search
        this.performSearch(searchValue);
    }

    handleFilterChange(event) {
        const filterName = event.target.name;
        const filterValue = event.target.value;
        const searchParam = new URLSearchParams(window.location.search);
        
        if (filterValue) {
            searchParam.set(filterName, filterValue);
        } else {
            searchParam.delete(filterName);
        }
        
        // Update URL without page reload
        const newUrl = window.location.pathname + '?' + searchParam.toString();
        window.history.pushState({}, '', newUrl);
        
        // Perform filter
        this.performFilter(filterName, filterValue);
    }

    clearAllFilters() {
        const newUrl = window.location.pathname;
        window.history.pushState({}, '', newUrl);
        window.location.reload();
    }

    performSearch(searchValue) {
        // Implementation for search functionality
        console.log('Searching for:', searchValue);
        this.showLoading();
        
        // Simulate API call
        setTimeout(() => {
            this.hideLoading();
            this.showToast('Pencarian', `Hasil pencarian untuk "${searchValue}"`, 'success');
        }, 1000);
    }

    performFilter(filterName, filterValue) {
        // Implementation for filter functionality
        console.log('Filtering by:', filterName, '=', filterValue);
        this.showLoading();
        
        // Simulate API call
        setTimeout(() => {
            this.hideLoading();
            this.showToast('Filter', `Filter ${filterName} diterapkan`, 'success');
        }, 1000);
    }

    // Modal Methods
    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
            
            // Focus first input
            const firstInput = modal.querySelector('input, textarea, select');
            if (firstInput) {
                setTimeout(() => firstInput.focus(), 100);
            }
        }
    }

    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = 'auto';
        }
    }

    handleModalFormSubmit(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        
        // Show loading
        this.showLoading();
        
        // Simulate form submission
        setTimeout(() => {
            this.hideLoading();
            this.showToast('Berhasil', 'Data berhasil disimpan', 'success');
            this.closeModal();
            form.reset();
        }, 2000);
    }

    // Data Table Methods
    setupDataTable(table) {
        // Add sorting functionality
        const headers = table.querySelectorAll('th[data-sortable]');
        headers.forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', () => this.sortTable(table, header));
        });
    }

    sortTable(table, header) {
        const column = Array.from(header.parentNode.children).indexOf(header);
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        const isAscending = header.classList.contains('sort-asc');
        
        // Remove all sort classes
        table.querySelectorAll('th').forEach(th => {
            th.classList.remove('sort-asc', 'sort-desc');
        });
        
        // Add appropriate sort class
        header.classList.add(isAscending ? 'sort-desc' : 'sort-asc');
        
        // Sort rows
        rows.sort((a, b) => {
            const aText = a.cells[column].textContent.trim();
            const bText = b.cells[column].textContent.trim();
            
            if (isAscending) {
                return bText.localeCompare(aText);
            } else {
                return aText.localeCompare(bText);
            }
        });
        
        // Reorder rows in DOM
        rows.forEach(row => tbody.appendChild(row));
    }

    // Utility Methods
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    showLoading() {
        const loadingElement = document.querySelector('.loading-overlay');
        if (loadingElement) {
            loadingElement.style.display = 'flex';
        }
    }

    hideLoading() {
        const loadingElement = document.querySelector('.loading-overlay');
        if (loadingElement) {
            loadingElement.style.display = 'none';
        }
    }

    showToast(title, message, type = 'info') {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="toast-header">
                <strong>${title}</strong>
                <button class="toast-close" onclick="this.parentElement.parentElement.remove()">×</button>
            </div>
            <div class="toast-body">${message}</div>
        `;
        
        // Add to page
        document.body.appendChild(toast);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 5000);
    }

    handleOutsideClick(event) {
        // Close modals when clicking outside
        const modals = document.querySelectorAll('.modal-overlay');
        modals.forEach(modal => {
            if (modal.contains(event.target) && event.target === modal) {
                this.closeModal();
            }
        });
    }

    handleKeyboardShortcuts(event) {
        // Escape key to close modals
        if (event.key === 'Escape') {
            this.closeModal();
        }
        
        // Ctrl/Cmd + K for search
        if ((event.ctrlKey || event.metaKey) && event.key === 'k') {
            event.preventDefault();
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.focus();
            }
        }
    }

    initTooltips() {
        // Initialize tooltips if using a tooltip library
        console.log('Initializing tooltips');
    }

    initCharts() {
        // Initialize charts if using a chart library
        console.log('Initializing charts');
    }

    initAnimations() {
        // Initialize animations
        const animatedElements = document.querySelectorAll('.fade-in, .slide-in');
        animatedElements.forEach(element => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                element.style.transition = 'all 0.3s ease';
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, 100);
        });
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new GuruDashboard();
});

// Global functions for backward compatibility
window.openModal = (modalId) => {
    const dashboard = new GuruDashboard();
    dashboard.openModal(modalId);
};

window.closeModal = (modalId) => {
    const dashboard = new GuruDashboard();
    dashboard.closeModal(modalId);
};

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = GuruDashboard;
}
