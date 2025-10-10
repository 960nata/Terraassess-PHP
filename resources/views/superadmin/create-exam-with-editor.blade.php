@extends('layouts.unified-layout')

@section('title', 'Buat Ujian dengan Editor Modern')

@section('content')
<div class="superadmin-container">
    <!-- Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-info">
                <h1 class="page-title">
                    <i class="fas fa-edit me-2"></i>Buat Ujian dengan Editor Modern
                </h1>
                <p class="page-description">Buat ujian dengan editor yang konsisten seperti Microsoft Word</p>
            </div>
            <a href="{{ route('superadmin.exam-management') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Exam Form -->
    <div class="exam-form-container">
        <form id="examForm" method="POST" action="{{ route('superadmin.exam-management.create') }}">
            @csrf
            
            <!-- Basic Information -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Informasi Dasar Ujian
                </h3>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="exam_title" class="form-label">Judul Ujian *</label>
                        <input type="text" id="exam_title" name="exam_title" class="form-input" 
                               placeholder="Masukkan judul ujian" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="exam_type" class="form-label">Tipe Ujian *</label>
                        <select id="exam_type" name="exam_type" class="form-select" required>
                            <option value="">Pilih tipe ujian</option>
                            <option value="mixed">Campuran (Essay + Pilihan Ganda)</option>
                            <option value="essay_only">Essay Saja</option>
                            <option value="multiple_choice_only">Pilihan Ganda Saja</option>
                        </select>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="subject_id" class="form-label">Mata Pelajaran *</label>
                        <select id="subject_id" name="subject_id" class="form-select" required>
                            <option value="">Pilih mata pelajaran</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="class_id" class="form-label">Kelas *</label>
                        <select id="class_id" name="class_id" class="form-select" required>
                            <option value="">Pilih kelas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="duration" class="form-label">Durasi (menit) *</label>
                        <input type="number" id="duration" name="duration" class="form-input" 
                               placeholder="120" min="1" max="480" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="max_attempts" class="form-label">Maksimal Percobaan</label>
                        <input type="number" id="max_attempts" name="max_attempts" class="form-input" 
                               placeholder="1" min="1" max="10" value="1">
                    </div>
                </div>

                <div class="form-group">
                    <label for="exam_description" class="form-label">Deskripsi Ujian</label>
                    @include('components.modern-quill-editor', [
                        'editorId' => 'exam_description',
                        'name' => 'exam_description',
                        'placeholder' => 'Berikan deskripsi atau instruksi ujian...',
                        'height' => '150px'
                    ])
                </div>
            </div>

            <!-- Questions Section -->
            <div class="form-section">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-question-circle"></i>
                        Soal Ujian
                    </h3>
                    <div class="section-actions">
                        <button type="button" class="btn-add-question" id="addQuestionBtn">
                            <i class="fas fa-plus"></i>
                            Tambah Soal
                        </button>
                        <button type="button" class="btn-import-questions" id="importQuestionsBtn">
                            <i class="fas fa-upload"></i>
                            Import Soal
                        </button>
                    </div>
                </div>

                <div class="questions-container" id="questionsContainer">
                    <!-- Questions will be added dynamically -->
                </div>

                <div class="questions-summary" id="questionsSummary" style="display: none;">
                    <div class="summary-card">
                        <h4>Ringkasan Soal</h4>
                        <div class="summary-stats">
                            <div class="stat-item">
                                <span class="stat-label">Total Soal:</span>
                                <span class="stat-value" id="totalQuestions">0</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Total Poin:</span>
                                <span class="stat-value" id="totalPoints">0</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Essay:</span>
                                <span class="stat-value" id="essayCount">0</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Pilihan Ganda:</span>
                                <span class="stat-value" id="multipleChoiceCount">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Section -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-cog"></i>
                    Pengaturan Ujian
                </h3>
                
                <div class="settings-grid">
                    <div class="setting-group">
                        <h4>Pengaturan Waktu</h4>
                        <div class="setting-item">
                            <label class="setting-label">
                                <input type="checkbox" name="show_timer" value="1" checked>
                                <span class="checkmark"></span>
                                Tampilkan Timer
                            </label>
                        </div>
                        <div class="setting-item">
                            <label class="setting-label">
                                <input type="checkbox" name="auto_submit" value="1">
                                <span class="checkmark"></span>
                                Submit Otomatis saat Waktu Habis
                            </label>
                        </div>
                    </div>

                    <div class="setting-group">
                        <h4>Pengaturan Jawaban</h4>
                        <div class="setting-item">
                            <label class="setting-label">
                                <input type="checkbox" name="shuffle_questions" value="1">
                                <span class="checkmark"></span>
                                Acak Urutan Soal
                            </label>
                        </div>
                        <div class="setting-item">
                            <label class="setting-label">
                                <input type="checkbox" name="shuffle_options" value="1">
                                <span class="checkmark"></span>
                                Acak Urutan Pilihan Jawaban
                            </label>
                        </div>
                    </div>

                    <div class="setting-group">
                        <h4>Pengaturan Review</h4>
                        <div class="setting-item">
                            <label class="setting-label">
                                <input type="checkbox" name="allow_review" value="1" checked>
                                <span class="checkmark"></span>
                                Izinkan Review Jawaban
                            </label>
                        </div>
                        <div class="setting-item">
                            <label class="setting-label">
                                <input type="checkbox" name="show_correct_answer" value="1">
                                <span class="checkmark"></span>
                                Tampilkan Jawaban Benar setelah Submit
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="button" class="btn-save-draft" id="saveDraftBtn">
                    <i class="fas fa-save"></i>
                    Simpan Draft
                </button>
                <button type="button" class="btn-preview" id="previewBtn">
                    <i class="fas fa-eye"></i>
                    Preview Ujian
                </button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-check"></i>
                    Buat Ujian
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.exam-form-container {
    background: rgba(30, 41, 59, 0.8);
    border-radius: 16px;
    padding: 32px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    margin-bottom: 32px;
}

.form-section {
    margin-bottom: 40px;
    padding-bottom: 32px;
    border-bottom: 2px solid #f1f5f9;
}

.form-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.section-title i {
    color: #3b82f6;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.section-actions {
    display: flex;
    gap: 12px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
    margin-bottom: 24px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 8px;
    font-size: 1rem;
}

.form-input, .form-select {
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.2s ease;
    background: rgba(30, 41, 59, 0.8);
}

.form-input:focus, .form-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.btn-add-question, .btn-import-questions {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-add-question:hover, .btn-import-questions:hover {
    background: #2563eb;
    transform: translateY(-1px);
}

.btn-import-questions {
    background: #10b981;
}

.btn-import-questions:hover {
    background: #059669;
}

.questions-container {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.questions-summary {
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 24px;
    margin-top: 24px;
}

.summary-card h4 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 16px;
}

.summary-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 16px;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    background: rgba(30, 41, 59, 0.8);
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.stat-label {
    font-weight: 600;
    color: #64748b;
}

.stat-value {
    font-weight: 700;
    color: #3b82f6;
    font-size: 1.125rem;
}

.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 32px;
}

.setting-group h4 {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 16px;
}

.setting-item {
    margin-bottom: 12px;
}

.setting-label {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    font-weight: 500;
    color: #1e293b;
    padding: 8px 0;
}

.setting-label input[type="checkbox"] {
    display: none;
}

.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid #cbd5e1;
    border-radius: 4px;
    position: relative;
    transition: all 0.2s ease;
}

.setting-label input[type="checkbox"]:checked + .checkmark {
    background: #3b82f6;
    border-color: #3b82f6;
}

.setting-label input[type="checkbox"]:checked + .checkmark::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-weight: bold;
    font-size: 12px;
}

.form-actions {
    display: flex;
    gap: 16px;
    justify-content: flex-end;
    padding-top: 32px;
    border-top: 2px solid #f1f5f9;
}

.btn-save-draft, .btn-preview, .btn-submit {
    padding: 14px 28px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 1rem;
}

.btn-save-draft {
    background: #f1f5f9;
    color: #64748b;
    border: 2px solid #e2e8f0;
}

.btn-save-draft:hover {
    background: #e2e8f0;
    color: #334155;
}

.btn-preview {
    background: #f59e0b;
    color: white;
    border: 2px solid #f59e0b;
}

.btn-preview:hover {
    background: #d97706;
    transform: translateY(-1px);
}

.btn-submit {
    background: #10b981;
    color: white;
    border: 2px solid #10b981;
}

.btn-submit:hover {
    background: #059669;
    transform: translateY(-1px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .exam-form-container {
        padding: 20px;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .section-header {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }
    
    .section-actions {
        width: 100%;
        justify-content: stretch;
    }
    
    .section-actions button {
        flex: 1;
        justify-content: center;
    }
    
    .settings-grid {
        grid-template-columns: 1fr;
        gap: 24px;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .form-actions button {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .exam-form-container {
        padding: 16px;
    }
    
    .summary-stats {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let questionCount = 0;
    const questionsContainer = document.getElementById('questionsContainer');
    const questionsSummary = document.getElementById('questionsSummary');
    
    // Add question button
    document.getElementById('addQuestionBtn').addEventListener('click', function() {
        addNewQuestion();
    });
    
    // Import questions button
    document.getElementById('importQuestionsBtn').addEventListener('click', function() {
        // Implementation for importing questions
        alert('Fitur import soal akan segera tersedia!');
    });
    
    // Add new question
    function addNewQuestion() {
        questionCount++;
        
        const questionDiv = document.createElement('div');
        questionDiv.className = 'question-item';
        questionDiv.innerHTML = `
            @include('components.question-editor', [
                'questionNumber' => '${questionCount}',
                'questionType' => 'essay',
                'points' => 10
            ])
        `;
        
        questionsContainer.appendChild(questionDiv);
        updateQuestionsSummary();
        
        // Scroll to new question
        questionDiv.scrollIntoView({ behavior: 'smooth' });
    }
    
    // Update questions summary
    function updateQuestionsSummary() {
        const totalQuestions = questionsContainer.children.length;
        const totalPoints = Array.from(questionsContainer.querySelectorAll('.points-input'))
            .reduce((sum, input) => sum + parseInt(input.value || 0), 0);
        
        const essayCount = questionsContainer.querySelectorAll('[data-question-type="essay"]').length;
        const multipleChoiceCount = questionsContainer.querySelectorAll('[data-question-type="multiple_choice"]').length;
        
        document.getElementById('totalQuestions').textContent = totalQuestions;
        document.getElementById('totalPoints').textContent = totalPoints;
        document.getElementById('essayCount').textContent = essayCount;
        document.getElementById('multipleChoiceCount').textContent = multipleChoiceCount;
        
        if (totalQuestions > 0) {
            questionsSummary.style.display = 'block';
        } else {
            questionsSummary.style.display = 'none';
        }
    }
    
    // Handle question type changes
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('type-select')) {
            const questionContainer = e.target.closest('.question-item');
            const questionType = e.target.value;
            questionContainer.setAttribute('data-question-type', questionType);
            updateQuestionsSummary();
        }
        
        if (e.target.classList.contains('points-input')) {
            updateQuestionsSummary();
        }
    });
    
    // Handle question removal
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove-question')) {
            const questionItem = e.target.closest('.question-item');
            if (confirm('Apakah Anda yakin ingin menghapus soal ini?')) {
                questionItem.remove();
                updateQuestionsSummary();
            }
        }
        
        if (e.target.closest('.btn-duplicate-question')) {
            const questionItem = e.target.closest('.question-item');
            const clonedQuestion = questionItem.cloneNode(true);
            questionCount++;
            
            // Update question number
            const questionLabel = clonedQuestion.querySelector('.question-label');
            questionLabel.textContent = `Soal ${questionCount}`;
            
            // Clear form data
            clonedQuestion.querySelectorAll('input, textarea, select').forEach(input => {
                if (input.type === 'radio' || input.type === 'checkbox') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
            });
            
            // Clear editor content
            clonedQuestion.querySelectorAll('.quill-editor').forEach(editor => {
                editor.innerHTML = '';
            });
            
            questionItem.parentNode.insertBefore(clonedQuestion, questionItem.nextSibling);
            updateQuestionsSummary();
        }
    });
    
    // Save draft functionality
    document.getElementById('saveDraftBtn').addEventListener('click', function() {
        const formData = new FormData(document.getElementById('examForm'));
        const examData = {
            title: formData.get('exam_title'),
            type: formData.get('exam_type'),
            subject_id: formData.get('subject_id'),
            class_id: formData.get('class_id'),
            duration: formData.get('duration'),
            description: formData.get('exam_description'),
            questions: getQuestionsData(),
            timestamp: new Date().toISOString()
        };
        
        localStorage.setItem('exam_draft', JSON.stringify(examData));
        
        // Show notification
        showNotification('Draft ujian tersimpan!', 'success');
    });
    
    // Get questions data
    function getQuestionsData() {
        const questions = [];
        const questionItems = questionsContainer.querySelectorAll('.question-item');
        
        questionItems.forEach((item, index) => {
            const questionData = {
                number: index + 1,
                type: item.querySelector('.type-select').value,
                points: parseInt(item.querySelector('.points-input').value || 0),
                content: item.querySelector('.quill-editor').innerHTML,
                options: [],
                correct_answer: ''
            };
            
            // Get options for multiple choice
            if (questionData.type === 'multiple_choice') {
                const optionInputs = item.querySelectorAll('input[name^="option_content"]');
                optionInputs.forEach(input => {
                    questionData.options.push(input.value);
                });
                
                const correctRadio = item.querySelector('input[name^="correct_answer"]:checked');
                if (correctRadio) {
                    questionData.correct_answer = correctRadio.value;
                }
            }
            
            questions.push(questionData);
        });
        
        return questions;
    }
    
    // Load draft on page load
    function loadDraft() {
        const savedDraft = localStorage.getItem('exam_draft');
        if (savedDraft) {
            const examData = JSON.parse(savedDraft);
            
            // Fill form fields
            document.getElementById('exam_title').value = examData.title || '';
            document.getElementById('exam_type').value = examData.type || '';
            document.getElementById('subject_id').value = examData.subject_id || '';
            document.getElementById('class_id').value = examData.class_id || '';
            document.getElementById('duration').value = examData.duration || '';
            
            // Load questions
            if (examData.questions && examData.questions.length > 0) {
                examData.questions.forEach(questionData => {
                    addNewQuestion();
                    // Fill question data
                    // Implementation depends on your question structure
                });
            }
        }
    }
    
    // Show notification
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#10b981' : '#3b82f6'};
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            z-index: 10000;
            font-weight: 600;
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
    
    // Initialize
    loadDraft();
});
</script>
@endsection
