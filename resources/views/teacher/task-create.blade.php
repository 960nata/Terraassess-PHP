@extends('layouts.unified-layout')

@section('title', $title)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $title }}</h1>
                <p class="mt-2 text-gray-300">Buat tugas {{ strtolower($tipeTugas) }} untuk siswa Anda</p>
            </div>
            <a href="{{ route('teacher.tasks') }}" class="btn btn-outline">
                <i class="ph-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <form id="taskForm" method="POST" action="{{ route('teacher.tasks.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="tipe" value="{{ $tipe }}">

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
                                       value="{{ old('name') }}" required>
                                @error('name')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Deskripsi/Instruksi *</label>
                                @include('components.modern-quill-editor', [
                                    'name' => 'content',
                                    'content' => old('content'),
                                    'placeholder' => 'Tuliskan instruksi yang jelas untuk siswa...',
                                    'height' => '200px',
                                    'required' => true
                                ])
                                @error('content')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>


                            {{-- Success/Error Messages --}}
                            @if(session('success'))
                            <div class="alert alert-success" style="background: #10b981; color: white; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                                <strong>✓ Berhasil!</strong> {{ session('success') }}
                            </div>
                            @endif

                            @if(session('error'))
                            <div class="alert alert-danger" style="background: #ef4444; color: white; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                                <strong>✗ Error!</strong> {{ session('error') }}
                            </div>
                            @endif

                            @if($errors->any())
                            <div class="alert alert-danger" style="background: #ef4444; color: white; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                                <strong>✗ Validasi Gagal!</strong>
                                <ul style="margin: 0.5rem 0 0 1.5rem;">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <div>
                                <label class="form-label">Kelas & Mata Pelajaran *</label>
                                <select id="kelas_mapel_id" name="kelas_mapel_id" class="form-select" required>
                                    <option value="">-- Pilih Kelas & Mata Pelajaran --</option>
                                    @forelse($kelasMapels ?? [] as $km)
                                        <option value="{{ $km->id }}" {{ old('kelas_mapel_id') == $km->id ? 'selected' : '' }}>
                                            {{ $km->kelas->name ?? 'N/A' }} - {{ $km->mapel->name ?? 'N/A' }}
                                            @if($km->pengajar)
                                                ({{ $km->pengajar->name }})
                                            @endif
                                        </option>
                                    @empty
                                        <option value="" disabled>Tidak ada kelas mapel tersedia</option>
                                    @endforelse
                                </select>
                                <small class="form-help">
                                    @if(empty($kelasMapels))
                                        <span style="color: #ef4444;">⚠️ Tidak ada kelas mapel. Hubungi admin untuk assign kelas mapel.</span>
                                    @else
                                        Pilih kombinasi kelas dan mata pelajaran untuk tugas ini
                                    @endif
                                </small>
                                @error('kelas_mapel_id')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Tanggal Tenggat</label>
                                <input type="datetime-local" name="due" class="form-input" 
                                       value="{{ old('due') }}">
                                @error('due')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Content Based on Task Type -->
                    @if($tipe == 1)
                        <!-- Multiple Choice Questions -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Soal Pilihan Ganda</h3>
                                <button type="button" onclick="addQuestion()" class="btn btn-primary btn-sm">
                                    <i class="ph-plus mr-1"></i>
                                    Tambah Soal
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="questionsContainer">
                                    <!-- Questions will be added here dynamically -->
                                </div>
                            </div>
                        </div>
                    @elseif($tipe == 2 || $tipe == 3)
                        <!-- Essay/Individual Task Options -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Opsi Pengumpulan</h3>
                            </div>
                            <div class="card-body space-y-4">
                                <div class="flex items-center space-x-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="allow_text_input" value="1" 
                                               {{ old('allow_text_input', true) ? 'checked' : '' }} 
                                               class="form-checkbox">
                                        <span class="ml-2 text-white">Izinkan ketik langsung</span>
                                    </label>
                                </div>

                                <div class="flex items-center space-x-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="allow_file_upload" value="1" 
                                               {{ old('allow_file_upload') ? 'checked' : '' }} 
                                               class="form-checkbox">
                                        <span class="ml-2 text-white">Izinkan upload file</span>
                                    </label>
                                </div>

                                <div id="fileTypesContainer" class="hidden">
                                    <label class="form-label">Jenis File yang Diizinkan</label>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(['pdf', 'docx', 'jpg', 'png', 'txt'] as $type)
                                            <label class="flex items-center">
                                                <input type="checkbox" name="file_types[]" value="{{ $type }}" 
                                                       {{ in_array($type, old('file_types', ['pdf', 'docx', 'jpg', 'png'])) ? 'checked' : '' }}
                                                       class="form-checkbox">
                                                <span class="ml-1 text-white">.{{ $type }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($tipe == 4)
                        <!-- Group Task Wizard -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Tugas Kelompok</h3>
                            </div>
                            <div class="card-body">
                                <div class="tabs">
                                    <div class="tab-buttons">
                                        <button type="button" class="tab-button active" onclick="switchTab('detail')">
                                            Detail Tugas
                                        </button>
                                        <button type="button" class="tab-button" onclick="switchTab('groups')">
                                            Pembentukan Kelompok
                                        </button>
                                        <button type="button" class="tab-button" onclick="switchTab('assessment')">
                                            Penilaian Antar Kelompok
                                        </button>
                                    </div>

                                    <!-- Tab 1: Detail -->
                                    <div id="tab-detail" class="tab-content active">
                                        <div class="space-y-4">
                                            <div>
                                                <label class="form-label">Tanggal Tenggat Penilaian Antar Kelompok</label>
                                                <input type="datetime-local" name="peer_assessment_due" 
                                                       class="form-input" value="{{ old('peer_assessment_due') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tab 2: Groups -->
                                    <div id="tab-groups" class="tab-content">
                                        <div class="space-y-4">
                                            <div class="flex justify-between items-center">
                                                <h4 class="text-lg font-medium text-white">Pembentukan Kelompok</h4>
                                                <button type="button" onclick="addGroup()" class="btn btn-primary btn-sm">
                                                    <i class="ph-plus mr-1"></i>
                                                    Tambah Kelompok
                                                </button>
                                            </div>
                                            
                                            <div id="groupsContainer">
                                                <!-- Groups will be added here dynamically -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tab 3: Assessment -->
                                    <div id="tab-assessment" class="tab-content">
                                        <div class="space-y-4">
                                            <div class="flex justify-between items-center">
                                                <h4 class="text-lg font-medium text-white">Rubrik Penilaian</h4>
                                                <button type="button" onclick="addRubricItem()" class="btn btn-primary btn-sm">
                                                    <i class="ph-plus mr-1"></i>
                                                    Tambah Item Penilaian
                                                </button>
                                            </div>
                                            
                                            <div id="rubricContainer">
                                                <!-- Rubric items will be added here dynamically -->
                                            </div>
                                        </div>
                                    </div>
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
                            Buat Tugas
                        </button>
                        
                        <a href="{{ route('teacher.tasks') }}" class="btn btn-outline w-full">
                            <i class="ph-x mr-2"></i>
                            Batal
                        </a>

                        <div class="border-t border-gray-600 pt-4">
                            <h4 class="text-sm font-medium text-gray-300 mb-2">Tips:</h4>
                            <ul class="text-xs text-gray-400 space-y-1">
                                @if($tipe == 1)
                                    <li>• Buat soal yang jelas dan tidak ambigu</li>
                                    <li>• Pastikan ada satu jawaban yang benar</li>
                                    <li>• Berikan poin yang sesuai dengan tingkat kesulitan</li>
                                @elseif($tipe == 2 || $tipe == 3)
                                    <li>• Berikan instruksi yang detail dan jelas</li>
                                    <li>• Tentukan format yang diinginkan</li>
                                    <li>• Berikan contoh jika diperlukan</li>
                                @elseif($tipe == 4)
                                    <li>• Bagi kelompok secara merata</li>
                                    <li>• Tentukan ketua kelompok yang bertanggung jawab</li>
                                    <li>• Buat rubrik penilaian yang objektif</li>
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

.tabs {
    margin-top: 1rem;
}

.tab-buttons {
    display: flex;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    margin-bottom: 1rem;
}

.tab-button {
    padding: 0.75rem 1rem;
    background: none;
    border: none;
    color: #94a3b8;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all 0.3s ease;
}

.tab-button.active {
    color: #3b82f6;
    border-bottom-color: #3b82f6;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.question-block {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.group-block {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.rubric-item {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}
</style>

<script>
let questionCount = 0;
let groupCount = 0;
let rubricCount = 0;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize based on task type
    @if($tipe == 1)
        addQuestion(); // Add first question for multiple choice
    @elseif($tipe == 4)
        addGroup(); // Add first group
        addRubricItem(); // Add first rubric item
    @endif

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
});

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
                    <textarea name="questions[${questionCount}][question]" class="form-textarea" rows="3" required></textarea>
                </div>
                
                <div>
                    <label class="form-label">Pilihan Jawaban</label>
                    <div id="options-${questionCount}">
                        <div class="flex items-center space-x-2 mb-2">
                            <input type="radio" name="questions[${questionCount}][correct_answer]" value="0" required>
                            <input type="text" name="questions[${questionCount}][options][]" class="form-input flex-1" placeholder="Pilihan A" required>
                        </div>
                        <div class="flex items-center space-x-2 mb-2">
                            <input type="radio" name="questions[${questionCount}][correct_answer]" value="1" required>
                            <input type="text" name="questions[${questionCount}][options][]" class="form-input flex-1" placeholder="Pilihan B" required>
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
}

function removeQuestion(id) {
    document.getElementById(`question-${id}`).remove();
}

function addOption(questionId) {
    const container = document.getElementById(`options-${questionId}`);
    const optionCount = container.children.length;
    
    const optionHTML = `
        <div class="flex items-center space-x-2 mb-2">
            <input type="radio" name="questions[${questionId}][correct_answer]" value="${optionCount}" required>
            <input type="text" name="questions[${questionId}][options][]" class="form-input flex-1" placeholder="Pilihan ${String.fromCharCode(65 + optionCount)}" required>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', optionHTML);
}

// Group Task Functions
function switchTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(`tab-${tabName}`).classList.add('active');
    
    // Add active class to clicked button
    event.target.classList.add('active');
}

function addGroup() {
    groupCount++;
    const container = document.getElementById('groupsContainer');
    
    const groupHTML = `
        <div class="group-block" id="group-${groupCount}">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-medium text-white">Kelompok ${groupCount}</h4>
                <button type="button" onclick="removeGroup(${groupCount})" class="text-red-400 hover:text-red-300">
                    <i class="ph-trash"></i>
                </button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="form-label">Nama Kelompok</label>
                    <input type="text" name="groups[${groupCount}][name]" class="form-input" placeholder="Kelompok ${groupCount}" required>
                </div>
                
                <div>
                    <label class="form-label">Anggota Kelompok</label>
                    <div id="members-${groupCount}" class="space-y-2">
                        <!-- Members will be added here -->
                    </div>
                    <button type="button" onclick="addMember(${groupCount})" class="btn btn-outline btn-sm mt-2">
                        <i class="ph-plus mr-1"></i>
                        Tambah Anggota
                    </button>
                </div>
                
                <div>
                    <label class="form-label">Ketua Kelompok</label>
                    <select name="groups[${groupCount}][leader]" class="form-select" required>
                        <option value="">Pilih Ketua</option>
                    </select>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', groupHTML);
}

function removeGroup(id) {
    document.getElementById(`group-${id}`).remove();
}

function addMember(groupId) {
    const container = document.getElementById(`members-${groupId}`);
    const memberCount = container.children.length;
    
    const memberHTML = `
        <div class="flex items-center space-x-2">
            <select name="groups[${groupId}][members][]" class="form-select flex-1" onchange="updateLeaderOptions(${groupId})" required>
                <option value="">Pilih Siswa</option>
                @foreach($kelas->first()->users()->where('roles_id', 4)->get() as $student)
                    <option value="{{ $student->id }}">{{ $student->name }}</option>
                @endforeach
            </select>
            <button type="button" onclick="removeMember(this)" class="text-red-400 hover:text-red-300">
                <i class="ph-trash"></i>
            </button>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', memberHTML);
}

function removeMember(button) {
    button.parentElement.remove();
}

function updateLeaderOptions(groupId) {
    const membersSelect = document.querySelector(`#members-${groupId} select[name*="[members]"]`);
    const leaderSelect = document.querySelector(`select[name="groups[${groupId}][leader]"]`);
    
    // Clear existing options
    leaderSelect.innerHTML = '<option value="">Pilih Ketua</option>';
    
    // Add selected members as leader options
    membersSelect.forEach(select => {
        if (select.value) {
            const option = document.createElement('option');
            option.value = select.value;
            option.textContent = select.options[select.selectedIndex].textContent;
            leaderSelect.appendChild(option);
        }
    });
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

// Real-time validation functions
function validateTaskForm() {
    let isValid = true;
    let errors = [];
    
    // Validate Judul
    const title = document.querySelector('input[name="name"]').value.trim();
    if (title === '' || title.length < 3) {
        showError('name', 'Judul tugas wajib diisi (minimal 3 karakter)');
        errors.push('Judul tugas');
        isValid = false;
    } else {
        hideError('name');
    }
    
    // Validate Kelas Mapel - Perbaikan validasi yang lebih toleran
    const kelasMapelSelect = document.getElementById('kelas_mapel_id');
    const kelasMapel = kelasMapelSelect ? kelasMapelSelect.value : '';
    
    // Debug logging untuk troubleshooting
    console.log('Validasi Kelas Mapel - Value:', kelasMapel, 'Type:', typeof kelasMapel);
    
    if (!kelasMapel || kelasMapel === '' || kelasMapel === 'null' || kelasMapel === 'undefined') {
        showError('kelas_mapel_id', 'Kelas & Mata Pelajaran wajib dipilih');
        errors.push('Kelas & Mata Pelajaran');
        isValid = false;
    } else {
        hideError('kelas_mapel_id');
    }
    
    // Validate Konten (Quill editor)
    if (typeof quill !== 'undefined') {
        const content = quill.getText().trim();
        if (content === '' || content.length < 10) {
            showError('content', 'Konten tugas wajib diisi (minimal 10 karakter)');
            errors.push('Konten tugas');
            isValid = false;
        } else {
            hideError('content');
        }
    }
    
    // Validate Deadline
    const deadline = document.querySelector('input[name="due"]').value;
    if (deadline === '') {
        showError('due', 'Deadline wajib diisi');
        errors.push('Deadline');
        isValid = false;
    } else {
        hideError('due');
    }
    
    // Show summary error if invalid
    if (!isValid) {
        alert('Form belum lengkap! Mohon lengkapi:\n\n' + errors.map((e, i) => `${i+1}. ${e}`).join('\n'));
    }
    
    return isValid;
}

function showError(fieldId, message) {
    const field = document.getElementById(fieldId) || document.querySelector(`input[name="${fieldId}"]`) || document.querySelector(`select[name="${fieldId}"]`);
    if (!field) return;
    
    field.style.borderColor = '#ef4444';
    
    let errorSpan = document.getElementById(fieldId + '-error');
    if (!errorSpan) {
        errorSpan = document.createElement('span');
        errorSpan.id = fieldId + '-error';
        errorSpan.className = 'validation-error';
        errorSpan.style.display = 'block';
        errorSpan.style.marginTop = '0.25rem';
        errorSpan.style.color = '#ef4444';
        errorSpan.style.fontSize = '0.875rem';
        field.parentNode.appendChild(errorSpan);
    }
    errorSpan.textContent = message;
}

function hideError(fieldId) {
    const field = document.getElementById(fieldId) || document.querySelector(`input[name="${fieldId}"]`) || document.querySelector(`select[name="${fieldId}"]`);
    if (!field) return;
    
    field.style.borderColor = '';
    
    const errorSpan = document.getElementById(fieldId + '-error');
    if (errorSpan) {
        errorSpan.remove();
    }
}

// Form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('taskForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateTaskForm()) {
                e.preventDefault();
                return false;
            }
        });
    }
    
    // Hapus real-time validation yang terlalu agresif
    // Validasi hanya dilakukan saat submit form untuk menghindari error prematur
    console.log('Form teacher task-create loaded - validation hanya pada submit');
});
</script>
@endsection
