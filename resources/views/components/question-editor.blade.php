{{-- Question Editor Component - Specialized for creating questions --}}
<div class="question-editor-container" data-question-type="{{ $questionType ?? 'essay' }}">
    <!-- Question Header -->
    <div class="question-header">
        <div class="question-number">
            <span class="question-label">Soal {{ $questionNumber ?? '1' }}</span>
            <div class="question-points">
                <label for="points_{{ $questionNumber ?? '1' }}">Nilai:</label>
                <input type="number" id="points_{{ $questionNumber ?? '1' }}" 
                       name="points[]" value="{{ $points ?? 10 }}" 
                       min="1" max="100" class="points-input">
            </div>
        </div>
        <div class="question-type-selector">
            <select name="question_type[]" class="type-select">
                <option value="essay" {{ ($questionType ?? 'essay') == 'essay' ? 'selected' : '' }}>Essay</option>
                <option value="multiple_choice" {{ ($questionType ?? 'essay') == 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda</option>
                <option value="true_false" {{ ($questionType ?? 'essay') == 'true_false' ? 'selected' : '' }}>Benar/Salah</option>
                <option value="fill_blank" {{ ($questionType ?? 'essay') == 'fill_blank' ? 'selected' : '' }}>Isian Singkat</option>
            </select>
        </div>
    </div>

    <!-- Question Content Editor -->
    <div class="question-content-editor">
        <label class="editor-label">Pertanyaan:</label>
        @include('components.modern-quill-editor', [
            'editorId' => 'question_' . ($questionNumber ?? '1'),
            'name' => 'question_content[]',
            'content' => $questionContent ?? '',
            'placeholder' => 'Tuliskan pertanyaan di sini...',
            'height' => '200px',
            'showQuestionTools' => true
        ])
    </div>

    <!-- Answer Section -->
    <div class="answer-section" id="answer_section_{{ $questionNumber ?? '1' }}">
        @if(($questionType ?? 'essay') == 'multiple_choice')
            <!-- Multiple Choice Options -->
            <div class="multiple-choice-options">
                <label class="editor-label">Pilihan Jawaban:</label>
                <div class="options-container">
                    @for($i = 1; $i <= 4; $i++)
                    <div class="option-item">
                        <div class="option-header">
                            <span class="option-label">{{ $i }}.</span>
                            <div class="option-actions">
                                <button type="button" class="btn-remove-option" title="Hapus Opsi">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="option-editor">
                            @include('components.modern-quill-editor', [
                                'editorId' => 'option_' . ($questionNumber ?? '1') . '_' . $i,
                                'name' => 'option_content[]',
                                'content' => $options[$i-1] ?? '',
                                'placeholder' => 'Tuliskan opsi ' . $i . '...',
                                'height' => '100px'
                            ])
                        </div>
                        <div class="option-correct">
                            <label class="correct-label">
                                <input type="radio" name="correct_answer_{{ $questionNumber ?? '1' }}" 
                                       value="{{ $i }}" class="correct-radio">
                                <span class="checkmark"></span>
                                Jawaban Benar
                            </label>
                        </div>
                    </div>
                    @endfor
                </div>
                <button type="button" class="btn-add-option">
                    <i class="fas fa-plus"></i> Tambah Opsi
                </button>
            </div>
        @elseif(($questionType ?? 'essay') == 'true_false')
            <!-- True/False Options -->
            <div class="true-false-options">
                <label class="editor-label">Jawaban:</label>
                <div class="tf-options">
                    <label class="tf-option">
                        <input type="radio" name="correct_answer_{{ $questionNumber ?? '1' }}" value="true" class="tf-radio">
                        <span class="tf-label">
                            <i class="fas fa-check-circle"></i>
                            Benar
                        </span>
                    </label>
                    <label class="tf-option">
                        <input type="radio" name="correct_answer_{{ $questionNumber ?? '1' }}" value="false" class="tf-radio">
                        <span class="tf-label">
                            <i class="fas fa-times-circle"></i>
                            Salah
                        </span>
                    </label>
                </div>
            </div>
        @elseif(($questionType ?? 'essay') == 'fill_blank')
            <!-- Fill in the Blank -->
            <div class="fill-blank-options">
                <label class="editor-label">Jawaban yang Benar:</label>
                <div class="blank-answers">
                    <input type="text" name="correct_answer_{{ $questionNumber ?? '1' }}" 
                           class="blank-input" placeholder="Masukkan jawaban yang benar...">
                    <button type="button" class="btn-add-answer">
                        <i class="fas fa-plus"></i> Tambah Jawaban
                    </button>
                </div>
            </div>
        @else
            <!-- Essay - No specific answer format needed -->
            <div class="essay-answer">
                <label class="editor-label">Petunjuk Jawaban (Opsional):</label>
                @include('components.modern-quill-editor', [
                    'editorId' => 'answer_guide_' . ($questionNumber ?? '1'),
                    'name' => 'answer_guide[]',
                    'content' => $answerGuide ?? '',
                    'placeholder' => 'Berikan petunjuk atau contoh jawaban...',
                    'height' => '150px'
                ])
            </div>
        @endif
    </div>

    <!-- Question Actions -->
    <div class="question-actions">
        <button type="button" class="btn-duplicate-question" title="Duplikat Soal">
            <i class="fas fa-copy"></i> Duplikat
        </button>
        <button type="button" class="btn-remove-question" title="Hapus Soal">
            <i class="fas fa-trash"></i> Hapus
        </button>
        <button type="button" class="btn-preview-question" title="Preview Soal">
            <i class="fas fa-eye"></i> Preview
        </button>
    </div>
</div>

<style>
.question-editor-container {
    background: #ffffff;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.question-editor-container:hover {
    border-color: #3b82f6;
    box-shadow: 0 8px 25px -5px rgba(59, 130, 246, 0.1);
}

.question-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f1f5f9;
}

.question-number {
    display: flex;
    align-items: center;
    gap: 16px;
}

.question-label {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    background: #3b82f6;
    color: white;
    padding: 8px 16px;
    border-radius: 8px;
}

.question-points {
    display: flex;
    align-items: center;
    gap: 8px;
}

.question-points label {
    font-weight: 600;
    color: #64748b;
}

.points-input {
    width: 80px;
    padding: 8px 12px;
    border: 2px solid #e2e8f0;
    border-radius: 6px;
    font-weight: 600;
    text-align: center;
    transition: all 0.2s ease;
}

.points-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.type-select {
    padding: 8px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    background: white;
    font-weight: 600;
    color: #1e293b;
    cursor: pointer;
    transition: all 0.2s ease;
}

.type-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.question-content-editor {
    margin-bottom: 24px;
}

.editor-label {
    display: block;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 12px;
    font-size: 1rem;
}

.answer-section {
    margin-bottom: 24px;
}

.multiple-choice-options .options-container {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.option-item {
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
}

.option-item:hover {
    border-color: #3b82f6;
    box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1);
}

.option-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.option-label {
    font-weight: 700;
    font-size: 1.125rem;
    color: #3b82f6;
    background: #dbeafe;
    padding: 8px 12px;
    border-radius: 6px;
    min-width: 40px;
    text-align: center;
}

.option-actions {
    display: flex;
    gap: 8px;
}

.btn-remove-option {
    background: #ef4444;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 12px;
}

.btn-remove-option:hover {
    background: #dc2626;
    transform: translateY(-1px);
}

.option-editor {
    margin-bottom: 16px;
}

.option-correct {
    display: flex;
    align-items: center;
    gap: 12px;
}

.correct-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-weight: 600;
    color: #1e293b;
    padding: 8px 16px;
    background: #f1f5f9;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.correct-label:hover {
    background: #e2e8f0;
}

.correct-radio {
    display: none;
}

.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid #cbd5e1;
    border-radius: 50%;
    position: relative;
    transition: all 0.2s ease;
}

.correct-radio:checked + .checkmark {
    background: #10b981;
    border-color: #10b981;
}

.correct-radio:checked + .checkmark::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-weight: bold;
    font-size: 12px;
}

.btn-add-option {
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
    margin-top: 16px;
}

.btn-add-option:hover {
    background: #2563eb;
    transform: translateY(-1px);
}

.true-false-options {
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
}

.tf-options {
    display: flex;
    gap: 24px;
}

.tf-option {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    padding: 16px 24px;
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    transition: all 0.2s ease;
    flex: 1;
}

.tf-option:hover {
    border-color: #3b82f6;
    box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1);
}

.tf-option input[type="radio"] {
    display: none;
}

.tf-option input[type="radio"]:checked + .tf-label {
    color: #10b981;
    font-weight: 700;
}

.tf-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #1e293b;
    transition: all 0.2s ease;
}

.fill-blank-options {
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
}

.blank-answers {
    display: flex;
    gap: 12px;
    align-items: center;
}

.blank-input {
    flex: 1;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.2s ease;
}

.blank-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.btn-add-answer {
    background: #10b981;
    color: white;
    border: none;
    padding: 12px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-add-answer:hover {
    background: #059669;
    transform: translateY(-1px);
}

.essay-answer {
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
}

.question-actions {
    display: flex;
    gap: 12px;
    padding-top: 16px;
    border-top: 2px solid #f1f5f9;
}

.question-actions button {
    background: #f1f5f9;
    color: #64748b;
    border: 2px solid #e2e8f0;
    padding: 10px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.question-actions button:hover {
    background: #e2e8f0;
    color: #334155;
    transform: translateY(-1px);
}

.btn-remove-question:hover {
    background: #fef2f2;
    color: #dc2626;
    border-color: #fecaca;
}

.btn-duplicate-question:hover {
    background: #f0f9ff;
    color: #2563eb;
    border-color: #bfdbfe;
}

.btn-preview-question:hover {
    background: #f0fdf4;
    color: #16a34a;
    border-color: #bbf7d0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .question-editor-container {
        padding: 16px;
        margin-bottom: 16px;
    }
    
    .question-header {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }
    
    .question-number {
        flex-direction: column;
        gap: 8px;
        align-items: flex-start;
    }
    
    .tf-options {
        flex-direction: column;
        gap: 12px;
    }
    
    .blank-answers {
        flex-direction: column;
        align-items: stretch;
    }
    
    .question-actions {
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .question-actions button {
        flex: 1;
        justify-content: center;
        min-width: 120px;
    }
}

@media (max-width: 480px) {
    .question-editor-container {
        padding: 12px;
    }
    
    .option-item {
        padding: 12px;
    }
    
    .question-actions button {
        font-size: 12px;
        padding: 8px 12px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const questionContainer = document.querySelector('.question-editor-container');
    const questionType = questionContainer.dataset.questionType;
    
    // Handle question type change
    const typeSelect = questionContainer.querySelector('.type-select');
    if (typeSelect) {
        typeSelect.addEventListener('change', function() {
            const newType = this.value;
            updateAnswerSection(newType);
        });
    }
    
    // Update answer section based on question type
    function updateAnswerSection(type) {
        const answerSection = questionContainer.querySelector('.answer-section');
        const questionNumber = questionContainer.querySelector('.question-label').textContent.split(' ')[1];
        
        let newContent = '';
        
        switch(type) {
            case 'multiple_choice':
                newContent = `
                    <div class="multiple-choice-options">
                        <label class="editor-label">Pilihan Jawaban:</label>
                        <div class="options-container">
                            ${Array.from({length: 4}, (_, i) => `
                                <div class="option-item">
                                    <div class="option-header">
                                        <span class="option-label">${i + 1}.</span>
                                        <div class="option-actions">
                                            <button type="button" class="btn-remove-option" title="Hapus Opsi">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="option-editor">
                                        <div class="modern-quill-editor" data-editor-id="option_${questionNumber}_${i+1}">
                                            <div class="quill-toolbar">
                                                <!-- Toolbar will be generated by Quill -->
                                            </div>
                                            <div class="quill-editor-container">
                                                <div id="option_${questionNumber}_${i+1}" class="quill-editor" style="min-height: 100px;">
                                                </div>
                                            </div>
                                            <textarea name="option_content[]" class="d-none"></textarea>
                                        </div>
                                    </div>
                                    <div class="option-correct">
                                        <label class="correct-label">
                                            <input type="radio" name="correct_answer_${questionNumber}" value="${i+1}" class="correct-radio">
                                            <span class="checkmark"></span>
                                            Jawaban Benar
                                        </label>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                        <button type="button" class="btn-add-option">
                            <i class="fas fa-plus"></i> Tambah Opsi
                        </button>
                    </div>
                `;
                break;
                
            case 'true_false':
                newContent = `
                    <div class="true-false-options">
                        <label class="editor-label">Jawaban:</label>
                        <div class="tf-options">
                            <label class="tf-option">
                                <input type="radio" name="correct_answer_${questionNumber}" value="true" class="tf-radio">
                                <span class="tf-label">
                                    <i class="fas fa-check-circle"></i>
                                    Benar
                                </span>
                            </label>
                            <label class="tf-option">
                                <input type="radio" name="correct_answer_${questionNumber}" value="false" class="tf-radio">
                                <span class="tf-label">
                                    <i class="fas fa-times-circle"></i>
                                    Salah
                                </span>
                            </label>
                        </div>
                    </div>
                `;
                break;
                
            case 'fill_blank':
                newContent = `
                    <div class="fill-blank-options">
                        <label class="editor-label">Jawaban yang Benar:</label>
                        <div class="blank-answers">
                            <input type="text" name="correct_answer_${questionNumber}" class="blank-input" placeholder="Masukkan jawaban yang benar...">
                            <button type="button" class="btn-add-answer">
                                <i class="fas fa-plus"></i> Tambah Jawaban
                            </button>
                        </div>
                    </div>
                `;
                break;
                
            default: // essay
                newContent = `
                    <div class="essay-answer">
                        <label class="editor-label">Petunjuk Jawaban (Opsional):</label>
                        <div class="modern-quill-editor" data-editor-id="answer_guide_${questionNumber}">
                            <div class="quill-toolbar">
                                <!-- Toolbar will be generated by Quill -->
                            </div>
                            <div class="quill-editor-container">
                                <div id="answer_guide_${questionNumber}" class="quill-editor" style="min-height: 150px;">
                                </div>
                            </div>
                            <textarea name="answer_guide[]" class="d-none"></textarea>
                        </div>
                    </div>
                `;
        }
        
        answerSection.innerHTML = newContent;
        
        // Reinitialize Quill editors for new content
        initializeQuillEditors();
    }
    
    // Initialize Quill editors
    function initializeQuillEditors() {
        // This would initialize Quill for any new editors
        // Implementation depends on your Quill setup
    }
    
    // Add option functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-add-option')) {
            addNewOption();
        }
        
        if (e.target.classList.contains('btn-remove-option')) {
            e.target.closest('.option-item').remove();
        }
        
        if (e.target.classList.contains('btn-add-answer')) {
            addNewAnswer();
        }
    });
    
    function addNewOption() {
        const optionsContainer = document.querySelector('.options-container');
        const optionCount = optionsContainer.children.length;
        const questionNumber = document.querySelector('.question-label').textContent.split(' ')[1];
        
        const newOption = document.createElement('div');
        newOption.className = 'option-item';
        newOption.innerHTML = `
            <div class="option-header">
                <span class="option-label">${String.fromCharCode(65 + optionCount)}.</span>
                <div class="option-actions">
                    <button type="button" class="btn-remove-option" title="Hapus Opsi">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="option-editor">
                <div class="modern-quill-editor" data-editor-id="option_${questionNumber}_${optionCount + 1}">
                    <div class="quill-toolbar">
                        <!-- Toolbar will be generated by Quill -->
                    </div>
                    <div class="quill-editor-container">
                        <div id="option_${questionNumber}_${optionCount + 1}" class="quill-editor" style="min-height: 100px;">
                        </div>
                    </div>
                    <textarea name="option_content[]" class="d-none"></textarea>
                </div>
            </div>
            <div class="option-correct">
                <label class="correct-label">
                    <input type="radio" name="correct_answer_${questionNumber}" value="${optionCount + 1}" class="correct-radio">
                    <span class="checkmark"></span>
                    Jawaban Benar
                </label>
            </div>
        `;
        
        optionsContainer.appendChild(newOption);
        initializeQuillEditors();
    }
    
    function addNewAnswer() {
        const blankAnswers = document.querySelector('.blank-answers');
        const newInput = document.createElement('input');
        newInput.type = 'text';
        newInput.name = 'correct_answer_' + document.querySelector('.question-label').textContent.split(' ')[1] + '[]';
        newInput.className = 'blank-input';
        newInput.placeholder = 'Masukkan jawaban yang benar...';
        
        blankAnswers.insertBefore(newInput, blankAnswers.lastElementChild);
    }
});
</script>
