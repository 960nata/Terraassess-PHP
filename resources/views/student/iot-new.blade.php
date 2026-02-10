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
    .iot-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 2rem;
        margin: 2rem 0;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
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
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stats-card {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stats-card:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
    }

    .stats-icon {
        font-size: 2.5rem;
        color: #3b82f6;
        margin-bottom: 1rem;
    }

    .stats-number {
        font-size: 2rem;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 0.5rem;
    }

    .stats-label {
        font-size: 1rem;
        color: #cbd5e1;
    }

    .iot-control-panel {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .iot-control-panel h3 {
        color: #ffffff;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .iot-control-panel h3::before {
        content: "🎛️";
        font-size: 1.5rem;
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

    .real-time-display {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .real-time-display h3 {
        color: #ffffff;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .real-time-display h3::before {
        content: "📊";
        font-size: 1.5rem;
    }

    .sensor-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .sensor-card {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .sensor-card:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
    }

    .sensor-card i {
        font-size: 2rem;
        color: #3b82f6;
        margin-bottom: 1rem;
    }

    .sensor-card h4 {
        color: #ffffff;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .sensor-card small {
        color: #cbd5e1;
        font-size: 0.9rem;
    }

    .manual-input-section {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
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

    .data-section {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .data-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .data-header h3 {
        color: #ffffff;
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .data-header h3::before {
        content: "📋";
        font-size: 1.5rem;
    }

    .data-actions {
        display: flex;
        gap: 0.75rem;
    }

    .data-table {
        overflow-x: auto;
        margin-bottom: 1.5rem;
    }

    .data-table table {
        width: 100%;
        border-collapse: collapse;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        overflow: hidden;
    }

    .data-table th {
        background: rgba(59, 130, 246, 0.2);
        color: #3b82f6;
        font-weight: 600;
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
    }

    .data-table tbody tr:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
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
    @media (max-width: 768px) {
        .glass-card {
            margin: 1rem 0;
            padding: 1.5rem;
        }

        .iot-title {
            font-size: 1.5rem;
        }

        .iot-meta {
            flex-direction: column;
            gap: 0.75rem;
            align-items: center;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .control-form {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }

        .data-header {
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
        .glass-card {
            padding: 1rem;
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
    }
</style>
@endsection

@section('additional-scripts')
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
            scanBtn.innerHTML = '<i class="fas fa-edit"></i>Input Data Manual';
            manualSection.style.display = 'block';
        } else {
            scanBtn.innerHTML = '<i class="fas fa-bluetooth"></i>Scan & Ambil Data';
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
        scanBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>Scanning...';
        
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
            
            scanBtn.innerHTML = '<i class="fas fa-check"></i>Terhubung';
            saveBtn.disabled = false;
            
            // Update connection status
            document.getElementById('connectionStatus').textContent = 'Terhubung';
            document.getElementById('connectionStatus').style.color = '#10b981';
            
        } catch (error) {
            console.error('Error connecting to device:', error);
            alert('Gagal terhubung ke perangkat: ' + error.message + '\n\nGunakan mode manual untuk input data.');
            
            scanBtn.disabled = false;
            scanBtn.innerHTML = '<i class="fas fa-bluetooth"></i>Scan & Ambil Data';
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
