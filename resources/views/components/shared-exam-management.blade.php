@props([
    'user' => null,
    'exams' => [],
    'classes' => [],
    'subjects' => [],
    'filters' => [],
    'totalExams' => 0,
    'activeExams' => 0,
    'completedExams' => 0,
    'totalParticipants' => 0,
    'userRole' => 'teacher'
])

<style>
.exam-filters {
    background-color: #1e293b;
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid #334155;
}

.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-group {
    margin-bottom: 1rem;
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
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: #475569;
}

.btn-success {
    background: #10b981;
    color: #ffffff;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-success:hover {
    background: #059669;
}

.btn-warning {
    background: #f59e0b;
    color: #ffffff;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-warning:hover {
    background: #d97706;
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

.exams-table {
    background-color: #1e293b;
    border-radius: 1rem;
    padding: 2rem;
    border: 1px solid #334155;
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th,
.table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #334155;
}

.table th {
    background-color: #2a2a3e;
    color: #ffffff;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.table td {
    color: #cbd5e1;
}

.table tbody tr:hover {
    background-color: #2a2a3e;
}

.exam-title {
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 0.25rem;
}

.exam-description {
    color: #94a3b8;
    font-size: 0.875rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-active {
    background-color: #10b981;
    color: #ffffff;
}

.status-draft {
    background-color: #f59e0b;
    color: #ffffff;
}

.status-completed {
    background-color: #6b7280;
    color: #ffffff;
}

.type-badge {
    background-color: #3b82f6;
    color: #ffffff;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 600;
}

.exam-actions {
    display: flex;
    gap: 0.5rem;
}

.create-exam-form {
    background-color: #1e293b;
    border-radius: 1rem;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid #334155;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background-color: #1e293b;
    border-radius: 1rem;
    padding: 1.5rem;
    border: 1px solid #334155;
    text-align: center;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #94a3b8;
    font-size: 0.875rem;
}

/* Exam Type Cards - Consistent with Task Management */
.exam-type-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}

.exam-type-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 1rem;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.exam-type-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    border-color: #667eea;
}

.exam-type-card.multiple-choice {
    background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 100%);
}

.exam-type-card.multiple-choice:hover {
    box-shadow: 0 10px 25px rgba(139, 92, 246, 0.3);
    border-color: #8b5cf6;
}

.exam-type-card.essay {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.exam-type-card.essay:hover {
    box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
    border-color: #10b981;
}

.exam-type-card.mixed {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.exam-type-card.mixed:hover {
    box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3);
    border-color: #f59e0b;
}

.exam-type-icon {
    font-size: 3rem;
    color: #ffffff;
    margin-bottom: 1rem;
}

.exam-type-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 0.5rem;
}

.exam-type-description {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.875rem;
}

@media (max-width: 768px) {
    .filter-row {
        grid-template-columns: 1fr;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .exam-type-cards {
        grid-template-columns: 1fr;
    }
    
    .exam-actions {
        flex-direction: column;
    }
}
</style>

<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-bullseye"></i>
        Manajemen Ujian
    </h1>
    <p class="page-description">Kelola ujian dan evaluasi pembelajaran siswa</p>
</div>

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $totalExams ?? 0 }}</div>
        <div class="stat-label">Total Ujian</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $activeExams ?? 0 }}</div>
        <div class="stat-label">Ujian Aktif</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $completedExams ?? 0 }}</div>
        <div class="stat-label">Ujian Selesai</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $totalParticipants ?? 0 }}</div>
        <div class="stat-label">Total Peserta</div>
    </div>
</div>

<!-- Exam Type Cards -->
<div class="exam-type-cards">
    <div class="exam-type-card" onclick="createMultipleChoiceExam()">
        <div class="exam-type-icon">
            <i class="fas fa-list-ul"></i>
        </div>
        <div class="exam-type-title">Pilihan Ganda</div>
        <div class="exam-type-description">Buat ujian dengan soal pilihan ganda</div>
    </div>
    <div class="exam-type-card essay" onclick="createEssayExam()">
        <div class="exam-type-icon">
            <i class="fas fa-pen-fancy"></i>
        </div>
        <div class="exam-type-title">Essay</div>
        <div class="exam-type-description">Buat ujian dengan soal essay</div>
    </div>
    <div class="exam-type-card mixed" onclick="createMixedExam()">
        <div class="exam-type-icon">
            <i class="fas fa-layer-group"></i>
        </div>
        <div class="exam-type-title">Campuran</div>
        <div class="exam-type-description">Buat ujian dengan soal campuran</div>
    </div>
</div>

