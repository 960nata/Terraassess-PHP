<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users to send notifications to
        $users = User::where('roles_id', '!=', 1)->take(5)->get();
        
        if ($users->count() > 0) {
            // Create sample notifications
            $notifications = [
                [
                    'user_id' => $users->first()->id,
                    'title' => 'Selamat Datang!',
                    'body' => 'Selamat datang di platform TerraAssessment. Silakan jelajahi fitur-fitur yang tersedia dan mulai belajar dengan IoT.',
                    'excerpt' => 'Selamat datang di platform TerraAssessment...',
                    'type' => 'success',
                    'is_read' => false,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'user_id' => $users->first()->id,
                    'title' => 'Maintenance Jadwal',
                    'body' => 'Akan dilakukan maintenance sistem pada hari Minggu, 22 September 2024 pukul 02:00 - 04:00 WIB. Mohon maaf atas ketidaknyamanan.',
                    'excerpt' => 'Maintenance sistem akan dilakukan...',
                    'type' => 'warning',
                    'is_read' => false,
                    'created_at' => now()->subHours(2),
                    'updated_at' => now()->subHours(2)
                ],
                [
                    'user_id' => $users->skip(1)->first()->id ?? $users->first()->id,
                    'title' => 'Tugas Baru Tersedia',
                    'body' => 'Tugas IoT Sensor Data telah ditambahkan untuk kelas Anda. Silakan kerjakan sebelum deadline yang ditentukan.',
                    'excerpt' => 'Tugas IoT Sensor Data telah ditambahkan...',
                    'type' => 'info',
                    'is_read' => true,
                    'read_at' => now()->subMinutes(30),
                    'created_at' => now()->subHours(1),
                    'updated_at' => now()->subHours(1)
                ],
                [
                    'user_id' => $users->first()->id,
                    'title' => 'Fitur Notifikasi Aktif',
                    'body' => 'Sistem notifikasi push telah diaktifkan! Admin dapat mengirim notifikasi langsung ke semua pengguna platform.',
                    'excerpt' => 'Sistem notifikasi push telah diaktifkan...',
                    'type' => 'info',
                    'is_read' => false,
                    'created_at' => now()->subMinutes(30),
                    'updated_at' => now()->subMinutes(30)
                ],
                [
                    'user_id' => $users->skip(2)->first()->id ?? $users->first()->id,
                    'title' => 'Nilai Ujian Tersedia',
                    'body' => 'Nilai ujian IoT Fundamentals telah tersedia. Silakan cek hasil ujian Anda di menu Ujian.',
                    'excerpt' => 'Nilai ujian IoT Fundamentals telah tersedia...',
                    'type' => 'success',
                    'is_read' => false,
                    'created_at' => now()->subMinutes(15),
                    'updated_at' => now()->subMinutes(15)
                ],
                [
                    'user_id' => $users->first()->id,
                    'title' => 'Reminder Tugas',
                    'body' => 'Jangan lupa untuk mengerjakan tugas IoT Sensor Data yang deadline-nya besok. Pastikan semua data sensor sudah dikumpulkan.',
                    'excerpt' => 'Reminder tugas IoT Sensor Data...',
                    'type' => 'warning',
                    'is_read' => false,
                    'created_at' => now()->subMinutes(5),
                    'updated_at' => now()->subMinutes(5)
                ],
                [
                    'user_id' => $users->first()->id,
                    'title' => 'Pembaruan Sistem',
                    'body' => 'Sistem telah diperbarui dengan fitur-fitur baru. Silakan refresh halaman untuk mendapatkan pengalaman terbaik.',
                    'excerpt' => 'Sistem telah diperbarui...',
                    'type' => 'info',
                    'is_read' => true,
                    'read_at' => now()->subMinutes(10),
                    'created_at' => now()->subMinutes(45),
                    'updated_at' => now()->subMinutes(45)
                ]
            ];

            foreach ($notifications as $notification) {
                Notification::create($notification);
            }
        }
    }
}