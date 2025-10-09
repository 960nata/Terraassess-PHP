@extends('layouts.unified-layout-new')

@section('title', 'Device IoT - Terra Assessment')

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

        .device-card {
            background: rgba(15, 15, 35, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(138, 43, 226, 0.1);
            border-radius: 16px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .device-card:hover {
            background: rgba(15, 15, 35, 0.8);
            border-color: rgba(138, 43, 226, 0.3);
            transform: translateY(-2px);
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

        .status-maintenance {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .sensor-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #8a2be2;
            text-shadow: 0 0 10px rgba(138, 43, 226, 0.3);
        }

        .sensor-label {
            font-size: 0.8rem;
            color: #a0a0a0;
            margin-top: 0.25rem;
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
                        <h1 class="text-2xl font-bold text-white">Device IoT</h1>
                        <p class="text-gray-400">Manajemen Device Sensor Kelas Anda</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('teacher.iot.sensor-data') }}" class="galaxy-button secondary">
                        <i class="ph-chart-line"></i>
                        Data Sensor
                    </a>
                    <a href="{{ route('teacher.iot.research-projects') }}" class="galaxy-button secondary">
                        <i class="ph-flask"></i>
                        Proyek
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
                            <p class="text-gray-400 text-sm">Total Device</p>
                            <p class="text-3xl font-bold text-white">{{ $devices->count() }}</p>
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
                            <p class="text-3xl font-bold text-white">{{ $devices->where('status', 'online')->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center">
                            <i class="ph-wifi-high text-emerald-400 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="iot-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Device Offline</p>
                            <p class="text-3xl font-bold text-white">{{ $devices->where('status', 'offline')->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-red-500/20 rounded-xl flex items-center justify-center">
                            <i class="ph-wifi-slash text-red-400 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="iot-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Total Data</p>
                            <p class="text-3xl font-bold text-white">{{ $devices->sum(function($device) { return $device->sensorData->count(); }) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                            <i class="ph-database text-blue-400 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Device List -->
            <div class="iot-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-white">Daftar Device</h3>
                    <div class="flex items-center space-x-3">
                        <button onclick="refreshDevices()" class="galaxy-button secondary text-sm">
                            <i class="ph-arrow-clockwise"></i>
                            Refresh
                        </button>
                        <button onclick="exportDevices()" class="galaxy-button secondary text-sm">
                            <i class="ph-download"></i>
                            Export
                        </button>
                    </div>
                </div>

                @if($devices->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($devices as $device)
                    <div class="device-card">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                                    <i class="ph-thermometer text-purple-400 text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-white">{{ $device->name }}</h4>
                                    <p class="text-sm text-gray-400">{{ $device->device_id }}</p>
                                </div>
                            </div>
                            <span class="status-badge {{ $device->isOnline() ? 'status-online' : 'status-offline' }}">
                                {{ $device->isOnline() ? 'Online' : 'Offline' }}
                            </span>
                        </div>

                        <!-- Device Info -->
                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Type:</span>
                                <span class="text-white">{{ $device->device_type ?? 'Unknown' }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Status:</span>
                                <span class="text-white capitalize">{{ $device->status ?? 'Unknown' }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Last Seen:</span>
                                <span class="text-white">{{ $device->last_seen ? $device->last_seen->diffForHumans() : 'Never' }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Total Data:</span>
                                <span class="text-white">{{ $device->sensorData->count() }}</span>
                            </div>
                        </div>

                        <!-- Latest Sensor Data -->
                        @if($device->latestSensorData)
                        <div class="border-t border-gray-700 pt-4">
                            <h5 class="text-sm font-semibold text-white mb-3">Data Terbaru</h5>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="text-center">
                                    <div class="sensor-value">{{ $device->latestSensorData->temperature }}°C</div>
                                    <div class="sensor-label">Suhu</div>
                                </div>
                                <div class="text-center">
                                    <div class="sensor-value">{{ $device->latestSensorData->humidity }}%</div>
                                    <div class="sensor-label">Kelembaban</div>
                                </div>
                                <div class="text-center">
                                    <div class="sensor-value">{{ $device->latestSensorData->soil_moisture }}%</div>
                                    <div class="sensor-label">Tanah</div>
                                </div>
                                <div class="text-center">
                                    <div class="sensor-value">{{ $device->latestSensorData->ph_level }}</div>
                                    <div class="sensor-label">pH</div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <span class="text-xs text-gray-400">
                                    {{ $device->latestSensorData->measured_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        @else
                        <div class="border-t border-gray-700 pt-4 text-center">
                            <i class="ph-thermometer text-2xl text-gray-500 mb-2"></i>
                            <p class="text-sm text-gray-400">Belum ada data sensor</p>
                        </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex justify-between mt-4 pt-4 border-t border-gray-700">
                            <a href="{{ route('teacher.iot.sensor-data', ['device_id' => $device->id]) }}" class="galaxy-button secondary text-sm">
                                <i class="ph-chart-line"></i>
                                Data
                            </a>
                            <button onclick="viewDeviceDetails({{ $device->id }})" class="galaxy-button text-sm">
                                <i class="ph-eye"></i>
                                Detail
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12 text-gray-400">
                    <i class="ph-device-mobile text-6xl mb-4 block"></i>
                    <h4 class="text-xl font-semibold mb-2">Belum Ada Device</h4>
                    <p>Device IoT akan muncul di sini setelah terdaftar dalam sistem</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Device Detail Modal -->
    <div id="deviceDetailModal" class="modal">
        <div class="modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-white">Detail Device</h3>
                <button onclick="closeDeviceDetailModal()" class="text-gray-400 hover:text-white">
                    <i class="ph-x text-xl"></i>
                </button>
            </div>
            <div id="deviceDetailContent">
                <!-- Device detail content will be loaded here -->
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    function refreshDevices() {
        location.reload();
    }

    function exportDevices() {
        alert('Fitur export device akan segera tersedia');
    }

    function viewDeviceDetails(deviceId) {
        // Simulate loading device details
        document.getElementById('deviceDetailContent').innerHTML = `
            <div class="text-center py-8">
                <i class="ph-spinner text-4xl text-purple-400 animate-spin mb-4"></i>
                <p class="text-gray-400">Memuat detail device...</p>
            </div>
        `;
        
        document.getElementById('deviceDetailModal').classList.add('show');
        
        // In real implementation, you would fetch data from API
        setTimeout(() => {
            document.getElementById('deviceDetailContent').innerHTML = `
                <div class="space-y-4">
                    <div class="iot-card p-4">
                        <h4 class="font-semibold text-white mb-2">Device ID: ${deviceId}</h4>
                        <p class="text-sm text-gray-400">Detail device akan ditampilkan di sini</p>
                    </div>
                </div>
            `;
        }, 1000);
    }

    function closeDeviceDetailModal() {
        document.getElementById('deviceDetailModal').classList.remove('show');
    }

    // Close modal when clicking outside
    document.getElementById('deviceDetailModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeviceDetailModal();
        }
    });

    // Auto refresh device status every 30 seconds
    setInterval(function() {
        fetch('{{ route("teacher.iot.device-status") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update device status indicators
                    data.devices.forEach(device => {
                        const statusElement = document.querySelector(`[data-device-id="${device.id}"] .status-badge`);
                        if (statusElement) {
                            statusElement.className = `status-badge ${device.is_online ? 'status-online' : 'status-offline'}`;
                            statusElement.textContent = device.is_online ? 'Online' : 'Offline';
                        }
                    });
                }
            })
            .catch(error => console.error('Error fetching device status:', error));
    }, 30000);

    // Add hover effects
    document.addEventListener('DOMContentLoaded', function() {
        const deviceCards = document.querySelectorAll('.device-card');
        deviceCards.forEach(card => {
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