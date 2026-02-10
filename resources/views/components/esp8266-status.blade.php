@extends('layouts.unified-layout')

@section('title', 'ESP8266 Status Monitor')

@section('content')
<div class="min-h-screen bg-gray-900 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Modern Header with Gradient -->
        <div class="bg-gradient-to-r from-cyan-600 to-blue-600 rounded-xl shadow-2xl p-6 mb-6">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-microchip text-2xl text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">ESP8266 Status Monitor</h1>
                        <p class="text-cyan-100 text-sm">Real-time NPK Sensor Monitoring & Control</p>
                    </div>
                </div>
                <div class="flex gap-2 flex-wrap">
                    <button onclick="detectPorts()" class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg font-medium transition-all flex items-center gap-2 backdrop-blur-sm">
                        <i class="fas fa-search"></i> Deteksi Port
                    </button>
                    <button onclick="autoDetectAndConnect()" class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg font-medium transition-all flex items-center gap-2 backdrop-blur-sm">
                        <i class="fas fa-magic"></i> Koneksi Otomatis
                    </button>
                    <button onclick="refreshSensorData()" class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg font-medium transition-all flex items-center gap-2 backdrop-blur-sm">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <button onclick="saveSensorData()" class="px-4 py-2 bg-green-600/80 hover:bg-green-500/80 text-white rounded-lg font-medium transition-all flex items-center gap-2 backdrop-blur-sm">
                        <i class="fas fa-save"></i> Simpan Data
                    </button>
                    <button onclick="downloadExcel()" class="px-4 py-2 bg-blue-600/80 hover:bg-blue-500/80 text-white rounded-lg font-medium transition-all flex items-center gap-2 backdrop-blur-sm">
                        <i class="fas fa-file-excel"></i> Download Excel
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Status Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gray-800 rounded-xl p-4 border border-gray-700 hover:border-cyan-500 transition-all hover:shadow-lg hover:shadow-cyan-500/20">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-wifi text-xl text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-400 text-xs font-medium uppercase">Status WiFi</p>
                        <p class="text-white text-lg font-bold" id="wifi-status">Terputus</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800 rounded-xl p-4 border border-gray-700 hover:border-green-500 transition-all hover:shadow-lg hover:shadow-green-500/20">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-plug text-xl text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-400 text-xs font-medium uppercase">Koneksi</p>
                        <p class="text-white text-lg font-bold" id="connection-status">Terputus</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800 rounded-xl p-4 border border-gray-700 hover:border-purple-500 transition-all hover:shadow-lg hover:shadow-purple-500/20">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-microchip text-xl text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-400 text-xs font-medium uppercase">Perangkat</p>
                        <p class="text-white text-lg font-bold" id="device-status">Offline</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-800 rounded-xl p-4 border border-gray-700 hover:border-orange-500 transition-all hover:shadow-lg hover:shadow-orange-500/20">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-thermometer-half text-xl text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-400 text-xs font-medium uppercase">Sensor</p>
                        <p class="text-white text-lg font-bold" id="sensor-count">7</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Connection & Controls -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Connection Panel -->
                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-plug text-cyan-400"></i>
                        Device Connection
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-300 mb-2 block">Serial Port</label>
                            <select id="portSelect" onchange="onPortSelected()" class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-lg text-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 outline-none transition-all">
                                <option value="">Select Port...</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium text-gray-300 mb-2 block">Baud Rate</label>
                            <select id="baudRateSelect" class="w-full px-3 py-2 bg-gray-900 border border-gray-600 rounded-lg text-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 outline-none transition-all">
                                <option value="9600">9600</option>
                                <option value="115200" selected>115200</option>
                            </select>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-2">
                            <button onclick="connectToDevice()" class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 text-white rounded-lg font-medium transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-plug"></i> Connect
                            </button>
                            <button onclick="disconnectDevice()" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-times"></i> Disconnect
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-2">
                            <button onclick="startAutoRefresh()" class="px-3 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-medium transition-all text-sm flex items-center justify-center gap-1">
                                <i class="fas fa-play"></i> Auto
                            </button>
                            <button onclick="stopAutoRefresh()" class="px-3 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-all text-sm flex items-center justify-center gap-1">
                                <i class="fas fa-stop"></i> Stop
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Console Output -->
                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-terminal text-green-400"></i>
                        Console
                    </h3>
                    <div id="consoleOutput" class="bg-gray-900 rounded-lg p-3 h-48 overflow-y-auto font-mono text-xs text-green-400 border border-gray-700">
                        <div>> ESP8266 Monitor initialized...</div>
                        <div>> Waiting for connection...</div>
                    </div>
                </div>

            </div>

            <!-- Right Column - Sensor Data -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- NPK Sensor Data -->
                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-seedling text-green-400"></i>
                        Data Sensor NPK
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gradient-to-br from-green-600/20 to-emerald-600/20 rounded-xl p-4 border border-green-500/30">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-green-400 font-medium text-sm">Nitrogen (N)</span>
                                <i class="fas fa-seedling text-green-400"></i>
                            </div>
                            <div class="text-3xl font-bold text-white" id="nitrogen-value">--</div>
                            <div class="text-xs text-gray-400 mt-1">mg/kg</div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-blue-600/20 to-cyan-600/20 rounded-xl p-4 border border-blue-500/30">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-blue-400 font-medium text-sm">Phosphorus (P)</span>
                                <i class="fas fa-flask text-blue-400"></i>
                            </div>
                            <div class="text-3xl font-bold text-white" id="phosphorus-value">--</div>
                            <div class="text-xs text-gray-400 mt-1">mg/kg</div>
                        </div>
                        
                        <div class="bg-gradient-to-br from-orange-600/20 to-red-600/20 rounded-xl p-4 border border-orange-500/30">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-orange-400 font-medium text-sm">Potassium (K)</span>
                                <i class="fas fa-leaf text-orange-400"></i>
                            </div>
                            <div class="text-3xl font-bold text-white" id="potassium-value">--</div>
                            <div class="text-xs text-gray-400 mt-1">mg/kg</div>
                        </div>
                    </div>
                </div>

                <!-- Environmental Sensors -->
                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-cloud text-cyan-400"></i>
                        Data Lingkungan
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div class="bg-gray-900 rounded-lg p-4 border border-gray-700">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-400 font-medium text-sm">Suhu</span>
                                <i class="fas fa-thermometer-half text-red-400"></i>
                            </div>
                            <div class="text-2xl font-bold text-white" id="temperature-value">--°C</div>
                        </div>
                        
                        <div class="bg-gray-900 rounded-lg p-4 border border-gray-700">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-400 font-medium text-sm">Kelembaban Tanah</span>
                                <i class="fas fa-seedling text-green-400"></i>
                            </div>
                            <div class="text-2xl font-bold text-white" id="moisture-value">--%</div>
                        </div>
                        
                        <div class="bg-gray-900 rounded-lg p-4 border border-gray-700">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-400 font-medium text-sm">Tingkat pH</span>
                                <i class="fas fa-vial text-purple-400"></i>
                            </div>
                            <div class="text-2xl font-bold text-white" id="ph-value">--</div>
                        </div>
                        
                        <div class="bg-gray-900 rounded-lg p-4 border border-gray-700">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-400 font-medium text-sm">Konduktivitas</span>
                                <i class="fas fa-bolt text-yellow-400"></i>
                            </div>
                            <div class="text-2xl font-bold text-white" id="conductivity-value">-- µS/cm</div>
                        </div>
                        
                        <div class="bg-gray-900 rounded-lg p-4 border border-gray-700">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-400 font-medium text-sm">Kelembaban Udara</span>
                                <i class="fas fa-tint text-blue-400"></i>
                            </div>
                            <div class="text-2xl font-bold text-white" id="humidity-value">--%</div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Riwayat Data Sensor -->
                <div class="mt-6 bg-gray-800 rounded-xl p-6 border border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <i class="fas fa-history text-cyan-400"></i>
                            Riwayat Data Sensor
                        </h3>
                        <span class="text-gray-400 text-sm" id="total-records">Total: 0 data</span>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-300">
                            <thead class="text-xs uppercase bg-gray-900 text-gray-400">
                                <tr>
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">Waktu</th>
                                    <th class="px-4 py-3">Suhu (°C)</th>
                                    <th class="px-4 py-3">Kel. Tanah (%)</th>
                                    <th class="px-4 py-3">pH</th>
                                    <th class="px-4 py-3">Konduktivitas (µS/cm)</th>
                                    <th class="px-4 py-3">N (mg/kg)</th>
                                    <th class="px-4 py-3">P (mg/kg)</th>
                                    <th class="px-4 py-3">K (mg/kg)</th>
                                </tr>
                            </thead>
                            <tbody id="sensor-history-table">
                                <tr>
                                    <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                        Belum ada data tersimpan
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="flex items-center justify-between mt-4">
                        <button onclick="loadPreviousPage()" id="prev-page-btn" class="px-4 py-2 bg-gray-700 text-white rounded-lg disabled:opacity-50" disabled>
                            <i class="fas fa-chevron-left"></i> Sebelumnya
                        </button>
                        <span class="text-gray-400" id="page-info">Halaman 1</span>
                        <button onclick="loadNextPage()" id="next-page-btn" class="px-4 py-2 bg-gray-700 text-white rounded-lg disabled:opacity-50" disabled>
                            Selanjutnya <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
