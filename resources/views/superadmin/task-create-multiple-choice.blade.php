@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - Buat Tugas Pilihan Ganda')

@section('styles')
<style>
.task-form {
            background-color: #1e293b;
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #334155;
        }

        .form-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #2a2a3e;
            border-radius: 12px;
            border: 1px solid #334155;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title i {
            color: #667eea;
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

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #ffffff;
            font-size: 0.9rem;
        }
        
        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #1e293b;
            border: 2px solid #334155;
            border-radius: 8px;
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: #667eea;
            background: #2a2a3e;
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .question-container {
            background: #1e293b;
            border: 2px solid #334155;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .question-number {
            font-weight: 600;
            color: #667eea;
            font-size: 1.1rem;
        }

        .remove-question {
            background: #e53e3e;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .remove-question:hover {
            background: #c53030;
        }

        .options-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .option-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .option-label {
            font-weight: 500;
            min-width: 30px;
            color: #94a3b8;
        }

        .correct-answer-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5a67d8;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #475569;
            color: #ffffff;
        }

        .btn-secondary:hover {
            background: #334155;
        }

        .btn-success {
            background: #48bb78;
            color: white;
        }

        .btn-success:hover {
            background: #38a169;
        }

        .btn-danger {
            background: #e53e3e;
            color: white;
        }

        .btn-danger:hover {
            background: #c53030;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .loading {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .tips {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .tips h4 {
            color: #ffffff;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .tips h4 i {
            color: #667eea;
        }

        .tips ul {
            color: #94a3b8;
            padding-left: 1.5rem;
        }

        .tips li {
            margin-bottom: 0.25rem;
        }
        
        .add-question-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0 auto;
        }

        .add-question-btn:hover {
            background: #5a67d8;
            transform: translateY(-2px);
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-title i {
            color: #667eea;
        }

        .page-description {
            color: #94a3b8;
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .options-grid {
                grid-template-columns: 1fr;
            }
            
            .correct-answer-group {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
</style>
@endsection

@section('content')
<div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-list-ul"></i>
                Buat Tugas Pilihan Ganda
            </h1>
            <p class="page-description">Buat soal pilihan ganda dengan mudah dan cepat</p>
        </div>

        <div class="task-form">
        <form id="multipleChoiceForm" method="POST" action="{{ route('teacher.tasks.store') }}">
            @csrf
            <input type="hidden" name="tipe" value="1">

                <!-- Informasi Dasar -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-info-circle"></i>
                                    Informasi Dasar
                                </h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                                        <label class="form-label">Judul Tugas *</label>
                            <input type="text" name="title" class="form-input" placeholder="Masukkan judul tugas" required>
                                    </div>

                        <div class="form-group">
                            <label class="form-label">Kelas *</label>
                            <select name="class_id" class="form-select" required>
                                            <option value="">Pilih Kelas</option>
                                @foreach($kelas as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                        <div class="form-group">
                                        <label class="form-label">Mata Pelajaran *</label>
                            <select name="subject_id" class="form-select" required>
                                            <option value="">Pilih Mata Pelajaran</option>
                                @foreach($mapel as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                        <div class="form-group">
                            <label class="form-label">Waktu Pengerjaan (menit) *</label>
                            <input type="number" name="duration" class="form-input" placeholder="60" min="5" max="180" required>
                        </div>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Tanggal Mulai *</label>
                            <input type="datetime-local" name="start_date" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Tanggal Berakhir *</label>
                            <input type="datetime-local" name="end_date" class="form-input" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Limit Hari Pengerjaan *</label>
                        <input type="number" name="limit_days" class="form-input" placeholder="7" min="1" max="30" required>
                        <small style="color: #94a3b8; font-size: 0.8rem;">Berapa hari siswa bisa mengerjakan tugas</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Deskripsi Tugas</label>
                        <textarea name="description" class="form-textarea" placeholder="Masukkan deskripsi tugas yang jelas dan detail"></textarea>
                    </div>
                        </div>

                <!-- Soal Pilihan Ganda -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-question-circle"></i>
                                        Soal Pilihan Ganda
                                    </h3>
                    
                                <div id="questionsContainer">
                                    <!-- Questions will be added here dynamically -->
                                </div>
                                
                    <button type="button" id="addQuestionBtn" class="add-question-btn">
                        <i class="fas fa-plus"></i> Tambah Soal
                    </button>
                                </div>

                <!-- Tips -->
                <div class="tips">
                    <h4><i class="fas fa-lightbulb"></i> Tips Membuat Soal Pilihan Ganda</h4>
                    <ul>
                        <li>Gunakan pertanyaan yang jelas dan mudah dipahami</li>
                        <li>Buat pilihan jawaban yang logis dan tidak terlalu mudah ditebak</li>
                        <li>Hindari pilihan "Semua benar" atau "Semua salah"</li>
                        <li>Pastikan hanya ada satu jawaban yang benar</li>
                        <li>Gunakan bahasa yang sesuai dengan tingkat pendidikan siswa</li>
                    </ul>
                </div>

                        <!-- Action Buttons -->
                <div class="action-buttons">
                    <button type="button" class="btn btn-secondary" onclick="history.back()">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </button>
                    <button type="button" class="btn btn-danger" onclick="clearForm()">
                        <i class="fas fa-trash"></i> Hapus Semua
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Tugas
                                    </button>
                </div>
            </form>
        </div>
@endsection
