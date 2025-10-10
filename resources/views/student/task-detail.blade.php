@extends('layouts.unified-layout')

@section('title', $tugas->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $tugas->name }}</h1>
                <p class="mt-2 text-gray-300">{{ $tugas->tipe_tugas }} - {{ $tugas->KelasMapel->Mapel->name ?? 'N/A' }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('student.tasks') }}" class="btn btn-outline">
                    <i class="ph-arrow-left mr-2"></i>
                    Kembali ke Daftar Tugas
                </a>
            </div>
        </div>
    </div>

    <!-- Task Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Task Details -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Instruksi Tugas</h3>
                </div>
                <div class="card-body">
                    <div class="prose prose-invert max-w-none">
                        {!! nl2br(e($tugas->content)) !!}
                    </div>
                </div>
            </div>

            <!-- Task Type Specific Content -->
            @if($tugas->tipe == 1)
                <!-- Multiple Choice Questions -->
                <div class="card mt-6">
                    <div class="card-header">
                        <h3 class="card-title">Soal Pilihan Ganda</h3>
                    </div>
                    <div class="card-body">
                        <form id="multipleChoiceForm" onsubmit="submitMultipleChoice(event)">
                            @csrf
                            <input type="hidden" name="tugas_id" value="{{ $tugas->id }}">
                            
                            <div class="space-y-6">
                                @foreach($tugas->TugasQuiz as $index => $question)
                                    <div class="question-item">
                                        <div class="question-header">
                                            <h4 class="text-lg font-medium text-white">
                                                Soal {{ $index + 1 }} ({{ $question->points }} poin)
                                            </h4>
                                        </div>
                                        <div class="question-content">
                                            <p class="text-gray-300 mb-4">{{ $question->question }}</p>
                                            
                                            <div class="space-y-2">
                                                @foreach($question->TugasMultiple as $optionIndex => $option)
                                                    <label class="option-item">
                                                        <input type="radio" name="answers[{{ $question->id }}]" 
                                                               value="{{ $option->id }}" 
                                                               {{ $progress && $progress->answers && isset($progress->answers[$question->id]) && $progress->answers[$question->id] == $option->id ? 'checked' : '' }}>
                                                        <span class="option-text">{{ $option->option }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="mt-8 flex justify-end space-x-4">
                                <button type="button" onclick="saveDraft()" class="btn btn-outline">
                                    <i class="ph-floppy-disk mr-2"></i>
                                    Simpan Draft
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ph-check mr-2"></i>
                                    Submit Jawaban
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            @elseif($tugas->tipe == 2 || $tugas->tipe == 3)
                <!-- Essay/Individual Task -->
                <div class="card mt-6">
                    <div class="card-header">
                        <h3 class="card-title">Kerjakan Tugas</h3>
                    </div>
                    <div class="card-body">
                        @php
                            $taskConfig = json_decode($tugas->content, true);
                        @endphp
                        
                        <form id="essayForm" onsubmit="submitEssay(event)" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="tugas_id" value="{{ $tugas->id }}">
                            
                            <div class="space-y-6">
                                @if($taskConfig['allow_text_input'] ?? true)
                                    <div>
                                        <label class="form-label">Jawaban Anda</label>
                                        <textarea name="answer_text" id="answerText" class="form-textarea" rows="10" 
                                                  placeholder="Tuliskan jawaban Anda di sini...">{{ $progress->notes ?? '' }}</textarea>
                                    </div>
                                @endif
                                
                                @if($taskConfig['allow_file_upload'] ?? false)
                                    <div>
                                        <label class="form-label">Upload File</label>
                                        <div class="file-upload-area" onclick="document.getElementById('fileInput').click()">
                                            <input type="file" id="fileInput" name="files[]" multiple 
                                                   accept=".{{ implode(',.', $taskConfig['file_types'] ?? ['pdf', 'docx', 'jpg', 'png']) }}"
                                                   class="hidden">
                                            <div class="file-upload-content">
                                                <i class="ph-upload text-4xl text-gray-400 mb-2"></i>
                                                <p class="text-gray-300">Klik untuk upload file</p>
                                                <p class="text-sm text-gray-400">
                                                    Format yang diizinkan: {{ implode(', ', $taskConfig['file_types'] ?? ['pdf', 'docx', 'jpg', 'png']) }}
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <!-- Uploaded Files List -->
                                        <div id="uploadedFiles" class="mt-4 space-y-2">
                                            @if($progress && $progress->files)
                                                @foreach($progress->files as $file)
                                                    <div class="uploaded-file-item">
                                                        <i class="ph-file text-blue-400"></i>
                                                        <span class="text-white">{{ $file['name'] }}</span>
                                                        <button type="button" onclick="removeFile(this)" class="text-red-400 hover:text-red-300">
                                                            <i class="ph-trash"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mt-8 flex justify-end space-x-4">
                                <button type="button" onclick="saveDraft()" class="btn btn-outline">
                                    <i class="ph-floppy-disk mr-2"></i>
                                    Simpan Draft
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ph-check mr-2"></i>
                                    Submit Tugas
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            @elseif($tugas->tipe == 4)
                <!-- Group Task -->
                <div class="card mt-6">
                    <div class="card-header">
                        <h3 class="card-title">Tugas Kelompok</h3>
                    </div>
                    <div class="card-body">
                        @php
                            $userGroup = $tugas->TugasKelompok()->whereHas('AnggotaTugasKelompok', function($query) {
                                $query->where('user_id', auth()->id());
                            })->first();
                        @endphp
                        
                        @if($userGroup)
                            <div class="mb-6">
                                <h4 class="text-lg font-medium text-white mb-2">Kelompok Anda</h4>
                                <div class="bg-gray-800 rounded-lg p-4">
                                    <div class="flex justify-between items-center mb-3">
                                        <h5 class="text-white font-medium">{{ $userGroup->name }}</h5>
                                        @if($userGroup->AnggotaTugasKelompok()->where('user_id', auth()->id())->first()->is_leader)
                                            <span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 rounded text-sm">
                                                <i class="ph-crown mr-1"></i>
                                                Ketua Kelompok
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <h6 class="text-gray-300 font-medium">Anggota Kelompok:</h6>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($userGroup->AnggotaTugasKelompok as $member)
                                                <span class="px-2 py-1 bg-gray-700 rounded text-sm text-white">
                                                    {{ $member->User->name }}
                                                    @if($member->is_leader)
                                                        <i class="ph-crown text-yellow-400 ml-1"></i>
                                                    @endif
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @if($userGroup->AnggotaTugasKelompok()->where('user_id', auth()->id())->first()->is_leader)
                                <!-- Group Task Form (Only for group leader) -->
                                <form id="groupForm" onsubmit="submitGroupTask(event)" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="tugas_id" value="{{ $tugas->id }}">
                                    <input type="hidden" name="group_id" value="{{ $userGroup->id }}">
                                    
                                    <div class="space-y-6">
                                        <div>
                                            <label class="form-label">Upload File Kelompok</label>
                                            <div class="file-upload-area" onclick="document.getElementById('groupFileInput').click()">
                                                <input type="file" id="groupFileInput" name="files[]" multiple 
                                                       accept=".pdf,.docx,.jpg,.png" class="hidden">
                                                <div class="file-upload-content">
                                                    <i class="ph-upload text-4xl text-gray-400 mb-2"></i>
                                                    <p class="text-gray-300">Klik untuk upload file kelompok</p>
                                                </div>
                                            </div>
                                            
                                            <!-- Group Files List -->
                                            <div id="groupFiles" class="mt-4 space-y-2">
                                                @foreach($userGroup->fileKelompok as $file)
                                                    <div class="uploaded-file-item">
                                                        <i class="ph-file text-blue-400"></i>
                                                        <span class="text-white">{{ $file->filename }}</span>
                                                        <a href="{{ Storage::url($file->file_path) }}" 
                                                           class="text-blue-400 hover:text-blue-300" target="_blank">
                                                            <i class="ph-download"></i>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <label class="form-label">Catatan Kelompok</label>
                                            <textarea name="group_notes" class="form-textarea" rows="4" 
                                                      placeholder="Tuliskan catatan atau penjelasan tambahan...">{{ $userGroup->notes ?? '' }}</textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-8 flex justify-end space-x-4">
                                        <button type="button" onclick="saveGroupDraft()" class="btn btn-outline">
                                            <i class="ph-floppy-disk mr-2"></i>
                                            Simpan Draft
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ph-check mr-2"></i>
                                            Submit Tugas Kelompok
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="text-center py-8 text-gray-400">
                                    <i class="ph-users text-4xl mb-2"></i>
                                    <p>Hanya ketua kelompok yang dapat submit tugas</p>
                                    <p class="text-sm">Hubungi ketua kelompok untuk mengumpulkan tugas</p>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-8 text-gray-400">
                                <i class="ph-warning text-4xl mb-2"></i>
                                <p>Anda belum terdaftar dalam kelompok</p>
                                <p class="text-sm">Hubungi guru untuk informasi lebih lanjut</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Task Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Tugas</h3>
                </div>
                <div class="card-body space-y-4">
                    <div>
                        <label class="info-label">Mata Pelajaran</label>
                        <div class="info-content">{{ $tugas->KelasMapel->Mapel->name ?? 'N/A' }}</div>
                    </div>
                    
                    <div>
                        <label class="info-label">Kelas</label>
                        <div class="info-content">{{ $tugas->KelasMapel->Kelas->name }}</div>
                    </div>
                    
                    <div>
                        <label class="info-label">Tanggal Tenggat</label>
                        <div class="info-content">
                            @if($tugas->due)
                                {{ $tugas->due->format('d M Y, H:i') }}
                                @if($tugas->due->isPast())
                                    <span class="text-red-400 ml-2">(Terlambat)</span>
                                @endif
                            @else
                                Tidak ada tenggat
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <label class="info-label">Status</label>
                        <div class="info-content">
                            <span class="task-status-badge status-{{ strtolower($progress->status ?? 'not_started') }}">
                                {{ ucfirst(str_replace('_', ' ', $progress->status ?? 'not_started')) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress -->
            @if($progress)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Progres</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-300">Progres</span>
                                    <span class="text-white">{{ $progress->progress_percentage ?? 0 }}%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $progress->progress_percentage ?? 0 }}%"></div>
                                </div>
                            </div>
                            
                            @if($progress->started_at)
                                <div>
                                    <label class="text-sm text-gray-400">Mulai</label>
                                    <div class="text-white">{{ $progress->started_at->format('d M Y, H:i') }}</div>
                                </div>
                            @endif
                            
                            @if($progress->submitted_at)
                                <div>
                                    <label class="text-sm text-gray-400">Dikumpulkan</label>
                                    <div class="text-white">{{ $progress->submitted_at->format('d M Y, H:i') }}</div>
                                </div>
                            @endif
                            
                            @if($progress->final_score !== null)
                                <div>
                                    <label class="text-sm text-gray-400">Nilai</label>
                                    <div class="text-white font-bold text-lg">{{ $progress->final_score }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
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

.info-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: #94a3b8;
    margin-bottom: 0.5rem;
}

.info-content {
    color: white;
    font-size: 0.875rem;
}

.question-item {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 1.5rem;
}

.option-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.option-item:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(59, 130, 246, 0.5);
}

