@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Ujian')

@section('styles')
<style>
/* Dark Theme Base */
body {
    background: #0f172a !important;
    color: #ffffff !important;
}

/* Override any white backgrounds */
* {
    box-sizing: border-box;
}

/* Ensure all text is visible on dark background */
h1, h2, h3, h4, h5, h6, p, span, div, a, label, select, option {
    color: inherit;
}

/* Dark theme for form elements */
select, input, textarea {
    background: #1e293b !important;
    color: #ffffff !important;
    border-color: #475569 !important;
}

select option {
    background: #1e293b !important;
    color: #ffffff !important;
}

/* Modern Student Exam UI */
.exam-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
    background: #0f172a;
    min-height: 100vh;
    width: 100%;
    position: relative;
    z-index: 1;
}

/* Full width dark background */
.exam-container::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #0f172a;
    z-index: -1;
}

/* Ensure full width dark background */
html, body {
    background: #0f172a !important;
    margin: 0;
    padding: 0;
    width: 100%;
    min-height: 100vh;
}

/* Override any parent container backgrounds */
.container, .main-content, .content-wrapper {
    background: #0f172a !important;
}

/* Force dark theme on all elements */
*, *::before, *::after {
    background-color: transparent;
}

/* Override any white backgrounds that might appear */
.card, .panel, .box, .container-fluid, .row, .col, .col-md, .col-lg, .col-xl {
    background: #0f172a !important;
    color: #ffffff !important;
}

/* Ensure navigation and header are dark */
.navbar, .header, .sidebar, .menu {
    background: #1e293b !important;
    color: #ffffff !important;
}

/* Override any framework defaults */
.bg-white, .bg-light, .text-dark {
    background: #1e293b !important;
    color: #ffffff !important;
}

.page-header {
    text-align: center;
    margin-bottom: 3rem;
    padding: 3rem 2rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    color: white;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="25" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="25" cy="75" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    position: relative;
    z-index: 2;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.page-description {
    font-size: 1.1rem;
    opacity: 0.9;
    margin: 0;
    position: relative;
    z-index: 2;
}

.exams-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.exam-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    padding: 1.5rem;
    border: none;
    transition: all 0.3s ease;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
    position: relative;
    overflow: hidden;
}

.exam-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    z-index: 1;
}

.exam-card > * {
    position: relative;
    z-index: 2;
}

.exam-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.exam-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.75rem;
}

.exam-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0;
    line-height: 1.3;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.exam-status {
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.status-pending {
    background: rgba(255, 193, 7, 0.9);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
}

.status-submitted {
    background: rgba(13, 110, 253, 0.9);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
}

.status-graded {
    background: rgba(25, 135, 84, 0.9);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(25, 135, 84, 0.3);
}

.status-overdue {
    background: rgba(220, 53, 69, 0.9);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
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
    gap: 0.75rem;
    padding: 0.5rem;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 8px;
    font-size: 0.85rem;
    color: #ffffff;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.meta-icon {
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    padding: 0.25rem;
}

.meta-text {
    color: #ffffff;
    font-size: 0.85rem;
    font-weight: 500;
}

.exam-description {
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.5;
    margin-bottom: 1rem;
    font-size: 0.85rem;
    background: rgba(255, 255, 255, 0.1);
    padding: 0.75rem;
    border-radius: 8px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.exam-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
    margin-bottom: 0.5rem;
}

.stat-item {
    text-align: center;
    background: rgba(255, 255, 255, 0.15);
    padding: 0.75rem;
    border-radius: 8px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.stat-label {
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.8);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.25rem;
    font-weight: 500;
}

.stat-value {
    font-size: 0.9rem;
    font-weight: 700;
    color: #ffffff;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.exam-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.exam-btn {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 0.8rem;
    flex: 1;
    justify-content: center;
}

.exam-btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.exam-btn-primary:hover {
    background: linear-gradient(135deg, #5a67d8, #6b46c1);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.exam-btn-secondary {
    background: #334155;
    color: #cbd5e1;
    border: 1px solid #475569;
}

.exam-btn-secondary:hover {
    background: #475569;
    color: #ffffff;
    transform: translateY(-2px);
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    border-radius: 16px;
    border: 2px solid #475569;
    position: relative;
    overflow: hidden;
}

.empty-state::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(102, 126, 234, 0.2)"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
    opacity: 0.5;
}

.empty-icon {
    font-size: 4rem;
    color: #667eea;
    margin-bottom: 1.5rem;
    position: relative;
    z-index: 2;
    text-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 0.75rem;
    position: relative;
    z-index: 2;
}

.empty-description {
    color: #cbd5e1;
    font-size: 1rem;
    margin: 0;
    position: relative;
    z-index: 2;
}

/* Filter Controls */
.filter-controls {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    border-radius: 12px;
    border: 1px solid #475569;
    flex-wrap: wrap;
    align-items: end;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    min-width: 180px;
}

.filter-label {
    color: #cbd5e1;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.filter-select {
    padding: 0.75rem 1rem;
    background: #1e293b;
    border: 2px solid #475569;
    border-radius: 8px;
    color: #ffffff;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
}

.filter-select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
    transform: translateY(-1px);
    background: #2a2a3e;
}

.filter-btn {
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    border-radius: 8px;
    color: #ffffff;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
}

.filter-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(102, 126, 234, 0.4);
}

