@extends('layouts.unified-layout')

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

.validation-error {
    display: block;
    margin-top: 0.25rem;
    color: #ef4444;
    font-size: 0.875rem;
    font-weight: 500;
}

.form-control.error {
    border-color: #ef4444 !important;
}

.form-control.valid {
    border-color: #10b981 !important;
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
let quill;
document.addEventListener('DOMContentLoaded', function() {
    quill = new Quill('#quill-editor', {
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

    // Fungsi untuk kompres gambar base64
    function compressImage(base64Str, maxWidth = 800, quality = 0.7) {
        return new Promise((resolve) => {
            const img = new Image();
            img.onload = function() {
                const canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;
                
                // Resize jika lebih besar dari maxWidth
                if (width > maxWidth) {
                    height = (height * maxWidth) / width;
                    width = maxWidth;
                }
                
                canvas.width = width;
                canvas.height = height;
                
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);
                
                // Kompres dengan quality
                const compressedBase64 = canvas.toDataURL('image/jpeg', quality);
                resolve(compressedBase64);
            };
            img.src = base64Str;
        });
    }

    // Custom image handler untuk kompres gambar
    quill.getModule('toolbar').addHandler('image', async function() {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();
        
        input.onchange = async function() {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = async function(e) {
                    const base64 = e.target.result;
                    
                    try {
                        // Kompres gambar
                        const compressed = await compressImage(base64, 800, 0.7);
                        
                        // Insert ke Quill
                        const range = quill.getSelection();
                        quill.insertEmbed(range.index, 'image', compressed);
                    } catch (error) {
                        console.error('Error compressing image:', error);
                        // Fallback: insert original image
                        const range = quill.getSelection();
                        quill.insertEmbed(range.index, 'image', base64);
                    }
                };
                reader.readAsDataURL(file);
            }
        };
    });

    // Set initial content if editing
    const contentTextarea = document.getElementById('content');
    if (contentTextarea.value) {
        quill.root.innerHTML = contentTextarea.value;
    }

    // Update hidden textarea when editor content changes
    quill.on('text-change', function() {
        contentTextarea.value = quill.root.innerHTML;
        validateContent();
    });

    // Initialize real-time validation
    initializeValidation();
});

// Real-time validation functions
function validateTitle() {
    const input = document.getElementById('title');
    const value = input.value.trim();
    const errorId = 'title-error';
    
    if (value === '') {
        showValidationError(input, errorId, 'Judul materi wajib diisi');
        return false;
    } else if (value.length < 3) {
        showValidationError(input, errorId, 'Judul minimal 3 karakter');
        return false;
    } else {
        hideValidationError(input, errorId);
        return true;
    }
}

function validateSubject() {
    const select = document.getElementById('subject_id');
    const value = select.value;
    const errorId = 'subject-error';
    
    if (value === '' || value === null) {
        showValidationError(select, errorId, 'Mata pelajaran wajib dipilih');
        return false;
    } else {
        hideValidationError(select, errorId);
        return true;
    }
}

function validateClass() {
    const select = document.getElementById('class_id');
    const value = select.value;
    const errorId = 'class-error';
    
    if (value === '' || value === null) {
        showValidationError(select, errorId, 'Kelas wajib dipilih');
        return false;
    } else {
        hideValidationError(select, errorId);
        return true;
    }
}

function validateContent() {
    const quillContent = quill.getText().trim();
    const errorId = 'content-error';
    const errorContainer = document.getElementById(errorId);
    
    if (quillContent === '' || quillContent.length < 10) {
        if (!errorContainer) {
            const error = document.createElement('span');
            error.id = errorId;
            error.className = 'validation-error';
            error.textContent = 'Konten materi wajib diisi (minimal 10 karakter)';
            document.getElementById('quill-editor').parentNode.appendChild(error);
        }
        return false;
    } else {
        if (errorContainer) {
            errorContainer.remove();
        }
        return true;
    }
}

function validateFile() {
    const input = document.getElementById('file');
    if (!input.files || input.files.length === 0) return true; // Optional field
    
    const file = input.files[0];
    const errorId = 'file-error';
    const maxSize = 10 * 1024 * 1024; // 10MB
    const allowedTypes = ['application/pdf', 'video/mp4', 'image/jpeg', 'image/png', 
                          'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                          'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'];
    
    if (file.size > maxSize) {
        showValidationError(input, errorId, 'Ukuran file maksimal 10MB');
        return false;
    }
    
    if (!allowedTypes.includes(file.type)) {
        showValidationError(input, errorId, 'Format file tidak didukung');
        return false;
    }
    
    hideValidationError(input, errorId);
    return true;
}


// Helper functions
function showValidationError(input, errorId, message) {
    // Add red border
    input.style.borderColor = '#ef4444';
    
    // Show/create error message
    let errorSpan = document.getElementById(errorId);
    if (!errorSpan) {
        errorSpan = document.createElement('span');
        errorSpan.id = errorId;
        errorSpan.className = 'validation-error';
        input.parentNode.appendChild(errorSpan);
    }
    errorSpan.textContent = message;
    errorSpan.style.display = 'block';
}

function hideValidationError(input, errorId) {
    // Reset border
    input.style.borderColor = '#333';
    
    // Hide error message
    const errorSpan = document.getElementById(errorId);
    if (errorSpan) {
        errorSpan.style.display = 'none';
    }
}

// Validate all before submit
function validateAllFields() {
    const validations = [
        validateTitle(),
        validateSubject(),
        validateClass(),
        validateContent(),
        validateFile()
    ];
    
    return validations.every(v => v === true);
}

// Initialize validation event listeners
function initializeValidation() {
    // Real-time validation on blur
    document.getElementById('title').addEventListener('blur', validateTitle);
    document.getElementById('title').addEventListener('input', validateTitle);
    
    document.getElementById('subject_id').addEventListener('change', validateSubject);
    document.getElementById('class_id').addEventListener('change', validateClass);
    
    document.getElementById('file').addEventListener('change', validateFile);
    
    // Form submit validation
    document.getElementById('materialForm').addEventListener('submit', function(e) {
        // Ensure content is updated before submission
        const contentTextarea = document.getElementById('content');
        contentTextarea.value = quill.root.innerHTML;
        
        if (!validateAllFields()) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi dengan benar');
            return false;
        }
    });

    // Restore material data dari old input jika ada
    @if(old('title') || old('content') || old('description'))
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Restoring old material data');
            
            // Restore basic fields
            if (document.getElementById('title') && '{{ old("title") }}') {
                document.getElementById('title').value = '{{ old("title") }}';
            }
            
            if (document.getElementById('description') && '{{ old("description") }}') {
                document.getElementById('description').value = '{{ old("description") }}';
            }
            
            if (document.getElementById('type') && '{{ old("type") }}') {
                document.getElementById('type').value = '{{ old("type") }}';
            }
            
            if (document.getElementById('class_id') && '{{ old("class_id") }}') {
                document.getElementById('class_id').value = '{{ old("class_id") }}';
            }
            
            if (document.getElementById('subject_id') && '{{ old("subject_id") }}') {
                document.getElementById('subject_id').value = '{{ old("subject_id") }}';
            }
            
            if (document.getElementById('status') && '{{ old("status") }}') {
                document.getElementById('status').value = '{{ old("status") }}';
            }
            
            // Restore Quill editor content
            if (quill && '{{ old("content") }}') {
                quill.root.innerHTML = '{!! old("content") !!}';
            }
            
            // Show notification that data was restored
            @if(session('error'))
                showNotification('Data Anda tersimpan! Silakan perbaiki error dan submit kembali. Data yang sudah Anda isi tidak hilang.', 'warning');
            @endif
        });
    @endif
}
</script>
@endsection
