@extends('layouts.unified-layout-consistent')

@section('title', 'Class Management - Terra Assessment')
@section('page-title', 'Class Management')
@section('page-description', 'Kelola kelas dan mata pelajaran')

@section('content')
<div class="space-y-6">
    <!-- Statistics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-unified-stats-card
            title="Total Classes"
            value="{{ $stats['totalClasses'] ?? '0' }}"
            change="+3"
            change-type="positive"
            icon="fas fa-chalkboard"
            color="primary"
        />
        
        <x-unified-stats-card
            title="Active Classes"
            value="{{ $stats['activeClasses'] ?? '0' }}"
            change="+2"
            change-type="positive"
            icon="fas fa-check-circle"
            color="success"
        />
        
        <x-unified-stats-card
            title="Total Subjects"
            value="{{ $stats['totalSubjects'] ?? '0' }}"
            change="+5"
            change-type="positive"
            icon="fas fa-book"
            color="info"
        />
        
        <x-unified-stats-card
            title="Total Students"
            value="{{ $stats['totalStudents'] ?? '0' }}"
            change="+12"
            change-type="positive"
            icon="fas fa-user-graduate"
            color="warning"
        />
    </div>

    <!-- Actions -->
    <x-unified-card title="Class Management Actions" icon="fas fa-cogs" color="secondary">
        <div class="flex flex-wrap gap-3">
            <x-unified-button variant="success" href="{{ route('class.create') }}">
                <i class="fas fa-plus"></i>
                Add New Class
            </x-unified-button>
            
            <x-unified-button variant="primary" href="{{ route('subject.create') }}">
                <i class="fas fa-book-plus"></i>
                Add New Subject
            </x-unified-button>
            
            <x-unified-button variant="secondary" href="{{ route('class.import') }}">
                <i class="fas fa-upload"></i>
                Import Classes
            </x-unified-button>
            
            <x-unified-button variant="info" href="{{ route('class.export') }}">
                <i class="fas fa-download"></i>
                Export Data
            </x-unified-button>
        </div>
    </x-unified-card>

    <!-- Classes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($classes ?? [] as $class)
            <x-unified-card class="hover:shadow-lg transition-shadow cursor-pointer" href="{{ route('class.detail', $class->id ?? 1) }}">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chalkboard text-primary-600 text-lg"></i>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $class->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($class->status ?? 'Active') }}
                    </span>
                </div>
                
                <h3 class="text-lg font-semibold text-secondary-900 mb-2">
                    {{ $class->nama_kelas ?? 'Class A' }}
                </h3>
                
                <p class="text-sm text-secondary-600 mb-4">
                    {{ $class->deskripsi ?? 'Class description goes here.' }}
                </p>
                
                <div class="space-y-2 mb-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-secondary-500">Students:</span>
                        <span class="font-medium text-secondary-900">{{ $class->total_students ?? '25' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-secondary-500">Subjects:</span>
                        <span class="font-medium text-secondary-900">{{ $class->total_subjects ?? '8' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-secondary-500">Teacher:</span>
                        <span class="font-medium text-secondary-900">{{ $class->teacher_name ?? 'John Doe' }}</span>
                    </div>
                </div>
                
                <div class="pt-4 border-t border-secondary-200">
                    <div class="flex items-center justify-between">
                        <div class="flex space-x-2">
                            <x-unified-button variant="secondary" size="sm" href="{{ route('class.edit', $class->id ?? 1) }}">
                                <i class="fas fa-edit"></i>
                            </x-unified-button>
                            <x-unified-button variant="error" size="sm" onclick="deleteClass({{ $class->id ?? 1 }})">
                                <i class="fas fa-trash"></i>
                            </x-unified-button>
                        </div>
                        <div class="text-sm text-secondary-500">
                            {{ $class->created_at ?? '2024-01-15' }}
                        </div>
                    </div>
                </div>
            </x-unified-card>
        @empty
            <div class="col-span-full">
                <x-unified-card>
                    <div class="text-center py-12">
                        <i class="fas fa-chalkboard text-secondary-300 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-secondary-900 mb-2">No classes found</h3>
                        <p class="text-secondary-500 mb-4">Get started by creating your first class.</p>
                        <x-unified-button variant="primary" href="{{ route('class.create') }}">
                            <i class="fas fa-plus"></i>
                            Create Class
                        </x-unified-button>
                    </div>
                </x-unified-card>
            </div>
        @endforelse
    </div>

    <!-- Subjects Table -->
    <x-unified-card title="Subjects List" icon="fas fa-book" color="primary">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-secondary-200">
                <thead class="bg-secondary-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">
                            Subject
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">
                            Code
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">
                            Classes
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">
                            Teacher
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-secondary-200">
                    @forelse($subjects ?? [] as $subject)
                        <tr class="hover:bg-secondary-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-lg bg-primary-100 flex items-center justify-center">
                                            <i class="fas fa-book text-primary-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-secondary-900">
                                            {{ $subject->nama_mapel ?? 'Mathematics' }}
                                        </div>
                                        <div class="text-sm text-secondary-500">
                                            {{ $subject->deskripsi ?? 'Subject description' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-900">
                                {{ $subject->kode_mapel ?? 'MATH-101' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-900">
                                {{ $subject->total_classes ?? '3' }} classes
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-900">
                                {{ $subject->teacher_name ?? 'Jane Smith' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $subject->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($subject->status ?? 'Active') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <x-unified-button variant="secondary" size="sm" href="{{ route('subject.view', $subject->id ?? 1) }}">
                                        <i class="fas fa-eye"></i>
                                    </x-unified-button>
                                    <x-unified-button variant="primary" size="sm" href="{{ route('subject.edit', $subject->id ?? 1) }}">
                                        <i class="fas fa-edit"></i>
                                    </x-unified-button>
                                    <x-unified-button variant="error" size="sm" onclick="deleteSubject({{ $subject->id ?? 1 }})">
                                        <i class="fas fa-trash"></i>
                                    </x-unified-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-center">
                                    <i class="fas fa-book text-secondary-300 text-4xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-secondary-900 mb-2">No subjects found</h3>
                                    <p class="text-secondary-500 mb-4">Get started by creating your first subject.</p>
                                    <x-unified-button variant="primary" href="{{ route('subject.create') }}">
                                        <i class="fas fa-plus"></i>
                                        Create Subject
                                    </x-unified-button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-unified-card>
</div>

@push('scripts')
<script>
function deleteClass(classId) {
    if (confirm('Are you sure you want to delete this class?')) {
        // Implement delete functionality
        console.log('Deleting class:', classId);
    }
}

function deleteSubject(subjectId) {
    if (confirm('Are you sure you want to delete this subject?')) {
        // Implement delete functionality
        console.log('Deleting subject:', subjectId);
    }
}
</script>
@endpush
@endsection
