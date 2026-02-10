@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Buat Ujian Campuran')

@section('styles')
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
            background: linear-gradient(45deg, #10b981, #059669);
            color: #ffffff;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .question-type {
            background: #3b82f6;
            color: #ffffff;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 500;
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

        .add-question-section {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
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
            flex: 1;
            justify-content: center;
        }

        .add-question-btn:hover {
            background: #059669;
            transform: translateY(-2px);
        }

        .add-question-btn.essay {
            background: #8b5cf6;
        }

        .add-question-btn.essay:hover {
            background: #7c3aed;
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

        .option-group {
            margin-bottom: 1rem;
        }

        .option-input {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .option-input input[type="radio"] {
            width: auto;
            margin: 0;
        }

        .option-input input[type="text"] {
            flex: 1;
        }

        .rubric-section {
            background-color: #1e293b;
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #334155;
        }

        .rubric-item {
            background-color: #2a2a3e;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid #334155;
        }

        .rubric-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .rubric-level {
            background: linear-gradient(45deg, #f59e0b, #d97706);
            color: #ffffff;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .add-rubric-btn {
            background: #f59e0b;
            color: #ffffff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .add-rubric-btn:hover {
            background: #d97706;
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
            
            .add-question-section {
                flex-direction: column;
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
</style>
@endsection

@section('content')
<div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-layer-group"></i>
                Buat Ujian Campuran
            </h1>
            <p class="page-description">Buat ujian dengan kombinasi soal pilihan ganda dan essay</p>
        </div>

        <!-- Exam Settings -->
        <div class="exam-settings">
            <h2 style="color: #ffffff; margin-bottom: 1.5rem; font-size: 1.25rem;">
                <i class="fas fa-cog me-2"></i>Pengaturan Ujian
            </h2>
            
            <form id="examForm" action="{{ route('superadmin.exam-management.create-mixed.store') }}" method="POST">
                @csrf
                
                <div class="settings-grid">
                    <div class="form-group">
                        <label for="exam_title">Judul Ujian</label>
                        <input type="text" id="exam_title" name="exam_title" placeholder="Masukkan judul ujian" value="{{ old('exam_title') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="class_id">Kelas</label>
                        <select id="class_id" name="class_id" required>
                            <option value="">Pilih kelas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject_id">Mata Pelajaran</label>
                        <select id="subject_id" name="subject_id" required>
                            <option value="">Pilih mata pelajaran</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="duration">Durasi (menit)</label>
                        <input type="number" id="duration" name="duration" placeholder="150" min="1" max="300" value="{{ old('duration') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="max_score">Nilai Maksimal</label>
                        <input type="number" id="max_score" name="max_score" placeholder="100" min="1" max="100" value="{{ old('max_score', 100) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="difficulty">Tingkat Kesulitan</label>
                        <select id="difficulty" name="difficulty" required>
                            <option value="">Pilih tingkat kesulitan</option>
                            <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>Mudah</option>
                            <option value="medium" {{ old('difficulty', 'medium') == 'medium' ? 'selected' : '' }}>Sedang</option>
                            <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>Sulit</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="exam_description">Deskripsi Ujian</label>
                    <textarea id="exam_description" name="exam_description" placeholder="Masukkan deskripsi ujian yang detail" required>{{ old('exam_description') }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="due_date">Tanggal Mulai</label>
                        <input type="datetime-local" id="due_date" name="due_date" value="{{ old('due_date') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="is_hidden">Status</label>
                        <select id="is_hidden" name="is_hidden">
                            <option value="1" {{ old('is_hidden') == '1' ? 'selected' : '' }}>Draft</option>
                            <option value="0" {{ old('is_hidden') == '0' ? 'selected' : '' }}>Publikasi</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- Questions Section -->
        <div class="question-section">
            <h2 style="color: #ffffff; margin-bottom: 1.5rem; font-size: 1.25rem;">
                <i class="fas fa-question-circle me-2"></i>Soal Ujian
            </h2>
            
            <div class="add-question-section">
                <button type="button" class="add-question-btn" onclick="addMultipleChoiceQuestion()">
                    <i class="fas fa-list-ul"></i>
                    Tambah Pilihan Ganda
                </button>
                <button type="button" class="add-question-btn essay" onclick="addEssayQuestion()">
                    <i class="fas fa-edit"></i>
                    Tambah Essay
                </button>
            </div>
            
            <div id="questionsContainer">
                <!-- Questions will be added here dynamically -->
            </div>
        </div>

        <!-- Rubric Section -->
        <div class="rubric-section">
            <h2 style="color: #ffffff; margin-bottom: 1.5rem; font-size: 1.25rem;">
                <i class="fas fa-list-check me-2"></i>Rubrik Penilaian (Untuk Soal Essay)
            </h2>
            
            <div id="rubricContainer">
                <!-- Rubric will be added here dynamically -->
            </div>
            
            <button type="button" class="add-rubric-btn" onclick="addRubric()">
                <i class="fas fa-plus"></i>
                Tambah Kriteria Penilaian
            </button>
        </div>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="button" class="btn-primary" onclick="saveExam()">
                <i class="fas fa-save"></i>
                Simpan Ujian
            </button>
            <a href="{{ route('superadmin.exam-management') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>
@section('scripts')
<!-- Quill Editor JS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
    let questionCount = 0;
    let rubricCount = 0;
    const quillEditors = {};

    function addMultipleChoiceQuestion() {
        questionCount++;
        const container = document.getElementById('questionsContainer');
        const questionHTML = `
            <div class="question-item" id="question-${questionCount}" style="background: #1e293b; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #334155;">
                <input type="hidden" name="questions[${questionCount}][type]" value="multiple_choice">
                <div class="question-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h3 class="question-title" style="color: white; margin: 0;">Soal #${questionCount} (Pilihan Ganda)</h3>
                    <div class="question-actions">
                        <button type="button" class="btn-remove" onclick="removeQuestion(${questionCount})" style="background: #ef4444; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="color: #94a3b8; display: block; margin-bottom: 5px;">Pertanyaan</label>
                    <div id="quill-editor-question-${questionCount}" style="height: 150px; background: #2a2a3e; color: white;"></div>
                    <textarea name="questions[${questionCount}][question]" id="quill-textarea-question-${questionCount}" class="hidden"></textarea>
                </div>
                <div class="options-container" style="background: #0f172a; padding: 15px; border-radius: 6px;">
                    ${['A', 'B', 'C', 'D'].map(opt => `
                        <div class="option-item" style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                            <input type="radio" name="questions[${questionCount}][correct_answer]" value="${opt}" required>
                            <span style="color: white; font-weight: bold; min-width: 20px;">${opt}.</span>
                            <input type="text" name="questions[${questionCount}][options][${opt}]" class="option-input" placeholder="Jawaban ${opt}" style="flex: 1; padding: 10px; border-radius: 4px; background: #1e293b; border: 1px solid #334155; color: white;" required>
                        </div>
                    `).join('')}
                </div>
                <div class="settings-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 20px;">
                    <div class="form-group">
                        <label style="color: #94a3b8; display: block; margin-bottom: 5px;">Poin</label>
                        <input type="number" name="questions[${questionCount}][score]" value="5" min="1" style="width: 100%; padding: 10px; border-radius: 4px; background: #1e293b; border: 1px solid #334155; color: white;" required>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', questionHTML);
        initializeQuill(`quill-editor-question-${questionCount}`, `quill-textarea-question-${questionCount}`);
    }

    function addEssayQuestion() {
        questionCount++;
        const container = document.getElementById('questionsContainer');
        const questionHTML = `
            <div class="question-item" id="question-${questionCount}" style="background: #1e293b; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #334155;">
                <input type="hidden" name="questions[${questionCount}][type]" value="essay">
                <div class="question-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h3 class="question-title" style="color: white; margin: 0;">Soal #${questionCount} (Essay)</h3>
                    <div class="question-actions">
                        <button type="button" class="btn-remove" onclick="removeQuestion(${questionCount})" style="background: #ef4444; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="color: #94a3b8; display: block; margin-bottom: 5px;">Pertanyaan</label>
                    <div id="quill-editor-question-${questionCount}" style="height: 150px; background: #2a2a3e; color: white;"></div>
                    <textarea name="questions[${questionCount}][question]" id="quill-textarea-question-${questionCount}" class="hidden"></textarea>
                </div>
                <div class="settings-grid" style="display: grid; grid-template-columns: 1fr; gap: 15px; margin-top: 20px;">
                    <div class="form-group">
                        <label style="color: #94a3b8; display: block; margin-bottom: 5px;">Poin</label>
                        <input type="number" name="questions[${questionCount}][score]" value="10" min="1" style="width: 100%; padding: 10px; border-radius: 4px; background: #1e293b; border: 1px solid #334155; color: white;" required>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', questionHTML);
        initializeQuill(`quill-editor-question-${questionCount}`, `quill-textarea-question-${questionCount}`);
    }

    function addRubric() {
        rubricCount++;
        const container = document.getElementById('rubricContainer');
        const rubricHTML = `
            <div class="rubric-item" id="rubric-${rubricCount}" style="background: #1e293b; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #334155;">
                <div class="rubric-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h4 style="color: white; margin: 0;">Kriteria #${rubricCount}</h4>
                    <button type="button" class="btn-remove" onclick="removeRubric(${rubricCount})" style="background: none; border: none; color: #ef4444; cursor: pointer;">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="settings-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label style="color: #94a3b8; display: block; margin-bottom: 5px;">Kriteria</label>
                        <input type="text" name="rubrics[${rubricCount}][name]" placeholder="Misal: Kejelasan Argumen" style="width: 100%; padding: 10px; border-radius: 4px; background: #1e293b; border: 1px solid #334155; color: white;" required>
                    </div>
                    <div class="form-group">
                        <label style="color: #94a3b8; display: block; margin-bottom: 5px;">Bobot (%)</label>
                        <input type="number" name="rubrics[${rubricCount}][weight]" value="25" min="1" max="100" style="width: 100%; padding: 10px; border-radius: 4px; background: #1e293b; border: 1px solid #334155; color: white;" required>
                    </div>
                </div>
                <div class="form-group" style="margin-top: 15px;">
                    <label style="color: #94a3b8; display: block; margin-bottom: 5px;">Deskripsi Kriteria</label>
                    <textarea name="rubrics[${rubricCount}][description]" rows="2" placeholder="Jelaskan apa yang dinilai..." style="width: 100%; padding: 10px; border-radius: 4px; background: #1e293b; border: 1px solid #334155; color: white;" required></textarea>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', rubricHTML);
    }

    function removeQuestion(id) {
        document.getElementById(`question-${id}`).remove();
    }

    function removeRubric(id) {
        document.getElementById(`rubric-${id}`).remove();
    }

    function initializeQuill(editorId, textareaId) {
        const quill = new Quill(`#${editorId}`, {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'image'],
                    ['clean']
                ]
            }
        });
        quillEditors[editorId] = quill;
        quill.on('text-change', () => {
            document.getElementById(textareaId).value = quill.root.innerHTML;
        });
        
        // Apply dark theme to toolbar
        setTimeout(() => {
            const toolbar = document.querySelector(`#${editorId}`).previousElementSibling;
            if (toolbar) {
                toolbar.style.background = '#1e293b';
                toolbar.style.borderColor = '#334155';
                toolbar.querySelectorAll('button, .ql-stroke, .ql-fill, .ql-picker').forEach(el => {
                    if (el.classList.contains('ql-stroke')) el.style.stroke = '#cbd5e1';
                    if (el.classList.contains('ql-fill')) el.style.fill = '#cbd5e1';
                    if (el.tagName === 'BUTTON') el.style.color = '#cbd5e1';
                });
            }
        }, 100);
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Restore dynamic questions
        @if(old('questions'))
            const oldQuestions = @json(old('questions'));
            Object.keys(oldQuestions).forEach(idx => {
                const q = oldQuestions[idx];
                if (q.type === 'multiple_choice') {
                    addMultipleChoiceQuestion();
                    const currentId = questionCount;
                    document.getElementById(`quill-textarea-question-${currentId}`).value = q.question;
                    quillEditors[`quill-editor-question-${currentId}`].root.innerHTML = q.question;
                    
                    if (q.options) {
                        Object.keys(q.options).forEach(opt => {
                            const optInput = document.querySelector(`input[name="questions[${currentId}][options][${opt}]"]`);
                            if (optInput) optInput.value = q.options[opt];
                        });
                    }
                    if (q.correct_answer) {
                        const radio = document.querySelector(`input[name="questions[${currentId}][correct_answer]"][value="${q.correct_answer}"]`);
                        if (radio) radio.checked = true;
                    }
                    if (q.score) document.querySelector(`input[name="questions[${currentId}][score]"]`).value = q.score;
                } else {
                    addEssayQuestion();
                    const currentId = questionCount;
                    document.getElementById(`quill-textarea-question-${currentId}`).value = q.question;
                    quillEditors[`quill-editor-question-${currentId}`].root.innerHTML = q.question;
                    if (q.score) document.querySelector(`input[name="questions[${currentId}][score]"]`).value = q.score;
                }
            });
        @endif

        // Restore rubrics
        @if(old('rubrics'))
            const oldRubrics = @json(old('rubrics'));
            Object.keys(oldRubrics).forEach(idx => {
                const r = oldRubrics[idx];
                addRubric();
                const currentId = rubricCount;
                if (r.name) document.querySelector(`input[name="rubrics[${currentId}][name]"]`).value = r.name;
                if (r.weight) document.querySelector(`input[name="rubrics[${currentId}][weight]"]`).value = r.weight;
                if (r.description) document.querySelector(`textarea[name="rubrics[${currentId}][description]"]`).value = r.description;
            });
        @endif
    });

    function saveExam() {
        document.getElementById('examForm').submit();
    }
</script>
@endsection
