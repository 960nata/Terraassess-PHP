@extends('layouts.unified-layout')

@section('title', 'Mata Pelajaran')

@section('content')
<!-- Page Header -->
@include('components.page-header', [
    'title' => 'Mata Pelajaran',
    'description' => 'Kelola dan pantau data mata pelajaran dalam sistem',
    'icon' => 'fas fa-book',
    'breadcrumbs' => [
        ['text' => 'Dashboard', 'url' => route('dashboard')],
        ['text' => 'Mata Pelajaran']
    ]
])

<!-- Search and Filter -->
@include('components.search-filter', [
    'placeholder' => 'Cari mata pelajaran...',
    'filters' => [
        [
            'name' => 'category',
            'label' => 'Kategori',
            'placeholder' => 'Semua Kategori',
            'options' => [
                'umum' => 'Umum',
                'jurusan' => 'Jurusan',
                'pilihan' => 'Pilihan',
                'ekstrakurikuler' => 'Ekstrakurikuler'
            ]
        ],
        [
            'name' => 'level',
            'label' => 'Tingkat',
            'placeholder' => 'Semua Tingkat',
            'options' => [
                'x' => 'Kelas X',
                'xi' => 'Kelas XI',
                'xii' => 'Kelas XII',
                'all' => 'Semua Tingkat'
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
            'icon' => 'fas fa-book',
            'value' => $totalMapel ?? 0,
            'label' => 'Total Mata Pelajaran'
        ],
        [
            'icon' => 'fas fa-chalkboard-teacher',
            'value' => $totalPengajar ?? 0,
            'label' => 'Pengajar Aktif'
        ],
        [
            'icon' => 'fas fa-building',
            'value' => $totalKelas ?? 0,
            'label' => 'Kelas Terdaftar'
        ],
        [
            'icon' => 'fas fa-clock',
            'value' => $totalJadwal ?? 0,
            'label' => 'Total Jadwal'
        ]
    ]
])

<!-- Data Table -->
@include('components.data-table', [
    'title' => 'Daftar Mata Pelajaran',
    'columns' => [
        [
            'label' => 'Mata Pelajaran',
            'class' => 'font-medium',
            'render' => function($mapel) {
                return '
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                            ' . substr($mapel['name'] ?? 'M', 0, 2) . '
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 text-lg">' . ($mapel['name'] ?? 'N/A') . '</div>
                            <div class="text-sm text-gray-500">' . ($mapel['code'] ?? 'N/A') . ' - ' . ($mapel['category'] ?? 'N/A') . '</div>
                        </div>
                    </div>
                ';
            }
        ],
        [
            'label' => 'Kode',
            'class' => 'text-center',
            'render' => function($mapel) {
                return '<span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">' . ($mapel['code'] ?? 'N/A') . '</span>';
            }
        ],
        [
            'label' => 'Kategori',
            'class' => 'text-center',
            'render' => function($mapel) {
                $category = $mapel['category'] ?? 'N/A';
                $categoryClass = $category === 'umum' ? 'bg-blue-100 text-blue-800' : 
                                ($category === 'jurusan' ? 'bg-green-100 text-green-800' : 
                                ($category === 'pilihan' ? 'bg-yellow-100 text-yellow-800' : 'bg-purple-100 text-purple-800'));
                return '<span class="px-3 py-1 ' . $categoryClass . ' text-sm rounded-full font-medium">' . ucfirst($category) . '</span>';
            }
        ],
        [
            'label' => 'Tingkat',
            'class' => 'text-center',
            'render' => function($mapel) {
                $level = $mapel['level'] ?? 'all';
                $levelText = $level === 'all' ? 'Semua' : 'Kelas ' . strtoupper($level);
                $levelClass = $level === 'all' ? 'bg-gray-100 text-gray-800' : 
                             ($level === 'x' ? 'bg-green-100 text-green-800' : 
                             ($level === 'xi' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'));
                return '<span class="px-3 py-1 ' . $levelClass . ' text-sm rounded-full font-medium">' . $levelText . '</span>';
            }
        ],
        [
            'label' => 'Pengajar',
            'class' => 'text-center',
            'render' => function($mapel) {
                $pengajar = $mapel['pengajar'] ?? null;
                if (!$pengajar) {
                    return '<span class="text-gray-400">Belum ditentukan</span>';
                }
                return '
                    <div class="flex items-center justify-center gap-2">
                        <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                            ' . substr($pengajar['name'] ?? 'P', 0, 2) . '
                        </div>
                        <span class="text-sm font-medium">' . $pengajar['name'] . '</span>
                    </div>
                ';
            }
        ],
        [
            'label' => 'Jumlah Kelas',
            'class' => 'text-center',
            'render' => function($mapel) {
                $kelasCount = $mapel['kelas_count'] ?? 0;
                return '
                    <div class="flex items-center justify-center gap-2">
                        <i class="fas fa-building text-gray-400"></i>
                        <span class="font-semibold text-gray-900">' . $kelasCount . '</span>
                        <span class="text-sm text-gray-500">kelas</span>
                    </div>
                ';
            }
        ],
        [
            'label' => 'Status',
            'class' => 'text-center',
            'render' => function($mapel) {
                $status = $mapel['status'] ?? 'active';
                $statusClass = $status === 'active' ? 'status-active' : 'status-inactive';
                $statusText = $status === 'active' ? 'Aktif' : 'Tidak Aktif';
                return '<span class="status-badge ' . $statusClass . '">' . $statusText . '</span>';
            }
        ],
        [
            'label' => 'Aksi',
            'class' => 'text-center',
            'render' => function($mapel) {
                return '
                    <div class="action-buttons">
                        <button class="action-btn btn-view" onclick="viewMapel(' . $mapel['id'] . ')" title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="action-btn btn-edit" onclick="editMapel(' . $mapel['id'] . ')" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn btn-chart" onclick="viewSchedule(' . $mapel['id'] . ')" title="Lihat Jadwal">
                            <i class="fas fa-calendar"></i>
                        </button>
                    </div>
                ';
            }
        ]
    ],
    'data' => $mapel ?? [],
    'actions' => [
        [
            'text' => 'Tambah Mata Pelajaran',
            'icon' => 'fas fa-plus',
            'class' => 'btn-primary',
            'onclick' => 'openCreateMapelModal()'
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
    'id' => 'mapelDetailModal',
    'title' => 'Detail Mata Pelajaran',
    'icon' => 'fas fa-book',
    'actions' => [
        [
            'text' => 'Tutup',
            'class' => 'btn-secondary',
            'onclick' => 'closeModal()'
        ]
    ]
])
    <div id="mapelDetailContent">
        <!-- Detail content will be loaded here -->
    </div>
@endcomponent

<!-- Schedule Modal -->
@include('components.galaxy-modal', [
    'id' => 'scheduleModal',
    'title' => 'Jadwal Mata Pelajaran',
    'icon' => 'fas fa-calendar',
    'actions' => [
        [
            'text' => 'Tutup',
            'class' => 'btn-secondary',
            'onclick' => 'closeModal()'
        ]
    ]
])
    <div id="scheduleContent">
        <!-- Schedule content will be loaded here -->
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

.from-orange-500 {
    --tw-gradient-from: #f97316;
    --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(249, 115, 22, 0));
}

.to-red-600 {
    --tw-gradient-to: #dc2626;
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

.px-2 {
    padding-left: 0.5rem;
    padding-right: 0.5rem;
}

.py-1 {
    padding-top: 0.25rem;
    padding-bottom: 0.25rem;
}

.bg-gray-100 {
    background-color: #f3f4f6;
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

.bg-yellow-100 {
    background-color: #fef3c7;
}

.text-yellow-800 {
    color: #92400e;
}

.bg-purple-100 {
    background-color: #f3e8ff;
}

.text-purple-800 {
    color: #6b21a8;
}

.justify-center {
    justify-content: center;
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
function viewMapel(id) {
    // Implementation for viewing mapel details
    console.log('Viewing mapel:', id);
    openModal('mapelDetailModal');
}

function editMapel(id) {
    // Implementation for editing mapel
    console.log('Editing mapel:', id);
}

function viewSchedule(id) {
    // Implementation for viewing mapel schedule
    console.log('Viewing schedule for mapel:', id);
    openModal('scheduleModal');
}

function openCreateMapelModal() {
    // Implementation for opening create mapel modal
    console.log('Opening create mapel modal');
}

function refreshData() {
    // Implementation for refreshing data
    window.location.reload();
}
</script>
@endpush
@endsection
