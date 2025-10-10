@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Laporan')

@section('styles')
<style>
/* Simplified and clean styles */
.simple-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.stat-value {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.report-section {
    background: #1e293b;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 1px solid #334155;
}

.section-title {
    color: #ffffff;
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.simple-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.simple-card {
    background: #2a2a3e;
    border-radius: 8px;
    padding: 1.5rem;
    border: 1px solid #334155;
    transition: all 0.3s ease;
}

.simple-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
}

.card-title {
    color: #ffffff;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.card-content {
    color: #cbd5e1;
    font-size: 0.9rem;
    line-height: 1.6;
}

.btn-simple {
    background: #667eea;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 1rem;
}

.btn-simple:hover {
    background: #5a67d8;
    transform: translateY(-1px);
}

.btn-success {
    background: #10b981;
}

.btn-success:hover {
    background: #059669;
}

.simple-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}

.simple-table th,
.simple-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #334155;
}

.simple-table th {
    background: #2a2a3e;
    color: #ffffff;
    font-weight: 600;
    font-size: 0.9rem;
}

.simple-table td {
    color: #cbd5e1;
    font-size: 0.9rem;
}

.simple-table tr:hover {
    background: #2a2a3e;
}

.no-data {
    text-align: center;
    padding: 3rem;
    color: #94a3b8;
}

@media (max-width: 768px) {
    .simple-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .simple-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-chart-line"></i>
        Laporan Sistem
    </h1>
    <p class="page-description">Ringkasan data dan performa sistem pembelajaran</p>
</div>

<!-- Statistics Overview -->
<div class="simple-stats">
    <div class="stat-card">
        <div class="stat-value">{{ $totalStudents ?? 0 }}</div>
        <div class="stat-label">Total Siswa</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $totalTeachers ?? 0 }}</div>
        <div class="stat-label">Total Guru</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $completedTasks ?? 0 }}</div>
        <div class="stat-label">Tugas Selesai</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $pendingTasks ?? 0 }}</div>
        <div class="stat-label">Tugas Pending</div>
    </div>
</div>

<!-- Quick Actions -->
<div class="report-section">
    <h2 class="section-title">
        <i class="fas fa-bolt"></i>
        Aksi Cepat
    </h2>
    
    <div class="simple-grid">
        <div class="simple-card">
            <div class="card-title">📊 Laporan Siswa</div>
            <div class="card-content">
                Lihat ringkasan performa dan aktivitas siswa di semua kelas
            </div>
            <button class="btn-simple" onclick="generateStudentReport()">
                <i class="fas fa-users"></i> Generate Laporan
            </button>
        </div>
        
        <div class="simple-card">
            <div class="card-title">📚 Laporan Tugas</div>
            <div class="card-content">
                Analisis penyelesaian tugas dan nilai rata-rata per mata pelajaran
            </div>
            <button class="btn-simple" onclick="generateTaskReport()">
                <i class="fas fa-tasks"></i> Generate Laporan
            </button>
        </div>
        
        <div class="simple-card">
            <div class="card-title">📋 Laporan Ujian</div>
            <div class="card-content">
                Hasil ujian dan statistik performa siswa dalam ujian
            </div>
            <button class="btn-simple" onclick="generateExamReport()">
                <i class="fas fa-clipboard-check"></i> Generate Laporan
            </button>
        </div>
    </div>
</div>

<!-- Data Kelas -->
<div class="report-section">
    <h2 class="section-title">
        <i class="fas fa-chalkboard"></i>
        Data Kelas
    </h2>
    
    <div class="simple-grid">
        @forelse($classData ?? [] as $data)
            <div class="simple-card">
                <div class="card-title">{{ $data['class']->nama_kelas }}</div>
                <div class="card-content">
                    <p><strong>Siswa:</strong> {{ $data['total_students'] }} orang</p>
                    <p><strong>Tugas:</strong> {{ $data['total_tasks'] }} tugas</p>
                    <p><strong>Ujian:</strong> {{ $data['total_exams'] }} ujian</p>
                    <p><strong>Rata-rata Nilai:</strong> {{ number_format($data['average_score'], 1) }}</p>
                </div>
                <button class="btn-simple btn-success" data-class-id="{{ $data['class']->id }}" onclick="viewClassDetail(this.dataset.classId)">
                    <i class="fas fa-eye"></i> Lihat Detail
                </button>
            </div>
        @empty
            <div class="no-data">
                <i class="fas fa-chalkboard" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                <p>Belum ada data kelas</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Data Mata Pelajaran -->
