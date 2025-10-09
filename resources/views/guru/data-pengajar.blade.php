@extends('layouts.unified-layout')

@section('title', 'Data Pengajar')

@section('content')
<!-- Page Header -->
@include('components.page-header', [
    'title' => 'Data Pengajar',
    'description' => 'Kelola dan pantau data pengajar dalam sistem',
    'icon' => 'fas fa-chalkboard-teacher',
    'breadcrumbs' => [
        ['text' => 'Dashboard', 'url' => route('dashboard')],
        ['text' => 'Data Pengajar']
    ]
])

<!-- Search and Filter -->
@include('components.search-filter', [
    'placeholder' => 'Cari pengajar...',
    'filters' => [
        [
            'name' => 'status',
            'label' => 'Status',
            'placeholder' => 'Semua Status',
            'options' => [
                'active' => 'Aktif',
                'inactive' => 'Tidak Aktif',
                'pending' => 'Menunggu'
            ]
        ],
        [
            'name' => 'subject',
            'label' => 'Mata Pelajaran',
            'placeholder' => 'Semua Mata Pelajaran',
            'options' => [
                'matematika' => 'Matematika',
                'fisika' => 'Fisika',
                'kimia' => 'Kimia',
                'biologi' => 'Biologi'
            ]
        ]
    ],
    'actions' => [
        [
            'text' => 'Export Excel',
            'icon' => 'fas fa-file-excel',
            'class' => 'btn-outline'
        ],
        [
            'text' => 'Import Data',
            'icon' => 'fas fa-upload',
            'class' => 'btn-secondary'
        ]
    ]
])

<!-- Statistics -->
@include('components.stats-grid', [
    'stats' => [
        [
            'icon' => 'fas fa-chalkboard-teacher',
            'value' => $totalPengajar ?? 0,
            'label' => 'Total Pengajar'
        ],
        [
            'icon' => 'fas fa-check-circle',
            'value' => $activePengajar ?? 0,
            'label' => 'Pengajar Aktif'
        ],
        [
            'icon' => 'fas fa-clock',
            'value' => $pendingPengajar ?? 0,
            'label' => 'Menunggu Persetujuan'
        ],
        [
            'icon' => 'fas fa-graduation-cap',
            'value' => $totalSubjects ?? 0,
            'label' => 'Total Mata Pelajaran'
        ]
    ]
])

<!-- Data Table -->
@include('components.data-table', [
    'title' => 'Daftar Pengajar',
    'columns' => [
        [
            'label' => 'Nama',
            'class' => 'font-medium',
            'render' => function($pengajar) {
                return '
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                            ' . substr($pengajar['name'] ?? 'P', 0, 2) . '
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">' . ($pengajar['name'] ?? 'N/A') . '</div>
                            <div class="text-sm text-gray-500">' . ($pengajar['email'] ?? 'N/A') . '</div>
                        </div>
                    </div>
                ';
            }
        ],
        [
            'label' => 'Mata Pelajaran',
            'class' => 'text-center',
            'render' => function($pengajar) {
                $subjects = $pengajar['subjects'] ?? [];
                if (empty($subjects)) {
                    return '<span class="text-gray-400">-</span>';
                }
                return '<div class="flex flex-wrap gap-1 justify-center">' . 
                    implode('', array_map(function($subject) {
                        return '<span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">' . $subject . '</span>';
                    }, $subjects)) . 
                '</div>';
            }
        ],
        [
            'label' => 'Kelas',
            'class' => 'text-center',
            'render' => function($pengajar) {
                $classes = $pengajar['classes'] ?? [];
                if (empty($classes)) {
                    return '<span class="text-gray-400">-</span>';
                }
                return '<div class="flex flex-wrap gap-1 justify-center">' . 
                    implode('', array_map(function($class) {
                        return '<span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">' . $class . '</span>';
                    }, $classes)) . 
                '</div>';
            }
        ],
        [
            'label' => 'Status',
            'class' => 'text-center',
            'render' => function($pengajar) {
                $status = $pengajar['status'] ?? 'inactive';
                $statusClass = $status === 'active' ? 'status-active' : 
                              ($status === 'pending' ? 'status-pending' : 'status-inactive');
                $statusText = $status === 'active' ? 'Aktif' : 
                             ($status === 'pending' ? 'Menunggu' : 'Tidak Aktif');
                return '<span class="status-badge ' . $statusClass . '">' . $statusText . '</span>';
            }
        ],
        [
            'label' => 'Terakhir Login',
            'class' => 'text-center',
            'render' => function($pengajar) {
                $lastLogin = $pengajar['last_login'] ?? null;
                if (!$lastLogin) {
                    return '<span class="text-gray-400">Belum pernah</span>';
                }
                return '<span class="text-sm text-gray-600">' . date('d M Y, H:i', strtotime($lastLogin)) . '</span>';
            }
        ],
        [
            'label' => 'Aksi',
            'class' => 'text-center',
            'render' => function($pengajar) {
                return '
                    <div class="action-buttons">
                        <button class="action-btn btn-view" onclick="viewPengajar(' . $pengajar['id'] . ')" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="action-btn btn-edit" onclick="editPengajar(' . $pengajar['id'] . ')" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn btn-delete" onclick="deletePengajar(' . $pengajar['id'] . ')" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                ';
            }
        ]
    ],
    'data' => $pengajar ?? [],
    'actions' => [
        [
            'text' => 'Tambah Pengajar',
            'icon' => 'fas fa-plus',
            'class' => 'btn-primary',
            'onclick' => 'openCreatePengajarModal()'
        ],
        [
            'text' => 'Refresh',
            'icon' => 'fas fa-sync',
            'class' => 'btn-secondary',
            'onclick' => 'refreshData()'
        ]
    ]
])

