@extends('layouts.unified-layout')

@section('title', 'Edit Kelompok')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">Edit Kelompok</h1>
                <p class="mt-2 text-gray-300">Edit kelompok {{ $group->name }} di kelas {{ $group->kelas->name }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('teacher.groups.index') }}" class="btn btn-outline">
                    <i class="ph-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('teacher.groups.update', $group->id) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2">
                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Kelompok</h3>
                        </div>
                        <div class="card-body space-y-4">
                            <div>
                                <label class="form-label">Nama Kelompok *</label>
                                <input type="text" name="name" class="form-input" 
                                       value="{{ old('name', $group->name) }}" required
                                       placeholder="Contoh: Kelompok 1, Tim Alpha, dll">
                                @error('name')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Deskripsi Kelompok</label>
                                <textarea name="description" class="form-textarea" rows="3" 
                                          placeholder="Deskripsi singkat tentang kelompok (opsional)">{{ old('description', $group->description) }}</textarea>
                                @error('description')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Current Members -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Anggota Kelompok Saat Ini</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($group->anggotaTugasKelompok as $member)
                                    <div class="member-item {{ $member->isKetua ? 'leader' : '' }}">
                                        <div class="member-info">
                                            <div class="member-name">
                                                {{ $member->user->name }}
                                                @if($member->isKetua)
                                                    <span class="leader-badge">
                                                        <i class="ph-crown"></i>
                                                        Ketua
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="member-email">{{ $member->user->email }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Student Selection -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Edit Anggota Kelompok</h3>
                        </div>
                        <div class="card-body space-y-4">
                            <div class="flex justify-between items-center">
                                <p class="text-sm text-gray-300">Pilih minimal 2 siswa untuk membentuk kelompok</p>
                                <div class="flex gap-2">
                                    <button type="button" onclick="selectAll()" class="btn btn-outline btn-sm">
                                        <i class="ph-check-square mr-1"></i>
                                        Pilih Semua
                                    </button>
                                    <button type="button" onclick="clearAll()" class="btn btn-outline btn-sm">
                                        <i class="ph-square mr-1"></i>
                                        Batal Semua
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($students as $student)
                                    <label class="student-item">
                                        <input type="checkbox" name="members[]" value="{{ $student->id }}" 
                                               class="student-checkbox" 
                                               {{ in_array($student->id, $currentMembers) ? 'checked' : '' }}
                                               onchange="updateLeaderOptions()">
                                        <div class="student-info">
                                            <div class="student-name">{{ $student->name }}</div>
                                            <div class="student-email">{{ $student->email }}</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            @error('members')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Leader Selection -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Pilih Ketua Kelompok</h3>
                        </div>
                        <div class="card-body">
                            <div>
                                <label class="form-label">Ketua Kelompok *</label>
                                <select name="leader" class="form-select" required>
                                    <option value="">Pilih ketua kelompok</option>
                                    <!-- Options will be populated by JavaScript -->
                                </select>
                                @error('leader')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <p class="text-sm text-gray-400 mt-2">
                                <i class="ph-info mr-1"></i>
                                Ketua kelompok akan bertanggung jawab untuk koordinasi dan pengumpulan tugas
                            </p>
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
                            Simpan Perubahan
                        </button>
                        
                        <a href="{{ route('teacher.groups.index') }}" class="btn btn-outline w-full">
                            <i class="ph-x mr-2"></i>
                            Batal
                        </a>

                        <div class="border-t border-gray-600 pt-4">
                            <h4 class="text-sm font-medium text-gray-300 mb-2">Tips:</h4>
                            <ul class="text-xs text-gray-400 space-y-1">
                                <li>• Pastikan ketua kelompok memiliki leadership</li>
                                <li>• Idealnya 3-5 siswa per kelompok</li>
                                <li>• Perubahan akan mempengaruhi tugas yang menggunakan kelompok ini</li>
                            </ul>
                        </div>

                        <div class="border-t border-gray-600 pt-4">
                            <h4 class="text-sm font-medium text-gray-300 mb-2">Statistik:</h4>
                            <div class="text-xs text-gray-400 space-y-1">
                                <div class="flex justify-between">
                                    <span>Total Siswa:</span>
                                    <span>{{ $students->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Terpilih:</span>
                                    <span id="selectedCount">{{ count($currentMembers) }}</span>
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
.student-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.student-item:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(59, 130, 246, 0.3);
}

.student-item input[type="checkbox"] {
    margin-right: 0.75rem;
    width: 1rem;
    height: 1rem;
}

.student-info {
    flex: 1;
}

.student-name {
    font-weight: 500;
    color: white;
    margin-bottom: 0.25rem;
}

.student-email {
    font-size: 0.75rem;
    color: #9ca3af;
}

.student-item:has(input:checked) {
    background: rgba(59, 130, 246, 0.1);
    border-color: rgba(59, 130, 246, 0.5);
}

.student-item:has(input:checked) .student-name {
    color: #60a5fa;
}

.member-item {
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
}

.member-item.leader {
    background: rgba(245, 158, 11, 0.1);
    border-color: rgba(245, 158, 11, 0.3);
}

.member-info {
    flex: 1;
}

.member-name {
    font-weight: 500;
    color: white;
    margin-bottom: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.member-email {
    font-size: 0.75rem;
    color: #9ca3af;
}

.leader-badge {
    background: rgba(245, 158, 11, 0.2);
    color: #fbbf24;
    padding: 0.125rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.625rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}
</style>

<script>
// Student data for JavaScript
const students = @json($students->map(function($s) {
    return ['id' => $s->id, 'name' => $s->name, 'email' => $s->email];
}));
const currentMembers = @json($currentMembers);
const currentLeader = {{ $currentLeader ?? 'null' }};

function selectAll() {
    const checkboxes = document.querySelectorAll('.student-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    updateLeaderOptions();
}

function clearAll() {
    const checkboxes = document.querySelectorAll('.student-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    updateLeaderOptions();
}

function updateLeaderOptions() {
    const checkboxes = document.querySelectorAll('.student-checkbox:checked');
    const leaderSelect = document.querySelector('select[name="leader"]');
    const selectedCount = document.getElementById('selectedCount');
    
    // Debug logging
    console.log('Students:', students);
    console.log('Selected checkboxes:', checkboxes.length);
    
    // Update selected count
    selectedCount.textContent = checkboxes.length;
    
    // Clear existing options
    leaderSelect.innerHTML = '<option value="">Pilih ketua kelompok</option>';
    
    // Add options for selected students
    checkboxes.forEach(checkbox => {
        const studentId = checkbox.value;
        const student = students.find(s => s.id == studentId);
        
        if (student) {
            const option = document.createElement('option');
            option.value = studentId;
            option.textContent = student.name;
            leaderSelect.appendChild(option);
        }
    });
    
    // Set current leader if still selected
    if (currentLeader && Array.from(checkboxes).some(cb => cb.value == currentLeader)) {
        leaderSelect.value = currentLeader;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateLeaderOptions();
});
</script>
@endsection
