@extends('layouts.unified-layout-consistent')

@section('title', 'Terra Assessment - Dashboard')

@section('page-title', 'Dashboard')
@section('page-description', 'Selamat datang di Terra Assessment')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="unified-card">
        <div class="unified-card-body">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-secondary-900">
                        Selamat datang, {{ Auth::user()->name }}!
                    </h2>
                    <p class="text-secondary-600 mt-1">
                        {{ $roleName }} - {{ now()->format('l, d F Y') }}
                    </p>
                </div>
                <div class="hidden md:block">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-satellite-dish text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @if($roleId == 1 || $roleId == 2)
            <!-- Super Admin & Admin Stats -->
            <x-unified-stats-card
                title="Total Users"
                value="{{ $stats['totalUsers'] ?? '0' }}"
                change="+12%"
                change-type="positive"
                icon="fas fa-users"
                color="primary"
                href="{{ route('user-management') }}"
            />
            
            <x-unified-stats-card
                title="Active Classes"
                value="{{ $stats['totalClasses'] ?? '0' }}"
                change="+5%"
                change-type="positive"
                icon="fas fa-chalkboard"
                color="success"
                href="{{ route('class-management') }}"
            />
            
            <x-unified-stats-card
                title="Total Tasks"
                value="{{ $stats['totalTasks'] ?? '0' }}"
                change="+8%"
                change-type="positive"
                icon="fas fa-tasks"
                color="warning"
                href="{{ $roleId == 1 ? route('superadmin.task-management') : route('admin.task-management') }}"
            />
            
            <x-unified-stats-card
                title="IoT Devices"
                value="{{ $stats['totalIotDevices'] ?? '0' }}"
                change="+3"
                change-type="positive"
                icon="fas fa-microchip"
                color="info"
                href="{{ $roleId == 1 ? route('superadmin.iot-management') : route('admin.iot-management') }}"
            />
        @elseif($roleId == 3)
            <!-- Teacher Stats -->
            <x-unified-stats-card
                title="My Students"
                value="{{ $stats['totalStudents'] ?? '0' }}"
                change="+2"
                change-type="positive"
                icon="fas fa-user-graduate"
                color="primary"
            />
            
            <x-unified-stats-card
                title="Active Tasks"
                value="{{ $stats['activeTasks'] ?? '0' }}"
                change="+5"
                change-type="positive"
                icon="fas fa-tasks"
                color="success"
                href="{{ route('teacher.task-management') }}"
            />
            
            <x-unified-stats-card
                title="Materials"
                value="{{ $stats['totalMaterials'] ?? '0' }}"
                change="+3"
                change-type="positive"
                icon="fas fa-file-alt"
                color="warning"
                href="{{ route('teacher.material-management') }}"
            />
            
            <x-unified-stats-card
                title="IoT Projects"
                value="{{ $stats['iotProjects'] ?? '0' }}"
                change="+1"
                change-type="positive"
                icon="fas fa-microchip"
                color="info"
                href="{{ route('teacher.iot') }}"
            />
        @elseif($roleId == 4)
            <!-- Student Stats -->
            <x-unified-stats-card
                title="Pending Tasks"
                value="{{ $stats['pendingTasks'] ?? '0' }}"
                change=""
                change-type="neutral"
                icon="fas fa-clock"
                color="warning"
                href="{{ route('student.task-management') }}"
            />
            
            <x-unified-stats-card
                title="Completed Tasks"
                value="{{ $stats['completedTasks'] ?? '0' }}"
                change="+2"
                change-type="positive"
                icon="fas fa-check-circle"
                color="success"
                href="{{ route('student.task-management') }}"
            />
            
            <x-unified-stats-card
                title="My Score"
                value="{{ $stats['averageScore'] ?? '0' }}%"
                change="+5%"
                change-type="positive"
                icon="fas fa-chart-line"
                color="primary"
            />
            
            <x-unified-stats-card
                title="IoT Projects"
                value="{{ $stats['iotProjects'] ?? '0' }}"
                change="+1"
                change-type="positive"
                icon="fas fa-microchip"
                color="info"
                href="{{ route('student.iot-management') }}"
            />
        @endif
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Activity -->
        <div class="lg:col-span-2">
            <x-unified-card title="Recent Activity" icon="fas fa-history" color="primary">
                <div class="space-y-4">
                    @forelse($recentActivities ?? [] as $activity)
                        <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-secondary-50 transition-colors">
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
                            <p class="text-secondary-500">Tidak ada aktivitas terbaru</p>
                        </div>
                    @endforelse
                </div>
            </x-unified-card>
        </div>

        <!-- Quick Actions -->
        <div>
            <x-unified-card title="Quick Actions" icon="fas fa-bolt" color="warning">
                <div class="space-y-3">
                    @if($roleId == 1 || $roleId == 2)
                        <x-unified-button variant="primary" size="sm" class="w-full justify-start" href="{{ route('user-management') }}">
                            <i class="fas fa-user-plus"></i>
                            Add New User
                        </x-unified-button>
                        
                        <x-unified-button variant="success" size="sm" class="w-full justify-start" href="{{ $roleId == 1 ? route('superadmin.task-management') : route('admin.task-management') }}">
                            <i class="fas fa-plus"></i>
                            Create Task
                        </x-unified-button>
                        
                        <x-unified-button variant="info" size="sm" class="w-full justify-start" href="{{ route('reports') }}">
                            <i class="fas fa-chart-bar"></i>
                            View Reports
                        </x-unified-button>
                    @elseif($roleId == 3)
                        <x-unified-button variant="primary" size="sm" class="w-full justify-start" href="{{ route('teacher.task-management') }}">
                            <i class="fas fa-plus"></i>
                            Create Task
                        </x-unified-button>
                        
                        <x-unified-button variant="success" size="sm" class="w-full justify-start" href="{{ route('teacher.material-management') }}">
                            <i class="fas fa-file-plus"></i>
                            Add Material
                        </x-unified-button>
                        
                        <x-unified-button variant="info" size="sm" class="w-full justify-start" href="{{ route('teacher.iot') }}">
                            <i class="fas fa-microchip"></i>
                            IoT Management
                        </x-unified-button>
                    @elseif($roleId == 4)
                        <x-unified-button variant="primary" size="sm" class="w-full justify-start" href="{{ route('student.task-management') }}">
                            <i class="fas fa-tasks"></i>
                            View Tasks
                        </x-unified-button>
                        
                        <x-unified-button variant="success" size="sm" class="w-full justify-start" href="{{ route('student.exam-management') }}">
                            <i class="fas fa-clipboard-check"></i>
                            Take Exam
                        </x-unified-button>
                        
                        <x-unified-button variant="info" size="sm" class="w-full justify-start" href="{{ route('student.iot-management') }}">
                            <i class="fas fa-microchip"></i>
                            IoT Projects
                        </x-unified-button>
                    @endif
                </div>
            </x-unified-card>
        </div>
    </div>

    <!-- Notifications -->
    @if(isset($notifications) && count($notifications) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <x-unified-card title="Notifications" icon="fas fa-bell" color="info">
                <div class="space-y-3">
                    @foreach($notifications as $notification)
                        <div class="flex items-start gap-3 p-3 rounded-lg {{ $notification['unread'] ? 'bg-info-50 border border-info-200' : 'bg-secondary-50' }}">
                            <div class="w-2 h-2 bg-info-500 rounded-full mt-2 flex-shrink-0 {{ $notification['unread'] ? '' : 'opacity-0' }}"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-secondary-900">{{ $notification['title'] }}</p>
                                <p class="text-xs text-secondary-600 mt-1">{{ $notification['message'] }}</p>
                                <p class="text-xs text-secondary-400 mt-1">{{ $notification['time'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-unified-card>
        </div>
    @endif
</div>
@endsection
