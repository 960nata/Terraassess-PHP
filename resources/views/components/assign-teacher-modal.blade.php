<!-- Modal untuk Menugaskan Guru ke Kelas-Mata Pelajaran -->
<div id="assignTeacherModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-chalkboard-teacher"></i>
                Menugaskan Guru ke Kelas-Mata Pelajaran
            </h3>
            <button type="button" class="modal-close" onclick="closeAssignTeacherModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="assignTeacherForm" method="POST" action="{{ route('superadmin.assign-teacher') }}">
            @csrf
            <div class="modal-body">
                <!-- Step 1: Pilih Guru -->
                <div class="assignment-step active" id="step1">
                    <h4 class="step-title">
                        <i class="fas fa-user-tie"></i>
                        Pilih Guru
                    </h4>
                    <div class="form-group">
                        <label for="assignTeacherId" class="form-label">Guru *</label>
                        <select name="teacher_id" id="assignTeacherId" class="form-select" required onchange="loadTeacherAssignments()">
                            <option value="">Pilih Guru</option>
                            @foreach($teachers ?? [] as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }} ({{ $teacher->email }})</option>
                            @endforeach
                        </select>
                        <div class="form-error" id="teacher-error"></div>
                    </div>
                    
                    <!-- Show current assignments -->
                    <div id="currentAssignments" class="current-assignments" style="display: none;">
                        <h5>Penugasan Saat Ini:</h5>
                        <div id="assignmentsList"></div>
                    </div>
                </div>

                <!-- Step 2: Pilih Kelas dan Mata Pelajaran -->
                <div class="assignment-step" id="step2">
                    <h4 class="step-title">
                        <i class="fas fa-chalkboard"></i>
                        Pilih Kelas & Mata Pelajaran
                    </h4>
                    
                    <!-- Multiple Selection Interface -->
                    <div class="multi-selection">
                        <div class="selection-grid">
                            <div class="selection-column">
                                <h5>Pilih Kelas:</h5>
                                <div class="checkbox-list" id="classSelection">
                                    @foreach($classes ?? [] as $class)
                                        <label class="checkbox-item">
                                            <input type="checkbox" name="selected_classes[]" value="{{ $class->id }}" 
                                                   onchange="loadSubjectsForSelectedClasses()">
                                            <span class="checkmark"></span>
                                            {{ $class->name }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div class="selection-column">
                                <h5>Pilih Mata Pelajaran:</h5>
                                <div class="checkbox-list" id="subjectSelection">
                                    @foreach($subjects ?? [] as $subject)
                                        <label class="checkbox-item">
                                            <input type="checkbox" name="selected_subjects[]" value="{{ $subject->id }}">
                                            <span class="checkmark"></span>
                                            {{ $subject->name }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <!-- Preview Assignments -->
                        <div id="assignmentPreview" class="assignment-preview" style="display: none;">
                            <h5>Preview Penugasan:</h5>
                            <div id="previewList"></div>
                        </div>
                    </div>
                </div>

                <!-- Hidden fields for bulk assignment -->
                <input type="hidden" name="bulk_assignments" id="bulkAssignments">

                <!-- Navigation Buttons -->
                <div class="step-navigation">
                    <button type="button" class="btn btn-secondary" id="prevStep" onclick="prevStep()" style="display: none;">
                        <i class="fas fa-arrow-left"></i> Sebelumnya
                    </button>
                    <button type="button" class="btn btn-primary" id="nextStep" onclick="nextStep()">
                        Selanjutnya <i class="fas fa-arrow-right"></i>
                    </button>
                    <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                        <i class="fas fa-check"></i> Tugaskan Guru
                    </button>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeAssignTeacherModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Enhanced styles for multi-step assignment */
.assignment-step {
    display: none;
    animation: fadeIn 0.3s ease-in-out;
}

.assignment-step.active {
    display: block;
}

.step-title {
    color: #ffffff;
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.multi-selection {
    margin-top: 1rem;
}

.selection-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.selection-column h5 {
    color: #ffffff;
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.checkbox-list {
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #334155;
    border-radius: 8px;
    padding: 1rem;
    background: #1e293b;
}

.checkbox-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 0;
    cursor: pointer;
    color: #cbd5e1;
    transition: all 0.2s ease;
}

.checkbox-item:hover {
    color: #ffffff;
    background: #334155;
    border-radius: 4px;
    padding: 0.5rem;
    margin: 0 -0.5rem;
}

.checkbox-item input[type="checkbox"] {
    display: none;
}

.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid #475569;
    border-radius: 4px;
    position: relative;
    transition: all 0.2s ease;
}

.checkbox-item input[type="checkbox"]:checked + .checkmark {
    background: #3b82f6;
    border-color: #3b82f6;
}

.checkbox-item input[type="checkbox"]:checked + .checkmark::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 12px;
    font-weight: bold;
}

.current-assignments {
    margin-top: 1rem;
    padding: 1rem;
    background: #1e293b;
    border-radius: 8px;
    border: 1px solid #334155;
}

.current-assignments h5 {
    color: #ffffff;
    margin-bottom: 0.5rem;
}

.assignment-preview {
    margin-top: 1rem;
    padding: 1rem;
    background: #1e293b;
    border-radius: 8px;
    border: 1px solid #334155;
}

.assignment-preview h5 {
    color: #ffffff;
    margin-bottom: 0.5rem;
}

.preview-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.25rem 0;
    color: #cbd5e1;
}

