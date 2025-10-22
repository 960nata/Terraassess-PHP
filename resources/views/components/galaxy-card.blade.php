@props([
    'class' => '',
    'header' => null,
    'footer' => null
])

<div class="bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900 rounded-xl shadow-2xl border border-purple-500 border-opacity-30 backdrop-blur-sm {{ $class }}">
    @if($header)
        <div class="px-6 py-4 border-b border-purple-500 border-opacity-30">
            {{ $header }}
        </div>
    @endif
    
    <div class="p-6">
        {{ $slot }}
    </div>
    
    @if($footer)
        <div class="px-6 py-4 border-t border-purple-500 border-opacity-30">
            {{ $footer }}
        </div>
    @endif
</div>
