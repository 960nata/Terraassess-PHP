@extends('layouts.unified-layout')

@section('title', $title)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $title }}</h1>
                <p class="mt-2 text-gray-300">Edit tugas {{ strtolower($tipeTugas) }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('teacher.tasks.show', $tugas->id) }}" class="btn btn-outline">
                    <i class="ph-arrow-left mr-2"></i>
                    Kembali ke Detail
                </a>
            </div>
        </div>
    </div>

    <form id="taskForm" method="POST" action="{{ route('teacher.tasks.update', $tugas->id) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2">
                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Dasar</h3>
                        </div>
                        <div class="card-body space-y-4">
                            <div>
                                <label class="form-label">Judul Tugas *</label>
                                <input type="text" name="name" class="form-input" 
                                       value="{{ old('name', $tugas->name) }}" required>
                                @error('name')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Deskripsi/Instruksi *</label>
                                <textarea name="content" class="form-textarea" rows="4" 
                                          placeholder="Tuliskan instruksi yang jelas untuk siswa..." required>{{ old('content', $tugas->content) }}</textarea>
                                @error('content')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- DEBUG INFO --}}
                            <div style="background: #f0f0f0; padding: 10px; margin: 10px 0; border-radius: 5px;">
                                <strong>DEBUG INFO:</strong><br>
                                Kelas Mapel ID: {{ $tugas->kelas_mapel_id }}<br>
                                Kelas ID: {{ $tugas->KelasMapel->kelas_id ?? 'NULL' }}<br>
                                Mapel ID: {{ $tugas->KelasMapel->mapel_id ?? 'NULL' }}<br>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label">Kelas Tujuan *</label>
                                    <select name="kelas_id" class="form-select" required>
                                        <option value="">Pilih Kelas</option>
                                        @foreach($kelas as $k)
                                            <option value="{{ $k->id }}" 
                                                    {{ (old('kelas_id') ?? ($tugas->KelasMapel->kelas_id ?? null)) == $k->id ? 'selected' : '' }}>
                                                {{ $k->name }} - {{ $k->level }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kelas_id')
                                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="form-label">Mata Pelajaran *</label>
                                    <select name="mapel_id" class="form-select" required>
                                        <option value="">Pilih Mata Pelajaran</option>
                                        @foreach($mapel as $m)
                                            <option value="{{ $m->id }}" 
                                                    {{ (old('mapel_id') ?? ($tugas->KelasMapel->mapel_id ?? null)) == $m->id ? 'selected' : '' }}>
                                                {{ $m->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('mapel_id')
                                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Tanggal Tenggat</label>
                                <input type="datetime-local" name="due" class="form-input" 
                                       value="{{ old('due', $tugas->due ? $tugas->due->format('Y-m-d\TH:i') : '') }}">
                                @error('due')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Task Type Specific Content -->
                    @if($tipe == 1)
                        <!-- Multiple Choice Questions -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Soal Pilihan Ganda</h3>
                                <p class="text-sm text-gray-400">Edit soal yang sudah ada atau tambah soal baru</p>
                            </div>
                            <div class="card-body">
                                <div id="questionsContainer">
                                    @foreach($tugas->TugasMultiple as $index => $question)
                                        <div class="question-block" id="question-{{ $index + 1 }}">
                                            <div class="flex justify-between items-center mb-4">
                                                <h4 class="text-lg font-medium text-white">Soal {{ $index + 1 }}</h4>
                                                <button type="button" onclick="removeQuestion({{ $index + 1 }})" class="text-red-400 hover:text-red-300">
                                                    <i class="ph-trash"></i>
                                                </button>
                                            </div>
                                            
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="form-label">Pertanyaan</label>
                                                    <div class="border border-gray-600 rounded-lg p-4 bg-gray-900 mb-2">
                                                        <div class="text-white">{!! $question->soal !!}</div>
                                                    </div>
                                                    <div id="quill-editor-question-{{ $index + 1 }}" class="quill-editor-dark" style="height: 120px;"></div>
                                                    <textarea name="questions[{{ $index + 1 }}][question]" id="quill-textarea-question-{{ $index + 1 }}" style="display: none;" required>{{ $question->soal }}</textarea>
                                                </div>
                                                
                                                <div>
                                                    <label class="form-label">Pilihan Jawaban</label>
                                                    <div id="options-{{ $index + 1 }}">
                                                        @php
                                                            $options = [
                                                                'a' => $question->a,
                                                                'b' => $question->b,
                                                                'c' => $question->c,
                                                                'd' => $question->d,
                                                                'e' => $question->e
                                                            ];
                                                            $correctAnswer = $question->jawaban;
                                                        @endphp
                                                        @foreach($options as $key => $value)
                                                            @if($value)
                                                                <div class="flex items-center space-x-2 mb-2">
                                                                    <input type="radio" name="questions[{{ $index + 1 }}][correct_answer]" 
                                                                           value="{{ $key }}" {{ $correctAnswer == $key ? 'checked' : '' }} required>
                                                                    <div class="flex-1">
                                                                        <div id="quill-editor-option-{{ strtoupper($key) }}-{{ $index + 1 }}" class="quill-editor-dark" style="height: 60px;"></div>
                                                                        <textarea name="questions[{{ $index + 1 }}][options][{{ $key }}]" 
                                                                                  id="quill-textarea-option-{{ strtoupper($key) }}-{{ $index + 1 }}" 
                                                                                  style="display: none;" required>{{ $value }}</textarea>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    <button type="button" onclick="addOption({{ $index + 1 }})" class="btn btn-outline btn-sm mt-2">
                                                        <i class="ph-plus mr-1"></i>
                                                        Tambah Pilihan
                                                    </button>
                                                </div>
                                                
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="form-label">Poin</label>
                                                        <input type="number" name="questions[{{ $index + 1 }}][points]" 
                                                               class="form-input" value="{{ $question->poin }}" min="1" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <button type="button" onclick="addQuestion()" class="btn btn-primary btn-sm mt-4">
                                    <i class="ph-plus mr-1"></i>
                                    Tambah Soal
                                </button>
                            </div>
                        </div>
                    @elseif($tipe == 2 || $tipe == 3)
                        <!-- Essay Questions -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Soal {{ $tipe == 2 ? 'Essay' : 'Mandiri' }}</h3>
                                <p class="text-sm text-gray-400">Edit soal yang sudah ada atau tambah soal baru</p>
                            </div>
                            <div class="card-body">
                                @if($tugas->TugasMandiri->count() == 0)
                                    <div class="alert alert-info mb-4" style="background: #3b82f6; color: white; padding: 1rem; border-radius: 8px;">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Belum ada soal untuk tugas ini. Tugas ini mungkin dibuat sebelum fitur soal essay ditambahkan.
                                        Silakan tambah soal baru dengan klik tombol "Tambah Soal" di bawah.
                                    </div>
                                @endif
                                <div id="essayQuestionsContainer">
                                    @foreach($tugas->TugasMandiri as $index => $question)
                                        <div class="question-block" id="essay-question-{{ $index + 1 }}">
                                            <div class="flex justify-between items-center mb-4">
                                                <h4 class="text-lg font-medium text-white">Soal {{ $index + 1 }}</h4>
                                                <button type="button" onclick="removeEssayQuestion({{ $index + 1 }})" class="text-red-400 hover:text-red-300">
                                                    <i class="ph-trash"></i>
                                                </button>
                                            </div>
                                            
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="form-label">Pertanyaan</label>
                                                    <div class="border border-gray-600 rounded-lg p-4 bg-gray-900 mb-2">
                                                        <div class="text-white">{!! $question->pertanyaan !!}</div>
                                                    </div>
                                                    <div id="quill-editor-essay-{{ $index + 1 }}" class="quill-editor-dark" style="height: 150px;"></div>
                                                    <textarea name="essay_questions[{{ $index + 1 }}][question]" id="quill-textarea-essay-{{ $index + 1 }}" style="display: none;" required>{{ $question->pertanyaan }}</textarea>
                                                </div>
                                                
                                                <div>
                                                    <label class="form-label">Poin</label>
                                                    <input type="number" name="essay_questions[{{ $index + 1 }}][points]" 
                                                           class="form-input" value="{{ $question->poin }}" min="1" max="100" required style="max-width: 150px;">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <button type="button" onclick="addEssayQuestion()" class="btn btn-primary btn-sm mt-4">
                                    <i class="ph-plus mr-1"></i>
                                    Tambah Soal
                                </button>
                            </div>
                        </div>
                    
                        <!-- Essay/Individual Task Options -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Opsi Pengumpulan</h3>
                            </div>
                            <div class="card-body space-y-4">
                                @php
                                    $taskConfig = json_decode($tugas->content, true);
                                    if (!$taskConfig) {
                                        $taskConfig = [];
                                    }
                                @endphp
                                
                                <div class="flex items-center space-x-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="allow_text_input" value="1" 
                                               {{ old('allow_text_input', $taskConfig['allow_text_input'] ?? true) ? 'checked' : '' }} 
                                               class="form-checkbox">
                                        <span class="ml-2 text-white">Izinkan ketik langsung</span>
                                    </label>
                                </div>

                                <div class="flex items-center space-x-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="allow_file_upload" value="1" 
                                               {{ old('allow_file_upload', $taskConfig['allow_file_upload'] ?? false) ? 'checked' : '' }} 
                                               class="form-checkbox">
                                        <span class="ml-2 text-white">Izinkan upload file</span>
                                    </label>
                                </div>

                                <div id="fileTypesContainer" class="{{ old('allow_file_upload', $taskConfig['allow_file_upload'] ?? false) ? '' : 'hidden' }}">
                                    <label class="form-label">Jenis File yang Diizinkan</label>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(['pdf', 'docx', 'jpg', 'png', 'txt'] as $type)
                                            <label class="flex items-center">
                                                <input type="checkbox" name="file_types[]" value="{{ $type }}" 
                                                       {{ in_array($type, old('file_types', $taskConfig['file_types'] ?? ['pdf', 'docx', 'jpg', 'png'])) ? 'checked' : '' }}
                                                       class="form-checkbox">
                                                <span class="ml-1 text-white">.{{ $type }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($tipe == 4)
                        <!-- Group Task Configuration -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Konfigurasi Tugas Kelompok</h3>
                                <p class="text-sm text-gray-400">Edit pengaturan tugas kelompok</p>
                            </div>
                            <div class="card-body space-y-4">
                                @php
                                    $taskConfig = json_decode($tugas->content, true);
                                    if (!$taskConfig) {
                                        $taskConfig = [];
                                    }
                                @endphp
                                
                                <div>
                                    <label class="form-label">Tanggal Tenggat Penilaian Antar Kelompok</label>
                                    <input type="datetime-local" name="peer_assessment_due" 
                                           class="form-input" 
                                           value="{{ old('peer_assessment_due', $taskConfig['peer_assessment_due'] ?? '') }}">
                                </div>
                                
                                <div>
                                    <h4 class="text-lg font-medium text-white mb-3">Kelompok yang Ada</h4>
                                    <div class="space-y-3">
                                        @foreach($tugas->TugasKelompok as $group)
                                            <div class="bg-gray-800 rounded-lg p-4">
                                                <div class="flex justify-between items-center mb-2">
                                                    <h5 class="text-white font-medium">{{ $group->name }}</h5>
                                                    <span class="text-sm text-gray-400">
                                                        {{ $group->AnggotaTugasKelompok->count() }} anggota
                                                    </span>
                                                </div>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($group->AnggotaTugasKelompok as $member)
                                                        <span class="px-2 py-1 bg-gray-700 rounded text-sm text-white">
                                                            {{ $member->User->name }}
                                                            @if($member->is_leader)
                                                                <i class="ph-crown text-yellow-400 ml-1"></i>
                                                            @endif
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <div>
                                    <h4 class="text-lg font-medium text-white mb-3">Rubrik Penilaian</h4>
                                    <div id="rubricContainer">
                                        @if(isset($taskConfig['rubric_items']))
                                            @foreach($taskConfig['rubric_items'] as $index => $item)
                                                <div class="rubric-item" id="rubric-{{ $index + 1 }}">
                                                    <div class="flex justify-between items-center mb-4">
                                                        <h4 class="text-lg font-medium text-white">Item Penilaian {{ $index + 1 }}</h4>
                                                        <button type="button" onclick="removeRubricItem({{ $index + 1 }})" class="text-red-400 hover:text-red-300">
                                                            <i class="ph-trash"></i>
                                                        </button>
                                                    </div>
                                                    
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label class="form-label">Item Penilaian</label>
                                                            <input type="text" name="rubric_items[{{ $index + 1 }}][item]" 
                                                                   class="form-input" value="{{ $item['item'] }}" required>
                                                        </div>
                                                        
                                                        <div>
                                                            <label class="form-label">Tipe Jawaban</label>
                                                            <select name="rubric_items[{{ $index + 1 }}][type]" 
                                                                    class="form-select" onchange="updateRubricType({{ $index + 1 }})" required>
                                                                <option value="yes_no" {{ $item['type'] == 'yes_no' ? 'selected' : '' }}>Ya/Tidak</option>
                                                                <option value="scale" {{ $item['type'] == 'scale' ? 'selected' : '' }}>Skala (Sangat Baik, Baik, Cukup, Kurang)</option>
                                                                <option value="text" {{ $item['type'] == 'text' ? 'selected' : '' }}>Teks Bebas</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <div>
                                                            <label class="form-label">Poin Maksimal</label>
                                                            <input type="number" name="rubric_items[{{ $index + 1 }}][points]" 
                                                                   class="form-input" value="{{ $item['points'] }}" min="0" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" onclick="addRubricItem()" class="btn btn-primary btn-sm mt-4">
                                        <i class="ph-plus mr-1"></i>
                                        Tambah Item Penilaian
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="card sticky top-6">
                    <div class="card-header">
                        <h3 class="card-title">Aksi</h3>
                    </div>
                    <div class="card-body space-y-4">
                        <button type="submit" class="btn btn-primary w-full">
                            <i class="ph-check mr-2"></i>
                            Simpan Perubahan
                        </button>
                        
                        <a href="{{ route('teacher.tasks.show', $tugas->id) }}" class="btn btn-outline w-full">
                            <i class="ph-x mr-2"></i>
                            Batal
                        </a>

                        <div class="border-t border-gray-600 pt-4">
                            <h4 class="text-sm font-medium text-gray-300 mb-2">Informasi:</h4>
                            <ul class="text-xs text-gray-400 space-y-1">
                                <li>• Tugas dibuat: {{ $tugas->created_at->format('d M Y, H:i') }}</li>
                                <li>• Terakhir diupdate: {{ $tugas->updated_at->format('d M Y, H:i') }}</li>
                                <li>• Status: {{ $tugas->status_tugas }}</li>
                                @if($tugas->due)
                                    <li>• Tenggat: {{ $tugas->due->format('d M Y, H:i') }}</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
/* Same styles as task-create.blade.php */
.card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(255, 255, 255, 0.02);
}

.card-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: white;
    margin: 0;
}

.card-body {
    padding: 1.5rem;
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: white;
    margin-bottom: 0.5rem;
}

.form-input, .form-select, .form-textarea {
    width: 100%;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    color: white;
    font-size: 0.875rem;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-checkbox {
    width: 1rem;
    height: 1rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 4px;
}

.question-block, .rubric-item {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

/* Quill Editor Styles */
.ql-editor {
    color: #ffffff;
    background: #2a2a3e;
    min-height: 120px;
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
    min-height: 60px;
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
</style>

<script>
let questionCount = {{ $tugas->TugasMultiple->count() }};
let rubricCount = {{ isset($taskConfig['rubric_items']) ? count($taskConfig['rubric_items']) : 0 }};
let quillEditors = {};

document.addEventListener('DOMContentLoaded', function() {
    // Pre-select dropdown values from database
    const kelasSelect = document.querySelector('select[name="kelas_id"]');
    const mapelSelect = document.querySelector('select[name="mapel_id"]');
    
    // Set kelas value from database
    const kelasId = '{{ $tugas->KelasMapel->kelas_id ?? "" }}';
    if (kelasId && kelasSelect) {
        kelasSelect.value = kelasId;
        console.log('Set kelas_id to:', kelasId);
    }
    
    // Set mapel value from database
    const mapelId = '{{ $tugas->KelasMapel->mapel_id ?? "" }}';
    if (mapelId && mapelSelect) {
        mapelSelect.value = mapelId;
        console.log('Set mapel_id to:', mapelId);
    }

    // Handle file upload checkbox
    const fileUploadCheckbox = document.querySelector('input[name="allow_file_upload"]');
    const fileTypesContainer = document.getElementById('fileTypesContainer');
    
    if (fileUploadCheckbox) {
        fileUploadCheckbox.addEventListener('change', function() {
            if (this.checked) {
                fileTypesContainer.classList.remove('hidden');
            } else {
                fileTypesContainer.classList.add('hidden');
            }
        });
    }
    
    // Initialize existing Quill editors
    initializeExistingQuillEditors();
});

// Initialize Quill editors for existing questions
function initializeExistingQuillEditors() {
    @if($tipe == 1)
        @foreach($tugas->TugasMultiple as $index => $question)
            initializeQuillEditors({{ $index + 1 }});
        @endforeach
    @elseif($tipe == 2 || $tipe == 3)
        @foreach($tugas->TugasMandiri as $index => $question)
            initializeEssayQuillEditor({{ $index + 1 }});
        @endforeach
    @endif
}

// Initialize Quill editors for a question
function initializeQuillEditors(questionNum) {
    // Question editor
    const questionEditorId = `quill-editor-question-${questionNum}`;
    const questionTextareaId = `quill-textarea-question-${questionNum}`;
    
    if (document.getElementById(questionEditorId)) {
        const isMobile = window.innerWidth <= 768;
        const questionEditor = new Quill(`#${questionEditorId}`, {
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
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'align': [] }],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            },
            placeholder: 'Tuliskan pertanyaan di sini...'
        });
        
        quillEditors[questionEditorId] = questionEditor;
        
        // Set initial content
        const textarea = document.getElementById(questionTextareaId);
        if (textarea && textarea.value) {
            questionEditor.root.innerHTML = textarea.value;
        }
        
        // Update textarea when editor content changes
        questionEditor.on('text-change', function() {
            textarea.value = questionEditor.root.innerHTML;
        });
        
        // Custom image handler
        questionEditor.getModule('toolbar').addHandler('image', async function() {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();
            
            input.onchange = async function() {
                const file = input.files[0];
                if (file) {
                    // Compress and insert image
                    const compressedFile = await compressImage(file);
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const range = questionEditor.getSelection();
                        questionEditor.insertEmbed(range.index, 'image', e.target.result);
                    };
                    reader.readAsDataURL(compressedFile);
                }
            };
        });
    }
    
    // Option editors
    ['A', 'B', 'C', 'D', 'E'].forEach(option => {
        const optionEditorId = `quill-editor-option-${option}-${questionNum}`;
        const optionTextareaId = `quill-textarea-option-${option}-${questionNum}`;
        
        if (document.getElementById(optionEditorId)) {
            const optionEditor = new Quill(`#${optionEditorId}`, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        ['link'],
                        ['clean']
                    ]
                },
                placeholder: `Pilihan ${option}...`
            });
            
            quillEditors[optionEditorId] = optionEditor;
            
            // Set initial content
            const textarea = document.getElementById(optionTextareaId);
            if (textarea && textarea.value) {
                optionEditor.root.innerHTML = textarea.value;
            }
            
            // Update textarea when editor content changes
            optionEditor.on('text-change', function() {
                textarea.value = optionEditor.root.innerHTML;
            });
        }
    });
}

