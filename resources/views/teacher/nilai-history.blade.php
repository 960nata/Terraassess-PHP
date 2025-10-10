@extends('layouts.unified-layout')

@section('container')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">📜 Riwayat Revisi Nilai - {{ $userTugas->user->name }}</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">📊 Informasi Tugas</h6>
                                    <p class="card-text">
                                        <strong>Tugas:</strong> {{ $userTugas->tugas->name }}<br>
                                        <strong>Siswa:</strong> {{ $userTugas->user->name }}<br>
                                        <strong>Email:</strong> {{ $userTugas->user->email }}<br>
                                        <strong>Status:</strong> 
                                        <span class="badge bg-{{ $userTugas->status == 'Telah dinilai' ? 'success' : 'warning' }}">
                                            {{ $userTugas->status }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">📈 Nilai Saat Ini</h6>
                                    <p class="card-text">
                                        <strong>Nilai:</strong> 
                                        <span class="h4 text-{{ $userTugas->nilai >= 80 ? 'success' : ($userTugas->nilai >= 60 ? 'warning' : 'danger') }}">
                                            {{ $userTugas->nilai ?? 'Belum dinilai' }}
                                        </span><br>
                                        <strong>Revisi ke:</strong> {{ $userTugas->revisi_ke ?? 0 }}<br>
                                        <strong>Dinilai oleh:</strong> {{ $userTugas->penilai->name ?? 'Guru' }}<br>
                                        <strong>Terakhir dinilai:</strong> 
                                        {{ $userTugas->dinilai_pada ? $userTugas->dinilai_pada->format('d M Y H:i') : '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($history->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="15%">Tanggal & Waktu</th>
                                        <th width="10%">Nilai Lama</th>
                                        <th width="10%">Nilai Baru</th>
                                        <th width="15%">Perubahan</th>
                                        <th width="15%">Diubah Oleh</th>
                                        <th width="20%">Alasan Revisi</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($history as $h)
                                    <tr>
                                        <td>
                                            <strong>{{ $h->diubah_pada->format('d M Y') }}</strong><br>
                                            <small class="text-muted">{{ $h->diubah_pada->format('H:i:s') }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary fs-6">{{ $h->nilai_lama }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary fs-6">{{ $h->nilai_baru }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $selisih = $h->nilai_baru - $h->nilai_lama;
                                                $warna = $selisih > 0 ? 'success' : ($selisih < 0 ? 'danger' : 'info');
                                                $icon = $selisih > 0 ? '↗️' : ($selisih < 0 ? '↘️' : '➡️');
                                            @endphp
                                            <span class="badge bg-{{ $warna }}">
                                                {{ $icon }} {{ $selisih > 0 ? '+' : '' }}{{ $selisih }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong>{{ $h->pengubah->name }}</strong><br>
                                            <small class="text-muted">{{ $h->pengubah->email }}</small>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $h->alasan_revisi ?? 'Tidak ada alasan' }}</span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#detailModal{{ $h->id }}">
                                                <i class="fas fa-eye"></i> Detail
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Detail Modal -->
                                    <div class="modal fade" id="detailModal{{ $h->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $h->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="detailModalLabel{{ $h->id }}">
                                                        📋 Detail Revisi - {{ $h->diubah_pada->format('d M Y H:i') }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h6 class="text-danger">📉 Sebelum Revisi</h6>
                                                            <div class="card bg-light">
                                                                <div class="card-body">
                                                                    <p><strong>Nilai:</strong> <span class="badge bg-secondary">{{ $h->nilai_lama }}</span></p>
                                                                    @if($h->komentar_lama)
                                                                        <p><strong>Komentar:</strong></p>
                                                                        <div class="alert alert-secondary">
                                                                            {{ $h->komentar_lama }}
                                                                        </div>
                                                                    @else
                                                                        <p class="text-muted">Tidak ada komentar sebelumnya</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6 class="text-success">📈 Setelah Revisi</h6>
                                                            <div class="card bg-light">
                                                                <div class="card-body">
                                                                    <p><strong>Nilai:</strong> <span class="badge bg-primary">{{ $h->nilai_baru }}</span></p>
                                                                    @if($h->komentar_baru)
                                                                        <p><strong>Komentar:</strong></p>
                                                                        <div class="alert alert-primary">
                                                                            {{ $h->komentar_baru }}
                                                                        </div>
                                                                    @else
                                                                        <p class="text-muted">Tidak ada komentar baru</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mt-3">
                                                        <h6>ℹ️ Informasi Revisi</h6>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <p><strong>Diubah oleh:</strong> {{ $h->pengubah->name }}</p>
                                                                <p><strong>Email:</strong> {{ $h->pengubah->email }}</p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <p><strong>Tanggal:</strong> {{ $h->diubah_pada->format('d M Y H:i:s') }}</p>
                                                                <p><strong>Alasan:</strong> {{ $h->alasan_revisi ?? 'Tidak ada alasan' }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card bg-info text-white">
                                        <div class="card-body">
                                            <h6 class="card-title">📊 Statistik Revisi</h6>
                                            <p class="card-text">
                                                <strong>Total Revisi:</strong> {{ $history->count() }} kali<br>
                                                <strong>Revisi Terakhir:</strong> {{ $history->first()->diubah_pada->diffForHumans() }}<br>
                                                <strong>Rata-rata Perubahan:</strong> 
                                                {{ round($history->avg(function($h) { return $h->nilai_baru - $h->nilai_lama; }), 2) }} poin
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-warning text-dark">
                                        <div class="card-body">
                                            <h6 class="card-title">⚠️ Catatan Penting</h6>
                                            <p class="card-text">
                                                Semua perubahan nilai dicatat untuk transparansi dan audit trail. 
                                                Revisi nilai harus disertai alasan yang jelas dan dapat dipertanggungjawabkan.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <h5><i class="fas fa-info-circle"></i> Belum Ada Riwayat Revisi</h5>
                            <p>Nilai ini belum pernah direvisi. Semua perubahan nilai akan dicatat di sini.</p>
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('teacher.tugas.view', ['token' => encrypt($userTugas->tugas->id)]) }}" 
                           class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Tugas
                        </a>
                        <button type="button" class="btn btn-primary" onclick="window.print()">
                            <i class="fas fa-print"></i> Cetak Riwayat
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .btn, .modal, .alert {
        display: none !important;
    }
    
    .card {
        border: 1px solid #000 !important;
    }
    
    .table {
        font-size: 12px;
    }
}

.badge.fs-6 {
    font-size: 1rem !important;
    padding: 0.5rem 0.75rem;
}

.table th {
    background-color: #343a40 !important;
    color: white !important;
}

.card.bg-light {
    background-color: #f8f9fa !important;
    border: 1px solid #dee2e6;
}

.alert-secondary {
    background-color: #e2e3e5;
    border-color: #d3d6d8;
    color: #383d41;
}

.alert-primary {
    background-color: #cce7ff;
    border-color: #b3d7ff;
    color: #004085;
}
</style>
@endpush
@endsection
