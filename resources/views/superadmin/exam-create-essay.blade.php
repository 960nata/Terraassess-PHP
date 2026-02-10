@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Buat Ujian Essay')

@push('styles')
<!-- Quill Editor CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush

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
            min-height: 120px;
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

        /* Question Section Styles */
        .question-section {
            background-color: #1e293b;
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #334155;
        }

        .add-question-btn {
            background: linear-gradient(45deg, #10b981, #059669);
            color: #ffffff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .add-question-btn:hover {
            background: linear-gradient(45deg, #059669, #047857);
            transform: translateY(-2px);
        }

        .question-item {
            background-color: #2a2a3e;
            border: 1px solid #475569;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .question-item:hover {
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
        }

        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #475569;
        }

        .question-header h4 {
            color: #ffffff;
            margin: 0;
            font-size: 1.1rem;
        }

        .remove-question-btn {
            background: #dc2626;
            color: #ffffff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .remove-question-btn:hover {
            background: #b91c1c;
        }

        .question-content {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .question-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        /* Rubric Section Styles */
        .rubric-section {
            background-color: #1e293b;
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #334155;
        }

        .add-rubric-btn {
            background: linear-gradient(45deg, #8b5cf6, #7c3aed);
            color: #ffffff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .add-rubric-btn:hover {
            background: linear-gradient(45deg, #7c3aed, #6d28d9);
            transform: translateY(-2px);
        }

        .rubric-item {
            background-color: #2a2a3e;
            border: 1px solid #475569;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .rubric-item:hover {
            border-color: #8b5cf6;
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.1);
        }

        .rubric-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #475569;
        }

        .rubric-header h4 {
            color: #ffffff;
            margin: 0;
            font-size: 1.1rem;
        }

        .remove-rubric-btn {
            background: #dc2626;
            color: #ffffff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .remove-rubric-btn:hover {
            background: #b91c1c;
        }

        .rubric-content {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .rubric-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        /* Quill Editor Styles */
        .question-editor {
            background: #2a2a3e;
            border: 2px solid #475569;
            border-radius: 8px;
            min-height: 150px;
        }

        .question-editor .ql-toolbar {
            background: #1e293b;
            border-bottom: 1px solid #475569;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .question-editor .ql-container {
            background: #2a2a3e;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            font-size: 14px;
        }

        .question-editor .ql-editor {
            color: #ffffff;
            min-height: 120px;
        }

        .question-editor .ql-editor.ql-blank::before {
            color: #94a3b8;
            font-style: normal;
        }

        .question-editor .ql-toolbar .ql-stroke {
            stroke: #ffffff;
        }

        .question-editor .ql-toolbar .ql-fill {
            fill: #ffffff;
        }

        .question-editor .ql-toolbar .ql-picker-label {
            color: #ffffff;
        }

        .question-editor .ql-toolbar button:hover .ql-stroke {
            stroke: #667eea;
        }

        .question-editor .ql-toolbar button:hover .ql-fill {
            fill: #667eea;
        }

        .question-editor .ql-toolbar button.ql-active .ql-stroke {
            stroke: #667eea;
        }

        .question-editor .ql-toolbar button.ql-active .ql-fill {
            fill: #667eea;
        }

        .question-editor .ql-toolbar .ql-picker-options {
            background: #1e293b;
            border: 1px solid #475569;
            border-radius: 6px;
        }

        .question-editor .ql-toolbar .ql-picker-item {
            color: #cbd5e1;
        }

        .question-editor .ql-toolbar .ql-picker-item:hover {
            color: #667eea;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .question-meta,
            .rubric-meta {
                grid-template-columns: 1fr;
            }
            
            .question-header,
            .rubric-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .question-editor {
                min-height: 120px;
            }

            .question-editor .ql-editor {
                min-height: 100px;
            }
            
            /* Mobile Quill toolbar optimization */
            .question-editor .ql-toolbar {
                padding: 8px;
            }
            
            .question-editor .ql-toolbar .ql-formats {
                margin-right: 8px;
            }
            
            .question-editor .ql-toolbar button {
                width: 24px;
                height: 24px;
                padding: 2px;
            }
            
            .question-editor .ql-toolbar .ql-formats:not(:last-child) {
                margin-right: 4px;
            }
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
            background: linear-gradient(45deg, #8b5cf6, #a855f7);
            color: #ffffff;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
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
            width: 100%;
            justify-content: center;
        }

        .add-question-btn:hover {
            background: #059669;
            transform: translateY(-2px);
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
            background: linear-gradient(45deg, #10b981, #059669);
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
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden border border-gray-700">
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                <h1 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-edit mr-3"></i>
                    Buat Ujian Essay
                </h1>
                <p class="text-purple-100 mt-1">Buat ujian dengan soal essay yang memerlukan penilaian manual</p>
                <div class="mt-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-500 text-white">
                        <i class="fas fa-tag mr-1"></i>
                        Tipe: Essay
                    </span>
                </div>
            </div>
            
            <div class="p-6 bg-gray-800">
                <!-- Exam Settings -->
                <div class="mb-6 bg-gray-700 rounded-lg p-6 border border-gray-600">
                    <h2 class="text-lg font-semibold text-white flex items-center mb-4">
                        <i class="fas fa-cog mr-2 text-purple-400"></i>Pengaturan Ujian
                    </h2>
            
            <form id="examForm" action="{{ route('superadmin.exam-management.create-essay.store') }}" method="POST">
                @csrf
                
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label for="exam_title" class="block text-sm font-medium text-gray-300 mb-2">
                                    Judul Ujian <span class="text-red-400">*</span>
                                </label>
                                <input type="text" id="exam_title" name="exam_title" 
                                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200" 
                                       placeholder="Masukkan judul ujian" value="{{ old('exam_title') }}" required>
                            </div>
                            
                            <div>
                                <label for="kelas_id" class="block text-sm font-medium text-gray-300 mb-2">
                                    Kelas <span class="text-red-400">*</span>
                                </label>
                                <select id="kelas_id" name="kelas_id" 
                                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200" 
                                        required>
                                    <option value="">Pilih kelas</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('kelas_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="mapel_id" class="block text-sm font-medium text-gray-300 mb-2">
                                    Mata Pelajaran <span class="text-red-400">*</span>
                                </label>
                                <select id="mapel_id" name="mapel_id" 
                                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200" 
                                        required>
                                    <option value="">Pilih mata pelajaran</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('mapel_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="duration" class="block text-sm font-medium text-gray-300 mb-2">
                                    Durasi (menit) <span class="text-red-400">*</span>
                                </label>
                                <input type="number" id="duration" name="duration" 
                                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200" 
                                       placeholder="120" min="1" max="300" value="{{ old('duration') }}" required>
                            </div>
                            
                            <div>
                                <label for="max_score" class="block text-sm font-medium text-gray-300 mb-2">
                                    Nilai Maksimal <span class="text-red-400">*</span>
                                </label>
                                <input type="number" id="max_score" name="max_score" 
                                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200" 
                                       placeholder="100" min="1" max="100" value="{{ old('max_score', 100) }}" required>
                            </div>
                            
                            <div>
                                <label for="difficulty" class="block text-sm font-medium text-gray-300 mb-2">
                                    Tingkat Kesulitan <span class="text-red-400">*</span>
                                </label>
                                <select id="difficulty" name="difficulty" 
                                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200" 
                                        required>
                                    <option value="">Pilih tingkat kesulitan</option>
                                     <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>Mudah</option>
                                    <option value="medium" {{ old('difficulty', 'medium') == 'medium' ? 'selected' : '' }}>Sedang</option>
                                    <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>Sulit</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="exam_description" class="block text-sm font-medium text-gray-300 mb-2">
                                Deskripsi Ujian <span class="text-red-400">*</span>
                            </label>
                            <textarea id="exam_description" name="exam_description" 
                                      class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200" 
                                      rows="3" placeholder="Masukkan deskripsi ujian yang detail" required>{{ old('exam_description') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-300 mb-2">
                                    Tanggal Mulai <span class="text-red-400">*</span>
                                </label>
                                <input type="datetime-local" id="due_date" name="due_date" 
                                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200" 
                                       value="{{ old('due_date') }}" required>
                            </div>
                            
                            <div>
                                <label for="is_hidden" class="block text-sm font-medium text-gray-300 mb-2">
                                    Status
                                </label>
                                <select id="is_hidden" name="is_hidden" 
                                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200">
                                    <option value="1" {{ old('is_hidden') == '1' ? 'selected' : '' }}>Draft</option>
                                    <option value="0" {{ old('is_hidden') == '0' ? 'selected' : '' }}>Publikasi</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Questions Section -->
                <div class="mb-6 bg-gray-700 rounded-lg p-6 border border-gray-600">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-white flex items-center">
                            <i class="fas fa-question-circle mr-2 text-purple-400"></i>Soal Ujian
                        </h2>
                        <button type="button" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-200 flex items-center text-sm" onclick="addQuestion()">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Soal
                        </button>
                    </div>
                    
                    <div id="questionsContainer">
                        <!-- Questions will be added here dynamically -->
                    </div>
                </div>

                <!-- Rubric Section -->
                <div class="mb-6 bg-gray-700 rounded-lg p-6 border border-gray-600">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-white flex items-center">
                            <i class="fas fa-list-check mr-2 text-purple-400"></i>Rubrik Penilaian
                        </h2>
                        <button type="button" class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition duration-200 flex items-center text-sm" onclick="addRubric()">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Kriteria Penilaian
                        </button>
                    </div>
                    
                    <div id="rubricContainer">
                        <!-- Rubric will be added here dynamically -->
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('superadmin.exam-management') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-200">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <button type="button" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-200" onclick="saveExam()">
                        <i class="fas fa-save mr-2"></i> Simpan Ujian
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let questionCount = 0;
    let rubricCount = 0;
    
    // Add question function
    window.addQuestion = function() {
        questionCount++;
        const questionsContainer = document.getElementById('questionsContainer');
        
        const questionHTML = `
            <div class="question-item" data-question="${questionCount}">
                <div class="question-header">
                    <h4>Soal ${questionCount}</h4>
                    ${questionCount > 1 ? `<button type="button" class="remove-question-btn" onclick="removeQuestion(${questionCount})">
                        <i class="fas fa-trash"></i> Hapus
                    </button>` : ''}
                </div>
                
                <div class="question-content">
                    <div class="form-group">
                        <label for="question_${questionCount}">Pertanyaan</label>
                        <div class="question-editor" id="question_editor_${questionCount}"></div>
                        <textarea id="question_${questionCount}" name="questions[${questionCount}][question]" 
                                  style="display: none;" required></textarea>
                    </div>
                    
                    <div class="question-meta">
                        <div class="form-group">
                            <label for="score_${questionCount}">Bobot Nilai</label>
                            <input type="number" id="score_${questionCount}" name="questions[${questionCount}][score]" 
                                   min="1" max="100" value="10" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="min_words_${questionCount}">Min. Kata</label>
                            <input type="number" id="min_words_${questionCount}" name="questions[${questionCount}][min_words]" 
                                   min="50" max="5000" value="200" required>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        questionsContainer.insertAdjacentHTML('beforeend', questionHTML);
        
        // Initialize Quill editor for the new question
        setTimeout(() => {
            initializeQuestionEditor(questionCount);
        }, 100);
    };
    
    // Remove question function
    window.removeQuestion = function(questionNum) {
        const question = document.querySelector(`[data-question="${questionNum}"]`);
        if (question) {
            question.remove();
            questionCount--;
            updateQuestionNumbers();
        }
    };
    
    // Update question numbers
    function updateQuestionNumbers() {
        const questions = document.querySelectorAll('.question-item');
        questions.forEach((question, index) => {
            const header = question.querySelector('.question-header h4');
            if (header) {
                header.textContent = `Soal ${index + 1}`;
            }
        });
    }
    
    // Add rubric function
    window.addRubric = function() {
        rubricCount++;
        const rubricContainer = document.getElementById('rubricContainer');
        
        const rubricHTML = `
            <div class="rubric-item" data-rubric="${rubricCount}">
                <div class="rubric-header">
                    <h4>Kriteria ${rubricCount}</h4>
                    ${rubricCount > 1 ? `<button type="button" class="remove-rubric-btn" onclick="removeRubric(${rubricCount})">
                        <i class="fas fa-trash"></i> Hapus
                    </button>` : ''}
                </div>
                
                <div class="rubric-content">
                    <div class="form-group">
                        <label for="rubric_name_${rubricCount}">Nama Kriteria</label>
                        <input type="text" id="rubric_name_${rubricCount}" name="rubrics[${rubricCount}][name]" 
                               placeholder="Contoh: Struktur Jawaban" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="rubric_description_${rubricCount}">Deskripsi</label>
                        <textarea id="rubric_description_${rubricCount}" name="rubrics[${rubricCount}][description]" 
                                  placeholder="Jelaskan kriteria penilaian..." required></textarea>
                    </div>
                    
                    <div class="rubric-meta">
                        <div class="form-group">
                            <label for="rubric_weight_${rubricCount}">Bobot (%)</label>
                            <input type="number" id="rubric_weight_${rubricCount}" name="rubrics[${rubricCount}][weight]" 
                                   min="1" max="100" value="25" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="rubric_max_score_${rubricCount}">Nilai Maksimal</label>
                            <input type="number" id="rubric_max_score_${rubricCount}" name="rubrics[${rubricCount}][max_score]" 
                                   min="1" max="100" value="25" required>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        rubricContainer.insertAdjacentHTML('beforeend', rubricHTML);
    };
    
    // Remove rubric function
    window.removeRubric = function(rubricNum) {
        const rubric = document.querySelector(`[data-rubric="${rubricNum}"]`);
        if (rubric) {
            rubric.remove();
            rubricCount--;
            updateRubricNumbers();
        }
    };
    
    // Update rubric numbers
    function updateRubricNumbers() {
        const rubrics = document.querySelectorAll('.rubric-item');
        rubrics.forEach((rubric, index) => {
            const header = rubric.querySelector('.rubric-header h4');
            if (header) {
                header.textContent = `Kriteria ${index + 1}`;
            }
        });
    }
    
    // Initialize Quill editor for question
    function initializeQuestionEditor(questionNum) {
        const editorId = `question_editor_${questionNum}`;
        const textareaId = `question_${questionNum}`;
        const editorElement = document.getElementById(editorId);
        const textareaElement = document.getElementById(textareaId);
        
        if (!editorElement || !textareaElement) return;
        
        try {
            // Initialize Quill editor
            const isMobile = window.innerWidth <= 768;
            const quill = new Quill('#' + editorId, {
                theme: 'snow',
                modules: {
                    toolbar: isMobile ? [
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'image'],
                        ['clean']
                    ] : [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'align': [] }],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        ['blockquote', 'code-block'],
                        ['link', 'image', 'video'],
                        ['clean']
                    ],
                    history: {
                        delay: 2000,
                        maxStack: 500,
                        userOnly: true
                    },
                    clipboard: {
                        matchVisual: false,
                    }
                },
                placeholder: 'Masukkan pertanyaan essay...'
            });
            
            // Apply dark theme
            setTimeout(() => {
                const toolbar = document.querySelector(`#${editorId} .ql-toolbar`);
                const container = document.querySelector(`#${editorId} .ql-container`);
                if (toolbar) {
                    toolbar.style.background = '#1e293b';
                    toolbar.style.borderColor = '#333';
                }
                if (container) {
                    container.style.background = '#2a2a3e';
                    container.style.borderColor = '#333';
                }
            }, 100);
            
            // Add image upload handler
            const toolbar = quill.getModule('toolbar');
            toolbar.addHandler('image', function() {
                selectLocalImage(quill);
            });
            
            // Update textarea on content change
            quill.on('text-change', function() {
                const html = quill.root.innerHTML;
                textareaElement.value = html;
            });
            
            // Store quill instance for later use
            editorElement.quillInstance = quill;
            
        } catch (error) {
            console.error(`Error initializing Quill editor ${questionNum}:`, error);
            // Show textarea as fallback
            if (textareaElement) {
                textareaElement.style.display = 'block';
                textareaElement.placeholder = 'Masukkan pertanyaan...';
                textareaElement.rows = 4;
            }
            if (editorElement) {
                editorElement.style.display = 'none';
            }
        }
    }
    
    // Image upload handler
    function selectLocalImage(quill) {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();
        
        input.onchange = function() {
            const file = input.files[0];
            if (file) {
                // Check file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 5MB.');
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function() {
                    const range = quill.getSelection();
                    quill.insertEmbed(range.index, 'image', reader.result);
                };
                reader.readAsDataURL(file);
            }
        };
    }
    
    // Save exam function
    window.saveExam = function() {
        const form = document.getElementById('examForm');
        const formData = new FormData(form);
        
        // Add questions data
        const questions = document.querySelectorAll('.question-item');
        questions.forEach((question, index) => {
            const editorElement = question.querySelector('.question-editor');
            const textareaElement = question.querySelector(`textarea[name*="[question]"]`);
            const score = question.querySelector(`input[name*="[score]"]`).value;
            const minWords = question.querySelector(`input[name*="[min_words]"]`).value;
            
            let questionText = '';
            if (editorElement && editorElement.quillInstance) {
                questionText = editorElement.quillInstance.root.innerHTML;
            } else if (textareaElement) {
                questionText = textareaElement.value;
            }
            
            if (questionText.trim()) {
                formData.append(`questions[${index + 1}][question]`, questionText);
                formData.append(`questions[${index + 1}][score]`, score);
                formData.append(`questions[${index + 1}][min_words]`, minWords);
            }
        });
        
        // Add rubrics data
        const rubrics = document.querySelectorAll('.rubric-item');
        rubrics.forEach((rubric, index) => {
            const name = rubric.querySelector(`input[name*="[name]"]`).value;
            const description = rubric.querySelector(`textarea[name*="[description]"]`).value;
            const weight = rubric.querySelector(`input[name*="[weight]"]`).value;
            const maxScore = rubric.querySelector(`input[name*="[max_score]"]`).value;
            
            if (name.trim()) {
                formData.append(`rubrics[${index + 1}][name]`, name);
                formData.append(`rubrics[${index + 1}][description]`, description);
                formData.append(`rubrics[${index + 1}][weight]`, weight);
                formData.append(`rubrics[${index + 1}][max_score]`, maxScore);
            }
        });
        
        // Submit form
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Ujian essay berhasil disimpan!');
                window.location.href = data.redirect || '{{ route("superadmin.exam-management") }}';
            } else {
                alert('Gagal menyimpan ujian: ' + (data.message || 'Terjadi kesalahan'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan ujian');
        });
    };
    
    // Initialize first question and rubric
    addQuestion();
    addRubric();
});

// Restore questions dan rubrics dari old input jika ada
@if(old('questions'))
    document.addEventListener('DOMContentLoaded', function() {
        const oldQuestions = @json(old('questions'));
        console.log('Restoring old essay questions:', oldQuestions);
        
        oldQuestions.forEach((question, index) => {
            if (index > 0) addQuestion(); // Skip first question as it's already added
            
            // Fill data ke form
            const questionId = index;
            
            // Fill question text
            const questionInput = document.querySelector(`#question-${questionId} input[name="questions[${questionId}][question]"]`);
            if (questionInput) {
                questionInput.value = question.question || '';
            }
            
            // Fill score
            const scoreInput = document.querySelector(`#question-${questionId} input[name="questions[${questionId}][score]"]`);
            if (scoreInput) {
                scoreInput.value = question.score || 1;
            }
            
            // Fill min_words
            const minWordsInput = document.querySelector(`#question-${questionId} input[name="questions[${questionId}][min_words]"]`);
            if (minWordsInput) {
                minWordsInput.value = question.min_words || 50;
            }
        });
    });
@endif

@if(old('rubrics'))
    document.addEventListener('DOMContentLoaded', function() {
        const oldRubrics = @json(old('rubrics'));
        console.log('Restoring old rubrics:', oldRubrics);
        
        oldRubrics.forEach((rubric, index) => {
            if (index > 0) addRubric(); // Skip first rubric as it's already added
            
            // Fill rubric data
            const rubricId = index;
            
            // Fill name
            const nameInput = document.querySelector(`#rubric_name_${rubricId}`);
            if (nameInput) {
                nameInput.value = rubric.name || '';
            }
            
            // Fill description
            const descInput = document.querySelector(`#rubric_description_${rubricId}`);
            if (descInput) {
                descInput.value = rubric.description || '';
            }
            
            // Fill weight
            const weightInput = document.querySelector(`input[name="rubrics[${rubricId}][weight]"]`);
            if (weightInput) {
                weightInput.value = rubric.weight || 1;
            }
            
            // Fill max_score
            const maxScoreInput = document.querySelector(`input[name="rubrics[${rubricId}][max_score]"]`);
            if (maxScoreInput) {
                maxScoreInput.value = rubric.max_score || 10;
            }
        });
    });
@endif

// Show notification that data was restored
@if(session('error') && (old('questions') || old('rubrics')))
    document.addEventListener('DOMContentLoaded', function() {
        showNotification('Data Anda tersimpan! Silakan perbaiki error dan submit kembali. Data yang sudah Anda isi tidak hilang.', 'warning');
    });
@endif
</script>

@push('scripts')
<!-- Quill Editor JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
@endpush

<!-- Quill Editor CSS -->
<style>
/* Quill Editor Styles - From material-create.blade.php */
.ql-editor {
    color: #ffffff;
    background: #2a2a3e;
    min-height: 200px;
}

.ql-toolbar {
    background: #1e293b;
    border: 1px solid #334155;
    border-bottom: none;
}

.ql-container {
    border: 1px solid #334155;
    border-top: none;
}

.ql-snow .ql-picker {
    color: #ffffff;
}

.ql-snow .ql-stroke {
    stroke: #ffffff;
}

.ql-snow .ql-fill {
    fill: #ffffff;
}

.quill-editor-dark .ql-editor {
    color: #ffffff;
    background: #2a2a3e;
    min-height: 120px;
}

.quill-editor-dark .ql-toolbar {
    background: #1e293b;
    border: 1px solid #334155;
    border-bottom: none;
}

.quill-editor-dark .ql-container {
    border: 1px solid #334155;
    border-top: none;
}

.quill-editor-dark .ql-snow .ql-picker {
    color: #ffffff;
}

.quill-editor-dark .ql-snow .ql-stroke {
    stroke: #ffffff;
}

.quill-editor-dark .ql-snow .ql-fill {
    fill: #ffffff;
}
</style>
@endsection
