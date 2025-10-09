@extends('layouts.unified-layout-new')

@section('title', 'Buat Soal Pilihan Ganda')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('teacher.tasks.management') }}">Manajemen Tugas</a></li>
                        <li class="breadcrumb-item active">Buat Soal Pilihan Ganda</li>
                    </ol>
                </div>
                <h4 class="page-title">Buat Soal Pilihan Ganda</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <form action="{{ route('teacher.tasks.store.multiple-choice') }}" method="POST" id="multipleChoiceForm">
                @csrf
                
                <!-- Basic Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-file-text me-2"></i>
                            Informasi Dasar Tugas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Judul Tugas <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="class_subject" class="form-label">Kelas & Mata Pelajaran <span class="text-danger">*</span></label>
                                    <select class="form-select @error('class_subject') is-invalid @enderror" 
                                            id="class_subject" name="class_subject" required>
                                        <option value="">Pilih Kelas & Mata Pelajaran</option>
                                        @foreach($classSubjects as $cs)
                                            <option value="{{ $cs->id }}" {{ old('class_subject') == $cs->id ? 'selected' : '' }}>
                                                {{ $cs->kelas->name }} - {{ $cs->mapel->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('class_subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">Tanggal Tenggat <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control @error('due_date') is-invalid @enderror" 
                                           id="due_date" name="due_date" value="{{ old('due_date') }}" required>
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="time_limit" class="form-label">Batas Waktu (menit)</label>
                                    <input type="number" class="form-control @error('time_limit') is-invalid @enderror" 
                                           id="time_limit" name="time_limit" value="{{ old('time_limit', 60) }}" min="1">
                                    @error('time_limit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi Tugas</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Masukkan deskripsi tugas (opsional)">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_hidden" name="is_hidden" value="1" {{ old('is_hidden') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_hidden">
                                        Sembunyikan dari siswa
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="shuffle_questions" name="shuffle_questions" value="1" {{ old('shuffle_questions') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="shuffle_questions">
                                        Acak urutan soal
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Questions Card -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ph-question me-2"></i>
                            Soal Pilihan Ganda
                        </h5>
                        <button type="button" class="btn btn-primary btn-sm" onclick="addQuestion()">
                            <i class="ph-plus me-1"></i>
                            Tambah Soal
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="questions-container">
                            <!-- Questions will be added here dynamically -->
                        </div>
                        
                        <div class="text-center mt-4" id="no-questions-message" style="display: none;">
                            <div class="text-muted">
                                <i class="ph-question-circle display-4"></i>
                                <p class="mt-2">Belum ada soal. Klik "Tambah Soal" untuk memulai.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('teacher.tasks.management') }}" class="btn btn-secondary">
                                <i class="ph-arrow-left me-1"></i>
                                Kembali
                            </a>
                            <div>
                                <button type="button" class="btn btn-outline-primary me-2" onclick="previewQuestions()">
                                    <i class="ph-eye me-1"></i>
                                    Preview
                                </button>
                                <button type="submit" class="btn btn-success" id="submitBtn" disabled>
                                    <i class="ph-check me-1"></i>
                                    Simpan Tugas
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Soal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Preview content will be loaded here -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<!-- Quill Editor CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link href="https://cdn.quilljs.com/1.3.6/quill.bubble.css" rel="stylesheet">

<style>
.question-card {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    background: #f8f9fa;
}

/* Quill Editor Modern Styles */
.quill-editor-modern {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    background: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: all 0.3s ease;
}

.quill-editor-modern:hover {
    border-color: #007bff;
    box-shadow: 0 4px 12px rgba(0,123,255,0.15);
}

.quill-editor-modern .ql-toolbar {
    border-bottom: 2px solid #f8f9fa;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 12px 16px;
    border-radius: 10px 10px 0 0;
}

.quill-editor-modern .ql-toolbar .ql-formats {
    margin-right: 16px;
}

.quill-editor-modern .ql-toolbar button {
    border-radius: 6px;
    padding: 6px 8px;
    margin: 0 2px;
    transition: all 0.2s ease;
}

.quill-editor-modern .ql-toolbar button:hover {
    background: #007bff;
    color: white;
}

.quill-editor-modern .ql-toolbar button.ql-active {
    background: #007bff;
    color: white;
}

.quill-editor-modern .ql-container {
    border: none;
    font-size: 15px;
    line-height: 1.6;
}

.quill-editor-modern .ql-editor {
    min-height: 100px;
    padding: 20px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.quill-editor-modern .ql-editor.ql-blank::before {
    color: #6c757d;
    font-style: italic;
    font-size: 15px;
    left: 20px;
    right: 20px;
}

.quill-editor-modern .ql-editor h1 {
    font-size: 24px;
    font-weight: 700;
    margin: 16px 0 12px 0;
    color: #212529;
}

.quill-editor-modern .ql-editor h2 {
    font-size: 20px;
    font-weight: 600;
    margin: 14px 0 10px 0;
    color: #212529;
}

.quill-editor-modern .ql-editor h3 {
    font-size: 18px;
    font-weight: 600;
    margin: 12px 0 8px 0;
    color: #212529;
}

.quill-editor-modern .ql-editor p {
    margin: 8px 0;
    color: #495057;
}

.quill-editor-modern .ql-editor ul, .quill-editor-modern .ql-editor ol {
    margin: 12px 0;
    padding-left: 24px;
}

.quill-editor-modern .ql-editor li {
    margin: 4px 0;
    color: #495057;
}

.quill-editor-modern .ql-editor a {
    color: #007bff;
    text-decoration: none;
    border-bottom: 1px solid transparent;
    transition: all 0.2s ease;
}

.quill-editor-modern .ql-editor a:hover {
    border-bottom-color: #007bff;
}

/* Image and Video Styles */
.quill-editor-modern .ql-editor img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 12px 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.quill-editor-modern .ql-editor video {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 12px 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.quill-editor-modern .ql-editor blockquote {
    border-left: 4px solid #007bff;
    padding-left: 16px;
    margin: 16px 0;
    font-style: italic;
    color: #6c757d;
    background: #f8f9fa;
    padding: 12px 16px;
    border-radius: 0 8px 8px 0;
}

.quill-editor-modern .ql-editor code {
    background: #f8f9fa;
    padding: 2px 6px;
    border-radius: 4px;
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 13px;
    color: #e83e8c;
}

.quill-editor-modern .ql-editor pre {
    background: #f8f9fa;
    padding: 16px;
    border-radius: 8px;
    margin: 12px 0;
    overflow-x: auto;
    border: 1px solid #e9ecef;
}

.quill-editor-modern .ql-editor pre code {
    background: none;
    padding: 0;
    color: #495057;
}

.question-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #dee2e6;
}

.question-number {
    font-weight: 600;
    color: #495057;
}

.option-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    padding: 8px;
    background: white;
    border-radius: 4px;
    border: 1px solid #e9ecef;
}

.option-radio {
    margin-right: 10px;
}

.option-input {
    flex: 1;
    border: none;
    outline: none;
    background: transparent;
}

.remove-option-btn {
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 4px 8px;
    margin-left: 10px;
    cursor: pointer;
}

.remove-option-btn:hover {
    background: #c82333;
}

.add-option-btn {
    background: #28a745;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 6px 12px;
    cursor: pointer;
}

.add-option-btn:hover {
    background: #218838;
}

.remove-question-btn {
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 6px 12px;
    cursor: pointer;
}

.remove-question-btn:hover {
    background: #c82333;
}

#no-questions-message {
    text-align: center;
    padding: 40px;
    color: #6c757d;
}
</style>
@endpush

@push('scripts')
<!-- Quill Editor JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
// Prevent variable conflicts
(function() {
    'use strict';
    
    let questionCount = 0;
    let quillEditors = {}; // Store Quill editor instances

    // Initialize with one question
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, checking Quill availability...');
        
        // Set up MutationObserver to watch for new questions
        const questionsContainer = document.getElementById('questions-container');
        if (questionsContainer) {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList') {
                        mutation.addedNodes.forEach(function(node) {
                            if (node.nodeType === 1 && node.classList && node.classList.contains('question-card')) {
                                const questionNum = node.dataset.question;
                                if (questionNum && !quillEditors[questionNum]) {
                                    console.log(`New question detected: ${questionNum}, initializing Quill...`);
                                    requestAnimationFrame(() => {
                                        initializeQuillEditor(parseInt(questionNum));
                                    });
                                }
                            }
                        });
                    }
                });
            });
            
            observer.observe(questionsContainer, {
                childList: true,
                subtree: true
            });
        }
        
        // Check if Quill is available
        if (typeof Quill !== 'undefined') {
            console.log('Quill is available, adding first question...');
            // Use requestAnimationFrame to ensure DOM is ready
            requestAnimationFrame(() => {
                addQuestion();
            });
        } else {
            console.log('Quill not available, waiting...');
            // Wait for Quill to load with better timing
            const checkQuill = () => {
                if (typeof Quill !== 'undefined') {
                    console.log('Quill loaded, adding first question...');
                    requestAnimationFrame(() => {
                        addQuestion();
                    });
                } else {
                    console.log('Quill still not available, retrying...');
                    setTimeout(checkQuill, 500);
                }
            };
            setTimeout(checkQuill, 500);
        }
    });

