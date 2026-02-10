@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Manajemen Ujian')

@section('styles')
<style>
/* SIDEBAR STYLES - SAME AS SUPER ADMIN DASHBOARD */
        .sidebar {
            position: fixed;
            top: 70px;
            left: 0;
            height: calc(100vh - 70px);
            width: 280px;
            background: #1e293b;
            z-index: 999;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .sidebar:not(.collapsed) {
            transform: translateX(0);
        }
        
        .sidebar.collapsed {
            transform: translateX(-100%);
        }
        
        .mobile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
            display: none;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .mobile-overlay.active {
            display: block;
            opacity: 1;
            visibility: visible;
        }
        
        .menu-toggle {
            background: #667eea;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .main-content {
            margin-left: 0;
            padding: 2rem;
        }
        
        @media (min-width: 1025px) {
            .sidebar {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 280px;
            }
        }

        /* Exam Cards Styles */
        .exam-cards-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        /* Mobile Responsive - Single Column */
        @media (max-width: 768px) {
            .exam-cards-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .exam-type-card {
                padding: 1rem;
            }
            
            .exam-card-title {
                font-size: 1rem;
            }
            
            .exam-card-description {
                font-size: 0.8rem;
            }
            
            .exam-count {
                font-size: 1.25rem;
            }
        }

        /* Extra Small Mobile - Single Column */
        @media (max-width: 480px) {
            .exam-cards-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
        }

        .exam-type-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .exam-type-card:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(59, 130, 246, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .exam-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .exam-card-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .exam-card-stats {
            text-align: right;
        }

        .exam-count {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
        }

        .exam-label {
            font-size: 0.875rem;
            color: #94a3b8;
        }

        .exam-card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.5rem;
        }

        .exam-card-description {
            color: #94a3b8;
            font-size: 0.875rem;
            line-height: 1.5;
            margin-bottom: 1rem;
        }

        .exam-card-action {
            color: #667eea;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Recent Exams Styles */
        .recent-exams-section {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .view-all-link {
            color: #667eea;
            text-decoration: none;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .exam-item {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .exam-item:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .exam-item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }

        .exam-item-title {
            font-size: 1rem;
            font-weight: 600;
            color: #ffffff;
        }

        .type-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .type-badge.multiple-choice {
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
        }

        .type-badge.essay {
            background: rgba(34, 197, 94, 0.2);
            color: #4ade80;
        }

        .type-badge.mixed {
            background: rgba(245, 158, 11, 0.2);
            color: #fbbf24;
        }

        .type-badge.published {
            background: rgba(34, 197, 94, 0.2);
            color: #4ade80;
        }

        .type-badge.draft {
            background: rgba(156, 163, 175, 0.2);
            color: #9ca3af;
        }

        .exam-item-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .exam-item-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.875rem;
            color: #94a3b8;
        }

        .exam-item-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .btn-action.edit {
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
        }

        .btn-action.view {
            background: rgba(34, 197, 94, 0.2);
            color: #4ade80;
        }

        .btn-action.delete {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
        }

        .btn-action.publish {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .btn-action:hover {
            transform: scale(1.1);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: block;
        }

        .empty-state h3 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: #ffffff;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .exam-cards-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
        }
            
            .exam-item-content {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
        }
</style>
@endsection

@section('content')
<div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-bullseye"></i>
                Manajemen Ujian
            </h1>
            <p class="page-description">Kelola ujian per kelas dengan berbagai tipe dan tingkat kesulitan</p>
        </div>

        <!-- Exam Type Cards -->
        <div class="exam-cards-grid">
            <div class="exam-type-card" onclick="navigateToExamType('multiple-choice')">
                <div class="exam-card-header">
                    <div class="exam-card-icon">
                        <i class="fas fa-list-ul"></i>
                    </div>
                    <div class="exam-card-stats">
                        <span class="exam-count">{{ $stats['multiple_choice'] ?? 0 }}</span>
                        <span class="exam-label">Ujian</span>
                    </div>
                </div>
                <div class="exam-card-content">
                    <h3 class="exam-card-title">Pilihan Ganda</h3>
                    <p class="exam-card-description">Buat ujian dengan pilihan ganda untuk evaluasi cepat</p>
                </div>
                <div class="exam-card-footer">
                    <span class="exam-card-action">Buat Ujian <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>

            <div class="exam-type-card" onclick="navigateToExamType('essay')">
                <div class="exam-card-header">
                    <div class="exam-card-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="exam-card-stats">
                        <span class="exam-count">{{ $stats['essay'] ?? 0 }}</span>
                        <span class="exam-label">Ujian</span>
                    </div>
                </div>
                <div class="exam-card-content">
                    <h3 class="exam-card-title">Essay</h3>
                    <p class="exam-card-description">Buat ujian essay untuk penilaian mendalam</p>
                </div>
                <div class="exam-card-footer">
                    <span class="exam-card-action">Buat Ujian <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>

        </div>

        <!-- Recent Exams List -->
        <div class="recent-exams-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-clock"></i>
                    Ujian Terbaru
                </h2>
                <a href="#" class="view-all-link">Lihat Semua <i class="fas fa-arrow-right"></i></a>
            </div>
            
            <div class="exams-list">
                @forelse($recentExams ?? [] as $exam)
                <div class="exam-item">
                    <div class="exam-item-header">
                        <div class="exam-item-title">{{ $exam->name ?? $exam->judul }}</div>
                        <div class="exam-item-type">
                            @if($exam->isHidden)
                                <span class="type-badge draft">Draft</span>
                            @else
                                <span class="type-badge published">Dipublikasi</span>
                            @endif
                        </div>
                    </div>
                    <div class="exam-item-content">
                        <div class="exam-item-meta">
                            <span class="exam-class">{{ $exam->KelasMapel->Kelas->name ?? 'N/A' }}</span>
                            <span class="exam-subject">{{ $exam->KelasMapel->Mapel->name ?? 'N/A' }}</span>
                            <span class="exam-date">{{ $exam->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="exam-item-actions">
                            <button class="btn-action edit" onclick="editExam({{ $exam->id }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-action view" onclick="viewExam({{ $exam->id }})">
                                <i class="fas fa-eye"></i>
                            </button>
                            @if($exam->isHidden)
                                <button class="btn-action publish" onclick="publishExam({{ $exam->id }})">
                                    <i class="fas fa-upload"></i>
                                </button>
                            @endif
                            <button class="btn-action delete" onclick="deleteExam({{ $exam->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>Belum ada ujian</h3>
                    <p>Mulai buat ujian pertama Anda dengan memilih salah satu tipe di atas</p>
                </div>
                @endforelse
            </div>
        </div>

<script>
// Navigation functions
function navigateToExamType(type) {
    const routes = {
        'multiple-choice': "{{ route('superadmin.exam-management.create-multiple-choice') }}",
        'essay': "{{ route('superadmin.exam-management.create-essay') }}"
    };
    
    if (routes[type]) {
        window.location.href = routes[type];
    }
}

// Exam action functions
function editExam(examId) {
    console.log('Edit exam:', examId);
    // Implement edit functionality
}

function viewExam(examId) {
    console.log('View exam:', examId);
    // Implement view functionality
}

function publishExam(examId) {
    if (confirm('Apakah Anda yakin ingin mempublikasikan ujian ini?')) {
        console.log('Publish exam:', examId);
        // Implement publish functionality
    }
}

function deleteExam(examId) {
    if (confirm('Apakah Anda yakin ingin menghapus ujian ini?')) {
        console.log('Delete exam:', examId);
        // Implement delete functionality
    }
}
</script>
@endsection