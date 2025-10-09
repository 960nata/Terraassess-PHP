@php
    $userRole = $userRole ?? 'superadmin';
@endphp

<div class="page-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-wifi"></i>
            Manajemen IoT
        </h1>
        <p class="page-description">Kelola perangkat dan sensor IoT</p>
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
/* IoT Management Styles - Override any conflicting styles */
.page-container {
    padding: 2rem;
    background: #0f172a;
    min-height: 100vh;
    margin-top: 70px; /* Account for fixed header */
    width: 100%;
    max-width: 100%;
    overflow-x: hidden;
}

/* Ensure FontAwesome icons are visible */
.fas, .fa, .far, .fab {
    font-family: "Font Awesome 6 Free" !important;
    font-weight: 900 !important;
    display: inline-block !important;
    font-style: normal !important;
    font-variant: normal !important;
    text-rendering: auto !important;
    line-height: 1 !important;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.header-content h1 {
    color: #ffffff;
    font-size: 2rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.header-content h1 i {
    font-size: 1.8rem !important;
    color: #667eea !important;
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
    flex-shrink: 0;
}

.stat-icon i {
    font-size: 1.5rem !important;
    color: white !important;
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

.btn-filter i, .btn-clear i {
    font-size: 0.9rem !important;
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
    touch-action: manipulation;
    -webkit-tap-highlight-color: transparent;
}

.btn-view i, .btn-edit i, .btn-data i, .btn-delete i {
    font-size: 0.8rem !important;
    color: white !important;
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
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-primary i {
    font-size: 0.9rem !important;
    color: white !important;
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

/* Override any Tailwind conflicts */
.page-container * {
    box-sizing: border-box;
}

.page-container .fas,
.page-container .fa,
.page-container .far,
.page-container .fab {
    font-family: "Font Awesome 6 Free" !important;
    font-weight: 900 !important;
    display: inline-block !important;
    font-style: normal !important;
    font-variant: normal !important;
    text-rendering: auto !important;
    line-height: 1 !important;
}

/* Ensure proper spacing and layout */
.page-container {
    position: relative;
    z-index: 1;
    box-sizing: border-box;
}

/* Global mobile improvements */
* {
    box-sizing: border-box;
}

body {
    overflow-x: hidden;
}

/* Mobile viewport improvements */
@media (max-width: 768px) {
    html {
        -webkit-text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
    }
    
    body {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
}

/* Mobile touch improvements */
@media (max-width: 768px) {
    .btn-primary, .btn-secondary, .btn-filter, .btn-clear, .btn-export {
        min-height: 44px;
        touch-action: manipulation;
        -webkit-tap-highlight-color: transparent;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        min-height: 44px;
        font-size: 16px; /* Prevent zoom on iOS */
    }
    
    .data-table th,
    .data-table td {
        min-height: 44px;
        vertical-align: middle;
    }
    
    /* Improve mobile scrolling */
    .page-container {
        -webkit-overflow-scrolling: touch;
    }
    
    /* Better mobile table handling */
    .table-wrapper {
        position: relative;
    }
    
    .table-wrapper::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 20px;
        height: 100%;
        background: linear-gradient(to left, rgba(30, 41, 59, 0.8), transparent);
        pointer-events: none;
        z-index: 5;
    }
    
    /* Mobile-specific improvements */
    .page-container {
        padding: 1rem;
        margin-top: 60px;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .stat-card {
        padding: 1rem;
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
    }
    
    .stat-value {
        font-size: 1.5rem;
    }
    
    .stat-label {
        font-size: 0.8rem;
    }
    
    .device-filters {
        padding: 1rem;
    }
    
    .filter-row {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
    
    .filter-actions {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .btn-filter, .btn-clear {
        width: 100%;
        justify-content: center;
    }
    
    .table-container {
        padding: 1rem;
    }
    
    .table-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .table-actions {
        width: 100%;
    }
    
    .btn-export {
        width: 100%;
        justify-content: center;
    }
    
    .table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border-radius: 8px;
        border: 1px solid #334155;
    }
    
    .data-table {
        min-width: 800px;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .data-table th:first-child,
    .data-table td:first-child {
        position: sticky;
        left: 0;
        background: #2a2a3e;
        z-index: 10;
        border-right: 1px solid #334155;
    }
    
    .data-table th,
    .data-table td {
        padding: 0.75rem 0.5rem;
        font-size: 0.85rem;
    }
    
    .action-buttons {
        flex-wrap: wrap;
        gap: 0.25rem;
    }
    
    .btn-view, .btn-edit, .btn-data, .btn-delete {
        width: 32px;
        height: 32px;
        font-size: 0.7rem;
        min-width: 32px;
        min-height: 32px;
    }
    
    .btn-view i, .btn-edit i, .btn-data i, .btn-delete i {
        font-size: 0.7rem !important;
    }
    
    .modal-content {
        width: 95%;
        padding: 1.5rem;
        margin: 1rem;
        max-height: 90vh;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-primary, .btn-secondary {
        width: 100%;
        justify-content: center;
    }
}

/* Tablet and larger mobile styles */
@media (max-width: 1024px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .header-actions {
        width: 100%;
    }
    
    .btn-primary {
        width: 100%;
        justify-content: center;
    }
    
    .page-header {
        margin-bottom: 1.5rem;
    }
    
    .header-content h1 {
        font-size: 1.5rem;
    }
    
    .header-content h1 i {
        font-size: 1.3rem !important;
    }
}

@media (max-width: 480px) {
    .page-container {
        padding: 0.75rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-card {
        padding: 0.75rem;
    }
    
    .stat-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .stat-value {
        font-size: 1.25rem;
    }
    
    .header-content h1 {
        font-size: 1.25rem;
    }
    
    .page-subtitle {
        font-size: 0.9rem;
    }
    
    .btn-text {
        display: none;
    }
    
    .btn-primary {
        width: auto;
        padding: 0.75rem;
    }
    
    .data-table th,
    .data-table td {
        padding: 0.5rem 0.25rem;
        font-size: 0.75rem;
    }
    
    .device-name {
        font-size: 0.9rem;
    }
    
    .device-description {
        font-size: 0.7rem;
    }
    
    .type-badge, .location-badge, .status-badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.5rem;
    }
    
    .sensor-count, .last-update {
        font-size: 0.75rem;
    }
    
    .modal-content {
        width: 98%;
        padding: 1rem;
        margin: 0.5rem;
        max-height: 95vh;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .data-table th:first-child,
    .data-table td:first-child {
        position: sticky;
        left: 0;
        background: #2a2a3e;
        z-index: 10;
        border-right: 1px solid #334155;
        min-width: 40px;
    }
    
    .modal-header h3 {
        font-size: 1.1rem;
    }
    
    /* Better mobile table handling for very small screens */
    .table-wrapper::after {
        width: 15px;
    }
    
    .data-table th:first-child,
    .data-table td:first-child {
        min-width: 35px;
    }
}

/* Extra small mobile devices */
@media (max-width: 360px) {
    .page-container {
        padding: 0.5rem;
    }
    
    .stats-grid {
        gap: 0.5rem;
    }
    
    .stat-card {
        padding: 0.5rem;
    }
    
    .stat-icon {
        width: 35px;
        height: 35px;
        font-size: 0.9rem;
    }
    
    .stat-value {
        font-size: 1.1rem;
    }
    
    .header-content h1 {
        font-size: 1.1rem;
    }
    
    .page-subtitle {
        font-size: 0.8rem;
    }
    
    .data-table th,
    .data-table td {
        padding: 0.4rem 0.2rem;
        font-size: 0.7rem;
    }
    
    .device-name {
        font-size: 0.8rem;
    }
    
    .device-description {
        font-size: 0.65rem;
    }
    
    .type-badge, .location-badge, .status-badge {
        font-size: 0.65rem;
        padding: 0.15rem 0.4rem;
    }
    
    .sensor-count, .last-update {
        font-size: 0.7rem;
    }
    
    .modal-content {
        width: 99%;
        padding: 0.75rem;
        margin: 0.25rem;
    }
    
    .modal-header h3 {
        font-size: 1rem;
    }
    
    .table-wrapper::after {
        width: 10px;
    }
}

/* Extra small mobile devices */
@media (max-width: 360px) {
    .page-container {
        padding: 0.5rem;
    }
    
    .stats-grid {
        gap: 0.5rem;
    }
    
    .stat-card {
        padding: 0.5rem;
    }
    
    .stat-icon {
        width: 35px;
        height: 35px;
        font-size: 0.9rem;
    }
    
    .stat-value {
        font-size: 1.1rem;
    }
    
    .header-content h1 {
        font-size: 1.1rem;
    }
    
    .page-subtitle {
        font-size: 0.8rem;
    }
    
    .data-table th,
    .data-table td {
        padding: 0.4rem 0.2rem;
        font-size: 0.7rem;
    }
    
    .device-name {
        font-size: 0.8rem;
    }
    
    .device-description {
        font-size: 0.65rem;
    }
    
    .type-badge, .location-badge, .status-badge {
        font-size: 0.65rem;
        padding: 0.15rem 0.4rem;
    }
    
    .sensor-count, .last-update {
        font-size: 0.7rem;
    }
    
    .modal-content {
        width: 99%;
        padding: 0.75rem;
        margin: 0.25rem;
    }
    
    .modal-header h3 {
        font-size: 1rem;
    }
    
    .table-wrapper::after {
        width: 10px;
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

// Mobile touch improvements
document.addEventListener('DOMContentLoaded', function() {
    // Prevent zoom on input focus for mobile
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            if (window.innerWidth <= 768) {
                this.style.fontSize = '16px';
            }
        });
    });
    
    // Improve table scrolling on mobile
    const tableWrapper = document.querySelector('.table-wrapper');
    if (tableWrapper && window.innerWidth <= 768) {
        tableWrapper.style.overflowX = 'auto';
        tableWrapper.style.webkitOverflowScrolling = 'touch';
    }
    
    // Add touch feedback for buttons
    const buttons = document.querySelectorAll('.btn-view, .btn-edit, .btn-data, .btn-delete, .btn-primary, .btn-secondary, .btn-filter, .btn-clear, .btn-export');
    buttons.forEach(button => {
        button.addEventListener('touchstart', function() {
            this.style.transform = 'scale(0.95)';
        });
        
        button.addEventListener('touchend', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // Improve modal handling on mobile
    const modal = document.getElementById('registerDeviceModal');
    if (modal) {
        modal.addEventListener('touchmove', function(e) {
            e.preventDefault();
        }, { passive: false });
    }
    
    // Add swipe gesture for table scrolling
    let startX = 0;
    let startY = 0;
    const tableWrapper = document.querySelector('.table-wrapper');
    
    if (tableWrapper) {
        tableWrapper.addEventListener('touchstart', function(e) {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        });
        
        tableWrapper.addEventListener('touchmove', function(e) {
            const currentX = e.touches[0].clientX;
            const currentY = e.touches[0].clientY;
            const diffX = startX - currentX;
            const diffY = startY - currentY;
            
            // If horizontal swipe is more significant than vertical
            if (Math.abs(diffX) > Math.abs(diffY)) {
                e.preventDefault();
            }
        }, { passive: false });
    }
});
</script>
