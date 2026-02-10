{{-- Student Answer Editor Component - For students to answer questions --}}
<div class="student-answer-container" data-question-id="{{ $questionId ?? '1' }}" data-question-type="{{ $questionType ?? 'essay' }}">
    <!-- Question Display -->
    <div class="question-display">
        <div class="question-header">
            <div class="question-number">
                <span class="question-label">Soal {{ $questionNumber ?? '1' }}</span>
                <span class="question-points">{{ $points ?? 10 }} poin</span>
            </div>
            <div class="question-timer" id="timer_{{ $questionId ?? '1' }}">
                <i class="fas fa-clock"></i>
                <span class="time-remaining">--:--</span>
            </div>
        </div>
        
        <div class="question-content">
            {!! $questionContent ?? '' !!}
        </div>
    </div>

    <!-- Answer Section -->
    <div class="answer-section">
        @if(($questionType ?? 'essay') == 'multiple_choice')
            <!-- Multiple Choice Answer -->
            <div class="multiple-choice-answer">
                <label class="answer-label">Pilih jawaban yang benar:</label>
                <div class="options-list">
                    @for($i = 1; $i <= 4; $i++)
                    <label class="option-item">
                        <input type="radio" name="answer_{{ $questionId ?? '1' }}" 
                               value="{{ $i }}" class="option-radio" 
                               {{ ($selectedAnswer ?? '') == $i ? 'checked' : '' }}>
                        <span class="option-label">{{ $i }}.</span>
                        <div class="option-content">
                            {!! $options[$i-1] ?? '' !!}
                        </div>
                        <span class="option-checkmark">
                            <i class="fas fa-check"></i>
                        </span>
                    </label>
                    @endfor
                </div>
            </div>
            
        @elseif(($questionType ?? 'essay') == 'true_false')
            <!-- True/False Answer -->
            <div class="true-false-answer">
                <label class="answer-label">Pilih jawaban yang benar:</label>
                <div class="tf-options">
                    <label class="tf-option">
                        <input type="radio" name="answer_{{ $questionId ?? '1' }}" 
                               value="true" class="tf-radio"
                               {{ ($selectedAnswer ?? '') == 'true' ? 'checked' : '' }}>
                        <span class="tf-label">
                            <i class="fas fa-check-circle"></i>
                            Benar
                        </span>
                    </label>
                    <label class="tf-option">
                        <input type="radio" name="answer_{{ $questionId ?? '1' }}" 
                               value="false" class="tf-radio"
                               {{ ($selectedAnswer ?? '') == 'false' ? 'checked' : '' }}>
                        <span class="tf-label">
                            <i class="fas fa-times-circle"></i>
                            Salah
                        </span>
                    </label>
                </div>
            </div>
            
        @elseif(($questionType ?? 'essay') == 'fill_blank')
            <!-- Fill in the Blank Answer -->
            <div class="fill-blank-answer">
                <label class="answer-label">Isilah jawaban yang benar:</label>
                <div class="blank-inputs">
                    <input type="text" name="answer_{{ $questionId ?? '1' }}" 
                           class="blank-input" placeholder="Tuliskan jawaban Anda di sini..."
                           value="{{ $selectedAnswer ?? '' }}">
                </div>
            </div>
            
        @else
            <!-- Essay Answer -->
            <div class="essay-answer">
                <label class="answer-label">Jawaban Anda:</label>
                @include('components.modern-quill-editor', [
                    'editorId' => 'answer_' . ($questionId ?? '1'),
                    'name' => 'answer_' . ($questionId ?? '1'),
                    'content' => $selectedAnswer ?? '',
                    'placeholder' => 'Tuliskan jawaban Anda di sini...',
                    'height' => '300px'
                ])
            </div>
        @endif
    </div>

    <!-- Answer Actions -->
    <div class="answer-actions">
        <button type="button" class="btn-save-draft" data-question-id="{{ $questionId ?? '1' }}">
            <i class="fas fa-save"></i>
            <span>Simpan Draft</span>
        </button>
        <button type="button" class="btn-clear-answer" data-question-id="{{ $questionId ?? '1' }}">
            <i class="fas fa-eraser"></i>
            <span>Hapus Jawaban</span>
        </button>
        <button type="button" class="btn-mark-review" data-question-id="{{ $questionId ?? '1' }}">
            <i class="fas fa-flag"></i>
            <span>Tandai untuk Review</span>
        </button>
    </div>

    <!-- Answer Status -->
    <div class="answer-status" id="status_{{ $questionId ?? '1' }}">
        <div class="status-indicator">
            <i class="fas fa-circle"></i>
            <span class="status-text">Belum dijawab</span>
        </div>
        <div class="word-count">
            <span class="word-count-text">Kata: <span class="word-count-number">0</span></span>
        </div>
    </div>
</div>

<style>
.student-answer-container {
    background: #ffffff;
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    position: relative;
}

.student-answer-container:hover {
    border-color: #3b82f6;
    box-shadow: 0 8px 25px -5px rgba(59, 130, 246, 0.1);
}

.student-answer-container.answered {
    border-color: #10b981;
    background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
}

.student-answer-container.review {
    border-color: #f59e0b;
    background: linear-gradient(135deg, #fef3c7 0%, #ffffff 100%);
}

.question-display {
    margin-bottom: 24px;
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
    font-size: 1rem;
    font-weight: 600;
    color: #10b981;
    background: #d1fae5;
    padding: 6px 12px;
    border-radius: 6px;
}

.question-timer {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #fef3c7;
    color: #d97706;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
}

.question-content {
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    font-size: 1.1rem;
    line-height: 1.7;
    color: #1e293b;
}

.question-content h1, .question-content h2, .question-content h3 {
    color: #1e293b;
    margin-bottom: 1rem;
}

.question-content p {
    margin-bottom: 1rem;
}

.question-content ul, .question-content ol {
    margin-bottom: 1rem;
    padding-left: 2rem;
}

.question-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1rem 0;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.answer-section {
    margin-bottom: 24px;
}

.answer-label {
    display: block;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 16px;
    font-size: 1.1rem;
}

.multiple-choice-answer .options-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.option-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px 20px;
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.option-item:hover {
    border-color: #3b82f6;
    background: #f0f9ff;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1);
}

.option-item.selected {
    border-color: #10b981;
    background: #f0fdf4;
    box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.1);
}

.option-radio {
    display: none;
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
    flex-shrink: 0;
}

.option-content {
    flex: 1;
    font-size: 1rem;
    line-height: 1.6;
    color: #1e293b;
}

.option-checkmark {
    position: absolute;
    top: 16px;
    right: 16px;
    width: 24px;
    height: 24px;
    background: #10b981;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transform: scale(0);
    transition: all 0.3s ease;
}

.option-item.selected .option-checkmark {
    opacity: 1;
    transform: scale(1);
}

.true-false-answer .tf-options {
    display: flex;
    gap: 24px;
}

.tf-option {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    padding: 20px 32px;
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    transition: all 0.3s ease;
    flex: 1;
    justify-content: center;
}

.tf-option:hover {
    border-color: #3b82f6;
    background: #f0f9ff;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1);
}

.tf-option.selected {
    border-color: #10b981;
    background: #f0fdf4;
    box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.1);
}

.tf-radio {
    display: none;
}

.tf-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #1e293b;
    font-size: 1.1rem;
    transition: all 0.2s ease;
}

.tf-option.selected .tf-label {
    color: #10b981;
}

.fill-blank-answer .blank-inputs {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.blank-input {
    width: 100%;
    padding: 16px 20px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    background: #f8fafc;
}

.blank-input:focus {
    outline: none;
    border-color: #3b82f6;
    background: white;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.essay-answer {
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
}

.answer-actions {
    display: flex;
    gap: 12px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}

.answer-actions button {
    background: #f1f5f9;
    color: #64748b;
    border: 2px solid #e2e8f0;
    padding: 12px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.answer-actions button:hover {
    background: #e2e8f0;
    color: #334155;
    transform: translateY(-1px);
}

.btn-save-draft:hover {
    background: #dbeafe;
    color: #2563eb;
    border-color: #bfdbfe;
}

.btn-clear-answer:hover {
    background: #fef2f2;
    color: #dc2626;
    border-color: #fecaca;
}

.btn-mark-review:hover {
    background: #fef3c7;
    color: #d97706;
    border-color: #fed7aa;
}

.btn-mark-review.active {
    background: #f59e0b;
    color: white;
    border-color: #f59e0b;
}

.answer-status {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.status-indicator {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
}

.status-indicator i {
    font-size: 12px;
    color: #94a3b8;
}

.status-indicator.answered i {
    color: #10b981;
}

.status-indicator.review i {
    color: #f59e0b;
}

.status-text {
    color: #64748b;
}

.word-count {
    font-size: 12px;
    color: #64748b;
}

.word-count-text {
    font-weight: 500;
}

.word-count-number {
    color: #3b82f6;
    font-weight: 600;
}

/* Responsive Design */
@media (max-width: 768px) {
    .student-answer-container {
        padding: 16px;
        margin-bottom: 16px;
    }
    
    .question-header {
        flex-direction: column;
        gap: 12px;
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
    
    .answer-actions {
        flex-direction: column;
    }
    
    .answer-actions button {
        justify-content: center;
    }
    
    .answer-status {
        flex-direction: column;
        gap: 8px;
        align-items: flex-start;
    }
}

@media (max-width: 480px) {
    .student-answer-container {
        padding: 12px;
    }
    
    .option-item {
        padding: 12px 16px;
        gap: 12px;
    }
    
    .option-label {
        min-width: 32px;
        padding: 6px 8px;
        font-size: 1rem;
    }
    
    .tf-option {
        padding: 16px 24px;
    }
    
    .answer-actions button {
        font-size: 12px;
        padding: 10px 16px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const answerContainer = document.querySelector('.student-answer-container');
    const questionId = answerContainer.dataset.questionId;
    const questionType = answerContainer.dataset.questionType;
    
    // Handle answer selection
    const answerInputs = answerContainer.querySelectorAll('input[type="radio"], input[type="text"]');
    const answerEditor = answerContainer.querySelector('.quill-editor');
    
    answerInputs.forEach(input => {
        input.addEventListener('change', function() {
            updateAnswerStatus();
            saveAnswerDraft();
        });
    });
    
    // Handle Quill editor changes
    if (answerEditor) {
        const quill = new Quill(answerEditor, {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link'],
                    ['clean']
                ]
            },
            placeholder: 'Tuliskan jawaban Anda di sini...'
        });
        
        quill.on('text-change', function() {
            updateAnswerStatus();
            updateWordCount();
            saveAnswerDraft();
        });
    }
    
    // Update answer status
    function updateAnswerStatus() {
        const statusIndicator = answerContainer.querySelector('.status-indicator');
        const statusText = statusIndicator.querySelector('.status-text');
        const statusIcon = statusIndicator.querySelector('i');
        
        let hasAnswer = false;
        
        if (questionType === 'multiple_choice' || questionType === 'true_false') {
            hasAnswer = answerContainer.querySelector('input[type="radio"]:checked') !== null;
        } else if (questionType === 'fill_blank') {
            hasAnswer = answerContainer.querySelector('input[type="text"]').value.trim() !== '';
        } else if (questionType === 'essay') {
            hasAnswer = answerEditor && answerEditor.textContent.trim() !== '';
        }
        
        if (hasAnswer) {
            answerContainer.classList.add('answered');
            statusText.textContent = 'Sudah dijawab';
            statusIcon.style.color = '#10b981';
        } else {
            answerContainer.classList.remove('answered');
            statusText.textContent = 'Belum dijawab';
            statusIcon.style.color = '#94a3b8';
        }
    }
    
    // Update word count
    function updateWordCount() {
        if (!answerEditor) return;
        
        const text = answerEditor.textContent;
        const words = text.trim().split(/\s+/).filter(word => word.length > 0).length;
        
        const wordCountElement = answerContainer.querySelector('.word-count-number');
        if (wordCountElement) {
            wordCountElement.textContent = words;
        }
    }
    
    // Save answer draft
    function saveAnswerDraft() {
        const answerData = {
            questionId: questionId,
            answer: getCurrentAnswer(),
            timestamp: new Date().toISOString()
        };
        
        localStorage.setItem(`answer_draft_${questionId}`, JSON.stringify(answerData));
    }
    
    // Get current answer
    function getCurrentAnswer() {
        if (questionType === 'multiple_choice' || questionType === 'true_false') {
            const selected = answerContainer.querySelector('input[type="radio"]:checked');
            return selected ? selected.value : '';
        } else if (questionType === 'fill_blank') {
            return answerContainer.querySelector('input[type="text"]').value;
        } else if (questionType === 'essay') {
            return answerEditor ? answerEditor.innerHTML : '';
        }
        return '';
    }
    
    // Load answer draft
    function loadAnswerDraft() {
        const savedDraft = localStorage.getItem(`answer_draft_${questionId}`);
        if (savedDraft) {
            const answerData = JSON.parse(savedDraft);
            setCurrentAnswer(answerData.answer);
        }
    }
    
    // Set current answer
    function setCurrentAnswer(answer) {
        if (questionType === 'multiple_choice' || questionType === 'true_false') {
            const input = answerContainer.querySelector(`input[value="${answer}"]`);
            if (input) {
                input.checked = true;
                input.closest('.option-item, .tf-option').classList.add('selected');
            }
        } else if (questionType === 'fill_blank') {
            answerContainer.querySelector('input[type="text"]').value = answer;
        } else if (questionType === 'essay' && answerEditor) {
            answerEditor.innerHTML = answer;
        }
        
        updateAnswerStatus();
        updateWordCount();
    }
    
    // Handle action buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-save-draft')) {
            saveAnswerDraft();
            showNotification('Draft tersimpan', 'success');
        }
        
        if (e.target.closest('.btn-clear-answer')) {
            clearAnswer();
        }
        
        if (e.target.closest('.btn-mark-review')) {
            toggleReview();
        }
    });
    
    // Clear answer
    function clearAnswer() {
        if (confirm('Apakah Anda yakin ingin menghapus jawaban ini?')) {
            answerInputs.forEach(input => {
                input.checked = false;
                input.value = '';
            });
            
            if (answerEditor) {
                answerEditor.innerHTML = '';
            }
            
            answerContainer.classList.remove('answered', 'review');
            updateAnswerStatus();
            updateWordCount();
            saveAnswerDraft();
        }
    }
    
    // Toggle review
    function toggleReview() {
        const reviewBtn = answerContainer.querySelector('.btn-mark-review');
        const statusIndicator = answerContainer.querySelector('.status-indicator');
        
        if (answerContainer.classList.contains('review')) {
            answerContainer.classList.remove('review');
            reviewBtn.classList.remove('active');
            statusIndicator.classList.remove('review');
        } else {
            answerContainer.classList.add('review');
            reviewBtn.classList.add('active');
            statusIndicator.classList.add('review');
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
    loadAnswerDraft();
    updateAnswerStatus();
    updateWordCount();
});
</script>
