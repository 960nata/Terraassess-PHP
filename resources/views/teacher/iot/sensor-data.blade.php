@extends('layouts.unified-layout')

@section('title', 'Data Sensor IoT - Terra Assessment')

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
    grid-template-columns: 1fr 1fr 1fr 1fr auto;
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

.data-card {
    background-color: #1e293b;
    border: 1px solid #334155;
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.data-card:hover {
    background-color: #2a2a3e;
    border-color: #475569;
}

.quality-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.quality-excellent {
    background-color: #10b981;
    color: #ffffff;
}

.quality-good {
    background-color: #3b82f6;
    color: #ffffff;
}

.quality-needs-attention {
    background-color: #f59e0b;
    color: #ffffff;
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
        <i class="fas fa-thermometer-half"></i>
        Data Sensor IoT
    </h1>
    <p class="page-description">Monitoring dan analisis data sensor real-time</p>
</div>

<!-- Filters -->
<div class="iot-filters">
    <form action="{{ route('teacher.iot.sensor-data') }}" method="GET" class="filter-form">
        <div class="filter-row">
            <div class="form-group">
                <select id="device_id" name="device_id">
                    <option value="">Semua Device</option>
                    @foreach($devices as $device)
                        <option value="{{ $device->id }}" {{ request('device_id') == $device->id ? 'selected' : '' }}>
                            {{ $device->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <select id="kelas_id" name="kelas_id">
                    <option value="">Semua Kelas</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" placeholder="Dari Tanggal">
            </div>
            
            <div class="form-group">
                <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" placeholder="Sampai Tanggal">
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-search"></i>
                    Filter
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Data Table -->
<div class="iot-table">
    <h2 style="color: #ffffff; margin-bottom: 1.5rem; font-size: 1.25rem;">
        <i class="fas fa-list me-2"></i>Data Sensor ({{ $sensorData->total() }} data)
    </h2>
    
    <!-- Desktop Table -->
    <div class="table-responsive desktop-table">
        <table class="table">
            <thead>
                <tr>
                    <th>Device</th>
                    <th>Kelas</th>
                    <th>Suhu</th>
                    <th>Kelembaban</th>
                    <th>Kel. Tanah</th>
                    <th>pH</th>
                    <th>Nitrogen</th>
                    <th>Fosfor</th>
                    <th>Kalium</th>
                    <th>Kualitas</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sensorData as $data)
                <tr>
                    <td>
                        <div style="font-weight: 600; color: #ffffff;">{{ $data->device->name ?? 'Unknown' }}</div>
                        <div style="color: #94a3b8; font-size: 0.875rem;">{{ $data->device->device_id ?? 'N/A' }}</div>
                    </td>
                    <td>{{ $data->kelas->name ?? 'Unknown' }}</td>
                    <td>
                        <span style="color: #667eea; font-weight: 600;">{{ $data->soil_temperature }}°C</span>
                        @if($data->soil_temperature > 30)
                            <i class="fas fa-exclamation-triangle" style="color: #f59e0b; font-size: 0.75rem;" title="Suhu tinggi"></i>
                        @endif
                    </td>
                    <td>
                        <span style="color: #3b82f6; font-weight: 600;">{{ $data->humidity }}%</span>
                        @if($data->humidity < 30 || $data->humidity > 80)
                            <i class="fas fa-exclamation-triangle" style="color: #f59e0b; font-size: 0.75rem;" title="Kelembaban tidak optimal"></i>
                        @endif
                    </td>
                    <td>
                        <span style="color: #10b981; font-weight: 600;">{{ $data->soil_moisture }}%</span>
                        @if($data->soil_moisture < 20 || $data->soil_moisture > 70)
                            <i class="fas fa-exclamation-triangle" style="color: #f59e0b; font-size: 0.75rem;" title="Kelembaban tanah tidak optimal"></i>
                        @endif
                    </td>
                    <td>
                        <span style="color: #8b5cf6; font-weight: 600;">{{ $data->ph_level }}</span>
                        @if($data->ph_level < 6 || $data->ph_level > 8)
                            <i class="fas fa-exclamation-triangle" style="color: #f59e0b; font-size: 0.75rem;" title="pH tidak optimal"></i>
                        @endif
                    </td>
                    <td>
                        <span style="color: #06b6d4; font-weight: 600;">{{ $data->nitrogen ? $data->nitrogen . ' ppm' : '-' }}</span>
                        @if($data->nitrogen && ($data->nitrogen < 20 || $data->nitrogen > 50))
                            <i class="fas fa-exclamation-triangle" style="color: #f59e0b; font-size: 0.75rem;" title="Nitrogen tidak optimal"></i>
                        @endif
                    </td>
                    <td>
                        <span style="color: #8b5cf6; font-weight: 600;">{{ $data->phosphorus ? $data->phosphorus . ' ppm' : '-' }}</span>
                        @if($data->phosphorus && ($data->phosphorus < 10 || $data->phosphorus > 30))
                            <i class="fas fa-exclamation-triangle" style="color: #f59e0b; font-size: 0.75rem;" title="Fosfor tidak optimal"></i>
                        @endif
                    </td>
                    <td>
                        <span style="color: #10b981; font-weight: 600;">{{ $data->potassium ? $data->potassium . ' ppm' : '-' }}</span>
                        @if($data->potassium && ($data->potassium < 15 || $data->potassium > 40))
                            <i class="fas fa-exclamation-triangle" style="color: #f59e0b; font-size: 0.75rem;" title="Kalium tidak optimal"></i>
                        @endif
                    </td>
                    <td>
                        @php
                            $quality = $data->soil_quality_status ?? 'needs_attention';
                            $qualityClass = match($quality) {
                                'excellent' => 'quality-excellent',
                                'good' => 'quality-good',
                                'needs_attention' => 'quality-needs-attention',
                                default => 'quality-needs-attention'
                            };
                            $qualityLabel = match($quality) {
                                'excellent' => 'Sangat Baik',
                                'good' => 'Baik',
                                'needs_attention' => 'Perlu Perhatian',
                                default => 'Perlu Perhatian'
                            };
                        @endphp
                        <span class="quality-badge {{ $qualityClass }}">{{ $qualityLabel }}</span>
                    </td>
                    <td>
                        <div style="font-size: 0.875rem;">
                            <div style="color: #ffffff;">{{ $data->measured_at->format('d M Y') }}</div>
                            <div style="color: #94a3b8;">{{ $data->measured_at->format('H:i:s') }}</div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 2rem; color: #94a3b8;">
                        <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                        Belum ada data sensor. Data akan muncul setelah device IoT mengirim data.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div class="mobile-cards">
        @forelse($sensorData as $data)
        <div class="data-card">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div>
                    <div style="font-weight: 600; color: #ffffff;">{{ $data->device->name ?? 'Unknown' }}</div>
                    <div style="color: #94a3b8; font-size: 0.875rem;">{{ $data->kelas->name ?? 'Unknown' }} • {{ $data->measured_at->diffForHumans() }}</div>
                </div>
                @php
                    $quality = $data->soil_quality_status ?? 'needs_attention';
                    $qualityClass = match($quality) {
                        'excellent' => 'quality-excellent',
                        'good' => 'quality-good',
                        'needs_attention' => 'quality-needs-attention',
                        default => 'quality-needs-attention'
                    };
                    $qualityLabel = match($quality) {
                        'excellent' => 'Sangat Baik',
                        'good' => 'Baik',
                        'needs_attention' => 'Perlu Perhatian',
                        default => 'Perlu Perhatian'
                    };
                @endphp
                <span class="quality-badge {{ $qualityClass }}">{{ $qualityLabel }}</span>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem;">
                <div>
                    <div style="color: #94a3b8; font-size: 0.75rem;">Suhu</div>
                    <div style="color: #667eea; font-weight: 600; font-size: 1.1rem;">{{ $data->soil_temperature }}°C</div>
                </div>
                <div>
                    <div style="color: #94a3b8; font-size: 0.75rem;">Kelembaban</div>
                    <div style="color: #3b82f6; font-weight: 600; font-size: 1.1rem;">{{ $data->humidity }}%</div>
                </div>
                <div>
                    <div style="color: #94a3b8; font-size: 0.75rem;">Kel. Tanah</div>
                    <div style="color: #10b981; font-weight: 600; font-size: 1.1rem;">{{ $data->soil_moisture }}%</div>
                </div>
                <div>
                    <div style="color: #94a3b8; font-size: 0.75rem;">pH</div>
                    <div style="color: #8b5cf6; font-weight: 600; font-size: 1.1rem;">{{ $data->ph_level }}</div>
                </div>
                <div>
                    <div style="color: #94a3b8; font-size: 0.75rem;">Nitrogen</div>
                    <div style="color: #06b6d4; font-weight: 600; font-size: 1.1rem;">{{ $data->nitrogen ? $data->nitrogen . ' ppm' : '-' }}</div>
                </div>
                <div>
                    <div style="color: #94a3b8; font-size: 0.75rem;">Fosfor</div>
                    <div style="color: #8b5cf6; font-weight: 600; font-size: 1.1rem;">{{ $data->phosphorus ? $data->phosphorus . ' ppm' : '-' }}</div>
                </div>
                <div>
                    <div style="color: #94a3b8; font-size: 0.75rem;">Kalium</div>
                    <div style="color: #10b981; font-weight: 600; font-size: 1.1rem;">{{ $data->potassium ? $data->potassium . ' ppm' : '-' }}</div>
                </div>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 3rem; color: #94a3b8;">
            <i class="fas fa-inbox" style="font-size: 4rem; margin-bottom: 1rem; display: block;"></i>
            <h3 style="color: #ffffff; margin-bottom: 0.5rem;">Belum Ada Data Sensor</h3>
            <p>Data akan muncul setelah device IoT mengirim data</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($sensorData->hasPages())
    <div style="margin-top: 2rem; display: flex; justify-content: center;">
        {{ $sensorData->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<script>
// Auto refresh every 30 seconds
setInterval(function() {
    if (!document.hidden) {
        location.reload();
    }
}, 30000);
</script>
@endsection