<!-- Create Exam Form -->
<div class="system-info">
    <div class="info-section">
        <h3 class="info-title">
            <i class="ph-plus"></i> Buat Ujian Baru
        </h3>
    
    <form action="{{ 
        $userRole === 'superadmin' ? route('superadmin.exam-management.create') : 
        ($userRole === 'admin' ? route('superadmin.exam-management.create') : route('teacher.exam-management.create')) 
    }}" method="POST">
        @csrf
        
        <div class="form-row">
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
        </div>

        <div class="form-row">
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
                <label for="exam_type">Tipe Ujian</label>
                <select id="exam_type" name="exam_type" required>
                    <option value="">Pilih tipe ujian</option>
                    <option value="multiple_choice">Pilihan Ganda</option>
                    <option value="essay">Essay</option>
                    <option value="mixed">Campuran</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="duration">Durasi (menit)</label>
                <input type="number" id="duration" name="duration" placeholder="120" min="1" max="300" required>
            </div>
            
            <div class="form-group">
                <label for="max_score">Nilai Maksimal</label>
                <input type="number" id="max_score" name="max_score" placeholder="100" min="1" max="100" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="due_date">Tanggal Ujian</label>
                <input type="datetime-local" id="due_date" name="due_date" required>
            </div>
            
            <div class="form-group">
                <label for="is_hidden">Status</label>
                <select id="is_hidden" name="is_hidden" required>
                    <option value="0">Publikasikan</option>
                    <option value="1">Simpan sebagai Draft</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="exam_description">Deskripsi Ujian</label>
            <textarea id="exam_description" name="exam_description" placeholder="Masukkan deskripsi ujian yang detail" required></textarea>
        </div>

        <button type="submit" class="btn-primary">
            <i class="fas fa-plus"></i>
            Buat Ujian
        </button>
    </form>
</div>

<!-- Exam Filters -->
<div class="exam-filters">
    <h2 style="color: #ffffff; margin-bottom: 1.5rem; font-size: 1.25rem;">
        <i class="fas fa-filter me-2"></i>Filter Ujian
    </h2>
    
    <form action="{{ 
        $userRole === 'superadmin' ? route('superadmin.exam-management.filter') : 
        ($userRole === 'admin' ? route('superadmin.exam-management.filter') : route('teacher.exam-management.filter')) 
    }}" method="GET">
        <div class="filter-row">
            <div class="form-group">
                <label for="filter_class">Kelas</label>
                <select id="filter_class" name="filter_class">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ (isset($filters['filter_class']) && $filters['filter_class'] == $class->id) ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label for="filter_subject">Mata Pelajaran</label>
                <select id="filter_subject" name="filter_subject">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ (isset($filters['filter_subject']) && $filters['filter_subject'] == $subject->id) ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label for="filter_status">Status</label>
                <select id="filter_status" name="filter_status">
                    <option value="">Semua Status</option>
                    <option value="active" {{ (isset($filters['filter_status']) && $filters['filter_status'] == 'active') ? 'selected' : '' }}>Aktif</option>
                    <option value="draft" {{ (isset($filters['filter_status']) && $filters['filter_status'] == 'draft') ? 'selected' : '' }}>Draft</option>
                    <option value="completed" {{ (isset($filters['filter_status']) && $filters['filter_status'] == 'completed') ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="filter_type">Tipe Ujian</label>
                <select id="filter_type" name="filter_type">
                    <option value="">Semua Tipe</option>
                    <option value="multiple_choice" {{ (isset($filters['filter_type']) && $filters['filter_type'] == 'multiple_choice') ? 'selected' : '' }}>Pilihan Ganda</option>
                    <option value="essay" {{ (isset($filters['filter_type']) && $filters['filter_type'] == 'essay') ? 'selected' : '' }}>Essay</option>
                    <option value="mixed" {{ (isset($filters['filter_type']) && $filters['filter_type'] == 'mixed') ? 'selected' : '' }}>Campuran</option>
                </select>
            </div>
        </div>
        
        <div class="filter-actions" style="display: flex; gap: 1rem; margin-top: 1rem;">
            <button type="submit" class="btn-primary">
                <i class="fas fa-search"></i>
                Terapkan Filter
            </button>
            <a href="{{ 
                $userRole === 'superadmin' ? route('superadmin.exam-management') : 
                ($userRole === 'admin' ? route('superadmin.exam-management') : route('teacher.exam-management')) 
            }}" class="btn-secondary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-times"></i>
                Reset Filter
            </a>
        </div>
    </form>
</div>

