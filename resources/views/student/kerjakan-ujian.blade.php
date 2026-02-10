@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Kerjakan Ujian')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-file-alt"></i>
        Kerjakan Ujian
    </h1>
    <p class="page-description">Selesaikan ujian yang diberikan oleh pengajar</p>
</div>

<div class="ujian-container">
    <div class="glass-card">
        <div class="ujian-header">
            <h2 class="ujian-title">{{ $ujian->judul }}</h2>
            <div class="ujian-meta">
                <div class="meta-item">
                    <i class="fas fa-book"></i>
                    <span>{{ $ujian->kelasMapel->mapel->name ?? 'Mata Pelajaran' }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-user"></i>
                    <span>{{ $ujian->kelasMapel->pengajar->first()->name ?? 'N/A' }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-clock"></i>
                    <span>{{ $ujian->waktu_pengerjaan }} menit</span>
                </div>
            </div>
        </div>

        <div class="ujian-content">
            <div class="ujian-description">
                <h3>Deskripsi Ujian</h3>
                <p>{{ $ujian->deskripsi ?? 'Tidak ada deskripsi tersedia.' }}</p>
            </div>

            <form action="{{ route('student.submit-ujian', $ujian->id) }}" method="POST" class="ujian-form">
                @csrf
                <div class="questions-container">
                    @foreach($ujian->soalUjianMultiples as $index => $soal)
                    <div class="question-card">
                        <div class="question-header">
                            <h4>Soal {{ $index + 1 }}</h4>
                            <span class="question-points">{{ $soal->poin ?? 1 }} poin</span>
                        </div>
                        <div class="question-content">
                            <p class="question-text">{{ $soal->pertanyaan }}</p>
                            <div class="options">
                                @foreach(['a', 'b', 'c', 'd'] as $option)
                                <label class="option-label">
                                    <input type="radio" name="jawaban[{{ $soal->id }}]" value="{{ $option }}" 
                                           class="option-input" required>
                                    <span class="option-text">{{ $option }}. {{ $soal->{'pilihan_' . $option} }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @foreach($ujian->soalUjianEssays as $index => $soal)
                    <div class="question-card">
                        <div class="question-header">
                            <h4>Soal {{ $ujian->soalUjianMultiples->count() + $index + 1 }}</h4>
                            <span class="question-points">{{ $soal->poin ?? 1 }} poin</span>
                        </div>
                        <div class="question-content">
                            <p class="question-text">{{ $soal->pertanyaan }}</p>
                            @include('components.modern-quill-editor', [
                                'name' => 'jawaban_essay[' . $soal->id . ']',
                                'content' => '',
                                'placeholder' => 'Tulis jawaban Anda di sini...',
                                'height' => '200px',
                                'required' => true,
                                'editorId' => 'essay-editor-' . $soal->id
                            ])
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="form-actions">
                    <a href="{{ route('student.ujian') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        Kirim Jawaban
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('additional-styles')
<style>
    .ujian-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 2rem;
        margin: 2rem 0;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }

    .ujian-header {
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .ujian-title {
        color: #ffffff;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .ujian-meta {
        display: flex;
        justify-content: center;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #cbd5e1;
        font-size: 0.9rem;
    }

    .meta-item i {
        color: #3b82f6;
        font-size: 1rem;
    }

    .ujian-content {
        color: #ffffff;
    }

    .ujian-description {
        margin-bottom: 2rem;
    }

    .ujian-description h3 {
        color: #ffffff;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .ujian-description h3::before {
        content: "📝";
        font-size: 1.5rem;
    }

    .ujian-description p {
        color: #cbd5e1;
        line-height: 1.6;
        font-size: 1rem;
        background: rgba(255, 255, 255, 0.05);
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid #3b82f6;
    }

    .questions-container {
        margin-bottom: 2rem;
    }

    .question-card {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .question-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .question-header h4 {
        color: #ffffff;
        font-size: 1.125rem;
        font-weight: 600;
        margin: 0;
    }

    .question-points {
        background: rgba(59, 130, 246, 0.2);
        color: #3b82f6;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .question-content {
        color: #ffffff;
    }

    .question-text {
        color: #ffffff;
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .options {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .option-label {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .option-label:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(59, 130, 246, 0.3);
    }

    .option-input {
        margin: 0;
        accent-color: #3b82f6;
    }

    .option-text {
        color: #ffffff;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .essay-answer {
        width: 100%;
        padding: 0.75rem 1rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        color: #ffffff;
        font-size: 1rem;
        transition: all 0.3s ease;
        resize: vertical;
    }

    .essay-answer:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        background: rgba(255, 255, 255, 0.15);
    }

    .essay-answer::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .ujian-form {
        background: rgba(255, 255, 255, 0.05);
        padding: 2rem;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        min-width: 120px;
        justify-content: center;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        transform: translateY(-2px);
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .glass-card {
            margin: 1rem 0;
            padding: 1.5rem;
        }

        .ujian-title {
            font-size: 1.5rem;
        }

        .ujian-meta {
            flex-direction: column;
            gap: 0.75rem;
            align-items: center;
        }

        .ujian-form {
            padding: 1.5rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }

        .question-card {
            padding: 1rem;
        }
    }

    @media (max-width: 480px) {
        .glass-card {
            padding: 1rem;
        }

        .ujian-title {
            font-size: 1.25rem;
        }

        .ujian-description h3 {
            font-size: 1.125rem;
        }

        .ujian-form {
            padding: 1rem;
        }
    }
</style>
@endsection

@section('additional-scripts')
<script>
    // Timer functionality
    let timeLeft = {{ $ujian->waktu_pengerjaan * 60 }};
    let timerInterval;

    function startTimer() {
        timerInterval = setInterval(function() {
            timeLeft--;
            
            const hours = Math.floor(timeLeft / 3600);
            const minutes = Math.floor((timeLeft % 3600) / 60);
            const seconds = timeLeft % 60;
            
            const timeString = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (document.getElementById('timer')) {
                document.getElementById('timer').textContent = timeString;
            }
            
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                alert('Waktu ujian telah habis! Jawaban akan otomatis dikirim.');
                document.querySelector('form').submit();
            }
        }, 1000);
    }

    // Start timer when page loads
    document.addEventListener('DOMContentLoaded', function() {
        startTimer();
    });

    // Auto-save answers
    function saveAnswers() {
        const formData = new FormData(document.querySelector('form'));
        const answers = {};
        
        for (let [key, value] of formData.entries()) {
            if (key.startsWith('jawaban')) {
                answers[key] = value;
            }
        }
        
        localStorage.setItem('ujian_answers_' + {{ $ujian->id }}, JSON.stringify(answers));
    }

    // Load saved answers
    function loadAnswers() {
        const saved = localStorage.getItem('ujian_answers_' + {{ $ujian->id }});
        if (saved) {
            const answers = JSON.parse(saved);
            for (let [key, value] of Object.entries(answers)) {
                const input = document.querySelector(`[name="${key}"][value="${value}"]`);
                if (input) {
                    input.checked = true;
                }
            }
        }
    }

    // Save answers every 30 seconds
    setInterval(saveAnswers, 30000);

    // Load answers on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadAnswers();
    });
</script>
<x-proctoring-scripts examId="{{ $ujian->id }}" />
@endsection
