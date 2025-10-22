@extends('layouts.unified-layout')

@section('title', 'Teacher Reports - Analytics Dashboard')

@push('styles')
<style>
    /* Modern UI Design System */
    :root {
        --primary-blue: #3b82f6;
        --primary-blue-dark: #1d4ed8;
        --success-green: #10b981;
        --warning-orange: #f59e0b;
        --danger-red: #ef4444;
        --purple: #8b5cf6;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-300: #cbd5e1;
        --gray-400: #94a3b8;
        --gray-500: #64748b;
        --gray-600: #475569;
        --gray-700: #334155;
        --gray-800: #1e293b;
        --gray-900: #0f172a;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--gray-50);
    }

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-blue-dark) 100%);
        color: white;
        padding: 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-description {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 0.5rem 0 0 0;
    }

    /* Statistics Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--gray-200);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-blue), var(--primary-blue-dark));
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px rgba(0, 0, 0, 0.1);
    }

    .stat-card.tasks::before { background: linear-gradient(90deg, var(--primary-blue), var(--primary-blue-dark)); }
    .stat-card.exams::before { background: linear-gradient(90deg, var(--success-green), #059669); }
    .stat-card.materials::before { background: linear-gradient(90deg, var(--warning-orange), #d97706); }
    .stat-card.students::before { background: linear-gradient(90deg, var(--purple), #7c3aed); }

    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .stat-icon.tasks { background: var(--primary-blue); }
    .stat-icon.exams { background: var(--success-green); }
    .stat-icon.materials { background: var(--warning-orange); }
    .stat-icon.students { background: var(--purple); }

    .stat-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--gray-600);
        margin: 0;
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--gray-900);
        margin: 0.5rem 0;
    }

    .stat-breakdown {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .breakdown-item {
        text-align: center;
        flex: 1;
    }

    .breakdown-value {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--gray-700);
    }

    .breakdown-label {
        font-size: 0.75rem;
        color: var(--gray-500);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Filter Panel */
    .filter-panel {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--gray-200);
    }

    .filter-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--gray-600);
        margin-bottom: 0.5rem;
    }

    .filter-input {
        padding: 0.75rem;
        border: 1px solid var(--gray-300);
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .filter-input:focus {
        outline: none;
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-primary {
        background: var(--primary-blue);
        color: white;
    }

    .btn-primary:hover {
        background: var(--primary-blue-dark);
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: var(--gray-100);
        color: var(--gray-700);
        border: 1px solid var(--gray-300);
    }

    .btn-secondary:hover {
        background: var(--gray-200);
    }

    .btn-success {
        background: var(--success-green);
        color: white;
    }

    .btn-success:hover {
        background: #059669;
        transform: translateY(-1px);
    }

    /* Charts Section */
    .charts-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .chart-container {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--gray-200);
    }

    .chart-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .chart-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    /* Tabs Section */
    .tabs-section {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--gray-200);
        overflow: hidden;
    }

    .tabs-header {
        display: flex;
        background: var(--gray-50);
        border-bottom: 1px solid var(--gray-200);
    }

    .tab-button {
        flex: 1;
        padding: 1rem 1.5rem;
        background: none;
        border: none;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--gray-600);
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .tab-button.active {
        color: var(--primary-blue);
        background: white;
    }

    .tab-button.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--primary-blue);
    }

    .tab-content {
        padding: 1.5rem;
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* Table Styles */
    .modern-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }

    .modern-table th {
        background: var(--gray-50);
        color: var(--gray-700);
        font-weight: 600;
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--gray-200);
        position: sticky;
        top: 0;
    }

    .modern-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--gray-100);
        color: var(--gray-600);
    }

    .modern-table tbody tr:hover {
        background: var(--gray-50);
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .status-active { background: #dcfce7; color: #166534; }
    .status-completed { background: #dbeafe; color: #1e40af; }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-excellent { background: #dcfce7; color: #166534; }
    .status-good { background: #dbeafe; color: #1e40af; }
    .status-needs-improvement { background: #fee2e2; color: #991b1b; }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-sm {
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .charts-section {
            grid-template-columns: 1fr;
        }
        
        .chart-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .filter-grid {
            grid-template-columns: 1fr;
        }
        
        .tabs-header {
            flex-direction: column;
        }
        
        .tab-button {
            border-bottom: 1px solid var(--gray-200);
        }
        
        .modern-table {
            font-size: 0.75rem;
        }
        
        .modern-table th,
        .modern-table td {
            padding: 0.75rem 0.5rem;
        }
    }

    /* Loading States */
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }

    .spinner {
        width: 20px;
        height: 20px;
        border: 2px solid var(--gray-300);
        border-top: 2px solid var(--primary-blue);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-chart-line"></i>
            Teacher Reports & Analytics
        </h1>
        <p class="page-description">Comprehensive analytics and detailed reports for your classes and students</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card tasks">
            <div class="stat-header">
                <div class="stat-icon tasks">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
            <h3 class="stat-title">Total Tasks</h3>
            <div class="stat-value">{{ $stats['total_tasks'] ?? 0 }}</div>
            <div class="stat-breakdown">
                <div class="breakdown-item">
                    <div class="breakdown-value">{{ $stats['active_tasks'] ?? 0 }}</div>
                    <div class="breakdown-label">Active</div>
                </div>
                <div class="breakdown-item">
                    <div class="breakdown-value">{{ $stats['completed_tasks'] ?? 0 }}</div>
                    <div class="breakdown-label">Completed</div>
                </div>
            </div>
        </div>

        <div class="stat-card exams">
            <div class="stat-header">
                <div class="stat-icon exams">
                    <i class="fas fa-clipboard-check"></i>
                </div>
            </div>
            <h3 class="stat-title">Total Exams</h3>
            <div class="stat-value">{{ $stats['total_exams'] ?? 0 }}</div>
            <div class="stat-breakdown">
                <div class="breakdown-item">
                    <div class="breakdown-value">{{ $stats['ongoing_exams'] ?? 0 }}</div>
                    <div class="breakdown-label">Ongoing</div>
                </div>
                <div class="breakdown-item">
                    <div class="breakdown-value">{{ $stats['finished_exams'] ?? 0 }}</div>
                    <div class="breakdown-label">Finished</div>
                </div>
            </div>
        </div>

        <div class="stat-card materials">
            <div class="stat-header">
                <div class="stat-icon materials">
                    <i class="fas fa-book"></i>
                </div>
            </div>
            <h3 class="stat-title">Total Materials</h3>
            <div class="stat-value">{{ $stats['total_materials'] ?? 0 }}</div>
            <div class="stat-breakdown">
                <div class="breakdown-item">
                    <div class="breakdown-value">{{ $stats['video_materials'] ?? 0 }}</div>
                    <div class="breakdown-label">Videos</div>
                </div>
                <div class="breakdown-item">
                    <div class="breakdown-value">{{ $stats['document_materials'] ?? 0 }}</div>
                    <div class="breakdown-label">Documents</div>
                </div>
            </div>
        </div>

        <div class="stat-card students">
            <div class="stat-header">
                <div class="stat-icon students">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <h3 class="stat-title">Total Students</h3>
            <div class="stat-value">{{ $stats['total_students'] ?? 0 }}</div>
            <div class="stat-breakdown">
                <div class="breakdown-item">
                    <div class="breakdown-value">{{ number_format($stats['avg_student_score'] ?? 0, 1) }}</div>
                    <div class="breakdown-label">Avg Score</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Panel -->
    <div class="filter-panel">
        <h3 class="filter-title">
            <i class="fas fa-filter"></i>
            Filter Reports
        </h3>
        <form method="GET" action="{{ route('teacher.reports') }}" id="filterForm">
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Classes</label>
                    <select name="kelas[]" class="filter-input" multiple>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" 
                                {{ in_array($class->id, $filters['kelas'] ?? []) ? 'selected' : '' }}>
                                {{ $class->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Subjects</label>
                    <select name="mapel[]" class="filter-input" multiple>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" 
                                {{ in_array($subject->id, $filters['mapel'] ?? []) ? 'selected' : '' }}>
                                {{ $subject->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Date From</label>
                    <input type="date" name="date_from" class="filter-input" 
                           value="{{ $filters['date_from'] ?? '' }}">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Date To</label>
                    <input type="date" name="date_to" class="filter-input" 
                           value="{{ $filters['date_to'] ?? '' }}">
                </div>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                    Apply Filter
                </button>
                <a href="{{ route('teacher.reports') }}" class="btn btn-secondary">
                    <i class="fas fa-undo"></i>
                    Reset Filter
                </a>
                <button type="button" class="btn btn-success" onclick="exportReports()">
                    <i class="fas fa-download"></i>
                    Export to Excel
                </button>
            </div>
        </form>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
        <div class="chart-container">
            <h3 class="chart-title">
                <i class="fas fa-chart-line"></i>
                Monthly Performance Trend
            </h3>
            <div id="monthlyTrendChart" style="height: 300px;"></div>
        </div>
        <div class="chart-container">
            <h3 class="chart-title">
                <i class="fas fa-chart-pie"></i>
                Task Status Distribution
            </h3>
            <div id="taskStatusChart" style="height: 300px;"></div>
        </div>
    </div>

    <div class="chart-grid">
        <div class="chart-container">
            <h3 class="chart-title">
                <i class="fas fa-chart-bar"></i>
                Completion Rate by Subject
            </h3>
            <div id="completionBySubjectChart" style="height: 300px;"></div>
        </div>
        <div class="chart-container">
            <h3 class="chart-title">
                <i class="fas fa-trophy"></i>
                Top 10 Students
            </h3>
            <div id="topStudentsChart" style="height: 300px;"></div>
        </div>
    </div>

    <!-- Detailed Reports Tabs -->
    <div class="tabs-section">
        <div class="tabs-header">
            <button class="tab-button active" onclick="switchTab('tasks')">
                <i class="fas fa-tasks"></i>
                Tasks Report
            </button>
            <button class="tab-button" onclick="switchTab('exams')">
                <i class="fas fa-clipboard-check"></i>
                Exams Report
            </button>
            <button class="tab-button" onclick="switchTab('materials')">
                <i class="fas fa-book"></i>
                Materials Report
            </button>
            <button class="tab-button" onclick="switchTab('students')">
                <i class="fas fa-users"></i>
                Students Report
            </button>
        </div>

        <!-- Tasks Tab -->
        <div id="tasks-tab" class="tab-content active">
            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th onclick="sortTable('name')">Task Name <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable('class')">Class <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable('subject')">Subject <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable('deadline')">Deadline <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable('completion')">Completion <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable('score')">Avg Score <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable('status')">Status <i class="fas fa-sort"></i></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            <tr>
                                <td><strong>{{ $task->name }}</strong></td>
                                <td>{{ $task->kelasMapel->kelas->nama_kelas ?? 'N/A' }}</td>
                                <td>{{ $task->kelasMapel->mapel->nama_mapel ?? 'N/A' }}</td>
                                <td>{{ $task->due ? $task->due->format('d M Y H:i') : 'No deadline' }}</td>
                                <td>{{ $task->submitted_count }}/{{ $task->total_assignments }} ({{ $task->completion_rate }}%)</td>
                                <td>{{ number_format($task->user_tugas_avg_nilai ?? 0, 1) }}</td>
                                <td>
                                    <span class="status-badge status-{{ $task->status }}">
                                        {{ ucfirst($task->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('teacher.reports.task.detail', $task->id) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 2rem; color: var(--gray-500);">
                                    <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                                    <br>No tasks found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Exams Tab -->
        <div id="exams-tab" class="tab-content">
            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th onclick="sortTable('name')">Exam Name <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable('class')">Class <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable('subject')">Subject <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable('date')">Date <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable('participants')">Participants <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable('score')">Avg Score <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable('status')">Status <i class="fas fa-sort"></i></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exams as $exam)
                            <tr>
                                <td><strong>{{ $exam->name }}</strong></td>
                                <td>{{ $exam->kelasMapel->kelas->nama_kelas ?? 'N/A' }}</td>
                                <td>{{ $exam->kelasMapel->mapel->nama_mapel ?? 'N/A' }}</td>
                                <td>{{ $exam->created_at->format('d M Y H:i') }}</td>
                                <td>{{ $exam->participants_count }}/{{ $exam->total_participants }} ({{ $exam->participation_rate }}%)</td>
                                <td>{{ number_format($exam->user_ujian_avg_nilai ?? 0, 1) }}</td>
                                <td>
                                    <span class="status-badge status-{{ $exam->status }}">
                                        {{ ucfirst($exam->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('teacher.reports.exam.detail', $exam->id) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 2rem; color: var(--gray-500);">
                                    <i class="fas fa-clipboard-check" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                                    <br>No exams found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Materials Tab -->
        <div id="materials-tab" class="tab-content">
            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th onclick="sortTable('name')">Material Name <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable('class')">Class <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable('subject')">Subject <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable('type')">Type <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable('file')">File <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable('status')">Status <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable('date')">Upload Date <i class="fas fa-sort"></i></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($materials as $material)
                            <tr>
                                <td><strong>{{ $material->name }}</strong></td>
                                <td>{{ $material->kelasMapel->kelas->nama_kelas ?? 'N/A' }}</td>
                                <td>{{ $material->kelasMapel->mapel->nama_mapel ?? 'N/A' }}</td>
                                <td>
                                    <span class="status-badge status-{{ $material->type }}">
                                        {{ ucfirst($material->type) }}
                                    </span>
                                </td>
                                <td>{{ $material->file_materi ?? 'Text Content' }}</td>
                                <td>
                                    <span class="status-badge status-{{ $material->isHidden ? 'completed' : 'active' }}">
                                        {{ $material->isHidden ? 'Hidden' : 'Active' }}
                                    </span>
                                </td>
                                <td>{{ $material->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="#" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 2rem; color: var(--gray-500);">
                                    <i class="fas fa-book" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                                    <br>No materials found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Students Tab -->
        <div id="students-tab" class="tab-content">
            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th onclick="sortTable('name')">Student Name <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable('class')">Class <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable('email')">Email <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable('tasks')">Tasks Completed <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable('exams')">Exams Completed <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable('score')">Avg Score <i class="fas fa-sort"></i></th>
                            <th onclick="sortTable('status')">Status <i class="fas fa-sort"></i></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td><strong>{{ $student->name }}</strong></td>
                                <td>{{ $student->kelas->nama_kelas ?? 'N/A' }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->tasks_completed }}</td>
                                <td>{{ $student->exams_completed }}</td>
                                <td>{{ number_format($student->avg_score, 1) }}</td>
                                <td>
                                    <span class="status-badge status-{{ $student->status }}">
                                        {{ ucfirst(str_replace('-', ' ', $student->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('teacher.reports.student.detail', $student->id) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 2rem; color: var(--gray-500);">
                                    <i class="fas fa-users" style="font-size: 2rem; margin-bottom: 1rem;"></i>
                                    <br>No students found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Chart Data
    const chartData = @json($chartData ?? []);

    // Initialize Charts
    document.addEventListener('DOMContentLoaded', function() {
        initMonthlyTrendChart();
        initTaskStatusChart();
        initCompletionBySubjectChart();
        initTopStudentsChart();
    });

    // Monthly Trend Chart
    function initMonthlyTrendChart() {
        const options = {
            series: [{
                name: 'Average Score',
                data: chartData.monthly_trend?.map(item => item.avg_score) || []
            }],
            chart: {
                type: 'line',
                height: 300,
                toolbar: { show: false },
                animations: { enabled: true }
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
                categories: chartData.monthly_trend?.map(item => item.month) || [],
                labels: { style: { colors: '#64748b' } }
            },
            yaxis: {
                min: 0,
                max: 100,
                labels: { style: { colors: '#64748b' } }
            },
            grid: {
                borderColor: '#e2e8f0',
                strokeDashArray: 4
            },
            tooltip: {
                theme: 'light',
                style: { fontSize: '12px' }
            }
        };

        const chart = new ApexCharts(document.querySelector("#monthlyTrendChart"), options);
        chart.render();
    }

    // Task Status Distribution Chart
    function initTaskStatusChart() {
        const options = {
            series: [
                chartData.task_status_distribution?.submitted || 0,
                chartData.task_status_distribution?.pending || 0,
                chartData.task_status_distribution?.late || 0
            ],
            chart: {
                type: 'donut',
                height: 300
            },
            colors: ['#10b981', '#f59e0b', '#ef4444'],
            labels: ['Submitted', 'Pending', 'Late'],
            legend: {
                position: 'bottom',
                fontSize: '14px'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%'
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val.toFixed(1) + "%"
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#taskStatusChart"), options);
        chart.render();
    }

    // Completion by Subject Chart
    function initCompletionBySubjectChart() {
        const options = {
            series: [{
                name: 'Completion Rate (%)',
                data: chartData.completion_by_subject?.map(item => item.completion_rate) || []
            }],
            chart: {
                type: 'bar',
                height: 300,
                toolbar: { show: false }
            },
            colors: ['#3b82f6'],
            plotOptions: {
                bar: {
                    borderRadius: 8,
                    horizontal: true
                }
            },
            xaxis: {
                categories: chartData.completion_by_subject?.map(item => item.subject) || [],
                labels: { style: { colors: '#64748b' } }
            },
            yaxis: {
                labels: { style: { colors: '#64748b' } }
            },
            grid: {
                borderColor: '#e2e8f0',
                strokeDashArray: 4
            },
            tooltip: {
                theme: 'light'
            }
        };

        const chart = new ApexCharts(document.querySelector("#completionBySubjectChart"), options);
        chart.render();
    }

    // Top Students Chart
    function initTopStudentsChart() {
        const options = {
            series: [{
                name: 'Average Score',
                data: chartData.top_students?.map(item => item.avg_score) || []
            }],
            chart: {
                type: 'bar',
                height: 300,
                toolbar: { show: false }
            },
            colors: ['#8b5cf6'],
            plotOptions: {
                bar: {
                    borderRadius: 8,
                    horizontal: true
                }
            },
            xaxis: {
                categories: chartData.top_students?.map(item => item.name) || [],
                labels: { style: { colors: '#64748b' } }
            },
            yaxis: {
                labels: { style: { colors: '#64748b' } }
            },
            grid: {
                borderColor: '#e2e8f0',
                strokeDashArray: 4
            },
            tooltip: {
                theme: 'light'
            }
        };

        const chart = new ApexCharts(document.querySelector("#topStudentsChart"), options);
        chart.render();
    }

    // Tab Switching
    function switchTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });
        
        // Remove active class from all buttons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active');
        });
        
        // Show selected tab content
        document.getElementById(tabName + '-tab').classList.add('active');
        
        // Add active class to clicked button
        event.target.classList.add('active');
    }

    // Table Sorting
    function sortTable(column) {
        // This would implement client-side sorting
        // For now, we'll just show a loading state
        console.log('Sorting by:', column);
    }

    // Export Reports
    function exportReports() {
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const params = new URLSearchParams();
        
        for (let [key, value] of formData.entries()) {
            if (Array.isArray(value)) {
                value.forEach(v => params.append(key + '[]', v));
            } else {
                params.append(key, value);
            }
        }
        
        window.location.href = '{{ route("teacher.reports.export") }}?' + params.toString();
    }

    // Multi-select styling
    document.addEventListener('DOMContentLoaded', function() {
        // Add some basic styling for multi-select elements
        const multiSelects = document.querySelectorAll('select[multiple]');
        multiSelects.forEach(select => {
            select.style.minHeight = '120px';
        });
    });
</script>
@endpush