function addQuestion() {
    questionCount++;
    const container = document.getElementById('questions-container');
    const noQuestionsMessage = document.getElementById('no-questions-message');
    
    // Hide no questions message
    noQuestionsMessage.style.display = 'none';
    
    const questionHTML = `
        <div class="question-card" data-question="${questionCount}">
            <div class="question-header">
                <span class="question-number">Soal ${questionCount}</span>
                <button type="button" class="remove-question-btn" onclick="removeQuestion(${questionCount})">
                    <i class="ph-trash me-1"></i>
                    Hapus Soal
                </button>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Pertanyaan <span class="text-danger">*</span></label>
                <div id="quill-editor-${questionCount}" class="quill-editor-modern" style="height: 150px;"></div>
                <textarea name="questions[${questionCount}][question]" id="quill-textarea-${questionCount}" style="display: none;" required></textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Pilihan Jawaban <span class="text-danger">*</span></label>
                <div id="options-${questionCount}">
                    <div class="option-item">
                        <input type="radio" name="questions[${questionCount}][correct_answer]" value="1" class="option-radio" required>
                        <input type="text" name="questions[${questionCount}][options][1]" class="option-input" 
                               placeholder="Pilihan 1" required>
                        <button type="button" class="remove-option-btn" onclick="removeOption(this)" style="display: none;">
                            <i class="ph-x"></i>
                        </button>
                    </div>
                    <div class="option-item">
                        <input type="radio" name="questions[${questionCount}][correct_answer]" value="2" class="option-radio" required>
                        <input type="text" name="questions[${questionCount}][options][2]" class="option-input" 
                               placeholder="Pilihan 2" required>
                        <button type="button" class="remove-option-btn" onclick="removeOption(this)" style="display: none;">
                            <i class="ph-x"></i>
                        </button>
                    </div>
                    <div class="option-item">
                        <input type="radio" name="questions[${questionCount}][correct_answer]" value="3" class="option-radio" required>
                        <input type="text" name="questions[${questionCount}][options][3]" class="option-input" 
                               placeholder="Pilihan 3" required>
                        <button type="button" class="remove-option-btn" onclick="removeOption(this)" style="display: none;">
                            <i class="ph-x"></i>
                        </button>
                    </div>
                    <div class="option-item">
                        <input type="radio" name="questions[${questionCount}][correct_answer]" value="4" class="option-radio" required>
                        <input type="text" name="questions[${questionCount}][options][4]" class="option-input" 
                               placeholder="Pilihan 4" required>
                        <button type="button" class="remove-option-btn" onclick="removeOption(this)" style="display: none;">
                            <i class="ph-x"></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="add-option-btn mt-2" onclick="addOption(${questionCount})">
                    <i class="ph-plus me-1"></i>
                    Tambah Pilihan
                </button>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Poin <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="questions[${questionCount}][points]" 
                               value="1" min="1" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select class="form-select" name="questions[${questionCount}][category]">
                            <option value="easy">Mudah</option>
                            <option value="medium">Sedang</option>
                            <option value="hard">Sulit</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', questionHTML);
    
    // Fallback: Initialize Quill editor if MutationObserver doesn't catch it
    // This ensures compatibility with older browsers or if MutationObserver fails
    setTimeout(() => {
        if (!quillEditors[questionCount]) {
            console.log(`Fallback: Initializing Quill editor for question ${questionCount}`);
            initializeQuillEditor(questionCount);
        }
    }, 100);
    
    updateSubmitButton();
}

function removeQuestion(questionNum) {
    const question = document.querySelector(`[data-question="${questionNum}"]`);
    if (question) {
        // Destroy Quill editor instance if it exists
        if (quillEditors[questionNum]) {
            quillEditors[questionNum].destroy();
            delete quillEditors[questionNum];
        }
        
        question.remove();
        updateQuestionNumbers();
        updateSubmitButton();
        
        // Show no questions message if no questions left
        const container = document.getElementById('questions-container');
        if (container.children.length === 0) {
            document.getElementById('no-questions-message').style.display = 'block';
        }
    }
}

function initializeQuillEditor(questionNum) {
    const editorId = `quill-editor-${questionNum}`;
    const textareaId = `quill-textarea-${questionNum}`;
    
    console.log(`Initializing Quill editor for question ${questionNum} (attempt 1)`);
    
    // Check if editor already exists
    if (quillEditors[questionNum]) {
        console.log(`Editor ${questionNum} already exists`);
        return;
    }
    
    // Check if Quill is available
    if (typeof Quill === 'undefined') {
        console.log('Quill is not loaded, using textarea');
        return;
    }
    
    // Check if element exists with retry mechanism
    const editorElement = document.getElementById(editorId);
    const textareaElement = document.getElementById(textareaId);
    
    if (!editorElement || !textareaElement) {
        console.log(`Elements not found for question ${questionNum}, retrying in 500ms...`);
        // Retry after a short delay
        setTimeout(() => {
            const retryEditorElement = document.getElementById(editorId);
            const retryTextareaElement = document.getElementById(textareaId);
            
            if (!retryEditorElement || !retryTextareaElement) {
                console.log(`Elements not found for question ${questionNum} after retry, trying again...`);
                setTimeout(() => {
                    const finalEditorElement = document.getElementById(editorId);
                    const finalTextareaElement = document.getElementById(textareaId);
                    
                    if (!finalEditorElement || !finalTextareaElement) {
                        console.error(`Elements not found for question ${questionNum} after 3 attempts`);
                        return;
                    }
                    
                    // Try to initialize with found elements
                    initializeQuillWithElements(questionNum, finalEditorElement, finalTextareaElement);
                }, 500);
            } else {
                // Try to initialize with found elements
                initializeQuillWithElements(questionNum, retryEditorElement, retryTextareaElement);
            }
        }, 500);
        return;
    }
    
    // Initialize with found elements
    initializeQuillWithElements(questionNum, editorElement, textareaElement);
}

function initializeQuillWithElements(questionNum, editorElement, textareaElement) {
    const editorId = `quill-editor-${questionNum}`;
    const textareaId = `quill-textarea-${questionNum}`;
    
    console.log(`Initializing Quill editor for question ${questionNum} with elements found`);
    console.log(`Editor element:`, editorElement);
    console.log(`Textarea element:`, textareaElement);
    
    try {
        // Initialize Quill editor with modern toolbar
        const quill = new Quill(`#${editorId}`, {
            theme: 'snow',
            placeholder: 'Masukkan pertanyaan...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    [{ 'font': [] }],
                    [{ 'size': ['small', false, 'large', 'huge'] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'direction': 'rtl' }],
                    [{ 'align': [] }],
                    ['blockquote', 'code-block'],
                    ['link', 'image', 'video'],
                    ['clean']
                ],
                keyboard: {
                    bindings: {
                        tab: {
                            key: 9,
                            handler: function(range, context) {
                                return true; // Allow default tab behavior
                            }
                        }
                    }
                }
            }
        });
        
        // Store editor instance
        quillEditors[questionNum] = quill;
        
        // Update textarea when Quill content changes
        quill.on('text-change', function() {
            const content = quill.root.innerHTML;
            textareaElement.value = content;
        });
        
        // Copy existing textarea content to Quill
        if (textareaElement.value) {
            quill.root.innerHTML = textareaElement.value;
        }
        
        // Add focus effect
        quill.on('selection-change', function(range) {
            if (range) {
                editorElement.style.borderColor = '#007bff';
                editorElement.style.boxShadow = '0 4px 12px rgba(0,123,255,0.15)';
            } else {
                editorElement.style.borderColor = '#e9ecef';
                editorElement.style.boxShadow = '0 2px 8px rgba(0,0,0,0.1)';
            }
        });
        
        // Handle image upload
        const toolbar = quill.getModule('toolbar');
        toolbar.addHandler('image', function() {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();
            
            input.onchange = function() {
                const file = input.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function() {
                        const range = quill.getSelection();
                        quill.insertEmbed(range.index, 'image', reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            };
        });
        
        // Handle video upload
        toolbar.addHandler('video', function() {
            const url = prompt('Masukkan URL video:');
            if (url) {
                const range = quill.getSelection();
                quill.insertEmbed(range.index, 'video', url);
            }
        });
        
        console.log(`Quill editor ${questionNum} initialized successfully`);
    } catch (error) {
        console.error(`Error initializing Quill editor ${questionNum}:`, error);
        console.error('Error details:', error.message, error.stack);
        
        // Show textarea as fallback
        textareaElement.style.display = 'block';
        textareaElement.placeholder = 'Masukkan pertanyaan...';
        textareaElement.rows = 3;
        
        // Add a visual indicator that this is a fallback
        const fallbackIndicator = document.createElement('div');
        fallbackIndicator.className = 'alert alert-warning mt-2';
        fallbackIndicator.innerHTML = '<small><i class="ph-warning me-1"></i>Rich text editor tidak tersedia, menggunakan textarea biasa</small>';
        textareaElement.parentNode.insertBefore(fallbackIndicator, textareaElement.nextSibling);
    }
}

