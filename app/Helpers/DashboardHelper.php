<?php

namespace App\Helpers;

class DashboardHelper
{
    /**
     * Get role configuration for dashboard
     */
    public static function getRoleConfig($roleId)
    {
        $configs = [
            1 => [ // Super Admin
                'title' => 'Super Admin',
                'icon' => 'fas fa-crown',
                'initial' => 'SA',
                'description' => 'Kontrol penuh atas sistem Terra Assessment',
                'welcome_message' => 'Sebagai Super Admin, Anda memiliki akses penuh untuk mengelola seluruh sistem.',
                'permissions_title' => 'Hak Akses Super Admin',
                'permissions' => [
                    'Kelola semua pengguna sistem',
                    'Akses ke semua fitur aplikasi',
                    'Konfigurasi sistem global',
                    'Monitoring aktivitas pengguna'
                ],
                'responsibilities_title' => 'Tanggung Jawab',
                'responsibilities' => [
                    'Memastikan keamanan sistem',
                    'Mengelola data pengguna',
                    'Konfigurasi aplikasi',
                    'Backup dan maintenance'
                ],
                'profile_route' => 'superadmin.profile',
                'settings_route' => 'superadmin.settings',
                'role' => 'superadmin'
            ],
            2 => [ // Admin
                'title' => 'Admin',
                'icon' => 'fas fa-user-shield',
                'initial' => 'A',
                'description' => 'Kelola sistem Terra Assessment dengan akses admin',
                'welcome_message' => 'Sebagai Admin, Anda memiliki akses untuk mengelola sistem Terra Assessment.',
                'permissions_title' => 'Hak Akses Admin',
                'permissions' => [
                    'Kelola pengguna sistem (Guru dan Siswa)',
                    'Akses ke fitur manajemen',
                    'Konfigurasi sistem',
                    'Monitoring aktivitas pengguna'
                ],
                'responsibilities_title' => 'Tanggung Jawab',
                'responsibilities' => [
                    'Memastikan keamanan sistem',
                    'Mengelola data pengguna',
                    'Konfigurasi aplikasi',
                    'Backup dan maintenance'
                ],
                'profile_route' => 'admin.profile',
                'settings_route' => 'admin.settings',
                'role' => 'admin'
            ],
            3 => [ // Teacher
                'title' => 'Guru',
                'icon' => 'fas fa-chalkboard-teacher',
                'initial' => 'G',
                'description' => 'Kelola pembelajaran dan kelas Anda',
                'welcome_message' => 'Sebagai Guru, Anda dapat mengelola pembelajaran, tugas, dan ujian untuk kelas Anda.',
                'permissions_title' => 'Hak Akses Guru',
                'permissions' => [
                    'Mengelola kelas yang diajar',
                    'Membuat tugas dan ujian',
                    'Mengelola materi pembelajaran',
                    'Memantau data IoT untuk penelitian'
                ],
                'responsibilities_title' => 'Tanggung Jawab',
                'responsibilities' => [
                    'Menyiapkan materi pembelajaran',
                    'Membuat dan menilai tugas',
                    'Mengelola ujian',
                    'Memantau perkembangan siswa'
                ],
                'profile_route' => 'teacher.profile',
                'settings_route' => 'teacher.settings',
                'role' => 'teacher'
            ],
            4 => [ // Student
                'title' => 'Siswa',
                'icon' => 'fas fa-graduation-cap',
                'initial' => 'S',
                'description' => 'Akses pembelajaran dan penelitian IoT',
                'welcome_message' => 'Sebagai Siswa, Anda dapat mengakses materi pembelajaran, mengerjakan tugas, dan melakukan penelitian IoT.',
                'permissions_title' => 'Hak Akses Siswa',
                'permissions' => [
                    'Mengakses materi pembelajaran',
                    'Mengerjakan tugas dan ujian',
                    'Melakukan penelitian IoT',
                    'Melihat nilai dan progress'
                ],
                'responsibilities_title' => 'Tanggung Jawab',
                'responsibilities' => [
                    'Mengerjakan tugas tepat waktu',
                    'Mengikuti ujian sesuai jadwal',
                    'Melakukan penelitian IoT',
                    'Mengikuti pembelajaran dengan baik'
                ],
                'profile_route' => 'student.profile',
                'settings_route' => 'student.settings',
                'role' => 'student'
            ]
        ];

        return $configs[$roleId] ?? $configs[4]; // Default to student if role not found
    }

