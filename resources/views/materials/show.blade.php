@extends('layouts.app')

@section('title', $material->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title mb-0">{{ $material->title }}</h3>
                            <div class="mt-2">
                                <span class="badge badge-{{ $material->type_color }} mr-2">
                                    <i class="{{ $material->type_icon }} mr-1"></i>
                                    {{ ucfirst($material->type) }}
                                </span>
                                <span class="badge badge-{{ $material->status === 'published' ? 'success' : 'warning' }}">
                                    {{ $material->status === 'published' ? 'Dipublikasi' : 'Draft' }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('materials.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Kembali
                            </a>
                            @if(auth()->user()->role === 'teacher')
                                <a href="{{ route('materials.edit', $material) }}" class="btn btn-primary">
                                    <i class="fas fa-edit mr-1"></i>
                                    Edit
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Material Content -->
                            <div class="mb-4">
                                @if($material->description)
                                    <div class="alert alert-info">
                                        <h6><i class="fas fa-info-circle mr-2"></i>Deskripsi</h6>
                                        <p class="mb-0">{{ $material->description }}</p>
                                    </div>
                                @endif
                                
                                @if($material->type === 'text' && $material->content)
                                    <div class="content-wrapper">
                                        {!! $material->content !!}
                                    </div>
                                @elseif($material->type === 'document')
                                    <div class="text-center">
                                        @if($material->file_url)
                                            <div class="mb-3">
                                                <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                                <h5>{{ $material->file_name }}</h5>
                                                <p class="text-muted">{{ $material->formatted_file_size }}</p>
                                            </div>
                                            <a href="{{ $material->file_url }}" target="_blank" class="btn btn-primary">
                                                <i class="fas fa-download mr-1"></i>
                                                Download Dokumen
                                            </a>
                                        @else
                                            <p class="text-muted">Dokumen tidak tersedia</p>
                                        @endif
                                    </div>
                                @elseif($material->type === 'video')
                                    <div class="text-center">
                                        @if($material->youtube_url)
                                            <div class="embed-responsive embed-responsive-16by9 mb-3">
                                                <iframe class="embed-responsive-item" 
                                                        src="{{ str_replace('watch?v=', 'embed/', $material->youtube_url) }}" 
                                                        allowfullscreen></iframe>
                                            </div>
                                        @endif
                                        
                                        @if($material->file_url)
                                            <div class="mt-3">
                                                <h6>Video Upload</h6>
                                                <video controls class="w-100" style="max-height: 400px;">
                                                    <source src="{{ $material->file_url }}" type="{{ $material->file_type }}">
                                                    Browser Anda tidak mendukung video.
                                                </video>
                                            </div>
                                        @endif
                                        
                                        @if($material->content)
                                            <div class="mt-4">
                                                <h6>Deskripsi Video</h6>
                                                <div class="content-wrapper">
                                                    {!! $material->content !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @elseif($material->type === 'image')
                                    <div class="text-center">
                                        @if($material->file_url)
                                            <img src="{{ $material->file_url }}" class="img-fluid rounded mb-3" 
                                                 alt="{{ $material->title }}" style="max-height: 500px;">
                                        @endif
                                        
                                        @if($material->content)
                                            <div class="mt-4">
                                                <h6>Deskripsi Gambar</h6>
                                                <div class="content-wrapper">
                                                    {!! $material->content !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @elseif($material->type === 'audio')
                                    <div class="text-center">
                                        @if($material->file_url)
                                            <div class="mb-3">
                                                <i class="fas fa-volume-up fa-3x text-warning mb-3"></i>
                                                <h5>{{ $material->file_name }}</h5>
                                                <p class="text-muted">{{ $material->formatted_file_size }}</p>
                                            </div>
                                            <audio controls class="w-100 mb-4">
                                                <source src="{{ $material->file_url }}" type="{{ $material->file_type }}">
                                                Browser Anda tidak mendukung audio.
                                            </audio>
                                        @endif
                                        
                                        @if($material->content)
                                            <div class="mt-4">
                                                <h6>Transkrip Audio</h6>
                                                <div class="content-wrapper">
                                                    {!! $material->content !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <!-- Material Info -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Informasi Materi</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Kelas</small>
                                            <strong>{{ $material->class->name ?? 'N/A' }}</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Mata Pelajaran</small>
                                            <strong>{{ $material->subject->name ?? 'N/A' }}</strong>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Dibuat Oleh</small>
                                            <strong>{{ $material->teacher->name ?? 'N/A' }}</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Dilihat</small>
                                            <strong>{{ $material->views }} kali</strong>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Dibuat</small>
                                            <strong>{{ $material->created_at->format('d M Y') }}</strong>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted d-block">Diperbarui</small>
                                            <strong>{{ $material->updated_at->format('d M Y') }}</strong>
                                        </div>
                                    </div>
                                    
                                    @if($material->file_name)
                                        <hr>
                                        <div class="mb-2">
                                            <small class="text-muted d-block">File</small>
                                            <strong>{{ $material->file_name }}</strong>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted d-block">Ukuran</small>
                                            <strong>{{ $material->formatted_file_size }}</strong>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Tipe</small>
                                            <strong>{{ $material->file_type }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            @if(auth()->user()->role === 'teacher')
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h6 class="mb-0">Aksi</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('materials.edit', $material) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-edit mr-1"></i>
                                                Edit Materi
                                            </a>
                                            
                                            @if($material->status === 'draft')
                                                <form action="{{ route('materials.publish', $material) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success w-100">
                                                        <i class="fas fa-upload mr-1"></i>
                                                        Publikasikan
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('materials.unpublish', $material) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning w-100">
                                                        <i class="fas fa-edit mr-1"></i>
                                                        Jadikan Draft
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <form action="{{ route('materials.destroy', $material) }}" method="POST" 
                                                  onsubmit="return confirm('Yakin ingin menghapus materi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger w-100">
                                                    <i class="fas fa-trash mr-1"></i>
                                                    Hapus Materi
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.content-wrapper {
    line-height: 1.6;
}

.content-wrapper h1, .content-wrapper h2, .content-wrapper h3, 
.content-wrapper h4, .content-wrapper h5, .content-wrapper h6 {
    margin-top: 1.5rem;
    margin-bottom: 0.5rem;
}

.content-wrapper p {
    margin-bottom: 1rem;
}

.content-wrapper ul, .content-wrapper ol {
    margin-bottom: 1rem;
    padding-left: 2rem;
}

.content-wrapper blockquote {
    border-left: 4px solid #dee2e6;
    padding-left: 1rem;
    margin: 1rem 0;
    font-style: italic;
    color: #6c757d;
}

.content-wrapper img {
    max-width: 100%;
    height: auto;
    border-radius: 0.375rem;
}

.embed-responsive {
    position: relative;
    display: block;
    width: 100%;
    padding: 0;
    overflow: hidden;
}

.embed-responsive::before {
    content: "";
    display: block;
    padding-top: 56.25%;
}

.embed-responsive-item {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: 0;
}
</style>
@endsection
