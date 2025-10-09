@extends('layouts.unified-layout-new')

@section('title', 'Buat Tugas Kelompok')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">Buat Tugas Kelompok</h1>
                <p class="mt-2 text-gray-300">Buat tugas kolaboratif dengan sistem penilaian antar-rekan</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('teacher.tasks.management') }}" class="btn btn-outline">
                    <i class="ph-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <form id="groupForm" method="POST" action="{{ route('teacher.tasks.store') }}">
        @csrf
        <input type="hidden" name="tipe" value="4">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2">
                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Dasar</h3>
                        </div>
                        <div class="card-body space-y-4">
                            <div>
                                <label class="form-label">Judul Tugas *</label>
                                <input type="text" name="name" class="form-input" 
                                       value="{{ old('name') }}" required
                                       placeholder="Contoh: Proyek Penelitian Lingkungan">
                                @error('name')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Deskripsi/Instruksi *</label>
                                <textarea name="content" class="form-textarea" rows="6" 
                                          placeholder="Tuliskan instruksi yang jelas untuk kelompok..." required>{{ old('content') }}</textarea>
                                @error('content')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
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

                                <div>
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
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label">Tanggal Tenggat Tugas</label>
                                    <input type="datetime-local" name="due" class="form-input" 
                                           value="{{ old('due') }}">
                                    @error('due')
                                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="form-label">Tanggal Tenggat Penilaian Antar Kelompok</label>
                                    <input type="datetime-local" name="peer_assessment_due" class="form-input" 
                                           value="{{ old('peer_assessment_due') }}">
                                    @error('peer_assessment_due')
                                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Group Formation -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Pembentukan Kelompok</h3>
                        </div>
                        <div class="card-body space-y-6">
                            <div class="flex justify-between items-center">
                                <h4 class="text-lg font-medium text-white">Kelompok</h4>
                                <button type="button" onclick="addGroup()" class="btn btn-primary btn-sm">
                                    <i class="ph-plus mr-1"></i>
                                    Tambah Kelompok
                                </button>
                            </div>
                            
                            <div id="groupsContainer">
                                <!-- Groups will be added here dynamically -->
                            </div>
                        </div>
                    </div>

                    <!-- Peer Assessment Rubric -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Rubrik Penilaian Antar Kelompok</h3>
                        </div>
                        <div class="card-body space-y-6">
                            <div class="flex justify-between items-center">
                                <h4 class="text-lg font-medium text-white">Item Penilaian</h4>
                                <button type="button" onclick="addRubricItem()" class="btn btn-primary btn-sm">
                                    <i class="ph-plus mr-1"></i>
                                    Tambah Item
                                </button>
                            </div>
                            
                            <div id="rubricContainer">
                                <!-- Rubric items will be added here dynamically -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="card sticky top-6">
                    <div class="card-header">
                        <h3 class="card-title">Aksi</h3>
                    </div>
                    <div class="card-body space-y-4">
                        <button type="submit" class="btn btn-primary w-full">
                            <i class="ph-check mr-2"></i>
                            Buat Tugas
                        </button>
                        
                        <a href="{{ route('teacher.tasks.management') }}" class="btn btn-outline w-full">
                            <i class="ph-x mr-2"></i>
                            Batal
                        </a>

                        <div class="border-t border-gray-600 pt-4">
                            <h4 class="text-sm font-medium text-gray-300 mb-2">Tips:</h4>
                            <ul class="text-xs text-gray-400 space-y-1">
                                <li>• Bagi kelompok secara merata</li>
                                <li>• Tentukan ketua kelompok yang bertanggung jawab</li>
                                <li>• Buat rubrik penilaian yang objektif</li>
                                <li>• Berikan instruksi yang jelas</li>
                                <li>• Atur tenggat waktu yang realistis</li>
                            </ul>
                        </div>

                        <div class="border-t border-gray-600 pt-4">
                            <h4 class="text-sm font-medium text-gray-300 mb-2">Fitur:</h4>
                            <ul class="text-xs text-gray-400 space-y-1">
                                <li>• Kolaborasi kelompok</li>
                                <li>• Penilaian antar-rekan</li>
                                <li>• Upload file bersama</li>
                                <li>• Rubrik penilaian otomatis</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(255, 255, 255, 0.02);
}

.card-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: white;
    margin: 0;
}

.card-body {
    padding: 1.5rem;
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: white;
    margin-bottom: 0.5rem;
}

