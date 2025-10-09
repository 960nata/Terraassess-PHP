<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class SimpleAccessControl
{
    /**
     * Check if user can access a menu item
     */
    public static function canAccessMenu(string $menu): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $userRoleId = Auth::user()->roles_id;
        
        // Menu access based on role
        $menuAccess = [
            'dashboard' => [1, 2, 3, 4], // All roles
            'task-management' => [1, 2, 3], // Superadmin, Admin, Teacher
            'exam-management' => [1, 2, 3], // Superadmin, Admin, Teacher
            'material-management' => [1, 2, 3], // Superadmin, Admin, Teacher
            'iot-management' => [1, 2, 3], // Superadmin, Admin, Teacher
            'user-management' => [1, 2], // Superadmin, Admin only
            'class-management' => [1, 2], // Superadmin, Admin only
            'subject-management' => [1, 2], // Superadmin, Admin only
            'teacher-assignments' => [1, 2], // Superadmin, Admin only
            'reports' => [1, 2, 3], // Superadmin, Admin, Teacher
            'analytics' => [1, 2], // Superadmin, Admin only
            'push-notification' => [1, 2, 3], // Superadmin, Admin, Teacher
        ];

        return in_array($userRoleId, $menuAccess[$menu] ?? []);
    }

    /**
     * Check if user can perform specific action
     */
    public static function canPerformAction(string $action): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $userRoleId = Auth::user()->roles_id;
        
        // Action permissions
        $actionPermissions = [
            'create' => [1, 2, 3], // Superadmin, Admin, Teacher
            'edit' => [1, 2, 3], // Superadmin, Admin, Teacher
            'delete' => [1, 2, 3], // Superadmin, Admin, Teacher
            'view_all' => [1, 2], // Superadmin, Admin only
            'edit_all' => [1, 2], // Superadmin, Admin only
            'delete_all' => [1, 2], // Superadmin, Admin only
            'create_admin' => [1], // Superadmin only
            'manage_users' => [1, 2], // Superadmin, Admin only
            'manage_classes' => [1, 2], // Superadmin, Admin only
            'manage_subjects' => [1, 2], // Superadmin, Admin only
        ];

        return in_array($userRoleId, $actionPermissions[$action] ?? []);
    }

    /**
     * Check if teacher can access specific subject
     */
    public static function canAccessSubject(int $subjectId): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();
        
        // Superadmin and Admin can access all subjects
        if (in_array($user->roles_id, [1, 2])) {
            return true;
        }

        // For teachers, check if they teach this subject
        if ($user->roles_id == 3) {
            // Check if teacher is assigned to this subject
            // This would need to be implemented based on your database structure
            // For now, return true for demonstration
            return true;
        }

        return false;
    }

    /**
     * Get user's accessible menus
     */
    public static function getAccessibleMenus(): array
    {
        if (!Auth::check()) {
            return [];
        }

        $menus = [
            'dashboard', 'task-management', 'exam-management', 'material-management',
            'iot-management', 'user-management', 'class-management', 'subject-management',
            'teacher-assignments', 'reports', 'analytics', 'push-notification'
        ];

        return array_filter($menus, function($menu) {
            return self::canAccessMenu($menu);
        });
    }

    /**
     * Check if user can create admin accounts
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
}
