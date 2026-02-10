@extends('layouts.unified-layout')

@section('title', 'Buat Tugas Pilihan Ganda')

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden border border-gray-700">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                <h1 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-list-ul mr-3"></i>
                    Buat Tugas Pilihan Ganda
                </h1>
                <p class="text-blue-100 mt-1">Buat kuis dengan jawaban A, B, C, D yang dapat dinilai otomatis</p>
                <div class="mt-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-500 text-white">
                        <i class="fas fa-tag mr-1"></i>
                        Tipe: Pilihan Ganda
                    </span>
                </div>
            </div>
            
            <div class="p-6 bg-gray-800">
                <form id="multipleChoiceForm" method="POST" action="{{ route('teacher.tasks.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="tipe" value="1">
                    
                    {{-- Success/Error Messages --}}
                    @if(session('success'))
                    <div class="alert alert-success" style="background: #10b981; color: white; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <strong>✓ Berhasil!</strong> {{ session('success') }}
                    </div>
                    @endif
                    @if(session('error'))
                    <div class="alert alert-danger" style="background: #ef4444; color: white; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <strong>✗ Error!</strong> {{ session('error') }}
                    </div>
                    @endif
                    @if($errors->any())
                    <div class="alert alert-danger" style="background: #ef4444; color: white; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <strong>✗ Validasi Gagal!</strong>
                        <ul style="margin: 0.5rem 0 0 1.5rem;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <!-- Informasi Kompresi Gambar -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-500 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Tips Kompresi Gambar</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Gambar akan otomatis dikompres menjadi maksimal 45KB per soal</li>
                                        <li>Resolusi maksimal 600px lebar untuk performa optimal</li>
                                        <li>Format yang disarankan: JPG untuk foto, PNG untuk diagram</li>
                                        <li>Jika gambar masih terlalu besar, gunakan resolusi yang lebih rendah</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informasi Tugas -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                                Judul Tugas <span class="text-red-400">*</span>
                            </label>
                            <input type="text" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('name') border-red-500 @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan judul tugas pilihan ganda" required>
                            @error('name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                Tipe Tugas
                            </label>
                            <div class="w-full px-4 py-3 bg-gray-600 border border-gray-500 text-gray-300 rounded-lg">
                                Pilihan Ganda
                            </div>
                        </div>
                    </div>

                    <!-- Kelas dan Mata Pelajaran -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6">
                        <div>
                            <label for="kelas_id" class="block text-sm font-medium text-gray-300 mb-2">
                                Kelas Tujuan <span class="text-red-400">*</span>
                            </label>
                            <select class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('kelas_id') border-red-500 @enderror" 
                                    id="kelas_id" name="kelas_id" required>
                                <option value="">Pilih Kelas</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->name }} - {{ $k->level }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="mapel_id" class="block text-sm font-medium text-gray-300 mb-2">
                                Mata Pelajaran <span class="text-red-400">*</span>
                            </label>
                            <select class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('mapel_id') border-red-500 @enderror" 
                                    id="mapel_id" name="mapel_id" required>
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach($mapel as $m)
                                    <option value="{{ $m->id }}" {{ old('mapel_id') == $m->id ? 'selected' : '' }}>
                                        {{ $m->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mapel_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Waktu Tugas -->
                    <div class="grid grid-cols-1 gap-4 md:gap-6 mb-6">
                        <div>
                            <label for="due" class="block text-sm font-medium text-gray-300 mb-2">
                                Tanggal Tenggat <span class="text-red-400">*</span>
                            </label>
                            <input type="datetime-local" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('due') border-red-500 @enderror" 
                                   id="due" name="due" value="{{ old('due') }}" required>
                            @error('due')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Deskripsi Tugas dengan Quill Editor -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Deskripsi/Instruksi Tugas
                        </label>
                        <div class="bg-gray-700 rounded-lg border border-gray-600 overflow-hidden">
                            <div id="deskripsi-editor" class="quill-editor-dark" style="height: 200px;"></div>
                            <textarea name="content" id="deskripsi-textarea" style="display: none;">{{ old('content') }}</textarea>
                        </div>
                        @error('content')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Questions Section -->
                    <div id="questionsSection" class="mb-6 bg-gray-700 rounded-lg p-6 border border-gray-600">
                        <div class="flex justify-between items-center mb-4">
                            <h5 class="text-lg font-semibold text-white flex items-center">
                                <i class="fas fa-question-circle mr-2 text-blue-400"></i>
                                Soal Pilihan Ganda
                            </h5>
                            <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 flex items-center text-sm" onclick="addQuestion()">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Soal
                            </button>
                        </div>
                        <div id="questionsContainer">
                            <div class="text-center py-8 text-gray-400">
                                <i class="fas fa-question-circle text-4xl mb-4"></i>
                                <p>Belum ada soal. Klik "Tambah Soal" untuk menambahkan soal pilihan ganda.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pengaturan Tugas -->
                    <div class="mb-6 bg-gray-700 rounded-lg p-6 border border-gray-600">
                        <h5 class="text-lg font-semibold text-white flex items-center mb-4">
                            <i class="fas fa-cog mr-2 text-blue-400"></i>
                            Pengaturan Tugas
                        </h5>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="isHidden" value="1" {{ old('isHidden') ? 'checked' : '' }} 
                                           class="rounded border-gray-600 text-blue-600 focus:ring-blue-500 focus:ring-offset-gray-800">
                                    <span class="ml-2 text-sm text-gray-300">Simpan sebagai draft (tidak langsung dipublikasikan)</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 mt-8">
                        <a href="{{ route('teacher.tasks') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                            <i class="fas fa-save mr-2"></i> Simpan Tugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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

/* Question specific styles */
.question-item {
    background-color: #2a2a3e;
    border-radius: 0.75rem;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid #334155;
}

.question-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.question-number {
    background: linear-gradient(45deg, #3b82f6, #1d4ed8);
    color: #ffffff;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.875rem;
}

.btn-danger {
    background: #ef4444;
    color: #ffffff;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-danger:hover {
    background: #dc2626;
}

.option-group {
    margin-bottom: 1rem;
}

.option-input {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.option-input input[type="radio"] {
    width: auto;
    margin: 0;
    margin-top: 0.5rem;
    flex-shrink: 0;
}

.option-input .option-editor {
    flex: 1;
}

.option-editor .ql-editor {
    min-height: 60px;
}

@media (max-width: 768px) {
    .question-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .option-input {
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>

<!-- Quill Editor JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
let questionCount = 0;
const quillEditors = {}; // Store Quill editor instances

// Fungsi untuk kompres gambar base64 dengan kontrol ukuran yang lebih baik
function compressImage(base64Str, maxWidth = 600, maxSizeKB = 45) {
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
            
            // Coba berbagai tingkat kompresi sampai ukuran sesuai
            let quality = 0.8;
            let compressedBase64;
            let sizeKB;
            
            do {
                compressedBase64 = canvas.toDataURL('image/jpeg', quality);
                // Hitung ukuran dalam KB (base64 adalah ~33% lebih besar dari binary)
                sizeKB = (compressedBase64.length * 0.75) / 1024;
                quality -= 0.1;
            } while (sizeKB > maxSizeKB && quality > 0.1);
            
            // Jika masih terlalu besar, kurangi resolusi
            if (sizeKB > maxSizeKB && quality <= 0.1) {
                const scaleFactor = Math.sqrt(maxSizeKB / sizeKB);
                width = Math.floor(width * scaleFactor);
                height = Math.floor(height * scaleFactor);
                
                canvas.width = width;
                canvas.height = height;
                ctx.drawImage(img, 0, 0, width, height);
                compressedBase64 = canvas.toDataURL('image/jpeg', 0.7);
            }
            
            resolve(compressedBase64);
        };
        img.src = base64Str;
    });
}

// Fungsi untuk validasi ukuran gambar sebelum upload
function validateImageSize(base64Str, maxSizeKB = 45) {
    const sizeKB = (base64Str.length * 0.75) / 1024;
    return {
        isValid: sizeKB <= maxSizeKB,
        sizeKB: Math.round(sizeKB * 100) / 100,
        maxSizeKB: maxSizeKB
    };
}

// Declare deskripsiEditor in global scope
let deskripsiEditor = null;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Quill for description
    if (document.getElementById('deskripsi-editor')) {
        deskripsiEditor = new Quill('#deskripsi-editor', {
            theme: 'snow',
            placeholder: 'Masukkan deskripsi tugas...',
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
        // Custom image handler untuk kompres gambar
        deskripsiEditor.getModule('toolbar').addHandler('image', async function() {
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
                            // Kompres gambar dengan kontrol ukuran yang lebih baik
                            const compressed = await compressImage(base64, 600, 45);
                            
                            // Validasi ukuran setelah kompresi
                            const validation = validateImageSize(compressed, 45);
                            if (!validation.isValid) {
                                alert(`Gambar masih terlalu besar (${validation.sizeKB}KB). Silakan gunakan gambar yang lebih kecil atau resolusi yang lebih rendah.`);
                                return;
                            }
                            
                            // Insert ke Quill
                            const range = deskripsiEditor.getSelection();
                            deskripsiEditor.insertEmbed(range.index, 'image', compressed);
                        } catch (error) {
                            console.error('Error compressing image:', error);
                            alert('Gagal memproses gambar. Silakan coba gambar lain atau gunakan resolusi yang lebih rendah.');
                        }
                    };
                    reader.readAsDataURL(file);
                }
            };
        });
        
        deskripsiEditor.on('text-change', function() {
            document.getElementById('deskripsi-textarea').value = deskripsiEditor.root.innerHTML;
        });
        // Set initial content if old content exists
        if (document.getElementById('deskripsi-textarea').value) {
            deskripsiEditor.root.innerHTML = document.getElementById('deskripsi-textarea').value;
        }
    }
    
    // Handle form submission - sync Quill editor sebelum submit
    const form = document.getElementById('multipleChoiceForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Sync Quill editor untuk deskripsi ke textarea
            if (deskripsiEditor) {
                const deskripsiContent = deskripsiEditor.root.innerHTML;
                document.getElementById('deskripsi-textarea').value = deskripsiContent;
            }
            
            // Sync semua Quill editor untuk soal dan pilihan jawaban
            const questionItems = document.querySelectorAll('.question-item');
            questionItems.forEach((item, index) => {
                const questionId = item.id.split('-')[1];
                
                // Sync question editor
                const questionEditor = window[`questionEditor${questionId}`];
                if (questionEditor) {
                    const questionContent = questionEditor.root.innerHTML;
                    document.getElementById(`quill-textarea-question-${questionId}`).value = questionContent;
                }
                
                // Sync option editors
                ['A', 'B', 'C', 'D'].forEach(option => {
                    const optionEditor = window[`optionEditor${option}_${questionId}`];
                    if (optionEditor) {
                        const optionContent = optionEditor.root.innerHTML;
                        document.getElementById(`quill-textarea-option-${option}-${questionId}`).value = optionContent;
                    }
                });
            });
            
            console.log('Form submit - Data being submitted:', new FormData(form));
        });
    }
});

