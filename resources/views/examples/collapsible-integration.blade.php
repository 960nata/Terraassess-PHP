{{-- Example: Integrating Collapsible Sections into Dashboard --}}
@extends('layouts.unified-layout')

@section('container')
<div class="max-w-6xl mx-auto p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard with Collapsible Sections</h1>
        <p class="text-gray-600">Example of how to integrate collapsible sections into your dashboard.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Left Column --}}
        <div class="space-y-6">
            {{-- Quick Stats --}}
            <x-modern-card>
                <x-slot name="header">
                    <h3 class="text-lg font-semibold">Quick Stats</h3>
                </x-slot>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">1,234</div>
                        <div class="text-sm text-blue-800">Total Users</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">567</div>
                        <div class="text-sm text-green-800">Active Today</div>
                    </div>
                </div>
            </x-modern-card>

            {{-- Recent Activity with Collapsible Sections --}}
            <x-modern-card>
                <x-slot name="header">
                    <h3 class="text-lg font-semibold">Recent Activity</h3>
                </x-slot>
                
                <div class="space-y-2">
                    <x-modern-collapsible title="Today's Activities">
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded">
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                <div class="flex-1">
                                    <div class="text-sm font-medium">New user registered</div>
                                    <div class="text-xs text-gray-500">2 minutes ago</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded">
                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                <div class="flex-1">
                                    <div class="text-sm font-medium">System backup completed</div>
                                    <div class="text-xs text-gray-500">15 minutes ago</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded">
                                <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                                <div class="flex-1">
                                    <div class="text-sm font-medium">Database maintenance scheduled</div>
                                    <div class="text-xs text-gray-500">1 hour ago</div>
                                </div>
                            </div>
                        </div>
                    </x-modern-collapsible>

                    <x-modern-collapsible title="System Alerts">
                        <div class="space-y-3">
                            <div class="bg-red-50 border border-red-200 p-3 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="text-sm font-medium text-red-800">High CPU Usage</div>
                                </div>
                                <div class="text-xs text-red-600 mt-1">CPU usage is at 85%</div>
                            </div>
                            <div class="bg-yellow-50 border border-yellow-200 p-3 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="text-sm font-medium text-yellow-800">Storage Warning</div>
                                </div>
                                <div class="text-xs text-yellow-600 mt-1">Disk space is 80% full</div>
                            </div>
                        </div>
                    </x-modern-collapsible>
                </div>
            </x-modern-card>
        </div>

        {{-- Right Column --}}
        <div class="space-y-6">
            {{-- User Management with Collapsible Sections --}}
            <x-modern-card>
                <x-slot name="header">
                    <h3 class="text-lg font-semibold">User Management</h3>
                </x-slot>
                
                <div class="space-y-2">
                    <x-modern-collapsible title="Active Users" :open="true">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-medium">JD</div>
                                    <div>
                                        <div class="text-sm font-medium">John Doe</div>
                                        <div class="text-xs text-gray-500">Administrator</div>
                                    </div>
                                </div>
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            </div>
                            <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm font-medium">JS</div>
                                    <div>
                                        <div class="text-sm font-medium">Jane Smith</div>
                                        <div class="text-xs text-gray-500">Teacher</div>
                                    </div>
                                </div>
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            </div>
                            <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white text-sm font-medium">MJ</div>
                                    <div>
                                        <div class="text-sm font-medium">Mike Johnson</div>
                                        <div class="text-xs text-gray-500">Student</div>
                                    </div>
                                </div>
                                <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                            </div>
                        </div>
                    </x-modern-collapsible>

                    <x-modern-collapsible title="Recent Logins">
                        <div class="space-y-2">
                            <div class="text-xs text-gray-500 space-y-1">
                                <div>• john.doe@example.com - 2 minutes ago</div>
                                <div>• jane.smith@example.com - 5 minutes ago</div>
                                <div>• mike.johnson@example.com - 12 minutes ago</div>
                                <div>• admin@example.com - 1 hour ago</div>
                            </div>
                        </div>
                    </x-modern-collapsible>

                    <x-modern-collapsible title="User Statistics">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-gray-50 rounded">
                                <div class="text-lg font-bold text-gray-900">1,234</div>
                                <div class="text-xs text-gray-600">Total Users</div>
                            </div>
                            <div class="text-center p-3 bg-gray-50 rounded">
                                <div class="text-lg font-bold text-gray-900">89%</div>
                                <div class="text-xs text-gray-600">Active Rate</div>
                            </div>
                        </div>
                    </x-modern-collapsible>
                </div>
            </x-modern-card>

            {{-- Settings with Collapsible Sections --}}
            <x-modern-card>
                <x-slot name="header">
                    <h3 class="text-lg font-semibold">System Settings</h3>
                </x-slot>
                
                <div class="space-y-2">
                    <x-modern-collapsible title="General Settings">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-sm font-medium">Email Notifications</div>
                                    <div class="text-xs text-gray-500">Receive email updates</div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-sm font-medium">Dark Mode</div>
                                    <div class="text-xs text-gray-500">Use dark theme</div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>
                    </x-modern-collapsible>

                    <x-modern-collapsible title="Security Settings">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-sm font-medium">Two-Factor Authentication</div>
                                    <div class="text-xs text-gray-500">Add extra security</div>
                                </div>
                                <x-modern-button variant="outline" size="sm">Enable</x-modern-button>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-sm font-medium">Session Timeout</div>
                                    <div class="text-xs text-gray-500">Auto-logout after inactivity</div>
                                </div>
                                <select class="text-xs border border-gray-300 rounded px-2 py-1">
                                    <option>15 minutes</option>
                                    <option>30 minutes</option>
                                    <option>1 hour</option>
                                </select>
                            </div>
                        </div>
                    </x-modern-collapsible>
                </div>
            </x-modern-card>
        </div>
    </div>
</div>
@endsection
