@extends('layouts.unified-layout')

@section('title', $title)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $tugas->name }}</h1>
                <p class="mt-2 text-gray-300">{{ $tugas->tipe_tugas }} - {{ $tugas->KelasMapel->Kelas->name }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('teacher.tasks') }}" class="btn btn-outline">
                    <i class="ph-arrow-left mr-2"></i>
                    Kembali
                </a>
                <a href="{{ route('teacher.tasks.edit', $tugas->id) }}" class="btn btn-primary">
                    <i class="ph-pencil mr-2"></i>
                    Edit Tugas
                </a>
            </div>
        </div>
    </div>

    <!-- Task Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Task Info -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Tugas</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div>
                            <label class="info-label">Deskripsi/Instruksi</label>
                            <div class="info-content">
                                {!! nl2br(e($tugas->content)) !!}
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                    @else
                                        Tidak ada tenggat
                                    @endif
                                </div>
                            </div>
                            
                            <div>
                                <label class="info-label">Status</label>
                                <div class="info-content">
                                    <span class="task-status-badge status-{{ strtolower($tugas->status_tugas) }}">
                                        {{ $tugas->status_tugas }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="space-y-6">
            <!-- Progress Stats -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Progres Pengumpulan</h3>
                </div>
                <div class="card-body">
                    @php
                        $totalStudents = $tugas->KelasMapel->Kelas->users()->where('roles_id', 3)->count();
                        $submittedCount = $tugas->TugasProgress()->where('status', 'submitted')->count();
                        $gradedCount = $tugas->TugasProgress()->where('status', 'graded')->count();
                        $progressPercentage = $totalStudents > 0 ? ($submittedCount / $totalStudents) * 100 : 0;
                    @endphp
                    
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-300">Terkumpul</span>
                                <span class="text-white">{{ $submittedCount }}/{{ $totalStudents }}</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $progressPercentage }}%"></div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div>
                                <div class="text-2xl font-bold text-white">{{ $submittedCount }}</div>
                                <div class="text-sm text-gray-400">Terkumpul</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-white">{{ $gradedCount }}</div>
                                <div class="text-sm text-gray-400">Dinilai</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aksi Cepat</h3>
                </div>
                <div class="card-body space-y-3">
                    <button onclick="gradeAll()" class="btn btn-primary w-full">
                        <i class="ph-check-circle mr-2"></i>
                        Nilai Semua
                    </button>
                    
                    <button onclick="exportResults()" class="btn btn-outline w-full">
                        <i class="ph-download mr-2"></i>
                        Export Hasil
                    </button>
                    
                    @if($tugas->tipe == 4)
                        <button onclick="viewPeerAssessment()" class="btn btn-outline w-full">
                            <i class="ph-users mr-2"></i>
                            Penilaian Antar Kelompok
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Students List -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h3 class="card-title">Daftar Siswa</h3>
                <div class="flex items-center space-x-3">
                    <select id="statusFilter" class="form-select" onchange="filterStudents()">
                        <option value="">Semua Status</option>
                        <option value="not_started">Belum Mulai</option>
                        <option value="in_progress">Sedang Mengerjakan</option>
                        <option value="submitted">Sudah Mengumpulkan</option>
                        <option value="graded">Sudah Dinilai</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Siswa
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Progres
th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Nilai
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700" id="studentsTableBody">
                        @foreach($tugas->KelasMapel->Kelas->users()->where('roles_id', 4)->get() as $student)
                            @php
                                $progress = $tugas->TugasProgress()->where('user_id', $student->id)->first();
                                $feedback = $tugas->TugasFeedback()->where('user_id', $student->id)->first();
                            @endphp
                            <tr class="student-row hover:bg-gray-800/50" data-status="{{ $progress->status ?? 'not_started' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-600 flex items-center justify-center">
                                                <span class="text-sm font-medium text-white">
                                                    {{ substr($student->name, 0, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-white">{{ $student->name }}</div>
                                            <div class="text-sm text-gray-400">{{ $student->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <span class="student-status-badge status-{{ $progress->status ?? 'not_started' }}">
                                        {{ ucfirst(str_replace('_', ' ', $progress->status ?? 'not_started')) }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <div class="progress-bar w-20">
                                            <div class="progress-fill" style="width: {{ $progress->progress_percentage ?? 0 }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-300">{{ $progress->progress_percentage ?? 0 }}%</span>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    @if($progress && $progress->final_score !== null)
                                        <div class="text-sm font-medium text-white">{{ $progress->final_score }}</div>
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <button onclick="viewStudentWork({{ $student->id }})" 
                                                class="btn btn-sm btn-outline" title="Lihat Hasil">
                                            <i class="ph-eye"></i>
                                        </button>
                                        
                                        <button onclick="gradeStudent({{ $student->id }})" 
                                                class="btn btn-sm btn-primary" title="Nilai">
                                            <i class="ph-check-circle"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Student Work Modal -->
<div id="studentWorkModal" class="modal hidden">
    <div class="modal-backdrop" onclick="closeStudentWorkModal()"></div>
    <div class="modal-content modal-large">
        <div class="modal-header">
            <h3 class="modal-title">Hasil Kerja Siswa</h3>
            <button onclick="closeStudentWorkModal()" class="modal-close">
                <i class="ph-x"></i>
            </button>
        </div>
        <div class="modal-body">
            <div id="studentWorkContent">
                <!-- Student work will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Grading Modal -->
<div id="gradingModal" class="modal hidden">
    <div class="modal-backdrop" onclick="closeGradingModal()"></div>
    <div class="modal-content modal-large">
        <div class="modal-header">
            <h3 class="modal-title">Penilaian Siswa</h3>
            <button onclick="closeGradingModal()" class="modal-close">
                <i class="ph-x"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="gradingForm" onsubmit="submitGrade(event)">
                <input type="hidden" id="gradingStudentId" name="user_id">
                <input type="hidden" id="gradingIsGroup" name="is_group" value="0">
                <input type="hidden" id="gradingGroupId" name="group_id">
                <input type="hidden" id="gradingTaskType" name="task_type" value="{{ $tugas->tipe }}">
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Student Work Panel -->
                    <div>
                        <h4 class="text-lg font-medium text-white mb-4">Hasil Kerja</h4>
                        <div id="gradingStudentWork" class="bg-gray-800 rounded-lg p-4 min-h-64">
                            <!-- Student work will be loaded here -->
                        </div>
                    </div>
                    
                    <!-- Grading Panel -->
                    <div>
                        <h4 class="text-lg font-medium text-white mb-4">Penilaian</h4>
                        <div class="space-y-4">
                            <!-- Task Type Specific Grading -->
                            @if($tugas->tipe == 1)
                                <!-- Multiple Choice - Read Only with Auto Score -->
                                <div id="multipleChoiceGrading" class="grading-panel">
                                    <div class="bg-blue-900/20 border border-blue-500/30 rounded-lg p-4 mb-4">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <i class="ph-info text-blue-400"></i>
                                            <span class="text-blue-300 font-medium">Tugas Pilihan Ganda</span>
                                        </div>
                                        <p class="text-sm text-blue-200">Nilai otomatis berdasarkan jawaban benar. Anda hanya dapat memberikan feedback.</p>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label">Skor Otomatis</label>
                                        <input type="text" id="gradingAutoScore" class="form-input bg-gray-700" readonly>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label">Feedback (Opsional)</label>
                                        <textarea name="feedback" id="gradingFeedback" 
                                                  class="form-textarea" rows="4" 
                                                  placeholder="Berikan feedback yang konstruktif..."></textarea>
                                    </div>
                                </div>
                            @elseif($tugas->tipe == 2 || $tugas->tipe == 3)
                                <!-- Essay/Individual - Manual Grading -->
                                <div id="essayGrading" class="grading-panel">
                                    <div class="bg-green-900/20 border border-green-500/30 rounded-lg p-4 mb-4">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <i class="ph-pencil text-green-400"></i>
                                            <span class="text-green-300 font-medium">Tugas {{ $tugas->tipe == 2 ? 'Essay' : 'Mandiri' }}</span>
                                        </div>
                                        <p class="text-sm text-green-200">Berikan nilai manual dan feedback untuk hasil kerja siswa.</p>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label">Nilai (0-100) *</label>
                                        <input type="number" name="score" id="gradingScore" 
                                               class="form-input" min="0" max="100" required>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label">Feedback *</label>
                                        <textarea name="feedback" id="gradingFeedback" 
                                                  class="form-textarea" rows="6" 
                                                  placeholder="Berikan feedback yang konstruktif..." required></textarea>
                                    </div>
                                </div>
                            @elseif($tugas->tipe == 4)
                                <!-- Group Task - Group Grading -->
                                <div id="groupGrading" class="grading-panel">
                                    <div class="bg-purple-900/20 border border-purple-500/30 rounded-lg p-4 mb-4">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <i class="ph-users text-purple-400"></i>
                                            <span class="text-purple-300 font-medium">Tugas Kelompok</span>
                                        </div>
                                        <p class="text-sm text-purple-200">Nilai untuk seluruh kelompok. Anggota kelompok akan mendapat nilai yang sama.</p>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label">Nilai Kelompok (0-100) *</label>
                                        <input type="number" name="score" id="gradingScore" 
                                               class="form-input" min="0" max="100" required>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label">Feedback untuk Kelompok *</label>
                                        <textarea name="feedback" id="gradingFeedback" 
                                                  class="form-textarea" rows="6" 
                                                  placeholder="Berikan feedback untuk kerja kelompok..." required></textarea>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-600">
                                <button type="button" onclick="closeGradingModal()" class="btn btn-outline">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ph-check mr-2"></i>
                                    Simpan Nilai
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
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

.student-status-badge {
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

.modal-large {
    max-width: 1200px;
    width: 95%;
}

.student-row.hidden {
    display: none;
}
</style>

<script>
function filterStudents() {
    const status = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('.student-row');
    
    rows.forEach(row => {
        if (!status || row.dataset.status === status) {
            row.classList.remove('hidden');
        } else {
            row.classList.add('hidden');
        }
    });
}

function viewStudentWork(studentId) {
    // Load student work content
    fetch(`/teacher/tasks/{{ $tugas->id }}/student-work/${studentId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('studentWorkContent').innerHTML = data.html;
            document.getElementById('studentWorkModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error loading student work:', error);
            alert('Gagal memuat hasil kerja siswa');
        });
}

function closeStudentWorkModal() {
    document.getElementById('studentWorkModal').classList.add('hidden');
}

function gradeStudent(studentId) {
    // Load student work for grading
    fetch(`/teacher/tasks/{{ $tugas->id }}/student-work/${studentId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('gradingStudentId').value = studentId;
            document.getElementById('gradingStudentWork').innerHTML = data.html;
            
            // Handle different task types
            const taskType = {{ $tugas->tipe }};
            
            if (taskType == 1) {
                // Multiple choice - calculate auto score
                calculateAutoScore(studentId);
            } else if (taskType == 4) {
                // Group task - set group info
                setGroupTaskInfo(studentId);
            }
            
            document.getElementById('gradingModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error loading student work:', error);
            alert('Gagal memuat hasil kerja siswa');
        });
}

function calculateAutoScore(studentId) {
    // Calculate auto score for multiple choice
    fetch(`/teacher/tasks/{{ $tugas->id }}/auto-score/${studentId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('gradingAutoScore').value = data.score + '/100';
        })
        .catch(error => {
            console.error('Error calculating auto score:', error);
            document.getElementById('gradingAutoScore').value = '0/100';
        });
}

function setGroupTaskInfo(studentId) {
    // Set group task information
    document.getElementById('gradingIsGroup').value = '1';
    // You can add more group-specific logic here
}

function closeGradingModal() {
    document.getElementById('gradingModal').classList.add('hidden');
}

function submitGrade(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    
    fetch(`/teacher/tasks/{{ $tugas->id }}/grade`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeGradingModal();
            location.reload(); // Refresh to show updated grades
        } else {
            alert('Gagal menyimpan nilai: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error saving grade:', error);
        alert('Gagal menyimpan nilai');
    });
}

function gradeAll() {
    if (confirm('Apakah Anda yakin ingin menilai semua siswa yang sudah mengumpulkan tugas?')) {
        // Implementation for grading all students
        alert('Fitur ini akan segera tersedia');
    }
}

function exportResults() {
    // Implementation for exporting results
    alert('Fitur export akan segera tersedia');
}

function viewPeerAssessment() {
    // Implementation for peer assessment
    alert('Fitur penilaian antar kelompok akan segera tersedia');
}
</script>
@endsection
