/**
 * IoT Debug Manager
 * Comprehensive debugging and testing tool for IoT devices
 * Supports USB, Bluetooth, and API testing
 */

class IoTDebugManager {
    constructor() {
        this.usbManager = null;
        this.bluetoothManager = null;
        this.isConnected = false;
        this.isMonitoring = false;
        this.currentDevice = null;
        this.debugLogs = [];
        this.dataHistory = [];
        
        // API endpoints
        this.apiBaseUrl = '/api/iot';
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        // Initialize managers
        this.initializeManagers();
    }

    /**
     * Initialize USB and Bluetooth managers
     */
    initializeManagers() {
        // Initialize USB Manager if available
        if (typeof USBIoTManager !== 'undefined') {
            this.usbManager = new USBIoTManager();
            this.setupUSBCallbacks();
        }

        // Initialize Bluetooth Manager if available
        if (typeof BluetoothIoT !== 'undefined') {
            this.bluetoothManager = new BluetoothIoT();
            this.setupBluetoothCallbacks();
        }

        this.log('info', 'Debug managers initialized');
    }

    /**
     * Setup USB manager callbacks
     */
    setupUSBCallbacks() {
        if (!this.usbManager) return;

        this.usbManager.onDataReceived = (data) => {
            this.handleDataReceived(data, 'USB');
        };

        this.usbManager.onConnectionChange = (connected, device) => {
            this.handleConnectionChange(connected, device, 'USB');
        };

        this.usbManager.onError = (message, error) => {
            this.handleError(message, error, 'USB');
        };

        this.usbManager.onStatusUpdate = (status) => {
            this.log('info', `USB Status: ${status}`);
        };
    }

    /**
     * Setup Bluetooth manager callbacks
     */
    setupBluetoothCallbacks() {
        if (!this.bluetoothManager) return;

        this.bluetoothManager.onDataReceived = (data) => {
            this.handleDataReceived(data, 'Bluetooth');
        };

        this.bluetoothManager.onConnectionChange = (connected, device) => {
            this.handleConnectionChange(connected, device, 'Bluetooth');
        };

        this.bluetoothManager.onError = (message, error) => {
            this.handleError(message, error, 'Bluetooth');
        };
    }

    /**
     * Scan for USB devices
     */
    async scanUSBDevices() {
        this.log('info', 'Scanning for USB devices...');
        
        if (!this.usbManager) {
            this.log('error', 'USB Manager not available. Web Serial API not supported.');
            return;
        }

        try {
            const result = await this.usbManager.testConnection();
            
            if (result.success) {
                this.log('success', `Found ${result.devices.length} USB devices`);
                this.updateDeviceList(result.devices, 'USB');
            } else {
                this.log('error', `USB scan failed: ${result.message}`);
            }
        } catch (error) {
            this.log('error', `USB scan error: ${error.message}`);
        }
    }

    /**
     * Scan for Bluetooth devices
     */
    async scanBluetoothDevices() {
        this.log('info', 'Scanning for Bluetooth devices...');
        
        if (!this.bluetoothManager) {
            this.log('error', 'Bluetooth Manager not available. Web Bluetooth API not supported.');
            return;
        }

        try {
            if (!this.bluetoothManager.isBluetoothAvailable()) {
                this.log('error', 'Bluetooth not available on this device');
                return;
            }

            // Request device
            const device = await this.bluetoothManager.requestDevice();
            this.log('success', `Bluetooth device found: ${device.name || 'Unknown'}`);
            this.updateDeviceList([device], 'Bluetooth');
            
        } catch (error) {
            this.log('error', `Bluetooth scan error: ${error.message}`);
        }
    }

    /**
     * Connect to device
     */
    async connectDevice() {
        this.log('info', 'Attempting to connect to device...');
        
        try {
            // Try USB first
            if (this.usbManager && this.usbManager.isSupported()) {
                await this.usbManager.connect();
                await this.usbManager.startReading();
                this.log('success', 'Connected via USB');
                return;
            }

            // Try Bluetooth
            if (this.bluetoothManager && this.bluetoothManager.isBluetoothAvailable()) {
                await this.bluetoothManager.connect();
                this.log('success', 'Connected via Bluetooth');
                return;
            }

            this.log('error', 'No connection method available');
            
        } catch (error) {
            this.log('error', `Connection failed: ${error.message}`);
        }
    }

