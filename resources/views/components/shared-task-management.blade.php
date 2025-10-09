@props([
    'user' => null,
    'tasks' => [],
    'classes' => [],
    'subjects' => [],
    'filters' => [],
    'totalTasks' => 0,
    'activeTasks' => 0,
    'completedTasks' => 0,
    'activeClasses' => 0,
    'userRole' => 'teacher'
])

<div class="page-header">
    <h1 class="page-title">
        <i class="ph-clipboard-text"></i>
        Manajemen Tugas
    </h1>
    <p class="page-description">Kelola tugas per kelas dengan kategorisasi dan tingkat kesulitan</p>
</div>

<!-- Welcome Banner -->
<div class="welcome-banner">
    <div class="welcome-icon">
        <i class="ph-clipboard-text"></i>
    </div>
    <div class="welcome-content">
        <h3 class="welcome-title">Manajemen Tugas</h3>
        <p class="welcome-description">
            Buat, kelola, dan pantau tugas untuk siswa Anda. 
            Lihat progress dan hasil penilaian dengan mudah.
        </p>
    </div>
</div>

<!-- Statistics -->
<div class="dashboard-grid">
    <div class="card">
        <div class="card-icon blue">
            <i class="ph-clipboard-text"></i>
        </div>
        <h3 class="card-title">Total Tugas</h3>
        <p class="card-description">{{ $totalTasks }} tugas telah dibuat</p>
    </div>
    <div class="card">
        <div class="card-icon green">
            <i class="ph-check-circle"></i>
        </div>
        <h3 class="card-title">Tugas Aktif</h3>
        <p class="card-description">{{ $activeTasks }} tugas sedang berlangsung</p>
    </div>
    <div class="card">
        <div class="card-icon purple">
            <i class="ph-clock"></i>
        </div>
        <h3 class="card-title">Tugas Selesai</h3>
        <p class="card-description">{{ $completedTasks }} tugas telah selesai</p>
    </div>
    <div class="card">
        <div class="card-icon orange">
            <i class="ph-users"></i>
        </div>
        <h3 class="card-title">Kelas Aktif</h3>
        <p class="card-description">{{ $activeClasses }} kelas sedang aktif</p>
    </div>
</div>

<!-- Task Type Cards -->
<div class="system-info">
    <div class="info-section">
        <h3 class="info-title">Buat Tugas Baru</h3>
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-primary" onclick="createMultipleChoiceTask()">
                <i class="ph-list"></i> Pilihan Ganda
            </button>
            <button class="btn btn-success" onclick="createEssayTask()">
                <i class="ph-pencil"></i> Esai
            </button>
            <button class="btn btn-warning" onclick="createGroupTask()">
                <i class="ph-users"></i> Kelompok
            </button>
            <button class="btn btn-info" onclick="createIndividualTask()">
                <i class="ph-user"></i> Mandiri
            </button>
        </div>
    </div>
</div>

<!-- Create Task Form -->
<div class="system-info">
    <div class="info-section">
        <h3 class="info-title">
            <i class="ph-plus"></i> Buat Tugas Baru
        </h3>
    
    <form action="{{ $userRole === 'superadmin' ? route('superadmin.tugas.store') : route('teacher.task-management.create') }}" method="POST">
        @csrf
        
        <div class="form-row">
            <div class="form-group">
                <label for="task_title">Judul Tugas</label>
                <input type="text" id="task_title" name="task_title" placeholder="Masukkan judul tugas" required>
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
                <label for="max_score">Nilai Maksimal</label>
                <input type="number" id="max_score" name="max_score" placeholder="100" min="1" max="100" required>
            </div>
        </div>

        <div class="form-group">
            <label for="task_description">Deskripsi Tugas</label>
            <textarea id="task_description" name="task_description" placeholder="Masukkan deskripsi tugas yang detail" required></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="category">Kategori</label>
                <select id="category" name="category" required>
                    <option value="">Pilih kategori</option>
                    <option value="tugas_rumah">Tugas Rumah</option>
                    <option value="tugas_kelompok">Tugas Kelompok</option>
                    <option value="proyek">Proyek</option>
                    <option value="presentasi">Presentasi</option>
                    <option value="penelitian">Penelitian</option>
                </select>
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

        <div class="form-row">
            <div class="form-group">
                <label for="due_date">Tanggal Deadline</label>
                <input type="datetime-local" id="due_date" name="due_date" required>
            </div>
        </div>

        <button type="submit" class="btn-primary">
            <i class="fas fa-plus"></i>
            Buat Tugas
        </button>
    </form>
</div>

