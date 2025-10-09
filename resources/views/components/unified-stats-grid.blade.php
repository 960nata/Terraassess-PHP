@props([
    'stats' => []
])

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    @foreach($stats as $stat)
        <div class="unified-card">
            <div class="unified-card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-secondary-600">{{ $stat['title'] }}</p>
                        <p class="text-2xl font-bold text-secondary-900">{{ $stat['value'] }}</p>
                        @if(isset($stat['change']))
                            <div class="flex items-center mt-1">
                                <span class="text-sm {{ $stat['change_type'] === 'positive' ? 'text-green-600' : 'text-red-600' }}">
                                    <i class="fas fa-arrow-{{ $stat['change_type'] === 'positive' ? 'up' : 'down' }} mr-1"></i>
                                    {{ $stat['change'] }}
                                </span>
                                <span class="text-sm text-secondary-500 ml-2">vs bulan lalu</span>
                            </div>
                        @endif
                    </div>
                    <div class="w-12 h-12 bg-{{ $stat['color'] ?? 'primary' }}-100 rounded-lg flex items-center justify-center">
                        <i class="{{ $stat['icon'] ?? 'fas fa-chart-bar' }} text-{{ $stat['color'] ?? 'primary' }}-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
