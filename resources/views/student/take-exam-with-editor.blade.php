@extends('layouts.unified-layout-new')

@section('title', 'Kerjakan Ujian')

@section('content')
<div class="student-container">
    <!-- Exam Header -->
    <div class="exam-header">
        <div class="exam-info">
            <h1 class="exam-title">{{ $exam->title ?? 'Ujian Matematika' }}</h1>
            <div class="exam-meta">
                <span class="exam-subject">{{ $exam->subject->name ?? 'Matematika' }}</span>
                <span class="exam-class">{{ $exam->class->name ?? 'Kelas 10A' }}</span>
                <span class="exam-duration">{{ $exam->duration ?? 120 }} menit</span>
            </div>
        </div>
        <div class="exam-timer" id="examTimer">
            <div class="timer-circle">
                <svg class="timer-svg" viewBox="0 0 100 100">
                    <circle class="timer-bg" cx="50" cy="50" r="45"></circle>
                    <circle class="timer-progress" cx="50" cy="50" r="45" id="timerProgress"></circle>
                </svg>
                <div class="timer-text">
                    <span class="timer-minutes" id="timerMinutes">120</span>
                    <span class="timer-separator">:</span>
                    <span class="timer-seconds" id="timerSeconds">00</span>
                </div>
            </div>
            <div class="timer-label">Waktu Tersisa</div>
        </div>
    </div>

    <!-- Exam Instructions -->
    <div class="exam-instructions">
        <div class="instructions-header">
            <h3><i class="fas fa-info-circle"></i> Petunjuk Ujian</h3>
            <button type="button" class="btn-toggle-instructions" id="toggleInstructions">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        <div class="instructions-content" id="instructionsContent">
            <div class="instructions-text">
                {!! $exam->description ?? '<p>Silakan kerjakan soal-soal berikut dengan teliti. Pastikan Anda menjawab semua pertanyaan sebelum waktu habis.</p>' !!}
            </div>
            <div class="instructions-rules">
                <h4>Aturan Ujian:</h4>
                <ul>
                    <li>Jawab semua soal dengan teliti</li>
                    <li>Gunakan editor yang disediakan untuk menjawab soal essay</li>
                    <li>Anda dapat menyimpan draft jawaban kapan saja</li>
                    <li>Pastikan mengirim jawaban sebelum waktu habis</li>
                    <li>Jangan menutup browser selama ujian berlangsung</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Exam Progress -->
    <div class="exam-progress">
        <div class="progress-header">
            <h3>Progress Ujian</h3>
            <span class="progress-text" id="progressText">0 dari 0 soal</span>
        </div>
        <div class="progress-bar">
            <div class="progress-fill" id="progressFill" style="width: 0%"></div>
        </div>
        <div class="progress-stats">
            <div class="stat-item">
                <i class="fas fa-check-circle"></i>
                <span>Dijawab: <span id="answeredCount">0</span></span>
            </div>
            <div class="stat-item">
                <i class="fas fa-flag"></i>
                <span>Review: <span id="reviewCount">0</span></span>
            </div>
            <div class="stat-item">
                <i class="fas fa-clock"></i>
                <span>Sisa: <span id="remainingCount">0</span></span>
            </div>
        </div>
    </div>

    <!-- Questions Container -->
    <div class="questions-container" id="questionsContainer">
        @for($i = 1; $i <= 5; $i++)
        <div class="question-wrapper" data-question="{{ $i }}">
            @include('components.student-answer-editor', [
                'questionId' => $i,
                'questionNumber' => $i,
                'questionType' => $i <= 3 ? 'essay' : 'multiple_choice',
                'questionContent' => '<p>Ini adalah contoh soal ' . $i . '. Silakan jawab dengan teliti.</p>',
                'points' => 20,
                'options' => [
                    '<p>Pilihan A</p>',
                    '<p>Pilihan B</p>',
                    '<p>Pilihan C</p>',
                    '<p>Pilihan D</p>'
                ]
            ])
        </div>
        @endfor
    </div>

    <!-- Navigation -->
    <div class="exam-navigation">
        <div class="nav-buttons">
            <button type="button" class="btn-nav btn-prev" id="prevBtn" disabled>
                <i class="fas fa-chevron-left"></i>
                Sebelumnya
            </button>
            <button type="button" class="btn-nav btn-next" id="nextBtn">
                Selanjutnya
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        <div class="nav-actions">
            <button type="button" class="btn-action btn-save-all" id="saveAllBtn">
                <i class="fas fa-save"></i>
                Simpan Semua
            </button>
            <button type="button" class="btn-action btn-submit" id="submitBtn">
                <i class="fas fa-check"></i>
                Submit Ujian
            </button>
        </div>
    </div>

    <!-- Question Navigator -->
    <div class="question-navigator" id="questionNavigator">
        <div class="navigator-header">
            <h4>Navigasi Soal</h4>
            <button type="button" class="btn-close-navigator" id="closeNavigator">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="navigator-grid" id="navigatorGrid">
            <!-- Question numbers will be generated here -->
        </div>
    </div>

    <!-- Floating Action Button -->
    <button type="button" class="fab" id="fabNavigator">
        <i class="fas fa-list"></i>
    </button>
