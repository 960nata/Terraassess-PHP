# Terra Assessment Design System

## Overview

Terra Assessment Design System adalah sistem desain yang komprehensif dan konsisten untuk platform monitoring IoT. Sistem ini dirancang dengan prinsip-prinsip modern UI/UX, accessibility, dan responsive design.

## 🎨 Design Principles

### 1. **Konsistensi (Consistency)**
- Menggunakan design tokens yang seragam
- Komponen yang dapat digunakan kembali
- Pola navigasi yang konsisten

### 2. **Accessibility First**
- WCAG 2.1 AA compliant
- Keyboard navigation support
- Screen reader friendly
- High contrast mode support

### 3. **Mobile-First Responsive**
- Mobile-first approach
- Touch-friendly interface
- Optimized for all screen sizes

### 4. **Performance Optimized**
- Minimal CSS footprint
- Lazy loading support
- Reduced motion support

## 🏗️ Architecture

```
resources/
├── css/
│   ├── terra-design-system.css    # Core design tokens & components
│   ├── terra-responsive.css       # Responsive utilities
│   ├── terra-accessibility.css    # Accessibility features
│   └── app.css                    # Main CSS file
├── js/
│   ├── terra-ui.js               # UI enhancement scripts
│   └── app.js                    # Main JavaScript file
└── views/
    ├── layouts/
    │   └── terra-layout.blade.php # Main layout template
    └── components/
        ├── terra-card.blade.php   # Card component
        ├── terra-button.blade.php # Button component
        ├── terra-input.blade.php  # Input component
        ├── terra-alert.blade.php  # Alert component
        └── terra-breadcrumb.blade.php # Breadcrumb component
```

## 🎯 Design Tokens

### Colors

#### Primary Palette
```css
--primary-50: #f0f9ff
--primary-100: #e0f2fe
--primary-200: #bae6fd
--primary-300: #7dd3fc
--primary-400: #38bdf8
--primary-500: #0ea5e9
--primary-600: #0284c7
--primary-700: #0369a1
--primary-800: #075985
--primary-900: #0c4a6e
```

#### Secondary Palette
```css
--secondary-50: #f8fafc
--secondary-100: #f1f5f9
--secondary-200: #e2e8f0
--secondary-300: #cbd5e1
--secondary-400: #94a3b8
--secondary-500: #64748b
--secondary-600: #475569
--secondary-700: #334155
--secondary-800: #1e293b
--secondary-900: #0f172a
```

#### Status Colors
```css
--success-50: #f0fdf4
--success-500: #22c55e
--success-600: #16a34a

--warning-50: #fffbeb
--warning-500: #f59e0b
--warning-600: #d97706

--error-50: #fef2f2
--error-500: #ef4444
--error-600: #dc2626

--info-50: #eff6ff
--info-500: #3b82f6
--info-600: #2563eb
```

### Typography

#### Font Families
```css
--font-family-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif
--font-family-mono: 'JetBrains Mono', 'Fira Code', monospace
```

#### Font Sizes
```css
--font-size-xs: 0.75rem    /* 12px */
--font-size-sm: 0.875rem   /* 14px */
--font-size-base: 1rem     /* 16px */
--font-size-lg: 1.125rem   /* 18px */
--font-size-xl: 1.25rem    /* 20px */
--font-size-2xl: 1.5rem    /* 24px */
--font-size-3xl: 1.875rem  /* 30px */
--font-size-4xl: 2.25rem   /* 36px */
--font-size-5xl: 3rem      /* 48px */
```

#### Font Weights
```css
--font-weight-light: 300
--font-weight-normal: 400
--font-weight-medium: 500
--font-weight-semibold: 600
--font-weight-bold: 700
--font-weight-extrabold: 800
```

### Spacing

