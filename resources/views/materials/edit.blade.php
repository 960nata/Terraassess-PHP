@extends('layouts.app')

@section('title', 'Edit Materi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Materi: {{ $material->title }}
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('materials.update', $material) }}" method="POST" enctype="multipart/form-data" id="materialForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Basic Information -->
                                <div class="form-group">
                                    <label for="title">Judul Materi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $material->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="type">Tipe Materi <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror" 
                                            id="type" name="type" required onchange="toggleContentType()">
                                        <option value="">Pilih Tipe Materi</option>
                                        <option value="text" {{ old('type', $material->type) == 'text' ? 'selected' : '' }}>Teks</option>
                                        <option value="document" {{ old('type', $material->type) == 'document' ? 'selected' : '' }}>Dokumen</option>
                                        <option value="video" {{ old('type', $material->type) == 'video' ? 'selected' : '' }}>Video</option>
                                        <option value="image" {{ old('type', $material->type) == 'image' ? 'selected' : '' }}>Gambar</option>
                                        <option value="audio" {{ old('type', $material->type) == 'audio' ? 'selected' : '' }}>Audio</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="description">Deskripsi</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3" 
                                              placeholder="Deskripsi singkat tentang materi ini...">{{ old('description', $material->description) }}</textarea>
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
                                        <textarea name="content" id="content" style="display: none;">{{ old('content', $material->content) }}</textarea>
                                    </div>
                                    
                                    <!-- Video Content -->
                                    <div id="videoContent" class="content-type" style="display: none;">
                                        <div class="form-group">
                                            <label for="youtube_url">URL YouTube</label>
                                            <input type="url" class="form-control @error('youtube_url') is-invalid @enderror" 
                                                   id="youtube_url" name="youtube_url" 
                                                   placeholder="https://www.youtube.com/watch?v=..." 
                                                   value="{{ old('youtube_url', $material->youtube_url) }}">
                                            @error('youtube_url')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="file">Upload Video (Opsional)</label>
                                            <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                                   id="file" name="file" accept="video/*">
                                            <small class="form-text text-muted">Format: MP4, AVI, MOV. Maksimal 10MB</small>
                                            @if($material->file_path)
                                                <div class="mt-2">
                                                    <small class="text-muted">File saat ini: </small>
                                                    <a href="{{ $material->file_url }}" target="_blank">{{ $material->file_name }}</a>
                                                </div>
                                            @endif
                                            @error('file')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <!-- Document Content -->
                                    <div id="documentContent" class="content-type" style="display: none;">
                                        <div class="form-group">
                                            <label for="file">Upload Dokumen</label>
                                            <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                                   id="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx">
                                            <small class="form-text text-muted">Format: PDF, Word, PowerPoint. Maksimal 10MB</small>
                                            @if($material->file_path)
                                                <div class="mt-2">
                                                    <small class="text-muted">File saat ini: </small>
                                                    <a href="{{ $material->file_url }}" target="_blank">{{ $material->file_name }}</a>
                                                </div>
                                            @endif
                                            @error('file')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <!-- Image Content -->
                                    <div id="imageContent" class="content-type" style="display: none;">
                                        <div class="form-group">
                                            <label for="file">Upload Gambar</label>
                                            <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                                   id="file" name="file" accept="image/*">
                                            <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 10MB</small>
                                            @if($material->file_path)
                                                <div class="mt-2">
                                                    <small class="text-muted">File saat ini: </small>
                                                    <a href="{{ $material->file_url }}" target="_blank">{{ $material->file_name }}</a>
                                                </div>
                                            @endif
                                            @error('file')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="content">Deskripsi Gambar (Opsional)</label>
                                            <div id="quillEditorImage" style="height: 200px;"></div>
                                            <textarea name="content" id="contentImage" style="display: none;">{{ old('content', $material->content) }}</textarea>
                                        </div>
                                    </div>
                                    
                                    <!-- Audio Content -->
                                    <div id="audioContent" class="content-type" style="display: none;">
                                        <div class="form-group">
                                            <label for="file">Upload Audio</label>
                                            <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                                   id="file" name="file" accept="audio/*">
                                            <small class="form-text text-muted">Format: MP3, WAV, M4A. Maksimal 10MB</small>
                                            @if($material->file_path)
                                                <div class="mt-2">
                                                    <small class="text-muted">File saat ini: </small>
                                                    <a href="{{ $material->file_url }}" target="_blank">{{ $material->file_name }}</a>
                                                </div>
                                            @endif
                                            @error('file')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="content">Transkrip Audio (Opsional)</label>
                                            <div id="quillEditorAudio" style="height: 200px;"></div>
                                            <textarea name="content" id="contentAudio" style="display: none;">{{ old('content', $material->content) }}</textarea>
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
                                            <option value="{{ $class->id }}" {{ old('class_id', $material->class_id) == $class->id ? 'selected' : '' }}>
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
                                            <option value="{{ $subject->id }}" {{ old('subject_id', $material->subject_id) == $subject->id ? 'selected' : '' }}>
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
                                        <input class="form-check-input" type="radio" name="status" id="draft" value="draft" 
                                               {{ old('status', $material->status) == 'draft' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="draft">
                                            <i class="fas fa-edit text-warning mr-1"></i>
                                            Draft (Simpan sementara)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="published" value="published"
                                               {{ old('status', $material->status) == 'published' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="published">
                                            <i class="fas fa-upload text-success mr-1"></i>
                                            Publikasikan (Tampil untuk siswa)
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Current File Preview -->
                                @if($material->file_path)
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">File Saat Ini</h6>
                                        </div>
                                        <div class="card-body">
                                            @if($material->type === 'image')
                                                <img src="{{ $material->file_url }}" class="img-fluid rounded mb-2" alt="Current file">
                                            @elseif($material->type === 'video')
                                                <video controls class="w-100 mb-2" style="max-height: 200px;">
                                                    <source src="{{ $material->file_url }}" type="{{ $material->file_type }}">
                                                </video>
                                            @elseif($material->type === 'audio')
                                                <audio controls class="w-100 mb-2">
                                                    <source src="{{ $material->file_url }}" type="{{ $material->file_type }}">
                                                </audio>
                                            @endif
                                            <p class="mb-1"><strong>{{ $material->file_name }}</strong></p>
                                            <p class="text-muted small mb-0">{{ $material->formatted_file_size }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="form-group text-right">
                            <a href="{{ route('materials.show', $material) }}" class="btn btn-secondary mr-2">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Batal
                            </a>
                            <button type="button" class="btn btn-outline-primary mr-2" onclick="saveDraft()">
                                <i class="fas fa-save mr-1"></i>
                                Simpan Draft
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>
                                Simpan Perubahan
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
    
    // Set initial content
    const type = '{{ $material->type }}';
    const content = {!! json_encode($material->content) !!};
    
    if (type === 'text' && content) {
        quillEditor.root.innerHTML = content;
    } else if (type === 'image' && content) {
        quillEditorImage.root.innerHTML = content;
    } else if (type === 'audio' && content) {
        quillEditorAudio.root.innerHTML = content;
    }
    
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
</script>
@endsection
