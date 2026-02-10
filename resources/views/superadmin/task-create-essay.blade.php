@extends('layouts.unified-layout')

@section('title', 'Buat Tugas Essay')

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden border border-gray-700">
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                <h1 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-edit mr-3"></i>
                    Buat Tugas Essay
                </h1>
                <p class="text-purple-100 mt-1">Buat tugas essay untuk penilaian mendalam dan analisis mendalam</p>
                <div class="mt-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-500 text-white">
                        <i class="fas fa-tag mr-1"></i>
                        Tipe: Essay
                    </span>
                </div>
            </div>
            
            <div class="p-6 bg-gray-800">
                <form id="essayForm" method="POST" action="{{ route('superadmin.tasks.store') }}" enctype="multipart/form-data">
                    @csrf
                    <!-- Hidden input untuk tipe tugas -->
                    <input type="hidden" name="tipe" value="2">
                    <!-- Hidden field untuk kelas_mapel_id yang akan diisi oleh JavaScript -->
                    <input type="hidden" name="kelas_mapel_id" id="kelas_mapel_id" value="">
                    
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
                    
                    <!-- Kelas dan Mata Pelajaran -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6">
                        <div>
                            <label for="kelas_id" class="block text-sm font-medium text-gray-300 mb-2">
                                Kelas Tujuan <span class="text-red-400">*</span>
                            </label>
                            <select class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 @error('kelas_id') border-red-500 @enderror" 
                                    id="kelas_id" name="kelas_id" required>
                                <option value="">Pilih Kelas</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->name }} - {{ $k->level }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="mapel_id" class="block text-sm font-medium text-gray-300 mb-2">
                                Mata Pelajaran <span class="text-red-400">*</span>
                            </label>
                            <select class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 @error('mapel_id') border-red-500 @enderror" 
                                    id="mapel_id" name="mapel_id" required>
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach($mapel as $m)
                                    <option value="{{ $m->id }}" {{ old('mapel_id') == $m->id ? 'selected' : '' }}>
                                        {{ $m->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mapel_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Informasi Tugas -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                                Judul Tugas <span class="text-red-400">*</span>
                            </label>
                            <input type="text" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 @error('name') border-red-500 @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan judul tugas essay" required>
                            @error('name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                Tipe Tugas
                            </label>
                            <div class="w-full px-4 py-3 bg-gray-600 border border-gray-500 text-gray-300 rounded-lg">
                                Essay
                            </div>
                        </div>
                    </div>

                    <!-- Waktu Tugas -->
                    <div class="grid grid-cols-1 gap-4 md:gap-6 mb-6">
                        <div>
                            <label for="due" class="block text-sm font-medium text-gray-300 mb-2">
                                Tanggal Tenggat <span class="text-red-400">*</span>
                            </label>
                            <input type="datetime-local" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 @error('due') border-red-500 @enderror" 
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

                    <!-- Essay Section -->
                    <div id="essaySection" class="mb-6 bg-gray-700 rounded-lg p-6 border border-gray-600">
                        <div class="flex justify-between items-center mb-4">
                            <h5 class="text-lg font-semibold text-white flex items-center">
                                <i class="fas fa-edit mr-2 text-purple-400"></i>
                                Soal Essay
                            </h5>
                            <button type="button" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-200 flex items-center text-sm" id="addEssay" onclick="addNewQuestion()">
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

                    <!-- Pengaturan Essay -->
                    <div class="mb-6 bg-gray-700 rounded-lg p-6 border border-gray-600">
                        <h5 class="text-lg font-semibold text-white flex items-center mb-4">
                            <i class="fas fa-cog mr-2 text-purple-400"></i>
                            Pengaturan Essay
                        </h5>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="word_count_min" class="block text-sm font-medium text-gray-300 mb-2">
                                    Jumlah Kata Minimum
                                </label>
                                <input type="number" id="word_count_min" name="word_count_min" 
                                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                       value="{{ old('word_count_min', 100) }}" min="0" placeholder="100">
                                @error('word_count_min')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="word_count_max" class="block text-sm font-medium text-gray-300 mb-2">
                                    Jumlah Kata Maksimum
                                </label>
                                <input type="number" id="word_count_max" name="word_count_max" 
                                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                       value="{{ old('word_count_max', 500) }}" min="0" placeholder="500">
                                @error('word_count_max')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="isHidden" value="1" {{ old('isHidden') ? 'checked' : '' }} 
                                       class="rounded border-gray-600 text-purple-600 focus:ring-purple-500 focus:ring-offset-gray-800">
                                <span class="ml-2 text-sm text-gray-300">Simpan sebagai draft (tidak langsung dipublikasikan)</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 mt-8">
                        <button type="button" onclick="history.back()" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </button>
                        <button type="submit" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-200">
                            <i class="fas fa-save mr-2"></i> Simpan Tugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Quill Editor JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    let questionCount = 0;
    let quillEditors = {}; // Store Quill editor instances

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Quill for description
        if (document.getElementById('deskripsi-editor')) {
            const deskripsiEditor = new Quill('#deskripsi-editor', {
                theme: 'snow',
                placeholder: 'Masukkan deskripsi tugas...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'image', 'video'],
                        ['clean']
                    ]
                }
            });
            deskripsiEditor.on('text-change', function() {
                document.getElementById('deskripsi-textarea').value = deskripsiEditor.root.innerHTML;
            });
            // Set initial content if old content exists
            if (document.getElementById('deskripsi-textarea').value) {
                deskripsiEditor.root.innerHTML = document.getElementById('deskripsi-textarea').value;
            }
        }
        
        // Handle form submission - gabungkan kelas_id dan mapel_id menjadi kelas_mapel_id
        const form = document.getElementById('essayForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const kelasId = document.getElementById('kelas_id').value;
                const mapelId = document.getElementById('mapel_id').value;
                
                // Validasi bahwa kedua dropdown sudah dipilih
                if (!kelasId || !mapelId) {
                    e.preventDefault();
                    alert('Kelas Tujuan dan Mata Pelajaran wajib dipilih!');
                    return false;
                }
                
                // Gabungkan kelas_id dan mapel_id menjadi kelas_mapel_id
                // Format: "kelas_id:mapel_id" untuk sementara, nanti controller akan handle
                const kelasMapelId = `${kelasId}:${mapelId}`;
                document.getElementById('kelas_mapel_id').value = kelasMapelId;
                
                console.log('Form submit (Superadmin Essay) - Kelas ID:', kelasId, 'Mapel ID:', mapelId, 'Kombinasi:', kelasMapelId);
            });
        }

        // Restore old essay questions if available
        @if(old('essay_questions'))
            const oldQuestions = @json(old('essay_questions'));
            Object.keys(oldQuestions).forEach(index => {
                addNewQuestion();
                const currentQuestionCount = questionCount;
                const questionData = oldQuestions[index];
                
                const pointsInput = document.querySelector(`input[name="essay_questions[${currentQuestionCount}][points]"]`);
                if (pointsInput) pointsInput.value = questionData.points || 1;
                
                if (questionData.question) {
                    const editorId = `quill-editor-essay-${currentQuestionCount}`;
                    const textareaId = `quill-textarea-essay-${currentQuestionCount}`;
                    
                    if (quillEditors[editorId]) {
                        quillEditors[editorId].root.innerHTML = questionData.question;
                        const textarea = document.getElementById(textareaId);
                        if (textarea) textarea.value = questionData.question;
                    }
                }
            });
        @endif
    });

    // Function to initialize Quill editor for dynamic questions
    function initializeQuillEditor(questionNum, type) {
        const editorId = `quill-editor-${type}-${questionNum}`;
        const textareaId = `quill-textarea-${type}-${questionNum}`;
        
        if (document.getElementById(editorId) && !quillEditors[editorId]) {
            const quill = new Quill(`#${editorId}`, {
                theme: 'snow',
                placeholder: 'Masukkan pertanyaan essay...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'image', 'video'],
                        ['clean']
                    ]
                }
            });
            quill.on('text-change', function() {
                document.getElementById(textareaId).value = quill.root.innerHTML;
            });
            quillEditors[editorId] = quill;
        }
    }

    // Function to add new question (Essay)
    function addNewQuestion() {
        const taskType = document.querySelector('input[name="tipe"]').value;
        questionCount++;

        if (taskType == 2) { // Essay
            addEssayQuestion(questionCount);
        }
    }

    // Function to remove question
    function removeQuestion(button, type, questionNum) {
        button.closest('.question-card').remove();
        delete quillEditors[`quill-editor-${type}-${questionNum}`];
    }

    // Essay Functions
    function addEssayQuestion(questionNum) {
        const container = document.getElementById('essayContainer');
        const questionCard = document.createElement('div');
        questionCard.className = 'question-card bg-gray-800 rounded-lg p-6 mb-4 border border-gray-600';
        questionCard.innerHTML = `
            <div class="flex justify-between items-center mb-4">
                <h6 class="text-lg font-semibold text-white">Soal Essay ${questionNum}</h6>
                <button type="button" class="px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700 transition duration-200" onclick="removeQuestion(this, 'essay', ${questionNum})">
                    <i class="fas fa-trash mr-1"></i> Hapus
                </button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Pertanyaan</label>
                    <div class="bg-gray-700 rounded-lg border border-gray-600 overflow-hidden">
                        <div id="quill-editor-essay-${questionNum}" class="quill-editor-dark" style="height: 150px;"></div>
                        <textarea name="essay_questions[${questionNum}][question]" id="quill-textarea-essay-${questionNum}" style="display: none;"></textarea>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Poin</label>
                    <input type="number" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                           name="essay_questions[${questionNum}][points]" value="1" min="1" step="0.1">
                </div>
            </div>
        `;
        container.appendChild(questionCard);
        initializeQuillEditor(questionNum, 'essay');
    }
</script>

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