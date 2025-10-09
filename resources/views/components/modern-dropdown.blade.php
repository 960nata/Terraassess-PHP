{{-- Modern Dropdown Component --}}
@props([
    'trigger' => null,
    'variant' => 'default', // 'default', 'notifications', 'profile'
    'position' => 'bottom-right', // 'bottom-left', 'bottom-right', 'top-left', 'top-right'
    'open' => false,
    'class' => '',
    'triggerClass' => '',
    'contentClass' => '',
    'width' => null, // Custom width override
    'maxHeight' => '400px'
])

@php
    $dropdownId = 'dropdown-' . uniqid();
    $isOpen = $open ? 'true' : 'false';
    
    // Set width based on variant
    $dropdownWidth = $width ?? match($variant) {
        'notifications' => '320px',
        'profile' => '240px',
        default => '280px'
    };
    
    $dropdownClasses = 'modern-dropdown ' . $class;
    $triggerClasses = 'dropdown-trigger ' . $triggerClass;
    $contentClasses = 'dropdown-content ' . $contentClass;
    
    // Position classes
    $positionClasses = match($position) {
        'bottom-left' => 'dropdown-bottom-left',
        'bottom-right' => 'dropdown-bottom-right',
        'top-left' => 'dropdown-top-left',
        'top-right' => 'dropdown-top-right',
        default => 'dropdown-bottom-right'
    };
@endphp

<div {{ $attributes->merge(['class' => $dropdownClasses]) }} 
     data-dropdown-id="{{ $dropdownId }}"
     data-dropdown-open="{{ $isOpen }}">
    
    {{-- Trigger --}}
    <div class="{{ $triggerClasses }}" data-dropdown-trigger>
        @if($trigger)
            {{ $trigger }}
        @else
            <button type="button" class="dropdown-default-trigger">
                <i class="ph-dots-three-vertical text-lg"></i>
            </button>
        @endif
    </div>
    
    {{-- Backdrop --}}
    <div class="dropdown-backdrop" data-dropdown-backdrop></div>
    
    {{-- Content --}}
    <div 
        class="{{ $contentClasses }} {{ $positionClasses }}"
        data-dropdown-content
        data-width="{{ $dropdownWidth }}"
        data-max-height="{{ $maxHeight }}"
        aria-hidden="{{ $isOpen ? 'false' : 'true' }}"
    >
        <div class="dropdown-content-inner">
            {{ $slot }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all dropdowns
    const dropdowns = document.querySelectorAll('.modern-dropdown');
    
    dropdowns.forEach(dropdown => {
        const trigger = dropdown.querySelector('[data-dropdown-trigger]');
        const content = dropdown.querySelector('[data-dropdown-content]');
        const backdrop = dropdown.querySelector('[data-dropdown-backdrop]');
        
        if (!trigger || !content) return;
        
        // Set initial state
        const isInitiallyOpen = dropdown.getAttribute('data-dropdown-open') === 'true';
        if (isInitiallyOpen) {
            showDropdown(dropdown, content, backdrop);
        }
        
        // Toggle dropdown on trigger click
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = dropdown.getAttribute('data-dropdown-open') === 'true';
            
            if (isOpen) {
                hideDropdown(dropdown, content, backdrop);
            } else {
                showDropdown(dropdown, content, backdrop);
            }
        });
        
        // Close dropdown on backdrop click
        if (backdrop) {
            backdrop.addEventListener('click', function() {
                hideDropdown(dropdown, content, backdrop);
            });
        }
        
        // Close dropdown on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && dropdown.getAttribute('data-dropdown-open') === 'true') {
                hideDropdown(dropdown, content, backdrop);
            }
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        dropdowns.forEach(dropdown => {
            const content = dropdown.querySelector('[data-dropdown-content]');
            const backdrop = dropdown.querySelector('[data-dropdown-backdrop]');
            
            if (!dropdown.contains(e.target) && dropdown.getAttribute('data-dropdown-open') === 'true') {
                hideDropdown(dropdown, content, backdrop);
            }
        });
    });
    
    function showDropdown(dropdown, content, backdrop) {
        dropdown.setAttribute('data-dropdown-open', 'true');
        content.setAttribute('aria-hidden', 'false');
        
        // Add backdrop
        if (backdrop) {
            backdrop.style.display = 'block';
            backdrop.style.opacity = '0';
            backdrop.style.transition = 'opacity 200ms ease';
            requestAnimationFrame(() => {
                backdrop.style.opacity = '1';
            });
        }
        
        // Show content with animation
        content.style.display = 'block';
        content.style.opacity = '0';
        content.style.transform = 'translateY(-10px) scale(0.95)';
        content.style.transition = 'opacity 200ms ease, transform 200ms ease';
        
        requestAnimationFrame(() => {
            content.style.opacity = '1';
            content.style.transform = 'translateY(0) scale(1)';
        });
    }
    
    function hideDropdown(dropdown, content, backdrop) {
        dropdown.setAttribute('data-dropdown-open', 'false');
        content.setAttribute('aria-hidden', 'true');
        
        // Hide content with animation
        content.style.opacity = '0';
        content.style.transform = 'translateY(-10px) scale(0.95)';
        
        // Hide backdrop
        if (backdrop) {
            backdrop.style.opacity = '0';
        }
        
        // Remove elements after animation
        setTimeout(() => {
            content.style.display = 'none';
            if (backdrop) {
                backdrop.style.display = 'none';
            }
        }, 200);
    }
});
</script>