```css
--space-1: 0.25rem   /* 4px */
--space-2: 0.5rem    /* 8px */
--space-3: 0.75rem   /* 12px */
--space-4: 1rem      /* 16px */
--space-5: 1.25rem   /* 20px */
--space-6: 1.5rem    /* 24px */
--space-8: 2rem      /* 32px */
--space-10: 2.5rem   /* 40px */
--space-12: 3rem     /* 48px */
--space-16: 4rem     /* 64px */
--space-20: 5rem     /* 80px */
--space-24: 6rem     /* 96px */
```

### Border Radius

```css
--radius-sm: 0.25rem   /* 4px */
--radius-md: 0.375rem  /* 6px */
--radius-lg: 0.5rem    /* 8px */
--radius-xl: 0.75rem   /* 12px */
--radius-2xl: 1rem     /* 16px */
--radius-3xl: 1.5rem   /* 24px */
--radius-full: 9999px
```

### Shadows

```css
--shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05)
--shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)
--shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)
--shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)
--shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25)
```

### Transitions

```css
--transition-fast: 150ms ease-in-out
--transition-normal: 250ms ease-in-out
--transition-slow: 350ms ease-in-out
```

## 🧩 Components

### Buttons

#### Basic Usage
```blade
<x-terra-button variant="primary" size="md">
    Primary Button
</x-terra-button>

<x-terra-button variant="secondary" size="lg">
    Secondary Button
</x-terra-button>

<x-terra-button variant="outline" size="sm">
    Outline Button
</x-terra-button>
```

#### With Icons
```blade
<x-terra-button variant="primary" icon="fas fa-save" icon-position="left">
    Save Changes
</x-terra-button>

<x-terra-button variant="secondary" icon="fas fa-arrow-right" icon-position="right">
    Continue
</x-terra-button>
```

#### Loading State
```blade
<x-terra-button variant="primary" loading="true">
    Processing...
</x-terra-button>
```

### Cards

#### Basic Card
```blade
<x-terra-card>
    <x-slot name="header">
        <h3 class="terra-card-title">Card Title</h3>
    </x-slot>
    
    <p>Card content goes here...</p>
    
    <x-slot name="footer">
        <x-terra-button variant="primary" size="sm">Action</x-terra-button>
    </x-slot>
</x-terra-card>
```

#### Elevated Card
```blade
<x-terra-card elevated="true">
    <h3 class="terra-card-title">Elevated Card</h3>
    <p>This card has elevated shadow.</p>
</x-terra-card>
```

### Forms

#### Input Fields
```blade
<x-terra-input 
    label="Email Address" 
    type="email" 
    placeholder="Enter your email"
    required="true"
    help="We'll never share your email"
/>
```

#### Textarea
```blade
<x-terra-input 
    label="Message" 
    type="textarea" 
    placeholder="Enter your message"
    required="true"
/>
```

#### Select
```blade
<x-terra-input label="Country" type="select" required="true">
    <option value="">Select a country</option>
    <option value="id">Indonesia</option>
    <option value="us">United States</option>
</x-terra-input>
```

### Alerts

#### Success Alert
```blade
<x-terra-alert type="success" title="Success!" dismissible="true">
    Your changes have been saved successfully.
</x-terra-alert>
```

#### Error Alert
```blade
<x-terra-alert type="error" title="Error!" dismissible="true">
    Something went wrong. Please try again.
</x-terra-alert>
```

#### Warning Alert
```blade
<x-terra-alert type="warning" title="Warning!" dismissible="true">
    Please review your input before submitting.
</x-terra-alert>
```

#### Info Alert
```blade
<x-terra-alert type="info" title="Information" dismissible="true">
    Here's some helpful information for you.
</x-terra-alert>
```

### Breadcrumbs

```blade
<x-terra-breadcrumb :items="[
    ['label' => 'Home', 'url' => '/', 'icon' => 'fas fa-home'],
    ['label' => 'Dashboard', 'url' => '/dashboard', 'icon' => 'fas fa-tachometer-alt'],
    ['label' => 'Settings', 'icon' => 'fas fa-cog']
]" />
```

## 📱 Responsive Design

### Breakpoints

