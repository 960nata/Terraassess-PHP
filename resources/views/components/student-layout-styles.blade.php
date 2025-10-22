<style>
    /* Header Styles */
    .header {
        background: rgba(15, 23, 42, 0.9);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 1rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .menu-toggle {
        background: none;
        border: none;
        color: white;
        font-size: 1.25rem;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 8px;
        transition: background-color 0.3s ease;
    }

    .menu-toggle:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .logo-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }

    .logo-text {
        color: white;
        font-size: 1.25rem;
        font-weight: 700;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    /* Notification Styles */
    .notification-container {
        position: relative;
    }

    .notification-btn {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        padding: 0.5rem;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .notification-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.3);
    }

    .notification-badge {
        background: #ef4444;
        color: white;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        top: -5px;
        right: -5px;
    }

    .notification-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        background: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        min-width: 320px;
        max-width: 400px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        z-index: 1000;
        margin-top: 0.5rem;
    }

    .notification-header {
        padding: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .notification-header h4 {
        margin: 0;
        color: white;
        font-size: 1rem;
        font-weight: 600;
    }

    .notification-actions {
        display: flex;
        gap: 0.5rem;
    }

    .mark-all-read-btn,
    .view-all-btn {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 6px;
        padding: 0.5rem;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 0.875rem;
        cursor: pointer;
    }

    .mark-all-read-btn:hover,
    .view-all-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.3);
    }

    .notification-list {
        max-height: 400px;
        overflow-y: auto;
    }

    .notification-loading,
    .notification-empty,
    .notification-error {
        padding: 2rem;
        text-align: center;
        color: rgba(255, 255, 255, 0.6);
    }

    .notification-loading i,
    .notification-empty i,
    .notification-error i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .notification-item {
        padding: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        display: flex;
        gap: 0.75rem;
        transition: background-color 0.3s ease;
    }

    .notification-item:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .notification-item.unread {
        background: rgba(59, 130, 246, 0.1);
    }

    .notification-icon {
        width: 32px;
        height: 32px;
        background: rgba(59, 130, 246, 0.2);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #3b82f6;
        font-size: 0.875rem;
    }

    .notification-content {
        flex: 1;
    }

    .notification-title {
        font-weight: 600;
        color: white;
        margin-bottom: 0.25rem;
        font-size: 0.875rem;
    }

    .notification-message {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.8rem;
        line-height: 1.4;
        margin-bottom: 0.25rem;
    }

    .notification-time {
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.75rem;
    }

    /* Profile Dropdown Styles */
    .user-profile-container {
        position: relative;
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .user-profile:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.3);
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        overflow: hidden;
    }

    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-info {
        display: flex;
        flex-direction: column;
    }

    .user-name {
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .user-role {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.75rem;
    }

    .profile-dropdown-arrow {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.75rem;
        transition: transform 0.3s ease;
    }

    .user-profile:hover .profile-dropdown-arrow {
        transform: rotate(180deg);
    }

    .profile-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        background: transparent;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        min-width: 200px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        z-index: 1000;
        margin-top: 0.5rem;
        overflow: hidden;
    }

    .profile-dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        color: white;
        text-decoration: none;
        transition: background-color 0.3s ease;
        font-size: 0.875rem;
    }

    .profile-dropdown-item:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .profile-dropdown-item.logout {
        color: #ef4444;
    }

    .profile-dropdown-item.logout:hover {
        background: rgba(239, 68, 68, 0.1);
    }

    .profile-dropdown-divider {
        height: 1px;
        background: rgba(255, 255, 255, 0.1);
        margin: 0.5rem 0;
    }

    /* Sidebar Styles */
    .sidebar {
        position: fixed;
        top: 0;
        left: -300px;
        width: 300px;
        height: 100vh;
        background: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(10px);
        border-right: 1px solid rgba(255, 255, 255, 0.1);
        z-index: 1001;
        transition: left 0.3s ease;
        overflow-y: auto;
    }

    .sidebar.open {
        left: 0;
    }

    .sidebar-menu {
        padding: 1.5rem 0;
    }

    .menu-section {
        margin-bottom: 2rem;
    }

    .menu-section-title {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 1rem;
        padding: 0 1.5rem;
    }

    .menu-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1.5rem;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }

    .menu-item:hover {
        background: rgba(255, 255, 255, 0.05);
        color: white;
    }

    .menu-item.active {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        border-left-color: #3b82f6;
    }

    .menu-item i {
        width: 20px;
        text-align: center;
    }

    .menu-item-text {
        font-weight: 500;
    }

    /* Mobile Overlay */
    .mobile-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        display: none;
    }

    .mobile-overlay.show {
        display: block;
    }

    /* Main Content Styles */
    .main-content {
        margin-left: 0;
        padding: 2rem;
        min-height: calc(100vh - 80px);
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    }

    .page-header {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .page-title {
        color: white;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-description {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.1rem;
        margin-bottom: 0;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .header {
            padding: 1rem;
        }
        
        .logo-text {
            display: none;
        }
        
        .profile-dropdown {
            min-width: 180px;
            right: -20px;
        }

        .notification-dropdown {
            min-width: 280px;
            right: -20px;
        }

        .main-content {
            padding: 1rem;
        }

        .page-header {
            padding: 1.5rem;
        }

        .page-title {
            font-size: 2rem;
        }
    }
</style>
