<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>📡 Device Dashboard - {{ $device->name ?? 'NPK Sensor' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --dark-bg: #1a1a1a;
            --card-bg: #2d2d2d;
            --text-light: #ecf0f1;
            --border-color: #34495e;
        }

        body {
            background: linear-gradient(135deg, var(--dark-bg) 0%, #2c3e50 100%);
            color: var(--text-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .navbar {
            background: rgba(44, 62, 80, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
        }

        .navbar-brand {
            color: var(--text-light) !important;
            font-weight: 600;
        }

        .main-container {
            padding: 2rem 0;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(45deg, var(--secondary-color), var(--success-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .page-subtitle {
            color: #bdc3c7;
            font-size: 1.1rem;
        }

        .status-card {
            background: rgba(45, 45, 45, 0.8);
            border: 1px solid var(--border-color);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .status-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .status-indicator {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 10px;
            animation: pulse 2s infinite;
        }

        .status-online {
            background-color: var(--success-color);
        }

        .status-offline {
            background-color: var(--danger-color);
        }

        .status-connecting {
            background-color: var(--warning-color);
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        .sensor-value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--secondary-color);
        }

        .sensor-unit {
            font-size: 1rem;
            color: #bdc3c7;
        }

        .sensor-card {
            text-align: center;
            padding: 1.5rem;
            border-radius: 10px;
            background: rgba(52, 73, 94, 0.3);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .sensor-card:hover {
            background: rgba(52, 73, 94, 0.5);
            transform: translateY(-2px);
        }

        .chart-container {
            background: rgba(45, 45, 45, 0.8);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .btn {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .last-update {
            color: #95a5a6;
            font-size: 0.9rem;
        }

        .loading-spinner {
            display: none;
        }

        .loading-spinner.show {
            display: inline-block;
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .nav-link {
            color: var(--text-light) !important;
        }

        .nav-link:hover {
            color: var(--secondary-color) !important;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-microchip me-2"></i>Device Dashboard
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('iot.esp8266-status.public') }}">
                    <i class="fas fa-wifi me-1"></i>ESP8266 Status
                </a>
                <a class="nav-link" href="{{ route('iot.wifi-config.public') }}">
                    <i class="fas fa-cog me-1"></i>WiFi Config
                </a>
            </div>
        </div>
    </nav>

    <div class="container main-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">📡 {{ $device->name ?? 'NPK Sensor' }}</h1>
            <p class="page-subtitle">Device ID: {{ $device->device_id ?? $deviceId }}</p>
        </div>

        <!-- Connection Status -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="status-card">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="text-white mb-3">
                                <span id="connection-indicator" class="status-indicator status-offline"></span>
                                Status Koneksi Device
                            </h5>
                            <div id="connection-info">
                                <p class="mb-1"><strong>Status:</strong> <span id="connection-status">Checking...</span></p>
                                <p class="mb-1"><strong>WiFi:</strong> <span id="wifi-info">Unknown</span></p>
                                <p class="mb-1"><strong>IP Address:</strong> <span id="ip-address">Unknown</span></p>
                                <p class="mb-1"><strong>Last Update:</strong> <span id="last-update">Never</span></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" onclick="refreshData()">
                                    <i class="fas fa-sync-alt me-2"></i>Refresh Data
                                    <span class="loading-spinner spinner-border spinner-border-sm ms-2"></span>
                                </button>
                                <button class="btn btn-success" onclick="syncFromThingsBoard()">
                                    <i class="fas fa-cloud-download-alt me-2"></i>Sync from ThingsBoard
                                </button>
                                <button class="btn btn-info" onclick="toggleAutoRefresh()">
                                    <i class="fas fa-play me-2"></i><span id="auto-refresh-text">Start Auto Refresh</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Real-Time Sensor Data -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="status-card">
                    <h5 class="text-white mb-3">
                        <i class="fas fa-thermometer-half me-2"></i>Real-Time Sensor Data
                        <span class="badge bg-success ms-2" id="data-status">Live</span>
                    </h5>
                    <div class="row" id="sensor-data">
                        <!-- Temperature & Humidity -->
                        <div class="col-6 col-md-3 mb-3">
                            <div class="sensor-card">
                                <div class="sensor-value" id="temperature">--</div>
                                <div class="sensor-unit">°C</div>
                                <small class="text-muted">Temperature</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="sensor-card">
                                <div class="sensor-value" id="humidity">--</div>
                                <div class="sensor-unit">%</div>
                                <small class="text-muted">Humidity</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="sensor-card">
                                <div class="sensor-value" id="ph">--</div>
                                <div class="sensor-unit">pH</div>
                                <small class="text-muted">pH Level</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <div class="sensor-card">
                                <div class="sensor-value" id="conductivity">--</div>
                                <div class="sensor-unit">μS/cm</div>
                                <small class="text-muted">Conductivity</small>
                            </div>
                        </div>
                        <!-- NPK Values -->
                        <div class="col-6 col-md-4 mb-3">
                            <div class="sensor-card">
                                <div class="sensor-value" id="nitrogen">--</div>
                                <div class="sensor-unit">mg/kg</div>
                                <small class="text-muted">Nitrogen</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 mb-3">
                            <div class="sensor-card">
                                <div class="sensor-value" id="phosphorus">--</div>
                                <div class="sensor-unit">mg/kg</div>
                                <small class="text-muted">Phosphorus</small>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 mb-3">
                            <div class="sensor-card">
                                <div class="sensor-value" id="potassium">--</div>
                                <div class="sensor-unit">mg/kg</div>
                                <small class="text-muted">Potassium</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historical Chart -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="chart-container">
                    <h5 class="text-white mb-3">
                        <i class="fas fa-chart-line me-2"></i>Historical Data (24 Hours)
                    </h5>
                    <canvas id="sensorChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- System Information -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="status-card">
                    <h5 class="text-white mb-3">
                        <i class="fas fa-info-circle me-2"></i>System Information
                    </h5>
                    <div id="system-info">
                        <p class="mb-1"><strong>Device Type:</strong> <span id="device-type">{{ $device->device_type ?? 'NPK Sensor' }}</span></p>
                        <p class="mb-1"><strong>Platform:</strong> <span id="platform">ThingsBoard</span></p>
                        <p class="mb-1"><strong>Uptime:</strong> <span id="uptime">Unknown</span></p>
                        <p class="mb-1"><strong>Free Heap:</strong> <span id="free-heap">Unknown</span></p>
                        <p class="mb-1"><strong>Chip ID:</strong> <span id="chip-id">Unknown</span></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="status-card">
                    <h5 class="text-white mb-3">
                        <i class="fas fa-database me-2"></i>Data Statistics
                    </h5>
                    <div id="data-stats">
                        <p class="mb-1"><strong>Total Readings:</strong> <span id="total-readings">{{ $historicalData->count() ?? 0 }}</span></p>
                        <p class="mb-1"><strong>Last Reading:</strong> <span id="last-reading">{{ $latestData->measured_at ?? 'Never' }}</span></p>
                        <p class="mb-1"><strong>Data Source:</strong> <span id="data-source">Database</span></p>
                        <p class="mb-1"><strong>Sync Status:</strong> <span id="sync-status">Unknown</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const deviceId = '{{ $device->device_id ?? $deviceId }}';
        let autoRefreshInterval = null;
        let isAutoRefresh = false;
        let sensorChart = null;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Device Dashboard loaded for device:', deviceId);
            initializeChart();
            refreshData();
        });

        // Initialize Chart.js
        function initializeChart() {
            const ctx = document.getElementById('sensorChart').getContext('2d');
            sensorChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [
                        {
                            label: 'Temperature (°C)',
                            data: [],
                            borderColor: '#e74c3c',
                            backgroundColor: 'rgba(231, 76, 60, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Humidity (%)',
                            data: [],
                            borderColor: '#3498db',
                            backgroundColor: 'rgba(52, 152, 219, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'pH',
                            data: [],
                            borderColor: '#f39c12',
                            backgroundColor: 'rgba(243, 156, 18, 0.1)',
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#ecf0f1'
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: '#bdc3c7'
                            },
                            grid: {
                                color: '#34495e'
                            }
                        },
                        y: {
                            ticks: {
                                color: '#bdc3c7'
                            },
                            grid: {
                                color: '#34495e'
                            }
                        }
                    }
                }
            });
        }

        // Refresh data from server
        function refreshData() {
            const spinner = document.querySelector('.loading-spinner');
            spinner.classList.add('show');

            fetch(`/api/iot/device/${deviceId}/realtime`)
                .then(response => response.json())
                .then(data => {
                    console.log('Real-time data received:', data);
                    updateDisplay(data);
                    spinner.classList.remove('show');
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    showAlert('error', 'Failed to fetch real-time data');
                    spinner.classList.remove('show');
                });
        }

        // Update display with new data
        function updateDisplay(data) {
            // Update connection status
            const indicator = document.getElementById('connection-indicator');
            const status = document.getElementById('connection-status');
            
            if (data.is_online) {
                indicator.className = 'status-indicator status-online';
                status.textContent = 'Online';
            } else {
                indicator.className = 'status-indicator status-offline';
                status.textContent = 'Offline';
            }

            // Update WiFi info
            if (data.device_status) {
                document.getElementById('wifi-info').textContent = data.device_status.wifi_ssid || 'Unknown';
                document.getElementById('ip-address').textContent = data.device_status.ip_address || 'Unknown';
                document.getElementById('uptime').textContent = data.device_status.system_info?.uptime ? 
                    Math.floor(data.device_status.system_info.uptime / 60) + ' minutes' : 'Unknown';
                document.getElementById('free-heap').textContent = data.device_status.system_info?.free_heap ? 
                    data.device_status.system_info.free_heap + ' bytes' : 'Unknown';
                document.getElementById('chip-id').textContent = data.device_status.system_info?.chip_id || 'Unknown';
            }

            // Update sensor data
            if (data.database_data) {
                const sensor = data.database_data;
                document.getElementById('temperature').textContent = sensor.soil_temperature ? 
                    parseFloat(sensor.soil_temperature).toFixed(1) : '--';
                document.getElementById('humidity').textContent = sensor.soil_moisture ? 
                    parseFloat(sensor.soil_moisture).toFixed(0) : '--';
                document.getElementById('ph').textContent = sensor.soil_ph ? 
                    parseFloat(sensor.soil_ph).toFixed(1) : '--';
                document.getElementById('conductivity').textContent = sensor.soil_conductivity ? 
                    parseFloat(sensor.soil_conductivity).toFixed(0) : '--';
                document.getElementById('nitrogen').textContent = sensor.nitrogen || '--';
                document.getElementById('phosphorus').textContent = sensor.phosphorus || '--';
                document.getElementById('potassium').textContent = sensor.potassium || '--';

                // Update last reading time
                document.getElementById('last-reading').textContent = new Date(sensor.measured_at).toLocaleString();
            }

            // Update ThingsBoard data if available
            if (data.thingsboard_data) {
                document.getElementById('data-source').textContent = 'ThingsBoard';
                document.getElementById('sync-status').textContent = 'Synced';
            } else {
                document.getElementById('data-source').textContent = 'Database';
                document.getElementById('sync-status').textContent = 'Local Only';
            }

            // Update last update time
            document.getElementById('last-update').textContent = new Date().toLocaleTimeString();
        }

        // Sync from ThingsBoard
        function syncFromThingsBoard() {
            fetch(`/api/iot/device/${deviceId}/sync`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', 'Data synced successfully from ThingsBoard');
                    refreshData();
                } else {
                    showAlert('error', 'Failed to sync from ThingsBoard: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Sync error:', error);
                showAlert('error', 'Failed to sync from ThingsBoard');
            });
        }

        // Toggle auto refresh
        function toggleAutoRefresh() {
            const button = document.getElementById('auto-refresh-text');
            
            if (isAutoRefresh) {
                clearInterval(autoRefreshInterval);
                isAutoRefresh = false;
                button.textContent = 'Start Auto Refresh';
                showAlert('info', 'Auto refresh stopped');
            } else {
                autoRefreshInterval = setInterval(refreshData, 5000); // 5 seconds
                isAutoRefresh = true;
                button.textContent = 'Stop Auto Refresh';
                showAlert('success', 'Auto refresh started (5 seconds)');
            }
        }

        // Show alert
        function showAlert(type, message) {
            const alertClass = type === 'error' ? 'danger' : type;
            const alertHtml = `
                <div class="alert alert-${alertClass} alert-dismissible fade show" role="alert">
                    <i class="fas fa-${getAlertIcon(type)} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            const alertContainer = document.createElement('div');
            alertContainer.innerHTML = alertHtml;
            document.body.insertBefore(alertContainer.firstElementChild, document.body.firstChild);
            
            setTimeout(() => {
                const alert = document.querySelector('.alert');
                if (alert) {
                    bootstrap.Alert.getOrCreateInstance(alert).close();
                }
            }, 5000);
        }

        // Get alert icon
        function getAlertIcon(type) {
            const icons = {
                'success': 'check-circle',
                'error': 'exclamation-triangle',
                'warning': 'exclamation-triangle',
                'info': 'info-circle'
            };
            return icons[type] || 'info-circle';
        }

        // Load historical data for chart
        function loadHistoricalData() {
            fetch(`/api/iot/device/${deviceId}/history?hours=24&limit=50`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.chart_data) {
                        updateChart(data.chart_data);
                    }
                })
                .catch(error => {
                    console.error('Error loading historical data:', error);
                });
        }

        // Update chart with new data
        function updateChart(chartData) {
            if (sensorChart && chartData.labels) {
                sensorChart.data.labels = chartData.labels;
                sensorChart.data.datasets[0].data = chartData.temperature;
                sensorChart.data.datasets[1].data = chartData.humidity;
                sensorChart.data.datasets[2].data = chartData.ph;
                sensorChart.update();
            }
        }

        // Load historical data on page load
        loadHistoricalData();
    </script>
</body>
</html>

