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
            <div class="bg-gradient-to-r {{ $isSuperadmin ? 'from-amber-600 to-yellow-600' : ($isAdmin ? 'from-blue-600 to-indigo-600' : 'from-green-600 to-teal-600') }} px-6 py-6 flex items-center justify-between transition-all duration-300">
                <div class="flex items-center">
                    <a href="{{ $backRoute }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white/20 text-white hover:bg-white/30 mr-4 transition-all duration-200">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-white flex items-center">
                            <i class="fas fa-users mr-3"></i>
                            Buat Tugas Kelompok
                        </h1>
                        <p class="text-white/80 mt-1">Kelola kolaborasi tim dan penilaian antar kelompok</p>
                    </div>
                </div>
                <div class="hidden md:block">
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold {{ $isSuperadmin ? 'bg-amber-500/30' : ($isAdmin ? 'bg-blue-500/30' : 'bg-green-500/30') }} text-white border border-white/20">
                        <i class="fas fa-tag mr-2"></i>
                        Tugas Kelompok
                    </span>
                </div>
            </div>
            
            <div class="p-6 bg-gray-800">
                <form id="groupForm" method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
                    @csrf
                    <!-- Hidden input untuk tipe tugas -->
                    <input type="hidden" name="tipe" value="4">
                    
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
                            <h5 class="text-lg font-bold text-white flex items-center">
                                <i class="fas fa-users-cog mr-3 text-{{ $isSuperadmin ? 'amber' : ($isAdmin ? 'blue' : 'green') }}-400"></i>
                                Konfigurasi Tim & Kelompok
                            </h5>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Opsi 1: Kelompok Existing -->
                            <div class="bg-gray-800/50 rounded-xl p-5 border border-gray-600 hover:border-{{ $isSuperadmin ? 'amber' : ($isAdmin ? 'blue' : 'green') }}-500/50 transition-all duration-300">
                                <div class="flex items-center mb-4">
                                    <div class="w-10 h-10 rounded-lg bg-{{ $isSuperadmin ? 'amber' : ($isAdmin ? 'blue' : 'green') }}-500/20 flex items-center justify-center mr-3">
                                        <i class="fas fa-layer-group text-{{ $isSuperadmin ? 'amber' : ($isAdmin ? 'blue' : 'green') }}-400"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-md font-bold text-white">Kelompok Terdaftar</h6>
                                        <p class="text-xs text-gray-400">Gunakan template yang sudah ada</p>
                                    </div>
                                </div>
                                
                                <div class="space-y-4">
                                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Pilih Template</label>
                                    <select class="w-full px-4 py-3 bg-gray-900 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-{{ $isSuperadmin ? 'amber' : ($isAdmin ? 'blue' : 'green') }}-500 transition duration-200" 
                                            id="existing_group_id" name="existing_group_id">
                                        <option value="">-- Pilih Kelompok --</option>
                                        @if(isset($groups) && $groups->count() > 0)
                                            @foreach($groups as $group)
                                                <option value="{{ $group->id }}" {{ old('existing_group_id') == $group->id ? 'selected' : '' }}>
                                                    {{ $group->name }} ({{ $group->AnggotaTugasKelompok->count() }} Anggota)
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('existing_group_id')
                                        <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Opsi 2: Kelompok Baru -->
                            <div class="bg-gray-800/50 rounded-xl p-5 border border-gray-600 hover:border-{{ $isSuperadmin ? 'amber' : ($isAdmin ? 'blue' : 'green') }}-500/50 transition-all duration-300">
                                <div class="flex items-center mb-4">
                                    <div class="w-10 h-10 rounded-lg bg-{{ $isSuperadmin ? 'amber' : ($isAdmin ? 'blue' : 'green') }}-500/20 flex items-center justify-center mr-3">
                                        <i class="fas fa-user-plus text-{{ $isSuperadmin ? 'amber' : ($isAdmin ? 'blue' : 'green') }}-400"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-md font-bold text-white">Buat Tim Baru</h6>
                                        <p class="text-xs text-gray-400">Sesuaikan anggota secara manual</p>
                                    </div>
                                </div>
                                
                                <button type="button" onclick="addNewGroupForm()" 
                                        class="w-full py-3 px-4 bg-{{ $isSuperadmin ? 'amber' : ($isAdmin ? 'blue' : 'green') }}-600/20 hover:bg-{{ $isSuperadmin ? 'amber' : ($isAdmin ? 'blue' : 'green') }}-600/30 text-{{ $isSuperadmin ? 'amber' : ($isAdmin ? 'blue' : 'green') }}-400 font-bold rounded-lg border border-{{ $isSuperadmin ? 'amber' : ($isAdmin ? 'blue' : 'green') }}-600/50 transition duration-200 flex items-center justify-center">
                                    <i class="fas fa-plus-circle mr-2"></i>
                                    KONFIGURASI TIM
                                </button>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <div id="groups_container" class="space-y-4">
                                <!-- Group forms akan di-generate oleh JavaScript -->
                            </div>
                        </div>

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
                        <div id="assessment_scale_section" class="mt-6 p-6 bg-gray-800/80 rounded-xl border border-{{ $isSuperadmin ? 'amber' : ($isAdmin ? 'blue' : 'green') }}-500/30" style="display: none;">
                            <div class="flex items-center mb-6">
                                <div class="w-10 h-10 rounded-lg bg-{{ $isSuperadmin ? 'amber' : ($isAdmin ? 'blue' : 'green') }}-500/20 flex items-center justify-center mr-3">
                                    <i class="fas fa-balance-scale text-{{ $isSuperadmin ? 'amber' : ($isAdmin ? 'blue' : 'green') }}-400"></i>
                                </div>
                                <div>
                                    <h6 class="text-md font-bold text-white">Parameter Penilaian Sejawat</h6>
                                    <p class="text-xs text-gray-400">Tentukan kriteria penilaian antar kelompok</p>
                                </div>
                            </div>
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
                        <button type="button" onclick="window.location.href='{{ $backRoute }}'" class="px-8 py-3 bg-gray-700 text-white font-bold rounded-xl hover:bg-gray-600 transition-all duration-200 border border-gray-600">
                            <i class="fas fa-times mr-2"></i> Batal
                        </button>
                        <button type="submit" class="px-8 py-3 bg-{{ $isSuperadmin ? 'amber' : ($isAdmin ? 'blue' : 'green') }}-600 text-white font-bold rounded-xl hover:bg-{{ $isSuperadmin ? 'amber' : ($isAdmin ? 'blue' : 'green') }}-700 transition-all duration-200 shadow-lg shadow-{{ $isSuperadmin ? 'amber' : ($isAdmin ? 'blue' : 'green') }}-500/20">
                            <i class="fas fa-paper-plane mr-2"></i> Terbitkan Tugas
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
                // Check for old input to restore groups
                @if(old('group_names'))
                    const oldGroupNames = @json(old('group_names'));
                    
                    Object.keys(oldGroupNames).forEach(index => {
                        addNewGroupForm();
                        const groupIdx = groupIndex - 1; // get the index of newly added group
                        
                        // Restore name
                        const nameInput = document.querySelector(`input[name="group_names[${groupIdx}]"]`);
                        if (nameInput) nameInput.value = oldGroupNames[index];
                    });
                @else
                    const groupForms = document.querySelectorAll('.group-form-item');
                    groupForms.forEach((form) => {
                        const groupIdx = form.dataset.groupIndex;
                        loadStudentsForGroup(kelasSelect.value, groupIdx);
                    });
                @endif
            }
        }
        
        // Global state for selected students across all groups
        const globalSelectedStudents = new Set();
        
        // Function to load students via AJAX
        function loadStudents(kelasId) {
            // function intentionally left blank as we use loadStudentsForGroup for the dynamic forms
        }
        
        // Global variables
        let groupIndex = 0;
        
        // Global functions untuk inline onclick handlers
        window.addNewGroupForm = function() {
            const container = document.getElementById('groups_container');
            const groupDiv = document.createElement('div');
            const accentColor = '{{ $isSuperadmin ? "amber" : ($isAdmin ? "blue" : "green") }}';
            groupDiv.className = `group-form-item bg-gray-800/80 rounded-xl p-5 border border-gray-600 hover:border-${accentColor}-500/50 transition-all duration-300 mb-4 animate-fade-in-up`;
            groupDiv.dataset.groupIndex = groupIndex;
            
            groupDiv.innerHTML = `
                <div class="flex justify-between items-center mb-5 pb-3 border-b border-gray-700">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-${accentColor}-500/20 flex items-center justify-center text-${accentColor}-400 mr-3 text-sm font-bold">
                            ${groupIndex + 1}
                        </div>
                        <h6 class="text-md font-bold text-white uppercase tracking-tight">Identitas Tim ${groupIndex + 1}</h6>
                    </div>
                    ${groupIndex > 0 ? `
                        <button type="button" onclick="removeGroupForm(this)" 
                                class="w-8 h-8 flex items-center justify-center bg-red-500/10 hover:bg-red-500/20 text-red-500 rounded-lg transition-all duration-200">
                            <i class="fas fa-times"></i>
                        </button>
                    ` : ''}
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Kelompok -->
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Nama Kelompok <span class="text-red-400">*</span></label>
                            <input type="text" name="group_names[${groupIndex}]" 
                                   class="w-full px-4 py-3 bg-gray-900 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-${accentColor}-500 transition duration-200" 
                                   placeholder="Contoh: Tim Inovasi A" required>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Ketua Kelompok <span class="text-red-400">*</span></label>
                            <div class="leader-selection-${groupIndex} bg-gray-900 rounded-lg p-4 border border-gray-600 min-h-[100px] flex items-center justify-center">
                                <p class="text-xs text-gray-500 text-center italic">Pilih minimal 2 anggota terlebih dahulu</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pilih Anggota -->
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Daftar Anggota <span class="text-red-400">*</span></label>
                        <div class="students-list-${groupIndex} max-h-48 overflow-y-auto bg-gray-900 rounded-lg p-3 border border-gray-600 custom-scrollbar">
                            <div class="flex flex-col items-center justify-center h-full text-gray-500 py-4">
                                <i class="fas fa-school mb-2 text-xl opacity-20"></i>
                                <p class="text-xs">Pilih kelas tujuan di atas</p>
                            </div>
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
            
            // Remove students in this group from global state
            const checkboxes = groupForm.querySelectorAll('input[type="checkbox"]:checked');
            checkboxes.forEach(cb => globalSelectedStudents.delete(cb.value));
            
            groupForm.remove();
            updateGroupNumbers();
            
            // Refresh availability in other groups
            updateAllStudentAvailability();
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
                            const studentId = String(student.user_id || student.id);
                            // Check isDisabled based on global state, BUT not if it's already selected in THIS group (handled by logic, but initial load assumes fresh)
                            // Actually on initial load of NEW group, check if selected in OTHERS
                            const isSelectedElsewhere = globalSelectedStudents.has(studentId);
                            const disabledAttr = isSelectedElsewhere ? 'disabled' : '';
                            const opacityClass = isSelectedElsewhere ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-600 cursor-pointer';
                            const labelText = isSelectedElsewhere ? '(Sudah dipilih di kelompok lain)' : '';
                            
                            html += `
                                <label class="flex items-center p-2 rounded ${opacityClass} transition-colors duration-200">
                                    <input type="checkbox" name="group_members[${groupIdx}][]" value="${studentId}" 
                                           class="member-checkbox-${groupIdx} member-checkbox-global rounded border-gray-600 text-green-600 focus:ring-green-500" 
                                           onchange="handleStudentSelection(this, ${groupIdx})" ${disabledAttr}>
                                    <span class="ml-3 text-sm text-gray-300">
                                        ${student.name} (${student.nis || 'N/A'}) 
                                        <span class="text-xs text-red-400 italic ml-2">${labelText}</span>
                                    </span>
                                </label>
                            `;
                        });
                        studentsList.innerHTML = html;

                        // Restore old selection if available
                        @if(old('group_members'))
                            const oldGroupMembers = @json(old('group_members'));
                            const oldGroupLeaders = @json(old('group_leaders'));
                            
                            if (oldGroupMembers && oldGroupMembers[groupIdx]) {
                                const groupMembers = oldGroupMembers[groupIdx];
                                groupMembers.forEach(studentId => {
                                    const checkbox = studentsList.querySelector(`input[value="${studentId}"]`);
                                    if (checkbox) {
                                        checkbox.checked = true;
                                        handleStudentSelection(checkbox, groupIdx);
                                    }
                                });
                                
                                // Restore leader
                                if (oldGroupLeaders && oldGroupLeaders[groupIdx]) {
                                    const leaderId = oldGroupLeaders[groupIdx];
                                    setTimeout(() => {
                                        const leaderRadio = document.querySelector(`input[name="group_leaders[${groupIdx}]"][value="${leaderId}"]`);
                                        if (leaderRadio) leaderRadio.checked = true;
                                    }, 100);
                                }
                            }
                        @endif
                    } else {
                        studentsList.innerHTML = '<div class="text-center text-gray-400 py-2"><i class="fas fa-exclamation-triangle mr-2"></i>Tidak ada siswa di kelas ini</div>';
                    }
                })
                .catch(error => {
                    console.error('Error loading students:', error);
                    studentsList.innerHTML = '<div class="text-center text-red-400 py-2"><i class="fas fa-exclamation-triangle mr-2"></i>Gagal memuat daftar siswa</div>';
                });
        }
        
        window.handleStudentSelection = function(checkbox, groupIdx) {
            const studentId = checkbox.value;
            
            if (checkbox.checked) {
                globalSelectedStudents.add(studentId);
            } else {
                globalSelectedStudents.delete(studentId);
            }
            
            updateLeaderSelectionForGroup(groupIdx);
            updateAllStudentAvailability();
        }
        
        function updateAllStudentAvailability() {
            // Get all checkboxes across all groups
            const allCheckboxes = document.querySelectorAll('.member-checkbox-global');
            
            allCheckboxes.forEach(cb => {
                const studentId = cb.value;
                const isChecked = cb.checked;
                
                // If this checkbox is checked, it stays enabled.
                if (isChecked) return;
                
                // If NOT checked, check if it's selected globally (in another group)
                if (globalSelectedStudents.has(studentId)) {
                    cb.disabled = true;
                    cb.parentElement.classList.add('opacity-50', 'cursor-not-allowed');
                    cb.parentElement.classList.remove('hover:bg-gray-600', 'cursor-pointer');
                    
                    // Add label if not exists
                    const labelSpan = cb.nextElementSibling;
                    if (!labelSpan.querySelector('.text-red-400')) {
                        labelSpan.insertAdjacentHTML('beforeend', '<span class="text-xs text-red-400 italic ml-2">(Sudah dipilih di kelompok lain)</span>');
                    }
                } else {
                    cb.disabled = false;
                    cb.parentElement.classList.remove('opacity-50', 'cursor-not-allowed');
                    cb.parentElement.classList.add('hover:bg-gray-600', 'cursor-pointer');
                    
                    // Remove label
                    const warningSpan = cb.nextElementSibling.querySelector('.text-red-400');
                    if (warningSpan) warningSpan.remove();
                }
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
                const studentNameOnly = checkbox.nextElementSibling.childNodes[0].textContent.trim();
                html += `
                    <label class="flex items-center p-2 hover:bg-gray-600 rounded cursor-pointer">
                        <input type="radio" name="group_leaders[${groupIdx}]" value="${checkbox.value}" 
                               class="rounded border-gray-600 text-green-600 focus:ring-green-500">
                        <span class="ml-3 text-sm text-gray-300">${studentNameOnly}</span>
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

            // Restore old scale items if available
            @if(old('scale_labels'))
                const oldLabels = @json(old('scale_labels'));
                const oldPoints = @json(old('scale_points'));
                
                if (oldLabels && oldLabels.length > 0) {
                    document.addEventListener('DOMContentLoaded', function() {
                        const container = document.getElementById('scale_items_container');
                        if (container) {
                            container.innerHTML = ''; // Clear default items
                            
                            oldLabels.forEach((label, index) => {
                                window.addScaleItem();
                                const items = container.querySelectorAll('.scale-item');
                                const lastItem = items[items.length - 1];
                                const labelInput = lastItem.querySelector('input[name="scale_labels[]"]');
                                const pointInput = lastItem.querySelector('input[name="scale_points[]"]');
                                if (labelInput) labelInput.value = label;
                                if (pointInput) pointInput.value = oldPoints[index];
                            });
                        }
                    });
                }
            @endif
        }
        
        // Form validation before submission
        const form = document.getElementById('groupForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const kId = document.getElementById('kelas_id').value;
                const mId = document.getElementById('mapel_id').value;
                console.log('Form submit (Group) - Kelas ID:', kId, 'Mapel ID:', mId);
                
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
        
        const accentColor = '{{ $isSuperadmin ? "amber" : ($isAdmin ? "blue" : "green") }}';
        newItem.className = `scale-item bg-gray-800/50 p-4 rounded-lg border border-gray-600 hover:border-${accentColor}-500/30 transition-all duration-300`;
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
                           class="w-full px-3 py-2 bg-gray-900 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-${accentColor}-500 focus:border-transparent transition duration-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Point</label>
                    <input type="number" name="scale_points[]" min="0" 
                           class="w-full px-3 py-2 bg-gray-900 border border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-${accentColor}-500 focus:border-transparent transition duration-200">
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