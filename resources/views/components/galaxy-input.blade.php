@props([
    'type' => 'text',
    'label' => null,
    'error' => null,
    'help' => null,
    'required' => false,
    'placeholder' => null
])

<div class="space-y-2">
    @if($label)
        <label class="block text-sm font-medium text-gray-300">
            {{ $label }}
            @if($required)
                <span class="text-red-400">*</span>
            @endif
        </label>
    @endif
    
    <input 
        type="{{ $type }}"
        {{ $attributes->merge([
            'class' => 'galaxy-input ' . ($error ? 'border-red-500 focus:border-red-500 focus:ring-red-500/20' : ''),
            'placeholder' => $placeholder
        ]) }}
        @if($required) required @endif
    />
    
    @if($error)
        <p class="text-sm text-red-400">{{ $error }}</p>
    @elseif($help)
        <p class="text-sm text-gray-400">{{ $help }}</p>
    @endif
</div>
