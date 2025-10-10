@extends('layouts.unified-layout')

@section('title', 'IoT Debug & Testing - Terra Assessment')

@section('styles')
<style>
.debug-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1rem;
}

.debug-header {
    background: linear-gradient(135deg, #1e293b, #334155);
    border-radius: 1rem;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid #475569;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

.debug-title {
    color: #ffffff;
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.debug-subtitle {
    color: #cbd5e1;
    font-size: 1.1rem;
    margin-bottom: 0;
}

.debug-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.debug-panel {
    background: #1e293b;
    border-radius: 1rem;
    padding: 1.5rem;
    border: 1px solid #334155;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.panel-title {
    color: #ffffff;
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.panel-title i {
    color: #3b82f6;
    font-size: 1.5rem;
}

.connection-status {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
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

.status-indicator.connecting {
    background: #f59e0b;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.status-text {
    color: #cbd5e1;
    font-weight: 500;
}

.debug-buttons {
    display: flex;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.debug-btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.debug-btn-primary {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: #ffffff;
}

.debug-btn-primary:hover {
    background: linear-gradient(135deg, #2563eb, #1e40af);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.debug-btn-success {
    background: linear-gradient(135deg, #10b981, #059669);
    color: #ffffff;
}

.debug-btn-success:hover {
    background: linear-gradient(135deg, #059669, #047857);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.debug-btn-warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #ffffff;
}

.debug-btn-warning:hover {
    background: linear-gradient(135deg, #d97706, #b45309);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.debug-btn-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #ffffff;
}

.debug-btn-danger:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.debug-btn-secondary {
    background: #475569;
    color: #ffffff;
}

.debug-btn-secondary:hover {
    background: #64748b;
    transform: translateY(-2px);
}

.console-log {
    background: #0f172a;
    border: 1px solid #334155;
    border-radius: 8px;
    padding: 1rem;
    height: 300px;
    overflow-y: auto;
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
    line-height: 1.5;
}

.log-entry {
    margin-bottom: 0.5rem;
    padding: 0.25rem 0;
    border-left: 3px solid transparent;
    padding-left: 0.75rem;
}

.log-entry.info {
    color: #60a5fa;
    border-left-color: #3b82f6;
}

.log-entry.success {
    color: #34d399;
    border-left-color: #10b981;
}

.log-entry.warning {
    color: #fbbf24;
    border-left-color: #f59e0b;
}

.log-entry.error {
    color: #f87171;
    border-left-color: #ef4444;
}

.log-timestamp {
    color: #6b7280;
    font-size: 0.75rem;
}

.data-monitor {
    background: #0f172a;
    border: 1px solid #334155;
    border-radius: 8px;
    padding: 1rem;
    height: 300px;
    overflow-y: auto;
}

.data-entry {
    background: #1e293b;
    border: 1px solid #334155;
    border-radius: 6px;
    padding: 0.75rem;
    margin-bottom: 0.5rem;
    font-family: 'Courier New', monospace;
    font-size: 0.8rem;
}

.data-entry-header {
    color: #3b82f6;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.data-entry-content {
    color: #cbd5e1;
    white-space: pre-wrap;
}

.device-list {
    background: #0f172a;
    border: 1px solid #334155;
    border-radius: 8px;
    padding: 1rem;
    height: 300px;
    overflow-y: auto;
}

.device-item {
    background: #1e293b;
    border: 1px solid #334155;
    border-radius: 6px;
    padding: 0.75rem;
    margin-bottom: 0.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.device-info h4 {
    color: #ffffff;
    margin: 0 0 0.25rem 0;
    font-size: 0.9rem;
}

.device-info p {
    color: #94a3b8;
    margin: 0;
    font-size: 0.8rem;
}

.device-status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.device-status.online {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.device-status.offline {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.device-status.connecting {
    background: rgba(245, 158, 11, 0.2);
    color: #f59e0b;
    border: 1px solid rgba(245, 158, 11, 0.3);
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    color: #ffffff;
    font-weight: 600;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    background: #1e293b;
    border: 1px solid #334155;
    border-radius: 6px;
    color: #ffffff;
    font-size: 0.9rem;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.full-width {
    grid-column: 1 / -1;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: #1e293b;
    border: 1px solid #334155;
    border-radius: 8px;
    padding: 1.5rem;
    text-align: center;
}

.stat-number {
    color: #3b82f6;
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #cbd5e1;
    font-size: 0.9rem;
    font-weight: 500;
}

@media (max-width: 768px) {
    .debug-grid {
        grid-template-columns: 1fr;
    }
    
    .debug-buttons {
        flex-direction: column;
    }
    
    .debug-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection

@section('content')
<div class="debug-container">
    <!-- Header -->
    <div class="debug-header">
        <h1 class="debug-title">
            <i class="fas fa-bug"></i>
            IoT Debug & Testing Center
        </h1>
        <p class="debug-subtitle">
            Real-time monitoring, testing, dan debugging untuk sistem IoT Terra Assessment
        </p>
    </div>

    <!-- Stats Overview -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $devices->count() }}</div>
            <div class="stat-label">Total Devices</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $devices->where('status', 'online')->count() }}</div>
            <div class="stat-label">Online Devices</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $recentSensorData->count() }}</div>
            <div class="stat-label">Recent Sensor Data</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $recentReadings->count() }}</div>
            <div class="stat-label">Recent Readings</div>
        </div>
    </div>

    <!-- Main Debug Grid -->
    <div class="debug-grid">
        <!-- Connection Status Panel -->
        <div class="debug-panel">
            <h3 class="panel-title">
                <i class="fas fa-wifi"></i>
                Connection Status
            </h3>
            
            <div class="connection-status">
                <div class="status-indicator" id="connectionIndicator"></div>
                <span class="status-text" id="connectionText">Disconnected</span>
            </div>
            
            <div class="debug-buttons">
                <button class="debug-btn debug-btn-primary" onclick="scanUSBDevices()">
                    <i class="fas fa-usb"></i>
                    Scan USB
                </button>
                <button class="debug-btn debug-btn-primary" onclick="scanBluetoothDevices()">
                    <i class="fas fa-bluetooth"></i>
                    Scan Bluetooth
                </button>
                <button class="debug-btn debug-btn-success" onclick="connectDevice()">
                    <i class="fas fa-link"></i>
                    Connect
                </button>
                <button class="debug-btn debug-btn-danger" onclick="disconnectDevice()">
                    <i class="fas fa-unlink"></i>
                    Disconnect
                </button>
            </div>
            
            <div class="device-list" id="deviceList">
                <div style="color: #6b7280; text-align: center; padding: 2rem;">
                    <i class="fas fa-microchip" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                    No devices found. Click "Scan USB" or "Scan Bluetooth" to search for devices.
                </div>
            </div>
        </div>

        <!-- Console Log Panel -->
        <div class="debug-panel">
            <h3 class="panel-title">
                <i class="fas fa-terminal"></i>
                Console Log
            </h3>
            
            <div class="debug-buttons">
                <button class="debug-btn debug-btn-secondary" onclick="clearLogs()">
                    <i class="fas fa-trash"></i>
                    Clear Logs
                </button>
                <button class="debug-btn debug-btn-secondary" onclick="exportLogs()">
                    <i class="fas fa-download"></i>
                    Export Logs
                </button>
                <button class="debug-btn debug-btn-warning" onclick="toggleAutoScroll()">
                    <i class="fas fa-arrows-alt-v"></i>
                    Auto Scroll
                </button>
            </div>
            
            <div class="console-log" id="consoleLog">
                <div class="log-entry info">
                    <span class="log-timestamp">[{{ now()->format('H:i:s') }}]</span>
                    IoT Debug Console initialized
                </div>
            </div>
        </div>
    </div>

    <!-- Data Monitoring Grid -->
    <div class="debug-grid">
        <!-- Raw Data Monitor -->
        <div class="debug-panel">
            <h3 class="panel-title">
                <i class="fas fa-database"></i>
                Raw Data Monitor
            </h3>
            
            <div class="debug-buttons">
                <button class="debug-btn debug-btn-success" onclick="startDataMonitoring()">
                    <i class="fas fa-play"></i>
                    Start Monitoring
                </button>
                <button class="debug-btn debug-btn-danger" onclick="stopDataMonitoring()">
                    <i class="fas fa-stop"></i>
                    Stop Monitoring
                </button>
                <button class="debug-btn debug-btn-secondary" onclick="clearDataMonitor()">
                    <i class="fas fa-eraser"></i>
                    Clear Data
                </button>
            </div>
            
            <div class="data-monitor" id="dataMonitor">
                <div style="color: #6b7280; text-align: center; padding: 2rem;">
                    <i class="fas fa-chart-line" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                    Data monitoring not started. Click "Start Monitoring" to begin.
                </div>
            </div>
        </div>

        <!-- API Tester -->
        <div class="debug-panel">
            <h3 class="panel-title">
                <i class="fas fa-code"></i>
                API Tester
            </h3>
            
            <div class="form-group">
                <label>Test Data (JSON)</label>
                <textarea id="testData" rows="8" placeholder='{
  "device_id": "test-device-001",
  "temperature": 25.5,
  "humidity": 60.0,
  "soil_moisture": 45.0,
  "ph_level": 6.5,
  "nutrient_level": 120,
  "kelas_id": 1,
  "location": "Test Location",
  "notes": "Debug test data"
}'></textarea>
            </div>
            
            <div class="debug-buttons">
                <button class="debug-btn debug-btn-primary" onclick="testSensorDataAPI()">
                    <i class="fas fa-paper-plane"></i>
                    Test Sensor Data API
                </button>
                <button class="debug-btn debug-btn-primary" onclick="testReadingsAPI()">
                    <i class="fas fa-paper-plane"></i>
                    Test Readings API
                </button>
                <button class="debug-btn debug-btn-warning" onclick="generateTestData()">
                    <i class="fas fa-magic"></i>
                    Generate Test Data
                </button>
            </div>
        </div>
    </div>

    <!-- Device Simulator -->
    <div class="debug-panel full-width">
        <h3 class="panel-title">
            <i class="fas fa-robot"></i>
            Device Simulator
        </h3>
        
        <div class="debug-grid">
            <div>
                <div class="form-group">
                    <label>Simulation Settings</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <input type="number" id="simInterval" placeholder="Interval (ms)" value="2000">
                        <input type="number" id="simCount" placeholder="Count (0 = infinite)" value="0">
                    </div>
                </div>
                
                <div class="debug-buttons">
                    <button class="debug-btn debug-btn-success" onclick="startSimulation()">
                        <i class="fas fa-play"></i>
                        Start Simulation
                    </button>
                    <button class="debug-btn debug-btn-danger" onclick="stopSimulation()">
                        <i class="fas fa-stop"></i>
                        Stop Simulation
                    </button>
                    <button class="debug-btn debug-btn-warning" onclick="sendSingleData()">
                        <i class="fas fa-paper-plane"></i>
                        Send Single Data
                    </button>
                </div>
            </div>
            
            <div>
                <div class="form-group">
                    <label>Data Range Settings</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; font-size: 0.8rem;">
                        <div>
                            <label>Temperature: <span id="tempRange">15-35°C</span></label>
                            <input type="range" id="tempMin" min="10" max="40" value="15" oninput="updateRange('temp')">
                            <input type="range" id="tempMax" min="10" max="40" value="35" oninput="updateRange('temp')">
                        </div>
                        <div>
                            <label>Humidity: <span id="humidityRange">30-80%</span></label>
                            <input type="range" id="humidityMin" min="0" max="100" value="30" oninput="updateRange('humidity')">
                            <input type="range" id="humidityMax" min="0" max="100" value="80" oninput="updateRange('humidity')">
                        </div>
                        <div>
                            <label>Soil Moisture: <span id="moistureRange">20-70%</span></label>
                            <input type="range" id="moistureMin" min="0" max="100" value="20" oninput="updateRange('moisture')">
                            <input type="range" id="moistureMax" min="0" max="100" value="70" oninput="updateRange('moisture')">
                        </div>
                        <div>
                            <label>pH Level: <span id="phRange">5.0-8.0</span></label>
                            <input type="range" id="phMin" min="0" max="14" step="0.1" value="5.0" oninput="updateRange('ph')">
                            <input type="range" id="phMax" min="0" max="14" step="0.1" value="8.0" oninput="updateRange('ph')">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('asset/js/iot-debug-manager.js') }}"></script>
<script>
// Global variables
let isConnected = false;
let isMonitoring = false;
let isSimulating = false;
let simulationInterval = null;
let autoScroll = true;

// Initialize debug manager
document.addEventListener('DOMContentLoaded', function() {
    window.iotDebugManager = new IoTDebugManager();
    logMessage('info', 'IoT Debug Manager initialized');
});

// Connection functions
function scanUSBDevices() {
    logMessage('info', 'Scanning for USB devices...');
    if (window.iotDebugManager) {
        window.iotDebugManager.scanUSBDevices();
    }
}

function scanBluetoothDevices() {
    logMessage('info', 'Scanning for Bluetooth devices...');
    if (window.iotDebugManager) {
        window.iotDebugManager.scanBluetoothDevices();
    }
}

function connectDevice() {
    logMessage('info', 'Attempting to connect to device...');
    if (window.iotDebugManager) {
        window.iotDebugManager.connectDevice();
    }
}

function disconnectDevice() {
    logMessage('info', 'Disconnecting device...');
    if (window.iotDebugManager) {
        window.iotDebugManager.disconnectDevice();
    }
}

// Data monitoring functions
function startDataMonitoring() {
    logMessage('info', 'Starting data monitoring...');
    isMonitoring = true;
    if (window.iotDebugManager) {
        window.iotDebugManager.startDataMonitoring();
    }
}

function stopDataMonitoring() {
    logMessage('info', 'Stopping data monitoring...');
    isMonitoring = false;
    if (window.iotDebugManager) {
        window.iotDebugManager.stopDataMonitoring();
    }
}

function clearDataMonitor() {
    document.getElementById('dataMonitor').innerHTML = '<div style="color: #6b7280; text-align: center; padding: 2rem;"><i class="fas fa-chart-line" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>Data monitoring cleared.</div>';
    logMessage('info', 'Data monitor cleared');
}

// API testing functions
function testSensorDataAPI() {
    const testData = document.getElementById('testData').value;
    logMessage('info', 'Testing Sensor Data API...');
    if (window.iotDebugManager) {
        window.iotDebugManager.testSensorDataAPI(testData);
    }
}

function testReadingsAPI() {
    const testData = document.getElementById('testData').value;
    logMessage('info', 'Testing Readings API...');
    if (window.iotDebugManager) {
        window.iotDebugManager.testReadingsAPI(testData);
    }
}

function generateTestData() {
    const testData = {
        device_id: "debug-device-" + Date.now(),
        temperature: (Math.random() * 20 + 15).toFixed(1),
        humidity: (Math.random() * 50 + 30).toFixed(1),
        soil_moisture: (Math.random() * 50 + 20).toFixed(1),
        ph_level: (Math.random() * 3 + 5).toFixed(1),
        nutrient_level: Math.floor(Math.random() * 200 + 50),
        kelas_id: 1,
        location: "Debug Test Location",
        notes: "Generated test data for debugging"
    };
    
    document.getElementById('testData').value = JSON.stringify(testData, null, 2);
    logMessage('success', 'Test data generated');
}

// Simulation functions
function startSimulation() {
    const interval = parseInt(document.getElementById('simInterval').value) || 2000;
    const count = parseInt(document.getElementById('simCount').value) || 0;
    
    logMessage('info', `Starting simulation with ${interval}ms interval`);
    isSimulating = true;
    
    let sentCount = 0;
    simulationInterval = setInterval(() => {
        if (count > 0 && sentCount >= count) {
            stopSimulation();
            return;
        }
        
        sendSingleData();
        sentCount++;
    }, interval);
}

function stopSimulation() {
    if (simulationInterval) {
        clearInterval(simulationInterval);
        simulationInterval = null;
    }
    isSimulating = false;
    logMessage('info', 'Simulation stopped');
}

function sendSingleData() {
    const data = generateSimulatedData();
    logMessage('info', 'Sending simulated data: ' + JSON.stringify(data));
    
    if (window.iotDebugManager) {
        window.iotDebugManager.sendSimulatedData(data);
    }
}

function generateSimulatedData() {
    const tempMin = parseFloat(document.getElementById('tempMin').value);
    const tempMax = parseFloat(document.getElementById('tempMax').value);
    const humidityMin = parseFloat(document.getElementById('humidityMin').value);
    const humidityMax = parseFloat(document.getElementById('humidityMax').value);
    const moistureMin = parseFloat(document.getElementById('moistureMin').value);
    const moistureMax = parseFloat(document.getElementById('moistureMax').value);
    const phMin = parseFloat(document.getElementById('phMin').value);
    const phMax = parseFloat(document.getElementById('phMax').value);
    
    return {
        device_id: "sim-device-" + Date.now(),
        temperature: (Math.random() * (tempMax - tempMin) + tempMin).toFixed(1),
        humidity: (Math.random() * (humidityMax - humidityMin) + humidityMin).toFixed(1),
        soil_moisture: (Math.random() * (moistureMax - moistureMin) + moistureMin).toFixed(1),
        ph_level: (Math.random() * (phMax - phMin) + phMin).toFixed(1),
        nutrient_level: Math.floor(Math.random() * 200 + 50),
        kelas_id: 1,
        location: "Simulated Location",
        notes: "Simulated data for testing",
        timestamp: new Date().toISOString()
    };
}

// Utility functions
function logMessage(type, message) {
    const consoleLog = document.getElementById('consoleLog');
    const timestamp = new Date().toLocaleTimeString();
    
    const logEntry = document.createElement('div');
    logEntry.className = `log-entry ${type}`;
    logEntry.innerHTML = `<span class="log-timestamp">[${timestamp}]</span> ${message}`;
    
    consoleLog.appendChild(logEntry);
    
    if (autoScroll) {
        consoleLog.scrollTop = consoleLog.scrollHeight;
    }
}

function clearLogs() {
    document.getElementById('consoleLog').innerHTML = '';
    logMessage('info', 'Console logs cleared');
}

function exportLogs() {
    const logs = document.getElementById('consoleLog').innerText;
    const blob = new Blob([logs], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `iot-debug-logs-${new Date().toISOString().slice(0, 19)}.txt`;
    a.click();
    URL.revokeObjectURL(url);
    logMessage('success', 'Logs exported');
}

function toggleAutoScroll() {
    autoScroll = !autoScroll;
    logMessage('info', `Auto scroll ${autoScroll ? 'enabled' : 'disabled'}`);
}

function updateRange(type) {
    const min = document.getElementById(`${type}Min`).value;
    const max = document.getElementById(`${type}Max`).value;
    const rangeElement = document.getElementById(`${type}Range`);
    
    if (type === 'temp') {
        rangeElement.textContent = `${min}-${max}°C`;
    } else if (type === 'humidity' || type === 'moisture') {
        rangeElement.textContent = `${min}-${max}%`;
    } else if (type === 'ph') {
        rangeElement.textContent = `${min}-${max}`;
    }
}
</script>
@endpush
