@extends('layouts.unified-layout')

@section('title', 'Buat Tugas Mandiri')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">Buat Tugas Mandiri</h1>
                <p class="mt-2 text-gray-300">Buat tugas individual dengan opsi upload file atau ketik langsung</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('teacher.tasks') }}" class="btn btn-outline">
                    <i class="ph-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <form id="individualForm" method="POST" action="{{ route('teacher.tasks.store') }}">
        @csrf
        <input type="hidden" name="tipe" value="3">
        <!-- Hidden field untuk kelas_mapel_id yang akan diisi oleh JavaScript -->
        <input type="hidden" name="kelas_mapel_id" id="kelas_mapel_id" value="">

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
                                       placeholder="Contoh: Laporan Praktikum Kimia">
                                @error('name')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Deskripsi/Instruksi *</label>
                                @include('components.modern-quill-editor', [
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

                    <!-- Individual Task Configuration -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Konfigurasi Tugas Mandiri</h3>
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
                                                   {{ old('allow_file_upload', true) ? 'checked' : '' }} 
                                                   class="form-checkbox" onchange="toggleFileTypes()">
                                            <span class="ml-2 text-white">Izinkan upload file</span>
                                        </label>
                                    </div>

                                    <div id="fileTypesContainer">
                                        <label class="form-label">Jenis File yang Diizinkan</label>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach(['pdf', 'docx', 'txt', 'jpg', 'png', 'xlsx', 'pptx'] as $type)
                                                <label class="flex items-center">
                                                    <input type="checkbox" name="file_types[]" value="{{ $type }}" 
                                                           {{ in_array($type, old('file_types', ['pdf', 'docx', 'jpg', 'png'])) ? 'checked' : '' }}
                                                           class="form-checkbox">
                                                    <span class="ml-1 text-white">.{{ $type }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Task Requirements -->
                            <div>
                                <h4 class="text-lg font-medium text-white mb-4">Persyaratan Tugas</h4>
                                <div class="space-y-4">
                                    <div>
                                        <label class="form-label">Jenis Tugas</label>
                                        <select name="task_type" class="form-select">
                                            <option value="report" {{ old('task_type') == 'report' ? 'selected' : '' }}>Laporan</option>
                                            <option value="presentation" {{ old('task_type') == 'presentation' ? 'selected' : '' }}>Presentasi</option>
                                            <option value="project" {{ old('task_type') == 'project' ? 'selected' : '' }}>Proyek</option>
                                            <option value="research" {{ old('task_type') == 'research' ? 'selected' : '' }}>Penelitian</option>
                                            <option value="creative" {{ old('task_type') == 'creative' ? 'selected' : '' }}>Karya Kreatif</option>
                                            <option value="other" {{ old('task_type') == 'other' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label">Format yang Diinginkan</label>
                                        <textarea name="format_requirements" class="form-textarea" rows="3" 
                                                  placeholder="Jelaskan format yang diinginkan (struktur, gaya penulisan, dll)">{{ old('format_requirements') }}</textarea>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="form-label">Jumlah File Maksimum</label>
                                            <input type="number" name="max_files" class="form-input" 
                                                   value="{{ old('max_files', 5) }}" min="1" max="20">
                                        </div>
                                        <div>
                                            <label class="form-label">Ukuran File Maksimum (MB)</label>
                                            <input type="number" name="max_file_size" class="form-input" 
                                                   value="{{ old('max_file_size', 10) }}" min="1" max="100">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Assessment Criteria -->
                            <div>
                                <h4 class="text-lg font-medium text-white mb-4">Kriteria Penilaian</h4>
                                <div class="space-y-4">
                                    <div>
                                        <label class="form-label">Aspek Penilaian</label>
                                        <div class="space-y-3">
                                            <div class="flex items-center space-x-3">
                                                <input type="checkbox" name="criteria_content" value="1" 
                                                       {{ old('criteria_content', true) ? 'checked' : '' }} 
                                                       class="form-checkbox">
                                                <span class="text-white">Konten dan Substansi</span>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <input type="checkbox" name="criteria_creativity" value="1" 
                                                       {{ old('criteria_creativity', true) ? 'checked' : '' }} 
                                                       class="form-checkbox">
                                                <span class="text-white">Kreativitas dan Inovasi</span>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <input type="checkbox" name="criteria_technical" value="1" 
                                                       {{ old('criteria_technical', true) ? 'checked' : '' }} 
                                                       class="form-checkbox">
                                                <span class="text-white">Aspek Teknis</span>
                                            </div>
                                            <div class="flex items-center space-x-3">
                                                <input type="checkbox" name="criteria_presentation" value="1" 
                                                       {{ old('criteria_presentation', true) ? 'checked' : '' }} 
                                                       class="form-checkbox">
                                                <span class="text-white">Presentasi dan Format</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label">Bobot Penilaian</label>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="text-sm text-gray-400">Konten (%)</label>
                                                <input type="number" name="weight_content" class="form-input" 
                                                       value="{{ old('weight_content', 40) }}" min="0" max="100">
                                            </div>
                                            <div>
                                                <label class="text-sm text-gray-400">Kreativitas (%)</label>
                                                <input type="number" name="weight_creativity" class="form-input" 
                                                       value="{{ old('weight_creativity', 30) }}" min="0" max="100">
                                            </div>
                                            <div>
                                                <label class="text-sm text-gray-400">Teknis (%)</label>
                                                <input type="number" name="weight_technical" class="form-input" 
                                                       value="{{ old('weight_technical', 20) }}" min="0" max="100">
                                            </div>
                                            <div>
                                                <label class="text-sm text-gray-400">Presentasi (%)</label>
                                                <input type="number" name="weight_presentation" class="form-input" 
                                                       value="{{ old('weight_presentation', 10) }}" min="0" max="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Resources -->
                            <div>
                                <h4 class="text-lg font-medium text-white mb-4">Sumber Daya Tambahan</h4>
                                <div class="space-y-4">
                                    <div>
                                        <label class="form-label">File Pendukung (Opsional)</label>
                                        <input type="file" name="supporting_files[]" multiple 
                                               accept=".pdf,.docx,.txt,.jpg,.png" class="form-input">
                                        <p class="text-xs text-gray-400 mt-1">Upload file template, contoh, atau referensi</p>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label">Link Referensi</label>
                                        <textarea name="reference_links" class="form-textarea" rows="3" 
                                                  placeholder="Masukkan link referensi yang berguna untuk siswa (satu per baris)">{{ old('reference_links') }}</textarea>
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
                        
                        <a href="{{ route('teacher.tasks') }}" class="btn btn-outline w-full">
                            <i class="ph-x mr-2"></i>
                            Batal
                        </a>

                        <div class="border-t border-gray-600 pt-4">
                            <h4 class="text-sm font-medium text-gray-300 mb-2">Tips:</h4>
                            <ul class="text-xs text-gray-400 space-y-1">
                                <li>• Berikan instruksi yang detail dan jelas</li>
                                <li>• Tentukan format yang diinginkan</li>
                                <li>• Berikan contoh jika diperlukan</li>
                                <li>• Sediakan sumber daya pendukung</li>
                                <li>• Buat rubrik penilaian yang objektif</li>
                            </ul>
                        </div>

                        <div class="border-t border-gray-600 pt-4">
                            <h4 class="text-sm font-medium text-gray-300 mb-2">Fitur:</h4>
                            <ul class="text-xs text-gray-400 space-y-1">
                                <li>• Upload file multiple</li>
                                <li>• Text input langsung</li>
                                <li>• Penilaian fleksibel</li>
                                <li>• Feedback terperinci</li>
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
    
    // Handle form submission - gabungkan kelas_id dan mapel_id menjadi kelas_mapel_id
    const form = document.getElementById('individualForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const kelasId = document.getElementById('kelas_id').value;
            const mapelId = document.getElementById('mapel_id').value;
            
            // Validasi bahwa kedua dropdown sudah dipilih
            if (!kelasId || !mapelId) {
                e.preventDefault();
                alert('Kelas Tujuan dan Mata Pelajaran wajib dipilih!');
                return false;
            }
            
            // Gabungkan kelas_id dan mapel_id menjadi kelas_mapel_id
            // Format: "kelas_id:mapel_id" untuk sementara, nanti controller akan handle
            const kelasMapelId = `${kelasId}:${mapelId}`;
            document.getElementById('kelas_mapel_id').value = kelasMapelId;
            
            console.log('Form submit (Individual) - Kelas ID:', kelasId, 'Mapel ID:', mapelId, 'Kombinasi:', kelasMapelId);
        });
    }
});
</script>

<!-- Quill Editor CSS -->
<style>
/* Quill Editor Styles - From material-create.blade.php */
.ql-editor {
    color: #ffffff;
    background: #2a2a3e;
    min-height: 200px;
}

.ql-toolbar {
    background: #1e293b;
    border: 1px solid #334155;
    border-bottom: none;
}

.ql-container {
    border: 1px solid #334155;
    border-top: none;
}

.ql-snow .ql-picker {
    color: #ffffff;
}

.ql-snow .ql-stroke {
    stroke: #ffffff;
}

.ql-snow .ql-fill {
    fill: #ffffff;
}

.quill-editor-dark .ql-editor {
    color: #ffffff;
    background: #2a2a3e;
    min-height: 120px;
}

.quill-editor-dark .ql-toolbar {
    background: #1e293b;
    border: 1px solid #334155;
    border-bottom: none;
}

.quill-editor-dark .ql-container {
    border: 1px solid #334155;
    border-top: none;
}

.quill-editor-dark .ql-snow .ql-picker {
    color: #ffffff;
}

.quill-editor-dark .ql-snow .ql-stroke {
    stroke: #ffffff;
}

.quill-editor-dark .ql-snow .ql-fill {
    fill: #ffffff;
}
</style>
@endsection
