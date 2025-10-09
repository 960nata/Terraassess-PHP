@extends('layouts.unified-layout-new')

@section('title', 'Manajemen Materi - Terra Assessment')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-book"></i>
        Manajemen Materi
    </h1>
    <p class="page-description">Akses dan kelola materi pembelajaran Anda</p>
</div>

<div class="material-management-container">
    <!-- Search and Filter Section -->
    <div class="search-filter-section">
        <div class="search-bar">
            <div class="search-input-group">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari materi..." class="search-input">
            </div>
            <button class="btn btn-primary" onclick="searchMaterials()">
                <i class="fas fa-search"></i>
                Cari
            </button>
        </div>
        
        <div class="filter-controls">
            <div class="filter-group">
                <label for="subjectFilter" class="filter-label">Mata Pelajaran</label>
                <select id="subjectFilter" class="filter-select">
                    <option value="">Semua Mata Pelajaran</option>
                    <option value="matematika">Matematika</option>
                    <option value="fisika">Fisika</option>
                    <option value="kimia">Kimia</option>
                    <option value="biologi">Biologi</option>
                    <option value="bahasa-indonesia">Bahasa Indonesia</option>
                    <option value="bahasa-inggris">Bahasa Inggris</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="typeFilter" class="filter-label">Tipe Materi</label>
                <select id="typeFilter" class="filter-select">
                    <option value="">Semua Tipe</option>
                    <option value="document">Dokumen</option>
                    <option value="video">Video</option>
                    <option value="image">Gambar</option>
                    <option value="audio">Audio</option>
                    <option value="presentation">Presentasi</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="sortFilter" class="filter-label">Urutkan</label>
                <select id="sortFilter" class="filter-select">
                    <option value="newest">Terbaru</option>
                    <option value="oldest">Terlama</option>
                    <option value="alphabetical">A-Z</option>
                    <option value="subject">Mata Pelajaran</option>
                </select>
            </div>
            
            <div class="filter-actions">
                <button class="btn btn-outline" onclick="resetFilters()">
                    <i class="fas fa-undo"></i>
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Material Statistics -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">24</div>
                    <div class="stat-label">Total Materi</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">12</div>
                    <div class="stat-label">Dokumen</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-video"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">8</div>
                    <div class="stat-label">Video</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-download"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">156</div>
                    <div class="stat-label">Total Download</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Material List -->
    <div class="material-list-section">
        <div class="section-header">
            <h2 class="section-title">Daftar Materi</h2>
            <div class="view-controls">
                <button class="view-btn active" data-view="grid">
                    <i class="fas fa-th"></i>
                </button>
                <button class="view-btn" data-view="list">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
        
        <div class="material-list" id="materialList">
            <!-- Sample Materials -->
            <div class="material-card">
                <div class="material-thumbnail">
                    <div class="material-type-icon document">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="material-overlay">
                        <button class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-outline btn-sm">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>
                
                <div class="material-content">
                    <div class="material-header">
                        <h3 class="material-title">Panduan Praktikum Fisika</h3>
                        <span class="material-subject">Fisika</span>
                    </div>
                    
                    <p class="material-description">
                        Panduan lengkap untuk praktikum fisika dasar, termasuk prosedur dan analisis data.
                    </p>
                    
                    <div class="material-meta">
                        <div class="meta-item">
                            <i class="fas fa-user"></i>
                            <span>Guru: Pak Budi</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <span>15 Jan 2024</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-download"></i>
                            <span>45 downloads</span>
                        </div>
                    </div>
                </div>
                
                <div class="material-actions">
                    <button class="btn btn-primary">
                        <i class="fas fa-eye"></i>
                        Lihat
                    </button>
                    <button class="btn btn-outline">
                        <i class="fas fa-download"></i>
                        Download
                    </button>
                </div>
            </div>
            
            <div class="material-card">
                <div class="material-thumbnail">
                    <div class="material-type-icon video">
                        <i class="fas fa-play"></i>
                    </div>
                    <div class="material-overlay">
                        <button class="btn btn-primary btn-sm">
                            <i class="fas fa-play"></i>
                        </button>
                        <button class="btn btn-outline btn-sm">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>
                
                <div class="material-content">
                    <div class="material-header">
                        <h3 class="material-title">Video Tutorial Matematika</h3>
                        <span class="material-subject">Matematika</span>
                    </div>
                    
                    <p class="material-description">
                        Video tutorial tentang aljabar linear dan matriks dengan penjelasan step-by-step.
                    </p>
                    
                    <div class="material-meta">
                        <div class="meta-item">
                            <i class="fas fa-user"></i>
                            <span>Guru: Bu Sari</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <span>12 Jan 2024</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span>25 menit</span>
                        </div>
                    </div>
                </div>
                
                <div class="material-actions">
                    <button class="btn btn-primary">
                        <i class="fas fa-play"></i>
                        Putar
                    </button>
                    <button class="btn btn-outline">
                        <i class="fas fa-download"></i>
                        Download
                    </button>
                </div>
            </div>
            
            <div class="material-card">
                <div class="material-thumbnail">
                    <div class="material-type-icon presentation">
                        <i class="fas fa-presentation"></i>
                    </div>
                    <div class="material-overlay">
                        <button class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-outline btn-sm">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>
                
                <div class="material-content">
                    <div class="material-header">
                        <h3 class="material-title">Presentasi Kimia Organik</h3>
                        <span class="material-subject">Kimia</span>
                    </div>
                    
                    <p class="material-description">
                        Presentasi tentang senyawa organik dan reaksi-reaksi penting dalam kimia organik.
                    </p>
                    
                    <div class="material-meta">
                        <div class="meta-item">
                            <i class="fas fa-user"></i>
                            <span>Guru: Pak Ahmad</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <span>10 Jan 2024</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-download"></i>
                            <span>32 downloads</span>
                        </div>
                    </div>
                </div>
                
                <div class="material-actions">
                    <button class="btn btn-primary">
                        <i class="fas fa-eye"></i>
                        Lihat
                    </button>
                    <button class="btn btn-outline">
                        <i class="fas fa-download"></i>
                        Download
                    </button>
                </div>
            </div>
            
            <div class="material-card">
                <div class="material-thumbnail">
                    <div class="material-type-icon image">
                        <i class="fas fa-image"></i>
                    </div>
                    <div class="material-overlay">
                        <button class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-outline btn-sm">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>
                
                <div class="material-content">
                    <div class="material-header">
                        <h3 class="material-title">Diagram Sistem Pencernaan</h3>
                        <span class="material-subject">Biologi</span>
                    </div>
                    
                    <p class="material-description">
                        Diagram interaktif sistem pencernaan manusia dengan penjelasan detail setiap organ.
                    </p>
                    
                    <div class="material-meta">
                        <div class="meta-item">
                            <i class="fas fa-user"></i>
                            <span>Guru: Bu Dewi</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <span>8 Jan 2024</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-download"></i>
                            <span>28 downloads</span>
                        </div>
                    </div>
                </div>
                
                <div class="material-actions">
                    <button class="btn btn-primary">
                        <i class="fas fa-eye"></i>
                        Lihat
                    </button>
                    <button class="btn btn-outline">
                        <i class="fas fa-download"></i>
                        Download
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional-styles')
<style>
.material-management-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.search-filter-section {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.search-bar {
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
}

.search-input-group {
    position: relative;
    flex: 1;
}

.search-input-group i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
}

