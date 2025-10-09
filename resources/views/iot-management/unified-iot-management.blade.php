@extends('layouts.unified-layout-consistent')

@section('title', 'IoT Management - Terra Assessment')
@section('page-title', 'IoT Management')
@section('page-description', 'Kelola perangkat IoT dan data sensor')

@section('content')
<div class="space-y-6">
    <!-- Statistics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-unified-stats-card
            title="Total Devices"
            value="{{ $stats['totalDevices'] ?? '0' }}"
            change="+5"
            change-type="positive"
            icon="fas fa-microchip"
            color="primary"
        />
        
        <x-unified-stats-card
            title="Active Devices"
            value="{{ $stats['activeDevices'] ?? '0' }}"
            change="+3"
            change-type="positive"
            icon="fas fa-wifi"
            color="success"
        />
        
        <x-unified-stats-card
            title="Data Points"
            value="{{ $stats['dataPoints'] ?? '0' }}"
            change="+1.2K"
            change-type="positive"
            icon="fas fa-database"
            color="info"
        />
        
        <x-unified-stats-card
            title="Alerts"
            value="{{ $stats['alerts'] ?? '0' }}"
            change="+2"
            change-type="neutral"
            icon="fas fa-exclamation-triangle"
            color="warning"
        />
    </div>

    <!-- Device Status Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Device List -->
        <x-unified-card title="IoT Devices" icon="fas fa-microchip" color="primary">
            <div class="space-y-4">
                @forelse($devices ?? [] as $device)
                    <div class="flex items-center justify-between p-4 bg-secondary-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-microchip text-primary-600"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-secondary-900">{{ $device->name ?? 'Sensor Device' }}</h4>
                                <p class="text-sm text-secondary-500">{{ $device->type ?? 'Temperature Sensor' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $device->status === 'online' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <div class="w-2 h-2 rounded-full mr-1 {{ $device->status === 'online' ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                {{ ucfirst($device->status ?? 'Online') }}
                            </span>
                            <x-unified-button variant="secondary" size="sm" href="{{ route('iot.device.detail', $device->id ?? 1) }}">
                                <i class="fas fa-eye"></i>
                            </x-unified-button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-microchip text-secondary-300 text-3xl mb-3"></i>
                        <p class="text-secondary-500">No devices found</p>
                    </div>
                @endforelse
            </div>
        </x-unified-card>

        <!-- Real-time Data -->
        <x-unified-card title="Real-time Data" icon="fas fa-chart-line" color="success">
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ $realtimeData['temperature'] ?? '24.5' }}°C</div>
                        <div class="text-sm text-blue-500">Temperature</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">{{ $realtimeData['humidity'] ?? '65' }}%</div>
                        <div class="text-sm text-green-500">Humidity</div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-yellow-50 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600">{{ $realtimeData['pressure'] ?? '1013' }} hPa</div>
                        <div class="text-sm text-yellow-500">Pressure</div>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600">{{ $realtimeData['light'] ?? '450' }} lux</div>
                        <div class="text-sm text-purple-500">Light</div>
                    </div>
                </div>
                
                <div class="pt-4 border-t border-secondary-200">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-secondary-500">Last updated:</span>
                        <span class="text-secondary-900">{{ now()->format('H:i:s') }}</span>
                    </div>
                </div>
            </div>
        </x-unified-card>
    </div>

    <!-- Actions and Controls -->
    <x-unified-card title="Device Management" icon="fas fa-cogs" color="secondary">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-secondary-700 mb-2">Device Type</label>
                <select class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All Types</option>
                    <option value="temperature">Temperature Sensor</option>
                    <option value="humidity">Humidity Sensor</option>
                    <option value="pressure">Pressure Sensor</option>
                    <option value="light">Light Sensor</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-secondary-700 mb-2">Status</label>
                <select class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All Status</option>
                    <option value="online">Online</option>
                    <option value="offline">Offline</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-secondary-700 mb-2">Location</label>
                <select class="w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All Locations</option>
                    <option value="lab1">Lab 1</option>
                    <option value="lab2">Lab 2</option>
                    <option value="classroom">Classroom</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <x-unified-button variant="primary" class="w-full">
                    <i class="fas fa-search"></i>
                    Filter
                </x-unified-button>
            </div>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <x-unified-button variant="success" href="{{ route('iot.device.create') }}">
                <i class="fas fa-plus"></i>
                Add Device
            </x-unified-button>
            
            <x-unified-button variant="secondary" href="{{ route('iot.data.export') }}">
                <i class="fas fa-download"></i>
                Export Data
            </x-unified-button>
            
            <x-unified-button variant="info" href="{{ route('iot.analytics') }}">
                <i class="fas fa-chart-bar"></i>
                View Analytics
            </x-unified-button>
            
            <x-unified-button variant="warning" href="{{ route('iot.alerts') }}">
                <i class="fas fa-bell"></i>
                Manage Alerts
            </x-unified-button>
        </div>
    </x-unified-card>

    <!-- Recent Activity -->
    <x-unified-card title="Recent Activity" icon="fas fa-history" color="info">
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

@push('scripts')
<script>
// Real-time data update simulation
function updateRealTimeData() {
    // Simulate real-time data updates
    const temperature = (Math.random() * 10 + 20).toFixed(1);
    const humidity = Math.floor(Math.random() * 20 + 50);
    const pressure = Math.floor(Math.random() * 20 + 1000);
    const light = Math.floor(Math.random() * 200 + 300);
    
    // Update display (in real implementation, this would come from WebSocket or API)
    console.log('Updating real-time data:', { temperature, humidity, pressure, light });
}

// Update data every 5 seconds
setInterval(updateRealTimeData, 5000);

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
