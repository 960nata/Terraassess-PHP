@extends('layouts.unified-layout')

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
        <input type="hidden" name="tipe" value="{{ $tipe }}">
        
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
                @if($tugas->TugasMultiple->count() > 0)
                    @foreach($tugas->TugasMultiple as $index => $question)
                        <div class="question-item" id="existing-question-{{ $index + 1 }}">
                            <div class="question-header">
                                <h4>Soal {{ $index + 1 }}</h4>
                                <div class="question-actions">
                                    <button type="button" onclick="editExistingQuestion({{ $index + 1 }})" class="btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button type="button" onclick="removeExistingQuestion({{ $index + 1 }})" class="btn-remove">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                            
                            <div class="question-content">
                                <div class="question-text">
                                    <strong>Pertanyaan:</strong>
                                    <div class="question-preview">{!! $question->soal !!}</div>
                                    <div class="mt-2">
                                        <div id="quill-editor-question-{{ $index + 1 }}" class="quill-editor-dark" style="height: 120px;"></div>
                                        <textarea name="questions[{{ $index + 1 }}][question]" id="quill-textarea-question-{{ $index + 1 }}" style="display: none;" required>{{ $question->soal }}</textarea>
                                    </div>
                                </div>
                                
                                <div class="question-options">
                                    <strong>Pilihan Jawaban:</strong>
                                    @php
                                        $options = [
                                            'A' => $question->a,
                                            'B' => $question->b,
                                            'C' => $question->c,
                                            'D' => $question->d,
                                            'E' => $question->e
                                        ];
                                        $correctAnswer = $question->jawaban;
                                    @endphp
                                    @foreach($options as $optionLetter => $optionText)
                                        @if($optionText)
                                            <div class="option-item {{ $correctAnswer == $optionLetter ? 'correct' : '' }}">
                                                <span class="option-letter">{{ $optionLetter }}.</span>
                                                <div class="option-text-preview">{{ $optionText }}</div>
                                                <div class="mt-2">
                                                    <div id="quill-editor-option-{{ $optionLetter }}-{{ $index + 1 }}" class="quill-editor-dark" style="height: 60px;"></div>
                                                    <textarea name="questions[{{ $index + 1 }}][options][{{ strtolower($optionLetter) }}]" 
                                                              id="quill-textarea-option-{{ $optionLetter }}-{{ $index + 1 }}" 
                                                              style="display: none;" required>{{ $optionText }}</textarea>
                                                </div>
                                                @if($correctAnswer == $optionLetter)
                                                    <span class="correct-badge">✓ Benar</span>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                
                                <div class="question-meta">
                                    <span class="points">Poin: {{ $question->poin }}</span>
                                    <span class="category">Kategori: {{ $question->kategori ?? 'Medium' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="no-questions">
                        <i class="fas fa-question-circle"></i>
                        <p>Belum ada soal yang dibuat. Klik "Tambah Soal" untuk membuat soal pertama.</p>
                    </div>
                @endif
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
        <!-- Essay Questions -->
        <div class="form-section">
            <h3 class="section-title">Soal Essay</h3>
            <p class="section-description">Edit soal essay yang sudah ada atau tambah soal baru.</p>
            
            <div id="essay-questions-container">
                @if($tugas->TugasMandiri->count() > 0)
                    @foreach($tugas->TugasMandiri as $index => $question)
                        <div class="question-item" id="essay-question-{{ $index + 1 }}">
                            <div class="question-header">
                                <h4>Soal {{ $index + 1 }}</h4>
                                <div class="question-actions">
                                    <button type="button" onclick="removeEssayQuestion({{ $index + 1 }})" class="btn-remove">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                            
                            <div class="question-content">
                                <div class="question-text">
                                    <strong>Pertanyaan:</strong>
                                    <div class="question-preview">{!! $question->pertanyaan !!}</div>
                                    <div class="mt-2">
                                        <div id="quill-editor-essay-{{ $index + 1 }}" class="quill-editor-dark" style="height: 150px;"></div>
                                        <textarea name="essay_questions[{{ $index + 1 }}][question]" id="quill-textarea-essay-{{ $index + 1 }}" style="display: none;" required>{{ $question->pertanyaan }}</textarea>
                                    </div>
                                </div>
                                
                                <div class="question-meta">
                                    <label>Poin:</label>
                                    <input type="number" name="essay_questions[{{ $index + 1 }}][points]" value="{{ $question->poin }}" min="1" max="100" required style="width: 100px; padding: 0.5rem; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(51, 65, 85, 0.5); border-radius: 6px; color: #e2e8f0;">
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="no-questions">
                        <i class="fas fa-question-circle"></i>
                        <p>Belum ada soal essay. Klik "Tambah Soal" untuk membuat soal.</p>
                    </div>
                @endif
            </div>
            
            <div class="question-actions">
                <button type="button" id="add-essay-question-btn" class="btn-add-question">
                    <i class="fas fa-plus"></i>
                    Tambah Soal
                </button>
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
    color: #f8fafc;
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

/* Question Display Styles */
.question-item {
    background: rgba(15, 23, 42, 0.6);
    border: 1px solid rgba(51, 65, 85, 0.5);
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
}

.question-item:hover {
    border-color: rgba(59, 130, 246, 0.5);
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.1);
}

.question-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid rgba(51, 65, 85, 0.5);
}

