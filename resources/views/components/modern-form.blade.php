@props([
    'method' => 'POST',
    'action' => '',
    'enctype' => null,
    'class' => ''
])

@php
    $method = strtoupper($method);
    $enctypeAttr = $enctype ? "enctype=\"{$enctype}\"" : '';
@endphp

<form 
    method="{{ $method === 'GET' ? 'GET' : 'POST' }}"
    action="{{ $action }}"
    {!! $enctypeAttr !!}
    class="space-y-6 {{ $class }}"
    {{ $attributes->except(['method', 'action', 'enctype', 'class']) }}
>
    @if($method !== 'GET')
        @csrf
    @endif
    
    @if($method === 'PUT' || $method === 'PATCH' || $method === 'DELETE')
        @method($method)
    @endif
    
    {{ $slot }}
</form>
