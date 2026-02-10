@extends('layouts.unified-layout')

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
                <a href="{{ route('teacher.groups.index') }}" class="btn btn-outline">
                    <i class="ph-users mr-2"></i>
                    Kelola Kelompok
                </a>
                <a href="{{ route('teacher.tasks') }}" class="btn btn-outline">
                    <i class="ph-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <form id="groupForm" method="POST" action="{{ route('teacher.tasks.store') }}">
        @csrf
        <input type="hidden" name="tipe" value="4">
        <!-- Hidden field untuk kelas_mapel_id yang akan diisi oleh JavaScript -->
        <input type="hidden" name="kelas_mapel_id" id="kelas_mapel_id" value="">

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
                                    <select name="kelas_id" class="form-select" required onchange="loadGroups(); loadStudents();">
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

                    <!-- Group Selection -->
                    <div class="card">
                        <div class="card-header">
                            <div class="flex items-center justify-between">
                                <h3 class="card-title">Pilih Kelompok</h3>
                                <div class="flex gap-2">
                                    <button type="button" onclick="selectAllGroups()" class="btn btn-outline btn-sm">
                                        <i class="ph-check-square mr-1"></i>
                                        Pilih Semua
                                    </button>
                                    <button type="button" onclick="clearAllGroups()" class="btn btn-outline btn-sm">
                                        <i class="ph-square mr-1"></i>
                                        Batal Semua
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body space-y-4">
                            <div class="alert alert-info">
                                <i class="ph-info mr-2"></i>
                                <div>
                                    <h4 class="font-medium">Cara Kerja:</h4>
                                    <p class="text-sm mt-1">
                                        Pilih kelas terlebih dahulu untuk melihat kelompok yang tersedia. 
                                        Kelompok yang dipilih akan mengerjakan tugas ini.
                                    </p>
                                </div>
                            </div>
                            
                            <div id="groupsContainer">
                                <div class="empty-state">
                                    <i class="ph-users"></i>
                                    <h3>Pilih Kelas Dulu</h3>
                                    <p>Pilih kelas untuk melihat kelompok yang tersedia</p>
                                </div>
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
                        
                        <a href="{{ route('teacher.tasks') }}" class="btn btn-outline w-full">
                            <i class="ph-x mr-2"></i>
                            Batal
                        </a>

                        <div class="border-t border-gray-600 pt-4">
                            <h4 class="text-sm font-medium text-gray-300 mb-2">Tips:</h4>
                            <ul class="text-xs text-gray-400 space-y-1">
                                <li>• Pastikan kelompok sudah dibuat sebelumnya</li>
                                <li>• Pilih kelompok yang relevan dengan tugas</li>
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

                        <div class="border-t border-gray-600 pt-4">
                            <h4 class="text-sm font-medium text-gray-300 mb-2">Statistik:</h4>
                            <div class="text-xs text-gray-400 space-y-1">
                                <div class="flex justify-between">
                                    <span>Kelompok Terpilih:</span>
                                    <span id="selectedGroupsCount">0</span>
                                </div>
                            </div>
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
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.card-header {
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.card-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: white;
    margin: 0;
}

