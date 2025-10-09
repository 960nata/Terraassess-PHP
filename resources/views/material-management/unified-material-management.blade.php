@extends('layouts.unified-layout-consistent')

@section('title', 'Material Management - Terra Assessment')
@section('page-title', 'Material Management')
@section('page-description', 'Kelola dan organisir materi pembelajaran')

@section('content')
<div class="space-y-6">
    <!-- Statistics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-unified-stats-card
            title="Total Materials"
            value="{{ $stats['totalMaterials'] ?? '0' }}"
            change="+8%"
            change-type="positive"
            icon="fas fa-file-alt"
            color="primary"
        />
        
        <x-unified-stats-card
            title="Published"
            value="{{ $stats['publishedMaterials'] ?? '0' }}"
            change="+12%"
            change-type="positive"
            icon="fas fa-check-circle"
            color="success"
        />
        
        <x-unified-stats-card
            title="Draft"
            value="{{ $stats['draftMaterials'] ?? '0' }}"
            change="+3"
            change-type="neutral"
            icon="fas fa-edit"
            color="warning"
        />
        
        <x-unified-stats-card
            title="Downloads"
            value="{{ $stats['totalDownloads'] ?? '0' }}"
            change="+25%"
            change-type="positive"
            icon="fas fa-download"
            color="info"
        />
    </div>

    <!-- Filters and Actions -->
    <x-unified-card title="Filters & Actions" icon="fas fa-filter" color="secondary">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-secondary-700 mb-2">Subject</label>
                <select class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All Subjects</option>
                    @foreach($subjects ?? [] as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->nama_mapel }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-secondary-700 mb-2">Class</label>
                <select class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All Classes</option>
                    @foreach($classes ?? [] as $class)
                        <option value="{{ $class->id }}">{{ $class->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-secondary-700 mb-2">Status</label>
                <select class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All Status</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                    <option value="archived">Archived</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <x-unified-button variant="primary" class="w-full">
                    <i class="fas fa-search"></i>
                    Apply Filters
                </x-unified-button>
            </div>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <x-unified-button variant="success" href="{{ route('material.create') }}">
                <i class="fas fa-plus"></i>
                Create New Material
            </x-unified-button>
            
            <x-unified-button variant="secondary" href="{{ route('material.import') }}">
                <i class="fas fa-upload"></i>
                Import Materials
            </x-unified-button>
            
            <x-unified-button variant="info" href="{{ route('material.analytics') }}">
                <i class="fas fa-chart-bar"></i>
                View Analytics
            </x-unified-button>
        </div>
    </x-unified-card>

    <!-- Materials Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($materials ?? [] as $material)
            <x-unified-card class="hover:shadow-lg transition-shadow cursor-pointer" href="{{ route('material.view', $material->id ?? 1) }}">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-alt text-primary-600 text-lg"></i>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $material->status === 'published' ? 'bg-green-100 text-green-800' : 
                           ($material->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 
                           'bg-gray-100 text-gray-800') }}">
                        {{ ucfirst($material->status ?? 'Published') }}
                    </span>
                </div>
                
                <h3 class="text-lg font-semibold text-secondary-900 mb-2">
                    {{ $material->judul ?? 'Sample Material' }}
                </h3>
                
                <p class="text-sm text-secondary-600 mb-4 line-clamp-3">
                    {{ $material->deskripsi ?? 'Material description goes here. This is a sample description for the material card.' }}
                </p>
                
                <div class="flex items-center justify-between text-sm text-secondary-500">
                    <div class="flex items-center">
                        <i class="fas fa-user mr-1"></i>
                        {{ $material->author ?? 'Author Name' }}
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-calendar mr-1"></i>
                        {{ $material->created_at ?? '2024-01-15' }}
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-secondary-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-sm text-secondary-500">
                            <i class="fas fa-download mr-1"></i>
                            {{ $material->downloads ?? '0' }} downloads
                        </div>
                        <div class="flex space-x-2">
                            <x-unified-button variant="secondary" size="sm" href="{{ route('material.edit', $material->id ?? 1) }}">
                                <i class="fas fa-edit"></i>
                            </x-unified-button>
                            <x-unified-button variant="error" size="sm" onclick="deleteMaterial({{ $material->id ?? 1 }})">
                                <i class="fas fa-trash"></i>
                            </x-unified-button>
                        </div>
                    </div>
                </div>
            </x-unified-card>
        @empty
            <div class="col-span-full">
                <x-unified-card>
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-secondary-300 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-secondary-900 mb-2">No materials found</h3>
                        <p class="text-secondary-500 mb-4">Get started by creating your first material.</p>
                        <x-unified-button variant="primary" href="{{ route('material.create') }}">
                            <i class="fas fa-plus"></i>
                            Create Material
                        </x-unified-button>
                    </div>
                </x-unified-card>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($materials) && count($materials) > 0)
        <div class="flex items-center justify-between">
            <div class="text-sm text-secondary-700">
                Showing {{ count($materials) }} of {{ count($materials) }} materials
            </div>
            <div class="flex space-x-2">
                <x-unified-button variant="secondary" size="sm" disabled>
                    <i class="fas fa-chevron-left"></i>
                    Previous
                </x-unified-button>
                <x-unified-button variant="secondary" size="sm">
                    Next
                    <i class="fas fa-chevron-right"></i>
                </x-unified-button>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function deleteMaterial(materialId) {
    if (confirm('Are you sure you want to delete this material?')) {
        // Implement delete functionality
        console.log('Deleting material:', materialId);
    }
}

// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterSelects = document.querySelectorAll('select');
    
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            // Implement filter logic
            console.log('Filter changed:', this.name, this.value);
        });
    });
});
</script>
@endpush
@endsection
