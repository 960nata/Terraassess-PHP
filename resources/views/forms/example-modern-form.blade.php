@extends('layouts.unified-layout')

@section('container')
<div class="max-w-2xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Create New Assignment</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            Fill in the details below to create a new assignment for your students.
        </p>
    </div>

    <!-- Modern Form -->
    <x-modern-form method="POST" action="{{ route('tugas.store') }}" enctype="multipart/form-data">
        <x-modern-card>
            <x-slot name="header">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Assignment Details</h3>
            </x-slot>
            
            <div class="space-y-6">
                <!-- Title -->
                <x-modern-form-group label="Assignment Title" required>
                    <x-modern-input 
                        name="judul" 
                        placeholder="Enter assignment title"
                        value="{{ old('judul') }}"
                        required
                    />
                </x-modern-form-group>
                
                <!-- Description -->
                <x-modern-form-group label="Description" required>
                    <x-modern-input 
                        name="deskripsi" 
                        type="textarea" 
                        placeholder="Describe the assignment requirements"
                        rows="4"
                        required
                    >{{ old('deskripsi') }}</x-modern-input>
                </x-modern-form-group>
                
                <!-- Class Selection -->
                <x-modern-form-group label="Target Class" required>
                    <x-modern-input name="kelas_id" type="select" required>
                        <option value="">Select a class</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </x-modern-input>
                </x-modern-form-group>
                
                <!-- Due Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-modern-form-group label="Due Date" required>
                        <x-modern-input 
                            name="tgl_deadline" 
                            type="datetime-local"
                            value="{{ old('tgl_deadline') }}"
                            required
                        />
                    </x-modern-form-group>
                    
                    <x-modern-form-group label="Points" required>
                        <x-modern-input-group prepend="pts">
                            <x-modern-input 
                                name="poin" 
                                type="number" 
                                placeholder="100"
                                min="1"
                                max="1000"
                                value="{{ old('poin', 100) }}"
                                required
                            />
                        </x-modern-input-group>
                    </x-modern-form-group>
                </div>
                
                <!-- Assignment Type -->
                <x-modern-form-group label="Assignment Type" required>
                    <div class="space-y-3">
                        <x-modern-radio 
                            name="jenis_tugas" 
                            value="individual" 
                            label="Individual Assignment"
                            checked="{{ old('jenis_tugas') == 'individual' }}"
                        />
                        <x-modern-radio 
                            name="jenis_tugas" 
                            value="group" 
                            label="Group Assignment"
                            checked="{{ old('jenis_tugas') == 'group' }}"
                        />
                        <x-modern-radio 
                            name="jenis_tugas" 
                            value="project" 
                            label="Project Assignment"
                            checked="{{ old('jenis_tugas') == 'project' }}"
                        />
                    </div>
                </x-modern-form-group>
                
                <!-- IoT Integration -->
                <x-modern-form-group label="IoT Integration">
                    <div class="space-y-3">
                        <x-modern-checkbox 
                            name="requires_iot" 
                            label="Requires IoT device/sensor data"
                            checked="{{ old('requires_iot') }}"
                        />
                        <x-modern-checkbox 
                            name="allows_collaboration" 
                            label="Allow student collaboration"
                            checked="{{ old('allows_collaboration') }}"
                        />
                        <x-modern-checkbox 
                            name="auto_grade" 
                            label="Enable automatic grading"
                            checked="{{ old('auto_grade') }}"
                        />
                    </div>
                </x-modern-form-group>
                
                <!-- File Upload -->
                <x-modern-form-group label="Attachment Files" help="Upload any supporting files (PDF, DOC, images)">
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <i class="ph-cloud-arrow-up text-4xl text-gray-400"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                    <span>Upload files</span>
                                    <input id="file-upload" name="files[]" type="file" class="sr-only" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, PDF up to 10MB each</p>
                        </div>
                    </div>
                </x-modern-form-group>
                
                <!-- Instructions -->
                <x-modern-form-group label="Special Instructions" help="Any additional instructions for students">
                    <x-modern-input 
                        name="instruksi_khusus" 
                        type="textarea" 
                        placeholder="Enter any special instructions or requirements"
                        rows="3"
                    >{{ old('instruksi_khusus') }}</x-modern-input>
                </x-modern-form-group>
            </div>
            
            <x-slot name="footer">
                <div class="flex items-center justify-end space-x-3">
                    <x-modern-button 
                        type="button" 
                        variant="secondary" 
                        onclick="window.history.back()"
                    >
                        Cancel
                    </x-modern-button>
                    
                    <x-modern-button 
                        type="submit" 
                        variant="primary"
                        loading="{{ false }}"
                    >
                        <i class="ph-check mr-2"></i>
                        Create Assignment
                    </x-modern-button>
                </div>
            </x-slot>
        </x-modern-card>
    </x-modern-form>
</div>

@push('scripts')
<script>
// File upload preview
document.getElementById('file-upload').addEventListener('change', function(e) {
    const files = e.target.files;
    const container = e.target.closest('.border-dashed');
    
    if (files.length > 0) {
        container.classList.remove('border-gray-300');
        container.classList.add('border-primary-500', 'bg-primary-50');
        
        // Show file names
        const fileNames = Array.from(files).map(file => file.name).join(', ');
        const existingText = container.querySelector('.text-gray-600');
        if (existingText) {
            existingText.innerHTML = `<span class="font-medium text-primary-600">${files.length} file(s) selected:</span><br><span class="text-sm">${fileNames}</span>`;
        }
    }
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('border-red-500');
            isValid = false;
        } else {
            field.classList.remove('border-red-500');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('Please fill in all required fields.');
    }
});
</script>
@endpush
@endsection