// Image compression function
async function compressImage(file) {
    return new Promise((resolve) => {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();
        
        img.onload = function() {
            const maxWidth = 800;
            const maxHeight = 600;
            let { width, height } = img;
            
            if (width > height) {
                if (width > maxWidth) {
                    height = (height * maxWidth) / width;
                    width = maxWidth;
                }
            } else {
                if (height > maxHeight) {
                    width = (width * maxHeight) / height;
                    height = maxHeight;
                }
            }
            
            canvas.width = width;
            canvas.height = height;
            
            ctx.drawImage(img, 0, 0, width, height);
            canvas.toBlob(resolve, 'image/jpeg', 0.8);
        };
        
        img.src = URL.createObjectURL(file);
    });
}

// Multiple Choice Functions
function addQuestion() {
    questionCount++;
    const container = document.getElementById('questionsContainer');
    
    const questionHTML = `
        <div class="question-block" id="question-${questionCount}">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-medium text-white">Soal ${questionCount}</h4>
                <button type="button" onclick="removeQuestion(${questionCount})" class="text-red-400 hover:text-red-300">
                    <i class="ph-trash"></i>
                </button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="form-label">Pertanyaan</label>
                    <div id="quill-editor-question-${questionCount}" class="quill-editor-dark" style="height: 120px;"></div>
                    <textarea name="questions[${questionCount}][question]" id="quill-textarea-question-${questionCount}" style="display: none;" required></textarea>
                </div>
                
                <div>
                    <label class="form-label">Pilihan Jawaban</label>
                    <div id="options-${questionCount}">
                        <div class="flex items-center space-x-2 mb-2">
                            <input type="radio" name="questions[${questionCount}][correct_answer]" value="a" required>
                            <div class="flex-1">
                                <div id="quill-editor-option-A-${questionCount}" class="quill-editor-dark" style="height: 60px;"></div>
                                <textarea name="questions[${questionCount}][options][a]" id="quill-textarea-option-A-${questionCount}" style="display: none;" required></textarea>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 mb-2">
                            <input type="radio" name="questions[${questionCount}][correct_answer]" value="b" required>
                            <div class="flex-1">
                                <div id="quill-editor-option-B-${questionCount}" class="quill-editor-dark" style="height: 60px;"></div>
                                <textarea name="questions[${questionCount}][options][b]" id="quill-textarea-option-B-${questionCount}" style="display: none;" required></textarea>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 mb-2">
                            <input type="radio" name="questions[${questionCount}][correct_answer]" value="c" required>
                            <div class="flex-1">
                                <div id="quill-editor-option-C-${questionCount}" class="quill-editor-dark" style="height: 60px;"></div>
                                <textarea name="questions[${questionCount}][options][c]" id="quill-textarea-option-C-${questionCount}" style="display: none;" required></textarea>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 mb-2">
                            <input type="radio" name="questions[${questionCount}][correct_answer]" value="d" required>
                            <div class="flex-1">
                                <div id="quill-editor-option-D-${questionCount}" class="quill-editor-dark" style="height: 60px;"></div>
                                <textarea name="questions[${questionCount}][options][d]" id="quill-textarea-option-D-${questionCount}" style="display: none;" required></textarea>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="addOption(${questionCount})" class="btn btn-outline btn-sm mt-2">
                        <i class="ph-plus mr-1"></i>
                        Tambah Pilihan
                    </button>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Poin</label>
                        <input type="number" name="questions[${questionCount}][points]" class="form-input" value="10" min="1" required>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', questionHTML);
    
    // Initialize Quill editors for the new question
    initializeQuillEditors(questionCount);
}

function removeQuestion(id) {
    document.getElementById(`question-${id}`).remove();
}

function addOption(questionId) {
    const container = document.getElementById(`options-${questionId}`);
    const optionCount = container.children.length;
    const optionLetter = String.fromCharCode(97 + optionCount); // a, b, c, d, e
    
    const optionHTML = `
        <div class="flex items-center space-x-2 mb-2">
            <input type="radio" name="questions[${questionId}][correct_answer]" value="${optionLetter}" required>
            <div class="flex-1">
                <div id="quill-editor-option-${optionLetter.toUpperCase()}-${questionId}" class="quill-editor-dark" style="height: 60px;"></div>
                <textarea name="questions[${questionId}][options][${optionLetter}]" id="quill-textarea-option-${optionLetter.toUpperCase()}-${questionId}" style="display: none;" required></textarea>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', optionHTML);
    
    // Initialize Quill editor for the new option
    const optionEditorId = `quill-editor-option-${optionLetter.toUpperCase()}-${questionId}`;
    const optionTextareaId = `quill-textarea-option-${optionLetter.toUpperCase()}-${questionId}`;
    
    if (document.getElementById(optionEditorId)) {
        const optionEditor = new Quill(`#${optionEditorId}`, {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    ['link'],
                    ['clean']
                ]
            },
            placeholder: `Pilihan ${optionLetter.toUpperCase()}...`
        });
        
        quillEditors[optionEditorId] = optionEditor;
        
        // Update textarea when editor content changes
        optionEditor.on('text-change', function() {
            const textarea = document.getElementById(optionTextareaId);
            textarea.value = optionEditor.root.innerHTML;
        });
    }
}

