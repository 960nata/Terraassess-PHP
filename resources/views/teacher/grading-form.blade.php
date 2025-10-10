{{-- Form Penilaian Guru dengan Feedback --}}
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">📝 Penilaian Tugas: {{ $tugas->name }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('siswaUpdateNilai', ['token' => encrypt($tugas->id)]) }}" method="post" id="gradingForm">
            @csrf
            
            <div class="table-responsive">
                <table class="table table-striped grading-table">
                    <thead class="table-dark">
                        <tr>
                            <th width="25%">Nama Siswa</th>
                            <th width="15%">Nilai (0-100)</th>
                            <th width="50%">Feedback/Komentar</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswa as $index => $s)
                        <tr>
                            <td>
                                <strong>{{ $s->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $s->email }}</small>
                            </td>
                            <td>
                                <input type="number" 
                                       name="nilai[]" 
                                       class="form-control nilai-input" 
                                       min="0" 
                                       max="100"
                                       value="{{ $s->userTugas->nilai ?? '' }}"
                                       onchange="autoSaveGrading()">
                            </td>
                            <td>
                                <div class="quick-comments mb-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="insertComment({{ $index }}, 'Bagus sekali! Teruskan.')">
                                        👍 Bagus
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-warning" 
                                            onclick="insertComment({{ $index }}, 'Perlu perbaikan di bagian analisis.')">
                                        📝 Perlu Perbaikan
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-info" 
                                            onclick="insertComment({{ $index }}, 'Cukup baik, tingkatkan lagi.')">
                                        💪 Cukup Baik
                                    </button>
                                </div>
                                <textarea name="komentar[]" 
                                          class="form-control komentar-textarea" 
                                          rows="3" 
                                          placeholder="Tuliskan feedback untuk {{ $s->name }}..."
                                          onchange="autoSaveGrading()">{{ $s->userTugas->komentar ?? '' }}</textarea>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-success" 
                                        onclick="quickGrade({{ $index }}, 85, 'Bagus!')">
                                    ⚡ Quick
                                </button>
                            </td>
                            <input type="hidden" name="siswaId[]" value="{{ $s->id }}">
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="alert alert-info">
                        <h6>💡 Tips Penilaian:</h6>
                        <ul class="mb-0">
                            <li>Gunakan quick comments untuk efisiensi</li>
                            <li>Berikan feedback yang konstruktif</li>
                            <li>Progress otomatis tersimpan</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <button type="button" class="btn btn-outline-secondary me-2" onclick="loadGradingDraft()">
                        📂 Load Draft
                    </button>
                    <button type="submit" class="btn btn-primary">
                        💾 Simpan Penilaian
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Progress Indicator --}}
<div class="progress mt-3" style="display: none;" id="gradingProgress">
    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
</div>

{{-- Auto-save Status --}}
<div class="alert alert-success mt-2" style="display: none;" id="autoSaveStatus">
    ✅ Progress tersimpan otomatis
</div>

<script>
// Quick comment insertion
function insertComment(index, comment) {
    const textarea = document.querySelectorAll('textarea[name="komentar[]"]')[index];
    textarea.value = comment;
    textarea.style.borderColor = '#28a745';
    setTimeout(() => {
        textarea.style.borderColor = '';
    }, 1000);
    autoSaveGrading();
}

// Quick grade function
function quickGrade(index, nilai, komentar) {
    const nilaiInput = document.querySelectorAll('input[name="nilai[]"]')[index];
    const komentarTextarea = document.querySelectorAll('textarea[name="komentar[]"]')[index];
    
    nilaiInput.value = nilai;
    komentarTextarea.value = komentar;
    
    // Visual feedback
    nilaiInput.style.borderColor = '#28a745';
    komentarTextarea.style.borderColor = '#28a745';
    
    setTimeout(() => {
        nilaiInput.style.borderColor = '';
        komentarTextarea.style.borderColor = '';
    }, 1000);
    
    autoSaveGrading();
}

// Auto-save progress (localStorage)
function autoSaveGrading() {
    const formData = {};
    document.querySelectorAll('input[name="nilai[]"]').forEach((input, index) => {
        formData[index] = {
            nilai: input.value,
            komentar: document.querySelectorAll('textarea[name="komentar[]"]')[index].value
        };
    });
    
    localStorage.setItem('grading_draft_{{ $tugas->id }}', JSON.stringify(formData));
    
    // Show auto-save status
    const status = document.getElementById('autoSaveStatus');
    status.style.display = 'block';
    setTimeout(() => {
        status.style.display = 'none';
    }, 2000);
}

// Load saved progress
function loadGradingDraft() {
    const saved = localStorage.getItem('grading_draft_{{ $tugas->id }}');
    if (saved) {
        const data = JSON.parse(saved);
        
        document.querySelectorAll('input[name="nilai[]"]').forEach((input, index) => {
            if (data[index]) {
                input.value = data[index].nilai || '';
            }
        });
        
        document.querySelectorAll('textarea[name="komentar[]"]').forEach((textarea, index) => {
            if (data[index]) {
                textarea.value = data[index].komentar || '';
            }
        });
        
        // Show success message
        const status = document.getElementById('autoSaveStatus');
        status.textContent = '📂 Draft berhasil dimuat';
        status.className = 'alert alert-info mt-2';
        status.style.display = 'block';
        setTimeout(() => {
            status.style.display = 'none';
        }, 2000);
    } else {
        alert('Tidak ada draft tersimpan');
    }
}

// Form submission with progress
document.getElementById('gradingForm').addEventListener('submit', function(e) {
    const progress = document.getElementById('gradingProgress');
    progress.style.display = 'block';
    
    let width = 0;
    const interval = setInterval(() => {
        width += 10;
        progress.querySelector('.progress-bar').style.width = width + '%';
        
        if (width >= 100) {
            clearInterval(interval);
        }
    }, 100);
    
    // Clear saved draft after successful submission
    setTimeout(() => {
        localStorage.removeItem('grading_draft_{{ $tugas->id }}');
    }, 2000);
});

// Auto-save every 30 seconds
setInterval(autoSaveGrading, 30000);

// Load draft on page load
document.addEventListener('DOMContentLoaded', function() {
    const saved = localStorage.getItem('grading_draft_{{ $tugas->id }}');
    if (saved) {
        const status = document.getElementById('autoSaveStatus');
        status.textContent = '📂 Draft tersimpan tersedia - klik "Load Draft" untuk memuat';
        status.className = 'alert alert-warning mt-2';
        status.style.display = 'block';
    }
});
</script>

<style>
.grading-table th {
    background-color: #343a40 !important;
    color: white;
    font-weight: 600;
}

.nilai-input {
    font-weight: bold;
    text-align: center;
}

.komentar-textarea {
    resize: vertical;
    min-height: 80px;
}

.quick-comments {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin-bottom: 8px;
}

.quick-comments button {
    font-size: 12px;
    padding: 4px 8px;
}

.grading-table tbody tr:hover {
    background-color: #f8f9fa;
}

@media (max-width: 768px) {
    .grading-table {
        font-size: 14px;
    }
    
    .quick-comments {
        flex-direction: column;
    }
    
    .quick-comments button {
        width: 100%;
        margin-bottom: 2px;
    }
    
    .komentar-textarea {
        min-height: 60px;
    }
}
</style>
