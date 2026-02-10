@extends('layouts.unified-layout')

@section('container')
<div class="max-w-6xl mx-auto p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dropdown Integration Example</h1>
        <p class="text-gray-600">Real-world examples of dropdown components integrated into application interfaces.</p>
    </div>

    {{-- Top Navigation Bar Example --}}
    <x-modern-card class="mb-8">
        <x-slot name="header">
            <h3 class="text-lg font-semibold">Top Navigation Bar</h3>
        </x-slot>
        
        <div class="bg-white border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                {{-- Logo/Brand --}}
                <div class="flex items-center space-x-4">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i class="ph-house text-white text-lg"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-900">MyApp</span>
                </div>
                
                {{-- Navigation Links --}}
                <div class="hidden md:flex items-center space-x-6">
                    <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">Dashboard</a>
                    <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">Projects</a>
                    <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">Analytics</a>
                    <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">Reports</a>
                </div>
                
                {{-- Right Side Actions --}}
                <div class="flex items-center space-x-4">
                    {{-- Search --}}
                    <button class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                        <i class="ph-magnifying-glass text-lg"></i>
                    </button>
                    
                    {{-- Notifications --}}
                    <x-modern-dropdown variant="notifications" position="bottom-right">
                        <x-slot name="trigger">
                            <button class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                                <i class="fas fa-bell text-lg"></i>
                                <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                            </button>
                        </x-slot>
                        
                        <div class="dropdown-header">Recent Notifications</div>
                        
                        <div class="dropdown-notification-item">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="ph-user-plus text-blue-600 text-sm"></i>
                            </div>
                            <div class="dropdown-notification-content">
                                <div class="dropdown-notification-title">New User Registration</div>
                                <div class="dropdown-notification-text">John Smith has joined your team</div>
                                <div class="dropdown-notification-time">5 minutes ago</div>
                            </div>
                            <div class="dropdown-notification-dot"></div>
                        </div>
                        
                        <div class="dropdown-notification-item">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="ph-check-circle text-green-600 text-sm"></i>
                            </div>
                            <div class="dropdown-notification-content">
                                <div class="dropdown-notification-title">Task Completed</div>
                                <div class="dropdown-notification-text">"Update documentation" has been completed</div>
                                <div class="dropdown-notification-time">1 hour ago</div>
                            </div>
                            <div class="dropdown-notification-dot"></div>
                        </div>
                        
                        <div class="dropdown-notification-item">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                <i class="ph-warning text-yellow-600 text-sm"></i>
                            </div>
                            <div class="dropdown-notification-content">
                                <div class="dropdown-notification-title">System Alert</div>
                                <div class="dropdown-notification-text">High memory usage detected</div>
                                <div class="dropdown-notification-time">2 hours ago</div>
                            </div>
                        </div>
                        
                        <div class="dropdown-divider"></div>
                        <button class="dropdown-item">
                            <span class="dropdown-item-text">View all notifications</span>
                        </button>
                    </x-modern-dropdown>
                    
                    {{-- Profile Dropdown --}}
                    <x-modern-dropdown variant="profile" position="bottom-right">
                        <x-slot name="trigger">
                            <button class="flex items-center space-x-2 p-1 hover:bg-gray-100 rounded-lg transition-colors">
                                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=32&h=32&fit=crop&crop=face" 
                                     alt="Profile" class="w-8 h-8 rounded-full">
                                <i class="ph-caret-down text-gray-500 text-sm"></i>
                            </button>
                        </x-slot>
                        
                        <div class="dropdown-profile-header">
                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=48&h=48&fit=crop&crop=face" 
                                 alt="Profile" class="dropdown-profile-avatar">
                            <div class="dropdown-profile-name">John Doe</div>
                            <div class="dropdown-profile-email">john.doe@company.com</div>
                        </div>
                        
                        <button class="dropdown-item">
                            <i class="ph-user dropdown-item-icon"></i>
                            <span class="dropdown-item-text">My Profile</span>
                        </button>
                        
                        <button class="dropdown-item">
                            <i class="ph-gear dropdown-item-icon"></i>
                            <span class="dropdown-item-text">Account Settings</span>
                        </button>
                        
                        <button class="dropdown-item">
                            <i class="ph-credit-card dropdown-item-icon"></i>
                            <span class="dropdown-item-text">Billing & Plans</span>
                        </button>
                        
                        <div class="dropdown-divider"></div>
                        
                        <button class="dropdown-item">
                            <i class="ph-question dropdown-item-icon"></i>
                            <span class="dropdown-item-text">Help & Support</span>
                        </button>
                        
                        <button class="dropdown-item">
                            <i class="ph-sign-out dropdown-item-icon"></i>
                            <span class="dropdown-item-text">Sign Out</span>
                        </button>
                    </x-modern-dropdown>
                </div>
            </div>
        </div>
    </x-modern-card>

    {{-- Data Table with Actions --}}
    <x-modern-card class="mb-8">
        <x-slot name="header">
            <h3 class="text-lg font-semibold">Data Table with Action Dropdowns</h3>
        </x-slot>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-medium text-gray-700">Name</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-700">Email</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-700">Role</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-700">Status</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-3">
                                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=32&h=32&fit=crop&crop=face" 
                                     alt="User" class="w-8 h-8 rounded-full">
                                <span class="font-medium text-gray-900">John Doe</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-600">john.doe@example.com</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Admin
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <x-modern-dropdown position="bottom-right">
                                <button class="dropdown-item">
                                    <i class="ph-eye dropdown-item-icon"></i>
                                    <span class="dropdown-item-text">View Details</span>
                                </button>
                                <button class="dropdown-item">
                                    <i class="ph-pencil dropdown-item-icon"></i>
                                    <span class="dropdown-item-text">Edit User</span>
                                </button>
                                <button class="dropdown-item">
                                    <i class="ph-key dropdown-item-icon"></i>
                                    <span class="dropdown-item-text">Reset Password</span>
                                </button>
                                <div class="dropdown-divider"></div>
                                <button class="dropdown-item text-red-600">
                                    <i class="ph-trash dropdown-item-icon"></i>
                                    <span class="dropdown-item-text">Delete User</span>
                                </button>
                            </x-modern-dropdown>
                        </td>
                    </tr>
                    
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-3">
                                <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?w=32&h=32&fit=crop&crop=face" 
                                     alt="User" class="w-8 h-8 rounded-full">
                                <span class="font-medium text-gray-900">Jane Smith</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-600">jane.smith@example.com</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                Editor
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <x-modern-dropdown position="bottom-right">
                                <button class="dropdown-item">
                                    <i class="ph-eye dropdown-item-icon"></i>
                                    <span class="dropdown-item-text">View Details</span>
                                </button>
                                <button class="dropdown-item">
                                    <i class="ph-pencil dropdown-item-icon"></i>
                                    <span class="dropdown-item-text">Edit User</span>
                                </button>
                                <button class="dropdown-item">
                                    <i class="ph-key dropdown-item-icon"></i>
                                    <span class="dropdown-item-text">Reset Password</span>
                                </button>
                                <div class="dropdown-divider"></div>
                                <button class="dropdown-item text-red-600">
                                    <i class="ph-trash dropdown-item-icon"></i>
                                    <span class="dropdown-item-text">Delete User</span>
                                </button>
                            </x-modern-dropdown>
                        </td>
                    </tr>
                    
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-3">
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=32&h=32&fit=crop&crop=face" 
                                     alt="User" class="w-8 h-8 rounded-full">
                                <span class="font-medium text-gray-900">Mike Johnson</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-600">mike.johnson@example.com</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Viewer
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <x-modern-dropdown position="bottom-right">
                                <button class="dropdown-item">
                                    <i class="ph-eye dropdown-item-icon"></i>
                                    <span class="dropdown-item-text">View Details</span>
                                </button>
                                <button class="dropdown-item">
                                    <i class="ph-pencil dropdown-item-icon"></i>
                                    <span class="dropdown-item-text">Edit User</span>
                                </button>
                                <button class="dropdown-item">
                                    <i class="ph-key dropdown-item-icon"></i>
                                    <span class="dropdown-item-text">Reset Password</span>
                                </button>
                                <div class="dropdown-divider"></div>
                                <button class="dropdown-item text-red-600">
                                    <i class="ph-trash dropdown-item-icon"></i>
                                    <span class="dropdown-item-text">Delete User</span>
                                </button>
                            </x-modern-dropdown>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </x-modern-card>

    {{-- Card with Action Menu --}}
    <x-modern-card class="mb-8">
        <x-slot name="header">
            <h3 class="text-lg font-semibold">Card with Action Menu</h3>
        </x-slot>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Project Card 1 --}}
            <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="ph-folder text-blue-600 text-xl"></i>
                    </div>
                    <x-modern-dropdown position="bottom-right">
                        <button class="dropdown-item">
                            <i class="ph-eye dropdown-item-icon"></i>
                            <span class="dropdown-item-text">View Project</span>
                        </button>
                        <button class="dropdown-item">
                            <i class="ph-pencil dropdown-item-icon"></i>
                            <span class="dropdown-item-text">Edit Project</span>
                        </button>
                        <button class="dropdown-item">
                            <i class="ph-users dropdown-item-icon"></i>
                            <span class="dropdown-item-text">Manage Team</span>
                        </button>
                        <div class="dropdown-divider"></div>
                        <button class="dropdown-item">
                            <i class="ph-download dropdown-item-icon"></i>
                            <span class="dropdown-item-text">Export Data</span>
                        </button>
                        <button class="dropdown-item text-red-600">
                            <i class="ph-trash dropdown-item-icon"></i>
                            <span class="dropdown-item-text">Delete Project</span>
                        </button>
                    </x-modern-dropdown>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">E-commerce Platform</h3>
                <p class="text-gray-600 text-sm mb-4">Building a modern e-commerce platform with React and Node.js</p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">In Progress</span>
                    </div>
                    <span class="text-sm text-gray-500">Due in 5 days</span>
                </div>
            </div>
            
            {{-- Project Card 2 --}}
            <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="ph-mobile text-green-600 text-xl"></i>
                    </div>
                    <x-modern-dropdown position="bottom-right">
                        <button class="dropdown-item">
                            <i class="ph-eye dropdown-item-icon"></i>
                            <span class="dropdown-item-text">View Project</span>
                        </button>
                        <button class="dropdown-item">
                            <i class="ph-pencil dropdown-item-icon"></i>
                            <span class="dropdown-item-text">Edit Project</span>
                        </button>
                        <button class="dropdown-item">
                            <i class="ph-users dropdown-item-icon"></i>
                            <span class="dropdown-item-text">Manage Team</span>
                        </button>
                        <div class="dropdown-divider"></div>
                        <button class="dropdown-item">
                            <i class="ph-download dropdown-item-icon"></i>
                            <span class="dropdown-item-text">Export Data</span>
                        </button>
                        <button class="dropdown-item text-red-600">
                            <i class="ph-trash dropdown-item-icon"></i>
                            <span class="dropdown-item-text">Delete Project</span>
                        </button>
                    </x-modern-dropdown>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Mobile App</h3>
                <p class="text-gray-600 text-sm mb-4">Cross-platform mobile application for iOS and Android</p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">Planning</span>
                    </div>
                    <span class="text-sm text-gray-500">Due in 12 days</span>
                </div>
            </div>
            
            {{-- Project Card 3 --}}
            <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="ph-chart-bar text-purple-600 text-xl"></i>
                    </div>
                    <x-modern-dropdown position="bottom-right">
                        <button class="dropdown-item">
                            <i class="ph-eye dropdown-item-icon"></i>
                            <span class="dropdown-item-text">View Project</span>
                        </button>
                        <button class="dropdown-item">
                            <i class="ph-pencil dropdown-item-icon"></i>
                            <span class="dropdown-item-text">Edit Project</span>
                        </button>
                        <button class="dropdown-item">
                            <i class="ph-users dropdown-item-icon"></i>
                            <span class="dropdown-item-text">Manage Team</span>
                        </button>
                        <div class="dropdown-divider"></div>
                        <button class="dropdown-item">
                            <i class="ph-download dropdown-item-icon"></i>
                            <span class="dropdown-item-text">Export Data</span>
                        </button>
                        <button class="dropdown-item text-red-600">
                            <i class="ph-trash dropdown-item-icon"></i>
                            <span class="dropdown-item-text">Delete Project</span>
                        </button>
                    </x-modern-dropdown>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Analytics Dashboard</h3>
                <p class="text-gray-600 text-sm mb-4">Real-time analytics and reporting dashboard</p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">Review</span>
                    </div>
                    <span class="text-sm text-gray-500">Due in 3 days</span>
                </div>
            </div>
        </div>
    </x-modern-card>

    {{-- Filter Dropdowns --}}
    <x-modern-card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold">Filter and Sort Dropdowns</h3>
        </x-slot>
        
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex items-center space-x-2">
                <span class="text-sm font-medium text-gray-700">Filter by:</span>
                <x-modern-dropdown position="bottom-left">
                    <button class="flex items-center space-x-2 px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <span class="text-sm text-gray-700">Status</span>
                        <i class="ph-caret-down text-gray-500 text-sm"></i>
                    </button>
                    <button class="dropdown-item">
                        <span class="dropdown-item-text">All Status</span>
                    </button>
                    <button class="dropdown-item">
                        <span class="dropdown-item-text">Active</span>
                    </button>
                    <button class="dropdown-item">
                        <span class="dropdown-item-text">Inactive</span>
                    </button>
                    <button class="dropdown-item">
                        <span class="dropdown-item-text">Pending</span>
                    </button>
                </x-modern-dropdown>
            </div>
            
            <div class="flex items-center space-x-2">
                <span class="text-sm font-medium text-gray-700">Sort by:</span>
                <x-modern-dropdown position="bottom-left">
                    <button class="flex items-center space-x-2 px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <span class="text-sm text-gray-700">Name</span>
                        <i class="ph-caret-down text-gray-500 text-sm"></i>
                    </button>
                    <button class="dropdown-item">
                        <span class="dropdown-item-text">Name (A-Z)</span>
                    </button>
                    <button class="dropdown-item">
                        <span class="dropdown-item-text">Name (Z-A)</span>
                    </button>
                    <button class="dropdown-item">
                        <span class="dropdown-item-text">Date Created</span>
                    </button>
                    <button class="dropdown-item">
                        <span class="dropdown-item-text">Last Modified</span>
                    </button>
                </x-modern-dropdown>
            </div>
            
            <div class="flex items-center space-x-2">
                <span class="text-sm font-medium text-gray-700">View:</span>
                <x-modern-dropdown position="bottom-left">
                    <button class="flex items-center space-x-2 px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-list text-gray-500"></i>
                        <span class="text-sm text-gray-700">List</span>
                        <i class="ph-caret-down text-gray-500 text-sm"></i>
                    </button>
                    <button class="dropdown-item">
                        <i class="fas fa-list dropdown-item-icon"></i>
                        <span class="dropdown-item-text">List View</span>
                    </button>
                    <button class="dropdown-item">
                        <i class="ph-grid-four dropdown-item-icon"></i>
                        <span class="dropdown-item-text">Grid View</span>
                    </button>
                    <button class="dropdown-item">
                        <i class="ph-table dropdown-item-icon"></i>
                        <span class="dropdown-item-text">Table View</span>
                    </button>
                </x-modern-dropdown>
            </div>
        </div>
    </x-modern-card>
</div>
@endsection
