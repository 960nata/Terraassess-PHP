@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Manajemen Siswa')

@section('styles')
    <style>
        /* Additional specific styles if needed */
    </style>
@endsection

@section('content')
<div class="min-h-screen">
        <!-- Header -->
        <header class="galaxy-card m-6 mb-0">
            <div class="flex items-center justify-between p-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <i class="ph-arrow-left text-white text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-white">Manajemen Siswa</h1>
                        <p class="text-gray-400">Kelola data siswa dan peserta didik</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button onclick="openAddModal()" class="galaxy-button">
                        <i class="ph-plus"></i>
                        Tambah Siswa
                    </button>
                    <button onclick="exportData()" class="galaxy-button secondary">
                        <i class="ph-download"></i>
                        Export
                    </button>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="galaxy-button secondary">
                            <i class="ph-sign-out"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <div class="p-6">
            <!-- Search and Filter -->
            <div class="galaxy-card p-6 mb-6">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" id="searchInput" placeholder="Cari siswa..." class="search-input">
                    </div>
                    <div class="flex gap-2">
                        <select class="search-input w-auto">
                            <option value="">Semua Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}">{{ $k->name }}</option>
                            @endforeach
                        </select>
                        <select class="search-input w-auto">
                            <option value="">Semua Status</option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Tidak Aktif</option>
                        </select>
                        <button class="galaxy-button secondary">
                            <i class="ph-funnel"></i>
                            Filter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="galaxy-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Total Siswa</p>
                            <p class="text-2xl font-bold text-white">{{ $siswa->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                            <i class="ph-student text-blue-400 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="galaxy-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Siswa Aktif</p>
                            <p class="text-2xl font-bold text-white">{{ $siswa->where('status', 'active')->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                            <i class="ph-check-circle text-green-400 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="galaxy-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Kelas Terisi</p>
                            <p class="text-2xl font-bold text-white">{{ $kelas->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                            <i class="ph-buildings text-purple-400 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="galaxy-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Rata-rata per Kelas</p>
                            <p class="text-2xl font-bold text-white">{{ $kelas->count() > 0 ? round($siswa->count() / $kelas->count(), 1) : 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-500/20 rounded-xl flex items-center justify-center">
                            <i class="ph-chart-bar text-orange-400 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="galaxy-card">
                <div class="overflow-x-auto">
                    <table class="data-table w-full">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIS</th>
                                <th>Kelas</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswa as $index => $s)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-blue-600 rounded-full flex items-center justify-center">
                                            <i class="ph-user text-white"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-white">{{ $s->name }}</div>
                                            <div class="text-sm text-gray-400">{{ $s->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="px-3 py-1 bg-gray-500/20 text-gray-400 rounded-full text-sm">
                                        {{ $s->nis ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    @if($s->kelas)
                                        <span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-sm">
                                            {{ $s->kelas->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td>{{ $s->email }}</td>
                                <td>
                                    <span class="status-badge {{ $s->status == 'active' ? 'status-active' : 'status-inactive' }}">
                                        {{ $s->status == 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex space-x-2">
                                        <button onclick="openEditModal({{ $s->id }})" class="galaxy-button secondary text-sm px-3 py-1">
                                            <i class="ph-pencil"></i>
                                        </button>
                                        <button onclick="viewDetail({{ $s->id }})" class="galaxy-button success text-sm px-3 py-1">
                                            <i class="ph-eye"></i>
                                        </button>
                                        <button onclick="deleteSiswa({{ $s->id }})" class="galaxy-button danger text-sm px-3 py-1">
                                            <i class="ph-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-400">
                                    <i class="ph-student text-4xl mb-2 block"></i>
                                    Belum ada data siswa
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="siswaModal" class="modal">
        <div class="modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-white" id="modalTitle">Tambah Siswa</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white">
                    <i class="ph-x text-xl"></i>
                </button>
            </div>

            <form id="siswaForm" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">NIS</label>
                    <input type="text" name="nis" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Kelas</label>
                    <select name="kelas_id" class="form-input">
                        <option value="">Pilih Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="active">Aktif</option>
                        <option value="inactive">Tidak Aktif</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal()" class="galaxy-button secondary">
                        Batal
                    </button>
                    <button type="submit" class="galaxy-button">
                        <i class="ph-check"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functions
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Siswa';
            document.getElementById('siswaForm').reset();
            document.getElementById('siswaForm').action = '{{ route("admin.siswa.store") }}';
            document.getElementById('siswaModal').classList.add('show');
        }

        function openEditModal(id) {
            document.getElementById('modalTitle').textContent = 'Edit Siswa';
            document.getElementById('siswaForm').action = `{{ url('admin/siswa') }}/${id}`;
            document.getElementById('siswaForm').innerHTML += '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('siswaModal').classList.add('show');
        }

        function viewDetail(id) {
            // Implement view detail functionality
            alert('View detail for student ID: ' + id);
        }

        function closeModal() {
            document.getElementById('siswaModal').classList.remove('show');
        }

        function deleteSiswa(id) {
            if (confirm('Apakah Anda yakin ingin menghapus siswa ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ url('admin/siswa') }}/${id}`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function exportData() {
            // Implement export functionality
            alert('Export data functionality');
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Close modal when clicking outside
        document.getElementById('siswaModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>
