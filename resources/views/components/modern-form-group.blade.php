@props([
    'label' => '',
    'required' => false,
    'help' => null,
    'error' => null,
    'class' => ''
])

<div class="space-y-2 {{ $class }}">
    @if($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ $label }}
            @if($required)
                <span class="text-red-500 ml-1">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        {{ $slot }}
    </div>
    
    @if($help)
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $help }}</p>
    @endif
    
    @if($error)
        <p class="text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @endif
</div>
