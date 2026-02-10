@props([
    'class' => '',
    'header' => null,
    'footer' => null
])

<div class="relative overflow-hidden bg-gradient-to-br from-indigo-950/80 via-purple-900/80 to-blue-900/80 rounded-2xl shadow-2xl border border-purple-500/30 backdrop-blur-md transition-all duration-500 hover:scale-[1.01] hover:shadow-purple-500/20 group {{ $class }} font-poppins">
    {{-- Animated Shimmer Effect --}}
    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent -translate-x-full group-hover:animate-[shimmer_2s_infinite] pointer-events-none"></div>

    @if($header)
        <div class="px-6 py-4 border-b border-purple-500/20 bg-black/20">
            <div class="font-bold text-white tracking-wider font-orbitron">
                {{ $header }}
            </div>
        </div>
    @endif
    
    <div class="p-4 sm:p-6 relative z-10">
        {{ $slot }}
    </div>
    
    @if($footer)
        <div class="px-6 py-4 border-t border-purple-500/20 bg-black/20">
            {{ $footer }}
        </div>
    @endif
</div>

<style>
    @keyframes shimmer {
        100% { transform: translateX(100%); }
    }
</style>