.step-navigation {
    display: flex;
    justify-content: space-between;
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 1px solid #334155;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: #1e293b;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    border: 1px solid #334155;
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid #334155;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.modal-title {
    color: #ffffff;
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.modal-close {
    background: none;
    border: none;
    color: #94a3b8;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0.25rem;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.modal-close:hover {
    color: #ffffff;
    background: #334155;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    padding: 1.5rem;
    border-top: 1px solid #334155;
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    color: #ffffff;
    font-weight: 500;
    font-size: 0.9rem;
}

.form-select {
    width: 100%;
    padding: 0.75rem 1rem;
    background: #2a2a3e;
    border: 2px solid #333;
    border-radius: 8px;
    color: #ffffff;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-select:focus {
    outline: none;
    border-color: #3b82f6;
    background: #333;
}

.form-error {
    color: #ef4444;
    font-size: 0.8rem;
    margin-top: 0.25rem;
    display: none;
}

.assignment-status {
    padding: 0.75rem 1rem;
    border-radius: 8px;
    margin-top: 0.5rem;
}

.assignment-status.success {
    background: rgba(34, 197, 94, 0.1);
    border: 1px solid rgba(34, 197, 94, 0.3);
    color: #22c55e;
}

.assignment-status.warning {
    background: rgba(245, 158, 11, 0.1);
    border: 1px solid rgba(245, 158, 11, 0.3);
    color: #f59e0b;
}

.assignment-status.error {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
}

.status-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    border: none;
}

.btn-primary {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #1d4ed8, #1e40af);
    transform: translateY(-1px);
}

.btn-secondary {
    background: #334155;
    color: #ffffff;
}

.btn-secondary:hover {
    background: #475569;
}

@media (max-width: 768px) {
    .modal-content {
        width: 95%;
        margin: 1rem;
    }
    
    .modal-header,
    .modal-body,
    .modal-footer {
        padding: 1rem;
    }
}
</style>

