@extends('layouts.unified-layout')

@section('title', 'IoT Dashboard - Terra Assessment')

@section('styles')
<style>
/* IoT Action Cards - Modern Design */
.iot-action-cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.iot-action-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    padding: 2rem;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.iot-action-card:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(59, 130, 246, 0.5);
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
}

.iot-action-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.iot-action-card.success::before {
    background: linear-gradient(90deg, #10b981, #059669);
}

.iot-action-card.info::before {
    background: linear-gradient(90deg, #3b82f6, #1d4ed8);
}

.iot-action-card.warning::before {
    background: linear-gradient(90deg, #f59e0b, #d97706);
}

.action-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
}

.action-card-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.iot-action-card.success .action-card-icon {
    background: linear-gradient(135deg, #10b981, #059669);
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
}

.iot-action-card.info .action-card-icon {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
}

.iot-action-card.warning .action-card-icon {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
}

.action-card-stats {
    text-align: right;
}

.action-count {
    display: block;
    font-size: 2rem;
    font-weight: 700;
    color: #ffffff;
    line-height: 1;
}

.action-label {
    font-size: 0.875rem;
    color: #94a3b8;
    font-weight: 500;
}

.action-card-content {
    margin-bottom: 1.5rem;
}

.action-type-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 0.5rem;
}

.action-type-description {
    color: #cbd5e1;
    font-size: 0.875rem;
    line-height: 1.5;
    margin: 0;
}

.action-card-footer {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding-top: 1rem;
}

.action-card-action {
    color: #667eea;
    font-weight: 600;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.arrow-icon {
    transition: transform 0.3s ease;
}

.iot-action-card:hover .arrow-icon {
    transform: translateX(4px);
}

.iot-action-card:hover .action-card-action {
    color: #ffffff;
}

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
    background: linear-gradient(45deg, #10b981, #059669);
    color: #ffffff;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.btn-success:hover {
    background: linear-gradient(45deg, #059669, #047857);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.iot-table {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    border-radius: 1rem;
    padding: 2rem;
    border: 1px solid #475569;
    overflow-x: auto;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.iot-table h2 {
    color: #ffffff;
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.iot-table h2 i {
    color: #667eea;
}

.table {
    width: 100%;
    border-collapse: collapse;
    background: transparent;
}

.table th,
.table td {
    padding: 1.25rem 1rem;
    text-align: left;
    border-bottom: 1px solid #475569;
}

.table th {
    background: linear-gradient(135deg, #2a2a3e 0%, #374151 100%);
    color: #ffffff;
    font-weight: 700;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    position: sticky;
    top: 0;
    z-index: 10;
}

.table td {
    color: #e2e8f0;
    font-weight: 500;
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background: linear-gradient(135deg, #2a2a3e 0%, #374151 100%);
    transform: scale(1.01);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.mobile-cards {
    display: none;
}

.device-card {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    border: 1px solid #475569;
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.device-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.device-card:hover {
    background: linear-gradient(135deg, #2a2a3e 0%, #374151 100%);
    border-color: #667eea;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.device-title {
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 0.25rem;
    font-size: 1.1rem;
}

.device-description {
    color: #cbd5e1;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-badge {
    padding: 0.375rem 0.875rem;
    border-radius: 25px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.status-online {
    background: linear-gradient(135deg, #10b981, #059669);
    color: #ffffff;
    box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
}

.status-offline {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #ffffff;
    box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
}

.device-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .iot-action-cards {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .iot-action-card {
        padding: 1.5rem;
    }
    
    .action-card-icon {
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
    }
    
    .action-count {
        font-size: 1.5rem;
    }
    
    .action-type-title {
        font-size: 1.1rem;
    }
    
    .filter-row {
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }
    
    .desktop-table {
        display: none;
    }
    
    .mobile-cards {
        display: block;
    }
}

@media (max-width: 480px) {
    .iot-action-cards {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
    
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
        <i class="fas fa-wifi"></i>
        IoT Dashboard
    </h1>
    <p class="page-description">Monitoring device IoT dan data sensor real-time</p>
</div>

<!-- IoT Action Cards -->
<div class="iot-action-cards">
    <div class="iot-action-card primary" onclick="viewAllDevices()">
        <div class="action-card-header">
            <div class="action-card-icon">
                <i class="fas fa-microchip"></i>
            </div>
            <div class="action-card-stats">
                <span class="action-count">{{ $totalDevices }}</span>
                <span class="action-label">Device</span>
            </div>
        </div>
        <div class="action-card-content">
            <h3 class="action-type-title">Total Device</h3>
            <p class="action-type-description">Device IoT terdaftar di kelas Anda</p>
        </div>
        <div class="action-card-footer">
            <span class="action-card-action">Lihat Semua <i class="fas fa-arrow-right arrow-icon"></i></span>
        </div>
    </div>

    <div class="iot-action-card success" onclick="viewOnlineDevices()">
        <div class="action-card-header">
            <div class="action-card-icon">
                <i class="fas fa-wifi"></i>
            </div>
            <div class="action-card-stats">
                <span class="action-count">{{ $onlineDevices }}</span>
                <span class="action-label">Online</span>
            </div>
        </div>
        <div class="action-card-content">
            <h3 class="action-type-title">Device Online</h3>
            <p class="action-type-description">Device yang aktif dan terhubung</p>
        </div>
        <div class="action-card-footer">
            <span class="action-card-action">Monitor Real-time <i class="fas fa-arrow-right arrow-icon"></i></span>
        </div>
    </div>

    <div class="iot-action-card info" onclick="viewSensorData()">
        <div class="action-card-header">
            <div class="action-card-icon">
                <i class="fas fa-database"></i>
            </div>
            <div class="action-card-stats">
                <span class="action-count">{{ $totalReadings }}</span>
                <span class="action-label">Data</span>
            </div>
        </div>
        <div class="action-card-content">
            <h3 class="action-type-title">Data Sensor</h3>
            <p class="action-type-description">Total data sensor yang terkumpul</p>
        </div>
        <div class="action-card-footer">
            <span class="action-card-action">Analisis Data <i class="fas fa-arrow-right arrow-icon"></i></span>
        </div>
    </div>

    <div class="iot-action-card warning" onclick="viewTodayData()">
        <div class="action-card-header">
            <div class="action-card-icon">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="action-card-stats">
                <span class="action-count">{{ $todayReadings }}</span>
                <span class="action-label">Hari Ini</span>
            </div>
        </div>
        <div class="action-card-content">
            <h3 class="action-type-title">Data Hari Ini</h3>
            <p class="action-type-description">Pembacaan sensor hari ini</p>
        </div>
        <div class="action-card-footer">
            <span class="action-card-action">Lihat Detail <i class="fas fa-arrow-right arrow-icon"></i></span>
        </div>
    </div>
</div>

<!-- Device List Table -->
<div class="iot-table">
    <h2>
        <i class="fas fa-list"></i>
        Daftar Device & Kelas
    </h2>
    
    <!-- Desktop Table -->
    <div class="table-responsive desktop-table">
        <table class="table">
            <thead>
                <tr>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Total Data</th>
                    <th>Data Hari Ini</th>
                    <th>Device</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignedKelas as $kelasId => $kelasMapel)
                    @php 
                        $kelas = $kelasMapel->first()->kelas;
                        $totalDataKelas = \App\Models\IotSensorData::where('kelas_id', $kelasId)->count();
                        $todayDataKelas = \App\Models\IotSensorData::where('kelas_id', $kelasId)->whereDate('measured_at', today())->count();
                    @endphp
                    <tr>
                        <td>
                            <div class="device-title">{{ $kelas->name }}</div>
                        </td>
                        <td>
                            <div class="device-description">
                                {{ $kelasMapel->pluck('mapel.name')->unique()->implode(', ') }}
                            </div>
                        </td>
                        <td>
                            <span style="color: #667eea; font-weight: 600;">{{ $totalDataKelas }}</span>
                        </td>
                        <td>
                            <span style="color: #10b981; font-weight: 600;">{{ $todayDataKelas }}</span>
                        </td>
                        <td>
                            @php
                                $devicesCount = $devices->filter(function($device) use ($kelasId) {
                                    return $device->sensorData->where('kelas_id', $kelasId)->count() > 0;
                                })->count();
                            @endphp
                            <span class="status-badge status-{{ $devicesCount > 0 ? 'online' : 'offline' }}">
                                {{ $devicesCount }} Device
                            </span>
                        </td>
                        <td>
                            <div class="device-actions">
                                <a href="{{ route('teacher.iot.class', $kelasId) }}" class="btn-success">
                                    <i class="fas fa-eye"></i> Lihat Data
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem; color: #94a3b8;">
                            <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                            Belum ada kelas yang ditugaskan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div class="mobile-cards">
        @forelse($assignedKelas as $kelasId => $kelasMapel)
            @php 
                $kelas = $kelasMapel->first()->kelas;
                $totalDataKelas = \App\Models\IotSensorData::where('kelas_id', $kelasId)->count();
                $todayDataKelas = \App\Models\IotSensorData::where('kelas_id', $kelasId)->whereDate('measured_at', today())->count();
                $devicesCount = $devices->filter(function($device) use ($kelasId) {
                    return $device->sensorData->where('kelas_id', $kelasId)->count() > 0;
                })->count();
            @endphp
            <div class="device-card">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <h3 class="device-title">{{ $kelas->name }}</h3>
                    <span class="status-badge status-{{ $devicesCount > 0 ? 'online' : 'offline' }}">
                        {{ $devicesCount }} Device
                    </span>
                </div>
                
                <p class="device-description">{{ $kelasMapel->pluck('mapel.name')->unique()->implode(', ') }}</p>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin: 1rem 0;">
                    <div>
                        <div style="color: #94a3b8; font-size: 0.75rem;">Total Data</div>
                        <div style="color: #667eea; font-weight: 600; font-size: 1.25rem;">{{ $totalDataKelas }}</div>
                    </div>
                    <div>
                        <div style="color: #94a3b8; font-size: 0.75rem;">Data Hari Ini</div>
                        <div style="color: #10b981; font-weight: 600; font-size: 1.25rem;">{{ $todayDataKelas }}</div>
                    </div>
                </div>
                
                <div class="device-actions" style="margin-top: 1rem;">
                    <a href="{{ route('teacher.iot.class', $kelasId) }}" class="btn-success" style="flex: 1; text-align: center; text-decoration: none;">
                        <i class="fas fa-eye"></i> Lihat Data
                    </a>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 3rem; color: #94a3b8;">
                <i class="fas fa-inbox" style="font-size: 4rem; margin-bottom: 1rem; display: block;"></i>
                <h3 style="color: #ffffff; margin-bottom: 0.5rem;">Belum Ada Kelas</h3>
                <p>Kelas yang ditugaskan akan muncul di sini</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Recent Sensor Data -->
@if($recentData->count() > 0)
<div class="iot-table" style="margin-top: 2rem;">
    <h2>
        <i class="fas fa-clock"></i>
        Data Sensor Terbaru
    </h2>
    
    <div class="table-responsive desktop-table">
        <table class="table">
            <thead>
                <tr>
                    <th>Device</th>
                    <th>Kelas</th>
                    <th>Suhu</th>
                    <th>Kelembaban</th>
                    <th>Kelembaban Tanah</th>
                    <th>pH</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentData->take(5) as $data)
                <tr>
                    <td>
                        <div class="device-title">{{ $data->device->name ?? 'Unknown' }}</div>
                        <div class="device-description">{{ $data->device->device_id ?? 'N/A' }}</div>
                    </td>
                    <td>{{ $data->kelas->name ?? 'N/A' }}</td>
                    <td><span style="color: #667eea; font-weight: 600;">{{ $data->temperature }}°C</span></td>
                    <td><span style="color: #3b82f6; font-weight: 600;">{{ $data->humidity }}%</span></td>
                    <td><span style="color: #10b981; font-weight: 600;">{{ $data->soil_moisture }}%</span></td>
                    <td><span style="color: #8b5cf6; font-weight: 600;">{{ $data->ph_level }}</span></td>
                    <td>
                        <div style="font-size: 0.875rem;">{{ $data->measured_at->diffForHumans() }}</div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards for Recent Data -->
    <div class="mobile-cards">
        @foreach($recentData->take(5) as $data)
        <div class="device-card">
            <div style="margin-bottom: 0.75rem;">
                <div class="device-title">{{ $data->device->name ?? 'Unknown' }}</div>
                <div class="device-description">{{ $data->kelas->name ?? 'N/A' }} • {{ $data->measured_at->diffForHumans() }}</div>
            </div>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem;">
                <div>
                    <div style="color: #94a3b8; font-size: 0.75rem;">Suhu</div>
                    <div style="color: #667eea; font-weight: 600;">{{ $data->temperature }}°C</div>
                </div>
                <div>
                    <div style="color: #94a3b8; font-size: 0.75rem;">Kelembaban</div>
                    <div style="color: #3b82f6; font-weight: 600;">{{ $data->humidity }}%</div>
                </div>
                <div>
                    <div style="color: #94a3b8; font-size: 0.75rem;">Kel. Tanah</div>
                    <div style="color: #10b981; font-weight: 600;">{{ $data->soil_moisture }}%</div>
                </div>
                <div>
                    <div style="color: #94a3b8; font-size: 0.75rem;">pH</div>
                    <div style="color: #8b5cf6; font-weight: 600;">{{ $data->ph_level }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<script>
// Auto refresh every 30 seconds
setInterval(function() {
    location.reload();
}, 30000);

// Add smooth animations on page load
document.addEventListener('DOMContentLoaded', function() {
    // Animate action cards
    const actionCards = document.querySelectorAll('.iot-action-card');
    actionCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 150);
    });
    
    // Animate table rows
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateX(-20px)';
        
        setTimeout(() => {
            row.style.transition = 'all 0.4s ease';
            row.style.opacity = '1';
            row.style.transform = 'translateX(0)';
        }, 800 + (index * 50));
    });
    
    // Add pulse animation to online devices
    const onlineBadges = document.querySelectorAll('.status-online');
    onlineBadges.forEach(badge => {
        badge.style.animation = 'pulse 2s infinite';
    });
});

// Action card functions
function viewAllDevices() {
    // Scroll to device table
    document.querySelector('.iot-table').scrollIntoView({ 
        behavior: 'smooth',
        block: 'start'
    });
}

function viewOnlineDevices() {
    // Filter to show only online devices
    alert('Fitur filter device online akan segera tersedia!');
}

function viewSensorData() {
    // Scroll to recent data section
    const recentData = document.querySelector('.iot-table:last-of-type');
    if (recentData) {
        recentData.scrollIntoView({ 
            behavior: 'smooth',
            block: 'start'
        });
    }
}

function viewTodayData() {
    // Show today's data
    alert('Fitur data hari ini akan segera tersedia!');
}

// Add pulse animation CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
`;
document.head.appendChild(style);
</script>
@endsection
