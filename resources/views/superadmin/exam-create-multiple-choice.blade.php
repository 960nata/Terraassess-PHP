@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Buat Ujian Pilihan Ganda')

@section('styles')
<!-- Quill Editor CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<style>
.exam-form {
            background-color: #1e293b;
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
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

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-group textarea::placeholder {
            color: #666;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: #ffffff;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #334155;
            color: #ffffff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-secondary:hover {
            background: #475569;
        }

        .question-section {
            background-color: #1e293b;
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #334155;
        }

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
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: #ffffff;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .question-actions {
            display: flex;
            gap: 0.5rem;
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

        .add-question-btn {
            background: #10b981;
            color: #ffffff;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            width: 100%;
            justify-content: center;
        }

        .add-question-btn:hover {
            background: #059669;
            transform: translateY(-2px);
        }

        .exam-settings {
            background-color: #1e293b;
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #334155;
        }

        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .settings-grid {
                grid-template-columns: 1fr;
            }
            
            .question-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
        }

        /* SIMPLE MOBILE SIDEBAR - GUARANTEED TO WORK */
        @media (max-width: 1024px) {
            .sidebar {
                position: fixed !important;
                top: 70px !important;
                left: 0 !important;
                height: calc(100vh - 70px) !important;
                width: 280px !important;
                z-index: 999 !important;
                transform: translateX(-100%) !important;
                transition: transform 0.3s ease !important;
                background: #1e293b !important;
            }
            
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
            }
            
            .mobile-overlay {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                background: rgba(0, 0, 0, 0.5) !important;
                z-index: 998 !important;
                display: none !important;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 280px !important;
                z-index: 999 !important;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 100% !important;
            }
        }

        /* Quill Editor Styles */
        .quill-editor-modern {
            background: #2a2a3e;
            border: 2px solid #333;
            border-radius: 8px;
            color: #ffffff;
        }

        .quill-editor-modern .ql-toolbar {
            background: #1e293b;
            border-bottom: 1px solid #333;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .quill-editor-modern .ql-container {
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            font-size: 14px;
        }

        .quill-editor-modern .ql-editor {
            color: #ffffff;
            min-height: 120px;
        }

        .quill-editor-modern .ql-editor.ql-blank::before {
            color: #666;
            font-style: normal;
        }

        .quill-editor-modern .ql-toolbar .ql-stroke {
            stroke: #ffffff;
        }

        .quill-editor-modern .ql-toolbar .ql-fill {
            fill: #ffffff;
        }

        .quill-editor-modern .ql-toolbar .ql-picker-label {
            color: #ffffff;
        }

        .quill-editor-modern .ql-toolbar button:hover {
            background: #334155;
        }

        .quill-editor-modern .ql-toolbar button.ql-active {
            background: #667eea;
        }

        .quill-editor-modern .ql-toolbar .ql-picker-options {
            background: #1e293b;
            border: 1px solid #333;
            color: #ffffff;
        }

        .quill-editor-modern .ql-toolbar .ql-picker-item:hover {
            background: #334155;
        }

        .quill-editor-modern .ql-toolbar .ql-picker-item.ql-selected {
            background: #667eea;
        }

        /* Image upload styles */
        .quill-editor-modern .ql-editor img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            margin: 8px 0;
        }

        /* Question editor specific styles */
        .question-editor {
            margin-bottom: 1rem;
        }

        .question-editor .ql-editor {
            min-height: 150px;
        }

        /* Option editor styles */
        .option-editor {
            margin-bottom: 0.75rem;
        }

        .option-editor .ql-editor {
            min-height: 60px;
        }

        /* Responsive Quill */
        @media (max-width: 768px) {
            .quill-editor-modern .ql-toolbar {
                padding: 8px;
            }
            
            .quill-editor-modern .ql-toolbar .ql-formats {
                margin-right: 8px;
            }
            
            .quill-editor-modern .ql-toolbar button {
                width: 24px;
                height: 24px;
                padding: 2px;
            }
            
            .question-editor .ql-editor {
                min-height: 120px;
            }
            
            .option-editor .ql-editor {
                min-height: 50px;
            }
            
            /* Mobile toolbar optimization */
            .quill-editor-modern .ql-toolbar .ql-formats:not(:last-child) {
                margin-right: 4px;
            }
        }

        /* Quill Editor Icon Colors - Make icons white */
        .ql-toolbar .ql-stroke {
            stroke: #ffffff !important;
        }

        .ql-toolbar .ql-fill {
            fill: #ffffff !important;
        }

        .ql-toolbar .ql-picker-label {
            color: #ffffff !important;
        }

        .ql-toolbar svg {
            color: #ffffff !important;
        }

        .ql-toolbar svg path {
            stroke: #ffffff !important;
            fill: #ffffff !important;
        }

        .ql-toolbar svg line {
            stroke: #ffffff !important;
        }

        .ql-toolbar svg circle {
            stroke: #ffffff !important;
            fill: #ffffff !important;
        }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden border border-gray-700">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                <h1 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-list-ul mr-3"></i>
                    Buat Ujian Pilihan Ganda
                </h1>
                <p class="text-blue-100 mt-1">Buat ujian dengan soal pilihan ganda yang dapat dinilai otomatis</p>
                <div class="mt-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-500 text-white">
                        <i class="fas fa-tag mr-1"></i>
                        Tipe: Pilihan Ganda
                    </span>
                </div>
            </div>
            
            <div class="p-6 bg-gray-800">
                <!-- Exam Settings -->
                <div class="mb-6 bg-gray-700 rounded-lg p-6 border border-gray-600">
                    <h2 class="text-lg font-semibold text-white flex items-center mb-4">
                        <i class="fas fa-cog mr-2 text-blue-400"></i>Pengaturan Ujian
                    </h2>
            
            <form id="examForm" action="{{ route('superadmin.exam-management.create-multiple-choice.store') }}" method="POST">
                @csrf
                
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label for="exam_title" class="block text-sm font-medium text-gray-300 mb-2">
                                    Judul Ujian <span class="text-red-400">*</span>
                                </label>
                                <input type="text" id="exam_title" name="exam_title" 
                                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                                       placeholder="Masukkan judul ujian" required>
                            </div>
                            
                            <div>
                                <label for="kelas_id" class="block text-sm font-medium text-gray-300 mb-2">
                                    Kelas <span class="text-red-400">*</span>
                                </label>
                                <select id="kelas_id" name="kelas_id" 
                                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                                        required>
                                    <option value="">Pilih kelas</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('kelas_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="mapel_id" class="block text-sm font-medium text-gray-300 mb-2">
                                    Mata Pelajaran <span class="text-red-400">*</span>
                                </label>
                                <select id="mapel_id" name="mapel_id" 
                                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                                        required>
                                    <option value="">Pilih mata pelajaran</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('mapel_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="duration" class="block text-sm font-medium text-gray-300 mb-2">
                                    Durasi (menit) <span class="text-red-400">*</span>
                                </label>
                                <input type="number" id="duration" name="duration" 
                                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                                       placeholder="90" min="1" max="300" value="{{ old('duration') }}" required>
                            </div>
                            
                            <div>
                                <label for="max_score" class="block text-sm font-medium text-gray-300 mb-2">
                                    Nilai Maksimal <span class="text-red-400">*</span>
                                </label>
                                <input type="number" id="max_score" name="max_score" 
                                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                                       placeholder="100" min="1" max="100" value="{{ old('max_score', 100) }}" required>
                            </div>
                            
                            <div>
                                <label for="difficulty" class="block text-sm font-medium text-gray-300 mb-2">
                                    Tingkat Kesulitan <span class="text-red-400">*</span>
                                </label>
                                <select id="difficulty" name="difficulty" 
                                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                                        required>
                                    <option value="">Pilih tingkat kesulitan</option>
                                     <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>Mudah</option>
                                    <option value="medium" {{ old('difficulty', 'medium') == 'medium' ? 'selected' : '' }}>Sedang</option>
                                    <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>Sulit</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="exam_description" class="block text-sm font-medium text-gray-300 mb-2">
                                Deskripsi Ujian <span class="text-red-400">*</span>
                            </label>
                            <textarea id="exam_description" name="exam_description" 
                                      class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                                      rows="3" placeholder="Masukkan deskripsi ujian yang detail" required>{{ old('exam_description') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-300 mb-2">
                                    Tanggal Mulai <span class="text-red-400">*</span>
                                </label>
                                <input type="datetime-local" id="due_date" name="due_date" 
                                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                                       value="{{ old('due_date') }}" required>
                            </div>
                            
                            <div>
                                <label for="is_hidden" class="block text-sm font-medium text-gray-300 mb-2">
                                    Status
                                </label>
                                <select id="is_hidden" name="is_hidden" 
                                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                                    <option value="1" {{ old('is_hidden') == '1' ? 'selected' : '' }}>Draft</option>
                                    <option value="0" {{ old('is_hidden') == '0' ? 'selected' : '' }}>Publikasi</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Questions Section -->
                <div class="mb-6 bg-gray-700 rounded-lg p-6 border border-gray-600">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-white flex items-center">
                            <i class="fas fa-question-circle mr-2 text-blue-400"></i>Soal Ujian
                        </h2>
                        <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 flex items-center text-sm" onclick="addQuestion()">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Soal
                        </button>
                    </div>
                    
                    <div id="questionsContainer">
                        <!-- Questions will be added here dynamically -->
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('superadmin.exam-management') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-200">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <button type="button" id="saveExamBtn" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200" onclick="saveExam()">
                        <i class="fas fa-save mr-2"></i> Simpan Ujian
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Quill Editor JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
let questionCount = 0;
const quillEditors = {}; // Store Quill editor instances

// Initialize with first question
document.addEventListener('DOMContentLoaded', function() {
    addQuestion();
});

function addQuestion() {
    questionCount++;
    const container = document.getElementById('questionsContainer');
    
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
                <div id="quill-editor-question-${questionCount}" class="quill-editor-modern"></div>
                <textarea name="questions[${questionCount}][question]" id="quill-textarea-question-${questionCount}" 
                          style="display: none;" required></textarea>
            </div>
            
            <div class="option-group">
                <label>Pilihan Jawaban</label>
                
                <div class="option-input">
                    <input type="radio" name="questions[${questionCount}][correct_answer]" value="1" required>
                    <div class="option-editor">
                        <div id="quill-editor-option-1-${questionCount}" class="quill-editor-modern"></div>
                        <textarea name="questions[${questionCount}][options][1]" id="quill-textarea-option-1-${questionCount}" 
                                  style="display: none;" required></textarea>
                    </div>
                </div>
                
                <div class="option-input">
                    <input type="radio" name="questions[${questionCount}][correct_answer]" value="2" required>
                    <div class="option-editor">
                        <div id="quill-editor-option-2-${questionCount}" class="quill-editor-modern"></div>
                        <textarea name="questions[${questionCount}][options][2]" id="quill-textarea-option-2-${questionCount}" 
                                  style="display: none;" required></textarea>
                    </div>
                </div>
                
                <div class="option-input">
                    <input type="radio" name="questions[${questionCount}][correct_answer]" value="3" required>
                    <div class="option-editor">
                        <div id="quill-editor-option-3-${questionCount}" class="quill-editor-modern"></div>
                        <textarea name="questions[${questionCount}][options][3]" id="quill-textarea-option-3-${questionCount}" 
                                  style="display: none;" required></textarea>
                    </div>
                </div>
                
                <div class="option-input">
                    <input type="radio" name="questions[${questionCount}][correct_answer]" value="4" required>
                    <div class="option-editor">
                        <div id="quill-editor-option-4-${questionCount}" class="quill-editor-modern"></div>
                        <textarea name="questions[${questionCount}][options][4]" id="quill-textarea-option-4-${questionCount}" 
                                  style="display: none;" required></textarea>
                    </div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="points_${questionCount}">Poin</label>
                    <input type="number" name="questions[${questionCount}][points]" id="points_${questionCount}" 
                           value="10" min="1" required>
                </div>
                
                <div class="form-group">
                    <label for="category_${questionCount}">Kategori</label>
                    <select name="questions[${questionCount}][category]" id="category_${questionCount}">
                        <option value="easy">Mudah</option>
                        <option value="medium" selected>Sedang</option>
                        <option value="hard">Sulit</option>
                    </select>
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
        
        // Apply dark theme
        setTimeout(() => {
            const toolbar = document.querySelector(`#${questionEditorId} .ql-toolbar`);
            const container = document.querySelector(`#${questionEditorId} .ql-container`);
            if (toolbar) {
                toolbar.style.background = '#1e293b';
                toolbar.style.borderColor = '#333';
            }
            if (container) {
                container.style.background = '#2a2a3e';
                container.style.borderColor = '#333';
            }
        }, 100);
        
        // Add image upload handler
        const toolbar = questionEditor.getModule('toolbar');
        toolbar.addHandler('image', function() {
            selectLocalImage(questionEditor);
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
    [1, 2, 3, 4].forEach(optionNum => {
        const optionEditorId = `quill-editor-option-${optionNum}-${questionNum}`;
        const optionTextareaId = `quill-textarea-option-${optionNum}-${questionNum}`;
        
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
                placeholder: `Pilihan ${String.fromCharCode(64 + optionNum)}`
            });
            
            quillEditors[optionEditorId] = optionEditor;
            
            // Apply dark theme
            setTimeout(() => {
                const toolbar = document.querySelector(`#${optionEditorId} .ql-toolbar`);
                const container = document.querySelector(`#${optionEditorId} .ql-container`);
                if (toolbar) {
                    toolbar.style.background = '#1e293b';
                    toolbar.style.borderColor = '#333';
                }
                if (container) {
                    container.style.background = '#2a2a3e';
                    container.style.borderColor = '#333';
                }
            }, 100);
            
            // Add image upload handler
            const toolbar = optionEditor.getModule('toolbar');
            toolbar.addHandler('image', function() {
                selectLocalImage(optionEditor);
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
    const optionEditorIds = [1, 2, 3, 4].map(optionNum => `quill-editor-option-${optionNum}-${questionId}`);
    
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

// Image upload handler
function selectLocalImage(quill) {
    const input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');
    input.click();
    
    input.onchange = function() {
        const file = input.files[0];
        if (file) {
            // Check file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 5MB.');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function() {
                const range = quill.getSelection();
                quill.insertEmbed(range.index, 'image', reader.result);
            };
            reader.readAsDataURL(file);
        }
    };
}

function saveExam() {
    // Validate form
    const form = document.getElementById('examForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Check if there are questions
    if (questionCount === 0) {
        alert('Minimal harus ada 1 soal');
        return;
    }
    
    // Validate all questions have correct answers selected
    const questions = document.querySelectorAll('.question-item');
    let allValid = true;
    
    questions.forEach((question, index) => {
        const correctAnswer = question.querySelector('input[name*="[correct_answer]"]:checked');
        if (!correctAnswer) {
            alert(`Soal ${index + 1} belum memilih jawaban yang benar`);
            allValid = false;
        }
    });
    
    if (!allValid) {
        return;
    }
    
    // Show loading
    const saveBtn = document.getElementById('saveExamBtn');
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    saveBtn.disabled = true;
    
    // Submit form
    form.submit();
}

// Auto-save draft functionality
function saveDraft() {
    const formData = new FormData(document.getElementById('examForm'));
    const questions = [];
    
    // Collect all questions data
    for (let i = 1; i <= questionCount; i++) {
        const questionElement = document.getElementById(`question-${i}`);
        if (questionElement) {
            // Get Quill editor content
            const questionEditorId = `quill-editor-question-${i}`;
            const questionContent = quillEditors[questionEditorId] ? quillEditors[questionEditorId].root.innerHTML : '';
            
            const options = {};
            [1, 2, 3, 4].forEach(optionNum => {
                const optionEditorId = `quill-editor-option-${optionNum}-${i}`;
                const optionKey = String.fromCharCode(64 + optionNum); // A, B, C, D
                options[optionKey] = quillEditors[optionEditorId] ? quillEditors[optionEditorId].root.innerHTML : '';
            });
            
            const questionData = {
                question: questionContent,
                options: options,
                correct_answer: questionElement.querySelector(`input[name="questions[${i}][correct_answer]"]:checked`)?.value || '',
                points: questionElement.querySelector(`input[name="questions[${i}][points]"]`).value,
                category: questionElement.querySelector(`select[name="questions[${i}][category]"]`).value
            };
            questions.push(questionData);
        }
    }
    
    const draftData = {
        exam_title: formData.get('exam_title'),
        class_id: formData.get('class_id'),
        subject_id: formData.get('subject_id'),
        duration: formData.get('duration'),
        max_score: formData.get('max_score'),
        difficulty: formData.get('difficulty'),
        exam_description: formData.get('exam_description'),
        due_date: formData.get('due_date'),
        is_hidden: formData.get('is_hidden'),
        questions: questions
    };
    
    localStorage.setItem('exam_draft', JSON.stringify(draftData));
}

// Load draft on page load
function loadDraft() {
    const savedDraft = localStorage.getItem('exam_draft');
    if (savedDraft) {
        try {
            const draftData = JSON.parse(savedDraft);
            
            // Fill form fields
            if (draftData.exam_title) document.getElementById('exam_title').value = draftData.exam_title;
            if (draftData.class_id) document.getElementById('class_id').value = draftData.class_id;
            if (draftData.subject_id) document.getElementById('subject_id').value = draftData.subject_id;
            if (draftData.duration) document.getElementById('duration').value = draftData.duration;
            if (draftData.max_score) document.getElementById('max_score').value = draftData.max_score;
            if (draftData.difficulty) document.getElementById('difficulty').value = draftData.difficulty;
            if (draftData.exam_description) document.getElementById('exam_description').value = draftData.exam_description;
            if (draftData.due_date) document.getElementById('due_date').value = draftData.due_date;
            if (draftData.is_hidden) document.getElementById('is_hidden').value = draftData.is_hidden;
            
            // Load questions
            if (draftData.questions && draftData.questions.length > 0) {
                // Clear existing questions
                document.getElementById('questionsContainer').innerHTML = '';
                questionCount = 0;
                
                // Add each question
                draftData.questions.forEach(questionData => {
                    addQuestion();
                    
                    const currentQuestion = document.getElementById(`question-${questionCount}`);
                    
                    // Set Quill editor content
                    const questionEditorId = `quill-editor-question-${questionCount}`;
                    if (quillEditors[questionEditorId] && questionData.question) {
                        quillEditors[questionEditorId].root.innerHTML = questionData.question;
                    }
                    
                    // Set option editor content
                    [1, 2, 3, 4].forEach(optionNum => {
                        const optionEditorId = `quill-editor-option-${optionNum}-${questionCount}`;
                        const optionKey = String.fromCharCode(64 + optionNum); // A, B, C, D
                        if (quillEditors[optionEditorId] && questionData.options?.[optionKey]) {
                            quillEditors[optionEditorId].root.innerHTML = questionData.options[optionKey];
                        }
                    });
                    
                    if (questionData.correct_answer) {
                        const correctRadio = currentQuestion.querySelector(`input[name="questions[${questionCount}][correct_answer]"][value="${questionData.correct_answer}"]`);
                        if (correctRadio) correctRadio.checked = true;
                    }
                    
                    currentQuestion.querySelector(`input[name="questions[${questionCount}][points]"]`).value = questionData.points || 10;
                    currentQuestion.querySelector(`select[name="questions[${questionCount}][category]"]`).value = questionData.category || 'medium';
                });
            }
        } catch (e) {
            console.error('Error loading draft:', e);
        }
    }
}

// Auto-save every 30 seconds
setInterval(saveDraft, 30000);

// Save draft on form changes
document.addEventListener('DOMContentLoaded', function() {
    loadDraft();
    
    // Add event listeners for auto-save
    const form = document.getElementById('examForm');
    if (form) {
        form.addEventListener('input', saveDraft);
        form.addEventListener('change', saveDraft);
    }
});

// Clear draft when form is successfully submitted
window.addEventListener('beforeunload', function() {
    // Only clear if form is being submitted (not just refreshed)
    if (document.querySelector('.btn-primary').disabled) {
        localStorage.removeItem('exam_draft');
    }
});

// Restore questions dari old input jika ada
@if(old('questions'))
    document.addEventListener('DOMContentLoaded', function() {
        const oldQuestions = @json(old('questions'));
        console.log('Restoring old exam questions:', oldQuestions);
        
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
            const correctAnswerRadio = document.querySelector(`#question-${questionId} input[name="questions[${questionId}][correct_answer]"][value="${question.correct_answer}"]`);
            if (correctAnswerRadio) {
                correctAnswerRadio.checked = true;
            }
            
            // Fill options
            if (question.options) {
                ['1', '2', '3', '4'].forEach(option => {
                    const optionInput = document.querySelector(`#question-${questionId} input[name="questions[${questionId}][options][${option}]"]`);
                    if (optionInput) {
                        optionInput.value = question.options[option] || '';
                    }
                });
            }
            
            // Initialize Quill editors for this question
            setTimeout(() => {
                initializeQuillEditors();
            }, 100);
        });
        
        // Show notification that data was restored
        @if(session('error'))
            showNotification('Data Anda tersimpan! Silakan perbaiki error dan submit kembali. Data yang sudah Anda isi tidak hilang.', 'warning');
        @endif
    });
@endif
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

.quill-editor-modern .ql-editor {
    color: #ffffff;
    background: #2a2a3e;
    min-height: 120px;
}

.quill-editor-modern .ql-toolbar {
    background: #1e293b;
    border: 1px solid #334155;
    border-bottom: none;
}

.quill-editor-modern .ql-container {
    border: 1px solid #334155;
    border-top: none;
}

.quill-editor-modern .ql-snow .ql-picker {
    color: #ffffff;
}

.quill-editor-modern .ql-snow .ql-stroke {
    stroke: #ffffff;
}

.quill-editor-modern .ql-snow .ql-fill {
    fill: #ffffff;
}
</style>
@endsection
