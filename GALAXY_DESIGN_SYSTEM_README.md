# Galaxy Design System v3.0

A comprehensive design system built for modern web applications with a space/galaxy theme. This design system provides a complete set of UI components, utilities, and guidelines for creating consistent and beautiful user interfaces.

## 🚀 Features

- **Complete Component Library**: Buttons, cards, forms, tabs, badges, and more
- **Galaxy Theme**: Modern space-inspired color palette and styling
- **Responsive Design**: Mobile-first approach with tablet and desktop optimizations
- **Accessibility**: WCAG compliant components with proper focus states
- **Animations**: Smooth transitions and micro-interactions
- **Dark Mode**: Built-in dark mode support
- **Tailwind Integration**: Seamless integration with Tailwind CSS

## 🎨 Color Palette

### Galaxy Colors
- **Primary**: `#8b5cf6` (Purple-500)
- **Secondary**: `#3b82f6` (Blue-500)
- **Accent**: `#ec4899` (Pink-500)
- **Success**: `#10b981` (Emerald-500)
- **Warning**: `#f59e0b` (Amber-500)
- **Error**: `#ef4444` (Red-500)

### Neutral Palette
- **White**: `#ffffff`
- **Gray-50**: `#f9fafb`
- **Gray-100**: `#f3f4f6`
- **Gray-200**: `#e5e7eb`
- **Gray-300**: `#d1d5db`
- **Gray-400**: `#9ca3af`
- **Gray-500**: `#6b7280`
- **Gray-600**: `#4b5563`
- **Gray-700**: `#374151`
- **Gray-800**: `#1f2937`
- **Gray-900**: `#111827`
- **Black**: `#000000`

## 📝 Typography

### Font Scale
- **H1**: 32px, Bold, Line-height: 1.2
- **H2**: 24px, Semibold, Line-height: 1.3
- **H3**: 20px, Semibold, Line-height: 1.4
- **H4**: 18px, Medium, Line-height: 1.4
- **Body**: 16px, Normal, Line-height: 1.6
- **Small**: 14px, Normal, Line-height: 1.5
- **Caption**: 12px, Normal, Line-height: 1.4

### Font Weights
- Light: 300
- Normal: 400
- Medium: 500
- Semibold: 600
- Bold: 700
- Extrabold: 800

## 🧩 Components

### Buttons

#### Primary Button
```html
<button class="galaxy-btn-primary">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
    </svg>
    Primary Button
</button>
```

**Features:**
- Gradient background: `linear-gradient(135deg, #8b5cf6, #3b82f6)`
- Hover: `linear-gradient(135deg, #7c3aed, #2563eb)`
- Height: 40px
- Min-width: 120px
- Shadow: `0 4px 6px -1px rgba(0, 0, 0, 0.1)`
- Transition: `all 200ms ease`

#### Secondary Button
```html
<button class="galaxy-btn-secondary">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
    </svg>
    Secondary Button
</button>
```

**Features:**
- Transparent background
- Border: `1px solid rgba(139, 92, 246, 0.3)`
- Hover: `rgba(139, 92, 246, 0.2)`
- Height: 40px

#### Ghost Button
```html
<button class="galaxy-btn-ghost">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
    </svg>
    Ghost Button
</button>
```

**Features:**
- Transparent background
- Color: `#9ca3af`
- Hover: `rgba(255, 255, 255, 0.1)`
- Height: 36px
- Smaller padding: `8px 16px`

### Cards

```html
<div class="galaxy-card">
    <div class="galaxy-card-header">
        <h3 class="galaxy-card-title">
            <svg class="galaxy-card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Card Title
        </h3>
        <p class="galaxy-card-subtitle">Card subtitle description</p>
    </div>
    <div class="galaxy-card-content">
        <p>This is the card content area where you can place any content.</p>
    </div>
</div>
```

**Features:**
- Glass morphism effect: `rgba(255, 255, 255, 0.05)`
- Backdrop filter: `blur(8px)`
- Border: `1px solid rgba(255, 255, 255, 0.1)`
- Border radius: `12px`
- Hover: Scale `1.02` and enhanced shadow
- Padding: `24px`

### Form Elements

#### Input
```html
<input type="text" class="galaxy-input" placeholder="Enter your text here">
```

**Features:**
- Background: `rgba(255, 255, 255, 0.1)`
- Border: `1px solid rgba(255, 255, 255, 0.2)`
- Height: 48px
- Focus: Purple border with ring effect
- Placeholder: `#9ca3af`

#### Textarea
```html
<textarea class="galaxy-textarea" placeholder="Enter your message here"></textarea>
```

**Features:**
- Same styling as input
- Min-height: 120px
- Resize: vertical only

#### Select
```html
<div class="galaxy-select-trigger">
    <span>Choose an option</span>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
    </svg>
</div>
```

### Tabs

