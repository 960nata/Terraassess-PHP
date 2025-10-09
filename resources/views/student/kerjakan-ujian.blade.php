@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - Kerjakan Ujian')

@section('content')
<!-- Modern Exam Interface -->
<div class="exam-interface">
    <!-- Fixed Header with Timer -->
    <div class="exam-header-fixed">
        <div class="exam-header-content">
            <div class="exam-info">
                <h1 class="exam-title">{{ $ujian->name }}</h1>
                <div class="exam-subtitle">
                    <span class="subject">{{ $ujian->kelasMapel->mapel->name ?? 'Mata Pelajaran' }}</span>
                    <span class="teacher">by {{ $ujian->kelasMapel->pengajar->first()->name ?? 'N/A' }}</span>
                </div>
            </div>
            
            <!-- Modern Timer -->
            <div class="timer-container">
                <div class="timer-display" id="timerDisplay">
                    <div class="timer-circle">
                        <svg class="timer-svg" viewBox="0 0 100 100">
                            <circle class="timer-bg" cx="50" cy="50" r="45"></circle>
                            <circle class="timer-progress" cx="50" cy="50" r="45" id="timerProgress"></circle>
                        </svg>
                        <div class="timer-text">
                            <span id="timerMinutes">00</span>
                            <span class="timer-separator">:</span>
                            <span id="timerSeconds">00</span>
                        </div>
                    </div>
                </div>
                <div class="timer-label">Time Remaining</div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="exam-main-content">
        <div class="exam-container">
            <!-- Progress Bar -->
            <div class="exam-progress">
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
                <div class="progress-text">
                    <span id="currentQuestion">1</span> of <span id="totalQuestions">{{ $ujian->soalMultiples->count() + $ujian->soalEssays->count() }}</span> questions
                </div>
            </div>

            <!-- Exam Description -->
            @if($ujian->content)
            <div class="exam-description">
                <div class="description-header">
                    <i class="fas fa-info-circle"></i>
                    <h3>Exam Instructions</h3>
                </div>
                <div class="description-content">
                    <p>{{ $ujian->content }}</p>
                </div>
            </div>
            @endif

            <!-- Questions Form -->
            <form action="{{ route('student.submit-ujian', $ujian->id) }}" method="POST" class="exam-form" id="examForm">
                @csrf
                
                <!-- Multiple Choice Questions -->
                @foreach($ujian->soalMultiples as $index => $soal)
                <div class="question-section" data-question="{{ $index + 1 }}" data-type="multiple">
                    <div class="question-card">
                        <div class="question-header">
                            <div class="question-number">
                                <span class="number">{{ $index + 1 }}</span>
                                <span class="type">Multiple Choice</span>
                            </div>
                            <div class="question-points">
                                <i class="fas fa-star"></i>
                                <span>{{ $soal->poin ?? 1 }} points</span>
                            </div>
                        </div>
                        
                        <div class="question-content">
                            <div class="question-text">
                                <p>{{ $soal->soal }}</p>
                            </div>
                            
                            <div class="options-container">
                                @foreach(['a', 'b', 'c', 'd'] as $option)
                                <label class="option-item">
                                    <input type="radio" name="jawaban[{{ $soal->id }}]" value="{{ $option }}" 
                                           class="option-radio" required>
                                    <div class="option-content">
                                        <div class="option-letter">{{ strtoupper($option) }}</div>
                                        <div class="option-text">{{ $soal->$option }}</div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Essay Questions -->
                @foreach($ujian->soalEssays as $index => $soal)
                <div class="question-section" data-question="{{ $ujian->soalMultiples->count() + $index + 1 }}" data-type="essay">
                    <div class="question-card">
                        <div class="question-header">
                            <div class="question-number">
                                <span class="number">{{ $ujian->soalMultiples->count() + $index + 1 }}</span>
                                <span class="type">Essay</span>
                            </div>
                            <div class="question-points">
                                <i class="fas fa-star"></i>
                                <span>{{ $soal->poin ?? 1 }} points</span>
                            </div>
                        </div>
                        
                        <div class="question-content">
                            <div class="question-text">
                                <p>{{ $soal->soal }}</p>
                            </div>
                            
                            <div class="essay-container">
                                @include('components.rich-text-editor', [
                                    'name' => 'jawaban_essay[' . $soal->id . ']',
                                    'content' => '',
                                    'placeholder' => 'Write your answer here...',
                                    'height' => '300px',
                                    'required' => true,
                                    'editorId' => 'essay-editor-' . $soal->id
                                ])
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </form>

            <!-- Navigation Controls -->
            <div class="exam-navigation">
                <div class="nav-buttons">
                    <button type="button" class="nav-btn prev-btn" id="prevBtn" disabled>
                        <i class="fas fa-chevron-left"></i>
                        Previous
                    </button>
                    
                    <div class="question-indicators" id="questionIndicators">
                        @for($i = 1; $i <= $ujian->soalMultiples->count() + $ujian->soalEssays->count(); $i++)
                        <button type="button" class="indicator {{ $i == 1 ? 'active' : '' }}" data-question="{{ $i }}">
                            {{ $i }}
                        </button>
                        @endfor
                    </div>
                    
                    <button type="button" class="nav-btn next-btn" id="nextBtn">
                        Next
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                
                <div class="submit-section">
                    <button type="submit" form="examForm" class="submit-btn" id="submitBtn">
                        <i class="fas fa-paper-plane"></i>
                        Submit Exam
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional-styles')
<style>
/* Modern Exam Interface Styles */
.exam-interface {
    min-height: 100vh;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    color: #ffffff;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Fixed Header */
.exam-header-fixed {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background: rgba(15, 23, 42, 0.95);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    z-index: 1000;
    padding: 1rem 0;
}

.exam-header-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.exam-info {
    flex: 1;
}

.exam-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
    color: #ffffff;
}

.exam-subtitle {
    display: flex;
    gap: 1rem;
    font-size: 0.875rem;
    color: #94a3b8;
}

.exam-subtitle .subject {
    color: #3b82f6;
    font-weight: 500;
}

/* Modern Timer */
.timer-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.timer-display {
    position: relative;
}

.timer-circle {
    width: 80px;
    height: 80px;
    position: relative;
}

.timer-svg {
    width: 100%;
    height: 100%;
    transform: rotate(-90deg);
}

.timer-bg {
    fill: none;
    stroke: rgba(255, 255, 255, 0.1);
    stroke-width: 4;
}

.timer-progress {
    fill: none;
    stroke: #3b82f6;
    stroke-width: 4;
    stroke-linecap: round;
    stroke-dasharray: 283;
    stroke-dashoffset: 283;
    transition: stroke-dashoffset 1s ease;
}

.timer-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 1.125rem;
    font-weight: 700;
    color: #ffffff;
    display: flex;
    align-items: center;
    gap: 0.125rem;
}

.timer-separator {
    color: #94a3b8;
}

.timer-label {
    font-size: 0.75rem;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Main Content */
.exam-main-content {
    margin-top: 120px;
    padding: 2rem 0;
}

.exam-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 2rem;
}

/* Progress Bar */
.exam-progress {
    margin-bottom: 2rem;
}

.progress-bar {
    width: 100%;
    height: 8px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #3b82f6, #1d4ed8);
    border-radius: 4px;
    transition: width 0.3s ease;
    width: 0%;
}

.progress-text {
    text-align: center;
    font-size: 0.875rem;
    color: #94a3b8;
}

/* Exam Description */
.exam-description {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.description-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.description-header i {
    color: #3b82f6;
    font-size: 1.25rem;
}

.description-header h3 {
    margin: 0;
    font-size: 1.125rem;
    font-weight: 600;
    color: #ffffff;
}

.description-content p {
    margin: 0;
    color: #cbd5e1;
    line-height: 1.6;
}

/* Question Sections */
.question-section {
    display: none;
    animation: fadeIn 0.3s ease;
}

.question-section.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.question-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    backdrop-filter: blur(10px);
}

.question-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.question-number {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.question-number .number {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: #ffffff;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.125rem;
}

.question-number .type {
    color: #94a3b8;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.question-points {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.question-points i {
    font-size: 0.75rem;
}

.question-content {
    color: #ffffff;
}

.question-text {
    margin-bottom: 1.5rem;
}

.question-text p {
    font-size: 1.125rem;
    line-height: 1.6;
    color: #ffffff;
    margin: 0;
}

/* Options Container */
.options-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.option-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.05);
    border: 2px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.option-item:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(59, 130, 246, 0.3);
    transform: translateY(-2px);
}

.option-item.selected {
    background: rgba(59, 130, 246, 0.1);
    border-color: #3b82f6;
}

.option-radio {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.option-content {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    width: 100%;
}

.option-letter {
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.option-item.selected .option-letter {
    background: #3b82f6;
    color: #ffffff;
}

.option-text {
    color: #ffffff;
    font-size: 1rem;
    line-height: 1.5;
    flex: 1;
}

/* Essay Container */
.essay-container {
    margin-top: 1rem;
}

/* Navigation */
.exam-navigation {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    padding: 1.5rem;
    margin-top: 2rem;
}

.nav-buttons {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.nav-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    color: #ffffff;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.nav-btn:hover:not(:disabled) {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

.nav-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.question-indicators {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    justify-content: center;
}

.indicator {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #ffffff;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.indicator:hover {
    background: rgba(255, 255, 255, 0.2);
}

.indicator.active {
    background: #3b82f6;
    border-color: #3b82f6;
}

.indicator.answered {
    background: rgba(34, 197, 94, 0.2);
    border-color: #22c55e;
    color: #22c55e;
}

/* Submit Section */
.submit-section {
    text-align: center;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.submit-btn {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #ffffff;
    border: none;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.submit-btn:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
}

/* Responsive Design */
@media (max-width: 768px) {
    .exam-header-content {
        padding: 0 1rem;
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .exam-title {
        font-size: 1.25rem;
    }

    .exam-subtitle {
        flex-direction: column;
        gap: 0.25rem;
    }

    .exam-container {
        padding: 0 1rem;
    }

    .question-card {
        padding: 1.5rem;
    }

    .nav-buttons {
        flex-direction: column;
        gap: 1rem;
    }

    .question-indicators {
        order: -1;
    }

    .nav-btn {
        width: 100%;
        justify-content: center;
    }

    .timer-circle {
        width: 60px;
        height: 60px;
    }

    .timer-text {
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .exam-main-content {
        margin-top: 140px;
    }

    .question-card {
        padding: 1rem;
    }

    .option-item {
        padding: 0.75rem;
    }

    .option-content {
        gap: 0.75rem;
    }

    .option-letter {
        width: 28px;
        height: 28px;
        font-size: 0.75rem;
    }

    .indicator {
        width: 32px;
        height: 32px;
        font-size: 0.75rem;
    }
}
</style>
@endsection

@section('additional-scripts')
<script>
// Modern Exam Interface JavaScript
class ExamInterface {
    constructor() {
        this.currentQuestion = 1;
        this.totalQuestions = {{ $ujian->soalMultiples->count() + $ujian->soalEssays->count() }};
        this.timeLeft = {{ $ujian->time * 60 }};
        this.totalTime = {{ $ujian->time * 60 }};
        this.timerInterval = null;
        this.autoSaveInterval = null;
        this.answeredQuestions = new Set();
        
        this.init();
    }

    init() {
        this.setupTimer();
        this.setupNavigation();
        this.setupQuestionIndicators();
        this.setupAutoSave();
        this.loadSavedAnswers();
        this.showQuestion(1);
        this.updateProgress();
        
        // Prevent accidental page refresh
        window.addEventListener('beforeunload', (e) => {
            if (this.answeredQuestions.size > 0) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    }

    setupTimer() {
        this.timerInterval = setInterval(() => {
            this.timeLeft--;
            this.updateTimer();
            
            if (this.timeLeft <= 0) {
                this.timeUp();
            }
        }, 1000);
        
        this.updateTimer();
    }

    updateTimer() {
        const minutes = Math.floor(this.timeLeft / 60);
        const seconds = this.timeLeft % 60;
        
        document.getElementById('timerMinutes').textContent = minutes.toString().padStart(2, '0');
        document.getElementById('timerSeconds').textContent = seconds.toString().padStart(2, '0');
        
        // Update progress circle
        const progress = ((this.totalTime - this.timeLeft) / this.totalTime) * 283;
        const progressCircle = document.getElementById('timerProgress');
        if (progressCircle) {
            progressCircle.style.strokeDashoffset = 283 - progress;
        }
        
        // Change color when time is running low
        if (this.timeLeft <= 300) { // 5 minutes
            progressCircle.style.stroke = '#ef4444';
        }
    }

    timeUp() {
        clearInterval(this.timerInterval);
        clearInterval(this.autoSaveInterval);
        
        // Show time up modal
        this.showModal(
            'Time\'s Up!',
            'The exam time has ended. Your answers will be automatically submitted.',
            'Submit Now',
            () => this.submitExam()
        );
    }

    setupNavigation() {
        document.getElementById('prevBtn').addEventListener('click', () => {
            if (this.currentQuestion > 1) {
                this.showQuestion(this.currentQuestion - 1);
            }
        });

        document.getElementById('nextBtn').addEventListener('click', () => {
            if (this.currentQuestion < this.totalQuestions) {
                this.showQuestion(this.currentQuestion + 1);
            }
        });

        document.getElementById('submitBtn').addEventListener('click', (e) => {
            e.preventDefault();
            this.confirmSubmit();
        });
    }

    setupQuestionIndicators() {
        const indicators = document.querySelectorAll('.indicator');
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                this.showQuestion(index + 1);
            });
        });
    }

    showQuestion(questionNumber) {
        // Hide all questions
        document.querySelectorAll('.question-section').forEach(section => {
            section.classList.remove('active');
        });

        // Show current question
        const currentSection = document.querySelector(`[data-question="${questionNumber}"]`);
        if (currentSection) {
            currentSection.classList.add('active');
        }

        this.currentQuestion = questionNumber;
        this.updateNavigation();
        this.updateProgress();
        this.updateIndicators();
    }

    updateNavigation() {
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');

        prevBtn.disabled = this.currentQuestion === 1;
        nextBtn.disabled = this.currentQuestion === this.totalQuestions;

        if (this.currentQuestion === this.totalQuestions) {
            nextBtn.style.display = 'none';
        } else {
            nextBtn.style.display = 'flex';
        }
    }

    updateProgress() {
        const progress = (this.currentQuestion / this.totalQuestions) * 100;
        document.getElementById('progressFill').style.width = progress + '%';
        document.getElementById('currentQuestion').textContent = this.currentQuestion;
        document.getElementById('totalQuestions').textContent = this.totalQuestions;
    }

    updateIndicators() {
        const indicators = document.querySelectorAll('.indicator');
        indicators.forEach((indicator, index) => {
            indicator.classList.remove('active');
            if (index + 1 === this.currentQuestion) {
                indicator.classList.add('active');
            }
        });
    }

    setupAutoSave() {
        // Auto-save every 30 seconds
        this.autoSaveInterval = setInterval(() => {
            this.saveAnswers();
        }, 30000);

        // Save on answer change
        document.addEventListener('change', (e) => {
            if (e.target.matches('input[type="radio"], textarea, .ql-editor')) {
                this.markQuestionAnswered(this.currentQuestion);
                this.saveAnswers();
            }
        });
    }

    markQuestionAnswered(questionNumber) {
        this.answeredQuestions.add(questionNumber);
        const indicator = document.querySelector(`[data-question="${questionNumber}"]`);
        if (indicator) {
            indicator.classList.add('answered');
        }
    }

    saveAnswers() {
        const formData = new FormData(document.getElementById('examForm'));
        const answers = {};
        
        for (let [key, value] of formData.entries()) {
            answers[key] = value;
        }
        
        // Also save rich text editor content
        document.querySelectorAll('.ql-editor').forEach((editor, index) => {
            const name = editor.closest('.essay-container').querySelector('textarea').name;
            answers[name] = editor.innerHTML;
        });
        
        localStorage.setItem('ujian_answers_' + {{ $ujian->id }}, JSON.stringify(answers));
        localStorage.setItem('ujian_progress_' + {{ $ujian->id }}, JSON.stringify({
            currentQuestion: this.currentQuestion,
            answeredQuestions: Array.from(this.answeredQuestions),
            timeLeft: this.timeLeft
        }));
    }

    loadSavedAnswers() {
        const saved = localStorage.getItem('ujian_answers_' + {{ $ujian->id }});
        const progress = localStorage.getItem('ujian_progress_' + {{ $ujian->id }});
        
        if (saved) {
            const answers = JSON.parse(saved);
            
            // Restore radio button answers
            Object.entries(answers).forEach(([key, value]) => {
                const input = document.querySelector(`[name="${key}"][value="${value}"]`);
                if (input) {
                    input.checked = true;
                    const questionNumber = parseInt(input.closest('.question-section').dataset.question);
                    this.markQuestionAnswered(questionNumber);
                }
            });
        }
        
        if (progress) {
            const progressData = JSON.parse(progress);
            this.answeredQuestions = new Set(progressData.answeredQuestions || []);
            
            // Restore answered indicators
            this.answeredQuestions.forEach(questionNumber => {
                const indicator = document.querySelector(`[data-question="${questionNumber}"]`);
                if (indicator) {
                    indicator.classList.add('answered');
                }
            });
        }
    }

    confirmSubmit() {
        const unansweredCount = this.totalQuestions - this.answeredQuestions.size;
        
        if (unansweredCount > 0) {
            this.showModal(
                'Confirm Submission',
                `You have ${unansweredCount} unanswered questions. Are you sure you want to submit?`,
                'Submit Anyway',
                () => this.submitExam(),
                'Review Questions',
                () => this.showUnansweredQuestions()
            );
        } else {
            this.showModal(
                'Submit Exam',
                'Are you sure you want to submit your exam? This action cannot be undone.',
                'Submit Exam',
                () => this.submitExam()
            );
        }
    }

    showUnansweredQuestions() {
        const unanswered = [];
        for (let i = 1; i <= this.totalQuestions; i++) {
            if (!this.answeredQuestions.has(i)) {
                unanswered.push(i);
            }
        }
        
        if (unanswered.length > 0) {
            this.showQuestion(unanswered[0]);
        }
    }

    submitExam() {
        clearInterval(this.timerInterval);
        clearInterval(this.autoSaveInterval);
        
        // Clear saved data
        localStorage.removeItem('ujian_answers_' + {{ $ujian->id }});
        localStorage.removeItem('ujian_progress_' + {{ $ujian->id }});
        
        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        submitBtn.disabled = true;
        
        // Submit form
        document.getElementById('examForm').submit();
    }

    showModal(title, message, confirmText, confirmCallback, cancelText = null, cancelCallback = null) {
        // Create modal HTML
        const modal = document.createElement('div');
        modal.className = 'exam-modal-overlay';
        modal.innerHTML = `
            <div class="exam-modal">
                <div class="modal-header">
                    <h3>${title}</h3>
                </div>
                <div class="modal-body">
                    <p>${message}</p>
                </div>
                <div class="modal-footer">
                    ${cancelText ? `<button class="modal-btn cancel-btn">${cancelText}</button>` : ''}
                    <button class="modal-btn confirm-btn">${confirmText}</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Add event listeners
        modal.querySelector('.confirm-btn').addEventListener('click', () => {
            document.body.removeChild(modal);
            confirmCallback();
        });
        
        if (cancelText) {
            modal.querySelector('.cancel-btn').addEventListener('click', () => {
                document.body.removeChild(modal);
                if (cancelCallback) cancelCallback();
            });
        }
        
        // Close on overlay click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                document.body.removeChild(modal);
            }
        });
    }
}

// Initialize exam interface when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new ExamInterface();
});

// Add modal styles
const modalStyles = `
.exam-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    backdrop-filter: blur(4px);
}

.exam-modal {
    background: #1e293b;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    padding: 2rem;
    max-width: 400px;
    width: 90%;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
}

.modal-header h3 {
    margin: 0 0 1rem 0;
    color: #ffffff;
    font-size: 1.25rem;
    font-weight: 600;
}

.modal-body p {
    margin: 0 0 1.5rem 0;
    color: #cbd5e1;
    line-height: 1.6;
}

.modal-footer {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

.modal-btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.cancel-btn {
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.cancel-btn:hover {
    background: rgba(255, 255, 255, 0.2);
}

.confirm-btn {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #ffffff;
}

.confirm-btn:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    transform: translateY(-2px);
}
`;

// Inject modal styles
const styleSheet = document.createElement('style');
styleSheet.textContent = modalStyles;
document.head.appendChild(styleSheet);
</script>
@endsection
