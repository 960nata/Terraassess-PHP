@extends('layouts.unified-layout-consistent')

@section('title', 'User Management - Terra Assessment')
@section('page-title', 'User Management')
@section('page-description', 'Kelola pengguna dan akses sistem')

@section('content')
<div class="space-y-6">
    <!-- Statistics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-unified-stats-card
            title="Total Users"
            value="{{ $stats['totalUsers'] ?? '0' }}"
            change="+12%"
            change-type="positive"
            icon="fas fa-users"
            color="primary"
        />
        
        <x-unified-stats-card
            title="Active Users"
            value="{{ $stats['activeUsers'] ?? '0' }}"
            change="+8%"
            change-type="positive"
            icon="fas fa-user-check"
            color="success"
        />
        
        <x-unified-stats-card
            title="Teachers"
            value="{{ $stats['totalTeachers'] ?? '0' }}"
            change="+3"
            change-type="positive"
            icon="fas fa-chalkboard-teacher"
            color="info"
        />
        
        <x-unified-stats-card
            title="Students"
            value="{{ $stats['totalStudents'] ?? '0' }}"
            change="+15%"
            change-type="positive"
            icon="fas fa-user-graduate"
            color="warning"
        />
    </div>

    <!-- Filters and Actions -->
    <x-unified-card title="Filters & Actions" icon="fas fa-filter" color="secondary">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-secondary-700 mb-2">Role</label>
                <select class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All Roles</option>
                    <option value="superadmin">Super Admin</option>
                    <option value="admin">Admin</option>
                    <option value="teacher">Teacher</option>
                    <option value="student">Student</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-secondary-700 mb-2">Status</label>
                <select class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="pending">Pending</option>
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
            <x-unified-button variant="success" href="{{ route('user.create') }}">
                <i class="fas fa-user-plus"></i>
                Add New User
            </x-unified-button>
            
            <x-unified-button variant="secondary" href="{{ route('user.import') }}">
                <i class="fas fa-upload"></i>
                Import Users
            </x-unified-button>
            
            <x-unified-button variant="info" href="{{ route('user.export') }}">
                <i class="fas fa-download"></i>
                Export Data
            </x-unified-button>
            
            <x-unified-button variant="warning" href="{{ route('user.bulk-actions') }}">
                <i class="fas fa-tasks"></i>
                Bulk Actions
            </x-unified-button>
        </div>
    </x-unified-card>

    <!-- Users Table -->
    <x-unified-card title="Users List" icon="fas fa-users" color="primary">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-secondary-200">
                <thead class="bg-secondary-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">
                            <input type="checkbox" class="rounded border-secondary-300 text-primary-600 focus:ring-primary-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">
                            User
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">
                            Role
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">
                            Class
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">
                            Last Login
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-secondary-200">
                    @forelse($users ?? [] as $user)
                        <tr class="hover:bg-secondary-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="rounded border-secondary-300 text-primary-600 focus:ring-primary-500">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center">
                                            <i class="fas fa-user text-primary-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-secondary-900">
                                            {{ $user->name ?? 'John Doe' }}
                                        </div>
                                        <div class="text-sm text-secondary-500">
                                            {{ $user->email ?? 'john.doe@example.com' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $user->role === 'superadmin' ? 'bg-purple-100 text-purple-800' : 
                                       ($user->role === 'admin' ? 'bg-blue-100 text-blue-800' : 
                                       ($user->role === 'teacher' ? 'bg-green-100 text-green-800' : 
                                       'bg-orange-100 text-orange-800')) }}">
                                    <i class="fas {{ $user->role === 'superadmin' ? 'fa-crown' : 
                                        ($user->role === 'admin' ? 'fa-user-shield' : 
                                        ($user->role === 'teacher' ? 'fa-chalkboard-teacher' : 
                                        'fa-user-graduate')) }} mr-1"></i>
                                    {{ ucfirst($user->role ?? 'Student') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-900">
                                {{ $user->class ?? 'Class A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 
                                       ($user->status === 'inactive' ? 'bg-red-100 text-red-800' : 
                                       'bg-yellow-100 text-yellow-800') }}">
                                    <div class="w-2 h-2 rounded-full mr-1 {{ $user->status === 'active' ? 'bg-green-500' : 
                                        ($user->status === 'inactive' ? 'bg-red-500' : 'bg-yellow-500') }}"></div>
                                    {{ ucfirst($user->status ?? 'Active') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-900">
                                {{ $user->last_login ?? '2024-01-15 10:30' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <x-unified-button variant="secondary" size="sm" href="{{ route('user.view', $user->id ?? 1) }}">
                                        <i class="fas fa-eye"></i>
                                    </x-unified-button>
                                    <x-unified-button variant="primary" size="sm" href="{{ route('user.edit', $user->id ?? 1) }}">
                                        <i class="fas fa-edit"></i>
                                    </x-unified-button>
                                    <x-unified-button variant="error" size="sm" onclick="deleteUser({{ $user->id ?? 1 }})">
                                        <i class="fas fa-trash"></i>
                                    </x-unified-button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-center">
                                    <i class="fas fa-users text-secondary-300 text-4xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-secondary-900 mb-2">No users found</h3>
                                    <p class="text-secondary-500 mb-4">Get started by adding your first user.</p>
                                    <x-unified-button variant="primary" href="{{ route('user.create') }}">
                                        <i class="fas fa-user-plus"></i>
                                        Add User
                                    </x-unified-button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($users) && count($users) > 0)
            <div class="px-6 py-4 border-t border-secondary-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-secondary-700">
                        Showing {{ count($users) }} of {{ count($users) }} users
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

    <!-- Role Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-unified-card title="Role Distribution" icon="fas fa-chart-pie" color="info">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-purple-500 rounded mr-3"></div>
                        <span class="text-sm text-secondary-700">Super Admin</span>
                    </div>
                    <span class="text-sm font-medium text-secondary-900">{{ $stats['totalSuperAdmins'] ?? '1' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-500 rounded mr-3"></div>
                        <span class="text-sm text-secondary-700">Admin</span>
                    </div>
                    <span class="text-sm font-medium text-secondary-900">{{ $stats['totalAdmins'] ?? '2' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-500 rounded mr-3"></div>
                        <span class="text-sm text-secondary-700">Teacher</span>
                    </div>
                    <span class="text-sm font-medium text-secondary-900">{{ $stats['totalTeachers'] ?? '15' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-orange-500 rounded mr-3"></div>
                        <span class="text-sm text-secondary-700">Student</span>
                    </div>
                    <span class="text-sm font-medium text-secondary-900">{{ $stats['totalStudents'] ?? '150' }}</span>
                </div>
            </div>
        </x-unified-card>

        <x-unified-card title="Recent Activity" icon="fas fa-history" color="success">
            <div class="space-y-4">
                @forelse($recentActivities ?? [] as $activity)
                    <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-secondary-50 transition-colors">
                        <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="{{ $activity['icon'] }} text-primary-600 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-secondary-900">{{ $activity['title'] }}</p>
                            <p class="text-xs text-secondary-500 mt-1">{{ $activity['description'] }}</p>
                            <p class="text-xs text-secondary-400 mt-1">{{ $activity['time'] }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-secondary-300 text-3xl mb-3"></i>
                        <p class="text-secondary-500">No recent activity</p>
                    </div>
                @endforelse
            </div>
        </x-unified-card>
    </div>
</div>

@push('scripts')
<script>
function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        // Implement delete functionality
        console.log('Deleting user:', userId);
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
    
    // Select all checkbox functionality
    const selectAllCheckbox = document.querySelector('thead input[type="checkbox"]');
    const rowCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    
    selectAllCheckbox.addEventListener('change', function() {
        rowCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
});
</script>
@endpush
@endsection