// ESP8266 Monitor - Web Serial API Integration
class ESP8266Monitor {
    constructor() {
        this.port = null;
        this.reader = null;
        this.isConnected = false;
        this.baudRate = 115200;
        this.sensorData = {};
        this.deviceStatus = {};
        this.serialBuffer = ''; // Buffer untuk data serial yang terpotong
        this.currentPage = 1;
        this.perPage = 10;
        this.totalRecords = 0;
        this.initialize();
    }
    
    initialize() {
        this.addConsoleLog('ESP8266 Monitor initialized...');
        if (!('serial' in navigator)) {
            this.addConsoleLog('ERROR: Web Serial API not supported in this browser');
            this.addConsoleLog('Please use Chrome, Edge, or Opera browser');
            this.showBrowserWarning();
            return;
        }
        this.addConsoleLog('Web Serial API supported');
        this.addConsoleLog('Ready to detect ports');
        this.addConsoleLog('Instructions:');
        this.addConsoleLog('1. Click "Detect" to scan for ports');
        this.addConsoleLog('2. Select your ESP8266 port from dropdown');
        this.addConsoleLog('3. Click "Connect" to establish connection');
        this.addConsoleLog('4. Or click "Auto Connect" for automatic setup');
        this.addConsoleLog('');
        this.addConsoleLog('💡 Smart Connection:');
        this.addConsoleLog('→ Can work alongside Arduino IDE (close Serial Monitor only)');
        this.addConsoleLog('→ Auto-tries different baud rates if connection fails');
        this.addConsoleLog('→ Falls back to API-only mode if serial unavailable');
        this.startApiRefresh();
        
        // Load riwayat data sensor
        this.loadSensorHistory(1);
    }
    