// Rubric Functions
function addRubricItem() {
    rubricCount++;
    const container = document.getElementById('rubricContainer');
    
    const rubricHTML = `
        <div class="rubric-item" id="rubric-${rubricCount}">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-medium text-white">Item Penilaian ${rubricCount}</h4>
                <button type="button" onclick="removeRubricItem(${rubricCount})" class="text-red-400 hover:text-red-300">
                    <i class="ph-trash"></i>
                </button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="form-label">Item Penilaian</label>
                    <input type="text" name="rubric_items[${rubricCount}][item]" class="form-input" placeholder="Contoh: Apakah diskusi kelompok berjalan dengan baik?" required>
                </div>
                
                <div>
                    <label class="form-label">Tipe Jawaban</label>
                    <select name="rubric_items[${rubricCount}][type]" class="form-select" onchange="updateRubricType(${rubricCount})" required>
                        <option value="yes_no">Ya/Tidak</option>
                        <option value="scale">Skala (Sangat Baik, Baik, Cukup, Kurang)</option>
                        <option value="text">Teks Bebas</option>
                    </select>
                </div>
                
                <div>
                    <label class="form-label">Poin Maksimal</label>
                    <input type="number" name="rubric_items[${rubricCount}][points]" class="form-input" value="10" min="0" required>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', rubricHTML);
}

function removeRubricItem(id) {
    document.getElementById(`rubric-${id}`).remove();
}

function updateRubricType(id) {
    // This function can be expanded to show different options based on rubric type
    console.log('Rubric type updated for item', id);
}

// Essay Question Functions
let essayQuestionCount = {{ $tipe == 2 || $tipe == 3 ? $tugas->TugasMandiri->count() : 0 }};

function initializeEssayQuillEditor(questionNum) {
    const editorId = `quill-editor-essay-${questionNum}`;
    const textareaId = `quill-textarea-essay-${questionNum}`;
    
    if (document.getElementById(editorId)) {
        const isMobile = window.innerWidth <= 768;
        const editor = new Quill(`#${editorId}`, {
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
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'align': [] }],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            },
            placeholder: 'Tuliskan pertanyaan essay di sini...'
        });
        
        quillEditors[editorId] = editor;
        
        // Set initial content
        const textarea = document.getElementById(textareaId);
        if (textarea && textarea.value) {
            editor.root.innerHTML = textarea.value;
        }
        
        // Update textarea when editor content changes
        editor.on('text-change', function() {
            textarea.value = editor.root.innerHTML;
        });
        
        // Custom image handler
        editor.getModule('toolbar').addHandler('image', async function() {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();
            
            input.onchange = async function() {
                const file = input.files[0];
                if (file) {
                    const compressedFile = await compressImage(file);
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const range = editor.getSelection();
                        editor.insertEmbed(range.index, 'image', e.target.result);
                    };
                    reader.readAsDataURL(compressedFile);
                }
            };
        });
    }
}

