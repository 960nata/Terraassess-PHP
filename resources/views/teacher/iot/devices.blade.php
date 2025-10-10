@extends('layouts.unified-layout')

@section('title', 'Manajemen Device IoT - Terra Assessment')

@section('styles')
<style>
.iot-filters {
    background-color: #1e293b;
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid #334155;
}

.filter-row {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr auto;
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

.btn-primary {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: #ffffff;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    background: #334155;
    color: #ffffff;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: #475569;
}

.btn-success {
    background: #10b981;
    color: #ffffff;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-success:hover {
    background: #059669;
}

.iot-table {
    background-color: #1e293b;
    border-radius: 1rem;
    padding: 2rem;
    border: 1px solid #334155;
    overflow-x: auto;
}

.device-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
}

.device-card {
    background-color: #1e293b;
    border: 1px solid #334155;
    border-radius: 1rem;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.device-card:hover {
    background-color: #2a2a3e;
    border-color: #475569;
    transform: translateY(-2px);
}

.device-title {
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 0.25rem;
}

.device-description {
    color: #94a3b8;
    font-size: 0.875rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-online {
    background-color: #10b981;
    color: #ffffff;
}

.status-offline {
    background-color: #ef4444;
    color: #ffffff;
}

.sensor-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #667eea;
}

.sensor-label {
    font-size: 0.75rem;
    color: #94a3b8;
    margin-top: 0.25rem;
}

.device-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
}

@media (max-width: 768px) {
    .filter-row {
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }
    
    .device-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .filter-row {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
}
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-microchip"></i>
        Manajemen Device IoT
    </h1>
    <p class="page-description">Kelola dan monitoring device IoT yang terdaftar</p>
</div>

<!-- Stats Overview -->
<div class="exam-type-cards">
    <div class="exam-type-card">
        <div class="exam-card-header">
            <div class="exam-card-icon">
                <i class="fas fa-microchip"></i>
            </div>
            <div class="exam-card-stats">
                <span class="exam-count">{{ $devices->count() }}</span>
                <span class="exam-label">Device</span>
            </div>
        </div>
        <div class="exam-card-content">
            <h3 class="exam-type-title">Total Device</h3>
            <p class="exam-type-description">Device IoT terdaftar</p>
        </div>
    </div>

    <div class="exam-type-card essay">
        <div class="exam-card-header">
            <div class="exam-card-icon">
                <i class="fas fa-wifi"></i>
            </div>
            <div class="exam-card-stats">
                <span class="exam-count">{{ $devices->where('status', 'online')->count() }}</span>
                <span class="exam-label">Online</span>
            </div>
        </div>
        <div class="exam-card-content">
            <h3 class="exam-type-title">Device Online</h3>
            <p class="exam-type-description">Device aktif saat ini</p>
        </div>
    </div>
</div>

<!-- Device List -->
<div class="iot-table">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="color: #ffffff; font-size: 1.25rem; margin: 0;">
            <i class="fas fa-list me-2"></i>Daftar Device
        </h2>
        <div style="display: flex; gap: 0.5rem;">
            <button onclick="location.reload()" class="btn-secondary">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>
    
    @if($devices->count() > 0)
    <div class="device-grid">
        @foreach($devices as $device)
        <div class="device-card">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div>
                    <div class="device-title">{{ $device->name }}</div>
                    <div class="device-description">{{ $device->device_id }}</div>
                </div>
                <span class="status-badge status-{{ $device->isOnline() ? 'online' : 'offline' }}">
                    {{ $device->isOnline() ? 'Online' : 'Offline' }}
                </span>
            </div>

            <!-- Device Info -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #334155;">
                <div>
                    <div style="color: #94a3b8; font-size: 0.75rem;">Type</div>
                    <div style="color: #ffffff; font-size: 0.875rem;">{{ $device->device_type ?? 'Unknown' }}</div>
                </div>
                <div>
                    <div style="color: #94a3b8; font-size: 0.75rem;">Status</div>
                    <div style="color: #ffffff; font-size: 0.875rem; text-transform: capitalize;">{{ $device->status ?? 'Unknown' }}</div>
                </div>
                <div>
                    <div style="color: #94a3b8; font-size: 0.75rem;">Last Seen</div>
                    <div style="color: #ffffff; font-size: 0.875rem;">{{ $device->last_seen ? $device->last_seen->diffForHumans() : 'Never' }}</div>
                </div>
                <div>
                    <div style="color: #94a3b8; font-size: 0.75rem;">Total Data</div>
                    <div style="color: #ffffff; font-size: 0.875rem;">{{ $device->sensorData->count() }}</div>
                </div>
            </div>

            <!-- Latest Sensor Data -->
            @if($device->latestSensorData)
            <div style="margin-bottom: 1rem;">
                <h5 style="color: #ffffff; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.75rem;">Data Terbaru</h5>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem;">
                    <div style="text-align: center;">
                        <div class="sensor-value">{{ $device->latestSensorData->temperature }}°</div>
                        <div class="sensor-label">Suhu</div>
                    </div>
                    <div style="text-align: center;">
                        <div class="sensor-value">{{ $device->latestSensorData->humidity }}%</div>
                        <div class="sensor-label">Kelembaban</div>
                    </div>
                    <div style="text-align: center;">
                        <div class="sensor-value">{{ $device->latestSensorData->soil_moisture }}%</div>
                        <div class="sensor-label">Tanah</div>
                    </div>
                    <div style="text-align: center;">
                        <div class="sensor-value">{{ $device->latestSensorData->ph_level }}</div>
                        <div class="sensor-label">pH</div>
                    </div>
                </div>
                <div style="text-align: center; margin-top: 0.5rem;">
                    <span style="color: #94a3b8; font-size: 0.75rem;">
                        {{ $device->latestSensorData->measured_at->diffForHumans() }}
                    </span>
                </div>
            </div>
            @else
            <div style="text-align: center; padding: 1rem; color: #94a3b8;">
                <i class="fas fa-thermometer" style="font-size: 2rem; margin-bottom: 0.5rem; display: block;"></i>
                <p style="font-size: 0.875rem;">Belum ada data sensor</p>
            </div>
            @endif

            <!-- Actions -->
            <div class="device-actions">
                <a href="{{ route('teacher.iot.sensor-data', ['device_id' => $device->id]) }}" class="btn-success" style="flex: 1; text-align: center; text-decoration: none;">
                    <i class="fas fa-chart-line"></i> Lihat Data
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div style="text-align: center; padding: 3rem; color: #94a3b8;">
        <i class="fas fa-microchip" style="font-size: 4rem; margin-bottom: 1rem; display: block;"></i>
        <h3 style="color: #ffffff; margin-bottom: 0.5rem;">Belum Ada Device</h3>
        <p>Device IoT akan muncul di sini setelah terdaftar dalam sistem</p>
    </div>
    @endif
</div>

<script>
// Auto refresh device status every 30 seconds
setInterval(function() {
    if (!document.hidden) {
        fetch('{{ route("teacher.iot.device-status") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update device status indicators
                    data.devices.forEach(device => {
                        const statusElement = document.querySelector(`[data-device-id="${device.id}"] .status-badge`);
                        if (statusElement) {
                            statusElement.className = `status-badge ${device.is_online ? 'status-online' : 'status-offline'}`;
                            statusElement.textContent = device.is_online ? 'Online' : 'Offline';
                        }
                    });
                }
            })
            .catch(error => console.error('Error fetching device status:', error));
    }
}, 30000);
</script>
@endsection
