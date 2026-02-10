/**
 * USB IoT Connection Manager
 * Supports Web Serial API for USB-connected IoT devices
 * Fallback to Bluetooth if USB not available
 */

class USBIoTManager {
    constructor() {
        this.port = null;
        this.reader = null;
        this.writer = null;
        this.isConnected = false;
        this.isReading = false;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 3;
        
        // USB Device Configuration
        this.vendorId = 0x1234; // Arduino/ESP32 vendor ID
        this.productId = 0x5678; // Product ID
        this.baudRate = 9600;
        
        // Data format for IoT sensors
        this.dataFormat = {
            temperature: 0,
            humidity: 0,
            soil_moisture: 0,
            ph_level: 0,
            nutrient_level: 0
        };
        
        // Event handlers
        this.onDataReceived = null;
        this.onConnectionChange = null;
        this.onError = null;
        this.onStatusUpdate = null;
    }

    /**
     * Check if Web Serial API is supported
     */
    isSupported() {
        return 'serial' in navigator;
    }

    /**
     * Get available USB devices
     */
    async getAvailableDevices() {
        if (!this.isSupported()) {
            throw new Error('Web Serial API tidak didukung di browser ini');
        }

        try {
            const ports = await navigator.serial.getPorts();
            return ports.map(port => ({
                port: port,
                info: port.getInfo(),
                connected: false
            }));
        } catch (error) {
            console.error('Error getting USB devices:', error);
            throw error;
        }
    }

    /**
     * Request USB device connection
     */
    async requestDevice() {
        if (!this.isSupported()) {
            throw new Error('Web Serial API tidak didukung di browser ini');
        }

        try {
            // Request specific device or show picker
            const port = await navigator.serial.requestPort({
                filters: [
                    { usbVendorId: this.vendorId },
                    { usbProductId: this.productId }
                ]
            });

            this.port = port;
            return port;
        } catch (error) {
            console.error('Error requesting USB device:', error);
            throw error;
        }
    }

    /**
     * Connect to USB device
     */
    async connect(port = null) {
        try {
            if (port) {
                this.port = port;
            } else if (!this.port) {
                await this.requestDevice();
            }

            if (this.onStatusUpdate) {
                this.onStatusUpdate('Connecting to USB device...');
            }

            // Open serial connection
            await this.port.open({ 
                baudRate: this.baudRate,
                dataBits: 8,
                stopBits: 1,
                parity: 'none',
                flowControl: 'none'
            });

            // Setup reader and writer
            this.reader = this.port.readable.getReader();
            this.writer = this.port.writable.getWriter();

            this.isConnected = true;
            this.reconnectAttempts = 0;

            if (this.onConnectionChange) {
                this.onConnectionChange(true, this.port);
            }

            if (this.onStatusUpdate) {
                this.onStatusUpdate('USB device connected successfully');
            }

            console.log('USB IoT device connected');
            return true;

        } catch (error) {
            console.error('USB connection failed:', error);
            this.isConnected = false;
            
            if (this.onConnectionChange) {
                this.onConnectionChange(false, null);
            }
            
            if (this.onError) {
                this.onError('USB connection failed', error);
            }
            
            throw error;
        }
    }

    /**
     * Disconnect from USB device
     */
    async disconnect() {
        try {
            if (this.isReading) {
                await this.stopReading();
            }

            if (this.reader) {
                await this.reader.releaseLock();
                this.reader = null;
            }

            if (this.writer) {
                await this.writer.releaseLock();
                this.writer = null;
            }

            if (this.port) {
                await this.port.close();
                this.port = null;
            }

            this.isConnected = false;

            if (this.onConnectionChange) {
                this.onConnectionChange(false, null);
            }

            if (this.onStatusUpdate) {
                this.onStatusUpdate('USB device disconnected');
            }

            console.log('USB IoT device disconnected');
            return true;

        } catch (error) {
            console.error('Error disconnecting USB device:', error);
            throw error;
        }
    }

