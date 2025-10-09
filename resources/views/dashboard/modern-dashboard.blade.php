@extends('layouts.unified-layout')

@section('container')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Welcome back, {{ Auth::user()->name }}! Here's what's happening with your IoT learning platform.
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <button class="btn btn-outline">
                <i class="ph-download-simple mr-2"></i>
                Export Data
            </button>
            <button class="btn btn-primary">
                <i class="ph-plus mr-2"></i>
                Add New
            </button>
        </div>
    </div>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <x-modern-stats-card
        title="Total Students"
        value="1,234"
        change="+12%"
        change-type="positive"
        icon="ph-student"
        color="primary"
    />
    
    <x-modern-stats-card
        title="Active Assignments"
        value="45"
        change="+8%"
        change-type="positive"
        icon="ph-clipboard-text"
        color="success"
    />
    
    <x-modern-stats-card
        title="IoT Devices"
        value="23"
        change="+2"
        change-type="positive"
        icon="ph-device-mobile"
        color="accent"
    />
    
    <x-modern-stats-card
        title="Completion Rate"
        value="87%"
        change="+5%"
        change-type="positive"
        icon="ph-chart-line"
        color="warning"
    />
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Recent Activity -->
    <div class="lg:col-span-2">
        <x-modern-card class="h-full">
            <x-slot name="header">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
                    <button class="text-sm text-primary-600 hover:text-primary-500">View all</button>
                </div>
            </x-slot>
            
            <div class="space-y-4">
                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="ph-check text-green-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Assignment Submitted</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Sistem IoT siap digunakan</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">2 minutes ago</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="ph-file-plus text-blue-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">New Material Added</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">"Arduino Programming Basics" uploaded</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">1 hour ago</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="ph-warning text-yellow-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Device Alert</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Temperature sensor offline</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">3 hours ago</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="ph-user-plus text-purple-600 text-sm"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">New Student</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Jane Smith joined the platform</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">5 hours ago</p>
                    </div>
                </div>
            </div>
        </x-modern-card>
    </div>
    
    <!-- Quick Actions -->
    <div>
        <x-modern-card class="h-full">
            <x-slot name="header">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Quick Actions</h3>
            </x-slot>
            
            <div class="space-y-3">
                <button class="w-full flex items-center p-3 text-left text-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="ph-plus-circle text-primary-600 text-lg mr-3"></i>
                    <span class="font-medium">Create Assignment</span>
                </button>
                
                <button class="w-full flex items-center p-3 text-left text-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="ph-file-plus text-success-600 text-lg mr-3"></i>
                    <span class="font-medium">Upload Material</span>
                </button>
                
                <button class="w-full flex items-center p-3 text-left text-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="ph-device-mobile text-accent-600 text-lg mr-3"></i>
                    <span class="font-medium">Add IoT Device</span>
                </button>
                
                <button class="w-full flex items-center p-3 text-left text-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="ph-chart-bar text-warning-600 text-lg mr-3"></i>
                    <span class="font-medium">View Analytics</span>
                </button>
                
                <button class="w-full flex items-center p-3 text-left text-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="ph-gear text-gray-600 text-lg mr-3"></i>
                    <span class="font-medium">Settings</span>
                </button>
            </div>
        </x-modern-card>
    </div>
</div>

<!-- Charts and Analytics -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Performance Chart -->
    <x-modern-card>
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Student Performance</h3>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-full hover:bg-gray-200">Week</button>
                    <button class="px-3 py-1 text-xs font-medium text-white bg-primary-600 rounded-full">Month</button>
                    <button class="px-3 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-full hover:bg-gray-200">Year</button>
                </div>
            </div>
        </x-slot>
        
        <div class="h-64 flex items-center justify-center bg-gray-50 dark:bg-gray-800 rounded-lg">
            <div class="text-center">
                <i class="ph-chart-line text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-500 dark:text-gray-400">Chart will be rendered here</p>
            </div>
        </div>
    </x-modern-card>
    
    <!-- IoT Device Status -->
    <x-modern-card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">IoT Device Status</h3>
        </x-slot>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                    <span class="font-medium text-gray-900 dark:text-white">Temperature Sensor 1</span>
                </div>
                <span class="text-sm text-green-600 font-medium">Online</span>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                    <span class="font-medium text-gray-900 dark:text-white">Humidity Sensor 1</span>
                </div>
                <span class="text-sm text-green-600 font-medium">Online</span>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                    <span class="font-medium text-gray-900 dark:text-white">Pressure Sensor 1</span>
                </div>
                <span class="text-sm text-red-600 font-medium">Offline</span>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                    <span class="font-medium text-gray-900 dark:text-white">Motion Sensor 1</span>
                </div>
                <span class="text-sm text-yellow-600 font-medium">Maintenance</span>
            </div>
        </div>
    </x-modern-card>
</div>

<!-- Recent Assignments Table -->
<x-modern-card>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Assignments</h3>
            <button class="text-sm text-primary-600 hover:text-primary-500">View all</button>
        </div>
    </x-slot>
    
    <x-modern-table striped hover>
        <x-slot name="header">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assignment</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </x-slot>
        
        <x-slot name="body">
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">IoT Sensor Analysis</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Analyze temperature and humidity data</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">XII RPL 1</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">Dec 25, 2024</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <x-modern-badge variant="success">Active</x-modern-badge>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button class="text-primary-600 hover:text-primary-900 mr-3">Edit</button>
                    <button class="text-red-600 hover:text-red-900">Delete</button>
                </td>
            </tr>
            
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">Arduino Programming</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Create LED control system</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">XII RPL 2</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">Dec 30, 2024</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <x-modern-badge variant="warning">Pending</x-modern-badge>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button class="text-primary-600 hover:text-primary-900 mr-3">Edit</button>
                    <button class="text-red-600 hover:text-red-900">Delete</button>
                </td>
            </tr>
            
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">Data Visualization</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Create charts from sensor data</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">XI RPL 1</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">Jan 5, 2025</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <x-modern-badge variant="primary">Draft</x-modern-badge>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button class="text-primary-600 hover:text-primary-900 mr-3">Edit</button>
                    <button class="text-red-600 hover:text-red-900">Delete</button>
                </td>
            </tr>
        </x-slot>
    </x-modern-table>
</x-modern-card>
@endsection
