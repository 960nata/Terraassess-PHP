@extends('layouts.unified-layout')

@section('title', $title)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $title }}</h1>
                <p class="mt-2 text-gray-300">Buat pengaduan baru untuk keluhan atau masalah yang Anda hadapi</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('student.complaints.index') }}" class="btn btn-outline">
                    <i class="ph-arrow-left mr-2"></i>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Pengaduan</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('student.complaints.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="space-y-6">
                            <!-- Category -->
                            <div>
                                <label class="form-label">Kategori Pengaduan *</label>
                                <select name="category" class="form-select" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="akademik" {{ old('category') == 'akademik' ? 'selected' : '' }}>Akademik</option>
                                    <option value="fasilitas" {{ old('category') == 'fasilitas' ? 'selected' : '' }}>Fasilitas</option>
                                    <option value="bullying" {{ old('category') == 'bullying' ? 'selected' : '' }}>Bullying</option>
                                    <option value="lainnya" {{ old('category') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('category')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Subject -->
                            <div>
                                <label class="form-label">Judul Pengaduan *</label>
                                <input type="text" name="subject" class="form-input" 
                                       value="{{ old('subject') }}" 
                                       placeholder="Masukkan judul pengaduan yang jelas dan singkat"
                                       required>
                                @error('subject')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Message -->
                            <div>
                                <label class="form-label">Isi Pengaduan *</label>
                                <div id="quill-editor" style="height: 200px; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 8px;"></div>
                                <textarea name="message" id="message" style="display: none;" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- File Upload -->
                            <div>
                                <label class="form-label">Lampiran (Opsional)</label>
                                <div class="file-upload-zone" id="file-upload-zone">
                                    <div class="file-upload-content">
                                        <i class="ph-upload text-4xl text-gray-400 mb-4"></i>
                                        <p class="text-gray-300 mb-2">Drag & drop file di sini atau klik untuk memilih</p>
                                        <p class="text-xs text-gray-500">Maksimal 5MB per file. Format: JPG, PNG, GIF, PDF, DOC, DOCX</p>
                                        <input type="file" name="attachments[]" id="file-input" multiple accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx" style="display: none;">
                                        <button type="button" class="btn btn-outline btn-sm mt-3" onclick="document.getElementById('file-input').click()">
                                            Pilih File
                                        </button>
                                    </div>
                                </div>
                                <div id="file-preview" class="mt-4 space-y-2"></div>
                                @error('attachments.*')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-center space-x-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ph-paper-plane mr-2"></i>
                                    Kirim Pengaduan
                                </button>
                                <a href="{{ route('student.complaints.index') }}" class="btn btn-outline">
                                    Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="card sticky top-6">
                <div class="card-header">
                    <h3 class="card-title">Panduan Pengaduan</h3>
                </div>
                <div class="card-body space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-white mb-2">Kategori Pengaduan:</h4>
                        <ul class="text-xs text-gray-400 space-y-1">
                            <li>• <strong>Akademik:</strong> Masalah pembelajaran, tugas, nilai</li>
                            <li>• <strong>Fasilitas:</strong> Masalah sarana dan prasarana</li>
                            <li>• <strong>Bullying:</strong> Pelecehan atau intimidasi</li>
                            <li>• <strong>Lainnya:</strong> Masalah lainnya</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-white mb-2">Tips Menulis Pengaduan:</h4>
                        <ul class="text-xs text-gray-400 space-y-1">
                            <li>• Gunakan bahasa yang sopan dan jelas</li>
                            <li>• Sertakan detail waktu dan tempat kejadian</li>
                            <li>• Jelaskan dampak masalah terhadap Anda</li>
                            <li>• Berikan saran solusi jika memungkinkan</li>
                        </ul>
                    </div>

                    <div class="border-t border-gray-600 pt-4">
                        <h4 class="text-sm font-medium text-white mb-2">Proses Penanganan:</h4>
                        <ul class="text-xs text-gray-400 space-y-1">
                            <li>• Pengaduan akan ditinjau dalam 1-2 hari kerja</li>
                            <li>• Status akan diperbarui secara berkala</li>
                            <li>• Anda akan mendapat notifikasi balasan</li>
                            <li>• Masalah akan ditindaklanjuti sesuai prioritas</li>
                        </ul>
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

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: white;
    margin-bottom: 0.5rem;
}

.form-input, .form-select, .form-textarea {
    width: 100%;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    color: white;
    font-size: 0.875rem;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-input::placeholder, .form-textarea::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

.file-upload-zone {
    border: 2px dashed rgba(255, 255, 255, 0.3);
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.file-upload-zone:hover {
    border-color: #3b82f6;
    background: rgba(59, 130, 246, 0.05);
}

.file-upload-zone.dragover {
    border-color: #3b82f6;
    background: rgba(59, 130, 246, 0.1);
}

.file-preview-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
}

.file-preview-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.file-icon {
    width: 2rem;
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
}

.file-details {
    flex: 1;
}

.file-name {
    font-size: 0.875rem;
    color: white;
    font-weight: 500;
}

.file-size {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
}

.file-remove {
    color: #ef4444;
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 4px;
    transition: background 0.2s;
}

.file-remove:hover {
    background: rgba(239, 68, 68, 0.1);
}

/* Quill Editor Dark Theme */
.ql-toolbar {
    border-top: 1px solid rgba(255, 255, 255, 0.2) !important;
    border-left: 1px solid rgba(255, 255, 255, 0.2) !important;
    border-right: 1px solid rgba(255, 255, 255, 0.2) !important;
    background: rgba(255, 255, 255, 0.05) !important;
}

.ql-container {
    border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
    border-left: 1px solid rgba(255, 255, 255, 0.2) !important;
    border-right: 1px solid rgba(255, 255, 255, 0.2) !important;
    background: rgba(255, 255, 255, 0.05) !important;
}

.ql-editor {
    color: white !important;
}

.ql-editor.ql-blank::before {
    color: rgba(255, 255, 255, 0.5) !important;
}

.ql-toolbar .ql-stroke {
    stroke: rgba(255, 255, 255, 0.7) !important;
}

.ql-toolbar .ql-fill {
    fill: rgba(255, 255, 255, 0.7) !important;
}

.ql-toolbar button:hover .ql-stroke {
    stroke: #3b82f6 !important;
}

.ql-toolbar button:hover .ql-fill {
    fill: #3b82f6 !important;
}

.ql-toolbar button.ql-active .ql-stroke {
    stroke: #3b82f6 !important;
}

.ql-toolbar button.ql-active .ql-fill {
    fill: #3b82f6 !important;
}
</style>

<!-- Quill Editor -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Quill Editor
    const quill = new Quill('#quill-editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    // Set initial content if exists
    const messageTextarea = document.getElementById('message');
    if (messageTextarea.value) {
        quill.root.innerHTML = messageTextarea.value;
    }

    // Update textarea when form is submitted
    document.querySelector('form').addEventListener('submit', function() {
        messageTextarea.value = quill.root.innerHTML;
    });

    // File upload functionality
    const fileInput = document.getElementById('file-input');
    const fileUploadZone = document.getElementById('file-upload-zone');
    const filePreview = document.getElementById('file-preview');
    const maxFileSize = 5 * 1024 * 1024; // 5MB
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

    let selectedFiles = [];

    // Drag and drop functionality
    fileUploadZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        fileUploadZone.classList.add('dragover');
    });

    fileUploadZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        fileUploadZone.classList.remove('dragover');
    });

    fileUploadZone.addEventListener('drop', function(e) {
        e.preventDefault();
        fileUploadZone.classList.remove('dragover');
        handleFiles(e.dataTransfer.files);
    });

    fileUploadZone.addEventListener('click', function() {
        fileInput.click();
    });

    fileInput.addEventListener('change', function() {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        Array.from(files).forEach(file => {
            // Validate file size
            if (file.size > maxFileSize) {
                alert(`File ${file.name} terlalu besar. Maksimal 5MB.`);
                return;
            }

            // Validate file type
            if (!allowedTypes.includes(file.type)) {
                alert(`File ${file.name} tidak didukung. Format yang diizinkan: JPG, PNG, GIF, PDF, DOC, DOCX.`);
                return;
            }

            // Add to selected files if not already exists
            if (!selectedFiles.find(f => f.name === file.name && f.size === file.size)) {
                selectedFiles.push(file);
                updateFilePreview();
            }
        });
    }

    function updateFilePreview() {
        filePreview.innerHTML = '';
        
        selectedFiles.forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-preview-item';
            
            const fileIcon = getFileIcon(file.type);
            const fileSize = formatFileSize(file.size);
            
            fileItem.innerHTML = `
                <div class="file-preview-info">
                    <div class="file-icon ${fileIcon.color}">
                        <i class="${fileIcon.icon}"></i>
                    </div>
                    <div class="file-details">
                        <div class="file-name">${file.name}</div>
                        <div class="file-size">${fileSize}</div>
                    </div>
                </div>
                <div class="file-remove" onclick="removeFile(${index})">
                    <i class="ph-x"></i>
                </div>
            `;
            
            filePreview.appendChild(fileItem);
        });

        // Update file input
        updateFileInput();
    }

    function getFileIcon(type) {
        if (type.startsWith('image/')) {
            return { icon: 'ph-image', color: 'text-green-500' };
        } else if (type === 'application/pdf') {
            return { icon: 'ph-file-pdf', color: 'text-red-500' };
        } else if (type.includes('word')) {
            return { icon: 'ph-file-doc', color: 'text-blue-500' };
        } else {
            return { icon: 'ph-file', color: 'text-gray-500' };
        }
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function updateFileInput() {
        // Create new file input with selected files
        const newFileInput = document.createElement('input');
        newFileInput.type = 'file';
        newFileInput.name = 'attachments[]';
        newFileInput.multiple = true;
        newFileInput.style.display = 'none';
        
        // Create DataTransfer object to set files
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => {
            dataTransfer.items.add(file);
        });
        newFileInput.files = dataTransfer.files;
        
        // Replace old input
        const oldInput = document.getElementById('file-input');
        oldInput.parentNode.replaceChild(newFileInput, oldInput);
        newFileInput.id = 'file-input';
    }

    // Global function to remove file
    window.removeFile = function(index) {
        selectedFiles.splice(index, 1);
        updateFilePreview();
    };
});
</script>
@endsection
