@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - Bantuan')

@section('content')
<div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-question-circle"></i>
                Pusat Bantuan
            </h1>
            <p class="page-description">Dapatkan bantuan dan panduan penggunaan sistem Terra Assessment</p>
        </div>

        <!-- Help Content -->
        <div style="background-color: #1e293b; border-radius: 1rem; padding: 2rem; border: 1px solid #334155; margin-bottom: 2rem;">
            <h2 style="color: #ffffff; margin-bottom: 1.5rem; font-size: 1.25rem;">
                <i class="fas fa-book me-2"></i>Panduan Penggunaan
                    </h2>
                    
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                <div style="background-color: #2a2a3e; border-radius: 0.5rem; padding: 1.5rem;">
                    <h3 style="color: #ffffff; margin-bottom: 1rem;">
                        <i class="fas fa-tachometer-alt" style="color: #667eea; margin-right: 0.5rem;"></i>
                        Dashboard
                    </h3>
                    <p style="color: #cbd5e1; font-size: 0.875rem; line-height: 1.6;">
                        Dashboard memberikan overview lengkap sistem dengan statistik pengguna, tugas, ujian, dan data IoT real-time.
                    </p>
                    </div>

                <div style="background-color: #2a2a3e; border-radius: 0.5rem; padding: 1.5rem;">
                    <h3 style="color: #ffffff; margin-bottom: 1rem;">
                        <i class="fas fa-bell" style="color: #10b981; margin-right: 0.5rem;"></i>
                        Push Notifikasi
                    </h3>
                    <p style="color: #cbd5e1; font-size: 0.875rem; line-height: 1.6;">
                        Kelola notifikasi untuk mengirim pengumuman, reminder tugas, dan update sistem kepada pengguna.
                    </p>
                    </div>

                <div style="background-color: #2a2a3e; border-radius: 0.5rem; padding: 1.5rem;">
                    <h3 style="color: #ffffff; margin-bottom: 1rem;">
                        <i class="fas fa-wifi" style="color: #f59e0b; margin-right: 0.5rem;"></i>
                        Manajemen IoT
                    </h3>
                    <p style="color: #cbd5e1; font-size: 0.875rem; line-height: 1.6;">
                        Monitor dan kelola perangkat IoT, data sensor, dan proyek penelitian terintegrasi.
                    </p>
                </div>

                <div style="background-color: #2a2a3e; border-radius: 0.5rem; padding: 1.5rem;">
                    <h3 style="color: #ffffff; margin-bottom: 1rem;">
                        <i class="fas fa-book" style="color: #8b5cf6; margin-right: 0.5rem;"></i>
                        Manajemen Tugas
                    </h3>
                    <p style="color: #cbd5e1; font-size: 0.875rem; line-height: 1.6;">
                        Buat, kelola, dan pantau tugas dengan berbagai tipe: pilihan ganda, esai, dan kelompok.
                    </p>
                </div>

                <div style="background-color: #2a2a3e; border-radius: 0.5rem; padding: 1.5rem;">
                    <h3 style="color: #ffffff; margin-bottom: 1rem;">
                        <i class="fas fa-bullseye" style="color: #ef4444; margin-right: 0.5rem;"></i>
                        Manajemen Ujian
                    </h3>
                    <p style="color: #cbd5e1; font-size: 0.875rem; line-height: 1.6;">
                        Kelola ujian dengan berbagai format dan sistem penilaian otomatis.
                    </p>
                </div>

                <div style="background-color: #2a2a3e; border-radius: 0.5rem; padding: 1.5rem;">
                    <h3 style="color: #ffffff; margin-bottom: 1rem;">
                        <i class="fas fa-users" style="color: #3b82f6; margin-right: 0.5rem;"></i>
                        Manajemen Pengguna
                    </h3>
                    <p style="color: #cbd5e1; font-size: 0.875rem; line-height: 1.6;">
                        Kelola semua pengguna sistem termasuk siswa, guru, admin, dan super admin.
                    </p>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div style="background-color: #1e293b; border-radius: 1rem; padding: 2rem; border: 1px solid #334155; margin-bottom: 2rem;">
            <h2 style="color: #ffffff; margin-bottom: 1.5rem; font-size: 1.25rem;">
                <i class="fas fa-question-circle me-2"></i>Pertanyaan yang Sering Diajukan
                </h2>
                
            <div style="background-color: #2a2a3e; border-radius: 0.5rem; padding: 1.5rem; margin-bottom: 1rem;">
                <h4 style="color: #ffffff; margin-bottom: 0.5rem;">Bagaimana cara membuat tugas baru?</h4>
                <p style="color: #cbd5e1; font-size: 0.875rem; line-height: 1.6;">
                    Masuk ke menu "Manajemen Tugas" → Klik "Tambah Tugas Baru" → Isi form detail tugas → Pilih tipe tugas → Klik "Simpan".
                </p>
                    </div>
                    
            <div style="background-color: #2a2a3e; border-radius: 0.5rem; padding: 1.5rem; margin-bottom: 1rem;">
                <h4 style="color: #ffffff; margin-bottom: 0.5rem;">Bagaimana cara mengatur notifikasi?</h4>
                <p style="color: #cbd5e1; font-size: 0.875rem; line-height: 1.6;">
                    Masuk ke menu "Pengaturan" → Pilih tab "Notifikasi" → Aktifkan/nonaktifkan jenis notifikasi → Klik "Simpan".
                </p>
            </div>

            <div style="background-color: #2a2a3e; border-radius: 0.5rem; padding: 1.5rem; margin-bottom: 1rem;">
                <h4 style="color: #ffffff; margin-bottom: 0.5rem;">Bagaimana cara mengelola data IoT?</h4>
                <p style="color: #cbd5e1; font-size: 0.875rem; line-height: 1.6;">
                    Masuk ke menu "Manajemen IoT" → Lihat daftar perangkat → Monitor data sensor → Atur interval pengumpulan data.
                </p>
            </div>
        </div>

        <!-- Contact Support -->
        <div style="background-color: #1e293b; border-radius: 1rem; padding: 2rem; border: 1px solid #334155;">
            <h2 style="color: #ffffff; margin-bottom: 1.5rem; font-size: 1.25rem;">
                <i class="fas fa-envelope me-2"></i>Hubungi Tim Support
                </h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                <div style="text-align: center; padding: 1rem;">
                    <i class="fas fa-envelope" style="font-size: 2rem; color: #667eea; margin-bottom: 1rem;"></i>
                    <h3 style="color: #ffffff; margin-bottom: 0.5rem;">Email Support</h3>
                    <p style="color: #cbd5e1; font-size: 0.875rem;">support@terraassessment.com</p>
        </div>

                <div style="text-align: center; padding: 1rem;">
                    <i class="fas fa-phone" style="font-size: 2rem; color: #10b981; margin-bottom: 1rem;"></i>
                    <h3 style="color: #ffffff; margin-bottom: 0.5rem;">Telepon</h3>
                    <p style="color: #cbd5e1; font-size: 0.875rem;">+62 21 1234 5678</p>
            </div>

                <div style="text-align: center; padding: 1rem;">
                    <i class="fas fa-clock" style="font-size: 2rem; color: #f59e0b; margin-bottom: 1rem;"></i>
                    <h3 style="color: #ffffff; margin-bottom: 0.5rem;">Jam Kerja</h3>
                    <p style="color: #cbd5e1; font-size: 0.875rem;">Senin - Jumat: 08:00 - 17:00</p>
                </div>
            </div>
        </div>
@endsection