```html
<div class="galaxy-tab-list">
    <button class="galaxy-tab-trigger" data-active="true">Overview</button>
    <button class="galaxy-tab-trigger">Analytics</button>
    <button class="galaxy-tab-trigger">Settings</button>
</div>
<div class="galaxy-tab-content">
    <p>Tab content goes here</p>
</div>
```

**Features:**
- Background: `rgba(255, 255, 255, 0.1)`
- Active state: White text with `rgba(255, 255, 255, 0.2)` background
- Hover: `rgba(255, 255, 255, 0.1)`
- Padding: `12px 24px`

### Badges

```html
<span class="galaxy-badge galaxy-badge-default">Default</span>
<span class="galaxy-badge galaxy-badge-success">Success</span>
<span class="galaxy-badge galaxy-badge-warning">Warning</span>
<span class="galaxy-badge galaxy-badge-error">Error</span>
<span class="galaxy-badge galaxy-badge-info">Info</span>
<span class="galaxy-badge galaxy-badge-galaxy">Galaxy</span>
```

**Features:**
- Padding: `4px 12px`
- Border radius: `9999px` (fully rounded)
- Font size: `12px`
- Font weight: `500`
- Display: `inline-flex` with center alignment

### Dropdown Items

```html
<div class="galaxy-dropdown-item">
    <svg class="galaxy-dropdown-item-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
    </svg>
    Profile
</div>
```

**Features:**
- Height: 44px
- Padding: `12px 16px`
- Hover: `rgba(139, 92, 246, 0.1)`
- Active: `rgba(139, 92, 246, 0.2)`
- Icon: 16px with 12px margin-right

### Grid System

```html
<div class="galaxy-grid">
    <div class="galaxy-grid-item">Grid Item 1</div>
    <div class="galaxy-grid-item">Grid Item 2</div>
    <div class="galaxy-grid-item">Grid Item 3</div>
</div>
```

**Features:**
- Display: `grid`
- Gap: 24px (desktop), 16px (tablet), 12px (mobile)
- Grid template: `repeat(auto-fit, minmax(300px, 1fr))`
- Max-width: 1200px
- Responsive columns: 1 (mobile), 2 (tablet), 3+ (desktop)

### Notification Bell

```html
<div class="galaxy-notification-bell">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12 7H4.828zM4 7h8l-2 2H6l-2-2z"></path>
    </svg>
    <div class="galaxy-notification-badge">3</div>
</div>
```

**Features:**
- Size: 24px × 24px
- Color: `#9ca3af`
- Hover: White
- Badge: Red background, white text, 20px × 20px
- Animation: Pulse effect

### Modal Components

```html
<x-galaxy-modal id="my-modal" title="Modal Title" size="md">
    <div class="space-y-4">
        <p>Modal content goes here</p>
        <div class="flex gap-3">
            <button class="galaxy-btn-primary" onclick="galaxyModalClose('my-modal')">
                Confirm
            </button>
            <button class="galaxy-btn-ghost" onclick="galaxyModalClose('my-modal')">
                Cancel
            </button>
        </div>
    </div>
</x-galaxy-modal>
```

**Features:**
- Backdrop blur effect
- Keyboard navigation (Escape to close)
- Focus management
- Multiple sizes: sm, md, lg, xl, full
- Dismissible option

### Advanced Tabs

```html
<x-galaxy-tabs :tabs="[
    ['label' => 'Overview', 'icon' => '<path...></path>'],
    ['label' => 'Analytics', 'icon' => '<path...></path>'],
    ['label' => 'Settings', 'icon' => '<path...></path>']
]">
    <div class="galaxy-tab-panel" data-tab="0">Overview content</div>
    <div class="galaxy-tab-panel hidden" data-tab="1">Analytics content</div>
    <div class="galaxy-tab-panel hidden" data-tab="2">Settings content</div>
</x-galaxy-tabs>
```

**Features:**
- Icon support
- Smooth transitions
- Keyboard navigation
- Active state management

### Advanced Dropdown

```html
<x-galaxy-dropdown placement="bottom-right" width="w-48">
    <x-slot name="trigger">
        <button class="galaxy-btn-secondary">Menu</button>
    </x-slot>
    
    <div class="galaxy-dropdown-item">Profile</div>
    <div class="galaxy-dropdown-item">Settings</div>
    <div class="h-px bg-gray-600 my-2"></div>
    <div class="galaxy-dropdown-item">Logout</div>
</x-galaxy-dropdown>
```

**Features:**
- Multiple placements: bottom-right, bottom-left, top-right, top-left
- Customizable width
- Backdrop click to close
- Smooth animations

### Progress Bars

```html
<x-galaxy-progress :value="75" :max="100" />
<x-galaxy-progress :value="60" animated striped />
<x-galaxy-progress :value="50" variant="warning" />
```

**Features:**
- Multiple variants: primary, secondary, success, warning, error, accent
- Animated option with stripes
- Multiple sizes: sm, md, lg, xl
- Show/hide labels

### Alerts

