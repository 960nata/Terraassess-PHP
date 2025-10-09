@extends('layouts.unified-layout-new')

@section('title', 'Tambah Materi Baru')

@section('content')
<div class="page-container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">
                <i class="fas fa-plus-circle"></i>
                Tambah Materi Baru
            </h1>
            <p class="page-subtitle">Buat materi pembelajaran dengan editor modern</p>
        </div>
        <div class="header-actions">
            <a href="{{ 
                $userRole === 'superadmin' ? route('superadmin.material-management') : 
                ($userRole === 'admin' ? route('admin.material-management') : route('teacher.material-management'))
            }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Material Creation Form -->
    <div class="form-container">
        <form action="{{ 
            $userRole === 'superadmin' ? route('superadmin.material-management.store') : 
            ($userRole === 'admin' ? route('admin.material-management.store') : route('teacher.material-management.store'))
        }}" method="POST" enctype="multipart/form-data" id="materialForm">
            @csrf
            
            <!-- Basic Information -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Informasi Dasar
                </h3>
                
                <div class="form-group">
                    <label for="title">Judul Materi <span class="required">*</span></label>
                    <input type="text" id="title" name="title" class="form-control" required 
                           placeholder="Masukkan judul materi" value="{{ old('title') }}">
                    @error('title')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="subject_id">Mata Pelajaran <span class="required">*</span></label>
                        <select id="subject_id" name="subject_id" class="form-control" required>
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach($subjects ?? [] as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="class_id">Kelas <span class="required">*</span></label>
                        <select id="class_id" name="class_id" class="form-control" required>
                            <option value="">Pilih Kelas</option>
                            @foreach($classes ?? [] as $class)
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi Singkat</label>
                    <textarea id="description" name="description" class="form-control" rows="3" 
                              placeholder="Deskripsi singkat tentang materi">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Content Editor -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-edit"></i>
                    Konten Materi
                </h3>
                
                <div class="form-group">
                    <label for="content">Isi Materi <span class="required">*</span></label>
                    <div id="quill-editor" style="height: 400px;"></div>
                    <textarea id="content" name="content" style="display: none;" required>{{ old('content') }}</textarea>
                    @error('content')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- File Uploads -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-upload"></i>
                    File & Media
                </h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="file">File Materi</label>
                        <input type="file" id="file" name="file" class="form-control" 
                               accept=".pdf,.mp4,.jpg,.jpeg,.png,.doc,.docx,.ppt,.pptx">
                        <small class="form-help">Format yang didukung: PDF, Video, Gambar, Dokumen</small>
                        @error('file')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="thumbnail">Thumbnail</label>
                        <input type="file" id="thumbnail" name="thumbnail" class="form-control" 
                               accept=".jpg,.jpeg,.png">
                        <small class="form-help">Gambar preview untuk materi (opsional)</small>
                        @error('thumbnail')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="youtube_url">URL YouTube (Opsional)</label>
                    <input type="url" id="youtube_url" name="youtube_url" class="form-control" 
                           placeholder="https://www.youtube.com/watch?v=..." value="{{ old('youtube_url') }}">
                    <small class="form-help">Link video YouTube untuk materi</small>
                    @error('youtube_url')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Settings -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-cog"></i>
                    Pengaturan
                </h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="type">Tipe Materi</label>
                        <select id="type" name="type" class="form-control">
                            <option value="document" {{ old('type') == 'document' ? 'selected' : '' }}>Dokumen</option>
                            <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>Video</option>
                            <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>Gambar</option>
                            <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>Teks</option>
                        </select>
                        @error('type')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Dipublikasi</option>
                        </select>
                        @error('status')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="history.back()">
                    <i class="fas fa-times"></i>
                    Batal
                </button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i>
                    Simpan Materi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Include Quill Editor -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<style>
/* Material Creation Styles */
.page-container {
    padding: 2rem;
    background: #0f172a;
    min-height: 100vh;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.header-content h1 {
    color: #ffffff;
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.header-content p {
    color: #94a3b8;
    font-size: 1rem;
}

.form-container {
    background: #1e293b;
    border-radius: 1rem;
    padding: 2rem;
    border: 1px solid #334155;
}

.form-section {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #334155;
}

.form-section:last-of-type {
    border-bottom: none;
    margin-bottom: 0;
}

.section-title {
    color: #ffffff;
    font-size: 1.25rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #ffffff;
    font-size: 0.9rem;
}

.required {
    color: #ef4444;
}

.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    background: #2a2a3e;
    border: 2px solid #333;
    border-radius: 8px;
    color: #ffffff;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    background: #333;
}

.form-help {
    display: block;
    margin-top: 0.25rem;
    color: #94a3b8;
    font-size: 0.8rem;
}

.error-message {
    display: block;
    margin-top: 0.25rem;
    color: #ef4444;
    font-size: 0.8rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #334155;
}

.btn-primary, .btn-secondary {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-primary:hover {
    background: #5a67d8;
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
}

/* Quill Editor Styles */
.ql-editor {
    color: #ffffff;
    background: #2a2a3e;
    min-height: 300px;
}

.ql-toolbar {
    background: #1e293b;
    border: 1px solid #334155;
    border-bottom: none;
}

.ql-container {
    border: 1px solid #334155;
    border-top: none;
}

.ql-snow .ql-picker {
    color: #ffffff;
}

.ql-snow .ql-stroke {
    stroke: #ffffff;
}

.ql-snow .ql-fill {
    fill: #ffffff;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
}
</style>

<script>
// Initialize Quill Editor
document.addEventListener('DOMContentLoaded', function() {
    const quill = new Quill('#quill-editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'align': [] }],
                ['link', 'image', 'video'],
                ['blockquote', 'code-block'],
                ['clean']
            ]
        }
    });

    // Set initial content if editing
    const contentTextarea = document.getElementById('content');
    if (contentTextarea.value) {
        quill.root.innerHTML = contentTextarea.value;
    }

    // Update hidden textarea when editor content changes
    quill.on('text-change', function() {
        contentTextarea.value = quill.root.innerHTML;
    });

    // Form submission
    document.getElementById('materialForm').addEventListener('submit', function(e) {
        // Ensure content is updated before submission
        contentTextarea.value = quill.root.innerHTML;
        
        // Basic validation
        if (!contentTextarea.value.trim()) {
            e.preventDefault();
            alert('Konten materi tidak boleh kosong');
            return false;
        }
    });
});
</script>
@endsection