.card-body {
    color: #e5e7eb;
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: #d1d5db;
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

.btn {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-outline {
    background: transparent;
    color: #9ca3af;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.btn-outline:hover {
    background: rgba(255, 255, 255, 0.05);
    color: white;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
}

.group-item {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.group-item:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(59, 130, 246, 0.3);
}

.group-item.selected {
    background: rgba(59, 130, 246, 0.1);
    border-color: rgba(59, 130, 246, 0.5);
}

.group-item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.group-item-title {
    font-size: 1rem;
    font-weight: 600;
    color: white;
    margin: 0;
}

.group-item-checkbox {
    width: 1rem;
    height: 1rem;
}

.group-members {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
}

.member-badge {
    background: rgba(34, 197, 94, 0.2);
    color: #4ade80;
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.leader-badge {
    background: rgba(245, 158, 11, 0.2);
    color: #fbbf24;
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.group-description {
    color: #9ca3af;
    font-size: 0.875rem;
    margin: 0;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: #9ca3af;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h3 {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: white;
}

.empty-state p {
    margin: 0;
}

.alert {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.alert-info {
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.3);
    color: #93c5fd;
}

.rubric-item {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.rubric-item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.rubric-item-title {
    font-size: 1rem;
    font-weight: 600;
    color: white;
    margin: 0;
}

.rubric-item-actions {
    display: flex;
    gap: 0.5rem;
}

.rubric-item-body {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 1rem;
    align-items: end;
}

.rubric-description {
    color: #9ca3af;
    font-size: 0.875rem;
    margin: 0;
}

.rubric-points {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.rubric-points input {
    width: 4rem;
    text-align: center;
}
</style>

<script>
let rubricItemCount = 0;

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

function loadGroups() {
    const kelasId = document.querySelector('select[name="kelas_id"]').value;
    const container = document.getElementById('groupsContainer');
    
    if (!kelasId) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="ph-users"></i>
                <h3>Pilih Kelas Dulu</h3>
                <p>Pilih kelas untuk melihat kelompok yang tersedia</p>
            </div>
        `;
        return;
    }
    
    // Show loading state
    container.innerHTML = `
        <div class="empty-state">
            <i class="ph-spinner ph-spin"></i>
            <h3>Memuat Kelompok...</h3>
            <p>Sedang mengambil data kelompok</p>
        </div>
    `;
    
    fetch(`/groups/get-groups/${kelasId}`, {
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="ph-users"></i>
                        <h3>Belum Ada Kelompok</h3>
                        <p>Kelas ini belum memiliki kelompok. <a href="/teacher/groups/create/${kelasId}" class="text-blue-400 hover:text-blue-300">Buat kelompok dulu</a></p>
            </div>
                `;
                return;
            }
            
            container.innerHTML = data.map(group => `
                <div class="group-item" onclick="toggleGroup(${group.id})">
                    <div class="group-item-header">
                        <h4 class="group-item-title">${group.name}</h4>
                        <input type="checkbox" name="selected_groups[]" value="${group.id}" 
                               class="group-item-checkbox" style="display: none;">
                </div>
                    <div class="group-members">
                        ${group.members.map(member => {
                            const isLeader = member === group.leader;
                            return isLeader 
                                ? `<span class="leader-badge"><i class="ph-crown"></i>${member}</span>`
                                : `<span class="member-badge">${member}</span>`;
                        }).join('')}
                    </div>
                    ${group.description ? `<p class="group-description">${group.description}</p>` : ''}
                </div>
            `).join('');
        })
        .catch(error => {
            console.error('Error loading groups:', error);
            container.innerHTML = `
                <div class="empty-state">
                    <i class="ph-warning"></i>
                    <h3>Error</h3>
                    <p>Gagal memuat data kelompok.</p>
                </div>
            `;
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
    
    // Keep the first option (placeholder)
    selectElement.innerHTML = selectElement.querySelector('option:first-child').outerHTML;
    
    // Add student options
    students.forEach(student => {
        const option = document.createElement('option');
        option.value = student.id;
        option.textContent = student.name;
        selectElement.appendChild(option);
    });
    
    // Restore previous selection if still valid
    if (currentValue && students.some(s => s.id == currentValue)) {
        selectElement.value = currentValue;
    }
}

function toggleGroup(groupId) {
    const groupItem = document.querySelector(`[onclick="toggleGroup(${groupId})"]`);
    const checkbox = groupItem.querySelector('input[type="checkbox"]');
    
    checkbox.checked = !checkbox.checked;
    
    if (checkbox.checked) {
        groupItem.classList.add('selected');
    } else {
        groupItem.classList.remove('selected');
    }
    
    updateSelectedCount();
}

function selectAllGroups() {
    const checkboxes = document.querySelectorAll('input[name="selected_groups[]"]');
    const groupItems = document.querySelectorAll('.group-item');
    
    checkboxes.forEach(checkbox => checkbox.checked = true);
    groupItems.forEach(item => item.classList.add('selected'));
    
    updateSelectedCount();
}

function clearAllGroups() {
    const checkboxes = document.querySelectorAll('input[name="selected_groups[]"]');
    const groupItems = document.querySelectorAll('.group-item');
    
    checkboxes.forEach(checkbox => checkbox.checked = false);
    groupItems.forEach(item => item.classList.remove('selected'));
    
    updateSelectedCount();
}

function updateSelectedCount() {
    const selectedCount = document.querySelectorAll('input[name="selected_groups[]"]:checked').length;
    document.getElementById('selectedGroupsCount').textContent = selectedCount;
}

function addRubricItem() {
    rubricItemCount++;
    const container = document.getElementById('rubricContainer');
    
    const rubricItem = document.createElement('div');
    rubricItem.className = 'rubric-item';
    rubricItem.innerHTML = `
        <div class="rubric-item-header">
            <h4 class="rubric-item-title">Item Penilaian ${rubricItemCount}</h4>
            <div class="rubric-item-actions">
                <button type="button" onclick="removeRubricItem(this)" class="btn btn-outline btn-sm">
                    <i class="ph-trash mr-1"></i>
                    Hapus
                </button>
            </div>
                </div>
        <div class="rubric-item-body">
                <div>
                <label class="form-label">Deskripsi Penilaian</label>
                <input type="text" name="rubric_items[${rubricItemCount}][description]" 
                       class="form-input" placeholder="Contoh: Kerjasama tim, Kreativitas, dll" required>
                </div>
            <div class="rubric-points">
                <label class="form-label">Poin</label>
                <input type="number" name="rubric_items[${rubricItemCount}][points]" 
                       class="form-input" min="1" max="100" value="10" required>
            </div>
        </div>
    `;
    
    container.appendChild(rubricItem);
}

function removeRubricItem(button) {
    button.closest('.rubric-item').remove();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Add initial rubric item
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
            
            console.log('Form submit (Group) - Kelas ID:', kelasId, 'Mapel ID:', mapelId, 'Kombinasi:', kelasMapelId);
        });
    }

    // Restore groups dan rubric dari old input jika ada
    @if(old('groups'))
        document.addEventListener('DOMContentLoaded', function() {
            const oldGroups = @json(old('groups'));
            console.log('Restoring old groups:', oldGroups);
            
            // Load students data if kelas_id is available
            const kelasId = document.querySelector('select[name="kelas_id"]').value;
            if (kelasId) {
                loadStudents();
            }
            
            oldGroups.forEach((group, index) => {
                // Note: Teacher version doesn't support creating new groups dynamically
                // Groups must be created first in the groups management page
                console.log('Restoring group data for existing group:', group);
                
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
    @if(old('rubric_items'))
        document.addEventListener('DOMContentLoaded', function() {
            const oldRubricItems = @json(old('rubric_items'));
            console.log('Restoring old rubric items:', oldRubricItems);
            
            oldRubricItems.forEach((item, index) => {
                addRubricItem();
                
                // Fill rubric item data
                const itemIndex = index;
                
                // Fill description
                const descInput = document.querySelector(`input[name="rubric_items[${itemIndex}][description]"]`);
                if (descInput) {
                    descInput.value = item.description || '';
                }
                
                // Fill points
                const pointsInput = document.querySelector(`input[name="rubric_items[${itemIndex}][points]"]`);
                if (pointsInput) {
                    pointsInput.value = item.points || 1;
                }
            });
        });
    @endif

    // Show notification that data was restored
    @if(session('error') && (old('groups') || old('rubric_items')))
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