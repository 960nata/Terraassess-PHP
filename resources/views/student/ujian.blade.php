@extends('layouts.unified-layout-consistent')

@section('title', 'Terra Assessment - My Exams')
@section('page-title', 'My Exams')
@section('page-description', 'Kerjakan ujian dan lihat hasil penilaian dari pengajar')

@section('styles')
<style>
/* Student Exam Management Styles - Consistent with Superadmin */
.exam-filters {
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

/* Exam Cards Grid */
.exams-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.exam-card {
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

.exam-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    border-color: #667eea;
}

/* Exam Card Components */
.exam-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.exam-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #ffffff;
    margin: 0;
    line-height: 1.3;
}

.exam-status {
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

.exam-meta {
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

.exam-description {
    color: #cbd5e1;
    line-height: 1.5;
    margin-bottom: 1rem;
    font-size: 0.875rem;
}

.exam-stats {
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
    
    .exams-grid {
        grid-template-columns: 1fr;
    }
    
    .exam-stats {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
}

</style>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filter Controls -->
    <div class="exam-filters">
        <div class="filter-row">
            <div class="form-group">
                <label for="statusFilter">Filter Status:</label>
                <select id="statusFilter" class="form-control" onchange="filterExams()">
                    <option value="">Semua Status</option>
                    <option value="pending">Belum Dikerjakan</option>
                    <option value="submitted">Sudah Dikumpulkan</option>
                    <option value="graded">Sudah Dinilai</option>
                    <option value="overdue">Terlambat</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="sortBy">Urutkan:</label>
                <select id="sortBy" class="form-control" onchange="sortExams()">
                    <option value="deadline">Berdasarkan Deadline</option>
                    <option value="status">Berdasarkan Status</option>
                    <option value="subject">Berdasarkan Mata Pelajaran</option>
                    <option value="created">Berdasarkan Tanggal Dibuat</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="subjectFilter">Mata Pelajaran:</label>
                <select id="subjectFilter" class="form-control" onchange="filterExams()">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($ujian->pluck('kelasMapel.mapel.name')->unique() as $subject)
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

    @if($ujian->count() > 0)
        <div class="exams-grid">
            @foreach($ujian as $ujianItem)
                @php
                    $now = now();
                    $deadline = \Carbon\Carbon::parse($ujianItem->deadline);
                    $isOverdue = $deadline->isPast();
                    $isSoon = $now->diffInDays($deadline) <= 2 && !$isOverdue;
                    $status = $ujianItem->status ?? 'pending';
                    
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

                <div class="exam-card" onclick="window.location.href='{{ route('student.kerjakan-ujian', $ujianItem->id) }}'">
                    <div class="exam-header">
                        <h3 class="exam-title">{{ $ujianItem->judul }}</h3>
                        <span class="exam-status {{ $statusClass }}">
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

                    <div class="exam-meta">
                        <div class="meta-item">
                            <i class="fas fa-book"></i>
                            <span>{{ $ujianItem->kelasMapel->mapel->name }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-user"></i>
                            <span>{{ $ujianItem->kelasMapel->pengajar->first()->name ?? 'N/A' }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <span>{{ $deadline->format('d M Y, H:i') }}</span>
                        </div>
                    </div>

                    <p class="exam-description">
                        {{ Str::limit($ujianItem->deskripsi ?? 'Tidak ada deskripsi', 150) }}
                    </p>

                    <div class="exam-stats">
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
                        
                        @if($ujianItem->durasi)
                        <div class="stat-item">
                            <div class="stat-label">Durasi</div>
                            <div class="stat-value">{{ $ujianItem->durasi }} menit</div>
                        </div>
                        @endif
                        
                        <div class="stat-item">
                            <div class="stat-label">Tipe</div>
                            <div class="stat-value">{{ ucfirst($ujianItem->tipe) }}</div>
                        </div>
                        
                        @if($ujianItem->status == 'graded' && $ujianItem->nilai)
                        <div class="stat-item">
                            <div class="stat-label">Nilai</div>
                            <div class="stat-value text-green-500">{{ $ujianItem->nilai }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <h3 class="empty-title">Belum Ada Ujian</h3>
            <p class="empty-description">Tidak ada ujian yang tersedia saat ini. Silakan cek kembali nanti.</p>
        </div>
    @endif
</div>

<script>
// Exam filtering and sorting functionality
let allExams = [];
let filteredExams = [];

// Initialize exams data
document.addEventListener('DOMContentLoaded', function() {
    const examCards = document.querySelectorAll('.exam-card');
    allExams = Array.from(examCards).map(card => {
        const statusElement = card.querySelector('.exam-status');
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
            title: card.querySelector('.exam-title').textContent,
            subject: subject,
            deadline: metaItems[2] ? metaItems[2].textContent : ''
        };
    });
    
    filteredExams = [...allExams];
});

function filterExams() {
    const statusFilter = document.getElementById('statusFilter').value;
    const subjectFilter = document.getElementById('subjectFilter').value;
    
    filteredExams = allExams.filter(exam => {
        const statusMatch = !statusFilter || exam.status === statusFilter;
        const subjectMatch = !subjectFilter || exam.subject === subjectFilter;
        return statusMatch && subjectMatch;
    });
    
    updateExamDisplay();
}

function sortExams() {
    const sortBy = document.getElementById('sortBy').value;
    
    filteredExams.sort((a, b) => {
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
    
    updateExamDisplay();
}

function updateExamDisplay() {
    const examsGrid = document.querySelector('.exams-grid');
    if (!examsGrid) return;
    
    // Hide all exams
    allExams.forEach(exam => {
        exam.element.style.display = 'none';
    });
    
    // Show filtered exams
    filteredExams.forEach(exam => {
        exam.element.style.display = 'block';
    });
}

function resetFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('sortBy').value = 'deadline';
    document.getElementById('subjectFilter').value = '';
    filteredExams = [...allExams];
    updateExamDisplay();
}

// Add smooth animations
function addExamAnimations() {
    const examCards = document.querySelectorAll('.exam-card');
    examCards.forEach((card, index) => {
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
    setTimeout(addExamAnimations, 100);
});
</script>
@endsection