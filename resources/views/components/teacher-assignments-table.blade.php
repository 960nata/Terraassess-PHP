<!-- Tabel Penugasan Guru -->
<div class="assignments-section">
    <div class="section-header">
        <h3 class="section-title">
            <i class="fas fa-chalkboard-teacher"></i>
            Penugasan Guru ke Kelas-Mata Pelajaran
        </h3>
        <div class="section-actions">
            <button class="btn btn-primary" onclick="openAssignTeacherModal()">
                <i class="fas fa-plus"></i>
                Tugaskan Guru
            </button>
            <button class="btn btn-secondary" onclick="refreshAssignmentsTable()">
                <i class="fas fa-sync-alt"></i>
                Refresh
            </button>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-row">
            <div class="filter-group">
                <label for="filterClass">Filter Kelas:</label>
                <select id="filterClass" onchange="filterAssignments()">
                    <option value="">Semua Kelas</option>
                    @foreach($classes ?? [] as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label for="filterSubject">Filter Mata Pelajaran:</label>
                <select id="filterSubject" onchange="filterAssignments()">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($subjects ?? [] as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label for="filterTeacher">Filter Guru:</label>
                <select id="filterTeacher" onchange="filterAssignments()">
                    <option value="">Semua Guru</option>
                    @foreach($teachers ?? [] as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <input type="text" id="searchAssignments" placeholder="Cari penugasan..." onkeyup="searchAssignments()">
            </div>
        </div>
    </div>

    <!-- Assignments Table -->
    <div class="assignments-table-container">
        <table class="assignments-table" id="assignmentsTable">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                    </th>
                    <th onclick="sortAssignments('kelas')">
                        Kelas <i class="fas fa-sort"></i>
                    </th>
                    <th onclick="sortAssignments('mata_pelajaran')">
                        Mata Pelajaran <i class="fas fa-sort"></i>
                    </th>
                    <th onclick="sortAssignments('guru')">
                        Guru <i class="fas fa-sort"></i>
                    </th>
                    <th onclick="sortAssignments('tanggal_ditugaskan')">
                        Tanggal Ditugaskan <i class="fas fa-sort"></i>
                    </th>
                    <th onclick="sortAssignments('status')">
                        Status <i class="fas fa-sort"></i>
                    </th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="assignmentsTableBody">
                @forelse($assignments ?? [] as $assignment)
                    <tr class="assignment-row" 
                        data-kelas="{{ $assignment->kelasMapel->kelas->name }}"
                        data-mata-pelajaran="{{ $assignment->kelasMapel->mapel->name }}"
                        data-guru="{{ $assignment->user->name }}"
                        data-tanggal="{{ $assignment->created_at->format('Y-m-d') }}">
                        <td>
                            <input type="checkbox" class="assignment-checkbox" value="{{ $assignment->id }}">
                        </td>
                        <td>
                            <div class="class-info">
                                <div class="class-name">{{ $assignment->kelasMapel->kelas->name }}</div>
                                <div class="class-level">{{ $assignment->kelasMapel->kelas->level ?? 'SMA' }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="subject-info">
                                <div class="subject-name">{{ $assignment->kelasMapel->mapel->name }}</div>
                                <div class="subject-code">{{ $assignment->kelasMapel->mapel->code ?? 'N/A' }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="teacher-info">
                                <div class="teacher-name">{{ $assignment->user->name }}</div>
                                <div class="teacher-email">{{ $assignment->user->email }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="date-info">
                                <div class="date">{{ $assignment->created_at->format('d M Y') }}</div>
                                <div class="time">{{ $assignment->created_at->format('H:i') }}</div>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge status-active">
                                <i class="fas fa-check-circle"></i>
                                Aktif
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action btn-view" onclick="viewAssignment('{{ $assignment->id }}')" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-action btn-edit" onclick="editAssignment('{{ $assignment->id }}')" title="Edit Penugasan">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-action btn-delete" onclick="deleteAssignment('{{ $assignment->id }}')" title="Hapus Penugasan">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-state">
                            <div class="empty-content">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <h4>Belum Ada Penugasan Guru</h4>
                                <p>Mulai dengan menugaskan guru ke kelas-mata pelajaran</p>
                                <button class="btn btn-primary" onclick="openAssignTeacherModal()">
                                    <i class="fas fa-plus"></i>
                                    Tugaskan Guru Pertama
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Bulk Actions -->
    <div class="bulk-actions" id="bulkActions" style="display: none;">
        <div class="bulk-info">
            <span id="selectedCount">0</span> penugasan dipilih
        </div>
        <div class="bulk-buttons">
            <button class="btn btn-warning" onclick="bulkEditAssignments()">
                <i class="fas fa-edit"></i>
                Edit Massal
            </button>
            <button class="btn btn-danger" onclick="bulkDeleteAssignments()">
                <i class="fas fa-trash"></i>
                Hapus Massal
            </button>
        </div>
    </div>

    <!-- Pagination -->
    @if(isset($assignments) && $assignments->hasPages())
        <div class="pagination-container">
            {{ $assignments->links() }}
        </div>
    @endif
</div>

<style>
.assignments-section {
    background: #1e293b;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid #334155;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #334155;
}

.section-title {
    color: #ffffff;
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-actions {
    display: flex;
    gap: 0.75rem;
}

.filter-section {
    background: #0f172a;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    border: 1px solid #334155;
}

.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-group label {
    color: #94a3b8;
    font-size: 0.875rem;
    font-weight: 500;
}

.filter-group select,
.filter-group input {
    padding: 0.5rem 0.75rem;
    background: #1e293b;
    border: 1px solid #334155;
    border-radius: 6px;
    color: #ffffff;
    font-size: 0.875rem;
}

.filter-group input {
    width: 100%;
}

.assignments-table-container {
    overflow-x: auto;
    border-radius: 8px;
    border: 1px solid #334155;
}

.assignments-table {
    width: 100%;
    border-collapse: collapse;
    background: #0f172a;
}

.assignments-table th {
    background: #1e293b;
    color: #ffffff;
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    border-bottom: 1px solid #334155;
    cursor: pointer;
    user-select: none;
}

.assignments-table th:hover {
    background: #334155;
}

.assignments-table td {
    padding: 1rem;
    border-bottom: 1px solid #334155;
    color: #e2e8f0;
}

.assignment-row:hover {
    background: #1e293b;
}

.class-info,
.subject-info,
.teacher-info,
.date-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.class-name,
.subject-name,
.teacher-name,
.date {
    font-weight: 500;
    color: #ffffff;
}

.class-level,
.subject-code,
.teacher-email,
.time {
    font-size: 0.8rem;
    color: #94a3b8;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-badge.status-active {
    background: rgba(34, 197, 94, 0.1);
    color: #22c55e;
    border: 1px solid rgba(34, 197, 94, 0.3);
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-action {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-view {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.btn-view:hover {
    background: rgba(59, 130, 246, 0.2);
}

.btn-edit {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}

.btn-edit:hover {
    background: rgba(245, 158, 11, 0.2);
}

.btn-delete {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.btn-delete:hover {
    background: rgba(239, 68, 68, 0.2);
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.empty-content i {
    font-size: 3rem;
    color: #64748b;
}

.empty-content h4 {
    color: #ffffff;
    margin: 0;
}

.empty-content p {
    color: #94a3b8;
    margin: 0;
}

.bulk-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: #1e293b;
    border-radius: 8px;
    margin-top: 1rem;
    border: 1px solid #334155;
}

.bulk-info {
    color: #94a3b8;
    font-weight: 500;
}

.bulk-buttons {
    display: flex;
    gap: 0.75rem;
}

.pagination-container {
    margin-top: 1.5rem;
    display: flex;
    justify-content: center;
}

@media (max-width: 768px) {
    .section-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .filter-row {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .bulk-actions {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
}
</style>

<script>
let currentSort = { column: null, direction: 'asc' };
let selectedAssignments = new Set();

// Toggle select all
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.assignment-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
        if (selectAll.checked) {
            selectedAssignments.add(checkbox.value);
        } else {
            selectedAssignments.delete(checkbox.value);
        }
    });
    
    updateBulkActions();
}

// Toggle individual checkbox
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('assignment-checkbox')) {
        if (e.target.checked) {
            selectedAssignments.add(e.target.value);
        } else {
            selectedAssignments.delete(e.target.value);
        }
        updateBulkActions();
    }
});

// Update bulk actions visibility
function updateBulkActions() {
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    
    if (selectedAssignments.size > 0) {
        bulkActions.style.display = 'flex';
        selectedCount.textContent = selectedAssignments.size;
    } else {
        bulkActions.style.display = 'none';
    }
}

// Filter assignments
function filterAssignments() {
    const classFilter = document.getElementById('filterClass').value;
    const subjectFilter = document.getElementById('filterSubject').value;
    const teacherFilter = document.getElementById('filterTeacher').value;
    const rows = document.querySelectorAll('.assignment-row');
    
    rows.forEach(row => {
        const kelas = row.dataset.kelas.toLowerCase();
        const mataPelajaran = row.dataset.mataPelajaran.toLowerCase();
        const guru = row.dataset.guru.toLowerCase();
        
        const classMatch = !classFilter || kelas.includes(classFilter.toLowerCase());
        const subjectMatch = !subjectFilter || mataPelajaran.includes(subjectFilter.toLowerCase());
        const teacherMatch = !teacherFilter || guru.includes(teacherFilter.toLowerCase());
        
        if (classMatch && subjectMatch && teacherMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Search assignments
function searchAssignments() {
    const searchTerm = document.getElementById('searchAssignments').value.toLowerCase();
    const rows = document.querySelectorAll('.assignment-row');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Sort assignments
function sortAssignments(column) {
    const table = document.getElementById('assignmentsTable');
    const tbody = document.getElementById('assignmentsTableBody');
    const rows = Array.from(tbody.querySelectorAll('.assignment-row'));
    
    // Determine sort direction
    if (currentSort.column === column) {
        currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
    } else {
        currentSort.direction = 'asc';
    }
    currentSort.column = column;
    
    // Sort rows
    rows.sort((a, b) => {
        let aValue, bValue;
        
        switch(column) {
            case 'kelas':
                aValue = a.dataset.kelas;
                bValue = b.dataset.kelas;
                break;
            case 'mata_pelajaran':
                aValue = a.dataset.mataPelajaran;
                bValue = b.dataset.mataPelajaran;
                break;
            case 'guru':
                aValue = a.dataset.guru;
                bValue = b.dataset.guru;
                break;
            case 'tanggal_ditugaskan':
                aValue = new Date(a.dataset.tanggal);
                bValue = new Date(b.dataset.tanggal);
                break;
            default:
                return 0;
        }
        
        if (currentSort.direction === 'asc') {
            return aValue > bValue ? 1 : -1;
        } else {
            return aValue < bValue ? 1 : -1;
        }
    });
    
    // Re-append sorted rows
    rows.forEach(row => tbody.appendChild(row));
    
    // Update sort indicators
    document.querySelectorAll('th i').forEach(icon => {
        icon.className = 'fas fa-sort';
    });
    
    const currentHeader = event.target.closest('th');
    const icon = currentHeader.querySelector('i');
    icon.className = currentSort.direction === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down';
}

// View assignment details
function viewAssignment(id) {
    // Implementation for viewing assignment details
    console.log('View assignment:', id);
    // You can implement a modal or redirect to detail page
}

// Edit assignment
function editAssignment(id) {
    // Implementation for editing assignment
    console.log('Edit assignment:', id);
    // You can implement edit functionality
}

// Delete assignment
function deleteAssignment(id) {
    if (confirm('Apakah Anda yakin ingin menghapus penugasan ini?')) {
        // Implementation for deleting assignment
        console.log('Delete assignment:', id);
        // You can implement delete functionality
    }
}

// Bulk edit assignments
function bulkEditAssignments() {
    if (selectedAssignments.size === 0) return;
    
    console.log('Bulk edit assignments:', Array.from(selectedAssignments));
    // Implementation for bulk edit
}

// Bulk delete assignments
function bulkDeleteAssignments() {
    if (selectedAssignments.size === 0) return;
    
    if (confirm(`Apakah Anda yakin ingin menghapus ${selectedAssignments.size} penugasan yang dipilih?`)) {
        console.log('Bulk delete assignments:', Array.from(selectedAssignments));
        // Implementation for bulk delete
    }
}

// Refresh assignments table
function refreshAssignmentsTable() {
    location.reload();
}
</script>
