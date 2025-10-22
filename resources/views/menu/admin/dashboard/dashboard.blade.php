@extends('layouts.unified-layout')

@section('container')
    {{-- Stats Overview --}}
    <div class="space-stats-grid">
        <div class="space-stat-card space-fade-in">
            <div class="flex items-center justify-between mb-4">
                <div class="space-stat-icon">
                    <i class="ph-student text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="space-stat-value">{{ $data['totalSiswa'] }}</div>
                    <div class="space-stat-label">Total Siswa</div>
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ph-trend-up text-green-400 text-sm"></i>
                    <span class="text-green-400 text-sm font-medium">+12%</span>
                </div>
                <div class="text-xs text-gray-400">vs last month</div>
            </div>
        </div>

        <div class="space-stat-card space-fade-in">
            <div class="flex items-center justify-between mb-4">
                <div class="space-stat-icon">
                    <i class="ph-chalkboard-teacher text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="space-stat-value">{{ $data['totalPengajar'] }}</div>
                    <div class="space-stat-label">Total Pengajar</div>
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ph-trend-up text-green-400 text-sm"></i>
                    <span class="text-green-400 text-sm font-medium">+8%</span>
                </div>
                <div class="text-xs text-gray-400">vs last month</div>
            </div>
        </div>

        <div class="space-stat-card space-fade-in">
            <div class="flex items-center justify-between mb-4">
                <div class="space-stat-icon">
                    <i class="ph-book text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="space-stat-value">{{ $data['totalMateri'] }}</div>
                    <div class="space-stat-label">Total Materi</div>
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ph-trend-up text-green-400 text-sm"></i>
                    <span class="text-green-400 text-sm font-medium">+15%</span>
                </div>
                <div class="text-xs text-gray-400">vs last month</div>
            </div>
        </div>

        <div class="space-stat-card space-fade-in">
            <div class="flex items-center justify-between mb-4">
                <div class="space-stat-icon">
                    <i class="ph-clipboard-text text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="space-stat-value">{{ $data['totalTugas'] }}</div>
                    <div class="space-stat-label">Total Tugas</div>
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ph-trend-up text-green-400 text-sm"></i>
                    <span class="text-green-400 text-sm font-medium">+22%</span>
                </div>
                <div class="text-xs text-gray-400">vs last month</div>
            </div>
        </div>

    </div>

    {{-- Charts Section --}}
    <div class="mb-8 mt-8">
        <div id="dashboard-charts">
            <dashboard-charts 
                :initial-data="{{ json_encode($chartData) }}"
                user-role="{{ $roles }}"
                :classes="{{ json_encode($chartData['classes'] ?? []) }}"
            ></dashboard-charts>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Quick Actions Card --}}
        <div class="space-stat-card space-fade-in">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-white">Quick Actions</h3>
                <i class="ph-lightning text-2xl text-yellow-400"></i>
            </div>
            
            <div class="space-y-3">
                <a href="{{ route('viewTambahSiswa') }}" class="flex items-center p-3 text-left text-gray-300 hover:bg-white hover:bg-opacity-10 rounded-lg transition-all duration-300 hover:transform hover:scale-105">
                    <i class="ph-user-plus text-primary-400 text-lg mr-3"></i>
                    <span class="font-medium">Tambah Siswa</span>
                </a>
                
                <a href="{{ route('viewTambahPengajar') }}" class="flex items-center p-3 text-left text-gray-300 hover:bg-white hover:bg-opacity-10 rounded-lg transition-all duration-300 hover:transform hover:scale-105">
                    <i class="ph-chalkboard-teacher text-success-400 text-lg mr-3"></i>
                    <span class="font-medium">Tambah Pengajar</span>
                </a>
                
                <a href="{{ route('viewKelas') }}" class="flex items-center p-3 text-left text-gray-300 hover:bg-white hover:bg-opacity-10 rounded-lg transition-all duration-300 hover:transform hover:scale-105">
                    <i class="ph-school text-blue-400 text-lg mr-3"></i>
                    <span class="font-medium">Kelola Kelas</span>
                </a>
                
                <a href="{{ route('notifications.index') }}" class="flex items-center p-3 text-left text-gray-300 hover:bg-white hover:bg-opacity-10 rounded-lg transition-all duration-300 hover:transform hover:scale-105">
                    <i class="fas fa-bell text-yellow-400 text-lg mr-3"></i>
                    <span class="font-medium">Notifikasi</span>
                </a>
            </div>
        </div>

        {{-- Recent Activity Card --}}
        <div class="space-stat-card space-fade-in">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-white">Aktivitas Terbaru</h3>
                <i class="ph-clock text-2xl text-cyan-400"></i>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center p-3 bg-white bg-opacity-5 rounded-lg">
                    <div class="w-2 h-2 bg-green-400 rounded-full mr-3"></div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-300">Siswa baru terdaftar</p>
                        <p class="text-xs text-gray-500">2 menit yang lalu</p>
                    </div>
                </div>
                
                <div class="flex items-center p-3 bg-white bg-opacity-5 rounded-lg">
                    <div class="w-2 h-2 bg-blue-400 rounded-full mr-3"></div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-300">Materi baru ditambahkan</p>
                        <p class="text-xs text-gray-500">15 menit yang lalu</p>
                    </div>
                </div>
                
                <div class="flex items-center p-3 bg-white bg-opacity-5 rounded-lg">
                    <div class="w-2 h-2 bg-yellow-400 rounded-full mr-3"></div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-300">Tugas baru dibuat</p>
                        <p class="text-xs text-gray-500">1 jam yang lalu</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- System Status Card --}}
        <div class="space-stat-card space-fade-in">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-white">Status Sistem</h3>
                <i class="ph-shield-check text-2xl text-green-400"></i>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-white bg-opacity-5 rounded-lg">
                    <span class="text-sm text-gray-300">Server Status</span>
                    <span class="flex items-center text-green-400 text-sm">
                        <i class="ph-check-circle mr-1"></i>
                        Online
                    </span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-white bg-opacity-5 rounded-lg">
                    <span class="text-sm text-gray-300">Database</span>
                    <span class="flex items-center text-green-400 text-sm">
                        <i class="ph-check-circle mr-1"></i>
                        Connected
                    </span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-white bg-opacity-5 rounded-lg">
                    <span class="text-sm text-gray-300">IoT Devices</span>
                    <span class="flex items-center text-yellow-400 text-sm">
                        <i class="ph-warning-circle mr-1"></i>
                        2 Offline
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile Dashboard - 2 Grid Layout (Kiri & Kanan) --}}
    <div class="mobile-dashboard-container">
        {{-- Grid Kiri --}}
        <div class="mobile-dashboard-grid-left">
            <h3 class="mobile-grid-title">Manajemen Sistem</h3>
            
            <div class="mobile-dashboard-card space-fade-in">
                <div class="mobile-card-icon">
                    <i class="ph-users text-2xl"></i>
                </div>
                <div class="mobile-card-content">
                    <h3 class="mobile-card-title">Manajemen Pengguna</h3>
                    <p class="mobile-card-desc">Kelola semua pengguna sistem (Admin, Guru, Siswa)</p>
                </div>
                <div class="mobile-card-action">
                <a href="{{ route('superadmin.user-management') }}" class="mobile-card-link">
                    <i class="ph-arrow-right text-lg"></i>
                </a>
                </div>
            </div>

            <div class="mobile-dashboard-card space-fade-in">
                <div class="mobile-card-icon">
                    <i class="ph-school text-2xl"></i>
                </div>
                <div class="mobile-card-content">
                    <h3 class="mobile-card-title">Manajemen Kelas</h3>
                    <p class="mobile-card-desc">Buat dan kelola semua kelas di sistem</p>
                </div>
                <div class="mobile-card-action">
                <a href="{{ route('superadmin.class-management') }}" class="mobile-card-link">
                    <i class="ph-arrow-right text-lg"></i>
                </a>
                </div>
            </div>

            <div class="mobile-dashboard-card space-fade-in">
                <div class="mobile-card-icon">
                    <i class="ph-book-open text-2xl"></i>
                </div>
                <div class="mobile-card-content">
                    <h3 class="mobile-card-title">Mata Pelajaran</h3>
                    <p class="mobile-card-desc">Tambah dan kelola mata pelajaran</p>
                </div>
                <div class="mobile-card-action">
                <a href="{{ route('superadmin.subject-management') }}" class="mobile-card-link">
                    <i class="ph-arrow-right text-lg"></i>
                </a>
                </div>
            </div>

            <div class="mobile-dashboard-card space-fade-in">
                <div class="mobile-card-icon">
                    <i class="ph-book text-2xl"></i>
                </div>
                <div class="mobile-card-content">
                    <h3 class="mobile-card-title">Materi</h3>
                    <p class="mobile-card-desc">Kelola materi pembelajaran dan konten</p>
                </div>
                <div class="mobile-card-action">
                <a href="{{ route('superadmin.material-management') }}" class="mobile-card-link">
                    <i class="ph-arrow-right text-lg"></i>
                </a>
                </div>
            </div>

            <div class="mobile-dashboard-card space-fade-in">
                <div class="mobile-card-icon">
                    <i class="fas fa-bell text-2xl"></i>
                </div>
                <div class="mobile-card-content">
                    <h3 class="mobile-card-title">Notifikasi</h3>
                    <p class="mobile-card-desc">Kirim notifikasi ke semua pengguna, kelas, atau pengguna spesifik</p>
                </div>
                <div class="mobile-card-action">
                    <a href="{{ route('notifications.index') }}" class="mobile-card-link">
                        <i class="ph-arrow-right text-lg"></i>
                    </a>
                </div>
            </div>

        </div>

        {{-- Grid Kanan --}}
        <div class="mobile-dashboard-grid-right">
            <h3 class="mobile-grid-title">Aktivitas Pembelajaran</h3>
            
            <div class="mobile-dashboard-card space-fade-in">
                <div class="mobile-card-icon">
                    <i class="ph-clipboard-text text-2xl"></i>
                </div>
                <div class="mobile-card-content">
                    <h3 class="mobile-card-title">Manajemen Tugas</h3>
                    <p class="mobile-card-desc">Kelola tugas per kelas dengan kategorisasi dan tingkat kesulitan</p>
                </div>
                <div class="mobile-card-action">
                <a href="{{ route('superadmin.task-management') }}" class="mobile-card-link">
                    <i class="ph-arrow-right text-lg"></i>
                </a>
                </div>
            </div>

            <div class="mobile-dashboard-card space-fade-in">
                <div class="mobile-card-icon">
                    <i class="ph-exam text-2xl"></i>
                </div>
                <div class="mobile-card-content">
                    <h3 class="mobile-card-title">Manajemen Ujian</h3>
                    <p class="mobile-card-desc">Buat, edit, dan kelola ujian dengan fitur lengkap</p>
                </div>
                <div class="mobile-card-action">
                <a href="{{ route('superadmin.exam-management') }}" class="mobile-card-link">
                    <i class="ph-arrow-right text-lg"></i>
                </a>
                </div>
            </div>

            <div class="mobile-dashboard-card space-fade-in">
                <div class="mobile-card-icon">
                    <i class="ph-device-mobile text-2xl"></i>
                </div>
                <div class="mobile-card-content">
                    <h3 class="mobile-card-title">Manajemen IoT</h3>
                    <p class="mobile-card-desc">Daftarkan perangkat IoT, test konektivitas, dan monitor data sensor</p>
                </div>
                <div class="mobile-card-action">
                    <a href="{{ route('superadmin.iot-management') }}" class="mobile-card-link">
                        <i class="ph-arrow-right text-lg"></i>
                    </a>
                </div>
            </div>

            <div class="mobile-dashboard-card space-fade-in">
                <div class="mobile-card-icon">
                    <i class="ph-clipboard-text text-2xl"></i>
                </div>
                <div class="mobile-card-content">
                    <h3 class="mobile-card-title">Tugas IoT</h3>
                    <p class="mobile-card-desc">Buat dan kelola tugas penelitian IoT</p>
                </div>
                <div class="mobile-card-action">
                    <a href="{{ route('superadmin.task-management') }}" class="mobile-card-link">
                        <i class="ph-arrow-right text-lg"></i>
                    </a>
                </div>
            </div>

            <div class="mobile-dashboard-card space-fade-in">
                <div class="mobile-card-icon">
                    <i class="ph-chart-line text-2xl"></i>
                </div>
                <div class="mobile-card-content">
                    <h3 class="mobile-card-title">Penelitian IoT</h3>
                    <p class="mobile-card-desc">Lihat hasil penelitian IoT siswa</p>
                </div>
                <div class="mobile-card-action">
                    <a href="{{ route('superadmin.iot-management') }}" class="mobile-card-link">
                        <i class="ph-arrow-right text-lg"></i>
                    </a>
                </div>
            </div>

            <div class="mobile-dashboard-card space-fade-in">
                <div class="mobile-card-icon">
                    <i class="ph-chart-bar text-2xl"></i>
                </div>
                <div class="mobile-card-content">
                    <h3 class="mobile-card-title">Laporan & Analisis</h3>
                    <p class="mobile-card-desc">Lihat laporan dan analisis data pembelajaran</p>
                </div>
                <div class="mobile-card-action">
                    <a href="{{ route('superadmin.reports') }}" class="mobile-card-link">
                        <i class="ph-arrow-right text-lg"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Additional Stats --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="space-stat-card space-fade-in">
            <div class="flex items-center justify-between mb-4">
                <div class="space-stat-icon">
                    <i class="ph-user-check text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="space-stat-value">{{ $data['totalUserSiswa'] }}</div>
                    <div class="space-stat-label">Siswa Terdaftar</div>
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ph-trend-up text-green-400 text-sm"></i>
                    <span class="text-green-400 text-sm font-medium">+5%</span>
                </div>
                <div class="text-xs text-gray-400">vs last month</div>
            </div>
        </div>

        <div class="space-stat-card space-fade-in">
            <div class="flex items-center justify-between mb-4">
                <div class="space-stat-icon">
                    <i class="ph-school text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="space-stat-value">{{ $data['totalKelas'] }}</div>
                    <div class="space-stat-label">Total Kelas</div>
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ph-trend-up text-green-400 text-sm"></i>
                    <span class="text-green-400 text-sm font-medium">+3%</span>
                </div>
                <div class="text-xs text-gray-400">vs last month</div>
            </div>
        </div>

        <div class="space-stat-card space-fade-in">
            <div class="flex items-center justify-between mb-4">
                <div class="space-stat-icon">
                    <i class="ph-book-open text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="space-stat-value">{{ $data['totalMapel'] }}</div>
                    <div class="space-stat-label">Total Mapel</div>
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ph-trend-up text-green-400 text-sm"></i>
                    <span class="text-green-400 text-sm font-medium">+7%</span>
                </div>
                <div class="text-xs text-gray-400">vs last month</div>
            </div>
        </div>

        <div class="space-stat-card space-fade-in">
            <div class="flex items-center justify-between mb-4">
                <div class="space-stat-icon">
                    <i class="ph-exam text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="space-stat-value">{{ $data['totalUjian'] }}</div>
                    <div class="space-stat-label">Total Ujian</div>
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="ph-trend-up text-green-400 text-sm"></i>
                    <span class="text-green-400 text-sm font-medium">+18%</span>
                </div>
                <div class="text-xs text-gray-400">vs last month</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    
    <script>
        // Chart data
        var data = <?php echo json_encode($data); ?>;
        var materiData = <?php echo json_encode(App\Models\Materi::where('created_at', '>=', now()->subWeek())->get()); ?>;
        var tugasData = <?php echo json_encode(App\Models\Tugas::where('created_at', '>=', now()->subWeek())->get()); ?>;
        var ujianData = <?php echo json_encode(App\Models\Ujian::where('created_at', '>=', now()->subWeek())->get()); ?>;

        // Generate labels for last 7 days
        var labelDates = [];
        for (var i = 6; i >= 0; i--) {
            var date = moment().subtract(i, 'days').format('MMM DD');
            labelDates.push(date);
        }

        // Count data for each day
        function countDataForDay(data, dayOffset) {
            var date = moment().subtract(dayOffset, 'days').format('YYYY-MM-DD');
            return data.filter(function(item) {
                return moment(item.created_at).format('YYYY-MM-DD') === date;
            }).length;
        }

        var materiCount = [];
        var tugasCount = [];
        var ujianCount = [];

        for (var i = 6; i >= 0; i--) {
            materiCount.push(countDataForDay(materiData, i));
            tugasCount.push(countDataForDay(tugasData, i));
            ujianCount.push(countDataForDay(ujianData, i));
        }

        // Create chart
        var ctx = document.getElementById('mainChart').getContext('2d');
        var mainChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labelDates,
                datasets: [{
                    label: 'Materi',
                    data: materiCount,
                    borderColor: 'rgba(0, 123, 255, 1)',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Tugas',
                    data: tugasCount,
                    borderColor: 'rgba(40, 167, 69, 1)',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Ujian',
                    data: ujianCount,
                    borderColor: 'rgba(255, 193, 7, 1)',
                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: 'rgba(255, 255, 255, 0.8)',
                            font: {
                                size: 14
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.8)'
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        },
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.8)',
                            beginAtZero: true
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    </script>

    {{-- Vue.js Script for Charts --}}
    <script>
        const { createApp } = Vue;
        
        createApp({
            components: {
                'dashboard-charts': DashboardCharts
            }
        }).mount('#dashboard-charts');
    </script>
@endsection