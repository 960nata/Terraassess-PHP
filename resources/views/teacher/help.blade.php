@extends('layouts.unified-layout')

@section('title', 'Bantuan Guru')

@section('content')
@include('components.page-header', [
    'title' => 'Bantuan',
    'description' => 'Panduan dan dukungan untuk menggunakan sistem',
    'icon' => 'fas fa-question-circle',
    'breadcrumbs' => [
        ['text' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['text' => 'Bantuan']
    ]
])

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Panduan Penggunaan</h3>
            </div>
            <div class="card-body">
                <div class="accordion" id="helpAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                Cara Membuat Tugas Baru
                            </button>
                        </h2>
                        <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                <ol>
                                    <li>Klik menu "Manajemen Tugas" di sidebar</li>
                                    <li>Klik tombol "Buat Tugas Baru"</li>
                                    <li>Isi informasi tugas (judul, deskripsi, deadline)</li>
                                    <li>Pilih kelas yang akan menerima tugas</li>
                                    <li>Upload file pendukung jika diperlukan</li>
                                    <li>Klik "Simpan" untuk menyimpan tugas</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                Cara Membuat Ujian
                            </button>
                        </h2>
                        <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                <ol>
                                    <li>Klik menu "Manajemen Ujian" di sidebar</li>
                                    <li>Klik tombol "Buat Ujian Baru"</li>
                                    <li>Isi informasi ujian (nama, durasi, instruksi)</li>
                                    <li>Tambah soal-soal ujian</li>
                                    <li>Atur pengaturan ujian (waktu mulai, durasi)</li>
                                    <li>Pilih kelas yang akan mengikuti ujian</li>
                                    <li>Klik "Aktifkan Ujian" untuk memulai</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                Cara Mengelola Materi
                            </button>
                        </h2>
                        <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                <ol>
                                    <li>Klik menu "Manajemen Materi" di sidebar</li>
                                    <li>Klik tombol "Tambah Materi"</li>
                                    <li>Isi judul dan deskripsi materi</li>
                                    <li>Upload file materi (PDF, PPT, Video)</li>
                                    <li>Pilih mata pelajaran dan kelas</li>
                                    <li>Atur visibilitas materi</li>
                                    <li>Klik "Simpan" untuk menyimpan materi</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                Cara Menggunakan IoT Dashboard
                            </button>
                        </h2>
                        <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                <ol>
                                    <li>Klik menu "Manajemen IoT" di sidebar</li>
                                    <li>Daftarkan perangkat IoT baru</li>
                                    <li>Monitor data sensor real-time</li>
                                    <li>Buat tugas penelitian IoT</li>
                                    <li>Lihat hasil penelitian siswa</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Kontak Dukungan</h3>
            </div>
            <div class="card-body">
                <div class="contact-item mb-3">
                    <i class="fas fa-envelope text-primary"></i>
                    <div class="ms-3">
                        <strong>Email</strong><br>
                        <small>support@terraassessment.com</small>
                    </div>
                </div>
                
                <div class="contact-item mb-3">
                    <i class="fas fa-phone text-success"></i>
                    <div class="ms-3">
                        <strong>Telepon</strong><br>
                        <small>+62 21 1234 5678</small>
                    </div>
                </div>
                
                <div class="contact-item mb-3">
                    <i class="fas fa-clock text-warning"></i>
                    <div class="ms-3">
                        <strong>Jam Operasional</strong><br>
                        <small>Senin - Jumat: 08:00 - 17:00</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">FAQ</h3>
            </div>
            <div class="card-body">
                <div class="faq-item mb-3">
                    <strong>Q: Bagaimana cara reset password?</strong><br>
                    <small class="text-muted">A: Klik "Lupa Password" di halaman login, atau gunakan menu Pengaturan.</small>
                </div>
                
                <div class="faq-item mb-3">
                    <strong>Q: Apakah bisa mengubah deadline tugas?</strong><br>
                    <small class="text-muted">A: Ya, buka detail tugas dan klik "Edit" untuk mengubah deadline.</small>
                </div>
                
                <div class="faq-item mb-3">
                    <strong>Q: Bagaimana cara melihat nilai siswa?</strong><br>
                    <small class="text-muted">A: Gunakan menu "Laporan" untuk melihat performa dan nilai siswa.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.contact-item {
    display: flex;
    align-items: center;
}

.faq-item {
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
}

.faq-item:last-child {
    border-bottom: none;
}
</style>
@endsection
