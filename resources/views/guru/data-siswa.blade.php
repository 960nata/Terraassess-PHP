@extends('layouts.unified-layout')

@section('title', 'Data Siswa')

@section('content')
<!-- Page Header -->
@include('components.page-header', [
    'title' => 'Data Siswa',
    'description' => 'Kelola dan pantau data siswa dalam sistem',
    'icon' => 'fas fa-users',
    'breadcrumbs' => [
        ['text' => 'Dashboard', 'url' => route('dashboard')],
        ['text' => 'Data Siswa']
    ]
])

<!-- Search and Filter -->
@include('components.search-filter', [
    'placeholder' => 'Cari siswa...',
    'filters' => [
        [
            'name' => 'class',
            'label' => 'Kelas',
            'placeholder' => 'Semua Kelas',
            'options' => [
                'x-ipa-1' => 'X IPA 1',
                'x-ipa-2' => 'X IPA 2',
                'xi-ipa-1' => 'XI IPA 1',
                'xi-ipa-2' => 'XI IPA 2',
                'xii-ipa-1' => 'XII IPA 1',
                'xii-ipa-2' => 'XII IPA 2'
            ]
        ],
        [
            'name' => 'status',
            'label' => 'Status',
            'placeholder' => 'Semua Status',
            'options' => [
                'active' => 'Aktif',
                'inactive' => 'Tidak Aktif',
                'graduated' => 'Lulus'
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
            'icon' => 'fas fa-users',
            'value' => $totalSiswa ?? 0,
            'label' => 'Total Siswa'
        ],
        [
            'icon' => 'fas fa-check-circle',
            'value' => $activeSiswa ?? 0,
            'label' => 'Siswa Aktif'
        ],
        [
            'icon' => 'fas fa-graduation-cap',
            'value' => $graduatedSiswa ?? 0,
            'label' => 'Siswa Lulus'
        ],
        [
            'icon' => 'fas fa-building',
            'value' => $totalKelas ?? 0,
            'label' => 'Total Kelas'
        ]
    ]
])

<!-- Data Table -->
@include('components.data-table', [
    'title' => 'Daftar Siswa',
    'columns' => [
        [
            'label' => 'Nama',
            'class' => 'font-medium',
            'render' => function($siswa) {
                return '
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                            ' . substr($siswa['name'] ?? 'S', 0, 2) . '
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">' . ($siswa['name'] ?? 'N/A') . '</div>
                            <div class="text-sm text-gray-500">' . ($siswa['email'] ?? 'N/A') . '</div>
                        </div>
                    </div>
                ';
            }
        ],
        [
            'label' => 'Kelas',
            'class' => 'text-center',
            'render' => function($siswa) {
                $kelas = $siswa['kelas'] ?? 'N/A';
                return '<span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full font-medium">' . $kelas . '</span>';
            }
        ],
        [
            'label' => 'NIS',
            'class' => 'text-center',
            'render' => function($siswa) {
                return '<span class="font-mono text-sm">' . ($siswa['nis'] ?? 'N/A') . '</span>';
            }
        ],
        [
            'label' => 'Status',
            'class' => 'text-center',
            'render' => function($siswa) {
                $status = $siswa['status'] ?? 'inactive';
                $statusClass = $status === 'active' ? 'status-active' : 
                              ($status === 'graduated' ? 'status-graduated' : 'status-inactive');
                $statusText = $status === 'active' ? 'Aktif' : 
                             ($status === 'graduated' ? 'Lulus' : 'Tidak Aktif');
                return '<span class="status-badge ' . $statusClass . '">' . $statusText . '</span>';
            }
        ],
        [
            'label' => 'Nilai Rata-rata',
            'class' => 'text-center',
            'render' => function($siswa) {
                $average = $siswa['average_score'] ?? 0;
                $scoreClass = $average >= 80 ? 'text-green-600' : 
                             ($average >= 60 ? 'text-yellow-600' : 'text-red-600');
                return '<span class="font-semibold ' . $scoreClass . '">' . number_format($average, 1) . '</span>';
            }
        ],
        [
            'label' => 'Terakhir Login',
            'class' => 'text-center',
            'render' => function($siswa) {
                $lastLogin = $siswa['last_login'] ?? null;
                if (!$lastLogin) {
                    return '<span class="text-gray-400">Belum pernah</span>';
                }
                return '<span class="text-sm text-gray-600">' . date('d M Y, H:i', strtotime($lastLogin)) . '</span>';
            }
        ],
        [
            'label' => 'Aksi',
            'class' => 'text-center',
            'render' => function($siswa) {
                return '
                    <div class="action-buttons">
                        <button class="action-btn btn-view" onclick="viewSiswa(' . $siswa['id'] . ')" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="action-btn btn-edit" onclick="editSiswa(' . $siswa['id'] . ')" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn btn-chart" onclick="viewProgress(' . $siswa['id'] . ')" title="Lihat Progress">
                            <i class="fas fa-chart-line"></i>
                        </button>
                    </div>
                ';
            }
        ]
    ],
    'data' => $siswa ?? [],
    'actions' => [
        [
            'text' => 'Tambah Siswa',
            'icon' => 'fas fa-plus',
            'class' => 'btn-primary',
            'onclick' => 'openCreateSiswaModal()'
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
    'id' => 'siswaDetailModal',
    'title' => 'Detail Siswa',
    'icon' => 'fas fa-user-graduate',
    'actions' => [
        [
            'text' => 'Tutup',
            'class' => 'btn-secondary',
            'onclick' => 'closeModal()'
        ]
    ]
])
    <div id="siswaDetailContent">
        <!-- Detail content will be loaded here -->
    </div>
@endcomponent

<!-- Progress Modal -->
@include('components.modal', [
    'id' => 'progressModal',
    'title' => 'Progress Siswa',
    'icon' => 'fas fa-chart-line',
    'actions' => [
        [
            'text' => 'Tutup',
            'class' => 'btn-secondary',
            'onclick' => 'closeModal()'
        ]
    ]
])
    <div id="progressContent">
        <!-- Progress content will be loaded here -->
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

.from-green-500 {
    --tw-gradient-from: #10b981;
    --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(16, 185, 129, 0));
}

.to-blue-600 {
    --tw-gradient-to: #2563eb;
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

.px-3 {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
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

.font-mono {
    font-family: ui-monospace, SFMono-Regular, "SF Mono", Consolas, "Liberation Mono", Menlo, monospace;
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

.status-graduated {
    background-color: #e0e7ff;
    color: #3730a3;
}

.text-green-600 {
    color: #059669;
}

.text-yellow-600 {
    color: #d97706;
}

.text-red-600 {
    color: #dc2626;
}

.text-gray-400 {
    color: #9ca3af;
}

.text-gray-600 {
    color: #4b5563;
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

.btn-chart {
    background-color: #8b5cf6;
    color: white;
}

.btn-chart:hover {
    background-color: #7c3aed;
}
</style>
@endpush

@push('scripts')
<script>
function viewSiswa(id) {
    // Implementation for viewing siswa details
    console.log('Viewing siswa:', id);
    openModal('siswaDetailModal');
}

function editSiswa(id) {
    // Implementation for editing siswa
    console.log('Editing siswa:', id);
}

function viewProgress(id) {
    // Implementation for viewing student progress
    console.log('Viewing progress for siswa:', id);
    openModal('progressModal');
}

function openCreateSiswaModal() {
    // Implementation for opening create siswa modal
    console.log('Opening create siswa modal');
}

function refreshData() {
    // Implementation for refreshing data
    window.location.reload();
}
</script>
@endpush
@endsection