<div class="report-section">
    <h2 class="section-title">
        <i class="fas fa-book"></i>
        Data Mata Pelajaran
    </h2>
    
    <div class="simple-grid">
        @forelse($subjectData ?? [] as $data)
            <div class="simple-card">
                <div class="card-title">{{ $data['subject']->nama_mapel }}</div>
                <div class="card-content">
                    <p><strong>Kelas:</strong> {{ $data['total_classes'] }} kelas</p>
                    <p><strong>Tugas:</strong> {{ $data['total_tasks'] }} tugas</p>
                    <p><strong>Ujian:</strong> {{ $data['total_exams'] }} ujian</p>
                    <p><strong>Rata-rata Nilai:</strong> {{ number_format($data['average_score'], 1) }}</p>
                </div>
                <button class="btn-simple btn-success" data-subject-id="{{ $data['subject']->id }}" onclick="viewSubjectDetail(this.dataset.subjectId)">
                    <i class="fas fa-eye"></i> Lihat Detail
                </button>
            </div>
        @empty
            <div class="no-data">
                <i class="fas fa-book" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                <p>Belum ada data mata pelajaran</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Laporan Terbaru -->
<div class="report-section">
    <h2 class="section-title">
        <i class="fas fa-file-alt"></i>
        Laporan Terbaru
    </h2>
    
    <table class="simple-table">
        <thead>
            <tr>
                <th>Nama Laporan</th>
                <th>Jenis</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports ?? [] as $report)
                <tr>
                    <td>
                        <strong>{{ $report->name ?? 'Laporan Sistem' }}</strong>
                        <br>
                        <small style="color: #94a3b8;">{{ Str::limit($report->description ?? 'Laporan sistem pembelajaran', 50) }}</small>
                    </td>
                    <td>{{ ucfirst($report->type ?? 'umum') }}</td>
                    <td>{{ $report->created_at ? \Carbon\Carbon::parse($report->created_at)->format('d M Y') : 'Tidak ada tanggal' }}</td>
                    <td>
                        <button class="btn-simple" data-report-id="{{ $report->id ?? 1 }}" onclick="viewReport(this.dataset.reportId)">
                            <i class="fas fa-eye"></i> Lihat
                        </button>
                        <button class="btn-simple btn-success" data-report-id="{{ $report->id ?? 1 }}" onclick="downloadReport(this.dataset.reportId)">
                            <i class="fas fa-download"></i> Download
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="no-data">
                        <i class="fas fa-file-alt" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                        <br>Belum ada laporan yang dibuat
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


<script>
// Simplified JavaScript functions
function viewClassDetail(classId) {
    window.location.href = `/superadmin/class-management?class_id=${classId}`;
}

function viewSubjectDetail(subjectId) {
    window.location.href = `/superadmin/subject-management?subject_id=${subjectId}`;
}

function generateStudentReport() {
    alert('Fitur laporan siswa akan segera tersedia!');
}

function generateTaskReport() {
    alert('Fitur laporan tugas akan segera tersedia!');
}

function generateExamReport() {
    alert('Fitur laporan ujian akan segera tersedia!');
}

function viewReport(id) {
    alert(`Melihat laporan ${id} - Fitur akan segera tersedia!`);
}

function downloadReport(id) {
    alert(`Mengunduh laporan ${id} - Fitur akan segera tersedia!`);
}
</script>
@endsection
