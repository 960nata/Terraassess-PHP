@props([
    'label' => null,
    'error' => null,
    'help' => null,
    'required' => false,
    'type' => 'text',
    'icon' => null
])

<div class="space-y-1">
    @if($label)
        <label class="block text-sm font-medium text-secondary-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="{{ $icon }} text-secondary-400"></i>
            </div>
            <input {{ $attributes->merge([
                'class' => 'block w-full pl-10 pr-3 py-2 border border-secondary-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 sm:text-sm ' . ($error ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : '')
            ]) }} type="{{ $type }}">
        @else
            <input {{ $attributes->merge([
                'class' => 'block w-full px-3 py-2 border border-secondary-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 sm:text-sm ' . ($error ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : '')
            ]) }} type="{{ $type }}">
        @endif
    </div>
    
    @if($help)
        <p class="text-sm text-secondary-500">{{ $help }}</p>
    @endif
    
    @if($error)
        <p class="text-sm text-red-600 flex items-center">
            <i class="fas fa-exclamation-circle mr-1"></i>
            {{ $error }}
        </p>
    @endif
</div>
