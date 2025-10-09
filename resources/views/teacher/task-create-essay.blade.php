@extends('layouts.unified-layout-new')

@section('title', 'Buat Tugas Esai')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">Buat Tugas Esai</h1>
                <p class="mt-2 text-gray-300">Buat tugas esai yang memungkinkan siswa menulis jawaban panjang</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('teacher.tasks.management') }}" class="btn btn-outline">
                    <i class="ph-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <form id="essayForm" method="POST" action="{{ route('teacher.tasks.store') }}">
        @csrf
        <input type="hidden" name="tipe" value="2">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2">
                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Dasar</h3>
                        </div>
                        <div class="card-body space-y-4">
                            <div>
                                <label class="form-label">Judul Tugas *</label>
                                <input type="text" name="name" class="form-input" 
                                       value="{{ old('name') }}" required
                                       placeholder="Contoh: Analisis Puisi 'Aku' karya Chairil Anwar">
                                @error('name')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Deskripsi/Instruksi *</label>
                                @include('components.rich-text-editor', [
                                    'name' => 'content',
                                    'content' => old('content'),
                                    'placeholder' => 'Tuliskan instruksi yang jelas untuk siswa...',
                                    'height' => '250px',
                                    'required' => true
                                ])
                                @error('content')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label">Kelas Tujuan *</label>
                                    <select name="kelas_id" class="form-select" required>
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

                                <div>
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
                            </div>

                            <div>
                                <label class="form-label">Tanggal Tenggat</label>
                                <input type="datetime-local" name="due" class="form-input" 
                                       value="{{ old('due') }}">
                                @error('due')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Essay Configuration -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Konfigurasi Tugas Esai</h3>
                        </div>
                        <div class="card-body space-y-6">
                            <!-- Submission Options -->
                            <div>
                                <h4 class="text-lg font-medium text-white mb-4">Opsi Pengumpulan</h4>
                                <div class="space-y-4">
                                    <div class="flex items-center space-x-4">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="allow_text_input" value="1" 
                                                   {{ old('allow_text_input', true) ? 'checked' : '' }} 
                                                   class="form-checkbox">
                                            <span class="ml-2 text-white">Izinkan ketik langsung</span>
                                        </label>
                                    </div>

                                    <div class="flex items-center space-x-4">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="allow_file_upload" value="1" 
                                                   {{ old('allow_file_upload') ? 'checked' : '' }} 
                                                   class="form-checkbox" onchange="toggleFileTypes()">
                                            <span class="ml-2 text-white">Izinkan upload file</span>
                                        </label>
                                    </div>

                                    <div id="fileTypesContainer" class="hidden">
                                        <label class="form-label">Jenis File yang Diizinkan</label>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach(['pdf', 'docx', 'txt', 'jpg', 'png'] as $type)
                                                <label class="flex items-center">
                                                    <input type="checkbox" name="file_types[]" value="{{ $type }}" 
                                                           {{ in_array($type, old('file_types', ['pdf', 'docx', 'txt'])) ? 'checked' : '' }}
                                                           class="form-checkbox">
                                                    <span class="ml-1 text-white">.{{ $type }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Essay Guidelines -->
                            <div>
                                <h4 class="text-lg font-medium text-white mb-4">Panduan Penulisan</h4>
                                <div class="space-y-4">
                                    <div>
                                        <label class="form-label">Panjang Minimum (kata)</label>
                                        <input type="number" name="min_words" class="form-input" 
                                               value="{{ old('min_words', 100) }}" min="1">
                                    </div>
                                    
                                    <div>
                                        <label class="form-label">Panjang Maksimum (kata)</label>
                                        <input type="number" name="max_words" class="form-input" 
                                               value="{{ old('max_words', 1000) }}" min="1">
                                    </div>
                                    
                                    <div>
                                        <label class="form-label">Format yang Diinginkan</label>
                                        <select name="format_requirement" class="form-select">
                                            <option value="free" {{ old('format_requirement') == 'free' ? 'selected' : '' }}>Bebas</option>
                                            <option value="paragraph" {{ old('format_requirement') == 'paragraph' ? 'selected' : '' }}>Paragraf</option>
                                            <option value="structured" {{ old('format_requirement') == 'structured' ? 'selected' : '' }}>Struktur (Pendahuluan, Isi, Penutup)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Rubric -->
                            <div>
                                <h4 class="text-lg font-medium text-white mb-4">Rubrik Penilaian</h4>
                                <div class="space-y-4">
                                    <div>
                                        <label class="form-label">Aspek Penilaian</label>
                                        <div class="space-y-3">
                                            <div class="flex items-center space-x-3">
                                                <input type="checkbox" name="rubric_content" value="1" 
                                                       {{ old('rubric_content', true) ? 'checked' : '' }} 
                                                       class="form-checkbox">
                                                <span class="text-white">Isi dan Substansi</span>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <input type="checkbox" name="rubric_organization" value="1" 
                                                       {{ old('rubric_organization', true) ? 'checked' : '' }} 
                                                       class="form-checkbox">
                                                <span class="text-white">Organisasi dan Struktur</span>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <input type="checkbox" name="rubric_language" value="1" 
                                                       {{ old('rubric_language', true) ? 'checked' : '' }} 
                                                       class="form-checkbox">
                                                <span class="text-white">Bahasa dan Gaya Penulisan</span>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <input type="checkbox" name="rubric_mechanics" value="1" 
                                                       {{ old('rubric_mechanics', true) ? 'checked' : '' }} 
                                                       class="form-checkbox">
                                                <span class="text-white">Ejaan dan Tanda Baca</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label">Bobot Penilaian</label>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="text-sm text-gray-400">Isi dan Substansi (%)</label>
                                                <input type="number" name="weight_content" class="form-input" 
                                                       value="{{ old('weight_content', 40) }}" min="0" max="100">
                                            </div>
                                            <div>
                                                <label class="text-sm text-gray-400">Organisasi (%)</label>
                                                <input type="number" name="weight_organization" class="form-input" 
                                                       value="{{ old('weight_organization', 25) }}" min="0" max="100">
                                            </div>
                                            <div>
                                                <label class="text-sm text-gray-400">Bahasa (%)</label>
                                                <input type="number" name="weight_language" class="form-input" 
                                                       value="{{ old('weight_language', 25) }}" min="0" max="100">
                                            </div>
                                            <div>
                                                <label class="text-sm text-gray-400">Ejaan (%)</label>
                                                <input type="number" name="weight_mechanics" class="form-input" 
                                                       value="{{ old('weight_mechanics', 10) }}" min="0" max="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="card sticky top-6">
                    <div class="card-header">
                        <h3 class="card-title">Aksi</h3>
                    </div>
                    <div class="card-body space-y-4">
                        <button type="submit" class="btn btn-primary w-full">
                            <i class="ph-check mr-2"></i>
                            Buat Tugas
                        </button>
                        
                        <a href="{{ route('teacher.tasks.management') }}" class="btn btn-outline w-full">
                            <i class="ph-x mr-2"></i>
                            Batal
                        </a>

                        <div class="border-t border-gray-600 pt-4">
                            <h4 class="text-sm font-medium text-gray-300 mb-2">Tips:</h4>
                            <ul class="text-xs text-gray-400 space-y-1">
                                <li>• Berikan instruksi yang detail dan jelas</li>
                                <li>• Tentukan format yang diinginkan</li>
                                <li>• Berikan contoh jika diperlukan</li>
                                <li>• Tentukan panjang minimum dan maksimum</li>
                                <li>• Buat rubrik penilaian yang objektif</li>
                            </ul>
                        </div>

                        <div class="border-t border-gray-600 pt-4">
                            <h4 class="text-sm font-medium text-gray-300 mb-2">Fitur:</h4>
                            <ul class="text-xs text-gray-400 space-y-1">
                                <li>• Penilaian manual oleh guru</li>
                                <li>• Feedback terperinci</li>
                                <li>• Upload file pendukung</li>
                                <li>• Rubrik penilaian otomatis</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(255, 255, 255, 0.02);
}

.card-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: white;
    margin: 0;
}

.card-body {
    padding: 1.5rem;
}

.form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: white;
    margin-bottom: 0.5rem;
}

.form-input, .form-select, .form-textarea {
    width: 100%;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    color: white;
    font-size: 0.875rem;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-checkbox {
    width: 1rem;
    height: 1rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 4px;
}
</style>

<script>
function toggleFileTypes() {
    const fileUploadCheckbox = document.querySelector('input[name="allow_file_upload"]');
    const fileTypesContainer = document.getElementById('fileTypesContainer');
    
    if (fileUploadCheckbox.checked) {
        fileTypesContainer.classList.remove('hidden');
    } else {
        fileTypesContainer.classList.add('hidden');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleFileTypes();
});
</script>
@endsection