function addQuestion() {
    questionCount++;
    const container = document.getElementById('questionsContainer');
    
    // Remove placeholder message if this is the first question
    const placeholder = container.querySelector('.text-center');
    if (placeholder) {
        placeholder.remove();
    }
    
    const questionHTML = `
        <div class="question-item" id="question-${questionCount}">
            <div class="question-header">
                <div class="question-number">Soal ${questionCount}</div>
                <div class="question-actions">
                    <button type="button" class="btn-danger" onclick="removeQuestion(${questionCount})">
                        <i class="fas fa-trash"></i>
                        Hapus
                    </button>
                </div>
            </div>
            
            <div class="form-group question-editor">
                <label for="question_${questionCount}">Pertanyaan</label>
                <div id="quill-editor-question-${questionCount}" class="quill-editor-dark"></div>
                <textarea name="questions[${questionCount}][question]" id="quill-textarea-question-${questionCount}" 
                          style="display: none;" required></textarea>
            </div>
            
            <div class="option-group">
                <label>Pilihan Jawaban</label>
                
                <div class="option-input">
                    <input type="radio" name="questions[${questionCount}][correct_answer]" value="A" required>
                    <div class="option-editor">
                        <div id="quill-editor-option-A-${questionCount}" class="quill-editor-dark"></div>
                        <textarea name="questions[${questionCount}][options][A]" id="quill-textarea-option-A-${questionCount}" 
                                  style="display: none;" required></textarea>
                    </div>
                </div>
                
                <div class="option-input">
                    <input type="radio" name="questions[${questionCount}][correct_answer]" value="B" required>
                    <div class="option-editor">
                        <div id="quill-editor-option-B-${questionCount}" class="quill-editor-dark"></div>
                        <textarea name="questions[${questionCount}][options][B]" id="quill-textarea-option-B-${questionCount}" 
                                  style="display: none;" required></textarea>
                    </div>
                </div>
                
                <div class="option-input">
                    <input type="radio" name="questions[${questionCount}][correct_answer]" value="C" required>
                    <div class="option-editor">
                        <div id="quill-editor-option-C-${questionCount}" class="quill-editor-dark"></div>
                        <textarea name="questions[${questionCount}][options][C]" id="quill-textarea-option-C-${questionCount}" 
                                  style="display: none;" required></textarea>
                    </div>
                </div>
                
                <div class="option-input">
                    <input type="radio" name="questions[${questionCount}][correct_answer]" value="D" required>
                    <div class="option-editor">
                        <div id="quill-editor-option-D-${questionCount}" class="quill-editor-dark"></div>
                        <textarea name="questions[${questionCount}][options][D]" id="quill-textarea-option-D-${questionCount}" 
                                  style="display: none;" required></textarea>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="points_${questionCount}">Poin</label>
                    <input type="number" name="questions[${questionCount}][points]" id="points_${questionCount}" 
                           class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           value="10" min="1" required>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', questionHTML);
    
    // Initialize Quill editors for the new question
    initializeQuillEditors(questionCount);
}

// Initialize Quill editors for a question
function initializeQuillEditors(questionNum) {
    // Question editor
    const questionEditorId = `quill-editor-question-${questionNum}`;
    const questionTextareaId = `quill-textarea-question-${questionNum}`;
    
    if (document.getElementById(questionEditorId)) {
        const isMobile = window.innerWidth <= 768;
        const questionEditor = new Quill(`#${questionEditorId}`, {
            theme: 'snow',
            modules: {
                toolbar: isMobile ? [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'image'],
                    ['clean']
                ] : [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'align': [] }],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            },
            placeholder: 'Tuliskan pertanyaan di sini...'
        });
        
        quillEditors[questionEditorId] = questionEditor;
        
        // Custom image handler untuk kompres gambar
        questionEditor.getModule('toolbar').addHandler('image', async function() {
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
                            const range = questionEditor.getSelection();
                            questionEditor.insertEmbed(range.index, 'image', compressed);
                        } catch (error) {
                            console.error('Error compressing image:', error);
                            // Fallback: insert original image
                            const range = questionEditor.getSelection();
                            questionEditor.insertEmbed(range.index, 'image', base64);
                        }
                    };
                    reader.readAsDataURL(file);
                }
            };
        });
        
        // Sync with textarea
        questionEditor.on('text-change', function() {
            const textarea = document.getElementById(questionTextareaId);
            if (textarea) {
                textarea.value = questionEditor.root.innerHTML;
            }
        });
    }
    
    // Option editors
    ['A', 'B', 'C', 'D'].forEach(option => {
        const optionEditorId = `quill-editor-option-${option}-${questionNum}`;
        const optionTextareaId = `quill-textarea-option-${option}-${questionNum}`;
        
        if (document.getElementById(optionEditorId)) {
            const isMobile = window.innerWidth <= 768;
            const optionEditor = new Quill(`#${optionEditorId}`, {
                theme: 'snow',
                modules: {
                    toolbar: isMobile ? [
                        ['bold', 'italic'],
                        ['link', 'image'],
                        ['clean']
                    ] : [
                        ['bold', 'italic', 'underline'],
                        [{ 'color': [] }, { 'background': [] }],
                        ['link', 'image'],
                        ['clean']
                    ]
                },
                placeholder: `Pilihan ${option}`
            });
            
            quillEditors[optionEditorId] = optionEditor;
            
            // Custom image handler untuk kompres gambar
            optionEditor.getModule('toolbar').addHandler('image', async function() {
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
                                const range = optionEditor.getSelection();
                                optionEditor.insertEmbed(range.index, 'image', compressed);
                            } catch (error) {
                                console.error('Error compressing image:', error);
                                // Fallback: insert original image
                                const range = optionEditor.getSelection();
                                optionEditor.insertEmbed(range.index, 'image', base64);
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                };
            });
            
            // Sync with textarea
            optionEditor.on('text-change', function() {
                const textarea = document.getElementById(optionTextareaId);
                if (textarea) {
                    textarea.value = optionEditor.root.innerHTML;
                }
            });
        }
    });
}

