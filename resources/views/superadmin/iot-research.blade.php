@extends('layouts.unified-layout')

@section('title', 'Penelitian IoT')

@section('content')
<div class="page-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">
                <i class="fas fa-flask"></i>
                Penelitian IoT
            </h1>
            <p class="page-subtitle">Dashboard penelitian IoT siswa dengan analisis data sensor</p>
        </div>
        <div class="header-actions">
            <button class="btn-primary" onclick="openCreateProjectModal()">
                <i class="fas fa-plus"></i>
                Buat Proyek Baru
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-project-diagram"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalResearchProjects ?? 0 }}</div>
                <div class="stat-label">Total Proyek</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-play-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $activeProjects ?? 0 }}</div>
                <div class="stat-label">Proyek Aktif</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-database"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($totalDataPoints ?? 0) }}</div>
                <div class="stat-label">Data Points</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-calendar-week"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $projectsThisWeek ?? 0 }}</div>
                <div class="stat-label">Proyek Minggu Ini</div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <form action="{{ route('superadmin.iot-research.filter') }}" method="GET" class="filter-form">
            <div class="filter-row">
                <div class="form-group">
                    <label for="filter_status">Status Proyek</label>
                    <select id="filter_status" name="filter_status">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('filter_status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="completed" {{ request('filter_status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="paused" {{ request('filter_status') == 'paused' ? 'selected' : '' }}>Dijeda</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="filter_kelas">Kelas</label>
                    <select id="filter_kelas" name="filter_kelas">
                        <option value="">Semua Kelas</option>
                        @foreach(\App\Models\Kelas::all() as $kelas)
                            <option value="{{ $kelas->id }}" {{ request('filter_kelas') == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="filter_teacher">Guru</label>
                    <select id="filter_teacher" name="filter_teacher">
                        <option value="">Semua Guru</option>
                        @foreach(\App\Models\User::where('roles_id', 3)->get() as $teacher)
                            <option value="{{ $teacher->id }}" {{ request('filter_teacher') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="filter_date_from">Tanggal Mulai</label>
                    <input type="date" id="filter_date_from" name="filter_date_from" value="{{ request('filter_date_from') }}">
                </div>
                <div class="form-group">
                    <label for="filter_date_to">Tanggal Selesai</label>
                    <input type="date" id="filter_date_to" name="filter_date_to" value="{{ request('filter_date_to') }}">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-filter"></i>
                        Filter
                    </button>
                    <a href="{{ route('superadmin.iot-research') }}" class="btn-clear">
                        <i class="fas fa-times"></i>
                        Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
        <div class="chart-container">
            <div class="chart-header">
                <h3 class="chart-title">
                    <i class="fas fa-chart-line"></i>
                    Tren Data Sensor (30 Hari Terakhir)
                </h3>
            </div>
            <div class="chart-content">
                <canvas id="sensorTrendsChart"></canvas>
            </div>
        </div>

        <div class="chart-container">
            <div class="chart-header">
                <h3 class="chart-title">
                    <i class="fas fa-chart-bar"></i>
                    Data Points per Proyek
                </h3>
            </div>
            <div class="chart-content">
                <canvas id="projectsDataChart"></canvas>
            </div>
        </div>

        <div class="chart-container">
            <div class="chart-header">
                <h3 class="chart-title">
                    <i class="fas fa-chart-pie"></i>
                    Distribusi Status Proyek
                </h3>
            </div>
            <div class="chart-content">
                <canvas id="statusDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Projects Table -->
    <div class="table-section">
        <div class="table-header">
            <h3 class="table-title">
                <i class="fas fa-list"></i>
                Daftar Proyek Penelitian
            </h3>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Proyek</th>
                        <th>Kelas</th>
                        <th>Guru</th>
                        <th>Status</th>
                        <th>Tanggal Mulai</th>
                        <th>Data Points</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($researchProjects as $project)
                        <tr>
                            <td>
                                <div class="project-name">
                                    <strong>{{ $project->project_name ?? 'N/A' }}</strong>
                                    @if($project->description)
                                        <small class="text-gray-500">{{ Str::limit($project->description, 50) }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $project->kelas->nama_kelas ?? 'N/A' }}</td>
                            <td>{{ $project->teacher->name ?? 'N/A' }}</td>
                            <td>
                                <span class="status-badge status-{{ $project->status }}">
                                    {{ $project->status_label }}
                                </span>
                            </td>
                            <td>{{ $project->start_date ? $project->start_date->format('d M Y') : 'N/A' }}</td>
                            <td>
                                <span class="data-points">{{ number_format($project->sensor_data_count) }}</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-view" onclick="viewProject({{ $project->id }})" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-edit" onclick="editProject({{ $project->id }})" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-delete" onclick="deleteProject({{ $project->id }})" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 py-8">
                                <i class="fas fa-inbox text-4xl mb-4"></i>
                                <p>Belum ada proyek penelitian IoT</p>
                                <button class="btn-primary mt-4" onclick="openCreateProjectModal()">
                                    <i class="fas fa-plus"></i>
                                    Buat Proyek Pertama
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($researchProjects->hasPages())
            <div class="pagination-container">
                {{ $researchProjects->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Custom Styles -->
<style>
.page-container {
    padding: 1.5rem;
    max-width: 1400px;
    margin: 0 auto;
    background: transparent;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid rgba(51, 65, 85, 0.5);
}

.header-content h1 {
    font-size: 2rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0;
}

.header-content p {
    color: #cbd5e1;
    margin: 0.5rem 0 0 0;
}

.header-actions .btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.header-actions .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: rgba(30, 41, 59, 0.8);
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(51, 65, 85, 0.5);
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
    background: rgba(30, 41, 59, 0.9);
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #ffffff;
    line-height: 1;
}

.stat-label {
    color: #cbd5e1;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.filter-section {
    background: rgba(30, 41, 59, 0.8);
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(51, 65, 85, 0.5);
    backdrop-filter: blur(10px);
}

.filter-form {
    width: 100%;
}

.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    align-items: end;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-group select,
.form-group input {
    padding: 0.75rem;
    border: 1px solid rgba(51, 65, 85, 0.5);
    border-radius: 0.5rem;
    font-size: 0.875rem;
    transition: border-color 0.3s ease;
    background: rgba(15, 23, 42, 0.8);
    color: #ffffff;
}

.form-group select:focus,
.form-group input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3);
    background: rgba(15, 23, 42, 0.9);
}

.btn-filter,
.btn-clear {
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
}

.btn-filter {
    background: #667eea;
    color: white;
}

.btn-filter:hover {
    background: #5a67d8;
}

.btn-clear {
    background: #6b7280;
    color: white;
}

.btn-clear:hover {
    background: #4b5563;
}

.charts-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.chart-container {
    background: rgba(30, 41, 59, 0.8);
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(51, 65, 85, 0.5);
    backdrop-filter: blur(10px);
}

.chart-header {
    margin-bottom: 1rem;
}

.chart-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #ffffff;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.chart-content {
    position: relative;
    height: 300px;
}

.table-section {
    background: rgba(30, 41, 59, 0.8);
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(51, 65, 85, 0.5);
    backdrop-filter: blur(10px);
}

.table-header {
    margin-bottom: 1rem;
}

.table-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #ffffff;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.table-container {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid rgba(51, 65, 85, 0.5);
}

.data-table th {
    background: rgba(15, 23, 42, 0.8);
    font-weight: 600;
    color: #ffffff;
    font-size: 0.875rem;
}

.data-table td {
    color: #cbd5e1;
}

.project-name strong {
    display: block;
    margin-bottom: 0.25rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-active {
    background: #d1fae5;
    color: #065f46;
}

.status-completed {
    background: #dbeafe;
    color: #1e40af;
}

.status-paused {
    background: #fef3c7;
    color: #92400e;
}

.data-points {
    font-weight: 600;
    color: #667eea;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.action-buttons button {
    width: 32px;
    height: 32px;
    border-radius: 0.375rem;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-view {
    background: #dbeafe;
    color: #1e40af;
}

.btn-view:hover {
    background: #bfdbfe;
}

.btn-edit {
    background: #fef3c7;
    color: #92400e;
}

.btn-edit:hover {
    background: #fde68a;
}

.btn-delete {
    background: #fee2e2;
    color: #dc2626;
}

.btn-delete:hover {
    background: #fecaca;
}

.pagination-container {
    margin-top: 1.5rem;
    display: flex;
    justify-content: center;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .filter-row {
        grid-template-columns: 1fr;
    }
    
    .charts-section {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<!-- JavaScript for Charts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart data from controller
    const chartData = @json($chartData ?? []);
    const statusDistribution = @json($statusDistribution ?? []);
    const projectsData = @json($projectsData ?? []);

    // Sensor Trends Chart (Line Chart)
    const sensorTrendsCtx = document.getElementById('sensorTrendsChart');
    if (sensorTrendsCtx) {
        new Chart(sensorTrendsCtx, {
            type: 'line',
            data: {
                labels: chartData.labels || [],
                datasets: [
                    {
                        label: 'Temperature (°C)',
                        data: chartData.temperature || [],
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Humidity (%)',
                        data: chartData.humidity || [],
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Soil Moisture (%)',
                        data: chartData.soil_moisture || [],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#ffffff'
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#ffffff',
                        bodyColor: '#cbd5e1',
                        borderColor: 'rgba(51, 65, 85, 0.5)',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Tanggal',
                            color: '#ffffff'
                        },
                        ticks: {
                            color: '#cbd5e1'
                        },
                        grid: {
                            color: 'rgba(51, 65, 85, 0.3)'
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Nilai',
                            color: '#ffffff'
                        },
                        ticks: {
                            color: '#cbd5e1'
                        },
                        grid: {
                            color: 'rgba(51, 65, 85, 0.3)'
                        }
                    }
                }
            }
        });
    }

    // Projects Data Chart (Bar Chart)
    const projectsDataCtx = document.getElementById('projectsDataChart');
    if (projectsDataCtx) {
        new Chart(projectsDataCtx, {
            type: 'bar',
            data: {
                labels: projectsData.map(project => project.project_name || 'N/A').slice(0, 5),
                datasets: [{
                    label: 'Data Points',
                    data: projectsData.map(project => project.sensor_data_count || 0).slice(0, 5),
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(118, 75, 162, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)'
                    ],
                    borderColor: [
                        '#667eea',
                        '#764ba2',
                        '#ef4444',
                        '#10b981',
                        '#f59e0b'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#ffffff',
                        bodyColor: '#cbd5e1',
                        borderColor: 'rgba(51, 65, 85, 0.5)',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#cbd5e1'
                        },
                        grid: {
                            color: 'rgba(51, 65, 85, 0.3)'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Data Points',
                            color: '#ffffff'
                        },
                        ticks: {
                            color: '#cbd5e1'
                        },
                        grid: {
                            color: 'rgba(51, 65, 85, 0.3)'
                        }
                    }
                }
            }
        });
    }

    // Status Distribution Chart (Doughnut Chart)
    const statusDistributionCtx = document.getElementById('statusDistributionChart');
    if (statusDistributionCtx) {
        const statusLabels = {
            'active': 'Aktif',
            'completed': 'Selesai',
            'paused': 'Dijeda'
        };

        new Chart(statusDistributionCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(statusDistribution).map(key => statusLabels[key] || key),
                datasets: [{
                    data: Object.values(statusDistribution),
                    backgroundColor: [
                        '#10b981',
                        '#3b82f6',
                        '#f59e0b'
                    ],
                    borderColor: [
                        '#059669',
                        '#2563eb',
                        '#d97706'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#ffffff'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#ffffff',
                        bodyColor: '#cbd5e1',
                        borderColor: 'rgba(51, 65, 85, 0.5)',
                        borderWidth: 1
                    }
                }
            }
        });
    }
});

// Modal functions (placeholder for future implementation)
function openCreateProjectModal() {
    alert('Fitur buat proyek akan segera tersedia!');
}

function viewProject(id) {
    alert('Fitur lihat detail proyek akan segera tersedia!');
}

function editProject(id) {
    alert('Fitur edit proyek akan segera tersedia!');
}

function deleteProject(id) {
    if (confirm('Apakah Anda yakin ingin menghapus proyek ini?')) {
        alert('Fitur hapus proyek akan segera tersedia!');
    }
}
</script>
@endsection