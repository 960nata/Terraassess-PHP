@extends('layouts.unified-layout')

@section('title', $title)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $complaint->subject }}</h1>
                <div class="flex items-center space-x-4 mt-2">
                    <span class="badge badge-{{ $complaint->status_color }}">{{ $complaint->status_text }}</span>
                    <span class="badge badge-{{ $complaint->priority_color }}">{{ $complaint->priority_text }}</span>
                    <span class="text-gray-400">{{ $complaint->category_text }}</span>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('student.complaints.index') }}" class="btn btn-outline">
                    <i class="ph-arrow-left mr-2"></i>
                    Kembali ke Daftar
                </a>
                @if($complaint->status === 'pending')
                    <a href="{{ route('student.complaints.edit', $complaint->id) }}" class="btn btn-outline">
                        <i class="ph-pencil mr-2"></i>
                        Edit
                    </a>
                @endif
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
                        <div>
                            <label class="text-sm font-medium text-gray-400">Dibuat pada:</label>
                            <p class="text-white">{{ $complaint->created_at->format('d M Y, H:i') }} ({{ $complaint->getTimeAgo() }})</p>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium text-gray-400">Isi Pengaduan:</label>
                            <div class="mt-2 p-4 bg-gray-800/50 rounded-lg">
                                <div class="text-white quill-content">{!! $complaint->message !!}</div>
                            </div>
                        </div>

                        <!-- Attachments -->
                        @if($complaint->attachments->count() > 0)
                            <div>
                                <label class="text-sm font-medium text-gray-400">Lampiran:</label>
                                <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($complaint->attachments as $attachment)
                                        <div class="attachment-item">
                                            @if($attachment->is_image)
                                                <div class="attachment-preview-image" onclick="openImageModal('{{ $attachment->file_url }}', '{{ $attachment->file_name }}')">
                                                    <img src="{{ $attachment->file_url }}" alt="{{ $attachment->file_name }}" class="w-full h-24 object-cover rounded-lg cursor-pointer hover:opacity-80 transition-opacity">
                                                    <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-20 transition-all rounded-lg flex items-center justify-center">
                                                        <i class="ph-magnifying-glass-plus text-white text-xl opacity-0 hover:opacity-100 transition-opacity"></i>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="attachment-preview-document">
                                                    <div class="w-full h-24 bg-gray-700 rounded-lg flex flex-col items-center justify-center">
                                                        <i class="{{ $attachment->file_icon }} {{ $attachment->file_icon_color }} text-2xl mb-2"></i>
                                                        <span class="text-xs text-white text-center px-2 truncate w-full">{{ $attachment->file_name }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="mt-2 flex items-center justify-between">
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-xs text-gray-300 truncate">{{ $attachment->file_name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $attachment->file_size_human }}</p>
                                                </div>
                                                <a href="{{ route('student.complaints.attachments.download', $attachment->id) }}" class="ml-2 text-blue-400 hover:text-blue-300">
                                                    <i class="ph-download text-sm"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Replies Section -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Balasan dari Admin</h3>
                </div>
                <div class="card-body">
                    @if($complaint->publicReplies->count() > 0)
                        <div class="space-y-4">
                            @foreach($complaint->publicReplies as $reply)
                                <div class="reply-item">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                                <i class="ph-user text-white text-sm"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <h4 class="text-sm font-medium text-white">{{ $reply->user->name }}</h4>
                                                <span class="text-xs text-gray-400">{{ $reply->getTimeAgo() }}</span>
                                            </div>
                                            <div class="bg-gray-800/50 rounded-lg p-3">
                                                <div class="text-white quill-content">{!! $reply->message !!}</div>
                                                
                                                <!-- Reply Attachments -->
                                                @if($reply->attachments->count() > 0)
                                                    <div class="mt-3 pt-3 border-t border-gray-600">
                                                        <div class="flex flex-wrap gap-2">
                                                            @foreach($reply->attachments as $attachment)
                                                                <div class="attachment-item-small">
                                                                    @if($attachment->is_image)
                                                                        <div class="attachment-preview-image-small" onclick="openImageModal('{{ $attachment->file_url }}', '{{ $attachment->file_name }}')">
                                                                            <img src="{{ $attachment->file_url }}" alt="{{ $attachment->file_name }}" class="w-16 h-16 object-cover rounded cursor-pointer hover:opacity-80 transition-opacity">
                                                                        </div>
                                                                    @else
                                                                        <div class="attachment-preview-document-small">
                                                                            <div class="w-16 h-16 bg-gray-600 rounded flex flex-col items-center justify-center">
                                                                                <i class="{{ $attachment->file_icon }} {{ $attachment->file_icon_color }} text-lg"></i>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                    <div class="mt-1 text-center">
                                                                        <a href="{{ route('student.complaints.attachments.download', $attachment->id) }}" class="text-blue-400 hover:text-blue-300 text-xs">
                                                                            <i class="ph-download"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
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
                            <p class="text-gray-400">Tim admin akan segera menindaklanjuti pengaduan Anda</p>
                        </div>
                    @endif
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
                    <div class="card-body space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Status:</span>
                            <span class="badge badge-{{ $complaint->status_color }}">{{ $complaint->status_text }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Prioritas:</span>
                            <span class="badge badge-{{ $complaint->priority_color }}">{{ $complaint->priority_text }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Kategori:</span>
                            <span class="text-white">{{ $complaint->category_text }}</span>
                        </div>
                        
                        @if($complaint->resolvedBy)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Diselesaikan oleh:</span>
                                <span class="text-white">{{ $complaint->resolvedBy->name }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Tanggal selesai:</span>
                                <span class="text-white">{{ $complaint->resolved_at->format('d M Y, H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Aksi</h3>
                    </div>
                    <div class="card-body space-y-3">
                        @if($complaint->status === 'pending')
                            <a href="{{ route('student.complaints.edit', $complaint->id) }}" class="btn btn-outline w-full">
                                <i class="ph-pencil mr-2"></i>
                                Edit Pengaduan
                            </a>
                            
                            <form action="{{ route('student.complaints.destroy', $complaint->id) }}" method="POST" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengaduan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline w-full text-red-400 hover:text-red-300">
                                    <i class="ph-trash mr-2"></i>
                                    Hapus Pengaduan
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('student.complaints.index') }}" class="btn btn-outline w-full">
                            <i class="ph-list mr-2"></i>
                            Lihat Semua Pengaduan
                        </a>
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
                            <span class="text-white">{{ $complaint->publicReplies->count() }}</span>
                        </div>
                        
                        @if($complaint->getLastReplyTime())
                            <div class="text-sm">
                                <span class="text-gray-400">Balasan terakhir:</span>
                                <span class="text-white">{{ $complaint->getLastReplyTime() }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(255, 255, 255, 0.02);
}

.card-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: white;
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

/* Attachment Styles */
.attachment-item {
    position: relative;
}

.attachment-preview-image {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
}

.attachment-preview-image img {
    transition: transform 0.3s ease;
}

.attachment-preview-image:hover img {
    transform: scale(1.05);
}

.attachment-item-small {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.attachment-preview-image-small {
    position: relative;
    overflow: hidden;
    border-radius: 4px;
}

.attachment-preview-image-small img {
    transition: transform 0.3s ease;
}

.attachment-preview-image-small:hover img {
    transform: scale(1.1);
}

/* Quill Content Styling */
.quill-content {
    line-height: 1.6;
}

.quill-content p {
    margin-bottom: 1rem;
}

.quill-content ul, .quill-content ol {
    margin-left: 1.5rem;
    margin-bottom: 1rem;
}

.quill-content li {
    margin-bottom: 0.5rem;
}

.quill-content a {
    color: #3b82f6;
    text-decoration: underline;
}

.quill-content a:hover {
    color: #60a5fa;
}

.quill-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1rem 0;
}

/* Image Modal */
.image-modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
    backdrop-filter: blur(4px);
}

.image-modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    max-width: 90%;
    max-height: 90%;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1rem;
    backdrop-filter: blur(10px);
}

.image-modal img {
    max-width: 100%;
    max-height: 80vh;
    border-radius: 8px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
}

.image-modal-close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    color: white;
    font-size: 2rem;
    font-weight: bold;
    cursor: pointer;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    width: 3rem;
    height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s ease;
}

.image-modal-close:hover {
    background: rgba(0, 0, 0, 0.8);
}

.image-modal-title {
    color: white;
    text-align: center;
    margin-top: 1rem;
    font-size: 0.875rem;
    opacity: 0.8;
}
</style>

<!-- Image Modal -->
<div id="imageModal" class="image-modal">
    <div class="image-modal-content">
        <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
        <img id="modalImage" src="" alt="">
        <div id="modalTitle" class="image-modal-title"></div>
    </div>
</div>

<script>
function openImageModal(imageSrc, imageTitle) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    
    modal.style.display = 'block';
    modalImg.src = imageSrc;
    modalTitle.textContent = imageTitle;
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
    
    // Restore body scroll
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside the image
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>
@endsection
