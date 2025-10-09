@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - Edit Ujian')

@section('styles')
<style>
    .exam-edit-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .exam-edit-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
    }

    .exam-edit-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .exam-edit-subtitle {
        opacity: 0.9;
        font-size: 1rem;
    }

    .exam-edit-form {
        background: #1e293b;
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid #334155;
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

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        justify-content: flex-end;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
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

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    .exam-info {
        background: #2a2a3e;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid #667eea;
    }

    .exam-info h4 {
        color: #ffffff;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }

    .exam-info p {
        color: #cbd5e1;
        margin: 0.25rem 0;
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="exam-edit-container">
    <div class="exam-edit-header">
        <h1 class="exam-edit-title">
            <i class="fas fa-edit"></i>
            Edit Ujian
        </h1>
        <p class="exam-edit-subtitle">Ubah informasi ujian yang sudah dibuat</p>
    </div>

    <div class="exam-info">
        <h4><i class="fas fa-info-circle"></i> Informasi Ujian</h4>
        <p><strong>Judul:</strong> {{ $exam->name }}</p>
        <p><strong>Kelas:</strong> {{ $exam->KelasMapel->Kelas->name ?? 'N/A' }}</p>
        <p><strong>Mata Pelajaran:</strong> {{ $exam->KelasMapel->Mapel->name ?? 'N/A' }}</p>
        <p><strong>Tipe:</strong> 
            @if($exam->tipe == 1)
                Pilihan Ganda
            @elseif($exam->tipe == 2)
                Essay
            @else
                Campuran
            @endif
        </p>
        <p><strong>Status:</strong> 
            @if($exam->isHidden)
                <span style="color: #f59e0b;">Draft</span>
            @else
                <span style="color: #10b981;">Dipublikasikan</span>
            @endif
        </p>
    </div>

    <div class="exam-edit-form">
        <form action="{{ route('superadmin.exam-management.update', $exam->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="name">Judul Ujian</label>
                <input type="text" id="name" name="name" value="{{ $exam->name }}" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="class_id">Kelas</label>
                    <select id="class_id" name="class_id" required>
                        <option value="">Pilih kelas</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" 
                                {{ $exam->KelasMapel->kelas_id == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="subject_id">Mata Pelajaran</label>
                    <select id="subject_id" name="subject_id" required>
                        <option value="">Pilih mata pelajaran</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" 
                                {{ $exam->KelasMapel->mapel_id == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="time">Durasi (menit)</label>
                    <input type="number" id="time" name="time" value="{{ $exam->time }}" min="1" required>
                </div>
                
                <div class="form-group">
                    <label for="due">Tanggal Mulai</label>
                    <input type="datetime-local" id="due" name="due" 
                           value="{{ $exam->due ? $exam->due->format('Y-m-d\TH:i') : '' }}" required>
                </div>
            </div>

            <div class="form-group">
                <label for="content">Deskripsi Ujian</label>
                <textarea id="content" name="content" rows="4" placeholder="Masukkan deskripsi ujian">{{ $exam->content }}</textarea>
            </div>

            <div class="form-group">
                <label for="isHidden">Status</label>
                <select id="isHidden" name="isHidden">
                    <option value="1" {{ $exam->isHidden ? 'selected' : '' }}>Draft</option>
                    <option value="0" {{ !$exam->isHidden ? 'selected' : '' }}>Dipublikasikan</option>
                </select>
            </div>

            <div class="form-actions">
                <a href="{{ route('superadmin.exam-management') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const classId = document.getElementById('class_id').value;
    const subjectId = document.getElementById('subject_id').value;
    const time = document.getElementById('time').value;
    const due = document.getElementById('due').value;

    if (!name || !classId || !subjectId || !time || !due) {
        e.preventDefault();
        alert('Mohon lengkapi semua field yang wajib diisi');
        return;
    }

    if (parseInt(time) < 1) {
        e.preventDefault();
        alert('Durasi harus minimal 1 menit');
        return;
    }

    const dueDate = new Date(due);
    const now = new Date();
    
    if (dueDate <= now) {
        e.preventDefault();
        alert('Tanggal mulai harus lebih dari waktu sekarang');
        return;
    }
});

// Auto-save draft
let autoSaveTimeout;
function autoSave() {
    clearTimeout(autoSaveTimeout);
    autoSaveTimeout = setTimeout(() => {
        const formData = new FormData(document.querySelector('form'));
        const data = Object.fromEntries(formData);
        
        localStorage.setItem('exam_edit_draft', JSON.stringify(data));
        console.log('Draft tersimpan otomatis');
    }, 2000);
}

// Load draft on page load
document.addEventListener('DOMContentLoaded', function() {
    const savedDraft = localStorage.getItem('exam_edit_draft');
    if (savedDraft) {
        try {
            const draftData = JSON.parse(savedDraft);
            
            if (confirm('Ditemukan draft yang belum tersimpan. Apakah Anda ingin memuatnya?')) {
                Object.keys(draftData).forEach(key => {
                    const element = document.querySelector(`[name="${key}"]`);
                    if (element) {
                        element.value = draftData[key];
                    }
                });
            }
        } catch (e) {
            console.error('Error loading draft:', e);
        }
    }
});

// Add auto-save listeners
document.querySelectorAll('input, select, textarea').forEach(element => {
    element.addEventListener('input', autoSave);
    element.addEventListener('change', autoSave);
});

// Clear draft on successful save
document.querySelector('form').addEventListener('submit', function() {
    localStorage.removeItem('exam_edit_draft');
});
</script>
@endsection
