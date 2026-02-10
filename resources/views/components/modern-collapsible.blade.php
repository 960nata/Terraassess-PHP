@props([
    'title' => '',
    'open' => false,
    'class' => ''
])

<div class="border border-gray-200 dark:border-gray-700 rounded-lg {{ $class }}" x-data="{ open: {{ $open ? 'true' : 'false' }} }">
    <button 
        @click="open = !open"
        class="w-full px-4 py-3 text-left flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200"
    >
        <span class="font-medium text-gray-900 dark:text-white">{{ $title }}</span>
        <i class="ph-caret-down text-gray-500 dark:text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
    </button>
    
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        class="px-4 pb-4 border-t border-gray-200 dark:border-gray-700"
        style="display: none;"
    >
        <div class="pt-4">
            {{ $slot }}
        </div>
    </div>
</div>