    showBrowserWarning() {
        const warning = document.createElement('div');
        warning.className = 'fixed top-4 right-4 bg-red-600 text-white p-4 rounded-lg shadow-lg z-50';
        warning.innerHTML = '<div class="flex items-center gap-2"><i class="fas fa-exclamation-triangle"></i><span class="font-bold">Browser Not Supported</span></div><p class="text-sm mt-1">Web Serial API requires Chrome, Edge, or Opera browser</p>';
        document.body.appendChild(warning);
        setTimeout(() => warning.remove(), 10000);
    }
    
    showPortBusyHelp() {
        this.addConsoleLog('');
        this.addConsoleLog('=== TROUBLESHOOTING: Port Busy ===');
        this.addConsoleLog('The serial port is being used by another application.');
        this.addConsoleLog('');
        this.addConsoleLog('Common causes:');
        this.addConsoleLog('1. Another browser tab has the port open');
        this.addConsoleLog('2. Terminal serial monitor (screen, minicom, etc)');
        this.addConsoleLog('3. Python script or other program using the port');
        this.addConsoleLog('4. Arduino IDE Serial Monitor (exclusive access)');
        this.addConsoleLog('');
        this.addConsoleLog('Solutions:');
        this.addConsoleLog('→ Close other browser tabs with this page');
        this.addConsoleLog('→ Close Arduino IDE Serial Monitor (keep IDE open)');
        this.addConsoleLog('→ Run: lsof | grep usbserial (to find process)');
        this.addConsoleLog('→ Unplug and replug the ESP8266');
        this.addConsoleLog('→ Try different baud rate (9600 or 115200)');
        this.addConsoleLog('');
        this.addConsoleLog('Note: You can keep Arduino IDE open, just close Serial Monitor');
        this.addConsoleLog('After fixing, click "Connect" again');
        this.addConsoleLog('===================================');
    }
    
