@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Bantuan Siswa')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-question-circle"></i>
        Pusat Bantuan Siswa
    </h1>
    <p class="page-description">Dapatkan bantuan dan panduan untuk menggunakan platform pembelajaran Terra Assessment</p>
</div>

<div class="help-container">
    <!-- Quick Help -->
    <div class="quick-help-section">
        <h2 class="section-title">
            <i class="fas fa-bolt"></i>
            Bantuan Cepat
        </h2>
        
        <div class="quick-help-grid">
            <div class="quick-help-card" onclick="scrollToSection('getting-started')">
                <div class="help-icon blue">
                    <i class="fas fa-play"></i>
                </div>
                <h3>Memulai</h3>
                <p>Panduan dasar untuk menggunakan platform</p>
            </div>
            
            <div class="quick-help-card" onclick="scrollToSection('tasks')">
                <div class="help-icon green">
                    <i class="fas fa-tasks"></i>
                </div>
                <h3>Mengerjakan Tugas</h3>
                <p>Cara mengerjakan dan mengumpulkan tugas</p>
            </div>
            
            <div class="quick-help-card" onclick="scrollToSection('exams')">
                <div class="help-icon purple">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h3>Mengikuti Ujian</h3>
                <p>Panduan mengikuti ujian online</p>
            </div>
            
            <div class="quick-help-card" onclick="scrollToSection('materials')">
                <div class="help-icon orange">
                    <i class="fas fa-book"></i>
                </div>
                <h3>Materi Pembelajaran</h3>
                <p>Akses dan download materi pembelajaran</p>
            </div>
        </div>
    </div>

    <!-- Search Help -->
    <div class="search-help-section">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="help-search" placeholder="Cari bantuan atau pertanyaan...">
        </div>
    </div>

    <!-- Help Content -->
    <div class="help-content">
        <!-- Getting Started -->
        <div class="help-section" id="getting-started">
            <h2 class="help-section-title">
                <i class="fas fa-play"></i>
                Memulai dengan Terra Assessment
            </h2>
            
            <div class="help-article">
                <h3>Selamat Datang di Platform Pembelajaran</h3>
                <p>Sebagai siswa, Anda dapat mengakses berbagai fitur pembelajaran melalui platform Terra Assessment. Berikut adalah panduan dasar untuk memulai:</p>
                
                <div class="help-steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h4>Login ke Akun</h4>
                            <p>Gunakan email dan password yang diberikan oleh sekolah untuk login ke platform.</p>
                        </div>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h4>Dashboard Siswa</h4>
                            <p>Setelah login, Anda akan melihat dashboard dengan ringkasan tugas, ujian, dan materi terbaru.</p>
                        </div>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h4>Navigasi Menu</h4>
                            <p>Gunakan menu sidebar untuk mengakses fitur-fitur seperti Tugas, Ujian, Materi, dan IoT Research.</p>
                        </div>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h4>Profil Saya</h4>
                            <p>Lengkapi profil Anda dan ubah password default untuk keamanan akun.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tasks -->
        <div class="help-section" id="tasks">
            <h2 class="help-section-title">
                <i class="fas fa-tasks"></i>
                Mengerjakan Tugas
            </h2>
            
            <div class="help-article">
                <h3>Melihat Tugas yang Tersedia</h3>
                <p>Untuk melihat tugas yang diberikan oleh guru:</p>
                
                <ol class="help-list">
                    <li>Klik menu <strong>Tugas Saya</strong> di sidebar</li>
                    <li>Lihat daftar tugas yang tersedia</li>
                    <li>Perhatikan deadline dan status tugas</li>
                    <li>Klik pada tugas untuk melihat detail</li>
                </ol>
            </div>
            
            <div class="help-article">
                <h3>Mengerjakan Tugas</h3>
                <p>Setelah memilih tugas, ikuti langkah-langkah berikut:</p>
                
                <div class="task-types">
                    <div class="task-type">
                        <h4><i class="fas fa-list"></i> Tugas Pilihan Ganda</h4>
                        <ol class="help-list">
                            <li>Baca soal dengan teliti</li>
                            <li>Pilih jawaban yang paling tepat</li>
                            <li>Pastikan semua soal terjawab</li>
                            <li>Klik <strong>Submit</strong> untuk mengumpulkan</li>
                        </ol>
                    </div>
                    
                    <div class="task-type">
                        <h4><i class="fas fa-file-alt"></i> Tugas Essay</h4>
                        <ol class="help-list">
                            <li>Baca instruksi tugas dengan seksama</li>
                            <li>Gunakan editor teks untuk menulis jawaban</li>
                            <li>Periksa ejaan dan tata bahasa</li>
                            <li>Upload file jika diperlukan</li>
                            <li>Klik <strong>Submit</strong> untuk mengumpulkan</li>
                        </ol>
                    </div>
                    
                    <div class="task-type">
                        <h4><i class="fas fa-users"></i> Tugas Kelompok</h4>
                        <ol class="help-list">
                            <li>Bergabung dengan kelompok yang ditentukan</li>
                            <li>Bagi tugas dengan anggota kelompok</li>
                            <li>Gunakan fitur kolaborasi untuk berdiskusi</li>
                            <li>Submit hasil kerja kelompok</li>
                        </ol>
                    </div>
                </div>
            </div>
            
            <div class="help-article">
                <h3>Melihat Nilai dan Feedback</h3>
                <p>Setelah mengumpulkan tugas:</p>
                
                <ul class="help-list">
                    <li>Nilai akan muncul setelah guru selesai menilai</li>
                    <li>Baca feedback dari guru untuk perbaikan</li>
                    <li>Lihat detail penilaian di halaman tugas</li>
                    <li>Gunakan feedback untuk tugas selanjutnya</li>
                </ul>
            </div>
        </div>

        <!-- Exams -->
        <div class="help-section" id="exams">
            <h2 class="help-section-title">
                <i class="fas fa-clipboard-check"></i>
                Mengikuti Ujian
            </h2>
            
            <div class="help-article">
                <h3>Persiapan Ujian</h3>
                <p>Sebelum mengikuti ujian, pastikan:</p>
                
                <ul class="help-list">
                    <li>Koneksi internet stabil</li>
                    <li>Browser terbaru (Chrome, Firefox, Safari)</li>
                    <li>Waktu yang cukup untuk menyelesaikan ujian</li>
                    <li>Materi pembelajaran sudah dipelajari</li>
                </ul>
            </div>
            
            <div class="help-article">
                <h3>Mengikuti Ujian Online</h3>
                <p>Langkah-langkah mengikuti ujian:</p>
                
                <ol class="help-list">
                    <li>Klik menu <strong>Ujian Saya</strong></li>
                    <li>Pilih ujian yang akan diikuti</li>
                    <li>Baca instruksi ujian dengan teliti</li>
                    <li>Klik <strong>Mulai Ujian</strong></li>
                    <li>Jawab soal satu per satu</li>
                    <li>Gunakan tombol navigasi untuk berpindah soal</li>
                    <li>Periksa kembali jawaban sebelum submit</li>
                    <li>Klik <strong>Selesai</strong> untuk mengumpulkan</li>
                </ol>
                
                <div class="help-tip">
                    <i class="fas fa-lightbulb"></i>
                    <p><strong>Tips:</strong> Jangan refresh halaman saat ujian berlangsung. Pastikan koneksi internet stabil selama ujian.</p>
                </div>
            </div>
            
            <div class="help-article">
                <h3>Jenis Ujian</h3>
                <p>Platform mendukung berbagai jenis ujian:</p>
                
                <div class="exam-types">
                    <div class="exam-type">
                        <h4><i class="fas fa-list"></i> Pilihan Ganda</h4>
                        <p>Pilih satu jawaban yang paling tepat dari beberapa pilihan yang tersedia.</p>
                    </div>
                    
                    <div class="exam-type">
                        <h4><i class="fas fa-file-alt"></i> Essay</h4>
                        <p>Jawab pertanyaan dengan menuliskan penjelasan lengkap menggunakan editor teks.</p>
                    </div>
                    
                    <div class="exam-type">
                        <h4><i class="fas fa-random"></i> Campuran</h4>
                        <p>Kombinasi soal pilihan ganda dan essay dalam satu ujian.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Materials -->
        <div class="help-section" id="materials">
            <h2 class="help-section-title">
                <i class="fas fa-book"></i>
                Materi Pembelajaran
            </h2>
            
            <div class="help-article">
                <h3>Akses Materi Pembelajaran</h3>
                <p>Untuk mengakses materi pembelajaran:</p>
                
                <ol class="help-list">
                    <li>Klik menu <strong>Materi Saya</strong></li>
                    <li>Lihat daftar materi yang tersedia</li>
                    <li>Filter berdasarkan mata pelajaran jika perlu</li>
                    <li>Klik pada materi untuk membuka</li>
                </ol>
            </div>
            
            <div class="help-article">
                <h3>Jenis Materi</h3>
                <p>Materi pembelajaran tersedia dalam berbagai format:</p>
                
                <div class="material-types">
                    <div class="material-type">
                        <h4><i class="fas fa-file-pdf"></i> Dokumen PDF</h4>
                        <p>Materi dalam format PDF yang dapat dibaca langsung atau didownload.</p>
                    </div>
                    
                    <div class="material-type">
                        <h4><i class="fas fa-file-word"></i> Dokumen Word</h4>
                        <p>Materi dalam format Word yang dapat diedit dan disesuaikan.</p>
                    </div>
                    
                    <div class="material-type">
                        <h4><i class="fas fa-video"></i> Video Pembelajaran</h4>
                        <p>Video tutorial dan penjelasan materi yang dapat diputar langsung.</p>
                    </div>
                    
                    <div class="material-type">
                        <h4><i class="fas fa-image"></i> Gambar dan Infografis</h4>
                        <p>Visualisasi materi dalam bentuk gambar dan infografis.</p>
                    </div>
                </div>
            </div>
            
            <div class="help-article">
                <h3>Download dan Organisasi</h3>
                <p>Tips untuk mengorganisir materi pembelajaran:</p>
                
                <ul class="help-list">
                    <li>Download materi penting untuk akses offline</li>
                    <li>Buat folder terpisah untuk setiap mata pelajaran</li>
                    <li>Beri nama file yang mudah diingat</li>
                    <li>Backup materi penting di cloud storage</li>
                </ul>
            </div>
        </div>

        <!-- IoT Research -->
        <div class="help-section" id="iot-research">
            <h2 class="help-section-title">
                <i class="fas fa-microscope"></i>
                Penelitian IoT
            </h2>
            
            <div class="help-article">
                <h3>Mengakses Fitur IoT</h3>
                <p>Platform menyediakan fitur penelitian IoT untuk eksplorasi teknologi:</p>
                
                <ol class="help-list">
                    <li>Klik menu <strong>Penelitian IoT</strong></li>
                    <li>Lihat proyek penelitian yang tersedia</li>
                    <li>Pilih proyek yang menarik minat Anda</li>
                    <li>Ikuti panduan penelitian yang disediakan</li>
                </ol>
            </div>
            
            <div class="help-article">
                <h3>Mengumpulkan Data IoT</h3>
                <p>Untuk mengumpulkan data dari perangkat IoT:</p>
                
                <ul class="help-list">
                    <li>Gunakan sensor yang tersedia di laboratorium</li>
                    <li>Catat data secara real-time</li>
                    <li>Upload data ke platform untuk analisis</li>
                    <li>Buat laporan hasil penelitian</li>
                </ul>
            </div>
        </div>

        <!-- Troubleshooting -->
        <div class="help-section" id="troubleshooting">
            <h2 class="help-section-title">
                <i class="fas fa-tools"></i>
                Troubleshooting
            </h2>
            
            <div class="help-article">
                <h3>Masalah Umum</h3>
                
                <div class="faq-item">
                    <h4>Q: Tidak bisa login ke akun?</h4>
                    <p>A: Pastikan email dan password benar. Jika lupa password, gunakan fitur "Lupa Password" atau hubungi guru.</p>
                </div>
                
                <div class="faq-item">
                    <h4>Q: Tugas tidak bisa dikumpulkan?</h4>
                    <p>A: Periksa koneksi internet dan pastikan belum melewati deadline. Coba refresh halaman dan submit ulang.</p>
                </div>
                
                <div class="faq-item">
                    <h4>Q: Video materi tidak bisa diputar?</h4>
                    <p>A: Pastikan browser mendukung HTML5 video. Coba gunakan browser lain atau update browser ke versi terbaru.</p>
                </div>
                
                <div class="faq-item">
                    <h4>Q: File tidak bisa didownload?</h4>
                    <p>A: Periksa pengaturan browser yang memblokir download. Pastikan pop-up blocker tidak aktif.</p>
                </div>
                
                <div class="faq-item">
                    <h4>Q: Ujian terputus di tengah jalan?</h4>
                    <p>A: Segera hubungi guru atau admin. Jawaban yang sudah tersimpan akan tetap ada.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Support -->
    <div class="contact-support">
        <div class="support-card">
            <h3>
                <i class="fas fa-headset"></i>
                Butuh Bantuan Lebih Lanjut?
            </h3>
            <p>Jika Anda tidak menemukan jawaban yang Anda cari, tim support kami siap membantu.</p>
            
            <div class="support-options">
                <a href="mailto:student-support@terraassessment.com" class="support-option">
                    <i class="fas fa-envelope"></i>
                    <span>Email Support</span>
                </a>
                
                <button class="support-option" onclick="contactTeacher()">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Hubungi Guru</span>
                </button>
                
                <button class="support-option" onclick="openLiveChat()">
                    <i class="fas fa-comments"></i>
                    <span>Live Chat</span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.help-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.quick-help-section {
    margin-bottom: 3rem;
}

