@extends('layouts.unified-layout')

@section('title', $title)

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white tracking-tight">{{ $title }}</h1>
                <p class="mt-2 text-gray-400">Edit tugas {{ strtolower($tipeTugas) }} dan kelola pertanyaan</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="{{ route('teacher.tasks.show', $tugas->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-600 rounded-lg text-sm font-medium text-gray-300 hover:bg-gray-800 hover:text-white transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail
                </a>
            </div>
        </div>

        <form id="taskForm" method="POST" action="{{ route('teacher.tasks.update', $tugas->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('teacher.tasks.edit.partials.messages')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information (Header) -->
                    @include('teacher.tasks.edit.partials.header')

                    <!-- Task Type Specific Content -->
                    @if($tipe == 1)
                        @include('teacher.tasks.edit.multiple-choice')
                    @elseif($tipe == 2 || $tipe == 3)
                        @include('teacher.tasks.edit.essay')
                    @elseif($tipe == 4)
                        @include('teacher.tasks.edit.group')
                    @endif
                </div>

                <!-- Sidebar Actions -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-800 rounded-xl shadow-2xl border border-gray-700 sticky top-24">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center">
                                <i class="fas fa-cogs mr-2 text-gray-400"></i>
                                Aksi & Status
                            </h3>
                            
                            <button type="submit" class="w-full flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 shadow-lg mb-3">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                            
                            <a href="{{ route('teacher.tasks.show', $tugas->id) }}" class="w-full flex justify-center items-center px-6 py-3 border border-gray-600 text-base font-medium rounded-lg text-gray-300 bg-gray-700 hover:bg-gray-600 hover:text-white transition duration-200">
                                <i class="fas fa-times mr-2"></i> Batal
                            </a>
                            
                            <hr class="my-6 border-gray-700">
                            
                            <div class="space-y-3">
                                <div>
                                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Informasi Tugas</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-400">Status:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tugas->status_tugas == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-700 text-gray-300' }}">
                                        {{ ucfirst($tugas->status_tugas) }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-400">Dibuat:</span>
                                    <span class="text-white">{{ $tugas->created_at->format('d M Y') }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-400">Terakhir Update:</span>
                                    <span class="text-white">{{ $tugas->updated_at->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Initialize Global Variables
    let questionCount = {{ $tugas->TugasMultiple ? $tugas->TugasMultiple->count() : 0 }};
    let essayCount = {{ $tugas->TugasMandiri ? $tugas->TugasMandiri->count() : 0 }};
    
    @php
        $taskConfig = json_decode($tugas->content, true);
        $rubricCount = isset($taskConfig['rubric_items']) ? count($taskConfig['rubric_items']) : 0;
    @endphp
    let rubricCount = {{ $rubricCount }};
    
    let quillEditors = {};

    document.addEventListener('DOMContentLoaded', function() {
        // Handle file upload checkbox visibility
        const fileUploadCheckbox = document.querySelector('input[name="allow_file_upload"]');
        const fileTypesContainer = document.getElementById('fileTypesContainer');
        
        if (fileUploadCheckbox && fileTypesContainer) {
            fileUploadCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    fileTypesContainer.classList.remove('hidden');
                    fileTypesContainer.classList.add('animate-fade-in-down');
                } else {
                    fileTypesContainer.classList.add('hidden');
                }
            });
        }
        
        // Initialize existing editors with a slight delay to ensure DOM is ready
        setTimeout(initializeExistingQuillEditors, 100);
    });

    // Image Compression Helper
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

    // Quill Initialization Logic
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
        
        // Initialize Description Editor
        // Note: The main description typically uses a simpler editor or just a textarea, 
        // but if we want to use Quill for it, we'd need to target it specifically.
        // For now, it seems the partial header uses a standard textarea.
    }

    // --- Specific Editor Initializers ---

    function initializeQuillEditors(questionNum) {
        // Question Editor
        const questionEditorId = `quill-editor-question-${questionNum}`;
        const questionTextareaId = `quill-textarea-question-${questionNum}`;
        
        if (document.getElementById(questionEditorId)) {
            setupQuill(questionEditorId, questionTextareaId, 'Pertanyaan...');
        }
        
        // Option Editors (A-E)
        ['A', 'B', 'C', 'D', 'E'].forEach(option => {
            const optionEditorId = `quill-editor-option-${option}-${questionNum}`;
            const optionTextareaId = `quill-textarea-option-${option}-${questionNum}`;
            
            if (document.getElementById(optionEditorId)) {
                setupQuill(optionEditorId, optionTextareaId, `Pilihan ${option}...`, true);
            }
        });
    }

    function initializeEssayQuillEditor(questionNum) {
        const editorId = `quill-editor-essay-${questionNum}`;
        const textareaId = `quill-textarea-essay-${questionNum}`;
        
        if (document.getElementById(editorId)) {
            setupQuill(editorId, textareaId, 'Tulis pertanyaan essay...');
        }
    }

    function setupQuill(editorId, textareaId, placeholder, isSimple = false) {
        if (quillEditors[editorId]) return; // Prevent double init

        const isMobile = window.innerWidth <= 768;
        
        let toolbarOptions;
        if (isSimple) {
            toolbarOptions = [
                ['bold', 'italic', 'underline'],
                ['image'] // Allow images in options too
            ];
        } else {
            toolbarOptions = isMobile ? [
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'image'],
                ['clean']
            ] : [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['link', 'image'], // Video removed for simplicity unless needed
                ['clean']
            ];
        }

        const quill = new Quill(`#${editorId}`, {
            theme: 'snow',
            modules: { toolbar: toolbarOptions },
            placeholder: placeholder
        });
        
        quillEditors[editorId] = quill;
        
        // Set initial content
        const textarea = document.getElementById(textareaId);
        if (textarea && textarea.value) {
            // Check if content is HTML or plain text, Quill handles HTML
            quill.root.innerHTML = textarea.value;
        }
        
        // Sync changes to textarea
        quill.on('text-change', function() {
            textarea.value = quill.root.innerHTML;
        });
        
        // Image Handler
        quill.getModule('toolbar').addHandler('image', async function() {
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
                        const range = quill.getSelection(true);
                        quill.insertEmbed(range.index, 'image', e.target.result);
                        quill.setSelection(range.index + 1);
                    };
                    reader.readAsDataURL(compressedFile);
                }
            };
        });
    }

    // --- Dynamic Form Actions ---

    // 1. Multiple Choice
    function addQuestion() {
        questionCount++;
        const container = document.getElementById('questionsContainer');
        const questionHTML = `
            <div class="bg-gray-750 rounded-xl border border-gray-600 p-6 transition-all duration-200 hover:border-purple-500 animate-fade-in-up" id="question-${questionCount}">
                <div class="flex justify-between items-center mb-6 border-b border-gray-700 pb-4">
                    <h4 class="text-lg font-bold text-white flex items-center">
                        <span class="bg-purple-600 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm mr-3">${questionCount}</span>
                        Soal #${questionCount}
                    </h4>
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center bg-gray-700 rounded-lg px-3 py-1">
                            <span class="text-gray-300 text-sm mr-2">Poin:</span>
                            <input type="number" name="questions[${questionCount}][points]" 
                                   class="w-16 bg-transparent border-none text-white text-right focus:ring-0 p-0" 
                                   value="10" min="1" required>
                        </div>
                        <button type="button" onclick="removeQuestion(${questionCount})" class="text-red-400 hover:text-red-300 transition duration-200 p-2 rounded-full hover:bg-red-400/10">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
                
                <div class="space-y-6">
                    <!-- Question Editor -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Pertanyaan</label>
                        <div class="bg-gray-900 rounded-lg border border-gray-700 overflow-hidden">
                            <div id="quill-editor-question-${questionCount}" class="quill-editor-dark h-32"></div>
                            <textarea name="questions[${questionCount}][question]" id="quill-textarea-question-${questionCount}" class="hidden" required></textarea>
                        </div>
                    </div>
                    
                    <!-- Options -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-3">Pilihan Jawaban</label>
                        <div id="options-${questionCount}" class="space-y-4">
                            ${['a', 'b', 'c', 'd'].map(key => `
                                <div class="flex items-start space-x-3 group">
                                    <div class="pt-3">
                                        <input type="radio" name="questions[${questionCount}][correct_answer]" 
                                               value="${key}" 
                                               class="w-5 h-5 text-purple-600 bg-gray-700 border-gray-600 focus:ring-purple-500 cursor-pointer" required>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center mb-1">
                                            <span class="text-xs font-semibold text-gray-400 uppercase w-6">${key}</span>
                                        </div>
                                        <div class="bg-gray-900 rounded-lg border border-gray-700 overflow-hidden group-hover:border-purple-500/50 transition duration-200">
                                            <div id="quill-editor-option-${key.toUpperCase()}-${questionCount}" class="quill-editor-dark h-16"></div>
                                            <textarea name="questions[${questionCount}][options][${key}]" 
                                                      id="quill-textarea-option-${key.toUpperCase()}-${questionCount}" 
                                                      class="hidden" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                        <button type="button" onclick="addOption(${questionCount})" class="mt-4 text-sm text-purple-400 hover:text-purple-300 font-medium flex items-center">
                            <i class="fas fa-plus-circle mr-2"></i> Tambah Pilihan
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', questionHTML);
        initializeQuillEditors(questionCount);
    }

    function removeQuestion(id) {
        if (confirm('Apakah Anda yakin ingin menghapus soal ini?')) {
            const element = document.getElementById(`question-${id}`);
            if (element) element.remove();
        }
    }

    function addOption(qId) {
        // Logic to add 'E' or more options if needed
        // For simplicity, reusing a basic check or just appending
        const optionsDiv = document.getElementById(`options-${qId}`);
        const currentOptions = optionsDiv.children.length;
        const nextKey = String.fromCharCode(97 + currentOptions); // a=97
        
        if (currentOptions >= 5) {
            alert('Maksimal 5 pilihan jawaban');
            return;
        }

        const optionHTML = `
            <div class="flex items-start space-x-3 group animate-fade-in">
                <div class="pt-3">
                    <input type="radio" name="questions[${qId}][correct_answer]" 
                           value="${nextKey}" 
                           class="w-5 h-5 text-purple-600 bg-gray-700 border-gray-600 focus:ring-purple-500 cursor-pointer" required>
                </div>
                <div class="flex-1">
                    <div class="flex items-center mb-1">
                        <span class="text-xs font-semibold text-gray-400 uppercase w-6">${nextKey}</span>
                    </div>
                    <div class="bg-gray-900 rounded-lg border border-gray-700 overflow-hidden group-hover:border-purple-500/50 transition duration-200">
                        <div id="quill-editor-option-${nextKey.toUpperCase()}-${qId}" class="quill-editor-dark h-16"></div>
                        <textarea name="questions[${qId}][options][${nextKey}]" 
                                  id="quill-textarea-option-${nextKey.toUpperCase()}-${qId}" 
                                  class="hidden" required></textarea>
                    </div>
                </div>
            </div>
        `;
        optionsDiv.insertAdjacentHTML('beforeend', optionHTML);
        
        // Init editor for new option
        const optionEditorId = `quill-editor-option-${nextKey.toUpperCase()}-${qId}`;
        const optionTextareaId = `quill-textarea-option-${nextKey.toUpperCase()}-${qId}`;
        setTimeout(() => {
            setupQuill(optionEditorId, optionTextareaId, `Pilihan ${nextKey.toUpperCase()}...`, true);
        }, 50);
    }

    // 2. Essay
    function addEssayQuestion() {
        essayCount++;
        const container = document.getElementById('essayQuestionsContainer');
        const questionHTML = `
            <div class="bg-gray-750 rounded-xl border border-gray-600 p-6 transition-all duration-200 hover:border-blue-500 animate-fade-in-up" id="essay-question-${essayCount}">
                <div class="flex justify-between items-center mb-6 border-b border-gray-700 pb-4">
                    <h4 class="text-lg font-bold text-white flex items-center">
                        <span class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm mr-3">${essayCount}</span>
                        Soal #${essayCount}
                    </h4>
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center bg-gray-700 rounded-lg px-3 py-1">
                            <span class="text-gray-300 text-sm mr-2">Poin:</span>
                            <input type="number" name="essay_questions[${essayCount}][points]" 
                                   class="w-16 bg-transparent border-none text-white text-right focus:ring-0 p-0" 
                                   value="10" min="1" max="100" required>
                        </div>
                        <button type="button" onclick="removeEssayQuestion(${essayCount})" class="text-red-400 hover:text-red-300 transition duration-200 p-2 rounded-full hover:bg-red-400/10">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Question Editor -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Pertanyaan</label>
                    <div class="bg-gray-900 rounded-lg border border-gray-700 overflow-hidden">
                        <div id="quill-editor-essay-${essayCount}" class="quill-editor-dark h-32"></div>
                        <textarea name="essay_questions[${essayCount}][question]" id="quill-textarea-essay-${essayCount}" class="hidden" required></textarea>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', questionHTML);
        
        // Remove "Belum ada soal" alert if likely present (optional check)
        
        initializeEssayQuillEditor(essayCount);
    }

    function removeEssayQuestion(id) {
        if (confirm('Apakah Anda yakin ingin menghapus soal ini?')) {
            const element = document.getElementById(`essay-question-${id}`);
            if (element) element.remove();
        }
    }

    // 3. Group / Rubric
    function addRubricItem() {
        rubricCount++;
        const container = document.getElementById('rubricContainer');
        const itemHTML = `
            <div class="bg-gray-750 rounded-xl border border-gray-600 p-4 transition-all duration-200 hover:border-green-500 animate-fade-in-up" id="rubric-${rubricCount}">
                <div class="flex justify-between items-center mb-4 border-b border-gray-700 pb-2">
                    <h5 class="text-white font-medium">Item Penilaian #${rubricCount}</h5>
                    <button type="button" onclick="removeRubricItem(${rubricCount})" class="text-red-400 hover:text-red-300 transition duration-200">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-400 mb-1">Kriteria Penilaian</label>
                        <input type="text" name="rubric_items[${rubricCount}][item]" 
                               class="w-full px-3 py-2 bg-gray-900 border border-gray-700 text-white rounded-lg focus:ring-1 focus:ring-green-500 transition duration-200" 
                               placeholder="Contoh: Kerjasama Tim" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1">Tipe Jawaban</label>
                        <select name="rubric_items[${rubricCount}][type]" 
                                class="w-full px-3 py-2 bg-gray-900 border border-gray-700 text-white rounded-lg focus:ring-1 focus:ring-green-500 transition duration-200" 
                                onchange="updateRubricType(${rubricCount})" required>
                            <option value="yes_no">Ya/Tidak</option>
                            <option value="scale">Skala (Sgt Baik - Kurang)</option>
                            <option value="text">Teks Bebas</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1">Poin Maksimal</label>
                        <input type="number" name="rubric_items[${rubricCount}][points]" 
                               class="w-full px-3 py-2 bg-gray-900 border border-gray-700 text-white rounded-lg focus:ring-1 focus:ring-green-500 transition duration-200" 
                               value="10" min="0" required>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', itemHTML);
    }

    function removeRubricItem(id) {
        if (confirm('Hapus item penilaian ini?')) {
            const element = document.getElementById(`rubric-${id}`);
            if (element) element.remove();
        }
    }

    function updateRubricType(id) {
        // Placeholder for future logic if type change affects other fields
    }

</script>

<style>
/* Animations and Custom Utilities */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translate3d(0, 20px, 0);
    }
    to {
        opacity: 1;
        transform: none;
    }
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translate3d(0, -20px, 0);
    }
    to {
        opacity: 1;
        transform: none;
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.3s ease-out;
}

.animate-fade-in-down {
    animation: fadeInDown 0.3s ease-out;
}

/* Quill Dark Theme Overrides */
.quill-editor-dark .ql-toolbar {
    background: #1f2937; /* gray-800 */
    border-color: #374151; /* gray-700 */
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
}

.quill-editor-dark .ql-container {
    background: #111827; /* gray-900 */
    border-color: #374151; /* gray-700 */
    border-bottom-left-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
    color: #f3f4f6; /* gray-100 */
}

.quill-editor-dark .ql-picker {
    color: #e5e7eb;
}

.quill-editor-dark .ql-stroke {
    stroke: #e5e7eb;
}

.quill-editor-dark .ql-fill {
    fill: #e5e7eb;
}
</style>
@endsection
