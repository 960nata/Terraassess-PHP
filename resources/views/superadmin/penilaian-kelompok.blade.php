@extends('layouts.unified-layout-new')

@section('title', $title)

@section('content')
<div class="superadmin-container">
    <!-- Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-info">
                <h1 class="page-title">
                    <i class="fas fa-star me-2"></i>Penilaian Kelompok
                </h1>
                <p class="page-description">{{ $tugas->name }} - {{ $tugas->KelasMapel->Kelas->name ?? 'N/A' }}</p>
            </div>
            <a href="{{ route('superadmin.tugas.show', $tugas->id) }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Kembali ke Detail Tugas
            </a>
        </div>
    </div>

    <!-- Instructions -->
    <div class="instructions-card">
        <div class="instructions-header">
            <i class="fas fa-info-circle"></i>
            <h3>Petunjuk Penilaian Kelompok</h3>
        </div>
        <div class="instructions-content">
            <p>Setiap kelompok akan menilai kelompok lain berdasarkan kriteria yang telah ditentukan. Penilaian ini akan mempengaruhi nilai akhir setiap kelompok.</p>
            <ul>
                <li>Berikan penilaian yang objektif dan adil</li>
                <li>Nilai berdasarkan kualitas kerja, bukan personal</li>
                <li>Gunakan skala 1-5 untuk setiap kriteria</li>
                <li>Berikan komentar yang konstruktif</li>
            </ul>
        </div>
    </div>

    <!-- Groups List -->
    <div class="groups-section">
        <h2 class="section-title">Daftar Kelompok</h2>
        
        @forelse($kelompok as $group)
        <div class="group-card">
            <div class="group-header">
                <div class="group-info">
                    <h3 class="group-name">{{ $group->name }}</h3>
                    <div class="group-members">
                        <i class="fas fa-users"></i>
                        <span>{{ $group->AnggotaTugasKelompok->count() }} anggota</span>
                    </div>
                </div>
                <div class="group-status">
                    @if($group->status == 1)
                        <span class="status-badge status-completed">Selesai</span>
                    @else
                        <span class="status-badge status-pending">Belum Selesai</span>
                    @endif
                </div>
            </div>
            
            <div class="group-members-list">
                <h4>Anggota Kelompok:</h4>
                <div class="members-grid">
                    @foreach($group->AnggotaTugasKelompok as $member)
                    <div class="member-item {{ $member->isKetua ? 'ketua' : '' }}">
                        <div class="member-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="member-info">
                            <div class="member-name">{{ $member->user->name }}</div>
                            @if($member->isKetua)
                                <div class="member-role">Ketua Kelompok</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="group-actions">
                <button class="btn-primary btn-evaluate" data-group-id="{{ $group->id }}" data-group-name="{{ $group->name }}">
                    <i class="fas fa-star"></i> Nilai Kelompok Ini
                </button>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-users"></i>
            <p>Belum ada kelompok yang dibentuk</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Evaluation Modal -->