function removeQuestion(questionId) {
    if (questionCount <= 1) {
        alert('Minimal harus ada 1 soal');
        return;
    }
    
    // Clean up Quill editors for this question
    const questionEditorId = `quill-editor-question-${questionId}`;
    const optionEditorIds = ['A', 'B', 'C', 'D'].map(option => `quill-editor-option-${option}-${questionId}`);
    
    // Remove question editor
    if (quillEditors[questionEditorId]) {
        quillEditors[questionEditorId].destroy();
        delete quillEditors[questionEditorId];
    }
    
    // Remove option editors
    optionEditorIds.forEach(editorId => {
        if (quillEditors[editorId]) {
            quillEditors[editorId].destroy();
            delete quillEditors[editorId];
        }
    });
    
    const questionElement = document.getElementById(`question-${questionId}`);
    if (questionElement) {
        questionElement.remove();
    }
}

// Restore questions dari old input jika ada
@if(old('questions'))
    document.addEventListener('DOMContentLoaded', function() {
        const oldQuestions = @json(old('questions'));
        console.log('Restoring old questions:', oldQuestions);
        
        oldQuestions.forEach((question, index) => {
            addQuestion();
            
            // Fill data ke form
            const questionId = index;
            
            // Fill question text
            const questionInput = document.querySelector(`#question-${questionId} input[name="questions[${questionId}][question]"]`);
            if (questionInput) {
                questionInput.value = question.question || '';
            }
            
            // Fill points
            const pointsInput = document.querySelector(`#question-${questionId} input[name="questions[${questionId}][points]"]`);
            if (pointsInput) {
                pointsInput.value = question.points || 1;
            }
            
            // Fill category
            const categorySelect = document.querySelector(`#question-${questionId} select[name="questions[${questionId}][category]"]`);
            if (categorySelect) {
                categorySelect.value = question.category || 'medium';
            }
            
            // Fill correct answer
            const correctAnswerRadios = document.querySelectorAll(`#question-${questionId} input[name="questions[${questionId}][correct_answer]"]`);
            correctAnswerRadios.forEach(radio => {
                if (radio.value === question.correct_answer) {
                    radio.checked = true;
                }
            });
            
            // Fill options
            if (question.options) {
                ['A', 'B', 'C', 'D'].forEach(option => {
                    const optionTextarea = document.querySelector(`#question-${questionId} textarea[name="questions[${questionId}][options][${option}]"]`);
                    if (optionTextarea) {
                        optionTextarea.value = question.options[option] || '';
                    }
                });
            }
            
            // Initialize Quill editors for this question
            setTimeout(() => {
                initializeQuillEditors();
                
                // After Quill is initialized, set the content
                setTimeout(() => {
                    // Set question content to Quill
                    const questionEditor = window[`questionEditor${questionId}`];
                    if (questionEditor && question.question) {
                        questionEditor.root.innerHTML = question.question;
                    }
                    
                    // Set options content to Quill
                    ['A', 'B', 'C', 'D'].forEach(option => {
                        const optionEditor = window[`optionEditor${option}_${questionId}`];
                        if (optionEditor && question.options && question.options[option]) {
                            optionEditor.root.innerHTML = question.options[option];
                        }
                    });
                }, 200);
            }, 100);
        });
        
        // Show notification that data was restored
        @if(session('error'))
            showNotification('⚠️ Terjadi error, tapi data Anda AMAN! Semua soal yang sudah Anda buat masih ada. Silakan perbaiki error dan submit kembali.', 'warning');
        @endif
    });
