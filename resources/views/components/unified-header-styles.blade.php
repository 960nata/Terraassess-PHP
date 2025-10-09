<style>
    /* Modern Profile Dropdown Styles */
    .profile-dropdown-container {
        position: relative;
    }

    .profile-dropdown-button {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 0.75rem;
        background: rgba(30, 41, 59, 0.3);
        border: 1px solid rgba(71, 85, 105, 0.2);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
    }

    .profile-dropdown-button:hover {
        background: rgba(51, 65, 85, 0.9);
        border-color: rgba(71, 85, 105, 0.5);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        transform: translateY(-1px);
    }

    .profile-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
        color: white;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .profile-info {
        display: flex;
        flex-direction: column;
        text-align: left;
    }

    .profile-name {
        font-weight: 600;
        font-size: 0.875rem;
        color: #f8fafc;
        line-height: 1.2;
    }

    .profile-role {
        font-size: 0.75rem;
        color: #cbd5e1;
        line-height: 1.2;
    }

    .profile-arrow {
        color: #cbd5e1;
        font-size: 0.75rem;
        transition: transform 0.3s ease;
    }

    .profile-dropdown-button:hover .profile-arrow {
        transform: rotate(180deg);
    }

    .profile-dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        margin-top: 0.5rem;
        min-width: 280px;
        max-width: 320px;
        width: auto;
        background: rgba(30, 41, 59, 0.95);
        border: 1px solid rgba(71, 85, 105, 0.3);
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        z-index: 1000;
        overflow: hidden;
        backdrop-filter: blur(20px);
    }

    .profile-dropdown-header {
        padding: 1rem;
        border-bottom: 1px solid rgba(71, 85, 105, 0.2);
        background: rgba(51, 65, 85, 0.8);
    }

    .profile-dropdown-user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .profile-dropdown-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
        color: white;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .profile-dropdown-details h6 {
        margin: 0 0 0.25rem 0;
        color: #f8fafc;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .profile-dropdown-details span {
        font-size: 0.75rem;
        color: #cbd5e1;
        word-break: break-all;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
    }

    .profile-dropdown-items {
        padding: 0.5rem 0;
    }

    .profile-dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        color: #e2e8f0;
        text-decoration: none;
        transition: all 0.2s ease;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .profile-dropdown-item:hover {
        background: rgba(71, 85, 105, 0.3);
        color: #f8fafc;
        text-decoration: none;
        padding-left: 1.25rem;
    }

    .profile-dropdown-item i {
        width: 16px;
        text-align: center;
        color: #94a3b8;
    }

    .profile-dropdown-item:hover i {
        color: #f8fafc;
    }

    .profile-dropdown-item.logout {
        color: #f87171;
    }

    .profile-dropdown-item.logout:hover {
        background: rgba(239, 68, 68, 0.2);
        color: #fca5a5;
    }

    .profile-dropdown-item.logout i {
        color: #f87171;
    }

    .profile-dropdown-divider {
        height: 1px;
        background: rgba(71, 85, 105, 0.3);
        margin: 0.5rem 0;
    }

    /* Fix any white cards or elements that might interfere */
    .profile-dropdown-container * {
        box-sizing: border-box;
    }

    /* Ensure no white backgrounds interfere - FORCE DARK THEME */
    .profile-dropdown-container,
    .profile-dropdown-container *,
    .profile-dropdown-button,
    .profile-dropdown-button *,
    .profile-dropdown-menu,
    .profile-dropdown-menu * {
        background: transparent !important;
    }

    .profile-dropdown-button {
        background: rgba(30, 41, 59, 0.3) !important;
    }

    .profile-dropdown-button:hover {
        background: rgba(51, 65, 85, 0.6) !important;
    }

    .profile-dropdown-menu {
        background: rgba(30, 41, 59, 0.7) !important;
    }

    .profile-dropdown-header {
        background: rgba(51, 65, 85, 0.8) !important;
    }

    /* Force all text to be light colored */
    .profile-dropdown-container * {
        color: #f8fafc !important;
    }

    .profile-dropdown-container .profile-name {
        color: #f8fafc !important;
    }

    .profile-dropdown-container .profile-role,
    .profile-dropdown-container .profile-arrow,
    .profile-dropdown-container .profile-dropdown-details span {
        color: #cbd5e1 !important;
    }

    .profile-dropdown-container .profile-dropdown-item.logout {
        color: #f87171 !important;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .profile-info {
            display: none;
        }
        
        .profile-dropdown-menu {
            min-width: 250px;
            max-width: 280px;
            right: -10px;
        }
    }
</style>