```css
/* Mobile First Approach */
/* Default: 0px and up */
/* sm: 640px and up */
/* md: 768px and up */
/* lg: 1024px and up */
/* xl: 1280px and up */
/* 2xl: 1536px and up */
```

### Responsive Utilities

#### Grid System
```blade
<div class="terra-grid terra-grid-cols-1 md:terra-grid-cols-2 lg:terra-grid-cols-3 gap-6">
    <!-- Responsive grid items -->
</div>
```

#### Responsive Text
```blade
<h1 class="terra-heading-fluid">Responsive Heading</h1>
<p class="terra-text-fluid">Responsive paragraph text</p>
```

#### Mobile-Only / Desktop-Only
```blade
<div class="terra-mobile-only">Only visible on mobile</div>
<div class="terra-desktop-only">Only visible on desktop</div>
```

## ♿ Accessibility Features

### Keyboard Navigation
- All interactive elements are keyboard accessible
- Tab order is logical and intuitive
- Focus indicators are clearly visible
- Skip links for main content

### Screen Reader Support
- Proper ARIA labels and roles
- Semantic HTML structure
- Screen reader announcements
- Alternative text for images

### Color Contrast
- WCAG 2.1 AA compliant contrast ratios
- High contrast mode support
- Color is not the only way to convey information

### Reduced Motion
- Respects `prefers-reduced-motion` setting
- Animations can be disabled
- Smooth transitions without motion

## 🚀 Performance Optimizations

### CSS Optimizations
- Minimal CSS footprint
- Efficient selectors
- Reduced specificity conflicts
- Optimized for critical rendering path

### JavaScript Optimizations
- Lazy loading for images
- Debounced scroll events
- Throttled resize events
- Intersection Observer for animations

### Loading States
- Skeleton screens
- Progressive loading
- Loading indicators
- Error boundaries

## 🎨 Customization

### Theme Customization
```css
:root {
    /* Override design tokens */
    --primary-600: #your-color;
    --font-family-sans: 'Your-Font', sans-serif;
    --radius-lg: 12px;
}
```

### Component Customization
```blade
<x-terra-button 
    variant="primary" 
    class="custom-button-class"
    style="background: linear-gradient(45deg, #ff6b6b, #4ecdc4);"
>
    Custom Button
</x-terra-button>
```

## 📚 Usage Guidelines

### Do's ✅
- Use semantic HTML elements
- Provide meaningful labels and descriptions
- Test with keyboard navigation
- Use consistent spacing and typography
- Follow the established color palette
- Implement proper error states
- Provide loading feedback

### Don'ts ❌
- Don't use color alone to convey information
- Don't create custom components without following the design system
- Don't ignore accessibility requirements
- Don't use inconsistent spacing or typography
- Don't create components that don't work on mobile
- Don't ignore performance implications

## 🧪 Testing

### Accessibility Testing
- Use screen readers (NVDA, JAWS, VoiceOver)
- Test keyboard navigation
- Check color contrast ratios
- Validate ARIA implementation

### Responsive Testing
- Test on various screen sizes
- Test on different devices
- Check touch interactions
- Validate mobile performance

### Browser Testing
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## 📖 Resources

### Documentation
- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [MDN Web Docs](https://developer.mozilla.org/)
- [WebAIM](https://webaim.org/)

### Tools
- [WAVE Web Accessibility Evaluator](https://wave.webaim.org/)
- [axe DevTools](https://www.deque.com/axe/devtools/)
- [Lighthouse](https://developers.google.com/web/tools/lighthouse)

## 🤝 Contributing

When contributing to the design system:

1. Follow the established patterns
2. Ensure accessibility compliance
3. Test across different devices and browsers
4. Update documentation
5. Consider performance implications

## 📝 Changelog

### v1.0.0 (Current)
- Initial design system implementation
- Core components (Button, Card, Input, Alert, Breadcrumb)
- Responsive design system
- Accessibility features
- Performance optimizations

---

**Terra Assessment Design System** - Built with ❤️ for better user experiences.

