@extends('layouts.unified-layout')

@section('container')
    <h1 class="text-white">🔬 Proyek Penelitian IoT</h1>
    <span class="text-white-75">Kelola dan pantau proyek penelitian siswa berdasarkan data IoT</span>
    <hr class="border-white-25">

    <!-- Create New Project -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="text-white mb-0">Buat Proyek Penelitian Baru</h5>
                    <button class="btn btn-glass" data-bs-toggle="modal" data-bs-target="#createProjectModal">
                        <i class="fas fa-plus me-1"></i> Buat Proyek
                    </button>
                </div>
                <p class="text-white-75 small mb-0">Buat proyek penelitian baru untuk memantau data IoT siswa per kelas</p>
            </div>
        </div>
    </div>

    <!-- Research Projects List -->
    <div class="row">
        @forelse($projects as $project)
            <div class="col-lg-6 mb-4">
                <div class="glass-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="text-white mb-0">{{ $project->project_name }}</h5>
                        <span class="badge {{ $project->status_badge_class }}">{{ $project->status_label }}</span>
                    </div>
                    <div class="card-body">
                        <p class="text-white-75">{{ $project->description ?: 'Tidak ada deskripsi' }}</p>
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-white-50">Kelas:</small>
                                <p class="text-white mb-0">{{ $project->kelas->nama_kelas ?? 'Unknown' }}</p>
                            </div>
                            <div class="col-6">
                                <small class="text-white-50">Durasi:</small>
                                <p class="text-white mb-0">{{ $project->duration }} hari</p>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-white-50">Mulai:</small>
                                <p class="text-white mb-0">{{ $project->start_date->format('d/m/Y') }}</p>
                            </div>
                            <div class="col-6">
                                <small class="text-white-50">Selesai:</small>
                                <p class="text-white mb-0">{{ $project->end_date ? $project->end_date->format('d/m/Y') : 'Berlangsung' }}</p>
                            </div>
                        </div>

                        @if($project->conclusion)
                            <div class="mb-3">
                                <small class="text-white-50">Kesimpulan:</small>
                                <p class="text-white-75 small">{{ Str::limit($project->conclusion, 100) }}</p>
                            </div>
                        @endif

                        <div class="d-flex gap-2">
                            <button class="btn btn-glass btn-sm" onclick="viewProjectData({{ $project->id }})">
                                <i class="fas fa-chart-line me-1"></i> Lihat Data
                            </button>
                            <button class="btn btn-glass btn-sm" onclick="editProject({{ $project->id }})">
                                <i class="fas fa-edit me-1"></i> Edit
                            </button>
                            @if($project->status === 'active')
                                <button class="btn btn-glass btn-sm" onclick="completeProject({{ $project->id }})">
                                    <i class="fas fa-check me-1"></i> Selesai
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="glass-card p-5 text-center">
                    <i class="fas fa-flask fa-4x text-warning mb-3"></i>
                    <h4 class="text-white">Belum Ada Proyek Penelitian</h4>
                    <p class="text-white-75">Mulai buat proyek penelitian pertama Anda untuk memantau data IoT siswa</p>
                    <button class="btn btn-glass" data-bs-toggle="modal" data-bs-target="#createProjectModal">
                        <i class="fas fa-plus me-1"></i> Buat Proyek Pertama
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Create Project Modal -->
    <div class="modal fade" id="createProjectModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white">Buat Proyek Penelitian Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="createProjectForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Nama Proyek</label>
                                <input type="text" class="form-control" name="project_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Kelas</label>
                                <select class="form-select" name="kelas_id" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach($kelas as $kelasItem)
                                        <option value="{{ $kelasItem->id }}">{{ $kelasItem->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-white">Deskripsi Proyek</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Jelaskan tujuan dan metode penelitian"></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Tanggal Mulai</label>
                                <input type="date" class="form-control" name="start_date" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white">Tanggal Selesai (Opsional)</label>
                                <input type="date" class="form-control" name="end_date">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-white">Parameter Penelitian</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="research_parameters[]" value="temperature" id="param_temp">
                                        <label class="form-check-label text-white" for="param_temp">Suhu Tanah</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="research_parameters[]" value="humidity" id="param_humidity">
                                        <label class="form-check-label text-white" for="param_humidity">Kelembaban Udara</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="research_parameters[]" value="soil_moisture" id="param_moisture">
                                        <label class="form-check-label text-white" for="param_moisture">Kelembaban Tanah</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-glass">
                            <i class="fas fa-save me-1"></i> Buat Proyek
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Project Data Modal -->
    <div class="modal fade" id="projectDataModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white">Data Proyek Penelitian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="projectDataContent">
                        <div class="text-center">
                            <i class="fas fa-spinner fa-spin fa-2x text-warning"></i>
                            <p class="text-white-75 mt-2">Memuat data...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set default start date to today
    document.querySelector('input[name="start_date"]').valueAsDate = new Date();
    
    // Create project form
    document.getElementById('createProjectForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        
        // Convert research parameters to array
        data.research_parameters = Array.from(document.querySelectorAll('input[name="research_parameters[]"]:checked')).map(cb => cb.value);
        
        try {
            const response = await fetch('/api/iot/research-project', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                location.reload();
            } else {
                alert('Gagal membuat proyek: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat membuat proyek');
        }
    });
});

