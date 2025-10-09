@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - Kelas Saya')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-users"></i>
        Kelas Saya
    </h1>
    <p class="page-description">Informasi kelas dan teman sekelas Anda</p>
</div>

<!-- Class Information -->
<div class="class-info-section">
    <div class="class-header">
        <div class="class-icon">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="class-details">
            <h2 class="class-name">{{ $kelas->name ?? 'Kelas Tidak Ditemukan' }}</h2>
            <p class="class-description">{{ $kelas->description ?? 'Deskripsi kelas tidak tersedia' }}</p>
            <div class="class-meta">
                <span class="meta-item">
                    <i class="fas fa-calendar"></i>
                    {{ $kelas->created_at ? $kelas->created_at->format('d M Y') : 'N/A' }}
                </span>
                <span class="meta-item">
                    <i class="fas fa-users"></i>
                    {{ $totalStudents }} Siswa
                </span>
            </div>
        </div>
    </div>
    
    <!-- Class Statistics -->
    <div class="class-stats">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-number">{{ $totalSubjects }}</div>
            <div class="stat-label">Mata Pelajaran</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="stat-number">{{ $totalTugas }}</div>
            <div class="stat-label">Total Tugas</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-number">{{ $totalUjian }}</div>
            <div class="stat-label">Total Ujian</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="stat-number">{{ $totalMateri }}</div>
            <div class="stat-label">Total Materi</div>
        </div>
    </div>
</div>

<!-- Subjects Section -->
<div class="subjects-section">
    <div class="section-header">
        <h3 class="section-title">
            <i class="fas fa-book"></i>
            Mata Pelajaran
        </h3>
    </div>
    
    @if($mapelKelas->count() > 0)
        <div class="subjects-grid">
            @foreach($mapelKelas as $mapel)
                <div class="subject-card">
                    <div class="subject-header">
                        <div class="subject-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="subject-info">
                            <h4 class="subject-name">{{ $mapel->mapel_name }}</h4>
                            <p class="subject-teacher">
                                <i class="fas fa-chalkboard-teacher"></i>
                                {{ $mapel->pengajar_name }}
                            </p>
                        </div>
                    </div>
                    <div class="subject-actions">
                        <a href="{{ route('student.materi') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-book-open"></i>
                            Materi
                        </a>
                        <a href="{{ route('student.tugas') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-tasks"></i>
                            Tugas
                        </a>
                        <a href="{{ route('student.ujian') }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-file-alt"></i>
                            Ujian
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-book-slash"></i>
            <h4>Belum Ada Mata Pelajaran</h4>
            <p>Kelas Anda belum memiliki mata pelajaran yang ditetapkan.</p>
        </div>
    @endif
</div>

<!-- Classmates Section -->
<div class="classmates-section">
    <div class="section-header">
        <h3 class="section-title">
            <i class="fas fa-user-friends"></i>
            Teman Sekelas
        </h3>
        <span class="classmates-count">{{ $classmates->count() }} Teman</span>
    </div>
    
    @if($classmates->count() > 0)
        <div class="classmates-grid">
            @foreach($classmates as $classmate)
                <div class="classmate-card">
                    <div class="classmate-avatar">
                        <img src="{{ $classmate->gambar ? asset('storage/' . $classmate->gambar) : asset('asset/icons/profile-women.svg') }}" 
                             alt="{{ $classmate->name }}" 
                             class="avatar-img">
                    </div>
                    <div class="classmate-info">
                        <h5 class="classmate-name">{{ $classmate->name }}</h5>
                        <p class="classmate-email">{{ $classmate->email }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-user-friends"></i>
            <h4>Belum Ada Teman Sekelas</h4>
            <p>Anda adalah satu-satunya siswa di kelas ini.</p>
        </div>
    @endif
</div>

<style>
        /* Class Info Styles */
        .class-info-section {
    background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .class-header {
            display: flex;
            align-items: center;
    gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .class-icon {
    width: 80px;
    height: 80px;
    border-radius: 20px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
    font-size: 2rem;
            color: white;
    flex-shrink: 0;
}

.class-details {
    flex-grow: 1;
}

.class-name {
    font-size: 2rem;
    font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
        }

.class-description {
    color: #94a3b8;
    font-size: 1rem;
    margin-bottom: 1rem;
}

.class-meta {
    display: flex;
    gap: 2rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
            color: #94a3b8;
            font-size: 0.875rem;
        }

.meta-item i {
    color: #3b82f6;
}

.class-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

/* Subjects Section */
.subjects-section {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

.section-header {
            display: flex;
    justify-content: space-between;
            align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.section-title {
            display: flex;
            align-items: center;
    gap: 0.5rem;
    color: white;
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
}

.section-title i {
    color: #3b82f6;
}

.classmates-count {
    color: #94a3b8;
            font-size: 0.875rem;
    background: rgba(255, 255, 255, 0.1);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
}

.subjects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.subject-card {
    background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.subject-card:hover {
    transform: translateY(-2px);
    background: rgba(255, 255, 255, 0.1);
}

.subject-header {
            display: flex;
            align-items: center;
            gap: 1rem;
    margin-bottom: 1rem;
}

.subject-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, #3b82f6, #2563eb);
            display: flex;
            align-items: center;
            justify-content: center;
    font-size: 1.25rem;
            color: white;
    flex-shrink: 0;
}

.subject-info {
    flex-grow: 1;
}

.subject-name {
            color: white;
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

.subject-teacher {
            color: #94a3b8;
            font-size: 0.875rem;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.subject-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.75rem;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
}

.btn-success {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.btn-warning {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.btn-sm:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

/* Classmates Section */
.classmates-section {
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 2rem;
            }

            .classmates-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

.classmate-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1rem;
    text-align: center;
    transition: all 0.3s ease;
}

.classmate-card:hover {
    transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.1);
        }

.classmate-avatar {
    margin-bottom: 0.75rem;
        }

        .avatar-img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
            object-fit: cover;
    border: 3px solid rgba(59, 130, 246, 0.3);
}

.classmate-name {
            color: white;
    font-size: 0.875rem;
            font-weight: 600;
    margin-bottom: 0.25rem;
        }

.classmate-email {
    color: #94a3b8;
            font-size: 0.75rem;
    margin: 0;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem;
    color: #94a3b8;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    display: block;
    color: #64748b;
}

.empty-state h4 {
    color: white;
    margin-bottom: 0.5rem;
}

.empty-state p {
    margin: 0;
            font-size: 0.875rem;
        }

/* Responsive Design */
@media (max-width: 768px) {
    .class-header {
        flex-direction: column;
        text-align: center;
    }
    
    .class-meta {
        justify-content: center;
    }
    
    .class-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .subjects-grid {
        grid-template-columns: 1fr;
    }
    
    .classmates-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .subject-actions {
        justify-content: center;
            }
        }
    </style>
@endsection