@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Analitik Admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">Analitik Admin</h1>
            <p class="text-gray-300">Analisis data statistik sistem dengan scope admin</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-300 text-sm">Total Pengguna</p>
                        <p class="text-3xl font-bold text-white">{{ $totalUsers ?? 0 }}</p>
                        <p class="text-gray-400 text-xs">Guru & Siswa</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-300 text-sm">Total Kelas</p>
                        <p class="text-3xl font-bold text-white">{{ $totalClasses ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chalkboard-teacher text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-300 text-sm">Mata Pelajaran</p>
                        <p class="text-3xl font-bold text-white">{{ $totalSubjects ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-book text-purple-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-300 text-sm">Total Tugas</p>
                        <p class="text-3xl font-bold text-white">{{ $totalTasks ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-tasks text-yellow-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-300 text-sm">Total Ujian</p>
                        <p class="text-3xl font-bold text-white">{{ $totalExams ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-500/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clipboard-check text-red-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Statistics by Role -->
        @if(isset($userStats) && count($userStats) > 0)
        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20 mb-8">
            <h2 class="text-xl font-semibold text-white mb-4">Statistik Pengguna Berdasarkan Role</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($userStats as $stat)
                <div class="bg-white/5 rounded-lg p-4 border border-white/10">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-300 text-sm">{{ ucfirst($stat->role) }}</p>
                            <p class="text-2xl font-bold text-white">{{ $stat->count }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center
                            @if($stat->role == 'admin') bg-blue-500/20
                            @elseif($stat->role == 'teacher') bg-green-500/20
                            @elseif($stat->role == 'student') bg-purple-500/20
                            @else bg-gray-500/20 @endif">
                            <i class="fas 
                                @if($stat->role == 'admin') fa-user-shield text-blue-400
                                @elseif($stat->role == 'teacher') fa-chalkboard-teacher text-green-400
                                @elseif($stat->role == 'student') fa-user-graduate text-purple-400
                                @else fa-user text-gray-400 @endif text-lg"></i>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Recent Users -->
            @if(isset($recentUsers) && count($recentUsers) > 0)
            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <h2 class="text-xl font-semibold text-white mb-4">Pengguna Terbaru</h2>
                <div class="space-y-4">
                    @foreach($recentUsers as $user)
                    <div class="flex items-center space-x-3 p-3 bg-white/5 rounded-lg border border-white/10">
                        <div class="w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-400"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-white font-medium">{{ $user->name }}</p>
                            <p class="text-gray-400 text-sm">{{ $user->email }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-400 text-xs">
                                {{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Recent Classes -->
            @if(isset($recentClasses) && count($recentClasses) > 0)
            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <h2 class="text-xl font-semibold text-white mb-4">Kelas Terbaru</h2>
                <div class="space-y-4">
                    @foreach($recentClasses as $class)
                    <div class="flex items-center space-x-3 p-3 bg-white/5 rounded-lg border border-white/10">
                        <div class="w-10 h-10 bg-green-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-chalkboard-teacher text-green-400"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-white font-medium">{{ $class->name }}</p>
                            <p class="text-gray-400 text-sm">{{ $class->description ?? 'Tidak ada deskripsi' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-400 text-xs">
                                {{ $class->created_at ? $class->created_at->format('d M Y') : 'N/A' }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- System Overview -->
        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20 mb-8">
            <h2 class="text-xl font-semibold text-white mb-4">Ringkasan Sistem</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-database text-blue-400 text-2xl"></i>
                    </div>
                    <h3 class="text-white font-medium mb-1">Database</h3>
                    <p class="text-gray-400 text-sm">Sistem berjalan dengan baik</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-server text-green-400 text-2xl"></i>
                    </div>
                    <h3 class="text-white font-medium mb-1">Server</h3>
                    <p class="text-gray-400 text-sm">Status online</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-shield-alt text-purple-400 text-2xl"></i>
                    </div>
                    <h3 class="text-white font-medium mb-1">Keamanan</h3>
                    <p class="text-gray-400 text-sm">Sistem aman</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-sync-alt text-yellow-400 text-2xl"></i>
                    </div>
                    <h3 class="text-white font-medium mb-1">Sinkronisasi</h3>
                    <p class="text-gray-400 text-sm">Data terbaru</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
            <h2 class="text-xl font-semibold text-white mb-4">Aksi Cepat</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.reports') }}" class="bg-red-500/20 hover:bg-red-500/30 border border-red-400/30 rounded-lg p-4 text-center transition-all duration-300">
                    <i class="fas fa-chart-bar text-red-400 text-2xl mb-2"></i>
                    <p class="text-white text-sm">Laporan</p>
                </a>
                <a href="{{ route('admin.users.index') }}" class="bg-blue-500/20 hover:bg-blue-500/30 border border-blue-400/30 rounded-lg p-4 text-center transition-all duration-300">
                    <i class="fas fa-users text-blue-400 text-2xl mb-2"></i>
                    <p class="text-white text-sm">Manajemen Pengguna</p>
                </a>
                <a href="{{ route('admin.task-management') }}" class="bg-green-500/20 hover:bg-green-500/30 border border-green-400/30 rounded-lg p-4 text-center transition-all duration-300">
                    <i class="fas fa-tasks text-green-400 text-2xl mb-2"></i>
                    <p class="text-white text-sm">Manajemen Tugas</p>
                </a>
                <a href="{{ route('admin.help') }}" class="bg-yellow-500/20 hover:bg-yellow-500/30 border border-yellow-400/30 rounded-lg p-4 text-center transition-all duration-300">
                    <i class="fas fa-question-circle text-yellow-400 text-2xl mb-2"></i>
                    <p class="text-white text-sm">Bantuan</p>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection