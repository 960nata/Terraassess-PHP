@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - {{ $title }}')

@section('styles')
<style>
.task-form {
            background-color: #1e293b;
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #334155;
        }

        .form-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #2a2a3e;
            border-radius: 12px;
            border: 1px solid #334155;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title i {
            color: #667eea;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #ffffff;
            font-size: 0.9rem;
        }
        
        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #1e293b;
            border: 2px solid #334155;
            border-radius: 8px;
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: #667eea;
            background: #2a2a3e;
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .group-block, .rubric-item {
            background: #1e293b;
            border: 2px solid #334155;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .group-header, .rubric-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .group-title, .rubric-title {
            font-weight: 600;
            color: #667eea;
            font-size: 1.1rem;
        }

        .remove-group, .remove-rubric {
            background: #e53e3e;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .remove-group:hover, .remove-rubric:hover {
            background: #c53030;
        }

        .students-list {
            max-height: 200px;
            overflow-y: auto;
            border: 2px solid #334155;
            border-radius: 8px;
            padding: 0.5rem;
            background: #1e293b;
        }

        .student-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .student-item:hover {
            background: #2a2a3e;
        }

        .student-item.selected {
            background: rgba(102, 126, 234, 0.2);
            border: 1px solid rgba(102, 126, 234, 0.3);
        }

        .group-members {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .member-tag {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            background: rgba(102, 126, 234, 0.2);
            border: 1px solid rgba(102, 126, 234, 0.3);
            border-radius: 4px;
            font-size: 0.75rem;
            color: #667eea;
        }

        .leader-tag {
            background: rgba(245, 158, 11, 0.2);
            border-color: rgba(245, 158, 11, 0.3);
            color: #f59e0b;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5a67d8;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #475569;
            color: #ffffff;
        }

        .btn-secondary:hover {
            background: #334155;
        }

        .btn-success {
            background: #48bb78;
            color: white;
        }

        .btn-success:hover {
            background: #38a169;
        }

        .btn-danger {
            background: #e53e3e;
            color: white;
        }

        .btn-danger:hover {
            background: #c53030;
        }

        .btn-outline {
            background: transparent;
            color: #667eea;
            border: 1px solid #667eea;
        }

        .btn-outline:hover {
            background: #667eea;
            color: white;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .loading {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .tips {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .tips h4 {
            color: #ffffff;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .tips h4 i {
            color: #667eea;
        }

        .tips ul {
            color: #94a3b8;
            padding-left: 1.5rem;
        }

        .tips li {
            margin-bottom: 0.25rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-title i {
            color: #667eea;
        }

        .page-description {
            color: #94a3b8;
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
</style>
@endsection

@section('content')
<div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-users"></i>
                {{ $title }}
            </h1>
            <p class="page-description">Buat tugas kolaboratif dengan sistem penilaian antar-rekan</p>
        </div>

        <div class="task-form">
        <form id="groupForm" method="POST" action="{{ route('superadmin.tasks.store') }}">
            @csrf
            <input type="hidden" name="tipe" value="4">
            <!-- Hidden field untuk kelas_mapel_id yang akan diisi oleh JavaScript -->
            <input type="hidden" name="kelas_mapel_id" id="kelas_mapel_id" value="">

            <!-- Basic Information -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Informasi Dasar
                </h3>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Judul Tugas *</label>
                        <input type="text" name="name" class="form-input" 
                               value="{{ old('name') }}" required
                               placeholder="Contoh: Proyek Penelitian Lingkungan">
                        @error('name')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kelas Tujuan *</label>
                        <select name="kelas_id" class="form-select" required onchange="loadStudents()">
                            <option value="">Pilih Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->name }} - {{ $k->level }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mata Pelajaran *</label>
                        <select name="mapel_id" class="form-select" required>
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach($mapel as $m)
                                <option value="{{ $m->id }}" {{ old('mapel_id') == $m->id ? 'selected' : '' }}>
                                    {{ $m->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('mapel_id')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal Tenggat Tugas</label>
                        <input type="datetime-local" name="due" class="form-input" 
                               value="{{ old('due') }}">
                        @error('due')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Tanggal Tenggat Penilaian Antar Kelompok</label>
                        <input type="datetime-local" name="peer_assessment_due" class="form-input" 
                               value="{{ old('peer_assessment_due') }}">
                        @error('peer_assessment_due')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Deskripsi/Instruksi *</label>
                    <textarea name="content" class="form-textarea" rows="6" 
                              placeholder="Tuliskan instruksi yang jelas untuk kelompok..." required>{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Group Formation -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-users"></i>
                    Pembentukan Kelompok
                </h3>
                
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-medium text-white">Kelompok</h4>
                    <button type="button" onclick="addGroup()" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i>
                        Tambah Kelompok
                    </button>
                </div>
                
                <div id="groupsContainer">
                    <!-- Groups will be added here dynamically -->
                </div>
            </div>

            <!-- Peer Assessment Rubric -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-clipboard-check"></i>
                    Rubrik Penilaian Antar Kelompok
                </h3>
                
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-medium text-white">Item Penilaian</h4>
                    <button type="button" onclick="addRubricItem()" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i>
                        Tambah Item
                    </button>
                </div>
                
                <div id="rubricContainer">
                    <!-- Rubric items will be added here dynamically -->
                </div>
            </div>

            <!-- Tips -->
            <div class="tips">
                <h4><i class="fas fa-lightbulb"></i> Tips Membuat Tugas Kelompok</h4>
                <ul>
                    <li>Bagi kelompok secara merata</li>
                    <li>Tentukan ketua kelompok yang bertanggung jawab</li>
                    <li>Buat rubrik penilaian yang objektif</li>
                    <li>Berikan instruksi yang jelas</li>
                    <li>Atur tenggat waktu yang realistis</li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button type="button" class="btn btn-secondary" onclick="history.back()">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
                <button type="button" class="btn btn-danger" onclick="clearForm()">
                    <i class="fas fa-trash"></i> Hapus Semua
                </button>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan Tugas
                </button>
            </div>
        </form>
        </div>

<script>
let groupCount = 0;
let rubricCount = 0;

// Function to add new group
function addGroup() {
    groupCount++;
    const container = document.getElementById('groupsContainer');
    
    const groupDiv = document.createElement('div');
    groupDiv.className = 'group-item bg-gray-700 p-4 rounded-lg mb-4 border border-gray-600';
    groupDiv.innerHTML = `
        <div class="flex justify-between items-center mb-3">
            <h5 class="text-white font-medium">Kelompok ${groupCount}</h5>
            <button type="button" onclick="removeGroup(this)" class="text-red-400 hover:text-red-300">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-300 mb-2">Nama Kelompok</label>
            <input type="text" name="groups[${groupCount}][name]" class="w-full px-3 py-2 bg-gray-600 border border-gray-500 text-white rounded" placeholder="Nama kelompok" required>
        </div>
        
        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-300 mb-2">Ketua Kelompok</label>
            <select name="groups[${groupCount}][leader]" class="w-full px-3 py-2 bg-gray-600 border border-gray-500 text-white rounded" required>
                <option value="">Pilih Ketua</option>
                <!-- Students will be loaded dynamically -->
            </select>
        </div>
        
        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-300 mb-2">Anggota Kelompok</label>
            <select name="groups[${groupCount}][members][]" class="w-full px-3 py-2 bg-gray-600 border border-gray-500 text-white rounded" multiple size="5">
                <option value="">Pilih Anggota (Hold Ctrl/Cmd untuk pilih banyak)</option>
                <!-- Students will be loaded dynamically -->
            </select>
            <small class="text-gray-400">Tahan Ctrl (Windows) atau Cmd (Mac) untuk memilih multiple siswa</small>
        </div>
        
        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-300 mb-2">Maksimal Anggota</label>
            <input type="number" name="groups[${groupCount}][max_members]" class="w-full px-3 py-2 bg-gray-600 border border-gray-500 text-white rounded" value="4" min="2" max="10" required>
        </div>
    `;
    
    container.appendChild(groupDiv);
    
    // Update the new group's dropdowns with current students data
    if (studentsData.length > 0) {
        const newGroupDiv = container.lastElementChild;
        const memberSelect = newGroupDiv.querySelector('select[name*="[members]"]');
        const leaderSelect = newGroupDiv.querySelector('select[name*="[leader]"]');
        
        updateStudentDropdown(memberSelect, studentsData);
        updateStudentDropdown(leaderSelect, studentsData);
    }
}

// Function to remove group
function removeGroup(button) {
    button.closest('.group-item').remove();
}

// Function to add new rubric item
function addRubricItem() {
    rubricCount++;
    const container = document.getElementById('rubricContainer');
    
    const rubricDiv = document.createElement('div');
    rubricDiv.className = 'rubric-item bg-gray-700 p-4 rounded-lg mb-4 border border-gray-600';
    rubricDiv.innerHTML = `
        <div class="flex justify-between items-center mb-3">
            <h5 class="text-white font-medium">Item Penilaian ${rubricCount}</h5>
            <button type="button" onclick="removeRubricItem(this)" class="text-red-400 hover:text-red-300">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Kriteria Penilaian</label>
                <input type="text" name="rubric[${rubricCount}][criteria]" class="w-full px-3 py-2 bg-gray-600 border border-gray-500 text-white rounded focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Kerjasama tim" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Bobot (%)</label>
                <input type="number" name="rubric[${rubricCount}][weight]" class="w-full px-3 py-2 bg-gray-600 border border-gray-500 text-white rounded focus:ring-2 focus:ring-blue-500" value="25" min="1" max="100" required>
            </div>
        </div>
        <div class="mt-3">
            <label class="block text-sm font-medium text-gray-300 mb-2">Tipe Penilaian</label>
            <select name="rubric[${rubricCount}][type]" class="w-full px-3 py-2 bg-gray-600 border border-gray-500 text-white rounded focus:ring-2 focus:ring-blue-500" onchange="updateRubricOptions(this, ${rubricCount})" required>
                <option value="binary">Ya/Tidak (100/50)</option>
                <option value="scale">4 Skala (100/75/50/25)</option>
            </select>
        </div>
        <div class="mt-3">
            <label class="block text-sm font-medium text-gray-300 mb-2">Opsi Penilaian</label>
            <div id="rubric-options-${rubricCount}" class="space-y-2">
                <!-- Binary options -->
                <div class="grid grid-cols-2 gap-2">
                    <div class="flex items-center space-x-2 p-2 bg-green-600 rounded">
                        <input type="radio" name="rubric[${rubricCount}][sample_value]" value="100" class="text-green-600" checked>
                        <label class="text-white text-sm">Ya (100)</label>
                    </div>
                    <div class="flex items-center space-x-2 p-2 bg-red-600 rounded">
                        <input type="radio" name="rubric[${rubricCount}][sample_value]" value="50" class="text-red-600">
                        <label class="text-white text-sm">Tidak (50)</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <label class="block text-sm font-medium text-gray-300 mb-2">Deskripsi Penilaian</label>
            <textarea name="rubric[${rubricCount}][description]" class="w-full px-3 py-2 bg-gray-600 border border-gray-500 text-white rounded focus:ring-2 focus:ring-blue-500" rows="2" placeholder="Jelaskan bagaimana menilai kriteria ini" required></textarea>
        </div>
    `;
    
    container.appendChild(rubricDiv);
}

// Function to update rubric options based on type
function updateRubricOptions(selectElement, rubricId) {
    const type = selectElement.value;
    const optionsContainer = document.getElementById(`rubric-options-${rubricId}`);
    
    if (type === 'binary') {
        optionsContainer.innerHTML = `
            <div class="grid grid-cols-2 gap-2">
                <div class="flex items-center space-x-2 p-2 bg-green-600 rounded">
                    <input type="radio" name="rubric[${rubricId}][sample_value]" value="100" class="text-green-600" checked>
                    <label class="text-white text-sm">Ya (100)</label>
                </div>
                <div class="flex items-center space-x-2 p-2 bg-red-600 rounded">
                    <input type="radio" name="rubric[${rubricId}][sample_value]" value="50" class="text-red-600">
                    <label class="text-white text-sm">Tidak (50)</label>
                </div>
            </div>
        `;
    } else if (type === 'scale') {
        optionsContainer.innerHTML = `
            <div class="grid grid-cols-2 gap-2">
                <div class="flex items-center space-x-2 p-2 bg-green-600 rounded">
                    <input type="radio" name="rubric[${rubricId}][sample_value]" value="100" class="text-green-600" checked>
                    <label class="text-white text-sm">Sangat Baik (100)</label>
                </div>
                <div class="flex items-center space-x-2 p-2 bg-blue-600 rounded">
                    <input type="radio" name="rubric[${rubricId}][sample_value]" value="75" class="text-blue-600">
                    <label class="text-white text-sm">Baik (75)</label>
                </div>
                <div class="flex items-center space-x-2 p-2 bg-yellow-600 rounded">
                    <input type="radio" name="rubric[${rubricId}][sample_value]" value="50" class="text-yellow-600">
                    <label class="text-white text-sm">Cukup Baik (50)</label>
                </div>
                <div class="flex items-center space-x-2 p-2 bg-red-600 rounded">
                    <input type="radio" name="rubric[${rubricId}][sample_value]" value="25" class="text-red-600">
                    <label class="text-white text-sm">Kurang Baik (25)</label>
                </div>
            </div>
        `;
    }
}

// Function to remove rubric item
function removeRubricItem(button) {
    button.closest('.rubric-item').remove();
}

// Function to clear form
function clearForm() {
    if (confirm('Apakah Anda yakin ingin menghapus semua data?')) {
        document.getElementById('taskForm').reset();
        document.getElementById('groupsContainer').innerHTML = '';
        document.getElementById('rubricContainer').innerHTML = '';
        groupCount = 0;
        rubricCount = 0;
    }
}

// Global variable to store students data
let studentsData = [];

// Function to load students when class is selected
function loadStudents() {
    const kelasId = document.querySelector('select[name="kelas_id"]').value;
    
    if (!kelasId) {
        studentsData = [];
        updateAllStudentDropdowns([]);
        return;
    }
    
    // Show loading state
    const classSelect = document.querySelector('select[name="kelas_id"]');
    classSelect.disabled = true;
    
    // Fetch students from the selected class
    fetch(`/groups/get-students/${kelasId}`, {
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(students => {
            studentsData = students;
            updateAllStudentDropdowns(students);
        })
        .catch(error => {
            console.error('Error loading students:', error);
            alert('Gagal memuat data siswa. Silakan coba lagi.');
        })
        .finally(() => {
            classSelect.disabled = false;
        });
}

// Function to update all student dropdowns
function updateAllStudentDropdowns(students) {
    // Update all member dropdowns
    const memberSelects = document.querySelectorAll('select[name*="[members]"]');
    memberSelects.forEach(select => {
        updateStudentDropdown(select, students);
    });
    
    // Update all leader dropdowns
    const leaderSelects = document.querySelectorAll('select[name*="[leader]"]');
    leaderSelects.forEach(select => {
        updateStudentDropdown(select, students);
    });
}

// Function to update a single student dropdown
function updateStudentDropdown(selectElement, students) {
    const currentValue = selectElement.value;
    
    // Clear existing options except the first one
    selectElement.innerHTML = selectElement.querySelector('option:first-child').outerHTML;
    
    // Add student options
    students.forEach(student => {
        const option = document.createElement('option');
        option.value = student.id;
        option.textContent = student.name;
        selectElement.appendChild(option);
    });
    
    // Restore previous value if it still exists
    if (currentValue && students.some(s => s.id == currentValue)) {
        selectElement.value = currentValue;
    }
}

// Initialize with one group and one rubric item
document.addEventListener('DOMContentLoaded', function() {
    addGroup();
    addRubricItem();
    
    // Handle form submission - gabungkan kelas_id dan mapel_id menjadi kelas_mapel_id
    const form = document.getElementById('groupForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const kelasId = document.getElementById('kelas_id').value;
            const mapelId = document.getElementById('mapel_id').value;
            
            // Validasi bahwa kedua dropdown sudah dipilih
            if (!kelasId || !mapelId) {
                e.preventDefault();
                alert('Kelas Tujuan dan Mata Pelajaran wajib dipilih!');
                return false;
            }
            
            // Gabungkan kelas_id dan mapel_id menjadi kelas_mapel_id
            // Format: "kelas_id:mapel_id" untuk sementara, nanti controller akan handle
            const kelasMapelId = `${kelasId}:${mapelId}`;
            document.getElementById('kelas_mapel_id').value = kelasMapelId;
            
            console.log('Form submit (Superadmin Group) - Kelas ID:', kelasId, 'Mapel ID:', mapelId, 'Kombinasi:', kelasMapelId);
        });
    }

    // Restore groups dan rubric dari old input jika ada
    @if(old('groups'))
        document.addEventListener('DOMContentLoaded', function() {
            const oldGroups = @json(old('groups'));
            console.log('Restoring old groups:', oldGroups);
            
            oldGroups.forEach((group, index) => {
                addGroup();
                
                // Fill group data
                const groupIndex = index;
                
                // Fill group name
                const groupNameInput = document.querySelector(`input[name="groups[${groupIndex}][name]"]`);
                if (groupNameInput) {
                    groupNameInput.value = group.name || '';
                }
                
                // Fill group description
                const groupDescInput = document.querySelector(`input[name="groups[${groupIndex}][description]"]`);
                if (groupDescInput) {
                    groupDescInput.value = group.description || '';
                }
                
                // Fill members (multiple select)
                if (group.members && Array.isArray(group.members)) {
                    const memberSelect = document.querySelector(`select[name="groups[${groupIndex}][members][]"]`);
                    if (memberSelect) {
                        group.members.forEach(memberId => {
                            const option = memberSelect.querySelector(`option[value="${memberId}"]`);
                            if (option) {
                                option.selected = true;
                            }
                        });
                    }
                }
                
                // Fill leader
                if (group.leader) {
                    const leaderSelect = document.querySelector(`select[name="groups[${groupIndex}][leader]"]`);
                    if (leaderSelect) {
                        leaderSelect.value = group.leader;
                    }
                }
            });
        });
    @endif

    // Restore rubric items dari old input jika ada
    @if(old('rubric'))
        document.addEventListener('DOMContentLoaded', function() {
            const oldRubricItems = @json(old('rubric'));
            console.log('Restoring old rubric items:', oldRubricItems);
            
            oldRubricItems.forEach((item, index) => {
                addRubricItem();
                
                // Fill rubric item data
                const itemIndex = index;
                
                // Fill description
                const descInput = document.querySelector(`textarea[name="rubric[${itemIndex}][description]"]`);
                if (descInput) {
                    descInput.value = item.description || '';
                }
                
                // Fill points
                const pointsInput = document.querySelector(`input[name="rubric[${itemIndex}][points]"]`);
                if (pointsInput) {
                    pointsInput.value = item.points || 1;
                }
            });
        });
    @endif

    // Show notification that data was restored
    @if(session('error') && (old('groups') || old('rubric')))
        document.addEventListener('DOMContentLoaded', function() {
            showNotification('Data Anda tersimpan! Silakan perbaiki error dan submit kembali. Data yang sudah Anda isi tidak hilang.', 'warning');
        });
    @endif
});
</script>

<!-- Quill Editor CSS -->
<style>
/* Quill Editor Styles - From material-create.blade.php */
.ql-editor {
    color: #ffffff;
    background: #2a2a3e;
    min-height: 200px;
}

.ql-toolbar {
    background: #1e293b;
    border: 1px solid #334155;
    border-bottom: none;
}

.ql-container {
    border: 1px solid #334155;
    border-top: none;
}

.ql-snow .ql-picker {
    color: #ffffff;
}

.ql-snow .ql-stroke {
    stroke: #ffffff;
}

.ql-snow .ql-fill {
    fill: #ffffff;
}

.quill-editor-dark .ql-editor {
    color: #ffffff;
    background: #2a2a3e;
    min-height: 120px;
}

.quill-editor-dark .ql-toolbar {
    background: #1e293b;
    border: 1px solid #334155;
    border-bottom: none;
}

.quill-editor-dark .ql-container {
    border: 1px solid #334155;
    border-top: none;
}

.quill-editor-dark .ql-snow .ql-picker {
    color: #ffffff;
}

.quill-editor-dark .ql-snow .ql-stroke {
    stroke: #ffffff;
}

.quill-editor-dark .ql-snow .ql-fill {
    fill: #ffffff;
}
</style>
@endsection
