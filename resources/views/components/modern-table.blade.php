{{-- Modern Table Component --}}
@props([
    'striped' => false,
    'hover' => true,
    'bordered' => false,
    'responsive' => true,
    'class' => ''
])

@php
    $tableClasses = 'w-full text-sm text-left text-gray-500 dark:text-gray-400';
    
    if ($striped) {
        $tableClasses .= ' table-striped';
    }
    
    if ($hover) {
        $tableClasses .= ' table-hover';
    }
    
    if ($bordered) {
        $tableClasses .= ' table-bordered';
    }
    
    $tableClasses .= ' ' . $class;
@endphp

<div class="relative overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
    @if($responsive)
        <div class="overflow-x-auto">
    @endif
    
    <table {{ $attributes->merge(['class' => $tableClasses]) }}>
        @if(isset($header))
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                {{ $header }}
            </thead>
        @endif
        
        @if(isset($body))
            <tbody>
                {{ $body }}
            </tbody>
        @else
            {{ $slot }}
        @endif
        
        @if(isset($footer))
            <tfoot class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                {{ $footer }}
            </tfoot>
        @endif
    </table>
    
    @if($responsive)
        </div>
    @endif
</div>

<style>
.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.05);
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.075);
}

.table-bordered {
    border: 1px solid #dee2e6;
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
}

.table-bordered thead th {
    border-bottom-width: 2px;
}
</style>
