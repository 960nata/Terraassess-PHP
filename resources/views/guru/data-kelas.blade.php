@extends('layouts.unified-layout')

@section('title', 'Data Kelas')

@section('content')
<!-- Page Header -->
@include('components.page-header', [
    'title' => 'Data Kelas',
    'description' => 'Kelola dan pantau data kelas dalam sistem',
    'icon' => 'fas fa-building',
    'breadcrumbs' => [
        ['text' => 'Dashboard', 'url' => route('dashboard')],
        ['text' => 'Data Kelas']
    ]
])

<!-- Search and Filter -->
@include('components.search-filter', [
    'placeholder' => 'Cari kelas...',
    'filters' => [
        [
            'name' => 'level',
            'label' => 'Tingkat',
            'placeholder' => 'Semua Tingkat',
            'options' => [
                'x' => 'Kelas X',
                'xi' => 'Kelas XI',
                'xii' => 'Kelas XII'
            ]
        ],
        [
            'name' => 'type',
            'label' => 'Jurusan',
            'placeholder' => 'Semua Jurusan',
            'options' => [
                'ipa' => 'IPA',
                'ips' => 'IPS',
                'bahasa' => 'Bahasa',
                'agama' => 'Agama'
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
            'icon' => 'fas fa-building',
            'value' => $totalKelas ?? 0,
            'label' => 'Total Kelas'
        ],
        [
            'icon' => 'fas fa-users',
            'value' => $totalSiswa ?? 0,
            'label' => 'Total Siswa'
        ],
        [
            'icon' => 'fas fa-chalkboard-teacher',
            'value' => $totalPengajar ?? 0,
            'label' => 'Total Pengajar'
        ],
        [
            'icon' => 'fas fa-book',
            'value' => $totalMapel ?? 0,
            'label' => 'Total Mata Pelajaran'
        ]
    ]
])

<!-- Data Table -->
@include('components.data-table', [
    'title' => 'Daftar Kelas',
    'columns' => [
        [
            'label' => 'Nama Kelas',
            'class' => 'font-medium',
            'render' => function($kelas) {
                return '
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                            ' . substr($kelas['name'] ?? 'K', 0, 2) . '
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 text-lg">' . ($kelas['name'] ?? 'N/A') . '</div>
                            <div class="text-sm text-gray-500">' . ($kelas['level'] ?? 'N/A') . ' - ' . ($kelas['type'] ?? 'N/A') . '</div>
                        </div>
                    </div>
                ';
            }
        ],
        [
            'label' => 'Tingkat',
            'class' => 'text-center',
            'render' => function($kelas) {
                $level = $kelas['level'] ?? 'N/A';
                $levelClass = $level === 'x' ? 'bg-green-100 text-green-800' : 
                             ($level === 'xi' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800');
                return '<span class="px-3 py-1 ' . $levelClass . ' text-sm rounded-full font-medium">Kelas ' . strtoupper($level) . '</span>';
            }
        ],
        [
            'label' => 'Jurusan',
            'class' => 'text-center',
            'render' => function($kelas) {
                $type = $kelas['type'] ?? 'N/A';
                $typeClass = $type === 'ipa' ? 'bg-blue-100 text-blue-800' : 
                            ($type === 'ips' ? 'bg-green-100 text-green-800' : 
                            ($type === 'bahasa' ? 'bg-yellow-100 text-yellow-800' : 'bg-purple-100 text-purple-800'));
                return '<span class="px-3 py-1 ' . $typeClass . ' text-sm rounded-full font-medium">' . strtoupper($type) . '</span>';
            }
        ],
        [
            'label' => 'Jumlah Siswa',
            'class' => 'text-center',
            'render' => function($kelas) {
                $studentCount = $kelas['student_count'] ?? 0;
                return '
                    <div class="flex items-center justify-center gap-2">
                        <i class="fas fa-users text-gray-400"></i>
                        <span class="font-semibold text-gray-900">' . $studentCount . '</span>
                        <span class="text-sm text-gray-500">siswa</span>
                    </div>
                ';
            }
        ],
        [
            'label' => 'Wali Kelas',
            'class' => 'text-center',
            'render' => function($kelas) {
                $waliKelas = $kelas['wali_kelas'] ?? null;
                if (!$waliKelas) {
                    return '<span class="text-gray-400">Belum ditentukan</span>';
                }
                return '
                    <div class="flex items-center justify-center gap-2">
                        <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                            ' . substr($waliKelas['name'] ?? 'W', 0, 2) . '
                        </div>
                        <span class="text-sm font-medium">' . $waliKelas['name'] . '</span>
                    </div>
                ';
            }
        ],
        [
            'label' => 'Status',
            'class' => 'text-center',
            'render' => function($kelas) {
                $status = $kelas['status'] ?? 'active';
                $statusClass = $status === 'active' ? 'status-active' : 'status-inactive';
                $statusText = $status === 'active' ? 'Aktif' : 'Tidak Aktif';
                return '<span class="status-badge ' . $statusClass . '">' . $statusText . '</span>';
            }
        ],
        [
            'label' => 'Aksi',
            'class' => 'text-center',
            'render' => function($kelas) {
                return '
                    <div class="action-buttons">
                        <button class="action-btn btn-view" onclick="viewKelas(' . $kelas['id'] . ')" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="action-btn btn-edit" onclick="editKelas(' . $kelas['id'] . ')" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn btn-chart" onclick="viewStatistics(' . $kelas['id'] . ')" title="Lihat Statistik">
                            <i class="fas fa-chart-bar"></i>
                        </button>
                    </div>
                ';
            }
        ]
    ],
    'data' => $kelas ?? [],
    'actions' => [
        [
            'text' => 'Tambah Kelas',
            'icon' => 'fas fa-plus',
            'class' => 'btn-primary',
            'onclick' => 'openCreateKelasModal()'
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
@include('components.galaxy-modal', [
    'id' => 'kelasDetailModal',
    'title' => 'Detail Kelas',
    'icon' => 'fas fa-building',
    'actions' => [
        [
            'text' => 'Tutup',
            'class' => 'btn-secondary',
            'onclick' => 'closeModal()'
        ]
    ]
])
    <div id="kelasDetailContent">
        <!-- Detail content will be loaded here -->
    </div>
@endcomponent

<!-- Statistics Modal -->
@include('components.galaxy-modal', [
    'id' => 'statisticsModal',
    'title' => 'Statistik Kelas',
    'icon' => 'fas fa-chart-bar',
    'actions' => [
        [
            'text' => 'Tutup',
            'class' => 'btn-secondary',
            'onclick' => 'closeModal()'
        ]
    ]
])
    <div id="statisticsContent">
        <!-- Statistics content will be loaded here -->
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

.gap-2 {
    gap: 0.5rem;
}

.w-12 {
    width: 3rem;
}

.h-12 {
    height: 3rem;
}

.w-8 {
    width: 2rem;
}

.h-8 {
    height: 2rem;
}

.bg-gradient-to-r {
    background-image: linear-gradient(to right, var(--tw-gradient-stops));
}

.from-purple-500 {
    --tw-gradient-from: #8b5cf6;
    --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(139, 92, 246, 0));
}

.to-pink-600 {
    --tw-gradient-to: #db2777;
}

.from-indigo-500 {
    --tw-gradient-from: #6366f1;
    --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(99, 102, 241, 0));
}

.to-purple-600 {
    --tw-gradient-to: #9333ea;
}

.rounded-lg {
    border-radius: 0.5rem;
}

.rounded-full {
    border-radius: 9999px;
}

.text-white {
    color: white;
}

.font-bold {
    font-weight: 700;
}

.font-semibold {
    font-weight: 600;
}

.font-medium {
    font-weight: 500;
}

.text-lg {
    font-size: 1.125rem;
}

.text-gray-900 {
    color: #111827;
}

.text-gray-500 {
    color: #6b7280;
}

.text-gray-400 {
    color: #9ca3af;
}

.text-sm {
    font-size: 0.875rem;
}

.text-xs {
    font-size: 0.75rem;
}

.px-3 {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
}

.py-1 {
    padding-top: 0.25rem;
    padding-bottom: 0.25rem;
}

.bg-green-100 {
    background-color: #dcfce7;
}

.text-green-800 {
    color: #166534;
}

.bg-blue-100 {
    background-color: #dbeafe;
}

.text-blue-800 {
    color: #1e40af;
}

.bg-purple-100 {
    background-color: #f3e8ff;
}

.text-purple-800 {
    color: #6b21a8;
}

.bg-yellow-100 {
    background-color: #fef3c7;
}

.text-yellow-800 {
    color: #92400e;
}

.justify-center {
    justify-content: center;
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
function viewKelas(id) {
    // Implementation for viewing kelas details
    console.log('Viewing kelas:', id);
    openModal('kelasDetailModal');
}

function editKelas(id) {
    // Implementation for editing kelas
    console.log('Editing kelas:', id);
}

function viewStatistics(id) {
    // Implementation for viewing class statistics
    console.log('Viewing statistics for kelas:', id);
    openModal('statisticsModal');
}

function openCreateKelasModal() {
    // Implementation for opening create kelas modal
    console.log('Opening create kelas modal');
}

function refreshData() {
    // Implementation for refreshing data
    window.location.reload();
}
</script>
@endpush
@endsection
