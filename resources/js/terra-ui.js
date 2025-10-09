/**
 * Terra Assessment UI Enhancement Script
 * Modern, accessible, and performant UI interactions
 */

class TerraUI {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initializeComponents();
        this.setupAccessibility();
        this.setupPerformanceOptimizations();
    }

    setupEventListeners() {
        // Handle form submissions with loading states
        document.addEventListener('submit', this.handleFormSubmission.bind(this));
        
        // Handle button clicks with loading states
        document.addEventListener('click', this.handleButtonClick.bind(this));
        
        // Handle keyboard navigation
        document.addEventListener('keydown', this.handleKeyboardNavigation.bind(this));
        
        // Handle window resize for responsive adjustments
        window.addEventListener('resize', this.debounce(this.handleWindowResize.bind(this), 250));
        
        // Handle scroll for performance optimizations
        window.addEventListener('scroll', this.throttle(this.handleScroll.bind(this), 100));
    }

    initializeComponents() {
        this.initializeTooltips();
        this.initializeModals();
        this.initializeDropdowns();
        this.initializeTabs();
        this.initializeAccordions();
        this.initializeProgressBars();
    }

    setupAccessibility() {
        // Add skip links
        this.addSkipLinks();
        
        // Improve focus management
        this.setupFocusManagement();
        
        // Add ARIA labels where needed
        this.enhanceARIA();
        
        // Setup screen reader announcements
        this.setupScreenReaderAnnouncements();
    }

    setupPerformanceOptimizations() {
        // Lazy load images
        this.setupLazyLoading();
        
        // Optimize animations
        this.optimizeAnimations();
        
        // Setup intersection observer for animations
        this.setupIntersectionObserver();
    }

    // Form handling
    handleFormSubmission(event) {
        const form = event.target;
        const submitButton = form.querySelector('button[type="submit"]');
        
        if (submitButton && !submitButton.disabled) {
            this.setButtonLoading(submitButton, true);
            
            // Re-enable button after 10 seconds as fallback
            setTimeout(() => {
                this.setButtonLoading(submitButton, false);
            }, 10000);
        }
    }

    handleButtonClick(event) {
        const button = event.target.closest('button[data-loading]');
        if (button) {
            this.setButtonLoading(button, true);
            
            // Re-enable after 5 seconds as fallback
            setTimeout(() => {
                this.setButtonLoading(button, false);
            }, 5000);
        }
    }

    setButtonLoading(button, loading) {
        if (loading) {
            button.disabled = true;
            button.dataset.originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Memproses...</span>';
        } else {
            button.disabled = false;
            if (button.dataset.originalText) {
                button.innerHTML = button.dataset.originalText;
                delete button.dataset.originalText;
            }
        }
    }

    // Keyboard navigation
    handleKeyboardNavigation(event) {
        // Handle Escape key
        if (event.key === 'Escape') {
            this.closeAllModals();
            this.closeAllDropdowns();
        }
        
        // Handle Tab navigation
        if (event.key === 'Tab') {
            this.handleTabNavigation(event);
        }
        
        // Handle Enter/Space on buttons
        if ((event.key === 'Enter' || event.key === ' ') && event.target.tagName === 'BUTTON') {
            event.preventDefault();
            event.target.click();
        }
    }

    handleTabNavigation(event) {
        const focusableElements = this.getFocusableElements();
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];
        
        if (event.shiftKey && document.activeElement === firstElement) {
            event.preventDefault();
            lastElement.focus();
        } else if (!event.shiftKey && document.activeElement === lastElement) {
            event.preventDefault();
            firstElement.focus();
        }
    }

    getFocusableElements() {
        return document.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
    }

    // Window resize handling
    handleWindowResize() {
        // Close mobile menus on desktop
        if (window.innerWidth > 1024) {
            this.closeMobileMenus();
        }
        
        // Adjust modal sizes
        this.adjustModalSizes();
    }

    handleScroll() {
        // Add scroll-based animations
        this.triggerScrollAnimations();
        
        // Update progress indicators
        this.updateScrollProgress();
    }

    // Component initialization
    initializeTooltips() {
        const tooltipElements = document.querySelectorAll('[data-tooltip]');
        tooltipElements.forEach(element => {
            this.createTooltip(element);
        });
    }

    createTooltip(element) {
        const tooltipText = element.dataset.tooltip;
        const tooltip = document.createElement('div');
        tooltip.className = 'terra-tooltip';
        tooltip.textContent = tooltipText;
        tooltip.setAttribute('role', 'tooltip');
        
        element.appendChild(tooltip);
        
        element.addEventListener('mouseenter', () => {
            tooltip.classList.add('show');
        });
        
        element.addEventListener('mouseleave', () => {
            tooltip.classList.remove('show');
        });
    }

    initializeModals() {
        const modalTriggers = document.querySelectorAll('[data-modal]');
        modalTriggers.forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                const modalId = trigger.dataset.modal;
                this.openModal(modalId);
            });
        });
        
        const modalCloses = document.querySelectorAll('[data-modal-close]');
        modalCloses.forEach(close => {
            close.addEventListener('click', () => {
                this.closeModal(close.closest('.terra-modal'));
            });
        });
    }

    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.setAttribute('aria-hidden', 'false');
            
            // Focus first focusable element
            const firstFocusable = modal.querySelector('button, input, select, textarea, [tabindex]:not([tabindex="-1"])');
            if (firstFocusable) {
                firstFocusable.focus();
            }
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        }
    }

    closeModal(modal) {
        if (modal) {
            modal.classList.add('hidden');
            modal.setAttribute('aria-hidden', 'true');
            
            // Restore body scroll
            document.body.style.overflow = '';
        }
    }

    closeAllModals() {
        const modals = document.querySelectorAll('.terra-modal');
        modals.forEach(modal => this.closeModal(modal));
    }

    initializeDropdowns() {
        const dropdownTriggers = document.querySelectorAll('[data-dropdown]');
        dropdownTriggers.forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                const dropdownId = trigger.dataset.dropdown;
                this.toggleDropdown(dropdownId);
            });
        });
    }

    toggleDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        if (dropdown) {
            const isOpen = !dropdown.classList.contains('hidden');
            
            // Close all other dropdowns
            this.closeAllDropdowns();
            
            if (!isOpen) {
                dropdown.classList.remove('hidden');
                dropdown.setAttribute('aria-expanded', 'true');
            }
        }
    }

    closeAllDropdowns() {
        const dropdowns = document.querySelectorAll('[id*="dropdown"]');
        dropdowns.forEach(dropdown => {
            dropdown.classList.add('hidden');
            dropdown.setAttribute('aria-expanded', 'false');
        });
    }

    initializeTabs() {
        const tabContainers = document.querySelectorAll('.terra-tabs');
        tabContainers.forEach(container => {
            const tabs = container.querySelectorAll('[data-tab]');
            const panels = container.querySelectorAll('[data-tab-panel]');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const targetPanel = tab.dataset.tab;
                    
                    // Update tab states
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    
                    // Update panel states
                    panels.forEach(p => p.classList.add('hidden'));
                    const activePanel = container.querySelector(`[data-tab-panel="${targetPanel}"]`);
                    if (activePanel) {
                        activePanel.classList.remove('hidden');
                    }
                });
            });
        });
    }

    initializeAccordions() {
        const accordionTriggers = document.querySelectorAll('[data-accordion]');
        accordionTriggers.forEach(trigger => {
            trigger.addEventListener('click', () => {
                const targetId = trigger.dataset.accordion;
                const target = document.getElementById(targetId);
                
                if (target) {
                    const isOpen = !target.classList.contains('hidden');
                    
                    if (isOpen) {
                        target.classList.add('hidden');
                        trigger.setAttribute('aria-expanded', 'false');
                    } else {
                        target.classList.remove('hidden');
                        trigger.setAttribute('aria-expanded', 'true');
                    }
                }
            });
        });
    }

    initializeProgressBars() {
        const progressBars = document.querySelectorAll('.terra-progress-bar');
        progressBars.forEach(bar => {
            const value = bar.dataset.value || 0;
            const max = bar.dataset.max || 100;
            const percentage = (value / max) * 100;
            
            bar.style.width = `${percentage}%`;
        });
    }

    // Accessibility enhancements
    addSkipLinks() {
        const skipLink = document.createElement('a');
        skipLink.href = '#main-content';
        skipLink.textContent = 'Skip to main content';
        skipLink.className = 'terra-skip-link';
        document.body.insertBefore(skipLink, document.body.firstChild);
    }

    setupFocusManagement() {
        // Add focus indicators
        document.addEventListener('focusin', (e) => {
            e.target.classList.add('focus-visible');
        });
        
        document.addEventListener('focusout', (e) => {
            e.target.classList.remove('focus-visible');
        });
    }

    enhanceARIA() {
        // Add ARIA labels to buttons without text
        const iconButtons = document.querySelectorAll('button:not([aria-label]):not([aria-labelledby])');
        iconButtons.forEach(button => {
            const icon = button.querySelector('i');
            if (icon && !button.textContent.trim()) {
                const iconClass = icon.className;
                const label = this.getIconLabel(iconClass);
                if (label) {
                    button.setAttribute('aria-label', label);
                }
            }
        });
    }

    getIconLabel(iconClass) {
        const iconLabels = {
            'fa-bars': 'Open menu',
            'fa-times': 'Close',
            'fa-search': 'Search',
            'fa-user': 'User menu',
            'fa-bell': 'Notifications',
            'fa-cog': 'Settings',
            'fa-edit': 'Edit',
            'fa-trash': 'Delete',
            'fa-plus': 'Add',
            'fa-minus': 'Remove',
            'fa-arrow-left': 'Go back',
            'fa-arrow-right': 'Go forward',
            'fa-chevron-up': 'Expand',
            'fa-chevron-down': 'Collapse'
        };
        
        for (const [icon, label] of Object.entries(iconLabels)) {
            if (iconClass.includes(icon)) {
                return label;
            }
        }
        
        return null;
    }

    setupScreenReaderAnnouncements() {
        // Create announcement region
        const announcement = document.createElement('div');
        announcement.id = 'terra-announcements';
        announcement.setAttribute('aria-live', 'polite');
        announcement.setAttribute('aria-atomic', 'true');
        announcement.className = 'sr-only';
        document.body.appendChild(announcement);
    }

    announce(message) {
        const announcement = document.getElementById('terra-announcements');
        if (announcement) {
            announcement.textContent = message;
        }
    }

    // Performance optimizations
    setupLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            const lazyImages = document.querySelectorAll('img[data-src]');
            lazyImages.forEach(img => imageObserver.observe(img));
        }
    }

    optimizeAnimations() {
        // Reduce animations for users who prefer reduced motion
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            document.documentElement.style.setProperty('--transition-fast', '0ms');
            document.documentElement.style.setProperty('--transition-normal', '0ms');
            document.documentElement.style.setProperty('--transition-slow', '0ms');
        }
    }

    setupIntersectionObserver() {
        if ('IntersectionObserver' in window) {
            const animationObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-in');
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });
            
            const animatedElements = document.querySelectorAll('.terra-animate-on-scroll');
            animatedElements.forEach(el => animationObserver.observe(el));
        }
    }

    triggerScrollAnimations() {
        const elements = document.querySelectorAll('.terra-animate-on-scroll');
        elements.forEach(element => {
            const rect = element.getBoundingClientRect();
            const isVisible = rect.top < window.innerHeight && rect.bottom > 0;
            
            if (isVisible) {
                element.classList.add('animate-in');
            }
        });
    }

    updateScrollProgress() {
        const progressBars = document.querySelectorAll('.terra-scroll-progress');
        progressBars.forEach(bar => {
            const scrollTop = window.pageYOffset;
            const docHeight = document.body.scrollHeight - window.innerHeight;
            const scrollPercent = (scrollTop / docHeight) * 100;
            bar.style.width = `${scrollPercent}%`;
        });
    }

    // Utility functions
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

    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    closeMobileMenus() {
        const mobileMenus = document.querySelectorAll('.mobile-menu');
        mobileMenus.forEach(menu => {
            menu.classList.remove('open');
        });
    }

    adjustModalSizes() {
        const modals = document.querySelectorAll('.terra-modal');
        modals.forEach(modal => {
            if (window.innerWidth < 768) {
                modal.classList.add('mobile-modal');
            } else {
                modal.classList.remove('mobile-modal');
            }
        });
    }
}

// Initialize Terra UI when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new TerraUI();
});

// Export for use in other scripts
window.TerraUI = TerraUI;

