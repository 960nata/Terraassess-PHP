@extends('layouts.unified-layout')

@section('title', 'Manajemen Kelompok Kelas')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">Manajemen Kelompok Kelas</h1>
                <p class="mt-2 text-gray-300">Kelola kelompok siswa untuk berbagai tugas kolaboratif</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('teacher.tasks') }}" class="btn btn-outline">
                    <i class="ph-arrow-left mr-2"></i>
                    Kembali ke Tugas
                </a>
            </div>
        </div>
    </div>

    <!-- Class Selection -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="card-title">Pilih Kelas</h3>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($kelas as $k)
                    <div class="group-card" data-kelas-id="{{ $k->id }}">
                        <div class="group-card-header">
                            <h4 class="group-card-title">{{ $k->name }}</h4>
                            <span class="group-card-level">{{ $k->level }}</span>
                        </div>
                        <div class="group-card-body">
                            <div class="group-stats">
                                <div class="stat">
                                    <span class="stat-label">Kelompok</span>
                                    <span class="stat-value">{{ $k->tugasKelompoks->count() }}</span>
                                </div>
                                <div class="stat">
                                    <span class="stat-label">Siswa</span>
                                    <span class="stat-value">{{ $k->users->where('roles_id', 3)->count() }}</span>
                                </div>
                            </div>
                            <div class="group-actions">
                                <a href="{{ route('teacher.groups.create', $k->id) }}" class="btn btn-primary btn-sm">
                                    <i class="ph-plus mr-1"></i>
                                    Buat Kelompok
                                </a>
                                <button onclick="viewGroups({{ $k->id }})" class="btn btn-outline btn-sm">
                                    <i class="ph-eye mr-1"></i>
                                    Lihat
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Groups List -->
    <div id="groupsList" class="hidden">
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="card-title" id="classTitle">Kelompok Kelas</h3>
                    <button onclick="hideGroups()" class="btn btn-outline btn-sm">
                        <i class="ph-x mr-1"></i>
                        Tutup
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div id="groupsContainer">
                    <!-- Groups will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.group-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.group-card:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(59, 130, 246, 0.5);
    transform: translateY(-2px);
}

.group-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.group-card-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: white;
    margin: 0;
}

.group-card-level {
    background: rgba(59, 130, 246, 0.2);
    color: #60a5fa;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
}

.group-stats {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.stat {
    text-align: center;
}

.stat-label {
    display: block;
    font-size: 0.75rem;
    color: #9ca3af;
    margin-bottom: 0.25rem;
}

.stat-value {
    display: block;
    font-size: 1.25rem;
    font-weight: 600;
    color: white;
}

.group-actions {
    display: flex;
    gap: 0.5rem;
}

.group-item {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
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

.group-item-actions {
    display: flex;
    gap: 0.5rem;
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
</style>

<script>
function viewGroups(kelasId) {
    const classCard = document.querySelector(`[data-kelas-id="${kelasId}"]`);
    const className = classCard.querySelector('.group-card-title').textContent;
    
    document.getElementById('classTitle').textContent = `Kelompok ${className}`;
    document.getElementById('groupsList').classList.remove('hidden');
    
    // Load groups for this class
    loadGroups(kelasId);
}

function hideGroups() {
    document.getElementById('groupsList').classList.add('hidden');
}

function loadGroups(kelasId) {
    fetch(`/teacher/groups/get-groups/${kelasId}`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('groupsContainer');
            
            if (data.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="ph-users"></i>
                        <h3>Belum Ada Kelompok</h3>
                        <p>Kelas ini belum memiliki kelompok. Buat kelompok pertama untuk memulai.</p>
                    </div>
                `;
                return;
            }
            
            container.innerHTML = data.map(group => `
                <div class="group-item">
                    <div class="group-item-header">
                        <h4 class="group-item-title">${group.name}</h4>
                        <div class="group-item-actions">
                            <a href="/teacher/groups/${group.id}/edit" class="btn btn-outline btn-sm">
                                <i class="ph-pencil mr-1"></i>
                                Edit
                            </a>
                            <button onclick="deleteGroup(${group.id})" class="btn btn-danger btn-sm">
                                <i class="ph-trash mr-1"></i>
                                Hapus
                            </button>
                        </div>
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
            document.getElementById('groupsContainer').innerHTML = `
                <div class="empty-state">
                    <i class="ph-warning"></i>
                    <h3>Error</h3>
                    <p>Gagal memuat data kelompok.</p>
                </div>
            `;
        });
}

function deleteGroup(groupId) {
    if (confirm('Apakah Anda yakin ingin menghapus kelompok ini?')) {
        fetch(`/teacher/groups/${groupId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menghapus kelompok: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus kelompok.');
        });
    }
}
</script>
@endsection
