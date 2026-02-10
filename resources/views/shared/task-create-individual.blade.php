@extends('layouts.unified-layout')

@section('title', 'Buat Tugas Mandiri')

@section('content')
@php
    $user = Auth::user();
    $isTeacher = $user->roles_id == 3;
    $isSuperadmin = $user->roles_id == 1;
    $isAdmin = $user->roles_id == 2;
    
    // Determine form action and back route based on user role
    if ($isTeacher) {
        $formAction = route('teacher.tasks.store');
        $backRoute = route('teacher.tasks');
    } elseif ($isAdmin) {
        $formAction = route('admin.tugas.store');
        $backRoute = route('admin.tugas.index');
    } else {
        $formAction = route('superadmin.tasks.store');
        $backRoute = route('superadmin.tugas.index');
    }
@endphp

<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden border border-gray-700">
            <div class="bg-gradient-to-r from-orange-600 to-yellow-600 px-6 py-4">
                <h1 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-user mr-3"></i>
                    Buat Tugas Mandiri
                </h1>
                <p class="text-orange-100 mt-1">Buat tugas individual untuk pembelajaran mandiri</p>
                <div class="mt-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-500 text-white">
                        <i class="fas fa-tag mr-1"></i>
                        Tipe: Mandiri
                    </span>
                </div>
            </div>
            
            <div class="p-6 bg-gray-800">
                <form id="individualForm" method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
                    @csrf
                    <!-- Hidden input untuk tipe tugas -->
                    <input type="hidden" name="tipe" value="3">
                    <!-- Hidden field untuk kelas_mapel_id yang akan diisi oleh JavaScript -->
                    <input type="hidden" name="kelas_mapel_id" id="kelas_mapel_id" value="">
                    
                    {{-- Success/Error Messages --}}
                    @if(session('success'))
                    <div class="alert alert-success" style="background: #10b981; color: white; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <strong>✓ Berhasil!</strong> {{ session('success') }}
                    </div>
                    @endif
                    @if(session('error'))
                    <div class="alert alert-danger" style="background: #ef4444; color: white; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <strong>✗ Error!</strong> {{ session('error') }}
                    </div>
                    @endif
                    @if($errors->any())
                    <div class="alert alert-danger" style="background: #ef4444; color: white; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <strong>✗ Validasi Gagal!</strong>
                        <ul style="margin: 0.5rem 0 0 1.5rem;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <!-- Kelas dan Mata Pelajaran -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6">
                        <div>
                            <label for="kelas_id" class="block text-sm font-medium text-gray-300 mb-2">
                                Kelas Tujuan <span class="text-red-400">*</span>
                            </label>
                            <select class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 @error('kelas_id') border-red-500 @enderror" 
                                    id="kelas_id" name="kelas_id" required>
                                <option value="">Pilih Kelas</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->name }} - {{ $k->level }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="mapel_id" class="block text-sm font-medium text-gray-300 mb-2">
                                Mata Pelajaran <span class="text-red-400">*</span>
                            </label>
                            <select class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 @error('mapel_id') border-red-500 @enderror" 
                                    id="mapel_id" name="mapel_id" required>
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach($mapel as $m)
                                    <option value="{{ $m->id }}" {{ old('mapel_id') == $m->id ? 'selected' : '' }}>
                                        {{ $m->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mapel_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Informasi Tugas -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                                Judul Tugas <span class="text-red-400">*</span>
                            </label>
                            <input type="text" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 @error('name') border-red-500 @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan judul tugas mandiri" required>
                            @error('name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                Tipe Tugas
                            </label>
                            <div class="w-full px-4 py-3 bg-gray-600 border border-gray-500 text-gray-300 rounded-lg">
                                Mandiri
                            </div>
                        </div>
                    </div>

                    <!-- Waktu Tugas -->
                    <div class="grid grid-cols-1 gap-4 md:gap-6 mb-6">
                        <div>
                            <label for="due" class="block text-sm font-medium text-gray-300 mb-2">
                                Tanggal Tenggat <span class="text-red-400">*</span>
                            </label>
                            <input type="datetime-local" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition duration-200 @error('due') border-red-500 @enderror" 
                                   id="due" name="due" value="{{ old('due') }}" required>
                            @error('due')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Deskripsi Tugas dengan Quill Editor -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Deskripsi Tugas
                        </label>
                        <div class="bg-gray-700 rounded-lg border border-gray-600 overflow-hidden">
                            <div id="deskripsi-editor" class="quill-editor-dark" style="height: 200px;"></div>
                            <textarea name="content" id="deskripsi-textarea" style="display: none;">{{ old('content') }}</textarea>
                        </div>
                        @error('content')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Individual Task Section -->
                    <div id="individualSection" class="mb-6 bg-gray-700 rounded-lg p-6 border border-gray-600">
                        <div class="flex justify-between items-center mb-4">
                            <h5 class="text-lg font-semibold text-white flex items-center">
                                <i class="fas fa-user mr-2 text-orange-400"></i>
                                Tugas Mandiri
                            </h5>
                        </div>
                        
                        <!-- Pengaturan File Upload -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="allow_file_upload" value="1" {{ old('allow_file_upload') ? 'checked' : '' }} 
                                           class="rounded border-gray-600 text-orange-600 focus:ring-orange-500 focus:ring-offset-gray-800">
                                    <span class="ml-2 text-sm text-gray-300">Izinkan upload file</span>
                                </label>
                            </div>
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="allow_text_input" value="1" {{ old('allow_text_input', true) ? 'checked' : '' }} 
                                           class="rounded border-gray-600 text-orange-600 focus:ring-orange-500 focus:ring-offset-gray-800">
                                    <span class="ml-2 text-sm text-gray-300">Izinkan input teks</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Tipe File yang Diizinkan -->
                        <div id="fileTypesSection" class="mb-4" style="display: none;">
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                Tipe File yang Diizinkan
                            </label>
                            <div class="flex flex-wrap gap-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="file_types[]" value="pdf" {{ in_array('pdf', old('file_types', ['pdf', 'docx', 'jpg', 'png'])) ? 'checked' : '' }} 
                                           class="rounded border-gray-600 text-orange-600 focus:ring-orange-500">
                                    <span class="ml-2 text-sm text-gray-300">PDF</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="file_types[]" value="docx" {{ in_array('docx', old('file_types', ['pdf', 'docx', 'jpg', 'png'])) ? 'checked' : '' }} 
                                           class="rounded border-gray-600 text-orange-600 focus:ring-orange-500">
                                    <span class="ml-2 text-sm text-gray-300">DOCX</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="file_types[]" value="jpg" {{ in_array('jpg', old('file_types', ['pdf', 'docx', 'jpg', 'png'])) ? 'checked' : '' }} 
                                           class="rounded border-gray-600 text-orange-600 focus:ring-orange-500">
                                    <span class="ml-2 text-sm text-gray-300">JPG</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="file_types[]" value="png" {{ in_array('png', old('file_types', ['pdf', 'docx', 'jpg', 'png'])) ? 'checked' : '' }} 
                                           class="rounded border-gray-600 text-orange-600 focus:ring-orange-500">
                                    <span class="ml-2 text-sm text-gray-300">PNG</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="isHidden" value="1" {{ old('isHidden') ? 'checked' : '' }} 
                                       class="rounded border-gray-600 text-orange-600 focus:ring-orange-500 focus:ring-offset-gray-800">
                                <span class="ml-2 text-sm text-gray-300">Simpan sebagai draft (tidak langsung dipublikasikan)</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 mt-8">
                        <button type="button" onclick="history.back()" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </button>
                        <button type="submit" class="px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition duration-200">
                            <i class="fas fa-save mr-2"></i> Simpan Tugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Quill Editor JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Quill for description
        if (document.getElementById('deskripsi-editor')) {
            const deskripsiEditor = new Quill('#deskripsi-editor', {
                theme: 'snow',
                placeholder: 'Masukkan deskripsi tugas...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'image', 'video'],
                        ['clean']
                    ]
                }
            });
            deskripsiEditor.on('text-change', function() {
                document.getElementById('deskripsi-textarea').value = deskripsiEditor.root.innerHTML;
            });
            // Set initial content if old content exists
            if (document.getElementById('deskripsi-textarea').value) {
                deskripsiEditor.root.innerHTML = document.getElementById('deskripsi-textarea').value;
            }
        }
        
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
                const kelasMapelId = `${kelasId}:${mapelId}`;
                document.getElementById('kelas_mapel_id').value = kelasMapelId;
                
                console.log('Form submit (Individual) - Kelas ID:', kelasId, 'Mapel ID:', mapelId, 'Kombinasi:', kelasMapelId);
            });
        }
        
        // Toggle file types section
        const fileUploadCheckbox = document.querySelector('input[name="allow_file_upload"]');
        const fileTypesSection = document.getElementById('fileTypesSection');
        
        if (fileUploadCheckbox && fileTypesSection) {
            function toggleFileTypes() {
                if (fileUploadCheckbox.checked) {
                    fileTypesSection.style.display = 'block';
                } else {
                    fileTypesSection.style.display = 'none';
                }
            }
            
            fileUploadCheckbox.addEventListener('change', toggleFileTypes);
            toggleFileTypes(); // Initial call
        }
    });
</script>

<!-- Quill Editor CSS -->
<style>
/* Quill Editor Styles */
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