    /**
     * Start reading data from USB device
     */
    async startReading() {
        if (!this.isConnected || !this.reader) {
            throw new Error('USB device not connected');
        }

        this.isReading = true;

        try {
            while (this.isReading && this.isConnected) {
                const { value, done } = await this.reader.read();
                
                if (done) {
                    break;
                }

                // Parse incoming data
                const data = this.parseSensorData(value);
                if (data && this.onDataReceived) {
                    this.onDataReceived(data);
                }
            }
        } catch (error) {
            console.error('Error reading USB data:', error);
            if (this.onError) {
                this.onError('Error reading data', error);
            }
        }
    }

    /**
     * Stop reading data
     */
    async stopReading() {
        this.isReading = false;
        
        if (this.reader) {
            try {
                await this.reader.cancel();
            } catch (error) {
                console.error('Error stopping USB reading:', error);
            }
        }
    }

    /**
     * Send command to USB device
     */
    async sendCommand(command) {
        if (!this.isConnected || !this.writer) {
            throw new Error('USB device not connected');
        }

        try {
            const encoder = new TextEncoder();
            const data = encoder.encode(command + '\n');
            await this.writer.write(data);
            
            console.log('Command sent to USB device:', command);
            return true;
        } catch (error) {
            console.error('Error sending command:', error);
            throw error;
        }
    }

    /**
     * Parse sensor data from USB
     */
    parseSensorData(data) {
        try {
            const decoder = new TextDecoder();
            const text = decoder.decode(data);
            
            // Look for JSON data in the text
            const jsonMatch = text.match(/\{.*\}/);
            if (jsonMatch) {
                const sensorData = JSON.parse(jsonMatch[0]);
                
                // Validate and format data
                return {
                    temperature: parseFloat(sensorData.temperature) || 0,
                    humidity: parseFloat(sensorData.humidity) || 0,
                    soil_moisture: parseFloat(sensorData.soil_moisture) || 0,
                    ph_level: parseFloat(sensorData.ph_level) || 0,
                    nutrient_level: parseFloat(sensorData.nutrient_level) || 0,
                    timestamp: new Date().toISOString(),
                    source: 'usb'
                };
            }
        } catch (error) {
            console.error('Error parsing USB data:', error);
        }
        
        return null;
    }

    /**
     * Get connection status
     */
    getConnectionStatus() {
        return {
            connected: this.isConnected,
            reading: this.isReading,
            port: this.port ? this.port.getInfo() : null,
            supported: this.isSupported()
        };
    }

    /**
     * Handle connection errors
     */
    handleConnectionError(error) {
        console.error('USB connection error:', error);
        
        if (this.reconnectAttempts < this.maxReconnectAttempts) {
            this.reconnectAttempts++;
            setTimeout(() => {
                this.reconnect();
            }, 5000);
        } else {
            if (this.onError) {
                this.onError('Max reconnection attempts reached', error);
            }
        }
    }

    /**
     * Attempt to reconnect
     */
    async reconnect() {
        try {
            if (this.onStatusUpdate) {
                this.onStatusUpdate(`Attempting to reconnect... (${this.reconnectAttempts}/${this.maxReconnectAttempts})`);
            }
            
            await this.connect();
            await this.startReading();
        } catch (error) {
            this.handleConnectionError(error);
        }
    }

    /**
     * Test USB connection
     */
    async testConnection() {
        try {
            if (!this.isSupported()) {
                return {
                    success: false,
                    message: 'Web Serial API tidak didukung'
                };
            }

            const devices = await this.getAvailableDevices();
            return {
                success: true,
                message: `Found ${devices.length} USB devices`,
                devices: devices
            };
        } catch (error) {
            return {
                success: false,
                message: error.message
            };
        }
    }
}