@endif

// Auto-save functionality
let autoSaveInterval;

function startAutoSave() {
    autoSaveInterval = setInterval(() => {
        saveToLocalStorage();
    }, 5000); // Save every 5 seconds
}

function saveToLocalStorage() {
    const formData = {
        name: document.querySelector('input[name="name"]')?.value,
        content: document.getElementById('deskripsi-textarea')?.value,
        kelas_id: document.querySelector('select[name="kelas_id"]')?.value,
        mapel_id: document.querySelector('select[name="mapel_id"]')?.value,
        due: document.querySelector('input[name="due"]')?.value,
        questions: []
    };
    
    // Collect all questions
    const questionItems = document.querySelectorAll('.question-item');
    questionItems.forEach((item, index) => {
        const questionId = item.id.split('-')[1];
        
        const questionData = {
            question: document.getElementById(`quill-textarea-question-${questionId}`)?.value,
            points: document.querySelector(`input[name="questions[${questionId}][points]"]`)?.value,
            category: document.querySelector(`select[name="questions[${questionId}][category]"]`)?.value,
            correct_answer: document.querySelector(`input[name="questions[${questionId}][correct_answer]"]:checked`)?.value,
            options: {}
        };
        
        ['A', 'B', 'C', 'D'].forEach(option => {
            questionData.options[option] = document.getElementById(`quill-textarea-option-${option}-${questionId}`)?.value;
        });
        
        formData.questions.push(questionData);
    });
    
    localStorage.setItem('multipleChoiceTaskDraft', JSON.stringify(formData));
    console.log('Auto-saved to localStorage');
    
    // Show indicator
    const indicator = document.querySelector('.auto-save-indicator');
    if (indicator) {
        indicator.style.display = 'flex';
        indicator.style.alignItems = 'center';
        setTimeout(() => {
            indicator.style.display = 'none';
        }, 2000);
    }
}

