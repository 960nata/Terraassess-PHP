@extends('layouts.unified-layout-new')

@section('title', 'Data Sensor IoT - Terra Assessment')

@section('content')
    <style>
        .iot-card {
            background: rgba(15, 15, 35, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(138, 43, 226, 0.2);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .data-table {
            background: rgba(15, 15, 35, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(138, 43, 226, 0.2);
            border-radius: 16px;
            overflow: hidden;
        }

        .data-table th {
            background: rgba(15, 15, 35, 0.85);
            color: #e2e8f0;
            font-weight: 600;
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .data-table td {
            padding: 16px;
            color: #cbd5e1;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .data-table tr:hover {
            background: rgba(138, 43, 226, 0.05);
        }

        .filter-card {
            background: rgba(15, 15, 35, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(138, 43, 226, 0.2);
            border-radius: 16px;
            padding: 20px;
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
</head>
<body>
    <div class="min-h-screen">
        <!-- Header -->
        <header class="iot-card m-6 mb-0">
            <div class="flex items-center justify-between p-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('teacher.iot.dashboard') }}" class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <i class="ph-arrow-left text-white text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-white">Data Sensor IoT</h1>
                        <p class="text-gray-400">Monitoring dan Analisis Data Sensor</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('teacher.iot.devices') }}" class="galaxy-button secondary">
                        <i class="ph-device-mobile"></i>
                        Device
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
        </header>

        <div class="p-6">
            <!-- Filter Section -->
            <div class="filter-card mb-6">
                <h3 class="text-lg font-bold text-white mb-4">Filter Data</h3>
                <form method="GET" action="{{ route('teacher.iot.sensor-data') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="form-group">
                        <label class="form-label">Device</label>
                        <select name="device_id" class="form-input">
                            <option value="">Semua Device</option>
                            @foreach($devices as $device)
                                <option value="{{ $device->id }}" {{ request('device_id') == $device->id ? 'selected' : '' }}>
                                    {{ $device->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kelas</label>
                        <select name="kelas_id" class="form-input">
                            <option value="">Semua Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="date_from" class="form-input" value="{{ request('date_from') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="date_to" class="form-input" value="{{ request('date_to') }}">
                    </div>

                    <div class="md:col-span-4 flex justify-end space-x-3">
                        <a href="{{ route('teacher.iot.sensor-data') }}" class="galaxy-button secondary">
                            <i class="ph-arrow-clockwise"></i>
                            Reset
                        </a>
                        <button type="submit" class="galaxy-button">
                            <i class="ph-magnifying-glass"></i>
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Data Table -->
            <div class="iot-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-white">Data Sensor</h3>
                    <div class="flex items-center space-x-3">
                        <span class="text-sm text-gray-400">
                            Total: {{ $sensorData->total() }} data
                        </span>
                        <button onclick="exportData()" class="galaxy-button secondary text-sm">
                            <i class="ph-download"></i>
                            Export
                        </button>
                    </div>
                </div>

                <div class="data-table">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th>Device</th>
                                <th>Kelas</th>
                                <th>Suhu</th>
                                <th>Kelembaban</th>
                                <th>Kelembaban Tanah</th>
                                <th>pH</th>
                                <th>Kualitas</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sensorData as $data)
                            <tr>
                                <td>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-8 h-8 bg-cyan-500/20 rounded-lg flex items-center justify-center">
                                            <i class="ph-thermometer text-cyan-400 text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-white font-medium">{{ $data->device->name ?? 'Unknown' }}</div>
                                            <div class="text-xs text-gray-400">{{ $data->device->device_id ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-white font-medium">{{ $data->kelas->name ?? 'Unknown' }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center space-x-1">
                                        <span class="text-cyan-400 font-semibold">{{ $data->temperature }}°C</span>
                                        @if($data->temperature > 30)
                                            <i class="ph-warning text-yellow-400 text-sm" title="Suhu tinggi"></i>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center space-x-1">
                                        <span class="text-blue-400 font-semibold">{{ $data->humidity }}%</span>
                                        @if($data->humidity < 30 || $data->humidity > 80)
                                            <i class="ph-warning text-yellow-400 text-sm" title="Kelembaban tidak optimal"></i>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center space-x-1">
                                        <span class="text-green-400 font-semibold">{{ $data->soil_moisture }}%</span>
                                        @if($data->soil_moisture < 20 || $data->soil_moisture > 70)
                                            <i class="ph-warning text-yellow-400 text-sm" title="Kelembaban tanah tidak optimal"></i>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center space-x-1">
                                        <span class="text-purple-400 font-semibold">{{ $data->ph_level }}</span>
                                        @if($data->ph_level < 6 || $data->ph_level > 8)
                                            <i class="ph-warning text-yellow-400 text-sm" title="pH tidak optimal"></i>
                                        @endif
                                    </div>
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
                                    <span class="{{ $qualityClass }}">{{ $qualityLabel }}</span>
                                </td>
                                    <td>
                                        <div class="text-sm">
                                            <div class="text-white">{{ $data->measured_at->format('d M Y') }}</div>
                                            <div class="text-gray-400">{{ $data->measured_at->format('H:i:s') }}</div>
                                        </div>
                                    </td>
                                <td>
                                    <div class="flex items-center space-x-2">
                                        <button onclick="viewDetails({{ $data->id }})" class="text-cyan-400 hover:text-cyan-300" title="Lihat Detail">
                                            <i class="ph-eye text-lg"></i>
                                        </button>
                                        <button onclick="exportSingle({{ $data->id }})" class="text-blue-400 hover:text-blue-300" title="Export">
                                            <i class="ph-download text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-12 text-gray-400">
                                    <i class="ph-thermometer text-6xl mb-4 block"></i>
                                    <h4 class="text-xl font-semibold mb-2">Tidak Ada Data Sensor</h4>
                                    <p>Data sensor akan muncul di sini setelah device IoT mengirim data</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($sensorData->hasPages())
                <div class="mt-6 flex justify-center">
                    {{ $sensorData->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-white">Detail Data Sensor</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-white">
                    <i class="ph-x text-xl"></i>
                </button>
            </div>
            <div id="detailContent">
                <!-- Detail content will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        function viewDetails(dataId) {
            // Simulate loading detail data
            document.getElementById('detailContent').innerHTML = `
                <div class="text-center py-8">
                    <i class="ph-spinner text-4xl text-cyan-400 animate-spin mb-4"></i>
                    <p class="text-gray-400">Memuat detail data...</p>
                </div>
            `;
            
            document.getElementById('detailModal').classList.add('show');
            
            // In real implementation, you would fetch data from API
            setTimeout(() => {
                document.getElementById('detailContent').innerHTML = `
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="iot-card p-4">
                                <h4 class="font-semibold text-white mb-2">Suhu</h4>
                                <p class="text-2xl text-cyan-400 font-bold">25.5°C</p>
                            </div>
                            <div class="iot-card p-4">
                                <h4 class="font-semibold text-white mb-2">Kelembaban</h4>
                                <p class="text-2xl text-blue-400 font-bold">65.2%</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-400">
                            <p>Data ID: ${dataId}</p>
                            <p>Waktu: ${new Date().toLocaleString()}</p>
                        </div>
                    </div>
                `;
            }, 1000);
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.remove('show');
        }

        function exportData() {
            // Implement export functionality
            alert('Fitur export akan segera tersedia');
        }

        function exportSingle(dataId) {
            // Implement single data export
            alert(`Export data ID ${dataId} akan segera tersedia`);
        }

        // Close modal when clicking outside
        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDetailModal();
            }
        });

        // Auto refresh every 30 seconds
        setInterval(function() {
            if (window.location.pathname === '{{ route("teacher.iot.sensor-data") }}') {
                location.reload();
            }
        }, 30000);
    </script>
</body>
</html>
