@extends('layouts.unified-layout')

@section('title', 'Buat Tugas Kelompok')

@section('content')
@php
    $user = Auth::user();
    $isTeacher = $user->roles_id == 3;
    $isSuperadmin = $user->roles_id == 1;
    $isAdmin = $user->roles_id == 2;
    
    // Set userRole for JavaScript API calls
    if ($isSuperadmin) {
        $userRole = 'superadmin';
    } elseif ($isAdmin) {
        $userRole = 'admin';
    } elseif ($isTeacher) {
        $userRole = 'teacher';
    } else {
        $userRole = 'teacher'; // fallback
    }
    
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
            <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
                <h1 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-users mr-3"></i>
                    Buat Tugas Kelompok
                </h1>
                <p class="text-green-100 mt-1">Buat tugas kelompok untuk kolaborasi dan kerja tim</p>
                <div class="mt-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-500 text-white">
                        <i class="fas fa-tag mr-1"></i>
                        Tipe: Kelompok
                    </span>
                </div>
            </div>
            
            <div class="p-6 bg-gray-800">
                <form id="groupForm" method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
                    @csrf
                    <!-- Hidden input untuk tipe tugas -->
                    <input type="hidden" name="tipe" value="4">
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
                            <select class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 @error('kelas_id') border-red-500 @enderror" 
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
                            <select class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 @error('mapel_id') border-red-500 @enderror" 
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
                            <input type="text" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 @error('name') border-red-500 @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan judul tugas kelompok" required>
                            @error('name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                Tipe Tugas
                            </label>
                            <div class="w-full px-4 py-3 bg-gray-600 border border-gray-500 text-gray-300 rounded-lg">
                                Kelompok
                            </div>
                        </div>
                    </div>

                    <!-- Waktu Tugas -->
                    <div class="grid grid-cols-1 gap-4 md:gap-6 mb-6">
                        <div>
                            <label for="due" class="block text-sm font-medium text-gray-300 mb-2">
                                Tanggal Tenggat <span class="text-red-400">*</span>
                            </label>
                            <input type="datetime-local" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 @error('due') border-red-500 @enderror" 
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

                    <!-- Group Task Section -->
                    <div id="groupSection" class="mb-6 bg-gray-700 rounded-lg p-6 border border-gray-600">
                        <div class="flex justify-between items-center mb-4">
                            <h5 class="text-lg font-semibold text-white flex items-center">
                                <i class="fas fa-users mr-2 text-green-400"></i>
                                Pengaturan Kelompok
                            </h5>
                        </div>
                        
                        <!-- Pilih Kelompok Existing -->
                        <h6 class="text-md font-semibold text-white mb-3 flex items-center">
                            <i class="fas fa-list mr-2 text-blue-400"></i>
                            Opsi 1: Pilih Kelompok Existing
                        </h6>
                        <p class="text-sm text-gray-400 mb-3">Pilih kelompok yang sudah ada sebelumnya</p>
                        
                        <div class="mb-4">
                            <label for="existing_group_id" class="block text-sm font-medium text-gray-300 mb-2">
                                Pilih Kelompok Existing
                            </label>
                            <select class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 @error('existing_group_id') border-red-500 @enderror" 
                                    id="existing_group_id" name="existing_group_id">
                                <option value="">Pilih Kelompok Existing</option>
                                @if(isset($groups) && $groups->count() > 0)
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}" {{ old('existing_group_id') == $group->id ? 'selected' : '' }}>
                                            {{ $group->name }} ({{ $group->AnggotaTugasKelompok->count() }} anggota)
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('existing_group_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Divider OR -->
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-600"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 bg-gray-700 text-gray-300 font-medium">ATAU</span>
                            </div>
                        </div>

                        <h6 class="text-md font-semibold text-white mb-3 flex items-center">
                            <i class="fas fa-plus-circle mr-2 text-green-400"></i>
                            Opsi 2: Buat Kelompok Baru
                        </h6>
                        <p class="text-sm text-gray-400 mb-3">Buat satu atau lebih kelompok baru dengan memilih anggota dan ketua</p>
                        
                        <!-- Container untuk multiple groups -->
                        <div id="groups_container" class="space-y-4 mb-4">
                            <!-- Group forms akan di-generate oleh JavaScript -->
                        </div>
                        
                        <!-- Tombol Tambah Kelompok -->
                        <button type="button" onclick="addNewGroupForm()" 
                                class="w-full py-3 px-4 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-200 flex items-center justify-center">
                            <i class="fas fa-plus-circle mr-2"></i>
                            TAMBAH KELOMPOK
                        </button>
                        
                        <!-- Hidden input untuk flag backend -->
                        <input type="hidden" name="is_new_group" value="0">
                    </div>

                    <!-- Pengaturan Penilaian Kelompok -->
                    <div class="mb-6 bg-gray-700 rounded-lg p-6 border border-gray-600">
                        <h5 class="text-lg font-semibold text-white flex items-center mb-4">
                            <i class="fas fa-star mr-2 text-yellow-400"></i>
                            Pengaturan Penilaian Kelompok
                        </h5>
                        
                        <!-- Checkbox Enable Peer Assessment -->
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" id="enable_peer_assessment" name="enable_peer_assessment" value="1" {{ old('enable_peer_assessment') ? 'checked' : '' }} 
                                       class="rounded border-gray-600 text-green-600 focus:ring-green-500 focus:ring-offset-gray-800">
                                <span class="ml-2 text-sm text-gray-300 font-medium">Aktifkan Penilaian Antar Kelompok</span>
                            </label>
                        </div>
                        
                        <!-- Section Skala Penilaian (muncul jika checkbox dicentang) -->
                        <div id="assessment_scale_section" style="display: none;">
                            <!-- Deadline Peer Assessment -->
                            <div class="mb-4">
                                <label for="peer_assessment_due" class="block text-sm font-medium text-gray-300 mb-2">
                                    Deadline Penilaian Antar Kelompok <span class="text-red-400">*</span>
                                </label>
                                <input type="datetime-local" id="peer_assessment_due" name="peer_assessment_due" value="{{ old('peer_assessment_due') }}" 
                                       class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 @error('peer_assessment_due') border-red-500 @enderror">
                                <p class="mt-1 text-sm text-gray-400">Deadline untuk ketua kelompok menilai kelompok lain</p>
                                @error('peer_assessment_due')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <h6 class="text-md font-semibold text-white mb-3">Skala Penilaian</h6>
                            <p class="text-sm text-gray-400 mb-3">Setiap ketua kelompok akan menilai kelompok lain berdasarkan skala berikut:</p>
                            
                            <div id="scale_items_container" class="space-y-3">
                                <!-- Default 5 skala penilaian -->
                                <div class="scale-item bg-gray-800 p-4 rounded-lg border border-gray-600">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-white font-medium">Skala 1</span>
                                        <button type="button" onclick="removeScaleItem(this)" 
                                                class="px-2 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700 transition duration-200">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Label</label>
                                            <input type="text" name="scale_labels[]" value="Tidak" 
                                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Point</label>
                                            <input type="number" name="scale_points[]" value="0" min="0" 
                                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="scale-item bg-gray-800 p-4 rounded-lg border border-gray-600">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-white font-medium">Skala 2</span>
                                        <button type="button" onclick="removeScaleItem(this)" 
                                                class="px-2 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700 transition duration-200">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Label</label>
                                            <input type="text" name="scale_labels[]" value="Kurang Baik" 
                                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Point</label>
                                            <input type="number" name="scale_points[]" value="1" min="0" 
                                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="scale-item bg-gray-800 p-4 rounded-lg border border-gray-600">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-white font-medium">Skala 3</span>
                                        <button type="button" onclick="removeScaleItem(this)" 
                                                class="px-2 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700 transition duration-200">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Label</label>
                                            <input type="text" name="scale_labels[]" value="Cukup Baik" 
                                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Point</label>
                                            <input type="number" name="scale_points[]" value="2" min="0" 
                                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="scale-item bg-gray-800 p-4 rounded-lg border border-gray-600">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-white font-medium">Skala 4</span>
                                        <button type="button" onclick="removeScaleItem(this)" 
                                                class="px-2 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700 transition duration-200">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Label</label>
                                            <input type="text" name="scale_labels[]" value="Baik" 
                                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Point</label>
                                            <input type="number" name="scale_points[]" value="3" min="0" 
                                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="scale-item bg-gray-800 p-4 rounded-lg border border-gray-600">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-white font-medium">Skala 5</span>
                                        <button type="button" onclick="removeScaleItem(this)" 
                                                class="px-2 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700 transition duration-200">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Label</label>
                                            <input type="text" name="scale_labels[]" value="Sangat Baik" 
                                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Point</label>
                                            <input type="number" name="scale_points[]" value="4" min="0" 
                                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" onclick="addScaleItem()" class="mt-3 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200">
                                <i class="fas fa-plus mr-2"></i>Tambah Skala
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 mt-8">
                        <button type="button" onclick="history.back()" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </button>
                        <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200">
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
        
        
        // Load students when class is selected
        const kelasSelect = document.getElementById('kelas_id');
        if (kelasSelect) {
            kelasSelect.addEventListener('change', function() {
                const kelasId = this.value;
                if (kelasId) {
                    // Load students untuk semua group forms yang ada
                    const groupForms = document.querySelectorAll('.group-form-item');
                    groupForms.forEach((form) => {
                        const groupIdx = form.dataset.groupIndex;
                        loadStudentsForGroup(kelasId, groupIdx);
                    });
                }
            });
            
            // Auto-load jika kelas sudah terisi saat page load (old values)
            if (kelasSelect.value) {
                const groupForms = document.querySelectorAll('.group-form-item');
                groupForms.forEach((form) => {
                    const groupIdx = form.dataset.groupIndex;
                    loadStudentsForGroup(kelasSelect.value, groupIdx);
                });
            }
        }
        
        // Function to load students via AJAX
        function loadStudents(kelasId) {
            const studentsList = document.getElementById('students_list');
            studentsList.innerHTML = '<div class="text-center text-gray-400 py-4"><i class="fas fa-spinner fa-spin mr-2"></i>Memuat daftar siswa...</div>';
            
            // Determine the correct API route based on user role
            let apiUrl;
            @if(isset($userRole))
                @if($userRole == 'superadmin')
                    apiUrl = '{{ route("superadmin.api.students-by-class", ":kelasId") }}';
                @elseif($userRole == 'admin')
                    apiUrl = '{{ route("admin.api.students-by-class", ":kelasId") }}';
                @else
                    apiUrl = '{{ route("teacher.api.students-by-class", ":kelasId") }}';
                @endif
            @else
                apiUrl = '{{ route("teacher.api.students-by-class", ":kelasId") }}';
            @endif
            
            apiUrl = apiUrl.replace(':kelasId', kelasId);
            
            fetch(apiUrl)
                .then(response => response.json())
                .then(students => {
                    if (students.length > 0) {
                        let html = '<div class="space-y-2">';
                        students.forEach(student => {
                            html += `
                                <label class="flex items-center p-2 hover:bg-gray-700 rounded cursor-pointer">
                                    <input type="checkbox" name="group_members[]" value="${student.user_id || student.id}" 
                                           class="member-checkbox rounded border-gray-600 text-green-600 focus:ring-green-500" 
                                           onchange="updateLeaderSelection()">
                                    <span class="ml-3 text-sm text-gray-300">${student.name} (${student.nis || 'N/A'})</span>
                                </label>
                            `;
                        });
                        html += '</div>';
                        studentsList.innerHTML = html;
                    } else {
                        studentsList.innerHTML = '<div class="text-center text-gray-400 py-4"><i class="fas fa-exclamation-triangle mr-2"></i>Tidak ada siswa di kelas ini</div>';
                    }
                })
                .catch(error => {
                    console.error('Error loading students:', error);
                    studentsList.innerHTML = '<div class="text-center text-red-400 py-4"><i class="fas fa-exclamation-triangle mr-2"></i>Gagal memuat daftar siswa</div>';
                });
        }
        
        // Global variables
        let groupIndex = 0;
        
        // Global functions untuk inline onclick handlers
        window.addNewGroupForm = function() {
            const container = document.getElementById('groups_container');
            const groupDiv = document.createElement('div');
            groupDiv.className = 'group-form-item bg-gray-800 rounded-lg p-4 border border-gray-600';
            groupDiv.dataset.groupIndex = groupIndex;
            
            groupDiv.innerHTML = `
                <div class="flex justify-between items-center mb-4">
                    <h6 class="text-md font-semibold text-white">Kelompok ${groupIndex + 1}</h6>
                    ${groupIndex > 0 ? `
                        <button type="button" onclick="removeGroupForm(this)" 
                                class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm rounded transition duration-200">
                            <i class="fas fa-trash mr-1"></i> Hapus
                        </button>
                    ` : ''}
                </div>
                
                <!-- Nama Kelompok -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Nama Kelompok <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="group_names[]" 
                           class="w-full px-4 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-green-500" 
                           placeholder="Masukkan nama kelompok" required>
                </div>
                
                <!-- Pilih Anggota -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Pilih Anggota Kelompok <span class="text-red-400">*</span>
                    </label>
                    <div class="students-list-${groupIndex} max-h-60 overflow-y-auto bg-gray-700 rounded-lg p-4 border border-gray-600">
                        <div class="text-center text-gray-400 py-2">
                            <i class="fas fa-info-circle mr-2"></i>
                            Pilih kelas terlebih dahulu
                        </div>
                    </div>
                </div>
                
                <!-- Pilih Ketua -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Pilih Ketua Kelompok <span class="text-red-400">*</span>
                    </label>
                    <div class="leader-selection-${groupIndex} bg-gray-700 rounded-lg p-4 border border-gray-600">
                        <div class="text-center text-gray-400 py-2">
                            <i class="fas fa-info-circle mr-2"></i>
                            Pilih anggota terlebih dahulu
                        </div>
                    </div>
                </div>
            `;
            
            container.appendChild(groupDiv);
            
            // Load students jika kelas sudah dipilih
            const kelasSelect = document.getElementById('kelas_id');
            if (kelasSelect && kelasSelect.value) {
                loadStudentsForGroup(kelasSelect.value, groupIndex);
            }
            
            groupIndex++;
        }
        
        window.removeGroupForm = function(button) {
            const groupForm = button.closest('.group-form-item');
            groupForm.remove();
            updateGroupNumbers();
        }
        
        function updateGroupNumbers() {
            const groupForms = document.querySelectorAll('.group-form-item');
            groupForms.forEach((form, index) => {
                const heading = form.querySelector('h6');
                if (heading) {
                    heading.textContent = `Kelompok ${index + 1}`;
                }
            });
        }
        
        function loadStudentsForGroup(kelasId, groupIdx) {
            const studentsList = document.querySelector(`.students-list-${groupIdx}`);
            studentsList.innerHTML = '<div class="text-center text-gray-400 py-2"><i class="fas fa-spinner fa-spin mr-2"></i>Memuat daftar siswa...</div>';
            
            // Determine the correct API route based on user role
            let apiUrl;
            @if(isset($userRole))
                @if($userRole == 'superadmin')
                    apiUrl = '{{ route("superadmin.api.students-by-class", ":kelasId") }}';
                @elseif($userRole == 'admin')
                    apiUrl = '{{ route("admin.api.students-by-class", ":kelasId") }}';
                @else
                    apiUrl = '{{ route("teacher.api.students-by-class", ":kelasId") }}';
                @endif
            @else
                apiUrl = '{{ route("teacher.api.students-by-class", ":kelasId") }}';
            @endif
            
            apiUrl = apiUrl.replace(':kelasId', kelasId);
            
            fetch(apiUrl)
                .then(response => response.json())
                .then(students => {
                    if (students.length > 0) {
                        let html = '<div class="space-y-2">';
                        students.forEach(student => {
                            html += `
                                <label class="flex items-center p-2 hover:bg-gray-600 rounded cursor-pointer">
                                    <input type="checkbox" name="group_members[${groupIdx}][]" value="${student.user_id || student.id}" 
                                           class="member-checkbox-${groupIdx} rounded border-gray-600 text-green-600 focus:ring-green-500" 
                                           onchange="updateLeaderSelectionForGroup(${groupIdx})">
                                    <span class="ml-3 text-sm text-gray-300">${student.name} (${student.nis || 'N/A'})</span>
                                </label>
                            `;
                        });
                        html += '</div>';
                        studentsList.innerHTML = html;
                    } else {
                        studentsList.innerHTML = '<div class="text-center text-gray-400 py-2"><i class="fas fa-exclamation-triangle mr-2"></i>Tidak ada siswa di kelas ini</div>';
                    }
                })
                .catch(error => {
                    console.error('Error loading students:', error);
                    studentsList.innerHTML = '<div class="text-center text-red-400 py-2"><i class="fas fa-exclamation-triangle mr-2"></i>Gagal memuat daftar siswa</div>';
                });
        }
        
        window.updateLeaderSelectionForGroup = function(groupIdx) {
            const memberCheckboxes = document.querySelectorAll(`.member-checkbox-${groupIdx}:checked`);
            const leaderSelection = document.querySelector(`.leader-selection-${groupIdx}`);
            
            if (memberCheckboxes.length < 2) {
                leaderSelection.innerHTML = '<div class="text-center text-gray-400 py-2"><i class="fas fa-info-circle mr-2"></i>Pilih minimal 2 anggota untuk memilih ketua</div>';
                return;
            }
            
            let html = '<div class="space-y-2">';
            memberCheckboxes.forEach(checkbox => {
                const studentName = checkbox.nextElementSibling.textContent.trim();
                html += `
                    <label class="flex items-center p-2 hover:bg-gray-600 rounded cursor-pointer">
                        <input type="radio" name="group_leaders[${groupIdx}]" value="${checkbox.value}" 
                               class="rounded border-gray-600 text-green-600 focus:ring-green-500">
                        <span class="ml-3 text-sm text-gray-300">${studentName}</span>
                    </label>
                `;
            });
            html += '</div>';
            leaderSelection.innerHTML = html;
        }
        
        // Function to update leader selection based on selected members
        window.updateLeaderSelection = function() {
            const memberCheckboxes = document.querySelectorAll('.member-checkbox:checked');
            const leaderSelection = document.getElementById('leader_selection');
            
            if (memberCheckboxes.length < 2) {
                leaderSelection.innerHTML = '<div class="text-center text-gray-400 py-4"><i class="fas fa-info-circle mr-2"></i>Pilih minimal 2 anggota untuk memilih ketua</div>';
                document.getElementById('member_validation').style.display = 'block';
                return;
            }
            
            document.getElementById('member_validation').style.display = 'none';
            
            let html = '<div class="space-y-2">';
            memberCheckboxes.forEach(checkbox => {
                const studentName = checkbox.nextElementSibling.textContent.trim();
                html += `
                    <label class="flex items-center p-2 hover:bg-gray-700 rounded cursor-pointer">
                        <input type="radio" name="group_leader" value="${checkbox.value}" 
                               class="rounded border-gray-600 text-green-600 focus:ring-green-500">
                        <span class="ml-3 text-sm text-gray-300">${studentName}</span>
                    </label>
                `;
            });
            html += '</div>';
            leaderSelection.innerHTML = html;
        }
        
        // Toggle peer assessment section
        const enablePeerAssessmentCheckbox = document.getElementById('enable_peer_assessment');
        const assessmentScaleSection = document.getElementById('assessment_scale_section');
        
        if (enablePeerAssessmentCheckbox && assessmentScaleSection) {
            function toggleAssessmentSection() {
                if (enablePeerAssessmentCheckbox.checked) {
                    assessmentScaleSection.style.display = 'block';
                } else {
                    assessmentScaleSection.style.display = 'none';
                }
            }
            
            enablePeerAssessmentCheckbox.addEventListener('change', toggleAssessmentSection);
            toggleAssessmentSection(); // Initial call
        }
        
        // Form validation before submission
        const form = document.getElementById('groupForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Handle form submission - gabungkan kelas_id dan mapel_id menjadi kelas_mapel_id
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
                
                console.log('Form submit (Group) - Kelas ID:', kelasId, 'Mapel ID:', mapelId, 'Kombinasi:', kelasMapelId);
                
                const existingGroupId = document.getElementById('existing_group_id').value;
                const groupForms = document.querySelectorAll('.group-form-item');
                
                // Validasi: harus pilih existing ATAU buat baru (lengkap)
                if (!existingGroupId && groupForms.length === 0) {
                    e.preventDefault();
                    alert('Pilih kelompok existing atau buat kelompok baru!');
                    return false;
                }
                
                // Validasi untuk multiple groups
                if (groupForms.length > 0) {
                    let isValid = true;
                    
                    groupForms.forEach((form, index) => {
                        const groupName = form.querySelector('input[name="group_names[]"]').value.trim();
                        const selectedMembers = form.querySelectorAll(`input[name^="group_members[${form.dataset.groupIndex}]"]:checked`);
                        const selectedLeader = form.querySelector(`input[name^="group_leaders[${form.dataset.groupIndex}]"]:checked`);
                        
                        if (!groupName) {
                            alert(`Kelompok ${index + 1}: Nama kelompok wajib diisi!`);
                            isValid = false;
                            return false;
                        }
                        
                        if (selectedMembers.length < 2) {
                            alert(`Kelompok ${index + 1}: Minimal 2 anggota diperlukan!`);
                            isValid = false;
                            return false;
                        }
                        
                        if (!selectedLeader) {
                            alert(`Kelompok ${index + 1}: Ketua kelompok wajib dipilih!`);
                            isValid = false;
                            return false;
                        }
                    });
                    
                    if (!isValid) {
                        e.preventDefault();
                        return false;
                    }
                }
                
                // Set hidden input untuk backend
                if (existingGroupId) {
                    // User pilih existing group
                    document.querySelector('input[name="is_new_group"]').value = '0';
                } else {
                    // User buat kelompok baru
                    document.querySelector('input[name="is_new_group"]').value = '1';
                }
                
                // Validate peer assessment if enabled
                if (enablePeerAssessmentCheckbox && enablePeerAssessmentCheckbox.checked) {
                    const peerAssessmentDue = document.getElementById('peer_assessment_due').value;
                    const taskDue = document.getElementById('due').value;
                    
                    if (!peerAssessmentDue) {
                        e.preventDefault();
                        alert('Deadline penilaian antar kelompok harus diisi!');
                        return false;
                    }
                    
                    // Validate scale items
                    const scaleLabels = document.querySelectorAll('input[name="scale_labels[]"]');
                    const scalePoints = document.querySelectorAll('input[name="scale_points[]"]');
                    
                    if (scaleLabels.length < 2) {
                        e.preventDefault();
                        alert('Minimal 2 skala penilaian diperlukan!');
                        return false;
                    }
                    
                    // Check if all scale items have values
                    for (let i = 0; i < scaleLabels.length; i++) {
                        if (!scaleLabels[i].value.trim() || !scalePoints[i].value) {
                            e.preventDefault();
                            alert('Semua skala penilaian harus diisi lengkap!');
                            return false;
                        }
                    }
                }
            });
        }
    });

    // Function to add new scale item
    window.addScaleItem = function() {
        const container = document.getElementById('scale_items_container');
        const scaleCount = container.children.length;
        
        const newItem = document.createElement('div');
        newItem.className = 'scale-item bg-gray-800 p-4 rounded-lg border border-gray-600';
        newItem.innerHTML = `
            <div class="flex justify-between items-center mb-3">
                <span class="text-white font-medium">Skala ${scaleCount + 1}</span>
                <button type="button" onclick="removeScaleItem(this)" 
                        class="px-2 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700 transition duration-200">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Label</label>
                    <input type="text" name="scale_labels[]" 
                           class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Point</label>
                    <input type="number" name="scale_points[]" min="0" 
                           class="w-full px-3 py-2 bg-gray-700 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
            </div>
        `;
        container.appendChild(newItem);
    }

    // Function to remove scale item
    window.removeScaleItem = function(button) {
        const container = document.getElementById('scale_items_container');
        if (container.children.length > 2) { // Keep minimum 2 items
            button.closest('.scale-item').remove();
            // Update scale numbers
            updateScaleNumbers();
        } else {
            alert('Minimal 2 skala penilaian diperlukan!');
        }
    }

    // Function to update scale numbers after removal
    function updateScaleNumbers() {
        const container = document.getElementById('scale_items_container');
        const scaleItems = container.querySelectorAll('.scale-item');
        scaleItems.forEach((item, index) => {
            const numberSpan = item.querySelector('.text-white.font-medium');
            if (numberSpan) {
                numberSpan.textContent = `Skala ${index + 1}`;
            }
        });
    }
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