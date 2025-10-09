@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - Detail Ujian')

@section('styles')
<style>
    .exam-view-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .exam-view-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
    }

    .exam-view-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .exam-view-subtitle {
        opacity: 0.9;
        font-size: 1rem;
    }

    .exam-details {
        background: #1e293b;
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid #334155;
        margin-bottom: 2rem;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .detail-item {
        background: #2a2a3e;
        border-radius: 8px;
        padding: 1.5rem;
        border-left: 4px solid #667eea;
    }

    .detail-item h4 {
        color: #ffffff;
        margin-bottom: 0.5rem;
        font-size: 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-item p {
        color: #cbd5e1;
        margin: 0.25rem 0;
        font-size: 0.9rem;
    }

    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .status-draft {
        background: #fef3c7;
        color: #92400e;
    }

    .status-published {
        background: #d1fae5;
        color: #065f46;
    }

    .exam-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        flex-wrap: wrap;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: #667eea;
        color: white;
    }

    .btn-primary:hover {
        background: #5a67d8;
    }

    .btn-secondary {
        background: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
    }

    .btn-success {
        background: #10b981;
        color: white;
    }

    .btn-success:hover {
        background: #059669;
    }

    .btn-warning {
        background: #f59e0b;
        color: white;
    }

    .btn-warning:hover {
        background: #d97706;
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    .exam-stats {
        background: #2a2a3e;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .stat-item {
        text-align: center;
        padding: 1rem;
        background: #1e293b;
        border-radius: 8px;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #cbd5e1;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
        
        .exam-actions {
            flex-direction: column;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endsection

@section('content')
<div class="exam-view-container">
    <div class="exam-view-header">
        <h1 class="exam-view-title">
            <i class="fas fa-eye"></i>
            Detail Ujian
        </h1>
        <p class="exam-view-subtitle">Informasi lengkap tentang ujian yang dipilih</p>
    </div>

    <div class="exam-details">
        <div class="detail-grid">
            <div class="detail-item">
                <h4><i class="fas fa-book"></i> Informasi Dasar</h4>
                <p><strong>Judul:</strong> {{ $exam->name }}</p>
                <p><strong>Kelas:</strong> {{ $exam->KelasMapel->Kelas->name ?? 'N/A' }}</p>
                <p><strong>Mata Pelajaran:</strong> {{ $exam->KelasMapel->Mapel->name ?? 'N/A' }}</p>
                <p><strong>Tipe:</strong> 
                    @if($exam->tipe == 1)
                        Pilihan Ganda
                    @elseif($exam->tipe == 2)
                        Essay
                    @else
                        Campuran
                    @endif
                </p>
            </div>

            <div class="detail-item">
                <h4><i class="fas fa-clock"></i> Waktu & Durasi</h4>
                <p><strong>Durasi:</strong> {{ $exam->time }} menit</p>
                <p><strong>Tanggal Mulai:</strong> {{ $exam->due ? $exam->due->format('d M Y H:i') : 'N/A' }}</p>
                <p><strong>Dibuat:</strong> {{ $exam->created_at->format('d M Y H:i') }}</p>
                <p><strong>Diperbarui:</strong> {{ $exam->updated_at->format('d M Y H:i') }}</p>
            </div>

            <div class="detail-item">
                <h4><i class="fas fa-info-circle"></i> Status & Deskripsi</h4>
                <p><strong>Status:</strong> 
                    @if($exam->isHidden)
                        <span class="status-badge status-draft">Draft</span>
                    @else
                        <span class="status-badge status-published">Dipublikasikan</span>
                    @endif
                </p>
                <p><strong>Deskripsi:</strong></p>
                <div style="background: #1e293b; padding: 1rem; border-radius: 6px; margin-top: 0.5rem;">
                    {!! $exam->content ?: '<em style="color: #6b7280;">Tidak ada deskripsi</em>' !!}
                </div>
            </div>
        </div>

        <div class="exam-stats">
            <h4 style="color: #ffffff; margin-bottom: 1rem;">
                <i class="fas fa-chart-bar"></i> Statistik Ujian
            </h4>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value">{{ $exam->participants_count ?? 0 }}</div>
                    <div class="stat-label">Peserta</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $exam->average_score ?? 0 }}</div>
                    <div class="stat-label">Rata-rata Nilai</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $exam->completion_rate ?? 0 }}%</div>
                    <div class="stat-label">Tingkat Penyelesaian</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $exam->questions_count ?? 0 }}</div>
                    <div class="stat-label">Jumlah Soal</div>
                </div>
            </div>
        </div>

        <div class="exam-actions">
            <a href="{{ route('superadmin.exam-management') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
            
            <a href="{{ route('superadmin.exam-management.edit', $exam->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i>
                Edit Ujian
            </a>
            
            @if($exam->isHidden)
                <button class="btn btn-warning" onclick="publishExam({{ $exam->id }})">
                    <i class="fas fa-paper-plane"></i>
                    Publikasikan
                </button>
            @else
                <button class="btn btn-success" onclick="unpublishExam({{ $exam->id }})">
                    <i class="fas fa-eye-slash"></i>
                    Batalkan Publikasi
                </button>
            @endif
            
            <a href="{{ route('superadmin.exam-management.results', $exam->id) }}" class="btn btn-primary">
                <i class="fas fa-chart-line"></i>
                Lihat Hasil
            </a>
            
            <button class="btn btn-danger" onclick="deleteExam({{ $exam->id }})">
                <i class="fas fa-trash"></i>
                Hapus Ujian
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function publishExam(examId) {
    if (confirm('Apakah Anda yakin ingin mempublikasikan ujian ini?')) {
        fetch(`{{ url('/superadmin/exam-management') }}/${examId}/publish`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal mempublikasikan ujian: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mempublikasikan ujian');
        });
    }
}

function unpublishExam(examId) {
    if (confirm('Apakah Anda yakin ingin membatalkan publikasi ujian ini?')) {
        fetch(`{{ url('/superadmin/exam-management') }}/${examId}/unpublish`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal membatalkan publikasi ujian: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat membatalkan publikasi ujian');
        });
    }
}

function deleteExam(examId) {
    if (confirm('Apakah Anda yakin ingin menghapus ujian ini? Tindakan ini tidak dapat dibatalkan.')) {
        fetch(`{{ url('/superadmin/exam-management') }}/${examId}/delete`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => {
            if (response.ok) {
                window.location.href = "{{ route('superadmin.exam-management') }}";
            } else {
                alert('Gagal menghapus ujian');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus ujian');
        });
    }
}
</script>
@endsection
