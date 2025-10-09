<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class GranularRbacService
{
    /**
     * Define granular permissions for each component and action
     * Format: 'component.action' => [allowed_role_ids]
     */
    private static $granularPermissions = [
        // User Management
        'user-management.view' => [1, 2], // Superadmin, Admin
        'user-management.create' => [1, 2], // Superadmin, Admin
        'user-management.edit' => [1, 2], // Superadmin, Admin
        'user-management.delete' => [1, 2], // Superadmin, Admin
        'user-management.create-admin' => [1], // Only Superadmin can create admin accounts
        
        // Class Management
        'class-management.view' => [1, 2], // Superadmin, Admin
        'class-management.create' => [1, 2], // Superadmin, Admin
        'class-management.edit' => [1, 2], // Superadmin, Admin
        'class-management.delete' => [1, 2], // Superadmin, Admin
        
        // Subject Management
        'subject-management.view' => [1, 2], // Superadmin, Admin
        'subject-management.create' => [1, 2], // Superadmin, Admin
        'subject-management.edit' => [1, 2], // Superadmin, Admin
        'subject-management.delete' => [1, 2], // Superadmin, Admin
        
        // Task Management
        'task-management.view' => [1, 2, 3], // Superadmin, Admin, Teacher
        'task-management.create' => [1, 2, 3], // Superadmin, Admin, Teacher
        'task-management.edit' => [1, 2, 3], // Superadmin, Admin, Teacher
        'task-management.delete' => [1, 2, 3], // Superadmin, Admin, Teacher
        'task-management.view-all' => [1, 2], // Superadmin, Admin can view all tasks
        'task-management.edit-all' => [1, 2], // Superadmin, Admin can edit all tasks
        'task-management.delete-all' => [1, 2], // Superadmin, Admin can delete all tasks
        
        // Exam Management
        'exam-management.view' => [1, 2, 3], // Superadmin, Admin, Teacher
        'exam-management.create' => [1, 2, 3], // Superadmin, Admin, Teacher
        'exam-management.edit' => [1, 2, 3], // Superadmin, Admin, Teacher
        'exam-management.delete' => [1, 2, 3], // Superadmin, Admin, Teacher
        'exam-management.view-all' => [1, 2], // Superadmin, Admin can view all exams
        'exam-management.edit-all' => [1, 2], // Superadmin, Admin can edit all exams
        'exam-management.delete-all' => [1, 2], // Superadmin, Admin can delete all exams
        
        // Material Management
        'material-management.view' => [1, 2, 3], // Superadmin, Admin, Teacher
        'material-management.create' => [1, 2, 3], // Superadmin, Admin, Teacher
        'material-management.edit' => [1, 2, 3], // Superadmin, Admin, Teacher
        'material-management.delete' => [1, 2, 3], // Superadmin, Admin, Teacher
        'material-management.view-all' => [1, 2], // Superadmin, Admin can view all materials
        'material-management.edit-all' => [1, 2], // Superadmin, Admin can edit all materials
        'material-management.delete-all' => [1, 2], // Superadmin, Admin can delete all materials
        
        // IoT Management
        'iot-management.view' => [1, 2, 3], // Superadmin, Admin, Teacher
        'iot-management.create' => [1, 2, 3], // Superadmin, Admin, Teacher
        'iot-management.edit' => [1, 2, 3], // Superadmin, Admin, Teacher
        'iot-management.delete' => [1, 2, 3], // Superadmin, Admin, Teacher
        'iot-management.view-all' => [1, 2], // Superadmin, Admin can view all IoT devices
        'iot-management.edit-all' => [1, 2], // Superadmin, Admin can edit all IoT devices
        'iot-management.delete-all' => [1, 2], // Superadmin, Admin can delete all IoT devices
        
        // Reports
        'reports.view' => [1, 2, 3], // Superadmin, Admin, Teacher
        'reports.export' => [1, 2, 3], // Superadmin, Admin, Teacher
        'reports.view-all' => [1, 2], // Superadmin, Admin can view all reports
        'reports.export-all' => [1, 2], // Superadmin, Admin can export all reports
        
        // Analytics
        'analytics.view' => [1, 2], // Superadmin, Admin
        'analytics.export' => [1, 2], // Superadmin, Admin
        
        // Push Notification
        'push-notification.view' => [1, 2, 3], // Superadmin, Admin, Teacher
        'push-notification.send' => [1, 2, 3], // Superadmin, Admin, Teacher
        'push-notification.send-all' => [1, 2], // Superadmin, Admin can send to all users
    ];

    /**
     * Check if current user has permission for specific action
     */
    public static function hasPermission(string $component, string $action): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $userRoleId = Auth::user()->roles_id;
        $permissionKey = $component . '.' . $action;
        
        if (!isset(self::$granularPermissions[$permissionKey])) {
            return false;
        }

        return in_array($userRoleId, self::$granularPermissions[$permissionKey]);
    }

    /**
     * Check if user can view a component (minimum read access)
     */
    public static function canView(string $component): bool
    {
        return self::hasPermission($component, 'view');
    }

    /**
     * Check if user can create in a component
     */
    public static function canCreate(string $component): bool
    {
        return self::hasPermission($component, 'create');
    }

    /**
     * Check if user can edit in a component
     */
    public static function canEdit(string $component): bool
    {
        return self::hasPermission($component, 'edit');
    }

    /**
     * Check if user can delete in a component
     */
    public static function canDelete(string $component): bool
    {
        return self::hasPermission($component, 'delete');
    }

    /**
     * Check if user can perform action on all records (not just their own)
     */
    public static function canManageAll(string $component): bool
    {
        $userRoleId = Auth::user()->roles_id ?? 0;
        return in_array($userRoleId, [1, 2]); // Only Superadmin and Admin
    }

    /**
     * Get user's accessible components
     */
    public static function getAccessibleComponents(): array
    {
        if (!Auth::check()) {
            return [];
        }

        $accessibleComponents = [];
        $components = array_unique(array_map(function($key) {
            return explode('.', $key)[0];
        }, array_keys(self::$granularPermissions)));

        foreach ($components as $component) {
            if (self::canView($component)) {
                $accessibleComponents[] = $component;
            }
        }

        return $accessibleComponents;
    }

    /**
     * Get user's permissions for a specific component
     */
    public static function getComponentPermissions(string $component): array
    {
        if (!Auth::check()) {
            return [];
        }

        $permissions = [];
        $userRoleId = Auth::user()->roles_id;

        foreach (self::$granularPermissions as $permission => $allowedRoles) {
            if (str_starts_with($permission, $component . '.')) {
                $action = explode('.', $permission)[1];
                $permissions[$action] = in_array($userRoleId, $allowedRoles);
            }
        }

        return $permissions;
    }

    /**
     * Check if user can access specific resource (for data filtering)
     */
    public static function canAccessResource(string $component, $resource = null): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $userRoleId = Auth::user()->roles_id;
        $userId = Auth::id();

        // Only Superadmin can access all resources
        if ($userRoleId == 1) {
            return true;
        }

        // For other roles, check if resource belongs to them
        if ($resource && method_exists($resource, 'user_id')) {
            return $resource->user_id == $userId;
        }

        // If no resource provided, allow based on view permission
        return self::canView($component);
    }
}
