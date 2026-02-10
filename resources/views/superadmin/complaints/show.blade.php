@extends('layouts.unified-layout')

@section('title', $title)

@section('content')
<div class="superadmin-container">
    <!-- Header -->
    <div class="page-header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="page-title">{{ $complaint->subject }}</h1>
                <div class="flex items-center space-x-4 mt-2">
                    <span class="badge badge-{{ $complaint->status_color }}">{{ $complaint->status_text }}</span>
                    <span class="badge badge-{{ $complaint->priority_color }}">{{ $complaint->priority_text }}</span>
                    <span class="text-gray-400">{{ $complaint->category_text }}</span>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('superadmin.complaints.index') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Complaint Details -->
            <div class="card mb-6">
                <div class="card-header">
                    <h3 class="card-title">Detail Pengaduan</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-400">Dari:</label>
                                <p class="text-white">{{ $complaint->user->name }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-400">Kelas:</label>
                                <p class="text-white">{{ $complaint->user->kelas->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium text-gray-400">Dibuat pada:</label>
                            <p class="text-white">{{ $complaint->created_at->format('d M Y, H:i') }} ({{ $complaint->getTimeAgo() }})</p>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium text-gray-400">Isi Pengaduan:</label>
                            <div class="mt-2 p-4 bg-gray-800/50 rounded-lg">
                                <p class="text-white whitespace-pre-wrap">{{ $complaint->message }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Replies Section -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Balasan</h3>
                </div>
                <div class="card-body">
                    @if($complaint->replies->count() > 0)
                        <div class="space-y-4">
                            @foreach($complaint->replies as $reply)
                                <div class="reply-item {{ $reply->is_internal_note ? 'internal-reply' : 'public-reply' }}">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 {{ $reply->is_internal_note ? 'bg-yellow-500' : 'bg-blue-500' }} rounded-full flex items-center justify-center">
                                                <i class="ph-user text-white text-sm"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <h4 class="text-sm font-medium text-white">{{ $reply->user->name }}</h4>
                                                <span class="text-xs text-gray-400">{{ $reply->getTimeAgo() }}</span>
                                                @if($reply->is_internal_note)
                                                    <span class="badge badge-warning text-xs">Internal</span>
                                                @endif
                                            </div>
                                            <div class="bg-gray-800/50 rounded-lg p-3">
                                                <p class="text-white whitespace-pre-wrap">{{ $reply->message }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 mb-4">
                                <i class="ph-chat-circle text-4xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-white mb-2">Belum Ada Balasan</h3>
                            <p class="text-gray-400">Kirim balasan untuk menindaklanjuti pengaduan ini</p>
                        </div>
                    @endif

                    <!-- Reply Form -->
                    <div class="mt-6 pt-6 border-t border-gray-600">
                        <form action="{{ route('superadmin.complaints.reply', $complaint->id) }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="form-label">Balasan</label>
                                    <textarea name="message" class="form-textarea" rows="4" 
                                              placeholder="Tulis balasan untuk siswa..." required></textarea>
                                </div>
                                
                                <div class="flex items-center space-x-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="is_internal_note" value="1" class="form-checkbox">
                                        <span class="ml-2 text-white">Catatan internal (tidak terlihat siswa)</span>
                                    </label>
                                </div>
                                
                                <div class="flex items-center space-x-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ph-paper-plane mr-2"></i>
                                        Kirim Balasan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Status Pengaduan</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('superadmin.complaints.update', $complaint->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="pending" {{ $complaint->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                        <option value="in_progress" {{ $complaint->status == 'in_progress' ? 'selected' : '' }}>Sedang Diproses</option>
                                        <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>Selesai</option>
                                        <option value="closed" {{ $complaint->status == 'closed' ? 'selected' : '' }}>Ditutup</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="form-label">Prioritas</label>
                                    <select name="priority" class="form-select">
                                        <option value="low" {{ $complaint->priority == 'low' ? 'selected' : '' }}>Rendah</option>
                                        <option value="medium" {{ $complaint->priority == 'medium' ? 'selected' : '' }}>Sedang</option>
                                        <option value="high" {{ $complaint->priority == 'high' ? 'selected' : '' }}>Tinggi</option>
                                    </select>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-full">
                                    <i class="ph-check mr-2"></i>
                                    Update Status
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informasi</h3>
                    </div>
                    <div class="card-body space-y-3">
                        <div class="text-sm">
                            <span class="text-gray-400">ID Pengaduan:</span>
                            <span class="text-white">#{{ $complaint->id }}</span>
                        </div>
                        
                        <div class="text-sm">
                            <span class="text-gray-400">Total balasan:</span>
                            <span class="text-white">{{ $complaint->replies->count() }}</span>
                        </div>
                        
                        @if($complaint->resolvedBy)
                            <div class="text-sm">
                                <span class="text-gray-400">Diselesaikan oleh:</span>
                                <span class="text-white">{{ $complaint->resolvedBy->name }}</span>
                            </div>
                            
                            <div class="text-sm">
                                <span class="text-gray-400">Tanggal selesai:</span>
                                <span class="text-white">{{ $complaint->resolved_at->format('d M Y, H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Same styles as superadmin complaints index */
.superadmin-container {
    padding: 2rem;
    max-width: 1400px;
    margin: 0 auto;
}

.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #f8fafc;
    margin-bottom: 0.5rem;
}

.card {
    background: rgba(30, 41, 59, 0.8);
    border-radius: 12px;
    border: 1px solid rgba(51, 65, 85, 0.5);
    margin-bottom: 2rem;
}

.card-header {
    padding: 1.5rem;
    border-bottom: 1px solid rgba(51, 65, 85, 0.5);
}

.card-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #e2e8f0;
    margin: 0;
}

.card-body {
    padding: 1.5rem;
}

.reply-item {
    padding: 1rem;
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
}

.internal-reply {
    background: rgba(245, 158, 11, 0.1);
    border-color: rgba(245, 158, 11, 0.3);
}

.public-reply {
    background: rgba(59, 130, 246, 0.1);
    border-color: rgba(59, 130, 246, 0.3);
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: #e2e8f0;
    margin-bottom: 0.5rem;
}

.form-select, .form-textarea, .form-checkbox {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid rgba(51, 65, 85, 0.5);
    border-radius: 8px;
    background: rgba(15, 23, 42, 0.8);
    color: #e2e8f0;
    font-size: 0.875rem;
}

.form-select:focus, .form-textarea:focus {
    outline: none;
    border-color: #3b82f6;
}

.form-checkbox {
    width: auto;
    margin-right: 0.5rem;
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
