@extends('layouts.unified-layout')

@section('container')
    <h1 class="text-white">🌡️ IoT Management Dashboard - Admin</h1>
    <span class="text-white-75">Kelola perangkat IoT dan monitoring data sensor untuk sekolah</span>
    <hr class="border-white-25">

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="glass-card p-4 text-center">
                <div class="text-white mb-2">
                    <i class="fas fa-microchip fa-2x"></i>
                </div>
                <h3 class="text-white mb-1" id="total-devices">0</h3>
                <p class="text-white-75 mb-0">Total Perangkat</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="glass-card p-4 text-center">
                <div class="text-white mb-2">
                    <i class="fas fa-plug fa-2x"></i>
                </div>
                <h3 class="text-white mb-1" id="connected-devices">0</h3>
                <p class="text-white-75 mb-0">Terhubung</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="glass-card p-4 text-center">
                <div class="text-white mb-2">
                    <i class="fas fa-chart-line fa-2x"></i>
                </div>
                <h3 class="text-white mb-1" id="total-data-points">0</h3>
                <p class="text-white-75 mb-0">Data Points</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="glass-card p-4 text-center">
                <div class="text-white mb-2">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <h3 class="text-white mb-1" id="active-classes">0</h3>
                <p class="text-white-75 mb-0">Kelas Aktif</p>
            </div>
        </div>
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
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="glass-card p-3 text-center">
                <div class="text-white mb-2">
                    <i class="fas fa-thermometer-half fa-2x"></i>
                </div>
                <h6 class="text-white mb-1">Suhu Tanah</h6>
                <div class="text-white h4 mb-0" id="temperature">--°C</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="glass-card p-3 text-center">
                <div class="text-white mb-2">
                    <i class="fas fa-tint fa-2x"></i>
                </div>
                <h6 class="text-white mb-1">Kelembaban Udara</h6>
                <div class="text-white h4 mb-0" id="humidity">--%</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="glass-card p-3 text-center">
                <div class="text-white mb-2">
                    <i class="fas fa-seedling fa-2x"></i>
                </div>
                <h6 class="text-white mb-1">Kelembaban Tanah</h6>
                <div class="text-white h4 mb-0" id="soil-moisture">--%</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="glass-card p-3 text-center">
                <div class="text-white mb-2">
                    <i class="fas fa-flask fa-2x"></i>
                </div>
                <h6 class="text-white mb-1">pH Tanah</h6>
                <div class="text-white h4 mb-0" id="ph-level">--</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="glass-card p-3 text-center">
                <div class="text-white mb-2">
                    <i class="fas fa-leaf fa-2x"></i>
                </div>
                <h6 class="text-white mb-1">Level Nutrisi</h6>
                <div class="text-white h4 mb-0" id="nutrient-level">--%</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="glass-card p-3 text-center">
                <div class="text-white mb-2">
                    <i class="fas fa-clock fa-2x"></i>
                </div>
                <h6 class="text-white mb-1">Terakhir Update</h6>
                <div class="text-white small" id="last-update">--:--</div>
            </div>
        </div>
    </div>

    <!-- Device Management -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="text-white mb-0">
                        <i class="fas fa-list me-2"></i>
                        Daftar Perangkat IoT
                    </h5>
                    <button class="btn btn-glass" onclick="openAddDeviceModal()">
                        <i class="fas fa-plus me-1"></i> Tambah Perangkat
                    </button>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th>Nama Perangkat</th>
                                <th>Tipe Koneksi</th>
                                <th>Platform</th>
                                <th>Status</th>
                                <th>Lokasi</th>
                                <th>Kelas</th>
                                <th>Data Points</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="devices-table">
                            <tr>
                                <td colspan="8" class="text-center text-white-75">
                                    <i class="fas fa-spinner fa-spin me-2"></i>
                                    Memuat data perangkat...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="glass-card p-4 text-center">
                <div class="text-white mb-3">
                    <i class="fas fa-cogs fa-3x"></i>
                </div>
                <h5 class="text-white mb-2">Manajemen Perangkat</h5>
                <p class="text-white-75 mb-3">Kelola semua perangkat IoT sekolah</p>
                <a href="{{ route('admin.iot-management') }}" class="btn btn-glass">
                    <i class="fas fa-arrow-right me-1"></i> Kelola
                </a>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="glass-card p-4 text-center">
                <div class="text-white mb-3">
                    <i class="fas fa-chart-bar fa-3x"></i>
                </div>
                <h5 class="text-white mb-2">Analisis Data</h5>
                <p class="text-white-75 mb-3">Lihat statistik dan analisis data sensor</p>
                <button class="btn btn-glass" onclick="showAnalytics()">
                    <i class="fas fa-arrow-right me-1"></i> Analisis
                </button>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="glass-card p-4 text-center">
                <div class="text-white mb-3">
                    <i class="fas fa-download fa-3x"></i>
                </div>
                <h5 class="text-white mb-2">Export Data</h5>
                <p class="text-white-75 mb-3">Download data sensor untuk analisis</p>
                <button class="btn btn-glass" onclick="exportData()">
                    <i class="fas fa-arrow-right me-1"></i> Export
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="/asset/js/usb-iot.js"></script>
<script>
    // Global variables
    let currentMethod = 'usb';
    let iotManager = null;
    let isConnected = false;
    let dataInterval = null;

    // Initialize dashboard
    document.addEventListener('DOMContentLoaded', function() {
        initializeDashboard();
        loadDeviceData();
        updateStatistics();
    });

    function initializeDashboard() {
        // Initialize IoT Manager
        if (typeof CombinedIoTManager !== 'undefined') {
            iotManager = new CombinedIoTManager();
            setupEventHandlers();
        }

        // Setup connection method selection
        document.getElementById('usb-method-btn').addEventListener('click', () => selectMethod('usb'));
        document.getElementById('bluetooth-method-btn').addEventListener('click', () => selectMethod('bluetooth'));

        // Setup connect/disconnect buttons
        document.getElementById('connect-btn').addEventListener('click', connectDevice);
        document.getElementById('disconnect-btn').addEventListener('click', disconnectDevice);

        // Select USB by default
        selectMethod('usb');
    }

    function selectMethod(method) {
        currentMethod = method;
        
        // Update UI
        document.querySelectorAll('.btn-group .btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.getElementById(method + '-method-btn').classList.add('active');
        
        // Update connection method display
        document.getElementById('connection-method').textContent = 
            method === 'usb' ? 'USB Connection' : 'Bluetooth Connection';
    }

    function setupEventHandlers() {
        if (!iotManager) return;

        // Data received handler
        iotManager.onDataReceived = function(data) {
            updateSensorData(data);
        };

        // Connection change handler
        iotManager.onConnectionChange = function(connected, device) {
            isConnected = connected;
            updateConnectionStatus(connected, device);
            
            if (connected) {
                startDataMonitoring();
            } else {
                stopDataMonitoring();
            }
        };

        // Error handler
        iotManager.onError = function(message, error) {
            console.error('IoT Error:', error);
            showNotification('Error: ' + message, 'error');
        };

        // Status update handler
        iotManager.onStatusUpdate = function(message) {
            console.log('Status:', message);
        };
    }

    async function connectDevice() {
        if (!iotManager) {
            showNotification('IoT Manager tidak tersedia', 'error');
            return;
        }

        try {
            updateConnectionStatus('connecting');
            await iotManager.connect(currentMethod);
        } catch (error) {
            showNotification('Gagal koneksi: ' + error.message, 'error');
            updateConnectionStatus(false);
        }
    }

    async function disconnectDevice() {
        if (!iotManager || !isConnected) return;

        try {
            await iotManager.disconnect();
        } catch (error) {
            showNotification('Error saat memutuskan: ' + error.message, 'error');
        }
    }

    function updateConnectionStatus(status, device = null) {
        const statusElement = document.getElementById('connection-status');
        const deviceInfo = document.getElementById('device-info');
        const connectBtn = document.getElementById('connect-btn');
        const disconnectBtn = document.getElementById('disconnect-btn');

        if (status === 'connecting') {
            statusElement.className = 'badge badge-warning';
            statusElement.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menghubungkan...';
            deviceInfo.textContent = 'Sedang menghubungkan...';
            connectBtn.disabled = true;
            disconnectBtn.disabled = true;
        } else if (status === true) {
            statusElement.className = 'badge badge-success';
            statusElement.innerHTML = '<i class="fas fa-check-circle me-1"></i> Terhubung';
            deviceInfo.textContent = device ? `Perangkat: ${device.getInfo?.() || 'Unknown'}` : 'Perangkat terhubung';
            connectBtn.disabled = true;
            disconnectBtn.disabled = false;
        } else {
            statusElement.className = 'badge badge-danger';
            statusElement.innerHTML = '<i class="fas fa-times-circle me-1"></i> Terputus';
            deviceInfo.textContent = 'Tidak ada perangkat terhubung';
            connectBtn.disabled = false;
            disconnectBtn.disabled = true;
        }
    }

    function updateSensorData(data) {
        if (data.temperature !== undefined) {
            document.getElementById('temperature').textContent = `${data.temperature}°C`;
        }
        if (data.humidity !== undefined) {
            document.getElementById('humidity').textContent = `${data.humidity}%`;
        }
        if (data.soil_moisture !== undefined) {
            document.getElementById('soil-moisture').textContent = `${data.soil_moisture}%`;
        }
        if (data.ph_level !== undefined) {
            document.getElementById('ph-level').textContent = data.ph_level;
        }
        if (data.nutrient_level !== undefined) {
            document.getElementById('nutrient-level').textContent = `${data.nutrient_level}%`;
        }
        
        document.getElementById('last-update').textContent = new Date().toLocaleTimeString();
    }

    function startDataMonitoring() {
        if (dataInterval) clearInterval(dataInterval);
        
        // Simulate data if no real device connected
        dataInterval = setInterval(() => {
            if (!isConnected) {
                const mockData = {
                    temperature: (Math.random() * 10 + 20).toFixed(1),
                    humidity: (Math.random() * 30 + 40).toFixed(1),
                    soil_moisture: (Math.random() * 40 + 30).toFixed(1),
                    ph_level: (Math.random() * 2 + 6).toFixed(1),
                    nutrient_level: (Math.random() * 30 + 50).toFixed(1)
                };
                updateSensorData(mockData);
            }
        }, 3000);
    }

    function stopDataMonitoring() {
        if (dataInterval) {
            clearInterval(dataInterval);
            dataInterval = null;
        }
    }

    function loadDeviceData() {
        // Load device data via AJAX
        fetch('/api/iot/devices')
            .then(response => response.json())
            .then(data => {
                updateDevicesTable(data.devices || []);
                updateStatistics(data.statistics || {});
            })
            .catch(error => {
                console.error('Error loading device data:', error);
                showNotification('Gagal memuat data perangkat', 'error');
            });
    }

    function updateDevicesTable(devices) {
        const tbody = document.getElementById('devices-table');
        
        if (devices.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center text-white-75">
                        <i class="fas fa-info-circle me-2"></i>
                        Belum ada perangkat IoT terdaftar
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = devices.map(device => `
            <tr>
                <td class="text-white">${device.name}</td>
                <td>
                    <span class="badge badge-info">${device.connection_type?.toUpperCase() || 'USB'}</span>
                </td>
                <td>
                    <span class="badge badge-primary">${device.platform?.toUpperCase() || 'ADRENO'}</span>
                </td>
                <td>
                    <span class="badge ${device.status === 'connected' ? 'badge-success' : 'badge-danger'}">
                        ${device.status === 'connected' ? 'Terhubung' : 'Terputus'}
                    </span>
                </td>
                <td class="text-white-75">${device.location || '-'}</td>
                <td class="text-white-75">${device.class_name || '-'}</td>
                <td class="text-white-75">${device.data_points || 0}</td>
                <td>
                    <button class="btn btn-sm btn-glass me-1" onclick="viewDevice(${device.id})" title="Lihat Detail">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-glass me-1" onclick="editDevice(${device.id})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-glass" onclick="deleteDevice(${device.id})" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    function updateStatistics(stats) {
        document.getElementById('total-devices').textContent = stats.total_devices || 0;
        document.getElementById('connected-devices').textContent = stats.connected_devices || 0;
        document.getElementById('total-data-points').textContent = stats.total_data_points || 0;
        document.getElementById('active-classes').textContent = stats.active_classes || 0;
    }

    function openAddDeviceModal() {
        // Redirect to device management page
        window.location.href = "{{ route('admin.iot-management') }}";
    }

    function viewDevice(id) {
        // Implement view device functionality
        showNotification('Fitur lihat detail perangkat akan segera tersedia', 'info');
    }

    function editDevice(id) {
        // Implement edit device functionality
        showNotification('Fitur edit perangkat akan segera tersedia', 'info');
    }

    function deleteDevice(id) {
        if (confirm('Apakah Anda yakin ingin menghapus perangkat ini?')) {
            // Implement delete device functionality
            showNotification('Fitur hapus perangkat akan segera tersedia', 'info');
        }
    }

    function showAnalytics() {
        showNotification('Fitur analisis data akan segera tersedia', 'info');
    }

    function exportData() {
        showNotification('Fitur export data akan segera tersedia', 'info');
    }

    function showNotification(message, type = 'info') {
        // Simple notification system
        const alertClass = type === 'error' ? 'alert-danger' : 
                          type === 'success' ? 'alert-success' : 
                          type === 'warning' ? 'alert-warning' : 'alert-info';
        
        const notification = document.createElement('div');
        notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 5000);
    }
</script>
@endsection
