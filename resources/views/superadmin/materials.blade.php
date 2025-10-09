@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - Materi')

@section('styles')
<style>
.materials-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background-color: #1e293b;
            border-radius: 1rem;
            padding: 1.5rem;
            border: 1px solid #334155;
            text-align: center;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #94a3b8;
            font-size: 0.875rem;
        }

        .materials-container {
            background-color: #1e293b;
            border-radius: 1rem;
            padding: 2rem;
            border: 1px solid #334155;
            margin-bottom: 2rem;
        }

        .material-item {
            background-color: #2a2a3e;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid #334155;
            transition: all 0.3s ease;
        }

        .material-item:hover {
            transform: translateY(-2px);
            border-color: #475569;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .material-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .material-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .material-icon {
            width: 50px;
            height: 50px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #ffffff;
        }

        .material-details {
            flex: 1;
        }

        .material-title {
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.25rem;
            font-size: 1.125rem;
        }

        .material-description {
            color: #cbd5e1;
            font-size: 0.875rem;
            line-height: 1.5;
            margin-bottom: 0.5rem;
        }

        .material-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .material-type {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .type-video {
            background-color: #3b82f6;
            color: #ffffff;
        }

        .type-document {
            background-color: #10b981;
            color: #ffffff;
        }

        .type-presentation {
            background-color: #f59e0b;
            color: #ffffff;
        }

        .type-interactive {
            background-color: #8b5cf6;
            color: #ffffff;
        }

        .type-quiz {
            background-color: #ef4444;
            color: #ffffff;
        }

        .type-assignment {
            background-color: #06b6d4;
            color: #ffffff;
        }

        .material-stats {
            display: flex;
            gap: 1rem;
            color: #94a3b8;
            font-size: 0.875rem;
        }

        .material-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: #ffffff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #334155;
            color: #ffffff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #475569;
        }

        .btn-success {
            background: #10b981;
            color: #ffffff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background: #059669;
        }

        .btn-warning {
            background: #f59e0b;
            color: #ffffff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-warning:hover {
            background: #d97706;
        }

        .btn-danger {
            background: #ef4444;
            color: #ffffff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .material-filters {
            background-color: #1e293b;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid #334155;
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #ffffff;
            font-size: 0.9rem;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #2a2a3e;
            border: 2px solid #333;
            border-radius: 8px;
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            background: #333;
        }

        .search-input {
            position: relative;
        }

        .search-input input {
            padding-left: 2.5rem;
        }

        .search-input i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .material-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .tab-button {
            padding: 0.75rem 1.5rem;
            background-color: #2a2a3e;
            color: #cbd5e1;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .tab-button.active {
            background-color: #667eea;
            color: #ffffff;
        }

        .tab-button:hover {
            background-color: #475569;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .bulk-actions {
            background-color: #2a2a3e;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .bulk-actions input[type="checkbox"] {
            margin-right: 0.5rem;
        }

        .bulk-actions label {
            color: #ffffff;
            font-size: 0.875rem;
        }

        .bulk-actions button {
            margin-left: 1rem;
        }

        .material-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            margin-top: 2rem;
        }

        .pagination-button {
            padding: 0.5rem 1rem;
            background-color: #334155;
            color: #ffffff;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .pagination-button:hover {
            background-color: #475569;
        }

        .pagination-button:disabled {
            background-color: #1e293b;
            color: #6b7280;
            cursor: not-allowed;
        }

        .pagination-info {
            color: #94a3b8;
            font-size: 0.875rem;
        }

        .add-material-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: #ffffff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
        }

        .add-material-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        @media (max-width: 768px) {
            .materials-stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .filter-row {
                grid-template-columns: 1fr;
            }
            
            .material-tabs {
                flex-wrap: wrap;
            }
            
            .bulk-actions {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .material-actions {
                flex-direction: column;
            }
        }
</style>
@endsection

@section('content')
<div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-book-open"></i>
                Materi
            </h1>
            <p class="page-description">Kelola materi pembelajaran dan konten</p>
        </div>

        <!-- Statistics -->
        <div class="materials-stats">
            <div class="stat-card">
                <div class="stat-value">245</div>
                <div class="stat-label">Total Materi</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">89</div>
                <div class="stat-label">Video</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">156</div>
                <div class="stat-label">Dokumen</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">45</div>
                <div class="stat-label">Presentasi</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">23</div>
                <div class="stat-label">Interaktif</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">12</div>
                <div class="stat-label">Kuis</div>
            </div>
        </div>

        <!-- Add Material Button -->
        <button class="add-material-btn" onclick="addMaterial()">
            <i class="fas fa-plus"></i> Tambah Materi Baru
        </button>

        <!-- Material Filters -->
        <div class="material-filters">
            <h2 style="color: #ffffff; margin-bottom: 1.5rem; font-size: 1.25rem;">
                <i class="fas fa-filter me-2"></i>Filter Materi
            </h2>
            
            <div class="filter-row">
                <div class="form-group search-input">
                    <label for="search">Cari Materi</label>
                    <input type="text" id="search" name="search" placeholder="Cari berdasarkan judul atau deskripsi">
                    <i class="fas fa-search"></i>
                </div>
                
                <div class="form-group">
                    <label for="filter_type">Filter Tipe</label>
                    <select id="filter_type" name="filter_type">
                        <option value="">Semua Tipe</option>
                        <option value="video">Video</option>
                        <option value="document">Dokumen</option>
                        <option value="presentation">Presentasi</option>
                        <option value="interactive">Interaktif</option>
                        <option value="quiz">Kuis</option>
                        <option value="assignment">Tugas</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="filter_subject">Filter Mata Pelajaran</label>
                    <select id="filter_subject" name="filter_subject">
                        <option value="">Semua Mata Pelajaran</option>
                        <option value="fisika">Fisika</option>
                        <option value="matematika">Matematika</option>
                        <option value="kimia">Kimia</option>
                        <option value="biologi">Biologi</option>
                        <option value="iot">IoT</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="filter_class">Filter Kelas</label>
                    <select id="filter_class" name="filter_class">
                        <option value="">Semua Kelas</option>
                        <option value="x-ipa-1">X IPA 1</option>
                        <option value="x-ipa-2">X IPA 2</option>
                        <option value="xi-ipa-1">XI IPA 1</option>
                        <option value="xi-ipa-2">XI IPA 2</option>
                        <option value="xii-ipa-1">XII IPA 1</option>
                        <option value="xii-ipa-2">XII IPA 2</option>
                    </select>
                </div>
            </div>
            
            <button type="button" class="btn-primary" onclick="applyFilters()">
                <i class="fas fa-search"></i>
                Terapkan Filter
            </button>
        </div>

        <!-- Material Tabs -->
        <div class="material-tabs">
            <button class="tab-button active" onclick="showTab('all')">
                <i class="fas fa-list"></i> Semua
            </button>
            <button class="tab-button" onclick="showTab('video')">
                <i class="fas fa-play"></i> Video
            </button>
            <button class="tab-button" onclick="showTab('document')">
                <i class="fas fa-file-alt"></i> Dokumen
            </button>
            <button class="tab-button" onclick="showTab('presentation')">
                <i class="fas fa-presentation"></i> Presentasi
            </button>
            <button class="tab-button" onclick="showTab('interactive')">
                <i class="fas fa-mouse-pointer"></i> Interaktif
            </button>
            <button class="tab-button" onclick="showTab('quiz')">
                <i class="fas fa-question-circle"></i> Kuis
            </button>
        </div>

        <!-- Materials Container -->
        <div class="materials-container">
            <div class="bulk-actions">
                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                <label for="selectAll">Pilih Semua</label>
                <button class="btn-secondary" onclick="downloadSelected()">
                    <i class="fas fa-download"></i> Download
                </button>
                <button class="btn-warning" onclick="archiveSelected()">
                    <i class="fas fa-archive"></i> Arsipkan
                </button>
                <button class="btn-danger" onclick="deleteSelected()">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </div>

            <!-- All Materials Tab -->
            <div id="all" class="tab-content active">
                <div class="material-item">
                    <div class="material-header">
                        <div class="material-info">
                            <div class="material-icon" style="background-color: #3b82f6;">
                                <i class="fas fa-play"></i>
                            </div>
                            <div class="material-details">
                                <div class="material-title">Pengantar IoT dan Sensor</div>
                                <div class="material-description">
                                    Video pembelajaran tentang dasar-dasar Internet of Things (IoT) dan berbagai jenis sensor yang digunakan dalam sistem IoT.
                                </div>
                                <div class="material-meta">
                                    <span class="material-type type-video">Video</span>
                                    <div class="material-stats">
                                        <span><i class="fas fa-eye"></i> 1,234 views</span>
                                        <span><i class="fas fa-download"></i> 89 downloads</span>
                                        <span><i class="fas fa-clock"></i> 15:30</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="material-actions">
                        <button class="btn-primary" onclick="viewMaterial(1)">
                            <i class="fas fa-eye"></i> Lihat
                        </button>
                        <button class="btn-secondary" onclick="editMaterial(1)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-success" onclick="downloadMaterial(1)">
                            <i class="fas fa-download"></i> Download
                        </button>
                        <button class="btn-warning" onclick="shareMaterial(1)">
                            <i class="fas fa-share"></i> Share
                        </button>
                        <button class="btn-danger" onclick="deleteMaterial(1)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>

                <div class="material-item">
                    <div class="material-header">
                        <div class="material-info">
                            <div class="material-icon" style="background-color: #10b981;">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="material-details">
                                <div class="material-title">Panduan Praktikum Arduino</div>
                                <div class="material-description">
                                    Dokumen panduan lengkap untuk praktikum Arduino, termasuk setup hardware dan programming dasar.
                                </div>
                                <div class="material-meta">
                                    <span class="material-type type-document">Dokumen</span>
                                    <div class="material-stats">
                                        <span><i class="fas fa-eye"></i> 856 views</span>
                                        <span><i class="fas fa-download"></i> 234 downloads</span>
                                        <span><i class="fas fa-file"></i> PDF (2.3 MB)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="material-actions">
                        <button class="btn-primary" onclick="viewMaterial(2)">
                            <i class="fas fa-eye"></i> Lihat
                        </button>
                        <button class="btn-secondary" onclick="editMaterial(2)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-success" onclick="downloadMaterial(2)">
                            <i class="fas fa-download"></i> Download
                        </button>
                        <button class="btn-warning" onclick="shareMaterial(2)">
                            <i class="fas fa-share"></i> Share
                        </button>
                        <button class="btn-danger" onclick="deleteMaterial(2)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>

                <div class="material-item">
                    <div class="material-header">
                        <div class="material-info">
                            <div class="material-icon" style="background-color: #f59e0b;">
                                <i class="fas fa-presentation"></i>
                            </div>
                            <div class="material-details">
                                <div class="material-title">Presentasi IoT Architecture</div>
                                <div class="material-description">
                                    Slide presentasi tentang arsitektur sistem IoT, komponen-komponen utama, dan alur data.
                                </div>
                                <div class="material-meta">
                                    <span class="material-type type-presentation">Presentasi</span>
                                    <div class="material-stats">
                                        <span><i class="fas fa-eye"></i> 567 views</span>
                                        <span><i class="fas fa-download"></i> 123 downloads</span>
                                        <span><i class="fas fa-file"></i> PPTX (5.7 MB)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="material-actions">
                        <button class="btn-primary" onclick="viewMaterial(3)">
                            <i class="fas fa-eye"></i> Lihat
                        </button>
                        <button class="btn-secondary" onclick="editMaterial(3)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-success" onclick="downloadMaterial(3)">
                            <i class="fas fa-download"></i> Download
                        </button>
                        <button class="btn-warning" onclick="shareMaterial(3)">
                            <i class="fas fa-share"></i> Share
                        </button>
                        <button class="btn-danger" onclick="deleteMaterial(3)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>

                <div class="material-item">
                    <div class="material-header">
                        <div class="material-info">
                            <div class="material-icon" style="background-color: #8b5cf6;">
                                <i class="fas fa-mouse-pointer"></i>
                            </div>
                            <div class="material-details">
                                <div class="material-title">Simulasi Sensor DHT22</div>
                                <div class="material-description">
                                    Simulasi interaktif untuk memahami cara kerja sensor DHT22 dalam mengukur suhu dan kelembaban.
                                </div>
                                <div class="material-meta">
                                    <span class="material-type type-interactive">Interaktif</span>
                                    <div class="material-stats">
                                        <span><i class="fas fa-eye"></i> 789 views</span>
                                        <span><i class="fas fa-download"></i> 45 downloads</span>
                                        <span><i class="fas fa-clock"></i> 8:45</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="material-actions">
                        <button class="btn-primary" onclick="viewMaterial(4)">
                            <i class="fas fa-eye"></i> Lihat
                        </button>
                        <button class="btn-secondary" onclick="editMaterial(4)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-success" onclick="downloadMaterial(4)">
                            <i class="fas fa-download"></i> Download
                        </button>
                        <button class="btn-warning" onclick="shareMaterial(4)">
                            <i class="fas fa-share"></i> Share
                        </button>
                        <button class="btn-danger" onclick="deleteMaterial(4)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>

                <div class="material-item">
                    <div class="material-header">
                        <div class="material-info">
                            <div class="material-icon" style="background-color: #ef4444;">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <div class="material-details">
                                <div class="material-title">Kuis IoT Fundamentals</div>
                                <div class="material-description">
                                    Kuis interaktif untuk menguji pemahaman tentang konsep dasar IoT, sensor, dan komunikasi data.
                                </div>
                                <div class="material-meta">
                                    <span class="material-type type-quiz">Kuis</span>
                                    <div class="material-stats">
                                        <span><i class="fas fa-eye"></i> 1,456 views</span>
                                        <span><i class="fas fa-download"></i> 67 downloads</span>
                                        <span><i class="fas fa-clock"></i> 20:00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="material-actions">
                        <button class="btn-primary" onclick="viewMaterial(5)">
                            <i class="fas fa-eye"></i> Lihat
                        </button>
                        <button class="btn-secondary" onclick="editMaterial(5)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-success" onclick="downloadMaterial(5)">
                            <i class="fas fa-download"></i> Download
                        </button>
                        <button class="btn-warning" onclick="shareMaterial(5)">
                            <i class="fas fa-share"></i> Share
                        </button>
                        <button class="btn-danger" onclick="deleteMaterial(5)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>

                <div class="material-item">
                    <div class="material-header">
                        <div class="material-info">
                            <div class="material-icon" style="background-color: #06b6d4;">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <div class="material-details">
                                <div class="material-title">Tugas Praktikum IoT</div>
                                <div class="material-description">
                                    Tugas praktikum untuk membuat proyek IoT sederhana menggunakan Arduino dan sensor suhu.
                                </div>
                                <div class="material-meta">
                                    <span class="material-type type-assignment">Tugas</span>
                                    <div class="material-stats">
                                        <span><i class="fas fa-eye"></i> 234 views</span>
                                        <span><i class="fas fa-download"></i> 89 downloads</span>
                                        <span><i class="fas fa-clock"></i> 2:30</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="material-actions">
                        <button class="btn-primary" onclick="viewMaterial(6)">
                            <i class="fas fa-eye"></i> Lihat
                        </button>
                        <button class="btn-secondary" onclick="editMaterial(6)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-success" onclick="downloadMaterial(6)">
                            <i class="fas fa-download"></i> Download
                        </button>
                        <button class="btn-warning" onclick="shareMaterial(6)">
                            <i class="fas fa-share"></i> Share
                        </button>
                        <button class="btn-danger" onclick="deleteMaterial(6)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>

            <!-- Video Materials Tab -->
            <div id="video" class="tab-content">
                <p style="color: #94a3b8; text-align: center; padding: 2rem;">
                    Menampilkan materi video...
                </p>
            </div>

            <!-- Document Materials Tab -->
            <div id="document" class="tab-content">
                <p style="color: #94a3b8; text-align: center; padding: 2rem;">
                    Menampilkan materi dokumen...
                </p>
            </div>

            <!-- Presentation Materials Tab -->
            <div id="presentation" class="tab-content">
                <p style="color: #94a3b8; text-align: center; padding: 2rem;">
                    Menampilkan materi presentasi...
                </p>
            </div>

            <!-- Interactive Materials Tab -->
            <div id="interactive" class="tab-content">
                <p style="color: #94a3b8; text-align: center; padding: 2rem;">
                    Menampilkan materi interaktif...
                </p>
            </div>

            <!-- Quiz Materials Tab -->
            <div id="quiz" class="tab-content">
                <p style="color: #94a3b8; text-align: center; padding: 2rem;">
                    Menampilkan materi kuis...
                </p>
            </div>
        </div>

        <!-- Pagination -->
        <div class="material-pagination">
            <button class="pagination-button" disabled>
                <i class="fas fa-chevron-left"></i> Sebelumnya
            </button>
            <span class="pagination-info">Halaman 1 dari 8</span>
            <button class="pagination-button">
                Selanjutnya <i class="fas fa-chevron-right"></i>
            </button>
        </div>
@endsection
