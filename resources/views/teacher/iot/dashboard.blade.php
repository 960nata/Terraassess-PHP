@extends('layouts.unified-layout')

@section('title', 'IoT Dashboard - Terra Assessment')

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

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th,
.table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #334155;
}

.table th {
    background-color: #2a2a3e;
    color: #ffffff;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.table td {
    color: #cbd5e1;
}

.table tbody tr:hover {
    background-color: #2a2a3e;
}

.mobile-cards {
    display: none;
}

.device-card {
    background-color: #1e293b;
    border: 1px solid #334155;
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.device-card:hover {
    background-color: #2a2a3e;
    border-color: #475569;
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

.device-actions {
    display: flex;
    gap: 0.5rem;
}

@media (max-width: 768px) {
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

<!-- Stats Overview -->
<div class="exam-type-cards">
    <div class="exam-type-card">
        <div class="exam-card-header">
            <div class="exam-card-icon">
                <i class="fas fa-microchip"></i>
            </div>
            <div class="exam-card-stats">
                <span class="exam-count">{{ $totalDevices }}</span>
                <span class="exam-label">Device</span>
            </div>
        </div>
        <div class="exam-card-content">
            <h3 class="exam-type-title">Total Device</h3>
            <p class="exam-type-description">Device IoT terdaftar di kelas Anda</p>
        </div>
    </div>

    <div class="exam-type-card essay">
        <div class="exam-card-header">
            <div class="exam-card-icon">
                <i class="fas fa-wifi"></i>
            </div>
            <div class="exam-card-stats">
                <span class="exam-count">{{ $onlineDevices }}</span>
                <span class="exam-label">Online</span>
            </div>
        </div>
        <div class="exam-card-content">
            <h3 class="exam-type-title">Device Online</h3>
            <p class="exam-type-description">Device yang aktif saat ini</p>
        </div>
    </div>
</div>

<!-- Device List Table -->
<div class="iot-table">
    <h2 style="color: #ffffff; margin-bottom: 1.5rem; font-size: 1.25rem;">
        <i class="fas fa-list me-2"></i>Daftar Device & Kelas
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
    <h2 style="color: #ffffff; margin-bottom: 1.5rem; font-size: 1.25rem;">
        <i class="fas fa-clock me-2"></i>Data Sensor Terbaru
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
</script>
@endsection
