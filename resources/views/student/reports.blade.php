@extends('layouts.unified-layout-new')

@section('title', 'Laporan Student - Terra Assessment')

@section('additional-styles')
<style>
/* Dark theme compatible reports styles */
.reports-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0;
}

.reports-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
}

.report-card {
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.report-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.4);
}

.report-card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.report-card-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.report-card-title {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}

.report-card-content {
    padding: 24px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-bottom: 20px;
}

.stat-item {
    text-align: center;
    padding: 20px;
    background: rgba(51, 65, 85, 0.5);
    border-radius: 12px;
    border: 1px solid rgba(71, 85, 105, 0.3);
    transition: all 0.2s ease;
}

.stat-item:hover {
    background: rgba(51, 65, 85, 0.7);
    transform: translateY(-2px);
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 8px;
    color: #f8fafc;
}

.stat-label {
    font-size: 14px;
    color: #cbd5e1;
    font-weight: 500;
}

.chart-container {
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    margin-top: 24px;
}

.chart-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
}

.chart-title {
    font-size: 18px;
    font-weight: 600;
    color: #f8fafc;
    margin: 0;
}

.chart-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.table-container {
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    overflow: hidden;
    margin-top: 24px;
}

.table-header {
    background: rgba(51, 65, 85, 0.8);
    padding: 16px 24px;
    border-bottom: 1px solid rgba(71, 85, 105, 0.3);
}

.table-title {
    font-size: 16px;
    font-weight: 600;
    color: #f8fafc;
    margin: 0;
}

.table-content {
    padding: 0;
}

.simple-table {
    width: 100%;
    border-collapse: collapse;
    color: #f8fafc;
}

.simple-table th {
    background: rgba(51, 65, 85, 0.8);
    color: #cbd5e1;
    font-weight: 600;
    padding: 16px;
    text-align: left;
    border-bottom: 1px solid rgba(71, 85, 105, 0.3);
    font-size: 14px;
}

.simple-table td {
    padding: 16px;
    border-bottom: 1px solid rgba(71, 85, 105, 0.2);
    font-size: 14px;
}

.simple-table tr:hover {
    background: rgba(51, 65, 85, 0.3);
}

.badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.badge-success {
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
}

.badge-warning {
    background: rgba(245, 158, 11, 0.2);
    color: #f59e0b;
}

