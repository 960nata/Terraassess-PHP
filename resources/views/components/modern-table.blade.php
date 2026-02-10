@props([
    'striped' => false,
    'hover' => false,
    'bordered' => false,
    'class' => ''
])

@php
    $tableClass = 'min-w-full divide-y divide-gray-200 dark:divide-gray-700';
    
    if ($striped) {
        $tableClass .= ' table-striped';
    }
    
    if ($hover) {
        $tableClass .= ' table-hover';
    }
    
    if ($bordered) {
        $tableClass .= ' table-bordered';
    }
    
    $tableClass .= ' ' . $class;
@endphp

<div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
    <table class="{{ $tableClass }}">
        {{ $slot }}
    </table>
</div>

<style>
.table-striped tbody tr:nth-child(odd) {
    background-color: #f9fafb;
}

.dark .table-striped tbody tr:nth-child(odd) {
    background-color: #374151;
}

.table-hover tbody tr:hover {
    background-color: #f3f4f6;
}

.dark .table-hover tbody tr:hover {
    background-color: #4b5563;
}

.table-bordered {
    border: 1px solid #e5e7eb;
}

.dark .table-bordered {
    border-color: #4b5563;
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #e5e7eb;
}

.dark .table-bordered th,
.dark .table-bordered td {
    border-color: #4b5563;
}
</style>
