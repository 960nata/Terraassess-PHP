@extends('layouts.unified-layout')

@section('title', 'Hasil Penilaian Kelompok')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Hasil Penilaian Kelompok - {{ $groupTask->title }}
                    </h3>
                </div>
                <div class="card-body">
                    @if(count($memberResults) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Peringkat</th>
                                        <th>Nama Siswa</th>
                                        <th>Total Poin</th>
                                        <th>Rata-rata Poin</th>
                                        <th>Detail Penilaian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($memberResults as $index => $result)
                                        <tr>
                                            <td>
                                                @if($index === 0)
                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-trophy mr-1"></i>
                                                        #1
                                                    </span>
                                                @elseif($index === 1)
                                                    <span class="badge badge-secondary">
                                                        <i class="fas fa-medal mr-1"></i>
                                                        #{{ $index + 1 }}
                                                    </span>
                                                @elseif($index === 2)
                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-award mr-1"></i>
                                                        #{{ $index + 1 }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-light">#{{ $index + 1 }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $result['student']->name }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge badge-primary badge-lg">
                                                    {{ $result['total_points'] }} poin
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ $result['average_points'] }} poin
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-info" 
                                                        data-toggle="collapse" 
                                                        data-target="#detail-{{ $result['student']->id }}">
                                                    <i class="fas fa-eye mr-1"></i>
                                                    Lihat Detail
                                                </button>
                                            </td>
                                        </tr>
                                        <tr class="collapse" id="detail-{{ $result['student']->id }}">
                                            <td colspan="5">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h6>Detail Penilaian:</h6>
                                                        @foreach($result['evaluations'] as $evaluation)
                                                            <div class="row mb-2">
                                                                <div class="col-md-3">
                                                                    <strong>Dari:</strong> {{ $evaluation->evaluator->name }}
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <strong>Rating:</strong> 
                                                                    <span class="badge badge-{{ 
                                                                        $evaluation->rating === 'sangat_baik' ? 'success' : 
                                                                        ($evaluation->rating === 'baik' ? 'info' : 
                                                                        ($evaluation->rating === 'cukup_baik' ? 'warning' : 'danger')) 
                                                                    }}">
                                                                        {{ $evaluation->rating_label }}
                                                                    </span>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <strong>Poin:</strong> {{ $evaluation->points }}
                                                                </div>
                                                                <div class="col-md-5">
                                                                    @if($evaluation->comment)
                                                                        <strong>Komentar:</strong> {{ $evaluation->comment }}
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Statistik Kelompok -->
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h4>{{ count($memberResults) }}</h4>
                                        <p class="mb-0">Total Anggota</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h4>{{ $memberResults[0]['total_points'] ?? 0 }}</h4>
                                        <p class="mb-0">Poin Tertinggi</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h4>{{ round(collect($memberResults)->avg('average_points'), 2) }}</h4>
                                        <p class="mb-0">Rata-rata Poin</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h4>{{ $memberResults[count($memberResults)-1]['total_points'] ?? 0 }}</h4>
                                        <p class="mb-0">Poin Terendah</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Grafik Penilaian -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Grafik Penilaian Kelompok</h5>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="evaluationChart" width="400" height="100"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada data penilaian</h5>
                            <p class="text-muted">Penilaian akan muncul di sini setelah ketua kelompok melakukan penilaian.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(count($memberResults) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('evaluationChart').getContext('2d');
    
    const labels = @json(collect($memberResults)->pluck('student.name'));
    const data = @json(collect($memberResults)->pluck('total_points'));
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Poin',
                data: data,
                backgroundColor: [
                    'rgba(255, 193, 7, 0.8)',   // Gold for #1
                    'rgba(108, 117, 125, 0.8)',  // Silver for #2
                    'rgba(255, 193, 7, 0.6)',    // Bronze for #3
                    'rgba(54, 162, 235, 0.8)',   // Blue for others
                    'rgba(40, 167, 69, 0.8)',    // Green for others
                    'rgba(220, 53, 69, 0.8)',    // Red for others
                ],
                borderColor: [
                    'rgba(255, 193, 7, 1)',
                    'rgba(108, 117, 125, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(40, 167, 69, 1)',
                    'rgba(220, 53, 69, 1)',
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Perbandingan Total Poin Anggota Kelompok'
                }
            }
        }
    });
});
</script>
@endif
@endsection