```html
<x-galaxy-alert type="info" title="Information">
    This is an informational alert.
</x-galaxy-alert>

<x-galaxy-alert type="success" title="Success">
    Your action was completed successfully!
</x-galaxy-alert>

<x-galaxy-alert type="warning" title="Warning">
    Please review your input.
</x-galaxy-alert>

<x-galaxy-alert type="error" title="Error">
    Something went wrong.
</x-galaxy-alert>
```

**Features:**
- 4 types: info, success, warning, error
- Dismissible option
- Auto-dismiss after 5 seconds
- Smooth animations

### Loading Components

#### Spinner
```html
<div class="galaxy-spinner"></div>
```

**Features:**
- Size: 24px × 24px
- Border: 2px solid `rgba(255, 255, 255, 0.3)`
- Border-top: 2px solid `#8b5cf6`
- Animation: Spin 1s linear infinite

#### Skeleton
```html
<div class="galaxy-skeleton"></div>
```

**Features:**
- Background: `rgba(255, 255, 255, 0.1)`
- Border radius: `4px`
- Animation: Pulse 2s ease-in-out infinite
- Height: 20px
- Width: 100%

## 🎭 Animations

### Available Animations
- **fade-in**: 300ms ease
- **scale-in**: 200ms ease
- **slide-down**: 200ms ease
- **pulse**: 2s ease-in-out infinite
- **bounce**: 1s ease-in-out infinite
- **spin**: 1s linear infinite

### Usage
```html
<div class="galaxy-fade-in">Fade in content</div>
<div class="galaxy-scale-in">Scale in content</div>
<div class="galaxy-pulse">Pulsing content</div>
```

## 📱 Responsive Design

### Mobile (≤768px)
- Sidebar: Full screen overlay
- Padding: 16px
- Font size: 14px
- Button height: 44px (touch target)
- Grid: 1 column

### Tablet (769px-1024px)
- Sidebar: Collapsible, 80px collapsed
- Padding: 20px
- Font size: 15px
- Grid: 2 columns
- Modal: 90% width

### Desktop (≥1025px)
- Sidebar: 256px fixed
- Padding: 24px
- Font size: 16px
- Grid: 3+ columns
- Modal: Max-width 512px

## 🌙 Dark Mode

The design system includes built-in dark mode support using CSS media queries:

```css
@media (prefers-color-scheme: dark) {
  .galaxy-card {
    background: rgba(17, 24, 39, 0.8);
    border-color: rgba(255, 255, 255, 0.1);
  }
}
```

## 🚀 Getting Started

### 1. Include the CSS
```html
<link rel="stylesheet" href="{{ asset('css/galaxy-design-system.css') }}">
```

### 2. Include Tailwind CSS
```html
<script src="https://cdn.tailwindcss.com"></script>
```

### 3. Use Components

#### Using CSS Classes
```html
<button class="galaxy-btn-primary">Click me</button>
<div class="galaxy-card">
    <div class="galaxy-card-content">Card content</div>
</div>
```

#### Using Blade Components
```html
<x-galaxy-button variant="primary" size="md">
    Click me
</x-galaxy-button>

<x-galaxy-card title="My Card" subtitle="Card description">
    Card content goes here
</x-galaxy-card>

<x-galaxy-input label="Email" type="email" placeholder="Enter your email" />
```

### 4. Access Demo Pages
- **Component Demo**: `/galaxy-demo` - See all components in action
- **Integration Example**: `/galaxy-integration` - Real-world usage examples

## 🎯 Best Practices

### Accessibility
- Always include proper ARIA labels
- Ensure sufficient color contrast
- Provide keyboard navigation
- Use semantic HTML elements

### Performance
- Use CSS custom properties for theming
- Minimize animation complexity on mobile
- Optimize images and icons
- Use efficient selectors

### Consistency
- Follow the spacing scale
- Use the defined color palette
- Maintain consistent border radius
- Apply consistent transitions

## 🔧 Customization

### CSS Custom Properties
You can customize the design system by overriding CSS custom properties:

```css
:root {
  --galaxy-primary: #your-color;
  --galaxy-secondary: #your-color;
  /* ... other properties */
}
```

### Tailwind Configuration
The design system integrates with Tailwind CSS. You can extend the configuration:

```javascript
module.exports = {
  theme: {
    extend: {
      colors: {
        'galaxy': {
          'primary': '#8b5cf6',
          // ... other colors
        }
      }
    }
  }
}
```

## 📄 License

This design system is part of the Elass 2 project and follows the project's licensing terms.

## 🤝 Contributing

When contributing to the design system:

1. Follow the established naming conventions
2. Maintain consistency with existing components
3. Test across different screen sizes
4. Ensure accessibility compliance
5. Update documentation

## 📞 Support

For questions or support regarding the Galaxy Design System, please refer to the project documentation or contact the development team.

---

**Galaxy Design System v3.0** - Built with ❤️ for modern web applications