function addEssayQuestion() {
    essayQuestionCount++;
    const container = document.getElementById('essayQuestionsContainer');
    
    const questionHTML = `
        <div class="question-block" id="essay-question-${essayQuestionCount}">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-medium text-white">Soal ${essayQuestionCount}</h4>
                <button type="button" onclick="removeEssayQuestion(${essayQuestionCount})" class="text-red-400 hover:text-red-300">
                    <i class="ph-trash"></i>
                </button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="form-label">Pertanyaan</label>
                    <div id="quill-editor-essay-${essayQuestionCount}" class="quill-editor-dark" style="height: 150px;"></div>
                    <textarea name="essay_questions[${essayQuestionCount}][question]" id="quill-textarea-essay-${essayQuestionCount}" style="display: none;" required></textarea>
                </div>
                
                <div>
                    <label class="form-label">Poin</label>
                    <input type="number" name="essay_questions[${essayQuestionCount}][points]" 
                           class="form-input" value="10" min="1" max="100" required style="max-width: 150px;">
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', questionHTML);
    
    // Initialize Quill editor for the new question
    initializeEssayQuillEditor(essayQuestionCount);
}

function removeEssayQuestion(id) {
    const element = document.getElementById(`essay-question-${id}`);
    if (element) {
        element.remove();
    }
}
</script>
@endsection
