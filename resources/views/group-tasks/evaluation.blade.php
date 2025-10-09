@extends('layouts.app')

@section('title', 'Penilaian Kelompok')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-star mr-2"></i>
                        Penilaian Kelompok - {{ $groupTask->title }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle mr-2"></i>Panduan Penilaian</h6>
                        <ul class="mb-0">
                            <li><strong>Kurang Baik (1 poin):</strong> Kontribusi sangat minim, tidak aktif dalam kelompok</li>
                            <li><strong>Cukup Baik (2 poin):</strong> Kontribusi terbatas, kadang aktif dalam kelompok</li>
                            <li><strong>Baik (3 poin):</strong> Kontribusi baik, aktif dalam kelompok</li>
                            <li><strong>Sangat Baik (4 poin):</strong> Kontribusi sangat baik, sangat aktif dan membantu</li>
                        </ul>
                    </div>
                    
                    <form action="{{ route('group-tasks.submit-evaluation', $groupTask) }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            @foreach($members as $member)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="text-center mb-3">
                                                <i class="fas fa-user fa-3x text-primary mb-2"></i>
                                                <h5 class="card-title">{{ $member->student->name }}</h5>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Penilaian</label>
                                                <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                                    <label class="btn btn-outline-danger {{ $existingEvaluations->get($member->student_id)?->rating === 'kurang_baik' ? 'active' : '' }}">
                                                        <input type="radio" name="evaluations[{{ $member->student_id }}][rating]" 
                                                               value="kurang_baik" 
                                                               {{ $existingEvaluations->get($member->student_id)?->rating === 'kurang_baik' ? 'checked' : '' }}>
                                                        Kurang Baik
                                                    </label>
                                                    <label class="btn btn-outline-warning {{ $existingEvaluations->get($member->student_id)?->rating === 'cukup_baik' ? 'active' : '' }}">
                                                        <input type="radio" name="evaluations[{{ $member->student_id }}][rating]" 
                                                               value="cukup_baik" 
                                                               {{ $existingEvaluations->get($member->student_id)?->rating === 'cukup_baik' ? 'checked' : '' }}>
                                                        Cukup Baik
                                                    </label>
                                                    <label class="btn btn-outline-info {{ $existingEvaluations->get($member->student_id)?->rating === 'baik' ? 'active' : '' }}">
                                                        <input type="radio" name="evaluations[{{ $member->student_id }}][rating]" 
                                                               value="baik" 
                                                               {{ $existingEvaluations->get($member->student_id)?->rating === 'baik' ? 'checked' : '' }}>
                                                        Baik
                                                    </label>
                                                    <label class="btn btn-outline-success {{ $existingEvaluations->get($member->student_id)?->rating === 'sangat_baik' ? 'active' : '' }}">
                                                        <input type="radio" name="evaluations[{{ $member->student_id }}][rating]" 
                                                               value="sangat_baik" 
                                                               {{ $existingEvaluations->get($member->student_id)?->rating === 'sangat_baik' ? 'checked' : '' }}>
                                                        Sangat Baik
                                                    </label>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label>Komentar (Opsional)</label>
                                                <textarea class="form-control" 
                                                          name="evaluations[{{ $member->student_id }}][comment]" 
                                                          rows="3" 
                                                          placeholder="Berikan komentar tentang kontribusi anggota...">{{ $existingEvaluations->get($member->student_id)?->comment }}</textarea>
                                            </div>
                                            
                                            <input type="hidden" name="evaluations[{{ $member->student_id }}][student_id]" value="{{ $member->student_id }}">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($members->count() > 0)
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save mr-2"></i>
                                    Simpan Penilaian
                                </button>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Tidak ada anggota untuk dinilai</h5>
                                <p class="text-muted">Kelompok ini belum memiliki anggota selain ketua.</p>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add visual feedback for rating selection
    const ratingButtons = document.querySelectorAll('input[type="radio"]');
    
    ratingButtons.forEach(button => {
        button.addEventListener('change', function() {
            const label = this.closest('label');
            const group = this.closest('.btn-group');
            
            // Remove active class from all labels in this group
            group.querySelectorAll('label').forEach(l => l.classList.remove('active'));
            
            // Add active class to selected label
            if (this.checked) {
                label.classList.add('active');
            }
        });
    });
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const requiredRadios = document.querySelectorAll('input[type="radio"]');
        let allSelected = true;
        
        // Group radio buttons by name
        const radioGroups = {};
        requiredRadios.forEach(radio => {
            if (!radioGroups[radio.name]) {
                radioGroups[radio.name] = [];
            }
            radioGroups[radio.name].push(radio);
        });
        
        // Check if at least one radio in each group is selected
        Object.values(radioGroups).forEach(group => {
            if (!group.some(radio => radio.checked)) {
                allSelected = false;
            }
        });
        
        if (!allSelected) {
            e.preventDefault();
            alert('Silakan pilih penilaian untuk semua anggota kelompok.');
        }
    });
});
</script>
@endsection
