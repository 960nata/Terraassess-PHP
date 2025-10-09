/**
 * Responsive Sidebar System
 * Handles sidebar toggle, collapse, and responsive behavior
 */
class ResponsiveSidebar {
    constructor() {
        this.sidebar = null;
        this.overlay = null;
        this.toggleBtn = null;
        this.mainContent = null;
        this.isCollapsed = false;
        this.isMobile = false;
        this.isHidden = false;
        
        this.init();
    }

    init() {
        this.sidebar = document.querySelector('.sidebar');
        this.overlay = document.querySelector('.sidebar-overlay');
        this.toggleBtn = document.querySelector('.sidebar-toggle');
        this.mainContent = document.querySelector('.main-content');
        
        if (!this.sidebar) {
            console.warn('Sidebar element not found');
            return;
        }

        this.setupEventListeners();
        this.handleResize();
        this.loadSidebarState();
    }

    setupEventListeners() {
        // Toggle button click
        if (this.toggleBtn) {
            this.toggleBtn.addEventListener('click', () => {
                this.toggleSidebar();
            });
        }

        // Overlay click (mobile)
        if (this.overlay) {
            this.overlay.addEventListener('click', () => {
                this.hideSidebar();
            });
        }

        // Window resize
        window.addEventListener('resize', () => {
            this.handleResize();
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey && e.key === 'b') {
                e.preventDefault();
                this.toggleSidebar();
            }
        });

        // Navigation item clicks
        this.setupNavigationListeners();
    }

    setupNavigationListeners() {
        const navItems = this.sidebar.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            item.addEventListener('click', (e) => {
                // Remove active class from all items
                navItems.forEach(nav => nav.classList.remove('active'));
                
                // Add active class to clicked item
                item.classList.add('active');
                
                // On mobile, hide sidebar after navigation
                if (this.isMobile) {
                    setTimeout(() => {
                        this.hideSidebar();
                    }, 300);
                }
            });
        });
    }

    toggleSidebar() {
        if (this.isMobile) {
            this.toggleMobileSidebar();
        } else {
            this.toggleDesktopSidebar();
        }
    }

    toggleDesktopSidebar() {
        this.isCollapsed = !this.isCollapsed;
        
        if (this.isCollapsed) {
            this.collapseSidebar();
        } else {
            this.expandSidebar();
        }
        
        this.saveSidebarState();
    }

    toggleMobileSidebar() {
        if (this.isHidden) {
            this.showSidebar();
        } else {
            this.hideSidebar();
        }
    }

    collapseSidebar() {
        this.sidebar.classList.add('collapsed');
        if (this.mainContent) {
            this.mainContent.classList.add('sidebar-collapsed');
        }
        this.isCollapsed = true;
    }

    expandSidebar() {
        this.sidebar.classList.remove('collapsed');
        if (this.mainContent) {
            this.mainContent.classList.remove('sidebar-collapsed');
        }
        this.isCollapsed = false;
    }

    showSidebar() {
        this.sidebar.classList.remove('hidden');
        this.sidebar.classList.add('mobile-open');
        if (this.overlay) {
            this.overlay.classList.add('active');
        }
        this.isHidden = false;
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }

    hideSidebar() {
        this.sidebar.classList.add('hidden');
        this.sidebar.classList.remove('mobile-open');
        if (this.overlay) {
            this.overlay.classList.remove('active');
        }
        this.isHidden = true;
        
        // Restore body scroll
        document.body.style.overflow = '';
    }

    handleResize() {
        const wasMobile = this.isMobile;
        this.isMobile = window.innerWidth <= 768;
        
        if (wasMobile !== this.isMobile) {
            this.handleBreakpointChange();
        }
    }

    handleBreakpointChange() {
        if (this.isMobile) {
            // Switch to mobile mode
            this.sidebar.classList.remove('collapsed');
            this.hideSidebar();
            if (this.mainContent) {
                this.mainContent.classList.remove('sidebar-collapsed');
            }
        } else {
            // Switch to desktop mode
            this.sidebar.classList.remove('hidden', 'mobile-open');
            if (this.overlay) {
                this.overlay.classList.remove('active');
            }
            document.body.style.overflow = '';
            
            // Restore collapsed state if it was collapsed
            if (this.isCollapsed) {
                this.collapseSidebar();
            }
        }
    }

    loadSidebarState() {
        const savedState = localStorage.getItem('sidebarState');
        if (savedState) {
            const state = JSON.parse(savedState);
            this.isCollapsed = state.collapsed || false;
            
            if (!this.isMobile && this.isCollapsed) {
                this.collapseSidebar();
            }
        }
    }

    saveSidebarState() {
        const state = {
            collapsed: this.isCollapsed,
            timestamp: Date.now()
        };
        localStorage.setItem('sidebarState', JSON.stringify(state));
    }

    // Public methods for external control
    show() {
        if (this.isMobile) {
            this.showSidebar();
        } else {
            this.expandSidebar();
        }
    }

    hide() {
        if (this.isMobile) {
            this.hideSidebar();
        } else {
            this.collapseSidebar();
        }
    }

    isVisible() {
        if (this.isMobile) {
            return !this.isHidden;
        } else {
            return !this.isCollapsed;
        }
    }

    // Method to update user info
    updateUserInfo(userData) {
        const userName = this.sidebar.querySelector('.sidebar-user-name');
        const userRole = this.sidebar.querySelector('.sidebar-user-role');
        const userAvatar = this.sidebar.querySelector('.sidebar-user-avatar');
        
        if (userName && userData.name) {
            userName.textContent = userData.name;
        }
        
        if (userRole && userData.role) {
            userRole.textContent = userData.role;
        }
        
        if (userAvatar && userData.avatar) {
            userAvatar.textContent = userData.avatar;
        }
    }

    // Method to add navigation items dynamically
    addNavItem(itemData) {
        const navContainer = this.sidebar.querySelector('.sidebar-nav');
        if (!navContainer) return;

        const navItem = document.createElement('a');
        navItem.className = 'nav-item';
        navItem.href = itemData.href || '#';
        
        navItem.innerHTML = `
            <div class="nav-item-icon">
                <i class="${itemData.icon}"></i>
            </div>
            <span class="nav-item-text">${itemData.text}</span>
            <div class="nav-item-tooltip">${itemData.text}</div>
        `;

        navContainer.appendChild(navItem);
        this.setupNavigationListeners();
    }

    // Method to set active navigation item
    setActiveNavItem(href) {
        const navItems = this.sidebar.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            item.classList.remove('active');
            if (item.getAttribute('href') === href) {
                item.classList.add('active');
            }
        });
    }
}

// Initialize sidebar when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.sidebar = new ResponsiveSidebar();
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ResponsiveSidebar;
}
