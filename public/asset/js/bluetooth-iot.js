/**
 * Cross-Platform Bluetooth IoT Connection
 * Supports: Chrome, Edge, Safari, Firefox, Mobile browsers
 */

class BluetoothIoT {
    constructor() {
        this.device = null;
        this.server = null;
        this.service = null;
        this.characteristic = null;
        this.isConnected = false;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        this.reconnectInterval = 5000; // 5 seconds
        
        // Service UUID for IoT soil sensor
        this.serviceUUID = '12345678-1234-1234-1234-123456789abc';
        // Characteristic UUID for sensor data
        this.characteristicUUID = '87654321-4321-4321-4321-cba987654321';
        
        this.onDataReceived = null;
        this.onConnectionChange = null;
        this.onError = null;
    }

    /**
     * Check if Bluetooth is available
     */
    isBluetoothAvailable() {
        if (!navigator.bluetooth) {
            console.error('Bluetooth API not supported');
            return false;
        }
        return true;
    }

    /**
     * Request Bluetooth device
     */
    async requestDevice() {
        if (!this.isBluetoothAvailable()) {
            throw new Error('Bluetooth tidak didukung di browser ini');
        }

        try {
            // Check if device is already connected
            if (this.isConnected) {
                return this.device;
            }

            // Request device with filters
            this.device = await navigator.bluetooth.requestDevice({
                filters: [
                    { name: 'IoT Soil Sensor' },
                    { namePrefix: 'IoT' },
                    { namePrefix: 'Soil' }
                ],
                optionalServices: [this.serviceUUID]
            });

            // Add event listeners
            this.device.addEventListener('gattserverdisconnected', () => {
                this.handleDisconnection();
            });

            return this.device;
        } catch (error) {
            console.error('Error requesting device:', error);
            throw this.handleBluetoothError(error);
        }
    }

    /**
     * Connect to Bluetooth device
     */
    async connect() {
        try {
            if (!this.device) {
                await this.requestDevice();
            }

            console.log('Connecting to device:', this.device.name);
            
            // Connect to GATT server
            this.server = await this.device.gatt.connect();
            console.log('Connected to GATT server');

            // Get the service
            this.service = await this.server.getPrimaryService(this.serviceUUID);
            console.log('Got service');

            // Get the characteristic
            this.characteristic = await this.service.getCharacteristic(this.characteristicUUID);
            console.log('Got characteristic');

            // Start notifications
            await this.characteristic.startNotifications();
            console.log('Started notifications');

            // Add event listener for data
            this.characteristic.addEventListener('characteristicvaluechanged', (event) => {
                this.handleDataReceived(event);
            });

            this.isConnected = true;
            this.reconnectAttempts = 0;

            if (this.onConnectionChange) {
                this.onConnectionChange(true, this.device);
            }

            console.log('Successfully connected to IoT device');
            return true;

        } catch (error) {
            console.error('Connection failed:', error);
            this.isConnected = false;
            
            if (this.onConnectionChange) {
                this.onConnectionChange(false, null);
            }
            
            throw this.handleBluetoothError(error);
        }
    }

    /**
     * Disconnect from device
     */
    async disconnect() {
        try {
            if (this.device && this.device.gatt.connected) {
                this.device.gatt.disconnect();
            }
            
            this.isConnected = false;
            this.device = null;
            this.server = null;
            this.service = null;
            this.characteristic = null;

            if (this.onConnectionChange) {
                this.onConnectionChange(false, null);
            }

            console.log('Disconnected from IoT device');
        } catch (error) {
            console.error('Error disconnecting:', error);
        }
    }

    /**
     * Handle data received from device
     */
    handleDataReceived(event) {
        try {
            const value = event.target.value;
            const data = this.parseSensorData(value);
            
            console.log('Received sensor data:', data);
            
            if (this.onDataReceived) {
                this.onDataReceived(data);
            }
        } catch (error) {
            console.error('Error parsing sensor data:', error);
        }
    }

