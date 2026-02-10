<!-- Data Table Component -->
<div class="data-table-container">
    @if(isset($title))
        <div class="table-header">
            <h3 class="table-title">{{ $title }}</h3>
            @if(isset($actions) && is_array($actions))
                <div class="table-actions">
                    @foreach($actions as $action)
                        <button class="btn btn-sm {{ $action['class'] ?? 'btn-primary' }}" 
                                @if(isset($action['onclick'])) onclick="{{ $action['onclick'] }}" @endif>
                            @if(isset($action['icon']))
                                <i class="{{ $action['icon'] }}"></i>
                            @endif
                            {{ $action['text'] ?? 'Action' }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <div class="table-wrapper">
        <table class="data-table">
            @if(isset($columns) && is_array($columns))
                <thead>
                    <tr>
                        @foreach($columns as $column)
                            <th class="{{ $column['class'] ?? '' }}">
                                {{ $column['label'] ?? $column }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
            @endif
            
            <tbody>
                @if(isset($data) && is_array($data) && count($data) > 0)
                    @foreach($data as $row)
                        <tr class="table-row">
                            @foreach($columns as $key => $column)
                                <td class="{{ $column['class'] ?? '' }}">
                                    @if(isset($column['render']) && is_callable($column['render']))
                                        {!! $column['render']($row) !!}
                                    @elseif(isset($row[$key]))
                                        {{ $row[$key] }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="{{ count($columns ?? []) }}" class="text-center text-muted py-4">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>Tidak ada data</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if(isset($pagination))
        <div class="table-pagination">
            {{ $pagination }}
        </div>
    @endif
</div>

<style>
.data-table-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    background: #f9fafb;
}

.table-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.table-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

.table-wrapper {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.9rem;
}

.data-table th {
    background: #f3f4f6;
    color: #374151;
    font-weight: 600;
    text-align: left;
    padding: 1rem;
    border-bottom: 2px solid #e5e7eb;
    white-space: nowrap;
}

.data-table td {
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
    vertical-align: middle;
}

.table-row {
    transition: background-color 0.2s ease;
}

.table-row:hover {
    background-color: #f9fafb;
}

.table-row:nth-child(even) {
    background-color: #fafafa;
}

.table-row:nth-child(even):hover {
    background-color: #f3f4f6;
}

.table-pagination {
    padding: 1rem 1.5rem;
    border-top: 1px solid #e5e7eb;
    background: #f9fafb;
    display: flex;
    justify-content: center;
}

.text-center {
    text-align: center;
}

.text-muted {
    color: #6b7280;
}

.text-4xl {
    font-size: 2.5rem;
}

.mb-2 {
    margin-bottom: 0.5rem;
}

.py-4 {
    padding-top: 1rem;
    padding-bottom: 1rem;
}

/* Status badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-active {
    background-color: #d1fae5;
    color: #065f46;
}

.status-inactive {
    background-color: #fee2e2;
    color: #991b1b;
}

.status-pending {
    background-color: #fef3c7;
    color: #92400e;
}

/* Action buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.action-btn {
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}

.action-btn:hover {
    transform: translateY(-1px);
}

.btn-edit {
    background-color: #3b82f6;
    color: white;
}

.btn-edit:hover {
    background-color: #2563eb;
}

.btn-delete {
    background-color: #ef4444;
    color: white;
}

.btn-delete:hover {
    background-color: #dc2626;
}

.btn-view {
    background-color: #10b981;
    color: white;
}

.btn-view:hover {
    background-color: #059669;
}

@media (max-width: 768px) {
    .table-header {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }
    
    .table-actions {
        justify-content: center;
    }
    
    .data-table th,
    .data-table td {
        padding: 0.75rem 0.5rem;
    }
}
</style>
