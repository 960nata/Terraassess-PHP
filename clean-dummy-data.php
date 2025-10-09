<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Support\Facades\Hash;

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧹 Membersihkan data dummy dari database...\n\n";

try {
    // Hapus data dummy users
    $dummyEmails = [
        'superadmin@terraassessment.com',
        'admin@terraassessment.com', 
        'guru@terraassessment.com',
        'siswa@terraassessment.com',
        'guru@example.com',
        'siswa@example.com'
    ];
    
    $deletedUsers = \App\Models\User::whereIn('email', $dummyEmails)->delete();
    echo "✅ Dihapus {$deletedUsers} user dummy\n";
    
    // Hapus data dummy kelas
    $deletedKelas = \App\Models\Kelas::where('name', 'X IPA 1')->delete();
    echo "✅ Dihapus {$deletedKelas} kelas dummy\n";
    
    // Hapus data dummy mapel
    $dummyMapel = ['Fisika', 'Kimia', 'Biologi'];
    $deletedMapel = \App\Models\Mapel::whereIn('name', $dummyMapel)->delete();
    echo "✅ Dihapus {$deletedMapel} mapel dummy\n";
    
    // Hapus data dummy materi
    $dummyMateri = ['Hukum Newton', 'Struktur Atom', 'Sistem Pencernaan'];
    $deletedMateri = \App\Models\Materi::whereIn('name', $dummyMateri)->delete();
    echo "✅ Dihapus {$deletedMateri} materi dummy\n";
    
    // Hapus data dummy tugas
    $dummyTugas = [
        'Latihan Hukum Newton',
        'Laporan Praktikum Kimia', 
        'Observasi Sel'
    ];
    $deletedTugas = \App\Models\Tugas::whereIn('name', $dummyTugas)->delete();
    echo "✅ Dihapus {$deletedTugas} tugas dummy\n";
    
    // Hapus data dummy ujian
    $dummyUjian = [
        'Ujian Tengah Semester Fisika',
        'Ujian Kimia Dasar'
    ];
    $deletedUjian = \App\Models\Ujian::whereIn('name', $dummyUjian)->delete();
    echo "✅ Dihapus {$deletedUjian} ujian dummy\n";
    
    // Hapus data dummy soal
    $dummySoal = [
        'Apa yang dimaksud dengan hukum Newton pertama?',
        'Rumus hukum Newton kedua adalah?'
    ];
    $deletedSoal = \App\Models\SoalUjianMultiple::whereIn('soal', $dummySoal)->delete();
    echo "✅ Dihapus {$deletedSoal} soal dummy\n";
    
    // Hapus data dummy IoT readings
    $deletedIotReadings = \App\Models\IotReading::where('device_id', 'IoT_DEVICE_001')->delete();
    echo "✅ Dihapus {$deletedIotReadings} IoT readings dummy\n";
    
    // Hapus data dummy notifications
    $dummyNotifications = [
        'Selamat Datang!',
        'Maintenance Jadwal',
        'Tugas Baru Tersedia',
        'Fitur Notifikasi Aktif',
        'Nilai Ujian Tersedia'
    ];
    $deletedNotifications = \App\Models\Notification::whereIn('title', $dummyNotifications)->delete();
    echo "✅ Dihapus {$deletedNotifications} notifikasi dummy\n";
    
    // Hapus data dummy user_tugas dan user_ujians
    $deletedUserTugas = \App\Models\UserTugas::whereHas('user', function($query) {
        $query->whereIn('email', [
            'siswa@terraassessment.com',
            'siswa@example.com'
        ]);
    })->delete();
    echo "✅ Dihapus {$deletedUserTugas} user_tugas dummy\n";
    
    $deletedUserUjian = \App\Models\UserUjian::whereHas('user', function($query) {
        $query->whereIn('email', [
            'siswa@terraassessment.com',
            'siswa@example.com'
        ]);
    })->delete();
    echo "✅ Dihapus {$deletedUserUjian} user_ujian dummy\n";
    
    echo "\n🎉 Pembersihan data dummy selesai!\n";
    echo "📊 Database sekarang hanya berisi data real.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
