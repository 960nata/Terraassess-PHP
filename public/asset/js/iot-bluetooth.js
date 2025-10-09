/**
 * IoT Bluetooth Manager
 * Handles Web Bluetooth API for IoT device connections
 */

class IoTBluetoothManager {
    constructor() {
        this.device = null;
        this.server = null;
        this.service = null;
        this.characteristic = null;
        this.isConnected = false;
        this.isReading = false;
        this.readingInterval = null;
        
        // BLE Service and Characteristic UUIDs for Soil Sensor
        this.SOIL_SENSOR_SERVICE_UUID = '12345678-1234-1234-1234-123456789abc';
        this.SOIL_TEMPERATURE_CHAR_UUID = '12345678-1234-1234-1234-123456789abd';
        this.SOIL_HUMUS_CHAR_UUID = '12345678-1234-1234-1234-123456789abe';
        this.SOIL_MOISTURE_CHAR_UUID = '12345678-1234-1234-1234-123456789abf';
        
        // Callbacks
        this.onDataReceived = null;
        this.onConnectionChange = null;
        this.onError = null;
    }

    /**
     * Check if Web Bluetooth is supported
     */
    isSupported() {
        return 'bluetooth' in navigator;
    }

    /**
     * Check if device is connected
     */
    isDeviceConnected() {
        return this.isConnected && this.device && this.device.gatt.connected;
    }

    /**
     * Scan and connect to IoT device
     */
    async connect() {
        if (!this.isSupported()) {
            throw new Error('Web Bluetooth tidak didukung di browser ini');
        }

        try {
            // Request device with soil sensor service
            this.device = await navigator.bluetooth.requestDevice({
                filters: [
                    { namePrefix: 'Soil' },
                    { namePrefix: 'IoT' },
                    { namePrefix: 'Sensor' }
                ],
                optionalServices: [
                    this.SOIL_SENSOR_SERVICE_UUID,
                    '0000180a-0000-1000-8000-00805f9b34fb', // Device Information Service
                    '0000180f-0000-1000-8000-00805f9b34fb'  // Battery Service
                ]
            });

            // Add event listener for device disconnection
            this.device.addEventListener('gattserverdisconnected', () => {
                this.handleDisconnection();
            });

            // Connect to GATT server
            this.server = await this.device.gatt.connect();
            this.isConnected = true;

            // Get soil sensor service
            this.service = await this.server.getPrimaryService(this.SOIL_SENSOR_SERVICE_UUID);

            // Get characteristics
            this.temperatureChar = await this.service.getCharacteristic(this.SOIL_TEMPERATURE_CHAR_UUID);
            this.humusChar = await this.service.getCharacteristic(this.SOIL_HUMUS_CHAR_UUID);
            this.moistureChar = await this.service.getCharacteristic(this.SOIL_MOISTURE_CHAR_UUID);

            if (this.onConnectionChange) {
                this.onConnectionChange(true);
            }

            return true;

        } catch (error) {
            console.error('Bluetooth connection error:', error);
            if (this.onError) {
                this.onError(error);
            }
            throw error;
        }
    }

    /**
     * Disconnect from device
     */
    async disconnect() {
        if (this.isReading) {
            this.stopReading();
        }

        if (this.device && this.device.gatt.connected) {
            await this.device.gatt.disconnect();
        }

        this.handleDisconnection();
    }

    /**
     * Handle device disconnection
     */
    handleDisconnection() {
        this.isConnected = false;
        this.device = null;
        this.server = null;
        this.service = null;
        this.characteristic = null;

        if (this.isReading) {
            this.stopReading();
        }

        if (this.onConnectionChange) {
            this.onConnectionChange(false);
        }
    }

    /**
     * Start reading sensor data
     */
    async startReading() {
        if (!this.isDeviceConnected()) {
            throw new Error('Device tidak terhubung');
        }

        this.isReading = true;

        try {
            // Read initial values
            await this.readSensorData();

            // Set up periodic reading
            this.readingInterval = setInterval(async () => {
                if (this.isReading && this.isDeviceConnected()) {
                    await this.readSensorData();
                }
            }, 2000); // Read every 2 seconds

        } catch (error) {
            console.error('Error starting reading:', error);
            if (this.onError) {
                this.onError(error);
            }
            throw error;
        }
    }

    /**
     * Stop reading sensor data
     */
    stopReading() {
        this.isReading = false;
        
        if (this.readingInterval) {
            clearInterval(this.readingInterval);
            this.readingInterval = null;
        }
    }

    /**
     * Read sensor data from characteristics
     */
    async readSensorData() {
        try {
            const data = {};

            // Read temperature
            if (this.temperatureChar) {
                const tempValue = await this.temperatureChar.readValue();
                data.soil_temperature = this.parseSensorValue(tempValue, 'temperature');
            }

            // Read humus
            if (this.humusChar) {
                const humusValue = await this.humusChar.readValue();
                data.soil_humus = this.parseSensorValue(humusValue, 'humus');
            }

            // Read moisture
            if (this.moistureChar) {
                const moistureValue = await this.moistureChar.readValue();
                data.soil_moisture = this.parseSensorValue(moistureValue, 'moisture');
            }

            // Add timestamp
            data.timestamp = new Date().toISOString();
            data.device_id = this.device.id;

            // Call data received callback
            if (this.onDataReceived) {
                this.onDataReceived(data);
            }

        } catch (error) {
            console.error('Error reading sensor data:', error);
            if (this.onError) {
                this.onError(error);
            }
        }
    }

