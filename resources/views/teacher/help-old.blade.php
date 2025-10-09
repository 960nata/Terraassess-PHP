@extends('layouts.unified-layout-new')

@section('title', 'Bantuan Guru')

@section('styles')
<style>
.help-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.help-grid {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 2rem;
    margin-top: 2rem;
}

.help-main {
    background: #1e293b;
    border-radius: 1rem;
    padding: 2rem;
    border: 1px solid #334155;
}

.help-sidebar {
    background: #1e293b;
    border-radius: 1rem;
    padding: 1.5rem;
    border: 1px solid #334155;
    height: fit-content;
    position: sticky;
    top: 2rem;
}

.help-title {
    color: #ffffff;
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.help-title i {
    color: #667eea;
}

.accordion {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.accordion-item {
    background: #2a2a3e;
    border: 1px solid #334155;
    border-radius: 0.75rem;
    overflow: hidden;
    transition: all 0.3s ease;
}

.accordion-item:hover {
    border-color: #475569;
}

.accordion-header {
    margin: 0;
}

.accordion-button {
    width: 100%;
    background: transparent;
    border: none;
    color: #ffffff;
    padding: 1.25rem 1.5rem;
    text-align: left;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
}

.accordion-button:hover {
    background: #334155;
}

.accordion-button:not(.collapsed) {
    background: #334155;
    color: #667eea;
}

.accordion-button::after {
    content: '\f078';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    transition: transform 0.3s ease;
}

.accordion-button:not(.collapsed)::after {
    transform: rotate(180deg);
}

.accordion-body {
    padding: 0 1.5rem 1.5rem 1.5rem;
    color: #cbd5e1;
    line-height: 1.6;
}

.accordion-body ol {
    margin: 0;
    padding-left: 1.5rem;
}

.accordion-body li {
    margin-bottom: 0.5rem;
}

.help-nav {
    list-style: none;
    padding: 0;
    margin: 0;
}

.help-nav li {
    margin-bottom: 0.5rem;
}

.help-nav a {
    color: #cbd5e1;
    text-decoration: none;
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
    display: block;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.help-nav a:hover {
    background: #334155;
    color: #667eea;
}

.help-nav a.active {
    background: #667eea;
    color: #ffffff;
}

.contact-card {
    background: #2a2a3e;
    border: 1px solid #334155;
    border-radius: 0.75rem;
    padding: 1.5rem;
    margin-top: 1.5rem;
}

.contact-title {
    color: #ffffff;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #cbd5e1;
    margin-bottom: 0.75rem;
    font-size: 0.9rem;
}

.contact-item i {
    color: #667eea;
    width: 16px;
}

@media (max-width: 1024px) {
    .help-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .help-sidebar {
        position: static;
        order: -1;
    }
}

@media (max-width: 768px) {
    .help-container {
        padding: 0 0.5rem;
    }
    
    .help-main,
    .help-sidebar {
        padding: 1.5rem;
    }
    
    .accordion-button {
        padding: 1rem;
        font-size: 0.9rem;
    }
    
    .accordion-body {
        padding: 0 1rem 1rem 1rem;
    }
}
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-question-circle"></i>
        Bantuan
    </h1>
    <p class="page-description">Panduan dan dukungan untuk menggunakan sistem</p>
</div>

<div class="help-container">
    <div class="help-grid">
        <div class="help-main">
            <h2 class="help-title">
                <i class="fas fa-book"></i>
                Panduan Penggunaan
            </h2>
            
            <div class="accordion" id="helpAccordion">
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
