@extends('layouts.unified-layout')

@section('title', 'Buat Tugas Kelompok')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus mr-2"></i>
                        Buat Tugas Kelompok Baru
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('group-tasks.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="title">Judul Tugas <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="class_id">Kelas <span class="text-danger">*</span></label>
                                    <select class="form-control @error('class_id') is-invalid @enderror" 
                                            id="class_id" name="class_id" required>
                                        <option value="">Pilih Kelas</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subject_id">Mata Pelajaran <span class="text-danger">*</span></label>
                                    <select class="form-control @error('subject_id') is-invalid @enderror" 
                                            id="subject_id" name="subject_id" required>
                                        <option value="">Pilih Mata Pelajaran</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('subject_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="min_members">Min. Anggota <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('min_members') is-invalid @enderror" 
                                           id="min_members" name="min_members" value="{{ old('min_members', 2) }}" 
                                           min="2" max="10" required>
                                    @error('min_members')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="max_members">Max. Anggota <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('max_members') is-invalid @enderror" 
                                           id="max_members" name="max_members" value="{{ old('max_members', 5) }}" 
                                           min="2" max="10" required>
                                    @error('max_members')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Tanggal Mulai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">Tanggal Selesai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                           id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Deskripsi Tugas <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="instructions">Instruksi Pengerjaan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('instructions') is-invalid @enderror" 
                                      id="instructions" name="instructions" rows="6" required>{{ old('instructions') }}</textarea>
                            <small class="form-text text-muted">
                                Berikan instruksi yang jelas tentang cara pengerjaan tugas kelompok.
                            </small>
                            @error('instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle mr-2"></i>Informasi Sistem Penilaian</h6>
                                <ul class="mb-0">
                                    <li><strong>Kurang Baik:</strong> 1 poin</li>
                                    <li><strong>Cukup Baik:</strong> 2 poin</li>
                                    <li><strong>Baik:</strong> 3 poin</li>
                                    <li><strong>Sangat Baik:</strong> 4 poin</li>
                                </ul>
                                <p class="mb-0 mt-2">Ketua kelompok akan melakukan penilaian terhadap anggota kelompoknya.</p>
                            </div>
                        </div>
                        
                        <div class="form-group text-right">
                            <a href="{{ route('group-tasks.index') }}" class="btn btn-secondary mr-2">
                                <i class="fas fa-arrow-left mr-1"></i>
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>
                                Buat Tugas Kelompok
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const minMembersInput = document.getElementById('min_members');
    const maxMembersInput = document.getElementById('max_members');
    
    // Set default start date to today
    if (!startDateInput.value) {
        startDateInput.value = new Date().toISOString().split('T')[0];
    }
    
    // Set default end date to 7 days from start date
    if (!endDateInput.value && startDateInput.value) {
        const startDate = new Date(startDateInput.value);
        startDate.setDate(startDate.getDate() + 7);
        endDateInput.value = startDate.toISOString().split('T')[0];
    }
    
    // Update end date when start date changes
    startDateInput.addEventListener('change', function() {
        if (this.value && !endDateInput.value) {
            const startDate = new Date(this.value);
            startDate.setDate(startDate.getDate() + 7);
            endDateInput.value = startDate.toISOString().split('T')[0];
        }
    });
    
    // Ensure min members is not greater than max members
    minMembersInput.addEventListener('change', function() {
        if (parseInt(this.value) > parseInt(maxMembersInput.value)) {
            maxMembersInput.value = this.value;
        }
    });
    
    maxMembersInput.addEventListener('change', function() {
        if (parseInt(this.value) < parseInt(minMembersInput.value)) {
            minMembersInput.value = this.value;
        }
    });
});
</script>
@endsection
