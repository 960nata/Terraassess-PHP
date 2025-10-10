@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - IoT Dashboard Super Admin')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-chart-line"></i>
        IoT Dashboard Super Admin
    </h1>
    <p class="page-subtitle">Monitoring dan manajemen sistem IoT untuk super admin</p>
</div>

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

<!-- Additional Sensor Data Row -->
<div class="row mb-4">
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="stat-card">
            <div class="text-center">
                <i class="fas fa-flask fa-3x text-info mb-3"></i>
                <h1 class="display-4 text-info fw-bold" id="current-ph">--</h1>
                <h5 class="text-white">pH Tanah</h5>
                <p class="text-white-75 small">Tingkat keasaman</p>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="stat-card">
            <div class="text-center">
                <i class="fas fa-leaf fa-3x text-success mb-3"></i>
                <h1 class="display-4 text-success fw-bold" id="current-nutrient">--%</h1>
                <h5 class="text-white">Level Nutrisi</h5>
                <p class="text-white-75 small">Kandungan nutrisi</p>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="stat-card">
            <div class="text-center">
                <i class="fas fa-microchip fa-3x text-primary mb-3"></i>
                <h1 class="display-4 text-primary fw-bold" id="connection-method-display">--</h1>
                <h5 class="text-white">Metode Koneksi</h5>
                <p class="text-white-75 small">USB/Bluetooth</p>
            </div>
        </div>
    </div>
</div>

<!-- Super Admin Controls -->
<div class="row mb-4">
    <div class="col-12">
        <div class="glass-card p-4">
            <h5 class="text-white mb-4">🎯 Kontrol Super Admin IoT</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label text-white">Pilih Kelas untuk Monitoring</label>
                    <select class="form-select" id="kelas-select">
                        <option value="">Pilih Kelas</option>
                        @foreach($kelas ?? [] as $kelasItem)
                            <option value="{{ $kelasItem->id }}">{{ $kelasItem->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label text-white">Lokasi Pengukuran</label>
                    <input type="text" class="form-control" id="location-input" placeholder="Contoh: Laboratorium IoT">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label text-white">Mode Monitoring</label>
                    <select class="form-select" id="monitoring-mode">
                        <option value="realtime">Real-time</option>
                        <option value="interval">Interval (5 menit)</option>
                        <option value="manual">Manual</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="button" id="start-monitoring-btn" class="btn btn-glass me-2" disabled>
                        <i class="fas fa-play me-1"></i> Mulai Monitoring
                    </button>
                    <button type="button" id="stop-monitoring-btn" class="btn btn-glass me-2" disabled>
                        <i class="fas fa-stop me-1"></i> Hentikan Monitoring
                    </button>
                    <button type="button" id="export-data-btn" class="btn btn-glass me-2">
                        <i class="fas fa-download me-1"></i> Export Data
                    </button>
                    <span class="text-white-75 small" id="last-update">Terakhir update: --</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Statistics -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="glass-card p-4 text-center">
            <i class="fas fa-database fa-3x text-warning mb-3"></i>
            <h5 class="text-white">Total Data</h5>
            <p class="text-white-75 small">Data sensor tersimpan</p>
            <h3 class="text-warning">{{ $totalData ?? 0 }}</h3>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="glass-card p-4 text-center">
            <i class="fas fa-wifi fa-3x text-warning mb-3"></i>
            <h5 class="text-white">Perangkat Aktif</h5>
            <p class="text-white-75 small">Device terhubung</p>
            <h3 class="text-warning">{{ $activeDevices ?? 0 }}</h3>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="glass-card p-4 text-center">
            <i class="fas fa-users fa-3x text-warning mb-3"></i>
            <h5 class="text-white">Kelas Aktif</h5>
            <p class="text-white-75 small">Kelas menggunakan IoT</p>
            <h3 class="text-warning">{{ $activeClasses ?? 0 }}</h3>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="glass-card p-4 text-center">
            <i class="fas fa-chart-bar fa-3x text-warning mb-3"></i>
            <h5 class="text-white">Proyek Penelitian</h5>
            <p class="text-white-75 small">Proyek aktif</p>
            <h3 class="text-warning">{{ $activeProjects ?? 0 }}</h3>
        </div>
    </div>
</div>

<!-- Advanced Analytics -->
<div class="row mb-4">
    <div class="col-12">
        <div class="glass-card p-4">
            <h5 class="text-white mb-4">📊 Analisis Lanjutan</h5>
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-white">Trend Data Sensor (24 Jam Terakhir)</h6>
                    <canvas id="sensorTrendChart" width="400" height="200"></canvas>
                </div>
                <div class="col-md-6">
                    <h6 class="text-white">Distribusi Kualitas Tanah</h6>
                    <canvas id="soilQualityChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="glass-card p-4 text-center">
            <i class="fas fa-cogs fa-3x text-warning mb-3"></i>
            <h5 class="text-white">Kelola Perangkat</h5>
            <p class="text-white-75 small">Kelola perangkat IoT</p>
            <a href="{{ route('superadmin.iot-management') }}" class="btn btn-glass">Kelola</a>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="glass-card p-4 text-center">
            <i class="fas fa-chart-line fa-3x text-warning mb-3"></i>
            <h5 class="text-white">Analisis Data</h5>
            <p class="text-white-75 small">Analisis dan visualisasi</p>
            <a href="#" class="btn btn-glass">Analisis</a>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="glass-card p-4 text-center">
            <i class="fas fa-file-export fa-3x text-warning mb-3"></i>
            <h5 class="text-white">Export Data</h5>
            <p class="text-white-75 small">Export data sensor</p>
            <a href="#" class="btn btn-glass">Export</a>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="glass-card p-4 text-center">
            <i class="fas fa-cog fa-3x text-warning mb-3"></i>
            <h5 class="text-white">Pengaturan</h5>
            <p class="text-white-75 small">Konfigurasi sistem</p>
            <a href="#" class="btn btn-glass">Pengaturan</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('asset/js/bluetooth-iot.js') }}"></script>
