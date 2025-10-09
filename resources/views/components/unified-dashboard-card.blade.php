@props([
    'title' => '',
    'description' => '',
    'icon' => 'fas fa-circle',
    'iconColor' => 'blue',
    'href' => '#',
    'badge' => null
])

<a href="{{ $href }}" class="unified-card hover:shadow-lg transition-all duration-300 group">
    <div class="unified-card-body text-center">
        <div class="w-12 h-12 bg-{{ $iconColor }}-100 rounded-lg flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
            <i class="{{ $icon }} text-{{ $iconColor }}-600 text-xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-secondary-900 mb-2 group-hover:text-primary-600 transition-colors">
            {{ $title }}
            @if($badge)
                <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $iconColor }}-100 text-{{ $iconColor }}-800">
                    {{ $badge }}
                </span>
            @endif
        </h3>
        <p class="text-sm text-secondary-600">{{ $description }}</p>
    </div>
</a>
