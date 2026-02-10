{{-- Tampilan Feedback untuk Siswa --}}
@if($userTugas && $userTugas->nilai)
<div class="card mt-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">📊 Hasil Penilaian</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 text-center">
                <div class="grade-display">
                    <h1 class="display-4 text-primary mb-0">{{ $userTugas->nilai }}</h1>
                    <small class="text-muted">dari 100</small>
                    <div class="mt-2">
                        <span class="badge badge-{{ $userTugas->getGradeColor() }} fs-6">
                            Grade: {{ $userTugas->getGrade() }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                @if($userTugas->komentar)
                <div class="feedback-section">
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-comment-dots"></i> Feedback dari Guru
                    </h6>
                    <div class="alert alert-info feedback-box">
                        <div class="feedback-content">
                            {!! nl2br(e($userTugas->komentar)) !!}
                        </div>
                    </div>
                </div>
                @else
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle"></i> Belum ada feedback dari guru
                </div>
                @endif
                
                <div class="grading-info mt-3">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-user"></i> 
                                <strong>Dinilai oleh:</strong> {{ $userTugas->penilai->name ?? 'Guru' }}
                            </small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-clock"></i> 
                                <strong>Pada:</strong> 
                                {{ $userTugas->dinilai_pada ? $userTugas->dinilai_pada->format('d M Y H:i') : '-' }}
                            </small>
                        </div>
                    </div>
                    
                    @if($userTugas->revisi_ke > 1)
                    <div class="mt-2">
                        <small class="text-info">
                            <i class="fas fa-history"></i> 
                            Revisi ke-{{ $userTugas->revisi_ke }}
                        </small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        {{-- Action Buttons --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        @if($userTugas->komentar)
                        <button class="btn btn-outline-primary btn-sm" onclick="printFeedback()">
                            <i class="fas fa-print"></i> Print Feedback
                        </button>
                        @endif
                    </div>
                    <div>
                        <button class="btn btn-outline-secondary btn-sm" onclick="shareFeedback()">
                            <i class="fas fa-share"></i> Share
                        </button>
                        <button class="btn btn-outline-info btn-sm" onclick="showGradeHistory()">
                            <i class="fas fa-chart-line"></i> Progress
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Grade History Modal (untuk Fase 2) --}}
<div class="modal fade" id="gradeHistoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">📈 Riwayat Nilai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6>Nilai Terbaru</h6>
                            <p class="mb-1">
                                <span class="badge bg-primary fs-6">{{ $userTugas->nilai }}</span>
                                @if($userTugas->komentar)
                                <br><small class="text-muted">{{ Str::limit($userTugas->komentar, 100) }}</small>
                                @endif
                            </p>
                            <small class="text-muted">
                                {{ $userTugas->dinilai_pada ? $userTugas->dinilai_pada->format('d M Y H:i') : '-' }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function printFeedback() {
    const printContent = `
        <div style="font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px;">
            <h2 style="color: #007bff; text-align: center;">Hasil Penilaian Tugas</h2>
            <hr>
            <div style="margin-bottom: 20px;">
                <h3>{{ $tugas->name ?? 'Tugas' }}</h3>
                <p><strong>Siswa:</strong> {{ auth()->user()->name }}</p>
                <p><strong>Tanggal Penilaian:</strong> {{ $userTugas->dinilai_pada ? $userTugas->dinilai_pada->format('d M Y H:i') : '-' }}</p>
            </div>
            
            <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                <h4 style="color: #007bff;">Nilai: {{ $userTugas->nilai }}/100</h4>
                <p><strong>Grade:</strong> {{ $userTugas->getGrade() }}</p>
            </div>
            
            @if($userTugas->komentar)
            <div style="background-color: #e3f2fd; padding: 20px; border-radius: 8px;">
                <h4 style="color: #1976d2;">Feedback dari Guru:</h4>
                <p style="white-space: pre-line;">{{ $userTugas->komentar }}</p>
            </div>
            @endif
            
            <div style="margin-top: 30px; text-align: center; color: #666;">
                <p>Dicetak pada: ${new Date().toLocaleString('id-ID')}</p>
            </div>
        </div>
    `;
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.print();
}

function shareFeedback() {
    if (navigator.share) {
        navigator.share({
            title: 'Hasil Penilaian Tugas',
            text: `Nilai: {{ $userTugas->nilai }}/100 - {{ $userTugas->komentar ? Str::limit($userTugas->komentar, 100) : 'Tugas telah dinilai' }}`,
            url: window.location.href
        });
    } else {
        // Fallback untuk browser yang tidak support Web Share API
        const text = `Nilai: {{ $userTugas->nilai }}/100\nFeedback: {{ $userTugas->komentar ? Str::limit($userTugas->komentar, 200) : 'Tugas telah dinilai' }}`;
        navigator.clipboard.writeText(text).then(() => {
            alert('Feedback berhasil disalin ke clipboard!');
        });
    }
}

function showGradeHistory() {
    const modal = new bootstrap.Modal(document.getElementById('gradeHistoryModal'));
    modal.show();
}

// Auto-refresh jika ada notifikasi baru
setInterval(() => {
    // Check for new feedback (implementasi sederhana)
    fetch('/api/check-new-feedback/{{ $userTugas->id }}')
        .then(response => response.json())
        .then(data => {
            if (data.hasNewFeedback) {
                location.reload();
            }
        })
        .catch(error => console.log('No new feedback'));
}, 30000); // Check every 30 seconds
</script>

<style>
.grade-display {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.feedback-box {
    border-left: 4px solid #007bff;
    background-color: #f8f9fa;
}

.feedback-content {
    font-size: 16px;
    line-height: 1.6;
    white-space: pre-line;
}

.grading-info {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-content {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
}

@media (max-width: 768px) {
    .grade-display {
        margin-bottom: 20px;
    }
    
    .feedback-content {
        font-size: 14px;
    }
    
    .grading-info .row > div {
        margin-bottom: 10px;
    }
}
</style>
@else
<div class="alert alert-info mt-4">
    <i class="fas fa-info-circle"></i> 
    Tugas belum dinilai oleh guru. Silakan tunggu feedback dari guru.
</div>
@endif