<script src="{{ asset('asset/js/usb-iot.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Combined IoT Manager (USB + Bluetooth)
    window.iotManager = new CombinedIoTManager();
    let selectedConnectionMethod = 'bluetooth'; // Default to Bluetooth
    const connectBtn = document.getElementById('connect-btn');
    const disconnectBtn = document.getElementById('disconnect-btn');
    const startMonitoringBtn = document.getElementById('start-monitoring-btn');
    const stopMonitoringBtn = document.getElementById('stop-monitoring-btn');
    const exportDataBtn = document.getElementById('export-data-btn');
    const kelasSelect = document.getElementById('kelas-select');
    const locationInput = document.getElementById('location-input');
    const monitoringMode = document.getElementById('monitoring-mode');
    
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
            startMonitoringBtn.disabled = false;
            
        } catch (error) {
            console.error('Connection failed:', error);
            alert('Gagal menghubungkan: ' + error.message);
            
            connectBtn.disabled = false;
            connectBtn.innerHTML = '<i class="fas fa-plug me-1"></i> Hubungkan';
        }
    });

    // Disconnect button
    disconnectBtn.addEventListener('click', async function() {
        try {
            await window.iotManager.disconnect();
            
            connectBtn.disabled = false;
            disconnectBtn.disabled = true;
            startMonitoringBtn.disabled = true;
            stopMonitoringBtn.disabled = true;
            
            connectBtn.innerHTML = '<i class="fas fa-plug me-1"></i> Hubungkan';
            
        } catch (error) {
            console.error('Disconnection failed:', error);
        }
    });

    // Start monitoring
    startMonitoringBtn.addEventListener('click', function() {
        const kelasId = kelasSelect.value;
        const location = locationInput.value;
        const mode = monitoringMode.value;
        
        if (!kelasId) {
            alert('Pilih kelas terlebih dahulu');
            return;
        }
        
        // Start monitoring
        window.iotManager.isMonitoring = true;
        window.iotManager.monitoringData = {
            kelasId: kelasId,
            location: location,
            mode: mode,
            startTime: new Date()
        };
        
        startMonitoringBtn.disabled = true;
        stopMonitoringBtn.disabled = false;
        startMonitoringBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Monitoring...';
        
        console.log('Monitoring started:', window.iotManager.monitoringData);
    });

    // Stop monitoring
    stopMonitoringBtn.addEventListener('click', function() {
        window.iotManager.isMonitoring = false;
        window.iotManager.monitoringData = null;
        
        startMonitoringBtn.disabled = false;
        stopMonitoringBtn.disabled = true;
        startMonitoringBtn.innerHTML = '<i class="fas fa-play me-1"></i> Mulai Monitoring';
        
        console.log('Monitoring stopped');
    });

    // Export data
    exportDataBtn.addEventListener('click', function() {
        // Implement export functionality
        alert('Export data functionality akan diimplementasikan');
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

    // Setup IoT Manager event handlers
    window.iotManager.onDataReceived = function(data) {
        console.log('Sensor data received:', data);
        
        // Update sensor display
        updateSensorDisplay(data);
        
        // Update soil quality
        updateSoilQuality(data.temperature, data.humidity, data.soil_moisture);
        
        // Update last update time
        document.getElementById('last-update').textContent = 
            'Terakhir update: ' + new Date().toLocaleTimeString();
        
        // Save data to server if monitoring is active
        if (window.iotManager.isMonitoring) {
            saveSensorDataToServer(data);
        }
        
        // Update charts
        updateCharts(data);
    };
    
    window.iotManager.onConnectionChange = function(connected, device) {
        const statusElement = document.getElementById('connection-status');
        const deviceInfoElement = document.getElementById('device-info');
        const connectionMethodDisplay = document.getElementById('connection-method-display');
        
        if (connected) {
            statusElement.textContent = 'Terhubung';
            statusElement.className = 'badge badge-success';
            deviceInfoElement.textContent = 'Perangkat IoT terhubung via ' + 
                (window.iotManager.currentConnection || 'USB/Bluetooth');
            
            // Update connection method display
            if (connectionMethodDisplay) {
                const method = window.iotManager.currentConnection || 'USB';
                connectionMethodDisplay.textContent = method.toUpperCase();
            }
        } else {
            statusElement.textContent = 'Terputus';
            statusElement.className = 'badge badge-danger';
            deviceInfoElement.textContent = 'Tidak ada perangkat terhubung';
            
            // Reset connection method display
            if (connectionMethodDisplay) {
                connectionMethodDisplay.textContent = '--';
            }
        }
    };
    
    window.iotManager.onError = function(message, error) {
        console.error('IoT Error:', message, error);
        alert('Error IoT: ' + message);
    };
    
    window.iotManager.onStatusUpdate = function(status) {
        console.log('IoT Status:', status);
    };
    
    // Function to update sensor display
    function updateSensorDisplay(data) {
        // Update temperature
        const tempElement = document.getElementById('current-temperature');
        if (tempElement && data.temperature !== undefined) {
            tempElement.textContent = data.temperature.toFixed(1) + '°C';
        }
        
        // Update humidity
        const humidityElement = document.getElementById('current-humidity');
        if (humidityElement && data.humidity !== undefined) {
            humidityElement.textContent = data.humidity.toFixed(1) + '%';
        }
        
        // Update soil moisture (humus)
        const moistureElement = document.getElementById('current-moisture');
        if (moistureElement && data.soil_moisture !== undefined) {
            moistureElement.textContent = data.soil_moisture.toFixed(1) + '%';
        }
        
        // Update pH if available
        const phElement = document.getElementById('current-ph');
        if (phElement && data.ph_level !== undefined) {
            phElement.textContent = data.ph_level.toFixed(1);
        }
        
        // Update nutrient level if available
        const nutrientElement = document.getElementById('current-nutrient');
        if (nutrientElement && data.nutrient_level !== undefined) {
            nutrientElement.textContent = data.nutrient_level.toFixed(1) + '%';
        }
    }
    
    // Function to save sensor data to server
    async function saveSensorDataToServer(data) {
        if (!window.iotManager.monitoringData) {
            return;
        }
        
        try {
            const payload = {
                device_id: 'superadmin_usb_' + Date.now(),
                temperature: data.temperature,
                humidity: data.humidity,
                soil_moisture: data.soil_moisture,
                ph_level: data.ph_level || null,
                nutrient_level: data.nutrient_level || null,
                kelas_id: window.iotManager.monitoringData.kelasId,
                location: window.iotManager.monitoringData.location,
                notes: 'Super Admin monitoring - ' + window.iotManager.monitoringData.mode,
                raw_data: data
            };
            
            const response = await fetch('/api/iot/sensor-data', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(payload)
            });
            
            const result = await response.json();
            
            if (result.success) {
                console.log('Sensor data saved successfully:', result.data);
            } else {
                console.error('Failed to save sensor data:', result.message);
            }
            
        } catch (error) {
            console.error('Error saving sensor data:', error);
        }
    }
    
    // Chart data storage
    let sensorDataHistory = [];
    let soilQualityData = { 'Sangat Baik': 0, 'Baik': 0, 'Perlu Perhatian': 0 };
    
    // Function to update charts
    function updateCharts(data) {
        // Add to history
        sensorDataHistory.push({
            timestamp: new Date(),
            temperature: data.temperature,
            humidity: data.humidity,
            soil_moisture: data.soil_moisture
        });
        
        // Keep only last 24 hours
        const oneDayAgo = new Date(Date.now() - 24 * 60 * 60 * 1000);
        sensorDataHistory = sensorDataHistory.filter(item => item.timestamp > oneDayAgo);
        
        // Update soil quality distribution
        const quality = getSoilQuality(data.temperature, data.humidity, data.soil_moisture);
        soilQualityData[quality]++;
        
        // Update charts
        updateSensorTrendChart();
        updateSoilQualityChart();
    }
    
    // Get soil quality
    function getSoilQuality(temperature, humidity, moisture) {
        if (temperature >= 20 && temperature <= 30 && 
            humidity >= 40 && humidity <= 70 && 
            moisture >= 30 && moisture <= 60) {
            return 'Sangat Baik';
        } else if (temperature < 15 || temperature > 35 || 
                   humidity < 30 || humidity > 80 || 
                   moisture < 20 || moisture > 70) {
            return 'Perlu Perhatian';
        }
        return 'Baik';
    }
    
    // Initialize charts
    let sensorTrendChart = null;
    let soilQualityChart = null;
    
    function updateSensorTrendChart() {
        const ctx = document.getElementById('sensorTrendChart').getContext('2d');
        
        if (sensorTrendChart) {
            sensorTrendChart.destroy();
        }
        
        sensorTrendChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: sensorDataHistory.map(item => item.timestamp.toLocaleTimeString()),
                datasets: [
                    {
                        label: 'Suhu (°C)',
                        data: sensorDataHistory.map(item => item.temperature),
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        tension: 0.1
                    },
                    {
                        label: 'Kelembaban (%)',
                        data: sensorDataHistory.map(item => item.humidity),
                        borderColor: 'rgb(54, 162, 235)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        tension: 0.1
                    },
                    {
                        label: 'Kelembaban Tanah (%)',
                        data: sensorDataHistory.map(item => item.soil_moisture),
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Trend Data Sensor 24 Jam Terakhir',
                        color: '#ffffff'
                    },
                    legend: {
                        labels: {
                            color: '#ffffff'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#ffffff'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    },
                    y: {
                        ticks: {
                            color: '#ffffff'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                }
            }
        });
    }
    
    function updateSoilQualityChart() {
        const ctx = document.getElementById('soilQualityChart').getContext('2d');
        
        if (soilQualityChart) {
            soilQualityChart.destroy();
        }
        
        soilQualityChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(soilQualityData),
                datasets: [{
                    data: Object.values(soilQualityData),
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(255, 99, 132, 0.8)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Distribusi Kualitas Tanah',
                        color: '#ffffff'
                    },
                    legend: {
                        labels: {
                            color: '#ffffff'
                        }
                    }
                }
            }
        });
    }
    
    // Initialize charts on page load
    updateSensorTrendChart();
    updateSoilQualityChart();
});
</script>
@endpush
