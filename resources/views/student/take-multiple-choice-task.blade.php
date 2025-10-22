@extends('layouts.unified-layout')

@section('title', 'Kerjakan Tugas Pilihan Ganda')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('student.tasks') }}">Tugas</a></li>
                        <li class="breadcrumb-item active">{{ $tugas->name }}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ $tugas->name }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ph-file-text me-2"></i>
                            {{ $tugas->name }}
                        </h5>
                        <div class="text-muted">
                            <i class="ph-clock me-1"></i>
                            Batas waktu: {{ $tugas->due->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($tugas->content)
                        <div class="alert alert-info">
                            <h6>Deskripsi Tugas:</h6>
                            <p class="mb-0">{{ $tugas->content }}</p>
                        </div>
                    @endif

                    <form action="{{ route('student.tasks.submit-multiple-choice', $tugas->id) }}" method="POST" id="taskForm">
                        @csrf
                        
                        <div class="questions-container">
                            @foreach($tugas->TugasMultiple as $index => $question)
                                <div class="question-card mb-4">
                                    <div class="question-header">
                                        <h6 class="question-number">Soal {{ $index + 1 }}</h6>
                                        <span class="badge bg-primary">{{ $question->poin }} poin</span>
                                    </div>
                                    
                                    <div class="question-content mb-3">
                                        <p class="question-text">{{ $question->soal }}</p>
                                    </div>
                                    
                                    <div class="options-container">
                                        @php
                                            $options = [
                                                '1' => $question->a,
                                                '2' => $question->b,
                                                '3' => $question->c,
                                            ];
                                            if($question->d) $options['4'] = $question->d;
                                            if($question->e) $options['5'] = $question->e;
                                        @endphp
                                        
                                        @foreach($options as $number => $option)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" 
                                                       name="answers[{{ $question->id }}]" 
                                                       value="{{ $number }}" 
                                                       id="q{{ $question->id }}_{{ $number }}"
                                                       {{ old('answers.' . $question->id) == $number ? 'checked' : '' }}>
                                                <label class="form-check-label" for="q{{ $question->id }}_{{ $number }}">
                                                    {{ $number }}. {{ $option }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                <i class="ph-check me-1"></i>
                                Kirim Jawaban
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.question-card {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    background: #f8f9fa;
}

.question-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #dee2e6;
}

.question-number {
    font-weight: 600;
    color: #495057;
    margin: 0;
}

.question-text {
    font-size: 16px;
    line-height: 1.6;
    color: #212529;
    margin-bottom: 0;
}

.options-container {
    background: white;
    border-radius: 6px;
    padding: 15px;
}

.form-check {
    padding-left: 1.5rem;
}

.form-check-input {
    margin-top: 0.25rem;
}

.form-check-label {
    font-size: 15px;
    line-height: 1.5;
    cursor: pointer;
}

.alert-info {
    border-left: 4px solid #17a2b8;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('taskForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Check if all questions are answered
    function checkAnswers() {
        const totalQuestions = document.querySelectorAll('.question-card').length;
        const answeredQuestions = document.querySelectorAll('input[type="radio"]:checked').length;
        
        if (answeredQuestions === totalQuestions) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="ph-check me-1"></i> Kirim Jawaban';
        } else {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<i class="ph-check me-1"></i> Jawab Semua Soal (${answeredQuestions}/${totalQuestions})`;
        }
    }
    
    // Add event listeners to all radio buttons
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', checkAnswers);
    });
    
    // Initial check
    checkAnswers();
    
    // Form submission
    form.addEventListener('submit', function(e) {
        const totalQuestions = document.querySelectorAll('.question-card').length;
        const answeredQuestions = document.querySelectorAll('input[type="radio"]:checked').length;
        
        if (answeredQuestions < totalQuestions) {
            e.preventDefault();
            alert('Silakan jawab semua soal sebelum mengirim!');
            return false;
        }
        
        if (!confirm('Apakah Anda yakin ingin mengirim jawaban? Setelah dikirim, Anda tidak dapat mengubah jawaban.')) {
            e.preventDefault();
            return false;
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ph-spinner ph-spin me-1"></i> Mengirim...';
    });
});
</script>
@endpush
