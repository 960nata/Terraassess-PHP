@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Notifikasi')

@section('styles')
<style>
.notification-stats {
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

        .notification-container {
            background-color: #1e293b;
            border-radius: 1rem;
            padding: 2rem;
            border: 1px solid #334155;
            margin-bottom: 2rem;
        }

        .notification-item {
            background-color: #2a2a3e;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid #334155;
            transition: all 0.3s ease;
        }

        .notification-item:hover {
            transform: translateY(-2px);
            border-color: #475569;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .notification-item.unread {
            border-left: 4px solid #667eea;
            background-color: #2a2a3e;
        }

        .notification-item.read {
            border-left: 4px solid #6b7280;
            background-color: #1e293b;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .notification-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #ffffff;
        }

        .notification-details {
            flex: 1;
        }

        .notification-title {
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.25rem;
        }

        .notification-message {
            color: #cbd5e1;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .notification-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .notification-type {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .type-system {
            background-color: #3b82f6;
            color: #ffffff;
        }

        .type-task {
            background-color: #10b981;
            color: #ffffff;
        }

        .type-exam {
            background-color: #f59e0b;
            color: #ffffff;
        }

        .type-announcement {
            background-color: #8b5cf6;
            color: #ffffff;
        }

        .type-warning {
            background-color: #ef4444;
            color: #ffffff;
        }

        .type-success {
            background-color: #10b981;
            color: #ffffff;
        }

        .notification-actions {
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

        .notification-filters {
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

        .notification-tabs {
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

        .notification-pagination {
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

        @media (max-width: 768px) {
            .notification-stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .filter-row {
                grid-template-columns: 1fr;
            }
            
            .notification-tabs {
                flex-wrap: wrap;
            }
            
            .bulk-actions {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .notification-actions {
                flex-direction: column;
            }
        }
</style>
@endsection

@section('content')
<div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-bell"></i>
                Notifikasi
            </h1>
            <p class="page-description">Kelola notifikasi sistem</p>
        </div>

        <!-- Statistics -->
        <div class="notification-stats">
            <div class="stat-card">
                <div class="stat-value">156</div>
                <div class="stat-label">Total Notifikasi</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">23</div>
                <div class="stat-label">Belum Dibaca</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">133</div>
                <div class="stat-label">Sudah Dibaca</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">5</div>
                <div class="stat-label">Hari Ini</div>
            </div>
        </div>

        <!-- Notification Filters -->
        <div class="notification-filters">
            <h2 style="color: #ffffff; margin-bottom: 1.5rem; font-size: 1.25rem;">
                <i class="fas fa-filter me-2"></i>Filter Notifikasi
            </h2>
            
            <div class="filter-row">
                <div class="form-group search-input">
                    <label for="search">Cari Notifikasi</label>
                    <input type="text" id="search" name="search" placeholder="Cari berdasarkan judul atau pesan">
                    <i class="fas fa-search"></i>
                </div>
                
                <div class="form-group">
                    <label for="filter_type">Filter Tipe</label>
                    <select id="filter_type" name="filter_type">
                        <option value="">Semua Tipe</option>
                        <option value="system">Sistem</option>
                        <option value="task">Tugas</option>
                        <option value="exam">Ujian</option>
                        <option value="announcement">Pengumuman</option>
                        <option value="warning">Peringatan</option>
                        <option value="success">Sukses</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="filter_status">Filter Status</label>
                    <select id="filter_status" name="filter_status">
                        <option value="">Semua Status</option>
                        <option value="unread">Belum Dibaca</option>
                        <option value="read">Sudah Dibaca</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="filter_date">Filter Tanggal</label>
                    <input type="date" id="filter_date" name="filter_date">
                </div>
            </div>
            
            <button type="button" class="btn-primary" onclick="applyFilters()">
                <i class="fas fa-search"></i>
                Terapkan Filter
            </button>
        </div>

        <!-- Notification Tabs -->
        <div class="notification-tabs">
            <button class="tab-button active" onclick="showTab('all')">
                <i class="fas fa-list"></i> Semua
            </button>
            <button class="tab-button" onclick="showTab('unread')">
                <i class="fas fa-envelope"></i> Belum Dibaca
            </button>
            <button class="tab-button" onclick="showTab('system')">
                <i class="fas fa-cog"></i> Sistem
            </button>
            <button class="tab-button" onclick="showTab('task')">
                <i class="fas fa-book"></i> Tugas
            </button>
            <button class="tab-button" onclick="showTab('exam')">
                <i class="fas fa-bullseye"></i> Ujian
            </button>
        </div>

        <!-- Notifications Container -->
        <div class="notification-container">
            <div class="bulk-actions">
                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                <label for="selectAll">Pilih Semua</label>
                <button class="btn-secondary" onclick="markAsRead()">
                    <i class="fas fa-check"></i> Tandai Dibaca
                </button>
                <button class="btn-secondary" onclick="markAsUnread()">
                    <i class="fas fa-envelope"></i> Tandai Belum Dibaca
                </button>
                <button class="btn-danger" onclick="deleteSelected()">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </div>

            <!-- All Notifications Tab -->
            <div id="all" class="tab-content active">
                <div class="notification-item unread">
                    <div class="notification-header">
                        <div class="notification-info">
                            <div class="notification-icon" style="background-color: #3b82f6;">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div class="notification-details">
                                <div class="notification-title">Sistem Maintenance</div>
                                <div class="notification-message">
                                    Sistem akan mengalami maintenance pada pukul 02:00 WIB. 
                                    Seluruh layanan akan tidak tersedia selama 2 jam.
                                </div>
                            </div>
                        </div>
                        <div class="notification-meta">
                            <span class="notification-type type-system">Sistem</span>
                            <span class="notification-time">2 jam lalu</span>
                        </div>
                    </div>
                    <div class="notification-actions">
                        <button class="btn-primary" onclick="viewNotification(1)">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </button>
                        <button class="btn-success" onclick="markAsRead(1)">
                            <i class="fas fa-check"></i> Tandai Dibaca
                        </button>
                        <button class="btn-danger" onclick="deleteNotification(1)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>

                <div class="notification-item read">
                    <div class="notification-header">
                        <div class="notification-info">
                            <div class="notification-icon" style="background-color: #10b981;">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="notification-details">
                                <div class="notification-title">Tugas Baru Tersedia</div>
                                <div class="notification-message">
                                    Tugas baru "Analisis Data IoT" telah tersedia untuk kelas X IPA 1. 
                                    Deadline: 25 Januari 2024.
                                </div>
                            </div>
                        </div>
                        <div class="notification-meta">
                            <span class="notification-type type-task">Tugas</span>
                            <span class="notification-time">1 hari lalu</span>
                        </div>
                    </div>
                    <div class="notification-actions">
                        <button class="btn-primary" onclick="viewNotification(2)">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </button>
                        <button class="btn-warning" onclick="markAsUnread(2)">
                            <i class="fas fa-envelope"></i> Tandai Belum Dibaca
                        </button>
                        <button class="btn-danger" onclick="deleteNotification(2)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>

                <div class="notification-item unread">
                    <div class="notification-header">
                        <div class="notification-info">
                            <div class="notification-icon" style="background-color: #f59e0b;">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <div class="notification-details">
                                <div class="notification-title">Ujian Dimulai</div>
                                <div class="notification-message">
                                    Ujian tengah semester untuk mata pelajaran Fisika telah dimulai. 
                                    Durasi: 90 menit.
                                </div>
                            </div>
                        </div>
                        <div class="notification-meta">
                            <span class="notification-type type-exam">Ujian</span>
                            <span class="notification-time">3 jam lalu</span>
                        </div>
                    </div>
                    <div class="notification-actions">
                        <button class="btn-primary" onclick="viewNotification(3)">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </button>
                        <button class="btn-success" onclick="markAsRead(3)">
                            <i class="fas fa-check"></i> Tandai Dibaca
                        </button>
                        <button class="btn-danger" onclick="deleteNotification(3)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>

                <div class="notification-item read">
                    <div class="notification-header">
                        <div class="notification-info">
                            <div class="notification-icon" style="background-color: #8b5cf6;">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div class="notification-details">
                                <div class="notification-title">Pengumuman Penting</div>
                                <div class="notification-message">
                                    Ada perubahan jadwal pembelajaran untuk minggu depan. 
                                    Silakan cek jadwal terbaru di dashboard.
                                </div>
                            </div>
                        </div>
                        <div class="notification-meta">
                            <span class="notification-type type-announcement">Pengumuman</span>
                            <span class="notification-time">1 minggu lalu</span>
                        </div>
                    </div>
                    <div class="notification-actions">
                        <button class="btn-primary" onclick="viewNotification(4)">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </button>
                        <button class="btn-warning" onclick="markAsUnread(4)">
                            <i class="fas fa-envelope"></i> Tandai Belum Dibaca
                        </button>
                        <button class="btn-danger" onclick="deleteNotification(4)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>

                <div class="notification-item unread">
                    <div class="notification-header">
                        <div class="notification-info">
                            <div class="notification-icon" style="background-color: #ef4444;">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="notification-details">
                                <div class="notification-title">Peringatan Keamanan</div>
                                <div class="notification-message">
                                    Deteksi aktivitas mencurigakan pada akun pengguna. 
                                    Silakan periksa log aktivitas terbaru.
                                </div>
                            </div>
                        </div>
                        <div class="notification-meta">
                            <span class="notification-type type-warning">Peringatan</span>
                            <span class="notification-time">4 jam lalu</span>
                        </div>
                    </div>
                    <div class="notification-actions">
                        <button class="btn-primary" onclick="viewNotification(5)">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </button>
                        <button class="btn-success" onclick="markAsRead(5)">
                            <i class="fas fa-check"></i> Tandai Dibaca
                        </button>
                        <button class="btn-danger" onclick="deleteNotification(5)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>

                <div class="notification-item read">
                    <div class="notification-header">
                        <div class="notification-info">
                            <div class="notification-icon" style="background-color: #10b981;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="notification-details">
                                <div class="notification-title">Backup Berhasil</div>
                                <div class="notification-message">
                                    Backup database harian telah berhasil diselesaikan. 
                                    Ukuran file: 2.3 GB.
                                </div>
                            </div>
                        </div>
                        <div class="notification-meta">
                            <span class="notification-type type-success">Sukses</span>
                            <span class="notification-time">6 jam lalu</span>
                        </div>
                    </div>
                    <div class="notification-actions">
                        <button class="btn-primary" onclick="viewNotification(6)">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </button>
                        <button class="btn-warning" onclick="markAsUnread(6)">
                            <i class="fas fa-envelope"></i> Tandai Belum Dibaca
                        </button>
                        <button class="btn-danger" onclick="deleteNotification(6)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>

            <!-- Unread Notifications Tab -->
            <div id="unread" class="tab-content">
                <p style="color: #94a3b8; text-align: center; padding: 2rem;">
                    Menampilkan notifikasi yang belum dibaca...
                </p>
            </div>

            <!-- System Notifications Tab -->
            <div id="system" class="tab-content">
                <p style="color: #94a3b8; text-align: center; padding: 2rem;">
                    Menampilkan notifikasi sistem...
                </p>
            </div>

            <!-- Task Notifications Tab -->
            <div id="task" class="tab-content">
                <p style="color: #94a3b8; text-align: center; padding: 2rem;">
                    Menampilkan notifikasi tugas...
                </p>
            </div>

            <!-- Exam Notifications Tab -->
            <div id="exam" class="tab-content">
                <p style="color: #94a3b8; text-align: center; padding: 2rem;">
                    Menampilkan notifikasi ujian...
                </p>
            </div>
        </div>

        <!-- Pagination -->
        <div class="notification-pagination">
            <button class="pagination-button" disabled>
                <i class="fas fa-chevron-left"></i> Sebelumnya
            </button>
            <span class="pagination-info">Halaman 1 dari 5</span>
            <button class="pagination-button">
                Selanjutnya <i class="fas fa-chevron-right"></i>
            </button>
        </div>
@endsection
