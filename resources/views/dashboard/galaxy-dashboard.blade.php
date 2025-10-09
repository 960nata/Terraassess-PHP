@extends('layouts.unified-layout')

@section('container')
<!-- Galaxy Background -->
<div class="galaxy-bg"></div>

<!-- Dashboard Content -->
<div class="space-y-8 galaxy-fade-in">
    <!-- Welcome Section -->
    <div class="galaxy-card">
        <div class="galaxy-card-body text-center py-12">
            <h1 class="text-4xl font-bold galaxy-text-gradient mb-4">
                Welcome to the Future of Learning
            </h1>
            <p class="text-xl text-white/70 mb-8 max-w-2xl mx-auto">
                Explore the infinite possibilities of IoT education in our cosmic learning environment. 
                Navigate through data streams and discover new knowledge across the digital universe.
            </p>
            <div class="flex justify-center gap-4">
                <button class="px-8 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-xl font-semibold hover:shadow-lg hover:shadow-purple-500/25 transition-all duration-300 transform hover:scale-105">
                    <i class="ph-rocket-launch mr-2"></i>
                    Launch Mission
                </button>
                <button class="px-8 py-3 border border-purple-400/50 text-white rounded-xl font-semibold hover:bg-purple-400/10 transition-all duration-300">
                    <i class="ph-compass mr-2"></i>
                    Explore Data
                </button>
            </div>
        </div>
    </div>
    
    <!-- Stats Grid -->
    <div class="galaxy-stats-grid">
        <div class="galaxy-stat-card galaxy-slide-up" style="animation-delay: 0.1s">
            <div class="galaxy-stat-icon primary">
                <i class="ph-student"></i>
            </div>
            <h3 class="galaxy-stat-value">1,247</h3>
            <p class="galaxy-stat-label">Total Students</p>
            <div class="galaxy-stat-change positive">
                <i class="ph-trend-up"></i>
                <span>+12% from last month</span>
            </div>
        </div>
        
        <div class="galaxy-stat-card galaxy-slide-up" style="animation-delay: 0.2s">
            <div class="galaxy-stat-icon success">
                <i class="ph-chalkboard-teacher"></i>
            </div>
            <h3 class="galaxy-stat-value">89</h3>
            <p class="galaxy-stat-label">Active Teachers</p>
            <div class="galaxy-stat-change positive">
                <i class="ph-trend-up"></i>
                <span>+5% from last month</span>
            </div>
        </div>
        
        <div class="galaxy-stat-card galaxy-slide-up" style="animation-delay: 0.3s">
            <div class="galaxy-stat-icon warning">
                <i class="ph-device-mobile"></i>
            </div>
            <h3 class="galaxy-stat-value">156</h3>
            <p class="galaxy-stat-label">IoT Devices</p>
            <div class="galaxy-stat-change positive">
                <i class="ph-trend-up"></i>
                <span>+8 new devices</span>
            </div>
        </div>
        
        <div class="galaxy-stat-card galaxy-slide-up" style="animation-delay: 0.4s">
            <div class="galaxy-stat-icon info">
                <i class="ph-chart-line"></i>
            </div>
            <h3 class="galaxy-stat-value">94.2%</h3>
            <p class="galaxy-stat-label">System Uptime</p>
            <div class="galaxy-stat-change positive">
                <i class="ph-trend-up"></i>
                <span>+2.1% improvement</span>
            </div>
        </div>
    </div>
    
    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Activity Chart -->
        <div class="galaxy-chart-container galaxy-scale-in" style="animation-delay: 0.5s">
            <h3 class="galaxy-chart-title">Learning Activity Over Time</h3>
            <div class="h-80 flex items-center justify-center">
                <div class="text-center">
                    <div class="w-32 h-32 mx-auto mb-4 rounded-full bg-gradient-to-br from-purple-500/20 to-blue-500/20 flex items-center justify-center">
                        <i class="ph-chart-line text-6xl text-purple-400"></i>
                    </div>
                    <p class="text-white/60">Interactive chart will be rendered here</p>
                    <p class="text-sm text-white/40 mt-2">Real-time data visualization</p>
                </div>
            </div>
        </div>
        
        <!-- Device Status -->
        <div class="galaxy-chart-container galaxy-scale-in" style="animation-delay: 0.6s">
            <h3 class="galaxy-chart-title">IoT Device Status</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-green-500/10 border border-green-500/20 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-white font-medium">Temperature Sensor Alpha</span>
                    </div>
                    <span class="text-green-400 text-sm font-semibold">Online</span>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-blue-500/10 border border-blue-500/20 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
                        <span class="text-white font-medium">Humidity Sensor Beta</span>
                    </div>
                    <span class="text-blue-400 text-sm font-semibold">Online</span>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-yellow-500/10 border border-yellow-500/20 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full animate-pulse"></div>
                        <span class="text-white font-medium">Pressure Sensor Gamma</span>
                    </div>
                    <span class="text-yellow-400 text-sm font-semibold">Maintenance</span>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-red-500/10 border border-red-500/20 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <span class="text-white font-medium">Motion Sensor Delta</span>
                    </div>
                    <span class="text-red-400 text-sm font-semibold">Offline</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="galaxy-card galaxy-fade-in" style="animation-delay: 0.7s">
        <div class="galaxy-card-header">
            <h3 class="galaxy-card-title">Recent Cosmic Activity</h3>
        </div>
        <div class="galaxy-card-body">
            <div class="space-y-4">
                <div class="flex items-start gap-4 p-4 hover:bg-white/5 rounded-xl transition-colors">
                    <div class="w-10 h-10 bg-green-500/20 rounded-full flex items-center justify-center">
                        <i class="ph-check-circle text-green-400"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-white font-medium">Assignment Submitted</h4>
                        <p class="text-white/60 text-sm">Sistem IoT siap digunakan</p>
                        <p class="text-white/40 text-xs mt-1">2 minutes ago</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-4 p-4 hover:bg-white/5 rounded-xl transition-colors">
                    <div class="w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center">
                        <i class="ph-file-plus text-blue-400"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-white font-medium">New Material Added</h4>
                        <p class="text-white/60 text-sm">"Arduino Programming Basics" uploaded to course library</p>
                        <p class="text-white/40 text-xs mt-1">1 hour ago</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-4 p-4 hover:bg-white/5 rounded-xl transition-colors">
                    <div class="w-10 h-10 bg-purple-500/20 rounded-full flex items-center justify-center">
                        <i class="ph-user-plus text-purple-400"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-white font-medium">New Student Joined</h4>
                        <p class="text-white/60 text-sm">Jane Smith registered for IoT Fundamentals course</p>
                        <p class="text-white/40 text-xs mt-1">3 hours ago</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-4 p-4 hover:bg-white/5 rounded-xl transition-colors">
                    <div class="w-10 h-10 bg-yellow-500/20 rounded-full flex items-center justify-center">
                        <i class="ph-warning text-yellow-400"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-white font-medium">System Alert</h4>
                        <p class="text-white/60 text-sm">Temperature sensor offline - maintenance required</p>
                        <p class="text-white/40 text-xs mt-1">5 hours ago</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="galaxy-card galaxy-scale-in" style="animation-delay: 0.8s">
            <div class="galaxy-card-body text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-purple-500/20 to-blue-500/20 rounded-2xl flex items-center justify-center">
                    <i class="ph-plus-circle text-3xl text-purple-400"></i>
                </div>
                <h4 class="text-white font-semibold mb-2">Create Assignment</h4>
                <p class="text-white/60 text-sm">Design new learning missions</p>
            </div>
        </div>
        
        <div class="galaxy-card galaxy-scale-in" style="animation-delay: 0.9s">
            <div class="galaxy-card-body text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-2xl flex items-center justify-center">
                    <i class="ph-file-plus text-3xl text-green-400"></i>
                </div>
                <h4 class="text-white font-semibold mb-2">Upload Material</h4>
                <p class="text-white/60 text-sm">Add cosmic knowledge resources</p>
            </div>
        </div>
        
        <div class="galaxy-card galaxy-scale-in" style="animation-delay: 1.0s">
            <div class="galaxy-card-body text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-blue-500/20 to-cyan-500/20 rounded-2xl flex items-center justify-center">
                    <i class="ph-device-mobile text-3xl text-blue-400"></i>
                </div>
                <h4 class="text-white font-semibold mb-2">Add IoT Device</h4>
                <p class="text-white/60 text-sm">Connect new sensors</p>
            </div>
        </div>
        
        <div class="galaxy-card galaxy-scale-in" style="animation-delay: 1.1s">
            <div class="galaxy-card-body text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-yellow-500/20 to-orange-500/20 rounded-2xl flex items-center justify-center">
                    <i class="ph-chart-bar text-3xl text-yellow-400"></i>
                </div>
                <h4 class="text-white font-semibold mb-2">View Analytics</h4>
                <p class="text-white/60 text-sm">Explore data insights</p>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/galaxy-theme.css') }}">
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects to cards
    const cards = document.querySelectorAll('.galaxy-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Add search functionality
    const searchInput = document.querySelector('.galaxy-search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                this.style.borderColor = 'rgba(138, 43, 226, 0.5)';
                this.style.boxShadow = '0 0 0 3px rgba(138, 43, 226, 0.1)';
            } else {
                this.style.borderColor = 'rgba(138, 43, 226, 0.2)';
                this.style.boxShadow = 'none';
            }
        });
    }
});
</script>
@endpush
@endsection