.option-item input[type="radio"] {
    margin-right: 0.75rem;
}

.option-item input[type="radio"]:checked + .option-text {
    color: #3b82f6;
    font-weight: 500;
}

.file-upload-area {
    border: 2px dashed rgba(255, 255, 255, 0.3);
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.file-upload-area:hover {
    border-color: #3b82f6;
    background: rgba(59, 130, 246, 0.05);
}

.uploaded-file-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 6px;
}

.task-status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-not_started {
    background: rgba(156, 163, 175, 0.2);
    color: #9ca3af;
}

.status-in_progress {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
}

.status-submitted {
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
}

.status-graded {
    background: rgba(168, 85, 247, 0.2);
    color: #a855f7;
}

.progress-bar {
    width: 100%;
    height: 4px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 2px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #3b82f6, #1d4ed8);
    transition: width 0.3s ease;
}
</style>

<script>
// Multiple Choice Functions
function submitMultipleChoice(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    
    fetch('{{ route("student.tasks.submit", $tugas->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal mengirim jawaban: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal mengirim jawaban');
    });
}

// Essay Functions
function submitEssay(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    
    fetch('{{ route("student.tasks.submit", $tugas->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal mengirim tugas: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal mengirim tugas');
    });
}

// Group Task Functions
function submitGroupTask(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    
    fetch('{{ route("student.tasks.submit", $tugas->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal mengirim tugas kelompok: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal mengirim tugas kelompok');
    });
}

// File Upload Functions
document.getElementById('fileInput')?.addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const container = document.getElementById('uploadedFiles');
    
    files.forEach(file => {
        const fileItem = document.createElement('div');
        fileItem.className = 'uploaded-file-item';
        fileItem.innerHTML = `
            <i class="ph-file text-blue-400"></i>
            <span class="text-white">${file.name}</span>
            <button type="button" onclick="removeFile(this)" class="text-red-400 hover:text-red-300">
                <i class="ph-trash"></i>
            </button>
        `;
        container.appendChild(fileItem);
    });
});

function removeFile(button) {
    button.parentElement.remove();
}

// Draft Functions
function saveDraft() {
    // Implementation for saving draft
    alert('Fitur simpan draft akan segera tersedia');
}

function saveGroupDraft() {
    // Implementation for saving group draft
    alert('Fitur simpan draft kelompok akan segera tersedia');
}
</script>
@endsection
