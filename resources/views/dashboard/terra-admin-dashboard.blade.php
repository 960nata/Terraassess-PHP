@extends('layouts.unified-layout-consistent')

@section('title', 'Terra Assessment - Admin Dashboard')

@section('page-title', 'Dashboard Admin')
@section('page-description', 'Selamat datang di dashboard admin Terra Assessment')

@section('content')
<!-- Welcome Banner -->
<div class="terra-card mb-8">
    <div class="terra-card-body">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center">
                <i class="fas fa-user-shield text-primary-600 text-2xl"></i>
            </div>
            <div>
                <h2 class="terra-heading-3 mb-2">Selamat Datang, {{ auth()->user()->name }}!</h2>
                <p class="terra-text-base text-secondary-600">
                    Anda dapat mengelola semua aktivitas admin dari dashboard ini. 
                    Mulai dari mengelola pengguna, kelas, mata pelajaran, hingga memantau data IoT.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="terra-grid terra-grid-cols-1 md:terra-grid-cols-2 lg:terra-grid-cols-4 mb-8">
    <!-- Total Users -->
    <div class="terra-card">
        <div class="terra-card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="terra-text-small text-secondary-600 mb-1">Total Pengguna</p>
                    <p class="text-3xl font-bold text-secondary-900">{{ $totalUsers ?? 0 }}</p>
                    <p class="terra-text-xs text-success-600 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>
                        +12% dari bulan lalu
                    </p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-primary-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Classes -->
    <div class="terra-card">
        <div class="terra-card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="terra-text-small text-secondary-600 mb-1">Total Kelas</p>
                    <p class="text-3xl font-bold text-secondary-900">{{ $totalClasses ?? 0 }}</p>
                    <p class="terra-text-xs text-success-600 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>
                        +5% dari bulan lalu
                    </p>
                </div>
                <div class="w-12 h-12 bg-success-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-school text-success-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Subjects -->
    <div class="terra-card">
        <div class="terra-card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="terra-text-small text-secondary-600 mb-1">Mata Pelajaran</p>
                    <p class="text-3xl font-bold text-secondary-900">{{ $totalSubjects ?? 0 }}</p>
                    <p class="terra-text-xs text-info-600 mt-1">
                        <i class="fas fa-minus mr-1"></i>
                        Tidak berubah
                    </p>
                </div>
                <div class="w-12 h-12 bg-info-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-book-open text-info-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Tasks -->
    <div class="terra-card">
        <div class="terra-card-body">
            <div class="flex items-center justify-between">
                <div>
                    <p class="terra-text-small text-secondary-600 mb-1">Total Tugas</p>
                    <p class="text-3xl font-bold text-secondary-900">{{ $totalTasks ?? 0 }}</p>
                    <p class="terra-text-xs text-warning-600 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>
                        +8% dari bulan lalu
                    </p>
                </div>
                <div class="w-12 h-12 bg-warning-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-tasks text-warning-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="terra-grid terra-grid-cols-1 lg:terra-grid-cols-3 gap-8">
    <!-- Management Cards -->
    <div class="lg:col-span-2">
        <h3 class="terra-heading-4 mb-6">Manajemen Sistem</h3>
        <div class="terra-grid terra-grid-cols-1 md:terra-grid-cols-2 gap-6">
            <!-- User Management -->
            <div class="terra-card">
                <div class="terra-card-body">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-users text-primary-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="terra-heading-6">Manajemen Pengguna</h4>
                            <p class="terra-text-small text-secondary-600">Kelola pengguna sistem</p>
                        </div>
                    </div>
                    <a href="{{ route('superadmin.user-management') }}" class="terra-btn terra-btn-primary terra-btn-sm w-full">
                        <span>Kelola Pengguna</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Class Management -->
            <div class="terra-card">
                <div class="terra-card-body">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-12 h-12 bg-success-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-school text-success-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="terra-heading-6">Manajemen Kelas</h4>
                            <p class="terra-text-small text-secondary-600">Kelola kelas dan siswa</p>
                        </div>
                    </div>
                    <a href="{{ route('superadmin.class-management') }}" class="terra-btn terra-btn-primary terra-btn-sm w-full">
                        <span>Kelola Kelas</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Subject Management -->
            <div class="terra-card">
                <div class="terra-card-body">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-12 h-12 bg-info-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-book-open text-info-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="terra-heading-6">Mata Pelajaran</h4>
                            <p class="terra-text-small text-secondary-600">Kelola mata pelajaran</p>
                        </div>
                    </div>
                    <a href="{{ route('superadmin.subject-management') }}" class="terra-btn terra-btn-primary terra-btn-sm w-full">
                        <span>Kelola Mata Pelajaran</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Task Management -->
            <div class="terra-card">
                <div class="terra-card-body">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-12 h-12 bg-warning-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-tasks text-warning-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="terra-heading-6">Manajemen Tugas</h4>
                            <p class="terra-text-small text-secondary-600">Kelola tugas dan ujian</p>
                        </div>
                    </div>
                    <a href="{{ route('task-management') }}" class="terra-btn terra-btn-primary terra-btn-sm w-full">
                        <span>Kelola Tugas</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- IoT Management -->
            <div class="terra-card">
                <div class="terra-card-body">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-wifi text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="terra-heading-6">Manajemen IoT</h4>
                            <p class="terra-text-small text-secondary-600">Kelola perangkat IoT</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.iot-management') }}" class="terra-btn terra-btn-primary terra-btn-sm w-full">
                        <span>Kelola IoT</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Reports -->
            <div class="terra-card">
                <div class="terra-card-body">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-chart-line text-red-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="terra-heading-6">Laporan</h4>
                            <p class="terra-text-small text-secondary-600">Lihat laporan sistem</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.reports') }}" class="terra-btn terra-btn-primary terra-btn-sm w-full">
                        <span>Lihat Laporan</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="terra-card">
            <div class="terra-card-header">
                <h3 class="terra-card-title">Aksi Cepat</h3>
            </div>
            <div class="terra-card-body">
                <div class="space-y-3">
                    <a href="{{ route('superadmin.user-management') }}" class="flex items-center p-3 rounded-lg hover:bg-secondary-50 transition-colors">
                        <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-user-plus text-primary-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-secondary-900">Tambah Pengguna</p>
                            <p class="text-xs text-secondary-600">Buat akun baru</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('superadmin.class-management') }}" class="flex items-center p-3 rounded-lg hover:bg-secondary-50 transition-colors">
                        <div class="w-8 h-8 bg-success-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-plus text-success-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-secondary-900">Tambah Kelas</p>
                            <p class="text-xs text-secondary-600">Buat kelas baru</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('task-management') }}" class="flex items-center p-3 rounded-lg hover:bg-secondary-50 transition-colors">
                        <div class="w-8 h-8 bg-warning-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-clipboard-list text-warning-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-secondary-900">Buat Tugas</p>
                            <p class="text-xs text-secondary-600">Tugas baru</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="terra-card">
            <div class="terra-card-header">
                <h3 class="terra-card-title">Aktivitas Terbaru</h3>
            </div>
            <div class="terra-card-body">
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-primary-600 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm text-secondary-900">Pengguna baru mendaftar</p>
                            <p class="text-xs text-secondary-600">2 menit yang lalu</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-success-600 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm text-secondary-900">Tugas baru dibuat</p>
                            <p class="text-xs text-secondary-600">15 menit yang lalu</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-warning-600 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm text-secondary-900">Kelas baru ditambahkan</p>
                            <p class="text-xs text-secondary-600">1 jam yang lalu</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-info-600 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm text-secondary-900">Laporan dihasilkan</p>
                            <p class="text-xs text-secondary-600">2 jam yang lalu</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-secondary-200">
                    <a href="#" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                        Lihat semua aktivitas
                        <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="terra-card">
            <div class="terra-card-header">
                <h3 class="terra-card-title">Status Sistem</h3>
            </div>
            <div class="terra-card-body">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-success-500 rounded-full"></div>
                            <span class="text-sm text-secondary-900">Server</span>
                        </div>
                        <span class="terra-badge terra-badge-success">Online</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-success-500 rounded-full"></div>
                            <span class="text-sm text-secondary-900">Database</span>
                        </div>
                        <span class="terra-badge terra-badge-success">Online</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-warning-500 rounded-full"></div>
                            <span class="text-sm text-secondary-900">IoT Devices</span>
                        </div>
                        <span class="terra-badge terra-badge-warning">5/8 Online</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-success-500 rounded-full"></div>
                            <span class="text-sm text-secondary-900">API</span>
                        </div>
                        <span class="terra-badge terra-badge-success">Online</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Add any dashboard-specific JavaScript here
    document.addEventListener('DOMContentLoaded', function() {
        // Animate stats on load
        const stats = document.querySelectorAll('.text-3xl.font-bold');
        stats.forEach(stat => {
            const finalValue = parseInt(stat.textContent);
            let currentValue = 0;
            const increment = finalValue / 50;
            
            const timer = setInterval(() => {
                currentValue += increment;
                if (currentValue >= finalValue) {
                    stat.textContent = finalValue;
                    clearInterval(timer);
                } else {
                    stat.textContent = Math.floor(currentValue);
                }
            }, 30);
        });
    });
</script>
@endsection

