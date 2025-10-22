@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Analytics Teacher')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-chart-bar"></i>
        Analytics Teacher
    </h1>
    <p class="page-description">Analisis performa kelas dan siswa Anda</p>
</div>

<div class="analytics-grid">
    <!-- Overview Cards -->
    <div class="analytics-overview">
        <div class="overview-card">
            <div class="overview-icon blue">
                <i class="fas fa-users"></i>
            </div>
            <div class="overview-content">
                <h3 class="overview-number">{{ $totalStudents ?? 0 }}</h3>
                <p class="overview-label">Total Siswa</p>
                <span class="overview-change positive">+5% dari bulan lalu</span>
            </div>
        </div>

        <div class="overview-card">
            <div class="overview-icon green">
                <i class="fas fa-book"></i>
            </div>
            <div class="overview-content">
                <h3 class="overview-number">{{ $totalTasks ?? 0 }}</h3>
                <p class="overview-label">Tugas Dibuat</p>
                <span class="overview-change positive">+12% dari bulan lalu</span>
            </div>
        </div>

        <div class="overview-card">
            <div class="overview-icon purple">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="overview-content">
                <h3 class="overview-number">{{ $totalExams ?? 0 }}</h3>
                <p class="overview-label">Ujian Dibuat</p>
                <span class="overview-change positive">+8% dari bulan lalu</span>
            </div>
        </div>

        <div class="overview-card">
            <div class="overview-icon orange">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="overview-content">
                <h3 class="overview-number">{{ $avgScore ?? 0 }}%</h3>
                <p class="overview-label">Rata-rata Nilai Kelas</p>
                <span class="overview-change positive">+2% dari bulan lalu</span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
        <div class="chart-container">
            <div class="chart-header">
                <h3 class="chart-title">
                    <i class="fas fa-chart-area"></i>
                    Performa Siswa per Kelas
                </h3>
                <div class="chart-controls">
                    <select class="chart-filter">
                        <option value="current">Semester Ini</option>
                        <option value="last" selected>Semester Lalu</option>
                        <option value="year">Tahun Ini</option>
                    </select>
                </div>
            </div>
            <div class="chart-content">
                <canvas id="classPerformanceChart"></canvas>
            </div>
        </div>

        <div class="chart-container">
            <div class="chart-header">
                <h3 class="chart-title">
                    <i class="fas fa-chart-pie"></i>
                    Distribusi Nilai
                </h3>
            </div>
            <div class="chart-content">
                <canvas id="gradeDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Student Performance -->
    <div class="performance-section">
        <div class="performance-card">
            <h3 class="performance-title">
                <i class="fas fa-trophy"></i>
                Top Performing Students
            </h3>
            <div class="performance-list">
                @forelse($topStudents ?? [] as $student)
                <div class="performance-item">
                    <div class="performance-info">
                        <h4 class="performance-name">{{ $student->name ?? 'Siswa ' . $loop->iteration }}</h4>
                        <p class="performance-detail">{{ $student->class_name ?? 'Kelas A' }}</p>
                    </div>
                    <div class="performance-score">
                        <span class="score-value">{{ $student->avg_score ?? 90 }}%</span>
                        <div class="score-bar">
                            <div class="score-fill" style="width: {{ $student->avg_score ?? 90 }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="performance-item">
                    <div class="performance-info">
                        <h4 class="performance-name">Belum ada data</h4>
                        <p class="performance-detail">Data performa siswa akan muncul di sini</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <div class="performance-card">
            <h3 class="performance-title">
                <i class="fas fa-tasks"></i>
                Tugas Terbaru
            </h3>
            <div class="task-list">
                @forelse($recentTasks ?? [] as $task)
                <div class="task-item">
                    <div class="task-icon">
                        <i class="fas fa-{{ $task->type === 'essay' ? 'file-alt' : 'list' }}"></i>
                    </div>
                    <div class="task-content">
                        <h4 class="task-title">{{ $task->title ?? 'Tugas Baru' }}</h4>
                        <p class="task-detail">{{ $task->submission_count ?? 0 }} dari {{ $task->total_students ?? 0 }} siswa mengumpulkan</p>
                    </div>
                    <div class="task-status">
                        <span class="status-badge {{ $task->status === 'completed' ? 'completed' : 'pending' }}">
                            {{ $task->status === 'completed' ? 'Selesai' : 'Berlangsung' }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="task-item">
                    <div class="task-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="task-content">
                        <h4 class="task-title">Belum ada tugas</h4>
                        <p class="task-detail">Tugas terbaru akan muncul di sini</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Class Comparison -->
    <div class="comparison-section">
        <div class="comparison-card">
            <h3 class="comparison-title">
                <i class="fas fa-balance-scale"></i>
                Perbandingan Kelas
            </h3>
            <div class="comparison-chart">
                <canvas id="classComparisonChart"></canvas>
            </div>
        </div>
    </div>
</div>

<style>
.analytics-grid {
    display: grid;
    gap: 2rem;
    margin-top: 2rem;
}

.analytics-overview {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.overview-card {
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.overview-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.overview-icon.blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
.overview-icon.green { background: linear-gradient(135deg, #10b981, #059669); }
.overview-icon.purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
.overview-icon.orange { background: linear-gradient(135deg, #f59e0b, #d97706); }

.overview-content {
    flex: 1;
}

.overview-number {
    font-size: 2rem;
    font-weight: bold;
    color: white;
    margin: 0;
}

.overview-label {
    color: rgba(255, 255, 255, 0.7);
    margin: 0.25rem 0;
}

.overview-change {
    font-size: 0.875rem;
    font-weight: 500;
}

.overview-change.positive { color: #10b981; }
.overview-change.negative { color: #ef4444; }

.charts-section {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
}

.chart-container {
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1.5rem;
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.chart-title {
    color: white;
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.chart-filter {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 6px;
    color: white;
    padding: 0.5rem;
    font-size: 0.875rem;
}

.chart-content {
    height: 300px;
    position: relative;
}

.performance-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.performance-card {
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1.5rem;
}

.performance-title {
    color: white;
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0 0 1.5rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.performance-list, .task-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.performance-item, .task-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
}

.performance-name, .task-title {
    color: white;
    font-size: 1rem;
    font-weight: 500;
    margin: 0;
}

.performance-detail, .task-detail {
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.875rem;
    margin: 0.25rem 0 0 0;
}

.performance-score {
    text-align: right;
}

.score-value {
    color: white;
    font-weight: 600;
    font-size: 1.125rem;
}

.score-bar {
    width: 80px;
    height: 4px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 2px;
    margin-top: 0.5rem;
}

.score-fill {
    height: 100%;
    background: linear-gradient(90deg, #10b981, #059669);
    border-radius: 2px;
    transition: width 0.3s ease;
}

.task-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: rgba(59, 130, 246, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #3b82f6;
    margin-right: 1rem;
}

.task-content {
    flex: 1;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-badge.completed {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.status-badge.pending {
    background: rgba(245, 158, 11, 0.2);
    color: #f59e0b;
}

.comparison-section {
    grid-column: 1 / -1;
}

.comparison-card {
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1.5rem;
}

.comparison-title {
    color: white;
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0 0 1.5rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.comparison-chart {
    height: 400px;
    position: relative;
}

@media (max-width: 768px) {
    .charts-section {
        grid-template-columns: 1fr;
    }
    
    .performance-section {
        grid-template-columns: 1fr;
    }
    
    .analytics-overview {
        grid-template-columns: 1fr;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Class Performance Chart
const classPerformanceCtx = document.getElementById('classPerformanceChart').getContext('2d');
new Chart(classPerformanceCtx, {
    type: 'bar',
    data: {
        labels: ['Kelas A', 'Kelas B', 'Kelas C', 'Kelas D'],
        datasets: [{
            label: 'Rata-rata Nilai',
            data: [85, 78, 92, 88],
            backgroundColor: [
                'rgba(59, 130, 246, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(245, 158, 11, 0.8)'
            ],
            borderColor: [
                '#3b82f6',
                '#10b981',
                '#8b5cf6',
                '#f59e0b'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: 'white'
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: 'rgba(255, 255, 255, 0.7)'
                },
                grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
                }
            },
            y: {
                ticks: {
                    color: 'rgba(255, 255, 255, 0.7)'
                },
                grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
                }
            }
        }
    }
});

// Grade Distribution Chart
const gradeDistributionCtx = document.getElementById('gradeDistributionChart').getContext('2d');
new Chart(gradeDistributionCtx, {
    type: 'doughnut',
    data: {
        labels: ['A (90-100)', 'B (80-89)', 'C (70-79)', 'D (60-69)', 'E (<60)'],
        datasets: [{
            data: [25, 35, 20, 15, 5],
            backgroundColor: [
                '#10b981',
                '#3b82f6',
                '#f59e0b',
                '#ef4444',
                '#6b7280'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: 'white',
                    padding: 15
                }
            }
        }
    }
});

// Class Comparison Chart
const classComparisonCtx = document.getElementById('classComparisonChart').getContext('2d');
new Chart(classComparisonCtx, {
    type: 'radar',
    data: {
        labels: ['Tugas', 'Ujian', 'Partisipasi', 'Kehadiran', 'Proyek'],
        datasets: [{
            label: 'Kelas A',
            data: [85, 90, 88, 92, 87],
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.2)',
            borderWidth: 2
        }, {
            label: 'Kelas B',
            data: [78, 82, 85, 88, 80],
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.2)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: 'white'
                }
            }
        },
        scales: {
            r: {
                ticks: {
                    color: 'rgba(255, 255, 255, 0.7)'
                },
                grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
                },
                pointLabels: {
                    color: 'white'
                }
            }
        }
    }
});

// Global variables for charts
let classPerformanceChart, gradeDistributionChart, classComparisonChart;
let isLoading = false;

// Initialize charts with empty data
function initializeCharts() {
    // Class Performance Chart
    const classPerformanceCtx = document.getElementById('classPerformanceChart').getContext('2d');
    classPerformanceChart = new Chart(classPerformanceCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Rata-rata Nilai',
                data: [],
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(139, 92, 246, 0.8)',
                    'rgba(245, 158, 11, 0.8)'
                ],
                borderColor: [
                    '#3b82f6',
                    '#10b981',
                    '#8b5cf6',
                    '#f59e0b'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: 'white'
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.7)'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                y: {
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.7)'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                }
            }
        }
    });

    // Grade Distribution Chart
    const gradeDistributionCtx = document.getElementById('gradeDistributionChart').getContext('2d');
    gradeDistributionChart = new Chart(gradeDistributionCtx, {
        type: 'doughnut',
        data: {
            labels: ['A (90-100)', 'B (80-89)', 'C (70-79)', 'D (60-69)', 'E (<60)'],
            datasets: [{
                data: [0, 0, 0, 0, 0],
                backgroundColor: [
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(107, 114, 128, 0.8)'
                ],
                borderColor: [
                    '#10b981',
                    '#3b82f6',
                    '#f59e0b',
                    '#ef4444',
                    '#6b7280'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: 'white',
                        padding: 20
                    }
                }
            }
        }
    });

    // Class Comparison Chart
    const classComparisonCtx = document.getElementById('classComparisonChart').getContext('2d');
    classComparisonChart = new Chart(classComparisonCtx, {
        type: 'radar',
        data: {
            labels: ['Tugas', 'Ujian', 'Partisipasi', 'Kehadiran', 'Proyek'],
            datasets: [{
                label: 'Kelas A',
                data: [0, 0, 0, 0, 0],
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                borderWidth: 2
            }, {
                label: 'Kelas B',
                data: [0, 0, 0, 0, 0],
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.2)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: 'white'
                    }
                }
            },
            scales: {
                r: {
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.7)'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    pointLabels: {
                        color: 'white'
                    }
                }
            }
        }
    });
}

// Fetch analytics data from API
async function fetchAnalyticsData() {
    if (isLoading) return;
    
    isLoading = true;
    showLoadingIndicator();
    
    try {
        const response = await fetch('/api/teacher/analytics-data', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await response.json();
        updateAllData(data);
        hideLoadingIndicator();
        
    } catch (error) {
        console.error('Error fetching analytics data:', error);
        showErrorNotification('Gagal memuat data analytics');
        hideLoadingIndicator();
    } finally {
        isLoading = false;
    }
}

// Update all data on the page
function updateAllData(data) {
    updateOverviewCards(data);
    updateCharts(data);
    updateTopStudents(data);
    updateRecentTasks(data);
    updateLastUpdatedTime(data.lastUpdated);
}

// Update overview cards
function updateOverviewCards(data) {
    document.querySelector('.overview-card:nth-child(1) .overview-number').textContent = data.totalStudents || 0;
    document.querySelector('.overview-card:nth-child(2) .overview-number').textContent = data.totalTasks || 0;
    document.querySelector('.overview-card:nth-child(3) .overview-number').textContent = data.totalExams || 0;
    document.querySelector('.overview-card:nth-child(4) .overview-number').textContent = (data.avgScore || 0) + '%';
}

// Update charts
function updateCharts(data) {
    // Update class performance chart
    if (data.classPerformance && data.classPerformance.length > 0) {
        classPerformanceChart.data.labels = data.classPerformance.map(c => c.class_name);
        classPerformanceChart.data.datasets[0].data = data.classPerformance.map(c => c.avg_score);
        classPerformanceChart.update();
    }
    
    // Update grade distribution chart
    if (data.gradeDistribution) {
        gradeDistributionChart.data.datasets[0].data = [
            data.gradeDistribution.A || 0,
            data.gradeDistribution.B || 0,
            data.gradeDistribution.C || 0,
            data.gradeDistribution.D || 0,
            data.gradeDistribution.E || 0
        ];
        gradeDistributionChart.update();
    }
}

// Update top students list
function updateTopStudents(data) {
    const performanceList = document.querySelector('.performance-list');
    if (!performanceList) return;
    
    if (data.topStudents && data.topStudents.length > 0) {
        performanceList.innerHTML = data.topStudents.map(student => `
            <div class="performance-item">
                <div class="performance-info">
                    <h4 class="performance-name">${student.name}</h4>
                    <p class="performance-detail">${student.class_name}</p>
                </div>
                <div class="performance-score">
                    <span class="score-value">${student.avg_score}%</span>
                    <div class="score-bar">
                        <div class="score-fill" style="width: ${student.avg_score}%"></div>
                    </div>
                </div>
            </div>
        `).join('');
    } else {
        performanceList.innerHTML = `
            <div class="performance-item">
                <div class="performance-info">
                    <h4 class="performance-name">Belum ada data</h4>
                    <p class="performance-detail">Data performa siswa akan muncul di sini</p>
                </div>
            </div>
        `;
    }
}

// Update recent tasks list
function updateRecentTasks(data) {
    const taskList = document.querySelector('.task-list');
    if (!taskList) return;
    
    if (data.recentTasks && data.recentTasks.length > 0) {
        taskList.innerHTML = data.recentTasks.map(task => `
            <div class="task-item">
                <div class="task-icon">
                    <i class="fas fa-${getTaskIcon(task.type)}"></i>
                </div>
                <div class="task-content">
                    <h4 class="task-title">${task.title}</h4>
                    <p class="task-detail">${task.submission_count} dari ${task.total_students} siswa mengumpulkan</p>
                </div>
                <div class="task-status">
                    <span class="status-badge ${task.status === 'completed' ? 'completed' : 'pending'}">
                        ${task.status === 'completed' ? 'Selesai' : 'Berlangsung'}
                    </span>
                </div>
            </div>
        `).join('');
    } else {
        taskList.innerHTML = `
            <div class="task-item">
                <div class="task-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="task-content">
                    <h4 class="task-title">Belum ada tugas</h4>
                    <p class="task-detail">Tugas terbaru akan muncul di sini</p>
                </div>
            </div>
        `;
    }
}

// Get task icon based on type
function getTaskIcon(type) {
    const icons = {
        'multiple_choice': 'list',
        'essay': 'file-alt',
        'mandiri': 'user',
        'kelompok': 'users',
        'unknown': 'question'
    };
    return icons[type] || 'question';
}

// Show loading indicator
function showLoadingIndicator() {
    const loadingDiv = document.createElement('div');
    loadingDiv.id = 'analytics-loading';
    loadingDiv.innerHTML = `
        <div style="position: fixed; top: 20px; right: 20px; background: rgba(0,0,0,0.8); color: white; padding: 10px 20px; border-radius: 5px; z-index: 9999;">
            <i class="fas fa-spinner fa-spin"></i> Memuat data...
        </div>
    `;
    document.body.appendChild(loadingDiv);
}

// Hide loading indicator
function hideLoadingIndicator() {
    const loadingDiv = document.getElementById('analytics-loading');
    if (loadingDiv) {
        loadingDiv.remove();
    }
}

// Show error notification
function showErrorNotification(message) {
    const errorDiv = document.createElement('div');
    errorDiv.innerHTML = `
        <div style="position: fixed; top: 20px; right: 20px; background: rgba(239, 68, 68, 0.9); color: white; padding: 10px 20px; border-radius: 5px; z-index: 9999;">
            <i class="fas fa-exclamation-triangle"></i> ${message}
        </div>
    `;
    document.body.appendChild(errorDiv);
    
    setTimeout(() => {
        errorDiv.remove();
    }, 5000);
}

// Update last updated time
function updateLastUpdatedTime(time) {
    let lastUpdatedDiv = document.getElementById('last-updated');
    if (!lastUpdatedDiv) {
        lastUpdatedDiv = document.createElement('div');
        lastUpdatedDiv.id = 'last-updated';
        lastUpdatedDiv.style.cssText = 'position: fixed; bottom: 20px; right: 20px; background: rgba(0,0,0,0.8); color: white; padding: 5px 10px; border-radius: 5px; font-size: 12px; z-index: 9999;';
        document.body.appendChild(lastUpdatedDiv);
    }
    lastUpdatedDiv.innerHTML = `<i class="fas fa-clock"></i> Terakhir update: ${time}`;
}

// Initialize everything when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    fetchAnalyticsData();
    
    // Set up auto-refresh every 5 seconds
    setInterval(fetchAnalyticsData, 5000);
});
</script>
@endsection