/**
 * Combined IoT Manager (USB + Bluetooth)
 * Automatically falls back to Bluetooth if USB not available
 */
class CombinedIoTManager {
    constructor() {
        this.usbManager = new USBIoTManager();
        this.bluetoothManager = null; // Will be initialized if needed
        this.currentConnection = null; // 'usb' or 'bluetooth'
        this.isConnected = false;
        
        // Event handlers
        this.onDataReceived = null;
        this.onConnectionChange = null;
        this.onError = null;
        this.onStatusUpdate = null;
    }

    /**
     * Initialize Bluetooth manager if available
     */
    initBluetoothManager() {
        if (typeof BluetoothIoT !== 'undefined') {
            this.bluetoothManager = new BluetoothIoT();
            
            // Forward events
            this.bluetoothManager.onDataReceived = (data) => {
                if (this.onDataReceived) {
                    this.onDataReceived({...data, source: 'bluetooth'});
                }
            };
            
            this.bluetoothManager.onConnectionChange = (connected, device) => {
                if (this.onConnectionChange) {
                    this.onConnectionChange(connected, device);
                }
            };
            
            this.bluetoothManager.onError = (message, error) => {
                if (this.onError) {
                    this.onError(message, error);
                }
            };
        }
    }

    /**
     * Connect using preferred method (USB first, then Bluetooth)
     */
    async connect(preferredMethod = 'usb') {
        try {
            if (preferredMethod === 'usb' && this.usbManager.isSupported()) {
                await this.usbManager.connect();
                this.currentConnection = 'usb';
                this.isConnected = true;
                
                // Start reading
                await this.usbManager.startReading();
                
                if (this.onStatusUpdate) {
                    this.onStatusUpdate('Connected via USB');
                }
                
                return true;
            } else if (this.bluetoothManager || this.initBluetoothManager()) {
                await this.bluetoothManager.connect();
                this.currentConnection = 'bluetooth';
                this.isConnected = true;
                
                if (this.onStatusUpdate) {
                    this.onStatusUpdate('Connected via Bluetooth');
                }
                
                return true;
            } else {
                throw new Error('No connection method available');
            }
        } catch (error) {
            // Try fallback method
            if (preferredMethod === 'usb' && this.bluetoothManager) {
                console.log('USB failed, trying Bluetooth...');
                return await this.connect('bluetooth');
            } else if (preferredMethod === 'bluetooth' && this.usbManager.isSupported()) {
                console.log('Bluetooth failed, trying USB...');
                return await this.connect('usb');
            }
            
            throw error;
        }
    }

    /**
     * Disconnect current connection
     */
    async disconnect() {
        try {
            if (this.currentConnection === 'usb' && this.usbManager) {
                await this.usbManager.disconnect();
            } else if (this.currentConnection === 'bluetooth' && this.bluetoothManager) {
                await this.bluetoothManager.disconnect();
            }
            
            this.currentConnection = null;
            this.isConnected = false;
            
            if (this.onStatusUpdate) {
                this.onStatusUpdate('Disconnected');
            }
            
            return true;
        } catch (error) {
            console.error('Error disconnecting:', error);
            throw error;
        }
    }

    /**
     * Get available connection methods
     */
    getAvailableMethods() {
        const methods = [];
        
        if (this.usbManager.isSupported()) {
            methods.push('usb');
        }
        
        if (typeof BluetoothIoT !== 'undefined') {
            methods.push('bluetooth');
        }
        
        return methods;
    }

    /**
     * Get connection status
     */
    getStatus() {
        return {
            connected: this.isConnected,
            method: this.currentConnection,
            available: this.getAvailableMethods(),
            usb: this.usbManager.getConnectionStatus(),
            bluetooth: this.bluetoothManager ? this.bluetoothManager.isConnected() : false
        };
    }
}

// Export for use
window.USBIoTManager = USBIoTManager;
window.CombinedIoTManager = CombinedIoTManager;
