@props([
    'tabs' => [],
    'activeTab' => 0,
    'id' => null
])

@php
    $tabsId = $id ?? 'galaxy-tabs-' . uniqid();
@endphp

<div class="galaxy-tabs" data-tabs-id="{{ $tabsId }}">
    <!-- Tab List -->
    <div class="galaxy-tab-list">
        @foreach($tabs as $index => $tab)
            <button 
                class="galaxy-tab-trigger {{ $index === $activeTab ? 'galaxy-tab-active' : '' }}"
                data-tab="{{ $index }}"
                onclick="galaxyTabSwitch('{{ $tabsId }}', '{{ $index }}')"
            >
                @if(isset($tab['icon']))
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {!! $tab['icon'] !!}
                    </svg>
                @endif
                {{ $tab['label'] ?? $tab }}
            </button>
        @endforeach
    </div>
    
    <!-- Tab Content -->
    <div class="galaxy-tab-content">
        @foreach($tabs as $index => $tab)
            <div 
                class="galaxy-tab-panel {{ $index === $activeTab ? 'galaxy-tab-panel-active' : 'hidden' }}"
                data-tab="{{ $index }}"
            >
                @if(isset($tab['content']))
                    {!! $tab['content'] !!}
                @endif
            </div>
        @endforeach
        
        {{ $slot }}
    </div>
</div>

<style>
.galaxy-tab-active {
    color: white !important;
    background: rgba(255, 255, 255, 0.2) !important;
}

.galaxy-tab-panel-active {
    display: block !important;
}
</style>

<script>
function galaxyTabSwitch(tabsId, tabIndex) {
    const tabsContainer = document.querySelector(`[data-tabs-id="${tabsId}"]`);
    if (!tabsContainer) return;
    
    // Update tab triggers
    const triggers = tabsContainer.querySelectorAll('.galaxy-tab-trigger');
    triggers.forEach((trigger, index) => {
        if (index === tabIndex) {
            trigger.classList.add('galaxy-tab-active');
        } else {
            trigger.classList.remove('galaxy-tab-active');
        }
    });
    
    // Update tab panels
    const panels = tabsContainer.querySelectorAll('.galaxy-tab-panel');
    panels.forEach((panel, index) => {
        if (index === tabIndex) {
            panel.classList.remove('hidden');
            panel.classList.add('galaxy-tab-panel-active');
        } else {
            panel.classList.add('hidden');
            panel.classList.remove('galaxy-tab-panel-active');
        }
    });
}
</script>