.section-title {
    color: white;
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.quick-help-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.quick-help-card {
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.quick-help-card:hover {
    transform: translateY(-5px);
    border-color: rgba(255, 255, 255, 0.2);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

.help-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    margin: 0 auto 1rem;
}

.help-icon.blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
.help-icon.green { background: linear-gradient(135deg, #10b981, #059669); }
.help-icon.purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
.help-icon.orange { background: linear-gradient(135deg, #f59e0b, #d97706); }

.quick-help-card h3 {
    color: white;
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
}

.quick-help-card p {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.875rem;
    margin: 0;
}

.search-help-section {
    margin-bottom: 3rem;
}

.search-box {
    position: relative;
    max-width: 600px;
    margin: 0 auto;
}

.search-box i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255, 255, 255, 0.5);
}

.search-box input {
    width: 100%;
    padding: 1rem 1rem 1rem 3rem;
    background: rgba(15, 23, 42, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    color: white;
    font-size: 1rem;
}

.search-box input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.help-content {
    margin-bottom: 3rem;
}

.help-section {
    margin-bottom: 3rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.help-section:last-child {
    border-bottom: none;
}

.help-section-title {
    color: white;
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.help-article {
    background: rgba(15, 23, 42, 0.5);
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.help-article h3 {
    color: white;
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0 0 1rem 0;
}

.help-article p {
    color: rgba(255, 255, 255, 0.8);
    line-height: 1.6;
    margin-bottom: 1rem;
}

.help-steps {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.step {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    flex-shrink: 0;
}

.step-content h4 {
    color: white;
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
}

.step-content p {
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

.help-list {
    color: rgba(255, 255, 255, 0.8);
    padding-left: 1.5rem;
}

.help-list li {
    margin-bottom: 0.5rem;
}

.help-tip {
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.3);
    border-radius: 8px;
    padding: 1rem;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    margin-top: 1rem;
}

.help-tip i {
    color: #3b82f6;
    margin-top: 0.25rem;
}

.help-tip p {
    margin: 0;
    color: rgba(255, 255, 255, 0.9);
}

.task-types, .exam-types, .material-types {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}

.task-type, .exam-type, .material-type {
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
}

.task-type h4, .exam-type h4, .material-type h4 {
    color: white;
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.task-type p, .exam-type p, .material-type p {
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
}

.faq-item {
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.faq-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.faq-item h4 {
    color: white;
    font-size: 1rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
}

.faq-item p {
    color: rgba(255, 255, 255, 0.8);
    margin: 0;
}

.contact-support {
    margin-top: 3rem;
}

.support-card {
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
}

.support-card h3 {
    color: white;
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0 0 1rem 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
}

.support-card p {
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 2rem;
}

.support-options {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.support-option {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.3);
    border-radius: 8px;
    color: #3b82f6;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
}

.support-option:hover {
    background: rgba(59, 130, 246, 0.2);
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .help-container {
        padding: 1rem;
    }
    
    .quick-help-grid {
        grid-template-columns: 1fr;
    }
    
    .task-types, .exam-types, .material-types {
        grid-template-columns: 1fr;
    }
    
    .support-options {
        flex-direction: column;
        align-items: center;
    }
}
</style>

<script>
function scrollToSection(sectionId) {
    const element = document.getElementById(sectionId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
}

function contactTeacher() {
    alert('Fitur hubungi guru akan segera tersedia. Silakan gunakan Email Support untuk bantuan langsung.');
}

function openLiveChat() {
    alert('Fitur Live Chat akan segera tersedia. Silakan gunakan Email Support untuk bantuan langsung.');
}

// Search functionality
document.getElementById('help-search').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const helpSections = document.querySelectorAll('.help-section');
    
    helpSections.forEach(section => {
        const text = section.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            section.style.display = 'block';
        } else {
            section.style.display = searchTerm ? 'none' : 'block';
        }
    });
});
</script>
@endsection
