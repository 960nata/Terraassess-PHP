<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NPK Sensor Test Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
            color: white;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .header .subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .status-bar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 1rem 2rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .status-indicator {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .status-online {
            background: #4ade80;
        }

        .status-offline {
            background: #ef4444;
        }

        .last-updated {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }

        .controls {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .btn-primary {
            background: #3b82f6;
            border-color: #3b82f6;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        .sensor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .sensor-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .sensor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .sensor-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--card-color), var(--card-color-light));
        }

        .sensor-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--card-color);
        }

        .sensor-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: white;
            margin-bottom: 0.5rem;
        }

        .sensor-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--card-color);
            margin-bottom: 0.5rem;
        }

        .sensor-unit {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .sensor-status {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #4ade80;
        }

        .sensor-status.offline {
            background: #ef4444;
        }

        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            color: #fca5a5;
            margin: 2rem 0;
        }

        .error-message i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #ef4444;
        }

        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
            color: white;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .footer {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 2rem;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }
            
            .status-bar {
                flex-direction: column;
                text-align: center;
            }
            
            .sensor-grid {
                grid-template-columns: 1fr;
            }
            
            .sensor-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-seedling"></i> NPK Sensor Test Dashboard</h1>
            <p class="subtitle">Real-time monitoring 7 parameter sensor tanah</p>
        </div>

        <div class="status-bar">
            <div class="status-indicator">
                <div class="status-dot" id="statusDot"></div>
                <span id="statusText">Checking connection...</span>
            </div>
            <div class="last-updated" id="lastUpdated">
                Last update: Never
            </div>
            <div class="controls">
                <button class="btn btn-primary" onclick="refreshData()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button class="btn" id="autoRefreshBtn" onclick="toggleAutoRefresh()">
                    <i class="fas fa-clock"></i> Auto-refresh: OFF
                </button>
            </div>
        </div>

        <div id="sensorContainer">
            <div class="loading">
                <div class="spinner"></div>
                <span style="margin-left: 1rem;">Loading sensor data...</span>
            </div>
        </div>

        <div class="footer">
            <p>ThingsBoard Integration Test | Terra Assessment System</p>
        </div>
    </div>

    <script>
        let autoRefreshInterval = null;
        let isAutoRefresh = false;

        // CSS Variables for sensor cards
        const sensorColors = {
            temperature: { color: '#ef4444', light: '#fca5a5' },
            humidity: { color: '#3b82f6', light: '#93c5fd' },
            conductivity: { color: '#8b5cf6', light: '#c4b5fd' },
            ph: { color: '#10b981', light: '#6ee7b7' },
            nitrogen: { color: '#f59e0b', light: '#fcd34d' },
            phosphorus: { color: '#ec4899', light: '#f9a8d4' },
            potassium: { color: '#06b6d4', light: '#67e8f9' }
        };

        const sensorIcons = {
            temperature: 'fas fa-thermometer-half',
            humidity: 'fas fa-tint',
            conductivity: 'fas fa-bolt',
            ph: 'fas fa-flask',
            nitrogen: 'fas fa-atom',
            phosphorus: 'fas fa-circle',
            potassium: 'fas fa-circle'
        };

        const sensorNames = {
            temperature: 'Suhu',
            humidity: 'Kelembaban',
            conductivity: 'Konduktivitas',
            ph: 'pH',
            nitrogen: 'Nitrogen',
            phosphorus: 'Fosfor',
            potassium: 'Kalium'
        };

        const sensorUnits = {
            temperature: '°C',
            humidity: '%',
            conductivity: 'mS/cm',
            ph: 'pH',
            nitrogen: 'ppm',
            phosphorus: 'ppm',
            potassium: 'ppm'
        };

        function updateStatus(online) {
            const statusDot = document.getElementById('statusDot');
            const statusText = document.getElementById('statusText');
            
            if (online) {
                statusDot.className = 'status-dot status-online';
                statusText.textContent = 'Online';
            } else {
                statusDot.className = 'status-dot status-offline';
                statusText.textContent = 'Offline';
            }
        }

        function updateLastUpdated() {
            const lastUpdated = document.getElementById('lastUpdated');
            lastUpdated.textContent = `Last update: ${new Date().toLocaleTimeString()}`;
        }

        function createSensorCard(sensorKey, value) {
            const colors = sensorColors[sensorKey];
            const icon = sensorIcons[sensorKey];
            const name = sensorNames[sensorKey];
            const unit = sensorUnits[sensorKey];
            
            return `
                <div class="sensor-card" style="--card-color: ${colors.color}; --card-color-light: ${colors.light}">
                    <div class="sensor-status"></div>
                    <div class="sensor-icon">
                        <i class="${icon}"></i>
                    </div>
                    <div class="sensor-name">${name}</div>
                    <div class="sensor-value">${value}</div>
                    <div class="sensor-unit">${unit}</div>
                </div>
            `;
        }

        function displaySensorData(data) {
            const container = document.getElementById('sensorContainer');
            
            if (!data || !data.sensor_data) {
                container.innerHTML = `
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h3>No sensor data available</h3>
                        <p>Unable to fetch sensor data from ThingsBoard</p>
                    </div>
                `;
                updateStatus(false);
                return;
            }

            const sensorData = data.sensor_data;
            let html = '<div class="sensor-grid">';
            
            // Display 7 sensor parameters
            const sensors = [
                { key: 'temperature', value: sensorData.temperature || 'N/A' },
                { key: 'humidity', value: sensorData.humidity || 'N/A' },
                { key: 'conductivity', value: sensorData.conductivity || 'N/A' },
                { key: 'ph', value: sensorData.ph || 'N/A' },
                { key: 'nitrogen', value: sensorData.nitrogen || 'N/A' },
                { key: 'phosphorus', value: sensorData.phosphorus || 'N/A' },
                { key: 'potassium', value: sensorData.potassium || 'N/A' }
            ];

            sensors.forEach(sensor => {
                html += createSensorCard(sensor.key, sensor.value);
            });
            
            html += '</div>';
            container.innerHTML = html;
            updateStatus(true);
            updateLastUpdated();
        }

        function displayError(message) {
            const container = document.getElementById('sensorContainer');
            container.innerHTML = `
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Connection Error</h3>
                    <p>${message}</p>
                    <button class="btn btn-primary" onclick="refreshData()" style="margin-top: 1rem;">
                        <i class="fas fa-redo"></i> Try Again
                    </button>
                </div>
            `;
            updateStatus(false);
        }

        async function fetchSensorData() {
            try {
                const response = await fetch('/api/sensor/public-data');
                const data = await response.json();
                
                if (data.success) {
                    displaySensorData(data.data);
                } else {
                    displayError(data.message || 'Failed to fetch sensor data');
                }
            } catch (error) {
                displayError('Network error: ' + error.message);
            }
        }

        function refreshData() {
            const container = document.getElementById('sensorContainer');
            container.innerHTML = `
                <div class="loading">
                    <div class="spinner"></div>
                    <span style="margin-left: 1rem;">Refreshing data...</span>
                </div>
            `;
            fetchSensorData();
        }

        function toggleAutoRefresh() {
            const btn = document.getElementById('autoRefreshBtn');
            
            if (isAutoRefresh) {
                clearInterval(autoRefreshInterval);
                isAutoRefresh = false;
                btn.innerHTML = '<i class="fas fa-clock"></i> Auto-refresh: OFF';
            } else {
                autoRefreshInterval = setInterval(fetchSensorData, 30000); // 30 seconds
                isAutoRefresh = true;
                btn.innerHTML = '<i class="fas fa-clock"></i> Auto-refresh: ON';
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            fetchSensorData();
        });
    </script>
</body>
</html>