    async detectPorts() {
        this.addConsoleLog('=== DETECT PORTS ===');
        this.addConsoleLog('Scanning for available serial ports...');
        try {
            const ports = await navigator.serial.getPorts();
            this.addConsoleLog('Found ' + ports.length + ' previously authorized ports');
            
            const portSelect = document.getElementById('portSelect');
            portSelect.innerHTML = '<option value="">Select Port...</option>';
            
            if (ports.length === 0) {
                this.addConsoleLog('No ports found. Click "Auto Connect" to request new port');
                portSelect.innerHTML += '<option value="request">Request New Port...</option>';
            } else {
                ports.forEach((port, i) => {
                    const info = port.getInfo();
                    const portName = 'Port ' + (i + 1) + ' (USB)';
                    portSelect.innerHTML += '<option value="' + i + '">' + portName + '</option>';
                });
                this.addConsoleLog('Ports added to dropdown');
            }
            this.addConsoleLog('=== DETECT PORTS COMPLETE ===');
        } catch (error) {
            this.addConsoleLog('ERROR: ' + error.message);
        }
    }
    
    onPortSelected() {
        const val = document.getElementById('portSelect').value;
        if (val === 'request') this.requestNewPort();
    }
    
    async requestNewPort() {
        try {
            await navigator.serial.requestPort();
            await this.detectPorts();
        } catch (error) {
            this.addConsoleLog('Port request cancelled');
        }
    }
    
    async autoDetectAndConnect() {
        this.addConsoleLog('=== AUTO DETECT & CONNECT ===');
        try {
            await this.detectPorts();
            const ports = await navigator.serial.getPorts();
            this.addConsoleLog('Found ' + ports.length + ' ports');
            
            if (ports.length === 0) {
                this.addConsoleLog('No ports found. Requesting new port...');
                await this.requestNewPort();
                // After requesting, try to connect to the first port
                const newPorts = await navigator.serial.getPorts();
                if (newPorts.length > 0) {
                    this.addConsoleLog('New port authorized. Connecting...');
                    await this.connectToDevice(0);
                }
            } else {
                this.addConsoleLog('Connecting to first available port...');
                await this.connectToDevice(0);
            }
        } catch (error) {
            this.addConsoleLog('ERROR: ' + error.message);
        }
    }
    
    async connectToDevice(idx = null, retryCount = 0, baudRateOverride = null) {
        const MAX_RETRIES = 2;
        const BAUD_RATES = [115200, 9600, 57600, 38400];
        
        try {
            const ports = await navigator.serial.getPorts();
            this.addConsoleLog('Available ports: ' + ports.length);
            
            if (idx === null) {
                const portSelect = document.getElementById('portSelect');
                const selectedValue = portSelect.value;
                if (!selectedValue || selectedValue === '') {
                    throw new Error('Please select a port first');
                }
                if (selectedValue === 'request') {
                    throw new Error('Please request a new port first');
                }
                idx = parseInt(selectedValue);
            }
            
            if (isNaN(idx) || idx < 0 || idx >= ports.length) {
                throw new Error('Invalid port selection. Please detect ports again.');
            }
            
            this.port = ports[idx];
            if (!this.port) {
                throw new Error('Port object is null. Please detect ports again.');
            }
            
            // Use override baud rate or selected one
            this.baudRate = baudRateOverride || parseInt(document.getElementById('baudRateSelect').value);
            this.addConsoleLog('Opening port ' + (idx + 1) + ' at ' + this.baudRate + ' baud...');
            
            await this.port.open({baudRate: this.baudRate, dataBits: 8, stopBits: 1, parity: 'none'});
            this.reader = this.port.readable.getReader();
            this.writer = this.port.writable.getWriter();
            this.isConnected = true;
            this.addConsoleLog('✓ Connected successfully at ' + this.baudRate + ' baud');
            document.getElementById('connection-status').textContent = 'Connected';
            this.startReading();
        } catch (error) {
            // Handle specific error cases
            if (error.message.includes('Failed to open serial port')) {
                this.addConsoleLog('✗ ERROR: Port is busy or already in use');
                
                // Try different baud rates
                if (retryCount < BAUD_RATES.length) {
                    const nextBaudRate = BAUD_RATES[retryCount];
                    this.addConsoleLog('Trying different baud rate: ' + nextBaudRate);
                    setTimeout(() => {
                        this.connectToDevice(idx, retryCount + 1, nextBaudRate);
                    }, 1000);
                    return;
                }
                
                // Show detailed help on first attempt
                if (retryCount === 0) {
                    this.showPortBusyHelp();
                }
                
                // Final retry logic
                if (retryCount < MAX_RETRIES + BAUD_RATES.length) {
                    this.addConsoleLog('');
                    this.addConsoleLog('⟳ Retrying in 2 seconds... (Attempt ' + (retryCount + 2) + '/' + (MAX_RETRIES + BAUD_RATES.length + 1) + ')');
                    setTimeout(() => {
                        this.connectToDevice(idx, retryCount + 1);
                    }, 2000);
                } else {
                    this.addConsoleLog('');
                    this.addConsoleLog('✗ Connection failed after trying all baud rates');
                    this.addConsoleLog('');
                    this.addConsoleLog('→ Falling back to API-only mode');
                    this.addConsoleLog('→ You can still view WiFi status and device info from API');
                    this.addConsoleLog('→ Serial data monitoring is disabled');
                    this.addConsoleLog('');
                    this.addConsoleLog('To enable serial monitoring:');
                    this.addConsoleLog('1. Close Arduino IDE Serial Monitor (keep IDE open)');
                    this.addConsoleLog('2. Close other browser tabs with this page');
                    this.addConsoleLog('3. Click "Connect" button again');
                }
            } else {
                this.addConsoleLog('✗ ERROR: ' + error.message);
            }
            // Update connection status in UI
            document.getElementById('connection-status').textContent = 'Disconnected';
        }
    }
    
