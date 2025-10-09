@extends('layouts.unified-layout-new')

@section('title', $title)

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="ph-chart-line"></i>
        Hasil Ujian
    </h1>
    <p class="page-description">Lihat hasil dan statistik ujian {{ $exam->name }}</p>
</div>

<!-- Exam Info Card -->
<div class="card mb-6">
    <div class="card-header">
        <h3 class="card-title">
            <i class="ph-info"></i>
            Informasi Ujian
        </h3>
    </div>
    <div class="card-body">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="info-item">
                <div class="info-label">Nama Ujian</div>
                <div class="info-value">{{ $exam->name ?? 'Ujian' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Kelas</div>
                <div class="info-value">{{ $exam->KelasMapel->Kelas->name ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Mata Pelajaran</div>
                <div class="info-value">{{ $exam->KelasMapel->Mapel->name ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Tipe Ujian</div>
                <div class="info-value">
                    @php
                        $typeMap = [1 => 'Pilihan Ganda', 2 => 'Essay', 3 => 'Campuran'];
                        $type = $typeMap[$exam->tipe ?? 1] ?? 'Pilihan Ganda';
                    @endphp
                    {{ $type }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="card">
        <div class="card-icon blue">
            <i class="ph-users"></i>
        </div>
        <h3 class="card-title">Total Peserta</h3>
        <p class="card-description">{{ $statistics['total_participants'] }} peserta</p>
    </div>
    <div class="card">
        <div class="card-icon green">
            <i class="ph-check-circle"></i>
        </div>
        <h3 class="card-title">Selesai</h3>
        <p class="card-description">{{ $statistics['completed'] }} peserta</p>
    </div>
    <div class="card">
        <div class="card-icon purple">
            <i class="ph-chart-line"></i>
        </div>
        <h3 class="card-title">Rata-rata Nilai</h3>
        <p class="card-description">{{ number_format($statistics['average_score'], 2) }}</p>
    </div>
    <div class="card">
        <div class="card-icon orange">
            <i class="ph-trophy"></i>
        </div>
        <h3 class="card-title">Nilai Tertinggi</h3>
        <p class="card-description">{{ $statistics['highest_score'] }}</p>
    </div>
</div>

<!-- Results Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="ph-list"></i>
            Daftar Hasil Ujian
        </h3>
    </div>
    <div class="card-body">
        @if($participants->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Peserta</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Nilai</th>
                            <th>Waktu Mulai</th>
                            <th>Waktu Selesai</th>
                            <th>Durasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($participants as $index => $participant)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $participant->name ?? 'N/A' }}</td>
                                <td>{{ $participant->email ?? 'N/A' }}</td>
                                <td>
                                    @if($participant->status === 'completed')
                                        <span class="status-badge status-completed">Selesai</span>
                                    @elseif($participant->status === 'in_progress')
                                        <span class="status-badge status-active">Sedang Mengerjakan</span>
                                    @else
                                        <span class="status-badge status-draft">Belum Mulai</span>
                                    @endif
                                </td>
                                <td>
                                    @if($participant->score !== null)
                                        <span class="score-badge">{{ $participant->score }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td>{{ $participant->started_at ? \Carbon\Carbon::parse($participant->started_at)->format('d M Y H:i') : '-' }}</td>
                                <td>{{ $participant->completed_at ? \Carbon\Carbon::parse($participant->completed_at)->format('d M Y H:i') : '-' }}</td>
                                <td>
                                    @if($participant->started_at && $participant->completed_at)
                                        {{ \Carbon\Carbon::parse($participant->started_at)->diffForHumans(\Carbon\Carbon::parse($participant->completed_at), true) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="flex gap-2">
                                        <button class="btn-secondary btn-sm" onclick="viewDetail('{{ $participant->id }}')">
                                            <i class="ph-eye"></i> Detail
                                        </button>
                                        @if($participant->status === 'completed')
                                            <button class="btn-success btn-sm" onclick="downloadCertificate('{{ $participant->id }}')">
                                                <i class="ph-download"></i> Sertifikat
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="ph-users"></i>
                </div>
                <h3>Belum Ada Peserta</h3>
                <p>Belum ada peserta yang mengikuti ujian ini.</p>
            </div>
        @endif
    </div>
</div>

<!-- Action Buttons -->
<div class="flex justify-between items-center mt-6">
    <a href="{{ route('admin.exam-management') }}" class="btn-secondary">
        <i class="ph-arrow-left"></i>
        Kembali ke Daftar Ujian
    </a>
    
    <div class="flex gap-3">
        <button class="btn-primary" onclick="exportResults()">
            <i class="ph-download"></i>
            Export Hasil
        </button>
        <button class="btn-success" onclick="sendResults()">
            <i class="ph-paper-plane"></i>
            Kirim Hasil
        </button>
    </div>
</div>

<style>
.info-item {
    padding: 1rem;
    background-color: #1f2937;
    border-radius: 0.5rem;
    border: 1px solid #374151;
}

.info-label {
    font-size: 0.875rem;
    color: #9ca3af;
    margin-bottom: 0.25rem;
}

.info-value {
    font-size: 1.125rem;
    font-weight: 600;
    color: white;
}

.score-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.125rem 0.625rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    background-color: #10b981;
    color: white;
}

.empty-state {
    text-align: center;
    padding: 3rem 0;
}

.empty-state-icon {
    font-size: 3.75rem;
    color: #9ca3af;
    margin-bottom: 1rem;
}

.empty-state h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: white;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #9ca3af;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}
</style>

<script>
function viewDetail(participantId) {
    // Implement view participant detail
    console.log('View detail for participant:', participantId);
    alert('Fitur detail peserta belum tersedia.');
}

function downloadCertificate(participantId) {
    // Implement download certificate
    console.log('Download certificate for participant:', participantId);
    alert('Fitur download sertifikat belum tersedia.');
}

function exportResults() {
    // Implement export results
    console.log('Export results');
    alert('Fitur export hasil belum tersedia.');
}

function sendResults() {
    // Implement send results
    console.log('Send results');
    alert('Fitur kirim hasil belum tersedia.');
}
</script>
@endsection
