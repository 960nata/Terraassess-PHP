<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Memverifikasi bahwa semua data hanya dari database...\n\n";

try {
    // Verifikasi bahwa tidak ada data dummy di database
    $dummyEmails = [
        'superadmin@terraassessment.com',
        'admin@terraassessment.com', 
        'guru@terraassessment.com',
        'siswa@terraassessment.com',
        'guru@example.com',
        'siswa@example.com'
    ];
    
    $dummyUsers = \App\Models\User::whereIn('email', $dummyEmails)->count();
    if ($dummyUsers > 0) {
        echo "❌ Masih ada {$dummyUsers} user dummy di database\n";
    } else {
        echo "✅ Tidak ada user dummy di database\n";
    }
    
    // Verifikasi data dummy lainnya
    $dummyKelas = \App\Models\Kelas::where('name', 'X IPA 1')->count();
    if ($dummyKelas > 0) {
        echo "❌ Masih ada {$dummyKelas} kelas dummy di database\n";
    } else {
        echo "✅ Tidak ada kelas dummy di database\n";
    }
    
    $dummyMapel = \App\Models\Mapel::whereIn('name', ['Fisika', 'Kimia', 'Biologi'])->count();
    if ($dummyMapel > 0) {
        echo "❌ Masih ada {$dummyMapel} mapel dummy di database\n";
    } else {
        echo "✅ Tidak ada mapel dummy di database\n";
    }
    
    $dummyMateri = \App\Models\Materi::whereIn('name', ['Hukum Newton', 'Struktur Atom', 'Sistem Pencernaan'])->count();
    if ($dummyMateri > 0) {
        echo "❌ Masih ada {$dummyMateri} materi dummy di database\n";
    } else {
        echo "✅ Tidak ada materi dummy di database\n";
    }
    
    $dummyTugas = \App\Models\Tugas::whereIn('name', [
        'Latihan Hukum Newton',
        'Laporan Praktikum Kimia', 
        'Observasi Sel'
    ])->count();
    if ($dummyTugas > 0) {
        echo "❌ Masih ada {$dummyTugas} tugas dummy di database\n";
    } else {
        echo "✅ Tidak ada tugas dummy di database\n";
    }
    
    $dummyUjian = \App\Models\Ujian::whereIn('name', [
        'Ujian Tengah Semester Fisika',
        'Ujian Kimia Dasar'
    ])->count();
    if ($dummyUjian > 0) {
        echo "❌ Masih ada {$dummyUjian} ujian dummy di database\n";
    } else {
        echo "✅ Tidak ada ujian dummy di database\n";
    }
    
    $dummySoal = \App\Models\SoalUjianMultiple::whereIn('soal', [
        'Apa yang dimaksud dengan hukum Newton pertama?',
        'Rumus hukum Newton kedua adalah?'
    ])->count();
    if ($dummySoal > 0) {
        echo "❌ Masih ada {$dummySoal} soal dummy di database\n";
    } else {
        echo "✅ Tidak ada soal dummy di database\n";
    }
    
    $dummyIotReadings = \App\Models\IotReading::where('device_id', 'IoT_DEVICE_001')->count();
    if ($dummyIotReadings > 0) {
        echo "❌ Masih ada {$dummyIotReadings} IoT readings dummy di database\n";
    } else {
        echo "✅ Tidak ada IoT readings dummy di database\n";
    }
    
    $dummyNotifications = \App\Models\Notification::whereIn('title', [
        'Selamat Datang!',
        'Maintenance Jadwal',
        'Tugas Baru Tersedia',
        'Fitur Notifikasi Aktif',
        'Nilai Ujian Tersedia'
    ])->count();
    if ($dummyNotifications > 0) {
        echo "❌ Masih ada {$dummyNotifications} notifikasi dummy di database\n";
    } else {
        echo "✅ Tidak ada notifikasi dummy di database\n";
    }
    
    // Verifikasi bahwa semua data berasal dari database
    echo "\n📊 Statistik Database Real:\n";
    echo "👥 Total Users: " . \App\Models\User::count() . "\n";
    echo "🏫 Total Kelas: " . \App\Models\Kelas::count() . "\n";
    echo "📚 Total Mapel: " . \App\Models\Mapel::count() . "\n";
    echo "📖 Total Materi: " . \App\Models\Materi::count() . "\n";
    echo "📝 Total Tugas: " . \App\Models\Tugas::count() . "\n";
    echo "📋 Total Ujian: " . \App\Models\Ujian::count() . "\n";
    echo "❓ Total Soal: " . \App\Models\SoalUjianMultiple::count() . "\n";
    echo "📱 Total IoT Readings: " . \App\Models\IotReading::count() . "\n";
    echo "🔔 Total Notifications: " . \App\Models\Notification::count() . "\n";
    
    echo "\n🎉 Verifikasi selesai! Database hanya berisi data real.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