    async disconnectDevice() {
        try {
            this.isReading = false;
            
            if (this.reader) {
                try {
                    await this.reader.cancel();
                    await this.reader.releaseLock();
                } catch (e) {
                    // Reader might already be released
                }
                this.reader = null;
            }
            
            if (this.writer) {
                try {
                    await this.writer.releaseLock();
                } catch (e) {
                    // Writer might already be released
                }
                this.writer = null;
            }
            
            if (this.port) {
                try {
                    await this.port.close();
                    this.addConsoleLog('✓ Port closed successfully');
                } catch (e) {
                    if (e.message.includes('already closed')) {
                        this.addConsoleLog('Port was already closed');
                    } else {
                        throw e;
                    }
                }
                this.port = null;
            }
            
            this.isConnected = false;
            this.addConsoleLog('Disconnected');
            document.getElementById('connection-status').textContent = 'Disconnected';
        } catch (error) {
            this.addConsoleLog('ERROR during disconnect: ' + error.message);
            // Force cleanup
            this.isConnected = false;
            this.port = null;
            this.reader = null;
            this.writer = null;
            document.getElementById('connection-status').textContent = 'Disconnected';
        }
    }
    
    async startReading() {
        this.isReading = true;
        try {
            while (this.isReading && this.reader) {
                const {value, done} = await this.reader.read();
                if (done) break;
                const data = new TextDecoder().decode(value);
                this.addConsoleLog('RX: ' + data.trim());
                this.parseSerialData(data);
            }
        } catch (error) {
            if (this.isReading) this.addConsoleLog('ERROR: ' + error.message);
        }
    }
    
    parseSerialData(data) {
        // Tambahkan data ke buffer
        this.serialBuffer += data;
        
        // Cari JSON yang lengkap dalam buffer
        const jsonRegex = /\{[^{}]*"temperature"[^{}]*\}/g;
        const matches = this.serialBuffer.match(jsonRegex);
        
        if (matches) {
            for (const jsonStr of matches) {
                try {
                    // Bersihkan string dari karakter yang tidak perlu
                    const cleanJson = jsonStr.replace(/\n/g, '').replace(/\r/g, '');
                    const json = JSON.parse(cleanJson);
                    
                    // Update sensor data
                    this.sensorData = {
                        temperature: json.temperature !== undefined ? json.temperature : null,
                        humidity: json.humidity !== undefined ? json.humidity : null,
                        ph: json.ph !== undefined ? json.ph : null,
                        conductivity: json.conductivity !== undefined ? json.conductivity : null,
                        nitrogen: json.nitrogen !== undefined ? json.nitrogen : null,
                        phosphorus: json.phosphorus !== undefined ? json.phosphorus : null,
                        potassium: json.potassium !== undefined ? json.potassium : null
                    };
                    
                    this.addConsoleLog('✓ Sensor data parsed: T=' + this.sensorData.temperature + '°C, pH=' + this.sensorData.ph);
                    this.updateSensorUI();
                    
                    // Hapus JSON yang sudah diparse dari buffer
                    this.serialBuffer = this.serialBuffer.replace(jsonStr, '');
                } catch (e) {
                    this.addConsoleLog('Parse error: ' + e.message);
                }
            }
        }
        
        // Batasi ukuran buffer (keep last 2000 chars)
        if (this.serialBuffer.length > 2000) {
            this.serialBuffer = this.serialBuffer.slice(-2000);
        }
    }
    
