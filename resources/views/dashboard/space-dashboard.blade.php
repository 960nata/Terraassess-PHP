@extends('layouts.unified-layout')

@section('container')
<!-- Stats Overview -->
<div class="space-stats-grid">
    <x-space-stats-card
        title="Total Students"
        value="1,234"
        change="+12%"
        change-type="positive"
        icon="ph-student"
    />
    
    <x-space-stats-card
        title="Active Assignments"
        value="45"
        change="+8%"
        change-type="positive"
        icon="ph-clipboard-text"
    />
    
    <x-space-stats-card
        title="IoT Devices"
        value="23"
        change="+2"
        change-type="positive"
        icon="ph-device-mobile"
    />
    
    <x-space-stats-card
        title="Completion Rate"
        value="87%"
        change="+5%"
        change-type="positive"
        icon="ph-chart-line"
    />
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Recent Activities -->
    <div class="lg:col-span-2">
        <div class="glass-card space-slide-up">
            <div class="p-6 border-b border-white/10">
                <h3 class="text-lg font-semibold text-white">Recent Activities</h3>
                <p class="text-sm text-gray-400">Latest updates from your IoT learning platform</p>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center gap-4 p-4 bg-white/5 rounded-lg">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-blue-500 rounded-lg flex items-center justify-center">
                            <i class="ph-user-plus text-white"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-white font-medium">New student registered</div>
                            <div class="text-sm text-gray-400">Sistem IoT siap digunakan</div>
                        </div>
                        <div class="text-xs text-gray-500">2 min ago</div>
                    </div>
                    
                    <div class="flex items-center gap-4 p-4 bg-white/5 rounded-lg">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-cyan-500 rounded-lg flex items-center justify-center">
                            <i class="ph-check-circle text-white"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-white font-medium">Assignment completed</div>
                            <div class="text-sm text-gray-400">Sarah completed IoT Sensors module</div>
                        </div>
                        <div class="text-xs text-gray-500">15 min ago</div>
                    </div>
                    
                    <div class="flex items-center gap-4 p-4 bg-white/5 rounded-lg">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-pink-500 rounded-lg flex items-center justify-center">
                            <i class="ph-warning text-white"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-white font-medium">Device offline</div>
                            <div class="text-sm text-gray-400">Arduino device #3 is not responding</div>
                        </div>
                        <div class="text-xs text-gray-500">1 hour ago</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="space-slide-up">
        <div class="glass-card">
            <div class="p-6 border-b border-white/10">
                <h3 class="text-lg font-semibold text-white">Quick Actions</h3>
                <p class="text-sm text-gray-400">Common tasks and shortcuts</p>
            </div>
            <div class="p-6 space-y-3">
                <a href="{{ route('tugas.create') }}" class="space-btn space-btn-primary w-full justify-center">
                    <i class="ph-plus"></i>
                    <span>New Assignment</span>
                </a>
                
                <a href="{{ route('materi.create') }}" class="space-btn w-full justify-center">
                    <i class="ph-book-open"></i>
                    <span>Add Material</span>
                </a>
                
                <a href="{{ route('ujian.create') }}" class="space-btn w-full justify-center">
                    <i class="ph-exam"></i>
                    <span>Create Exam</span>
                </a>
                
                <a href="{{ route('settings') }}" class="space-btn w-full justify-center">
                    <i class="ph-gear"></i>
                    <span>Settings</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Performance Chart -->
    <div class="glass-card space-slide-up">
        <div class="p-6 border-b border-white/10">
            <h3 class="text-lg font-semibold text-white">Performance Overview</h3>
            <p class="text-sm text-gray-400">Student progress and engagement metrics</p>
        </div>
        <div class="p-6">
            <div class="h-64 flex items-center justify-center bg-white/5 rounded-lg">
                <div class="text-center">
                    <i class="ph-chart-bar text-4xl text-gray-400 mb-2"></i>
                    <p class="text-gray-400">Chart will be rendered here</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Device Status -->
    <div class="glass-card space-slide-up">
        <div class="p-6 border-b border-white/10">
            <h3 class="text-lg font-semibold text-white">Device Status</h3>
            <p class="text-sm text-gray-400">IoT devices connectivity and health</p>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                        <span class="text-white">Arduino Uno #1</span>
                    </div>
                    <span class="text-sm text-green-400">Online</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                        <span class="text-white">Raspberry Pi #2</span>
                    </div>
                    <span class="text-sm text-green-400">Online</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                        <span class="text-white">ESP32 #3</span>
                    </div>
                    <span class="text-sm text-red-400">Offline</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                        <span class="text-white">Sensor Hub #4</span>
                    </div>
                    <span class="text-sm text-yellow-400">Maintenance</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