<div id="evaluationModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Penilaian Kelompok</h3>
            <span class="close">&times;</span>
        </div>
        <form id="evaluationForm" method="POST" action="{{ route('superadmin.tugas.store-penilaian-kelompok') }}">
            @csrf
            <input type="hidden" name="tugas_id" value="{{ $tugas->id }}">
            <input type="hidden" name="tugas_kelompok_id" id="evaluated_group_id">
            <input type="hidden" name="penilai_kelompok_id" id="evaluator_group_id">
            
            <div class="modal-body">
                <div class="evaluation-info">
                    <h4>Kelompok yang Dinilai: <span id="evaluated_group_name"></span></h4>
                    <p>Pilih kelompok yang akan memberikan penilaian:</p>
                    <select id="evaluator_select" name="evaluator_group" required>
                        <option value="">Pilih Kelompok Penilai</option>
                        @foreach($kelompok as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="evaluation-criteria">
                    <h4>Kriteria Penilaian</h4>
                    
                    <div class="criteria-item">
                        <label for="nilai_kerjasama">Kerjasama Tim</label>
                        <div class="rating-input">
                            <input type="radio" name="nilai_kerjasama" value="1" id="kerjasama1">
                            <label for="kerjasama1">1</label>
                            <input type="radio" name="nilai_kerjasama" value="2" id="kerjasama2">
                            <label for="kerjasama2">2</label>
                            <input type="radio" name="nilai_kerjasama" value="3" id="kerjasama3">
                            <label for="kerjasama3">3</label>
                            <input type="radio" name="nilai_kerjasama" value="4" id="kerjasama4">
                            <label for="kerjasama4">4</label>
                            <input type="radio" name="nilai_kerjasama" value="5" id="kerjasama5">
                            <label for="kerjasama5">5</label>
                        </div>
                        <small>1 = Sangat Kurang, 5 = Sangat Baik</small>
                    </div>
                    
                    <div class="criteria-item">
                        <label for="nilai_kualitas">Kualitas Hasil</label>
                        <div class="rating-input">
                            <input type="radio" name="nilai_kualitas" value="1" id="kualitas1">
                            <label for="kualitas1">1</label>
                            <input type="radio" name="nilai_kualitas" value="2" id="kualitas2">
                            <label for="kualitas2">2</label>
                            <input type="radio" name="nilai_kualitas" value="3" id="kualitas3">
                            <label for="kualitas3">3</label>
                            <input type="radio" name="nilai_kualitas" value="4" id="kualitas4">
                            <label for="kualitas4">4</label>
                            <input type="radio" name="nilai_kualitas" value="5" id="kualitas5">
                            <label for="kualitas5">5</label>
                        </div>
                        <small>1 = Sangat Kurang, 5 = Sangat Baik</small>
                    </div>
                    
                    <div class="criteria-item">
                        <label for="nilai_presentasi">Presentasi</label>
                        <div class="rating-input">
                            <input type="radio" name="nilai_presentasi" value="1" id="presentasi1">
                            <label for="presentasi1">1</label>
                            <input type="radio" name="nilai_presentasi" value="2" id="presentasi2">
                            <label for="presentasi2">2</label>
                            <input type="radio" name="nilai_presentasi" value="3" id="presentasi3">
                            <label for="presentasi3">3</label>
                            <input type="radio" name="nilai_presentasi" value="4" id="presentasi4">
                            <label for="presentasi4">4</label>
                            <input type="radio" name="nilai_presentasi" value="5" id="presentasi5">
                            <label for="presentasi5">5</label>
                        </div>
                        <small>1 = Sangat Kurang, 5 = Sangat Baik</small>
                    </div>
                    
                    <div class="criteria-item">
                        <label for="nilai_inovasi">Inovasi & Kreativitas</label>
                        <div class="rating-input">
                            <input type="radio" name="nilai_inovasi" value="1" id="inovasi1">
                            <label for="inovasi1">1</label>
                            <input type="radio" name="nilai_inovasi" value="2" id="inovasi2">
                            <label for="inovasi2">2</label>
                            <input type="radio" name="nilai_inovasi" value="3" id="inovasi3">
                            <label for="inovasi3">3</label>
                            <input type="radio" name="nilai_inovasi" value="4" id="inovasi4">
                            <label for="inovasi4">4</label>
                            <input type="radio" name="nilai_inovasi" value="5" id="inovasi5">
                            <label for="inovasi5">5</label>
                        </div>
                        <small>1 = Sangat Kurang, 5 = Sangat Baik</small>
                    </div>
                </div>
                
                <div class="criteria-item">
                    <label for="komentar">Komentar (Opsional)</label>
                    <textarea id="komentar" name="komentar" rows="3" placeholder="Berikan komentar konstruktif untuk kelompok ini"></textarea>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeEvaluationModal()">Batal</button>
                <button type="submit" class="btn-primary">Simpan Penilaian</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Penilaian Kelompok Styles */
.superadmin-container {
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.page-header {
    margin-bottom: 2rem;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 0.5rem;
}

.page-description {
    color: #718096;
    font-size: 1.1rem;
}

.back-btn {
    background: #f7fafc;
    color: #4a5568;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    border: 1px solid #e2e8f0;
}

.back-btn:hover {
    background: #edf2f7;
    color: #2d3748;
}

/* Instructions Card */
.instructions-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 12px;
    margin-bottom: 2rem;
}

.instructions-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.instructions-header h3 {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
}

.instructions-content ul {
    margin: 1rem 0 0 1.5rem;
    padding: 0;
}

.instructions-content li {
    margin-bottom: 0.5rem;
}

/* Groups Section */
.groups-section {
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1a202c;
    margin-bottom: 1.5rem;
}

.group-card {
    background: rgba(30, 41, 59, 0.8);
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.group-header {
    padding: 1.5rem;
    background: #f7fafc;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #e2e8f0;
}

.group-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1a202c;
    margin: 0 0 0.5rem 0;
}

.group-members {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #718096;
    font-size: 0.9rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-completed {
    background: #d1fae5;
    color: #065f46;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.group-members-list {
    padding: 1.5rem;
}

.group-members-list h4 {
    color: #4a5568;
    margin-bottom: 1rem;
    font-size: 1rem;
}

.members-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}

.member-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: #f7fafc;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.member-item.ketua {
    background: #e6fffa;
    border-color: #10b981;
}