/* Tablet - 2x2 Grid */
@media (max-width: 1200px) {
    .exams-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }
}

@media (max-width: 768px) {
    .exam-container {
        padding: 1rem;
    }
    
    .page-header {
        padding: 2rem 1rem;
        margin-bottom: 2rem;
    }
    
    .page-title {
        font-size: 2rem;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .filter-controls {
        flex-direction: column;
        gap: 1rem;
        padding: 1rem;
    }
    
    .filter-group {
        min-width: auto;
        width: 100%;
    }
    
    .exams-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .exam-card {
        padding: 1.25rem;
    }
    
    .exam-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .exam-title {
        font-size: 1rem;
    }
    
    .exam-stats {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    
    .empty-state {
        padding: 3rem 1.5rem;
    }
    
    .empty-icon {
        font-size: 3rem;
    }
    
    .empty-title {
        font-size: 1.25rem;
    }
}

@media (max-width: 480px) {
    .exam-container {
        padding: 0.75rem;
    }
    
    .page-header {
        padding: 1.5rem 1rem;
        margin-bottom: 1.5rem;
    }
    
    .page-title {
        font-size: 1.75rem;
    }
    
    .page-description {
        font-size: 0.95rem;
    }
    
    .exams-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .exam-card {
        padding: 1rem;
    }
    
    .exam-title {
        font-size: 1rem;
    }
    
    .exam-description {
        font-size: 0.8rem;
    }
    
    .meta-item {
        font-size: 0.8rem;
        padding: 0.4rem;
    }
    
    .filter-controls {
        padding: 1rem;
    }
    
    .empty-state {
        padding: 2rem 1rem;
    }
    
    .empty-icon {
        font-size: 2.5rem;
    }
    
    .empty-title {
        font-size: 1.1rem;
    }
}
</style>
@endsection

@section('content')
<div class="exam-container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="ph-exam"></i>
            Ujian Saya
        </h1>
        <p class="page-description">Kerjakan ujian dan lihat hasil penilaian dari pengajar</p>
    </div>

    <!-- Filter and Sort Controls -->
    <div class="filter-controls">
        <div class="filter-group">
            <label for="statusFilter" class="filter-label">Filter Status:</label>
            <select id="statusFilter" class="filter-select" onchange="filterExams()">
                <option value="">Semua Status</option>
                <option value="pending">Belum Dikerjakan</option>
                <option value="submitted">Sudah Dikumpulkan</option>
                <option value="graded">Sudah Dinilai</option>
                <option value="overdue">Terlambat</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="sortBy" class="filter-label">Urutkan:</label>
            <select id="sortBy" class="filter-select" onchange="sortExams()">
                <option value="deadline">Berdasarkan Deadline</option>
                <option value="status">Berdasarkan Status</option>
                <option value="subject">Berdasarkan Mata Pelajaran</option>
                <option value="created">Berdasarkan Tanggal Dibuat</option>
            </select>
        </div>
        
        <div class="filter-group">
            <button class="filter-btn" onclick="resetFilters()">
                <i class="ph-arrow-clockwise"></i>
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
                        <h2 class="exam-title">{{ $ujianItem->judul }}</h2>
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
                            <div class="meta-icon">
                                <i class="ph-book"></i>
                            </div>
                            <span class="meta-text">{{ $ujianItem->kelasMapel->mapel->name }}</span>
                        </div>
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="ph-user"></i>
                            </div>
                            <span class="meta-text">{{ $ujianItem->kelasMapel->pengajar->first()->name ?? 'N/A' }}</span>
                        </div>
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="ph-calendar"></i>
                            </div>
                            <span class="meta-text">{{ $deadline->format('d M Y, H:i') }}</span>
                        </div>
                    </div>

                    <p class="exam-description">
                        {{ Str::limit($ujianItem->deskripsi ?? 'Tidak ada deskripsi', 150) }}
                    </p>

                    <div class="exam-stats">
                        <div class="stat-item">
                            <div class="stat-label">Sisa Waktu</div>
                            <div class="stat-value @if($isOverdue) text-red-600 @elseif($isSoon) text-orange-600 @else text-blue-600 @endif">
                                @if($isOverdue)
                                    Terlambat {{ $now->diffForHumans($deadline) }}
                                @else
                                    {{ $now->diffForHumans($deadline, true) }}
                                @endif
                            </div>
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
                            <div class="stat-value" style="color: #059669;">{{ $ujianItem->nilai }}</div>
                        </div>
                        @endif
                    </div>

                    <div class="exam-actions" style="display: none;">
                        <!-- Actions hidden since card is clickable -->
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="ph-exam empty-icon"></i>
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
        
        return {
            element: card,
            status: status,
            title: card.querySelector('.exam-title').textContent,
            subject: card.querySelector('.meta-text').textContent,
            deadline: card.querySelector('.meta-text:last-child').textContent
        };
    });
    
    filteredExams = [...allExams];
});

function filterExams() {
    const statusFilter = document.getElementById('statusFilter').value;
    
    filteredExams = allExams.filter(exam => {
        if (!statusFilter) return true;
        return exam.status === statusFilter;
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
    
    // Update grid layout
    examsGrid.style.display = 'grid';
    examsGrid.style.gridTemplateColumns = 'repeat(3, 1fr)';
    examsGrid.style.gap = '1.5rem';
}

function resetFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('sortBy').value = 'deadline';
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