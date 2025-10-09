{{-- Modern Collapsible Section Component --}}
@props([
    'title' => '',
    'open' => false,
    'class' => '',
    'headerClass' => '',
    'contentClass' => '',
    'icon' => 'ph-caret-down',
    'iconClass' => ''
])

@php
    $collapsibleId = 'collapsible-' . uniqid();
    $isOpen = $open ? 'true' : 'false';
    $headerClasses = 'collapsible-header ' . $headerClass;
    $contentClasses = 'collapsible-content ' . $contentClass;
    $iconClasses = 'collapsible-icon ' . $iconClass;
@endphp

<div {{ $attributes->merge(['class' => 'collapsible-section ' . $class]) }} 
     data-collapsible-id="{{ $collapsibleId }}">
    
    {{-- Header --}}
    <button 
        class="{{ $headerClasses }}"
        type="button"
        aria-expanded="{{ $isOpen }}"
        aria-controls="{{ $collapsibleId }}-content"
        data-collapsible-trigger
    >
        <span class="collapsible-title">{{ $title }}</span>
        <i class="{{ $iconClasses }} {{ $icon }}" data-collapsible-icon></i>
    </button>
    
    {{-- Content --}}
    <div 
        id="{{ $collapsibleId }}-content"
        class="{{ $contentClasses }}"
        data-collapsible-content
        aria-hidden="{{ $isOpen ? 'false' : 'true' }}"
    >
        <div class="collapsible-content-inner">
            {{ $slot }}
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all collapsible sections
    const collapsibleSections = document.querySelectorAll('.collapsible-section');
    
    collapsibleSections.forEach(section => {
        const trigger = section.querySelector('[data-collapsible-trigger]');
        const content = section.querySelector('[data-collapsible-content]');
        const icon = section.querySelector('[data-collapsible-icon]');
        
        if (!trigger || !content) return;
        
        // Set initial state
        const isInitiallyOpen = trigger.getAttribute('aria-expanded') === 'true';
        section.setAttribute('data-expanded', isInitiallyOpen);
        
        // Add click event listener
        trigger.addEventListener('click', function() {
            const isExpanded = trigger.getAttribute('aria-expanded') === 'true';
            const newState = !isExpanded;
            
            // Update ARIA attributes
            trigger.setAttribute('aria-expanded', newState);
            content.setAttribute('aria-hidden', !newState);
            section.setAttribute('data-expanded', newState);
            
            // Update content height
            if (newState) {
                content.style.maxHeight = content.scrollHeight + 'px';
            } else {
                content.style.maxHeight = '0px';
            }
        });
        
        // Set initial height if open
        if (isInitiallyOpen) {
            content.style.maxHeight = content.scrollHeight + 'px';
        }
    });
});
</script>
