@extends('layouts.unified-layout')

@section('title', 'Data IoT - ' . $kelasMapel->kelas->name . ' - Terra Assessment')

@section('content')
    <style>
        .iot-card {
            background: rgba(15, 15, 35, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(138, 43, 226, 0.2);
            border-radius: 16px;
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .sensor-value {
            font-size: 2rem;
            font-weight: 800;
            color: #8a2be2;
            text-shadow: 0 0 20px rgba(138, 43, 226, 0.5);
        }

        .chart-container {
            background: rgba(15, 15, 35, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(138, 43, 226, 0.1);
            border-radius: 16px;
            padding: 24px;
            height: 400px;
        }

        .data-table {
            background: rgba(15, 15, 35, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(138, 43, 226, 0.1);
            border-radius: 16px;
            overflow: hidden;
        }

        .data-table th {
            background: rgba(15, 15, 35, 0.8);
            color: #e2e8f0;
            font-weight: 600;
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid rgba(138, 43, 226, 0.1);
        }

        .data-table td {
            padding: 16px;
            color: #cbd5e1;
            border-bottom: 1px solid rgba(138, 43, 226, 0.05);
        }

        .data-table tr:hover {
            background: rgba(138, 43, 226, 0.05);
        }

        .quality-excellent {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .quality-good {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.3);
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .quality-needs-attention {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>

    <div class="galaxy-container">
        <!-- Galaxy Header -->
        <div class="galaxy-header">
            <div class="flex items-center justify-between p-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('teacher.iot.dashboard') }}" class="galaxy-nav-item">
                        <i class="ph-arrow-left galaxy-nav-icon"></i>
                        <span class="galaxy-nav-text">Kembali</span>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-white">{{ $kelasMapel->kelas->name }}</h1>
                        <p class="text-gray-400">Data IoT - {{ $kelasMapel->mapel->name }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('teacher.iot.sensor-data', ['kelas_id' => $kelasMapel->kelas->id]) }}" class="galaxy-button">
                        <i class="ph-chart-line"></i>
                        Lihat Semua Data
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="galaxy-button secondary">
                            <i class="ph-sign-out"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="main-content">
            <!-- Current Sensor Values -->
            @if($devices->count() > 0)
            <div class="iot-card p-6 mb-6">
                <h3 class="text-lg font-bold text-white mb-4">Nilai Sensor Saat Ini</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($devices as $device)
                        @if($device->latestSensorData)
                            <div class="text-center">
                                <div class="w-16 h-16 bg-cyan-500/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                                    <i class="ph-thermometer text-cyan-400 text-2xl"></i>
                                </div>
                                <h4 class="font-semibold text-white mb-2">{{ $device->name }}</h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-400">Suhu:</span>
                                        <span class="text-cyan-400 font-semibold">{{ $device->latestSensorData->temperature }}°C</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-400">Kelembaban:</span>
                                        <span class="text-blue-400 font-semibold">{{ $device->latestSensorData->humidity }}%</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-400">Tanah:</span>
                                        <span class="text-green-400 font-semibold">{{ $device->latestSensorData->soil_moisture }}%</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-400">pH:</span>
                                        <span class="text-purple-400 font-semibold">{{ $device->latestSensorData->ph_level }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Data Table -->
                <div class="iot-card p-6">
                    <h3 class="text-lg font-bold text-white mb-4">Data Terbaru</h3>
                    <div class="data-table">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th>Device</th>
                                    <th>Suhu</th>
                                    <th>Kelembaban</th>
                                    <th>Tanah</th>
                                    <th>pH</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sensorData->take(10) as $data)
                                <tr>
                                    <td>
                                        <div class="flex items-center space-x-2">
                                            <div class="w-8 h-8 bg-cyan-500/20 rounded-lg flex items-center justify-center">
                                                <i class="ph-thermometer text-cyan-400 text-sm"></i>
                                            </div>
                                            <span class="text-white font-medium">{{ $data->device->name ?? 'Unknown' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-cyan-400 font-semibold">{{ $data->temperature }}°C</span>
                                    </td>
                                    <td>
                                        <span class="text-blue-400 font-semibold">{{ $data->humidity }}%</span>
                                    </td>
                                    <td>
                                        <span class="text-green-400 font-semibold">{{ $data->soil_moisture }}%</span>
                                    </td>
                                    <td>
                                        <span class="text-purple-400 font-semibold">{{ $data->ph_level }}</span>
                                    </td>
                                    <td>
                                        <span class="text-gray-400 text-sm">{{ $data->measured_at->format('H:i') }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8 text-gray-400">
                                        <i class="ph-thermometer text-4xl mb-2 block"></i>
                                        Belum ada data sensor
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Research Projects -->
                <div class="iot-card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-white">Proyek Penelitian</h3>
                        <button onclick="openCreateProjectModal()" class="galaxy-button text-sm">
                            <i class="ph-plus"></i>
                            Buat Proyek
                        </button>
                    </div>
                    
                    <div class="space-y-3">
                        @forelse($researchProjects as $project)
                        <div class="class-card">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-semibold text-white">{{ $project->title }}</h4>
                                <span class="status-badge {{ $project->status === 'active' ? 'status-active' : 'status-inactive' }}">
                                    {{ $project->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-400 mb-2">{{ Str::limit($project->description, 100) }}</p>
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>{{ $project->created_at->format('d M Y') }}</span>
                                <span>{{ $project->sensorData->count() }} Data</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-400">
                            <i class="ph-flask text-4xl mb-2 block"></i>
                            <p>Belum ada proyek penelitian</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="iot-card p-6 mt-6">
                <h3 class="text-lg font-bold text-white mb-4">Grafik Data Sensor</h3>
                <div class="chart-container">
                    <canvas id="sensorChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Research Project Modal -->
    <div id="createProjectModal" class="modal">
        <div class="modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-white">Buat Proyek Penelitian</h3>
                <button onclick="closeCreateProjectModal()" class="text-gray-400 hover:text-white">
                    <i class="ph-x text-xl"></i>
                </button>
            </div>

            <form id="createProjectForm">
                @csrf
                <div class="form-group">
                    <label class="form-label">Judul Proyek</label>
                    <input type="text" name="title" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-input" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Tujuan</label>
                    <textarea name="objectives" class="form-input" rows="2" required></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Metodologi</label>
                    <textarea name="methodology" class="form-input" rows="2" required></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Hasil yang Diharapkan</label>
                    <textarea name="expected_outcomes" class="form-input" rows="2" required></textarea>
                </div>

                <input type="hidden" name="kelas_id" value="{{ $kelasMapel->kelas->id }}">

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeCreateProjectModal()" class="galaxy-button secondary">
                        Batal
                    </button>
                    <button type="submit" class="galaxy-button">
                        <i class="ph-check"></i>
                        Buat Proyek
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
        // Chart initialization
        const ctx = document.getElementById('sensorChart').getContext('2d');
        const sensorChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Suhu (°C)',
                    data: [],
                    borderColor: '#00d4ff',
                    backgroundColor: 'rgba(0, 212, 255, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Kelembaban (%)',
                    data: [],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Kelembaban Tanah (%)',
                    data: [],
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#e2e8f0'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#94a3b8'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    },
                    y: {
                        ticks: {
                            color: '#94a3b8'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                }
            }
        });

        // Load chart data
        function loadChartData() {
            fetch('{{ route("teacher.iot.realtime", ["kelas_id" => $kelasMapel->kelas->id]) }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const chartData = data.data.slice(0, 20).reverse();
                        sensorChart.data.labels = chartData.map(d => new Date(d.measured_at).toLocaleTimeString());
                        sensorChart.data.datasets[0].data = chartData.map(d => d.temperature);
                        sensorChart.data.datasets[1].data = chartData.map(d => d.humidity);
                        sensorChart.data.datasets[2].data = chartData.map(d => d.soil_moisture);
                        sensorChart.update();
                    }
                })
                .catch(error => console.error('Error loading chart data:', error));
        }

        // Load initial data
        loadChartData();

        // Auto refresh every 30 seconds
        setInterval(loadChartData, 30000);

        // Modal functions
        function openCreateProjectModal() {
            document.getElementById('createProjectModal').classList.add('show');
        }

        function closeCreateProjectModal() {
            document.getElementById('createProjectModal').classList.remove('show');
        }

        // Form submission
        document.getElementById('createProjectForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route("teacher.iot.create-project") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Proyek penelitian berhasil dibuat');
                    location.reload();
                } else {
                    alert('Gagal membuat proyek: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat membuat proyek');
            });
        });

        // Close modal when clicking outside
        document.getElementById('createProjectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCreateProjectModal();
            }
        });
    </script>
@endsection
