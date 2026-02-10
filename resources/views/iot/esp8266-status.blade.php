<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>📡 ESP8266 Real-Time Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--secondary-color);
        }

        .sensor-unit {
            font-size: 0.9rem;
            color: #bdc3c7;
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

        .form-control, .form-select {
            background: rgba(52, 73, 94, 0.8);
            border: 1px solid var(--border-color);
            color: var(--text-light);
            border-radius: 10px;
            padding: 0.75rem 1rem;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(52, 73, 94, 0.9);
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
            color: var(--text-light);
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .log-container {
            background: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            padding: 1rem;
            max-height: 300px;
            overflow-y: auto;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
        }

        .log-entry {
            margin-bottom: 0.5rem;
            padding: 0.25rem 0;
        }

        .log-timestamp {
            color: #95a5a6;
        }

        .log-info {
            color: var(--secondary-color);
        }

        .log-success {
            color: var(--success-color);
        }

        .log-warning {
            color: var(--warning-color);
        }

        .log-error {
            color: var(--danger-color);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-microchip me-2"></i>ESP8266 Real-Time Status
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('iot.wifi-config.public') }}">
                    <i class="fas fa-wifi me-1"></i>WiFi Config
                </a>
            </div>
        </div>
    </nav>

    <div class="container main-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">📡 ESP8266 Real-Time Status</h1>
            <p class="page-subtitle">Monitoring langsung dari perangkat ESP8266 (bukan dari database)</p>
        </div>

        <!-- Connection Status -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="status-card">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="text-white mb-3">
                                <span id="connection-indicator" class="status-indicator status-offline"></span>
                                Status Koneksi ESP8266
                            </h5>
                            <div id="connection-info">
                                <p class="mb-1"><strong>Status:</strong> <span id="connection-status">Disconnected</span></p>
                                <p class="mb-1"><strong>Port:</strong> <span id="current-port">Not selected</span></p>
                                <p class="mb-1"><strong>Last Update:</strong> <span id="last-update">Never</span></p>
                                <p class="mb-1"><strong>Health:</strong> <span id="connection-health" class="badge bg-secondary">Unknown</span></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                             <div class="d-grid gap-2">
                                 <button class="btn btn-primary" onclick="detectPorts()">
                                     <i class="fas fa-search me-2"></i>Deteksi Port
                                 </button>
                                 <button class="btn btn-info" onclick="autoDetectAndConnect()">
                                     <i class="fas fa-magic me-2"></i>Auto Detect & Connect
                                 </button>
                                 <button class="btn btn-outline-primary btn-sm" onclick="manualPortDetection()">
                                     <i class="fas fa-hand-paper me-1"></i>Manual Detection
                                 </button>
                                 <button class="btn btn-success" onclick="connectToDevice()" id="connect-btn" disabled>
                                     <i class="fas fa-plug me-2"></i>Connect to ESP8266
                                 </button>
                                 <button class="btn btn-warning" onclick="disconnectDevice()" id="disconnect-btn" disabled>
                                     <i class="fas fa-unlink me-2"></i>Disconnect
                                 </button>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Port Selection -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="status-card">
                    <h5 class="text-white mb-3">
                        <i class="fas fa-usb me-2"></i>Port Selection
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="serialPort" class="form-label">Serial Port</label>
                            <select class="form-select" id="serialPort" onchange="onPortSelected()">
                                <option value="">Pilih Port Serial...</option>
                            </select>
                            <div id="device-info" class="mt-2" style="display: none;">
                                <small class="text-muted">
                                    <strong>Device:</strong> <span id="device-name">-</span><br>
                                    <strong>Type:</strong> <span id="device-type">-</span><br>
                                    <strong>Vendor ID:</strong> <span id="vendor-id">-</span><br>
                                    <strong>Compatible:</strong> <span id="compatibility-status">-</span>
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Available Commands</label>
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-info btn-sm" onclick="sendCommand('STATUS')">
                                    <i class="fas fa-info-circle me-1"></i>Get Status
                                </button>
                                <button class="btn btn-outline-warning btn-sm" onclick="sendCommand('SCAN')">
                                    <i class="fas fa-wifi me-1"></i>Scan WiFi
                                </button>
                                <button class="btn btn-outline-success btn-sm" onclick="sendCommand('SENSOR')">
                                    <i class="fas fa-thermometer-half me-1"></i>Read Sensors
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Real-Time Data -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="status-card">
                    <h5 class="text-white mb-3">
                        <i class="fas fa-wifi me-2"></i>WiFi Status
                    </h5>
                    <div id="wifi-status">
                        <p class="mb-1"><strong>SSID:</strong> <span id="wifi-ssid">Not connected</span></p>
                        <p class="mb-1"><strong>IP Address:</strong> <span id="wifi-ip">Not assigned</span></p>
                        <p class="mb-1"><strong>Signal:</strong> <span id="wifi-signal">Unknown</span></p>
                        <p class="mb-1"><strong>Status:</strong> <span id="wifi-status-text">Disconnected</span></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="status-card">
                    <h5 class="text-white mb-3">
                        <i class="fas fa-microchip me-2"></i>System Info
                    </h5>
                    <div id="system-info">
                        <p class="mb-1"><strong>Chip ID:</strong> <span id="chip-id">Unknown</span></p>
                        <p class="mb-1"><strong>Free Heap:</strong> <span id="free-heap">Unknown</span></p>
                        <p class="mb-1"><strong>Uptime:</strong> <span id="uptime">Unknown</span></p>
                        <p class="mb-1"><strong>Firmware:</strong> <span id="firmware">Unknown</span></p>
                    </div>
                </div>
            </div>
        </div>


        <!-- Sensor Data -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="status-card">
                    <h5 class="text-white mb-3">
                        <i class="fas fa-thermometer-half me-2"></i>Sensor Data (Real-Time)
                    </h5>
                    <div class="row" id="sensor-data">
                        <!-- Row 1: 4 sensors -->
                        <div class="col-6 col-md-3 text-center mb-3">
                            <div class="sensor-value" id="soil-temperature">--</div>
                            <div class="sensor-unit">°C</div>
                            <small class="text-muted">Soil Temperature</small>
                        </div>
                        <div class="col-6 col-md-3 text-center mb-3">
                            <div class="sensor-value" id="soil-moisture">--</div>
                            <div class="sensor-unit">%</div>
                            <small class="text-muted">Soil Moisture</small>
                        </div>
                        <div class="col-6 col-md-3 text-center mb-3">
                            <div class="sensor-value" id="soil-conductivity">--</div>
                            <div class="sensor-unit">μS/cm</div>
                            <small class="text-muted">Conductivity</small>
                        </div>
                        <div class="col-6 col-md-3 text-center mb-3">
                            <div class="sensor-value" id="soil-ph">--</div>
                            <div class="sensor-unit">pH</div>
                            <small class="text-muted">pH Level</small>
                        </div>
                        <!-- Row 2: 3 sensors -->
                        <div class="col-6 col-md-4 text-center mb-3">
                            <div class="sensor-value" id="nitrogen">--</div>
                            <div class="sensor-unit">mg/kg</div>
                            <small class="text-muted">Nitrogen</small>
                        </div>
                        <div class="col-6 col-md-4 text-center mb-3">
                            <div class="sensor-value" id="phosphorus">--</div>
                            <div class="sensor-unit">mg/kg</div>
                            <small class="text-muted">Phosphorus</small>
                        </div>
                        <div class="col-6 col-md-4 text-center mb-3">
                            <div class="sensor-value" id="potassium">--</div>
                            <div class="sensor-unit">mg/kg</div>
                            <small class="text-muted">Potassium</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Communication Log -->
        <div class="row">
            <div class="col-12">
                <div class="status-card">
                    <h5 class="text-white mb-3">
                        <i class="fas fa-terminal me-2"></i>Communication Log
                        <button class="btn btn-outline-secondary btn-sm float-end" onclick="clearLog()">
                            <i class="fas fa-trash me-1"></i>Clear
                        </button>
                    </h5>
                    <div class="log-container" id="communication-log">
                        <div class="log-entry">
                            <span class="log-timestamp">[00:00:00]</span>
                            <span class="log-info">System ready. Click "Deteksi Port" button to scan for ESP8266 devices.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-body text-center py-4">
                    <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h5 id="loadingText" class="mb-2">Processing...</h5>
                    <p class="text-muted mb-3" id="loadingSubtext">Please wait</p>
                    
                    <!-- Progress Steps -->
                    <div id="loadingSteps" class="d-none">
                        <div class="progress mb-2" style="height: 8px;">
                            <div id="loadingProgress" class="progress-bar progress-bar-striped progress-bar-animated" 
                                 role="progressbar" style="width: 0%"></div>
                        </div>
                        <small id="loadingStep" class="text-muted">Step 1 of 3</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Result Modal -->
    <div class="modal fade" id="resultModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title" id="resultTitle">
                        <i id="resultIcon" class="fas fa-check-circle text-success"></i>
                        Detection Result
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="resultBody">
                    <!-- Result content will be inserted here -->
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentPort = null;
        let isConnected = false;
        let updateInterval = null;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Page loaded, ready for port detection');
            // REMOVED: detectPorts() - user must click button manually
            addLog('info', 'Ready. Click "Auto Detect & Connect" for automatic ESP8266 detection, or "Deteksi Port" for manual selection.');
        });

        // Detect available serial ports
        function detectPorts() {
            console.log('=== DETECT PORTS START ===');
            showLoading('Detecting Serial Ports', 'Please wait...', true);
            updateLoadingProgress(10, 'Step 1 of 3: Initializing...');
            addLog('info', 'Starting port detection...');
            
            // Update progress
            setTimeout(() => updateLoadingProgress(33, 'Step 2 of 3: Scanning USB devices...'), 200);
            
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 8000);
            
            fetch('/iot/wifi-config/detect-ports', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                signal: controller.signal
            })
            .then(response => {
                clearTimeout(timeoutId);
                updateLoadingProgress(66, 'Step 3 of 3: Processing results...');
                return response.json();
            })
            .then(data => {
                updateLoadingProgress(100, 'Complete!');
                
                setTimeout(() => {
                    hideLoading();
                    
                    // Populate dropdown
                    const portSelect = document.getElementById('serialPort');
                    portSelect.innerHTML = '<option value="">Pilih Port Serial...</option>';
                    
                    if (data.success && data.ports && data.ports.length > 0) {
                        data.ports.forEach(port => {
                            const deviceInfo = port.device_name || 'Unknown Device';
                            const vendorId = port.vendor_id ? ` (${port.vendor_id}:${port.product_id})` : '';
                            const optionText = `${port.port} - ${deviceInfo}${vendorId}`;
                            
                            const option = new Option(optionText, port.port);
                            option.dataset.deviceType = port.device_type;
                            option.dataset.isCompatible = port.is_esp8266_compatible;
                            option.style.color = port.is_esp8266_compatible ? '#28a745' : '#dc3545';
                            
                            portSelect.add(option);
                        });
                        
                        // Show success modal with details
                        showResultModal('success', 'Port Detection Successful', 
                            `Found ${data.ports.length} serial port(s)`, data.ports);
                        addLog('success', `Found ${data.ports.length} port(s)`);
                    } else {
                        showResultModal('warning', 'No Ports Found', 
                            'No serial ports detected. Make sure ESP8266 is connected via USB.', []);
                        addLog('warning', 'No serial ports detected');
                    }
                }, 500);
            })
            .catch(error => {
                hideLoading();
                console.error('Detection error:', error);
                
                const errorMsg = error.name === 'AbortError' 
                    ? 'Detection timeout. Please try again.' 
                    : `Error: ${error.message}`;
                
                showResultModal('error', 'Detection Failed', errorMsg, []);
                addLog('error', errorMsg);
            });
        }

        // Auto-detect and connect to ESP8266
        function autoDetectAndConnect() {
            showLoading('Auto-Detecting ESP8266', 'Testing all ports...', true);
            updateLoadingProgress(20, 'Step 1 of 4: Scanning ports...');
            addLog('info', 'Starting ESP8266 auto-detection...');
            
            setTimeout(() => updateLoadingProgress(40, 'Step 2 of 4: Testing connections...'), 500);
            
            fetch('/iot/wifi-config/auto-detect-esp8266')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    updateLoadingProgress(60, 'Step 3 of 4: Validating response...');
                    return response.json();
                })
                .then(data => {
                    updateLoadingProgress(80, 'Step 3 of 4: Validating response...');
                    
                    setTimeout(() => {
                        updateLoadingProgress(100, 'Step 4 of 4: Complete!');
                        
                        setTimeout(() => {
                            hideLoading();
                            
                            if (data.success) {
                                showResultModal('success', 'ESP8266 Found!', 
                                    `Device detected on port: ${data.port}`, 
                                    [{ port: data.port, device_name: 'ESP8266', is_esp8266_compatible: true }]);
                                
                                // Auto-select and enable connect button
                                currentPort = data.port;
                                document.getElementById('serialPort').value = data.port;
                                document.getElementById('current-port').textContent = data.port;
                                document.getElementById('connect-btn').disabled = false;
                                
                                // Update device info display
                                document.getElementById('device-name').textContent = 'ESP8266 (Auto-detected)';
                                document.getElementById('device-type').textContent = 'ESP8266 Module';
                                document.getElementById('vendor-id').textContent = 'Auto-detected';
                                document.getElementById('compatibility-status').textContent = 'Yes (ESP8266 Compatible)';
                                document.getElementById('compatibility-status').style.color = '#28a745';
                                document.getElementById('device-info').style.display = 'block';
                                
                                addLog('success', `ESP8266 found on ${data.port}`);
                                
                                // Automatically connect
                                setTimeout(() => {
                                    connectToDevice();
                                }, 1000);
                            } else {
                                showResultModal('warning', 'ESP8266 Not Found', data.message, []);
                                addLog('warning', data.message);
                                
                                // Fallback to manual detection
                                addLog('info', 'Falling back to manual port detection...');
                                manualPortDetection();
                            }
                        }, 500);
                    }, 300);
                })
                .catch(error => {
                    hideLoading();
                    showResultModal('error', 'Auto-Detection Failed', error.message, []);
                    addLog('error', 'Auto-detection failed: ' + error.message);
                    
                    // Fallback to manual detection
                    addLog('info', 'Falling back to manual port detection...');
                    manualPortDetection();
                });
        }

        // Manual port detection (bypass loading issue)
        function manualPortDetection() {
            console.log('Manual port detection triggered');
            hideLoading(); // Force hide any loading modal
            
            // Add common ESP8266 ports manually
            const portSelect = document.getElementById('serialPort');
            portSelect.innerHTML = '<option value="">Pilih Port Serial...</option>';
            
            const commonPorts = [
                '/dev/cu.usbserial-10',
                '/dev/cu.usbserial-110', 
                '/dev/cu.usbserial-000',
                '/dev/cu.usbserial-001',
                '/dev/ttyUSB0',
                '/dev/ttyUSB1',
                '/dev/ttyACM0',
                '/dev/ttyACM1'
            ];
            
            commonPorts.forEach(port => {
                const option = new Option(port, port);
                portSelect.add(option);
            });
            
            addLog('info', 'Manual port detection completed. Please select your ESP8266 port.');
            showAlert('info', 'Manual port detection completed. Please select your ESP8266 port.');
        }

        // Handle port selection
        function onPortSelected() {
            const portSelect = document.getElementById('serialPort');
            const port = portSelect.value;
            const selectedOption = portSelect.options[portSelect.selectedIndex];
            currentPort = port;
            
            if (port) {
                document.getElementById('current-port').textContent = port;
                document.getElementById('connect-btn').disabled = false;
                addLog('info', `Port selected: ${port}`);
                
                // Display device information
                if (selectedOption.dataset.deviceType) {
                    document.getElementById('device-name').textContent = selectedOption.text.split(' - ')[1] || 'Unknown Device';
                    document.getElementById('device-type').textContent = selectedOption.dataset.deviceType;
                    document.getElementById('vendor-id').textContent = selectedOption.text.match(/\(([^)]+)\)/)?.[1] || 'Unknown';
                    
                    const isCompatible = selectedOption.dataset.isCompatible === 'true';
                    const compatibilityElement = document.getElementById('compatibility-status');
                    if (isCompatible) {
                        compatibilityElement.textContent = 'Yes (ESP8266 Compatible)';
                        compatibilityElement.style.color = '#28a745';
                    } else {
                        compatibilityElement.textContent = 'No (May not work)';
                        compatibilityElement.style.color = '#dc3545';
                    }
                    
                    document.getElementById('device-info').style.display = 'block';
                }
            } else {
                document.getElementById('current-port').textContent = 'Not selected';
                document.getElementById('connect-btn').disabled = true;
                document.getElementById('device-info').style.display = 'none';
            }
        }

        // Connect to ESP8266
        function connectToDevice() {
            if (!currentPort) {
                showAlert('warning', 'Please select a serial port first');
                return;
            }
            
            showLoading('Connecting to ESP8266...', 'Testing serial communication');
            addLog('info', `Connecting to ESP8266 on port ${currentPort}...`);
            
            // Test connection by sending STATUS command
            fetch('/iot/wifi-config/test-connection', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ port: currentPort })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                hideLoading();
                
                if (data.success) {
                    isConnected = true;
                    updateConnectionStatus(true);
                    addLog('success', 'Successfully connected to ESP8266');
                    showAlert('success', 'Successfully connected to ESP8266');
                    
                    // Start real-time updates
                    startRealTimeUpdates();
                } else {
                    addLog('error', 'Failed to connect: ' + data.message);
                    showAlert('error', 'Failed to connect: ' + data.message);
                    
                    // Provide helpful troubleshooting tips
                    if (data.message.includes('not exist')) {
                        addLog('info', 'Tip: Make sure ESP8266 is connected via USB and try "Manual Detection"');
                    } else if (data.message.includes('not responding')) {
                        addLog('info', 'Tip: Check if ESP8266 is powered on and running the correct firmware');
                    }
                }
            })
            .catch(error => {
                hideLoading();
                addLog('error', 'Connection error: ' + error.message);
                showAlert('error', 'Connection error: ' + error.message);
                
                // Provide troubleshooting tips for network errors
                if (error.message.includes('Failed to fetch')) {
                    addLog('info', 'Tip: Check if the server is running and accessible');
                }
            });
        }

        // Disconnect from device
        function disconnectDevice() {
            isConnected = false;
            updateConnectionStatus(false);
            stopRealTimeUpdates();
            addLog('info', 'Disconnected from ESP8266');
            showAlert('info', 'Disconnected from ESP8266');
        }

        // Update connection status UI
        function updateConnectionStatus(connected) {
            const indicator = document.getElementById('connection-indicator');
            const status = document.getElementById('connection-status');
            const connectBtn = document.getElementById('connect-btn');
            const disconnectBtn = document.getElementById('disconnect-btn');
            const health = document.getElementById('connection-health');
            
            if (connected) {
                indicator.className = 'status-indicator status-online';
                status.textContent = 'Connected';
                connectBtn.disabled = true;
                disconnectBtn.disabled = false;
                health.className = 'badge bg-success';
                health.textContent = 'Good';
            } else {
                indicator.className = 'status-indicator status-offline';
                status.textContent = 'Disconnected';
                connectBtn.disabled = false;
                disconnectBtn.disabled = true;
                health.className = 'badge bg-danger';
                health.textContent = 'Offline';
            }
        }
        
        // Update connection health based on command success/failure
        function updateConnectionHealth(success) {
            const health = document.getElementById('connection-health');
            if (success) {
                health.className = 'badge bg-success';
                health.textContent = 'Good';
            } else {
                health.className = 'badge bg-warning';
                health.textContent = 'Issues';
            }
        }

        // Auto refresh functions
        function startAutoRefresh() {
            if (esp8266Monitor && typeof esp8266Monitor.startAutoRefresh === 'function') {
                esp8266Monitor.startAutoRefresh();
            } else {
                console.log('Auto refresh started');
            }
        }

        function stopAutoRefresh() {
            if (esp8266Monitor && typeof esp8266Monitor.stopAutoRefresh === 'function') {
                esp8266Monitor.stopAutoRefresh();
            } else {
                console.log('Auto refresh stopped');
            }
        }

        // Send command to ESP8266
        function sendCommand(command) {
            if (!currentPort) {
                showAlert('warning', 'Please select a serial port first');
                return;
            }
            
            addLog('info', `Sending command: ${command}`);
            showLoading(`Sending ${command} command...`, 'Waiting for ESP8266 response');
            
            let url, body;
            
            if (command === 'STATUS') {
                url = '/iot/wifi-config/device-status';
                body = { port: currentPort };
            } else if (command === 'SCAN') {
                url = '/iot/wifi-config/scan-networks';
                body = { port: currentPort };
            } else if (command === 'SENSOR') {
                url = '/iot/wifi-config/sensor-data';
                body = { port: currentPort };
            } else {
                url = '/iot/wifi-config/send-serial';
                body = { 
                    port: currentPort, 
                    command: command,
                    ssid: '',
                    password: ''
                };
            }
            
            // Add timeout for command execution (reduced to 8 seconds to match backend)
            const timeoutPromise = new Promise((_, reject) => 
                setTimeout(() => reject(new Error('Command timeout after 8 seconds')), 8000)
            );
            
            const fetchPromise = fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(body)
            })
            .then(response => {
                console.log(`Command ${command} response:`, response.status);
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            });
            
            Promise.race([fetchPromise, timeoutPromise])
                .then(data => {
                    hideLoading();
                    console.log(`Command ${command} data:`, data);
                    
                    if (data.success) {
                        addLog('success', `Command ${command} executed successfully`);
                        updateConnectionHealth(true);
                        
                        if (command === 'STATUS') {
                            updateDeviceStatus(data.status);
                        } else if (command === 'SENSOR') {
                            updateSensorData(data.sensor_data);
                            if (data.message) {
                                addLog('info', data.message);
                            }
                        } else if (command === 'SCAN') {
                            if (data.networks && data.networks.length > 0) {
                                addLog('success', `Found ${data.networks.length} WiFi networks`);
                                displayWiFiNetworks(data.networks);
                            } else {
                                addLog('warning', 'No WiFi networks found');
                            }
                        }
                    } else {
                        addLog('error', `Command ${command} failed: ${data.message}`);
                        updateConnectionHealth(false);
                        showAlert('error', `Command ${command} failed: ${data.message}`);
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error(`Command ${command} error:`, error);
                    addLog('error', `Command ${command} error: ${error.message}`);
                    updateConnectionHealth(false);
                    showAlert('error', `Command ${command} error: ${error.message}`);
                })
                .finally(() => {
                    hideLoading(); // Ensure modal always closes
                });
        }

        // Start real-time updates
        function startRealTimeUpdates() {
            // Get initial status
            sendCommand('STATUS');
            
            // Update every 10 seconds (reduced frequency to prevent overwhelming)
            updateInterval = setInterval(() => {
                if (isConnected) {
                    // Only send commands if not already processing
                    if (!document.getElementById('loadingModal').classList.contains('show')) {
                        sendCommand('STATUS');
                        sendCommand('SENSOR');
                    }
                }
            }, 10000);
        }

        // Stop real-time updates
        function stopRealTimeUpdates() {
            if (updateInterval) {
                clearInterval(updateInterval);
                updateInterval = null;
            }
        }

        // Update device status display
        function updateDeviceStatus(status) {
            if (status) {
                document.getElementById('wifi-ssid').textContent = status.wifi_ssid || 'Not connected';
                document.getElementById('wifi-ip').textContent = status.ip_address || 'Not assigned';
                document.getElementById('wifi-signal').textContent = status.wifi_rssi ? `${status.wifi_rssi} dBm` : 'Unknown';
                document.getElementById('wifi-status-text').textContent = status.wifi_connected ? 'Connected' : 'Disconnected';
                
                if (status.system_info) {
                    document.getElementById('chip-id').textContent = status.system_info.chip_id || 'Unknown';
                    document.getElementById('free-heap').textContent = status.system_info.free_heap ? `${status.system_info.free_heap} bytes` : 'Unknown';
                    document.getElementById('uptime').textContent = status.system_info.uptime || 'Unknown';
                    document.getElementById('firmware').textContent = status.system_info.firmware_version || 'Unknown';
                }
                
                document.getElementById('last-update').textContent = new Date().toLocaleTimeString();
            }
        }

        // Update sensor data display
        function updateSensorData(sensorData) {
            if (sensorData) {
                // Update all 7 sensor indicators
                document.getElementById('soil-temperature').textContent = sensorData.soil_temperature || sensorData.temperature || '--';
                document.getElementById('soil-moisture').textContent = sensorData.soil_moisture || '--';
                document.getElementById('soil-conductivity').textContent = sensorData.soil_conductivity || '--';
                document.getElementById('soil-ph').textContent = sensorData.soil_ph || sensorData.ph_level || '--';
                document.getElementById('nitrogen').textContent = sensorData.nitrogen || '--';
                document.getElementById('phosphorus').textContent = sensorData.phosphorus || '--';
                document.getElementById('potassium').textContent = sensorData.potassium || '--';
                
                
                // Log sensor data for debugging
                console.log('Sensor data updated:', sensorData);
            }
        }

        // Display WiFi networks from scan
        function displayWiFiNetworks(networks) {
            const wifiStatusDiv = document.getElementById('wifi-status');
            
            // Create WiFi networks list
            let networksHtml = '<div class="mt-3"><h6>Available WiFi Networks:</h6>';
            networksHtml += '<div class="list-group" style="max-height: 200px; overflow-y: auto;">';
            
            networks.forEach(network => {
                const signalStrength = getSignalStrengthColor(network.rssi);
                const encryption = network.encryption ? '🔒' : '📡';
                
                networksHtml += `
                    <div class="list-group-item list-group-item-action">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">${network.ssid}</h6>
                                <small class="text-muted">Channel ${network.channel} ${encryption}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-${signalStrength}">${network.rssi} dBm</span>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            networksHtml += '</div></div>';
            
            // Add to WiFi status section
            wifiStatusDiv.innerHTML += networksHtml;
        }

        // Get signal strength color
        function getSignalStrengthColor(rssi) {
            if (rssi >= -50) return 'success';
            if (rssi >= -60) return 'info';
            if (rssi >= -70) return 'warning';
            return 'danger';
        }

        // Add log entry
        function addLog(type, message) {
            const logContainer = document.getElementById('communication-log');
            const timestamp = new Date().toLocaleTimeString();
            const logEntry = document.createElement('div');
            logEntry.className = 'log-entry';
            logEntry.innerHTML = `
                <span class="log-timestamp">[${timestamp}]</span>
                <span class="log-${type}">${message}</span>
            `;
            logContainer.appendChild(logEntry);
            logContainer.scrollTop = logContainer.scrollHeight;
        }

        // Clear log
        function clearLog() {
            document.getElementById('communication-log').innerHTML = `
                <div class="log-entry">
                    <span class="log-timestamp">[${new Date().toLocaleTimeString()}]</span>
                    <span class="log-info">Log cleared.</span>
                </div>
            `;
        }

        // Show loading modal
        function showLoading(text, subtext, showProgress = false) {
            document.getElementById('loadingText').textContent = text;
            document.getElementById('loadingSubtext').textContent = subtext;
            
            const stepsDiv = document.getElementById('loadingSteps');
            if (showProgress) {
                stepsDiv.classList.remove('d-none');
                updateLoadingProgress(0, 'Initializing...');
            } else {
                stepsDiv.classList.add('d-none');
            }
            
            const modalEl = document.getElementById('loadingModal');
            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.show();
        }

        function updateLoadingProgress(percent, stepText) {
            document.getElementById('loadingProgress').style.width = percent + '%';
            document.getElementById('loadingStep').textContent = stepText;
        }

        // Hide loading modal
        function hideLoading() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('loadingModal'));
            if (modal) modal.hide();
        }

        // Show alert
        function showAlert(type, message) {
            const alertClass = type === 'error' ? 'danger' : type;
            const alertId = 'alert-' + Date.now();
            const alertHtml = `
                <div id="${alertId}" class="alert alert-${alertClass} alert-dismissible fade show" role="alert">
                    <i class="fas fa-${getAlertIcon(type)} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            // Create temporary container for alert
            const alertContainer = document.createElement('div');
            alertContainer.innerHTML = alertHtml;
            document.body.insertBefore(alertContainer.firstElementChild, document.body.firstChild);
            
            // Auto-dismiss after 5 seconds with proper cleanup
            setTimeout(() => {
                const alert = document.getElementById(alertId);
                if (alert) {
                    try {
                        const alertInstance = bootstrap.Alert.getOrCreateInstance(alert);
                        alertInstance.close();
                    } catch (e) {
                        // Fallback: remove element directly
                        alert.remove();
                    }
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

        // Show result modal
        function showResultModal(type, title, message, ports = []) {
            const modal = new bootstrap.Modal(document.getElementById('resultModal'));
            const iconEl = document.getElementById('resultIcon');
            const titleEl = document.getElementById('resultTitle');
            const bodyEl = document.getElementById('resultBody');
            
            // Set icon and color based on type
            const icons = {
                success: 'fa-check-circle text-success',
                warning: 'fa-exclamation-triangle text-warning',
                error: 'fa-times-circle text-danger'
            };
            
            iconEl.className = 'fas ' + icons[type];
            titleEl.innerHTML = `<i class="${icons[type]} me-2"></i>${title}`;
            
            // Build body content
            let bodyContent = `<p class="mb-3">${message}</p>`;
            
            if (ports.length > 0) {
                bodyContent += '<div class="list-group">';
                ports.forEach(port => {
                    const compatible = port.is_esp8266_compatible;
                    const badge = compatible 
                        ? '<span class="badge bg-success">Compatible</span>' 
                        : '<span class="badge bg-secondary">Unknown</span>';
                    
                    bodyContent += `
                        <div class="list-group-item bg-secondary text-white mb-2 rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${port.port}</strong><br>
                                    <small class="text-muted">${port.device_name || 'Unknown Device'}</small>
                                </div>
                                ${badge}
                            </div>
                        </div>
                    `;
                });
                bodyContent += '</div>';
            }
            
            bodyEl.innerHTML = bodyContent;
            modal.show();
        }

    </script>
</body>
</html>
