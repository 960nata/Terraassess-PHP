@extends('layouts.unified-layout-new')

@section('title', 'Bantuan Student')

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

.accordion-collapse {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.accordion-collapse.show {
    max-height: 1000px;
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
    cursor: pointer;
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
        Bantuan Student
    </h1>
    <p class="page-description">Panduan dan dukungan untuk menggunakan sistem sebagai student</p>
</div>

<div class="help-container">
    <div class="help-grid">
        <div class="help-main">
            <h2 class="help-title">
                <i class="fas fa-book"></i>
                Panduan Penggunaan
            </h2>
            
            <div class="accordion" id="helpAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" onclick="toggleAccordion('collapse1')">
                            Cara Mengakses Tugas
                        </button>
                    </h2>
                    <div id="collapse1" class="accordion-collapse show">
                        <div class="accordion-body">
                            <ol>
                                <li>Klik menu "Tugas" di sidebar</li>
                                <li>Lihat daftar tugas yang diberikan oleh guru</li>
                                <li>Klik pada tugas untuk melihat detail</li>
                                <li>Baca instruksi dan deadline dengan teliti</li>
                                <li>Download file pendukung jika ada</li>
                                <li>Kerjakan tugas sesuai instruksi</li>
                            </ol>
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" onclick="toggleAccordion('collapse2')">
                            Cara Mengikuti Ujian
                        </button>
                    </h2>
                    <div id="collapse2" class="accordion-collapse">
                        <div class="accordion-body">
                            <ol>
                                <li>Klik menu "Ujian" di sidebar</li>
                                <li>Lihat daftar ujian yang tersedia</li>
                                <li>Pastikan waktu ujian sudah dimulai</li>
                                <li>Klik "Mulai Ujian" untuk memulai</li>
                                <li>Baca instruksi dengan teliti</li>
                                <li>Jawab soal sesuai waktu yang diberikan</li>
                                <li>Klik "Submit" untuk mengirim jawaban</li>
                            </ol>
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" onclick="toggleAccordion('collapse3')">
                            Cara Mengakses Materi
                        </button>
                    </h2>
                    <div id="collapse3" class="accordion-collapse">
                        <div class="accordion-body">
                            <ol>
                                <li>Klik menu "Materi" di sidebar</li>
                                <li>Lihat daftar materi yang tersedia</li>
                                <li>Klik pada materi untuk melihat detail</li>
                                <li>Download file materi jika diperlukan</li>
                                <li>Baca dan pelajari materi dengan teliti</li>
                                <li>Gunakan materi untuk mengerjakan tugas/ujian</li>
                            </ol>
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" onclick="toggleAccordion('collapse4')">
                            Cara Menggunakan IoT
                        </button>
                    </h2>
                    <div id="collapse4" class="accordion-collapse">
                        <div class="accordion-body">
                            <ol>
                                <li>Klik menu "IoT" di sidebar</li>
                                <li>Lihat daftar perangkat IoT yang tersedia</li>
                                <li>Klik pada perangkat untuk melihat detail</li>
                                <li>Monitor data real-time dari sensor</li>
                                <li>Download data untuk analisis</li>
                                <li>Gunakan data untuk penelitian atau tugas</li>
                            </ol>
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" onclick="toggleAccordion('collapse5')">
                            Cara Melihat Nilai dan Hasil
                        </button>
                    </h2>
                    <div id="collapse5" class="accordion-collapse">
                        <div class="accordion-body">
                            <ol>
                                <li>Klik menu "Dashboard" di sidebar</li>
                                <li>Lihat ringkasan nilai dan progress</li>
                                <li>Klik "Lihat Detail" untuk melihat detail nilai</li>
                                <li>Download sertifikat jika tersedia</li>
                                <li>Lihat feedback dari guru</li>
                                <li>Gunakan informasi untuk perbaikan</li>
                            </ol>
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" onclick="toggleAccordion('collapse6')">
                            Cara Mengelola Profil
                        </button>
                    </h2>
                    <div id="collapse6" class="accordion-collapse">
                        <div class="accordion-body">
                            <ol>
                                <li>Klik menu "Profil" di sidebar</li>
                                <li>Lihat informasi profil Anda</li>
                                <li>Klik "Edit Profil" untuk mengubah data</li>
                                <li>Update foto profil jika diperlukan</li>
                                <li>Ubah password untuk keamanan</li>
                                <li>Simpan perubahan yang telah dibuat</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="help-sidebar">
            <h3 class="help-title">
                <i class="fas fa-list"></i>
                Navigasi Cepat
            </h3>
            <ul class="help-nav">
                <li><a href="#" onclick="scrollToAccordion('collapse1')" class="active">Cara Mengakses Tugas</a></li>
                <li><a href="#" onclick="scrollToAccordion('collapse2')">Cara Mengikuti Ujian</a></li>
                <li><a href="#" onclick="scrollToAccordion('collapse3')">Cara Mengakses Materi</a></li>
                <li><a href="#" onclick="scrollToAccordion('collapse4')">Cara Menggunakan IoT</a></li>
                <li><a href="#" onclick="scrollToAccordion('collapse5')">Cara Melihat Nilai</a></li>
                <li><a href="#" onclick="scrollToAccordion('collapse6')">Cara Mengelola Profil</a></li>
            </ul>
            
            <div class="contact-card">
                <h3 class="contact-title">
                    <i class="fas fa-headset"></i>
                    Kontak Dukungan
                </h3>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span>support@terraassessment.com</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <span>+62 21 1234 5678</span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-clock"></i>
                    <span>Senin - Jumat, 08:00 - 17:00</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleAccordion(id) {
    const collapse = document.getElementById(id);
    const button = collapse.previousElementSibling.querySelector('.accordion-button');
    
    // Close all other accordions
    document.querySelectorAll('.accordion-collapse').forEach(item => {
        if (item.id !== id) {
            item.classList.remove('show');
            item.previousElementSibling.querySelector('.accordion-button').classList.add('collapsed');
        }
    });
    
    // Toggle current accordion
    if (collapse.classList.contains('show')) {
        collapse.classList.remove('show');
        button.classList.add('collapsed');
    } else {
        collapse.classList.add('show');
        button.classList.remove('collapsed');
    }
}

function scrollToAccordion(id) {
    const element = document.getElementById(id);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
        toggleAccordion(id);
    }
}
</script>
@endsection