    /**
     * Get dashboard view based on role
     */
    public static function getDashboardView($roleId)
    {
        $views = [
            1 => 'dashboard.superadmin-new',
            2 => 'dashboard.admin-new',
            3 => 'dashboard.teacher-new',
            4 => 'dashboard.student-new'
        ];

        return $views[$roleId] ?? 'dashboard.student-new';
    }

    /**
     * Get dashboard route based on role
     */
    public static function getDashboardRoute($roleId)
    {
        $routes = [
            1 => 'superadmin.dashboard',
            2 => 'admin.dashboard',
            3 => 'teacher.dashboard',
            4 => 'student.dashboard'
        ];

        return $routes[$roleId] ?? 'student.dashboard';
    }

    /**
     * Get role name by ID
     */
    public static function getRoleName($roleId)
    {
        $roles = [
            1 => 'Super Admin',
            2 => 'Admin',
            3 => 'Guru',
            4 => 'Siswa'
        ];

        return $roles[$roleId] ?? 'Siswa';
    }

    /**
     * Check if user has permission for specific action
     */
    public static function hasPermission($roleId, $action)
    {
        $permissions = [
            1 => [ // Super Admin - can do everything
                'manage_users', 'manage_classes', 'manage_subjects', 'manage_materials',
                'manage_tasks', 'manage_exams', 'view_reports', 'manage_iot',
                'send_notifications', 'system_settings'
            ],
            2 => [ // Admin - can manage most things except super admin functions
                'manage_users', 'manage_classes', 'manage_subjects', 'manage_materials',
                'manage_tasks', 'manage_exams', 'view_reports', 'manage_iot',
                'send_notifications'
            ],
            3 => [ // Teacher - can manage their own content
                'manage_own_tasks', 'manage_own_exams', 'manage_own_materials',
                'view_own_reports', 'manage_iot', 'view_students'
            ],
            4 => [ // Student - can only view and participate
                'view_materials', 'do_tasks', 'take_exams', 'view_grades',
                'do_iot_research', 'view_own_progress'
            ]
        ];

        return in_array($action, $permissions[$roleId] ?? []);
    }

