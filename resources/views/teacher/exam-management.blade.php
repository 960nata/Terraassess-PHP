@extends('layouts.unified-layout')

@section('title', 'Manajemen Ujian Guru')

@section('styles')
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

        /* Mobile Cards */
        .mobile-cards {
            display: none;
        }

        .exam-card {
            background-color: #1e293b;
            border: 1px solid #334155;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .exam-card:hover {
            background-color: #2a2a3e;
            border-color: #475569;
        }

        .exam-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .exam-card-title {
            color: #ffffff;
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
            flex: 1;
        }

        .exam-card-badges {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .exam-card-description {
            color: #94a3b8;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        .exam-card-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #cbd5e1;
            font-size: 0.875rem;
        }

        .info-item i {
            color: #667eea;
            width: 16px;
        }

        .exam-card-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .exam-card-actions button {
            flex: 1;
            min-width: 80px;
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
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

        /* Exam Type Cards - Modern Design */
        .exam-type-cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .exam-type-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .exam-type-card:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(59, 130, 246, 0.5);
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }

        .exam-type-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .exam-type-card.essay::before {
            background: linear-gradient(90deg, #8b5cf6, #a855f7);
        }


        .exam-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .exam-card-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .exam-type-card.essay .exam-card-icon {
            background: linear-gradient(135deg, #8b5cf6, #a855f7);
            box-shadow: 0 8px 20px rgba(139, 92, 246, 0.3);
        }


        .exam-card-stats {
            text-align: right;
        }

        .exam-count {
            display: block;
            font-size: 1.75rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1;
        }

        .exam-label {
            font-size: 0.875rem;
            color: #94a3b8;
            margin-top: 0.25rem;
        }

        .exam-card-content {
            margin-bottom: 1.5rem;
        }

        .exam-type-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.75rem;
            line-height: 1.3;
        }

        .exam-type-description {
            color: #94a3b8;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .exam-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .exam-card-action {
            color: #667eea;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .exam-type-card.essay .exam-card-action {
            color: #8b5cf6;
        }


        .arrow-icon {
            transition: transform 0.3s ease;
        }

        .exam-type-card:hover .arrow-icon {
            transform: translateX(4px);
        }

        /* Tablet Responsive - 2x1 Grid */
        @media (max-width: 1024px) {
            .exam-type-cards {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.25rem;
            }
        }

        @media (max-width: 768px) {
            .filter-row {
                grid-template-columns: 1fr 1fr;
                gap: 0.75rem;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .exam-type-cards {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .exam-actions {
                flex-direction: column;
            }

            /* Show mobile cards, hide desktop table */
            .desktop-table {
                display: none;
            }
            
            .mobile-cards {
                display: block;
            }
            
            .exam-card-info {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
            
            .exam-card-actions {
                flex-direction: column;
            }
            
            .exam-card-actions button {
                flex: none;
                width: 100%;
            }
        }

        /* Mobile - 1x2 Grid */
        @media (max-width: 480px) {
            .filter-row {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
            
            .exam-type-cards {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
            
            .exam-type-card {
                padding: 1.5rem;
            }
            
            .exam-type-title {
                font-size: 1rem;
            }
            
            .exam-type-description {
                font-size: 0.8rem;
            }

            /* Mobile cards adjustments */
            .exam-card {
                padding: 1rem;
            }
            
            .exam-card-title {
                font-size: 1rem;
            }
            
            .exam-card-description {
                font-size: 0.85rem;
            }
        }

        /* Very Small Mobile - Tetap 1x2 */
        @media (max-width: 360px) {
            .exam-type-cards {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
            
            .exam-type-card {
                padding: 1rem;
            }
            
            .exam-type-title {
                font-size: 0.9rem;
            }
            
            .exam-type-description {
                font-size: 0.75rem;
            }
        }

        /* SIMPLE MOBILE SIDEBAR - GUARANTEED TO WORK */
        @media (max-width: 1024px) {
            .sidebar {
                position: fixed !important;
                top: 70px !important;
                left: 0 !important;
                height: calc(100vh - 70px) !important;
                width: 280px !important;
                z-index: 999 !important;
                transform: translateX(-100%) !important;
                transition: transform 0.3s ease !important;
                background: #1e293b !important;
            }
            
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
            }
            
            .mobile-overlay {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                background: rgba(0, 0, 0, 0.5) !important;
                z-index: 998 !important;
                display: none !important;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 280px !important;
                z-index: 999 !important;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 100% !important;
            }
        }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-chart-line"></i>
        Manajemen Ujian
    </h1>
    <p class="page-description">Kelola ujian per kelas dengan kategorisasi dan tingkat kesulitan</p>
</div>

<!-- Exam Type Cards -->
<div class="exam-type-cards">
    <div class="exam-type-card" onclick="createMultipleChoiceExam()">
        <div class="exam-card-header">
            <div class="exam-card-icon">
                <i class="fas fa-list-ul"></i>
            </div>
            <div class="exam-card-stats">
                <span class="exam-count">{{ $exams->where('tipe', 1)->count() }}</span>
                <span class="exam-label">Ujian</span>
            </div>
        </div>
        <div class="exam-card-content">
            <h3 class="exam-type-title">Pilihan Ganda</h3>
            <p class="exam-type-description">Buat ujian dengan soal pilihan ganda untuk evaluasi cepat</p>
        </div>
        <div class="exam-card-footer">
            <span class="exam-card-action">Buat Ujian <i class="fas fa-arrow-right arrow-icon"></i></span>
        </div>
    </div>

    <div class="exam-type-card essay" onclick="createEssayExam()">
        <div class="exam-card-header">
            <div class="exam-card-icon">
                <i class="fas fa-edit"></i>
            </div>
            <div class="exam-card-stats">
                <span class="exam-count">{{ $exams->where('tipe', 2)->count() }}</span>
                <span class="exam-label">Ujian</span>
            </div>
        </div>
        <div class="exam-card-content">
            <h3 class="exam-type-title">Essay</h3>
            <p class="exam-type-description">Buat ujian essay untuk penilaian mendalam</p>
        </div>
        <div class="exam-card-footer">
            <span class="exam-card-action">Buat Ujian <i class="fas fa-arrow-right arrow-icon"></i></span>
        </div>
    </div>
</div>

<!-- Exam Filters -->
<div class="exam-filters">
    <form action="{{ route('teacher.exam-management') }}" method="GET" class="filter-form">
        <div class="filter-row">
            <div class="form-group">
                <select id="filter_class" name="filter_class">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ ($filters['filter_class'] ?? '') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <select id="filter_subject" name="filter_subject">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ ($filters['filter_subject'] ?? '') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <select id="filter_status" name="filter_status">
                    <option value="">Semua Status</option>
                    <option value="active" {{ ($filters['filter_status'] ?? '') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="draft" {{ ($filters['filter_status'] ?? '') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-search"></i>
                    Filter
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Exams Table -->
<div class="exams-table">
    <h2 style="color: #ffffff; margin-bottom: 1.5rem; font-size: 1.25rem;">
        <i class="fas fa-list me-2"></i>Daftar Ujian
    </h2>
    
    <!-- Desktop Table -->
    <div class="table-responsive desktop-table">
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
                        <div class="exam-title">{{ $exam->name ?? 'Ujian Tanpa Judul' }}</div>
                        <div class="exam-description">{{ \Illuminate\Support\Str::limit($exam->deskripsi ?? 'Tidak ada deskripsi', 50) }}</div>
                    </td>
                    <td>{{ $exam->kelasMapel->kelas->name ?? 'N/A' }}</td>
                    <td>{{ $exam->kelasMapel->mapel->name ?? 'N/A' }}</td>
                    <td>
                        <span class="type-badge">
                            @if($exam->tipe == 1)
                                Pilihan Ganda
                            @elseif($exam->tipe == 2)
                                Essay
                            @else
                                Campuran
                            @endif
                        </span>
                    </td>
                    <td>
                        <span class="status-badge {{ $exam->isHidden ? 'status-draft' : 'status-active' }}">
                            {{ $exam->isHidden ? 'Draft' : 'Aktif' }}
                        </span>
                    </td>
                    <td>{{ $exam->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <div class="exam-actions">
                            <button class="btn-secondary" onclick="editExam({{ $exam->id }})">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn-success" onclick="viewExam({{ $exam->id }})">
                                <i class="fas fa-eye"></i> Lihat
                            </button>
                            @if($exam->isHidden)
                            <button class="btn-warning" onclick="publishExam({{ $exam->id }})">
                                <i class="fas fa-paper-plane"></i> Publikasi
                            </button>
                            @endif
                            <button class="btn-danger" onclick="deleteExam({{ $exam->id }})">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 2rem; color: #94a3b8;">
                        <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                        Belum ada ujian. Mulai buat ujian pertama Anda!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div class="mobile-cards">
        @forelse($exams as $exam)
        <div class="exam-card">
            <div class="exam-card-header">
                <h3 class="exam-card-title">{{ $exam->name ?? 'Ujian Tanpa Judul' }}</h3>
                <div class="exam-card-badges">
                    <span class="type-badge">
                        @if($exam->tipe == 1)
                            Pilihan Ganda
                        @elseif($exam->tipe == 2)
                            Essay
                        @else
                            Campuran
                        @endif
                    </span>
                    <span class="status-badge {{ $exam->isHidden ? 'status-draft' : 'status-active' }}">
                        {{ $exam->isHidden ? 'Draft' : 'Aktif' }}
                    </span>
                </div>
            </div>
            
            <p class="exam-card-description">{{ \Illuminate\Support\Str::limit($exam->deskripsi ?? 'Tidak ada deskripsi', 100) }}</p>
            
            <div class="exam-card-info">
                <div class="info-item">
                    <i class="fas fa-graduation-cap"></i>
                    <span>{{ $exam->kelasMapel->kelas->name ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-book"></i>
                    <span>{{ $exam->kelasMapel->mapel->name ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-calendar"></i>
                    <span>{{ $exam->created_at->format('d M Y H:i') }}</span>
                </div>
            </div>
            
            <div class="exam-card-actions">
                <button class="btn-secondary" onclick="editExam({{ $exam->id }})">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn-success" onclick="viewExam({{ $exam->id }})">
                    <i class="fas fa-eye"></i> Lihat
                </button>
                @if($exam->isHidden)
                <button class="btn-warning" onclick="publishExam({{ $exam->id }})">
                    <i class="fas fa-paper-plane"></i> Publikasi
                </button>
                @endif
                <button class="btn-danger" onclick="deleteExam({{ $exam->id }})">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 3rem; color: #94a3b8;">
            <i class="fas fa-inbox" style="font-size: 4rem; margin-bottom: 1rem; display: block;"></i>
            <h3 style="color: #ffffff; margin-bottom: 0.5rem;">Belum Ada Ujian</h3>
            <p>Mulai buat ujian pertama Anda dengan memilih salah satu tipe di atas</p>
        </div>
        @endforelse
    </div>
</div>

<script>
// Exam creation functions
function createMultipleChoiceExam() {
    // Redirect ke halaman pembuatan soal pilihan ganda
    window.location.href = "{{ route('teacher.exam-create-multiple-choice') }}";
}

function createEssayExam() {
    // Redirect ke halaman pembuatan soal essay
    window.location.href = "{{ route('teacher.exam-create-essay') }}";
}

// Exam action functions
function editExam(examId) {
    window.location.href = "{{ url('teacher/exam') }}/" + examId + "/edit";
}

function viewExam(examId) {
    window.location.href = "{{ route('teacher.exam-management') }}/" + examId;
}

function publishExam(examId) {
    if (confirm('Apakah Anda yakin ingin mempublikasikan ujian ini?')) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('teacher.exam-management') }}/" + examId + "/publish";
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PUT';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function deleteExam(examId) {
    if (confirm('Apakah Anda yakin ingin menghapus ujian ini?')) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ url('teacher/exam') }}/" + examId;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
