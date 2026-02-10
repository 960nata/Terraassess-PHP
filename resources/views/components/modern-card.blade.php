@props([
    'class' => '',
    'header' => null,
    'footer' => null
])

<div class="glass-card relative overflow-hidden bg-white/10 dark:bg-gray-900/40 backdrop-blur-xl border border-white/20 dark:border-white/10 rounded-2xl shadow-xl transition-all duration-300 hover:shadow-2xl hover:border-white/30 group {{ $class }} font-poppins">
    {{-- Decorative gradient blob --}}
    <div class="absolute -top-24 -right-24 w-48 h-48 bg-purple-500/10 blur-3xl rounded-full group-hover:bg-purple-500/20 transition-all duration-500"></div>
    <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-blue-500/10 blur-3xl rounded-full group-hover:bg-blue-500/20 transition-all duration-500"></div>

    @if($header)
        <div class="px-6 py-4 border-b border-white/10 relative z-10 bg-white/5">
            <div class="font-semibold text-gray-800 dark:text-white tracking-wide">
                {{ $header }}
            </div>
        </div>
    @endif
    
    <div class="p-4 sm:p-6 relative z-10">
        {{ $slot }}
    </div>
    
    @if($footer)
        <div class="px-6 py-4 border-t border-white/10 relative z-10 bg-white/5">
            {{ $footer }}
        </div>
    @endif
</div>

<style>
    .glass-card {
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    }
</style>
