@extends('layouts.unified-layout-new')

@section('title', 'IoT Dashboard - Terra Assessment')

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

        .iot-card:hover {
            transform: translateY(-4px);
            border-color: rgba(138, 43, 226, 0.4);
            box-shadow: 
                0 12px 40px rgba(138, 43, 226, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .sensor-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: #8a2be2;
            text-shadow: 0 0 20px rgba(138, 43, 226, 0.5);
        }

        .sensor-label {
            font-size: 0.9rem;
            color: #a0a0a0;
            margin-top: 0.5rem;
        }

        .sensor-unit {
            font-size: 1.2rem;
            color: #8a2be2;
            font-weight: 600;
        }

        .status-online {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-offline {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .class-card {
            background: rgba(15, 15, 35, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(138, 43, 226, 0.1);
            border-radius: 16px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .class-card:hover {
            background: rgba(15, 15, 35, 0.8);
            border-color: rgba(138, 43, 226, 0.3);
            transform: translateY(-2px);
        }

        .data-timeline {
            max-height: 400px;
            overflow-y: auto;
        }

        .timeline-item {
            background: rgba(15, 15, 35, 0.4);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(138, 43, 226, 0.1);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
        }

        .timeline-item:hover {
            background: rgba(15, 15, 35, 0.6);
            border-color: rgba(138, 43, 226, 0.3);
        }

        .chart-container {
            background: rgba(15, 15, 35, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(138, 43, 226, 0.1);
            border-radius: 16px;
            padding: 24px;
            height: 300px;
        }
    </style>

    <div class="galaxy-container">
        <!-- Galaxy Header -->
        <div class="galaxy-header">
            <div class="flex items-center justify-between p-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="galaxy-nav-item">
                        <i class="ph-arrow-left galaxy-nav-icon"></i>
                        <span class="galaxy-nav-text">Kembali</span>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-white">IoT Dashboard</h1>
                        <p class="text-gray-400">Monitoring Data Sensor Kelas Anda</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-white font-medium">{{ $user->name }}</p>
                        <p class="text-gray-400 text-sm">Guru</p>
                    </div>
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
                            <p class="text-gray-400 text-sm">Total Device</p>
                            <p class="text-3xl font-bold text-white">{{ $totalDevices }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                            <i class="ph-device-mobile text-purple-400 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="iot-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Device Online</p>
                            <p class="text-3xl font-bold text-white">{{ $onlineDevices }}</p>
                        </div>
                        <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center">
                            <i class="ph-wifi-high text-emerald-400 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="iot-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Total Data</p>
                            <p class="text-3xl font-bold text-white">{{ $totalReadings }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                            <i class="ph-database text-blue-400 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="iot-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Data Hari Ini</p>
                            <p class="text-3xl font-bold text-white">{{ $todayReadings }}</p>
                        </div>
                        <div class="w-12 h-12 bg-violet-500/20 rounded-xl flex items-center justify-center">
                            <i class="ph-chart-line text-violet-400 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Kelas yang Diampu -->
                <div class="lg:col-span-1">
                    <div class="iot-card p-6">
                        <h3 class="text-lg font-bold text-white mb-4">Kelas yang Diampu</h3>
                        <div class="space-y-3">
                            @forelse($assignedKelas as $kelasId => $kelasMapel)
                                @php $kelas = $kelasMapel->first()->kelas; @endphp
                                <a href="{{ route('teacher.iot.class', $kelasId) }}" class="class-card block">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-semibold text-white">{{ $kelas->name }}</h4>
                                            <p class="text-sm text-gray-400">
                                                {{ $kelasMapel->count() }} Mata Pelajaran
                                            </p>
                                        </div>
                                        <div class="w-8 h-8 bg-purple-500/20 rounded-lg flex items-center justify-center">
                                            <i class="ph-buildings text-purple-400"></i>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-8 text-gray-400">
                                    <i class="ph-buildings text-4xl mb-2 block"></i>
                                    <p>Belum ada kelas yang diampu</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Real-time Sensor Data -->
                <div class="lg:col-span-2">
                    <div class="iot-card p-6">
                        <h3 class="text-lg font-bold text-white mb-4">Data Sensor Real-time</h3>
                        
                        @if($recentData->count() > 0)
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                <div class="text-center">
                                    <div class="sensor-value">{{ $recentData->first()->temperature ?? '--' }}</div>
                                    <div class="sensor-unit">°C</div>
                                    <div class="sensor-label">Suhu</div>
                                </div>
                                <div class="text-center">
                                    <div class="sensor-value">{{ $recentData->first()->humidity ?? '--' }}</div>
                                    <div class="sensor-unit">%</div>
                                    <div class="sensor-label">Kelembaban</div>
                                </div>
                                <div class="text-center">
                                    <div class="sensor-value">{{ $recentData->first()->soil_moisture ?? '--' }}</div>
                                    <div class="sensor-unit">%</div>
                                    <div class="sensor-label">Kelembaban Tanah</div>
                                </div>
                                <div class="text-center">
                                    <div class="sensor-value">{{ $recentData->first()->ph_level ?? '--' }}</div>
                                    <div class="sensor-unit">pH</div>
                                    <div class="sensor-label">pH Tanah</div>
                                </div>
                            </div>

                            <!-- Data Timeline -->
                            <div class="data-timeline">
                                <h4 class="text-white font-semibold mb-3">Data Terbaru</h4>
                                @foreach($recentData->take(5) as $data)
                                <div class="timeline-item">
                                    <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                                        <i class="ph-thermometer text-purple-400"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <h5 class="text-white font-medium">{{ $data->device->name ?? 'Unknown Device' }}</h5>
                                            <span class="text-xs text-gray-400">{{ $data->measured_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="flex items-center space-x-4 mt-1">
                                            <span class="text-sm text-gray-400">{{ $data->kelas->name }}</span>
                                            <span class="text-sm text-purple-400">{{ $data->temperature }}°C</span>
                                            <span class="text-sm text-blue-400">{{ $data->humidity }}%</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12 text-gray-400">
                                <i class="ph-thermometer text-6xl mb-4 block"></i>
                                <h4 class="text-xl font-semibold mb-2">Belum Ada Data Sensor</h4>
                                <p>Data sensor akan muncul di sini setelah device IoT mengirim data</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Device Status -->
            @if($devices->count() > 0)
            <div class="iot-card p-6 mt-6">
                <h3 class="text-lg font-bold text-white mb-4">Status Device</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($devices as $device)
                    <div class="class-card">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-semibold text-white">{{ $device->name }}</h4>
                            <span class="status-badge {{ $device->isOnline() ? 'status-online' : 'status-offline' }}">
                                {{ $device->isOnline() ? 'Online' : 'Offline' }}
                            </span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Device ID:</span>
                                <span class="text-white">{{ $device->device_id }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Type:</span>
                                <span class="text-white">{{ $device->device_type ?? 'Unknown' }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Last Seen:</span>
                                <span class="text-white">{{ $device->last_seen ? $device->last_seen->diffForHumans() : 'Never' }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

@endsection

@section('scripts')
<script>
        // Auto refresh data every 30 seconds
        setInterval(function() {
            fetch('{{ route("teacher.iot.realtime") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update sensor values
                        const latestData = data.data[0];
                        if (latestData) {
                            document.querySelector('.sensor-value').textContent = latestData.temperature || '--';
                        }
                    }
                })
                .catch(error => console.error('Error fetching real-time data:', error));
        }, 30000);

        // Add hover effects
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.class-card, .timeline-item');
            cards.forEach(card => {
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