    async refreshSensorData() {
        try {
            const res = await fetch('/api/iot/devices-status');
            const data = await res.json();
            if (data.devices && data.devices[0]) {
                const dev = data.devices[0];
                this.deviceStatus = {isOnline: dev.is_online, wifiSSID: dev.wifi_ssid};
                this.updateDeviceStatusUI();
            }
        } catch (error) {
            this.addConsoleLog('API Error: ' + error.message);
        }
    }
    
    startAutoRefresh() {
        this.addConsoleLog('Auto refresh started');
        this.autoRefreshInterval = setInterval(() => this.refreshSensorData(), 5000);
    }
    
    stopAutoRefresh() {
        if (this.autoRefreshInterval) {
            clearInterval(this.autoRefreshInterval);
            this.addConsoleLog('Auto refresh stopped');
        }
    }
    
    startApiRefresh() {
        this.refreshSensorData();
        setInterval(() => this.refreshSensorData(), 30000);
    }
    
    updateDeviceStatusUI() {
        const wifi = document.getElementById('wifi-status');
        if (wifi) wifi.textContent = this.deviceStatus.wifiSSID ? 'Connected' : 'Disconnected';
        const dev = document.getElementById('device-status');
        if (dev) dev.textContent = this.deviceStatus.isOnline ? 'Online' : 'Offline';
    }
    
    updateSensorUI() {
        // NPK Values
        const nitrogenEl = document.getElementById('nitrogen-value');
        if (nitrogenEl) {
            nitrogenEl.textContent = this.sensorData.nitrogen !== null && this.sensorData.nitrogen !== undefined ? this.sensorData.nitrogen : '--';
        }
        
        const phosphorusEl = document.getElementById('phosphorus-value');
        if (phosphorusEl) {
            phosphorusEl.textContent = this.sensorData.phosphorus !== null && this.sensorData.phosphorus !== undefined ? this.sensorData.phosphorus : '--';
        }
        
        const potassiumEl = document.getElementById('potassium-value');
        if (potassiumEl) {
            potassiumEl.textContent = this.sensorData.potassium !== null && this.sensorData.potassium !== undefined ? this.sensorData.potassium : '--';
        }
        
        // Environmental Data
        const temperatureEl = document.getElementById('temperature-value');
        if (temperatureEl) {
            if (this.sensorData.temperature !== null && this.sensorData.temperature !== undefined) {
                temperatureEl.textContent = this.sensorData.temperature.toFixed(1) + '°C';
            } else {
                temperatureEl.textContent = '--°C';
            }
        }
        
        // Kelembaban Tanah (soil moisture) - dari humidity Arduino
        const moistureEl = document.getElementById('moisture-value');
        if (moistureEl) {
            if (this.sensorData.humidity !== null && this.sensorData.humidity !== undefined) {
                moistureEl.textContent = this.sensorData.humidity.toFixed(1) + '%';
            } else {
                moistureEl.textContent = '--%';
            }
        }
        
        const phEl = document.getElementById('ph-value');
        if (phEl) {
            if (this.sensorData.ph !== null && this.sensorData.ph !== undefined) {
                phEl.textContent = this.sensorData.ph.toFixed(1);
            } else {
                phEl.textContent = '--';
            }
        }
        
        // Konduktivitas - field baru
        const conductivityEl = document.getElementById('conductivity-value');
        if (conductivityEl) {
            if (this.sensorData.conductivity !== null && this.sensorData.conductivity !== undefined) {
                conductivityEl.textContent = this.sensorData.conductivity.toFixed(0) + ' µS/cm';
            } else {
                conductivityEl.textContent = '-- µS/cm';
            }
        }
        
        // Kelembaban Udara - untuk saat ini sama dengan kelembaban tanah (karena Arduino hanya mengirim satu humidity)
        const humidityEl = document.getElementById('humidity-value');
        if (humidityEl) {
            if (this.sensorData.humidity !== null && this.sensorData.humidity !== undefined) {
                humidityEl.textContent = this.sensorData.humidity.toFixed(1) + '%';
            } else {
                humidityEl.textContent = '--%';
            }
        }
    }
    
