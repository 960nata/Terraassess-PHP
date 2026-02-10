@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Detail Materi')

@section('content')
<div class="page-header">
    <div class="header-content">
        <h1 class="page-title">
            <i class="fas fa-book me-3"></i>
            Detail Materi
        </h1>
        <p class="page-subtitle">Pelajari materi pembelajaran dengan detail</p>
    </div>
</div>

<div class="materi-detail-container">
    <div class="materi-detail-card">
        <div class="materi-detail-header">
            <div class="materi-info">
                <h2 class="materi-title">{{ $materi->name }}</h2>
                <div class="materi-meta">
                    <div class="materi-meta-item">
                        <i class="fas fa-book"></i>
                        <span>{{ $materi->kelasMapel->mapel->nama_mapel }}</span>
                    </div>
                    <div class="materi-meta-item">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>{{ $materi->kelasMapel->pengajar->first()->name ?? 'N/A' }}</span>
                    </div>
                    <div class="materi-meta-item">
                        <i class="fas fa-calendar"></i>
                        <span>{{ $materi->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="materi-detail-content">
            <div class="materi-description">
                <h3>Deskripsi Materi</h3>
                <p>{{ $materi->deskripsi }}</p>
            </div>
            
            @if($materi->content)
            <div class="materi-content">
                <h3>Konten Materi</h3>
                <div class="content-display">
                    {!! $materi->content !!}
                </div>
            </div>
            @endif
            
            @if($materi->file_materi)
            <div class="materi-files">
                <h3>File Materi</h3>
                <div class="file-list">
                    <div class="file-item">
                        <i class="fas fa-file-pdf"></i>
                        <span class="file-name">{{ basename($materi->file_materi) }}</span>
                        <a href="{{ asset('storage/' . $materi->file_materi) }}" target="_blank" class="file-download">
                            <i class="fas fa-download"></i>
                            Download
                        </a>
                    </div>
                </div>
            </div>
            @endif
            
            @if($materi->link_materi)
            <div class="materi-links">
                <h3>Link Materi</h3>
                <div class="link-list">
                    <a href="{{ $materi->link_materi }}" target="_blank" class="materi-link">
                        <i class="fas fa-external-link-alt"></i>
                        {{ $materi->link_materi }}
                    </a>
                </div>
            </div>
            @endif
        </div>
        
        <div class="materi-detail-actions">
            <a href="{{ route('student.materi') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Materi
            </a>
        </div>
    </div>
</div>
@endsection

@section('additional-styles')
<style>
.materi-detail-container {
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.materi-detail-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.materi-detail-header {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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

.materi-meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #cbd5e1;
    font-size: 0.9rem;
}

.materi-meta-item i {
    color: #60a5fa;
    width: 16px;
}

.materi-detail-content {
    margin-bottom: 2rem;
}

.materi-detail-content h3 {
    color: #ffffff;
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
    margin-top: 1.5rem;
}

.materi-detail-content h3:first-child {
    margin-top: 0;
}

.materi-description p {
    color: #e2e8f0;
    line-height: 1.6;
    font-size: 1rem;
}

.materi-content {
    margin-top: 1.5rem;
}

.content-display {
    color: #e2e8f0;
    line-height: 1.6;
    font-size: 1rem;
}

.content-display h1, .content-display h2, .content-display h3, .content-display h4, .content-display h5, .content-display h6 {
    color: #ffffff;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
}

.content-display h1:first-child, .content-display h2:first-child, .content-display h3:first-child, 
.content-display h4:first-child, .content-display h5:first-child, .content-display h6:first-child {
    margin-top: 0;
}

.content-display p {
    margin-bottom: 0.75rem;
    color: #e2e8f0;
}

.content-display ul, .content-display ol {
    margin: 0 0 0.75rem 0;
    padding-left: 1.5rem;
}

.content-display li {
    margin: 0.25rem 0;
    color: #e2e8f0;
}

.content-display blockquote {
    border-left: 4px solid #60a5fa;
    padding-left: 1rem;
    margin: 0 0 0.75rem 0;
    font-style: italic;
    color: #cbd5e1;
    background: rgba(255, 255, 255, 0.05);
    padding: 1rem;
    border-radius: 0 8px 8px 0;
}

.content-display code {
    background: rgba(255, 255, 255, 0.1);
    padding: 0.125rem 0.25rem;
    border-radius: 0.25rem;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 0.875rem;
    color: #fbbf24;
}

.content-display pre {
    background: rgba(255, 255, 255, 0.05);
    padding: 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 0 0 0.75rem 0;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.content-display pre code {
    background: none;
    padding: 0;
    color: #e2e8f0;
}

.content-display img {
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    margin: 0.5rem 0;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
    display: block;
}

.content-display a {
    color: #60a5fa;
    text-decoration: underline;
}

.content-display a:hover {
    color: #93c5fd;
}

.materi-files, .materi-links {
    margin-top: 1.5rem;
}

.file-list, .link-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.file-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.file-item i {
    color: #ef4444;
    font-size: 1.25rem;
}

.file-name {
    color: #e2e8f0;
    flex: 1;
}

.file-download {
    color: #60a5fa;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: rgba(96, 165, 250, 0.1);
    border-radius: 8px;
    transition: all 0.3s ease;
}

.file-download:hover {
    background: rgba(96, 165, 250, 0.2);
    color: #93c5fd;
}

.materi-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: rgba(34, 197, 94, 0.1);
    border-radius: 12px;
    border: 1px solid rgba(34, 197, 94, 0.2);
    color: #22c55e;
    text-decoration: none;
    transition: all 0.3s ease;
}

.materi-link:hover {
    background: rgba(34, 197, 94, 0.2);
    color: #4ade80;
}

.materi-detail-actions {
    display: flex;
    justify-content: flex-start;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-secondary {
    background: rgba(107, 114, 128, 0.2);
    color: #d1d5db;
    border: 1px solid rgba(107, 114, 128, 0.3);
}

.btn-secondary:hover {
    background: rgba(107, 114, 128, 0.3);
    color: #f3f4f6;
}

@media (max-width: 768px) {
    .materi-detail-container {
        padding: 1rem;
    }
    
    .materi-detail-card {
        padding: 1.5rem;
    }
    
    .materi-title {
        font-size: 1.5rem;
    }
    
    .materi-meta {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .file-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .file-download {
        align-self: stretch;
        justify-content: center;
    }
}
</style>
@endsection