.search-input {
    width: 100%;
    padding: 12px 12px 12px 40px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.2s ease;
}

.search-input:focus {
    outline: none;
    border-color: #667eea;
}

.filter-controls {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-label {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.filter-select {
    padding: 10px 12px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    background: white;
    transition: border-color 0.2s ease;
}

.filter-select:focus {
    outline: none;
    border-color: #667eea;
}

.filter-actions {
    display: flex;
    gap: 12px;
}

.stats-section {
    margin-bottom: 32px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 28px;
    font-weight: 700;
    color: #1f2937;
    line-height: 1;
}

.stat-label {
    font-size: 14px;
    color: #6b7280;
    margin-top: 4px;
}

.material-list-section {
    background: white;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid #e5e7eb;
}

.section-title {
    font-size: 20px;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.view-controls {
    display: flex;
    gap: 8px;
}

.view-btn {
    width: 36px;
    height: 36px;
    border: 2px solid #e5e7eb;
    background: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.view-btn.active {
    background: #667eea;
    border-color: #667eea;
    color: white;
}

.material-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 24px;
}

.material-card {
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.2s ease;
}

.material-card:hover {
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
}

.material-thumbnail {
    position: relative;
    height: 200px;
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.material-type-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.material-type-icon.document {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.material-type-icon.video {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}

.material-type-icon.presentation {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.material-type-icon.image {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.material-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.material-card:hover .material-overlay {
    opacity: 1;
}

.material-content {
    padding: 20px;
}

.material-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}

.material-title {
    font-size: 16px;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
    flex: 1;
}

.material-subject {
    font-size: 11px;
    color: #6b7280;
    background: #f3f4f6;
    padding: 4px 8px;
    border-radius: 6px;
    margin-left: 8px;
}

.material-description {
    color: #6b7280;
    font-size: 14px;
    line-height: 1.5;
    margin-bottom: 16px;
}

.material-meta {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-bottom: 16px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: #6b7280;
}

.meta-item i {
    width: 14px;
    text-align: center;
}

.material-actions {
    display: flex;
    gap: 12px;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-sm {
    padding: 8px 12px;
    font-size: 12px;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-outline {
    background: transparent;
    color: #667eea;
    border: 2px solid #667eea;
}

.btn-outline:hover {
    background: #667eea;
    color: white;
}

@media (max-width: 768px) {
    .material-list {
        grid-template-columns: 1fr;
    }
    
    .search-bar {
        flex-direction: column;
    }
    
    .filter-controls {
        grid-template-columns: 1fr;
    }
    
    .material-header {
        flex-direction: column;
        gap: 8px;
    }
    
    .material-actions {
        flex-direction: column;
    }
}
</style>
@endsection

@section('additional-scripts')
<script>
function searchMaterials() {
    const searchTerm = document.getElementById('searchInput').value;
    console.log('Searching for:', searchTerm);
    // Implement search logic here
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('subjectFilter').value = '';
    document.getElementById('typeFilter').value = '';
    document.getElementById('sortFilter').value = 'newest';
    
    console.log('Filters reset');
    // Implement reset logic here
}

// View toggle functionality
document.querySelectorAll('.view-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const view = this.dataset.view;
        const materialList = document.getElementById('materialList');
        
        if (view === 'list') {
            materialList.style.gridTemplateColumns = '1fr';
        } else {
            materialList.style.gridTemplateColumns = 'repeat(auto-fill, minmax(350px, 1fr))';
        }
    });
});

// Search on Enter key
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        searchMaterials();
    }
});
</script>
@endsection
