@extends('layouts.unified-layout')

@section('title', $title)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $title }}</h1>
                <p class="mt-2 text-gray-300">Kelola pengaduan dan keluhan Anda</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('student.complaints.create') }}" class="btn btn-primary">
                    <i class="ph-plus mr-2"></i>
                    Buat Pengaduan Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500/20 text-blue-400">
                        <i class="ph-file-text text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-400">Total Pengaduan</p>
                        <p class="text-2xl font-bold text-white">{{ $complaints->total() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-500/20 text-yellow-400">
                        <i class="ph-clock text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-400">Menunggu</p>
                        <p class="text-2xl font-bold text-white">{{ $complaints->where('status', 'pending')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500/20 text-blue-400">
                        <i class="ph-gear text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-400">Diproses</p>
                        <p class="text-2xl font-bold text-white">{{ $complaints->where('status', 'in_progress')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-500/20 text-green-400">
                        <i class="ph-check-circle text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-400">Selesai</p>
                        <p class="text-2xl font-bold text-white">{{ $complaints->where('status', 'resolved')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Complaints List -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Pengaduan</h3>
        </div>
        <div class="card-body">
            @if($complaints->count() > 0)
                <div class="space-y-4">
                    @foreach($complaints as $complaint)
                        <div class="complaint-item">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h4 class="text-lg font-medium text-white">{{ $complaint->subject }}</h4>
                                        <span class="badge badge-{{ $complaint->status_color }}">{{ $complaint->status_text }}</span>
                                        <span class="badge badge-{{ $complaint->priority_color }}">{{ $complaint->priority_text }}</span>
                                    </div>
                                    
                                    <p class="text-gray-300 mb-2">{{ Str::limit($complaint->message, 150) }}</p>
                                    
                                    <div class="flex items-center space-x-4 text-sm text-gray-400">
                                        <span><i class="ph-tag mr-1"></i>{{ $complaint->category_text }}</span>
                                        <span><i class="ph-calendar mr-1"></i>{{ $complaint->created_at->format('d M Y, H:i') }}</span>
                                        @if($complaint->replies->count() > 0)
                                            <span><i class="ph-chat-circle mr-1"></i>{{ $complaint->replies->count() }} balasan</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-2 ml-4">
                                    <a href="{{ route('student.complaints.show', $complaint->id) }}" 
                                       class="btn btn-outline btn-sm">
                                        <i class="ph-eye mr-1"></i>
                                        Lihat
                                    </a>
                                    
                                    @if($complaint->status === 'pending')
                                        <a href="{{ route('student.complaints.edit', $complaint->id) }}" 
                                           class="btn btn-outline btn-sm">
                                            <i class="ph-pencil mr-1"></i>
                                            Edit
                                        </a>
                                        
                                        <form action="{{ route('student.complaints.destroy', $complaint->id) }}" 
                                              method="POST" class="inline" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengaduan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline btn-sm text-red-400 hover:text-red-300">
                                                <i class="ph-trash mr-1"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $complaints->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <i class="ph-file-text text-6xl"></i>
                    </div>
                    <h3 class="text-xl font-medium text-white mb-2">Belum Ada Pengaduan</h3>
                    <p class="text-gray-400 mb-6">Anda belum membuat pengaduan apapun</p>
                    <a href="{{ route('student.complaints.create') }}" class="btn btn-primary">
                        <i class="ph-plus mr-2"></i>
                        Buat Pengaduan Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.complaint-item {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.complaint-item:hover {
    background: rgba(255, 255, 255, 0.05);
    border-color: rgba(255, 255, 255, 0.2);
}

.badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.badge-warning {
    background: rgba(245, 158, 11, 0.2);
    color: #f59e0b;
}

.badge-info {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
}

.badge-success {
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
}

.badge-secondary {
    background: rgba(107, 114, 128, 0.2);
    color: #6b7280;
}

.badge-danger {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}
</style>
@endsection