.form-input, .form-select, .form-textarea {
    width: 100%;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    color: white;
    font-size: 0.875rem;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.group-block, .rubric-item {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.students-list {
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 6px;
    padding: 0.5rem;
    background: rgba(255, 255, 255, 0.02);
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
    background: rgba(255, 255, 255, 0.05);
}

.student-item.selected {
    background: rgba(59, 130, 246, 0.2);
    border: 1px solid rgba(59, 130, 246, 0.3);
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
    background: rgba(59, 130, 246, 0.2);
    border: 1px solid rgba(59, 130, 246, 0.3);
    border-radius: 4px;
    font-size: 0.75rem;
    color: #3b82f6;
}

.leader-tag {
    background: rgba(245, 158, 11, 0.2);
    border-color: rgba(245, 158, 11, 0.3);
    color: #f59e0b;
}
</style>

<script>
let groupCount = 0;
let rubricCount = 0;
let students = [];

document.addEventListener('DOMContentLoaded', function() {
    // Add first group and rubric item
    addGroup();
    addRubricItem();
});

function loadStudents() {
    const kelasId = document.querySelector('select[name="kelas_id"]').value;
    if (!kelasId) return;
    
    // Load students for the selected class
    fetch(`/api/students/class/${kelasId}`)
        .then(response => response.json())
        .then(data => {
            students = data;
            updateStudentsList();
        })
        .catch(error => {
            console.error('Error loading students:', error);
        });
}

function updateStudentsList() {
    const studentsList = document.querySelectorAll('.students-list');
    studentsList.forEach(list => {
        list.innerHTML = students.map(student => `
            <div class="student-item" onclick="toggleStudent(this, ${student.id})">
                <input type="checkbox" class="hidden">
                <span class="text-white text-sm">${student.name}</span>
            </div>
        `).join('');
    });
}

function addGroup() {
    groupCount++;
    const container = document.getElementById('groupsContainer');
    
    const groupHTML = `
        <div class="group-block" id="group-${groupCount}">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-medium text-white">Kelompok ${groupCount}</h4>
                <button type="button" onclick="removeGroup(${groupCount})" class="text-red-400 hover:text-red-300">
                    <i class="ph-trash"></i>
                </button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="form-label">Nama Kelompok</label>
                    <input type="text" name="groups[${groupCount}][name]" class="form-input" 
                           placeholder="Kelompok ${groupCount}" required>
                </div>
                
                <div>
                    <label class="form-label">Pilih Anggota</label>
                    <div class="students-list" id="students-${groupCount}">
                        <!-- Students will be loaded here -->
                    </div>
                </div>
                
                <div>
                    <label class="form-label">Ketua Kelompok</label>
                    <select name="groups[${groupCount}][leader]" class="form-select" required>
                        <option value="">Pilih Ketua</option>
                    </select>
                </div>
                
                <div class="group-members" id="members-${groupCount}">
                    <!-- Selected members will be shown here -->
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', groupHTML);
    updateStudentsList();
}

function removeGroup(id) {
    if (groupCount <= 1) {
        alert('Minimal harus ada 1 kelompok');
        return;
    }
    
    document.getElementById(`group-${id}`).remove();
}

function toggleStudent(element, studentId) {
    const groupBlock = element.closest('.group-block');
    const groupId = groupBlock.id.split('-')[1];
    const membersContainer = document.getElementById(`members-${groupId}`);
    const leaderSelect = groupBlock.querySelector('select[name*="[leader]"]');
    
    if (element.classList.contains('selected')) {
        // Remove student
        element.classList.remove('selected');
        const memberTag = membersContainer.querySelector(`[data-student-id="${studentId}"]`);
        if (memberTag) {
            memberTag.remove();
        }
        
        // Remove from leader options
        const leaderOption = leaderSelect.querySelector(`option[value="${studentId}"]`);
        if (leaderOption) {
            leaderOption.remove();
        }
    } else {
        // Add student
        element.classList.add('selected');
        
        // Add member tag
        const student = students.find(s => s.id == studentId);
        const memberTag = document.createElement('div');
        memberTag.className = 'member-tag';
        memberTag.setAttribute('data-student-id', studentId);
        memberTag.innerHTML = `
            <span>${student.name}</span>
            <button type="button" onclick="removeMember(this, ${studentId}, ${groupId})" class="text-red-400 hover:text-red-300">
                <i class="ph-x"></i>
            </button>
        `;
        membersContainer.appendChild(memberTag);
        
        // Add to leader options
        const leaderOption = document.createElement('option');
        leaderOption.value = studentId;
        leaderOption.textContent = student.name;
        leaderSelect.appendChild(leaderOption);
    }
}

function removeMember(button, studentId, groupId) {
    const memberTag = button.parentElement;
    memberTag.remove();
    
    // Remove from selected state
    const groupBlock = document.getElementById(`group-${groupId}`);
    const studentItem = groupBlock.querySelector(`[onclick*="${studentId}"]`);
    if (studentItem) {
        studentItem.classList.remove('selected');
    }
    
    // Remove from leader options
    const leaderSelect = groupBlock.querySelector('select[name*="[leader]"]');
    const leaderOption = leaderSelect.querySelector(`option[value="${studentId}"]`);
    if (leaderOption) {
        leaderOption.remove();
    }
}

function addRubricItem() {
    rubricCount++;
    const container = document.getElementById('rubricContainer');
    
    const rubricHTML = `
        <div class="rubric-item" id="rubric-${rubricCount}">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-medium text-white">Item Penilaian ${rubricCount}</h4>
                <button type="button" onclick="removeRubricItem(${rubricCount})" class="text-red-400 hover:text-red-300">
                    <i class="ph-trash"></i>
                </button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="form-label">Item Penilaian</label>
                    <input type="text" name="rubric_items[${rubricCount}][item]" class="form-input" 
                           placeholder="Contoh: Apakah diskusi kelompok berjalan dengan baik?" required>
                </div>
                
                <div>
                    <label class="form-label">Tipe Jawaban</label>
                    <select name="rubric_items[${rubricCount}][type]" class="form-select" required>
                        <option value="yes_no">Ya/Tidak</option>
                        <option value="scale">Skala (Sangat Baik, Baik, Cukup, Kurang)</option>
                        <option value="text">Teks Bebas</option>
                    </select>
                </div>
                
                <div>
                    <label class="form-label">Poin Maksimal</label>
                    <input type="number" name="rubric_items[${rubricCount}][points]" class="form-input" 
                           value="10" min="0" required>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', rubricHTML);
}

function removeRubricItem(id) {
    if (rubricCount <= 1) {
        alert('Minimal harus ada 1 item penilaian');
        return;
    }
    
    document.getElementById(`rubric-${id}`).remove();
}
</script>
@endsection
