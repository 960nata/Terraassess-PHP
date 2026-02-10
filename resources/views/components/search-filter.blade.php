<!-- Search and Filter Component -->
<div class="search-filter-container">
    <div class="search-filter-header">
        <div class="search-section">
            <div class="search-input-group">
                <i class="fas fa-search search-icon"></i>
                <input type="text" 
                       class="search-input" 
                       placeholder="{{ $placeholder ?? 'Cari...' }}"
                       value="{{ $searchValue ?? '' }}"
                       onkeyup="handleSearch(event)">
            </div>
        </div>
        
        <div class="filter-section">
            @if(isset($filters) && is_array($filters))
                @foreach($filters as $filter)
                    <div class="filter-group">
                        <label class="filter-label">{{ $filter['label'] ?? 'Filter' }}</label>
                        <select class="filter-select" 
                                name="{{ $filter['name'] ?? 'filter' }}"
                                onchange="handleFilterChange(this)">
                            <option value="">{{ $filter['placeholder'] ?? 'Semua' }}</option>
                            @if(isset($filter['options']) && is_array($filter['options']))
                                @foreach($filter['options'] as $value => $label)
                                    <option value="{{ $value }}" 
                                            {{ ($filter['selected'] ?? '') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                @endforeach
            @endif
            
            @if(isset($actions) && is_array($actions))
                <div class="action-buttons">
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
    </div>
    
    @if(isset($activeFilters) && count($activeFilters) > 0)
        <div class="active-filters">
            <span class="active-filters-label">Filter Aktif:</span>
            <div class="active-filters-list">
                @foreach($activeFilters as $filter)
                    <span class="filter-tag">
                        {{ $filter['label'] }}
                        <button class="filter-tag-remove" onclick="removeFilter('{{ $filter['key'] }}')">
                            <i class="fas fa-times"></i>
                        </button>
                    </span>
                @endforeach
                <button class="clear-all-filters" onclick="clearAllFilters()">
                    Hapus Semua
                </button>
            </div>
        </div>
    @endif
</div>

<style>
.search-filter-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.search-filter-header {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem;
    flex-wrap: wrap;
}

.search-section {
    flex: 1;
    min-width: 300px;
}

.search-input-group {
    position: relative;
    display: flex;
    align-items: center;
}

.search-icon {
    position: absolute;
    left: 1rem;
    color: #6b7280;
    z-index: 2;
}

.search-input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    background: #f9fafb;
}

.search-input:focus {
    outline: none;
    border-color: #3b82f6;
    background: white;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.filter-section {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    min-width: 150px;
}

.filter-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
}

.filter-select {
    padding: 0.75rem;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 0.9rem;
    background: white;
    transition: all 0.3s ease;
    cursor: pointer;
}

.filter-select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
    text-decoration: none;
}

.btn-sm {
    padding: 0.5rem 0.75rem;
    font-size: 0.8rem;
}

.btn-primary {
    background: #3b82f6;
    color: white;
}

.btn-primary:hover {
    background: #2563eb;
    transform: translateY(-1px);
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
    transform: translateY(-1px);
}

.btn-outline {
    background: transparent;
    border: 2px solid #d1d5db;
    color: #374151;
}

.btn-outline:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
}

.active-filters {
    padding: 1rem 1.5rem;
    background: #f3f4f6;
    border-top: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.active-filters-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    white-space: nowrap;
}

.active-filters-list {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.filter-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.375rem 0.75rem;
    background: #3b82f6;
    color: white;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.filter-tag-remove {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    padding: 0.125rem;
    border-radius: 50%;
    transition: background-color 0.2s ease;
}

.filter-tag-remove:hover {
    background: rgba(255, 255, 255, 0.2);
}

.clear-all-filters {
    background: #ef4444;
    color: white;
    border: none;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.clear-all-filters:hover {
    background: #dc2626;
}

@media (max-width: 768px) {
    .search-filter-header {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }
    
    .search-section {
        min-width: auto;
    }
    
    .filter-section {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-group {
        min-width: auto;
    }
    
    .active-filters {
        flex-direction: column;
        align-items: stretch;
    }
    
    .active-filters-list {
        justify-content: flex-start;
    }
}
</style>

<script>
function handleSearch(event) {
    const searchValue = event.target.value;
    const searchParam = new URLSearchParams(window.location.search);
    
    if (searchValue) {
        searchParam.set('search', searchValue);
    } else {
        searchParam.delete('search');
    }
    
    // Update URL without page reload
    const newUrl = window.location.pathname + '?' + searchParam.toString();
    window.history.pushState({}, '', newUrl);
    
    // Trigger search (implement your search logic here)
    performSearch(searchValue);
}

function handleFilterChange(selectElement) {
    const filterName = selectElement.name;
    const filterValue = selectElement.value;
    const searchParam = new URLSearchParams(window.location.search);
    
    if (filterValue) {
        searchParam.set(filterName, filterValue);
    } else {
        searchParam.delete(filterName);
    }
    
    // Update URL without page reload
    const newUrl = window.location.pathname + '?' + searchParam.toString();
    window.history.pushState({}, '', newUrl);
    
    // Trigger filter (implement your filter logic here)
    performFilter(filterName, filterValue);
}

function removeFilter(filterKey) {
    const searchParam = new URLSearchParams(window.location.search);
    searchParam.delete(filterKey);
    
    // Update URL without page reload
    const newUrl = window.location.pathname + '?' + searchParam.toString();
    window.history.pushState({}, '', newUrl);
    
    // Reload page to apply changes
    window.location.reload();
}

function clearAllFilters() {
    // Clear all filters and search
    const newUrl = window.location.pathname;
    window.history.pushState({}, '', newUrl);
    
    // Reload page to apply changes
    window.location.reload();
}

function performSearch(searchValue) {
    // Implement your search logic here
    console.log('Searching for:', searchValue);
    // You can make an AJAX request to update the results
}

function performFilter(filterName, filterValue) {
    // Implement your filter logic here
    console.log('Filtering by:', filterName, '=', filterValue);
    // You can make an AJAX request to update the results
}

// Initialize filters from URL parameters
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    // Set search input value
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.value = urlParams.get('search') || '';
    }
    
    // Set filter values
    const filterSelects = document.querySelectorAll('.filter-select');
    filterSelects.forEach(select => {
        const paramValue = urlParams.get(select.name);
        if (paramValue) {
            select.value = paramValue;
        }
    });
});
</script>
