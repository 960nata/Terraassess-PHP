@extends('layouts.unified-layout')

@section('container')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">📊 Penilaian dengan Rubrik - {{ $tugas->name }}</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($tugas->rubrik->count() > 0)
                        <form action="{{ route('siswaUpdateNilai', ['token' => encrypt($tugas->id)]) }}" method="POST" id="gradingForm">
                            @csrf
                            
                            <div class="table-responsive">
                                <table class="table table-bordered grading-table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th width="20%">Nama Siswa</th>
                                            <th width="50%">Penilaian per Aspek</th>
                                            <th width="20%">Komentar Umum</th>
                                            <th width="10%">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($siswaTugas as $index => $userTugas)
                                        <tr>
                                            <td>
                                                <strong>{{ $userTugas->user->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $userTugas->user->email }}</small>
                                            </td>
                                            <td>
                                                @foreach($tugas->rubrik as $rubrik)
                                                <div class="mb-3 rubrik-aspek">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-bold">
                                                                {{ $rubrik->aspek }} 
                                                                <span class="badge bg-info">{{ $rubrik->bobot }}%</span>
                                                            </label>
                                                            @if($rubrik->deskripsi)
                                                                <small class="text-muted d-block">{{ $rubrik->deskripsi }}</small>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="number" 
                                                                   name="rubrik[{{ $userTugas->user->id }}][{{ $rubrik->id }}][nilai]" 
                                                                   class="form-control form-control-sm rubrik-nilai" 
                                                                   min="0" max="100"
                                                                   data-bobot="{{ $rubrik->bobot }}"
                                                                   data-siswa="{{ $userTugas->user->id }}"
                                                                   value="{{ $userTugas->userTugasRubrik->where('rubrik_id', $rubrik->id)->first()->nilai ?? '' }}"
                                                                   onchange="calculateTotal({{ $userTugas->user->id }})">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <textarea name="rubrik[{{ $userTugas->user->id }}][{{ $rubrik->id }}][komentar]" 
                                                                      class="form-control form-control-sm" 
                                                                      rows="2" 
                                                                      placeholder="Komentar untuk aspek ini...">{{ $userTugas->userTugasRubrik->where('rubrik_id', $rubrik->id)->first()->komentar_aspek ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </td>
                                            <td>
                                                <textarea name="komentar[{{ $userTugas->user->id }}]" 
                                                          class="form-control" 
                                                          rows="4" 
                                                          placeholder="Komentar umum untuk siswa...">{{ $userTugas->komentar ?? '' }}</textarea>
                                                
                                                <div class="quick-comments mt-2">
                                                    <button type="button" class="btn btn-sm btn-outline-primary me-1" 
                                                            data-comment="Bagus sekali! Teruskan kerja bagusmu." 
                                                            data-target="komentar[{{ $userTugas->user->id }}]">
                                                        👍 Bagus
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-warning me-1" 
                                                            data-comment="Perlu perbaikan di beberapa aspek." 
                                                            data-target="komentar[{{ $userTugas->user->id }}]">
                                                        📝 Perbaikan
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                                            data-comment="Cukup baik, tingkatkan lagi." 
                                                            data-target="komentar[{{ $userTugas->user->id }}]">
                                                        💪 Cukup Baik
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <h4 class="text-primary mb-0" id="total-{{ $userTugas->user->id }}">0</h4>
                                                <small class="text-muted">Nilai Akhir</small>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> Simpan Penilaian
                                </button>
                                <a href="{{ route('teacher.tugas.view', ['token' => encrypt($tugas->id)]) }}" 
                                   class="btn btn-secondary btn-lg ms-2">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            <h5><i class="fas fa-exclamation-triangle"></i> Rubrik Belum Dibuat</h5>
                            <p>Untuk menggunakan penilaian dengan rubrik, Anda perlu membuat rubrik terlebih dahulu.</p>
                            <a href="{{ route('rubrik.show', $tugas->id) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Buat Rubrik
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function calculateTotal(siswaId) {
    let total = 0;
    const rubrikInputs = document.querySelectorAll(`input[data-siswa="${siswaId}"].rubrik-nilai`);
    
    rubrikInputs.forEach(input => {
        const nilai = parseFloat(input.value) || 0;
        const bobot = parseInt(input.dataset.bobot) || 0;
        total += (nilai * bobot / 100);
    });
    
    const totalElement = document.getElementById(`total-${siswaId}`);
    if (totalElement) {
        totalElement.textContent = Math.round(total);
        
        // Color coding based on score
        totalElement.className = 'text-primary mb-0';
        if (total >= 90) {
            totalElement.className = 'text-success mb-0';
        } else if (total >= 80) {
            totalElement.className = 'text-primary mb-0';
        } else if (total >= 70) {
            totalElement.className = 'text-warning mb-0';
        } else if (total >= 60) {
            totalElement.className = 'text-info mb-0';
        } else {
            totalElement.className = 'text-danger mb-0';
        }
    }
}

// Quick Comments functionality
document.addEventListener('DOMContentLoaded', function() {
    // Quick comment buttons
    document.querySelectorAll('.quick-comments button').forEach(button => {
        button.addEventListener('click', function() {
            const targetName = this.dataset.target;
            const comment = this.dataset.comment;
            const textarea = document.querySelector(`textarea[name="${targetName}"]`);
            if (textarea) {
                textarea.value = comment;
            }
        });
    });

    // Calculate initial totals
    document.querySelectorAll('.rubrik-nilai').forEach(input => {
        const siswaId = input.dataset.siswa;
        calculateTotal(siswaId);
    });

    // Auto-save functionality
    let saveTimeout;
    document.getElementById('gradingForm').addEventListener('input', function() {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(() => {
            console.log('Auto-saving progress...');
            // Here you would typically send an AJAX request to save the form data
        }, 3000);
    });

    // Prevent accidental navigation
    window.addEventListener('beforeunload', function (e) {
        const form = document.getElementById('gradingForm');
        if (form.querySelector('input:not([type="hidden"]):not([value=""]), textarea:not([value=""])')) {
            const confirmationMessage = 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman ini?';
            (e || window.event).returnValue = confirmationMessage;
            return confirmationMessage;
        }
    });
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl + S to save
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        document.getElementById('gradingForm').submit();
    }
});
</script>
@endpush

@push('styles')
<style>
.grading-table {
    font-size: 0.9rem;
}

.rubrik-aspek {
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 10px;
    background-color: #f8f9fa;
}

.rubrik-aspek:hover {
    background-color: #e3f2fd;
    border-color: #007bff;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.quick-comments button {
    font-size: 0.8rem;
    padding: 0.25rem 0.5rem;
    margin-bottom: 5px;
}

#total-0, #total-1, #total-2, #total-3, #total-4, #total-5, #total-6, #total-7, #total-8, #total-9 {
    font-weight: bold;
    font-size: 1.5rem;
}

@media (max-width: 768px) {
    .grading-table {
        display: block;
        overflow-x: auto;
    }
    
    .rubrik-aspek .row > div {
        margin-bottom: 10px;
    }
    
    .quick-comments {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }
    
    .quick-comments button {
        flex: 1 1 45%;
        font-size: 0.7rem;
    }
}
</style>
@endpush
@endsection
