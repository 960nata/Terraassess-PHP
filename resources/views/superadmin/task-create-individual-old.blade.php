@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - Buat Tugas Mandiri')

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
<div class="main-content">
        <div class="container">
            <!-- Header -->
            <div class="page-header">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white">Buat Tugas Mandiri</h1>
                        <p class="mt-2 text-gray-300">Buat tugas individual dengan opsi upload file atau ketik langsung</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('superadmin.tugas.index') }}" class="btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Task Form -->
            <div class="task-form">
                <form id="individualForm" method="POST" action="{{ route('superadmin.tugas.index.create') }}">
                    @csrf
                    <input type="hidden" name="tipe" value="3">

                    <!-- Basic Information -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-info-circle"></i>
                            Informasi Dasar
                        </h3>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name">Judul Tugas *</label>
                                <input type="text" id="name" name="name" 
                                       value="{{ old('name') }}" required
                                       placeholder="Contoh: Laporan Praktikum Kimia">
                                @error('name')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="kelas_mapel_id">Kelas & Mata Pelajaran *</label>
                                <select id="kelas_mapel_id" name="kelas_mapel_id" required>
                                    <option value="">Pilih Kelas & Mata Pelajaran</option>
                                    @foreach($kelas as $k)
                                        @foreach($k->KelasMapel as $km)
                                            @if($km->Mapel)
                                                <option value="{{ $km->id }}" {{ old('kelas_mapel_id') == $km->id ? 'selected' : '' }}>
                                                    {{ $k->name }} - {{ $km->Mapel->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </select>
                                @error('kelas_mapel_id')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="content">Deskripsi/Instruksi *</label>
                            <textarea id="content" name="content" rows="6" 
                                      placeholder="Tuliskan instruksi yang jelas untuk siswa..." required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Task Settings -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-cog"></i>
                            Pengaturan Tugas
                        </h3>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="due_date">Tanggal Deadline</label>
                                <input type="datetime-local" id="due_date" name="due_date" 
                                       value="{{ old('due_date') }}">
                                @error('due_date')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="max_score">Nilai Maksimal</label>
                                <input type="number" id="max_score" name="max_score" 
                                       value="{{ old('max_score', 100) }}" min="1" max="100">
                                @error('max_score')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="isHidden" value="1" {{ old('isHidden') ? 'checked' : '' }}>
                                Simpan sebagai draft (tidak langsung dipublikasikan)
                            </label>
                        </div>
                    </div>

                    <!-- File Upload Section -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-paperclip"></i>
                            File Pendukung (Opsional)
                        </h3>
                        
                        <div class="file-upload" id="fileUpload">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: #667eea; margin-bottom: 1rem;"></i>
                            <p class="text-white mb-2">Drag & drop file di sini atau klik untuk memilih</p>
                            <p class="text-gray-400 text-sm">Maksimal 10MB per file. Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG</p>
                            <input type="file" id="fileInput" name="files[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png" style="display: none;">
                        </div>

                        <div class="file-list" id="fileList"></div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('superadmin.tugas.index') }}" class="btn-secondary">
                            <i class="fas fa-times"></i>
                            Batal
                        </a>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i>
                            Buat Tugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // File upload handling
        const fileUpload = document.getElementById('fileUpload');
        const fileInput = document.getElementById('fileInput');
        const fileList = document.getElementById('fileList');

        fileUpload.addEventListener('click', () => fileInput.click());
        fileUpload.addEventListener('dragover', handleDragOver);
        fileUpload.addEventListener('dragleave', handleDragLeave);
        fileUpload.addEventListener('drop', handleDrop);
        fileInput.addEventListener('change', handleFileSelect);

        function handleDragOver(e) {
            e.preventDefault();
            fileUpload.classList.add('dragover');
        }

        function handleDragLeave(e) {
            e.preventDefault();
            fileUpload.classList.remove('dragover');
        }

        function handleDrop(e) {
            e.preventDefault();
            fileUpload.classList.remove('dragover');
            const files = e.dataTransfer.files;
            handleFiles(files);
        }

        function handleFileSelect(e) {
            const files = e.target.files;
            handleFiles(files);
        }

        function handleFiles(files) {
            Array.from(files).forEach(file => {
                if (validateFile(file)) {
                    addFileToList(file);
                }
            });
        }

        function validateFile(file) {
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
                alert('File terlalu besar. Maksimal 10MB.');
                return false;
            }

            if (!allowedTypes.includes(file.type)) {
                alert('Format file tidak didukung.');
                return false;
            }

            return true;
        }

        function addFileToList(file) {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item';
            fileItem.innerHTML = `
                <div class="file-info">
                    <i class="fas fa-file"></i>
                    <span>${file.name}</span>
                    <span class="text-gray-400">(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
                </div>
                <div class="file-remove" onclick="removeFile(this)">
                    <i class="fas fa-times"></i>
                </div>
            `;
            fileList.appendChild(fileItem);
        }

        function removeFile(element) {
            element.parentElement.remove();
        }

        // Form validation
        document.getElementById('individualForm').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const content = document.getElementById('content').value.trim();
            const kelasMapel = document.getElementById('kelas_mapel_id').value;

            if (!name || !content || !kelasMapel) {
                e.preventDefault();
                alert('Harap lengkapi semua field yang wajib diisi.');
                return;
            }
        });
    </script>
@endsection
