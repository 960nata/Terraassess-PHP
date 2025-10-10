@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Research Projects')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-flask"></i>
        Research Projects
    </h1>
    <p class="page-description">Jelajahi proyek penelitian IoT yang tersedia untuk kelas Anda</p>
</div>

<div class="projects-container">
    @if($projects->count() > 0)
        @foreach($projects as $project)
        <div class="project-card">
            <div class="project-header">
                <div>
                    <h2 class="project-title">{{ $project->title }}</h2>
                    <span class="project-status {{ $project->status_badge_class }}">{{ $project->status_label }}</span>
                </div>
            </div>

            <p class="project-description">
                {{ $project->description ?: 'Proyek penelitian IoT untuk mengumpulkan dan menganalisis data sensor dari lingkungan sekitar.' }}
            </p>

            <div class="project-details">
                <div class="detail-item">
                    <div class="detail-label">Kelas</div>
                    <div class="detail-value">{{ $project->kelas->name ?? 'Unknown' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Guru Pembimbing</div>
                    <div class="detail-value">{{ $project->pengajar->name ?? 'Unknown' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Tanggal Mulai</div>
                    <div class="detail-value">{{ $project->start_date ? $project->start_date->format('d M Y') : 'N/A' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Durasi</div>
                    <div class="detail-value">{{ $project->duration }} hari</div>
                </div>
            </div>

            <div class="project-actions">
                <a href="#" class="action-btn" onclick="viewProjectData({{ $project->id }})">
                    <i class="fas fa-chart-line"></i>
                    Lihat Data
                </a>
                <a href="#" class="action-btn secondary" onclick="viewProjectDetails({{ $project->id }})">
                    <i class="fas fa-info-circle"></i>
                    Detail Proyek
                </a>
            </div>
        </div>
        @endforeach
    @else
        <div class="empty-state">
            <i class="fas fa-flask"></i>
            <h3>Belum Ada Proyek Penelitian</h3>
            <p>Belum ada proyek penelitian IoT yang tersedia untuk kelas Anda. Silakan hubungi guru untuk informasi lebih lanjut.</p>
        </div>
    @endif

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h3>Quick Actions</h3>
        <div class="actions-grid">
            <a href="{{ route('student.dashboard') }}" class="action-card">
                <div class="action-icon blue">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <h4 class="action-title">Dashboard</h4>
                <p class="action-description">Kembali ke dashboard utama</p>
            </a>

            <a href="{{ route('student.tugas') }}" class="action-card">
                <div class="action-icon green">
                    <i class="fas fa-tasks"></i>
                </div>
                <h4 class="action-title">Tugas</h4>
                <p class="action-description">Lihat tugas yang tersedia</p>
            </a>

            <a href="{{ route('student.materi') }}" class="action-card">
                <div class="action-icon purple">
                    <i class="fas fa-book"></i>
                </div>
                <h4 class="action-title">Materi</h4>
                <p class="action-description">Akses materi pembelajaran</p>
            </a>

            <a href="{{ route('student.ujian') }}" class="action-card">
                <div class="action-icon orange">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h4 class="action-title">Ujian</h4>
                <p class="action-description">Ikuti ujian yang tersedia</p>
            </a>
        </div>
    </div>
</div>
@endsection

@section('additional-styles')
<style>
    .projects-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .project-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 2rem;
        margin: 2rem 0;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
    }

    .project-card:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-2px);
    }

    .project-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }

    .project-title {
        color: #ffffff;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .project-status {
        background: rgba(16, 185, 129, 0.2);
        color: #10b981;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .project-description {
        color: #cbd5e1;
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        background: rgba(255, 255, 255, 0.05);
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid #3b82f6;
    }

    .project-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .detail-item {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        padding: 1rem;
        transition: all 0.3s ease;
    }

    .detail-item:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .detail-label {
        color: #94a3b8;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
        font-weight: 500;
    }

    .detail-value {
        color: #ffffff;
        font-weight: 600;
        font-size: 1rem;
    }

    .project-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .action-btn {
        background: rgba(59, 130, 246, 0.2);
        border: 1px solid rgba(59, 130, 246, 0.3);
        color: #3b82f6;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .action-btn:hover {
        background: rgba(59, 130, 246, 0.3);
        border-color: rgba(59, 130, 246, 0.5);
        color: #60a5fa;
        transform: translateY(-1px);
    }

    .action-btn.secondary {
        background: rgba(107, 114, 128, 0.2);
        border-color: rgba(107, 114, 128, 0.3);
        color: #9ca3af;
    }

    .action-btn.secondary:hover {
        background: rgba(107, 114, 128, 0.3);
        border-color: rgba(107, 114, 128, 0.5);
        color: #d1d5db;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: rgba(255, 255, 255, 0.6);
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        margin: 2rem 0;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        opacity: 0.5;
        color: #3b82f6;
    }

    .empty-state h3 {
        color: #ffffff;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .empty-state p {
        font-size: 1rem;
        line-height: 1.6;
    }

    .quick-actions {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 2rem;
        margin: 2rem 0;
    }

    .quick-actions h3 {
        color: #ffffff;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .quick-actions h3::before {
        content: "⚡";
        font-size: 1.5rem;
    }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .action-card {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 1.5rem;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .action-card:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
        border-color: rgba(255, 255, 255, 0.2);
    }

    .action-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.5rem;
        color: white;
    }

    .action-icon.blue { background-color: #3b82f6; }
    .action-icon.green { background-color: #10b981; }
    .action-icon.purple { background-color: #8b5cf6; }
    .action-icon.orange { background-color: #f59e0b; }

    .action-title {
        color: #ffffff;
        font-size: 1.125rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .action-description {
        color: #cbd5e1;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .projects-container {
            padding: 0 0.5rem;
        }

        .project-card {
            padding: 1.5rem;
            margin: 1rem 0;
        }

        .project-header {
            flex-direction: column;
            gap: 1rem;
        }

        .project-details {
            grid-template-columns: 1fr;
        }

        .project-actions {
            flex-direction: column;
        }

        .action-btn {
            justify-content: center;
        }

        .actions-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .action-card {
            padding: 1rem;
        }

        .action-icon {
            width: 36px;
            height: 36px;
            font-size: 1.25rem;
        }

        .action-title {
            font-size: 1rem;
        }

        .action-description {
            font-size: 0.8rem;
        }
    }

    @media (max-width: 480px) {
        .actions-grid {
            grid-template-columns: 1fr;
        }

        .project-card {
            padding: 1rem;
        }

        .empty-state {
            padding: 2rem 1rem;
        }

        .empty-state i {
            font-size: 3rem;
        }

        .empty-state h3 {
            font-size: 1.25rem;
        }
    }
</style>
@endsection

@section('additional-scripts')
<script>
    // View project data
    function viewProjectData(projectId) {
        alert('Fitur lihat data proyek akan segera tersedia untuk proyek ID: ' + projectId);
    }

    // View project details
    function viewProjectDetails(projectId) {
        alert('Fitur detail proyek akan segera tersedia untuk proyek ID: ' + projectId);
    }
</script>
@endsection