.question-header h4 {
    color: #e2e8f0;
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0;
}

.question-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-edit, .btn-remove {
    padding: 0.5rem 0.75rem;
    border: none;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.btn-edit {
    background: #3b82f6;
    color: white;
}

.btn-edit:hover {
    background: #2563eb;
}

.btn-remove {
    background: #ef4444;
    color: white;
}

.btn-remove:hover {
    background: #dc2626;
}

.question-content {
    space-y: 1rem;
}

.question-text {
    margin-bottom: 1rem;
}

.question-text strong {
    color: #e2e8f0;
    display: block;
    margin-bottom: 0.5rem;
}

.question-preview {
    background: rgba(0, 0, 0, 0.3);
    padding: 1rem;
    border-radius: 6px;
    color: #cbd5e1;
    line-height: 1.6;
}

.question-options {
    margin-bottom: 1rem;
}

.question-options strong {
    color: #e2e8f0;
    display: block;
    margin-bottom: 0.75rem;
}

.option-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    margin-bottom: 0.5rem;
    background: rgba(0, 0, 0, 0.2);
    border-radius: 6px;
    border: 1px solid rgba(51, 65, 85, 0.3);
    transition: all 0.2s ease;
}

.option-item.correct {
    background: rgba(34, 197, 94, 0.1);
    border-color: rgba(34, 197, 94, 0.3);
}

.option-letter {
    font-weight: 600;
    color: #94a3b8;
    margin-right: 0.75rem;
    min-width: 1.5rem;
}

.option-text {
    flex: 1;
    color: #cbd5e1;
}

.correct-badge {
    background: #22c55e;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
    margin-left: 0.5rem;
}

.question-meta {
    display: flex;
    gap: 1rem;
    padding-top: 0.75rem;
    border-top: 1px solid rgba(51, 65, 85, 0.3);
}

.question-meta span {
    color: #94a3b8;
    font-size: 0.875rem;
}

.no-questions {
    text-align: center;
    padding: 3rem 1rem;
    color: #94a3b8;
}

.no-questions i {
    font-size: 3rem;
    margin-bottom: 1rem;
    display: block;
    opacity: 0.5;
}

.no-questions p {
    margin: 0;
    font-size: 1.1rem;
}

/* Quill Editor Styles */
.ql-editor {
    color: #ffffff;
    background: #2a2a3e;
    min-height: 120px;
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
    min-height: 60px;
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

.option-text-preview {
    color: #cbd5e1;
    margin-bottom: 0.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .question-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .question-actions {
        width: 100%;
        justify-content: flex-end;
    }
    
    .question-meta {
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>
@endsection

<script>
// Existing question management functions
function editExistingQuestion(questionId) {
    // For now, show alert. In future, this can open a modal or inline editor
    alert('Fitur edit soal individual akan segera tersedia. Untuk saat ini, gunakan fitur "Tambah Soal" untuk menambah soal baru.');
}

function removeExistingQuestion(questionId) {
    if (confirm('Apakah Anda yakin ingin menghapus soal ini?')) {
        const questionElement = document.getElementById('existing-question-' + questionId);
        if (questionElement) {
            questionElement.remove();
            
            // Update question numbers
            updateQuestionNumbers();
        }
    }
}

function updateQuestionNumbers() {
    const questions = document.querySelectorAll('.question-item');
    questions.forEach((question, index) => {
        const header = question.querySelector('.question-header h4');
        if (header) {
            header.textContent = 'Soal ' + (index + 1);
        }
        
        // Update the ID
        question.id = 'existing-question-' + (index + 1);
        
        // Update button onclick attributes
        const editBtn = question.querySelector('.btn-edit');
        const removeBtn = question.querySelector('.btn-remove');
        
        if (editBtn) {
            editBtn.setAttribute('onclick', 'editExistingQuestion(' + (index + 1) + ')');
        }
        if (removeBtn) {
            removeBtn.setAttribute('onclick', 'removeExistingQuestion(' + (index + 1) + ')');
        }
    });
}

// Quill editors management
let quillEditors = {};

// Add question functionality (placeholder)
document.addEventListener('DOMContentLoaded', function() {
    const addQuestionBtn = document.getElementById('add-question-btn');
    if (addQuestionBtn) {
        addQuestionBtn.addEventListener('click', function() {
            alert('Fitur tambah soal akan segera tersedia. Untuk saat ini, gunakan halaman pembuatan tugas untuk menambah soal.');
        });
    }
    
    const generateQuestionsBtn = document.getElementById('generate-questions-btn');
    if (generateQuestionsBtn) {
        generateQuestionsBtn.addEventListener('click', function() {
            alert('Fitur generate soal otomatis akan segera tersedia.');
        });
    }
    
    // Initialize existing Quill editors
    initializeExistingQuillEditors();
});

// Initialize Quill editors for existing questions
function initializeExistingQuillEditors() {
    @if($tipe == 1 && $tugas->TugasMultiple->count() > 0)
        @foreach($tugas->TugasMultiple as $index => $question)
            initializeQuillEditors({{ $index + 1 }});
        @endforeach
    @elseif($tipe == 2 && $tugas->TugasMandiri->count() > 0)
        @foreach($tugas->TugasMandiri as $index => $question)
            initializeEssayQuillEditor({{ $index + 1 }});
        @endforeach
    @endif
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
        
        // Set initial content
        const textarea = document.getElementById(questionTextareaId);
        if (textarea && textarea.value) {
            questionEditor.root.innerHTML = textarea.value;
        }
        
        // Update textarea when editor content changes
        questionEditor.on('text-change', function() {
            textarea.value = questionEditor.root.innerHTML;
        });
        
        // Custom image handler
        questionEditor.getModule('toolbar').addHandler('image', async function() {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();
            
            input.onchange = async function() {
                const file = input.files[0];
                if (file) {
                    // Compress and insert image
                    const compressedFile = await compressImage(file);
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const range = questionEditor.getSelection();
                        questionEditor.insertEmbed(range.index, 'image', e.target.result);
                    };
                    reader.readAsDataURL(compressedFile);
                }
            };
        });
    }
    
    // Option editors
    ['A', 'B', 'C', 'D', 'E'].forEach(option => {
        const optionEditorId = `quill-editor-option-${option}-${questionNum}`;
        const optionTextareaId = `quill-textarea-option-${option}-${questionNum}`;
        
        if (document.getElementById(optionEditorId)) {
            const optionEditor = new Quill(`#${optionEditorId}`, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        ['link'],
                        ['clean']
                    ]
                },
                placeholder: `Pilihan ${option}...`
            });
            
            quillEditors[optionEditorId] = optionEditor;
            
            // Set initial content
            const textarea = document.getElementById(optionTextareaId);
            if (textarea && textarea.value) {
                optionEditor.root.innerHTML = textarea.value;
            }
            
            // Update textarea when editor content changes
            optionEditor.on('text-change', function() {
                textarea.value = optionEditor.root.innerHTML;
            });
        }
    });
}

