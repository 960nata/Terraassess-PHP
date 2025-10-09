<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terra Assessment - Buat Tugas Pilihan Ganda</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .header h1 {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .header p {
            color: rgba(255,255,255,0.9);
            font-size: 1.1rem;
        }

        .form-container {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .form-section {
            margin-bottom: 2.5rem;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 3px solid #667eea;
            display: inline-block;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #4a5568;
            font-size: 0.95rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .question-card {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .question-card:hover {
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
        }

        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .question-number {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .remove-question {
            background: #fed7d7;
            color: #c53030;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .remove-question:hover {
            background: #feb2b2;
        }

        .options-container {
            margin-top: 1rem;
        }

        .option-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            padding: 0.75rem;
            background: white;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }

        .option-radio {
            width: 20px;
            height: 20px;
            accent-color: #667eea;
        }

        .option-input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 0.5rem;
            font-size: 1rem;
        }

        .option-input:focus {
            outline: none;
            background: #f8fafc;
            border-radius: 5px;
        }

        .add-question-btn {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 1rem;
        }

        .add-question-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(72, 187, 120, 0.3);
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .btn {
            padding: 1rem 2rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
            border: 2px solid #cbd5e0;
        }

        .btn-secondary:hover {
            background: #cbd5e0;
            transform: translateY(-1px);
        }

        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .floating-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 6s ease-in-out infinite;
        }

        .floating-circle:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-circle:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }

        .floating-circle:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .back-btn {
            position: fixed;
            top: 2rem;
            left: 2rem;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            z-index: 1000;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .form-container {
                padding: 1.5rem;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>

    <a href="{{ route('superadmin.tugas.index') }}" class="back-btn">
        <i class="fas fa-arrow-left"></i>
        Kembali
    </a>

    <div class="container">
        <div class="header">
            <h1><i class="fas fa-list-ul"></i> Buat Tugas Pilihan Ganda</h1>
            <p>Buat tugas dengan pilihan ganda untuk evaluasi yang efektif</p>
        </div>

        <form class="form-container" action="{{ route('superadmin.tugas.index.create') }}" method="POST">
            @csrf
            <input type="hidden" name="task_type" value="multiple_choice">
            
            <!-- Basic Information -->
            <div class="form-section">
                <h2 class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Informasi Dasar
                </h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="task_title">Judul Tugas</label>
                        <input type="text" id="task_title" name="task_title" placeholder="Masukkan judul tugas" required>
                    </div>
                    <div class="form-group">
                        <label for="class_id">Kelas</label>
                        <select id="class_id" name="class_id" required>
                            <option value="">Pilih kelas</option>
                            @foreach($classes ?? [] as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="subject_id">Mata Pelajaran</label>
                        <select id="subject_id" name="subject_id" required>
                            <option value="">Pilih mata pelajaran</option>
                            @foreach($subjects ?? [] as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="max_score">Nilai Maksimal</label>
                        <input type="number" id="max_score" name="max_score" placeholder="100" min="1" max="100" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="task_description">Deskripsi Tugas</label>
                    <textarea id="task_description" name="task_description" placeholder="Masukkan deskripsi tugas" required></textarea>
                </div>
            </div>

            <!-- Questions Section -->
            <div class="form-section">
                <h2 class="section-title">
                    <i class="fas fa-question-circle"></i>
                    Pertanyaan
                </h2>
                <div id="questions-container">
                    <div class="question-card" data-question="1">
                        <div class="question-header">
                            <span class="question-number">Pertanyaan 1</span>
                            <button type="button" class="remove-question" onclick="removeQuestion(1)" style="display: none;">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                        <div class="form-group">
                            <label>Pertanyaan</label>
                            <textarea name="questions[1][question]" placeholder="Masukkan pertanyaan" required></textarea>
                        </div>
                        <div class="options-container">
                            <div class="option-row">
                                <input type="radio" name="questions[1][correct]" value="A" class="option-radio" required>
                                <input type="text" name="questions[1][options][A]" placeholder="Pilihan A" class="option-input" required>
                            </div>
                            <div class="option-row">
                                <input type="radio" name="questions[1][correct]" value="B" class="option-radio" required>
                                <input type="text" name="questions[1][options][B]" placeholder="Pilihan B" class="option-input" required>
                            </div>
                            <div class="option-row">
                                <input type="radio" name="questions[1][correct]" value="C" class="option-radio" required>
                                <input type="text" name="questions[1][options][C]" placeholder="Pilihan C" class="option-input" required>
                            </div>
                            <div class="option-row">
                                <input type="radio" name="questions[1][correct]" value="D" class="option-radio" required>
                                <input type="text" name="questions[1][options][D]" placeholder="Pilihan D" class="option-input" required>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="add-question-btn" onclick="addQuestion()">
                    <i class="fas fa-plus"></i>
                    Tambah Pertanyaan
                </button>
            </div>

            <div class="form-actions">
                <a href="{{ route('superadmin.tugas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Simpan Tugas
                </button>
            </div>
        </form>
    </div>

    <script>
        let questionCount = 1;

        function addQuestion() {
            questionCount++;
            const container = document.getElementById('questions-container');
            const questionHTML = `
                <div class="question-card" data-question="${questionCount}">
                    <div class="question-header">
                        <span class="question-number">Pertanyaan ${questionCount}</span>
                        <button type="button" class="remove-question" onclick="removeQuestion(${questionCount})">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                    <div class="form-group">
                        <label>Pertanyaan</label>
                        <textarea name="questions[${questionCount}][question]" placeholder="Masukkan pertanyaan" required></textarea>
                    </div>
                    <div class="options-container">
                        <div class="option-row">
                            <input type="radio" name="questions[${questionCount}][correct]" value="1" class="option-radio" required>
                            <input type="text" name="questions[${questionCount}][options][1]" placeholder="Pilihan 1" class="option-input" required>
                        </div>
                        <div class="option-row">
                            <input type="radio" name="questions[${questionCount}][correct]" value="2" class="option-radio" required>
                            <input type="text" name="questions[${questionCount}][options][2]" placeholder="Pilihan 2" class="option-input" required>
                        </div>
                        <div class="option-row">
                            <input type="radio" name="questions[${questionCount}][correct]" value="3" class="option-radio" required>
                            <input type="text" name="questions[${questionCount}][options][3]" placeholder="Pilihan 3" class="option-input" required>
                        </div>
                        <div class="option-row">
                            <input type="radio" name="questions[${questionCount}][correct]" value="4" class="option-radio" required>
                            <input type="text" name="questions[${questionCount}][options][4]" placeholder="Pilihan 4" class="option-input" required>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', questionHTML);
        }

        function removeQuestion(questionNum) {
            const question = document.querySelector(`[data-question="${questionNum}"]`);
            if (question) {
                question.remove();
            }
        }

        // Show remove button for first question when there are multiple questions
        function updateRemoveButtons() {
            const questions = document.querySelectorAll('.question-card');
            questions.forEach((question, index) => {
                const removeBtn = question.querySelector('.remove-question');
                if (questions.length > 1) {
                    removeBtn.style.display = 'block';
                } else {
                    removeBtn.style.display = 'none';
                }
            });
        }

        // Update remove buttons when questions are added/removed
        document.addEventListener('DOMContentLoaded', function() {
            updateRemoveButtons();
        });

        // Override addQuestion to update remove buttons
        const originalAddQuestion = addQuestion;
        addQuestion = function() {
            originalAddQuestion();
            updateRemoveButtons();
        };

        // Override removeQuestion to update remove buttons
        const originalRemoveQuestion = removeQuestion;
        removeQuestion = function(questionNum) {
            originalRemoveQuestion(questionNum);
            updateRemoveButtons();
        };
    </script>
</body>
</html>
