@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Bantuan Admin')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-question-circle"></i>
        Pusat Bantuan Admin
    </h1>
    <p class="page-description">Dapatkan bantuan dan panduan untuk menggunakan sistem Terra Assessment</p>
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
                <p>Panduan dasar untuk menggunakan sistem</p>
            </div>
            
            <div class="quick-help-card" onclick="scrollToSection('user-management')">
                <div class="help-icon green">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Manajemen Pengguna</h3>
                <p>Cara mengelola pengguna dan hak akses</p>
            </div>
            
            <div class="quick-help-card" onclick="scrollToSection('task-management')">
                <div class="help-icon purple">
                    <i class="fas fa-tasks"></i>
                </div>
                <h3>Manajemen Tugas</h3>
                <p>Membuat dan mengelola tugas</p>
            </div>
            
            <div class="quick-help-card" onclick="scrollToSection('reports')">
                <div class="help-icon orange">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Laporan & Analitik</h3>
                <p>Melihat laporan dan analisis data</p>
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
                <h3>Selamat Datang di Terra Assessment</h3>
                <p>Sebagai Admin, Anda memiliki akses penuh untuk mengelola sistem pembelajaran. Berikut adalah panduan dasar untuk memulai:</p>
                
                <div class="help-steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h4>Dashboard</h4>
                            <p>Mulai dari dashboard untuk melihat ringkasan sistem dan akses cepat ke fitur utama.</p>
                        </div>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h4>Manajemen Pengguna</h4>
                            <p>Kelola pengguna sistem termasuk guru, siswa, dan admin lainnya.</p>
                        </div>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h4>Manajemen Kelas</h4>
                            <p>Buat dan atur kelas untuk mengorganisir siswa dan mata pelajaran.</p>
                        </div>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h4>Mata Pelajaran</h4>
                            <p>Tambahkan mata pelajaran dan atur akses untuk guru yang mengajar.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Management -->
        <div class="help-section" id="user-management">
            <h2 class="help-section-title">
                <i class="fas fa-users"></i>
                Manajemen Pengguna
            </h2>
            
            <div class="help-article">
                <h3>Menambahkan Pengguna Baru</h3>
                <p>Untuk menambahkan pengguna baru ke sistem:</p>
                
                <ol class="help-list">
                    <li>Buka menu <strong>Manajemen Pengguna</strong></li>
                    <li>Klik tombol <strong>Tambah Pengguna</strong></li>
                    <li>Isi informasi pengguna (nama, email, role)</li>
                    <li>Atur kelas untuk siswa atau mata pelajaran untuk guru</li>
                    <li>Klik <strong>Simpan</strong> untuk menyimpan</li>
                </ol>
                
                <div class="help-tip">
                    <i class="fas fa-lightbulb"></i>
                    <p><strong>Tips:</strong> Pastikan email yang digunakan unik dan valid. Password default akan dikirim ke email pengguna.</p>
                </div>
            </div>
            
            <div class="help-article">
                <h3>Mengelola Hak Akses</h3>
                <p>Sistem Terra Assessment memiliki 4 level akses:</p>
                
                <div class="access-levels">
                    <div class="access-level">
                        <div class="access-icon superadmin">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div class="access-info">
                            <h4>Super Admin</h4>
                            <p>Akses penuh ke semua fitur sistem</p>
                        </div>
                    </div>
                    
                    <div class="access-level">
                        <div class="access-icon admin">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="access-info">
                            <h4>Admin</h4>
                            <p>Akses untuk mengelola pengguna dan konten</p>
                        </div>
                    </div>
                    
                    <div class="access-level">
                        <div class="access-icon teacher">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="access-info">
                            <h4>Guru</h4>
                            <p>Akses untuk membuat tugas, ujian, dan materi</p>
                        </div>
                    </div>
                    
                    <div class="access-level">
                        <div class="access-icon student">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="access-info">
                            <h4>Siswa</h4>
                            <p>Akses untuk mengerjakan tugas dan ujian</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Task Management -->
        <div class="help-section" id="task-management">
            <h2 class="help-section-title">
                <i class="fas fa-tasks"></i>
                Manajemen Tugas
            </h2>
            
            <div class="help-article">
                <h3>Membuat Tugas Baru</h3>
                <p>Sebagai Admin, Anda dapat membuat tugas untuk semua kelas:</p>
                
                <ol class="help-list">
                    <li>Buka menu <strong>Manajemen Tugas</strong></li>
                    <li>Klik <strong>Buat Tugas Baru</strong></li>
                    <li>Pilih jenis tugas (Pilihan Ganda, Essay, atau Kelompok)</li>
                    <li>Isi detail tugas (judul, deskripsi, deadline)</li>
                    <li>Pilih kelas yang akan menerima tugas</li>
                    <li>Atur bobot nilai dan kriteria penilaian</li>
                    <li>Klik <strong>Publikasikan</strong> untuk mengirim ke siswa</li>
                </ol>
            </div>
            
            <div class="help-article">
                <h3>Memantau Progress Tugas</h3>
                <p>Anda dapat memantau progress pengerjaan tugas:</p>
                
                <ul class="help-list">
                    <li>Lihat daftar tugas yang telah dibuat</li>
                    <li>Monitor jumlah siswa yang telah mengumpulkan</li>
                    <li>Periksa kualitas jawaban siswa</li>
                    <li>Berikan feedback dan nilai</li>
                </ul>
            </div>
        </div>

        <!-- Reports -->
        <div class="help-section" id="reports">
            <h2 class="help-section-title">
                <i class="fas fa-chart-line"></i>
                Laporan & Analitik
            </h2>
            
            <div class="help-article">
                <h3>Mengakses Laporan</h3>
                <p>Sistem menyediakan berbagai jenis laporan:</p>
                
                <div class="report-types">
                    <div class="report-type">
                        <h4><i class="fas fa-chart-line"></i> Laporan</h4>
                        <p>Laporan sistem secara umum termasuk statistik pengguna, tugas, dan ujian</p>
                    </div>
                    
                    <div class="report-type">
                        <h4><i class="fas fa-chart-bar"></i> Analitik</h4>
                        <p>Analisis mendalam dengan grafik dan statistik performa sistem</p>
                    </div>
                </div>
            </div>
            
            <div class="help-article">
                <h3>Export Data</h3>
                <p>Anda dapat mengexport data dalam berbagai format:</p>
                
                <ul class="help-list">
                    <li><strong>Excel (.xlsx):</strong> Untuk analisis data lanjutan</li>
                    <li><strong>PDF:</strong> Untuk laporan resmi</li>
                    <li><strong>CSV:</strong> Untuk integrasi dengan sistem lain</li>
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
                    <h4>Q: Bagaimana jika lupa password?</h4>
                    <p>A: Gunakan fitur "Lupa Password" di halaman login. Link reset akan dikirim ke email Anda.</p>
                </div>
                
                <div class="faq-item">
                    <h4>Q: Mengapa tidak bisa mengakses menu tertentu?</h4>
                    <p>A: Pastikan akun Anda memiliki role yang sesuai. Hubungi Super Admin jika perlu perubahan akses.</p>
                </div>
                
                <div class="faq-item">
                    <h4>Q: Bagaimana cara backup data?</h4>
                    <p>A: Pergi ke Pengaturan > Manajemen Data > Backup Data untuk membuat backup otomatis.</p>
                </div>
                
                <div class="faq-item">
                    <h4>Q: Sistem berjalan lambat, apa yang harus dilakukan?</h4>
                    <p>A: Coba clear cache di Pengaturan > Manajemen Data > Clear Cache.</p>
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
                <a href="mailto:support@terraassessment.com" class="support-option">
                    <i class="fas fa-envelope"></i>
                    <span>Email Support</span>
                </a>
                
                <a href="tel:+6281234567890" class="support-option">
                    <i class="fas fa-phone"></i>
                    <span>Telepon</span>
                </a>
                
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

.access-levels {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}

.access-level {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
}

.access-icon {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
}

.access-icon.superadmin { background: linear-gradient(135deg, #f59e0b, #d97706); }
.access-icon.admin { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
.access-icon.teacher { background: linear-gradient(135deg, #10b981, #059669); }
.access-icon.student { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }

.access-info h4 {
    color: white;
    font-size: 1rem;
    font-weight: 600;
    margin: 0 0 0.25rem 0;
}

.access-info p {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.875rem;
    margin: 0;
}

.report-types {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}

.report-type {
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
}

.report-type h4 {
    color: white;
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.report-type p {
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
    
    .access-levels {
        grid-template-columns: 1fr;
    }
    
    .report-types {
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

function openLiveChat() {
    alert('Fitur Live Chat akan segera tersedia. Silakan gunakan Email Support atau Telepon untuk bantuan langsung.');
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