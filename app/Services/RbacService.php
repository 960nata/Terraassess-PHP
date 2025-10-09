<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class RbacService
{
    /**
     * Define component access rights based on user roles
     */
    private static $componentAccessRights = [
        // Dashboard
        'dashboard' => [1, 2, 3, 4], // Superadmin, Admin, Teacher, Student
        
        // User Management - Only Superadmin and Admin
        'user-management' => [1, 2],
        
        // Class Management - Only Superadmin and Admin
        'class-management' => [1, 2],
        
        // Subject Management - Only Superadmin and Admin
        'subject-management' => [1, 2],
        
        // Task Management - Superadmin, Admin, Teacher
        'task-management' => [1, 2, 3],
        
        // Exam Management - Superadmin, Admin, Teacher
        'exam-management' => [1, 2, 3],
        
        // Material Management - Superadmin, Admin, Teacher
        'material-management' => [1, 2, 3],
        
        // IoT Management - Superadmin, Admin, Teacher
        'iot-management' => [1, 2, 3],
        
        // Reports - Superadmin, Admin, Teacher
        'reports' => [1, 2, 3],
        
        // Analytics - Only Superadmin and Admin
        'analytics' => [1, 2],
        
        // Push Notification - Superadmin, Admin, Teacher
        'push-notification' => [1, 2, 3],
        
        // Profile - All roles
        'profile' => [1, 2, 3, 4],
        
        // Settings - All roles
        'settings' => [1, 2, 3, 4],
        
        // Help - All roles
        'help' => [1, 2, 3, 4],
    ];

    /**
     * Check if current user has access to a component
     */
    public static function hasAccess(string $component): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $userRoleId = Auth::user()->roles_id;
        
        if (!isset(self::$componentAccessRights[$component])) {
            return false;
        }

        return in_array($userRoleId, self::$componentAccessRights[$component]);
    }

    /**
     * Get all accessible components for current user
     */
    public static function getAccessibleComponents(): array
    {
        if (!Auth::check()) {
            return [];
        }

        $accessibleComponents = [];
        foreach (self::$componentAccessRights as $component => $allowedRoles) {
            if (self::hasAccess($component)) {
                $accessibleComponents[] = $component;
            }
        }

        return $accessibleComponents;
    }

    /**
     * Check if user can create admin accounts
     * Only Superadmin can create admin accounts
     */
    public static function canCreateAdmin(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->roles_id === 1; // Only Superadmin
    }

    /**
     * Get user role name
     */
    public static function getUserRoleName(): string
    {
        if (!Auth::check()) {
            return 'guest';
        }

        $roleMap = [
            1 => 'superadmin',
            2 => 'admin',
            3 => 'teacher',
            4 => 'student',
        ];

        return $roleMap[Auth::user()->roles_id] ?? 'unknown';
    }

    /**
     * Check if user is superadmin or admin (same capabilities)
     */
    public static function isSuperAdminOrAdmin(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $userRoleId = Auth::user()->roles_id;
        return in_array($userRoleId, [1, 2]); // Superadmin or Admin
    }
}
