@extends('layouts.unified-layout-new')

@section('title', 'Dashboard Guru')

@section('content')
<!-- Page Header -->
@include('components.page-header', [
    'title' => 'Dashboard Guru',
    'description' => 'Selamat datang di dashboard guru Terra Assessment',
    'icon' => 'fas fa-chalkboard-teacher',
    'actions' => [
        [
            'text' => 'Tambah Tugas',
            'icon' => 'fas fa-plus',
            'class' => 'btn-primary',
            'onclick' => 'openCreateTaskModal()'
        ],
        [
            'text' => 'Tambah Ujian',
            'icon' => 'fas fa-clipboard-check',
            'class' => 'btn-secondary',
            'onclick' => 'openCreateExamModal()'
        ]
    ]
])

<!-- Statistics Grid -->
@include('components.stats-grid', [
    'stats' => [
        [
            'icon' => 'fas fa-tasks',
            'value' => $totalTugas ?? 0,
            'label' => 'Total Tugas'
        ],
        [
            'icon' => 'fas fa-clipboard-check',
            'value' => $totalUjian ?? 0,
            'label' => 'Total Ujian'
        ],
        [
            'icon' => 'fas fa-book',
            'value' => $totalMateri ?? 0,
            'label' => 'Total Materi'
        ],
        [
            'icon' => 'fas fa-users',
            'value' => $totalSiswa ?? 0,
            'label' => 'Siswa Saya'
        ]
    ]
])

<!-- Main Content Grid -->
<div class="content-grid">
    <!-- Recent Activities -->
    <div class="grid-item">
        @include('components.info-card', [
            'header' => [
                'icon' => 'fas fa-clock',
                'title' => 'Aktivitas Terbaru',
                'subtitle' => 'Aktivitas terbaru dalam sistem'
            ],
            'actions' => [
                [
                    'text' => 'Lihat Semua',
                    'icon' => 'fas fa-eye',
                    'class' => 'btn-outline'
                ]
            ]
        ])
        <div class="activity-list">
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">Siswa baru bergabung</div>
                    <div class="activity-time">2 jam yang lalu</div>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">Tugas baru dibuat</div>
                    <div class="activity-time">4 jam yang lalu</div>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title">Ujian selesai dinilai</div>
                    <div class="activity-time">1 hari yang lalu</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid-item">
        @include('components.info-card', [
            'header' => [
                'icon' => 'fas fa-bolt',
                'title' => 'Aksi Cepat',
                'subtitle' => 'Akses cepat ke fitur utama'
            ]
        ])
        <div class="quick-actions">
            <a href="{{ route('teacher.tugas') }}" class="quick-action-item">
                <div class="quick-action-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="quick-action-text">
                    <div class="quick-action-title">Kelola Tugas</div>
                    <div class="quick-action-desc">Buat dan kelola tugas</div>
                </div>
            </a>
            <a href="{{ route('ujian.index') }}" class="quick-action-item">
                <div class="quick-action-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="quick-action-text">
                    <div class="quick-action-title">Kelola Ujian</div>
                    <div class="quick-action-desc">Buat dan kelola ujian</div>
                </div>
            </a>
            <a href="{{ route('teacher.materi') }}" class="quick-action-item">
                <div class="quick-action-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="quick-action-text">
                    <div class="quick-action-title">Kelola Materi</div>
                    <div class="quick-action-desc">Buat dan kelola materi</div>
                </div>
            </a>
            <a href="{{ route('iot.dashboard') }}" class="quick-action-item">
                <div class="quick-action-icon">
                    <i class="fas fa-wifi"></i>
                </div>
                <div class="quick-action-text">
                    <div class="quick-action-title">IoT Dashboard</div>
                    <div class="quick-action-desc">Monitoring IoT</div>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Data Tables Section -->
<div class="tables-section">
    <!-- Recent Tasks -->
    <div class="table-container">
        @include('components.data-table', [
            'title' => 'Tugas Terbaru',
            'columns' => [
                ['label' => 'Judul Tugas', 'class' => 'font-medium'],
                ['label' => 'Kelas', 'class' => 'text-center'],
                ['label' => 'Tanggal Dibuat', 'class' => 'text-center'],
                ['label' => 'Status', 'class' => 'text-center'],
                ['label' => 'Aksi', 'class' => 'text-center']
            ],
            'data' => $recentTugas ?? [],
            'actions' => [
                [
                    'text' => 'Tambah Tugas',
                    'icon' => 'fas fa-plus',
                    'class' => 'btn-primary',
                    'onclick' => 'openCreateTaskModal()'
                ]
            ]
        ])
    </div>

    <!-- Recent Exams -->
    <div class="table-container">
        @include('components.data-table', [
            'title' => 'Ujian Terbaru',
            'columns' => [
                ['label' => 'Judul Ujian', 'class' => 'font-medium'],
                ['label' => 'Kelas', 'class' => 'text-center'],
                ['label' => 'Tanggal Mulai', 'class' => 'text-center'],
                ['label' => 'Durasi', 'class' => 'text-center'],
                ['label' => 'Status', 'class' => 'text-center'],
                ['label' => 'Aksi', 'class' => 'text-center']
            ],
            'data' => $recentUjian ?? [],
            'actions' => [
                [
                    'text' => 'Tambah Ujian',
                    'icon' => 'fas fa-plus',
                    'class' => 'btn-primary',
                    'onclick' => 'openCreateExamModal()'
                ]
            ]
        ])
    </div>
</div>

<!-- Modals -->
@include('components.modal', [
    'id' => 'createTaskModal',
    'title' => 'Buat Tugas Baru',
    'icon' => 'fas fa-tasks',
    'actions' => [
        [
            'text' => 'Batal',
            'class' => 'btn-secondary',
            'onclick' => 'closeModal()'
        ],
        [
            'text' => 'Simpan',
            'class' => 'btn-primary',
            'onclick' => 'saveTask()'
        ]
    ]
])
    <form id="taskForm">
        <div class="form-group">
            <label for="taskTitle">Judul Tugas</label>
            <input type="text" id="taskTitle" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="taskDescription">Deskripsi</label>
            <textarea id="taskDescription" name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label for="taskClass">Kelas</label>
            <select id="taskClass" name="class_id" class="form-control" required>
                <option value="">Pilih Kelas</option>
                <!-- Options will be populated dynamically -->
            </select>
        </div>
    </form>
@endcomponent

@push('styles')
<style>
.content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.grid-item {
    min-height: 400px;
}

.tables-section {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
}

.table-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.activity-list {
    padding: 0;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
    transition: background-color 0.2s ease;
}

.activity-item:hover {
    background: #f9fafb;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.activity-content {
    flex: 1;
}

.activity-title {
    font-weight: 500;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.activity-time {
    font-size: 0.875rem;
    color: #6b7280;
}

.quick-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.quick-action-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 10px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.quick-action-item:hover {
    background: #f3f4f6;
    border-color: #3b82f6;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

.quick-action-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.quick-action-text {
    flex: 1;
}

.quick-action-title {
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.quick-action-desc {
    font-size: 0.875rem;
    color: #6b7280;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

@media (max-width: 768px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
    
    .quick-actions {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
function openCreateTaskModal() {
    // Implementation for opening create task modal
    console.log('Opening create task modal');
}

function openCreateExamModal() {
    // Implementation for opening create exam modal
    console.log('Opening create exam modal');
}

function saveTask() {
    // Implementation for saving task
    console.log('Saving task');
}
</script>
@endpush
@endsection
