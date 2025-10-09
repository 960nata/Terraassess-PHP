@extends('layouts.unified-layout-new')

@section('title', 'Proyek Penelitian IoT - Terra Assessment')

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

        .project-card {
            background: rgba(15, 15, 35, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(138, 43, 226, 0.1);
            border-radius: 16px;
            padding: 24px;
            transition: all 0.3s ease;
        }

        .project-card:hover {
            background: rgba(15, 15, 35, 0.8);
            border-color: rgba(138, 43, 226, 0.3);
            transform: translateY(-2px);
        }

        .status-active {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-completed {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.3);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-paused {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .progress-bar {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            height: 8px;
            overflow: hidden;
        }

        .progress-fill {
            background: linear-gradient(90deg, #8a2be2, #3b82f6);
            height: 100%;
            border-radius: 10px;
            transition: width 0.3s ease;
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
                        <h1 class="text-2xl font-bold text-white">Proyek Penelitian IoT</h1>
                        <p class="text-gray-400">Manajemen Proyek Penelitian Kelas Anda</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button onclick="openCreateProjectModal()" class="galaxy-button">
                        <i class="ph-plus"></i>
                        Buat Proyek
                    </button>
                    <a href="{{ route('teacher.iot.sensor-data') }}" class="galaxy-button secondary">
                        <i class="ph-chart-line"></i>
                        Data Sensor
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
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="iot-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Total Proyek</p>
                            <p class="text-3xl font-bold text-white">{{ $researchProjects->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                            <i class="ph-flask text-purple-400 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="iot-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Proyek Aktif</p>
                            <p class="text-3xl font-bold text-white">{{ $researchProjects->where('status', 'active')->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center">
                            <i class="ph-play text-emerald-400 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="iot-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Proyek Selesai</p>
                            <p class="text-3xl font-bold text-white">{{ $researchProjects->where('status', 'completed')->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                            <i class="ph-check-circle text-blue-400 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="iot-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Total Data</p>
                            <p class="text-3xl font-bold text-white">{{ $researchProjects->sum(function($project) { return $project->sensorData->count(); }) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-violet-500/20 rounded-xl flex items-center justify-center">
                            <i class="ph-database text-violet-400 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project List -->
            <div class="iot-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-white">Daftar Proyek Penelitian</h3>
                    <div class="flex items-center space-x-3">
                        <button onclick="refreshProjects()" class="galaxy-button secondary text-sm">
                            <i class="ph-arrow-clockwise"></i>
                            Refresh
                        </button>
                        <button onclick="exportProjects()" class="galaxy-button secondary text-sm">
                            <i class="ph-download"></i>
                            Export
                        </button>
                    </div>
                </div>

                @if($researchProjects->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($researchProjects as $project)
                    <div class="project-card">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h4 class="text-xl font-bold text-white mb-2">{{ $project->title }}</h4>
                                <p class="text-sm text-gray-400 mb-2">{{ $project->kelas->name }}</p>
                                <span class="status-badge {{ $project->status === 'active' ? 'status-active' : ($project->status === 'completed' ? 'status-completed' : 'status-paused') }}">
                                    {{ $project->status === 'active' ? 'Aktif' : ($project->status === 'completed' ? 'Selesai' : 'Dijeda') }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="viewProjectDetails({{ $project->id }})" class="text-purple-400 hover:text-purple-300" title="Lihat Detail">
                                    <i class="ph-eye text-lg"></i>
                                </button>
                                <button onclick="editProject({{ $project->id }})" class="text-blue-400 hover:text-blue-300" title="Edit">
                                    <i class="ph-pencil text-lg"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <p class="text-gray-300 text-sm leading-relaxed">{{ Str::limit($project->description, 150) }}</p>
                        </div>

                        <!-- Project Progress -->
                        <div class="mb-4">
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-400">Progress</span>
                                <span class="text-white">{{ $project->sensorData->count() }} Data</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ min(($project->sensorData->count() / 100) * 100, 100) }}%"></div>
                            </div>
                        </div>

                        <!-- Project Info -->
                        <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                            <div>
                                <span class="text-gray-400">Tujuan:</span>
                                <p class="text-white mt-1">{{ Str::limit($project->objectives, 50) }}</p>
                            </div>
                            <div>
                                <span class="text-gray-400">Metodologi:</span>
                                <p class="text-white mt-1">{{ Str::limit($project->methodology, 50) }}</p>
                            </div>
                        </div>

                        <!-- Project Actions -->
                        <div class="flex justify-between items-center pt-4 border-t border-gray-700">
                            <div class="text-xs text-gray-400">
                                Dibuat: {{ $project->created_at->format('d M Y') }}
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('teacher.iot.sensor-data', ['research_project_id' => $project->id]) }}" class="galaxy-button secondary text-sm">
                                    <i class="ph-chart-line"></i>
                                    Data
                                </a>
                                <button onclick="viewProjectChart({{ $project->id }})" class="galaxy-button text-sm">
                                    <i class="ph-chart-bar"></i>
                                    Grafik
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12 text-gray-400">
                    <i class="ph-flask text-6xl mb-4 block"></i>
                    <h4 class="text-xl font-semibold mb-2">Belum Ada Proyek Penelitian</h4>
                    <p class="mb-4">Mulai buat proyek penelitian untuk kelas Anda</p>
                    <button onclick="openCreateProjectModal()" class="galaxy-button">
                        <i class="ph-plus"></i>
                        Buat Proyek Pertama
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create Project Modal -->
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
                    <label class="form-label">Kelas</label>
                    <select name="kelas_id" class="form-input" required>
                        <option value="">Pilih Kelas</option>
                        @foreach($assignedKelas as $kelasId => $kelasMapel)
                            @php $kelas = $kelasMapel->first()->kelas; @endphp
                            <option value="{{ $kelasId }}">{{ $kelas->name }}</option>
                        @endforeach
                    </select>
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

    <!-- Project Detail Modal -->
    <div id="projectDetailModal" class="modal">
        <div class="modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-white">Detail Proyek</h3>
                <button onclick="closeProjectDetailModal()" class="text-gray-400 hover:text-white">
                    <i class="ph-x text-xl"></i>
                </button>
            </div>
            <div id="projectDetailContent">
                <!-- Project detail content will be loaded here -->
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    function openCreateProjectModal() {
        document.getElementById('createProjectModal').classList.add('show');
    }

    function closeCreateProjectModal() {
        document.getElementById('createProjectModal').classList.remove('show');
    }

    function refreshProjects() {
        location.reload();
    }

    function exportProjects() {
        alert('Fitur export proyek akan segera tersedia');
    }

    function viewProjectDetails(projectId) {
        // Simulate loading project details
        document.getElementById('projectDetailContent').innerHTML = `
            <div class="text-center py-8">
                <i class="ph-spinner text-4xl text-purple-400 animate-spin mb-4"></i>
                <p class="text-gray-400">Memuat detail proyek...</p>
            </div>
        `;
        
        document.getElementById('projectDetailModal').classList.add('show');
        
        // In real implementation, you would fetch data from API
        setTimeout(() => {
            document.getElementById('projectDetailContent').innerHTML = `
                <div class="space-y-4">
                    <div class="iot-card p-4">
                        <h4 class="font-semibold text-white mb-2">Proyek ID: ${projectId}</h4>
                        <p class="text-sm text-gray-400">Detail proyek akan ditampilkan di sini</p>
                    </div>
                </div>
            `;
        }, 1000);
    }

    function closeProjectDetailModal() {
        document.getElementById('projectDetailModal').classList.remove('show');
    }

    function editProject(projectId) {
        alert(`Edit proyek ${projectId} akan segera tersedia`);
    }

    function viewProjectChart(projectId) {
        alert(`Grafik proyek ${projectId} akan segera tersedia`);
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

    // Close modals when clicking outside
    document.getElementById('createProjectModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCreateProjectModal();
        }
    });

    document.getElementById('projectDetailModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeProjectDetailModal();
        }
    });

    // Add hover effects
    document.addEventListener('DOMContentLoaded', function() {
        const projectCards = document.querySelectorAll('.project-card');
        projectCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>
@endsection