.badge-danger {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.badge-info {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
}

.no-data {
    text-align: center;
    padding: 40px 20px;
    color: #94a3b8;
}

.no-data i {
    font-size: 48px;
    margin-bottom: 16px;
    color: #64748b;
}

/* Page header styling to match unified layout */
.page-header {
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #f8fafc;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.page-title i {
    color: #667eea;
}

.page-description {
    color: #cbd5e1;
    font-size: 1.125rem;
}

@media (max-width: 768px) {
    .reports-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .reports-container {
        padding: 16px;
    }
    
    .report-card-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .chart-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
}
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-chart-line"></i>
        Laporan Student
    </h1>
    <p class="page-description">Lihat laporan performa dan aktivitas pembelajaran Anda</p>
</div>

<div class="reports-container">
    <!-- Performance Overview -->
    <div class="reports-grid">
        <!-- Academic Performance Card -->
        <div class="report-card">
            <div class="report-card-header">
                <div class="report-card-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 class="report-card-title">Performa Akademik</h3>
            </div>
            <div class="report-card-content">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-value" style="color: #22c55e;">85.2</div>
                        <div class="stat-label">Nilai Rata-rata</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" style="color: #3b82f6;">24</div>
                        <div class="stat-label">Total Tugas</div>
                    </div>
                </div>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-value" style="color: #22c55e;">18</div>
                        <div class="stat-label">Tugas Selesai</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" style="color: #f59e0b;">6</div>
                        <div class="stat-label">Tugas Pending</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exam Performance Card -->
        <div class="report-card">
            <div class="report-card-header">
                <div class="report-card-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h3 class="report-card-title">Performa Ujian</h3>
            </div>
            <div class="report-card-content">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-value" style="color: #22c55e;">88.5</div>
                        <div class="stat-label">Nilai Rata-rata</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" style="color: #3b82f6;">12</div>
                        <div class="stat-label">Total Ujian</div>
                    </div>
                </div>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-value" style="color: #22c55e;">10</div>
                        <div class="stat-label">Ujian Selesai</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" style="color: #f59e0b;">2</div>
                        <div class="stat-label">Ujian Pending</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">
                <i class="fas fa-history"></i>
                Aktivitas Terbaru
            </h3>
        </div>
        <div class="table-content">
            <table class="simple-table">
                <thead>
                    <tr>
                        <th>Aktivitas</th>
                        <th>Mata Pelajaran</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>Ujian Matematika</strong>
                            <br>
                            <small style="color: #94a3b8;">Ujian tengah semester</small>
                        </td>
                        <td>Matematika</td>
                        <td>15 Des 2024</td>
                        <td><span class="badge badge-success">Selesai</span></td>
                        <td><strong style="color: #22c55e;">85</strong></td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Tugas Fisika</strong>
                            <br>
                            <small style="color: #94a3b8;">Laporan praktikum</small>
                        </td>
                        <td>Fisika</td>
                        <td>12 Des 2024</td>
                        <td><span class="badge badge-success">Selesai</span></td>
                        <td><strong style="color: #22c55e;">92</strong></td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Ujian Bahasa Indonesia</strong>
                            <br>
                            <small style="color: #94a3b8;">Ujian akhir semester</small>
                        </td>
                        <td>Bahasa Indonesia</td>
                        <td>10 Des 2024</td>
                        <td><span class="badge badge-warning">Pending</span></td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Tugas Kimia</strong>
                            <br>
                            <small style="color: #94a3b8;">Eksperimen laboratorium</small>
                        </td>
                        <td>Kimia</td>
                        <td>8 Des 2024</td>
                        <td><span class="badge badge-success">Selesai</span></td>
                        <td><strong style="color: #22c55e;">78</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Performance Chart -->
    <div class="chart-container">
        <div class="chart-header">
            <div class="chart-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <h3 class="chart-title">Grafik Performa Bulanan</h3>
        </div>
        <div id="performanceChart" style="height: 300px;"></div>
    </div>

    <!-- Subject Performance -->
    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">
                <i class="fas fa-book"></i>
                Performa Per Mata Pelajaran
            </h3>
        </div>
        <div class="table-content">
            <table class="simple-table">
                <thead>
                    <tr>
                        <th>Mata Pelajaran</th>
                        <th>Nilai Rata-rata</th>
                        <th>Tugas Selesai</th>
                        <th>Ujian Selesai</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>Matematika</strong>
                            <br>
                            <small style="color: #94a3b8;">Kelas X IPA 1</small>
                        </td>
                        <td><strong style="color: #22c55e;">87.5</strong></td>
                        <td>8/10</td>
                        <td>3/4</td>
                        <td><span class="badge badge-success">Baik</span></td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Fisika</strong>
                            <br>
                            <small style="color: #94a3b8;">Kelas X IPA 1</small>
                        </td>
                        <td><strong style="color: #22c55e;">89.2</strong></td>
                        <td>6/8</td>
                        <td>2/3</td>
                        <td><span class="badge badge-success">Baik</span></td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Kimia</strong>
                            <br>
                            <small style="color: #94a3b8;">Kelas X IPA 1</small>
                        </td>
                        <td><strong style="color: #f59e0b;">76.8</strong></td>
                        <td>4/6</td>
                        <td>2/3</td>
                        <td><span class="badge badge-warning">Cukup</span></td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Bahasa Indonesia</strong>
                            <br>
                            <small style="color: #94a3b8;">Kelas X IPA 1</small>
                        </td>
                        <td><strong style="color: #3b82f6;">82.1</strong></td>
                        <td>5/7</td>
                        <td>1/2</td>
                        <td><span class="badge badge-info">Sedang</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('additional-scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Performance Chart
    const options = {
        series: [{
            name: 'Nilai Rata-rata',
            data: [82, 85, 78, 88, 90, 85, 87, 89]
        }],
        chart: {
            type: 'line',
            height: 300,
            background: 'transparent',
            toolbar: {
                show: false
            }
        },
        colors: ['#667eea'],
        stroke: {
            curve: 'smooth',
            width: 3
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                type: 'vertical',
                shadeIntensity: 0.5,
                gradientToColors: ['#764ba2'],
                inverseColors: false,
                opacityFrom: 0.1,
                opacityTo: 0.1,
                stops: [0, 100]
            }
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
            labels: {
                style: {
                    colors: '#cbd5e1'
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
                    colors: '#cbd5e1'
                }
            }
        },
        grid: {
            borderColor: 'rgba(71, 85, 105, 0.3)',
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
            theme: 'dark',
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
});
</script>
@endsection
