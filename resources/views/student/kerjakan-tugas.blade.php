@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - Kerjakan Tugas')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-tasks"></i>
        Kerjakan Tugas
    </h1>
    <p class="page-description">Selesaikan tugas yang diberikan oleh pengajar</p>
</div>

<div class="tugas-container">
    <div class="glass-card">
        <div class="tugas-header">
            <h2 class="tugas-title">{{ $tugas->name }}</h2>
            <div class="tugas-meta">
                <div class="meta-item">
                    <i class="fas fa-book"></i>
                    <span>{{ $tugas->kelasMapel->mapel->name ?? 'Mata Pelajaran' }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-user"></i>
                    <span>{{ $tugas->kelasMapel->pengajar->first()->name ?? 'N/A' }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>{{ \Carbon\Carbon::parse($tugas->created_at)->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        <div class="tugas-content">
            <div class="tugas-description">
                <h3>Deskripsi Tugas</h3>
                <p>{{ $tugas->description ?? 'Tidak ada deskripsi tersedia.' }}</p>
            </div>

            @if($tugas->file_path)
            <div class="tugas-file">
                <h3>File Tugas</h3>
                <div class="file-download">
                    <i class="fas fa-download"></i>
                    <a href="{{ asset('storage/' . $tugas->file_path) }}" download class="download-link">
                        Download File Tugas
                    </a>
                </div>
            </div>
            @endif

            <form action="{{ route('student.submit-tugas', $tugas->id) }}" method="POST" enctype="multipart/form-data" class="tugas-form">
                @csrf
                <div class="form-group">
                    <label for="answer">Jawaban Anda</label>
                    <textarea name="answer" id="answer" rows="10" class="form-control" placeholder="Tulis jawaban Anda di sini..." required></textarea>
                </div>

                <div class="form-group">
                    <label for="file">Upload File Jawaban (Opsional)</label>
                    <input type="file" name="file" id="file" class="form-control" accept=".pdf,.doc,.docx,.txt">
                    <small class="form-text">Format yang diperbolehkan: PDF, DOC, DOCX, TXT (Max: 10MB)</small>
                </div>

                <div class="form-actions">
                    <a href="{{ route('student.tugas') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        Kirim Jawaban
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('additional-styles')
<style>
    .tugas-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 2rem;
        margin: 2rem 0;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }

    .tugas-header {
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .tugas-title {
        color: #ffffff;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .tugas-meta {
        display: flex;
        justify-content: center;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #cbd5e1;
        font-size: 0.9rem;
    }

    .meta-item i {
        color: #3b82f6;
        font-size: 1rem;
    }

    .tugas-content {
        color: #ffffff;
    }

    .tugas-description {
        margin-bottom: 2rem;
    }

    .tugas-description h3 {
        color: #ffffff;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tugas-description h3::before {
        content: "📝";
        font-size: 1.5rem;
    }

    .tugas-description p {
        color: #cbd5e1;
        line-height: 1.6;
        font-size: 1rem;
        background: rgba(255, 255, 255, 0.05);
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid #3b82f6;
    }

    .tugas-file {
        margin-bottom: 2rem;
    }

    .tugas-file h3 {
        color: #ffffff;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tugas-file h3::before {
        content: "📎";
        font-size: 1.5rem;
    }

    .file-download {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: rgba(59, 130, 246, 0.1);
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid rgba(59, 130, 246, 0.3);
        transition: all 0.3s ease;
    }

    .file-download:hover {
        background: rgba(59, 130, 246, 0.2);
        border-color: rgba(59, 130, 246, 0.5);
        transform: translateY(-2px);
    }

    .file-download i {
        color: #3b82f6;
        font-size: 1.25rem;
    }

    .download-link {
        color: #3b82f6;
        text-decoration: none;
        font-weight: 500;
        font-size: 1rem;
        transition: color 0.3s ease;
    }

    .download-link:hover {
        color: #60a5fa;
        text-decoration: underline;
    }

    .tugas-form {
        background: rgba(255, 255, 255, 0.05);
        padding: 2rem;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        color: #ffffff;
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        color: #ffffff;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        background: rgba(255, 255, 255, 0.15);
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .form-text {
        color: #94a3b8;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        min-width: 120px;
        justify-content: center;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        transform: translateY(-2px);
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .glass-card {
            margin: 1rem 0;
            padding: 1.5rem;
        }

        .tugas-title {
            font-size: 1.5rem;
        }

        .tugas-meta {
            flex-direction: column;
            gap: 0.75rem;
            align-items: center;
        }

        .tugas-form {
            padding: 1.5rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .glass-card {
            padding: 1rem;
        }

        .tugas-title {
            font-size: 1.25rem;
        }

        .tugas-description h3,
        .tugas-file h3 {
            font-size: 1.125rem;
        }

        .tugas-form {
            padding: 1rem;
        }
    }
</style>
@endsection

@section('additional-scripts')
<script>
    // File upload handling
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('file');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    console.log('File selected:', file.name);
                }
            });
        }
    });
</script>
@endsection