function loadFromLocalStorage() {
    const savedData = localStorage.getItem('multipleChoiceTaskDraft');
    if (!savedData) return false;
    
    try {
        const formData = JSON.parse(savedData);
        
        // Ask user if they want to restore
        if (confirm('Ditemukan draft yang tersimpan. Apakah Anda ingin memulihkan data?')) {
            // Restore basic fields
            if (formData.name) document.querySelector('input[name="name"]').value = formData.name;
            if (formData.kelas_id) document.querySelector('select[name="kelas_id"]').value = formData.kelas_id;
            if (formData.mapel_id) document.querySelector('select[name="mapel_id"]').value = formData.mapel_id;
            if (formData.due) document.querySelector('input[name="due"]').value = formData.due;
            if (formData.content) document.getElementById('deskripsi-textarea').value = formData.content;
            
            // Restore questions
            if (formData.questions && formData.questions.length > 0) {
                formData.questions.forEach((question, index) => {
                    addQuestion();
                    const questionId = index;
                    
                    // Fill question text
                    const questionTextarea = document.getElementById(`quill-textarea-question-${questionId}`);
                    if (questionTextarea && question.question) {
                        questionTextarea.value = question.question;
                    }
                    
                    // Fill points
                    const pointsInput = document.querySelector(`input[name="questions[${questionId}][points]"]`);
                    if (pointsInput && question.points) {
                        pointsInput.value = question.points;
                    }
                    
                    // Fill category
                    const categorySelect = document.querySelector(`select[name="questions[${questionId}][category]"]`);
                    if (categorySelect && question.category) {
                        categorySelect.value = question.category;
                    }
                    
                    // Fill correct answer
                    const correctAnswerRadios = document.querySelectorAll(`input[name="questions[${questionId}][correct_answer]"]`);
                    correctAnswerRadios.forEach(radio => {
                        if (radio.value === question.correct_answer) {
                            radio.checked = true;
                        }
                    });
                    
                    // Fill options
                    if (question.options) {
                        ['A', 'B', 'C', 'D'].forEach(option => {
                            const optionTextarea = document.getElementById(`quill-textarea-option-${option}-${questionId}`);
                            if (optionTextarea && question.options[option]) {
                                optionTextarea.value = question.options[option];
                            }
                        });
                    }
                });
            }
            
            return true;
        } else {
            localStorage.removeItem('multipleChoiceTaskDraft');
            return false;
        }
    } catch (e) {
        console.error('Error loading from localStorage:', e);
        return false;
    }
}

function clearLocalStorage() {
    localStorage.removeItem('multipleChoiceTaskDraft');
}

// Initialize auto-save on page load
document.addEventListener('DOMContentLoaded', function() {
    // Try to load from localStorage first (if no old input from server)
    @if(!old('questions'))
        const restored = loadFromLocalStorage();
        if (restored) {
            setTimeout(() => initializeQuillEditors(), 500);
        }
    @endif
    
    // Start auto-save
    startAutoSave();
});

// Clear localStorage on successful submit
const form = document.getElementById('multipleChoiceForm');
if (form) {
    form.addEventListener('submit', function(e) {
        // Clear localStorage on submit (will be cleared after successful save)
        setTimeout(() => {
            clearLocalStorage();
        }, 1000);
    });
}
</script>

<!-- Auto-save indicator -->
<div class="auto-save-indicator" style="position: fixed; bottom: 20px; right: 20px; padding: 10px 15px; background: #2a2a3e; border-radius: 8px; display: none; z-index: 1000;">
    <i class="fas fa-check-circle" style="color: #10b981;"></i>
    <span style="color: #fff; margin-left: 8px;">Draft tersimpan</span>
</div>
@endsection
