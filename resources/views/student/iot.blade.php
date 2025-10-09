@extends('layouts.unified-layout-consistent')

@section('title', 'Terra Assessment - IoT Research')
@section('page-title', 'IoT Research')
@section('page-description', 'Kumpulkan dan analisis data sensor IoT untuk penelitian Anda')

@section('content')
<div class="space-y-6">
    <div class="iot-container">
    <div class="glass-card">
        <div class="iot-header">
            <h2 class="iot-title">Sistem Monitoring IoT</h2>
            <div class="iot-meta">
                <div class="meta-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span id="currentDateTime"></span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-circle"></i>
                    <span id="connectionStatus">Terputus</span>
                </div>
            </div>
        </div>

        <div class="iot-content">
            <div class="iot-description">
                <h3>Deskripsi Sistem</h3>
                <p>Gunakan perangkat IoT untuk mengumpulkan data sensor tanah seperti suhu, kelembaban, dan kadar humus. Data ini dapat digunakan untuk penelitian dan analisis kualitas tanah.</p>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-database"></i>
                    </div>
                    <div class="stats-number">{{ $myReadingsStats['total'] }}</div>
                    <div class="stats-label">Total Data Saya</div>
                </div>
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="stats-number">{{ $myReadingsStats['today_count'] }}</div>
                    <div class="stats-label">Data Hari Ini</div>
                </div>
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-thermometer-half"></i>
                    </div>
                    <div class="stats-number">{{ $myReadingsStats['avg_temperature'] ? number_format($myReadingsStats['avg_temperature'], 1) . '°C' : 'N/A' }}</div>
                    <div class="stats-label">Suhu Rata-rata</div>
                </div>
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-tint"></i>
                    </div>
                    <div class="stats-number">{{ $myReadingsStats['avg_moisture'] ? number_format($myReadingsStats['avg_moisture'], 1) . '%' : 'N/A' }}</div>
                    <div class="stats-label">Kelembaban Rata-rata</div>
                </div>
            </div>

            <!-- IoT Control Panel -->
            <div class="iot-control-panel">
                <h3>Kontrol Perangkat IoT</h3>
                
                <div class="control-form">
                    <div class="form-group">
                        <label>Lokasi Pengukuran</label>
                        <input type="text" id="location" placeholder="Masukkan lokasi pengukuran">
                    </div>
                    
                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea id="notes" rows="3" placeholder="Masukkan catatan tambahan"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Kelas</label>
                        <select id="selectKelas">
                            <option value="">-- Pilih Kelas --</option>
                            @if(Auth::user()->kelas_id)
                                <option value="{{ Auth::user()->kelas_id }}" selected>{{ Auth::user()->kelas->name ?? 'Kelas Saya' }}</option>
                            @endif
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Mode Pengukuran</label>
                        <select id="measurementMode">
                            <option value="auto">Otomatis (dari alat IoT)</option>
                            <option value="manual">Manual (input sendiri)</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button class="btn btn-primary" id="scanDeviceBtn" onclick="scanDevice()">
                        <i class="fas fa-bluetooth"></i>
                        Scan & Ambil Data
                    </button>
                    <button class="btn btn-success" id="saveDataBtn" onclick="saveData()" disabled>
                        <i class="fas fa-save"></i>
                        Simpan Data
                    </button>
                </div>
            </div>

            <!-- Real-time Data Display -->
            <div class="real-time-display" id="realTimeDataSection" style="display: none;">
                <h3>Data Real-time</h3>
                
                <div class="sensor-grid">
                    <div class="sensor-card">
                        <i class="fas fa-thermometer-half"></i>
                        <h4 id="realTimeTemp">--°C</h4>
                        <small>Suhu Tanah</small>
                    </div>
                    <div class="sensor-card">
                        <i class="fas fa-seedling"></i>
                        <h4 id="realTimeHumus">--%</h4>
                        <small>Kadar Humus</small>
                    </div>
                    <div class="sensor-card">
                        <i class="fas fa-tint"></i>
                        <h4 id="realTimeMoisture">--%</h4>
                        <small>Kelembaban Tanah</small>
                    </div>
                </div>
            </div>

            <!-- Manual Input Form -->
            <div class="manual-input-section" id="manualInputSection" style="display: none;">
                <h3>Input Data Manual</h3>
                
                <div class="input-grid">
                    <div class="form-group">
                        <label>Suhu Tanah (°C)</label>
                        <input type="number" id="manualTemp" step="0.1" placeholder="Masukkan suhu">
                    </div>
                    <div class="form-group">
                        <label>Kadar Humus (%)</label>
                        <input type="number" id="manualHumus" step="0.1" placeholder="Masukkan kadar humus">
                    </div>
                    <div class="form-group">
                        <label>Kelembaban Tanah (%)</label>
                        <input type="number" id="manualMoisture" step="0.1" placeholder="Masukkan kelembaban">
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="data-section">
                <div class="data-header">
                    <h3>Data Saya</h3>
                    <div class="data-actions">
                        <button class="btn btn-secondary" onclick="refreshData()">
                            <i class="fas fa-sync-alt"></i>
                            Refresh
                        </button>
                        <button class="btn btn-success" onclick="exportMyData()">
                            <i class="fas fa-download"></i>
                            Export CSV
                        </button>
                    </div>
                </div>
                
                <div class="data-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Kelas</th>
                                <th>Suhu</th>
                                <th>Humus</th>
                                <th>Kelembaban</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($myReadings as $reading)
                                <tr>
                                    <td>{{ $reading->timestamp ? (is_string($reading->timestamp) ? \Carbon\Carbon::parse($reading->timestamp)->format('d/m/Y H:i') : $reading->timestamp->format('d/m/Y H:i')) : '-' }}</td>
                                    <td>{{ $reading->kelas->name ?? $reading->class_id }}</td>
                                    <td>{{ $reading->formatted_soil_temperature }}</td>
                                    <td>{{ $reading->formatted_soil_humus }}</td>
                                    <td>{{ $reading->formatted_soil_moisture }}</td>
                                    <td>{{ $reading->location ?? '-' }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $reading->soil_quality_color }}">
                                            {{ $reading->soil_quality_status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="pagination">
                    {{ $myReadings->links() }}
                </div>
            </div>

            <!-- Class Data (if available) -->
            @if($classReadings->count() > 0)
            <div class="data-section">
                <h3>Data Kelas</h3>
                
                <div class="data-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Siswa</th>
                                <th>Suhu</th>
                                <th>Humus</th>
                                <th>Kelembaban</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($classReadings as $reading)
                                <tr>
                                    <td>{{ $reading->timestamp ? (is_string($reading->timestamp) ? \Carbon\Carbon::parse($reading->timestamp)->format('d/m/Y H:i') : $reading->timestamp->format('d/m/Y H:i')) : '-' }}</td>
                                    <td>{{ $reading->student->name ?? $reading->student_id }}</td>
                                    <td>{{ $reading->formatted_soil_temperature }}</td>
                                    <td>{{ $reading->formatted_soil_humus }}</td>
                                    <td>{{ $reading->formatted_soil_moisture }}</td>
                                    <td>{{ $reading->location ?? '-' }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $reading->soil_quality_color }}">
                                            {{ $reading->soil_quality_status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
/* Student IoT Management Styles - Consistent with Superadmin */
    .iot-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1rem;
        min-height: 100vh;
}

.glass-card {
    background: rgba(15, 23, 42, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 20px;
    padding: 2rem;
    margin: 1.5rem 0;
    box-shadow: 
        0 20px 40px rgba(0, 0, 0, 0.3),
        0 0 0 1px rgba(255, 255, 255, 0.05),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.glass-card:hover {
    transform: translateY(-8px) scale(1.02);
    border-color: rgba(59, 130, 246, 0.4);
    box-shadow: 
        0 32px 64px rgba(0, 0, 0, 0.4),
        0 0 0 1px rgba(59, 130, 246, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.2);
}

.iot-header {
    text-align: center;
    margin-bottom: 2rem;
    padding: 2rem 0;
    border-bottom: 2px solid rgba(59, 130, 246, 0.2);
    position: relative;
}

.iot-header::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 2px;
    background: linear-gradient(90deg, transparent, #3b82f6, transparent);
}

.iot-title {
    color: #ffffff;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    background: linear-gradient(135deg, #3b82f6, #8b5cf6, #06b6d4);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: 0 0 30px rgba(59, 130, 246, 0.3);
}

.iot-meta {
    display: flex;
    justify-content: center;
    gap: 2rem;
    flex-wrap: wrap;
    margin-top: 1rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #cbd5e1;
    font-size: 1rem;
    padding: 0.75rem 1.5rem;
    background: rgba(59, 130, 246, 0.1);
    border-radius: 50px;
    border: 1px solid rgba(59, 130, 246, 0.2);
    transition: all 0.3s ease;
}

.meta-item:hover {
    background: rgba(59, 130, 246, 0.2);
    transform: translateY(-2px);
}

.meta-item i {
    color: #3b82f6;
    font-size: 1.1rem;
    filter: drop-shadow(0 0 8px rgba(59, 130, 246, 0.5));
}

.iot-content {
    color: #ffffff;
}

.iot-description {
    margin-bottom: 2rem;
}

.iot-description h3 {
    color: #ffffff;
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.iot-description h3::before {
    content: "🔬";
    font-size: 1.5rem;
}

.iot-description p {
    color: #cbd5e1;
    line-height: 1.6;
    font-size: 1rem;
    background: rgba(255, 255, 255, 0.05);
    padding: 1rem;
    border-radius: 8px;
    border-left: 4px solid #3b82f6;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stats-card {
    background: rgba(15, 23, 42, 0.9);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 20px;
    padding: 2rem;
    text-align: center;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    min-height: 160px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #3b82f6, #8b5cf6, #06b6d4);
}

.stats-card:hover {
    transform: translateY(-8px) scale(1.05);
    border-color: rgba(59, 130, 246, 0.4);
    box-shadow: 
        0 20px 40px rgba(0, 0, 0, 0.3),
        0 0 0 1px rgba(59, 130, 246, 0.1);
}

.stats-icon {
    font-size: 3rem;
    color: #3b82f6;
    margin-bottom: 1rem;
    filter: drop-shadow(0 0 20px rgba(59, 130, 246, 0.5));
    transition: all 0.3s ease;
}

.stats-card:hover .stats-icon {
    transform: scale(1.1) rotate(5deg);
    filter: drop-shadow(0 0 30px rgba(59, 130, 246, 0.8));
}

.stats-number {
    font-size: 2.5rem;
    font-weight: 800;
    color: #ffffff;
    margin-bottom: 0.75rem;
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
}

.stats-label {
    font-size: 1rem;
    color: #cbd5e1;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.iot-control-panel {
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
}

.iot-control-panel:hover {
    transform: translateY(-2px);
    border-color: rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.iot-control-panel h3 {
    color: #ffffff;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.iot-control-panel h3::before {
    content: "🎛️";
    font-size: 1.2rem;
}

.control-form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    color: #ffffff;
    font-weight: 600;
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 0.5rem 0.75rem;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 6px;
    color: #ffffff;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    width: 100%;
    box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    background: rgba(255, 255, 255, 0.15);
}

.form-group input::placeholder,
.form-group textarea::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

.form-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    min-width: 140px;
    justify-content: center;
    position: relative;
    overflow: hidden;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
}

.btn-primary {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8, #7c3aed);
    color: #ffffff;
    box-shadow: 
        0 8px 25px rgba(59, 130, 246, 0.4),
        0 0 0 1px rgba(59, 130, 246, 0.1);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #2563eb, #1e40af, #6d28d9);
    box-shadow: 
        0 12px 35px rgba(59, 130, 246, 0.6),
        0 0 0 1px rgba(59, 130, 246, 0.2);
    transform: translateY(-4px) scale(1.05);
}

.btn-success {
    background: linear-gradient(135deg, #10b981, #059669, #047857);
    color: #ffffff;
    box-shadow: 
        0 8px 25px rgba(16, 185, 129, 0.4),
        0 0 0 1px rgba(16, 185, 129, 0.1);
}

.btn-success:hover {
    background: linear-gradient(135deg, #059669, #047857, #065f46);
    box-shadow: 
        0 12px 35px rgba(16, 185, 129, 0.6),
        0 0 0 1px rgba(16, 185, 129, 0.2);
    transform: translateY(-4px) scale(1.05);
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.4);
    transform: translateY(-4px) scale(1.05);
    box-shadow: 0 12px 35px rgba(255, 255, 255, 0.1);
}

.real-time-display {
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
}

.real-time-display:hover {
    transform: translateY(-2px);
    border-color: rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.real-time-display h3 {
    color: #ffffff;
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.real-time-display h3::before {
    content: "📊";
    font-size: 1.5rem;
}

.sensor-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.sensor-card {
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    min-height: 120px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.sensor-card:hover {
    transform: translateY(-3px);
    border-color: rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.sensor-card i {
    font-size: 2rem;
    color: #3b82f6;
    margin-bottom: 1rem;
}

.sensor-card h4 {
    color: #ffffff;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.sensor-card small {
    color: #cbd5e1;
    font-size: 0.9rem;
}

.manual-input-section {
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
}

.manual-input-section:hover {
    transform: translateY(-2px);
    border-color: rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.manual-input-section h3 {
    color: #ffffff;
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.manual-input-section h3::before {
    content: "✏️";
    font-size: 1.5rem;
}

.input-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.data-section {
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
}

.data-section:hover {
    transform: translateY(-2px);
    border-color: rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.data-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.data-header h3 {
    color: #ffffff;
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.data-header h3::before {
    content: "📋";
    font-size: 1.5rem;
}

.data-actions {
    display: flex;
    gap: 0.75rem;
}

.data-table {
    overflow-x: auto;
    margin-bottom: 1.5rem;
    -webkit-overflow-scrolling: touch;
    border-radius: 8px;
}

.data-table table {
    width: 100%;
    min-width: 600px;
    border-collapse: collapse;
    background: rgba(15, 23, 42, 0.6);
    border-radius: 8px;
    overflow: hidden;
}

.data-table th {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
    font-weight: 600;
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.data-table td {
    padding: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    color: #ffffff;
}

.data-table tbody tr:hover {
    background: rgba(255, 255, 255, 0.08);
}

.data-table tbody tr:last-child td {
    border-bottom: none;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-block;
}

.status-excellent { 
    background: rgba(16, 185, 129, 0.2); 
    color: #10b981; 
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.status-good { 
    background: rgba(59, 130, 246, 0.2); 
    color: #3b82f6; 
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.status-fair { 
    background: rgba(245, 158, 11, 0.2); 
    color: #f59e0b; 
    border: 1px solid rgba(245, 158, 11, 0.3);
}

.status-poor { 
    background: rgba(239, 68, 68, 0.2); 
    color: #ef4444; 
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.pagination {
    display: flex;
    justify-content: center;
    margin-top: 1.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .iot-container {
        padding: 0.5rem;
    }

    .glass-card {
        padding: 1rem;
        margin: 0.5rem 0;
    }

    .iot-title {
        font-size: 1.25rem;
    }

    .iot-meta {
        flex-direction: column;
        gap: 0.5rem;
        align-items: center;
    }

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }

    .control-form {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }

    .form-actions {
        flex-direction: column;
        gap: 0.5rem;
    }

    .btn {
        width: 100%;
        min-width: auto;
    }

    .data-header {
        flex-direction: column;
        gap: 0.75rem;
        align-items: flex-start;
    }

    .data-actions {
        width: 100%;
        justify-content: center;
        flex-direction: column;
        gap: 0.5rem;
    }

    .data-table {
        font-size: 0.8rem;
    }

    .data-table th,
    .data-table td {
        padding: 0.5rem 0.25rem;
    }

    .sensor-grid {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }

    .input-grid {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
}

@media (max-width: 480px) {
    .iot-container {
        padding: 0.25rem;
    }

    .glass-card {
        padding: 0.75rem;
        margin: 0.25rem 0;
    }

    .iot-title {
        font-size: 1.1rem;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .stats-card,
    .sensor-card {
        padding: 0.75rem;
    }
}

.text-center {
    text-align: center;
    color: #ffffff;
}

.status-badge {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100px;
}
</style>
@endsection

    <div class="iot-container">
        <div class="glass-card">
            <div class="iot-header">
                <h2 class="iot-title">Sistem Monitoring IoT</h2>
                <div class="iot-meta">
                    <div class="meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span id="currentDateTime"></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-circle"></i>
                        <span id="connectionStatus">Terputus</span>
                    </div>
                </div>
            </div>
        border-radius: 20px;
        padding: 2rem;
        margin: 1.5rem 0;
        box-shadow: 
            0 20px 40px rgba(0, 0, 0, 0.3),
            0 0 0 1px rgba(255, 255, 255, 0.05),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .glass-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.5), transparent);
    }

    .glass-card:hover {
        transform: translateY(-8px) scale(1.02);
        border-color: rgba(59, 130, 246, 0.4);
        box-shadow: 
            0 32px 64px rgba(0, 0, 0, 0.4),
            0 0 0 1px rgba(59, 130, 246, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }

    .iot-header {
        text-align: center;
        margin-bottom: 2rem;
        padding: 2rem 0;
        border-bottom: 2px solid rgba(59, 130, 246, 0.2);
        position: relative;
    }

    .iot-header::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 2px;
        background: linear-gradient(90deg, transparent, #3b82f6, transparent);
    }

    .iot-title {
        color: #ffffff;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6, #06b6d4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 0 30px rgba(59, 130, 246, 0.3);
    }

    .iot-meta {
        display: flex;
        justify-content: center;
        gap: 2rem;
        flex-wrap: wrap;
        margin-top: 1rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #cbd5e1;
        font-size: 1rem;
        padding: 0.75rem 1.5rem;
        background: rgba(59, 130, 246, 0.1);
        border-radius: 50px;
        border: 1px solid rgba(59, 130, 246, 0.2);
        transition: all 0.3s ease;
    }

    .meta-item:hover {
        background: rgba(59, 130, 246, 0.2);
        transform: translateY(-2px);
    }

    .meta-item i {
        color: #3b82f6;
        font-size: 1.1rem;
        filter: drop-shadow(0 0 8px rgba(59, 130, 246, 0.5));
    }

    .iot-content {
        color: #ffffff;
    }

    .iot-description {
        margin-bottom: 2rem;
    }

    .iot-description h3 {
        color: #ffffff;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .iot-description h3::before {
        content: "🔬";
        font-size: 1.5rem;
    }

    .iot-description p {
        color: #cbd5e1;
        line-height: 1.6;
        font-size: 1rem;
        background: rgba(255, 255, 255, 0.05);
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid #3b82f6;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stats-card {
        background: rgba(15, 23, 42, 0.9);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(59, 130, 246, 0.2);
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        min-height: 160px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6, #06b6d4);
    }

    .stats-card:hover {
        transform: translateY(-8px) scale(1.05);
        border-color: rgba(59, 130, 246, 0.4);
        box-shadow: 
            0 20px 40px rgba(0, 0, 0, 0.3),
            0 0 0 1px rgba(59, 130, 246, 0.1);
    }

    .stats-icon {
        font-size: 3rem;
        color: #3b82f6;
        margin-bottom: 1rem;
        filter: drop-shadow(0 0 20px rgba(59, 130, 246, 0.5));
        transition: all 0.3s ease;
    }

    .stats-card:hover .stats-icon {
        transform: scale(1.1) rotate(5deg);
        filter: drop-shadow(0 0 30px rgba(59, 130, 246, 0.8));
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 0.75rem;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
    }

    .stats-label {
        font-size: 1rem;
        color: #cbd5e1;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .iot-control-panel {
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .iot-control-panel:hover {
        transform: translateY(-2px);
        border-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .iot-control-panel h3 {
        color: #ffffff;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .iot-control-panel h3::before {
        content: "🎛️";
        font-size: 1.2rem;
    }

    .control-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        color: #ffffff;
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 0.5rem 0.75rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 6px;
        color: #ffffff;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        width: 100%;
        box-sizing: border-box;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        background: rgba(255, 255, 255, 0.15);
    }

    .form-group input::placeholder,
    .form-group textarea::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .form-actions {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 2rem;
        border: none;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        min-width: 140px;
        justify-content: center;
        position: relative;
        overflow: hidden;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8, #7c3aed);
        color: #ffffff;
        box-shadow: 
            0 8px 25px rgba(59, 130, 246, 0.4),
            0 0 0 1px rgba(59, 130, 246, 0.1);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #2563eb, #1e40af, #6d28d9);
        box-shadow: 
            0 12px 35px rgba(59, 130, 246, 0.6),
            0 0 0 1px rgba(59, 130, 246, 0.2);
        transform: translateY(-4px) scale(1.05);
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981, #059669, #047857);
        color: #ffffff;
        box-shadow: 
            0 8px 25px rgba(16, 185, 129, 0.4),
            0 0 0 1px rgba(16, 185, 129, 0.1);
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #059669, #047857, #065f46);
        box-shadow: 
            0 12px 35px rgba(16, 185, 129, 0.6),
            0 0 0 1px rgba(16, 185, 129, 0.2);
        transform: translateY(-4px) scale(1.05);
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
    }

    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.4);
        transform: translateY(-4px) scale(1.05);
        box-shadow: 0 12px 35px rgba(255, 255, 255, 0.1);
    }

    .real-time-display {
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .real-time-display:hover {
        transform: translateY(-2px);
        border-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .real-time-display h3 {
        color: #ffffff;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .real-time-display h3::before {
        content: "📊";
        font-size: 1.5rem;
    }

    .sensor-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .sensor-card {
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        min-height: 120px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .sensor-card:hover {
        transform: translateY(-3px);
        border-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .sensor-card i {
        font-size: 2rem;
        color: #3b82f6;
        margin-bottom: 1rem;
    }

    .sensor-card h4 {
        color: #ffffff;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .sensor-card small {
        color: #cbd5e1;
        font-size: 0.9rem;
    }

    .manual-input-section {
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .manual-input-section:hover {
        transform: translateY(-2px);
        border-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .manual-input-section h3 {
        color: #ffffff;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .manual-input-section h3::before {
        content: "✏️";
        font-size: 1.5rem;
    }

    .input-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .data-section {
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .data-section:hover {
        transform: translateY(-2px);
        border-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .data-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .data-header h3 {
        color: #ffffff;
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .data-header h3::before {
        content: "📋";
        font-size: 1.5rem;
    }

    .data-actions {
        display: flex;
        gap: 0.75rem;
    }

    .data-table {
        overflow-x: auto;
        margin-bottom: 1.5rem;
        -webkit-overflow-scrolling: touch;
        border-radius: 8px;
    }

    .data-table table {
        width: 100%;
        min-width: 600px;
        border-collapse: collapse;
        background: rgba(15, 23, 42, 0.6);
        border-radius: 8px;
        overflow: hidden;
    }

    .data-table th {
        background: rgba(59, 130, 246, 0.2);
        color: #3b82f6;
        font-weight: 600;
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        color: #ffffff;
    }

    .data-table tbody tr:hover {
        background: rgba(255, 255, 255, 0.08);
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
    }

    .status-excellent { 
        background: rgba(16, 185, 129, 0.2); 
        color: #10b981; 
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .status-good { 
        background: rgba(59, 130, 246, 0.2); 
        color: #3b82f6; 
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .status-fair { 
        background: rgba(245, 158, 11, 0.2); 
        color: #f59e0b; 
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .status-poor { 
        background: rgba(239, 68, 68, 0.2); 
        color: #ef4444; 
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 1.5rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .iot-container {
            padding: 0.5rem;
        }

        .glass-card {
            padding: 1rem;
            margin: 0.5rem 0;
        }

        .iot-title {
            font-size: 1.25rem;
        }

        .iot-meta {
            flex-direction: column;
            gap: 0.5rem;
            align-items: center;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .control-form {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .form-actions {
            flex-direction: column;
            gap: 0.5rem;
        }

        .btn {
            width: 100%;
            min-width: auto;
        }

        .data-header {
            flex-direction: column;
            gap: 0.75rem;
            align-items: flex-start;
        }

        .data-actions {
            width: 100%;
            justify-content: center;
            flex-direction: column;
            gap: 0.5rem;
        }

        .data-table {
            font-size: 0.8rem;
        }

        .data-table th,
        .data-table td {
            padding: 0.5rem 0.25rem;
        }

        .sensor-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .input-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
    }

    @media (max-width: 480px) {
        .iot-container {
            padding: 0.25rem;
        }

        .glass-card {
            padding: 0.75rem;
            margin: 0.25rem 0;
        }

        .iot-title {
            font-size: 1.1rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stats-card,
        .sensor-card {
            padding: 0.75rem;
        }
    }

    /* Animation Keyframes */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    @keyframes glow {
        0%, 100% {
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
        }
        50% {
            box-shadow: 0 0 40px rgba(59, 130, 246, 0.6);
        }
    }

    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }
        100% {
            background-position: 200% 0;
        }
    }

    /* Apply animations */
    .glass-card {
        animation: fadeInUp 0.6s ease-out;
    }

    .stats-card:nth-child(1) { animation-delay: 0.1s; }
    .stats-card:nth-child(2) { animation-delay: 0.2s; }
    .stats-card:nth-child(3) { animation-delay: 0.3s; }
    .stats-card:nth-child(4) { animation-delay: 0.4s; }

    .stats-card:hover {
        animation: pulse 2s infinite;
    }

    .meta-item i {
        animation: glow 3s ease-in-out infinite;
    }

    /* Loading animation for real-time data */
    .loading {
        position: relative;
        overflow: hidden;
    }

    .loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.2), transparent);
        animation: shimmer 2s infinite;
    }

    /* Enhanced focus states */
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 
            0 0 0 3px rgba(59, 130, 246, 0.1),
            0 0 20px rgba(59, 130, 246, 0.3);
        background: rgba(255, 255, 255, 0.15);
        transform: scale(1.02);
    }

    /* Status indicators with animation */
    .status-badge {
        position: relative;
        overflow: hidden;
    }

    .status-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        animation: shimmer 3s infinite;
    }

        .data-table {
            font-size: 0.75rem;
        }

        .data-table th,
        .data-table td {
            padding: 0.4rem 0.2rem;
        }
    }

    /* Additional fixes */
    .data-table {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .data-table table {
        min-width: 600px;
    }

    .data-table td {
        word-wrap: break-word;
        max-width: 150px;
    }

    .text-center {
        text-align: center;
        color: #ffffff;
    }

    .status-badge {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100px;
    }
</style>
@endsection

@section('additional-scripts')
<script>
    let currentDevice = null;
    let currentData = null;
    let isConnected = false;

    // Update current date and time
    function updateDateTime() {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        const dateTimeElement = document.getElementById('currentDateTime');
        if (dateTimeElement) {
            dateTimeElement.textContent = now.toLocaleDateString('id-ID', options);
        }
    }
    
    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        updateDateTime();
        setInterval(updateDateTime, 60000);
    });

    // Handle measurement mode change
    document.addEventListener('DOMContentLoaded', function() {
        const measurementMode = document.getElementById('measurementMode');
        if (measurementMode) {
            measurementMode.addEventListener('change', function() {
                const mode = this.value;
                const scanBtn = document.getElementById('scanDeviceBtn');
                const manualSection = document.getElementById('manualInputSection');
                
                if (scanBtn && manualSection) {
                    if (mode === 'manual') {
                        scanBtn.innerHTML = '<i class="fas fa-edit"></i>Input Data Manual';
                        manualSection.style.display = 'block';
                    } else {
                        scanBtn.innerHTML = '<i class="fas fa-bluetooth"></i>Scan & Ambil Data';
                        manualSection.style.display = 'none';
                    }
                }
            });
        }
    });

    // Scan and connect to IoT device
    async function scanDevice() {
        const measurementMode = document.getElementById('measurementMode');
        if (!measurementMode) return;
        
        const mode = measurementMode.value;
        
        if (mode === 'manual') {
            showManualInput();
            return;
        }
        
        const scanBtn = document.getElementById('scanDeviceBtn');
        const saveBtn = document.getElementById('saveDataBtn');
        
        if (!scanBtn || !saveBtn) return;
        
        scanBtn.disabled = true;
        scanBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>Scanning...';
        
        try {
            // Check if Web Bluetooth is supported
            if (!navigator.bluetooth) {
                throw new Error('Web Bluetooth tidak didukung di browser ini. Gunakan mode manual.');
            }
            
            // Request device
            const device = await navigator.bluetooth.requestDevice({
                acceptAllDevices: true,
                optionalServices: ['0000180a-0000-1000-8000-00805f9b34fb'] // Device Information Service
            });
            
            currentDevice = device;
            
            // Connect to device
            const server = await device.gatt.connect();
            isConnected = true;
            
            // Show real-time data section
            document.getElementById('realTimeDataSection').style.display = 'block';
            
            // Start reading data
            startReadingData(server);
            
            scanBtn.innerHTML = '<i class="fas fa-check"></i>Terhubung';
            saveBtn.disabled = false;
            
            // Update connection status
            document.getElementById('connectionStatus').textContent = 'Terhubung';
            document.getElementById('connectionStatus').style.color = '#10b981';
            
        } catch (error) {
            console.error('Error connecting to device:', error);
            alert('Gagal terhubung ke perangkat: ' + error.message + '\n\nGunakan mode manual untuk input data.');
            
            scanBtn.disabled = false;
            scanBtn.innerHTML = '<i class="fas fa-bluetooth"></i>Scan & Ambil Data';
        }
    }

    // Show manual input form
    function showManualInput() {
        const manualSection = document.getElementById('manualInputSection');
        const saveBtn = document.getElementById('saveDataBtn');
        
        if (manualSection) {
            manualSection.style.display = 'block';
        }
        
        if (saveBtn) {
            saveBtn.disabled = false;
        }
    }

    // Start reading data from device
    async function startReadingData(server) {
        try {
            // Get device information service
            const service = await server.getPrimaryService('0000180a-0000-1000-8000-00805f9b34fb');
            
            // Simulate data reading (replace with actual characteristic reading)
            setInterval(() => {
                if (isConnected) {
                    // Load real sensor data from database
                    currentData = {
                        soil_temperature: '--', // Data akan dimuat dari database
                        soil_humus: '--', // Data akan dimuat dari database
                        soil_moisture: '--', // Data akan dimuat dari database
                        timestamp: new Date().toISOString()
                    };
                    
                    // Update UI
                    document.getElementById('realTimeTemp').textContent = currentData.soil_temperature + '°C';
                    document.getElementById('realTimeHumus').textContent = currentData.soil_humus + '%';
                    document.getElementById('realTimeMoisture').textContent = currentData.soil_moisture + '%';
                }
            }, 2000);
            
        } catch (error) {
            console.error('Error reading data:', error);
        }
    }

    // Save data to database
    async function saveData() {
        const measurementMode = document.getElementById('measurementMode');
        const selectKelas = document.getElementById('selectKelas');
        const location = document.getElementById('location');
        const notes = document.getElementById('notes');
        
        if (!measurementMode || !selectKelas || !location || !notes) {
            console.error('Required form elements not found');
            return;
        }
        
        const mode = measurementMode.value;
        const classId = selectKelas.value;
        const locationValue = location.value;
        const notesValue = notes.value;
        
        if (!classId) {
            alert('Pilih kelas terlebih dahulu');
            return;
        }
        
        let dataToSave = {};
        
        if (mode === 'manual') {
            const manualTemp = document.getElementById('manualTemp');
            const manualHumus = document.getElementById('manualHumus');
            const manualMoisture = document.getElementById('manualMoisture');
            
            if (!manualTemp || !manualHumus || !manualMoisture) {
                console.error('Manual input elements not found');
                return;
            }
            
            const temp = manualTemp.value;
            const humus = manualHumus.value;
            const moisture = manualMoisture.value;
            
            if (!temp || !humus || !moisture) {
                alert('Isi semua data manual terlebih dahulu');
                return;
            }
            
            dataToSave = {
                soil_temperature: parseFloat(temp),
                soil_humus: parseFloat(humus),
                soil_moisture: parseFloat(moisture),
                timestamp: new Date().toISOString()
            };
        } else {
            if (!currentData) {
                alert('Tidak ada data untuk disimpan');
                return;
            }
            
            dataToSave = currentData;
        }
        
        try {
            const response = await fetch('/api/iot/readings', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    student_id: '{{ Auth::user()->nis }}',
                    class_id: classId,
                    soil_temperature: parseFloat(dataToSave.soil_temperature),
                    soil_humus: parseFloat(dataToSave.soil_humus),
                    soil_moisture: parseFloat(dataToSave.soil_moisture),
                    device_id: currentDevice ? currentDevice.id : 'manual',
                    location: locationValue,
                    notes: notesValue,
                    raw_data: dataToSave
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert('Data berhasil disimpan!');
                refreshData();
            } else {
                alert('Gagal menyimpan data: ' + result.message);
            }
            
        } catch (error) {
            console.error('Error saving data:', error);
            alert('Gagal menyimpan data: ' + error.message);
        }
    }

    // Refresh data table
    function refreshData() {
        try {
            location.reload();
        } catch (error) {
            console.error('Error refreshing data:', error);
        }
    }

    // Export my data to CSV
    function exportMyData() {
        try {
            const exportUrl = '/api/iot/readings/export?student_id={{ Auth::user()->nis }}';
            window.open(exportUrl, '_blank');
        } catch (error) {
            console.error('Error exporting data:', error);
            alert('Gagal mengekspor data. Silakan coba lagi.');
        }
    }
</script>
</div>
@endsection
