@extends('layouts.unified-layout')

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
    <!-- Modern Statistics Cards -->
    <div class="stats-grid">
        <div class="stats-card">
            <div class="stats-card-header">
                <div class="stats-icon">
                    <i class="fas fa-database"></i>
                </div>
                <div class="stats-trend">
                    <i class="fas fa-arrow-up"></i>
                </div>
            </div>
            <div class="stats-content">
                <div class="stats-number">{{ $myReadings->total() }}</div>
                <div class="stats-label">Total Data Saya</div>
                <div class="stats-description">Data yang telah dikumpulkan</div>
            </div>
        </div>
        
        <div class="stats-card">
            <div class="stats-card-header">
                <div class="stats-icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stats-trend">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stats-content">
                <div class="stats-number">{{ $myReadings->where('timestamp', '>=', today())->count() }}</div>
                <div class="stats-label">Data Hari Ini</div>
                <div class="stats-description">Pengukuran terbaru</div>
            </div>
        </div>
        
        <div class="stats-card">
            <div class="stats-card-header">
                <div class="stats-icon">
                    <i class="fas fa-thermometer-half"></i>
                </div>
                <div class="stats-trend">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="stats-content">
                <div class="stats-number">{{ $myReadings->avg('soil_temperature') ? number_format($myReadings->avg('soil_temperature'), 1) . '°C' : 'N/A' }}</div>
                <div class="stats-label">Suhu Rata-rata</div>
                <div class="stats-description">Temperatur tanah</div>
            </div>
        </div>
        
        <div class="stats-card">
            <div class="stats-card-header">
                <div class="stats-icon">
                    <i class="fas fa-tint"></i>
                </div>
                <div class="stats-trend">
                    <i class="fas fa-droplet"></i>
                </div>
            </div>
            <div class="stats-content">
                <div class="stats-number">{{ $myReadings->avg('soil_moisture') ? number_format($myReadings->avg('soil_moisture'), 1) . '%' : 'N/A' }}</div>
                <div class="stats-label">Kelembaban Rata-rata</div>
                <div class="stats-description">Kadar air tanah</div>
            </div>
        </div>
    </div>

    <!-- Modern Control Panel -->
    <div class="control-panel">
        <div class="control-header">
            <h2 class="control-title">
                <i class="fas fa-cogs"></i>
                Kontrol Perangkat IoT
            </h2>
            <div class="connection-status">
                <div class="status-indicator" id="connectionIndicator"></div>
                <span id="connectionStatus">Terputus</span>
            </div>
        </div>
        
        <div class="control-content">
            <div class="control-grid">
                <div class="control-section">
                    <h3 class="section-title">
                        <i class="fas fa-map-marker-alt"></i>
                        Informasi Pengukuran
                    </h3>
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
                </div>
                
                <div class="control-section">
                    <h3 class="section-title">
                        <i class="fas fa-sliders-h"></i>
                        Mode Pengukuran
                    </h3>
                    <div class="mode-selector">
                        <div class="mode-option" data-mode="auto">
                            <div class="mode-icon">
                                <i class="fas fa-robot"></i>
                            </div>
                            <div class="mode-content">
                                <h4>Otomatis</h4>
                                <p>Dari alat IoT</p>
                            </div>
                            <input type="radio" name="measurementMode" value="auto" id="mode-auto" checked>
                        </div>
                        <div class="mode-option" data-mode="manual">
                            <div class="mode-icon">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="mode-content">
                                <h4>Manual</h4>
                                <p>Input sendiri</p>
                            </div>
                            <input type="radio" name="measurementMode" value="manual" id="mode-manual">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="connection-methods">
                <h3 class="section-title">
                    <i class="fas fa-wifi"></i>
                    Metode Koneksi
                </h3>
                <div class="method-buttons">
                    <button type="button" class="method-btn" id="usb-method-btn" onclick="selectConnectionMethod('usb')">
                        <i class="fas fa-usb"></i>
                        <span>USB</span>
                    </button>
                    <button type="button" class="method-btn active" id="bluetooth-method-btn" onclick="selectConnectionMethod('bluetooth')">
                        <i class="fas fa-bluetooth"></i>
                        <span>Bluetooth</span>
                    </button>
                </div>
            </div>
            
            <div class="action-buttons">
                <button class="btn-primary" id="scanDeviceBtn" onclick="scanDevice()">
                    <i class="fas fa-bluetooth"></i>
                    <span>Scan & Ambil Data</span>
                </button>
                <button class="btn-success" id="saveDataBtn" onclick="saveData()" disabled>
                    <i class="fas fa-save"></i>
                    <span>Simpan Data</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Real-time Data Display -->
    <div class="realtime-panel" id="realTimeDataSection" style="display: none;">
        <div class="panel-header">
            <h2 class="panel-title">
                <i class="fas fa-chart-line"></i>
                Data Real-time
            </h2>
            <div class="live-indicator">
                <div class="pulse-dot"></div>
                <span>LIVE</span>
            </div>
        </div>
        
        <div class="sensor-grid">
            <div class="sensor-card temperature">
                <div class="sensor-icon">
                    <i class="fas fa-thermometer-half"></i>
                </div>
                <div class="sensor-content">
                    <div class="sensor-value" id="realTimeTemp">--°C</div>
                    <div class="sensor-label">Suhu Tanah</div>
                    <div class="sensor-status">Normal</div>
                </div>
            </div>
            
            <div class="sensor-card humus">
                <div class="sensor-icon">
                    <i class="fas fa-seedling"></i>
                </div>
                <div class="sensor-content">
                    <div class="sensor-value" id="realTimeHumus">--%</div>
                    <div class="sensor-label">Kadar Humus</div>
                    <div class="sensor-status">Baik</div>
                </div>
            </div>
            
            <div class="sensor-card moisture">
                <div class="sensor-icon">
                    <i class="fas fa-tint"></i>
                </div>
                <div class="sensor-content">
                    <div class="sensor-value" id="realTimeMoisture">--%</div>
                    <div class="sensor-label">Kelembaban Tanah</div>
                    <div class="sensor-status">Optimal</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Manual Input Form -->
    <div class="manual-panel" id="manualInputSection" style="display: none;">
        <div class="panel-header">
            <h2 class="panel-title">
                <i class="fas fa-edit"></i>
                Input Data Manual
            </h2>
            <p class="panel-description">Masukkan data sensor secara manual</p>
        </div>
        
        <div class="input-grid">
            <div class="input-card">
                <div class="input-icon">
                    <i class="fas fa-thermometer-half"></i>
                </div>
                <div class="input-content">
                    <label>Suhu Tanah (°C)</label>
                    <input type="number" id="manualTemp" step="0.1" placeholder="Masukkan suhu">
                    <small>Rentang: 15-35°C</small>
                </div>
            </div>
            
            <div class="input-card">
                <div class="input-icon">
                    <i class="fas fa-seedling"></i>
                </div>
                <div class="input-content">
                    <label>Kadar Humus (%)</label>
                    <input type="number" id="manualHumus" step="0.1" placeholder="Masukkan kadar humus">
                    <small>Rentang: 3-8%</small>
                </div>
            </div>
            
            <div class="input-card">
                <div class="input-icon">
                    <i class="fas fa-tint"></i>
                </div>
                <div class="input-content">
                    <label>Kelembaban Tanah (%)</label>
                    <input type="number" id="manualMoisture" step="0.1" placeholder="Masukkan kelembaban">
                    <small>Rentang: 30-70%</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table Section -->
    <div class="data-panel">
        <div class="panel-header">
            <h2 class="panel-title">
                <i class="fas fa-database"></i>
                Data Saya
            </h2>
            <div class="data-actions">
                <button class="btn-secondary" onclick="refreshData()">
                    <i class="fas fa-sync-alt"></i>
                    <span>Refresh</span>
                </button>
                <button class="btn-success" onclick="exportMyData()">
                    <i class="fas fa-download"></i>
                    <span>Export CSV</span>
                </button>
            </div>
        </div>
        
        <div class="data-content">
            @if($myReadings->count() > 0)
                <!-- Desktop Table -->
                <div class="data-table desktop-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Kelas</th>
                                <th>Suhu</th>
                                <th>Humus</th>
                                <th>Kelembaban</th>
                                <th>Nitrogen</th>
                                <th>Fosfor</th>
                                <th>Kalium</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($myReadings as $reading)
                                <tr>
                                    <td>
                                        <div class="time-info">
                                            <div class="date">{{ $reading->timestamp ? (is_string($reading->timestamp) ? \Carbon\Carbon::parse($reading->timestamp)->format('d M Y') : $reading->timestamp->format('d M Y')) : '-' }}</div>
                                            <div class="time">{{ $reading->timestamp ? (is_string($reading->timestamp) ? \Carbon\Carbon::parse($reading->timestamp)->format('H:i') : $reading->timestamp->format('H:i')) : '-' }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="class-info">
                                            <i class="fas fa-graduation-cap"></i>
                                            <span>{{ $reading->kelas->name ?? $reading->class_id }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="sensor-value">
                                            <i class="fas fa-thermometer-half"></i>
                                            <span>{{ $reading->formatted_soil_temperature ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="sensor-value">
                                            <i class="fas fa-seedling"></i>
                                            <span>{{ $reading->formatted_soil_humus ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="sensor-value">
                                            <i class="fas fa-tint"></i>
                                            <span>{{ $reading->formatted_soil_moisture ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="sensor-value">
                                            <i class="fas fa-atom"></i>
                                            <span>{{ $reading->nitrogen ? $reading->nitrogen . ' ppm' : '-' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="sensor-value">
                                            <i class="fas fa-flask"></i>
                                            <span>{{ $reading->phosphorus ? $reading->phosphorus . ' ppm' : '-' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="sensor-value">
                                            <i class="fas fa-seedling"></i>
                                            <span>{{ $reading->potassium ? $reading->potassium . ' ppm' : '-' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="location-info">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>{{ $reading->location ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $reading->soil_quality_color ?? 'unknown' }}">
                                            {{ $reading->soil_quality_status ?? 'Unknown' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Mobile Cards -->
                <div class="mobile-cards">
                    @foreach($myReadings as $reading)
                        <div class="data-card">
                            <div class="card-header">
                                <div class="card-time">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $reading->timestamp ? (is_string($reading->timestamp) ? \Carbon\Carbon::parse($reading->timestamp)->format('d M Y H:i') : $reading->timestamp->format('d M Y H:i')) : '-' }}</span>
                                </div>
                                <span class="status-badge status-{{ $reading->soil_quality_color ?? 'unknown' }}">
                                    {{ $reading->soil_quality_status ?? 'Unknown' }}
                                </span>
                            </div>
                            
                            <div class="card-content">
                                <div class="sensor-readings">
                                    <div class="reading-item">
                                        <i class="fas fa-thermometer-half"></i>
                                        <div class="reading-info">
                                            <span class="reading-label">Suhu</span>
                                            <span class="reading-value">{{ $reading->formatted_soil_temperature ?? '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="reading-item">
                                        <i class="fas fa-seedling"></i>
                                        <div class="reading-info">
                                            <span class="reading-label">Humus</span>
                                            <span class="reading-value">{{ $reading->formatted_soil_humus ?? '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="reading-item">
                                        <i class="fas fa-tint"></i>
                                        <div class="reading-info">
                                            <span class="reading-label">Kelembaban</span>
                                            <span class="reading-value">{{ $reading->formatted_soil_moisture ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-graduation-cap"></i>
                                        <span>{{ $reading->kelas->name ?? $reading->class_id }}</span>
                                    </div>
                                    @if($reading->location)
                                    <div class="meta-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ $reading->location }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="pagination">
                    {{ $myReadings->links() }}
                </div>
            @else
                <div class="no-data">
                    <div class="no-data-icon">
                        <i class="fas fa-database"></i>
                    </div>
                    <h3>Belum Ada Data</h3>
                    <p>Mulai kumpulkan data IoT dengan mengklik tombol "Scan & Ambil Data" di atas.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Class Data (if available) -->
    @if($classReadings->count() > 0)
    <div class="data-panel">
        <div class="panel-header">
            <h2 class="panel-title">
                <i class="fas fa-users"></i>
                Data Kelas
            </h2>
            <div class="class-stats">
                <span class="stat-item">
                    <i class="fas fa-user"></i>
                    {{ $classReadings->count() }} Siswa
                </span>
            </div>
        </div>
        
        <div class="data-content">
            <div class="data-table desktop-table">
                <table>
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Siswa</th>
                            <th>Suhu</th>
                            <th>Humus</th>
                            <th>Kelembaban</th>
                            <th>Nitrogen</th>
                            <th>Fosfor</th>
                            <th>Kalium</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($classReadings as $reading)
                            <tr>
                                <td>
                                    <div class="time-info">
                                        <div class="date">{{ $reading->timestamp ? (is_string($reading->timestamp) ? \Carbon\Carbon::parse($reading->timestamp)->format('d M Y') : $reading->timestamp->format('d M Y')) : '-' }}</div>
                                        <div class="time">{{ $reading->timestamp ? (is_string($reading->timestamp) ? \Carbon\Carbon::parse($reading->timestamp)->format('H:i') : $reading->timestamp->format('H:i')) : '-' }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="student-info">
                                        <i class="fas fa-user"></i>
                                        <span>{{ $reading->student->name ?? $reading->student_id }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="sensor-value">
                                        <i class="fas fa-thermometer-half"></i>
                                        <span>{{ $reading->formatted_soil_temperature ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="sensor-value">
                                        <i class="fas fa-seedling"></i>
                                        <span>{{ $reading->formatted_soil_humus ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="sensor-value">
                                        <i class="fas fa-tint"></i>
                                        <span>{{ $reading->formatted_soil_moisture ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="sensor-value">
                                        <i class="fas fa-atom"></i>
                                        <span>{{ $reading->nitrogen ? $reading->nitrogen . ' ppm' : '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="sensor-value">
                                        <i class="fas fa-flask"></i>
                                        <span>{{ $reading->phosphorus ? $reading->phosphorus . ' ppm' : '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="sensor-value">
                                        <i class="fas fa-seedling"></i>
                                        <span>{{ $reading->potassium ? $reading->potassium . ' ppm' : '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="location-info">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ $reading->location ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $reading->soil_quality_color ?? 'unknown' }}">
                                        {{ $reading->soil_quality_status ?? 'Unknown' }}
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
@endsection

@push('scripts')
<script src="{{ asset('asset/js/usb-iot.js') }}"></script>
<script src="{{ asset('asset/js/iot-bluetooth.js') }}"></script>
@endpush

@section('styles')
<style>
/* Dark Theme Base */
body {
    background: #0f172a !important;
    color: #ffffff !important;
}

/* Override any white backgrounds */
* {
    box-sizing: border-box;
}

/* Ensure all text is visible on dark background */
h1, h2, h3, h4, h5, h6, p, span, div, a, label, select, option {
    color: inherit;
}

/* Dark theme for form elements */
select, input, textarea {
    background: #1e293b !important;
    color: #ffffff !important;
    border-color: #475569 !important;
}

select option {
    background: #1e293b !important;
    color: #ffffff !important;
}

/* Override any white backgrounds that might appear */
.card, .panel, .box, .container-fluid, .row, .col, .col-md, .col-lg, .col-xl {
    background: #0f172a !important;
    color: #ffffff !important;
}

/* Ensure navigation and header are dark */
.navbar, .header, .sidebar, .menu {
    background: #1e293b !important;
    color: #ffffff !important;
}

/* Override any framework defaults */
.bg-white, .bg-light, .text-dark {
    background: #1e293b !important;
    color: #ffffff !important;
}

    .iot-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
        background: #0f172a;
        min-height: 100vh;
        width: 100%;
        position: relative;
        z-index: 1;
    }

    /* Full width dark background */
    .iot-container::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: #0f172a;
        z-index: -1;
    }

    /* Ensure full width dark background */
    html, body {
        background: #0f172a !important;
        margin: 0;
        padding: 0;
        width: 100%;
        min-height: 100vh;
    }

    /* Override any parent container backgrounds */
    .container, .main-content, .content-wrapper {
        background: #0f172a !important;
    }

    /* Force dark theme on all elements */
    *, *::before, *::after {
        background-color: transparent;
    }

    .page-header {
        text-align: center;
        margin-bottom: 3rem;
        padding: 3rem 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="25" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="25" cy="75" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        position: relative;
        z-index: 2;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .page-description {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 0;
        position: relative;
        z-index: 2;
    }

    .glass-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 1.5rem;
        border: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
        position: relative;
        overflow: hidden;
        margin: 2rem 0;
    }

    .glass-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        z-index: 1;
    }

    .glass-card > * {
        position: relative;
        z-index: 2;
    }

    .glass-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }

    .iot-header {
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .iot-title {
        color: #ffffff;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .iot-meta {
        display: flex;
        justify-content: center;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #cbd5e1;
        font-size: 0.9rem;
    }

    .meta-item i {
        color: #3b82f6;
        font-size: 1rem;
    }

    .iot-content {
        color: #ffffff;
    }

    .iot-description {
        margin-bottom: 2rem;
    }

    .iot-description h3 {
        color: #ffffff;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .iot-description h3::before {
        content: "🔬";
        font-size: 1.5rem;
    }

    .iot-description p {
        color: #cbd5e1;
        line-height: 1.6;
        font-size: 1rem;
        background: rgba(255, 255, 255, 0.05);
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid #3b82f6;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stats-card {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 2rem;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        border-radius: 16px 16px 0 0;
    }

    .stats-card:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(59, 130, 246, 0.5);
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
    }

    .stats-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }

    .stats-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .stats-trend {
        color: #10b981;
        font-size: 1.2rem;
        opacity: 0.8;
    }

    .stats-content {
        text-align: left;
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #ffffff;
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stats-label {
        font-size: 1rem;
        color: #ffffff;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .stats-description {
        font-size: 0.875rem;
        color: #94a3b8;
    }

    /* Modern Control Panel */
    .control-panel {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        backdrop-filter: blur(10px);
        position: relative;
        overflow: hidden;
    }

    .control-panel::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        border-radius: 16px 16px 0 0;
    }

    .control-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .control-title {
        color: #ffffff;
        font-size: 1.5rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
    }

    .control-title i {
        color: #667eea;
        font-size: 1.25rem;
    }

    .connection-status {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #ef4444;
        font-weight: 500;
    }

    .status-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #ef4444;
        animation: pulse 2s infinite;
    }

    .status-indicator.connected {
        background: #10b981;
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    .control-content {
        color: #ffffff;
    }

    .control-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .control-section {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        padding: 1.5rem;
    }

    .section-title {
        color: #ffffff;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        color: #667eea;
        font-size: 1rem;
    }

    .mode-selector {
        display: flex;
        gap: 1rem;
    }

    .mode-option {
        flex: 1;
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .mode-option:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(59, 130, 246, 0.3);
    }

    .mode-option.selected {
        background: rgba(59, 130, 246, 0.1);
        border-color: #3b82f6;
    }

    .mode-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        margin: 0;
        cursor: pointer;
    }

    .mode-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        margin-bottom: 1rem;
    }

    .mode-content h4 {
        color: #ffffff;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .mode-content p {
        color: #94a3b8;
        font-size: 0.875rem;
        margin: 0;
    }

    .connection-methods {
        margin-bottom: 2rem;
    }

    .method-buttons {
        display: flex;
        gap: 1rem;
    }

    .method-btn {
        flex: 1;
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 1rem 1.5rem;
        color: #ffffff;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .method-btn:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(59, 130, 246, 0.3);
    }

    .method-btn.active {
        background: rgba(59, 130, 246, 0.1);
        border-color: #3b82f6;
        color: #3b82f6;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .control-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        color: #ffffff;
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 0.75rem 1rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        color: #ffffff;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        background: rgba(255, 255, 255, 0.15);
    }

    .form-group input::placeholder,
    .form-group textarea::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        min-width: 150px;
        justify-content: center;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        transform: translateY(-2px);
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #059669, #047857);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
        transform: translateY(-2px);
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    /* Real-time Display Panel */
    .realtime-panel {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        backdrop-filter: blur(10px);
        position: relative;
        overflow: hidden;
    }

    .realtime-panel::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #10b981, #059669);
        border-radius: 16px 16px 0 0;
    }

    .panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .panel-title {
        color: #ffffff;
        font-size: 1.5rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
    }

    .panel-title i {
        color: #10b981;
        font-size: 1.25rem;
    }

    .live-indicator {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #10b981;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #10b981;
        animation: pulse 1.5s infinite;
    }

    .sensor-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .sensor-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .sensor-card:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(59, 130, 246, 0.3);
        transform: translateY(-2px);
    }

    .sensor-card.temperature {
        border-left: 4px solid #ef4444;
    }

    .sensor-card.humus {
        border-left: 4px solid #10b981;
    }

    .sensor-card.moisture {
        border-left: 4px solid #3b82f6;
    }

    .sensor-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .sensor-card.temperature .sensor-icon {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .sensor-card.humus .sensor-icon {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .sensor-card.moisture .sensor-icon {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }

    .sensor-content {
        text-align: left;
    }

    .sensor-value {
        font-size: 2rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 0.5rem;
        line-height: 1;
    }

    .sensor-label {
        color: #ffffff;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .sensor-status {
        color: #94a3b8;
        font-size: 0.875rem;
    }

    /* Manual Input Panel */
    .manual-panel {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        backdrop-filter: blur(10px);
        position: relative;
        overflow: hidden;
    }

    .manual-panel::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #f59e0b, #d97706);
        border-radius: 16px 16px 0 0;
    }

    .panel-description {
        color: #94a3b8;
        font-size: 0.875rem;
        margin: 0.5rem 0 0 0;
    }

    .input-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .input-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    .input-card:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(59, 130, 246, 0.3);
    }

    .input-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        margin-bottom: 1rem;
    }

    .input-content label {
        color: #ffffff;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
    }

    .input-content input {
        width: 100%;
        padding: 0.75rem 1rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        color: #ffffff;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .input-content input:focus {
        outline: none;
        border-color: #3b82f6;
        background: rgba(255, 255, 255, 0.15);
    }

    .input-content small {
        color: #94a3b8;
        font-size: 0.75rem;
        margin-top: 0.25rem;
        display: block;
    }

    .manual-input-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
        position: relative;
        overflow: hidden;
    }

    .manual-input-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        z-index: 1;
    }

    .manual-input-section > * {
        position: relative;
        z-index: 2;
    }

    .manual-input-section h3 {
        color: #ffffff;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .manual-input-section h3::before {
        content: "✏️";
        font-size: 1.5rem;
    }

    .input-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    /* Data Panel */
    .data-panel {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        backdrop-filter: blur(10px);
        position: relative;
        overflow: hidden;
    }

    .data-panel::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6, #1d4ed8);
        border-radius: 16px 16px 0 0;
    }

    .data-actions {
        display: flex;
        gap: 0.75rem;
    }

    .class-stats {
        display: flex;
        gap: 1rem;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #94a3b8;
        font-size: 0.875rem;
    }

    .stat-item i {
        color: #3b82f6;
    }

    .data-table {
        overflow-x: auto;
        margin-bottom: 1.5rem;
    }

    .data-table table {
        width: 100%;
        border-collapse: collapse;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .data-table th {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        font-weight: 600;
        padding: 1.25rem 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .data-table td {
        padding: 1.25rem 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        color: #ffffff;
        vertical-align: middle;
    }

    .data-table tbody tr:hover {
        background: rgba(255, 255, 255, 0.03);
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .time-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .time-info .date {
        font-weight: 600;
        color: #ffffff;
        font-size: 0.875rem;
    }

    .time-info .time {
        color: #94a3b8;
        font-size: 0.75rem;
    }

    .class-info, .student-info, .location-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #cbd5e1;
    }

    .class-info i, .student-info i, .location-info i {
        color: #3b82f6;
        font-size: 0.875rem;
    }

    .sensor-value {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #ffffff;
        font-weight: 600;
    }

    .sensor-value i {
        color: #3b82f6;
        font-size: 0.875rem;
    }

    /* Mobile Cards */
    .mobile-cards {
        display: none;
    }

    .data-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .data-card:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(59, 130, 246, 0.3);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .card-time {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #94a3b8;
        font-size: 0.875rem;
    }

    .card-time i {
        color: #3b82f6;
    }

    .sensor-readings {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .reading-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 8px;
    }

    .reading-item i {
        color: #3b82f6;
        font-size: 1rem;
    }

    .reading-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .reading-label {
        color: #94a3b8;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .reading-value {
        color: #ffffff;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .card-meta {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .card-meta .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #94a3b8;
        font-size: 0.875rem;
    }

    .card-meta .meta-item i {
        color: #3b82f6;
        font-size: 0.75rem;
    }

    .no-data {
        text-align: center;
        padding: 3rem 2rem;
        color: #94a3b8;
    }

    .no-data-icon {
        font-size: 4rem;
        color: #3b82f6;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .no-data h3 {
        color: #ffffff;
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
    }

    .no-data p {
        color: #94a3b8;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
    }

    .status-excellent { 
        background: rgba(16, 185, 129, 0.2); 
        color: #10b981; 
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .status-good { 
        background: rgba(59, 130, 246, 0.2); 
        color: #3b82f6; 
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .status-fair { 
        background: rgba(245, 158, 11, 0.2); 
        color: #f59e0b; 
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .status-poor { 
        background: rgba(239, 68, 68, 0.2); 
        color: #ef4444; 
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 1.5rem;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .control-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .iot-container {
            padding: 1rem;
        }

        .page-header {
            padding: 2rem 1rem;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            flex-direction: column;
            gap: 0.5rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .stats-card {
            padding: 1.5rem;
        }

        .control-panel, .realtime-panel, .manual-panel, .data-panel {
            padding: 1.5rem;
        }

        .mode-selector {
            flex-direction: column;
        }

        .method-buttons {
            flex-direction: column;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }

        .sensor-grid {
            grid-template-columns: 1fr;
        }

        .input-grid {
            grid-template-columns: 1fr;
        }

        .sensor-readings {
            grid-template-columns: 1fr;
        }

        /* Show mobile cards, hide desktop table */
        .desktop-table {
            display: none;
        }
        
        .mobile-cards {
            display: block;
        }

        .panel-header {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .data-actions {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .iot-container {
            padding: 0.5rem;
        }

        .page-header {
            padding: 1.5rem 1rem;
        }

        .page-title {
            font-size: 1.75rem;
        }

        .glass-card {
            padding: 1rem;
            margin: 0.5rem 0;
        }

        .iot-title {
            font-size: 1.25rem;
        }

        .stats-card,
        .sensor-card {
            padding: 1rem;
        }

        .data-table {
            font-size: 0.9rem;
        }

        .data-table th,
        .data-table td {
            padding: 0.75rem 0.5rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
    }
</style>
@endsection

@section('scripts')
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
    document.addEventListener('DOMContentLoaded', function() {
        // Mode selector functionality
        const modeOptions = document.querySelectorAll('.mode-option');
        modeOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Remove selected class from all options
                modeOptions.forEach(opt => opt.classList.remove('selected'));
                // Add selected class to clicked option
                this.classList.add('selected');
                // Update radio button
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                
                // Update UI based on mode
                const mode = this.dataset.mode;
                const scanBtn = document.getElementById('scanDeviceBtn');
                const manualSection = document.getElementById('manualInputSection');
                
                if (mode === 'manual') {
                    scanBtn.innerHTML = '<i class="fas fa-edit"></i><span>Input Data Manual</span>';
                    manualSection.style.display = 'block';
                } else {
                    scanBtn.innerHTML = '<i class="fas fa-bluetooth"></i><span>Scan & Ambil Data</span>';
                    manualSection.style.display = 'none';
                }
            });
        });
        
        // Set initial selected mode
        const autoMode = document.querySelector('.mode-option[data-mode="auto"]');
        if (autoMode) {
            autoMode.classList.add('selected');
        }
    });

    // Connection method variables
    let selectedConnectionMethod = 'bluetooth';
    let usbManager = null;
    let bluetoothManager = null;

    // Select connection method
    function selectConnectionMethod(method) {
        selectedConnectionMethod = method;
        
        // Update button states
        document.getElementById('usb-method-btn').classList.toggle('active', method === 'usb');
        document.getElementById('bluetooth-method-btn').classList.toggle('active', method === 'bluetooth');
        
        // Update scan button icon
        const scanBtn = document.getElementById('scanDeviceBtn');
        if (method === 'usb') {
            scanBtn.innerHTML = '<i class="fas fa-usb me-1"></i>Hubungkan USB';
        } else {
            scanBtn.innerHTML = '<i class="fas fa-bluetooth me-1"></i>Scan & Ambil Data';
        }
    }

    // Initialize USB Manager if available
    function initUSBManager() {
        if (typeof USBIoTManager !== 'undefined') {
            usbManager = new USBIoTManager();
            
            usbManager.onDataReceived = function(data) {
                console.log('USB data received:', data);
                currentData = {
                    soil_temperature: data.temperature || 0,
                    soil_humus: data.soil_moisture || 0,
                    soil_moisture: data.humidity || 0,
                    timestamp: new Date().toISOString()
                };
                
                // Update UI
                updateRealTimeDisplay();
            };
            
            usbManager.onConnectionChange = function(connected, device) {
                isConnected = connected;
                const statusElement = document.getElementById('connectionStatus');
                const indicatorElement = document.getElementById('connectionIndicator');
                
                if (connected) {
                    statusElement.textContent = 'Terhubung via USB';
                    statusElement.style.color = '#10b981';
                    indicatorElement.classList.add('connected');
                    document.getElementById('realTimeDataSection').style.display = 'block';
                } else {
                    statusElement.textContent = 'Terputus';
                    statusElement.style.color = '#ef4444';
                    indicatorElement.classList.remove('connected');
                }
            };
            
            usbManager.onError = function(message, error) {
                console.error('USB Error:', message, error);
                alert('USB Error: ' + message);
            };
        }
    }

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
        scanBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>Menghubungkan...';
        
        try {
            if (selectedConnectionMethod === 'usb') {
                // USB Connection
                if (!usbManager) {
                    initUSBManager();
                }
                
                if (!usbManager) {
                    throw new Error('USB Manager tidak tersedia. Gunakan Bluetooth atau mode manual.');
                }
                
                if (!usbManager.isSupported()) {
                    throw new Error('Web Serial API tidak didukung di browser ini. Gunakan Chrome/Edge atau mode manual.');
                }
                
                await usbManager.connect();
                await usbManager.startReading();
                
                scanBtn.innerHTML = '<i class="fas fa-check"></i>Terhubung USB';
                saveBtn.disabled = false;
                
            } else {
                // Bluetooth Connection (existing code)
                if (!navigator.bluetooth) {
                    throw new Error('Web Bluetooth tidak didukung di browser ini. Gunakan mode manual.');
                }
                
                const device = await navigator.bluetooth.requestDevice({
                    acceptAllDevices: true,
                    optionalServices: ['0000180a-0000-1000-8000-00805f9b34fb']
                });
                
                currentDevice = device;
                const server = await device.gatt.connect();
                isConnected = true;
                
                document.getElementById('realTimeDataSection').style.display = 'block';
                startReadingData(server);
                
                scanBtn.innerHTML = '<i class="fas fa-check"></i><span>Terhubung</span>';
                saveBtn.disabled = false;
                
                const statusElement = document.getElementById('connectionStatus');
                const indicatorElement = document.getElementById('connectionIndicator');
                statusElement.textContent = 'Terhubung via Bluetooth';
                statusElement.style.color = '#10b981';
                indicatorElement.classList.add('connected');
            }
            
        } catch (error) {
            console.error('Error connecting to device:', error);
            alert('Gagal terhubung ke perangkat: ' + error.message + '\n\nGunakan mode manual untuk input data.');
            
            scanBtn.disabled = false;
            if (selectedConnectionMethod === 'usb') {
                scanBtn.innerHTML = '<i class="fas fa-usb me-1"></i>Hubungkan USB';
            } else {
                scanBtn.innerHTML = '<i class="fas fa-bluetooth me-1"></i>Scan & Ambil Data';
            }
        }
    }

    // Show manual input form
    function showManualInput() {
        document.getElementById('manualInputSection').style.display = 'block';
        document.getElementById('saveDataBtn').disabled = false;
    }

    // Update real-time display
    function updateRealTimeDisplay() {
        if (currentData) {
            document.getElementById('realTimeTemp').textContent = currentData.soil_temperature + '°C';
            document.getElementById('realTimeHumus').textContent = currentData.soil_humus + '%';
            document.getElementById('realTimeMoisture').textContent = currentData.soil_moisture + '%';
        }
    }

    // Start reading data from device
    async function startReadingData(server) {
        try {
            // Get device information service
            const service = await server.getPrimaryService('0000180a-0000-1000-8000-00805f9b34fb');
            
            // Initialize Bluetooth IoT Manager for real data reading
            if (typeof IoTBluetoothManager !== 'undefined') {
                const bluetoothManager = new IoTBluetoothManager();
                bluetoothManager.server = server;
                
                // Set up data received callback
                bluetoothManager.onDataReceived = function(data) {
                    currentData = {
                        soil_temperature: data.soil_temperature || 0,
                        soil_humus: data.soil_humus || 0,
                        soil_moisture: data.soil_moisture || 0,
                        timestamp: new Date().toISOString()
                    };
                    
                    // Update UI with real data
                    updateRealTimeDisplay();
                };
                
                // Set up error callback
                bluetoothManager.onError = function(error) {
                    console.error('Bluetooth data reading error:', error);
                    // Show error message to user
                    alert('Gagal membaca data dari perangkat: ' + error.message);
                };
                
                // Start reading real sensor data
                await bluetoothManager.readSensorData();
                
                // Set up periodic reading
                setInterval(async () => {
                    if (isConnected && bluetoothManager) {
                        try {
                            await bluetoothManager.readSensorData();
                        } catch (error) {
                            console.error('Error reading sensor data:', error);
                        }
                    }
                }, 2000);
                
            } else {
                // Fallback: Show no data message
                console.warn('IoTBluetoothManager not available, showing no data');
                document.getElementById('realTimeTemp').textContent = '--°C';
                document.getElementById('realTimeHumus').textContent = '--%';
                document.getElementById('realTimeMoisture').textContent = '--%';
            }
            
        } catch (error) {
            console.error('Error reading data:', error);
            // Show error in UI
            document.getElementById('realTimeTemp').textContent = 'Error';
            document.getElementById('realTimeHumus').textContent = 'Error';
            document.getElementById('realTimeMoisture').textContent = 'Error';
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