<script>
// Fungsi untuk membuka modal
function openAssignTeacherModal() {
    document.getElementById('assignTeacherModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

// Fungsi untuk menutup modal
function closeAssignTeacherModal() {
    document.getElementById('assignTeacherModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    resetAssignTeacherForm();
}

// Reset form
function resetAssignTeacherForm() {
    document.getElementById('assignTeacherForm').reset();
    document.getElementById('assignMapelId').innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
    document.getElementById('assignTeacherId').innerHTML = '<option value="">Pilih Guru</option>';
    document.getElementById('assignmentStatus').style.display = 'none';
    clearFormErrors();
}

// Clear form errors
function clearFormErrors() {
    document.querySelectorAll('.form-error').forEach(error => {
        error.style.display = 'none';
        error.textContent = '';
    });
}

// Multi-step assignment management
let currentStep = 1;
const totalSteps = 2;

function nextStep() {
    if (currentStep < totalSteps) {
        // Validate current step
        if (currentStep === 1) {
            const teacherId = document.getElementById('assignTeacherId').value;
            if (!teacherId) {
                alert('Pilih guru terlebih dahulu!');
                return;
            }
        }
        
        // Hide current step
        document.getElementById(`step${currentStep}`).classList.remove('active');
        
        // Show next step
        currentStep++;
        document.getElementById(`step${currentStep}`).classList.add('active');
        
        // Update navigation buttons
        updateNavigationButtons();
        
        // Load data for next step
        if (currentStep === 2) {
            loadSubjectsForSelectedClasses();
        }
    }
}

function prevStep() {
    if (currentStep > 1) {
        // Hide current step
        document.getElementById(`step${currentStep}`).classList.remove('active');
        
        // Show previous step
        currentStep--;
        document.getElementById(`step${currentStep}`).classList.add('active');
        
        // Update navigation buttons
        updateNavigationButtons();
    }
}

function updateNavigationButtons() {
    const prevBtn = document.getElementById('prevStep');
    const nextBtn = document.getElementById('nextStep');
    const submitBtn = document.getElementById('submitBtn');
    
    // Show/hide previous button
    prevBtn.style.display = currentStep > 1 ? 'block' : 'none';
    
    // Show/hide next/submit button
    if (currentStep === totalSteps) {
        nextBtn.style.display = 'none';
        submitBtn.style.display = 'block';
    } else {
        nextBtn.style.display = 'block';
        submitBtn.style.display = 'none';
    }
}

// Load teacher's current assignments
function loadTeacherAssignments() {
    const teacherId = document.getElementById('assignTeacherId').value;
    const currentAssignments = document.getElementById('currentAssignments');
    const assignmentsList = document.getElementById('assignmentsList');
    
    if (!teacherId) {
        currentAssignments.style.display = 'none';
        return;
    }
    
    // Show loading
    assignmentsList.innerHTML = '<div class="loading">Memuat penugasan...</div>';
    currentAssignments.style.display = 'block';
    
    // Fetch teacher assignments
    fetch(`/api/teachers/${teacherId}/assignments`)
        .then(response => response.json())
        .then(data => {
            if (data.assignments && data.assignments.length > 0) {
                assignmentsList.innerHTML = data.assignments.map(assignment => 
                    `<div class="preview-item">
                        <i class="fas fa-chalkboard"></i>
                        <span>${assignment.kelas_name} - ${assignment.mapel_name}</span>
                    </div>`
                ).join('');
            } else {
                assignmentsList.innerHTML = '<div class="no-assignments">Belum ada penugasan</div>';
            }
        })
        .catch(error => {
            console.error('Error loading assignments:', error);
            assignmentsList.innerHTML = '<div class="error">Gagal memuat penugasan</div>';
        });
}

// Load subjects for selected classes
function loadSubjectsForSelectedClasses() {
    const selectedClasses = Array.from(document.querySelectorAll('input[name="selected_classes[]"]:checked'))
        .map(cb => cb.value);
    
    if (selectedClasses.length === 0) {
        document.getElementById('assignmentPreview').style.display = 'none';
        return;
    }
    
    // Update preview
    updateAssignmentPreview();
}

// Update assignment preview
function updateAssignmentPreview() {
    const selectedClasses = Array.from(document.querySelectorAll('input[name="selected_classes[]"]:checked'));
    const selectedSubjects = Array.from(document.querySelectorAll('input[name="selected_subjects[]"]:checked'));
    const preview = document.getElementById('assignmentPreview');
    const previewList = document.getElementById('previewList');
    
    if (selectedClasses.length === 0 || selectedSubjects.length === 0) {
        preview.style.display = 'none';
        return;
    }
    
    // Generate preview combinations
    const combinations = [];
    selectedClasses.forEach(classCheckbox => {
        selectedSubjects.forEach(subjectCheckbox => {
            combinations.push({
                class: classCheckbox.nextElementSibling.textContent,
                subject: subjectCheckbox.nextElementSibling.textContent
            });
        });
    });
    
    // Display preview
    previewList.innerHTML = combinations.map(combo => 
        `<div class="preview-item">
            <i class="fas fa-chalkboard"></i>
            <span>${combo.class} - ${combo.subject}</span>
        </div>`
    ).join('');
    
    preview.style.display = 'block';
    
    // Update hidden field for bulk assignments
    const bulkAssignments = combinations.map(combo => ({
        class_id: selectedClasses.find(cb => cb.nextElementSibling.textContent === combo.class).value,
        subject_id: selectedSubjects.find(sb => sb.nextElementSibling.textContent === combo.subject).value
    }));
    
    document.getElementById('bulkAssignments').value = JSON.stringify(bulkAssignments);
}

// Add event listeners for checkbox changes
document.addEventListener('DOMContentLoaded', function() {
    // Listen for class checkbox changes
    document.querySelectorAll('input[name="selected_classes[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', updateAssignmentPreview);
    });
    
    // Listen for subject checkbox changes
    document.querySelectorAll('input[name="selected_subjects[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', updateAssignmentPreview);
    });
});