function addOption(questionNum) {
    const optionsContainer = document.getElementById(`options-${questionNum}`);
    const optionCount = optionsContainer.children.length;
    const optionLetters = ['A', 'B', 'C', 'D', 'E', 'F'];
    
    if (optionCount >= 6) {
        alert('Maksimal 6 pilihan jawaban');
        return;
    }
    
    const nextLetter = optionLetters[optionCount];
    const optionHTML = `
        <div class="option-item">
            <input type="radio" name="questions[${questionNum}][correct_answer]" value="${nextLetter}" class="option-radio" required>
            <input type="text" name="questions[${questionNum}][options][${nextLetter}]" class="option-input" 
                   placeholder="Pilihan ${nextLetter}" required>
            <button type="button" class="remove-option-btn" onclick="removeOption(this)">
                <i class="ph-x"></i>
            </button>
        </div>
    `;
    
    optionsContainer.insertAdjacentHTML('beforeend', optionHTML);
    updateRemoveButtons(questionNum);
}

function removeOption(button) {
    const optionItem = button.closest('.option-item');
    const optionsContainer = optionItem.parentElement;
    
    if (optionsContainer.children.length <= 2) {
        alert('Minimal 2 pilihan jawaban');
        return;
    }
    
    optionItem.remove();
    updateRemoveButtons(questionNum);
}

