@extends('layouts.app')

@section('title', 'Tambah Materi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Materi Baru
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data" id="materialForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Basic Information -->
                                <div class="form-group">
                                    <label for="title">Judul Materi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="type">Tipe Materi <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror" 
                                            id="type" name="type" required onchange="toggleContentType()">
                                        <option value="">Pilih Tipe Materi</option>
                                        <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>Teks</option>
                                        <option value="document" {{ old('type') == 'document' ? 'selected' : '' }}>Dokumen</option>
                                        <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>Video</option>
                                        <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>Gambar</option>
                                        <option value="audio" {{ old('type') == 'audio' ? 'selected' : '' }}>Audio</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="description">Deskripsi</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3" 
                                              placeholder="Deskripsi singkat tentang materi ini...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Content based on type -->
                                <div id="contentSection">
                                    <!-- Text Content (Quill Editor) -->
                                    <div id="textContent" class="content-type" style="display: none;">
                                        <label for="content">Konten Materi</label>
                                        <div id="quillEditor" style="height: 300px;"></div>
                                        <textarea name="content" id="content" style="display: none;"></textarea>
                                    </div>
                                    
                                    <!-- Video Content -->
                                    <div id="videoContent" class="content-type" style="display: none;">
                                        <div class="form-group">
                                            <label for="youtube_url">URL YouTube</label>
                                            <input type="url" class="form-control @error('youtube_url') is-invalid @enderror" 
                                                   id="youtube_url" name="youtube_url" 
                                                   placeholder="https://www.youtube.com/watch?v=..." 
                                                   value="{{ old('youtube_url') }}">
                                            @error('youtube_url')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="file">Upload Video (Opsional)</label>
                                            <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                                   id="file" name="file" accept="video/*">
                                            <small class="form-text text-muted">Format: MP4, AVI, MOV. Maksimal 10MB</small>
                                            @error('file')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <!-- Document Content -->
                                    <div id="documentContent" class="content-type" style="display: none;">
                                        <div class="form-group">
                                            <label for="file">Upload Dokumen <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                                   id="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx" required>
                                            <small class="form-text text-muted">Format: PDF, Word, PowerPoint. Maksimal 10MB</small>
                                            @error('file')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <!-- Image Content -->
                                    <div id="imageContent" class="content-type" style="display: none;">
                                        <div class="form-group">
                                            <label for="file">Upload Gambar <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                                   id="file" name="file" accept="image/*" required>
                                            <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 10MB</small>
                                            @error('file')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="content">Deskripsi Gambar (Opsional)</label>
                                            <div id="quillEditorImage" style="height: 200px;"></div>
                                            <textarea name="content" id="contentImage" style="display: none;"></textarea>
                                        </div>
                                    </div>
                                    
                                    <!-- Audio Content -->
                                    <div id="audioContent" class="content-type" style="display: none;">
                                        <div class="form-group">
                                            <label for="file">Upload Audio <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                                   id="file" name="file" accept="audio/*" required>
                                            <small class="form-text text-muted">Format: MP3, WAV, M4A. Maksimal 10MB</small>
                                            @error('file')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="content">Transkrip Audio (Opsional)</label>
                                            <div id="quillEditorAudio" style="height: 200px;"></div>
                                            <textarea name="content" id="contentAudio" style="display: none;"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <!-- Class and Subject -->
                                <div class="form-group">
                                    <label for="class_id">Kelas <span class="text-danger">*</span></label>
                                    <select class="form-control @error('class_id') is-invalid @enderror" 
                                            id="class_id" name="class_id" required>
                                        <option value="">Pilih Kelas</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="subject_id">Mata Pelajaran <span class="text-danger">*</span></label>
                                    <select class="form-control @error('subject_id') is-invalid @enderror" 
                                            id="subject_id" name="subject_id" required>
                                        <option value="">Pilih Mata Pelajaran</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('subject_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <!-- Status -->
                                <div class="form-group">
                                    <label>Status</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="draft" value="draft" checked>
                                        <label class="form-check-label" for="draft">
                                            <i class="fas fa-edit text-warning mr-1"></i>
                                            Draft (Simpan sementara)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="published" value="published">
                                        <label class="form-check-label" for="published">
                                            <i class="fas fa-upload text-success mr-1"></i>
                                            Publikasikan (Tampil untuk siswa)
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Preview -->
                                <div class="card" id="previewCard" style="display: none;">
                                    <div class="card-header">
                                        <h6 class="mb-0">Preview</h6>
                                    </div>
                                    <div class="card-body" id="previewContent">
                                        <!-- Preview content will be inserted here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="form-group text-right">
                            <a href="{{ route('materials.index') }}" class="btn btn-secondary mr-2">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Batal
                            </a>
                            <button type="button" class="btn btn-outline-primary mr-2" onclick="saveDraft()">
                                <i class="fas fa-save mr-1"></i>
                                Simpan Draft
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload mr-1"></i>
                                Publikasikan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Quill CSS and JS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
let quillEditor, quillEditorImage, quillEditorAudio;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Quill editors
    quillEditor = new Quill('#quillEditor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                ['link', 'image', 'video'],
                ['clean']
            ]
        }
    });
    
    quillEditorImage = new Quill('#quillEditorImage', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link'],
                ['clean']
            ]
        }
    });
    
    quillEditorAudio = new Quill('#quillEditorAudio', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link'],
                ['clean']
            ]
        }
    });
    
    // Set initial content type
    toggleContentType();
});

function toggleContentType() {
    const type = document.getElementById('type').value;
    const contentTypes = document.querySelectorAll('.content-type');
    
    // Hide all content types
    contentTypes.forEach(content => {
        content.style.display = 'none';
    });
    
    // Show selected content type
    if (type) {
        document.getElementById(type + 'Content').style.display = 'block';
    }
    
    // Update file input requirements
    const fileInput = document.getElementById('file');
    if (type === 'text') {
        fileInput.required = false;
    } else if (type === 'video') {
        fileInput.required = false;
    } else {
        fileInput.required = true;
    }
}

function saveDraft() {
    document.getElementById('draft').checked = true;
    document.getElementById('materialForm').submit();
}

// Update content textarea before form submission
document.getElementById('materialForm').addEventListener('submit', function() {
    const type = document.getElementById('type').value;
    
    if (type === 'text' && quillEditor) {
        document.getElementById('content').value = quillEditor.root.innerHTML;
    } else if (type === 'image' && quillEditorImage) {
        document.getElementById('contentImage').value = quillEditorImage.root.innerHTML;
    } else if (type === 'audio' && quillEditorAudio) {
        document.getElementById('contentAudio').value = quillEditorAudio.root.innerHTML;
    }
});

// File upload preview
document.getElementById('file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewCard = document.getElementById('previewCard');
            const previewContent = document.getElementById('previewContent');
            
            const type = document.getElementById('type').value;
            
            if (type === 'image') {
                previewContent.innerHTML = `<img src="${e.target.result}" class="img-fluid" alt="Preview">`;
            } else if (type === 'video') {
                previewContent.innerHTML = `<video controls class="w-100"><source src="${e.target.result}" type="${file.type}"></video>`;
            } else if (type === 'audio') {
                previewContent.innerHTML = `<audio controls class="w-100"><source src="${e.target.result}" type="${file.type}"></audio>`;
            } else {
                previewContent.innerHTML = `<p><i class="fas fa-file mr-2"></i>${file.name}</p><p class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</p>`;
            }
            
            previewCard.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
