@extends('layouts.unified-layout')

@section('title', 'Dashboard Penugasan Guru')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-chalkboard-teacher"></i>
        Dashboard Penugasan Guru
    </h1>
    <p class="page-description">Kelola penugasan guru ke kelas-mata pelajaran dengan mudah</p>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-number" id="totalAssignments">0</h3>
            <p class="stat-label">Total Penugasan</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-chalkboard"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-number" id="totalClasses">0</h3>
            <p class="stat-label">Total Kelas</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-book"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-number" id="totalSubjects">0</h3>
            <p class="stat-label">Total Mata Pelajaran</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-user-tie"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-number" id="totalTeachers">0</h3>
            <p class="stat-label">Total Guru</p>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <h3 class="section-title">
        <i class="fas fa-bolt"></i>
        Aksi Cepat
    </h3>
    <div class="action-buttons">
        <button class="btn btn-primary" onclick="openAssignTeacherModal()">
            <i class="fas fa-plus"></i>
            Tugaskan Guru
        </button>
        <button class="btn btn-success" onclick="exportAssignments()">
            <i class="fas fa-download"></i>
            Export Data
        </button>
        <button class="btn btn-warning" onclick="importAssignments()">
            <i class="fas fa-upload"></i>
            Import Data
        </button>
        <button class="btn btn-info" onclick="viewStatistics()">
            <i class="fas fa-chart-bar"></i>
            Lihat Statistik
        </button>
    </div>
</div>

<!-- Charts Section -->
<div class="charts-section">
    <div class="chart-container">
        <h3 class="chart-title">Penugasan per Kelas</h3>
        <canvas id="assignmentsByClassChart"></canvas>
    </div>
    
    <div class="chart-container">
        <h3 class="chart-title">Penugasan per Mata Pelajaran</h3>
        <canvas id="assignmentsBySubjectChart"></canvas>
    </div>
</div>

<!-- Recent Assignments -->
<div class="recent-assignments">
    <h3 class="section-title">
        <i class="fas fa-clock"></i>
        Penugasan Terbaru
    </h3>
    <div class="assignments-list" id="recentAssignmentsList">
        <!-- Will be populated by JavaScript -->
    </div>
</div>

<!-- Include Components -->
@include('components.assign-teacher-modal')
@include('components.teacher-assignments-table')

