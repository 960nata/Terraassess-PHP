@extends('layouts.unified-layout')

@section('title', 'Detail Tugas Kelompok')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-users mr-2"></i>
                        {{ $groupTask->title }}
                    </h3>
                    <div>
                        <span class="badge badge-{{ $groupTask->is_active ? 'success' : 'secondary' }} mr-2">
                            {{ $groupTask->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                        <a href="{{ route('group-tasks.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <h5>Deskripsi Tugas</h5>
                                <p class="text-muted">{{ $groupTask->description }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <h5>Instruksi Pengerjaan</h5>
                                <div class="bg-light p-3 rounded">
                                    {!! nl2br(e($groupTask->instructions)) !!}
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6><i class="fas fa-book mr-2"></i>Mata Pelajaran</h6>
                                    <p class="text-muted">{{ $groupTask->subject->name ?? 'Tidak ada' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="fas fa-users mr-2"></i>Kelas</h6>
                                    <p class="text-muted">{{ $groupTask->class->name ?? 'Tidak ada' }}</p>
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6><i class="fas fa-calendar mr-2"></i>Tanggal Mulai</h6>
                                    <p class="text-muted">{{ $groupTask->start_date->format('d M Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="fas fa-calendar mr-2"></i>Tanggal Selesai</h6>
                                    <p class="text-muted">{{ $groupTask->end_date->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card border-left-primary">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Informasi Kelompok
                                    </h5>
                                    
                                    <div class="mb-3">
                                        <strong>Anggota:</strong>
                                        <span class="badge badge-primary ml-2">
                                            {{ $groupTask->member_count }}/{{ $groupTask->max_members }}
                                        </span>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <strong>Min. Anggota:</strong>
                                        <span class="ml-2">{{ $groupTask->min_members }}</span>
                                    </div>
                                    
                                    @if($groupTask->leader)
                                        <div class="mb-3">
                                            <strong>Ketua Kelompok:</strong>
                                            <div class="mt-1">
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-crown mr-1"></i>
                                                    {{ $groupTask->leader->student->name }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if(auth()->user()->role === 'student')
                                        @if($isMember)
                                            <div class="alert alert-success">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                Anda adalah anggota kelompok ini
                                            </div>
                                            
                                            @if($isLeader)
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-crown mr-2"></i>
                                                    Anda adalah ketua kelompok
                                                </div>
                                            @endif
                                        @elseif($groupTask->canJoin())
                                            <form action="{{ route('group-tasks.join', $groupTask) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-block">
                                                    <i class="fas fa-user-plus mr-1"></i>
                                                    Bergabung dengan Kelompok
                                                </button>
                                            </form>
                                        @else
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle mr-2"></i>
                                                Kelompok penuh atau tidak aktif
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Daftar Anggota Kelompok -->
                    <div class="mt-4">
                        <h5>
                            <i class="fas fa-users mr-2"></i>
                            Anggota Kelompok
                        </h5>
                        
                        @if($groupTask->members->count() > 0)
                            <div class="row">
                                @foreach($groupTask->members as $member)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card {{ $member->is_leader ? 'border-warning' : 'border-light' }}">
                                            <div class="card-body text-center">
                                                <div class="mb-2">
                                                    @if($member->is_leader)
                                                        <i class="fas fa-crown fa-2x text-warning"></i>
                                                    @else
                                                        <i class="fas fa-user fa-2x text-primary"></i>
                                                    @endif
                                                </div>
                                                
                                                <h6 class="card-title">
                                                    {{ $member->student->name }}
                                                    @if($member->is_leader)
                                                        <span class="badge badge-warning ml-1">Ketua</span>
                                                    @endif
                                                </h6>
                                                
                                                <p class="card-text text-muted small">
                                                    Bergabung: {{ $member->joined_at->format('d M Y') }}
                                                </p>
                                                
                                                @if(auth()->user()->role === 'student' && $isLeader && !$member->is_leader)
                                                    <button type="button" class="btn btn-sm btn-outline-warning" 
                                                            onclick="selectLeader({{ $member->student_id }}, '{{ $member->student->name }}')">
                                                        <i class="fas fa-crown mr-1"></i>
                                                        Jadikan Ketua
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">Belum ada anggota kelompok</h6>
                                <p class="text-muted">Siswa dapat bergabung dengan kelompok ini.</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Aksi untuk Ketua Kelompok -->
                    @if(auth()->user()->role === 'student' && $isLeader)
                        <div class="mt-4">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">
                                        <i class="fas fa-crown mr-2"></i>
                                        Aksi Ketua Kelompok
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a href="{{ route('group-tasks.evaluation', $groupTask) }}" 
                                               class="btn btn-primary btn-block">
                                                <i class="fas fa-star mr-1"></i>
                                                Lakukan Penilaian
                                            </a>
                                        </div>
                                        <div class="col-md-6">
                                            <a href="{{ route('group-tasks.results', $groupTask) }}" 
                                               class="btn btn-info btn-block">
                                                <i class="fas fa-chart-bar mr-1"></i>
                                                Lihat Hasil Penilaian
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk memilih ketua -->
<div class="modal fade" id="selectLeaderModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Ketua Kelompok</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="selectLeaderForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menjadikan <strong id="studentName"></strong> sebagai ketua kelompok?</p>
                    <p class="text-muted small">Anda akan kehilangan status sebagai ketua kelompok.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-crown mr-1"></i>
                        Jadikan Ketua
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function selectLeader(studentId, studentName) {
    document.getElementById('studentName').textContent = studentName;
    document.getElementById('selectLeaderForm').action = '{{ route("group-tasks.select-leader", $groupTask) }}';
    document.querySelector('#selectLeaderForm input[name="student_id"]')?.remove();
    
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'student_id';
    input.value = studentId;
    document.getElementById('selectLeaderForm').appendChild(input);
    
    $('#selectLeaderModal').modal('show');
}
</script>
@endsection