    /**
     * Parse sensor data from Bluetooth characteristic
     */
    parseSensorData(value) {
        try {
            // Convert ArrayBuffer to string
            const decoder = new TextDecoder();
            const dataString = decoder.decode(value);
            
            // Parse JSON data
            const data = JSON.parse(dataString);
            
            // Validate required fields
            if (!data.temperature || !data.humidity || !data.soil_moisture) {
                throw new Error('Invalid sensor data format');
            }
            
            return {
                temperature: parseFloat(data.temperature),
                humidity: parseFloat(data.humidity),
                soil_moisture: parseFloat(data.soil_moisture),
                ph_level: data.ph_level ? parseFloat(data.ph_level) : null,
                nutrient_level: data.nutrient_level ? parseFloat(data.nutrient_level) : null,
                timestamp: new Date().toISOString(),
                raw_data: data
            };
        } catch (error) {
            console.error('Error parsing sensor data:', error);
            throw new Error('Failed to parse sensor data');
        }
    }

    /**
     * Handle disconnection
     */
    handleDisconnection() {
        console.log('Device disconnected');
        this.isConnected = false;
        
        if (this.onConnectionChange) {
            this.onConnectionChange(false, null);
        }
        
        // Attempt to reconnect
        if (this.reconnectAttempts < this.maxReconnectAttempts) {
            this.reconnectAttempts++;
            console.log(`Attempting to reconnect... (${this.reconnectAttempts}/${this.maxReconnectAttempts})`);
            
            setTimeout(() => {
                this.connect().catch(error => {
                    console.error('Reconnection failed:', error);
                });
            }, this.reconnectInterval);
        } else {
            console.log('Max reconnection attempts reached');
        }
    }

    /**
     * Handle Bluetooth errors
     */
    handleBluetoothError(error) {
        let message = 'Terjadi kesalahan Bluetooth';
        
        switch (error.name) {
            case 'NotFoundError':
                message = 'Perangkat Bluetooth tidak ditemukan';
                break;
            case 'SecurityError':
                message = 'Akses Bluetooth ditolak. Silakan izinkan akses Bluetooth.';
                break;
            case 'NotSupportedError':
                message = 'Bluetooth tidak didukung di perangkat ini';
                break;
            case 'NotAllowedError':
                message = 'Akses Bluetooth tidak diizinkan';
                break;
            case 'NetworkError':
                message = 'Koneksi Bluetooth terputus';
                break;
            case 'InvalidStateError':
                message = 'Perangkat Bluetooth dalam keadaan tidak valid';
                break;
            default:
                message = error.message || 'Terjadi kesalahan tidak diketahui';
        }
        
        if (this.onError) {
            this.onError(message, error);
        }
        
        return new Error(message);
    }

    /**
     * Send command to device
     */
    async sendCommand(command) {
        if (!this.isConnected || !this.characteristic) {
            throw new Error('Device not connected');
        }
        
        try {
            const encoder = new TextEncoder();
            const data = encoder.encode(JSON.stringify(command));
            await this.characteristic.writeValue(data);
            console.log('Command sent:', command);
        } catch (error) {
            console.error('Error sending command:', error);
            throw error;
        }
    }

    /**
     * Get device info
     */
    getDeviceInfo() {
        if (!this.device) {
            return null;
        }
        
        return {
            name: this.device.name,
            id: this.device.id,
            connected: this.isConnected,
            gatt: this.device.gatt.connected
        };
    }
}

/**
 * IoT Data Manager
 */
class IoTDataManager {
    constructor() {
        this.apiUrl = '/api/iot';
        this.bluetooth = new BluetoothIoT();
        this.isRecording = false;
        this.recordingInterval = null;
        this.recordingIntervalTime = 10000; // 10 seconds
    }

    /**
     * Initialize IoT system
     */
    async initialize() {
        // Set up Bluetooth event handlers
        this.bluetooth.onDataReceived = (data) => {
            this.handleSensorData(data);
        };
        
        this.bluetooth.onConnectionChange = (connected, device) => {
            this.handleConnectionChange(connected, device);
        };
        
        this.bluetooth.onError = (message, error) => {
            this.handleError(message, error);
        };
    }

    /**
     * Connect to IoT device
     */
    async connect() {
        try {
            await this.bluetooth.connect();
            return true;
        } catch (error) {
            console.error('Failed to connect:', error);
            throw error;
        }
    }

    /**
     * Disconnect from IoT device
     */
    async disconnect() {
        try {
            await this.bluetooth.disconnect();
            this.stopRecording();
        } catch (error) {
            console.error('Failed to disconnect:', error);
        }
    }

