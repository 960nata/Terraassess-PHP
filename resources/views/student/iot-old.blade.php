@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - IoT Research')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-microchip"></i>
        Penelitian IoT
    </h1>
    <p class="page-description">Kumpulkan dan analisis data sensor IoT untuk penelitian Anda</p>
</div>

<div class="iot-container">
    <div class="glass-card">
        <div class="iot-header">
            <h2 class="iot-title">Sistem Monitoring IoT</h2>
            <div class="iot-meta">
                <div class="meta-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span id="currentDateTime"></span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-circle"></i>
                    <span id="connectionStatus">Terputus</span>
                </div>
            </div>
        </div>

        <div class="iot-content">
            <div class="iot-description">
                <h3>Deskripsi Sistem</h3>
                <p>Gunakan perangkat IoT untuk mengumpulkan data sensor tanah seperti suhu, kelembaban, dan kadar humus. Data ini dapat digunakan untuk penelitian dan analisis kualitas tanah.</p>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-database"></i>
                    </div>
                    <div class="stats-number">{{ $myReadings->total() }}</div>
                    <div class="stats-label">Total Data Saya</div>
                </div>
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="stats-number">{{ $myReadings->where('timestamp', '>=', today())->count() }}</div>
                    <div class="stats-label">Data Hari Ini</div>
                </div>
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-thermometer-half"></i>
                    </div>
                    <div class="stats-number">{{ $myReadings->avg('soil_temperature') ? number_format($myReadings->avg('soil_temperature'), 1) . '°C' : 'N/A' }}</div>
                    <div class="stats-label">Suhu Rata-rata</div>
                </div>
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-tint"></i>
                    </div>
                    <div class="stats-number">{{ $myReadings->avg('soil_moisture') ? number_format($myReadings->avg('soil_moisture'), 1) . '%' : 'N/A' }}</div>
                    <div class="stats-label">Kelembaban Rata-rata</div>
                </div>
            </div>

            <!-- IoT Control Panel -->
            <div class="iot-control-panel">
                <h3>Kontrol Perangkat IoT</h3>
                
                <div class="control-form">
                    <div class="form-group">
                        <label>Lokasi Pengukuran</label>
                        <input type="text" id="location" placeholder="Masukkan lokasi pengukuran">
                    </div>
                    
                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea id="notes" rows="3" placeholder="Masukkan catatan tambahan"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Kelas</label>
                        <select id="selectKelas">
                            <option value="">-- Pilih Kelas --</option>
                            @if(Auth::user()->kelas_id)
                                <option value="{{ Auth::user()->kelas_id }}" selected>{{ Auth::user()->kelas->name ?? 'Kelas Saya' }}</option>
                            @endif
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Mode Pengukuran</label>
                        <select id="measurementMode">
                            <option value="auto">Otomatis (dari alat IoT)</option>
                            <option value="manual">Manual (input sendiri)</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button class="btn btn-primary" id="scanDeviceBtn" onclick="scanDevice()">
                        <i class="fas fa-bluetooth"></i>
                        Scan & Ambil Data
                    </button>
                    <button class="btn btn-success" id="saveDataBtn" onclick="saveData()" disabled>
                        <i class="fas fa-save"></i>
                        Simpan Data
                    </button>
                </div>
            </div>

            <!-- Real-time Data Display -->
            <div class="real-time-display" id="realTimeDataSection" style="display: none;">
                <h3>Data Real-time</h3>
                
                <div class="sensor-grid">
                    <div class="sensor-card">
                        <i class="fas fa-thermometer-half"></i>
                        <h4 id="realTimeTemp">--°C</h4>
                        <small>Suhu Tanah</small>
                    </div>
                    <div class="sensor-card">
                        <i class="fas fa-seedling"></i>
                        <h4 id="realTimeHumus">--%</h4>
                        <small>Kadar Humus</small>
                    </div>
                    <div class="sensor-card">
                        <i class="fas fa-tint"></i>
                        <h4 id="realTimeMoisture">--%</h4>
                        <small>Kelembaban Tanah</small>
                    </div>
                </div>
            </div>

            <!-- Manual Input Form -->
            <div class="manual-input-section" id="manualInputSection" style="display: none;">
                <h3>Input Data Manual</h3>
                
                <div class="input-grid">
                    <div class="form-group">
                        <label>Suhu Tanah (°C)</label>
                        <input type="number" id="manualTemp" step="0.1" placeholder="Masukkan suhu">
                    </div>
                    <div class="form-group">
                        <label>Kadar Humus (%)</label>
                        <input type="number" id="manualHumus" step="0.1" placeholder="Masukkan kadar humus">
                    </div>
                    <div class="form-group">
                        <label>Kelembaban Tanah (%)</label>
                        <input type="number" id="manualMoisture" step="0.1" placeholder="Masukkan kelembaban">
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="data-section">
                <div class="data-header">
                    <h3>Data Saya</h3>
                    <div class="data-actions">
                        <button class="btn btn-secondary" onclick="refreshData()">
                            <i class="fas fa-sync-alt"></i>
                            Refresh
                        </button>
                        <button class="btn btn-success" onclick="exportMyData()">
                            <i class="fas fa-download"></i>
                            Export CSV
                        </button>
                    </div>
                </div>
                
                <div class="data-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Kelas</th>
                                <th>Suhu</th>
                                <th>Humus</th>
                                <th>Kelembaban</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($myReadings as $reading)
                                <tr>
                                    <td>{{ $reading->timestamp ? (is_string($reading->timestamp) ? \Carbon\Carbon::parse($reading->timestamp)->format('d/m/Y H:i') : $reading->timestamp->format('d/m/Y H:i')) : '-' }}</td>
                                    <td>{{ $reading->kelas->name ?? $reading->class_id }}</td>
                                    <td>{{ $reading->formatted_soil_temperature }}</td>
                                    <td>{{ $reading->formatted_soil_humus }}</td>
                                    <td>{{ $reading->formatted_soil_moisture }}</td>
                                    <td>{{ $reading->location ?? '-' }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $reading->soil_quality_color }}">
                                            {{ $reading->soil_quality_status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="pagination">
                    {{ $myReadings->links() }}
                </div>
            </div>

            <!-- Class Data (if available) -->
            @if($classReadings->count() > 0)
            <div class="data-section">
                <h3>Data Kelas</h3>
                
                <div class="data-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Siswa</th>
                                <th>Suhu</th>
                                <th>Humus</th>
                                <th>Kelembaban</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($classReadings as $reading)
                                <tr>
                                    <td>{{ $reading->timestamp ? (is_string($reading->timestamp) ? \Carbon\Carbon::parse($reading->timestamp)->format('d/m/Y H:i') : $reading->timestamp->format('d/m/Y H:i')) : '-' }}</td>
                                    <td>{{ $reading->student->name ?? $reading->student_id }}</td>
                                    <td>{{ $reading->formatted_soil_temperature }}</td>
                                    <td>{{ $reading->formatted_soil_humus }}</td>
                                    <td>{{ $reading->formatted_soil_moisture }}</td>
                                    <td>{{ $reading->location ?? '-' }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $reading->soil_quality_color }}">
                                            {{ $reading->soil_quality_status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('additional-styles')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .glass-card:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-2px);
    }
    
    .iot-header {
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(0, 153, 204, 0.05));
        backdrop-filter: blur(15px);
        border: 1px solid rgba(0, 212, 255, 0.3);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .stats-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-3px);
        background: rgba(255, 255, 255, 0.15);
    }
    
    .stats-icon {
        font-size: 2.5rem;
        color: #00d4ff;
        margin-bottom: 1rem;
    }
    
    .stats-number {
        font-size: 2rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.5rem;
    }
    
    .stats-label {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.8);
    }
    
    .iot-control-panel {
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(0, 153, 204, 0.05));
        backdrop-filter: blur(15px);
        border: 1px solid rgba(0, 212, 255, 0.3);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .form-control, .form-select {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        border-radius: 10px;
    }
    
    .form-control:focus, .form-select:focus {
        background: rgba(255, 255, 255, 0.15);
        border-color: #00d4ff;
        color: white;
        box-shadow: 0 0 0 0.2rem rgba(0, 212, 255, 0.25);
    }
    
    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #007bff, #0056b3);
        border: none;
        border-radius: 25px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
    }
    
    .btn-success {
        background: linear-gradient(135deg, #28a745, #1e7e34);
        border: none;
        border-radius: 25px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
    }
    
    .btn-warning {
        background: linear-gradient(135deg, #ffc107, #e0a800);
        border: none;
        border-radius: 25px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        color: #212529;
    }
    
    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
    }
    
    .data-table {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        overflow: hidden;
    }
    
    .table-dark {
        --bs-table-bg: transparent;
        --bs-table-color: white;
    }
    
    .table-dark th {
        background: rgba(0, 212, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.1);
        color: #00d4ff;
        font-weight: 600;
    }
    
    .table-dark td {
        border-color: rgba(255, 255, 255, 0.1);
    }
    
    .table-dark tbody tr:hover {
        background: rgba(255, 255, 255, 0.05);
    }
    
    .status-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .status-excellent { 
        background: rgba(40, 167, 69, 0.2); 
        color: #28a745; 
        border: 1px solid rgba(40, 167, 69, 0.3);
    }
    
    .status-good { 
        background: rgba(0, 123, 255, 0.2); 
        color: #007bff; 
        border: 1px solid rgba(0, 123, 255, 0.3);
    }
    
    .status-fair { 
        background: rgba(255, 193, 7, 0.2); 
        color: #ffc107; 
        border: 1px solid rgba(255, 193, 7, 0.3);
    }
    
    .status-poor { 
        background: rgba(220, 53, 69, 0.2); 
        color: #dc3545; 
        border: 1px solid rgba(220, 53, 69, 0.3);
    }
    
    .real-time-display {
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(0, 153, 204, 0.05));
        border: 1px solid rgba(0, 212, 255, 0.3);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .sensor-value {
        font-size: 2rem;
        font-weight: 800;
        color: #00d4ff;
    }
    
    .connection-status {
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .status-connected { 
        background: rgba(40, 167, 69, 0.2); 
        color: #28a745; 
        border: 1px solid rgba(40, 167, 69, 0.3);
    }
    
    .status-disconnected { 
        background: rgba(220, 53, 69, 0.2); 
        color: #dc3545; 
        border: 1px solid rgba(220, 53, 69, 0.3);
    }
</style>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="text-white display-4 fw-bold mb-2">
                <i class="fas fa-microchip me-3 text-primary"></i>Penelitian IoT
            </h1>
            <p class="text-white-75 fs-5 mb-0">Kumpulkan dan analisis data sensor IoT untuk penelitian Anda</p>
        </div>
        <div class="text-end">
            <div class="glass-card p-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                    <span class="text-white-75" id="currentDateTime"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- IoT Header -->
    <div class="iot-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="text-white mb-3">Sistem Monitoring IoT</h2>
                <p class="text-white-75 mb-0">
                    Gunakan perangkat IoT untuk mengumpulkan data sensor tanah seperti suhu, kelembaban, dan kadar humus. 
                    Data ini dapat digunakan untuk penelitian dan analisis kualitas tanah.
                </p>
            </div>
            <div class="col-md-4 text-end">
                <div class="connection-status status-disconnected" id="connectionStatus">
                    <i class="fas fa-circle me-1"></i>Terputus
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="fas fa-database"></i>
                </div>
                <div class="stats-number">{{ $myReadings->total() }}</div>
                <div class="stats-label">Total Data Saya</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stats-number">{{ $myReadings->where('timestamp', '>=', today())->count() }}</div>
                <div class="stats-label">Data Hari Ini</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="fas fa-thermometer-half"></i>
                </div>
                <div class="stats-number">{{ $myReadings->avg('soil_temperature') ? number_format($myReadings->avg('soil_temperature'), 1) . '°C' : 'N/A' }}</div>
                <div class="stats-label">Suhu Rata-rata</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon">
                    <i class="fas fa-tint"></i>
                </div>
                <div class="stats-number">{{ $myReadings->avg('soil_moisture') ? number_format($myReadings->avg('soil_moisture'), 1) . '%' : 'N/A' }}</div>
                <div class="stats-label">Kelembaban Rata-rata</div>
            </div>
        </div>
    </div>

    <!-- IoT Control Panel -->
    <div class="iot-control-panel">
        <h3 class="text-white mb-4">
            <i class="fas fa-bluetooth me-2 text-primary"></i>Kontrol Perangkat IoT
        </h3>
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="text-white-75 fw-semibold mb-2">Lokasi Pengukuran</label>
                    <input type="text" class="form-control" id="location" placeholder="Masukkan lokasi pengukuran">
                </div>
                
                <div class="mb-4">
                    <label class="text-white-75 fw-semibold mb-2">Catatan</label>
                    <textarea class="form-control" id="notes" rows="3" placeholder="Masukkan catatan tambahan"></textarea>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="text-white-75 fw-semibold mb-2">Kelas</label>
                    <select class="form-select" id="selectKelas">
                        <option value="">-- Pilih Kelas --</option>
                        @if(Auth::user()->kelas_id)
                            <option value="{{ Auth::user()->kelas_id }}" selected>{{ Auth::user()->kelas->name ?? 'Kelas Saya' }}</option>
                        @endif
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="text-white-75 fw-semibold mb-2">Mode Pengukuran</label>
                    <select class="form-select" id="measurementMode">
                        <option value="auto">Otomatis (dari alat IoT)</option>
                        <option value="manual">Manual (input sendiri)</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="text-center">
            <button class="btn btn-primary btn-lg me-3" id="scanDeviceBtn" onclick="scanDevice()">
                <i class="fas fa-bluetooth me-2"></i>Scan & Ambil Data
            </button>
            <button class="btn btn-success btn-lg" id="saveDataBtn" onclick="saveData()" disabled>
                <i class="fas fa-save me-2"></i>Simpan Data
            </button>
        </div>
    </div>

    <!-- Real-time Data Display -->
    <div class="real-time-display" id="realTimeDataSection" style="display: none;">
        <h3 class="text-white mb-4">
            <i class="fas fa-chart-line me-2 text-primary"></i>Data Real-time
        </h3>
        
        <div class="row text-center">
            <div class="col-md-4 mb-3">
                <div class="bg-primary bg-opacity-20 rounded p-3">
                    <i class="fas fa-thermometer-half fa-2x text-primary mb-2"></i>
                    <h4 class="text-white mb-1" id="realTimeTemp">--°C</h4>
                    <small class="text-white-75">Suhu Tanah</small>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="bg-success bg-opacity-20 rounded p-3">
                    <i class="fas fa-seedling fa-2x text-success mb-2"></i>
                    <h4 class="text-white mb-1" id="realTimeHumus">--%</h4>
                    <small class="text-white-75">Kadar Humus</small>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="bg-info bg-opacity-20 rounded p-3">
                    <i class="fas fa-tint fa-2x text-info mb-2"></i>
                    <h4 class="text-white mb-1" id="realTimeMoisture">--%</h4>
                    <small class="text-white-75">Kelembaban Tanah</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Manual Input Form -->
    <div class="glass-card" id="manualInputSection" style="display: none;">
        <h3 class="text-white mb-4">
            <i class="fas fa-edit me-2 text-primary"></i>Input Data Manual
        </h3>
        
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="text-white-75 fw-semibold mb-2">Suhu Tanah (°C)</label>
                <input type="number" class="form-control" id="manualTemp" step="0.1" placeholder="Masukkan suhu">
            </div>
            <div class="col-md-4 mb-3">
                <label class="text-white-75 fw-semibold mb-2">Kadar Humus (%)</label>
                <input type="number" class="form-control" id="manualHumus" step="0.1" placeholder="Masukkan kadar humus">
            </div>
            <div class="col-md-4 mb-3">
                <label class="text-white-75 fw-semibold mb-2">Kelembaban Tanah (%)</label>
                <input type="number" class="form-control" id="manualMoisture" step="0.1" placeholder="Masukkan kelembaban">
            </div>
        </div>
    </div>

    <!-- My Data Table -->
    <div class="glass-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-white mb-0">
                <i class="fas fa-table me-2 text-primary"></i>Data Saya
            </h3>
            <div>
                <button class="btn btn-outline-primary btn-sm me-2" onclick="refreshData()">
                    <i class="fas fa-sync-alt me-1"></i>Refresh
                </button>
                <button class="btn btn-outline-success btn-sm" onclick="exportMyData()">
                    <i class="fas fa-download me-1"></i>Export CSV
                </button>
            </div>
        </div>
        
        <div class="data-table">
            <div class="table-responsive">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Kelas</th>
                            <th>Suhu</th>
                            <th>Humus</th>
                            <th>Kelembaban</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($myReadings as $reading)
                            <tr>
                                <td>{{ $reading->timestamp ? $reading->timestamp->format('d/m/Y H:i') : '-' }}</td>
                                <td>{{ $reading->kelas->name ?? $reading->class_id }}</td>
                                <td>{{ $reading->formatted_soil_temperature }}</td>
                                <td>{{ $reading->formatted_soil_humus }}</td>
                                <td>{{ $reading->formatted_soil_moisture }}</td>
                                <td>{{ $reading->location ?? '-' }}</td>
                                <td>
                                    <span class="status-badge status-{{ $reading->soil_quality_color }}">
                                        {{ $reading->soil_quality_status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-white-75">Belum ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                {{ $myReadings->links() }}
            </div>
        </div>
    </div>

    <!-- Class Data (if available) -->
    @if($classReadings->count() > 0)
    <div class="glass-card mt-4">
        <h3 class="text-white mb-4">
            <i class="fas fa-users me-2 text-primary"></i>Data Kelas
        </h3>
        
        <div class="data-table">
            <div class="table-responsive">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Siswa</th>
                            <th>Suhu</th>
                            <th>Humus</th>
                            <th>Kelembaban</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($classReadings as $reading)
                            <tr>
                                <td>{{ $reading->timestamp ? $reading->timestamp->format('d/m/Y H:i') : '-' }}</td>
                                <td>{{ $reading->student->name ?? $reading->student_id }}</td>
                                <td>{{ $reading->formatted_soil_temperature }}</td>
                                <td>{{ $reading->formatted_soil_humus }}</td>
                                <td>{{ $reading->formatted_soil_moisture }}</td>
                                <td>{{ $reading->location ?? '-' }}</td>
                                <td>
                                    <span class="status-badge status-{{ $reading->soil_quality_color }}">
                                        {{ $reading->soil_quality_status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    let currentDevice = null;
    let currentData = null;
    let isConnected = false;

    // Update current date and time
    function updateDateTime() {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        document.getElementById('currentDateTime').textContent = now.toLocaleDateString('id-ID', options);
    }
    
    updateDateTime();
    setInterval(updateDateTime, 60000);

    // Handle measurement mode change
    document.getElementById('measurementMode').addEventListener('change', function() {
        const mode = this.value;
        const scanBtn = document.getElementById('scanDeviceBtn');
        const manualSection = document.getElementById('manualInputSection');
        
        if (mode === 'manual') {
            scanBtn.innerHTML = '<i class="fas fa-edit me-2"></i>Input Data Manual';
            manualSection.style.display = 'block';
        } else {
            scanBtn.innerHTML = '<i class="fas fa-bluetooth me-2"></i>Scan & Ambil Data';
            manualSection.style.display = 'none';
        }
    });

    // Scan and connect to IoT device
    async function scanDevice() {
        const mode = document.getElementById('measurementMode').value;
        
        if (mode === 'manual') {
            showManualInput();
            return;
        }
        
        const scanBtn = document.getElementById('scanDeviceBtn');
        const saveBtn = document.getElementById('saveDataBtn');
        
        scanBtn.disabled = true;
        scanBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Scanning...';
        
        try {
            // Check if Web Bluetooth is supported
            if (!navigator.bluetooth) {
                throw new Error('Web Bluetooth tidak didukung di browser ini. Gunakan mode manual.');
            }
            
            // Request device
            const device = await navigator.bluetooth.requestDevice({
                acceptAllDevices: true,
                optionalServices: ['0000180a-0000-1000-8000-00805f9b34fb'] // Device Information Service
            });
            
            currentDevice = device;
            
            // Connect to device
            const server = await device.gatt.connect();
            isConnected = true;
            
            // Show real-time data section
            document.getElementById('realTimeDataSection').style.display = 'block';
            
            // Start reading data
            startReadingData(server);
            
            scanBtn.innerHTML = '<i class="fas fa-check me-2"></i>Terhubung';
            saveBtn.disabled = false;
            
            // Update connection status
            document.getElementById('connectionStatus').innerHTML = '<i class="fas fa-circle me-1"></i>Terhubung';
            document.getElementById('connectionStatus').className = 'connection-status status-connected';
            
        } catch (error) {
            console.error('Error connecting to device:', error);
            alert('Gagal terhubung ke perangkat: ' + error.message + '\n\nGunakan mode manual untuk input data.');
            
            scanBtn.disabled = false;
            scanBtn.innerHTML = '<i class="fas fa-bluetooth me-2"></i>Scan & Ambil Data';
        }
    }

    // Show manual input form
    function showManualInput() {
        document.getElementById('manualInputSection').style.display = 'block';
        document.getElementById('saveDataBtn').disabled = false;
    }

    // Start reading data from device
    async function startReadingData(server) {
        try {
            // Get device information service
            const service = await server.getPrimaryService('0000180a-0000-1000-8000-00805f9b34fb');
            
            // Simulate data reading (replace with actual characteristic reading)
            setInterval(() => {
                if (isConnected) {
                    // Load real sensor data from database
                    currentData = {
                        soil_temperature: '--', // Data akan dimuat dari database
                        soil_humus: '--', // Data akan dimuat dari database
                        soil_moisture: '--', // Data akan dimuat dari database
                        timestamp: new Date().toISOString()
                    };
                    
                    // Update UI
                    document.getElementById('realTimeTemp').textContent = currentData.soil_temperature + '°C';
                    document.getElementById('realTimeHumus').textContent = currentData.soil_humus + '%';
                    document.getElementById('realTimeMoisture').textContent = currentData.soil_moisture + '%';
                }
            }, 2000);
            
        } catch (error) {
            console.error('Error reading data:', error);
        }
    }

    // Save data to database
    async function saveData() {
        const mode = document.getElementById('measurementMode').value;
        const classId = document.getElementById('selectKelas').value;
        const location = document.getElementById('location').value;
        const notes = document.getElementById('notes').value;
        
        if (!classId) {
            alert('Pilih kelas terlebih dahulu');
            return;
        }
        
        let dataToSave = {};
        
        if (mode === 'manual') {
            const temp = document.getElementById('manualTemp').value;
            const humus = document.getElementById('manualHumus').value;
            const moisture = document.getElementById('manualMoisture').value;
            
            if (!temp || !humus || !moisture) {
                alert('Isi semua data manual terlebih dahulu');
                return;
            }
            
            dataToSave = {
                soil_temperature: parseFloat(temp),
                soil_humus: parseFloat(humus),
                soil_moisture: parseFloat(moisture),
                timestamp: new Date().toISOString()
            };
        } else {
            if (!currentData) {
                alert('Tidak ada data untuk disimpan');
                return;
            }
            
            dataToSave = currentData;
        }
        
        try {
            const response = await fetch('/api/iot/readings', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    student_id: '{{ Auth::user()->nis }}',
                    class_id: classId,
                    soil_temperature: parseFloat(dataToSave.soil_temperature),
                    soil_humus: parseFloat(dataToSave.soil_humus),
                    soil_moisture: parseFloat(dataToSave.soil_moisture),
                    device_id: currentDevice ? currentDevice.id : 'manual',
                    location: location,
                    notes: notes,
                    raw_data: dataToSave
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert('Data berhasil disimpan!');
                refreshData();
            } else {
                alert('Gagal menyimpan data: ' + result.message);
            }
            
        } catch (error) {
            console.error('Error saving data:', error);
            alert('Gagal menyimpan data: ' + error.message);
        }
    }

    // Refresh data table
    function refreshData() {
        location.reload();
    }

    // Export my data to CSV
    function exportMyData() {
        window.open('/api/iot/readings/export?student_id={{ Auth::user()->nis }}', '_blank');
    }
</script>
@endsection
