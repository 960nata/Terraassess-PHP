@props([
    'headers' => [],
    'data' => [],
    'striped' => true,
    'hover' => true,
    'bordered' => false
])

@php
    $tableClasses = 'min-w-full divide-y divide-secondary-200';
    if ($bordered) {
        $tableClasses .= ' border border-secondary-200';
    }
@endphp

<div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
    <table class="{{ $tableClasses }}">
        @if(!empty($headers))
            <thead class="bg-secondary-50">
                <tr>
                    @foreach($headers as $header)
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-secondary-500 uppercase tracking-wider">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody class="bg-white divide-y divide-secondary-200 {{ $striped ? 'divide-y' : '' }}">
            @if(!empty($data))
                @foreach($data as $index => $row)
                    <tr class="{{ $striped && $index % 2 === 1 ? 'bg-secondary-50' : '' }} {{ $hover ? 'hover:bg-secondary-50' : '' }}">
                        @foreach($row as $cell)
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-900">
                                {{ $cell }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            @else
                {{ $slot }}
            @endif
        </tbody>
    </table>
</div>
