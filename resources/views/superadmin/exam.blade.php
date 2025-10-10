@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Ujian')

@section('styles')
<style>
.exam-stats {
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

        .exam-container {
            background-color: #1e293b;
            border-radius: 1rem;
            padding: 2rem;
            border: 1px solid #334155;
            margin-bottom: 2rem;
        }

        .exam-item {
            background-color: #2a2a3e;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid #334155;
            transition: all 0.3s ease;
        }

        .exam-item:hover {
            transform: translateY(-2px);
            border-color: #475569;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .exam-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .exam-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .exam-icon {
            width: 50px;
            height: 50px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #ffffff;
        }

        .exam-details {
            flex: 1;
        }

        .exam-title {
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.25rem;
            font-size: 1.125rem;
        }

        .exam-description {
            color: #cbd5e1;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .exam-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .exam-type {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .type-quiz {
            background-color: #3b82f6;
            color: #ffffff;
        }

        .type-midterm {
            background-color: #10b981;
            color: #ffffff;
        }

        .type-final {
            background-color: #ef4444;
            color: #ffffff;
        }

        .type-assignment {
            background-color: #f59e0b;
            color: #ffffff;
        }

        .type-practical {
            background-color: #8b5cf6;
            color: #ffffff;
        }

        .exam-status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .status-active {
            background-color: #10b981;
        }

        .status-inactive {
            background-color: #ef4444;
        }

        .status-scheduled {
            background-color: #f59e0b;
        }

        .status-completed {
            background-color: #6b7280;
        }

        .exam-actions {
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

        .exam-filters {
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

        .exam-tabs {
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

        .exam-pagination {
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

        .add-exam-btn {
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

        .add-exam-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .exam-details-info {
            background-color: #2a2a3e;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-top: 1rem;
            border: 1px solid #334155;
        }

        .details-title {
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }

        .detail-item {
            color: #94a3b8;
            font-size: 0.875rem;
        }

        .detail-label {
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .detail-value {
            color: #ffffff;
            font-weight: 600;
        }

        .exam-participants {
            background-color: #2a2a3e;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-top: 1rem;
            border: 1px solid #334155;
        }

        .participants-title {
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
        }

        .participants-stats {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .participant-stat {
            background-color: #334155;
            color: #94a3b8;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .participant-stat.active {
            background-color: #10b981;
            color: #ffffff;
        }

        .participant-stat.completed {
            background-color: #3b82f6;
            color: #ffffff;
        }

        .participant-stat.pending {
            background-color: #f59e0b;
            color: #ffffff;
        }

        @media (max-width: 768px) {
            .exam-stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .filter-row {
                grid-template-columns: 1fr;
            }
            
            .exam-tabs {
                flex-wrap: wrap;
            }
            
            .bulk-actions {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .exam-actions {
                flex-direction: column;
            }
        }
</style>
@endsection

@section('content')
<div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-file-alt"></i>
                Ujian
            </h1>
            <p class="page-description">Kelola semua ujian sistem</p>
        </div>

        <!-- Statistics -->
        <div class="exam-stats">
            <div class="stat-card">
                <div class="stat-value">24</div>
                <div class="stat-label">Total Ujian</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">8</div>
                <div class="stat-label">Ujian Aktif</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">12</div>
                <div class="stat-label">Ujian Selesai</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">4</div>
                <div class="stat-label">Ujian Terjadwal</div>
            </div>
        </div>

        <!-- Add Exam Button -->
        <button class="add-exam-btn" onclick="addExam()">
            <i class="fas fa-plus"></i> Buat Ujian Baru
        </button>

        <!-- Exam Filters -->
        <div class="exam-filters">
            <h2 style="color: #ffffff; margin-bottom: 1.5rem; font-size: 1.25rem;">
                <i class="fas fa-filter me-2"></i>Filter Ujian
            </h2>
            
            <div class="filter-row">
                <div class="form-group search-input">
                    <label for="search">Cari Ujian</label>
                    <input type="text" id="search" name="search" placeholder="Cari berdasarkan judul atau deskripsi">
                    <i class="fas fa-search"></i>
                </div>
                
                <div class="form-group">
                    <label for="filter_type">Filter Tipe</label>
                    <select id="filter_type" name="filter_type">
                        <option value="">Semua Tipe</option>
                        <option value="quiz">Quiz</option>
                        <option value="midterm">UTS</option>
                        <option value="final">UAS</option>
                        <option value="assignment">Tugas</option>
                        <option value="practical">Praktikum</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="filter_status">Filter Status</label>
                    <select id="filter_status" name="filter_status">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Tidak Aktif</option>
                        <option value="scheduled">Terjadwal</option>
                        <option value="completed">Selesai</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="filter_subject">Filter Mata Pelajaran</label>
                    <select id="filter_subject" name="filter_subject">
                        <option value="">Semua Mata Pelajaran</option>
                        <option value="matematika">Matematika</option>
                        <option value="fisika">Fisika</option>
                        <option value="kimia">Kimia</option>
                        <option value="biologi">Biologi</option>
                        <option value="iot">IoT</option>
                    </select>
                </div>
            </div>
            
            <button type="button" class="btn-primary" onclick="applyFilters()">
                <i class="fas fa-search"></i>
                Terapkan Filter
            </button>
        </div>

        <!-- Exam Tabs -->
        <div class="exam-tabs">
            <button class="tab-button active" onclick="showTab('all')">
                <i class="fas fa-list"></i> Semua
            </button>
            <button class="tab-button" onclick="showTab('active')">
                <i class="fas fa-play"></i> Aktif
            </button>
            <button class="tab-button" onclick="showTab('scheduled')">
                <i class="fas fa-clock"></i> Terjadwal
            </button>
            <button class="tab-button" onclick="showTab('completed')">
                <i class="fas fa-check"></i> Selesai
            </button>
            <button class="tab-button" onclick="showTab('quiz')">
                <i class="fas fa-question-circle"></i> Quiz
            </button>
        </div>

        <!-- Exam Container -->
        <div class="exam-container">
            <div class="bulk-actions">
                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                <label for="selectAll">Pilih Semua</label>
                <button class="btn-secondary" onclick="exportSelected()">
                    <i class="fas fa-download"></i> Export
                </button>
                <button class="btn-warning" onclick="scheduleSelected()">
                    <i class="fas fa-clock"></i> Jadwalkan
                </button>
                <button class="btn-danger" onclick="deleteSelected()">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </div>

            <!-- All Exams Tab -->
            <div id="all" class="tab-content active">
                <div class="exam-item">
                    <div class="exam-header">
                        <div class="exam-info">
                            <div class="exam-icon" style="background-color: #3b82f6;">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <div class="exam-details">
                                <div class="exam-title">Quiz Matematika Dasar</div>
                                <div class="exam-description">
                                    Quiz untuk menguji pemahaman dasar matematika siswa kelas 10
                                </div>
                                <div class="exam-meta">
                                    <span class="exam-type type-quiz">Quiz</span>
                                    <div class="exam-status">
                                        <span class="status-indicator status-active"></span>
                                        <span style="color: #10b981; font-size: 0.875rem;">Aktif</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="exam-details-info">
                        <div class="details-title">Detail Ujian:</div>
                        <div class="details-grid">
                            <div class="detail-item">
                                <div class="detail-label">Durasi:</div>
                                <div class="detail-value">30 menit</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Soal:</div>
                                <div class="detail-value">20 soal</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Kelas:</div>
                                <div class="detail-value">X-A, X-B</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Peserta:</div>
                                <div class="detail-value">45 siswa</div>
                            </div>
                        </div>
                    </div>
                    <div class="exam-participants">
                        <div class="participants-title">Status Peserta:</div>
                        <div class="participants-stats">
                            <span class="participant-stat active">25 Aktif</span>
                            <span class="participant-stat completed">15 Selesai</span>
                            <span class="participant-stat pending">5 Pending</span>
                        </div>
                    </div>
                    <div class="exam-actions">
                        <button class="btn-primary" onclick="viewExam(1)">
                            <i class="fas fa-eye"></i> Lihat
                        </button>
                        <button class="btn-secondary" onclick="editExam(1)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-success" onclick="startExam(1)">
                            <i class="fas fa-play"></i> Mulai
                        </button>
                        <button class="btn-warning" onclick="pauseExam(1)">
                            <i class="fas fa-pause"></i> Pause
                        </button>
                        <button class="btn-danger" onclick="deleteExam(1)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>

                <div class="exam-item">
                    <div class="exam-header">
                        <div class="exam-info">
                            <div class="exam-icon" style="background-color: #10b981;">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="exam-details">
                                <div class="exam-title">UTS Fisika Kelas 11</div>
                                <div class="exam-description">
                                    Ujian Tengah Semester untuk mata pelajaran Fisika kelas 11
                                </div>
                                <div class="exam-meta">
                                    <span class="exam-type type-midterm">UTS</span>
                                    <div class="exam-status">
                                        <span class="status-indicator status-scheduled"></span>
                                        <span style="color: #f59e0b; font-size: 0.875rem;">Terjadwal</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="exam-details-info">
                        <div class="details-title">Detail Ujian:</div>
                        <div class="details-grid">
                            <div class="detail-item">
                                <div class="detail-label">Durasi:</div>
                                <div class="detail-value">120 menit</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Soal:</div>
                                <div class="detail-value">50 soal</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Kelas:</div>
                                <div class="detail-value">XI-A, XI-B, XI-C</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Peserta:</div>
                                <div class="detail-value">75 siswa</div>
                            </div>
                        </div>
                    </div>
                    <div class="exam-participants">
                        <div class="participants-title">Status Peserta:</div>
                        <div class="participants-stats">
                            <span class="participant-stat">0 Aktif</span>
                            <span class="participant-stat">0 Selesai</span>
                            <span class="participant-stat pending">75 Terjadwal</span>
                        </div>
                    </div>
                    <div class="exam-actions">
                        <button class="btn-primary" onclick="viewExam(2)">
                            <i class="fas fa-eye"></i> Lihat
                        </button>
                        <button class="btn-secondary" onclick="editExam(2)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-success" onclick="startExam(2)">
                            <i class="fas fa-play"></i> Mulai
                        </button>
                        <button class="btn-warning" onclick="scheduleExam(2)">
                            <i class="fas fa-clock"></i> Jadwalkan
                        </button>
                        <button class="btn-danger" onclick="deleteExam(2)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>

                <div class="exam-item">
                    <div class="exam-header">
                        <div class="exam-info">
                            <div class="exam-icon" style="background-color: #ef4444;">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="exam-details">
                                <div class="exam-title">UAS Kimia Kelas 12</div>
                                <div class="exam-description">
                                    Ujian Akhir Semester untuk mata pelajaran Kimia kelas 12
                                </div>
                                <div class="exam-meta">
                                    <span class="exam-type type-final">UAS</span>
                                    <div class="exam-status">
                                        <span class="status-indicator status-completed"></span>
                                        <span style="color: #6b7280; font-size: 0.875rem;">Selesai</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="exam-details-info">
                        <div class="details-title">Detail Ujian:</div>
                        <div class="details-grid">
                            <div class="detail-item">
                                <div class="detail-label">Durasi:</div>
                                <div class="detail-value">150 menit</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Soal:</div>
                                <div class="detail-value">60 soal</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Kelas:</div>
                                <div class="detail-value">XII-A, XII-B</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Peserta:</div>
                                <div class="detail-value">50 siswa</div>
                            </div>
                        </div>
                    </div>
                    <div class="exam-participants">
                        <div class="participants-title">Status Peserta:</div>
                        <div class="participants-stats">
                            <span class="participant-stat">0 Aktif</span>
                            <span class="participant-stat completed">50 Selesai</span>
                            <span class="participant-stat">0 Pending</span>
                        </div>
                    </div>
                    <div class="exam-actions">
                        <button class="btn-primary" onclick="viewExam(3)">
                            <i class="fas fa-eye"></i> Lihat
                        </button>
                        <button class="btn-secondary" onclick="editExam(3)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-success" onclick="viewResults(3)">
                            <i class="fas fa-chart-bar"></i> Hasil
                        </button>
                        <button class="btn-warning" onclick="exportResults(3)">
                            <i class="fas fa-download"></i> Export
                        </button>
                        <button class="btn-danger" onclick="deleteExam(3)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>

                <div class="exam-item">
                    <div class="exam-header">
                        <div class="exam-info">
                            <div class="exam-icon" style="background-color: #f59e0b;">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <div class="exam-details">
                                <div class="exam-title">Tugas IoT Praktikum</div>
                                <div class="exam-description">
                                    Tugas praktikum IoT untuk menguji kemampuan siswa dalam menggunakan sensor
                                </div>
                                <div class="exam-meta">
                                    <span class="exam-type type-assignment">Tugas</span>
                                    <div class="exam-status">
                                        <span class="status-indicator status-active"></span>
                                        <span style="color: #10b981; font-size: 0.875rem;">Aktif</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="exam-details-info">
                        <div class="details-title">Detail Ujian:</div>
                        <div class="details-grid">
                            <div class="detail-item">
                                <div class="detail-label">Durasi:</div>
                                <div class="detail-value">180 menit</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Soal:</div>
                                <div class="detail-value">5 soal</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Kelas:</div>
                                <div class="detail-value">X-A, X-B, X-C</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Peserta:</div>
                                <div class="detail-value">60 siswa</div>
                            </div>
                        </div>
                    </div>
                    <div class="exam-participants">
                        <div class="participants-title">Status Peserta:</div>
                        <div class="participants-stats">
                            <span class="participant-stat active">30 Aktif</span>
                            <span class="participant-stat completed">20 Selesai</span>
                            <span class="participant-stat pending">10 Pending</span>
                        </div>
                    </div>
                    <div class="exam-actions">
                        <button class="btn-primary" onclick="viewExam(4)">
                            <i class="fas fa-eye"></i> Lihat
                        </button>
                        <button class="btn-secondary" onclick="editExam(4)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-success" onclick="startExam(4)">
                            <i class="fas fa-play"></i> Mulai
                        </button>
                        <button class="btn-warning" onclick="pauseExam(4)">
                            <i class="fas fa-pause"></i> Pause
                        </button>
                        <button class="btn-danger" onclick="deleteExam(4)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>

                <div class="exam-item">
                    <div class="exam-header">
                        <div class="exam-info">
                            <div class="exam-icon" style="background-color: #8b5cf6;">
                                <i class="fas fa-flask"></i>
                            </div>
                            <div class="exam-details">
                                <div class="exam-title">Praktikum Biologi</div>
                                <div class="exam-description">
                                    Praktikum biologi untuk menguji kemampuan praktis siswa
                                </div>
                                <div class="exam-meta">
                                    <span class="exam-type type-practical">Praktikum</span>
                                    <div class="exam-status">
                                        <span class="status-indicator status-scheduled"></span>
                                        <span style="color: #f59e0b; font-size: 0.875rem;">Terjadwal</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="exam-details-info">
                        <div class="details-title">Detail Ujian:</div>
                        <div class="details-grid">
                            <div class="detail-item">
                                <div class="detail-label">Durasi:</div>
                                <div class="detail-value">90 menit</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Soal:</div>
                                <div class="detail-value">10 soal</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Kelas:</div>
                                <div class="detail-value">XI-A, XI-B</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Peserta:</div>
                                <div class="detail-value">40 siswa</div>
                            </div>
                        </div>
                    </div>
                    <div class="exam-participants">
                        <div class="participants-title">Status Peserta:</div>
                        <div class="participants-stats">
                            <span class="participant-stat">0 Aktif</span>
                            <span class="participant-stat">0 Selesai</span>
                            <span class="participant-stat pending">40 Terjadwal</span>
                        </div>
                    </div>
                    <div class="exam-actions">
                        <button class="btn-primary" onclick="viewExam(5)">
                            <i class="fas fa-eye"></i> Lihat
                        </button>
                        <button class="btn-secondary" onclick="editExam(5)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-success" onclick="startExam(5)">
                            <i class="fas fa-play"></i> Mulai
                        </button>
                        <button class="btn-warning" onclick="scheduleExam(5)">
                            <i class="fas fa-clock"></i> Jadwalkan
                        </button>
                        <button class="btn-danger" onclick="deleteExam(5)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>

            <!-- Active Exams Tab -->
            <div id="active" class="tab-content">
                <p style="color: #94a3b8; text-align: center; padding: 2rem;">
                    Menampilkan ujian aktif...
                </p>
            </div>

            <!-- Scheduled Exams Tab -->
            <div id="scheduled" class="tab-content">
                <p style="color: #94a3b8; text-align: center; padding: 2rem;">
                    Menampilkan ujian terjadwal...
                </p>
            </div>

            <!-- Completed Exams Tab -->
            <div id="completed" class="tab-content">
                <p style="color: #94a3b8; text-align: center; padding: 2rem;">
                    Menampilkan ujian selesai...
                </p>
            </div>

            <!-- Quiz Tab -->
            <div id="quiz" class="tab-content">
                <p style="color: #94a3b8; text-align: center; padding: 2rem;">
                    Menampilkan quiz...
                </p>
            </div>
        </div>

        <!-- Pagination -->
        <div class="exam-pagination">
            <button class="pagination-button" disabled>
                <i class="fas fa-chevron-left"></i> Sebelumnya
            </button>
            <span class="pagination-info">Halaman 1 dari 3</span>
            <button class="pagination-button">
                Selanjutnya <i class="fas fa-chevron-right"></i>
            </button>
        </div>
@endsection
