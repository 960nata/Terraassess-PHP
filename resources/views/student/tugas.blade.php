@extends('layouts.unified-layout-consistent')

@section('title', 'Terra Assessment - My Tasks')
@section('page-title', 'My Tasks')
@section('page-description', 'Lihat dan kerjakan tugas yang diberikan oleh pengajar')

@section('styles')
<style>
/* Student Task Management Styles - Consistent with Superadmin */
.task-filters {
    background-color: #1e293b;
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid #334155;
}

.filter-row {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr auto;
    gap: 1rem;
    align-items: end;
}

.form-group {
    margin-bottom: 0;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #ffffff;
    font-size: 0.9rem;
}

.form-group input,
.form-group select {
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
.form-group select:focus {
    outline: none;
    border-color: #667eea;
    background: #333;
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
    color: #cbd5e1;
    border: 1px solid #475569;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-secondary:hover {
    background: #475569;
    color: #ffffff;
    transform: translateY(-2px);
}

/* Task Card Components */
.task-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.task-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #ffffff;
    margin: 0;
    line-height: 1.3;
}

.task-status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-pending {
    background: rgba(255, 193, 7, 0.2);
    color: #fbbf24;
    border: 1px solid rgba(255, 193, 7, 0.3);
}

.status-submitted {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.status-graded {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.status-overdue {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.task-meta {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #cbd5e1;
    font-size: 0.875rem;
}

.meta-item i {
    color: #667eea;
    width: 16px;
}

.task-description {
    color: #cbd5e1;
    line-height: 1.5;
    margin-bottom: 1rem;
    font-size: 0.875rem;
}

.task-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
}

.stat-item {
    text-align: center;
    background: rgba(255, 255, 255, 0.05);
    padding: 0.75rem;
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.stat-label {
    font-size: 0.75rem;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.25rem;
    font-weight: 500;
}

.stat-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: #ffffff;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    border-radius: 16px;
    border: 1px solid #475569;
}

.empty-icon {
    font-size: 4rem;
    color: #667eea;
    margin-bottom: 1.5rem;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 0.75rem;
}

.empty-description {
    color: #cbd5e1;
    font-size: 1rem;
    margin: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .filter-row {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .tasks-grid {
        grid-template-columns: 1fr;
    }
    
    .task-stats {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
}

/* Task Cards Grid */
.tasks-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.task-card {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid #475569;
    transition: all 0.3s ease;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
}

.task-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    border-color: #667eea;
}
</style>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filter Controls -->
    <div class="task-filters">
        <div class="filter-row">
            <div class="form-group">
                <label for="statusFilter">Filter Status:</label>
                <select id="statusFilter" class="form-control" onchange="filterTasks()">
                    <option value="">Semua Status</option>
                    <option value="pending">Belum Dikerjakan</option>
                    <option value="submitted">Sudah Dikumpulkan</option>
                    <option value="graded">Sudah Dinilai</option>
                    <option value="overdue">Terlambat</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="sortBy">Urutkan:</label>
                <select id="sortBy" class="form-control" onchange="sortTasks()">
                    <option value="deadline">Berdasarkan Deadline</option>
                    <option value="status">Berdasarkan Status</option>
                    <option value="subject">Berdasarkan Mata Pelajaran</option>
                    <option value="created">Berdasarkan Tanggal Dibuat</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="subjectFilter">Mata Pelajaran:</label>
                <select id="subjectFilter" class="form-control" onchange="filterTasks()">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($tugas->pluck('kelasMapel.mapel.name')->unique() as $subject)
                        <option value="{{ $subject }}">{{ $subject }}</option>
                    @endforeach
                </select>
            </div>
            
            <button class="btn-primary" onclick="resetFilters()">
                <i class="fas fa-sync-alt"></i>
                Reset Filter
            </button>
        </div>
    </div>

    @if($tugas->count() > 0)
        <div class="tasks-grid">
            @foreach($tugas as $tugasItem)
                @php
                    $userTugas = \App\Models\UserTugas::where('user_id', auth()->id())
                        ->where('tugas_id', $tugasItem->id)
                        ->first();

                    $status = $userTugas ? $userTugas->status : 'pending';
                    $deadline = \Carbon\Carbon::parse($tugasItem->due);
                    $now = \Carbon\Carbon::now();
                    $isOverdue = $now->gt($deadline);
                    $isSoon = $now->diffInDays($deadline) <= 2 && !$isOverdue;
                    
                    // Determine status class
                    $statusClass = 'status-pending';
                    if ($isOverdue && $status === 'pending') {
                        $statusClass = 'status-overdue';
                    } elseif ($status === 'submitted') {
                        $statusClass = 'status-submitted';
                    } elseif ($status === 'graded') {
                        $statusClass = 'status-graded';
                    }
                @endphp

                <div class="task-card" onclick="window.location.href='{{ route('student.kerjakan-tugas', $tugasItem->id) }}'">
                    <div class="task-header">
                        <h3 class="task-title">{{ $tugasItem->name }}</h3>
                        <span class="task-status {{ $statusClass }}">
                            @if($status === 'pending')
                                @if($isOverdue)
                                    Terlambat
                                @elseif($isSoon)
                                    Segera Deadline
                                @else
                                    Belum Dikerjakan
                                @endif
                            @elseif($status === 'submitted')
                                Sudah Dikumpulkan
                            @elseif($status === 'graded')
                                Sudah Dinilai
                            @else
                                {{ ucfirst($status) }}
                            @endif
                        </span>
                    </div>

                    <div class="task-meta">
                        <div class="meta-item">
                            <i class="fas fa-book"></i>
                            <span>{{ $tugasItem->kelasMapel->mapel->name }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-user"></i>
                            <span>{{ $tugasItem->kelasMapel->pengajar->first()->name ?? 'N/A' }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <span>{{ $deadline->format('d M Y, H:i') }}</span>
                        </div>
                    </div>

                    <p class="task-description">
                        {{ Str::limit($tugasItem->content, 150) }}
                    </p>

                    <div class="task-stats">
                        <div class="stat-item">
                            <div class="stat-label">Sisa Waktu</div>
                            @if($isOverdue)
                                <div class="stat-value text-red-500">
                                    Terlambat {{ $now->diffForHumans($deadline) }}
                                </div>
                            @elseif($isSoon)
                                <div class="stat-value text-orange-500">
                                    {{ $now->diffForHumans($deadline, true) }}
                                </div>
                            @else
                                <div class="stat-value text-blue-500">
                                    {{ $now->diffForHumans($deadline, true) }}
                                </div>
                            @endif
                        </div>
                        
                        @if($userTugas && $userTugas->nilai)
                        <div class="stat-item">
                            <div class="stat-label">Nilai</div>
                            <div class="stat-value text-green-500">{{ $userTugas->nilai }}</div>
                        </div>
                        @endif
                        
                        <div class="stat-item">
                            <div class="stat-label">Tipe</div>
                            <div class="stat-value">{{ ucfirst($tugasItem->tipe ?? 'Essay') }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <h3 class="empty-title">Belum Ada Tugas</h3>
            <p class="empty-description">Tidak ada tugas yang tersedia saat ini. Silakan cek kembali nanti.</p>
        </div>
    @endif
</div>

<script>
// Task filtering and sorting functionality
let allTasks = [];
let filteredTasks = [];

// Initialize tasks data
document.addEventListener('DOMContentLoaded', function() {
    const taskCards = document.querySelectorAll('.task-card');
    allTasks = Array.from(taskCards).map(card => {
        const statusElement = card.querySelector('.task-status');
        const statusText = statusElement.textContent.trim();
        
        // Determine status for filtering
        let status = 'pending';
        if (statusText.includes('Sudah Dikumpulkan')) status = 'submitted';
        else if (statusText.includes('Sudah Dinilai')) status = 'graded';
        else if (statusText.includes('Terlambat')) status = 'overdue';
        
        // Get subject from meta items
        const metaItems = card.querySelectorAll('.meta-item span');
        const subject = metaItems[0] ? metaItems[0].textContent : '';
        
        return {
            element: card,
            status: status,
            title: card.querySelector('.task-title').textContent,
            subject: subject,
            deadline: metaItems[2] ? metaItems[2].textContent : ''
        };
    });
    
    filteredTasks = [...allTasks];
});

function filterTasks() {
    const statusFilter = document.getElementById('statusFilter').value;
    const subjectFilter = document.getElementById('subjectFilter').value;
    
    filteredTasks = allTasks.filter(task => {
        const statusMatch = !statusFilter || task.status === statusFilter;
        const subjectMatch = !subjectFilter || task.subject === subjectFilter;
        return statusMatch && subjectMatch;
    });
    
    updateTaskDisplay();
}

function sortTasks() {
    const sortBy = document.getElementById('sortBy').value;
    
    filteredTasks.sort((a, b) => {
        switch(sortBy) {
            case 'status':
                const statusOrder = { 'overdue': 0, 'pending': 1, 'submitted': 2, 'graded': 3 };
                return statusOrder[a.status] - statusOrder[b.status];
            case 'subject':
                return a.subject.localeCompare(b.subject);
            case 'deadline':
                return a.deadline.localeCompare(b.deadline);
            case 'created':
                return 0;
            default:
                return 0;
        }
    });
    
    updateTaskDisplay();
}

function updateTaskDisplay() {
    const tasksGrid = document.querySelector('.tasks-grid');
    if (!tasksGrid) return;
    
    // Hide all tasks
    allTasks.forEach(task => {
        task.element.style.display = 'none';
    });
    
    // Show filtered tasks
    filteredTasks.forEach(task => {
        task.element.style.display = 'block';
    });
}

function resetFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('sortBy').value = 'deadline';
    document.getElementById('subjectFilter').value = '';
    filteredTasks = [...allTasks];
    updateTaskDisplay();
}

// Add smooth animations
function addTaskAnimations() {
    const taskCards = document.querySelectorAll('.task-card');
    taskCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

// Initialize animations when page loads
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(addTaskAnimations, 100);
});
</script>
@endsection