<!-- Exams Table -->
<div class="exams-table">
    <h2 style="color: #ffffff; margin-bottom: 1.5rem; font-size: 1.25rem;">
        <i class="fas fa-list me-2"></i>Daftar Ujian
    </h2>
    
    <table class="table">
        <thead>
            <tr>
                <th>Judul Ujian</th>
                <th>Kelas</th>
                <th>Mata Pelajaran</th>
                <th>Tipe</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($exams as $exam)
                <tr>
                    <td>
                        <div class="exam-title">{{ $exam->name ?? 'Ujian' }}</div>
                        <div class="exam-description">{{ Str::limit($exam->content ?? 'Tidak ada deskripsi', 100) }}</div>
                    </td>
                    <td>{{ $exam->KelasMapel->Kelas->name ?? 'Tidak ada kelas' }}</td>
                    <td>{{ $exam->KelasMapel->Mapel->name ?? 'Tidak ada mata pelajaran' }}</td>
                    <td>
                        @php
                            $typeMap = [1 => 'multiple_choice', 2 => 'essay', 3 => 'mixed'];
                            $typeLabels = ['multiple_choice' => 'Pilihan Ganda', 'essay' => 'Essay', 'mixed' => 'Campuran'];
                            $type = $typeMap[$exam->tipe ?? 1] ?? 'multiple_choice';
                        @endphp
                        <span class="type-badge">{{ $typeLabels[$type] }}</span>
                    </td>
                    <td>
                        @if($exam->isHidden == 0)
                            @if($exam->due && $exam->due < now())
                                <span class="status-badge status-completed">Selesai</span>
                            @else
                                <span class="status-badge status-active">Aktif</span>
                            @endif
                        @else
                            <span class="status-badge status-draft">Draft</span>
                        @endif
                    </td>
                    <td>{{ $exam->due ? \Carbon\Carbon::parse($exam->due)->format('d M Y H:i') : 'N/A' }}</td>
                    <td>
                        <div class="exam-actions">
                            <button class="btn-secondary" onclick="viewExam('{{ $exam->id ?? 1 }}')">
                                <i class="fas fa-eye"></i> Lihat
                            </button>
                            <button class="btn-success" onclick="viewResults('{{ $exam->id ?? 1 }}')">
                                <i class="fas fa-chart-bar"></i> Hasil
                            </button>
                            <button class="btn-warning" onclick="editExam('{{ $exam->id ?? 1 }}')">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            @if($exam->isHidden == 1)
                                <button class="btn-primary" onclick="publishExam('{{ $exam->id ?? 1 }}')">
                                    <i class="fas fa-paper-plane"></i> Publikasi
                                </button>
                            @endif
                            <button class="btn-danger" onclick="deleteExam('{{ $exam->id ?? 1 }}')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 2rem; color: #94a3b8;">
                        <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                        Belum ada ujian yang dibuat
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


@php
    $examConfig = [
        'userRole' => $userRole,
        'isSuperAdmin' => $userRole === 'superadmin',
        'isAdmin' => $userRole === 'admin',
        'hasTeacherCreateMixed' => Route::has('teacher.exam-management.create-mixed'),
        'hasTeacherView' => Route::has('teacher.exam-management.view'),
        'hasTeacherResults' => Route::has('teacher.exam-management.results'),
        'hasTeacherEdit' => Route::has('teacher.exam-management.edit'),
        'urls' => []
    ];
    
    if ($userRole === 'superadmin') {
        $examConfig['urls'] = array_merge($examConfig['urls'], [
            'superAdminCreateMultipleChoice' => route('superadmin.exam-management.create-multiple-choice'),
            'superAdminCreateEssay' => route('superadmin.exam-management.create-essay'),
            'superAdminCreateMixed' => route('superadmin.exam-management.create-mixed'),
            'superAdminView' => route('superadmin.exam-management.view', ''),
            'superAdminResults' => route('superadmin.exam-management.results', ''),
            'superAdminEdit' => route('superadmin.exam-management.edit', ''),
        ]);
    }
    
    if ($userRole === 'admin') {
        $examConfig['urls']['adminResults'] = route('superadmin.exam-management.results', '');
    }
    
    if (Route::has('teacher.exam-management.create-mixed')) {
        $examConfig['urls']['teacherCreateMixed'] = route('teacher.exam-management.create-mixed');
    }
    
    if (Route::has('teacher.exam-management.view')) {
        $examConfig['urls']['teacherView'] = route('teacher.exam-management.view', '');
    }
    
    if (Route::has('teacher.exam-management.results')) {
        $examConfig['urls']['teacherResults'] = route('teacher.exam-management.results', '');
    }
    
    if (Route::has('teacher.exam-management.edit')) {
        $examConfig['urls']['teacherEdit'] = route('teacher.exam-management.edit', '');
    }
@endphp

<script>
// Load exam configuration
window.examConfig = JSON.parse('{!! json_encode($examConfig) !!}');
</script>

<script src="{{ asset('js/exam-management-functions.js') }}"></script>
