@extends('layouts.unified-layout-new')

@section('title', 'Buat Tugas - Superadmin')

@section('content')
<!-- JavaScript untuk fungsi addNewQuestion -->
<script>
let questionCount = 0;
let quillEditors = {};

// Global function for adding new question
function addNewQuestion() {
    console.log('addNewQuestion called, current count:', questionCount);
    questionCount++;
    console.log('About to add question:', questionCount);
    
    // Check task type from hidden input
    const taskType = document.querySelector('input[name="tipe"]').value;
    console.log('Task type detected:', taskType);
    
    if (taskType == 1) {
        console.log('Adding pilihan ganda question');
        addPilihanGandaQuestion(questionCount);
    } else if (taskType == 2) {
        console.log('Adding essay question');
        addEssayQuestion(questionCount);
    } else if (taskType == 3) {
        console.log('Mandiri task - single question, no need to add');
        // Mandiri hanya 1 soal, tidak perlu menambah
    } else if (taskType == 4) {
        console.log('Kelompok task - no dynamic questions needed');
        // Kelompok tidak perlu menambah soal dinamis
    } else {
        console.error('Unknown task type:', taskType);
    }
    
    console.log('Question added successfully');
}

// Global function for adding pilihan ganda question
function addPilihanGandaQuestion(questionNum) {
    console.log('Adding pilihan ganda question:', questionNum);
    const container = document.getElementById('pilihanGandaContainer');
    const questionId = `pilihan_ganda_${questionNum}`;
    
    if (!container) {
        console.error('pilihanGandaContainer not found');
        return;
    }
    
    // Remove placeholder message if this is the first question
    const placeholder = container.querySelector('.text-center');
    if (placeholder) {
        placeholder.remove();
    }
    
    const questionCard = document.createElement('div');
    questionCard.className = 'bg-gray-800 rounded-lg p-6 mb-4 border border-gray-600';
    questionCard.innerHTML = `
        <div class="flex justify-between items-center mb-4">
            <h6 class="text-lg font-semibold text-white">Soal ${questionNum}</h6>
            <div class="flex space-x-2">
                <button type="button" class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700 transition duration-200" onclick="removeQuestion(${questionNum})">
                    <i class="fas fa-trash mr-1"></i> Hapus
                </button>
                </div>
        </div>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Pertanyaan</label>
                <div class="bg-gray-700 rounded-lg border border-gray-600 overflow-hidden">
                    <div id="quill-editor-${questionNum}" class="quill-editor-dark" style="height: 150px;"></div>
                    <textarea name="pilihan_ganda[${questionNum}][pertanyaan]" id="quill-textarea-${questionNum}" style="display: none;"></textarea>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Poin</label>
                <input type="number" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                       name="pilihan_ganda[${questionNum}][poin]" value="1" min="1" step="0.1">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Pilihan Jawaban</label>
                <div id="options-${questionNum}" class="space-y-3">
                    <div class="flex items-center space-x-3 p-3 bg-gray-700 rounded-lg border border-gray-600">
                        <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-semibold text-sm">1</div>
                        <input type="text" class="flex-1 px-4 py-2 bg-gray-600 border border-gray-500 text-white rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               name="pilihan_ganda[${questionNum}][pilihan][1]" placeholder="Pilihan 1" required>
                        <div class="flex items-center space-x-2">
                            <input type="radio" name="pilihan_ganda[${questionNum}][jawaban]" value="1" required class="text-blue-600">
                            <label class="text-gray-300 text-sm">Benar</label>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3 p-3 bg-gray-700 rounded-lg border border-gray-600">
                        <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-semibold text-sm">2</div>
                        <input type="text" class="flex-1 px-4 py-2 bg-gray-600 border border-gray-500 text-white rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               name="pilihan_ganda[${questionNum}][pilihan][2]" placeholder="Pilihan 2" required>
                        <div class="flex items-center space-x-2">
                            <input type="radio" name="pilihan_ganda[${questionNum}][jawaban]" value="2" required class="text-blue-600">
                            <label class="text-gray-300 text-sm">Benar</label>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3 p-3 bg-gray-700 rounded-lg border border-gray-600">
                        <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-semibold text-sm">3</div>
                        <input type="text" class="flex-1 px-4 py-2 bg-gray-600 border border-gray-500 text-white rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               name="pilihan_ganda[${questionNum}][pilihan][3]" placeholder="Pilihan 3" required>
                        <div class="flex items-center space-x-2">
                            <input type="radio" name="pilihan_ganda[${questionNum}][jawaban]" value="3" required class="text-blue-600">
                            <label class="text-gray-300 text-sm">Benar</label>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3 p-3 bg-gray-700 rounded-lg border border-gray-600">
                        <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-semibold text-sm">4</div>
                        <input type="text" class="flex-1 px-4 py-2 bg-gray-600 border border-gray-500 text-white rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               name="pilihan_ganda[${questionNum}][pilihan][4]" placeholder="Pilihan 4" required>
                        <div class="flex items-center space-x-2">
                            <input type="radio" name="pilihan_ganda[${questionNum}][jawaban]" value="4" required class="text-blue-600">
                            <label class="text-gray-300 text-sm">Benar</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.appendChild(questionCard);
    console.log('Question card added to container:', questionCard);
    
    // Initialize Quill editor
    setTimeout(() => {
        console.log('Initializing Quill editor for question:', questionNum);
        initializeQuillEditor(questionNum);
    }, 100);
}

// Test function availability
console.log('addNewQuestion function available:', typeof addNewQuestion);
console.log('addPilihanGandaQuestion function available:', typeof addPilihanGandaQuestion);
console.log('addEssayQuestion function available:', typeof addEssayQuestion);
console.log('addMandiriQuestion function available:', typeof addMandiriQuestion);
console.log('addKelompokQuestion function available:', typeof addKelompokQuestion);
</script>

<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden border border-gray-700">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                <h1 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-plus-circle mr-3"></i>
                    Buat Tugas {{ $tipeTugas }}
                </h1>
                <p class="text-blue-100 mt-1">Buat tugas {{ strtolower($tipeTugas) }} baru untuk siswa dengan mudah</p>
                <div class="mt-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-500 text-white">
                        <i class="fas fa-tag mr-1"></i>
                        Tipe: {{ $tipeTugas }}
                    </span>
                </div>
            </div>
            <div class="p-6 bg-gray-800">
                    <form id="tugasForm" method="POST" action="{{ route('superadmin.tugas.store') }}" enctype="multipart/form-data">
                        @csrf
                        <!-- Hidden input untuk tipe tugas -->
                        <input type="hidden" name="tipe" value="{{ $tipe }}">
                        <!-- Pilih Kelas dan Mata Pelajaran -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="kelas_id" class="block text-sm font-medium text-gray-300 mb-2">
                                    Pilih Kelas <span class="text-red-400">*</span>
                                </label>
                                <select class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('kelas_id') border-red-500 @enderror" 
                                        id="kelas_id" name="kelas_id" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                            {{ $k->name }} - {{ $k->level ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kelas_id')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="mapel_id" class="block text-sm font-medium text-gray-300 mb-2">
                                    Pilih Mata Pelajaran <span class="text-red-400">*</span>
                                </label>
                                <select class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('mapel_id') border-red-500 @enderror" 
                                        id="mapel_id" name="mapel_id" required>
                                    <option value="">Pilih Mata Pelajaran</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('mapel_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('mapel_id')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Informasi Tugas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                                    Judul Tugas <span class="text-red-400">*</span>
                                </label>
                                <input type="text" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('name') border-red-500 @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan judul tugas" required>
                                    @error('name')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Tipe Tugas
                                </label>
                                <div class="w-full px-4 py-3 bg-gray-600 border border-gray-500 text-gray-300 rounded-lg">
                                    Pilihan Ganda
                            </div>
                                <input type="hidden" name="tipe" value="pilihan_ganda">
                            </div>
                        </div>

                        <!-- Waktu Tugas -->
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-6 mb-6">
                            <div>
                                <label for="due" class="block text-sm font-medium text-gray-300 mb-2">
                                    Tanggal Tenggat <span class="text-red-400">*</span>
                                </label>
                                <input type="datetime-local" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('due') border-red-500 @enderror" 
                                           id="due" name="due" value="{{ old('due') }}" required>
                                    @error('due')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                            </div>
                        </div>

                        <!-- Deskripsi Tugas dengan Quill Editor -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                Deskripsi Tugas
                            </label>
                            <div class="bg-gray-700 rounded-lg border border-gray-600 overflow-hidden">
                                <div id="deskripsi-editor" class="quill-editor-dark" style="height: 200px;"></div>
                                <textarea name="content" id="deskripsi-textarea" style="display: none;">{{ old('content') }}</textarea>
                            </div>
                            @error('content')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        @if($tipe == 1)
                        <!-- Pilihan Ganda Section -->
                        <div id="pilihanGandaSection" class="mb-6 bg-gray-700 rounded-lg p-6 border border-gray-600" style="display: block !important;">
                            <div class="flex justify-between items-center mb-4">
                                <h5 class="text-lg font-semibold text-white flex items-center">
                                    <i class="fas fa-list-ul mr-2 text-blue-400"></i>
                                    Soal Pilihan Ganda
                                </h5>
                                <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 flex items-center text-sm" id="addPilihanGanda" onclick="addNewQuestion()">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Soal
                                </button>
                            </div>
                            <div id="pilihanGandaContainer">
                                <div class="text-center py-8 text-gray-400">
                                    <i class="fas fa-list-ul text-4xl mb-4"></i>
                                    <p>Belum ada soal. Klik "Tambah Soal" untuk menambahkan soal pilihan ganda.</p>
                            </div>
                        </div>
                        </div>
                        @elseif($tipe == 2)
                        <!-- Essay Section -->
                        <div id="essaySection" class="mb-6 bg-gray-700 rounded-lg p-6 border border-gray-600">
                            <div class="flex justify-between items-center mb-4">
                                <h5 class="text-lg font-semibold text-white flex items-center">
                                    <i class="fas fa-edit mr-2 text-green-400"></i>
                                    Soal Essay
                                </h5>
                                <button type="button" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200 flex items-center text-sm" id="addEssay" onclick="addNewQuestion()">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Soal
                                </button>
                            </div>
                            <div id="essayContainer">
                                <div class="text-center py-8 text-gray-400">
                                    <i class="fas fa-edit text-4xl mb-4"></i>
                                    <p>Belum ada soal. Klik "Tambah Soal" untuk menambahkan soal essay.</p>
                            </div>
                        </div>
                        </div>
                        @elseif($tipe == 3)
                        <!-- Mandiri Section - Single Question -->
                        <div id="mandiriSection" class="mb-6 bg-gray-700 rounded-lg p-6 border border-gray-600">
                            <div class="flex justify-between items-center mb-4">
                                <h5 class="text-lg font-semibold text-white flex items-center">
                                    <i class="fas fa-user mr-2 text-purple-400"></i>
                                    Soal Mandiri
                                </h5>
                                <span class="px-3 py-1 bg-purple-500 text-white rounded-full text-sm">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    1 Soal
                                </span>
                            </div>
                            <div id="mandiriContainer">
                                <div class="bg-gray-800 rounded-lg p-6 border border-gray-600">
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Pertanyaan</label>
                                            <div class="bg-gray-700 rounded-lg border border-gray-600 overflow-hidden">
                                                <div id="quill-editor-mandiri-1" class="quill-editor-dark" style="height: 200px;"></div>
                                                <textarea name="mandiri[1][pertanyaan]" id="quill-textarea-mandiri-1" style="display: none;"></textarea>
                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Poin</label>
                                            <input type="number" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                                   name="mandiri[1][poin]" value="100" min="1" step="0.1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($tipe == 4)
                        <!-- Kelompok Section -->
                        <div id="kelompokSection" class="mb-6 bg-gray-700 rounded-lg p-6 border border-gray-600">
                            <div class="flex justify-between items-center mb-4">
                                <h5 class="text-lg font-semibold text-white flex items-center">
                                    <i class="fas fa-users mr-2 text-orange-400"></i>
                                    Tugas Kelompok
                                </h5>
                                <span class="px-3 py-1 bg-orange-500 text-white rounded-full text-sm">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Penilaian Kelompok
                                </span>
                        </div>
                            
                            <!-- Deskripsi Pemilihan Anggota dan Ketua -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Deskripsi Pemilihan Anggota Kelompok dan Ketua Kelompok
                                </label>
                                <div class="bg-gray-700 rounded-lg border border-gray-600 overflow-hidden">
                                    <div id="quill-editor-kelompok-deskripsi" class="quill-editor-dark" style="height: 200px;"></div>
                                    <textarea name="kelompok[deskripsi]" id="quill-textarea-kelompok-deskripsi" style="display: none;"></textarea>
                                </div>
                            </div>

                            <!-- Sistem Penilaian -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Penilaian Ya/Tidak -->
                                <div class="bg-gray-800 rounded-lg p-6 border border-gray-600">
                                    <h6 class="text-lg font-semibold text-white mb-4 flex items-center">
                                        <i class="fas fa-check-circle mr-2 text-green-400"></i>
                                        Penilaian Ya/Tidak
                                    </h6>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Pertanyaan</label>
                                            <div class="bg-gray-700 rounded-lg border border-gray-600 overflow-hidden">
                                                <div id="quill-editor-kelompok-ya-tidak" class="quill-editor-dark" style="height: 120px;"></div>
                                                <textarea name="kelompok[ya_tidak][pertanyaan]" id="quill-textarea-kelompok-ya-tidak" style="display: none;"></textarea>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-300 mb-2">Poin Ya</label>
                                                <input type="number" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                                                       name="kelompok[ya_tidak][poin_ya]" value="100" min="50" max="100" step="1">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-300 mb-2">Poin Tidak</label>
                                                <input type="number" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent" 
                                                       name="kelompok[ya_tidak][poin_tidak]" value="50" min="0" max="50" step="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Penilaian Skala Setuju -->
                                <div class="bg-gray-800 rounded-lg p-6 border border-gray-600">
                                    <h6 class="text-lg font-semibold text-white mb-4 flex items-center">
                                        <i class="fas fa-star mr-2 text-yellow-400"></i>
                                        Skala Setuju
                                    </h6>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Pertanyaan</label>
                                            <div class="bg-gray-700 rounded-lg border border-gray-600 overflow-hidden">
                                                <div id="quill-editor-kelompok-skala" class="quill-editor-dark" style="height: 120px;"></div>
                                                <textarea name="kelompok[skala][pertanyaan]" id="quill-textarea-kelompok-skala" style="display: none;"></textarea>
                                            </div>
                                        </div>
                                        <div class="space-y-3">
                                            <div class="flex items-center justify-between">
                                                <span class="text-gray-300 text-sm">Sangat Setuju</span>
                                                <input type="number" class="w-20 px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded focus:ring-2 focus:ring-yellow-500 focus:border-transparent" 
                                                       name="kelompok[skala][poin_sangat_setuju]" value="100" min="75" max="100" step="1">
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-gray-300 text-sm">Setuju</span>
                                                <input type="number" class="w-20 px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded focus:ring-2 focus:ring-yellow-500 focus:border-transparent" 
                                                       name="kelompok[skala][poin_setuju]" value="75" min="50" max="90" step="1">
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-gray-300 text-sm">Cukup Setuju</span>
                                                <input type="number" class="w-20 px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded focus:ring-2 focus:ring-yellow-500 focus:border-transparent" 
                                                       name="kelompok[skala][poin_cukup_setuju]" value="50" min="25" max="75" step="1">
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-gray-300 text-sm">Kurang Setuju</span>
                                                <input type="number" class="w-20 px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded focus:ring-2 focus:ring-yellow-500 focus:border-transparent" 
                                                       name="kelompok[skala][poin_kurang_setuju]" value="25" min="0" max="50" step="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Perwakilan Kelompok -->
                            <div class="mt-6 bg-gray-800 rounded-lg p-6 border border-gray-600">
                                <h6 class="text-lg font-semibold text-white mb-4 flex items-center">
                                    <i class="fas fa-user-tie mr-2 text-blue-400"></i>
                                    Perwakilan Kelompok (Ketua)
                                </h6>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-300 mb-2">Pilih Ketua Kelompok</label>
                                        <select class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                                name="kelompok[ketua_id]" id="ketua_kelompok">
                                            <option value="">Pilih Ketua Kelompok</option>
                                            @foreach($kelas as $k)
                                                <optgroup label="Kelas {{ $k->name }}">
                                                    @foreach($k->users as $user)
                                                        @if($user->roles_id == 4) <!-- Siswa -->
                                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                                        @endif
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="text-sm text-gray-400">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Ketua kelompok akan menjadi perwakilan untuk mengumpulkan tugas dan berkoordinasi dengan guru.
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Submit Button -->
                        <!-- Tombol Aksi -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-600">
                            <a href="{{ route('superadmin.task-management') }}" 
                               class="px-6 py-3 border border-gray-600 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200 flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali
                            </a>
                            <button type="submit" 
                                    class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition duration-200 flex items-center font-medium">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Tugas
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
<!-- Quill Editor CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
/* Dark Theme untuk Quill Editor */
.quill-editor-dark .ql-toolbar {
    background: #374151;
    border-color: #4B5563;
    color: #D1D5DB;
}

.quill-editor-dark .ql-container {
    background: #1F2937;
    border-color: #4B5563;
    color: #F9FAFB;
}

.quill-editor-dark .ql-editor {
    color: #F9FAFB;
}

.quill-editor-dark .ql-editor.ql-blank::before {
    color: #9CA3AF;
}

.quill-editor-dark .ql-toolbar .ql-stroke {
    stroke: #D1D5DB;
}

.quill-editor-dark .ql-toolbar .ql-fill {
    fill: #D1D5DB;
}

.quill-editor-dark .ql-toolbar button:hover {
    background: #4B5563;
}

.quill-editor-dark .ql-toolbar button.ql-active {
    background: #3B82F6;
    color: white;
}
</style>

<style>
/* Quill Editor Modern Styling */
.quill-editor-modern {
    border: 2px solid #e3e6f0;
    border-radius: 8px;
    background: #fff;
    transition: all 0.3s ease;
}

.quill-editor-modern:hover {
    border-color: #5a5c69;
}

.quill-editor-modern .ql-toolbar {
    border-top: none;
    border-left: none;
    border-right: none;
    border-bottom: 1px solid #e3e6f0;
    background: #f8f9fc;
    border-radius: 8px 8px 0 0;
    padding: 12px;
}

.quill-editor-modern .ql-toolbar .ql-formats {
    margin-right: 15px;
}

.quill-editor-modern .ql-toolbar button {
    width: 28px;
    height: 28px;
    border: none;
    background: transparent;
    border-radius: 4px;
    transition: all 0.2s ease;
    color: #5a5c69;
}

.quill-editor-modern .ql-toolbar button:hover {
    background: #e3e6f0;
    color: #3a3b45;
}

.quill-editor-modern .ql-toolbar button.ql-active {
    background: #4e73df;
    color: white;
}

.quill-editor-modern .ql-container {
    border: none;
    border-radius: 0 0 8px 8px;
    font-size: 14px;
}

.quill-editor-modern .ql-editor {
    min-height: 120px;
    padding: 15px;
    line-height: 1.6;
    color: #5a5c69;
}

.quill-editor-modern .ql-editor.ql-blank::before {
    color: #a0a0a0;
    font-style: italic;
    font-size: 14px;
}

/* Question Card Styling */
.question-card {
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    margin-bottom: 20px;
    background: #fff;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.question-header {
    background: #f8f9fc;
    padding: 15px 20px;
    border-bottom: 1px solid #e3e6f0;
    border-radius: 8px 8px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.question-number {
    font-weight: 600;
    color: #5a5c69;
    margin: 0;
}

.question-actions {
    display: flex;
    gap: 10px;
}

.question-body {
    padding: 20px;
}

.option-group {
    margin-top: 15px;
}

.option-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    padding: 10px;
    background: #f8f9fc;
    border-radius: 6px;
    border: 1px solid #e3e6f0;
}

.option-letter {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #4e73df;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    margin-right: 15px;
    flex-shrink: 0;
}

.option-input {
    flex: 1;
    border: none;
    background: transparent;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 14px;
}

.option-input:focus {
    outline: none;
    background: white;
    box-shadow: 0 0 0 2px #4e73df;
}

.option-actions {
    display: flex;
    gap: 5px;
    margin-left: 10px;
    align-items: center;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 12px;
}

/* Responsive */
@media (max-width: 768px) {
    .question-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .option-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .option-letter {
        margin-right: 0;
        margin-bottom: 5px;
    }
}
</style>
@endpush

<!-- Quill Editor JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize deskripsi Quill editor
    const deskripsiEditor = new Quill('#deskripsi-editor', {
        theme: 'snow',
        placeholder: 'Masukkan deskripsi tugas...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'align': [] }],
                ['link', 'image', 'video'],
                ['blockquote', 'code-block'],
                ['clean']
            ]
        }
    });
    
    // Apply dark theme to deskripsi editor
    setTimeout(() => {
        const toolbar = document.querySelector('#deskripsi-editor .ql-toolbar');
        const container = document.querySelector('#deskripsi-editor .ql-container');
        if (toolbar) {
            toolbar.style.background = '#374151';
            toolbar.style.borderColor = '#4B5563';
        }
        if (container) {
            container.style.background = '#1F2937';
            container.style.borderColor = '#4B5563';
        }
    }, 100);
    
    // Update deskripsi textarea
    deskripsiEditor.on('text-change', function() {
        document.getElementById('deskripsi-textarea').value = deskripsiEditor.root.innerHTML;
    });
    
    // Ensure sections are visible and setup event listeners
    document.addEventListener('DOMContentLoaded', function() {
        const pilihanGandaSection = document.getElementById('pilihanGandaSection');
        if (pilihanGandaSection) {
            pilihanGandaSection.style.display = 'block';
        }
        
        // Add Pilihan Ganda Question event listener
        const addButton = document.getElementById('addPilihanGanda');
        if (addButton) {
            addButton.addEventListener('click', function() {
                console.log('Tambah soal pilihan ganda clicked');
        questionCount++;
                addPilihanGandaQuestion(questionCount);
            });
            console.log('Event listener added to addPilihanGanda button');
        } else {
            console.error('addPilihanGanda button not found');
        }
        
        // Initialize Quill editors for mandiri and kelompok
        const taskType = document.querySelector('input[name="tipe"]').value;
        
        if (taskType == 3) {
            // Initialize mandiri editor
        setTimeout(() => {
                initializeQuillEditorForMandiri(1);
        }, 100);
        } else if (taskType == 4) {
            // Initialize kelompok editors
        setTimeout(() => {
                initializeQuillEditorForKelompok('deskripsi');
                initializeQuillEditorForKelompok('ya-tidak');
                initializeQuillEditorForKelompok('skala');
        }, 100);
    }
    });
    
    // Only pilihan ganda questions are available on this page
});


// Global function for initializing Quill editor
    function initializeQuillEditor(questionNum) {
        const editorId = `quill-editor-${questionNum}`;
        const textareaId = `quill-textarea-${questionNum}`;
        
        const editorElement = document.getElementById(editorId);
        const textareaElement = document.getElementById(textareaId);
        
        if (!editorElement || !textareaElement) {
            console.error(`Elements not found for question ${questionNum}`);
            return;
        }
        
        try {
            const quill = new Quill(`#${editorId}`, {
                theme: 'snow',
                placeholder: 'Masukkan pertanyaan...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'image'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'align': [] }],
                        ['clean']
                    ]
                }
            });
            
            quillEditors[questionNum] = quill;
            
            // Apply dark theme
            setTimeout(() => {
                const toolbar = editorElement.querySelector('.ql-toolbar');
                const container = editorElement.querySelector('.ql-container');
                if (toolbar) {
                    toolbar.style.background = '#374151';
                    toolbar.style.borderColor = '#4B5563';
                }
                if (container) {
                    container.style.background = '#1F2937';
                    container.style.borderColor = '#4B5563';
                }
            }, 100);
            
            // Update textarea when content changes
            quill.on('text-change', function() {
                textareaElement.value = quill.root.innerHTML;
            });
            
            console.log(`Quill editor initialized for question ${questionNum}`);
        } catch (error) {
            console.error(`Error initializing Quill editor for question ${questionNum}:`, error);
        }
    }
    
    // Global function for initializing Quill editor for essay
    function initializeQuillEditorForEssay(questionNum) {
        const editorId = `quill-editor-essay-${questionNum}`;
        const textareaId = `quill-textarea-essay-${questionNum}`;
        
        const editorElement = document.getElementById(editorId);
        const textareaElement = document.getElementById(textareaId);
        
        if (!editorElement || !textareaElement) {
            console.error(`Elements not found for essay question ${questionNum}`);
            return;
        }
        
        try {
            const quill = new Quill(`#${editorId}`, {
                theme: 'snow',
                placeholder: 'Masukkan pertanyaan essay...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'image'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'align': [] }],
                        ['clean']
                    ]
                }
            });
            
            quillEditors[`essay-${questionNum}`] = quill;
            
            // Apply dark theme
            setTimeout(() => {
                const toolbar = editorElement.querySelector('.ql-toolbar');
                const container = editorElement.querySelector('.ql-container');
                if (toolbar) {
                    toolbar.style.background = '#374151';
                    toolbar.style.borderColor = '#4B5563';
                }
                if (container) {
                    container.style.background = '#1F2937';
                    container.style.borderColor = '#4B5563';
                }
            }, 100);
            
            // Update textarea when content changes
            quill.on('text-change', function() {
                textareaElement.value = quill.root.innerHTML;
            });
            
            console.log(`Quill editor initialized for essay question ${questionNum}`);
        } catch (error) {
            console.error(`Error initializing Quill editor for essay question ${questionNum}:`, error);
        }
    }
    
    // Global function for initializing Quill editor for mandiri
    function initializeQuillEditorForMandiri(questionNum) {
        const editorId = `quill-editor-mandiri-${questionNum}`;
        const textareaId = `quill-textarea-mandiri-${questionNum}`;
        
        const editorElement = document.getElementById(editorId);
        const textareaElement = document.getElementById(textareaId);
        
        if (!editorElement || !textareaElement) {
            console.error(`Elements not found for mandiri question ${questionNum}`);
            return;
        }
        
        try {
            const quill = new Quill(`#${editorId}`, {
                theme: 'snow',
                placeholder: 'Masukkan pertanyaan mandiri...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'image'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'align': [] }],
                        ['clean']
                    ]
                }
            });
            
            quillEditors[`mandiri-${questionNum}`] = quill;
            
            // Apply dark theme
            setTimeout(() => {
                const toolbar = editorElement.querySelector('.ql-toolbar');
                const container = editorElement.querySelector('.ql-container');
                if (toolbar) {
                    toolbar.style.background = '#374151';
                    toolbar.style.borderColor = '#4B5563';
                }
                if (container) {
                    container.style.background = '#1F2937';
                    container.style.borderColor = '#4B5563';
                }
            }, 100);
            
            // Update textarea when content changes
            quill.on('text-change', function() {
                textareaElement.value = quill.root.innerHTML;
            });
            
            console.log(`Quill editor initialized for mandiri question ${questionNum}`);
        } catch (error) {
            console.error(`Error initializing Quill editor for mandiri question ${questionNum}:`, error);
        }
    }
    
    // Global function for initializing Quill editor for kelompok
    function initializeQuillEditorForKelompok(type) {
        const editorId = `quill-editor-kelompok-${type}`;
        const textareaId = `quill-textarea-kelompok-${type}`;
        
        const editorElement = document.getElementById(editorId);
        const textareaElement = document.getElementById(textareaId);
        
        if (!editorElement || !textareaElement) {
            console.error(`Elements not found for kelompok ${type}`);
            return;
        }
        
        try {
            let placeholder = 'Masukkan pertanyaan kelompok...';
            if (type === 'deskripsi') {
                placeholder = 'Masukkan deskripsi pemilihan anggota dan ketua kelompok...';
            } else if (type === 'ya-tidak') {
                placeholder = 'Masukkan pertanyaan untuk penilaian Ya/Tidak...';
            } else if (type === 'skala') {
                placeholder = 'Masukkan pertanyaan untuk skala setuju...';
            }
            
            const quill = new Quill(`#${editorId}`, {
                theme: 'snow',
                placeholder: placeholder,
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'image'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'align': [] }],
                        ['clean']
                    ]
                }
            });
            
            quillEditors[`kelompok-${type}`] = quill;
            
            // Apply dark theme
            setTimeout(() => {
                const toolbar = editorElement.querySelector('.ql-toolbar');
                const container = editorElement.querySelector('.ql-container');
                if (toolbar) {
                    toolbar.style.background = '#374151';
                    toolbar.style.borderColor = '#4B5563';
                }
                if (container) {
                    container.style.background = '#1F2937';
                    container.style.borderColor = '#4B5563';
                }
            }, 100);
            
            // Update textarea when content changes
            quill.on('text-change', function() {
                textareaElement.value = quill.root.innerHTML;
            });
            
            console.log(`Quill editor initialized for kelompok ${type}`);
        } catch (error) {
            console.error(`Error initializing Quill editor for kelompok ${type}:`, error);
        }
    }
    
    // Only pilihan ganda Quill editor is needed
    
    // Global functions for buttons
    // Pilihan jawaban dibatasi hanya A, B, C, D
    
    // Global function for adding essay question
    function addEssayQuestion(questionNum) {
        console.log('Adding essay question:', questionNum);
        const container = document.getElementById('essayContainer');
        
        if (!container) {
            console.error('essayContainer not found');
            return;
        }
        
        // Remove placeholder message if this is the first question
        const placeholder = container.querySelector('.text-center');
        if (placeholder) {
            placeholder.remove();
        }
        
        const questionCard = document.createElement('div');
        questionCard.className = 'bg-gray-800 rounded-lg p-6 mb-4 border border-gray-600';
        questionCard.innerHTML = `
            <div class="flex justify-between items-center mb-4">
                <h6 class="text-lg font-semibold text-white">Soal ${questionNum}</h6>
                <button type="button" class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700 transition duration-200" onclick="removeQuestion(${questionNum})">
                    <i class="fas fa-trash mr-1"></i> Hapus
                </button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Pertanyaan</label>
                    <div class="bg-gray-700 rounded-lg border border-gray-600 overflow-hidden">
                        <div id="quill-editor-essay-${questionNum}" class="quill-editor-dark" style="height: 150px;"></div>
                        <textarea name="essay[${questionNum}][pertanyaan]" id="quill-textarea-essay-${questionNum}" style="display: none;"></textarea>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Poin</label>
                    <input type="number" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                           name="essay[${questionNum}][poin]" value="1" min="1" step="0.1">
                </div>
            </div>
        `;
        
        container.appendChild(questionCard);
        
        // Initialize Quill editor
        setTimeout(() => {
            initializeQuillEditorForEssay(questionNum);
        }, 100);
    }
    
    // Global function for adding mandiri question
    function addMandiriQuestion(questionNum) {
        console.log('Adding mandiri question:', questionNum);
        const container = document.getElementById('mandiriContainer');
        
        if (!container) {
            console.error('mandiriContainer not found');
            return;
        }
        
        // Remove placeholder message if this is the first question
        const placeholder = container.querySelector('.text-center');
        if (placeholder) {
            placeholder.remove();
        }
        
        const questionCard = document.createElement('div');
        questionCard.className = 'bg-gray-800 rounded-lg p-6 mb-4 border border-gray-600';
        questionCard.innerHTML = `
            <div class="flex justify-between items-center mb-4">
                <h6 class="text-lg font-semibold text-white">Soal ${questionNum}</h6>
                <button type="button" class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700 transition duration-200" onclick="removeQuestion(${questionNum})">
                    <i class="fas fa-trash mr-1"></i> Hapus
                </button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Pertanyaan</label>
                    <div class="bg-gray-700 rounded-lg border border-gray-600 overflow-hidden">
                        <div id="quill-editor-mandiri-${questionNum}" class="quill-editor-dark" style="height: 150px;"></div>
                        <textarea name="mandiri[${questionNum}][pertanyaan]" id="quill-textarea-mandiri-${questionNum}" style="display: none;"></textarea>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Poin</label>
                    <input type="number" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                           name="mandiri[${questionNum}][poin]" value="1" min="1" step="0.1">
                </div>
            </div>
        `;
        
        container.appendChild(questionCard);
        
        // Initialize Quill editor
        setTimeout(() => {
            initializeQuillEditorForMandiri(questionNum);
        }, 100);
    }
    
    // Global function for adding kelompok question
    function addKelompokQuestion(questionNum) {
        console.log('Adding kelompok question:', questionNum);
        const container = document.getElementById('kelompokContainer');
        
        if (!container) {
            console.error('kelompokContainer not found');
            return;
        }
        
        // Remove placeholder message if this is the first question
        const placeholder = container.querySelector('.text-center');
        if (placeholder) {
            placeholder.remove();
        }
        
        const questionCard = document.createElement('div');
        questionCard.className = 'bg-gray-800 rounded-lg p-6 mb-4 border border-gray-600';
        questionCard.innerHTML = `
            <div class="flex justify-between items-center mb-4">
                <h6 class="text-lg font-semibold text-white">Soal ${questionNum}</h6>
                <button type="button" class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700 transition duration-200" onclick="removeQuestion(${questionNum})">
                    <i class="fas fa-trash mr-1"></i> Hapus
                </button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Pertanyaan</label>
                    <div class="bg-gray-700 rounded-lg border border-gray-600 overflow-hidden">
                        <div id="quill-editor-kelompok-${questionNum}" class="quill-editor-dark" style="height: 150px;"></div>
                        <textarea name="kelompok[${questionNum}][pertanyaan]" id="quill-textarea-kelompok-${questionNum}" style="display: none;"></textarea>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Poin</label>
                    <input type="number" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" 
                           name="kelompok[${questionNum}][poin]" value="1" min="1" step="0.1">
                </div>
            </div>
        `;
        
        container.appendChild(questionCard);
        
        // Initialize Quill editor
        setTimeout(() => {
            initializeQuillEditorForKelompok(questionNum);
        }, 100);
    }
    
    window.removeQuestion = function(questionNum) {
        const questionCard = document.querySelector(`[onclick*="removeQuestion(${questionNum})"]`).closest('.question-card');
        questionCard.remove();
        
        // Clean up Quill editor
        if (quillEditors[questionNum]) {
            quillEditors[questionNum] = null;
        }
    };
    
    // Form submission
    document.getElementById('tugasForm').addEventListener('submit', function(e) {
        // Update deskripsi textarea
        document.getElementById('deskripsi-textarea').value = deskripsiEditor.root.innerHTML;
        
        // Update all question textarea values before submission
        Object.keys(quillEditors).forEach(key => {
            if (quillEditors[key]) {
                const questionNum = key.replace('essay_', '').replace('mandiri_', '');
                const textareaId = key.startsWith('essay_') ? `essay-quill-textarea-${questionNum}` : 
                                 key.startsWith('mandiri_') ? `mandiri-quill-textarea-${questionNum}` : 
                                 `quill-textarea-${questionNum}`;
                const textarea = document.getElementById(textareaId);
                if (textarea) {
                    textarea.value = quillEditors[key].root.innerHTML;
                }
            }
    });
});
</script>
