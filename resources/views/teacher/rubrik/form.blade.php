@extends('layouts.unified-layout')

@section('container')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">⚖️ Rubrik Penilaian - {{ $tugas->name }}</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('rubrik.store') }}" method="POST" id="rubrikForm">
                        @csrf
                        <input type="hidden" name="tugas_id" value="{{ $tugas->id }}">
                        
                        <div id="rubrik-container">
                            @if($tugas->rubrik->count() > 0)
                                @foreach($tugas->rubrik as $index => $rubrik)
                                <div class="row mb-3 rubrik-item" data-index="{{ $index }}">
                                    <div class="col-md-4">
                                        <label class="form-label">Aspek Penilaian</label>
                                        <input type="text" name="aspek[]" class="form-control" 
                                               value="{{ $rubrik->aspek }}" 
                                               placeholder="Contoh: Isi & Analisis" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Bobot (%)</label>
                                        <input type="number" name="bobot[]" class="form-control bobot-input" 
                                               value="{{ $rubrik->bobot }}" 
                                               placeholder="40" min="1" max="100" required>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Deskripsi Kriteria</label>
                                        <input type="text" name="deskripsi[]" class="form-control" 
                                               value="{{ $rubrik->deskripsi }}" 
                                               placeholder="Deskripsi kriteria penilaian...">
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-sm d-block" 
                                                onclick="removeRubrik(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <!-- Default rubrik items -->
                                <div class="row mb-3 rubrik-item" data-index="0">
                                    <div class="col-md-4">
                                        <label class="form-label">Aspek Penilaian</label>
                                        <input type="text" name="aspek[]" class="form-control" 
                                               value="Isi & Analisis" 
                                               placeholder="Contoh: Isi & Analisis" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Bobot (%)</label>
                                        <input type="number" name="bobot[]" class="form-control bobot-input" 
                                               value="40" 
                                               placeholder="40" min="1" max="100" required>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Deskripsi Kriteria</label>
                                        <input type="text" name="deskripsi[]" class="form-control" 
                                               value="Kedalaman analisis dan relevansi isi" 
                                               placeholder="Deskripsi kriteria penilaian...">
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-sm d-block" 
                                                onclick="removeRubrik(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="row mb-3 rubrik-item" data-index="1">
                                    <div class="col-md-4">
                                        <label class="form-label">Aspek Penilaian</label>
                                        <input type="text" name="aspek[]" class="form-control" 
                                               value="Struktur & Organisasi" 
                                               placeholder="Contoh: Struktur & Organisasi" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Bobot (%)</label>
                                        <input type="number" name="bobot[]" class="form-control bobot-input" 
                                               value="30" 
                                               placeholder="30" min="1" max="100" required>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Deskripsi Kriteria</label>
                                        <input type="text" name="deskripsi[]" class="form-control" 
                                               value="Keruntutan dan logika penyajian" 
                                               placeholder="Deskripsi kriteria penilaian...">
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-sm d-block" 
                                                onclick="removeRubrik(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="row mb-3 rubrik-item" data-index="2">
                                    <div class="col-md-4">
                                        <label class="form-label">Aspek Penilaian</label>
                                        <input type="text" name="aspek[]" class="form-control" 
                                               value="Bahasa & Ejaan" 
                                               placeholder="Contoh: Bahasa & Ejaan" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Bobot (%)</label>
                                        <input type="number" name="bobot[]" class="form-control bobot-input" 
                                               value="30" 
                                               placeholder="30" min="1" max="100" required>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Deskripsi Kriteria</label>
                                        <input type="text" name="deskripsi[]" class="form-control" 
                                               value="Ketepatan bahasa dan ejaan" 
                                               placeholder="Deskripsi kriteria penilaian...">
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" class="btn btn-danger btn-sm d-block" 
                                                onclick="removeRubrik(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-primary" onclick="addRubrik()">
                                <i class="fas fa-plus"></i> Tambah Aspek
                            </button>
                        </div>
                        
                        <div class="mt-4 p-3 bg-light rounded">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Total Bobot: <span id="total-bobot" class="text-primary">0</span>%</strong>
                                </div>
                                <div class="col-md-6 text-end">
                                    <span id="bobot-warning" class="text-danger" style="display:none">
                                        <i class="fas fa-exclamation-triangle"></i> Total harus 100%
                                    </span>
                                    <span id="bobot-success" class="text-success" style="display:none">
                                        <i class="fas fa-check-circle"></i> Bobot sudah sesuai
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Simpan Rubrik
                            </button>
                            <a href="{{ route('teacher.tugas.view', ['token' => encrypt($tugas->id)]) }}" 
                               class="btn btn-secondary btn-lg ms-2">
                                <i class="fas fa-arrow-left"></i> Kembali ke Tugas
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let rubrikIndex = {{ $tugas->rubrik->count() > 0 ? $tugas->rubrik->count() : 3 }};

