@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - Buat Ujian Campuran')

@section('styles')
<style>
.exam-form {
            background-color: #1e293b;
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #334155;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #ffffff;
            font-size: 0.9rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #2a2a3e;
            border: 2px solid #333;
            border-radius: 8px;
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            background: #333;
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-group textarea::placeholder {
            color: #666;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: #ffffff;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #334155;
            color: #ffffff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-secondary:hover {
            background: #475569;
        }

        .question-section {
            background-color: #1e293b;
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #334155;
        }

        .question-item {
            background-color: #2a2a3e;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #334155;
        }

        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .question-number {
            background: linear-gradient(45deg, #10b981, #059669);
            color: #ffffff;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .question-type {
            background: #3b82f6;
            color: #ffffff;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .question-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-danger {
            background: #ef4444;
            color: #ffffff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .add-question-section {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .add-question-btn {
            background: #10b981;
            color: #ffffff;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
            justify-content: center;
        }

        .add-question-btn:hover {
            background: #059669;
            transform: translateY(-2px);
        }

        .add-question-btn.essay {
            background: #8b5cf6;
        }

        .add-question-btn.essay:hover {
            background: #7c3aed;
        }

        .exam-settings {
            background-color: #1e293b;
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #334155;
        }

        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .option-group {
            margin-bottom: 1rem;
        }

        .option-input {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .option-input input[type="radio"] {
            width: auto;
            margin: 0;
        }

        .option-input input[type="text"] {
            flex: 1;
        }

        .rubric-section {
            background-color: #1e293b;
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #334155;
        }

        .rubric-item {
            background-color: #2a2a3e;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid #334155;
        }

        .rubric-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .rubric-level {
            background: linear-gradient(45deg, #f59e0b, #d97706);
            color: #ffffff;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .add-rubric-btn {
            background: #f59e0b;
            color: #ffffff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .add-rubric-btn:hover {
            background: #d97706;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .settings-grid {
                grid-template-columns: 1fr;
            }
            
            .question-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            
            .add-question-section {
                flex-direction: column;
            }
        }

        /* SIMPLE MOBILE SIDEBAR - GUARANTEED TO WORK */
        @media (max-width: 1024px) {
            .sidebar {
                position: fixed !important;
                top: 70px !important;
                left: 0 !important;
                height: calc(100vh - 70px) !important;
                width: 280px !important;
                z-index: 999 !important;
                transform: translateX(-100%) !important;
                transition: transform 0.3s ease !important;
                background: #1e293b !important;
            }
            
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
            }
            
            .mobile-overlay {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                background: rgba(0, 0, 0, 0.5) !important;
                z-index: 998 !important;
                display: none !important;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 280px !important;
                z-index: 999 !important;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 100% !important;
            }
        }
</style>
@endsection

@section('content')
<div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-layer-group"></i>
                Buat Ujian Campuran
            </h1>
            <p class="page-description">Buat ujian dengan kombinasi soal pilihan ganda dan essay</p>
        </div>

        <!-- Exam Settings -->
        <div class="exam-settings">
            <h2 style="color: #ffffff; margin-bottom: 1.5rem; font-size: 1.25rem;">
                <i class="fas fa-cog me-2"></i>Pengaturan Ujian
            </h2>
            
            <form id="examForm" action="{{ route('superadmin.exam-management.create-mixed.store') }}" method="POST">
                @csrf
                
                <div class="settings-grid">
                    <div class="form-group">
                        <label for="exam_title">Judul Ujian</label>
                        <input type="text" id="exam_title" name="exam_title" placeholder="Masukkan judul ujian" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="class_id">Kelas</label>
                        <select id="class_id" name="class_id" required>
                            <option value="">Pilih kelas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject_id">Mata Pelajaran</label>
                        <select id="subject_id" name="subject_id" required>
                            <option value="">Pilih mata pelajaran</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="duration">Durasi (menit)</label>
                        <input type="number" id="duration" name="duration" placeholder="150" min="1" max="300" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="max_score">Nilai Maksimal</label>
                        <input type="number" id="max_score" name="max_score" placeholder="100" min="1" max="100" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="difficulty">Tingkat Kesulitan</label>
                        <select id="difficulty" name="difficulty" required>
                            <option value="">Pilih tingkat kesulitan</option>
                            <option value="easy">Mudah</option>
                            <option value="medium">Sedang</option>
                            <option value="hard">Sulit</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="exam_description">Deskripsi Ujian</label>
                    <textarea id="exam_description" name="exam_description" placeholder="Masukkan deskripsi ujian yang detail" required></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="due_date">Tanggal Mulai</label>
                        <input type="datetime-local" id="due_date" name="due_date" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="is_hidden">Status</label>
                        <select id="is_hidden" name="is_hidden">
                            <option value="1">Draft</option>
                            <option value="0">Publikasi</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- Questions Section -->
        <div class="question-section">
            <h2 style="color: #ffffff; margin-bottom: 1.5rem; font-size: 1.25rem;">
                <i class="fas fa-question-circle me-2"></i>Soal Ujian
            </h2>
            
            <div class="add-question-section">
                <button type="button" class="add-question-btn" onclick="addMultipleChoiceQuestion()">
                    <i class="fas fa-list-ul"></i>
                    Tambah Pilihan Ganda
                </button>
                <button type="button" class="add-question-btn essay" onclick="addEssayQuestion()">
                    <i class="fas fa-edit"></i>
                    Tambah Essay
                </button>
            </div>
            
            <div id="questionsContainer">
                <!-- Questions will be added here dynamically -->
            </div>
        </div>

        <!-- Rubric Section -->
        <div class="rubric-section">
            <h2 style="color: #ffffff; margin-bottom: 1.5rem; font-size: 1.25rem;">
                <i class="fas fa-list-check me-2"></i>Rubrik Penilaian (Untuk Soal Essay)
            </h2>
            
            <div id="rubricContainer">
                <!-- Rubric will be added here dynamically -->
            </div>
            
            <button type="button" class="add-rubric-btn" onclick="addRubric()">
                <i class="fas fa-plus"></i>
                Tambah Kriteria Penilaian
            </button>
        </div>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="button" class="btn-primary" onclick="saveExam()">
                <i class="fas fa-save"></i>
                Simpan Ujian
            </button>
            <a href="{{ route('superadmin.exam-management') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>
@endsection
