{{-- Modern Form Component --}}
@props([
    'method' => 'POST',
    'action' => '',
    'enctype' => null,
    'class' => ''
])

@php
    $formClasses = 'space-y-6 ' . $class;
    $formMethod = strtoupper($method);
    $formAction = $action ?: request()->url();
@endphp

<form 
    method="{{ $formMethod === 'GET' ? 'GET' : 'POST' }}"
    action="{{ $formAction }}"
    @if($enctype) enctype="{{ $enctype }}" @endif
    {{ $attributes->merge(['class' => $formClasses]) }}
>
    @if($formMethod !== 'GET')
        @csrf
    @endif
    
    @if($formMethod === 'PUT' || $formMethod === 'PATCH' || $formMethod === 'DELETE')
        @method($formMethod)
    @endif
    
    {{ $slot }}
    
    @if(isset($actions))
        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
            {{ $actions }}
        </div>
    @endif
</form>
