@extends('layouts.unified-layout-new')

@section('title', 'Buat Tugas Pilihan Ganda')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Buat Tugas Pilihan Ganda</h1>
                <p class="mt-2 text-gray-300 text-sm sm:text-base">Buat kuis dengan jawaban A, B, C, D yang dapat dinilai otomatis</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('teacher.tasks.management') }}" class="btn btn-outline text-sm sm:text-base">
                    <i class="ph-arrow-left mr-2"></i>
                    Kembali
                </a>
                <button type="submit" form="multipleChoiceForm" class="btn btn-primary text-sm sm:text-base">
                    <i class="ph-check mr-2"></i>
                    Buat Tugas
                </button>
                <a href="{{ route('teacher.tasks.management') }}" class="btn btn-outline text-sm sm:text-base">
                    <i class="ph-x mr-2"></i>
                    Batal
                </a>
            </div>
        </div>
    </div>

    <form id="multipleChoiceForm" method="POST" action="{{ route('teacher.tasks.store') }}">
        @csrf
        <input type="hidden" name="tipe" value="1">

        <div class="max-w-6xl mx-auto">
            <div class="space-y-6">
                    <!-- Basic Information -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Dasar</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Kolom 1 -->
                                <div class="space-y-4">
                                    <div>
                                        <label class="form-label">Judul Tugas *</label>
                                        <input type="text" name="name" class="form-input" 
                                               value="{{ old('name') }}" required
                                               placeholder="Contoh: Kuis Matematika Kelas 10">
                                        @error('name')
                                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="form-label">Kelas Tujuan *</label>
                                        <select name="kelas_id" class="form-select" required>
                                            <option value="">Pilih Kelas</option>
                                            @foreach($kelas as $k)
                                                <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                                    {{ $k->name }} - {{ $k->level }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('kelas_id')
                                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="form-label">Tanggal Tenggat</label>
                                        <input type="datetime-local" name="due" class="form-input" 
                                               value="{{ old('due') }}">
                                        @error('due')
                                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Kolom 2 -->
                                <div class="space-y-4">
                                    <div>
                                        <label class="form-label">Deskripsi/Instruksi *</label>
                                        <textarea name="content" class="form-textarea" rows="4" 
                                                  placeholder="Tuliskan instruksi yang jelas untuk siswa..." required>{{ old('content') }}</textarea>
                                        @error('content')
                                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="form-label">Mata Pelajaran *</label>
                                        <select name="mapel_id" class="form-select" required>
                                            <option value="">Pilih Mata Pelajaran</option>
                                            @foreach($mapel as $m)
                                                <option value="{{ $m->id }}" {{ old('mapel_id') == $m->id ? 'selected' : '' }}>
                                                    {{ $m->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('mapel_id')
                                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Questions Section -->
                    <div class="card">
                        <div class="card-header">
                            <div class="flex items-center justify-between">
                                <h3 class="card-title">Soal Pilihan Ganda</h3>
                                <button type="button" onclick="addQuestion()" class="btn btn-primary btn-sm">
                                    <i class="ph-plus mr-1"></i>
                                    Tambah Soal
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="questionsContainer">
                                <!-- Questions will be added here dynamically -->
                            </div>
                            
                            <div class="mt-6">
                                <button type="button" onclick="addQuestion()" class="btn btn-primary w-full py-3 text-lg">
                                    <i class="ph-plus mr-2"></i>
                                    + Tambah Pertanyaan Baru
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Help & Tips Section -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Bantuan & Tips</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-300 mb-3">Tips Membuat Soal:</h4>
                                    <ul class="text-sm text-gray-400 space-y-2">
                                        <li>• Buat soal yang jelas dan tidak ambigu</li>
                                        <li>• Pastikan ada satu jawaban yang benar</li>
                                        <li>• Berikan poin yang sesuai dengan tingkat kesulitan</li>
                                        <li>• Gunakan pilihan yang masuk akal</li>
                                        <li>• Hindari pilihan "Semua benar" atau "Tidak ada yang benar"</li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-300 mb-3">Statistik:</h4>
                                    <div class="space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-400">Total Soal:</span>
                                            <span class="text-white" id="totalQuestions">0</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-400">Total Poin:</span>
                                            <span class="text-white" id="totalPoints">0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    overflow: hidden;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem;
    }
    
    .card-header {
        padding: 0.75rem 1rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .card-title {
        font-size: 1rem;
    }
    
    .form-label {
        font-size: 0.8rem;
    }
    
    .form-input, .form-select, .form-textarea {
        padding: 0.6rem;
        font-size: 0.8rem;
    }
    
    .question-block {
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .question-number {
        font-size: 1rem;
    }
    
    .option-item {
        padding: 0.6rem;
        gap: 0.5rem;
    }
    
    .option-item input[type="text"] {
        font-size: 0.8rem;
    }
    
    .btn {
        padding: 0.6rem 1rem;
        font-size: 0.8rem;
    }
    
    .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.75rem;
    }
}

@media (max-width: 480px) {
    .container-fluid {
        padding: 0.5rem;
    }
    
    .card-header {
        padding: 0.6rem 0.8rem;
    }
    
    .card-body {
        padding: 0.8rem;
    }
    
    .question-block {
        padding: 0.8rem;
    }
    
    .option-item {
        padding: 0.5rem;
    }
    
    .btn {
        padding: 0.5rem 0.8rem;
        font-size: 0.75rem;
    }
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

.question-block {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    position: relative;
}

.question-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.question-number {
    font-size: 1.125rem;
    font-weight: 600;
    color: white;
}

.remove-question-btn {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
    padding: 0.5rem;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.remove-question-btn:hover {
    background: rgba(239, 68, 68, 0.2);
    border-color: rgba(239, 68, 68, 0.5);
}

.option-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 6px;
    margin-bottom: 0.5rem;
    transition: all 0.2s ease;
}

.option-item:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(59, 130, 246, 0.3);
}

.option-item input[type="radio"] {
    margin: 0;
}

.option-item input[type="text"] {
    flex: 1;
    background: transparent;
    border: none;
    color: white;
    font-size: 0.875rem;
}

.option-item input[type="text"]:focus {
    outline: none;
}

.remove-option-btn {
    background: none;
    border: none;
    color: #ef4444;
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.remove-option-btn:hover {
    background: rgba(239, 68, 68, 0.1);
}

/* Enhanced form styling for better UX */
.form-textarea {
    resize: vertical;
    min-height: 120px;
}

.question-block {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    position: relative;
    transition: all 0.3s ease;
}

.question-block:hover {
    border-color: rgba(59, 130, 246, 0.3);
    background: rgba(255, 255, 255, 0.03);
}

/* Enhanced button styling */
.btn {
    transition: all 0.3s ease;
    font-weight: 500;
}

.btn-primary {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    border: none;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #1d4ed8, #1e40af);
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
    transform: translateY(-1px);
}

.btn-outline {
    border: 1px solid rgba(255, 255, 255, 0.3);
    background: rgba(255, 255, 255, 0.05);
}

.btn-outline:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.5);
}

/* Enhanced card styling */
.card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    overflow: hidden;
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.card-header {
    background: rgba(255, 255, 255, 0.02);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding: 1.5rem 2rem;
}

.card-body {
    padding: 2rem;
}

/* Enhanced form inputs */
.form-input, .form-select, .form-textarea {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    color: white;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    background: rgba(255, 255, 255, 0.08);
}

/* Enhanced option items */
.option-item {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 0.75rem;
    transition: all 0.3s ease;
}

.option-item:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(59, 130, 246, 0.3);
    transform: translateY(-1px);
}

