<!-- Info Card Component -->
<div class="info-card {{ $class ?? '' }}">
    @if(isset($header))
        <div class="card-header">
            @if(isset($header['icon']))
                <div class="card-icon">
                    <i class="{{ $header['icon'] }}"></i>
                </div>
            @endif
            <div class="card-title-section">
                <h3 class="card-title">{{ $header['title'] ?? 'Card Title' }}</h3>
                @if(isset($header['subtitle']))
                    <p class="card-subtitle">{{ $header['subtitle'] }}</p>
                @endif
            </div>
            @if(isset($header['actions']) && is_array($header['actions']))
                <div class="card-actions">
                    @foreach($header['actions'] as $action)
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

    <div class="card-body">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif
</div>

<style>
.info-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid #e5e7eb;
}

.info-card:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.card-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    background: #f9fafb;
}

.card-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 10px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.card-title-section {
    flex: 1;
    min-width: 0;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 0.25rem 0;
    line-height: 1.2;
}

.card-subtitle {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
    line-height: 1.4;
}

.card-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    flex-shrink: 0;
}

.card-body {
    padding: 1.5rem;
}

.card-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #e5e7eb;
    background: #f9fafb;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
    text-decoration: none;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
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

.btn-success {
    background: #10b981;
    color: white;
}

.btn-success:hover {
    background: #059669;
    transform: translateY(-1px);
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
    transform: translateY(-1px);
}

.btn-outline {
    background: transparent;
    border: 1px solid #d1d5db;
    color: #374151;
}

.btn-outline:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
}

/* Card variants */
.info-card.primary .card-icon {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
}

.info-card.success .card-icon {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.info-card.warning .card-icon {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.info-card.danger .card-icon {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.info-card.info .card-icon {
    background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
}

/* Card sizes */
.info-card.sm .card-header,
.info-card.sm .card-body,
.info-card.sm .card-footer {
    padding: 1rem;
}

.info-card.lg .card-header,
.info-card.lg .card-body,
.info-card.lg .card-footer {
    padding: 2rem;
}

/* Card with image */
.card-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 12px 12px 0 0;
}

/* Card with stats */
.card-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.stat-item {
    text-align: center;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

@media (max-width: 768px) {
    .card-header {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }
    
    .card-actions {
        justify-content: center;
    }
    
    .card-title-section {
        text-align: center;
    }
}
</style>