function updateRemoveButtons(questionNum) {
    const optionsContainer = document.getElementById(`options-${questionNum}`);
    const removeButtons = optionsContainer.querySelectorAll('.remove-option-btn');
    
    removeButtons.forEach((button, index) => {
        if (optionsContainer.children.length <= 2) {
            button.style.display = 'none';
        } else {
            button.style.display = 'inline-block';
        }
    });
}

function updateQuestionNumbers() {
    const questions = document.querySelectorAll('.question-card');
    questions.forEach((question, index) => {
        const questionNumber = question.querySelector('.question-number');
        questionNumber.textContent = `Soal ${index + 1}`;
    });
}

function updateSubmitButton() {
    const submitBtn = document.getElementById('submitBtn');
    const questions = document.querySelectorAll('.question-card');
    
    if (questions.length > 0) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="ph-check me-1"></i> Simpan Tugas';
    } else {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ph-check me-1"></i> Tambah Soal Terlebih Dahulu';
    }
}

function previewQuestions() {
    const questions = document.querySelectorAll('.question-card');
    if (questions.length === 0) {
        alert('Belum ada soal untuk di-preview');
        return;
    }
    
    let previewHTML = '<div class="preview-questions">';
    
    questions.forEach((question, index) => {
        // Get content from textarea (which is always available)
        const textarea = question.querySelector('textarea[name*="[question]"]');
        const questionText = textarea ? textarea.value : '';
        
        const options = question.querySelectorAll('.option-item');
        
        previewHTML += `
            <div class="preview-question mb-4">
                <h6>Soal ${index + 1}</h6>
                <div class="mb-3">${questionText || 'Pertanyaan belum diisi'}</div>
                <div class="preview-options">
        `;
        
        options.forEach((option, optIndex) => {
            const optionText = option.querySelector('.option-input').value;
            const isCorrect = option.querySelector('.option-radio').checked;
            const optionLetter = String.fromCharCode(65 + optIndex);
            
            previewHTML += `
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" disabled>
                    <label class="form-check-label ${isCorrect ? 'text-success fw-bold' : ''}">
                        ${optionLetter}. ${optionText || 'Pilihan belum diisi'}
                        ${isCorrect ? ' ✓' : ''}
                    </label>
                </div>
            `;
        });
        
        previewHTML += `
                </div>
            </div>
        `;
    });
    
    previewHTML += '</div>';
    
    document.getElementById('previewContent').innerHTML = previewHTML;
    new bootstrap.Modal(document.getElementById('previewModal')).show();
}

