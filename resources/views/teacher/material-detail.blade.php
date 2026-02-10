@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Detail Materi')

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden border border-gray-700 mb-6">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white flex items-center">
                            <i class="fas fa-file-alt mr-3"></i>
                            Detail Materi
                        </h1>
                        <p class="text-blue-100 mt-1">Informasi lengkap dan statistik materi</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('teacher.material-management') }}" class="btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('teacher.material-management.edit', $material->id) }}" class="btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Material Information -->
            <div class="lg:col-span-2">
                <div class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden border border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-700">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Informasi Materi
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Judul Materi</label>
                                <p class="text-white text-lg font-semibold">{{ $material->title }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Deskripsi</label>
                                <div class="text-gray-200 bg-gray-700 p-4 rounded-lg">
                                    {!! $material->description ?? 'Tidak ada deskripsi' !!}
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Tipe Materi</label>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-500 text-white">
                                        @switch($material->type ?? 'text')
                                            @case('pdf') <i class="fas fa-file-pdf mr-1"></i> PDF @break
                                            @case('video') <i class="fas fa-video mr-1"></i> Video @break
                                            @case('image') <i class="fas fa-image mr-1"></i> Gambar @break
                                            @case('document') <i class="fas fa-file-word mr-1"></i> Dokumen @break
                                            @case('text') <i class="fas fa-file-alt mr-1"></i> Teks @break
                                            @default <i class="fas fa-file mr-1"></i> {{ ucfirst($material->type ?? 'text') }}
                                        @endswitch
                                    </span>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Kelas</label>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-500 text-white">
                                        <i class="fas fa-users mr-1"></i>
                                        {{ $material->class_name ?? ($material->class ? ($material->class->nama_kelas ?? $material->class->name) : 'N/A') }}
                                    </span>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Mata Pelajaran</label>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-500 text-white">
                                        <i class="fas fa-book mr-1"></i>
                                        {{ $material->subject_name ?? ($material->subject ? $material->subject->name : 'N/A') }}
                                    </span>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $material->status === 'published' ? 'bg-green-500' : 'bg-yellow-500' }} text-white">
                                        <i class="fas fa-{{ $material->status === 'published' ? 'check-circle' : 'clock' }} mr-1"></i>
                                        {{ $material->status === 'published' ? 'Dipublikasi' : 'Draft' }}
                                    </span>
                                </div>
                            </div>

                            @if($material->file_path)
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">File</label>
                                <div class="flex items-center space-x-3">
                                    <span class="text-gray-200">{{ basename($material->file_path) }}</span>
                                    <span class="text-sm text-gray-400">({{ $material->formatted_file_size ?? $material->file_size ?? '0 B' }})</span>
                                    <a href="{{ Storage::url($material->file_path) }}" target="_blank" class="btn-primary text-sm">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                            </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Dibuat Oleh</label>
                                    <p class="text-gray-200">{{ $material->creator ? $material->creator->name : 'N/A' }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Tanggal Dibuat</label>
                                    <p class="text-gray-200">{{ $material->created_at ? $material->created_at->format('d M Y, H:i') : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="space-y-6">
                <!-- Stats Card -->
                <div class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden border border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-700">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Statistik
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-eye text-white"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-300">Total Views</p>
                                        <p class="text-2xl font-bold text-white">{{ $material->views ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-download text-white"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-300">Total Downloads</p>
                                        <p class="text-2xl font-bold text-white">{{ $material->downloads ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-users text-white"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-300">Pembaca</p>
                                        <p class="text-2xl font-bold text-white">{{ $readers->count() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Readers -->
                <div class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden border border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-700">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-users mr-2"></i>
                            Pembaca Terbaru
                        </h2>
                    </div>
                    <div class="p-6">
                        @if($readers->count() > 0)
                            <div class="space-y-3 max-h-64 overflow-y-auto">
                                @foreach($readers->take(10) as $reader)
                                <div class="flex items-center space-x-3 p-3 bg-gray-700 rounded-lg">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-white text-sm"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-white truncate">{{ $reader->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $reader->role_name }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($reader->read_at)->format('d M, H:i') }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @if($readers->count() > 10)
                            <div class="mt-3 text-center">
                                <p class="text-sm text-gray-400">dan {{ $readers->count() - 10 }} pembaca lainnya</p>
                            </div>
                            @endif
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-users text-gray-500 text-3xl mb-3"></i>
                                <p class="text-gray-400">Belum ada yang membaca materi ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- All Readers Table -->
        @if($readers->count() > 0)
        <div class="mt-6">
            <div class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden border border-gray-700">
                <div class="px-6 py-4 border-b border-gray-700">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-list mr-2"></i>
                        Semua Pembaca ({{ $readers->count() }})
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead class="bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Tanggal Baca</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @foreach($readers as $reader)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-white text-sm"></i>
                                        </div>
                                        <div class="text-sm font-medium text-white">{{ $reader->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $reader->role_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $reader->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    {{ \Carbon\Carbon::parse($reader->read_at)->format('d M Y, H:i') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
