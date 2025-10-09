@extends('layouts.unified-layout-consistent')

@section('title', 'Task Management - Terra Assessment')
@section('page-title', 'Task Management')
@section('page-description', 'Kelola dan pantau semua tugas')

@section('content')
<div class="space-y-6">
    <!-- Statistics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-unified-stats-card
            title="Total Tasks"
            value="{{ $stats['totalTasks'] ?? '0' }}"
            change="+12%"
            change-type="positive"
            icon="fas fa-tasks"
            color="primary"
        />
        
        <x-unified-stats-card
            title="Active Tasks"
            value="{{ $stats['activeTasks'] ?? '0' }}"
            change="+8%"
            change-type="positive"
            icon="fas fa-play-circle"
            color="success"
        />
        
        <x-unified-stats-card
            title="Completed Tasks"
            value="{{ $stats['completedTasks'] ?? '0' }}"
            change="+15%"
            change-type="positive"
            icon="fas fa-check-circle"
            color="info"
        />
        
        <x-unified-stats-card
            title="Pending Review"
            value="{{ $stats['pendingReview'] ?? '0' }}"
            change="+3"
            change-type="neutral"
            icon="fas fa-clock"
            color="warning"
        />
    </div>

    <!-- Filters and Actions -->
    <x-unified-card title="Filters & Actions" icon="fas fa-filter" color="secondary">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-secondary-700 mb-2">Status</label>
                <select class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-secondary-700 mb-2">Type</label>
                <select class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All Types</option>
                    <option value="essay">Essay</option>
                    <option value="multiple_choice">Multiple Choice</option>
                    <option value="individual">Individual</option>
                    <option value="group">Group</option>
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
            
            <div class="flex items-end">
                <x-unified-button variant="primary" class="w-full">
                    <i class="fas fa-search"></i>
                    Apply Filters
                </x-unified-button>
            </div>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <x-unified-button variant="success" href="{{ route('task.create') }}">
                <i class="fas fa-plus"></i>
                Create New Task
            </x-unified-button>
            
            <x-unified-button variant="secondary" href="{{ route('task.export') }}">
                <i class="fas fa-download"></i>
                Export Data
            </x-unified-button>
            
            <x-unified-button variant="info" href="{{ route('task.analytics') }}">
                <i class="fas fa-chart-bar"></i>
                View Analytics
            </x-unified-button>
        </div>
    </x-unified-card>

    <!-- Tasks List -->
    <x-unified-card title="Tasks List" icon="fas fa-list" color="primary">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-secondary-200">
                <thead class="bg-secondary-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">
                            Task
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">
                            Type
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">
                            Class
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">
                            Due Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">
                            Progress
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-secondary-200">
                    @forelse($tasks ?? [] as $task)
                        <tr class="hover:bg-secondary-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-lg bg-primary-100 flex items-center justify-center">
                                            <i class="fas fa-tasks text-primary-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-secondary-900">
                                            {{ $task->judul ?? 'Sample Task' }}
                                        </div>
                                        <div class="text-sm text-secondary-500">
                                            {{ $task->deskripsi ?? 'Task description' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $task->tipe === 'essay' ? 'bg-blue-100 text-blue-800' : 
                                       ($task->tipe === 'multiple_choice' ? 'bg-green-100 text-green-800' : 
                                       'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($task->tipe ?? 'Individual') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-900">
                                {{ $task->kelas ?? 'Class A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $task->status === 'active' ? 'bg-green-100 text-green-800' : 
                                       ($task->status === 'completed' ? 'bg-blue-100 text-blue-800' : 
                                       'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($task->status ?? 'Active') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-900">
                                {{ $task->due_date ?? '2024-01-15' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-16 bg-secondary-200 rounded-full h-2 mr-2">
                                        <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $task->progress ?? 75 }}%"></div>
                                    </div>
                                    <span class="text-sm text-secondary-600">{{ $task->progress ?? 75 }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <x-unified-button variant="secondary" size="sm" href="{{ route('task.view', $task->id ?? 1) }}">
                                        <i class="fas fa-eye"></i>
                                    </x-unified-button>
                                    <x-unified-button variant="primary" size="sm" href="{{ route('task.edit', $task->id ?? 1) }}">
                                        <i class="fas fa-edit"></i>
                                    </x-unified-button>
                                    <x-unified-button variant="error" size="sm" onclick="deleteTask({{ $task->id ?? 1 }})">
                                        <i class="fas fa-trash"></i>
                                    </x-unified-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-center">
                                    <i class="fas fa-inbox text-secondary-300 text-4xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-secondary-900 mb-2">No tasks found</h3>
                                    <p class="text-secondary-500 mb-4">Get started by creating your first task.</p>
                                    <x-unified-button variant="primary" href="{{ route('task.create') }}">
                                        <i class="fas fa-plus"></i>
                                        Create Task
                                    </x-unified-button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($tasks) && count($tasks) > 0)
            <div class="px-6 py-4 border-t border-secondary-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-secondary-700">
                        Showing {{ count($tasks) }} of {{ count($tasks) }} tasks
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
            </div>
        @endif
    </x-unified-card>
</div>

@push('scripts')
<script>
function deleteTask(taskId) {
    if (confirm('Are you sure you want to delete this task?')) {
        // Implement delete functionality
        console.log('Deleting task:', taskId);
    }
}

// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterSelects = document.querySelectorAll('select');
    const applyButton = document.querySelector('[href*="filter"]');
    
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
