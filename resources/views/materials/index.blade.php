@extends('layouts.app')

@section('title', 'Manajemen Materi')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">
                        <i class="fas fa-book mr-2"></i>
                        Manajemen Materi
                    </h2>
                    <p class="text-muted mb-0">Kelola materi pembelajaran Anda</p>
                </div>
                @if(auth()->user()->role === 'teacher')
                    <a href="{{ route('materials.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i>
                        + Materi
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card border-left-primary">
                <div class="card-body text-center">
                    <div class="text-primary mb-2">
                        <i class="fas fa-file-pdf fa-2x"></i>
                    </div>
                    <h4 class="mb-0">{{ $stats['documents'] }}</h4>
                    <p class="text-muted mb-0">Dokumen</p>
                    <small class="text-muted">PDF, Word, PowerPoint</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card border-left-success">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="fas fa-video fa-2x"></i>
                    </div>
                    <h4 class="mb-0">{{ $stats['videos'] }}</h4>
                    <p class="text-muted mb-0">Video</p>
                    <small class="text-muted">YouTube & Upload</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card border-left-info">
                <div class="card-body text-center">
                    <div class="text-info mb-2">
                        <i class="fas fa-image fa-2x"></i>
                    </div>
                    <h4 class="mb-0">{{ $stats['images'] }}</h4>
                    <p class="text-muted mb-0">Gambar</p>
                    <small class="text-muted">Diagram & Infografis</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card border-left-warning">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="fas fa-volume-up fa-2x"></i>
                    </div>
                    <h4 class="mb-0">{{ $stats['audio'] }}</h4>
                    <p class="text-muted mb-0">Audio</p>
                    <small class="text-muted">Rekaman & Podcast</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card border-left-secondary">
                <div class="card-body text-center">
                    <div class="text-secondary mb-2">
                        <i class="fas fa-file-alt fa-2x"></i>
                    </div>
                    <h4 class="mb-0">{{ $stats['total'] - $stats['documents'] - $stats['videos'] - $stats['images'] - $stats['audio'] }}</h4>
                    <p class="text-muted mb-0">Teks</p>
                    <small class="text-muted">Artikel & Catatan</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card border-left-danger">
                <div class="card-body text-center">
                    <div class="text-danger mb-2">
                        <i class="fas fa-edit fa-2x"></i>
                    </div>
                    <h4 class="mb-0">{{ $stats['drafts'] }}</h4>
                    <p class="text-muted mb-0">Draft</p>
                    <small class="text-muted">Belum Dipublikasi</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Cari materi..." id="searchInput">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <select class="form-control" id="typeFilter">
                <option value="">Semua Tipe</option>
                <option value="document">Dokumen</option>
                <option value="video">Video</option>
                <option value="image">Gambar</option>
                <option value="audio">Audio</option>
                <option value="text">Teks</option>
            </select>
        </div>
    </div>

    <!-- Materials List -->
    <div class="row" id="materialsList">
        @forelse($materials as $material)
            <div class="col-lg-4 col-md-6 mb-4 material-item" data-type="{{ $material->type }}">
                <div class="card h-100">
                    <!-- Thumbnail -->
                    <div class="position-relative">
                        @if($material->thumbnail_url)
                            <img src="{{ $material->thumbnail_url }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $material->title }}">
                        @elseif($material->type === 'video' && $material->youtube_url)
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                <i class="fas fa-play-circle fa-3x text-primary"></i>
                            </div>
                        @else
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                <i class="{{ $material->type_icon }} fa-3x text-{{ $material->type_color }}"></i>
                            </div>
                        @endif
                        
                        <!-- Type Badge -->
                        <span class="badge badge-{{ $material->type_color }} position-absolute" style="top: 10px; left: 10px;">
                            <i class="{{ $material->type_icon }} mr-1"></i>
                            {{ ucfirst($material->type) }}
                        </span>
                        
                        <!-- Status Badge -->
                        <span class="badge badge-{{ $material->status === 'published' ? 'success' : 'warning' }} position-absolute" style="top: 10px; right: 10px;">
                            {{ $material->status === 'published' ? 'Dipublikasi' : 'Draft' }}
                        </span>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $material->title }}</h5>
                        <p class="card-text text-muted small">{{ Str::limit($material->description, 100) }}</p>
                        
                        <div class="mt-auto">
                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <small class="text-muted d-block">Kelas</small>
                                    <strong>{{ $material->class->name ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Mapel</small>
                                    <strong>{{ $material->subject->name ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Dilihat</small>
                                    <strong>{{ $material->views }}</strong>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('materials.show', $material) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye mr-1"></i>
                                    Lihat
                                </a>
                                
                                @if(auth()->user()->role === 'teacher')
                                    <div class="btn-group">
                                        <a href="{{ route('materials.edit', $material) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        @if($material->status === 'draft')
                                            <form action="{{ route('materials.publish', $material) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" title="Publikasikan">
                                                    <i class="fas fa-upload"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('materials.unpublish', $material) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning" title="Jadikan Draft">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form action="{{ route('materials.destroy', $material) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus materi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada materi</h5>
                    <p class="text-muted">
                        @if(auth()->user()->role === 'teacher')
                            Mulai buat materi pertama Anda
                        @else
                            Materi akan muncul di sini setelah dipublikasikan oleh guru
                        @endif
                    </p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const typeFilter = document.getElementById('typeFilter');
    const materialItems = document.querySelectorAll('.material-item');
    
    function filterMaterials() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedType = typeFilter.value;
        
        materialItems.forEach(item => {
            const title = item.querySelector('.card-title').textContent.toLowerCase();
            const type = item.dataset.type;
            
            const matchesSearch = title.includes(searchTerm);
            const matchesType = !selectedType || type === selectedType;
            
            if (matchesSearch && matchesType) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }
    
    searchInput.addEventListener('input', filterMaterials);
    typeFilter.addEventListener('change', filterMaterials);
});
</script>
@endsection