</div>

<!-- Submit Confirmation Modal -->
<div class="modal" id="submitModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Konfirmasi Submit Ujian</h3>
        </div>
        <div class="modal-body">
            <p>Apakah Anda yakin ingin mengirim jawaban ujian?</p>
            <div class="submit-summary">
                <div class="summary-item">
                    <span>Total Soal:</span>
                    <span id="modalTotalQuestions">0</span>
                </div>
                <div class="summary-item">
                    <span>Dijawab:</span>
                    <span id="modalAnsweredQuestions">0</span>
                </div>
                <div class="summary-item">
                    <span>Belum Dijawab:</span>
                    <span id="modalUnansweredQuestions">0</span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" id="cancelSubmit">Batal</button>
            <button type="button" class="btn-confirm" id="confirmSubmit">Submit Ujian</button>
        </div>
    </div>
</div>

<style>
.student-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px;
}

.exam-header {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
    padding: 32px;
    border-radius: 16px;
    margin-bottom: 32px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.exam-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 12px;
}

.exam-meta {
    display: flex;
    gap: 24px;
    font-size: 1.1rem;
    opacity: 0.9;
}

.exam-meta span {
    background: rgba(255, 255, 255, 0.2);
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: 600;
}

.exam-timer {
    text-align: center;
}

.timer-circle {
    position: relative;
    width: 120px;
    height: 120px;
    margin: 0 auto 12px;
}

.timer-svg {
    width: 100%;
    height: 100%;
    transform: rotate(-90deg);
}

.timer-bg {
    fill: none;
    stroke: rgba(255, 255, 255, 0.3);
    stroke-width: 8;
}

.timer-progress {
    fill: none;
    stroke: #ffffff;
    stroke-width: 8;
    stroke-linecap: round;
    stroke-dasharray: 283;
    stroke-dashoffset: 0;
    transition: stroke-dashoffset 1s linear;
}

.timer-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 1.5rem;
    font-weight: 700;
    color: white;
}

.timer-label {
    font-size: 0.9rem;
    opacity: 0.8;
}

.exam-instructions {
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    margin-bottom: 32px;
    overflow: hidden;
}

.instructions-header {
    background: #3b82f6;
    color: white;
    padding: 20px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
}

.instructions-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 12px;
}

.btn-toggle-instructions {
    background: none;
    border: none;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.btn-toggle-instructions.collapsed {
    transform: rotate(-90deg);
}

.instructions-content {
    padding: 24px;
    transition: all 0.3s ease;
}

.instructions-content.collapsed {
    display: none;
}

.instructions-text {
    margin-bottom: 20px;
    font-size: 1.1rem;
    line-height: 1.7;
    color: #1e293b;
}

.instructions-rules h4 {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 12px;
}

.instructions-rules ul {
    list-style: none;
    padding: 0;
}

.instructions-rules li {
    padding: 8px 0;
    padding-left: 24px;
    position: relative;
    color: #64748b;
}

.instructions-rules li::before {
    content: '•';
    color: #3b82f6;
    font-weight: bold;
    position: absolute;
    left: 0;
}

.exam-progress {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 32px;
}

.progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.progress-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
}

.progress-text {
    font-weight: 600;
    color: #3b82f6;
}

.progress-bar {
    width: 100%;
    height: 12px;
    background: #f1f5f9;
    border-radius: 6px;
    overflow: hidden;
    margin-bottom: 16px;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #3b82f6, #10b981);
    border-radius: 6px;
    transition: width 0.3s ease;
}

.progress-stats {
    display: flex;
    gap: 24px;
    flex-wrap: wrap;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #64748b;
}

.stat-item i {
    color: #3b82f6;
}

.questions-container {
    margin-bottom: 32px;
}

.question-wrapper {
    margin-bottom: 32px;
}

.exam-navigation {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
}

.nav-buttons {
    display: flex;
    gap: 12px;
}

.btn-nav {
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
}

.btn-nav:hover:not(:disabled) {
    background: #e2e8f0;
    color: #334155;
}

.btn-nav:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.nav-actions {
    display: flex;
    gap: 12px;
}

.btn-action {
    padding: 12px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    border: none;
}

.btn-save-all {
    background: #f1f5f9;
    color: #64748b;
    border: 2px solid #e2e8f0;
}

.btn-save-all:hover {
    background: #e2e8f0;
    color: #334155;
}

.btn-submit {
    background: #10b981;
    color: white;
}

.btn-submit:hover {
    background: #059669;
    transform: translateY(-1px);
}

.question-navigator {
    position: fixed;
    top: 50%;
    right: 24px;
    transform: translateY(-50%);
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    width: 300px;
    max-height: 400px;
    overflow-y: auto;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    display: none;
}

.navigator-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f1f5f9;
}

.navigator-header h4 {
    margin: 0;
    font-size: 1.125rem;
    font-weight: 700;
    color: #1e293b;
}

.btn-close-navigator {
    background: none;
    border: none;
    color: #64748b;
    cursor: pointer;
    font-size: 1.2rem;
}

.navigator-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 8px;
}

.nav-question-btn {
    width: 40px;
    height: 40px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    background: white;
    color: #64748b;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.nav-question-btn:hover {
    border-color: #3b82f6;
    color: #3b82f6;
}

.nav-question-btn.answered {
    background: #10b981;
    border-color: #10b981;
    color: white;
}

.nav-question-btn.current {
    background: #3b82f6;
    border-color: #3b82f6;
    color: white;
}

.nav-question-btn.review {
    background: #f59e0b;
    border-color: #f59e0b;
    color: white;
}

.fab {
    position: fixed;
    bottom: 24px;
    right: 24px;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: #3b82f6;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 1.5rem;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    transition: all 0.3s ease;
    z-index: 1000;
}

.fab:hover {
    background: #2563eb;
    transform: scale(1.1);
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 2000;
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    border-radius: 12px;
    padding: 32px;
    max-width: 500px;
    width: 90%;
}

.modal-header h3 {
    margin: 0 0 20px 0;
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
}

.submit-summary {
    background: #f8fafc;
    border-radius: 8px;
    padding: 20px;
    margin: 20px 0;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    font-weight: 600;
}

.summary-item:last-child {
    margin-bottom: 0;
}

.modal-footer {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 24px;
}

.btn-cancel, .btn-confirm {
    padding: 12px 24px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s ease;
}

.btn-cancel {
    background: #f1f5f9;
    color: #64748b;
    border: 2px solid #e2e8f0;
}

.btn-cancel:hover {
    background: #e2e8f0;
    color: #334155;
}

.btn-confirm {
    background: #10b981;
    color: white;
    border: 2px solid #10b981;
}

.btn-confirm:hover {
    background: #059669;
}

/* Responsive Design */
@media (max-width: 768px) {
    .student-container {
        padding: 16px;
    }
    
    .exam-header {
        flex-direction: column;
        gap: 24px;
        text-align: center;
    }
    
    .exam-meta {
        flex-direction: column;
        gap: 12px;
    }
    
    .exam-navigation {
        flex-direction: column;
        gap: 16px;
    }
    
    .nav-buttons, .nav-actions {
        width: 100%;
        justify-content: center;
    }
    
    .question-navigator {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        transform: none;
        width: 100%;
        max-height: 100%;
        border-radius: 0;
    }
    
    .navigator-grid {
        grid-template-columns: repeat(6, 1fr);
    }
}

@media (max-width: 480px) {
    .exam-header {
        padding: 20px;
    }
    
    .exam-title {
        font-size: 1.5rem;
    }
    
    .timer-circle {
        width: 80px;
        height: 80px;
    }
    
    .timer-text {
        font-size: 1.2rem;
    }
    
    .progress-stats {
        flex-direction: column;
        gap: 12px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentQuestion = 1;
    let totalQuestions = document.querySelectorAll('.question-wrapper').length;
    let timeRemaining = {{ $exam->duration ?? 120 }} * 60; // Convert to seconds
    let timerInterval;
    
    // Initialize exam
    initializeExam();
    
    function initializeExam() {
        updateQuestionDisplay();
        updateProgress();
        startTimer();
        generateNavigator();
        loadAnswers();
        
        // Show first question
        showQuestion(1);
    }
    
    // Timer functionality
    function startTimer() {
        timerInterval = setInterval(function() {
            timeRemaining--;
            updateTimer();
            
            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                autoSubmit();
            }
        }, 1000);
    }
    
    function updateTimer() {
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        
        document.getElementById('timerMinutes').textContent = minutes.toString().padStart(2, '0');
        document.getElementById('timerSeconds').textContent = seconds.toString().padStart(2, '0');
        
        // Update progress circle
        const totalTime = {{ $exam->duration ?? 120 }} * 60;
        const progress = ((totalTime - timeRemaining) / totalTime) * 283;
        document.getElementById('timerProgress').style.strokeDashoffset = 283 - progress;
        
        // Change color when time is running low
        if (timeRemaining < 300) { // 5 minutes
            document.getElementById('timerProgress').style.stroke = '#ef4444';
        }
    }
    
    // Question navigation
    function showQuestion(questionNumber) {
        // Hide all questions
        document.querySelectorAll('.question-wrapper').forEach(q => {
            q.style.display = 'none';
        });
        
        // Show current question
        const currentQ = document.querySelector(`[data-question="${questionNumber}"]`);
        if (currentQ) {
            currentQ.style.display = 'block';
            currentQuestion = questionNumber;
            updateNavigationButtons();
            updateNavigator();
        }
    }
    
    function updateNavigationButtons() {
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        
        prevBtn.disabled = currentQuestion === 1;
        nextBtn.disabled = currentQuestion === totalQuestions;
        
        if (currentQuestion === totalQuestions) {
            nextBtn.innerHTML = '<i class="fas fa-check"></i> Submit Ujian';
            nextBtn.className = 'btn-nav btn-submit-final';
        } else {
            nextBtn.innerHTML = 'Selanjutnya <i class="fas fa-chevron-right"></i>';
            nextBtn.className = 'btn-nav btn-next';
        }
    }
    
    // Navigation event listeners
    document.getElementById('prevBtn').addEventListener('click', function() {
        if (currentQuestion > 1) {
            showQuestion(currentQuestion - 1);
        }
    });
    
    document.getElementById('nextBtn').addEventListener('click', function() {
        if (currentQuestion < totalQuestions) {
            showQuestion(currentQuestion + 1);
        } else {
            showSubmitModal();
        }
    });
    
    // Navigator functionality
    function generateNavigator() {
        const navigatorGrid = document.getElementById('navigatorGrid');
        navigatorGrid.innerHTML = '';
        
        for (let i = 1; i <= totalQuestions; i++) {
            const btn = document.createElement('button');
            btn.className = 'nav-question-btn';
            btn.textContent = i;
            btn.addEventListener('click', function() {
                showQuestion(i);
                toggleNavigator();
            });
            navigatorGrid.appendChild(btn);
        }
    }
    
    function updateNavigator() {
        document.querySelectorAll('.nav-question-btn').forEach((btn, index) => {
            btn.classList.remove('current', 'answered', 'review');
            
            if (index + 1 === currentQuestion) {
                btn.classList.add('current');
            }
            
            // Check if question is answered
            const questionWrapper = document.querySelector(`[data-question="${index + 1}"]`);
            if (isQuestionAnswered(questionWrapper)) {
                btn.classList.add('answered');
            }
            
            // Check if question is marked for review
            if (questionWrapper && questionWrapper.querySelector('.btn-mark-review.active')) {
                btn.classList.add('review');
            }
        });
    }
    
    function isQuestionAnswered(questionWrapper) {
        if (!questionWrapper) return false;
        
        const radioInputs = questionWrapper.querySelectorAll('input[type="radio"]:checked');
        const textInputs = questionWrapper.querySelectorAll('input[type="text"]');
        const editorContent = questionWrapper.querySelector('.quill-editor');
        
        if (radioInputs.length > 0) return true;
        if (textInputs.length > 0 && Array.from(textInputs).some(input => input.value.trim() !== '')) return true;
        if (editorContent && editorContent.textContent.trim() !== '') return true;
        
        return false;
    }
    
    // Toggle navigator
    document.getElementById('fabNavigator').addEventListener('click', toggleNavigator);
    document.getElementById('closeNavigator').addEventListener('click', toggleNavigator);
    
    function toggleNavigator() {
        const navigator = document.getElementById('questionNavigator');
        navigator.style.display = navigator.style.display === 'none' ? 'block' : 'none';
    }
    
    // Update progress
    function updateProgress() {
        const answeredCount = document.querySelectorAll('.question-wrapper').filter(q => isQuestionAnswered(q)).length;
        const reviewCount = document.querySelectorAll('.btn-mark-review.active').length;
        const remainingCount = totalQuestions - answeredCount;
        
        const progressPercentage = (answeredCount / totalQuestions) * 100;
        
        document.getElementById('progressText').textContent = `${answeredCount} dari ${totalQuestions} soal`;
        document.getElementById('progressFill').style.width = `${progressPercentage}%`;
        document.getElementById('answeredCount').textContent = answeredCount;
        document.getElementById('reviewCount').textContent = reviewCount;
        document.getElementById('remainingCount').textContent = remainingCount;
        
        updateNavigator();
    }
    
    // Save all answers
    document.getElementById('saveAllBtn').addEventListener('click', function() {
        saveAllAnswers();
        showNotification('Semua jawaban tersimpan!', 'success');
    });
    
    function saveAllAnswers() {
        const answers = {};
        
        document.querySelectorAll('.question-wrapper').forEach((wrapper, index) => {
            const questionNumber = index + 1;
            answers[questionNumber] = getQuestionAnswer(wrapper);
        });
        
        localStorage.setItem('exam_answers', JSON.stringify(answers));
    }
    
    function getQuestionAnswer(questionWrapper) {
        const radioInput = questionWrapper.querySelector('input[type="radio"]:checked');
        const textInput = questionWrapper.querySelector('input[type="text"]');
        const editorContent = questionWrapper.querySelector('.quill-editor');
        
        if (radioInput) return radioInput.value;
        if (textInput) return textInput.value;
        if (editorContent) return editorContent.innerHTML;
        
        return '';
    }
    
    // Load answers
    function loadAnswers() {
        const savedAnswers = localStorage.getItem('exam_answers');
        if (savedAnswers) {
            const answers = JSON.parse(savedAnswers);
            
            Object.keys(answers).forEach(questionNumber => {
                const questionWrapper = document.querySelector(`[data-question="${questionNumber}"]`);
                if (questionWrapper) {
                    setQuestionAnswer(questionWrapper, answers[questionNumber]);
                }
            });
        }
    }
    
    function setQuestionAnswer(questionWrapper, answer) {
        const radioInput = questionWrapper.querySelector(`input[value="${answer}"]`);
        const textInput = questionWrapper.querySelector('input[type="text"]');
        const editorContent = questionWrapper.querySelector('.quill-editor');
        
        if (radioInput) {
            radioInput.checked = true;
            radioInput.closest('.option-item, .tf-option').classList.add('selected');
        }
        if (textInput) textInput.value = answer;
        if (editorContent) editorContent.innerHTML = answer;
    }
    
    // Submit functionality
    function showSubmitModal() {
        const answeredCount = document.querySelectorAll('.question-wrapper').filter(q => isQuestionAnswered(q)).length;
        const unansweredCount = totalQuestions - answeredCount;
        
        document.getElementById('modalTotalQuestions').textContent = totalQuestions;
        document.getElementById('modalAnsweredQuestions').textContent = answeredCount;
        document.getElementById('modalUnansweredQuestions').textContent = unansweredCount;
        
        document.getElementById('submitModal').style.display = 'block';
    }
    
    document.getElementById('cancelSubmit').addEventListener('click', function() {
        document.getElementById('submitModal').style.display = 'none';
    });
    
    document.getElementById('confirmSubmit').addEventListener('click', function() {
        submitExam();
    });
    
    function submitExam() {
        // Save all answers before submit
        saveAllAnswers();
        
        // Submit form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("student.submit-ujian", $exam->id ?? 1) }}';
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add answers
        const answers = JSON.parse(localStorage.getItem('exam_answers') || '{}');
        Object.keys(answers).forEach(questionNumber => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `answers[${questionNumber}]`;
            input.value = answers[questionNumber];
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
    
    function autoSubmit() {
        if (confirm('Waktu ujian telah habis. Apakah Anda ingin mengirim jawaban sekarang?')) {
            submitExam();
        }
    }
    
    // Instructions toggle
    document.getElementById('toggleInstructions').addEventListener('click', function() {
        const content = document.getElementById('instructionsContent');
        const icon = this.querySelector('i');
        
        content.classList.toggle('collapsed');
        icon.classList.toggle('fa-chevron-down');
        icon.classList.toggle('fa-chevron-up');
    });
    
    // Auto-save every 30 seconds
    setInterval(function() {
        saveAllAnswers();
    }, 30000);
    
    // Update progress on answer changes
    document.addEventListener('change', function(e) {
        if (e.target.matches('input[type="radio"], input[type="text"]') || 
            e.target.closest('.quill-editor')) {
            updateProgress();
        }
    });
    
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
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey) {
            switch(e.key) {
                case 's':
                    e.preventDefault();
                    saveAllAnswers();
                    showNotification('Jawaban tersimpan!', 'success');
                    break;
                case 'ArrowLeft':
                    e.preventDefault();
                    if (currentQuestion > 1) {
                        showQuestion(currentQuestion - 1);
                    }
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    if (currentQuestion < totalQuestions) {
                        showQuestion(currentQuestion + 1);
                    }
                    break;
            }
        }
    });
});
</script>
@endsection
