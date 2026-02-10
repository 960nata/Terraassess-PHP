@extends('layouts.unified-layout')

@section('title', 'Terra Assessment - Manajemen Pengajar')

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
                        <h1 class="text-2xl font-bold text-white">Manajemen Pengajar</h1>
                        <p class="text-gray-400">Kelola data pengajar dan guru</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button onclick="openAddModal()" class="galaxy-button">
                        <i class="ph-plus"></i>
                        Tambah Pengajar
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
                        <input type="text" id="searchInput" placeholder="Cari pengajar..." class="search-input">
                    </div>
                    <div class="flex gap-2">
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

            <!-- Data Table -->
            <div class="galaxy-card">
                <div class="overflow-x-auto">
                    <table class="data-table w-full">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengajar as $index => $p)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                            <i class="ph-user text-white"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-white">{{ $p->name }}</div>
                                            <div class="text-sm text-gray-400">ID: {{ $p->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $p->email }}</td>
                                <td>
                                    @if($p->mapel)
                                        <span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-sm">
                                            {{ $p->mapel->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($p->kelas && count($p->kelas) > 0)
                                        @foreach($p->kelas->take(2) as $kelas)
                                            <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-xs mr-1">
                                                {{ $kelas->name }}
                                            </span>
                                        @endforeach
                                        @if(count($p->kelas) > 2)
                                            <span class="text-gray-400 text-xs">+{{ count($p->kelas) - 2 }} lainnya</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-badge {{ $p->status == 'active' ? 'status-active' : 'status-inactive' }}">
                                        {{ $p->status == 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex space-x-2">
                                        <button onclick="openEditModal({{ $p->id }})" class="galaxy-button secondary text-sm px-3 py-1">
                                            <i class="ph-pencil"></i>
                                        </button>
                                        <button onclick="deletePengajar({{ $p->id }})" class="galaxy-button danger text-sm px-3 py-1">
                                            <i class="ph-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-400">
                                    <i class="ph-user-circle text-4xl mb-2 block"></i>
                                    Belum ada data pengajar
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
    <div id="pengajarModal" class="modal">
        <div class="modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-white" id="modalTitle">Tambah Pengajar</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white">
                    <i class="ph-x text-xl"></i>
                </button>
            </div>

            <form id="pengajarForm" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-input" required>
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
                    <label class="form-label">Mata Pelajaran</label>
                    <select name="mapel_id" class="form-input">
                        <option value="">Pilih Mata Pelajaran</option>
                        @foreach($mapel as $m)
                            <option value="{{ $m->id }}">{{ $m->name }}</option>
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
            document.getElementById('modalTitle').textContent = 'Tambah Pengajar';
            document.getElementById('pengajarForm').reset();
            document.getElementById('pengajarForm').action = '{{ route("admin.pengajar.store") }}';
            document.getElementById('pengajarModal').classList.add('show');
        }

        function openEditModal(id) {
            document.getElementById('modalTitle').textContent = 'Edit Pengajar';
            document.getElementById('pengajarForm').action = `{{ url('admin/pengajar') }}/${id}`;
            document.getElementById('pengajarForm').innerHTML += '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('pengajarModal').classList.add('show');
            
            // Load data for editing (you can implement this with AJAX)
        }

        function closeModal() {
            document.getElementById('pengajarModal').classList.remove('show');
        }

        function deletePengajar(id) {
            if (confirm('Apakah Anda yakin ingin menghapus pengajar ini?')) {
                // Create form for deletion
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ url('admin/pengajar') }}/${id}`;
                
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
        document.getElementById('pengajarModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>