// View project data
async function viewProjectData(projectId) {
    const modal = new bootstrap.Modal(document.getElementById('projectDataModal'));
    modal.show();
    
    try {
        const response = await fetch(`/iot/research-project/${projectId}/data`);
        const result = await response.json();
        
        if (result.success) {
            displayProjectData(result.project, result.sensor_data);
        } else {
            document.getElementById('projectDataContent').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Gagal memuat data proyek
                </div>
            `;
        }
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('projectDataContent').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Terjadi kesalahan saat memuat data
            </div>
        `;
    }
}

// Display project data
function displayProjectData(project, sensorData) {
    const content = document.getElementById('projectDataContent');
    
    if (sensorData.length === 0) {
        content.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-database fa-3x text-warning mb-3"></i>
                <h5 class="text-white">Belum Ada Data Sensor</h5>
                <p class="text-white-75">Data sensor akan muncul setelah siswa melakukan pengukuran</p>
            </div>
        `;
        return;
    }
    
    // Create chart data
    const labels = sensorData.map(d => new Date(d.measured_at).toLocaleDateString());
    const temperatureData = sensorData.map(d => d.temperature);
    const humidityData = sensorData.map(d => d.humidity);
    const moistureData = sensorData.map(d => d.soil_moisture);
    
    content.innerHTML = `
        <div class="row mb-4">
            <div class="col-12">
                <h5 class="text-white">${project.project_name}</h5>
                <p class="text-white-75">${project.description || 'Tidak ada deskripsi'}</p>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <h6 class="text-white">Total Data</h6>
                    <h3 class="text-warning">${sensorData.length}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <h6 class="text-white">Rata-rata Suhu</h6>
                    <h3 class="text-warning">${(temperatureData.reduce((a, b) => a + b, 0) / temperatureData.length).toFixed(1)}°C</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <h6 class="text-white">Rata-rata Kelembaban</h6>
                    <h3 class="text-warning">${(humidityData.reduce((a, b) => a + b, 0) / humidityData.length).toFixed(1)}%</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glass-card p-3 text-center">
                    <h6 class="text-white">Rata-rata Kelembaban Tanah</h6>
                    <h3 class="text-warning">${(moistureData.reduce((a, b) => a + b, 0) / moistureData.length).toFixed(1)}%</h3>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="glass-card p-4">
                    <h6 class="text-white mb-3">Grafik Data Sensor</h6>
                    <canvas id="projectChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    `;
    
    // Create chart
    setTimeout(() => {
        const ctx = document.getElementById('projectChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Suhu Tanah (°C)',
                        data: temperatureData,
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        tension: 0.1
                    },
                    {
                        label: 'Kelembaban Udara (%)',
                        data: humidityData,
                        borderColor: 'rgb(54, 162, 235)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        tension: 0.1
                    },
                    {
                        label: 'Kelembaban Tanah (%)',
                        data: moistureData,
                        borderColor: 'rgb(255, 205, 86)',
                        backgroundColor: 'rgba(255, 205, 86, 0.2)',
                        tension: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: 'white'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: 'white'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: 'white'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                }
            }
        });
    }, 100);
}

// Edit project
function editProject(projectId) {
    // Implement edit functionality
    try {
        // Redirect to edit project page
        window.location.href = `/teacher/iot/research-projects/${projectId}/edit`;
    } catch (error) {
        console.error('Error navigating to edit project:', error);
        alert('Terjadi kesalahan saat membuka halaman edit proyek');
    }
}

// Complete project
async function completeProject(projectId) {
    if (confirm('Apakah Anda yakin ingin menyelesaikan proyek ini?')) {
        try {
            // Send completion request to server
            const response = await fetch(`/teacher/iot/research-projects/${projectId}/complete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert('Proyek berhasil diselesaikan!');
                // Reload the page to reflect changes
                location.reload();
            } else {
                alert('Gagal menyelesaikan proyek: ' + (data.message || 'Terjadi kesalahan'));
            }
        } catch (error) {
            console.error('Error completing project:', error);
            alert('Terjadi kesalahan saat menyelesaikan proyek');
        }
    }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush
