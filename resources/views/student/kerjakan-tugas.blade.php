@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Kerjakan Tugas')

@section('styles')
<style>
    .task-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem;
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }

    .task-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .task-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    .task-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        position: relative;
        z-index: 2;
    }

    .task-badges {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        position: relative;
        z-index: 2;
    }

    .badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .badge-blue {
        background: rgba(59, 130, 246, 0.3);
        color: #93c5fd;
    }

    .badge-purple {
        background: rgba(147, 51, 234, 0.3);
        color: #c4b5fd;
    }

    .badge-green {
        background: rgba(34, 197, 94, 0.3);
        color: #86efac;
    }

    .badge-orange {
        background: rgba(249, 115, 22, 0.3);
        color: #fed7aa;
    }

    .badge-deadline {
        background: rgba(239, 68, 68, 0.3);
        color: #fca5a5;
    }

    .task-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        position: relative;
        z-index: 2;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: rgba(255, 255, 255, 0.1);
        padding: 0.75rem 1rem;
        border-radius: 12px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .meta-item i {
        color: #93c5fd;
        font-size: 1.1rem;
    }

    .task-content {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .task-description {
        margin-bottom: 2rem;
    }

    .task-description h3 {
        color: #ffffff;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .task-description h3::before {
        content: '';
        width: 4px;
        height: 24px;
        background: linear-gradient(45deg, #667eea, #764ba2);
        border-radius: 2px;
    }

    .task-description p {
        color: #cbd5e1;
        line-height: 1.6;
        font-size: 1rem;
    }

    .questions-container {
        margin-top: 2rem;
    }

    .question-card {
        background: linear-gradient(135deg, #2a2a3e 0%, #1e293b 100%);
        border: 1px solid #475569;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .question-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2);
    }

    .question-card:hover {
        transform: translateY(-2px);
        border-color: #667eea;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .question-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .question-number {
        color: #ffffff;
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }

    .question-points {
        background: linear-gradient(45deg, #10b981, #059669);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .question-content {
        margin-bottom: 2rem;
    }

    .question-text {
        color: #ffffff;
        font-size: 1.1rem;
        line-height: 1.6;
        margin: 0;
    }

    .options-container {
        display: grid;
        gap: 1rem;
    }

    .option-item {
        position: relative;
    }

    .option-input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .option-label {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.5rem;
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid transparent;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .option-label::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, #667eea, #764ba2);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .option-label:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: #667eea;
    }

    .option-input:checked + .option-label {
        background: rgba(102, 126, 234, 0.2);
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
    }

    .option-input:checked + .option-label::before {
        opacity: 0.1;
    }

    .option-radio {
        width: 20px;
        height: 20px;
        border: 2px solid #64748b;
        border-radius: 50%;
        position: relative;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .option-input:checked + .option-label .option-radio {
        border-color: #667eea;
        background: #667eea;
    }

    .option-input:checked + .option-label .option-radio::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 8px;
        height: 8px;
        background: white;
        border-radius: 50%;
    }

    .option-letter {
        background: linear-gradient(45deg, #667eea, #764ba2);
        color: white;
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

    .option-text {
        color: #ffffff;
        font-size: 1rem;
        flex: 1;
        position: relative;
        z-index: 2;
    }

    .task-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #475569;
    }

    .btn {
        padding: 1rem 2rem;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
    }

    .btn-secondary {
        background: #475569;
        color: #ffffff;
    }

    .btn-secondary:hover {
        background: #64748b;
        transform: translateY(-2px);
    }

    .btn-primary {
        background: linear-gradient(45deg, #667eea, #764ba2);
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .progress-bar {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        height: 8px;
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .progress-fill {
        background: linear-gradient(45deg, #10b981, #059669);
        height: 100%;
        border-radius: 10px;
        transition: width 0.3s ease;
    }

    .timer {
        background: rgba(239, 68, 68, 0.2);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 2rem;
        text-align: center;
    }

    .timer-text {
        color: #fca5a5;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .file-download {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .download-link {
        color: #93c5fd;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .download-link:hover {
        color: #60a5fa;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .task-container {
            padding: 1rem;
        }

        .task-header {
            padding: 1.5rem;
        }

        .task-title {
            font-size: 1.5rem;
        }

        .task-badges {
            flex-direction: column;
            gap: 0.5rem;
        }

        .task-meta {
            grid-template-columns: 1fr;
        }

        .question-card {
            padding: 1.5rem;
        }

        .task-actions {
            flex-direction: column;
            gap: 1rem;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }

    /* Animation for questions */
    .question-card {
        animation: slideInUp 0.6s ease-out;
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Staggered animation */
    .question-card:nth-child(1) { animation-delay: 0.1s; }
    .question-card:nth-child(2) { animation-delay: 0.2s; }
    .question-card:nth-child(3) { animation-delay: 0.3s; }
    .question-card:nth-child(4) { animation-delay: 0.4s; }
    .question-card:nth-child(5) { animation-delay: 0.5s; }

    /* Styling untuk konten HTML di soal */
    .question-text img,
    .option-text img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1rem 0;
        display: block;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .question-text p,
    .option-text p {
        margin: 0.5rem 0;
        line-height: 1.6;
    }

    .question-content {
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    /* Styling khusus untuk gambar base64 */
    .question-text img[src^="data:image"],
    .option-text img[src^="data:image"] {
        max-width: 600px;
        max-height: 400px;
        object-fit: contain;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Styling untuk konten HTML lainnya */
    .question-text ul,
    .option-text ul {
        margin: 1rem 0;
        padding-left: 2rem;
    }

    .question-text li,
    .option-text li {
        margin: 0.5rem 0;
        color: #cbd5e1;
    }

    .question-text strong,
    .option-text strong {
        color: #ffffff;
        font-weight: 600;
    }

    .question-text em,
    .option-text em {
        color: #94a3b8;
        font-style: italic;
    }

    /* Responsive untuk gambar */
    @media (max-width: 768px) {
        .question-text img,
        .option-text img {
            max-width: 100%;
            height: auto;
        }
        
        .question-text img[src^="data:image"],
        .option-text img[src^="data:image"] {
            max-width: 100%;
            max-height: 300px;
        }
    }
</style>
@endsection

@php
function getTaskTypeInfo($tipe) {
    $types = [
        1 => ['name' => 'Pilihan Ganda', 'icon' => 'fas fa-list-ul', 'color' => 'blue'],
        2 => ['name' => 'Essay', 'icon' => 'fas fa-edit', 'color' => 'purple'],
        3 => ['name' => 'Mandiri', 'icon' => 'fas fa-user', 'color' => 'green'],
        4 => ['name' => 'Kelompok', 'icon' => 'fas fa-users', 'color' => 'orange']
    ];
    return $types[$tipe] ?? ['name' => 'Tugas', 'icon' => 'fas fa-clipboard', 'color' => 'gray'];
}

function decodeHtml($text) {
    return html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}
@endphp

@section('content')
<div class="task-container">
    <!-- Task Header -->
    <div class="task-header">
        <h1 class="task-title">{{ $tugas->name }}</h1>
        
        <div class="task-badges">
            @php
                $typeInfo = getTaskTypeInfo($tugas->tipe);
            @endphp
            <span class="badge badge-{{ $typeInfo['color'] }}">
                <i class="{{ $typeInfo['icon'] }}"></i>
                {{ $typeInfo['name'] }}
            </span>
            <span class="badge badge-deadline">
                <i class="fas fa-clock"></i>
                Deadline: {{ \Carbon\Carbon::parse($tugas->due)->format('d M Y, H:i') }}
            </span>
        </div>

        <div class="task-meta">
            <div class="meta-item">
                <i class="fas fa-book"></i>
                <span>{{ $tugas->kelasMapel->mapel->name ?? 'Mata Pelajaran' }}</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-user"></i>
                <span>{{ $tugas->kelasMapel->pengajar->first()->name ?? 'N/A' }}</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-calendar"></i>
                <span>{{ \Carbon\Carbon::parse($tugas->created_at)->format('d M Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Task Content -->
    <div class="task-content">
        <!-- Task Description -->
        <div class="task-description">
            <h3>
                <i class="fas fa-info-circle"></i>
                Deskripsi Tugas
            </h3>
            <p>{{ $tugas->description ?? 'Tidak ada deskripsi tersedia.' }}</p>
        </div>

        <!-- Task File -->
        @if($tugas->file_path)
        <div class="file-download">
            <i class="fas fa-download"></i>
            <a href="{{ asset('storage/' . $tugas->file_path) }}" download class="download-link">
                <i class="fas fa-file"></i>
                Download File Tugas
            </a>
        </div>
        @endif

        <!-- Timer (if deadline is set) -->
        @if($tugas->due)
        <div class="timer">
            <div class="timer-text">
                <i class="fas fa-clock"></i>
                Waktu tersisa: <span id="countdown"></span>
            </div>
        </div>
        @endif

        <!-- Progress Bar -->
        <div class="progress-bar">
            <div class="progress-fill" style="width: 0%" id="progressBar"></div>
        </div>

        <!-- Questions Form -->
        @if($tugas->tipe == 1)
            <!-- Multiple Choice Questions -->
            <form action="{{ route('student.submit-tugas', $tugas->id) }}" method="POST" class="tugas-form" id="multipleChoiceForm">
                @csrf
                
                <div class="questions-container">
                    @foreach($tugas->TugasMultiple as $index => $question)
                        <div class="question-card">
                            <div class="question-header">
                                <h3 class="question-number">Soal {{ $index + 1 }}</h3>
                                <span class="question-points">{{ $question->poin ?? 1 }} poin</span>
                            </div>
                            
                            <div class="question-content">
                                <div class="question-text">{!! decodeHtml($question->soal) !!}</div>
                            </div>
                            
                            <div class="options-container">
                                @php
                                    $options = [
                                        'A' => $question->a,
                                        'B' => $question->b,
                                        'C' => $question->c,
                                        'D' => $question->d,
                                    ];
                                @endphp
                                
                                @foreach($options as $letter => $option)
                                    <div class="option-item">
                                        <input type="radio" 
                                               name="answers[{{ $question->id }}]" 
                                               value="{{ $letter }}" 
                                               id="question_{{ $question->id }}_{{ $letter }}"
                                               class="option-input">
                                        <label for="question_{{ $question->id }}_{{ $letter }}" class="option-label">
                                            <div class="option-radio"></div>
                                            <span class="option-letter">{{ $letter }}</span>
                                            <span class="option-text">{!! decodeHtml($option) !!}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="task-actions">
                    <a href="{{ route('student.tugas') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        Kirim Jawaban
                    </button>
                </div>
            </form>

        @elseif($tugas->tipe == 2)
            <!-- Essay Questions -->
            <form action="{{ route('student.submit-tugas', $tugas->id) }}" method="POST" enctype="multipart/form-data" class="tugas-form">
                @csrf
                
                <div class="question-card">
                    <div class="question-header">
                        <h3 class="question-number">Jawaban Essay</h3>
                        <span class="question-points">{{ $tugas->poin ?? 10 }} poin</span>
                    </div>
                    
                    <div class="question-content">
                        <div class="question-text">{!! decodeHtml($tugas->soal) !!}</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="essay_answer" class="form-label">Jawaban Anda:</label>
                        <textarea name="essay_answer" 
                                  id="essay_answer" 
                                  rows="10" 
                                  class="form-control"
                                  placeholder="Tulis jawaban essay Anda di sini..."
                                  required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="file_upload" class="form-label">Upload File (Opsional):</label>
                        <input type="file" 
                               name="file_upload" 
                               id="file_upload" 
                               class="form-control"
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    </div>
                </div>

                <div class="task-actions">
                    <a href="{{ route('student.tugas') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        Kirim Jawaban
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>

<script>
// Countdown Timer
@if($tugas->due)
function updateCountdown() {
    const deadline = new Date('{{ $tugas->due }}').getTime();
    const now = new Date().getTime();
    const distance = deadline - now;

    if (distance < 0) {
        document.getElementById('countdown').innerHTML = "Waktu habis!";
        return;
    }

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    let timeString = '';
    if (days > 0) timeString += days + ' hari ';
    if (hours > 0) timeString += hours + ' jam ';
    if (minutes > 0) timeString += minutes + ' menit ';
    timeString += seconds + ' detik';

    document.getElementById('countdown').innerHTML = timeString;
}

// Update countdown every second
setInterval(updateCountdown, 1000);
updateCountdown();
@endif

// Progress Bar
function updateProgress() {
    const totalQuestions = {{ $tugas->TugasMultiple->count() ?? 1 }};
    const answeredQuestions = document.querySelectorAll('input[type="radio"]:checked').length;
    const progress = (answeredQuestions / totalQuestions) * 100;
    document.getElementById('progressBar').style.width = progress + '%';
}

// Update progress when answers change
document.querySelectorAll('input[type="radio"]').forEach(input => {
    input.addEventListener('change', updateProgress);
});

// Form validation
document.getElementById('multipleChoiceForm').addEventListener('submit', function(e) {
    const totalQuestions = {{ $tugas->TugasMultiple->count() ?? 1 }};
    const answeredQuestions = document.querySelectorAll('input[type="radio"]:checked').length;
    
    if (answeredQuestions < totalQuestions) {
        e.preventDefault();
        alert('Silakan jawab semua pertanyaan sebelum mengirim!');
        return false;
    }
    
    if (!confirm('Apakah Anda yakin ingin mengirim jawaban? Anda tidak dapat mengubah jawaban setelah mengirim.')) {
        e.preventDefault();
        return false;
    }
});

// Auto-save progress (optional)
function autoSave() {
    const formData = new FormData(document.getElementById('multipleChoiceForm'));
    // You can implement auto-save functionality here
}

// Auto-save every 30 seconds
setInterval(autoSave, 30000);
</script>
@endsection