@extends('layouts.unified-layout')

@section('title', 'Daftar Ujian')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="ph-exam"></i>
        Daftar Ujian
    </h1>
    <p class="page-description">
        Kelola dan pantau semua ujian yang telah dibuat untuk kelas yang Anda ajar.
    </p>
</div>

<!-- Welcome Banner -->
<div class="welcome-banner">
    <div class="welcome-icon">
        <i class="ph-clipboard-text"></i>
    </div>
    <div class="welcome-content">
        <h3 class="welcome-title">Manajemen Ujian</h3>
        <p class="welcome-description">
            Buat, kelola, dan pantau ujian untuk siswa Anda. 
            Lihat hasil dan analisis performa siswa dengan mudah.
        </p>
    </div>
</div>

<!-- Stats Cards -->
<div class="dashboard-grid">
    <div class="card">
        <div class="card-icon blue">
            <i class="ph-clipboard-text"></i>
        </div>
        <h3 class="card-title">Total Ujian</h3>
        <p class="card-description">
            {{ $ujian->count() }} ujian telah dibuat
        </p>
    </div>

    <div class="card">
        <div class="card-icon green">
            <i class="ph-check-circle"></i>
        </div>
        <h3 class="card-title">Ujian Aktif</h3>
        <p class="card-description">
            {{ $ujian->where('isHidden', 0)->count() }} ujian sedang berlangsung
        </p>
    </div>

    <div class="card">
        <div class="card-icon purple">
            <i class="ph-clock"></i>
        </div>
        <h3 class="card-title">Selesai</h3>
        <p class="card-description">
            {{ $ujian->where('due', '<', now())->count() }} ujian telah selesai
        </p>
    </div>

    <div class="card">
        <div class="card-icon orange">
            <i class="ph-users"></i>
        </div>
        <h3 class="card-title">Total Peserta</h3>
        <p class="card-description">
            {{ $ujian->sum(function($u) { return $u->kelasMapel->kelas->users->where('roles_id', 4)->count(); }) }} siswa
        </p>
    </div>
</div>

<!-- Ujian List -->
<div class="system-info">
    <div class="info-section">
        <h3 class="info-title">Daftar Ujian</h3>
        
        @if($ujian->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Ujian</th>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ujian as $ujianItem)
                            @php
                                $now = now();
                                $deadline = \Carbon\Carbon::parse($ujianItem->due);
                                $isOverdue = $deadline->isPast();
                                $isSoon = $now->diffInDays($deadline) <= 2 && !$isOverdue;
                                $status = $ujianItem->isHidden ? 'Draft' : ($isOverdue ? 'Selesai' : 'Aktif');
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="ph-clipboard-text me-2"></i>
                                        <strong>{{ $ujianItem->name }}</strong>
                                    </div>
                                </td>
                                <td>{{ $ujianItem->kelasMapel->kelas->name ?? 'N/A' }}</td>
                                <td>{{ $ujianItem->kelasMapel->mapel->name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($ujianItem->start)->format('d M Y H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($ujianItem->due)->format('d M Y H:i') }}</td>
                                <td>
                                    @if($status == 'Aktif')
                                        <span class="badge bg-success">{{ $status }}</span>
                                    @elseif($status == 'Selesai')
                                        <span class="badge bg-secondary">{{ $status }}</span>
                                    @else
                                        <span class="badge bg-warning">{{ $status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('viewUjian', ['token' => encrypt($ujianItem->id)]) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="ph-eye"></i> Lihat
                                        </a>
                                        <a href="{{ route('viewUpdateUjian', ['token' => encrypt($ujianItem->id)]) }}" 
                                           class="btn btn-sm btn-warning">
                                            <i class="ph-pencil"></i> Edit
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger"
                                                onclick="deleteUjian({{ $ujianItem->id }})">
                                            <i class="ph-trash"></i> Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="ph-clipboard-text"></i>
                <h3>Belum Ada Ujian</h3>
                <p>Mulai buat ujian pertama Anda untuk siswa.</p>
                <a href="#" class="btn btn-primary">
                    <i class="ph-plus"></i> Buat Ujian Baru
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Quick Actions -->
<div class="system-info">
    <div class="info-section">
        <h3 class="info-title">Aksi Cepat</h3>
        <div class="d-flex gap-2 flex-wrap">
            <a href="#" class="btn btn-primary">
                <i class="ph-plus"></i> Buat Ujian Baru
            </a>
            <a href="#" class="btn btn-success">
                <i class="ph-upload"></i> Import Soal
            </a>
            <a href="#" class="btn btn-info">
                <i class="ph-download"></i> Export Data
            </a>
            <a href="#" class="btn btn-warning">
                <i class="ph-chart-bar"></i> Lihat Laporan
            </a>
        </div>
    </div>
</div>

<script>
function deleteUjian(ujianId) {
    if (confirm('Apakah Anda yakin ingin menghapus ujian ini?')) {
        // Implementasi hapus ujian
        console.log('Hapus ujian:', ujianId);
    }
}
</script>
@endsection
