@extends('layouts.unified-layout-new')

@section('title', 'Laporan Guru')

@push('styles')
<style>
    .reports-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .reports-grid {
            grid-template-columns: 1fr;
        }
    }

    .report-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .report-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .report-card-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .report-card-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }

    .report-card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-item {
        text-align: center;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .chart-container {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .chart-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Page Header -->
        <div class="d-flex align-items-center mb-4">
            <div class="report-card-icon">
                <i class="fas fa-chart-line text-white"></i>
            </div>
            <div>
                <h1 class="h3 mb-1">Laporan Guru</h1>
                <p class="text-muted mb-0">Lihat laporan performa siswa dan kelas</p>
            </div>
        </div>

        <!-- Reports Grid -->
        <div class="reports-grid">
            <!-- Student Reports Card -->
            <div class="report-card">
                <div class="report-card-header">
                    <div class="report-card-icon">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <h3 class="report-card-title">Laporan Per Siswa</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Nilai Rata-rata</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Data Tidak Tersedia</td>
                                <td>-</td>
                                <td>-</td>
                                <td><span class="badge bg-success">Baik</span></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada data siswa</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Class Statistics Card -->
            <div class="report-card">
                <div class="report-card-header">
                    <div class="report-card-icon">
                        <i class="fas fa-chart-bar text-white"></i>
                    </div>
                    <h3 class="report-card-title">Statistik Kelas</h3>
                </div>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-value text-primary">85.2</div>
                        <div class="stat-label">Nilai Rata-rata</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value text-success">24</div>
                        <div class="stat-label">Total Siswa</div>
                    </div>
                </div>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-value text-success">18</div>
                        <div class="stat-label">Lulus</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value text-warning">4</div>
                        <div class="stat-label">Remedial</div>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <div class="stat-item">
                        <div class="stat-value text-danger">2</div>
                        <div class="stat-label">Tidak Lulus</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Chart -->
        <div class="chart-container">
            <div class="d-flex align-items-center mb-4">
                <div class="report-card-icon">
                    <i class="fas fa-chart-line text-white"></i>
                </div>
                <h3 class="chart-title ms-3">Grafik Performa</h3>
            </div>
            <div id="performanceChart" style="height: 200px;"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Chart data from database
    const options = {
        series: [{
            name: 'Nilai Rata-rata',
            data: [82, 85, 78, 88, 90, 85]
        }],
        chart: {
            type: 'line',
            height: 200,
            toolbar: {
                show: false
            }
        },
        colors: ['#3b82f6'],
        stroke: {
            curve: 'smooth',
            width: 3
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: 'vertical',
                shadeIntensity: 0.5,
                gradientToColors: ['#3b82f6'],
                inverseColors: false,
                opacityFrom: 0.1,
                opacityTo: 0.1,
                stops: [0, 100]
            }
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            labels: {
                style: {
                    colors: '#6b7280'
                }
            },
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            }
        },
        yaxis: {
            min: 0,
            max: 100,
            labels: {
                style: {
                    colors: '#6b7280'
                }
            }
        },
        grid: {
            borderColor: '#e5e7eb',
            strokeDashArray: 4,
            xaxis: {
                lines: {
                    show: true
                }
            },
            yaxis: {
                lines: {
                    show: true
                }
            }
        },
        tooltip: {
            theme: 'light',
            style: {
                fontSize: '12px'
            }
        },
        legend: {
            show: false
        }
    };

    const chart = new ApexCharts(document.querySelector("#performanceChart"), options);
    chart.render();
</script>
@endpush