<!-- Task Filters -->
<div class="task-filters">
    <h2 style="color: #ffffff; margin-bottom: 1.5rem; font-size: 1.25rem;">
        <i class="fas fa-filter me-2"></i>Filter Tugas
    </h2>
    
    <form action="{{ $userRole === 'superadmin' ? route('superadmin.tugas.index') : route('teacher.task-management.filter') }}" method="GET">
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
                <label for="filter_difficulty">Kesulitan</label>
                <select id="filter_difficulty" name="filter_difficulty">
                    <option value="">Semua Tingkat</option>
                    <option value="easy" {{ (isset($filters['filter_difficulty']) && $filters['filter_difficulty'] == 'easy') ? 'selected' : '' }}>Mudah</option>
                    <option value="medium" {{ (isset($filters['filter_difficulty']) && $filters['filter_difficulty'] == 'medium') ? 'selected' : '' }}>Sedang</option>
                    <option value="hard" {{ (isset($filters['filter_difficulty']) && $filters['filter_difficulty'] == 'hard') ? 'selected' : '' }}>Sulit</option>
                </select>
            </div>
        </div>
        
        <div class="filter-actions" style="display: flex; gap: 1rem; margin-top: 1rem;">
            <button type="submit" class="btn-primary">
                <i class="fas fa-search"></i>
                Terapkan Filter
            </button>
            <a href="{{ $userRole === 'superadmin' ? route('superadmin.tugas.index') : route('teacher.task-management') }}" class="btn-secondary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-times"></i>
                Reset Filter
            </a>
        </div>
    </form>
</div>

<!-- Tasks Table -->
<div class="tasks-table">
    <h2 style="color: #ffffff; margin-bottom: 1.5rem; font-size: 1.25rem;">
        <i class="fas fa-list me-2"></i>Daftar Tugas
    </h2>
    
    <table class="table">
        <thead>
            <tr>
                <th>Judul Tugas</th>
                <th>Kelas</th>
                <th>Mata Pelajaran</th>
                <th>Kesulitan</th>
                <th>Status</th>
                <th>Deadline</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tasks as $task)
                <tr>
                    <td>
                        <div class="task-title">{{ $task->name }}</div>
                        <div class="task-description">{{ Str::limit($task->content, 100) }}</div>
                    </td>
                    <td>{{ $task->KelasMapel->Kelas->name ?? 'N/A' }}</td>
                    <td>{{ $task->KelasMapel->Mapel->name ?? 'N/A' }}</td>
                    <td>
                        @php
                            $difficultyMap = [1 => 'easy', 2 => 'medium', 3 => 'hard'];
                            $difficultyLabels = ['easy' => 'Mudah', 'medium' => 'Sedang', 'hard' => 'Sulit'];
                            $difficulty = $difficultyMap[$task->tipe] ?? 'medium';
                        @endphp
                        <span class="difficulty-badge difficulty-{{ $difficulty }}">
                            {{ $difficultyLabels[$difficulty] }}
                        </span>
                    </td>
                    <td>
                        @if($task->isHidden == 0)
                            @if($task->due && $task->due < now())
                                <span class="status-badge status-completed">Selesai</span>
                            @else
                                <span class="status-badge status-active">Aktif</span>
                            @endif
                        @else
                            <span class="status-badge status-draft">Draft</span>
                        @endif
                    </td>
                    <td>{{ $task->due ? \Carbon\Carbon::parse($task->due)->format('d M Y H:i') : 'N/A' }}</td>
                    <td>
                        <div class="task-actions">
                            <button class="btn-secondary" onclick="editTask('{{ $task->id }}')">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn-success" onclick="viewSubmissions('{{ $task->id }}')">
                                <i class="fas fa-eye"></i> Lihat
                            </button>
                            @if($task->isHidden == 1)
                                <button class="btn-warning" onclick="publishTask('{{ $task->id }}')">
                                    <i class="fas fa-paper-plane"></i> Publikasi
                                </button>
                            @endif
                            <button class="btn-danger" onclick="deleteTask('{{ $task->id }}')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 2rem; color: #94a3b8;">
                        <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                        Belum ada tugas yang dibuat
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


<script>
function createMultipleChoiceTask() {
    // Redirect to create multiple choice task
    window.location.href = "{{ $userRole === 'superadmin' ? route('superadmin.task-create-multiple-choice') : route('teacher.tasks.create.multiple-choice') }}";
}

function createEssayTask() {
    // Redirect to create essay task
    window.location.href = "{{ $userRole === 'superadmin' ? route('superadmin.task-create-essay') : route('teacher.tasks.create.essay') }}";
}

function createGroupTask() {
    // Redirect to create group task
    window.location.href = "{{ $userRole === 'superadmin' ? route('superadmin.task-create-group') : route('teacher.tasks.create.group') }}";
}

function editTask(taskId) {
    // Redirect to edit task
    window.location.href = "{{ $userRole === 'superadmin' ? route('superadmin.task-edit', '') : route('teacher.tasks.edit', '') }}/" + taskId;
}

function viewSubmissions(taskId) {
    // Redirect to view submissions
    if ("{{ $userRole }}" === 'teacher') {
        window.location.href = "{{ route('teacher.tasks.detail', '') }}/" + taskId;
    } else {
        window.location.href = "{{ $userRole === 'superadmin' ? route('superadmin.task-detail', '') : route('teacher.tasks.show', '') }}/" + taskId;
    }
}

function publishTask(taskId) {
    // Publish task
    if (confirm('Apakah Anda yakin ingin mempublikasikan tugas ini?')) {
        // Add publish logic here
        console.log('Publishing task:', taskId);
    }
}

function deleteTask(taskId) {
    // Delete task
    if (confirm('Apakah Anda yakin ingin menghapus tugas ini?')) {
        // Add delete logic here
        console.log('Deleting task:', taskId);
    }
}
</script>