    addConsoleLog(msg) {
        const con = document.getElementById('consoleOutput');
        if (con) {
            const div = document.createElement('div');
            div.innerHTML = '<span class="text-gray-500">[' + new Date().toLocaleTimeString() + ']</span> ' + msg;
            con.appendChild(div);
            con.scrollTop = con.scrollHeight;
            if (con.children.length > 100) con.removeChild(con.children[0]);
        }
        console.log('[ESP8266] ' + msg);
    }
    
    // Load riwayat data sensor dengan pagination
    async loadSensorHistory(page = 1) {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const headers = {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            };
            
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken;
            }
            
            const response = await fetch(`/api/iot/sensor-data?device_id=091334f0-a73e-11f0-8c95-7536037a85df&page=${page}&per_page=${this.perPage}`, {
                method: 'GET',
                headers: headers,
                credentials: 'same-origin'
            });
            
            if (response.ok) {
                const result = await response.json();
                this.updateHistoryTable(result.data, result.pagination);
                this.currentPage = page;
            } else {
                this.addConsoleLog('❌ Error memuat riwayat: ' + response.statusText);
                if (response.status === 401) {
                    this.addConsoleLog('❌ Authentication required. Please login.');
                }
            }
        } catch (error) {
            this.addConsoleLog('❌ Error memuat riwayat: ' + error.message);
        }
    }
    
    // Update tabel riwayat data
    updateHistoryTable(data, pagination) {
        const tbody = document.getElementById('sensor-history-table');
        const totalRecords = document.getElementById('total-records');
        const pageInfo = document.getElementById('page-info');
        const prevBtn = document.getElementById('prev-page-btn');
        const nextBtn = document.getElementById('next-page-btn');
        
        if (!data || data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="9" class="px-4 py-8 text-center text-gray-500">Belum ada data tersimpan</td></tr>';
            totalRecords.textContent = 'Total: 0 data';
            pageInfo.textContent = 'Halaman 1';
            prevBtn.disabled = true;
            nextBtn.disabled = true;
            return;
        }
        
        let html = '';
        data.forEach((item, index) => {
            const no = (pagination.current_page - 1) * this.perPage + index + 1;
            const date = new Date(item.recorded_at);
            const formattedDate = date.toLocaleString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            html += `
                <tr class="border-b border-gray-700 hover:bg-gray-700/50">
                    <td class="px-4 py-3">${no}</td>
                    <td class="px-4 py-3">${formattedDate}</td>
                    <td class="px-4 py-3">${item.temperature || '-'}</td>
                    <td class="px-4 py-3">${item.humidity || '-'}</td>
                    <td class="px-4 py-3">${item.ph || '-'}</td>
                    <td class="px-4 py-3">${item.conductivity || '-'}</td>
                    <td class="px-4 py-3">${item.nitrogen || '-'}</td>
                    <td class="px-4 py-3">${item.phosphorus || '-'}</td>
                    <td class="px-4 py-3">${item.potassium || '-'}</td>
                </tr>
            `;
        });
        
        tbody.innerHTML = html;
        totalRecords.textContent = `Total: ${pagination.total} data`;
        pageInfo.textContent = `Halaman ${pagination.current_page} dari ${pagination.last_page}`;
        
        prevBtn.disabled = pagination.current_page === 1;
        nextBtn.disabled = pagination.current_page === pagination.last_page;
    }
    
    // Simpan data sensor ke database
    async saveSensorData() {
        if (!this.sensorData.temperature && !this.sensorData.humidity && !this.sensorData.ph) {
            this.addConsoleLog('❌ Tidak ada data sensor untuk disimpan');
            return;
        }
        
        this.addConsoleLog('💾 Menyimpan data sensor...');
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            };
            
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken;
            }
            
            const response = await fetch('/esp8266/sensor-data', {
                method: 'POST',
                headers: headers,
                body: JSON.stringify({
                    device_id: '091334f0-a73e-11f0-8c95-7536037a85df',
                    temperature: this.sensorData.temperature,
                    humidity: this.sensorData.humidity,
                    ph: this.sensorData.ph,
                    conductivity: this.sensorData.conductivity,
                    nitrogen: this.sensorData.nitrogen,
                    phosphorus: this.sensorData.phosphorus,
                    potassium: this.sensorData.potassium,
                    recorded_at: new Date().toISOString()
                })
            });
            
            if (response.ok) {
                this.addConsoleLog('✅ Data sensor berhasil disimpan ke database');
                // Auto-refresh tabel riwayat
                await this.loadSensorHistory(this.currentPage);
            } else {
                this.addConsoleLog('❌ Gagal menyimpan data: ' + response.statusText);
            }
        } catch (error) {
            this.addConsoleLog('❌ Error menyimpan data: ' + error.message);
        }
    }
    
    // Download data dalam format Excel
    async downloadExcel() {
        this.addConsoleLog('📊 Mengunduh data Excel...');
        
        try {
            const response = await fetch('/esp8266/sensor-data/export?device_id=091334f0-a73e-11f0-8c95-7536037a85df', {
                method: 'GET',
                headers: {
                    'Accept': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                }
            });
            
            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'data_sensor_npk_' + new Date().toISOString().split('T')[0] + '.xlsx';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                
                this.addConsoleLog('✅ File Excel berhasil diunduh');
            } else {
                this.addConsoleLog('❌ Gagal mengunduh Excel: ' + response.statusText);
            }
        } catch (error) {
            this.addConsoleLog('❌ Error mengunduh Excel: ' + error.message);
        }
    }
    
    cleanup() {
        this.stopAutoRefresh();
        if (this.isConnected) this.disconnectDevice();
    }
}

