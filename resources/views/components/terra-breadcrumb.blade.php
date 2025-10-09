{{-- Terra Breadcrumb Component --}}
@props([
    'items' => [],
    'separator' => '/',
    'class' => ''
])

@php
    $breadcrumbClasses = 'flex items-center space-x-2 text-sm ' . $class;
@endphp

<nav {{ $attributes->merge(['class' => $breadcrumbClasses, 'aria-label' => 'Breadcrumb']) }}>
    @foreach($items as $index => $item)
        @if($index > 0)
            <span class="text-secondary-400" aria-hidden="true">{{ $separator }}</span>
        @endif
        
        @if(isset($item['url']) && $index < count($items) - 1)
            <a href="{{ $item['url'] }}" class="text-secondary-600 hover:text-primary-600 transition-colors">
                @if(isset($item['icon']))
                    <i class="{{ $item['icon'] }} mr-1"></i>
                @endif
                {{ $item['label'] }}
            </a>
        @else
            <span class="text-secondary-900 font-medium" aria-current="page">
                @if(isset($item['icon']))
                    <i class="{{ $item['icon'] }} mr-1"></i>
                @endif
                {{ $item['label'] }}
            </span>
        @endif
    @endforeach
</nav>