// Load teachers for selected class-subject
function loadTeachersForClassSubject() {
    const kelasId = document.getElementById('assignKelasId').value;
    const mapelId = document.getElementById('assignMapelId').value;
    const teacherSelect = document.getElementById('assignTeacherId');
    const statusDiv = document.getElementById('assignmentStatus');
    
    // Reset teacher select
    teacherSelect.innerHTML = '<option value="">Pilih Guru</option>';
    statusDiv.style.display = 'none';
    
    if (!kelasId || !mapelId) return;
    
    // Show loading
    teacherSelect.innerHTML = '<option value="">Memuat guru...</option>';
    
    fetch(`/api/classes/${kelasId}/subjects/${mapelId}/teachers`)
        .then(response => response.json())
        .then(data => {
            teacherSelect.innerHTML = '<option value="">Pilih Guru</option>';
            
            if (data.available_teachers.length > 0) {
                data.available_teachers.forEach(teacher => {
                    const option = document.createElement('option');
                    option.value = teacher.id;
                    option.textContent = `${teacher.name} (${teacher.email})`;
                    teacherSelect.appendChild(option);
                });
            } else {
                teacherSelect.innerHTML = '<option value="">Tidak ada guru tersedia</option>';
            }
            
            // Show assignment status
            if (data.current_teacher) {
                statusDiv.className = 'assignment-status warning';
                statusDiv.querySelector('#statusMessage').textContent = 
                    `Guru saat ini: ${data.current_teacher.name}. Pilih guru lain untuk mengganti.`;
                statusDiv.style.display = 'block';
            } else {
                statusDiv.className = 'assignment-status success';
                statusDiv.querySelector('#statusMessage').textContent = 
                    'Belum ada guru yang ditugaskan untuk kelas-mata pelajaran ini.';
                statusDiv.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error loading teachers:', error);
            teacherSelect.innerHTML = '<option value="">Error loading teachers</option>';
        });
}

// Form submission
document.getElementById('assignTeacherForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = document.getElementById('assignTeacherBtn');
    
    // Show loading
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menugaskan...';
    submitBtn.disabled = true;
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showNotification('Guru berhasil ditugaskan!', 'success');
            closeAssignTeacherModal();
            
            // Refresh the page or update the UI
            if (typeof refreshAssignmentsTable === 'function') {
                refreshAssignmentsTable();
            } else {
                location.reload();
            }
        } else {
            // Show error message
            showNotification(data.message || 'Gagal menugaskan guru', 'error');
            
            // Show field errors
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    const errorDiv = document.getElementById(field + '-error');
                    if (errorDiv) {
                        errorDiv.textContent = data.errors[field][0];
                        errorDiv.style.display = 'block';
                    }
                });
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat menugaskan guru', 'error');
    })
    .finally(() => {
        submitBtn.innerHTML = '<i class="fas fa-check"></i> Tugaskan Guru';
        submitBtn.disabled = false;
    });
});

// Close modal when clicking outside
document.getElementById('assignTeacherModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAssignTeacherModal();
    }
});

// Notification function
function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification-toast ${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
    `;
    
    // Add to body
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => notification.classList.add('show'), 100);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>
