<!-- Page Header Component -->
<div class="page-header">
    <div class="page-header-content">
        <div class="page-title-section">
            <h1 class="page-title">
                @if(isset($icon))
                    <i class="{{ $icon }}"></i>
                @endif
                {{ $title ?? 'Dashboard' }}
            </h1>
            @if(isset($description))
                <p class="page-description">{{ $description }}</p>
            @endif
        </div>
        
        @if(isset($actions) && is_array($actions))
            <div class="page-actions">
                @foreach($actions as $action)
                    <a href="{{ $action['url'] ?? '#' }}" 
                       class="btn {{ $action['class'] ?? 'btn-primary' }}"
                       @if(isset($action['onclick'])) onclick="{{ $action['onclick'] }}" @endif>
                        @if(isset($action['icon']))
                            <i class="{{ $action['icon'] }}"></i>
                        @endif
                        {{ $action['text'] ?? 'Action' }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>
    
    @if(isset($breadcrumbs) && is_array($breadcrumbs))
        <nav class="breadcrumb">
            <ol class="breadcrumb-list">
                @foreach($breadcrumbs as $breadcrumb)
                    <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                        @if(!$loop->last && isset($breadcrumb['url']))
                            <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['text'] }}</a>
                        @else
                            {{ $breadcrumb['text'] }}
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
    @endif
</div>

<style>
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
    border-radius: 0 0 20px 20px;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
}

.page-header-content {
    position: relative;
    z-index: 2;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 1rem;
}

.page-title-section {
    flex: 1;
    min-width: 300px;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.page-title i {
    font-size: 2rem;
    opacity: 0.9;
}

.page-description {
    font-size: 1.1rem;
    opacity: 0.9;
    margin: 0;
    font-weight: 400;
}

.page-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    align-items: center;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.btn-primary:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-2px);
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.4);
    transform: translateY(-2px);
}

.breadcrumb {
    position: relative;
    z-index: 2;
    margin-top: 1rem;
}

.breadcrumb-list {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.breadcrumb-item {
    display: flex;
    align-items: center;
    font-size: 0.9rem;
    opacity: 0.8;
}

.breadcrumb-item:not(:last-child)::after {
    content: '>';
    margin-left: 0.5rem;
    opacity: 0.6;
}

.breadcrumb-item a {
    color: white;
    text-decoration: none;
    transition: opacity 0.3s ease;
}

.breadcrumb-item a:hover {
    opacity: 1;
}

.breadcrumb-item.active {
    opacity: 1;
    font-weight: 600;
}

@media (max-width: 768px) {
    .page-header {
        padding: 1.5rem 0;
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .page-header-content {
        flex-direction: column;
        align-items: stretch;
    }
    
    .page-actions {
        justify-content: flex-start;
    }
}
</style>
