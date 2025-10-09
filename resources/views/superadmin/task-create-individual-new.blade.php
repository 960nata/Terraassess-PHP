@extends('layouts.unified-layout-new')

@section('title', 'Buat Tugas Mandiri')

@section('styles')
<style>
    .task-form {
        background-color: #1e293b;
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid #334155;
    }

    .form-section {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: #2a2a3e;
        border-radius: 12px;
        border: 1px solid #334155;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #ffffff;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        color: #667eea;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #ffffff;
        font-size: 0.9rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        background: #2a2a3e;
        border: 2px solid #333;
        border-radius: 8px;
        color: #ffffff;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #667eea;
        background: #333;
    }

    .form-group textarea {
        min-height: 120px;
        resize: vertical;
    }

    .form-group textarea::placeholder {
        color: #666;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }

    .btn-secondary {
        background: #374151;
        color: white;
        border: 1px solid #4b5563;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-secondary:hover {
        background: #4b5563;
        transform: translateY(-1px);
    }

    .file-upload {
        border: 2px dashed #4b5563;
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        background: #2a2a3e;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .file-upload:hover {
        border-color: #667eea;
        background: #333;
    }

    .file-upload.dragover {
        border-color: #667eea;
        background: #333;
    }

    .file-list {
        margin-top: 1rem;
    }

    .file-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem;
        background: #374151;
        border-radius: 8px;
        margin-bottom: 0.5rem;
    }

    .file-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .file-remove {
        color: #ef4444;
        cursor: pointer;
        padding: 0.25rem;
    }

    .file-remove:hover {
        color: #dc2626;
    }

    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .success-message {
        color: #10b981;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .task-form {
            padding: 1rem;
        }
        
        .form-section {
            padding: 1rem;
        }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-user-plus"></i>
        Buat Tugas Mandiri
    </h1>
    <p class="page-description">Buat tugas individual untuk siswa</p>
</div>

<div class="task-form">
    <form action="{{ route('superadmin.task-create-individual.store') }}" method="POST" enctype="multipart/form-data" id="taskForm">
        @csrf
        
        <!-- Basic Information Section -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-info-circle"></i>
                Informasi Dasar
            </h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="title">Judul Tugas *</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="class_id">Kelas *</label>
                    <select id="class_id" name="class_id" required>
                        <option value="">Pilih Kelas</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('class_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-group">
                <label for="description">Deskripsi Tugas *</label>
                <textarea id="description" name="description" placeholder="Masukkan deskripsi tugas yang jelas dan detail..." required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Task Details Section -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-tasks"></i>
                Detail Tugas
            </h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="difficulty">Tingkat Kesulitan *</label>
                    <select id="difficulty" name="difficulty" required>
                        <option value="">Pilih Tingkat Kesulitan</option>
                        <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>Mudah</option>
                        <option value="medium" {{ old('difficulty') == 'medium' ? 'selected' : '' }}>Sedang</option>
                        <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>Sulit</option>
                    </select>
                    @error('difficulty')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="max_score">Nilai Maksimal *</label>
                    <input type="number" id="max_score" name="max_score" value="{{ old('max_score', 100) }}" min="1" max="100" required>
                    @error('max_score')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="start_date">Tanggal Mulai *</label>
                    <input type="datetime-local" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                    @error('start_date')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="end_date">Tanggal Selesai *</label>
                    <input type="datetime-local" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                    @error('end_date')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- File Upload Section -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-paperclip"></i>
                Lampiran (Opsional)
            </h3>
            
            <div class="file-upload" id="fileUpload">
                <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: #667eea; margin-bottom: 1rem;"></i>
                <p>Drag & drop file di sini atau klik untuk memilih file</p>
                <p style="font-size: 0.875rem; color: #94a3b8; margin-top: 0.5rem;">
                    Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG (Max: 10MB)
                </p>
                <input type="file" id="fileInput" name="attachments[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png" style="display: none;">
            </div>
            
            <div class="file-list" id="fileList"></div>
        </div>

        <!-- Instructions Section -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-list-ol"></i>
                Instruksi Pengerjaan
            </h3>
            
            <div class="form-group">
                <label for="instructions">Instruksi Detail *</label>
                <textarea id="instructions" name="instructions" placeholder="Masukkan instruksi pengerjaan yang jelas dan terstruktur..." required>{{ old('instructions') }}</textarea>
                @error('instructions')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
            <a href="{{ route('superadmin.tugas.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i>
                Simpan Tugas
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileUpload = document.getElementById('fileUpload');
    const fileInput = document.getElementById('fileInput');
    const fileList = document.getElementById('fileList');
    const form = document.getElementById('taskForm');
    
    let files = [];
    
    // File upload click handler
    fileUpload.addEventListener('click', () => {
        fileInput.click();
    });
    
    // File input change handler
    fileInput.addEventListener('change', handleFiles);
    
    // Drag and drop handlers
    fileUpload.addEventListener('dragover', (e) => {
        e.preventDefault();
        fileUpload.classList.add('dragover');
    });
    
    fileUpload.addEventListener('dragleave', () => {
        fileUpload.classList.remove('dragover');
    });
    
    fileUpload.addEventListener('drop', (e) => {
        e.preventDefault();
        fileUpload.classList.remove('dragover');
        handleFiles({ target: { files: e.dataTransfer.files } });
    });
    
    function handleFiles(e) {
        const newFiles = Array.from(e.target.files);
        
        // Validate file size and type
        const validFiles = newFiles.filter(file => {
            const maxSize = 10 * 1024 * 1024; // 10MB
            const allowedTypes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'image/jpeg',
                'image/png'
            ];
            
            if (file.size > maxSize) {
                alert(`File ${file.name} terlalu besar. Maksimal 10MB.`);
                return false;
            }
            
            if (!allowedTypes.includes(file.type)) {
                alert(`File ${file.name} tidak didukung.`);
                return false;
            }
            
            return true;
        });
        
        files = [...files, ...validFiles];
        updateFileList();
    }
    
    function updateFileList() {
        fileList.innerHTML = '';
        
        files.forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item';
            fileItem.innerHTML = `
                <div class="file-info">
                    <i class="fas fa-file"></i>
                    <span>${file.name}</span>
                    <span style="color: #94a3b8; font-size: 0.875rem;">(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
                </div>
                <div class="file-remove" onclick="removeFile(${index})">
                    <i class="fas fa-times"></i>
                </div>
            `;
            fileList.appendChild(fileItem);
        });
    }
    
    window.removeFile = function(index) {
        files.splice(index, 1);
        updateFileList();
    };
    
    // Form submission
    form.addEventListener('submit', function(e) {
        // Add files to form data
        const formData = new FormData(form);
        
        // Clear existing file inputs
        const existingFiles = form.querySelectorAll('input[type="file"]');
        existingFiles.forEach(input => {
            if (input.name === 'attachments[]') {
                input.remove();
            }
        });
        
        // Add new file inputs
        files.forEach((file, index) => {
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.name = 'attachments[]';
            fileInput.style.display = 'none';
            
            // Create a new FileList with the file
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
            
            form.appendChild(fileInput);
        });
    });
    
    // Date validation
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    
    startDate.addEventListener('change', function() {
        if (endDate.value && new Date(this.value) >= new Date(endDate.value)) {
            endDate.value = '';
            alert('Tanggal selesai harus setelah tanggal mulai.');
        }
    });
    
    endDate.addEventListener('change', function() {
        if (startDate.value && new Date(this.value) <= new Date(startDate.value)) {
            this.value = '';
            alert('Tanggal selesai harus setelah tanggal mulai.');
        }
    });
});
</script>
@endsection