.member-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: #e2e8f0;
    color: #4a5568;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
}

.member-name {
    font-weight: 500;
    color: #1a202c;
    margin-bottom: 0.25rem;
}

.member-role {
    font-size: 0.8rem;
    color: #10b981;
    font-weight: 500;
}

.group-actions {
    padding: 1.5rem;
    background: #f7fafc;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
}

.btn-evaluate {
    background: #667eea;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border: none;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn-evaluate:hover {
    background: #5a67d8;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: rgba(30, 41, 59, 0.8);
    margin: 2% auto;
    padding: 0;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.modal-header {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: #1a202c;
}

.close {
    color: #718096;
    font-size: 1.5rem;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: #4a5568;
}

.modal-body {
    padding: 2rem;
}

.evaluation-info {
    margin-bottom: 2rem;
    padding: 1rem;
    background: #f7fafc;
    border-radius: 8px;
}

.evaluation-info h4 {
    color: #1a202c;
    margin-bottom: 0.5rem;
}

.evaluation-info select {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    margin-top: 0.5rem;
}

.evaluation-criteria {
    margin-bottom: 2rem;
}

.evaluation-criteria h4 {
    color: #1a202c;
    margin-bottom: 1rem;
}

.criteria-item {
    margin-bottom: 1.5rem;
}

.criteria-item label {
    display: block;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
}

.rating-input {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.rating-input input[type="radio"] {
    display: none;
}

.rating-input label {
    width: 40px;
    height: 40px;
    border: 2px solid #e2e8f0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
    color: #4a5568;
}

.rating-input input[type="radio"]:checked + label {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.rating-input label:hover {
    border-color: #667eea;
    color: #667eea;
}

.criteria-item small {
    color: #718096;
    font-size: 0.8rem;
}

.criteria-item textarea {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-family: inherit;
    resize: vertical;
}

.modal-footer {
    padding: 1.5rem 2rem;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
}

.btn-primary, .btn-secondary {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    font-size: 1rem;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-primary:hover {
    background: #5a67d8;
}

.btn-secondary {
    background: #e2e8f0;
    color: #4a5568;
}

.btn-secondary:hover {
    background: #cbd5e0;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem;
    color: #718096;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Responsive Design */
@media (max-width: 768px) {
    .superadmin-container {
        padding: 1rem;
    }
    
    .header-content {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .group-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .members-grid {
        grid-template-columns: 1fr;
    }
    
    .modal-content {
        width: 95%;
        margin: 5% auto;
    }
    
    .rating-input {
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const evaluationModal = document.getElementById('evaluationModal');
    const evaluateButtons = document.querySelectorAll('.btn-evaluate');
    const closeBtn = document.querySelector('.close');
    const evaluationForm = document.getElementById('evaluationForm');
    const evaluatedGroupId = document.getElementById('evaluated_group_id');
    const evaluatedGroupName = document.getElementById('evaluated_group_name');
    const evaluatorSelect = document.getElementById('evaluator_select');
    const evaluatorGroupId = document.getElementById('evaluator_group_id');
    
    evaluateButtons.forEach(button => {
        button.addEventListener('click', function() {
            const groupId = this.dataset.groupId;
            const groupName = this.dataset.groupName;
            
            evaluatedGroupId.value = groupId;
            evaluatedGroupName.textContent = groupName;
            evaluationModal.style.display = 'block';
        });
    });
    
    closeBtn.addEventListener('click', closeEvaluationModal);
    
    window.addEventListener('click', function(event) {
        if (event.target === evaluationModal) {
            closeEvaluationModal();
        }
    });
    
    evaluatorSelect.addEventListener('change', function() {
        evaluatorGroupId.value = this.value;
    });
    
    function closeEvaluationModal() {
        evaluationModal.style.display = 'none';
        evaluationForm.reset();
        evaluatorGroupId.value = '';
    }
});
</script>
@endsection
