# Modern UI Components Documentation

## Overview
This document provides comprehensive documentation for the modern UI components built with Tailwind CSS v4 and Laravel Blade.

## Design System

### Color Palette
- **Primary**: Blue shades (#3b82f6 to #172554)
- **Secondary**: Gray shades (#f8fafc to #020617)
- **Accent**: Cyan shades (#f0f9ff to #0c4a6e)
- **Success**: Green shades (#f0fdf4 to #14532d)
- **Warning**: Yellow shades (#fffbeb to #78350f)
- **Error**: Red shades (#fef2f2 to #7f1d1d)

### Typography
- **Font Family**: Inter, Poppins, system-ui
- **Font Sizes**: 12px to 60px (--text-xs to --text-6xl)
- **Font Weights**: 100 to 900

## Components

### 1. Modern Card (`modern-card.blade.php`)

A flexible card component with multiple variants.

```blade
<x-modern-card variant="elevated" class="mb-4">
    <x-slot name="header">
        <h3>Card Title</h3>
    </x-slot>
    
    <p>Card content goes here</p>
    
    <x-slot name="footer">
        <button class="btn btn-primary">Action</button>
    </x-slot>
</x-modern-card>
```

**Props:**
- `variant`: `default`, `elevated`, `glass`, `flat`
- `elevated`: `boolean`
- `glass`: `boolean`
- `hover`: `boolean` (default: true)
- `class`: Additional CSS classes

### 2. Modern Button (`modern-button.blade.php`)

A versatile button component with multiple variants and sizes.

```blade
<x-modern-button variant="primary" size="lg" icon="ph-plus" icon-position="left">
    Create New
</x-modern-button>
```

**Props:**
- `variant`: `primary`, `secondary`, `outline`, `ghost`, `success`, `warning`, `error`
- `size`: `sm`, `md`, `lg`, `xl`
- `loading`: `boolean`
- `disabled`: `boolean`
- `icon`: Icon class name
- `iconPosition`: `left`, `right`

### 3. Modern Input (`modern-input.blade.php`)

A comprehensive input component supporting various input types.

```blade
<x-modern-input 
    name="email" 
    type="email" 
    label="Email Address" 
    placeholder="Enter your email"
    required
    error="{{ $errors->first('email') }}"
    help="We'll never share your email"
/>
```

**Props:**
- `type`: `text`, `email`, `password`, `number`, `tel`, `url`, `textarea`, `select`
- `label`: Input label
- `error`: Error message
- `help`: Help text
- `required`: `boolean`
- `disabled`: `boolean`
- `placeholder`: Placeholder text

### 4. Modern Badge (`modern-badge.blade.php`)

A small status indicator component.

```blade
<x-modern-badge variant="success" size="lg">
    Active
</x-modern-badge>
```

**Props:**
- `variant`: `primary`, `secondary`, `success`, `warning`, `error`
- `size`: `sm`, `md`, `lg`

### 5. Modern Alert (`modern-alert.blade.php`)

An alert component for displaying messages.

```blade
<x-modern-alert type="success" dismissible>
    Your changes have been saved successfully!
</x-modern-alert>
```

**Props:**
- `type`: `success`, `warning`, `error`, `info`
- `dismissible`: `boolean`

### 6. Modern Table (`modern-table.blade.php`)

A responsive table component.

```blade
<x-modern-table striped hover>
    <x-slot name="header">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </x-slot>
    
    <x-slot name="body">
        <tr>
            <td>Data Real</td>
            <td>user@terraassessment.com</td>
            <td>
                <button class="btn btn-sm btn-primary">Edit</button>
            </td>
        </tr>
    </x-slot>
</x-modern-table>
```

**Props:**
- `striped`: `boolean`
- `hover`: `boolean`
- `bordered`: `boolean`
- `responsive`: `boolean` (default: true)

### 7. Modern Stats Card (`modern-stats-card.blade.php`)

A statistics display card.

```blade
<x-modern-stats-card
    title="Total Users"
    value="1,234"
    change="+12%"
    change-type="positive"
    icon="ph-users"
    color="primary"
/>
```

**Props:**
- `title`: Card title
- `value`: Main value
- `change`: Change indicator
- `changeType`: `positive`, `negative`, `neutral`
- `icon`: Icon class name
- `color`: `primary`, `success`, `warning`, `error`, `accent`, `secondary`

### 8. Modern Progress (`modern-progress.blade.php`)

A progress bar component.

```blade
<x-modern-progress
    value="75"
    max="100"
    size="lg"
    color="primary"
    show-label="true"
    animated="true"
    label="Project Progress"
/>
```

**Props:**
- `value`: Current value
- `max`: Maximum value (default: 100)
- `size`: `sm`, `md`, `lg`
- `color`: `primary`, `success`, `warning`, `error`, `accent`
- `showLabel`: `boolean`
- `animated`: `boolean`

### 9. Modern Collapsible (`modern-collapsible.blade.php`)

A collapsible section component with smooth animations and accessibility features.

```blade
<x-modern-collapsible title="Section Title">
    <p>Your content here</p>
</x-modern-collapsible>
```

**Props:**
- `title`: Section header text
- `open`: `boolean` (default: false) - Whether section starts expanded
- `class`: Additional CSS classes for the container
- `headerClass`: Additional CSS classes for the header
- `contentClass`: Additional CSS classes for the content area
- `icon`: Icon class name (default: 'ph-caret-down')
- `iconClass`: Additional CSS classes for the icon

**Features:**
- Smooth height animation (200ms)
- Accessible with ARIA attributes
- Keyboard navigation support
- Customizable styling
- Dark mode support
- Header height: 32px
- Text: uppercase, 12px, tracking-wider
- Color: #9ca3af (gray-400)
- Chevron icon: 16px, rotates 180deg on expand

### 10. Modern Dropdown (`modern-dropdown.blade.php`)

A glassmorphism dropdown component with smooth animations and multiple variants.

```blade
<x-modern-dropdown>
    <button class="dropdown-item">
        <i class="ph-user dropdown-item-icon"></i>
        <span class="dropdown-item-text">Profile</span>
    </button>
    <button class="dropdown-item">
        <i class="ph-gear dropdown-item-icon"></i>
        <span class="dropdown-item-text">Settings</span>
    </button>
</x-modern-dropdown>
```

**Props:**
- `trigger`: Custom trigger element (slot)
- `variant`: `default`, `notifications`, `profile`
- `position`: `bottom-left`, `bottom-right`, `top-left`, `top-right`
- `open`: `boolean` (default: false) - Whether dropdown starts open
- `class`: Additional CSS classes for the container
- `triggerClass`: Additional CSS classes for the trigger
- `contentClass`: Additional CSS classes for the content
- `width`: Custom width override
- `maxHeight`: Maximum height (default: 400px)

**Features:**
- Glassmorphism design with backdrop blur
- Smooth slide-down animation (200ms)
- Multiple position variants
- Custom trigger support
- Keyboard navigation (Escape to close)
- Click outside to close
- Backdrop overlay
- Dark mode support
- Background: rgba(255, 255, 255, 0.95)
- Backdrop-filter: blur(12px)
- Border: 1px solid rgba(139, 92, 246, 0.3)
- Border-radius: 8px
- Shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1)
- Z-index: 50

## Form Components

### 1. Modern Form (`modern-form.blade.php`)

A form wrapper component.

```blade
<x-modern-form method="POST" action="{{ route('users.store') }}">
    <!-- Form fields -->
    
    <x-slot name="actions">
        <x-modern-button type="submit" variant="primary">Save</x-modern-button>
    </x-slot>
</x-modern-form>
```

### 2. Modern Form Group (`modern-form-group.blade.php`)

A form field wrapper.

```blade
<x-modern-form-group label="Username" required error="{{ $errors->first('username') }}">
    <x-modern-input name="username" placeholder="Enter username" />
</x-modern-form-group>
```

### 3. Modern Checkbox (`modern-checkbox.blade.php`)

A checkbox component.

```blade
<x-modern-checkbox name="terms" label="I agree to the terms and conditions" required />
```

### 4. Modern Radio (`modern-radio.blade.php`)

A radio button component.

```blade
<x-modern-radio name="gender" value="male" label="Male" />
<x-modern-radio name="gender" value="female" label="Female" />
```

## Layout Components

### 1. Modern Template (`modernTemplate.blade.php`)

The main layout template.

```blade
@extends('layout.template.modernTemplate')

@section('container')
    <!-- Page content -->
@endsection
```

### 2. Modern Sidebar (`modern-sidebar.blade.php`)

The navigation sidebar.

### 3. Modern Topbar (`modern-topbar.blade.php`)

The top navigation bar.

## Usage Examples

### Complete Dashboard Page

```blade
@extends('layout.template.modernTemplate')

@section('container')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
    <p class="mt-2 text-sm text-gray-600">Welcome back!</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <x-modern-stats-card
        title="Total Users"
        value="1,234"
        change="+12%"
        change-type="positive"
        icon="ph-users"
        color="primary"
    />
    <!-- More stats cards -->
</div>

<x-modern-card>
    <x-slot name="header">
        <h3 class="text-lg font-semibold">Recent Activity</h3>
    </x-slot>
    
    <!-- Activity content -->
</x-modern-card>
@endsection
```

### Complete Form Page

```blade
@extends('layout.template.modernTemplate')

@section('container')
<x-modern-form method="POST" action="{{ route('users.store') }}">
    <x-modern-card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold">Create User</h3>
        </x-slot>
        
        <div class="space-y-6">
            <x-modern-form-group label="Name" required>
                <x-modern-input name="name" placeholder="Enter name" required />
            </x-modern-form-group>
            
            <x-modern-form-group label="Email" required>
                <x-modern-input name="email" type="email" placeholder="Enter email" required />
            </x-modern-form-group>
        </div>
        
        <x-slot name="footer">
            <div class="flex justify-end space-x-3">
                <x-modern-button type="button" variant="secondary">Cancel</x-modern-button>
                <x-modern-button type="submit" variant="primary">Save</x-modern-button>
            </div>
        </x-slot>
    </x-modern-card>
</x-modern-form>
@endsection
```

## Customization

### CSS Custom Properties

All design tokens are defined as CSS custom properties in `design-system.css`:

```css
:root {
  --primary-500: #3b82f6;
  --secondary-500: #64748b;
  --text-base: 1rem;
  --space-4: 1rem;
  /* ... more tokens */
}
```

### Component Styling

Components can be customized by:

1. **Props**: Use component props to change appearance
2. **CSS Classes**: Add custom classes via the `class` prop
3. **CSS Custom Properties**: Override design tokens
4. **Component Extension**: Extend components with additional functionality

### Dark Mode

Dark mode is automatically supported through CSS custom properties and media queries:

```css
@media (prefers-color-scheme: dark) {
  :root {
    --neutral-50: #0a0a0a;
    --neutral-900: #f5f5f5;
    /* ... more dark mode tokens */
  }
}
```

## Best Practices

1. **Consistency**: Use the same components throughout the application
2. **Accessibility**: Always provide proper labels and ARIA attributes
3. **Responsive Design**: Test components on different screen sizes
4. **Performance**: Use CSS custom properties for theming
5. **Documentation**: Keep component documentation up to date

## Browser Support

- Chrome 88+
- Firefox 87+
- Safari 14+
- Edge 88+

## Dependencies

- Tailwind CSS v4
- Phosphor Icons
- Font Awesome (optional)
- Laravel Blade