.option-item input[type="text"] {
    background: transparent;
    border: none;
    color: white;
    font-size: 0.875rem;
    padding: 0.5rem 0;
}

.option-item input[type="text"]:focus {
    outline: none;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 6px;
    padding: 0.5rem;
}
</style>

<script>
let questionCount = 0;

document.addEventListener('DOMContentLoaded', function() {
    // Add first question automatically
    addQuestion();
});

function addQuestion() {
    questionCount++;
    const container = document.getElementById('questionsContainer');
    
    const questionHTML = `
        <div class="question-block" id="question-${questionCount}">
            <div class="question-header">
                <h4 class="question-number">Soal ${questionCount}</h4>
                <button type="button" onclick="removeQuestion(${questionCount})" class="remove-question-btn">
                    <i class="ph-trash"></i>
                </button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="form-label">Pertanyaan</label>
                    <textarea name="questions[${questionCount}][question]" class="form-textarea" rows="5" 
                              placeholder="Tuliskan pertanyaan di sini..." required></textarea>
                </div>
                
                <div>
                    <label class="form-label">Pilihan Jawaban</label>
                    <div id="options-${questionCount}" class="space-y-2">
                        <div class="option-item">
                            <input type="radio" name="questions[${questionCount}][correct_answer]" value="0" required>
                            <input type="text" name="questions[${questionCount}][options][]" 
                                   placeholder="Pilihan A" required>
                            <button type="button" onclick="removeOption(this)" class="remove-option-btn">
                                <i class="ph-x"></i>
                            </button>
                        </div>
                        <div class="option-item">
                            <input type="radio" name="questions[${questionCount}][correct_answer]" value="1" required>
                            <input type="text" name="questions[${questionCount}][options][]" 
                                   placeholder="Pilihan B" required>
                            <button type="button" onclick="removeOption(this)" class="remove-option-btn">
                                <i class="ph-x"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" onclick="addOption(${questionCount})" class="btn btn-outline btn-sm mt-2">
                        <i class="ph-plus mr-1"></i>
                        Tambah Pilihan
                    </button>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Poin</label>
                        <input type="number" name="questions[${questionCount}][points]" 
                               class="form-input" value="10" min="1" required
                               onchange="updateStatistics()">
                    </div>
                    <div>
                        <!-- Empty space for better layout -->
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', questionHTML);
    updateStatistics();
}

function removeQuestion(id) {
    if (questionCount <= 1) {
        alert('Minimal harus ada 1 soal');
        return;
    }
    
    document.getElementById(`question-${id}`).remove();
    updateStatistics();
}

function addOption(questionId) {
    const container = document.getElementById(`options-${questionId}`);
    const optionCount = container.children.length;
    
    if (optionCount >= 6) {
        alert('Maksimal 6 pilihan jawaban');
        return;
    }
    
    const optionHTML = `
        <div class="option-item">
            <input type="radio" name="questions[${questionId}][correct_answer]" value="${optionCount}" required>
            <input type="text" name="questions[${questionId}][options][]" 
                   placeholder="Pilihan ${String.fromCharCode(65 + optionCount)}" required>
            <button type="button" onclick="removeOption(this)" class="remove-option-btn">
                <i class="ph-x"></i>
            </button>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', optionHTML);
}

function removeOption(button) {
    const optionItem = button.parentElement;
    const container = optionItem.parentElement;
    
    if (container.children.length <= 2) {
        alert('Minimal harus ada 2 pilihan jawaban');
        return;
    }
    
    optionItem.remove();
}

function updateStatistics() {
    const totalQuestions = questionCount;
    const totalPoints = Array.from(document.querySelectorAll('input[name*="[points]"]'))
        .reduce((sum, input) => sum + (parseInt(input.value) || 0), 0);
    
    document.getElementById('totalQuestions').textContent = totalQuestions;
    document.getElementById('totalPoints').textContent = totalPoints;
}
</script>
@endsection
