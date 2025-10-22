<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IoT Debugging Tool - USB Connection</title>
    
    <!-- CSS Dependencies -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('asset/css/modern-dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/unified-dashboard.css') }}" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }

        .debug-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .debug-header {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #475569;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .debug-title {
            color: #ffffff;
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 1rem 0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .debug-title i {
            color: #10b981;
        }

        .connection-status {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1rem;
        }

        .status-indicator {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
        }

        .status-connected {
            background: #065f46;
            color: #10b981;
        }

        .status-disconnected {
            background: #7f1d1d;
            color: #ef4444;
        }

        .live-indicator {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #dc2626;
            color: #ffffff;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            background: #ffffff;
            border-radius: 50%;
            animation: pulse-dot 1s infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        /* Filter Panel */
        .filter-panel {
            background: #1e293b;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid #334155;
        }

        .filter-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr auto auto;
            gap: 1rem;
            align-items: end;
        }

        .form-group {
            margin-bottom: 0;
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

        .btn {
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: #ffffff;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-success {
            background: #10b981;
            color: #ffffff;
        }

        .btn-success:hover {
            background: #059669;
        }

        .btn-danger {
            background: #ef4444;
            color: #ffffff;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-warning {
            background: #f59e0b;
            color: #ffffff;
        }

        .btn-warning:hover {
            background: #d97706;
        }

        /* Connection Panel */
        .connection-panel {
            background: #1e293b;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid #334155;
        }

        .connection-controls {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .device-info {
            background: #2a2a3e;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid #333;
            flex: 1;
            min-width: 200px;
        }

        .device-info h4 {
            color: #ffffff;
            margin: 0 0 0.5rem 0;
            font-size: 1rem;
        }

        .device-info p {
            color: #94a3b8;
            margin: 0;
            font-size: 0.9rem;
        }

        /* Real-time Data Cards */
        .sensor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .sensor-card {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border-radius: 1rem;
            padding: 1.5rem;
            border: 1px solid #475569;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .sensor-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .sensor-card.temperature {
            border-left: 4px solid #ef4444;
        }

        .sensor-card.humidity {
            border-left: 4px solid #3b82f6;
        }

        .sensor-card.soil-moisture {
            border-left: 4px solid #10b981;
        }

        .sensor-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .sensor-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .sensor-card.temperature .sensor-icon {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .sensor-card.humidity .sensor-icon {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }

        .sensor-card.soil-moisture .sensor-icon {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .sensor-title {
            color: #ffffff;
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }

        .sensor-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0.5rem 0;
        }

        .sensor-label {
            color: #94a3b8;
            font-size: 0.9rem;
            margin: 0;
        }

        .sensor-status {
            color: #10b981;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        /* Data Table */
        .data-table-container {
            background: #1e293b;
            border-radius: 1rem;
            padding: 1.5rem;
            border: 1px solid #334155;
        }

        .table-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .table-title {
            color: #ffffff;
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            background: #1e293b;
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .data-table th {
            background: #334155;
            color: #ffffff;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            border-bottom: 1px solid #475569;
        }

        .data-table td {
            padding: 1rem;
            border-bottom: 1px solid #2a2a3e;
            color: #e2e8f0;
        }

        .data-table tr:hover {
            background: #2a2a3e;
        }

        .time-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .date {
            font-weight: 600;
            color: #ffffff;
        }

        .time {
            font-size: 0.8rem;
            color: #94a3b8;
        }

        .sensor-value-cell {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sensor-value-cell i {
            width: 1rem;
        }

        .no-data {
            text-align: center;
            padding: 3rem;
            color: #94a3b8;
        }

        .no-data i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Notifications */
        .notification {
            position: fixed;
            top: 2rem;
            right: 2rem;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            color: #ffffff;
            font-weight: 600;
            z-index: 1000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.success {
            background: #10b981;
        }

        .notification.error {
            background: #ef4444;
        }

        .notification.warning {
            background: #f59e0b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .debug-container {
                padding: 1rem;
            }

            .filter-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .sensor-grid {
                grid-template-columns: 1fr;
            }

            .connection-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .data-table {
                font-size: 0.8rem;
            }

            .data-table th,
            .data-table td {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="debug-container">
        <!-- Header Section -->
        <div class="debug-header">
            <h1 class="debug-title">
                <i class="fas fa-bug"></i>
                IoT Debugging Tool - USB Connection
            </h1>
            <div class="connection-status">
                <div id="connectionStatus" class="status-indicator status-disconnected">
                    <i class="fas fa-circle"></i>
                    <span>Disconnected</span>
                </div>
                <div id="liveIndicator" class="live-indicator" style="display: none;">
                    <div class="pulse-dot"></div>
                    <span>LIVE</span>
                </div>
            </div>
        </div>

        <!-- Filter Panel -->
        <div class="filter-panel">
            <div class="filter-row">
                <div class="form-group">
                    <label for="fromDate">From Date</label>
                    <input type="date" id="fromDate" class="form-control">
                </div>
                <div class="form-group">
                    <label for="toDate">To Date</label>
                    <input type="date" id="toDate" class="form-control">
                </div>
                <div class="form-group">
                    <label for="sensorType">Sensor Type</label>
                    <select id="sensorType" class="form-control">
                        <option value="">All Sensors</option>
                        <option value="temperature">Temperature</option>
                        <option value="humidity">Humidity</option>
                        <option value="soil_moisture">Soil Moisture</option>
                    </select>
                </div>
                <button class="btn btn-primary" onclick="refreshData()">
                    <i class="fas fa-sync-alt"></i>
                    Refresh Data
                </button>
                <button class="btn btn-danger" onclick="clearAllData()">
                    <i class="fas fa-trash"></i>
                    Clear All
                </button>
            </div>
        </div>

        <!-- Connection Panel -->
        <div class="connection-panel">
            <div class="connection-controls">
                <button id="connectBtn" class="btn btn-success" onclick="connectUSB()">
                    <i class="fas fa-plug"></i>
                    Connect USB Device
                </button>
                <div class="device-info">
                    <h4>Device Information</h4>
                    <p id="deviceInfo">No device connected</p>
                </div>
            </div>
        </div>

        <!-- Real-time Data Display -->
        <div class="sensor-grid">
            <div class="sensor-card temperature">
                <div class="sensor-header">
                    <div class="sensor-icon">
                        <i class="fas fa-thermometer-half"></i>
                    </div>
                    <h3 class="sensor-title">Suhu Tanah</h3>
                </div>
                <div class="sensor-value" id="realTimeTemp">--°C</div>
                <p class="sensor-label">Temperature</p>
                <div class="sensor-status">Normal</div>
            </div>

            <div class="sensor-card humidity">
                <div class="sensor-header">
                    <div class="sensor-icon">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <h3 class="sensor-title">Kadar Humus</h3>
                </div>
                <div class="sensor-value" id="realTimeHumus">--%</div>
                <p class="sensor-label">Humus Level</p>
                <div class="sensor-status">Good</div>
            </div>

            <div class="sensor-card soil-moisture">
                <div class="sensor-header">
                    <div class="sensor-icon">
                        <i class="fas fa-tint"></i>
                    </div>
                    <h3 class="sensor-title">Kelembaban Tanah</h3>
                </div>
                <div class="sensor-value" id="realTimeMoisture">--%</div>
                <p class="sensor-label">Soil Moisture</p>
                <div class="sensor-status">Optimal</div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="data-table-container">
            <div class="table-header">
                <h3 class="table-title">Sensor Data History</h3>
            </div>
            <div id="dataTableContainer">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>Device ID</th>
                            <th>Suhu</th>
                            <th>Humus</th>
                            <th>Kelembaban</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="dataTableBody">
                        @if($recentData->count() > 0)
                            @foreach($recentData as $data)
                                <tr>
                                    <td>
                                        <div class="time-info">
                                            <div class="date">{{ $data->measured_at->format('d M Y') }}</div>
                                            <div class="time">{{ $data->measured_at->format('H:i:s') }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $data->device->device_id ?? 'Unknown' }}</td>
                                    <td>
                                        <div class="sensor-value-cell">
                                            <i class="fas fa-thermometer-half" style="color: #ef4444;"></i>
                                            <span>{{ $data->temperature ?? '-' }}°C</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="sensor-value-cell">
                                            <i class="fas fa-seedling" style="color: #3b82f6;"></i>
                                            <span>{{ $data->humidity ?? '-' }}%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="sensor-value-cell">
                                            <i class="fas fa-tint" style="color: #10b981;"></i>
                                            <span>{{ $data->soil_moisture ?? '-' }}%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-warning" onclick="viewDetails({{ $data->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="no-data">
                                    <i class="fas fa-database"></i>
                                    <p>No sensor data available</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="{{ asset('asset/js/usb-iot.js') }}"></script>
    <script>
        let usbManager = null;
        let isConnected = false;
        let currentData = {
            temperature: null,
            humidity: null,
            soil_moisture: null
        };

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            console.log('IoT Debug Tool initialized');
            updateConnectionStatus(false);
        });

        // Connect USB Device
        async function connectUSB() {
            const connectBtn = document.getElementById('connectBtn');
            const deviceInfo = document.getElementById('deviceInfo');
            
            try {
                connectBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connecting...';
                connectBtn.disabled = true;

                // Initialize USB IoT Manager
                if (typeof USBIoTManager !== 'undefined') {
                    usbManager = new USBIoTManager();
                    
                    // Set up event handlers
                    usbManager.onDataReceived = function(data) {
                        console.log('Data received from USB:', data);
                        updateRealTimeDisplay(data);
                        saveSensorData(data);
                    };

                    usbManager.onError = function(error) {
                        console.error('USB connection error:', error);
                        showNotification('Connection error: ' + error.message, 'error');
                        updateConnectionStatus(false);
                    };

                    usbManager.onConnected = function(deviceInfo) {
                        console.log('USB device connected:', deviceInfo);
                        showNotification('USB device connected successfully', 'success');
                        updateConnectionStatus(true, deviceInfo);
                    };

                    usbManager.onDisconnected = function() {
                        console.log('USB device disconnected');
                        showNotification('USB device disconnected', 'warning');
                        updateConnectionStatus(false);
                    };

                    // Connect to device
                    await usbManager.connect();
                    
                } else {
                    throw new Error('USB IoT Manager not available');
                }

            } catch (error) {
                console.error('Failed to connect USB device:', error);
                showNotification('Failed to connect: ' + error.message, 'error');
                updateConnectionStatus(false);
            } finally {
                connectBtn.innerHTML = '<i class="fas fa-plug"></i> Connect USB Device';
                connectBtn.disabled = false;
            }
        }

        // Update connection status
        function updateConnectionStatus(connected, deviceInfo = null) {
            const statusElement = document.getElementById('connectionStatus');
            const liveIndicator = document.getElementById('liveIndicator');
            const deviceInfoElement = document.getElementById('deviceInfo');
            const connectBtn = document.getElementById('connectBtn');

            isConnected = connected;

            if (connected) {
                statusElement.className = 'status-indicator status-connected';
                statusElement.innerHTML = '<i class="fas fa-circle"></i><span>Connected</span>';
                liveIndicator.style.display = 'flex';
                connectBtn.innerHTML = '<i class="fas fa-unlink"></i> Disconnect';
                connectBtn.onclick = disconnectUSB;
                
                if (deviceInfo) {
                    deviceInfoElement.innerHTML = `
                        <strong>Device ID:</strong> ${deviceInfo.deviceId || 'Unknown'}<br>
                        <strong>Type:</strong> ${deviceInfo.deviceType || 'Soil Sensor'}<br>
                        <strong>Status:</strong> Online
                    `;
                }
            } else {
                statusElement.className = 'status-indicator status-disconnected';
                statusElement.innerHTML = '<i class="fas fa-circle"></i><span>Disconnected</span>';
                liveIndicator.style.display = 'none';
                connectBtn.innerHTML = '<i class="fas fa-plug"></i> Connect USB Device';
                connectBtn.onclick = connectUSB;
                deviceInfoElement.textContent = 'No device connected';
            }
        }

        // Disconnect USB Device
        async function disconnectUSB() {
            if (usbManager) {
                try {
                    await usbManager.disconnect();
                    usbManager = null;
                } catch (error) {
                    console.error('Error disconnecting:', error);
                }
            }
            updateConnectionStatus(false);
        }

        // Update real-time display
        function updateRealTimeDisplay(data) {
            if (data.temperature !== undefined) {
                document.getElementById('realTimeTemp').textContent = data.temperature + '°C';
                currentData.temperature = data.temperature;
            }
            
            if (data.humidity !== undefined) {
                document.getElementById('realTimeHumus').textContent = data.humidity + '%';
                currentData.humidity = data.humidity;
            }
            
            if (data.soil_moisture !== undefined) {
                document.getElementById('realTimeMoisture').textContent = data.soil_moisture + '%';
                currentData.soil_moisture = data.soil_moisture;
            }
        }

        // Save sensor data to server
        async function saveSensorData(data) {
            try {
                const payload = {
                    device_id: 'USB_DEBUG_' + Date.now(),
                    temperature: data.temperature || null,
                    humidity: data.humidity || null,
                    soil_moisture: data.soil_moisture || null,
                    ph_level: data.ph_level || null,
                    nutrient_level: data.nutrient_level || null,
                    location: 'Debug Area',
                    notes: 'Auto-saved from USB debug session'
                };

                const response = await fetch('/api/iot-debug/store-data', {
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
                    // Optionally refresh the data table
                    // refreshData();
                } else {
                    console.error('Failed to save sensor data:', result.message);
                    showNotification('Failed to save data: ' + result.message, 'error');
                }

            } catch (error) {
                console.error('Error saving sensor data:', error);
                showNotification('Error saving data: ' + error.message, 'error');
            }
        }

        // Refresh data table
        async function refreshData() {
            try {
                const fromDate = document.getElementById('fromDate').value;
                const toDate = document.getElementById('toDate').value;
                const sensorType = document.getElementById('sensorType').value;

                const params = new URLSearchParams();
                if (fromDate) params.append('from_date', fromDate);
                if (toDate) params.append('to_date', toDate);
                if (sensorType) params.append('sensor_type', sensorType);

                const response = await fetch('/api/iot-debug/sensor-data?' + params);
                const result = await response.json();

                if (result.success) {
                    updateDataTable(result.data);
                    showNotification('Data refreshed successfully', 'success');
                } else {
                    showNotification('Failed to refresh data', 'error');
                }

            } catch (error) {
                console.error('Error refreshing data:', error);
                showNotification('Error refreshing data: ' + error.message, 'error');
            }
        }

        // Update data table
        function updateDataTable(data) {
            const tbody = document.getElementById('dataTableBody');
            
            if (data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="no-data">
                            <i class="fas fa-database"></i>
                            <p>No sensor data available</p>
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = data.map(item => `
                <tr>
                    <td>
                        <div class="time-info">
                            <div class="date">${new Date(item.measured_at).toLocaleDateString()}</div>
                            <div class="time">${new Date(item.measured_at).toLocaleTimeString()}</div>
                        </div>
                    </td>
                    <td>${item.device?.device_id || 'Unknown'}</td>
                    <td>
                        <div class="sensor-value-cell">
                            <i class="fas fa-thermometer-half" style="color: #ef4444;"></i>
                            <span>${item.temperature || '-'}°C</span>
                        </div>
                    </td>
                    <td>
                        <div class="sensor-value-cell">
                            <i class="fas fa-seedling" style="color: #3b82f6;"></i>
                            <span>${item.humidity || '-'}%</span>
                        </div>
                    </td>
                    <td>
                        <div class="sensor-value-cell">
                            <i class="fas fa-tint" style="color: #10b981;"></i>
                            <span>${item.soil_moisture || '-'}%</span>
                        </div>
                    </td>
                    <td>
                        <button class="btn btn-warning" onclick="viewDetails(${item.id})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        // Clear all debug data
        async function clearAllData() {
            if (!confirm('Are you sure you want to clear all debug data? This action cannot be undone.')) {
                return;
            }

            try {
                const response = await fetch('/api/iot-debug/clear-data', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const result = await response.json();

                if (result.success) {
                    showNotification(result.message, 'success');
                    refreshData();
                } else {
                    showNotification('Failed to clear data: ' + result.message, 'error');
                }

            } catch (error) {
                console.error('Error clearing data:', error);
                showNotification('Error clearing data: ' + error.message, 'error');
            }
        }

        // View details (placeholder)
        function viewDetails(id) {
            alert('View details for sensor data ID: ' + id);
        }

        // Show notification
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);
            
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }
    </script>
</body>
</html>
