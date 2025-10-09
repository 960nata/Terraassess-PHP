@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - Detail Materi')

@section('styles')
<style>
.materi-detail-container {
    background: #1e293b;
    border-radius: 1rem;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid #334155;
}

.materi-header {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #334155;
}

.materi-title {
    color: #ffffff;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.materi-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #94a3b8;
    font-size: 0.9rem;
}

.meta-item i {
    color: #667eea;
    width: 16px;
}

.materi-content {
    margin-bottom: 2rem;
}

.content-section {
    margin-bottom: 2rem;
}

.section-title {
    color: #ffffff;
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #667eea;
    display: inline-block;
}

.content-text {
    color: #e2e8f0;
    line-height: 1.7;
    font-size: 1rem;
}

.file-download-card {
    background: #2a2a3e;
    border: 1px solid #334155;
    border-radius: 0.75rem;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.file-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
}

.file-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(45deg, #667eea, #764ba2);
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 1.25rem;
}

.file-details {
    flex: 1;
}

.file-name {
    color: #ffffff;
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 0.25rem;
}

.file-meta {
    color: #94a3b8;
    font-size: 0.875rem;
}

.download-btn {
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: #ffffff;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    text-decoration: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.download-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    color: #ffffff;
    text-decoration: none;
}

.materi-actions {
    padding-top: 1.5rem;
    border-top: 1px solid #334155;
}

.btn-secondary {
    background: #334155;
    color: #ffffff;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: #475569;
    color: #ffffff;
    text-decoration: none;
}

/* Responsive */
@media (max-width: 768px) {
    .materi-detail-container {
        padding: 1.5rem;
    }
    
    .materi-title {
        font-size: 1.5rem;
    }
    
    .materi-meta {
        flex-direction: column;
        gap: 1rem;
    }
    
    .file-download-card {
        flex-direction: column;
        align-items: stretch;
    }
    
    .file-info {
        margin-bottom: 1rem;
    }
    
    .download-btn {
        justify-content: center;
    }
}
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-book"></i>
        Detail Materi
    </h1>
    <p class="page-description">Pelajari materi pembelajaran dengan detail</p>
</div>

<div class="materi-detail-container">
    <div class="materi-header">
        <h2 class="materi-title">{{ $materi->name }}</h2>
        <div class="materi-meta">
            <div class="meta-item">
                <i class="fas fa-book"></i>
                <span>{{ $materi->kelasMapel->mapel->name }}</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>{{ $materi->kelasMapel->pengajar->first()->name ?? 'N/A' }}</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-calendar"></i>
                <span>{{ $materi->created_at->format('d M Y, H:i') }}</span>
            </div>
        </div>
    </div>
    
    <div class="materi-content">
        @if($materi->deskripsi)
        <div class="content-section">
            <h3 class="section-title">Deskripsi Materi</h3>
            <div class="content-text">
                {{ $materi->deskripsi }}
            </div>
        </div>
        @endif
        
        @if($materi->content)
        <div class="content-section">
            <h3 class="section-title">Konten Materi</h3>
            <div class="content-text">
                {!! $materi->content !!}
            </div>
        </div>
        @endif
        
        @if($materi->file_materi)
        <div class="content-section">
            <h3 class="section-title">File Materi</h3>
            <div class="file-download-card">
                <div class="file-info">
                    <div class="file-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="file-details">
                        <div class="file-name">{{ basename($materi->file_materi) }}</div>
                        <div class="file-meta">
                            {{ $materi->getFileSize() ?? 'Unknown size' }} • {{ $materi->getFileExtension() ?? 'Unknown type' }}
                        </div>
                    </div>
                </div>
                <a href="{{ asset('storage/' . $materi->file_materi) }}" target="_blank" class="download-btn">
                    <i class="fas fa-download"></i>
                    Download
                </a>
            </div>
        </div>
        @endif
    </div>
    
    <div class="materi-actions">
        <a href="{{ route('student.materi') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Materi
        </a>
    </div>
</div>
@endsection