<!-- Import Modal -->
<div id="importModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-upload"></i>
                Import Penugasan Guru
            </h3>
            <button type="button" class="modal-close" onclick="closeImportModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="importForm" method="POST" action="{{ route('api.assignments.import') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="importFile" class="form-label">Pilih File Excel</label>
                    <input type="file" id="importFile" name="file" accept=".xlsx,.xls,.csv" required>
                    <small class="form-text">Format: Excel (.xlsx, .xls) atau CSV (.csv)</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Template File</label>
                    <a href="{{ route('api.assignments.export') }}" class="btn btn-sm btn-outline">
                        <i class="fas fa-download"></i>
                        Download Template
                    </a>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeImportModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-upload"></i> Import
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Statistics Modal -->
<div id="statisticsModal" class="modal" style="display: none;">
    <div class="modal-content large">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-chart-bar"></i>
                Statistik Penugasan Guru
            </h3>
            <button type="button" class="modal-close" onclick="closeStatisticsModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <div class="stats-grid-detailed">
                <div class="stat-item">
                    <h4>Penugasan per Kelas</h4>
                    <canvas id="detailedClassChart"></canvas>
                </div>
                <div class="stat-item">
                    <h4>Penugasan per Mata Pelajaran</h4>
                    <canvas id="detailedSubjectChart"></canvas>
                </div>
                <div class="stat-item">
                    <h4>Penugasan per Guru</h4>
                    <canvas id="detailedTeacherChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: linear-gradient(135deg, #1e293b, #334155);
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid #475569;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0;
}

.stat-label {
    color: #94a3b8;
    margin: 0;
    font-size: 0.9rem;
}

.quick-actions {
    background: #1e293b;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid #334155;
}

.section-title {
    color: #ffffff;
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0 0 1rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.charts-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.chart-container {
    background: #1e293b;
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid #334155;
}

.chart-title {
    color: #ffffff;
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0 0 1rem 0;
    text-align: center;
}

.recent-assignments {
    background: #1e293b;
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid #334155;
}

.assignments-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.assignment-item {
    background: #0f172a;
    border-radius: 8px;
    padding: 1rem;
    border: 1px solid #334155;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.assignment-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.assignment-title {
    color: #ffffff;
    font-weight: 600;
    margin: 0;
}

.assignment-meta {
    color: #94a3b8;
    font-size: 0.9rem;
    margin: 0;
}

.assignment-time {
    color: #64748b;
    font-size: 0.8rem;
}

.modal.large .modal-content {
    max-width: 800px;
}

.stats-grid-detailed {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.stat-item {
    text-align: center;
}

.stat-item h4 {
    color: #ffffff;
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .charts-section {
        grid-template-columns: 1fr;
    }
    
    .stats-grid-detailed {
        grid-template-columns: 1fr;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let classChart, subjectChart, teacherChart;

// Load dashboard data
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardData();
    loadRecentAssignments();
    initializeCharts();
});

// Load dashboard statistics
async function loadDashboardData() {
    try {
        const response = await fetch('/api/assignments/statistics');
        const data = await response.json();
        
        document.getElementById('totalAssignments').textContent = data.total_assignments || 0;
        document.getElementById('totalClasses').textContent = data.total_classes || 0;
        document.getElementById('totalSubjects').textContent = data.total_subjects || 0;
        document.getElementById('totalTeachers').textContent = data.total_teachers || 0;
        
        // Update charts with data
        updateCharts(data);
        
    } catch (error) {
        console.error('Error loading dashboard data:', error);
    }
}

// Load recent assignments
async function loadRecentAssignments() {
    try {
        const response = await fetch('/api/assignments?limit=5');
        const data = await response.json();
        
        const container = document.getElementById('recentAssignmentsList');
        container.innerHTML = '';
        
        if (data.length === 0) {
            container.innerHTML = '<p class="text-center text-gray-400">Belum ada penugasan</p>';
            return;
        }
        
        data.forEach(assignment => {
            const item = document.createElement('div');
            item.className = 'assignment-item';
            item.innerHTML = `
                <div class="assignment-info">
                    <h4 class="assignment-title">${assignment.teacher_name} → ${assignment.class_name}</h4>
                    <p class="assignment-meta">Mata Pelajaran: ${assignment.subject_name}</p>
                    <p class="assignment-time">${assignment.assigned_at}</p>
                </div>
            `;
            container.appendChild(item);
        });
        
    } catch (error) {
        console.error('Error loading recent assignments:', error);
    }
}

// Initialize charts
function initializeCharts() {
    // Assignments by Class Chart
    const classCtx = document.getElementById('assignmentsByClassChart').getContext('2d');
    classChart = new Chart(classCtx, {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: [
                    '#3b82f6',
                    '#10b981',
                    '#f59e0b',
                    '#ef4444',
                    '#8b5cf6',
                    '#06b6d4',
                    '#84cc16',
                    '#f97316'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#ffffff'
                    }
                }
            }
        }
    });
    
    // Assignments by Subject Chart
    const subjectCtx = document.getElementById('assignmentsBySubjectChart').getContext('2d');
    subjectChart = new Chart(subjectCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Jumlah Penugasan',
                data: [],
                backgroundColor: '#3b82f6',
                borderColor: '#1d4ed8',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#ffffff'
                    },
                    grid: {
                        color: '#334155'
                    }
                },
                x: {
                    ticks: {
                        color: '#ffffff'
                    },
                    grid: {
                        color: '#334155'
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: '#ffffff'
                    }
                }
            }
        }
    });
}

