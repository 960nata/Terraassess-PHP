@extends('layouts.unified-layout')

@section('title', 'Bantuan Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Bantuan Admin</h1>
        <p class="text-gray-600 dark:text-gray-400">Panduan penggunaan sistem Terra Assessment untuk Administrator</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Menu Navigasi Bantuan -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 sticky top-4">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Menu Bantuan</h2>
                <nav class="space-y-2">
                    <a href="#dashboard" class="block px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                    <a href="#user-management" class="block px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <i class="fas fa-users mr-2"></i>Manajemen Pengguna
                    </a>
                    <a href="#class-management" class="block px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <i class="fas fa-school mr-2"></i>Manajemen Kelas
                    </a>
                    <a href="#subject-management" class="block px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <i class="fas fa-book-open mr-2"></i>Mata Pelajaran
                    </a>
                    <a href="#material-management" class="block px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <i class="fas fa-book mr-2"></i>Materi
                    </a>
                    <a href="#task-management" class="block px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <i class="fas fa-clipboard-text mr-2"></i>Manajemen Tugas
                    </a>
                    <a href="#exam-management" class="block px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <i class="fas fa-exam mr-2"></i>Manajemen Ujian
                    </a>
                    <a href="#notifications" class="block px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <i class="fas fa-bell mr-2"></i>Notifikasi
                    </a>
                    <a href="#iot-management" class="block px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <i class="fas fa-wifi mr-2"></i>Manajemen IoT
                    </a>
                    <a href="#settings" class="block px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <i class="fas fa-cog mr-2"></i>Pengaturan
                    </a>
                </nav>
            </div>
        </div>

        <!-- Konten Bantuan -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Dashboard -->
            <section id="dashboard" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-tachometer-alt mr-2 text-blue-600"></i>Dashboard
                </h2>
                <div class="prose dark:prose-invert max-w-none">
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Dashboard admin memberikan gambaran menyeluruh tentang sistem Terra Assessment. Di sini Anda dapat melihat:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 space-y-2">
                        <li>Statistik pengguna aktif</li>
                        <li>Jumlah kelas dan mata pelajaran</li>
                        <li>Aktivitas terbaru sistem</li>
                        <li>Notifikasi penting</li>
                        <li>Status sistem dan performa</li>
                    </ul>
                </div>
            </section>

            <!-- Manajemen Pengguna -->
            <section id="user-management" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-users mr-2 text-green-600"></i>Manajemen Pengguna
                </h2>
                <div class="prose dark:prose-invert max-w-none">
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Kelola semua pengguna sistem termasuk admin, guru, dan siswa:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 space-y-2">
                        <li><strong>Tambah Pengguna:</strong> Buat akun baru untuk admin, guru, atau siswa</li>
                        <li><strong>Edit Profil:</strong> Ubah informasi pengguna dan peran</li>
                        <li><strong>Nonaktifkan Akun:</strong> Matikan akses pengguna sementara</li>
                        <li><strong>Reset Password:</strong> Atur ulang kata sandi pengguna</li>
                        <li><strong>Log Aktivitas:</strong> Pantau aktivitas pengguna</li>
                    </ul>
                </div>
            </section>

            <!-- Manajemen Kelas -->
            <section id="class-management" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-school mr-2 text-purple-600"></i>Manajemen Kelas
                </h2>
                <div class="prose dark:prose-invert max-w-none">
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Atur struktur kelas dan penugasan guru:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 space-y-2">
                        <li><strong>Buat Kelas:</strong> Tambah kelas baru dengan nama dan deskripsi</li>
                        <li><strong>Assign Guru:</strong> Tentukan guru yang mengajar di kelas</li>
                        <li><strong>Kelola Siswa:</strong> Tambah atau pindahkan siswa antar kelas</li>
                        <li><strong>Jadwal Kelas:</strong> Atur jadwal dan mata pelajaran</li>
                    </ul>
                </div>
            </section>

            <!-- Mata Pelajaran -->
            <section id="subject-management" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-book-open mr-2 text-orange-600"></i>Mata Pelajaran
                </h2>
                <div class="prose dark:prose-invert max-w-none">
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Kelola mata pelajaran yang tersedia di sistem:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 space-y-2">
                        <li><strong>Tambah Mata Pelajaran:</strong> Buat mata pelajaran baru</li>
                        <li><strong>Edit Informasi:</strong> Ubah nama dan deskripsi mata pelajaran</li>
                        <li><strong>Assign Guru:</strong> Tentukan guru yang mengajar mata pelajaran</li>
                        <li><strong>Kurikulum:</strong> Atur standar kompetensi dan indikator</li>
                    </ul>
                </div>
            </section>

            <!-- Manajemen Tugas -->
            <section id="task-management" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-clipboard-text mr-2 text-red-600"></i>Manajemen Tugas
                </h2>
                <div class="prose dark:prose-invert max-w-none">
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Pantau dan kelola tugas yang diberikan guru:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 space-y-2">
                        <li><strong>Lihat Semua Tugas:</strong> Pantau tugas dari semua guru</li>
                        <li><strong>Status Pengumpulan:</strong> Lihat progress pengumpulan tugas</li>
                        <li><strong>Evaluasi Kualitas:</strong> Review kualitas tugas yang diberikan</li>
                        <li><strong>Laporan Tugas:</strong> Generate laporan aktivitas tugas</li>
                    </ul>
                </div>
            </section>

            <!-- Manajemen Ujian -->
            <section id="exam-management" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-exam mr-2 text-indigo-600"></i>Manajemen Ujian
                </h2>
                <div class="prose dark:prose-invert max-w-none">
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Kelola ujian dan evaluasi pembelajaran:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 space-y-2">
                        <li><strong>Jadwal Ujian:</strong> Atur jadwal ujian untuk semua kelas</li>
                        <li><strong>Bank Soal:</strong> Kelola kumpulan soal ujian</li>
                        <li><strong>Hasil Ujian:</strong> Lihat dan analisis hasil ujian</li>
                        <li><strong>Laporan Nilai:</strong> Generate laporan nilai siswa</li>
                    </ul>
                </div>
            </section>

            <!-- Notifikasi -->
            <section id="notifications" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-bell mr-2 text-yellow-600"></i>Notifikasi
                </h2>
                <div class="prose dark:prose-invert max-w-none">
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Kelola sistem notifikasi untuk pengguna:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 space-y-2">
                        <li><strong>Push Notifikasi:</strong> Kirim notifikasi real-time ke pengguna</li>
                        <li><strong>Email Notifikasi:</strong> Atur notifikasi via email</li>
                        <li><strong>Template Pesan:</strong> Buat template notifikasi</li>
                        <li><strong>Jadwal Notifikasi:</strong> Atur notifikasi terjadwal</li>
                    </ul>
                </div>
            </section>

            <!-- Manajemen IoT -->
            <section id="iot-management" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-wifi mr-2 text-teal-600"></i>Manajemen IoT
                </h2>
                <div class="prose dark:prose-invert max-w-none">
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Kelola perangkat IoT yang terintegrasi dengan sistem:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 space-y-2">
                        <li><strong>Status Perangkat:</strong> Pantau status perangkat IoT</li>
                        <li><strong>Konfigurasi:</strong> Atur pengaturan perangkat</li>
                        <li><strong>Data Sensor:</strong> Lihat data dari sensor IoT</li>
                        <li><strong>Maintenance:</strong> Kelola jadwal maintenance perangkat</li>
                    </ul>
                </div>
            </section>

            <!-- Pengaturan -->
            <section id="settings" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
                    <i class="fas fa-cog mr-2 text-gray-600"></i>Pengaturan
                </h2>
                <div class="prose dark:prose-invert max-w-none">
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Konfigurasi sistem dan pengaturan umum:
                    </p>
                    <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 space-y-2">
                        <li><strong>Pengaturan Umum:</strong> Nama sistem, email admin, timezone</li>
                        <li><strong>Keamanan:</strong> 2FA, log aktivitas, durasi session</li>
                        <li><strong>Notifikasi:</strong> Email, push, SMS notification settings</li>
                        <li><strong>Backup:</strong> Atur jadwal backup otomatis</li>
                    </ul>
                </div>
            </section>

            <!-- Kontak Support -->
            <section class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-md p-6 text-white">
                <h2 class="text-2xl font-semibold mb-4">
                    <i class="fas fa-headset mr-2"></i>Butuh Bantuan Lebih Lanjut?
                </h2>
                <p class="mb-4">Jika Anda mengalami kesulitan atau memiliki pertanyaan yang tidak terjawab di panduan ini, jangan ragu untuk menghubungi tim support kami.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h3 class="font-semibold mb-2">Kontak Support</h3>
                        <p><i class="fas fa-envelope mr-2"></i>support@terraassessment.com</p>
                        <p><i class="fas fa-phone mr-2"></i>+62 21 1234 5678</p>
                    </div>
                    <div>
                        <h3 class="font-semibold mb-2">Jam Operasional</h3>
                        <p>Senin - Jumat: 08:00 - 17:00 WIB</p>
                        <p>Sabtu: 08:00 - 12:00 WIB</p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Highlight active section in navigation
window.addEventListener('scroll', function() {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('nav a[href^="#"]');
    
    let current = '';
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        if (scrollY >= (sectionTop - 200)) {
            current = section.getAttribute('id');
        }
    });

    navLinks.forEach(link => {
        link.classList.remove('bg-blue-100', 'dark:bg-gray-700', 'text-blue-600', 'dark:text-blue-400');
        if (link.getAttribute('href') === '#' + current) {
            link.classList.add('bg-blue-100', 'dark:bg-gray-700', 'text-blue-600', 'dark:text-blue-400');
        }
    });
});
</script>
@endsection
