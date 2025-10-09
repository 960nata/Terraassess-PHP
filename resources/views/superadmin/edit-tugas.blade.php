@extends('layouts.unified-layout-new')

@section('title', $title)

@section('content')
<div class="superadmin-container">
    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-edit me-2"></i>Edit Tugas: {{ $tugas->name }}
        </h1>
        <p class="page-description">Edit tugas {{ $tipeTugas }} yang sudah ada</p>
    </div>

    <!-- Form -->
    <form action="{{ route('superadmin.tugas.update', $tugas->id) }}" method="POST" class="task-form">
        @csrf
        @method('PUT')
        
        <!-- Basic Information -->
        <div class="form-section">
            <h3 class="section-title">Informasi Dasar</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Nama Tugas</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $tugas->name) }}" required>
                </div>
                
                <div class="form-group">
                    <label for="due">Tanggal Deadline</label>
                    <input type="datetime-local" id="due" name="due" value="{{ old('due', $tugas->due ? \Carbon\Carbon::parse($tugas->due)->format('Y-m-d\TH:i') : '') }}">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="mapel_id">Mata Pelajaran</label>
                    <select id="mapel_id" name="mapel_id" required>
                        <option value="">Pilih Mata Pelajaran</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" 
                                {{ old('mapel_id', $tugas->KelasMapel->mapel_id) == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="kelas_id">Kelas</label>
                    <select id="kelas_id" name="kelas_id" required>
                        <option value="">Pilih Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" 
                                {{ old('kelas_id', $tugas->KelasMapel->kelas_id) == $k->id ? 'selected' : '' }}>
                                {{ $k->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="content">Deskripsi Tugas</label>
                @include('components.modern-quill-editor', [
                    'name' => 'content',
                    'content' => old('content', $tugas->content),
                    'placeholder' => 'Masukkan deskripsi tugas yang jelas dan detail...',
                    'height' => '250px',
                    'required' => true
                ])
            </div>
        </div>

        <!-- Additional Fields Based on Task Type -->
        @if($tipe == 1)
        <!-- Pilihan Ganda -->
        <div class="form-section">
            <h3 class="section-title">Konfigurasi Pilihan Ganda</h3>
            <p class="section-description">Buat soal-soal dengan pilihan A, B, C, D. Maksimal 100 soal.</p>
            
            <div id="questions-container">
                <!-- Soal akan ditambahkan secara dinamis -->
            </div>
            
            <div class="question-actions">
                <button type="button" id="add-question-btn" class="btn-add-question">
                    <i class="fas fa-plus"></i>
                    Tambah Soal
                </button>
                <button type="button" id="generate-questions-btn" class="btn-generate-questions">
                    <i class="fas fa-magic"></i>
                    Generate Soal Otomatis
                </button>
            </div>
        </div>
        @elseif($tipe == 2)
        <!-- Essay -->
        <div class="form-section">
            <h3 class="section-title">Konfigurasi Essay</h3>
            <p class="section-description">Buat pertanyaan essay yang akan dijawab siswa. Maksimal 10 soal.</p>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="total_points">Total Poin</label>
                    <input type="number" id="total_points" name="total_points" min="1" max="1000" value="{{ old('total_points', 100) }}" required>
                </div>
                
                <div class="form-group">
                    <label for="min_words">Minimal Kata</label>
                    <input type="number" id="min_words" name="min_words" min="50" max="5000" value="{{ old('min_words', 200) }}" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="rubrik_penilaian">Rubrik Penilaian (Opsional)</label>
                @include('components.modern-quill-editor', [
                    'name' => 'rubrik_penilaian',
                    'content' => old('rubrik_penilaian'),
                    'placeholder' => 'Tuliskan kriteria penilaian yang detail...',
                    'height' => '150px',
                    'required' => false
                ])
            </div>
        </div>
        @elseif($tipe == 3)
        <!-- Mandiri -->
        <div class="form-section">
            <h3 class="section-title">Konfigurasi Tugas Mandiri</h3>
            <p class="section-description">Berikan instruksi yang jelas dan detail untuk siswa.</p>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="tipe_submission">Tipe Pengumpulan</label>
                    <select id="tipe_submission" name="tipe_submission" required>
                        <option value="text" {{ old('tipe_submission', 'text') == 'text' ? 'selected' : '' }}>Teks</option>
                        <option value="file" {{ old('tipe_submission', 'file') == 'file' ? 'selected' : '' }}>File</option>
                        <option value="both" {{ old('tipe_submission', 'both') == 'both' ? 'selected' : '' }}>Teks + File</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="max_attempts">Maksimal Percobaan</label>
                    <input type="number" id="max_attempts" name="max_attempts" min="1" max="10" value="{{ old('max_attempts', 3) }}" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="instructions">Instruksi Detail</label>
                @include('components.modern-quill-editor', [
                    'name' => 'instructions',
                    'content' => old('instructions'),
                    'placeholder' => 'Berikan instruksi yang jelas dan detail...',
                    'height' => '200px',
                    'required' => true
                ])
            </div>
        </div>
        @elseif($tipe == 4)
        <!-- Kelompok -->
        <div class="form-section">
            <h3 class="section-title">Konfigurasi Tugas Kelompok</h3>
            <p class="section-description">Konfigurasi pengelolaan kelompok dan peran anggota.</p>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="min_anggota">Minimal Anggota</label>
                    <input type="number" id="min_anggota" name="min_anggota" min="2" max="10" value="{{ old('min_anggota', 2) }}" required>
                </div>
                
                <div class="form-group">
                    <label for="max_anggota">Maksimal Anggota</label>
                    <input type="number" id="max_anggota" name="max_anggota" min="2" max="10" value="{{ old('max_anggota', 5) }}" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="group_guidelines">Panduan Kelompok</label>
                @include('components.modern-quill-editor', [
                    'name' => 'group_guidelines',
                    'content' => old('group_guidelines'),
                    'placeholder' => 'Berikan panduan untuk kerja kelompok...',
                    'height' => '200px',
                    'required' => true
                ])
            </div>
        </div>
        @endif

        <!-- Form Actions -->
        <div class="form-actions">
            <a href="{{ route('superadmin.tugas.show', $tugas->id) }}" class="btn-cancel">
                <i class="fas fa-times"></i>
                Batal
            </a>
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<style>
/* Edit Task Styles */
.superadmin-container {
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 0.5rem;
}

.page-description {
    color: #718096;
    font-size: 1.1rem;
}

.task-form {
    background: rgba(30, 41, 59, 0.8);
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.form-section {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid rgba(51, 65, 85, 0.5);
}

.form-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #e2e8f0;
    margin-bottom: 1rem;
}

.section-description {
    color: #94a3b8;
    margin-bottom: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    font-weight: 500;
    color: #e2e8f0;
    margin-bottom: 0.5rem;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid rgba(51, 65, 85, 0.5);
    border-radius: 8px;
    background: rgba(15, 23, 42, 0.8);
    color: #e2e8f0;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #3b82f6;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(51, 65, 85, 0.5);
}

.btn-cancel {
    background: #6b7280;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-cancel:hover {
    background: #4b5563;
    transform: translateY(-1px);
}

.btn-primary {
    background: #3b82f6;
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary:hover {
    background: #2563eb;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
}

/* Responsive */
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