// Update charts with data
function updateCharts(data) {
    // Update class chart
    if (data.assignments_by_class) {
        classChart.data.labels = Object.keys(data.assignments_by_class);
        classChart.data.datasets[0].data = Object.values(data.assignments_by_class);
        classChart.update();
    }
    
    // Update subject chart
    if (data.assignments_by_subject) {
        subjectChart.data.labels = Object.keys(data.assignments_by_subject);
        subjectChart.data.datasets[0].data = Object.values(data.assignments_by_subject);
        subjectChart.update();
    }
}

// Export assignments
function exportAssignments() {
    window.open('/api/assignments/export', '_blank');
}

// Import assignments
function importAssignments() {
    document.getElementById('importModal').style.display = 'flex';
}

// Close import modal
function closeImportModal() {
    document.getElementById('importModal').style.display = 'none';
    document.getElementById('importForm').reset();
}

// View statistics
function viewStatistics() {
    document.getElementById('statisticsModal').style.display = 'flex';
    loadDetailedStatistics();
}

// Close statistics modal
function closeStatisticsModal() {
    document.getElementById('statisticsModal').style.display = 'none';
}

// Load detailed statistics
async function loadDetailedStatistics() {
    try {
        const response = await fetch('/api/assignments/statistics');
        const data = await response.json();
        
        // Update detailed charts
        updateDetailedCharts(data);
        
    } catch (error) {
        console.error('Error loading detailed statistics:', error);
    }
}

// Update detailed charts
function updateDetailedCharts(data) {
    // Detailed class chart
    const detailedClassCtx = document.getElementById('detailedClassChart').getContext('2d');
    new Chart(detailedClassCtx, {
        type: 'pie',
        data: {
            labels: Object.keys(data.assignments_by_class || {}),
            datasets: [{
                data: Object.values(data.assignments_by_class || {}),
                backgroundColor: [
                    '#3b82f6', '#10b981', '#f59e0b', '#ef4444',
                    '#8b5cf6', '#06b6d4', '#84cc16', '#f97316'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#ffffff'
                    }
                }
            }
        }
    });
    
    // Detailed subject chart
    const detailedSubjectCtx = document.getElementById('detailedSubjectChart').getContext('2d');
    new Chart(detailedSubjectCtx, {
        type: 'horizontalBar',
        data: {
            labels: Object.keys(data.assignments_by_subject || {}),
            datasets: [{
                data: Object.values(data.assignments_by_subject || {}),
                backgroundColor: '#10b981'
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            plugins: {
                legend: {
                    labels: {
                        color: '#ffffff'
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: '#ffffff'
                    },
                    grid: {
                        color: '#334155'
                    }
                },
                y: {
                    ticks: {
                        color: '#ffffff'
                    },
                    grid: {
                        color: '#334155'
                    }
                }
            }
        }
    });
    
    // Detailed teacher chart
    const detailedTeacherCtx = document.getElementById('detailedTeacherChart').getContext('2d');
    new Chart(detailedTeacherCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(data.assignments_by_teacher || {}),
            datasets: [{
                data: Object.values(data.assignments_by_teacher || {}),
                backgroundColor: '#f59e0b'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#ffffff'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#ffffff'
                    },
                    grid: {
                        color: '#334155'
                    }
                },
                x: {
                    ticks: {
                        color: '#ffffff'
                    },
                    grid: {
                        color: '#334155'
                    }
                }
            }
        }
    });
}

// Handle import form submission
document.getElementById('importForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Importing...';
    submitBtn.disabled = true;
    
    try {
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Data berhasil diimport!', 'success');
            closeImportModal();
            loadDashboardData();
            loadRecentAssignments();
        } else {
            showNotification(result.message || 'Gagal mengimport data', 'error');
        }
        
    } catch (error) {
        console.error('Import error:', error);
        showNotification('Terjadi kesalahan saat mengimport data', 'error');
    } finally {
        submitBtn.innerHTML = '<i class="fas fa-upload"></i> Import';
        submitBtn.disabled = false;
    }
});

// Notification function
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification-toast ${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => notification.classList.add('show'), 100);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>
@endsection