    /**
     * Disconnect from device
     */
    async disconnectDevice() {
        this.log('info', 'Disconnecting device...');
        
        try {
            if (this.usbManager && this.usbManager.isConnected) {
                await this.usbManager.disconnect();
            }
            
            if (this.bluetoothManager && this.bluetoothManager.isConnected) {
                await this.bluetoothManager.disconnect();
            }
            
            this.isConnected = false;
            this.currentDevice = null;
            this.updateConnectionStatus(false);
            this.log('success', 'Device disconnected');
            
        } catch (error) {
            this.log('error', `Disconnect error: ${error.message}`);
        }
    }

    /**
     * Start data monitoring
     */
    startDataMonitoring() {
        this.log('info', 'Starting data monitoring...');
        this.isMonitoring = true;
        
        // Start monitoring if connected
        if (this.isConnected) {
            this.log('success', 'Data monitoring started');
        } else {
            this.log('warning', 'No device connected. Monitoring will start when device connects.');
        }
    }

    /**
     * Stop data monitoring
     */
    stopDataMonitoring() {
        this.log('info', 'Stopping data monitoring...');
        this.isMonitoring = false;
        this.log('success', 'Data monitoring stopped');
    }

    /**
     * Handle data received from device
     */
    handleDataReceived(data, source) {
        this.log('success', `Data received from ${source}: ${JSON.stringify(data)}`);
        
        // Add to data history
        this.dataHistory.push({
            timestamp: new Date(),
            source: source,
            data: data
        });
        
        // Update data monitor
        this.updateDataMonitor(data, source);
        
        // Send to API if monitoring is active
        if (this.isMonitoring) {
            this.sendDataToAPI(data);
        }
    }

    /**
     * Handle connection change
     */
    handleConnectionChange(connected, device, source) {
        this.isConnected = connected;
        this.currentDevice = device;
        
        this.updateConnectionStatus(connected);
        
        if (connected) {
            this.log('success', `Connected to ${source} device: ${device?.name || 'Unknown'}`);
        } else {
            this.log('warning', `Disconnected from ${source} device`);
        }
    }

    /**
     * Handle errors
     */
    handleError(message, error, source) {
        this.log('error', `${source} Error: ${message} - ${error?.message || error}`);
    }

    /**
     * Update connection status UI
     */
    updateConnectionStatus(connected) {
        const indicator = document.getElementById('connectionIndicator');
        const text = document.getElementById('connectionText');
        
        if (indicator && text) {
            if (connected) {
                indicator.className = 'status-indicator connected';
                text.textContent = 'Connected';
            } else {
                indicator.className = 'status-indicator';
                text.textContent = 'Disconnected';
            }
        }
    }

    /**
     * Update device list
     */
    updateDeviceList(devices, type) {
        const deviceList = document.getElementById('deviceList');
        if (!deviceList) return;

        if (devices.length === 0) {
            deviceList.innerHTML = '<div style="color: #6b7280; text-align: center; padding: 2rem;"><i class="fas fa-microchip" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>No devices found.</div>';
            return;
        }

        deviceList.innerHTML = devices.map(device => `
            <div class="device-item">
                <div class="device-info">
                    <h4>${device.name || device.info?.productName || 'Unknown Device'}</h4>
                    <p>${type} • ${device.id || 'No ID'}</p>
                </div>
                <div class="device-status ${this.isConnected ? 'online' : 'offline'}">
                    ${this.isConnected ? 'Online' : 'Offline'}
                </div>
            </div>
        `).join('');
    }