    /**
     * Start recording sensor data
     */
    startRecording(kelasId, location = null, notes = null) {
        if (this.isRecording) {
            console.log('Already recording');
            return;
        }
        
        this.isRecording = true;
        this.recordingKelasId = kelasId;
        this.recordingLocation = location;
        this.recordingNotes = notes;
        
        console.log('Started recording sensor data');
    }

    /**
     * Stop recording sensor data
     */
    stopRecording() {
        if (!this.isRecording) {
            return;
        }
        
        this.isRecording = false;
        this.recordingKelasId = null;
        this.recordingLocation = null;
        this.recordingNotes = null;
        
        console.log('Stopped recording sensor data');
    }

    /**
     * Handle sensor data received
     */
    async handleSensorData(data) {
        if (!this.isRecording) {
            return;
        }
        
        try {
            // Add additional data
            const sensorData = {
                ...data,
                device_id: this.bluetooth.device?.id || 'unknown',
                bluetooth_address: this.bluetooth.device?.id || 'unknown',
                kelas_id: this.recordingKelasId,
                location: this.recordingLocation,
                notes: this.recordingNotes
            };
            
            // Send to server
            await this.sendSensorDataToServer(sensorData);
            
            // Update UI
            this.updateSensorDisplay(data);
            
        } catch (error) {
            console.error('Error handling sensor data:', error);
        }
    }

    /**
     * Send sensor data to server
     */
    async sendSensorDataToServer(data) {
        try {
            const response = await fetch(`${this.apiUrl}/sensor-data`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (!result.success) {
                throw new Error(result.message || 'Failed to save sensor data');
            }
            
            console.log('Sensor data saved:', result.data);
            return result.data;
            
        } catch (error) {
            console.error('Error sending sensor data to server:', error);
            throw error;
        }
    }

    /**
     * Update sensor display
     */
    updateSensorDisplay(data) {
        // Update temperature
        const tempElement = document.getElementById('current-temperature');
        if (tempElement) {
            tempElement.textContent = `${data.temperature.toFixed(1)}°C`;
        }
        
        // Update humidity
        const humidityElement = document.getElementById('current-humidity');
        if (humidityElement) {
            humidityElement.textContent = `${data.humidity.toFixed(1)}%`;
        }
        
        // Update soil moisture
        const moistureElement = document.getElementById('current-moisture');
        if (moistureElement) {
            moistureElement.textContent = `${data.soil_moisture.toFixed(1)}%`;
        }
        
        // Update timestamp
        const timestampElement = document.getElementById('last-update');
        if (timestampElement) {
            timestampElement.textContent = new Date().toLocaleString();
        }
        
        // Update status
        const statusElement = document.getElementById('connection-status');
        if (statusElement) {
            statusElement.textContent = 'Terhubung';
            statusElement.className = 'badge badge-success';
        }
    }

    /**
     * Handle connection change
     */
    handleConnectionChange(connected, device) {
        const statusElement = document.getElementById('connection-status');
        if (statusElement) {
            if (connected) {
                statusElement.textContent = 'Terhubung';
                statusElement.className = 'badge badge-success';
            } else {
                statusElement.textContent = 'Terputus';
                statusElement.className = 'badge badge-danger';
            }
        }
        
        // Update device info
        const deviceInfoElement = document.getElementById('device-info');
        if (deviceInfoElement && device) {
            deviceInfoElement.textContent = device.name || 'Unknown Device';
        }
    }

    /**
     * Handle errors
     */
    handleError(message, error) {
        console.error('IoT Error:', message, error);
        
        // Show error to user
        if (typeof showAlert === 'function') {
            showAlert('error', message);
        } else {
            alert(message);
        }
    }

    /**
     * Get real-time data
     */
    async getRealTimeData(deviceId = null) {
        try {
            const url = deviceId ? `${this.apiUrl}/real-time-data?device_id=${deviceId}` : `${this.apiUrl}/real-time-data`;
            const response = await fetch(url);
            const result = await response.json();
            
            if (result.success) {
                return result.data;
            } else {
                throw new Error(result.message || 'Failed to get real-time data');
            }
        } catch (error) {
            console.error('Error getting real-time data:', error);
            throw error;
        }
    }
}

// Global instance
window.iotManager = new IoTDataManager();

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.iotManager.initialize();
});