    /**
     * Get menu items for specific role
     */
    public static function getMenuItems($roleId)
    {
        $menus = [
            1 => [ // Super Admin
                'main' => [
                    ['name' => 'Dashboard', 'route' => 'superadmin.dashboard', 'icon' => 'fas fa-tachometer-alt'],
                    ['name' => 'Push Notifikasi', 'route' => 'superadmin.push-notification', 'icon' => 'fas fa-bell'],
                    ['name' => 'Manajemen IoT', 'route' => 'superadmin.iot-management', 'icon' => 'fas fa-wifi']
                ],
                'management' => [
                    ['name' => 'Manajemen Tugas', 'route' => 'superadmin.task-management', 'icon' => 'fas fa-book'],
                    ['name' => 'Manajemen Ujian', 'route' => 'superadmin.exam-management', 'icon' => 'fas fa-bullseye'],
                    ['name' => 'Manajemen Pengguna', 'route' => 'superadmin.user-management', 'icon' => 'fas fa-users'],
                    ['name' => 'Manajemen Kelas', 'route' => 'superadmin.class-management', 'icon' => 'fas fa-chart-bar'],
                    ['name' => 'Mata Pelajaran', 'route' => 'superadmin.subject-management', 'icon' => 'fas fa-database'],
                    ['name' => 'Manajemen Materi', 'route' => 'superadmin.material-management', 'icon' => 'fas fa-file-alt']
                ],
                'iot' => [
                    ['name' => 'Penelitian IoT', 'route' => 'iot.research-projects', 'icon' => 'fas fa-wave-square']
                ],
                'analytics' => [
                    ['name' => 'Laporan', 'route' => 'superadmin.reports', 'icon' => 'fas fa-chart-line'],
                    ['name' => 'Analitik', 'route' => 'superadmin.analytics', 'icon' => 'fas fa-chart-bar']
                ],
                'settings' => [
                    ['name' => 'Pengaturan', 'route' => 'superadmin.settings', 'icon' => 'fas fa-cog'],
                    ['name' => 'Bantuan', 'route' => 'superadmin.help', 'icon' => 'fas fa-question-circle']
                ]
            ],
            2 => [ // Admin
                'main' => [
                    ['name' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'fas fa-tachometer-alt'],
                    ['name' => 'Push Notifikasi', 'route' => 'admin.push-notification', 'icon' => 'fas fa-bell'],
                    ['name' => 'Manajemen IoT', 'route' => 'admin.iot-management', 'icon' => 'fas fa-wifi']
                ],
                'management' => [
                    ['name' => 'Manajemen Tugas', 'route' => 'admin.task-management', 'icon' => 'fas fa-book'],
                    ['name' => 'Manajemen Ujian', 'route' => 'admin.exam-management', 'icon' => 'fas fa-bullseye'],
                    ['name' => 'Manajemen Pengguna', 'route' => 'admin.user-management', 'icon' => 'fas fa-users'],
                    ['name' => 'Manajemen Kelas', 'route' => 'admin.class-management', 'icon' => 'fas fa-chart-bar'],
                    ['name' => 'Mata Pelajaran', 'route' => 'admin.subject-management', 'icon' => 'fas fa-database'],
                    ['name' => 'Manajemen Materi', 'route' => 'admin.material-management', 'icon' => 'fas fa-file-alt']
                ],
                'iot' => [
                    ['name' => 'Penelitian IoT', 'route' => 'iot.research-projects', 'icon' => 'fas fa-wave-square']
                ],
                'analytics' => [
                    ['name' => 'Laporan', 'route' => 'superadmin.reports', 'icon' => 'fas fa-chart-line'],
                    ['name' => 'Analitik', 'route' => 'admin.analytics', 'icon' => 'fas fa-chart-bar']
                ],
                'settings' => [
                    ['name' => 'Pengaturan', 'route' => 'admin.settings', 'icon' => 'fas fa-cog'],
                    ['name' => 'Bantuan', 'route' => 'admin.help', 'icon' => 'fas fa-question-circle']
                ]
            ],
            3 => [ // Teacher
                'main' => [
                    ['name' => 'Dashboard', 'route' => 'teacher.dashboard', 'icon' => 'fas fa-tachometer-alt'],
                    ['name' => 'Tugas Saya', 'route' => 'teacher.tasks', 'icon' => 'fas fa-book'],
                    ['name' => 'Ujian Saya', 'route' => 'teacher.exam-management', 'icon' => 'fas fa-bullseye'],
                    ['name' => 'Materi Saya', 'route' => 'teacher.materials', 'icon' => 'fas fa-file-alt']
                ],
                'iot' => [
                    ['name' => 'IoT Dashboard', 'route' => 'teacher.iot-dashboard', 'icon' => 'fas fa-chart-line'],
                    ['name' => 'Devices', 'route' => 'teacher.iot-devices', 'icon' => 'fas fa-device-mobile'],
                    ['name' => 'Sensor Data', 'route' => 'teacher.iot-sensor-data', 'icon' => 'fas fa-thermometer']
                ],
                'analytics' => [
                    ['name' => 'Laporan', 'route' => 'teacher.reports', 'icon' => 'fas fa-chart-line'],
                    ['name' => 'Analitik', 'route' => 'teacher.analytics', 'icon' => 'fas fa-chart-bar']
                ],
                'settings' => [
                    ['name' => 'Pengaturan', 'route' => 'teacher.settings', 'icon' => 'fas fa-cog'],
                    ['name' => 'Bantuan', 'route' => 'teacher.help', 'icon' => 'fas fa-question-circle']
                ]
            ],
            4 => [ // Student
                'main' => [
                    ['name' => 'Dashboard', 'route' => 'student.dashboard', 'icon' => 'fas fa-tachometer-alt'],
                    ['name' => 'Tugas Saya', 'route' => 'student.tasks', 'icon' => 'fas fa-book'],
                    ['name' => 'Ujian Saya', 'route' => 'student.exams', 'icon' => 'fas fa-bullseye'],
                    ['name' => 'Materi Saya', 'route' => 'student.materials', 'icon' => 'fas fa-file-alt']
                ],
                'iot' => [
                    ['name' => 'Penelitian IoT', 'route' => 'student.iot-research', 'icon' => 'fas fa-microscope'],
                    ['name' => 'Data IoT', 'route' => 'student.iot-data', 'icon' => 'fas fa-database']
                ],
                'settings' => [
                    ['name' => 'Pengaturan', 'route' => 'student.settings', 'icon' => 'fas fa-cog'],
                    ['name' => 'Bantuan', 'route' => 'student.help', 'icon' => 'fas fa-question-circle']
                ]
            ]
        ];

        return $menus[$roleId] ?? $menus[4];
    }
}
