@extends('layouts.unified-layout')

@section('container')
    {{-- Cek peran pengguna --}}
    @if (Auth()->user()->roles_id == 1)
        @include('menu.admin.adminHelper')
    @endif

    {{-- Navigasi Breadcrumb --}}
    <div class="col-15 ps-1 pe-1 mb-1">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Manajemen Materi</li>
            </ol>
        </nav>
    </div>

    {{-- Judul Halaman --}}
    <div class="ps-2 pe-2 mt-2 pt-2">
        <h5 class="display-6 fw-bold">Manajemen Materi</h5>
        <p class="text-muted">Kelola materi pembelajaran Anda</p>
    </div>

    {{-- Statistik Materi --}}
    <div class="col-15 ps-2 pe-2 mb-3">
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">{{ $myMateri->count() }}</h4>
                                <p class="card-text">Total Materi</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-book fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">{{ $myMateri->where('isHidden', 0)->count() }}</h4>
                                <p class="card-text">Materi Aktif</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-eye fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">{{ $myMateri->where('isHidden', 1)->count() }}</h4>
                                <p class="card-text">Materi Tersembunyi</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-eye-slash fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title">{{ $assignedKelas->count() }}</h4>
                                <p class="card-text">Kelas Diampu</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-chalkboard-teacher fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Materi --}}
    <div class="col-15 ps-2 pe-2 mb-2">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Daftar Materi</h5>
            </div>
            <div class="card-body">
                @if($myMateri->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul Materi</th>
                                    <th>Kelas</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Status</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($myMateri as $index => $materi)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $materi->name }}</strong>
                                            @if($materi->isHidden == 1)
                                                <i class="fa-solid fa-lock text-danger ms-1"></i>
                                            @endif
                                        </td>
                                        <td>{{ $materi->kelasMapel->kelas->name ?? 'N/A' }}</td>
                                        <td>{{ $materi->kelasMapel->mapel->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($materi->isHidden == 1)
                                                <span class="badge bg-warning">Tersembunyi</span>
                                            @else
                                                <span class="badge bg-success">Aktif</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($materi->created_at)->format('d M Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('viewMateri', [
                                                    'token' => encrypt($materi->id), 
                                                    'kelasMapelId' => encrypt($materi->kelas_mapel_id), 
                                                    'mapelId' => $materi->kelasMapel->mapel->id ?? 1
                                                ]) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="Lihat Materi">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                                <a href="{{ route('viewUpdateMateri', [
                                                    'token' => encrypt($materi->id)
                                                ]) }}?mapelId={{ $materi->kelasMapel->mapel->id ?? 1 }}" 
                                                   class="btn btn-sm btn-outline-warning" 
                                                   title="Edit Materi">
                                                    <i class="fa-solid fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fa-solid fa-book fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada materi</h5>
                        <p class="text-muted">Mulai buat materi pertama Anda</p>
                        @if($assignedKelas->count() > 0)
                            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMateriModal">
                                <i class="fa-solid fa-plus"></i> Buat Materi Baru
                            </a>
                        @else
                            <p class="text-muted small">Hubungi administrator untuk mendapatkan akses kelas</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal untuk memilih kelas dan mapel --}}
    @if($assignedKelas->count() > 0)
        <div class="modal fade" id="createMateriModal" tabindex="-1" aria-labelledby="createMateriModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createMateriModalLabel">Pilih Kelas dan Mata Pelajaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="createMateriForm">
                            <div class="mb-3">
                                <label for="kelasSelect" class="form-label">Kelas</label>
                                <select class="form-select" id="kelasSelect" name="kelas_id" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach($assignedKelas as $kelas)
                                        <option value="{{ $kelas->id }}">{{ $kelas->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="mapelSelect" class="form-label">Mata Pelajaran</label>
                                <select class="form-select" id="mapelSelect" name="mapel_id" required disabled>
                                    <option value="">Pilih Mata Pelajaran</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="createMateriBtn" disabled>Buat Materi</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        // Handle kelas selection to load mapel
        document.getElementById('kelasSelect').addEventListener('change', function() {
            const kelasId = this.value;
            const mapelSelect = document.getElementById('mapelSelect');
            const createBtn = document.getElementById('createMateriBtn');
            
            if (kelasId) {
                // Enable mapel select
                mapelSelect.disabled = false;
                mapelSelect.innerHTML = '<option value="">Loading...</option>';
                
                // Fetch mapel for selected kelas
                fetch(`/api/kelas/${kelasId}/mapel`)
                    .then(response => response.json())
                    .then(data => {
                        mapelSelect.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
                        data.forEach(mapel => {
                            const option = document.createElement('option');
                            option.value = mapel.id;
                            option.textContent = mapel.name;
                            mapelSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        mapelSelect.innerHTML = '<option value="">Error loading data</option>';
                    });
            } else {
                mapelSelect.disabled = true;
                mapelSelect.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
                createBtn.disabled = true;
            }
        });

        // Handle mapel selection
        document.getElementById('mapelSelect').addEventListener('change', function() {
            const createBtn = document.getElementById('createMateriBtn');
            createBtn.disabled = !this.value;
        });

        // Handle create materi button
        document.getElementById('createMateriBtn').addEventListener('click', function() {
            const kelasId = document.getElementById('kelasSelect').value;
            const mapelId = document.getElementById('mapelSelect').value;
            
            if (kelasId && mapelId) {
                // Redirect to create materi page
                window.location.href = `/materi/add/${btoa(kelasId)}?mapelId=${mapelId}`;
            }
        });
    </script>
@endsection
