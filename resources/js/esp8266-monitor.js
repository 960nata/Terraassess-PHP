// ESP8266 Monitor - Web Serial API Integration
class ESP8266Monitor {
    constructor() {
        this.port = null;
        this.reader = null;
        this.isConnected = false;
        this.baudRate = 115200;
        this.sensorData = {};
        this.deviceStatus = {};
        this.initialize();
    }
    
    initialize() {
        this.addConsoleLog('ESP8266 Monitor initialized...');
        if (!('serial' in navigator)) {
            this.addConsoleLog('ERROR: Web Serial API not supported');
            return;
        }
        this.addConsoleLog('Ready to detect ports');
        this.startApiRefresh();
    }
    
    async detectPorts() {
        this.addConsoleLog('Scanning for ports...');
        try {
            const ports = await navigator.serial.getPorts();
            const portSelect = document.getElementById('portSelect');
            portSelect.innerHTML = '<option value="">Select Port...</option>';
            if (ports.length === 0) {
                portSelect.innerHTML += '<option value="request">Request New Port...</option>';
            } else {
                ports.forEach((port, i) => {
                    portSelect.innerHTML += '<option value="' + i + '">Port ' + (i+1) + '</option>';
                });
            }
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
        await this.detectPorts();
        const ports = await navigator.serial.getPorts();
        if (ports.length === 0) {
            await this.requestNewPort();
        } else {
            await this.connectToDevice(0);
        }
    }
    
    async connectToDevice(idx = null) {
        try {
            const ports = await navigator.serial.getPorts();
            if (idx === null) idx = parseInt(document.getElementById('portSelect').value);
            this.port = ports[idx];
            this.baudRate = parseInt(document.getElementById('baudRateSelect').value);
            await this.port.open({baudRate: this.baudRate, dataBits: 8, stopBits: 1, parity: 'none'});
            this.reader = this.port.readable.getReader();
            this.writer = this.port.writable.getWriter();
            this.isConnected = true;
            this.addConsoleLog('Connected at ' + this.baudRate + ' baud');
            document.getElementById('connection-status').textContent = 'Connected';
            this.startReading();
        } catch (error) {
            this.addConsoleLog('ERROR: ' + error.message);
        }
    }
    
    async disconnectDevice() {
        try {
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
            this.addConsoleLog('Disconnected');
            document.getElementById('connection-status').textContent = 'Disconnected';
        } catch (error) {
            this.addConsoleLog('ERROR: ' + error.message);
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
        const lines = data.split('\n');
        for (const line of lines) {
            const trim = line.trim();
            if (trim.startsWith('{') && trim.endsWith('}')) {
                try {
                    const json = JSON.parse(trim);
                    this.sensorData = {
                        temperature: json.temperature || null,
                        humidity: json.humidity || null,
                        ph: json.ph || null,
                        nitrogen: json.nitrogen || null,
                        phosphorus: json.phosphorus || null,
                        potassium: json.potassium || null
                    };
                    this.updateSensorUI();
                } catch (e) {}
            }
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
        const els = {
            'nitrogen-value': this.sensorData.nitrogen,
            'phosphorus-value': this.sensorData.phosphorus,
            'potassium-value': this.sensorData.potassium,
            'temperature-value': this.sensorData.temperature ? this.sensorData.temperature.toFixed(1) + '°C' : '--°C',
            'humidity-value': this.sensorData.humidity ? this.sensorData.humidity.toFixed(1) + '%' : '--%',
            'ph-value': this.sensorData.ph ? this.sensorData.ph.toFixed(1) : '--'
        };
        for (const [id, val] of Object.entries(els)) {
            const el = document.getElementById(id);
            if (el) el.textContent = val !== null && val !== undefined ? val : '--';
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
    
    cleanup() {
        this.stopAutoRefresh();
        if (this.isConnected) this.disconnectDevice();
    }
}

let esp8266Monitor = null;
document.addEventListener('DOMContentLoaded', () => { esp8266Monitor = new ESP8266Monitor(); });
window.addEventListener('beforeunload', () => { if (esp8266Monitor) esp8266Monitor.cleanup(); });
