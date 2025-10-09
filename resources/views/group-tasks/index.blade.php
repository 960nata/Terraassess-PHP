@extends('layouts.app')

@section('title', 'Tugas Kelompok')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-users mr-2"></i>
                        Daftar Tugas Kelompok
                    </h3>
                    @if(auth()->user()->role === 'teacher')
                        <a href="{{ route('group-tasks.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i>
                            Buat Tugas Kelompok
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @if($groupTasks->count() > 0)
                        <div class="row">
                            @foreach($groupTasks as $task)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 border-left-primary">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h5 class="card-title text-primary">{{ $task->title }}</h5>
                                                <span class="badge badge-{{ $task->is_active ? 'success' : 'secondary' }}">
                                                    {{ $task->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                                </span>
                                            </div>
                                            
                                            <p class="card-text text-muted small mb-2">
                                                <i class="fas fa-book mr-1"></i>
                                                {{ $task->subject->name ?? 'Mata Pelajaran' }}
                                            </p>
                                            
                                            <p class="card-text text-muted small mb-2">
                                                <i class="fas fa-users mr-1"></i>
                                                {{ $task->member_count }}/{{ $task->max_members }} anggota
                                            </p>
                                            
                                            <p class="card-text text-muted small mb-3">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $task->start_date->format('d M Y') }} - {{ $task->end_date->format('d M Y') }}
                                            </p>
                                            
                                            <p class="card-text">{{ Str::limit($task->description, 100) }}</p>
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="{{ route('group-tasks.show', $task) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye mr-1"></i>
                                                    Lihat Detail
                                                </a>
                                                
                                                @if(auth()->user()->role === 'student' && $task->canJoin())
                                                    @php
                                                        $isMember = $task->members()->where('student_id', auth()->id())->exists();
                                                    @endphp
                                                    
                                                    @if($isMember)
                                                        <form action="{{ route('group-tasks.leave', $task) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                    onclick="return confirm('Yakin ingin keluar dari kelompok?')">
                                                                <i class="fas fa-sign-out-alt mr-1"></i>
                                                                Keluar
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('group-tasks.join', $task) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success">
                                                                <i class="fas fa-user-plus mr-1"></i>
                                                                Bergabung
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada tugas kelompok</h5>
                            <p class="text-muted">Tugas kelompok akan muncul di sini setelah dibuat oleh guru.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
