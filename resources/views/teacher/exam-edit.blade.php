@extends('layouts.unified-layout')

@section('title', $title)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $title }}</h1>
                <p class="mt-2 text-gray-300">Edit ujian yang sudah dibuat</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('teacher.exam-management') }}" class="btn btn-outline">
                    <i class="ph-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <form id="examForm" method="POST" action="{{ route('teacher.exam-update', $ujian->id) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2">
                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Dasar</h3>
                        </div>
                        <div class="card-body space-y-4">
                            <div>
                                <label class="form-label">Judul Ujian *</label>
                                <input type="text" name="exam_title" class="form-input" 
                                       value="{{ old('exam_title', $ujian->name) }}" required>
                                @error('exam_title')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Deskripsi/Instruksi *</label>
                                <textarea name="exam_description" class="form-textarea" rows="4" 
                                          placeholder="Tuliskan instruksi yang jelas untuk siswa..." required>{{ old('exam_description', $ujian->content) }}</textarea>
                                @error('exam_description')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label">Kelas Tujuan *</label>
                                    <select name="class_id" class="form-select" required>
                                        <option value="">Pilih Kelas</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" 
                                                    {{ old('class_id', $ujian->kelasMapel->kelas_id) == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }} - {{ $class->level }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="form-label">Mata Pelajaran *</label>
                                    <select name="subject_id" class="form-select" required>
                                        <option value="">Pilih Mata Pelajaran</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" 
                                                    {{ old('subject_id', $ujian->kelasMapel->mapel_id) == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('subject_id')
                                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label">Durasi (menit) *</label>
                                    <input type="number" name="duration" class="form-input" 
                                           value="{{ old('duration', $ujian->time) }}" min="1" max="300" required>
                                    @error('duration')
                                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="form-label">Nilai Maksimal *</label>
                                    <input type="number" name="max_score" class="form-input" 
                                           value="{{ old('max_score', 100) }}" min="1" max="100" required>
                                    @error('max_score')
                                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Tanggal Tenggat *</label>
                                <input type="datetime-local" name="due_date" class="form-input" 
                                       value="{{ old('due_date', $ujian->due ? $ujian->due->format('Y-m-d\TH:i') : '') }}" required>
                                @error('due_date')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Tipe Ujian *</label>
                                <select name="exam_type" class="form-select" required>
                                    <option value="">Pilih Tipe Ujian</option>
                                    <option value="multiple_choice" {{ old('exam_type', $ujian->tipe == 1 ? 'multiple_choice' : '') == 'multiple_choice' ? 'selected' : '' }}>
                                        Pilihan Ganda
                                    </option>
                                    <option value="essay" {{ old('exam_type', $ujian->tipe == 2 ? 'essay' : '') == 'essay' ? 'selected' : '' }}>
                                        Essay
                                    </option>
                                    <option value="mixed" {{ old('exam_type', $ujian->tipe == 3 ? 'mixed' : '') == 'mixed' ? 'selected' : '' }}>
                                        Campuran
                                    </option>
                                </select>
                                @error('exam_type')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center space-x-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_hidden" value="1" 
                                           {{ old('is_hidden', $ujian->isHidden) ? 'checked' : '' }} 
                                           class="form-checkbox">
                                    <span class="ml-2 text-white">Sembunyikan ujian (draft)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Questions Section -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Soal Ujian</h3>
                            <p class="text-sm text-gray-400">Kelola soal yang sudah ada</p>
                        </div>
                        <div class="card-body">
                            @if($ujian->tipe == 1 || $ujian->tipe == 3)
                                <!-- Multiple Choice Questions -->
                                <div class="space-y-4">
                                    <h4 class="text-lg font-medium text-white">Soal Pilihan Ganda</h4>
                                    @php
                                        $multipleQuestions = $ujian->soalUjianMultiple;
                                    @endphp
                                    
                                    @if($multipleQuestions->count() > 0)
                                        @foreach($multipleQuestions as $index => $question)
                                            <div class="bg-gray-800 rounded-lg p-4">
                                                <div class="flex justify-between items-center mb-3">
                                                    <h5 class="text-white font-medium">Soal {{ $index + 1 }}</h5>
                                                    <span class="text-sm text-gray-400">{{ $question->poin }} poin</span>
                                                </div>
                                                <p class="text-gray-300 mb-3">{{ $question->soal }}</p>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <div class="text-sm {{ $question->jawaban == 'A' ? 'text-green-400' : 'text-gray-400' }}">
                                                        A. {{ $question->a }}
                                                    </div>
                                                    <div class="text-sm {{ $question->jawaban == 'B' ? 'text-green-400' : 'text-gray-400' }}">
                                                        B. {{ $question->b }}
                                                    </div>
                                                    <div class="text-sm {{ $question->jawaban == 'C' ? 'text-green-400' : 'text-gray-400' }}">
                                                        C. {{ $question->c }}
                                                    </div>
                                                    <div class="text-sm {{ $question->jawaban == 'D' ? 'text-green-400' : 'text-gray-400' }}">
                                                        D. {{ $question->d }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-gray-400">Belum ada soal pilihan ganda</p>
                                    @endif
                                </div>
                            @endif

                            @if($ujian->tipe == 2 || $ujian->tipe == 3)
                                <!-- Essay Questions -->
                                <div class="space-y-4 mt-6">
                                    <h4 class="text-lg font-medium text-white">Soal Essay</h4>
                                    @php
                                        $essayQuestions = $ujian->soalUjianEssay;
                                    @endphp
                                    
                                    @if($essayQuestions->count() > 0)
                                        @foreach($essayQuestions as $index => $question)
                                            <div class="bg-gray-800 rounded-lg p-4">
                                                <div class="flex justify-between items-center mb-3">
                                                    <h5 class="text-white font-medium">Soal {{ $index + 1 }}</h5>
                                                    <span class="text-sm text-gray-400">{{ $question->poin }} poin</span>
                                                </div>
                                                <p class="text-gray-300">{{ $question->soal }}</p>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-gray-400">Belum ada soal essay</p>
                                    @endif
                                </div>
                            @endif

                            <div class="mt-6 p-4 bg-blue-900/20 border border-blue-500/30 rounded-lg">
                                <div class="flex items-center space-x-2 mb-2">
                                    <i class="ph-info text-blue-400"></i>
                                    <span class="text-blue-300 font-medium">Catatan</span>
                                </div>
                                <p class="text-sm text-blue-200">
                                    Untuk mengedit soal, gunakan fitur "Kelola Soal" di halaman detail ujian. 
                                    Perubahan pada informasi dasar akan disimpan terlebih dahulu.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Save Actions -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Aksi</h3>
                    </div>
                    <div class="card-body space-y-3">
                        <button type="submit" class="btn btn-primary w-full">
                            <i class="ph-check mr-2"></i>
                            Simpan Perubahan
                        </button>
                        
                        <a href="{{ route('teacher.exam-detail', $ujian->id) }}" class="btn btn-outline w-full">
                            <i class="ph-eye mr-2"></i>
                            Lihat Detail
                        </a>
                        
                        <a href="{{ route('teacher.exam-management') }}" class="btn btn-outline w-full">
                            <i class="ph-arrow-left mr-2"></i>
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>

                <!-- Exam Info -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Ujian</h3>
                    </div>
                    <div class="card-body space-y-3">
                        <div>
                            <label class="text-sm text-gray-400">ID Ujian</label>
                            <div class="text-white font-mono">{{ $ujian->id }}</div>
                        </div>
                        
                        <div>
                            <label class="text-sm text-gray-400">Dibuat</label>
                            <div class="text-white">{{ $ujian->created_at->format('d M Y, H:i') }}</div>
                        </div>
                        
                        <div>
                            <label class="text-sm text-gray-400">Terakhir Diupdate</label>
                            <div class="text-white">{{ $ujian->updated_at->format('d M Y, H:i') }}</div>
                        </div>
                        
                        <div>
                            <label class="text-sm text-gray-400">Status</label>
                            <div class="text-white">
                                @if($ujian->isHidden)
                                    <span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 rounded text-xs">Draft</span>
                                @else
                                    <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-xs">Dipublikasi</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: #94a3b8;
    margin-bottom: 0.5rem;
}

.form-input, .form-select, .form-textarea {
    width: 100%;
    padding: 0.75rem;
    background: #374151;
    border: 1px solid #4b5563;
    border-radius: 0.5rem;
    color: white;
    font-size: 0.875rem;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-checkbox {
    width: 1rem;
    height: 1rem;
    background: #374151;
    border: 1px solid #4b5563;
    border-radius: 0.25rem;
}

.card {
    background: #1f2937;
    border-radius: 0.75rem;
    border: 1px solid #374151;
}

.card-header {
    padding: 1.5rem 1.5rem 0 1.5rem;
    border-bottom: 1px solid #374151;
    padding-bottom: 1rem;
    margin-bottom: 1.5rem;
}

.card-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: white;
}

.card-body {
    padding: 0 1.5rem 1.5rem 1.5rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-primary {
    background: #3b82f6;
    color: white;
    border: 1px solid #3b82f6;
}

.btn-primary:hover {
    background: #2563eb;
    border-color: #2563eb;
}

.btn-outline {
    background: transparent;
    color: #94a3b8;
    border: 1px solid #4b5563;
}

.btn-outline:hover {
    background: #374151;
    color: white;
    border-color: #6b7280;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.getElementById('examForm');
    const examTypeSelect = document.querySelector('select[name="exam_type"]');
    
    // Update form based on exam type
    examTypeSelect.addEventListener('change', function() {
        // You can add dynamic behavior here if needed
        console.log('Exam type changed to:', this.value);
    });
    
    // Form submission
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('border-red-500');
            } else {
                field.classList.remove('border-red-500');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi.');
        }
    });
});
</script>
@endsection
