@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Laporan Admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">Laporan Admin</h1>
            <p class="text-gray-300">Laporan dan statistik sistem dengan scope admin</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-300 text-sm">Total Laporan</p>
                        <p class="text-3xl font-bold text-white">{{ $totalReports ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-alt text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-300 text-sm">Laporan Selesai</p>
                        <p class="text-3xl font-bold text-green-400">{{ $completedReports ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-300 text-sm">Laporan Pending</p>
                        <p class="text-3xl font-bold text-yellow-400">{{ $pendingReports ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-300 text-sm">Laporan Gagal</p>
                        <p class="text-3xl font-bold text-red-400">{{ $failedReports ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-500/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exclamation-circle text-red-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20 mb-8">
            <h2 class="text-xl font-semibold text-white mb-4">Filter Laporan</h2>
            <form method="GET" action="{{ route('admin.reports.filter') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-gray-300 text-sm mb-2">Kelas</label>
                    <select name="filter_class" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Kelas</option>
                        @if(isset($classes))
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('filter_class') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div>
                    <label class="block text-gray-300 text-sm mb-2">Mata Pelajaran</label>
                    <select name="filter_subject" class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Mata Pelajaran</option>
                        @if(isset($subjects))
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ request('filter_subject') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div>
                    <label class="block text-gray-300 text-sm mb-2">Dari Tanggal</label>
                    <input type="date" name="filter_date_from" value="{{ request('filter_date_from') }}" 
                           class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-300 text-sm mb-2">Sampai Tanggal</label>
                    <input type="date" name="filter_date_to" value="{{ request('filter_date_to') }}" 
                           class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="md:col-span-4 flex space-x-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition-colors duration-300">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.reports') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors duration-300">
                        <i class="fas fa-refresh mr-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Class Data -->
        @if(isset($classData) && count($classData) > 0)
        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20 mb-8">
            <h2 class="text-xl font-semibold text-white mb-4">Data Kelas</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-white">
                    <thead>
                        <tr class="border-b border-white/20">
                            <th class="text-left py-3 px-4">Nama Kelas</th>
                            <th class="text-left py-3 px-4">Jumlah Siswa</th>
                            <th class="text-left py-3 px-4">Jumlah Tugas</th>
                            <th class="text-left py-3 px-4">Jumlah Ujian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($classData as $class)
                        <tr class="border-b border-white/10">
                            <td class="py-3 px-4">{{ $class['name'] }}</td>
                            <td class="py-3 px-4">{{ $class['student_count'] }}</td>
                            <td class="py-3 px-4">{{ $class['task_count'] }}</td>
                            <td class="py-3 px-4">{{ $class['exam_count'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Subject Data -->
        @if(isset($subjectData) && count($subjectData) > 0)
        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20 mb-8">
            <h2 class="text-xl font-semibold text-white mb-4">Data Mata Pelajaran</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-white">
                    <thead>
                        <tr class="border-b border-white/20">
                            <th class="text-left py-3 px-4">Nama Mata Pelajaran</th>
                            <th class="text-left py-3 px-4">Jumlah Kelas</th>
                            <th class="text-left py-3 px-4">Jumlah Tugas</th>
                            <th class="text-left py-3 px-4">Jumlah Ujian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjectData as $subject)
                        <tr class="border-b border-white/10">
                            <td class="py-3 px-4">{{ $subject['name'] }}</td>
                            <td class="py-3 px-4">{{ $subject['class_count'] }}</td>
                            <td class="py-3 px-4">{{ $subject['task_count'] }}</td>
                            <td class="py-3 px-4">{{ $subject['exam_count'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
            <h2 class="text-xl font-semibold text-white mb-4">Aksi Cepat</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.analytics') }}" class="bg-purple-500/20 hover:bg-purple-500/30 border border-purple-400/30 rounded-lg p-4 text-center transition-all duration-300">
                    <i class="fas fa-chart-line text-purple-400 text-2xl mb-2"></i>
                    <p class="text-white text-sm">Analitik</p>
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