// Image compression function
async function compressImage(file) {
    return new Promise((resolve) => {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();
        
        img.onload = function() {
            const maxWidth = 800;
            const maxHeight = 600;
            let { width, height } = img;
            
            if (width > height) {
                if (width > maxWidth) {
                    height = (height * maxWidth) / width;
                    width = maxWidth;
                }
            } else {
                if (height > maxHeight) {
                    width = (width * maxHeight) / height;
                    height = maxHeight;
                }
            }
            
            canvas.width = width;
            canvas.height = height;
            
            ctx.drawImage(img, 0, 0, width, height);
            canvas.toBlob(resolve, 'image/jpeg', 0.8);
        };
        
        img.src = URL.createObjectURL(file);
    });
}

// Essay Question Functions
let essayQuestionCount = {{ $tipe == 2 ? $tugas->TugasMandiri->count() : 0 }};

function initializeEssayQuillEditor(questionNum) {
    const editorId = `quill-editor-essay-${questionNum}`;
    const textareaId = `quill-textarea-essay-${questionNum}`;
    
    if (document.getElementById(editorId)) {
        const isMobile = window.innerWidth <= 768;
        const editor = new Quill(`#${editorId}`, {
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
            placeholder: 'Tuliskan pertanyaan essay di sini...'
        });
        
        quillEditors[editorId] = editor;
        
        // Set initial content
        const textarea = document.getElementById(textareaId);
        if (textarea && textarea.value) {
            editor.root.innerHTML = textarea.value;
        }
        
        // Update textarea when editor content changes
        editor.on('text-change', function() {
            textarea.value = editor.root.innerHTML;
        });
        
        // Custom image handler
        editor.getModule('toolbar').addHandler('image', async function() {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();
            
            input.onchange = async function() {
                const file = input.files[0];
                if (file) {
                    const compressedFile = await compressImage(file);
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const range = editor.getSelection();
                        editor.insertEmbed(range.index, 'image', e.target.result);
                    };
                    reader.readAsDataURL(compressedFile);
                }
            };
        });
    }
}

function removeEssayQuestion(id) {
    if (confirm('Apakah Anda yakin ingin menghapus soal ini?')) {
        const element = document.getElementById(`essay-question-${id}`);
        if (element) {
            element.remove();
        }
    }
}

// Add essay question button handler
document.addEventListener('DOMContentLoaded', function() {
    const addEssayBtn = document.getElementById('add-essay-question-btn');
    if (addEssayBtn) {
        addEssayBtn.addEventListener('click', function() {
            alert('Fitur tambah soal essay akan segera tersedia. Untuk saat ini, gunakan halaman pembuatan tugas untuk menambah soal.');
        });
    }
});
</script>
