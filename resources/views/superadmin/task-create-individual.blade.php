@extends('layouts.unified-layout')

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
    <form action="{{ route('superadmin.tasks.store') }}" method="POST" enctype="multipart/form-data" id="taskForm">
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
                    <label for="kelas_id">Kelas *</label>
                    <select id="kelas_id" name="kelas_id" required>
                        <option value="">Pilih Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->name }} - {{ $k->level }}
                            </option>
                        @endforeach
                    </select>
                    @error('kelas_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="mapel_id">Mata Pelajaran *</label>
                    <select id="mapel_id" name="mapel_id" required>
                        <option value="">Pilih Mata Pelajaran</option>
                        @foreach($mapel as $m)
                            <option value="{{ $m->id }}" {{ old('mapel_id') == $m->id ? 'selected' : '' }}>
                                {{ $m->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('mapel_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-group">
                <label for="description">Deskripsi Tugas *</label>
                <div class="bg-gray-700 rounded-lg border border-gray-600 overflow-hidden">
                    <div id="description-editor" class="quill-editor-dark" style="height: 200px;"></div>
                    <textarea name="description" id="description-textarea" style="display: none;">{{ old('description') }}</textarea>
                </div>
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
<!-- Quill Editor JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('taskForm');
    
    // Initialize Quill for description
    if (document.getElementById('description-editor')) {
        const descriptionEditor = new Quill('#description-editor', {
            theme: 'snow',
            placeholder: 'Masukkan deskripsi tugas yang jelas dan detail...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            }
        });
        descriptionEditor.on('text-change', function() {
            document.getElementById('description-textarea').value = descriptionEditor.root.innerHTML;
        });
        // Set initial content if old content exists
        if (document.getElementById('description-textarea').value) {
            descriptionEditor.root.innerHTML = document.getElementById('description-textarea').value;
        }
    }
    
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

<!-- Quill Editor CSS -->
<style>
/* Quill Editor Styles - From material-create.blade.php */
.ql-editor {
    color: #ffffff;
    background: #2a2a3e;
    min-height: 200px;
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

.quill-editor-dark .ql-editor {
    color: #ffffff;
    background: #2a2a3e;
    min-height: 120px;
}

.quill-editor-dark .ql-toolbar {
    background: #1e293b;
    border: 1px solid #334155;
    border-bottom: none;
}

.quill-editor-dark .ql-container {
    border: 1px solid #334155;
    border-top: none;
}

.quill-editor-dark .ql-snow .ql-picker {
    color: #ffffff;
}

.quill-editor-dark .ql-snow .ql-stroke {
    stroke: #ffffff;
}

.quill-editor-dark .ql-snow .ql-fill {
    fill: #ffffff;
}
</style>
@endsection
