@props([
    'title' => 'Dashboard',
    'description' => 'Selamat datang di Terra Assessment',
    'icon' => 'fas fa-home',
    'breadcrumbs' => []
])

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-secondary-900 flex items-center gap-3">
                <i class="{{ $icon }} text-primary-600"></i>
                {{ $title }}
            </h1>
            <p class="text-secondary-600 mt-1">{{ $description }}</p>
            
            @if(!empty($breadcrumbs))
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        @foreach($breadcrumbs as $index => $breadcrumb)
                            <li class="inline-flex items-center">
                                @if($index > 0)
                                    <i class="fas fa-chevron-right text-secondary-400 mx-2"></i>
                                @endif
                                @if(isset($breadcrumb['href']))
                                    <a href="{{ $breadcrumb['href'] }}" class="text-sm font-medium text-secondary-700 hover:text-primary-600">
                                        {{ $breadcrumb['label'] }}
                                    </a>
                                @else
                                    <span class="text-sm font-medium text-secondary-500">
                                        {{ $breadcrumb['label'] }}
                                    </span>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </nav>
            @endif
        </div>
        
        @if(isset($actions))
            <div class="flex items-center space-x-3">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>
