@props([
    'trigger' => null,
    'placement' => 'bottom-right',
    'width' => 'w-48',
    'id' => null
])

@php
    $dropdownId = $id ?? 'galaxy-dropdown-' . uniqid();
    $placementClasses = [
        'bottom-right' => 'top-full right-0 mt-1',
        'bottom-left' => 'top-full left-0 mt-1',
        'top-right' => 'bottom-full right-0 mb-1',
        'top-left' => 'bottom-full left-0 mb-1',
    ];
@endphp

<div class="galaxy-dropdown relative inline-block" data-dropdown-id="{{ $dropdownId }}">
    <!-- Trigger -->
    <button 
        type="button"
        class="galaxy-dropdown-trigger"
        onclick="galaxyDropdownToggle('{{ $dropdownId }}')"
        aria-expanded="false"
        aria-haspopup="true"
    >
        @if($trigger)
            {!! $trigger !!}
        @else
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
            </svg>
        @endif
    </button>
    
    <!-- Dropdown Content -->
    <div 
        class="galaxy-dropdown-content {{ $placementClasses[$placement] }} {{ $width }} hidden absolute z-50"
        data-dropdown-content
    >
        <div class="galaxy-dropdown-content-inner">
            {{ $slot }}
        </div>
    </div>
</div>

<script>
function galaxyDropdownToggle(dropdownId) {
    const dropdown = document.querySelector(`[data-dropdown-id="${dropdownId}"]`);
    if (!dropdown) return;
    
    const content = dropdown.querySelector('[data-dropdown-content]');
    const trigger = dropdown.querySelector('.galaxy-dropdown-trigger');
    
    if (content.classList.contains('hidden')) {
        // Close other dropdowns
        document.querySelectorAll('.galaxy-dropdown-content:not(.hidden)').forEach(el => {
            el.classList.add('hidden');
        });
        
        // Open this dropdown
        content.classList.remove('hidden');
        trigger.setAttribute('aria-expanded', 'true');
        
        // Add backdrop
        const backdrop = document.createElement('div');
        backdrop.className = 'galaxy-dropdown-backdrop fixed inset-0 z-40';
        backdrop.onclick = () => galaxyDropdownClose(dropdownId);
        document.body.appendChild(backdrop);
        
        // Animate in
        setTimeout(() => {
            content.style.opacity = '1';
            content.style.transform = 'translateY(0) scale(1)';
        }, 10);
    } else {
        galaxyDropdownClose(dropdownId);
    }
}

function galaxyDropdownClose(dropdownId) {
    const dropdown = document.querySelector(`[data-dropdown-id="${dropdownId}"]`);
    if (!dropdown) return;
    
    const content = dropdown.querySelector('[data-dropdown-content]');
    const trigger = dropdown.querySelector('.galaxy-dropdown-trigger');
    const backdrop = document.querySelector('.galaxy-dropdown-backdrop');
    
    if (content && !content.classList.contains('hidden')) {
        // Animate out
        content.style.opacity = '0';
        content.style.transform = 'translateY(-10px) scale(0.95)';
        
        setTimeout(() => {
            content.classList.add('hidden');
            trigger.setAttribute('aria-expanded', 'false');
            
            if (backdrop) {
                backdrop.remove();
            }
        }, 150);
    }
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.galaxy-dropdown')) {
        document.querySelectorAll('.galaxy-dropdown-content:not(.hidden)').forEach(content => {
            const dropdown = content.closest('.galaxy-dropdown');
            if (dropdown) {
                galaxyDropdownClose(dropdown.dataset.dropdownId);
            }
        });
    }
});
</script>