    /**
     * Update data monitor
     */
    updateDataMonitor(data, source) {
        const dataMonitor = document.getElementById('dataMonitor');
        if (!dataMonitor) return;

        const dataEntry = document.createElement('div');
        dataEntry.className = 'data-entry';
        dataEntry.innerHTML = `
            <div class="data-entry-header">
                [${new Date().toLocaleTimeString()}] ${source} Data
            </div>
            <div class="data-entry-content">${JSON.stringify(data, null, 2)}</div>
        `;

        dataMonitor.appendChild(dataEntry);
        dataMonitor.scrollTop = dataMonitor.scrollHeight;
    }

    /**
     * Send data to API
     */
    async sendDataToAPI(data) {
        try {
            const response = await fetch(`${this.apiBaseUrl}/sensor-data`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    ...data,
                    device_id: this.currentDevice?.id || 'debug-device',
                    kelas_id: 1,
                    user_id: window.userId || 1
                })
            });

            const result = await response.json();
            
            if (result.success) {
                this.log('success', 'Data sent to API successfully');
            } else {
                this.log('error', `API Error: ${result.message}`);
            }
        } catch (error) {
            this.log('error', `API Send Error: ${error.message}`);
        }
    }

    /**
     * Test Sensor Data API
     */
    async testSensorDataAPI(testData) {
        try {
            const data = JSON.parse(testData);
            this.log('info', 'Testing Sensor Data API...');
            
            const response = await fetch(`${this.apiBaseUrl}/sensor-data`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            
            if (result.success) {
                this.log('success', 'Sensor Data API test successful');
                this.log('info', `Response: ${JSON.stringify(result)}`);
            } else {
                this.log('error', `Sensor Data API test failed: ${result.message}`);
            }
        } catch (error) {
            this.log('error', `Sensor Data API test error: ${error.message}`);
        }
    }

    /**
     * Test Readings API
     */
    async testReadingsAPI(testData) {
        try {
            const data = JSON.parse(testData);
            this.log('info', 'Testing Readings API...');
            
            const response = await fetch(`${this.apiBaseUrl}/readings`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            
            if (result.success) {
                this.log('success', 'Readings API test successful');
                this.log('info', `Response: ${JSON.stringify(result)}`);
            } else {
                this.log('error', `Readings API test failed: ${result.message}`);
            }
        } catch (error) {
            this.log('error', `Readings API test error: ${error.message}`);
        }
    }

    /**
     * Send simulated data
     */
    async sendSimulatedData(data) {
        this.log('info', 'Sending simulated data...');
        await this.sendDataToAPI(data);
        this.updateDataMonitor(data, 'Simulation');
    }

    /**
     * Log message to console
     */
    log(type, message) {
        this.debugLogs.push({
            timestamp: new Date(),
            type: type,
            message: message
        });

        // Call global log function if available
        if (typeof logMessage === 'function') {
            logMessage(type, message);
        }
    }

    /**
     * Get connection status
     */
    getConnectionStatus() {
        return {
            connected: this.isConnected,
            monitoring: this.isMonitoring,
            device: this.currentDevice,
            usbAvailable: this.usbManager?.isSupported() || false,
            bluetoothAvailable: this.bluetoothManager?.isBluetoothAvailable() || false
        };
    }

    /**
     * Get debug logs
     */
    getDebugLogs() {
        return this.debugLogs;
    }

    /**
     * Get data history
     */
    getDataHistory() {
        return this.dataHistory;
    }

    /**
     * Clear all data
     */
    clearAllData() {
        this.debugLogs = [];
        this.dataHistory = [];
        this.log('info', 'All debug data cleared');
    }

    /**
     * Export debug data
     */
    exportDebugData() {
        const debugData = {
            timestamp: new Date().toISOString(),
            connectionStatus: this.getConnectionStatus(),
            logs: this.debugLogs,
            dataHistory: this.dataHistory
        };

        const blob = new Blob([JSON.stringify(debugData, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `iot-debug-data-${new Date().toISOString().slice(0, 19)}.json`;
        a.click();
        URL.revokeObjectURL(url);
        
        this.log('success', 'Debug data exported');
    }
}

// Export for global use
window.IoTDebugManager = IoTDebugManager;