<!-- Detail Modal -->
@include('components.modal', [
    'id' => 'pengajarDetailModal',
    'title' => 'Detail Pengajar',
    'icon' => 'fas fa-user',
    'actions' => [
        [
            'text' => 'Tutup',
            'class' => 'btn-secondary',
            'onclick' => 'closeModal()'
        ]
    ]
])
    <div id="pengajarDetailContent">
        <!-- Detail content will be loaded here -->
    </div>
@endcomponent

@push('styles')
<style>
.flex {
    display: flex;
}

.items-center {
    align-items: center;
}

.gap-3 {
    gap: 0.75rem;
}

.w-10 {
    width: 2.5rem;
}

.h-10 {
    height: 2.5rem;
}

.bg-gradient-to-r {
    background-image: linear-gradient(to right, var(--tw-gradient-stops));
}

.from-blue-500 {
    --tw-gradient-from: #3b82f6;
    --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(59, 130, 246, 0));
}

.to-purple-600 {
    --tw-gradient-to: #9333ea;
}

.rounded-full {
    border-radius: 9999px;
}

.text-white {
    color: white;
}

.font-semibold {
    font-weight: 600;
}

.font-medium {
    font-weight: 500;
}

.text-gray-900 {
    color: #111827;
}

.text-gray-500 {
    color: #6b7280;
}

.text-sm {
    font-size: 0.875rem;
}

.text-xs {
    font-size: 0.75rem;
}

.px-2 {
    padding-left: 0.5rem;
    padding-right: 0.5rem;
}

.py-1 {
    padding-top: 0.25rem;
    padding-bottom: 0.25rem;
}

.bg-blue-100 {
    background-color: #dbeafe;
}

.text-blue-800 {
    color: #1e40af;
}

.bg-green-100 {
    background-color: #dcfce7;
}

.text-green-800 {
    color: #166534;
}

.rounded-full {
    border-radius: 9999px;
}

.gap-1 {
    gap: 0.25rem;
}

.justify-center {
    justify-content: center;
}

.flex-wrap {
    flex-wrap: wrap;
}

.text-gray-400 {
    color: #9ca3af;
}

.text-gray-600 {
    color: #4b5563;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-active {
    background-color: #d1fae5;
    color: #065f46;
}

.status-inactive {
    background-color: #fee2e2;
    color: #991b1b;
}

.status-pending {
    background-color: #fef3c7;
    color: #92400e;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    justify-content: center;
}

.action-btn {
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
}

.action-btn:hover {
    transform: translateY(-1px);
}

.btn-view {
    background-color: #10b981;
    color: white;
}

.btn-view:hover {
    background-color: #059669;
}

.btn-edit {
    background-color: #3b82f6;
    color: white;
}

.btn-edit:hover {
    background-color: #2563eb;
}

.btn-delete {
    background-color: #ef4444;
    color: white;
}

.btn-delete:hover {
    background-color: #dc2626;
}
</style>
@endpush

@push('scripts')
<script>
function viewPengajar(id) {
    // Implementation for viewing pengajar details
    console.log('Viewing pengajar:', id);
    openModal('pengajarDetailModal');
}

function editPengajar(id) {
    // Implementation for editing pengajar
    console.log('Editing pengajar:', id);
}

function deletePengajar(id) {
    // Implementation for deleting pengajar
    if (confirm('Apakah Anda yakin ingin menghapus pengajar ini?')) {
        console.log('Deleting pengajar:', id);
    }
}

function openCreatePengajarModal() {
    // Implementation for opening create pengajar modal
    console.log('Opening create pengajar modal');
}

function refreshData() {
    // Implementation for refreshing data
    window.location.reload();
}
</script>
@endpush
@endsection