// Form validation
document.getElementById('multipleChoiceForm').addEventListener('submit', function(e) {
    const questions = document.querySelectorAll('.question-card');
    
    if (questions.length === 0) {
        e.preventDefault();
        alert('Minimal harus ada 1 soal');
        return;
    }
    
    // Validate each question
    let isValid = true;
    questions.forEach((question, index) => {
        // Get content from textarea (which is always available)
        const textarea = question.querySelector('textarea[name*="[question]"]');
        const questionText = textarea ? textarea.value.trim() : '';
        
        const options = question.querySelectorAll('.option-input');
        const correctAnswer = question.querySelector('input[name*="[correct_answer]"]:checked');
        
        if (!questionText) {
            alert(`Soal ${index + 1}: Pertanyaan harus diisi`);
            isValid = false;
            return;
        }
        
        if (!correctAnswer) {
            alert(`Soal ${index + 1}: Pilih jawaban yang benar`);
            isValid = false;
            return;
        }
        
        let hasEmptyOption = false;
        options.forEach(option => {
            if (!option.value.trim()) {
                hasEmptyOption = true;
            }
        });
        
        if (hasEmptyOption) {
            alert(`Soal ${index + 1}: Semua pilihan jawaban harus diisi`);
            isValid = false;
            return;
        }
    });
    
    if (!isValid) {
        e.preventDefault();
    }
});

})(); // End of function wrapper
</script>
@endpush
