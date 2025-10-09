@extends('layouts.unified-layout')

@section('container')
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="text-white display-4 fw-bold mb-2">
                <i class="fas fa-chart-line me-3 text-primary"></i>Hasil IoT Saya
            </h1>
            <p class="text-white-75 fs-5 mb-0">Lihat dan kelola data IoT pribadi Anda</p>
        </div>
        <div class="text-end">
            <div class="glass-card p-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                    <span class="text-white-75" id="currentDateTime"></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3">
            <div class="glass-card p-4 text-center">
                <i class="fas fa-database fa-3x text-primary mb-3"></i>
                <h2 class="text-white mb-1">{{ $myReadings->total() }}</h2>
                <small class="text-white-75">Total Data Saya</small>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="glass-card p-4 text-center">
                <i class="fas fa-calendar-day fa-3x text-success mb-3"></i>
                <h2 class="text-white mb-1">{{ $myReadings->where('timestamp', '>=', today())->count() }}</h2>
                <small class="text-white-75">Hari Ini</small>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="glass-card p-4 text-center">
                <i class="fas fa-thermometer-half fa-3x text-warning mb-3"></i>
                <h2 class="text-white mb-1">{{ $myReadings->avg('soil_temperature') ? number_format($myReadings->avg('soil_temperature'), 1) . '°C' : 'N/A' }}</h2>
                <small class="text-white-75">Suhu Rata-rata</small>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="glass-card p-4 text-center">
                <i class="fas fa-tint fa-3x text-info mb-3"></i>
                <h2 class="text-white mb-1">{{ $myReadings->avg('soil_moisture') ? number_format($myReadings->avg('soil_moisture'), 1) . '%' : 'N/A' }}</h2>
                <small class="text-white-75">Kelembaban Rata-rata</small>
            </div>
        </div>
    </div>

    {{-- IoT Device Control Panel --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-card p-4">
                <h3 class="text-white mb-4">
                    <i class="fas fa-bluetooth me-2 text-primary"></i>Ambil Data IoT
                </h3>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="text-white-75 fw-semibold mb-2">Lokasi Pengukuran</label>
                            <input type="text" class="form-control" id="location" placeholder="Masukkan lokasi pengukuran">
                        </div>
                        
                        <div class="mb-4">
                            <label class="text-white-75 fw-semibold mb-2">Catatan</label>
                            <textarea class="form-control" id="notes" rows="3" placeholder="Masukkan catatan tambahan"></textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="text-white-75 fw-semibold mb-2">Kelas</label>
                            <select class="form-control" id="selectKelas">
                                <option value="">-- Pilih Kelas --</option>
                                @if(Auth::user()->kelas_id)
                                    <option value="{{ Auth::user()->kelas_id }}" selected>{{ Auth::user()->kelas->name ?? 'Kelas Saya' }}</option>
                                @endif
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="text-white-75 fw-semibold mb-2">Mode Pengukuran</label>
                            <select class="form-control" id="measurementMode">
                                <option value="auto">Otomatis (dari alat IoT)</option>
                                <option value="manual">Manual (input sendiri)</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
                    <button class="btn btn-primary btn-lg me-3" id="scanDeviceBtn" onclick="scanDevice()">
                        <i class="fas fa-bluetooth me-2"></i>Scan & Ambil Data
                    </button>
                    <button class="btn btn-success btn-lg" id="saveDataBtn" onclick="saveData()" disabled>
                        <i class="fas fa-save me-2"></i>Simpan Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Real-time Data Display --}}
    <div class="row mb-4" id="realTimeDataSection" style="display: none;">
        <div class="col-12">
            <div class="glass-card p-4">
                <h3 class="text-white mb-4">
                    <i class="fas fa-chart-line me-2 text-primary"></i>Data Real-time
                </h3>
                
                <div class="row text-center">
                    <div class="col-md-4 mb-3">
                        <div class="bg-primary bg-opacity-20 rounded p-3">
                            <i class="fas fa-thermometer-half fa-2x text-primary mb-2"></i>
                            <h4 class="text-white mb-1" id="realTimeTemp">--°C</h4>
                            <small class="text-white-75">Suhu Tanah</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="bg-success bg-opacity-20 rounded p-3">
                            <i class="fas fa-seedling fa-2x text-success mb-2"></i>
                            <h4 class="text-white mb-1" id="realTimeHumus">--%</h4>
                            <small class="text-white-75">Kadar Humus</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="bg-info bg-opacity-20 rounded p-3">
                            <i class="fas fa-tint fa-2x text-info mb-2"></i>
                            <h4 class="text-white mb-1" id="realTimeMoisture">--%</h4>
                            <small class="text-white-75">Kelembaban Tanah</small>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <div class="badge bg-success fs-6" id="connectionStatus">
                        <i class="fas fa-circle me-1"></i>Terhubung
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Manual Input Form --}}
    <div class="row mb-4" id="manualInputSection" style="display: none;">
        <div class="col-12">
            <div class="glass-card p-4">
                <h3 class="text-white mb-4">
                    <i class="fas fa-edit me-2 text-primary"></i>Input Data Manual
                </h3>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="text-white-75 fw-semibold mb-2">Suhu Tanah (°C)</label>
                        <input type="number" class="form-control" id="manualTemp" step="0.1" placeholder="Masukkan suhu">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-white-75 fw-semibold mb-2">Kadar Humus (%)</label>
                        <input type="number" class="form-control" id="manualHumus" step="0.1" placeholder="Masukkan kadar humus">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-white-75 fw-semibold mb-2">Kelembaban Tanah (%)</label>
                        <input type="number" class="form-control" id="manualMoisture" step="0.1" placeholder="Masukkan kelembaban">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- My Data Table --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="glass-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-white mb-0">
                        <i class="fas fa-table me-2 text-primary"></i>Data Saya
                    </h3>
                    <div>
                        <button class="btn btn-outline-primary btn-sm me-2" onclick="refreshData()">
                            <i class="fas fa-sync-alt me-1"></i>Refresh
                        </button>
                        <button class="btn btn-outline-success btn-sm" onclick="exportMyData()">
                            <i class="fas fa-download me-1"></i>Export CSV
                        </button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-dark table-striped">
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
                                        <span class="badge bg-{{ $reading->soil_quality_color }}">
                                            {{ $reading->soil_quality_status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-white-75">Belum ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-3">
                    {{ $myReadings->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Class Data (if available) --}}
    @if($classReadings->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="glass-card p-4">
                <h3 class="text-white mb-4">
                    <i class="fas fa-users me-2 text-primary"></i>Data Kelas
                </h3>
                
                <div class="table-responsive">
                    <table class="table table-dark table-striped">
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
                                        <span class="badge bg-{{ $reading->soil_quality_color }}">
                                            {{ $reading->soil_quality_status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            document.getElementById('currentDateTime').textContent = now.toLocaleDateString('id-ID', options);
        }
        
        updateDateTime();
        setInterval(updateDateTime, 60000);

        // Handle measurement mode change
        document.getElementById('measurementMode').addEventListener('change', function() {
            const mode = this.value;
            const scanBtn = document.getElementById('scanDeviceBtn');
            const manualSection = document.getElementById('manualInputSection');
            
            if (mode === 'manual') {
                scanBtn.innerHTML = '<i class="fas fa-edit me-2"></i>Input Data Manual';
                manualSection.style.display = 'block';
            } else {
                scanBtn.innerHTML = '<i class="fas fa-bluetooth me-2"></i>Scan & Ambil Data';
                manualSection.style.display = 'none';
            }
        });

        // Scan and connect to IoT device
        async function scanDevice() {
            const mode = document.getElementById('measurementMode').value;
            
            if (mode === 'manual') {
                showManualInput();
                return;
            }
            
            const scanBtn = document.getElementById('scanDeviceBtn');
            const saveBtn = document.getElementById('saveDataBtn');
            
            scanBtn.disabled = true;
            scanBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Scanning...';
            
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
                
                scanBtn.innerHTML = '<i class="fas fa-check me-2"></i>Terhubung';
                saveBtn.disabled = false;
                
            } catch (error) {
                console.error('Error connecting to device:', error);
                alert('Gagal terhubung ke perangkat: ' + error.message + '\n\nGunakan mode manual untuk input data.');
                
                scanBtn.disabled = false;
                scanBtn.innerHTML = '<i class="fas fa-bluetooth me-2"></i>Scan & Ambil Data';
            }
        }

        // Show manual input form
        function showManualInput() {
            document.getElementById('manualInputSection').style.display = 'block';
            document.getElementById('saveDataBtn').disabled = false;
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
                        
                        document.getElementById('connectionStatus').innerHTML = '<i class="fas fa-circle me-1"></i>Terhubung';
                        document.getElementById('connectionStatus').className = 'badge bg-success fs-6';
                    }
                }, 2000);
                
            } catch (error) {
                console.error('Error reading data:', error);
            }
        }

        // Save data to database
        async function saveData() {
            const mode = document.getElementById('measurementMode').value;
            const classId = document.getElementById('selectKelas').value;
            const location = document.getElementById('location').value;
            const notes = document.getElementById('notes').value;
            
            if (!classId) {
                alert('Pilih kelas terlebih dahulu');
                return;
            }
            
            let dataToSave = {};
            
            if (mode === 'manual') {
                const temp = document.getElementById('manualTemp').value;
                const humus = document.getElementById('manualHumus').value;
                const moisture = document.getElementById('manualMoisture').value;
                
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
                        location: location,
                        notes: notes,
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
            location.reload();
        }

        // Export my data to CSV
        function exportMyData() {
            window.open('/api/iot/readings/export?student_id={{ Auth::user()->nis }}', '_blank');
        }
    </script>
@endsection