function addRubrik() {
    const container = document.getElementById('rubrik-container');
    const newItem = document.createElement('div');
    newItem.className = 'row mb-3 rubrik-item';
    newItem.setAttribute('data-index', rubrikIndex);
    
    newItem.innerHTML = `
        <div class="col-md-4">
            <label class="form-label">Aspek Penilaian</label>
            <input type="text" name="aspek[]" class="form-control" 
                   placeholder="Contoh: Kreativitas" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">Bobot (%)</label>
            <input type="number" name="bobot[]" class="form-control bobot-input" 
                   placeholder="0" min="1" max="100" required>
        </div>
        <div class="col-md-5">
            <label class="form-label">Deskripsi Kriteria</label>
            <input type="text" name="deskripsi[]" class="form-control" 
                   placeholder="Deskripsi kriteria penilaian...">
        </div>
        <div class="col-md-1">
            <label class="form-label">&nbsp;</label>
            <button type="button" class="btn btn-danger btn-sm d-block" 
                    onclick="removeRubrik(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    
    container.appendChild(newItem);
    rubrikIndex++;
    updateTotalBobot();
}

function removeRubrik(button) {
    const rubrikItems = document.querySelectorAll('.rubrik-item');
    if (rubrikItems.length <= 1) {
        alert('Minimal harus ada 1 aspek penilaian');
        return;
    }
    
    button.closest('.rubrik-item').remove();
    updateTotalBobot();
}

function updateTotalBobot() {
    const bobotInputs = document.querySelectorAll('.bobot-input');
    let total = 0;
    
    bobotInputs.forEach(input => {
        const value = parseInt(input.value) || 0;
        total += value;
    });
    
    const totalElement = document.getElementById('total-bobot');
    const warningElement = document.getElementById('bobot-warning');
    const successElement = document.getElementById('bobot-success');
    
    totalElement.textContent = total;
    
    if (total === 100) {
        warningElement.style.display = 'none';
        successElement.style.display = 'inline';
        totalElement.className = 'text-success';
    } else {
        warningElement.style.display = 'inline';
        successElement.style.display = 'none';
        totalElement.className = 'text-danger';
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    updateTotalBobot();
    
    // Add event listeners to existing bobot inputs
    document.querySelectorAll('.bobot-input').forEach(input => {
        input.addEventListener('input', updateTotalBobot);
    });
    
    // Form validation
    document.getElementById('rubrikForm').addEventListener('submit', function(e) {
        const total = parseInt(document.getElementById('total-bobot').textContent);
        if (total !== 100) {
            e.preventDefault();
            alert('Total bobot harus 100%. Saat ini: ' + total + '%');
            return false;
        }
    });
});

// Add event listener for dynamically added inputs
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('bobot-input')) {
        updateTotalBobot();
    }
});
</script>
@endpush

@push('styles')
<style>
.rubrik-item {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    background-color: #f8f9fa;
}

.rubrik-item:hover {
    border-color: #007bff;
    background-color: #e3f2fd;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.bobot-input {
    text-align: center;
    font-weight: bold;
}

#total-bobot {
    font-size: 1.2em;
    font-weight: bold;
}

@media (max-width: 768px) {
    .rubrik-item .col-md-4,
    .rubrik-item .col-md-2,
    .rubrik-item .col-md-5,
    .rubrik-item .col-md-1 {
        margin-bottom: 10px;
    }
}
</style>
@endpush
@endsection
