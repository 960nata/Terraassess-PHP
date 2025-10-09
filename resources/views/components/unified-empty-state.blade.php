@props([
    'icon' => 'fas fa-inbox',
    'title' => 'No data available',
    'description' => 'There is no data to display at the moment.',
    'action' => null
])

<div class="text-center py-12">
    <div class="mx-auto w-24 h-24 bg-secondary-100 rounded-full flex items-center justify-center mb-4">
        <i class="{{ $icon }} text-secondary-400 text-2xl"></i>
    </div>
    <h3 class="text-lg font-medium text-secondary-900 mb-2">{{ $title }}</h3>
    <p class="text-secondary-500 mb-6">{{ $description }}</p>
    
    @if($action)
        <div class="flex justify-center">
            {{ $action }}
        </div>
    @endif
    
    @if(isset($slot))
        <div class="mt-4">
            {{ $slot }}
        </div>
    @endif
</div>
