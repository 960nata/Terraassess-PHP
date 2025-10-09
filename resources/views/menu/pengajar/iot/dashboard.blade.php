@extends('layouts.unified-layout')

@section('container')
    <h1 class="text-white">🌡️ IoT Monitoring Dashboard</h1>
    <span class="text-white-75">Monitoring Suhu Tanah, Kelembaban, dan Kualitas Tanah Real-time</span>
    <hr class="border-white-25">

    <!-- Connection Status -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-card p-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="text-white mb-2">Status Koneksi IoT</h5>
                        <span id="connection-status" class="badge badge-danger">Terputus</span>
                        <p class="text-white-75 small mt-2 mb-0" id="device-info">Tidak ada perangkat terhubung</p>
                        <div class="mt-2">
                            <small class="text-white-75">
                                <i class="fas fa-info-circle me-1"></i>
                                <span id="connection-method">Pilih metode koneksi</span>
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <!-- Connection Method Selection -->
                        <div class="btn-group mb-2" role="group">
                            <button type="button" class="btn btn-glass btn-sm" id="usb-method-btn" title="USB Connection (1m range)">
                                <i class="fas fa-usb me-1"></i> USB
                            </button>
                            <button type="button" class="btn btn-glass btn-sm" id="bluetooth-method-btn" title="Bluetooth Connection (10m range)">
                                <i class="fas fa-bluetooth me-1"></i> Bluetooth
                            </button>
                        </div>
                        <div>
                            <button id="connect-btn" class="btn btn-glass me-2">
                                <i class="fas fa-plug me-1"></i> Hubungkan
                            </button>
                            <button id="disconnect-btn" class="btn btn-glass" disabled>
                                <i class="fas fa-times me-1"></i> Putuskan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Real-time Sensor Data -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="text-center">
                    <i class="fas fa-thermometer-half fa-3x text-warning mb-3"></i>
                    <h1 class="display-4 text-warning fw-bold" id="current-temperature">--°C</h1>
                    <h5 class="text-white">Suhu Tanah</h5>
                    <p class="text-white-75 small">Suhu saat ini</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="text-center">
                    <i class="fas fa-tint fa-3x text-warning mb-3"></i>
                    <h1 class="display-4 text-warning fw-bold" id="current-humidity">--%</h1>
                    <h5 class="text-white">Kelembaban Udara</h5>
                    <p class="text-white-75 small">Kelembaban saat ini</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="text-center">
                    <i class="fas fa-seedling fa-3x text-warning mb-3"></i>
                    <h1 class="display-4 text-warning fw-bold" id="current-moisture">--%</h1>
                    <h5 class="text-white">Kelembaban Tanah</h5>
                    <p class="text-white-75 small">Kelembaban tanah</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="text-center">
                    <i class="fas fa-chart-line fa-3x text-warning mb-3"></i>
                    <h1 class="display-4 text-warning fw-bold" id="soil-quality">--</h1>
                    <h5 class="text-white">Kualitas Tanah</h5>
                    <p class="text-white-75 small">Status kualitas</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recording Controls -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-card p-4">
                <h5 class="text-white mb-4">🎯 Kontrol Perekaman Data</h5>
                <form id="recording-form">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-white">Pilih Kelas</label>
                            <select class="form-select" id="kelas-select" required>
                                <option value="">Pilih Kelas</option>
                                @foreach($kelas ?? [] as $kelasItem)
                                    <option value="{{ $kelasItem->id }}">{{ $kelasItem->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-white">Lokasi Pengukuran</label>
                            <input type="text" class="form-control" id="location-input" placeholder="Contoh: Kebun Sekolah">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-white">Catatan</label>
                            <input type="text" class="form-control" id="notes-input" placeholder="Catatan tambahan">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="button" id="start-recording-btn" class="btn btn-glass me-2" disabled>
                                <i class="fas fa-play me-1"></i> Mulai Perekaman
                            </button>
                            <button type="button" id="stop-recording-btn" class="btn btn-glass me-2" disabled>
                                <i class="fas fa-stop me-1"></i> Hentikan Perekaman
                            </button>
                            <span class="text-white-75 small" id="last-update">Terakhir update: --</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Recent Data -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-card">
                <div class="card-header">
                    <h5 class="text-white mb-0">📊 Data Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Perangkat</th>
                                    <th>Suhu</th>
                                    <th>Kelembaban</th>
                                    <th>Kelembaban Tanah</th>
                                    <th>Kelas</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="recent-data-table">
                                @forelse($recentData ?? [] as $data)
                                    <tr>
                                        <td>{{ $data->measured_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $data->device->device_name ?? 'Unknown' }}</td>
                                        <td>{{ $data->formatted_temperature }}</td>
                                        <td>{{ $data->formatted_humidity }}</td>
                                        <td>{{ $data->formatted_soil_moisture }}</td>
                                        <td>{{ $data->kelas->nama_kelas ?? 'Unknown' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $data->soil_quality_status === 'excellent' ? 'success' : ($data->soil_quality_status === 'good' ? 'warning' : 'danger') }}">
                                                {{ $data->soil_quality_label }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-white-75">Belum ada data sensor</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="glass-card p-4 text-center">
                <i class="fas fa-database fa-3x text-warning mb-3"></i>
                <h5 class="text-white">Data Sensor</h5>
                <p class="text-white-75 small">Lihat semua data sensor</p>
                <a href="{{ route('iot.sensor-data') }}" class="btn btn-glass">Lihat Data</a>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="glass-card p-4 text-center">
                <i class="fas fa-cogs fa-3x text-warning mb-3"></i>
                <h5 class="text-white">Kelola Perangkat</h5>
                <p class="text-white-75 small">Kelola perangkat IoT</p>
                <a href="{{ route('iot.devices') }}" class="btn btn-glass">Kelola</a>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="glass-card p-4 text-center">
                <i class="fas fa-flask fa-3x text-warning mb-3"></i>
                <h5 class="text-white">Proyek Penelitian</h5>
                <p class="text-white-75 small">Kelola proyek penelitian</p>
                <a href="{{ route('iot.research-projects') }}" class="btn btn-glass">Kelola</a>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="glass-card p-4 text-center">
                <i class="fas fa-chart-bar fa-3x text-warning mb-3"></i>
                <h5 class="text-white">Analisis Data</h5>
                <p class="text-white-75 small">Analisis dan visualisasi</p>
                <a href="#" class="btn btn-glass">Analisis</a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('asset/js/bluetooth-iot.js') }}"></script>
<script src="{{ asset('asset/js/usb-iot.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Combined IoT Manager (USB + Bluetooth)
    window.iotManager = new CombinedIoTManager();
    let selectedConnectionMethod = 'bluetooth'; // Default to Bluetooth
    const connectBtn = document.getElementById('connect-btn');
    const disconnectBtn = document.getElementById('disconnect-btn');
    const startRecordingBtn = document.getElementById('start-recording-btn');
    const stopRecordingBtn = document.getElementById('stop-recording-btn');
    const kelasSelect = document.getElementById('kelas-select');
    const locationInput = document.getElementById('location-input');
    const notesInput = document.getElementById('notes-input');
    
    // Connection method buttons
    const usbMethodBtn = document.getElementById('usb-method-btn');
    const bluetoothMethodBtn = document.getElementById('bluetooth-method-btn');
    const connectionMethodSpan = document.getElementById('connection-method');
    
    // Setup connection method selection
    usbMethodBtn.addEventListener('click', function() {
        selectedConnectionMethod = 'usb';
        usbMethodBtn.classList.add('active');
        bluetoothMethodBtn.classList.remove('active');
        connectionMethodSpan.textContent = 'USB Connection (1m range, stable)';
    });
    
    bluetoothMethodBtn.addEventListener('click', function() {
        selectedConnectionMethod = 'bluetooth';
        bluetoothMethodBtn.classList.add('active');
        usbMethodBtn.classList.remove('active');
        connectionMethodSpan.textContent = 'Bluetooth Connection (10m range, wireless)';
    });
    
    // Set default method
    bluetoothMethodBtn.click();

    // Connect button
    connectBtn.addEventListener('click', async function() {
        try {
            connectBtn.disabled = true;
            connectBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menghubungkan...';
            
            await window.iotManager.connect(selectedConnectionMethod);
            
            connectBtn.disabled = true;
            disconnectBtn.disabled = false;
            startRecordingBtn.disabled = false;
            
        } catch (error) {
            console.error('Connection failed:', error);
            alert('Gagal menghubungkan: ' + error.message);
            
            connectBtn.disabled = false;
            connectBtn.innerHTML = '<i class="fas fa-bluetooth me-1"></i> Hubungkan';
        }
    });

    // Disconnect button
    disconnectBtn.addEventListener('click', async function() {
        try {
            await window.iotManager.disconnect();
            
            connectBtn.disabled = false;
            disconnectBtn.disabled = true;
            startRecordingBtn.disabled = true;
            stopRecordingBtn.disabled = true;
            
            connectBtn.innerHTML = '<i class="fas fa-bluetooth me-1"></i> Hubungkan';
            
        } catch (error) {
            console.error('Disconnection failed:', error);
        }
    });

    // Start recording
    startRecordingBtn.addEventListener('click', function() {
        const kelasId = kelasSelect.value;
        const location = locationInput.value;
        const notes = notesInput.value;
        
        if (!kelasId) {
            alert('Pilih kelas terlebih dahulu');
            return;
        }
        
        window.iotManager.startRecording(kelasId, location, notes);
        
        startRecordingBtn.disabled = true;
        stopRecordingBtn.disabled = false;
        startRecordingBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Merekam...';
    });

    // Stop recording
    stopRecordingBtn.addEventListener('click', function() {
        window.iotManager.stopRecording();
        
        startRecordingBtn.disabled = false;
        stopRecordingBtn.disabled = true;
        startRecordingBtn.innerHTML = '<i class="fas fa-play me-1"></i> Mulai Perekaman';
    });

    // Update soil quality display
    function updateSoilQuality(temperature, humidity, moisture) {
        const qualityElement = document.getElementById('soil-quality');
        
        // Simple quality calculation
        let quality = 'Baik';
        if (temperature >= 20 && temperature <= 30 && 
            humidity >= 40 && humidity <= 70 && 
            moisture >= 30 && moisture <= 60) {
            quality = 'Sangat Baik';
        } else if (temperature < 15 || temperature > 35 || 
                   humidity < 30 || humidity > 80 || 
                   moisture < 20 || moisture > 70) {
            quality = 'Perlu Perhatian';
        }
        
        qualityElement.textContent = quality;
    }

    // Override the updateSensorDisplay function to include soil quality
    const originalUpdateDisplay = window.iotManager.updateSensorDisplay;
    window.iotManager.updateSensorDisplay = function(data) {
        originalUpdateDisplay.call(this, data);
        updateSoilQuality(data.temperature, data.humidity, data.soil_moisture);
    };
});
</script>
@endpush
