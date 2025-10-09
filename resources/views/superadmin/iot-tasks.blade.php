@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - Tugas IoT')

@section('styles')
<style>
.iot-task-stats {
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

        .iot-tasks-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .iot-task-card {
            background-color: #1e293b;
            border-radius: 1rem;
            padding: 1.5rem;
            border: 1px solid #334155;
            transition: all 0.3s ease;
        }

        .iot-task-card:hover {
            transform: translateY(-2px);
            border-color: #475569;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .iot-task-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .iot-task-title {
            font-weight: 600;
            color: #ffffff;
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
        }

        .iot-task-subject {
            color: #94a3b8;
            font-size: 0.875rem;
        }

        .iot-task-type {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .type-sensor {
            background-color: #3b82f6;
            color: #ffffff;
        }

        .type-actuator {
            background-color: #10b981;
            color: #ffffff;
        }

        .type-monitoring {
            background-color: #f59e0b;
            color: #ffffff;
        }

        .type-control {
            background-color: #ef4444;
            color: #ffffff;
        }

        .iot-task-info {
            margin-bottom: 1.5rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .info-label {
            color: #94a3b8;
        }

        .info-value {
            color: #ffffff;
            font-weight: 500;
        }

        .iot-task-description {
            color: #cbd5e1;
            font-size: 0.875rem;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .iot-task-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
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

        .iot-task-filters {
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

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-active {
            background-color: #10b981;
            color: #ffffff;
        }

        .status-draft {
            background-color: #f59e0b;
            color: #ffffff;
        }

        .status-completed {
            background-color: #6b7280;
            color: #ffffff;
        }

        .status-pending {
            background-color: #3b82f6;
            color: #ffffff;
        }

        .iot-device-info {
            background-color: #2a2a3e;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .device-title {
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.5rem;
        }

        .device-details {
            color: #94a3b8;
            font-size: 0.875rem;
        }

        .sensor-data {
            background-color: #2a2a3e;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .sensor-title {
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.5rem;
        }

        .sensor-values {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 0.5rem;
        }

        .sensor-value {
            text-align: center;
            padding: 0.5rem;
            background-color: #1e293b;
            border-radius: 0.5rem;
        }

        .sensor-label {
            color: #94a3b8;
            font-size: 0.75rem;
            margin-bottom: 0.25rem;
        }

        .sensor-data-value {
            color: #ffffff;
            font-weight: 600;
            font-size: 1.125rem;
        }

        @media (max-width: 768px) {
            .iot-task-stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .iot-tasks-grid {
                grid-template-columns: 1fr;
            }
            
            .filter-row {
                grid-template-columns: 1fr;
            }
            
            .iot-task-actions {
                flex-direction: column;
            }
        }
</style>
@endsection

@section('content')
<div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-microchip"></i>
                Tugas IoT
            </h1>
            <p class="page-description">Buat dan kelola tugas penelitian IoT</p>
        </div>

        <!-- Statistics -->
        <div class="iot-task-stats">
            <div class="stat-card">
                <div class="stat-value">28</div>
                <div class="stat-label">Total Tugas IoT</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">15</div>
                <div class="stat-label">Tugas Aktif</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">8</div>
                <div class="stat-label">Tugas Selesai</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">5</div>
                <div class="stat-label">Tugas Draft</div>
            </div>
        </div>

        <!-- IoT Task Filters -->
        <div class="iot-task-filters">
            <h2 style="color: #ffffff; margin-bottom: 1.5rem; font-size: 1.25rem;">
                <i class="fas fa-filter me-2"></i>Filter Tugas IoT
            </h2>
            
            <div class="filter-row">
                <div class="form-group search-input">
                    <label for="search">Cari Tugas IoT</label>
                    <input type="text" id="search" name="search" placeholder="Cari berdasarkan judul atau deskripsi">
                    <i class="fas fa-search"></i>
                </div>
                
                <div class="form-group">
                    <label for="filter_subject">Filter Mata Pelajaran</label>
                    <select id="filter_subject" name="filter_subject">
                        <option value="">Semua Mata Pelajaran</option>
                        <option value="1">Matematika</option>
                        <option value="2">Fisika</option>
                        <option value="3">Kimia</option>
                        <option value="4">Biologi</option>
                        <option value="5">Bahasa Indonesia</option>
                        <option value="6">Bahasa Inggris</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="filter_type">Filter Tipe</label>
                    <select id="filter_type" name="filter_type">
                        <option value="">Semua Tipe</option>
                        <option value="sensor">Sensor Monitoring</option>
                        <option value="actuator">Actuator Control</option>
                        <option value="monitoring">Data Monitoring</option>
                        <option value="control">System Control</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="filter_status">Filter Status</label>
                    <select id="filter_status" name="filter_status">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="draft">Draft</option>
                        <option value="completed">Selesai</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
            </div>
            
            <button type="button" class="btn-primary" onclick="applyFilters()">
                <i class="fas fa-search"></i>
                Terapkan Filter
            </button>
        </div>

        <!-- IoT Tasks Grid -->
        <div>
            <h2 style="color: #ffffff; margin-bottom: 1.5rem; font-size: 1.25rem;">
                <i class="fas fa-list me-2"></i>Daftar Tugas IoT
            </h2>
            
            <div class="iot-tasks-grid">
                <div class="iot-task-card">
                    <div class="iot-task-header">
                        <div>
                            <div class="iot-task-title">Monitoring Suhu Ruangan</div>
                            <div class="iot-task-subject">Fisika</div>
                        </div>
                        <div class="iot-task-type type-sensor">Sensor</div>
                    </div>
                    <div class="iot-task-description">
                        Tugas untuk memantau suhu ruangan menggunakan sensor DHT22 dan menampilkan data real-time.
                    </div>
                    <div class="iot-device-info">
                        <div class="device-title">Perangkat IoT</div>
                        <div class="device-details">Arduino Uno + DHT22 + WiFi Module</div>
                    </div>
                    <div class="sensor-data">
                        <div class="sensor-title">Data Sensor</div>
                        <div class="sensor-values">
                            <div class="sensor-value">
                                <div class="sensor-label">Suhu</div>
                                <div class="sensor-data-value">25.3°C</div>
                            </div>
                            <div class="sensor-value">
                                <div class="sensor-label">Kelembaban</div>
                                <div class="sensor-data-value">65%</div>
                            </div>
                        </div>
                    </div>
                    <div class="iot-task-info">
                        <div class="info-item">
                            <span class="info-label">Durasi:</span>
                            <span class="info-value">7 hari</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kesulitan:</span>
                            <span class="info-value">Sedang</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kelas:</span>
                            <span class="info-value">X IPA 1</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status:</span>
                            <span class="status-badge status-active">Aktif</span>
                        </div>
                    </div>
                    <div class="iot-task-actions">
                        <button class="btn-primary" onclick="viewIoTTask(1)">
                            <i class="fas fa-eye"></i> Lihat
                        </button>
                        <button class="btn-secondary" onclick="editIoTTask(1)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-success" onclick="monitorIoTTask(1)">
                            <i class="fas fa-chart-line"></i> Monitor
                        </button>
                        <button class="btn-warning" onclick="duplicateIoTTask(1)">
                            <i class="fas fa-copy"></i> Duplikat
                        </button>
                        <button class="btn-danger" onclick="deleteIoTTask(1)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>

                <div class="iot-task-card">
                    <div class="iot-task-header">
                        <div>
                            <div class="iot-task-title">Kontrol Lampu Otomatis</div>
                            <div class="iot-task-subject">Fisika</div>
                        </div>
                        <div class="iot-task-type type-actuator">Actuator</div>
                    </div>
                    <div class="iot-task-description">
                        Tugas untuk membuat sistem kontrol lampu otomatis berdasarkan sensor cahaya.
                    </div>
                    <div class="iot-device-info">
                        <div class="device-title">Perangkat IoT</div>
                        <div class="device-details">ESP32 + LDR + Relay + LED</div>
                    </div>
                    <div class="sensor-data">
                        <div class="sensor-title">Data Sensor</div>
                        <div class="sensor-values">
                            <div class="sensor-value">
                                <div class="sensor-label">Cahaya</div>
                                <div class="sensor-data-value">320 lux</div>
                            </div>
                            <div class="sensor-value">
                                <div class="sensor-label">Status</div>
                                <div class="sensor-data-value">ON</div>
                            </div>
                        </div>
                    </div>
                    <div class="iot-task-info">
                        <div class="info-item">
                            <span class="info-label">Durasi:</span>
                            <span class="info-value">5 hari</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kesulitan:</span>
                            <span class="info-value">Mudah</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kelas:</span>
                            <span class="info-value">X IPA 2</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status:</span>
                            <span class="status-badge status-active">Aktif</span>
                        </div>
                    </div>
                    <div class="iot-task-actions">
                        <button class="btn-primary" onclick="viewIoTTask(2)">
                            <i class="fas fa-eye"></i> Lihat
                        </button>
                        <button class="btn-secondary" onclick="editIoTTask(2)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-success" onclick="monitorIoTTask(2)">
                            <i class="fas fa-chart-line"></i> Monitor
                        </button>
                        <button class="btn-warning" onclick="duplicateIoTTask(2)">
                            <i class="fas fa-copy"></i> Duplikat
                        </button>
                        <button class="btn-danger" onclick="deleteIoTTask(2)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>

                <div class="iot-task-card">
                    <div class="iot-task-header">
                        <div>
                            <div class="iot-task-title">Monitoring Kualitas Air</div>
                            <div class="iot-task-subject">Kimia</div>
                        </div>
                        <div class="iot-task-type type-monitoring">Monitoring</div>
                    </div>
                    <div class="iot-task-description">
                        Tugas untuk memantau kualitas air menggunakan sensor pH, TDS, dan suhu.
                    </div>
                    <div class="iot-device-info">
                        <div class="device-title">Perangkat IoT</div>
                        <div class="device-details">Arduino Mega + pH Sensor + TDS Sensor</div>
                    </div>
                    <div class="sensor-data">
                        <div class="sensor-title">Data Sensor</div>
                        <div class="sensor-values">
                            <div class="sensor-value">
                                <div class="sensor-label">pH</div>
                                <div class="sensor-data-value">7.2</div>
                            </div>
                            <div class="sensor-value">
                                <div class="sensor-label">TDS</div>
                                <div class="sensor-data-value">150 ppm</div>
                            </div>
                        </div>
                    </div>
                    <div class="iot-task-info">
                        <div class="info-item">
                            <span class="info-label">Durasi:</span>
                            <span class="info-value">10 hari</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kesulitan:</span>
                            <span class="info-value">Sulit</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kelas:</span>
                            <span class="info-value">XI IPA 1</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status:</span>
                            <span class="status-badge status-completed">Selesai</span>
                        </div>
                    </div>
                    <div class="iot-task-actions">
                        <button class="btn-primary" onclick="viewIoTTask(3)">
                            <i class="fas fa-eye"></i> Lihat
                        </button>
                        <button class="btn-secondary" onclick="editIoTTask(3)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-success" onclick="viewResults(3)">
                            <i class="fas fa-chart-bar"></i> Hasil
                        </button>
                        <button class="btn-warning" onclick="duplicateIoTTask(3)">
                            <i class="fas fa-copy"></i> Duplikat
                        </button>
                        <button class="btn-danger" onclick="deleteIoTTask(3)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>

                <div class="iot-task-card">
                    <div class="iot-task-header">
                        <div>
                            <div class="iot-task-title">Sistem Keamanan IoT</div>
                            <div class="iot-task-subject">Fisika</div>
                        </div>
                        <div class="iot-task-type type-control">Control</div>
                    </div>
                    <div class="iot-task-description">
                        Tugas untuk membuat sistem keamanan menggunakan sensor PIR dan buzzer.
                    </div>
                    <div class="iot-device-info">
                        <div class="device-title">Perangkat IoT</div>
                        <div class="device-details">Raspberry Pi + PIR Sensor + Buzzer + Camera</div>
                    </div>
                    <div class="sensor-data">
                        <div class="sensor-title">Data Sensor</div>
                        <div class="sensor-values">
                            <div class="sensor-value">
                                <div class="sensor-label">PIR</div>
                                <div class="sensor-data-value">OFF</div>
                            </div>
                            <div class="sensor-value">
                                <div class="sensor-label">Status</div>
                                <div class="sensor-data-value">Safe</div>
                            </div>
                        </div>
                    </div>
                    <div class="iot-task-info">
                        <div class="info-item">
                            <span class="info-label">Durasi:</span>
                            <span class="info-value">14 hari</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kesulitan:</span>
                            <span class="info-value">Sulit</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kelas:</span>
                            <span class="info-value">XII IPA 1</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status:</span>
                            <span class="status-badge status-pending">Pending</span>
                        </div>
                    </div>
                    <div class="iot-task-actions">
                        <button class="btn-primary" onclick="viewIoTTask(4)">
                            <i class="fas fa-eye"></i> Lihat
                        </button>
                        <button class="btn-secondary" onclick="editIoTTask(4)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-success" onclick="startIoTTask(4)">
                            <i class="fas fa-play"></i> Mulai
                        </button>
                        <button class="btn-warning" onclick="duplicateIoTTask(4)">
                            <i class="fas fa-copy"></i> Duplikat
                        </button>
                        <button class="btn-danger" onclick="deleteIoTTask(4)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>

                <div class="iot-task-card">
                    <div class="iot-task-header">
                        <div>
                            <div class="iot-task-title">Smart Garden System</div>
                            <div class="iot-task-subject">Biologi</div>
                        </div>
                        <div class="iot-task-type type-monitoring">Monitoring</div>
                    </div>
                    <div class="iot-task-description">
                        Tugas untuk membuat sistem monitoring taman otomatis dengan sensor kelembaban tanah.
                    </div>
                    <div class="iot-device-info">
                        <div class="device-title">Perangkat IoT</div>
                        <div class="device-details">Arduino Uno + Soil Moisture + Water Pump</div>
                    </div>
                    <div class="sensor-data">
                        <div class="sensor-title">Data Sensor</div>
                        <div class="sensor-values">
                            <div class="sensor-value">
                                <div class="sensor-label">Kelembaban</div>
                                <div class="sensor-data-value">45%</div>
                            </div>
                            <div class="sensor-value">
                                <div class="sensor-label">Pompa</div>
                                <div class="sensor-data-value">OFF</div>
                            </div>
                        </div>
                    </div>
                    <div class="iot-task-info">
                        <div class="info-item">
                            <span class="info-label">Durasi:</span>
                            <span class="info-value">21 hari</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kesulitan:</span>
                            <span class="info-value">Sedang</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kelas:</span>
                            <span class="info-value">XI IPA 2</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status:</span>
                            <span class="status-badge status-draft">Draft</span>
                        </div>
                    </div>
                    <div class="iot-task-actions">
                        <button class="btn-primary" onclick="viewIoTTask(5)">
                            <i class="fas fa-eye"></i> Lihat
                        </button>
                        <button class="btn-secondary" onclick="editIoTTask(5)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-success" onclick="publishIoTTask(5)">
                            <i class="fas fa-paper-plane"></i> Publikasi
                        </button>
                        <button class="btn-warning" onclick="duplicateIoTTask(5)">
                            <i class="fas fa-copy"></i> Duplikat
                        </button>
                        <button class="btn-danger" onclick="deleteIoTTask(5)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>

                <div class="iot-task-card">
                    <div class="iot-task-header">
                        <div>
                            <div class="iot-task-title">Weather Station</div>
                            <div class="iot-task-subject">Fisika</div>
                        </div>
                        <div class="iot-task-type type-sensor">Sensor</div>
                    </div>
                    <div class="iot-task-description">
                        Tugas untuk membuat stasiun cuaca mini dengan sensor suhu, kelembaban, dan tekanan.
                    </div>
                    <div class="iot-device-info">
                        <div class="device-title">Perangkat IoT</div>
                        <div class="device-details">ESP32 + BME280 + Anemometer</div>
                    </div>
                    <div class="sensor-data">
                        <div class="sensor-title">Data Sensor</div>
                        <div class="sensor-values">
                            <div class="sensor-value">
                                <div class="sensor-label">Suhu</div>
                                <div class="sensor-data-value">28.5°C</div>
                            </div>
                            <div class="sensor-value">
                                <div class="sensor-label">Tekanan</div>
                                <div class="sensor-data-value">1013 hPa</div>
                            </div>
                        </div>
                    </div>
                    <div class="iot-task-info">
                        <div class="info-item">
                            <span class="info-label">Durasi:</span>
                            <span class="info-value">30 hari</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kesulitan:</span>
                            <span class="info-value">Sulit</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kelas:</span>
                            <span class="info-value">XII IPA 2</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status:</span>
                            <span class="status-badge status-active">Aktif</span>
                        </div>
                    </div>
                    <div class="iot-task-actions">
                        <button class="btn-primary" onclick="viewIoTTask(6)">
                            <i class="fas fa-eye"></i> Lihat
                        </button>
                        <button class="btn-secondary" onclick="editIoTTask(6)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-success" onclick="monitorIoTTask(6)">
                            <i class="fas fa-chart-line"></i> Monitor
                        </button>
                        <button class="btn-warning" onclick="duplicateIoTTask(6)">
                            <i class="fas fa-copy"></i> Duplikat
                        </button>
                        <button class="btn-danger" onclick="deleteIoTTask(6)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
@endsection