    /**
     * Parse sensor value from DataView
     */
    parseSensorValue(dataView, sensorType) {
        try {
            // Assuming data is sent as 16-bit integer (2 bytes)
            const value = dataView.getInt16(0, true); // little-endian
            
            // Convert based on sensor type
            switch (sensorType) {
                case 'temperature':
                    return (value / 100).toFixed(1); // Temperature in Celsius * 100
                case 'humus':
                    return (value / 100).toFixed(1); // Humus percentage * 100
                case 'moisture':
                    return (value / 100).toFixed(1); // Moisture percentage * 100
                default:
                    return value;
            }
        } catch (error) {
            console.error('Error parsing sensor value:', error);
            return 0;
        }
    }

    /**
     * Get device information
     */
    async getDeviceInfo() {
        if (!this.isDeviceConnected()) {
            throw new Error('Device tidak terhubung');
        }

        try {
            const deviceInfoService = await this.server.getPrimaryService('0000180a-0000-1000-8000-00805f9b34fb');
            
            const info = {};
            
            // Get device name
            try {
                const deviceNameChar = await deviceInfoService.getCharacteristic('00002a00-0000-1000-8000-00805f9b34fb');
                const deviceName = await deviceNameChar.readValue();
                info.name = new TextDecoder().decode(deviceName);
            } catch (e) {
                info.name = 'Unknown Device';
            }

            // Get manufacturer name
            try {
                const manufacturerChar = await deviceInfoService.getCharacteristic('00002a29-0000-1000-8000-00805f9b34fb');
                const manufacturer = await manufacturerChar.readValue();
                info.manufacturer = new TextDecoder().decode(manufacturer);
            } catch (e) {
                info.manufacturer = 'Unknown';
            }

            return info;

        } catch (error) {
            console.error('Error getting device info:', error);
            return {
                name: 'Unknown Device',
                manufacturer: 'Unknown'
            };
        }
    }

    /**
     * Get battery level
     */
    async getBatteryLevel() {
        if (!this.isDeviceConnected()) {
            throw new Error('Device tidak terhubung');
        }

        try {
            const batteryService = await this.server.getPrimaryService('0000180f-0000-1000-8000-00805f9b34fb');
            const batteryChar = await batteryService.getCharacteristic('00002a19-0000-1000-8000-00805f9b34fb');
            const batteryValue = await batteryChar.readValue();
            
            return batteryValue.getUint8(0); // Battery percentage

        } catch (error) {
            console.error('Error getting battery level:', error);
            return null;
        }
    }

    /**
     * Set data received callback
     */
    onData(callback) {
        this.onDataReceived = callback;
    }

    /**
     * Set connection change callback
     */
    onConnection(callback) {
        this.onConnectionChange = callback;
    }

    /**
     * Set error callback
     */
    onErrorCallback(callback) {
        this.onError = callback;
    }
}

/**
 * IoT Data Manager
 * Handles data storage and API communication
 */
class IoTDataManager {
    constructor() {
        this.apiBaseUrl = '/api/iot';
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    }

    /**
     * Save reading data to server
     */
    async saveReading(data) {
        try {
            const response = await fetch(`${this.apiBaseUrl}/readings`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            
            if (!result.success) {
                throw new Error(result.message || 'Gagal menyimpan data');
            }

            return result.data;

        } catch (error) {
            console.error('Error saving reading:', error);
            throw error;
        }
    }

    /**
     * Get readings for a class
     */
    async getClassReadings(classId, filters = {}) {
        try {
            const params = new URLSearchParams({ class_id: classId, ...filters });
            const response = await fetch(`${this.apiBaseUrl}/readings/class/${classId}?${params}`);
            
            const result = await response.json();
            
            if (!result.success) {
                throw new Error(result.message || 'Gagal mengambil data');
            }

            return result.data;

        } catch (error) {
            console.error('Error getting class readings:', error);
            throw error;
        }
    }

    /**
     * Get readings for a student
     */
    async getStudentReadings(studentId, filters = {}) {
        try {
            const params = new URLSearchParams({ student_id: studentId, ...filters });
            const response = await fetch(`${this.apiBaseUrl}/readings/student/${studentId}?${params}`);
            
            const result = await response.json();
            
            if (!result.success) {
                throw new Error(result.message || 'Gagal mengambil data');
            }

            return result.data;

        } catch (error) {
            console.error('Error getting student readings:', error);
            throw error;
        }
    }

    /**
     * Export readings to CSV
     */
    exportToCSV(filters = {}) {
        const params = new URLSearchParams(filters);
        const url = `${this.apiBaseUrl}/readings/export?${params}`;
        window.open(url, '_blank');
    }

    /**
     * Get real-time data
     */
    async getRealTimeData(classId = null) {
        try {
            const params = classId ? `?class_id=${classId}` : '';
            const response = await fetch(`${this.apiBaseUrl}/readings/realtime${params}`);
            
            const result = await response.json();
            
            if (!result.success) {
                throw new Error(result.message || 'Gagal mengambil data real-time');
            }

            return result.data;

        } catch (error) {
            console.error('Error getting real-time data:', error);
            throw error;
        }
    }

    /**
     * Get statistics
     */
    async getStatistics(classId = null) {
        try {
            const params = classId ? `?class_id=${classId}` : '';
            const response = await fetch(`${this.apiBaseUrl}/readings/statistics${params}`);
            
            const result = await response.json();
            
            if (!result.success) {
                throw new Error(result.message || 'Gagal mengambil statistik');
            }

            return result.data;

        } catch (error) {
            console.error('Error getting statistics:', error);
            throw error;
        }
    }
}

// Export classes for global use
window.IoTBluetoothManager = IoTBluetoothManager;
window.IoTDataManager = IoTDataManager;