// Global functions for ESP8266 monitor
var esp8266Monitor = null;

function detectPorts() {
    if (esp8266Monitor && typeof esp8266Monitor.detectPorts === 'function') {
        esp8266Monitor.detectPorts();
    } else {
        console.log('ESP8266 Monitor not initialized yet');
    }
}

function onPortSelected() {
    if (esp8266Monitor && typeof esp8266Monitor.onPortSelected === 'function') {
        esp8266Monitor.onPortSelected();
    }
}

function connectToDevice() {
    if (esp8266Monitor && typeof esp8266Monitor.connectToDevice === 'function') {
        esp8266Monitor.connectToDevice();
    }
}

function disconnectDevice() {
    if (esp8266Monitor && typeof esp8266Monitor.disconnectDevice === 'function') {
        esp8266Monitor.disconnectDevice();
    }
}

function refreshSensorData() {
    if (esp8266Monitor && typeof esp8266Monitor.refreshSensorData === 'function') {
        esp8266Monitor.refreshSensorData();
    }
}

function startAutoRefresh() {
    if (esp8266Monitor && typeof esp8266Monitor.startAutoRefresh === 'function') {
        esp8266Monitor.startAutoRefresh();
    }
}

function stopAutoRefresh() {
    if (esp8266Monitor && typeof esp8266Monitor.stopAutoRefresh === 'function') {
        esp8266Monitor.stopAutoRefresh();
    }
}

function saveSensorData() {
    if (esp8266Monitor && typeof esp8266Monitor.saveSensorData === 'function') {
        esp8266Monitor.saveSensorData();
    }
}

function downloadExcel() {
    if (esp8266Monitor && typeof esp8266Monitor.downloadExcel === 'function') {
        esp8266Monitor.downloadExcel();
    }
}

function loadPreviousPage() {
    if (esp8266Monitor && esp8266Monitor.currentPage > 1) {
        esp8266Monitor.loadSensorHistory(esp8266Monitor.currentPage - 1);
    }
}

function loadNextPage() {
    if (esp8266Monitor) {
        esp8266Monitor.loadSensorHistory(esp8266Monitor.currentPage + 1);
    }
}

function initializeESP8266Monitor() {
    if (typeof ESP8266Monitor !== 'undefined') {
        esp8266Monitor = new ESP8266Monitor();
    }
}

function autoDetectAndConnect() {
    if (esp8266Monitor && typeof esp8266Monitor.autoDetectAndConnect === 'function') {
        esp8266Monitor.autoDetectAndConnect();
    }
}


// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    esp8266Monitor = new ESP8266Monitor();
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (esp8266Monitor) {
        esp8266Monitor.cleanup();
    }
});
</script>
@endsection
