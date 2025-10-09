@extends('layouts.unified-layout-new')

@section('title', 'Terra Assessment - {{ $title }}')

@section('styles')
<style>
.task-form {
            background-color: #1e293b;
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #334155;
        }

        .form-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #2a2a3e;
            border-radius: 12px;
            border: 1px solid #334155;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title i {
            color: #667eea;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #ffffff;
            font-size: 0.9rem;
        }
        
        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #1e293b;
            border: 2px solid #334155;
            border-radius: 8px;
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: #667eea;
            background: #2a2a3e;
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .group-block, .rubric-item {
            background: #1e293b;
            border: 2px solid #334155;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .group-header, .rubric-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .group-title, .rubric-title {
            font-weight: 600;
            color: #667eea;
            font-size: 1.1rem;
        }

        .remove-group, .remove-rubric {
            background: #e53e3e;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .remove-group:hover, .remove-rubric:hover {
            background: #c53030;
        }

        .students-list {
            max-height: 200px;
            overflow-y: auto;
            border: 2px solid #334155;
            border-radius: 8px;
            padding: 0.5rem;
            background: #1e293b;
        }

        .student-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .student-item:hover {
            background: #2a2a3e;
        }

        .student-item.selected {
            background: rgba(102, 126, 234, 0.2);
            border: 1px solid rgba(102, 126, 234, 0.3);
        }

        .group-members {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .member-tag {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            background: rgba(102, 126, 234, 0.2);
            border: 1px solid rgba(102, 126, 234, 0.3);
            border-radius: 4px;
            font-size: 0.75rem;
            color: #667eea;
        }

        .leader-tag {
            background: rgba(245, 158, 11, 0.2);
            border-color: rgba(245, 158, 11, 0.3);
            color: #f59e0b;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5a67d8;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #475569;
            color: #ffffff;
        }

        .btn-secondary:hover {
            background: #334155;
        }

        .btn-success {
            background: #48bb78;
            color: white;
        }

        .btn-success:hover {
            background: #38a169;
        }

        .btn-danger {
            background: #e53e3e;
            color: white;
        }

        .btn-danger:hover {
            background: #c53030;
        }

        .btn-outline {
            background: transparent;
            color: #667eea;
            border: 1px solid #667eea;
        }

        .btn-outline:hover {
            background: #667eea;
            color: white;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .loading {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .tips {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .tips h4 {
            color: #ffffff;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .tips h4 i {
            color: #667eea;
        }

        .tips ul {
            color: #94a3b8;
            padding-left: 1.5rem;
        }

        .tips li {
            margin-bottom: 0.25rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-title i {
            color: #667eea;
        }

        .page-description {
            color: #94a3b8;
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
</style>
@endsection

@section('content')
<div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-users"></i>
                {{ $title }}
            </h1>
            <p class="page-description">Buat tugas kolaboratif dengan sistem penilaian antar-rekan</p>
        </div>

        <div class="task-form">
        <form id="groupForm" method="POST" action="{{ route('teacher.tasks.store') }}">
            @csrf
            <input type="hidden" name="tipe" value="4">

            <!-- Basic Information -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Informasi Dasar
                </h3>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Judul Tugas *</label>
                        <input type="text" name="name" class="form-input" 
                               value="{{ old('name') }}" required
                               placeholder="Contoh: Proyek Penelitian Lingkungan">
                        @error('name')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kelas Tujuan *</label>
                        <select name="kelas_id" class="form-select" required onchange="loadStudents()">
                            <option value="">Pilih Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->name }} - {{ $k->level }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mata Pelajaran *</label>
                        <select name="mapel_id" class="form-select" required>
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach($mapel as $m)
                                <option value="{{ $m->id }}" {{ old('mapel_id') == $m->id ? 'selected' : '' }}>
                                    {{ $m->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('mapel_id')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal Tenggat Tugas</label>
                        <input type="datetime-local" name="due" class="form-input" 
                               value="{{ old('due') }}">
                        @error('due')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Tanggal Tenggat Penilaian Antar Kelompok</label>
                        <input type="datetime-local" name="peer_assessment_due" class="form-input" 
                               value="{{ old('peer_assessment_due') }}">
                        @error('peer_assessment_due')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Deskripsi/Instruksi *</label>
                    <textarea name="content" class="form-textarea" rows="6" 
                              placeholder="Tuliskan instruksi yang jelas untuk kelompok..." required>{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Group Formation -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-users"></i>
                    Pembentukan Kelompok
                </h3>
                
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-medium text-white">Kelompok</h4>
                    <button type="button" onclick="addGroup()" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i>
                        Tambah Kelompok
                    </button>
                </div>
                
                <div id="groupsContainer">
                    <!-- Groups will be added here dynamically -->
                </div>
            </div>

            <!-- Peer Assessment Rubric -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-clipboard-check"></i>
                    Rubrik Penilaian Antar Kelompok
                </h3>
                
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-medium text-white">Item Penilaian</h4>
                    <button type="button" onclick="addRubricItem()" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i>
                        Tambah Item
                    </button>
                </div>
                
                <div id="rubricContainer">
                    <!-- Rubric items will be added here dynamically -->
                </div>
            </div>

            <!-- Tips -->
            <div class="tips">
                <h4><i class="fas fa-lightbulb"></i> Tips Membuat Tugas Kelompok</h4>
                <ul>
                    <li>Bagi kelompok secara merata</li>
                    <li>Tentukan ketua kelompok yang bertanggung jawab</li>
                    <li>Buat rubrik penilaian yang objektif</li>
                    <li>Berikan instruksi yang jelas</li>
                    <li>Atur tenggat waktu yang realistis</li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button type="button" class="btn btn-secondary" onclick="history.back()">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
                <button type="button" class="btn btn-danger" onclick="clearForm()">
                    <i class="fas fa-trash"></i> Hapus Semua
                </button>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan Tugas
                </button>
            </div>
        </form>
        </div>
@endsection
