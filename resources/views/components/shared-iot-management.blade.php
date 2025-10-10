@php
    $userRole = $userRole ?? 'superadmin';
@endphp

<div class="page-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">
                <i class="fas fa-wifi"></i>
                Manajemen IoT
            </h1>
            <p class="page-subtitle">Kelola perangkat dan sensor IoT</p>
        </div>
        <div class="header-actions">
            <button class="btn-primary" onclick="openRegisterDeviceModal()">
                <i class="fas fa-plus"></i>
                Daftarkan Perangkat
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-wifi"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalDevices ?? 0 }}</div>
                <div class="stat-label">Total Perangkat</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $activeDevices ?? 0 }}</div>
                <div class="stat-label">Perangkat Aktif</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-thermometer-half"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalSensors ?? 0 }}</div>
                <div class="stat-label">Sensor Terpasang</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-database"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalDataPoints ?? 0 }}</div>
                <div class="stat-label">Data Points</div>
            </div>
        </div>
    </div>

    <!-- Device Filters -->
    <div class="device-filters">
        <form action="{{ 
            $userRole === 'superadmin' ? route('superadmin.iot-management.filter') : 
            ($userRole === 'admin' ? route('superadmin.iot-management.filter') : route('teacher.iot-management.filter'))
        }}" method="GET" class="filter-form">
            <div class="filter-row">
                <div class="form-group">
                    <label for="filter_type">Tipe Perangkat</label>
                    <select id="filter_type" name="filter_type">
                        <option value="">Semua Tipe</option>
                        <option value="sensor" {{ request('filter_type') == 'sensor' ? 'selected' : '' }}>Sensor</option>
                        <option value="actuator" {{ request('filter_type') == 'actuator' ? 'selected' : '' }}>Actuator</option>
                        <option value="gateway" {{ request('filter_type') == 'gateway' ? 'selected' : '' }}>Gateway</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="filter_status">Status</label>
                    <select id="filter_status" name="filter_status">
                        <option value="">Semua Status</option>
                        <option value="online" {{ request('filter_status') == 'online' ? 'selected' : '' }}>Online</option>
                        <option value="offline" {{ request('filter_status') == 'offline' ? 'selected' : '' }}>Offline</option>
                        <option value="maintenance" {{ request('filter_status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="filter_location">Lokasi</label>
                    <select id="filter_location" name="filter_location">
                        <option value="">Semua Lokasi</option>
                        <option value="lab1" {{ request('filter_location') == 'lab1' ? 'selected' : '' }}>Lab 1</option>
                        <option value="lab2" {{ request('filter_location') == 'lab2' ? 'selected' : '' }}>Lab 2</option>
                        <option value="outdoor" {{ request('filter_location') == 'outdoor' ? 'selected' : '' }}>Outdoor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="filter_search">Cari</label>
                    <input type="text" id="filter_search" name="filter_search" placeholder="Nama perangkat..." value="{{ request('filter_search') }}">
                </div>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-search"></i>
                    Filter
                </button>
                <a href="{{ 
                    $userRole === 'superadmin' ? route('superadmin.iot-management') : 
                    ($userRole === 'admin' ? route('superadmin.iot-management') : route('teacher.iot-management'))
                }}" class="btn-clear">
                    <i class="fas fa-times"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Devices Table -->
    <div class="table-container">
        <div class="table-header">
            <h3>Daftar Perangkat IoT</h3>
            <div class="table-actions">
                <button class="btn-export" onclick="exportDevices()">
                    <i class="fas fa-download"></i>
                    Export
                </button>
            </div>
        </div>
        
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Device ID</th>
                        <th>Nama Perangkat</th>
                        <th>Tipe</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Sensor</th>
                        <th>Terakhir Update</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($devices ?? [] as $index => $device)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span class="device-id">{{ $device->device_id ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="device-info">
                                    <div class="device-name">{{ $device->name }}</div>
                                    <div class="device-description">{{ $device->description ?? 'Tidak ada deskripsi' }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="type-badge type-{{ $device->type }}">
                                    @switch($device->type)
                                        @case('sensor') Sensor @break
                                        @case('actuator') Actuator @break
                                        @case('gateway') Gateway @break
                                        @default {{ ucfirst($device->type) }}
                                    @endswitch
                                </span>
                            </td>
                            <td>
                                <span class="location-badge">{{ $device->location ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="status-badge {{ $device->status }}">
                                    @switch($device->status)
                                        @case('online') Online @break
                                        @case('offline') Offline @break
                                        @case('maintenance') Maintenance @break
                                        @default {{ ucfirst($device->status) }}
                                    @endswitch
                                </span>
                            </td>
                            <td>
                                <span class="sensor-count">{{ $device->sensors_count ?? 0 }} sensor</span>
                            </td>
                            <td>
                                <span class="last-update">{{ $device->last_update ? $device->last_update->format('d M Y H:i') : 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-view" onclick="viewDevice('{{ $device->id }}')" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-edit" onclick="editDevice('{{ $device->id }}')" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-data" onclick="viewData('{{ $device->id }}')" title="Lihat Data">
                                        <i class="fas fa-chart-line"></i>
                                    </button>
                                    <button class="btn-delete" onclick="deleteDevice('{{ $device->id }}')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                <div class="empty-state">
                                    <i class="fas fa-wifi"></i>
                                    <p>Tidak ada data perangkat IoT</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Register Device Modal -->
<div id="registerDeviceModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Daftarkan Perangkat IoT Baru</h3>
            <button class="modal-close" onclick="closeRegisterDeviceModal()">&times;</button>
        </div>
        <form action="{{ 
            $userRole === 'superadmin' ? route('superadmin.iot-management.register') : 
            ($userRole === 'admin' ? route('superadmin.iot-management.register') : route('teacher.iot-management.register'))
        }}" method="POST" class="modal-form">
            @csrf
            <div class="form-group">
                <label for="name">Nama Perangkat</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="device_id">Device ID</label>
                    <input type="text" id="device_id" name="device_id" required>
                </div>
                <div class="form-group">
                    <label for="type">Tipe Perangkat</label>
                    <select id="type" name="type" required>
                        <option value="">Pilih Tipe</option>
                        <option value="sensor">Sensor</option>
                        <option value="actuator">Actuator</option>
                        <option value="gateway">Gateway</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="location">Lokasi</label>
                    <select id="location" name="location" required>
                        <option value="">Pilih Lokasi</option>
                        <option value="lab1">Lab 1</option>
                        <option value="lab2">Lab 2</option>
                        <option value="outdoor">Outdoor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ip_address">IP Address</label>
                    <input type="text" id="ip_address" name="ip_address" placeholder="192.168.1.100">
                </div>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="3"></textarea>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="closeRegisterDeviceModal()">Batal</button>
                <button type="submit" class="btn-primary">Daftarkan</button>
            </div>
        </form>
    </div>
</div>

<style>
/* IoT Management Styles */
.page-container {
    padding: 2rem;
    background: #0f172a;
    min-height: 100vh;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.header-content h1 {
    color: #ffffff;
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.header-content p {
    color: #94a3b8;
    font-size: 1rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: #1e293b;
    border-radius: 1rem;
    padding: 1.5rem;
    border: 1px solid #334155;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #667eea;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 0.25rem;
}

.stat-label {
    color: #94a3b8;
    font-size: 0.9rem;
}

.device-filters {
    background: #1e293b;
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid #334155;
}

.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #ffffff;
    font-size: 0.9rem;
}

.form-group input,
.form-group select,
.form-group textarea {
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
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #667eea;
    background: #333;
}

.filter-actions {
    display: flex;
    gap: 1rem;
}

.btn-filter, .btn-clear {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-filter {
    background: #667eea;
    color: white;
}

.btn-clear {
    background: #6b7280;
    color: white;
}

.table-container {
    background: #1e293b;
    border-radius: 1rem;
    padding: 1.5rem;
    border: 1px solid #334155;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.table-header h3 {
    color: #ffffff;
    font-size: 1.25rem;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #334155;
}

.data-table th {
    background: #2a2a3e;
    color: #ffffff;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.data-table td {
    color: #e2e8f0;
}

.device-id {
    font-family: monospace;
    background: #2a2a3e;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.8rem;
    color: #10b981;
}

.device-info {
    display: flex;
    flex-direction: column;
}

.device-name {
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 0.25rem;
}

.device-description {
    font-size: 0.8rem;
    color: #94a3b8;
}

.type-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.type-sensor { background: #10b981; color: white; }
.type-actuator { background: #3b82f6; color: white; }
.type-gateway { background: #f59e0b; color: white; }

.location-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    background: #667eea;
    color: white;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-badge.online {
    background: #10b981;
    color: white;
}

.status-badge.offline {
    background: #6b7280;
    color: white;
}

.status-badge.maintenance {
    background: #f59e0b;
    color: white;
}

.sensor-count, .last-update {
    color: #94a3b8;
    font-size: 0.9rem;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-view, .btn-edit, .btn-data, .btn-delete {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-view {
    background: #3b82f6;
    color: white;
}

.btn-edit {
    background: #f59e0b;
    color: white;
}

.btn-data {
    background: #10b981;
    color: white;
}

.btn-delete {
    background: #ef4444;
    color: white;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #94a3b8;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 1000;
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #1e293b;
    border-radius: 1rem;
    padding: 2rem;
    width: 90%;
    max-width: 500px;
    border: 1px solid #334155;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.modal-header h3 {
    color: #ffffff;
    font-size: 1.25rem;
}

.modal-close {
    background: none;
    border: none;
    color: #94a3b8;
    font-size: 1.5rem;
    cursor: pointer;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 1.5rem;
}

.btn-primary, .btn-secondary {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .filter-row {
        grid-template-columns: 1fr;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .table-wrapper {
        overflow-x: auto;
    }
}
</style>

<script>
function openRegisterDeviceModal() {
    document.getElementById('registerDeviceModal').style.display = 'block';
}

function closeRegisterDeviceModal() {
    document.getElementById('registerDeviceModal').style.display = 'none';
}

function viewDevice(deviceId) {
    // Implementation for viewing device details
    console.log('View device:', deviceId);
}

function editDevice(deviceId) {
    // Implementation for editing device
    console.log('Edit device:', deviceId);
}

function viewData(deviceId) {
    // Implementation for viewing device data
    console.log('View data for device:', deviceId);
}

function deleteDevice(deviceId) {
    if (confirm('Apakah Anda yakin ingin menghapus perangkat ini?')) {
        // Implementation for deleting device
        console.log('Delete device:', deviceId);
    }
}

function exportDevices() {
    // Implementation for exporting devices
    console.log('Export devices');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('registerDeviceModal');
    if (event.target === modal) {
        closeRegisterDeviceModal();
    }
}
</script>
